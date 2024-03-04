<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use View;
use Session;
use Hashids;
use File;
use App\PERPersona;
use App\WEBTrabajador;
use App\WEBEncuesta;
use App\WEBRespuestapersona;
use Maatwebsite\Excel\Facades\Excel;


class EncuestaController extends Controller
{

	public function actionVerRevistavirtual(Request $request)
	{



		$dni 	 				 		= 	strtoupper($request['dni']);

		$persona						=   WEBTrabajador::where('activo','=','1')->where('Dni','=',$dni)->first();

		if(count($persona)>0)
		{
			echo("1");
		}else{
			echo("0");
		}	


	}



	public function actionDetalleEncuestaTrabajador($encuesta_id)
	{

	    $encuesta_id 			= 	$this->funciones->decodificarmaestra($encuesta_id);
		$encuesta				=   WEBEncuesta::where('activo','=','1')->where('id','=',$encuesta_id)->first();
		$persona				=   WEBTrabajador::where('id','=',$encuesta->persona_id)->first();

		$listapregunta 			= 	DB::table('WEB.tiporespuestas')
									->join('WEB.preguntas', 'WEB.tiporespuestas.id', '=', 'WEB.preguntas.tiporespuesta_id')
					   				->leftJoin('WEB.preguntarespuestas', function($leftJoin)
								        {
								            $leftJoin->on('WEB.preguntas.id', '=', 'WEB.preguntarespuestas.pregunta_id')
								            ->where('WEB.preguntarespuestas.activo', '=', 1);
								        })
							   		->leftJoin('WEB.respuestas', function($leftJoin)
								        {
								            $leftJoin->on('WEB.respuestas.id', '=', 'WEB.preguntarespuestas.respuesta_id')
								            ->where('WEB.respuestas.activo', '=', 1);
								        })
							   		->where('WEB.preguntas.activo', '=', 1)
							   		->orderBy('WEB.preguntas.numero', 'ASC')
							   		->select('WEB.preguntas.id','WEB.preguntas.numero','WEB.preguntarespuestas.id as IdPreguntaRespuesta','WEB.tiporespuestas.Descripcion as DescripcionTipo','WEB.preguntas.descripcion','WEB.respuestas.descripcion as DescripcionResp')
								    ->get();

		$funcion 				= 	$this->funciones;



		return View::make('encuesta/detalleencuesta',
						  [
						   'listapregunta' 			=> $listapregunta,
						   'persona' 				=> $persona,
						   'encuesta' 				=> $encuesta,
						   'funcion' 				=> $funcion,
						  ]
						 );

	}





	public function actionListaEncuesta()
	{

		$listaencuestas			=   WEBEncuesta::where('activo','=','1')->where('fecha','>','2020-07-01')->get();
		$funcion 				= 	$this->funciones;

		return View::make('encuesta/listaencuesta',
						  [
						   'listaencuestas' 		=> $listaencuestas,
						   'funcion' 		=> $funcion
						  ]
						 );
	}

	public function actionBingo(Request $request)
	{

		if($_POST)
		{
			/**** Validaciones laravel ****/
			$this->validate($request, [
	            'dni' => 'required',
			], [
            	'dni.required' => 'El campo DNI es obligatorio',
        	]);

			/**********************************************************/
			
			$dni 	 				 		= 	strtoupper($request['dni']);
			$imagepath = $dni.".jpg";
			$directoryPath = "bingos/".$imagepath;

			if(File::exists($directoryPath)){
				return Redirect::back()->withInput()->with('descargar_bingo', $dni);
			}else{
				return Redirect::back()->withInput()->with('errorbd', 'dni incorrecto o no tiene un bingo asignado');
			}
				    

		}else{
			return View::make('encuesta/bingo');
		}

	}


	public function actionInicioEncuesta(Request $request)
	{

		if($_POST)
		{
			/**** Validaciones laravel ****/
			$this->validate($request, [
	            'dni' => 'required',
			], [
            	'dni.required' => 'El campo DNI es obligatorio',
        	]);

			/**********************************************************/
			
			$dni 	 				 		= 	strtoupper($request['dni']);

			$persona						=   WEBTrabajador::where('activo','=','1')->where('Dni','=',$dni)->first();

			if(count($persona)>0)
			{
				return Redirect::to('/realizar-encuesta/'.$dni);
			}else{
				return Redirect::back()->withInput()->with('errorbd', 'DNI incorrecto');
			}						    

		}else{
			return View::make('encuesta/ingreso');
		}

	}


