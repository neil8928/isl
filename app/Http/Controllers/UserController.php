<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\User,App\WEBGrupoopcion,App\WEBRol,App\WEBRolOpcion,App\WEBOpcion,App\WEBDocumento;
use App\UsuariosIsl,App\SolicitudesMaterial;
use View;
use Session;
use Hashids;



class UserController extends Controller
{



    public function actionLogin(Request $request){

		if($_POST)
		{
			/**** Validaciones laravel ****/
			$this->validate($request, [
	            'name' => 'required',
	            'password' => 'required',

			], [
            	'name.required' => 'El campo Usuario es obligatorio',
            	'password.required' => 'El campo Clave es obligatorio',
        	]);

			/**********************************************************/
			
			$usuario 	 				 = strtoupper($request['name']);
			$clave   	 				 = strtoupper($request['password']);
			$local_id  	 				 = $request['local_id'];

			$tusuario    				 = User::join('usuariosisls', 'users.usuarioisl_id', '=', 'usuariosisls.IdUsuariaIsl')
										   ->whereRaw('UPPER(usuariosisls.Nombre)=?',[$usuario])->first();

			if(count($tusuario)>0)
			{
				$clavedesifrada 		 = 	strtoupper($tusuario->passwordmobil);

				if($clavedesifrada == $clave)
				{

					$listamenu    		 = 	WEBGrupoopcion::join('web.opciones', 'web.opciones.grupoopcion_id', '=', 'web.grupoopciones.id')
											->join('web.rolopciones', 'web.rolopciones.opcion_id', '=', 'web.opciones.id')
											->where('web.grupoopciones.activo', '=', 1)
											->where('web.rolopciones.rol_id', '=', $tusuario->rol_id)
											->where('web.rolopciones.ver', '=', 1)
											->groupBy('web.grupoopciones.id')
											->groupBy('web.grupoopciones.nombre')
											->groupBy('web.grupoopciones.icono')
											->groupBy('web.grupoopciones.orden')
											->select('web.grupoopciones.id','web.grupoopciones.nombre','web.grupoopciones.icono','web.grupoopciones.orden')
											->orderBy('web.grupoopciones.orden', 'asc')
											->get();

					$listaopciones    	= 	WEBRolOpcion::where('rol_id', '=', $tusuario->rol_id)
											->where('ver', '=', 1)
											->orderBy('orden', 'asc')
											->pluck('opcion_id')
											->toArray();


					Session::put('usuario', $tusuario);
					Session::put('listamenu', $listamenu);
					Session::put('listaopciones', $listaopciones);

					return Redirect::to('bienvenido');
					
						
				}else{
					return Redirect::back()->withInput()->with('errorbd', 'Usuario o clave incorrecto');
				}	
			}else{
				return Redirect::back()->withInput()->with('errorbd', 'Usuario o clave incorrecto');
			}						    

		}else{
			return view('usuario.login');
		}
    }


	public function actionBienvenido()
	{

	    $fecha_menos_siete_dias  	= 	$this->fecha_menos_siete_dias;
	    $fechafin  					= 	$this->fin;

	    $listasolicitudes 			= 	SolicitudesMaterial::where('IdEstadoOA','=','1CH00014')
	    	    						->where('Fecha','>=', $fecha_menos_siete_dias)
	    								->where('Fecha','<=', $fechafin)
	    								->orderBy('id', 'asc')
	    								->get();


		$area_id 		= 	'';
	    $idtipoordencompra          = 	'1PK000000001';
		$listaordencompra_m 		= 	$this->funciones->lista_orden_compra($fecha_menos_siete_dias,$fechafin,$idtipoordencompra,$area_id);
		$cantidad_m 				=   0;
		while ($objeto = $listaordencompra_m->fetch()){
			$cantidad_m 				=  $cantidad_m  + 1;
		}


		/******************* es con area o sin area **********************/
		$proceso_id 	= 	'1CH000000086';
		$validartodo 	= 	$this->funciones->listar_todo_o_solo_area($proceso_id);

	    if($validartodo==0){
	    	$data_ocupacion_trabajador = $this->funciones->data_ocupacion_trabajador();
	    	if(count($data_ocupacion_trabajador)>0){
	    		$area_id 		= 	$data_ocupacion_trabajador->IdArea;
	    	}

	    }
	    /******************************************************/

	    $idtipoordencompra          = 	'1PK000000003';
		$listaordencompra_s 		= 	$this->funciones->lista_orden_compra($fecha_menos_siete_dias,$fechafin,$idtipoordencompra,$area_id);
		$cantidad_s 				=   0;
		while ($objeto = $listaordencompra_s->fetch()){
			$cantidad_s 				=  $cantidad_s  + 1;
		}


		return View::make('bienvenido',
						 [
						 	'listasolicitudes' 		=> $listasolicitudes,
						 	'cantidad_s' 			=> $cantidad_s,
						 	'cantidad_m' 			=> $cantidad_m,
						 ]);
	}