	public function actionRealizarEncuesta($dni)
	{


		$persona				=   WEBTrabajador::where('activo','=','1')->where('Dni','=',$dni)->first();
		$persona_encuesta		=   WEBEncuesta::where('activo','=','1')->where('persona_id','=',$persona->Id)
									->orderBy('fecha_crea', 'desc')
									->first();

		$listapregunta 			= 	DB::table('WEB.tiporespuestas')
									->join('WEB.preguntas', 'WEB.tiporespuestas.id', '=', 'WEB.preguntas.tiporespuesta_id')
							   		->leftJoin('WEB.preguntarespuestas', function($leftJoin)
								        {
								            $leftJoin->on('WEB.preguntas.id', '=', 'WEB.preguntarespuestas.pregunta_id')
								            ->where('WEB.preguntarespuestas.activo', '=', 1);
								        })
							   		->leftJoin('WEB.respuestas', function($leftJoin)
								        {
								            $leftJoin->on('WEB.respuestas.id', '=', 'WEB.preguntarespuestas.respuesta_id')
								            ->where('WEB.respuestas.activo', '=', 1);
								        })
							   		->where('WEB.preguntas.activo', '=', 1)
							   		->orderBy('WEB.preguntas.numero', 'ASC')
							   		->select('WEB.preguntas.id','WEB.preguntas.numero','WEB.preguntas.grupo','WEB.preguntarespuestas.id as IdPreguntaRespuesta','WEB.tiporespuestas.Descripcion as DescripcionTipo','WEB.preguntas.descripcion','WEB.respuestas.descripcion as DescripcionResp')
								    ->get();

		$funcion 				= 	$this->funciones;

		return View::make('encuesta/encuesta',
						  [
						   'listapregunta' 			=> $listapregunta,
						   'persona' 				=> $persona,
						   'persona_encuesta' 		=> $persona_encuesta,
						   'funcion' 				=> $funcion,
						  ]
						 );

	}



	public function actionGuardarEncuestaTrabajador(Request $request){



		$xmle			=	explode('***', $request['xml']);
		$txtespecificar =	$request['txtespecificar'];
		$persona_id 	=	$request['persona_id'];
		$cont 			= 	0;
		$sw 			=   '0';


		$persona		=   WEBEncuesta::where('activo','=','1')->where('persona_id','=',$persona_id)->first();


		/*if(count($persona)>0){
			$sw 		= 	'1';
			print_r($sw);
			exit();
		}*/


		$id 					 	= 	$this->funciones->getCreateIdMaestra('WEB.encuestas');
		$codigo 					= 	$this->funciones->generar_codigo('WEB.encuestas',8);


		$encuesta 					=  	new WEBEncuesta;
		$encuesta->id 				=  	$id;
		$encuesta->codigo 			=  	$codigo;
		$encuesta->persona_id 		=  	$persona_id;
		$encuesta->descripcion      = 	$txtespecificar;
		$encuesta->cantidad_doctor 	=  	0;
		$encuesta->cantidad_enfermera = 0;
		$encuesta->fecha      		= 	$this->fecha_sin_hora;
		$encuesta->fecha_crea 		=  	$this->fechaactual;
		$encuesta->usuario_crea 	=  	$persona_id;
		$encuesta->save();



		$count_doctor 				= 	0;
		$count_enfermera 			= 	0;
			// radio y check
		for ($i = 0; $i < count($xmle)-1; $i++) {

			$separar 							= 	explode('&&&', $xmle[$i]);
			$idd 					 			= 	$this->funciones->getCreateIdMaestra('WEB.respuestapersonas');
			$detalle 							=  	new WEBRespuestapersona;
			$detalle->id 						=  	$idd;
			$detalle->encuesta_id 				=  	$id;
			$detalle->preguntarespuesta_id      = 	$separar[0];
			$detalle->fecha_crea 				=  	$this->fechaactual;
			$detalle->usuario_crea 				=  	$persona_id;
			$detalle->save();

			if($detalle->preguntarespuesta->pregunta->grupo == '0002'){
				$count_doctor 					=   $count_doctor + $detalle->preguntarespuesta->respuesta->valoracion;
			}
		
			if($detalle->preguntarespuesta->pregunta->grupo == '0003'){
				$count_enfermera 				=   $count_enfermera + $detalle->preguntarespuesta->respuesta->valoracion;
			}	


		}

		if($count_doctor>0){
			$encuesta->ind_doctor 		=  	0;			
		}
		if($count_enfermera>0){
			$encuesta->ind_enfermera 	=  	0;			
		}

		$encuesta->cantidad_doctor 		=  	$count_doctor;
		$encuesta->cantidad_enfermera 	=  	$count_enfermera;
		$encuesta->save();

		print_r($sw);
		exit();
			
	}


	public function actionTerminoEncuesta($dni)
	{

		$persona						=   WEBTrabajador::where('activo','=','1')->where('Dni','=',$dni)->first();

		return View::make('encuesta/terminoencuesta',
						  [
						   'persona' 		=> $persona
						  ]
						 );
	}

	public function actionTamizajeDiario()
	{
		$this->funciones->encuesta_trabajadores_tamizaje_dia($this->fecha_menos_uno);

	}

	public function actionTamizajeDiarioRioja()
	{
		$this->funciones->encuesta_trabajadores_tamizaje_dia_rioja($this->fecha_menos_uno);

	}


	public function actionTamizajeDiarioBellavista()
	{
		$this->funciones->encuesta_trabajadores_tamizaje_dia_bellavista($this->fecha_menos_uno);

	}


	public function actionTamizajeDiarioIsl()
	{
		$this->funciones->encuesta_trabajadores_tamizaje_dia_isl($this->fecha_menos_uno);

	}







}