	public function actionCerrarSesion()
	{
		Session::forget('usuario');
		Session::forget('listamenu');
		Session::forget('listaopciones');
		return Redirect::to('/login');

	}



	public function actionListarUsuarios($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listausuarios 	= 	UsuariosIsl::leftJoin('users', 'users.usuarioisl_id', '=', 'usuariosisls.IdUsuariaIsl')
	    					->get();

		$funcion 		= 	$this;

		return View::make('usuario/listausuarios',
						 [
						 	'listausuarios' 	=> $listausuarios,
						 	'funcion' 			=> $funcion,
						 	'idopcion' 			=> $idopcion,
						 ]);
	}




	public function actionModificarUsuario($idopcion,$idusuario,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idusuario = $idusuario;



		if($_POST)
		{

			$usuario    					= 	User::where('usuarioisl_id','=',$idusuario)->first();

			if(count($usuario)>0){

				$usuario->passwordmobil 	= 	$request['password'];		
				$usuario->rol_id 	 	 	= 	$request['rol_id'];
				$usuario->save();

			}else{


				$idusers 				 	=   $this->funciones->getCreateIdMaestra('users');
				
				$cabecera            	 	=	new User;
				$cabecera->id 	     	 	=   $idusers;
				$cabecera->passwordmobil 	= 	$request['password'];		
				$cabecera->rol_id 	 	 	= 	$request['rol_id'];
				$cabecera->usuarioisl_id	= 	$idusuario;
				$cabecera->save();


			}



 
 			return Redirect::to('/gestion-de-usuarios/'.$idopcion)->with('bienhecho', 'Usuario '.$request['nombre'].' '.$request['apellido'].' modificado con exito');


		}else{



				$usuario    = 	UsuariosIsl::leftJoin('users', 'users.usuarioisl_id', '=', 'usuariosisls.IdUsuariaIsl')
							 	->where('IdUsuariaIsl','=',$idusuario)->first();

				$rol 		= 	DB::table('WEB.Rols')->where('id','<>','1CIX00000001')->pluck('nombre','id')->toArray();


				if(is_null($usuario->rol_id) or $usuario->rol_id == ''){
					$comborol  	= 	array('' => 'Seleccione Rol') + $rol;		
				}else{
					$user    	= 	User::where('id','=',$usuario->id)->first();
					$comborol  	= 	array($user->rol_id => $user->rol->nombre) + $rol;
				}


		        return View::make('usuario/modificarusuario', 
		        				[
		        					'usuario'  => $usuario,
									'comborol' => $comborol,
						  			'idopcion' => $idopcion
		        				]);


		}
	}


	public function actionCambiarClave(Request $request)
	{

		if($_POST)
		{

			$usuario_id	                    =   $request['usuario_id'];
			$password	                    =   $request['password'];


			$usuario    					= 	User::where('id','=',$usuario_id)->first();
			$usuario->passwordmobil 		= 	$request['password'];		
			$usuario->save();
	
 			return Redirect::to('/cambiar-clave')->with('bienhecho', 'clave modificado con exito');


		}else{


				$usuario    = 	UsuariosIsl::leftJoin('users', 'users.usuarioisl_id', '=', 'usuariosisls.IdUsuariaIsl')
							 	->where('id','=',Session::get('usuario')->id)->first();


		        return View::make('usuario/cambiarclave', 
		        				[
		        					'usuario'  => $usuario
		        				]);


		}
	}





	public function actionListarRoles($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listaroles = WEBRol::where('id','<>','1CIX00000001')->orderBy('id', 'asc')->get();

		return View::make('usuario/listaroles',
						 [
						 	'listaroles' => $listaroles,
						 	'idopcion' => $idopcion,
						 ]);


	}


	public function actionAgregarRol($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{

			/**** Validaciones laravel ****/
			
			$this->validate($request, [
			    'nombre' => 'unico:WEB,rols',
			], [
            	'nombre.unico' => 'Rol ya registrado',
        	]);

			/******************************/
			$idrol 					 = $this->funciones->getCreateIdMaestra('WEB.rols');

			$cabecera            	 =	new WEBRol;
			$cabecera->id 	     	 =  $idrol;
			$cabecera->nombre 	     =  $request['nombre'];
			$cabecera->save();

			$listaopcion = WEBOpcion::orderBy('id', 'asc')->get();
			$count = 1;
			foreach($listaopcion as $item){


				$idrolopciones 		= $this->funciones->getCreateIdMaestra('WEB.rolopciones');


			    $detalle            =	new WEBRolOpcion;
			    $detalle->id 	    =  	$idrolopciones;
				$detalle->opcion_id = 	$item->id;
				$detalle->rol_id    =  	$idrol;
				$detalle->orden     =  	$count;
				$detalle->ver       =  	0;
				$detalle->anadir    =  	0;
				$detalle->modificar =  	0;
				$detalle->eliminar  =  	0;
				$detalle->todas     = 	0;
				$detalle->save();
				$count 				= 	$count +1;
			}

 			return Redirect::to('/gestion-de-roles/'.$idopcion)->with('bienhecho', 'Rol '.$request['nombre'].' registrado con exito');
		}else{

		
			return View::make('usuario/agregarrol',
						[
						  	'idopcion' => $idopcion
						]);

		}
	}


	public function actionModificarRol($idopcion,$idrol,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $idrol = $this->funciones->decodificarmaestra($idrol);

		if($_POST)
		{

			/**** Validaciones laravel ****/
			$this->validate($request, [
				'nombre' => 'unico_menos:WEB,rols,id,'.$idrol,
			], [
            	'nombre.unico_menos' => 'Rol ya registrado',
        	]);
			/******************************/

			$cabecera            	 =	WEBRol::find($idrol);
			$cabecera->nombre 	     =  $request['nombre'];
			$cabecera->activo 	 	 =  $request['activo'];			
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-roles/'.$idopcion)->with('bienhecho', 'Rol '.$request['nombre'].' modificado con éxito');

		}else{
				$rol = WEBRol::where('id', $idrol)->first();

		        return View::make('usuario/modificarrol', 
		        				[
		        					'rol'  		=> $rol,
						  			'idopcion' 	=> $idopcion
		        				]);
		}
	}


	public function actionListarPermisos($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listaroles = WEBRol::where('id','<>','1CIX00000001')->orderBy('id', 'asc')->get();

		return View::make('usuario/listapermisos',
						 [
						 	'listaroles' => $listaroles,
						 	'idopcion' => $idopcion,
						 ]);
	}


	public function actionAjaxListarOpciones(Request $request)
	{
		$idrol =  $request['idrol'];
		$idrol = $this->funciones->decodificarmaestra($idrol);

		$listaopciones = WEBRolOpcion::where('rol_id','=',$idrol)->get();

		return View::make('usuario/ajax/listaopciones',
						 [
							 'listaopciones'   => $listaopciones
						 ]);
	}

	public function actionAjaxActivarPermisos(Request $request)
	{

		$idrolopcion =  $request['idrolopcion'];
		$idrolopcion = $this->funciones->decodificarmaestra($idrolopcion);

		$cabecera            	 =	WEBRolOpcion::find($idrolopcion);
		$cabecera->ver 	     	 =  $request['ver'];
		$cabecera->anadir 	 	 =  $request['anadir'];	
		$cabecera->modificar 	 =  $request['modificar'];
		$cabecera->todas 	 	 =  $request['todas'];	
		$cabecera->save();

		echo("gmail");

	}
	
	public function actionAjaxActivarPerfiles(Request $request)
	{

		$idempresa =  $request['idempresa'];
		$idcentro =  $request['idcentro'];
		$idusuario =  $request['idusuario'];
		$check =  $request['check'];	

		$perfiles = WEBUserEmpresaCentro::where('empresa_id','=',$idempresa)
										  ->where('centro_id','=',$idcentro)
										  ->where('usuario_id','=',$idusuario)
										  ->first();

		if(count($perfiles)>0){

			$cabecera            	 =	WEBUserEmpresaCentro::find($perfiles->id);
			$cabecera->activo 	     =  $check;	
			$cabecera->save();	
			
		}else{

			$id 					= 	$this->funciones->getCreateIdMaestra('WEB.userempresacentros');
		    $detalle            	=	new WEBUserEmpresaCentro;
		    $detalle->id 	    	=  	$id;
			$detalle->empresa_id 	= 	$idempresa;
			$detalle->centro_id    	=  	$idcentro;
			$detalle->usuario_id    =  	$idusuario;
			$detalle->save();

		}

		echo("gmail");

	}



	public function pruebas()
	{

            $doc            =   Documento::where('id', 11)->first();
            dd($doc->sunatdocumentoreferencia->nota_cre_deb);

		dd("hola");
		/*$listaopciones 	= RolOpcion::where('rol_id','=',2)->get();
		$listaopcion 	= Opcion::where('id','=',1)->first();
		foreach($listaopciones as $item){
			dd($item->opcion->grupoopcion->nombre);
		}*/

	}





}
