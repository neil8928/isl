<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\SolicitudesMaterial,App\ALMUnidadMedida;
use App\STDOrdenAsignacionUnidadAsignacion;
use View;
use Session;
use Hashids;
use PDO;



class SolicitudMaterialesController extends Controller
{


	public function actionListarSolicitudMateriales($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		/******************* validar permiso aporbacion **********************/
		$proceso_id = '1CH000000050';
		$validarpermiso = $this->funciones->permiso_aprobacion_requerimiento($proceso_id);
	    if($validarpermiso <> 'true'){return $validarpermiso;}
	    /******************************************************/

	    $fecha_menos_siete_dias  	= 	$this->fecha_menos_siete_dias;
	    $fechafin  					= 	$this->fin;


	    $listasolicitudes 			= 	SolicitudesMaterial::where('IdEstadoOA','=','1CH00014')
	    	    						->where('Fecha','>=', $fecha_menos_siete_dias)
	    								->where('Fecha','<=', $fechafin)
	    								->orderBy('id', 'asc')
	    								->get();
							
		return View::make('solicitudesmateriales/listasolicitudesmateriales',
						 [
						 	'listasolicitudes' 			=> $listasolicitudes,
						 	'idopcion' 					=> $idopcion,
						 	'fecha_menos_siete_dias' 	=> $fecha_menos_siete_dias,
						 	'fechafin' 					=> $fechafin
						 ]);


	}




	public function actionAjaxListarSolicitudMateriales(Request $request)
	{

		$finicio 		=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 			=  date_format(date_create($request['ffin']), 'd-m-Y');
		$idopcion 		=  $request['idopcion'];


	    $listasolicitudes 			= 	SolicitudesMaterial::where('IdEstadoOA','=','1CH00014')
	    	    						->where('Fecha','>=', $finicio)
	    								->where('Fecha','<=', $ffin)
	    								->orderBy('id', 'asc')
	    								->get();

		$funcion 		= 	$this;	

		return View::make('solicitudesmateriales/ajax/listasolicitudesmateriales',
						 [
						 	'listasolicitudes' 	=> $listasolicitudes,
						 	'ajax'   			=> true,
						 ]);

	}


	public function actionAjaxMantenimientoSolicitudMateriales(Request $request)
	{

		$orden_asignacion_id 		=  $request['data_ioa'];
		$idopcion 					=  $request['idopcion'];


	    $vacio 						=   "";
	    $tipooperacion 				=   1;


	    /*Lista para seleccionar solititud*/
		$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_OrdenAsignacion_Listar ?,?');
        $stmt->bindParam(1, $tipooperacion ,PDO::PARAM_STR);
        $stmt->bindParam(2, $orden_asignacion_id ,PDO::PARAM_STR);
        $stmt->execute();
        $headers = $stmt->fetch(2);

	    /*Lista para seleccionar solititud*/
		$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_OrdenAsignacion_Material_Listar ?,?,?,?,?,?,?,?,?,?');
        $stmt->bindParam(1, $tipooperacion ,PDO::PARAM_STR);
        $stmt->bindParam(2, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(4, $orden_asignacion_id ,PDO::PARAM_STR);
        $stmt->bindParam(5, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(6, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(7, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(8, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(9, $vacio ,PDO::PARAM_STR);
        $stmt->bindParam(10, $vacio ,PDO::PARAM_STR);
        $stmt->execute();
        $cabecera = $stmt->fetch(2);
        $stmt->nextRowset();


	    /*unidad de medidas*/

	    $unidad 	=	ALMUnidadMedida::get();


		return View::make('solicitudesmateriales/ajax/mantenimientosolicitudesmateriales',
						 [
						 	'headers' 			=> $headers,
						 	'cabecera' 			=> $cabecera,
						 	'detalle' 			=> $stmt,
						 	'unidad' 			=> $unidad,
						 	'idopcion' 			=> $idopcion,
						 	'ajax'   			=> true,
						 ]);


	}




	public function actionGuardarSolicitudMateriales($idopcion,Request $request)
	{


		try{

			DB::beginTransaction();

			$orden_asignacion_id 			=  $request['orden_asignacion_id'];
			$orden_asignacion_fecha 		=  $request['orden_asignacion_fecha'];
			$orden_asignacion_nrooa 		=  $request['orden_asignacion_nrooa'];
			$orden_asignacion_glosa 		=  $request['orden_asignacion_glosa'];
			$ind_unidad_asignada 			=  $request['ind_unidad_asignada'];
			$id_unidad_asignacion 			=  $request['id_unidad_asignacion'];
			$unidad_asignada 				=  $request['unidad_asignada'];

			$respuesta 						=  json_decode($request['xml_productos'], true);

			$prefijo 						=  	'1CH'; //queda pendiente logeo
			$vacio 							=  	'';
			$float_vacio 					=  	0.0000;

			$accion 						=  	'S';
			$orden_asignacion_estado 		=  	'1CH00014';
			$estado 						=  	1;
			$usuario_id						=  	Session::get('usuario')->IdUsuariaIsl;
			$orden_asignacion_fecha         =  	date_format(date_create($orden_asignacion_fecha), 'd-m-Y H:m:s');
			$fecha_hora 					= 	$this->fecha_hora;

			//Obtener igv

			$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_IGV_Obtener ?');
	        $stmt->bindParam(1, $fecha_hora ,PDO::PARAM_STR);
	        $stmt->execute();
	        $ligv = $stmt->fetch(2);
	        $igv  = $ligv['Porcentaje'];


	        
			// 	STD.Isp_OrdenAsignacion_IAE
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_OrdenAsignacion_IAE ?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@TipoOperacion='S',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 //@Prefijo='1SI',
	        $stmt->bindParam(3, $orden_asignacion_id ,PDO::PARAM_STR);                      //@Id='1CH000010473',
	        $stmt->bindParam(4, $orden_asignacion_fecha ,PDO::PARAM_STR);                   //@Fecha='2019-10-18 15:57:47',
	        $stmt->bindParam(5, $orden_asignacion_nrooa ,PDO::PARAM_STR);                   //@NroOA='OACH000010473'
	        $stmt->bindParam(6, $orden_asignacion_estado ,PDO::PARAM_STR);                  //@IdEstadoOA='1CH00014',
	        $stmt->bindParam(7, $orden_asignacion_glosa  ,PDO::PARAM_STR);                  //@Glosa='EPSON L5190',
	        $stmt->bindParam(8, $estado ,PDO::PARAM_STR);                                 	//@Activo=1,
	        $stmt->bindParam(9, $usuario_id ,PDO::PARAM_STR);                         		//@UsuarioCreacion='1CH000000338',
	        $stmt->bindParam(10,$vacio ,PDO::PARAM_STR);                         			//@entregadoA='NULL' 
	        $stmt->execute();


	        //STD.Isp_OrdenAsignacion_UnidadAsignacion_IAE E
			STDOrdenAsignacionUnidadAsignacion::where('IdOrdenAsignacion','=',$orden_asignacion_id)
										->update(['activo' => 0]);


			//STD.Isp_OrdenAsignacion_UnidadAsignacion_IAE S
			STDOrdenAsignacionUnidadAsignacion::where('Id','=',$orden_asignacion_id)
										->update([	'activo' => 1 , 
													'IdOrdenAsignacion' => $orden_asignacion_id ,
													'IndUnidadAsignacion' => $ind_unidad_asignada,
													'IdUnidadAsignacion' => $id_unidad_asignacion,
													'UsuarioCreacion' => $usuario_id
												]);




			//STD.Isp_OrdenAsignacion_Material_IAE E
			$accion 						=  'E';

	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_OrdenAsignacion_Material_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       //@TipoOperacion='E',
	        $stmt->bindParam(2, $vacio  ,PDO::PARAM_STR);                       //@Prefijo='',
	        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                      	//@Id='',
	        $stmt->bindParam(4, $vacio ,PDO::PARAM_STR);                   		//@IdMaterial='',
	        $stmt->bindParam(5, $orden_asignacion_id ,PDO::PARAM_STR);          //@IdOrdenAsignacion='1CH000010473'

	        $stmt->bindParam(6, $vacio ,PDO::PARAM_STR);                  		//@IndUnidadAsignacion='',
	        $stmt->bindParam(7, $vacio  ,PDO::PARAM_STR);                  		//@IdUnidadAsignacion='',
	        $stmt->bindParam(8, $vacio ,PDO::PARAM_STR);                        //@IdUnidadMedida='',
	        $stmt->bindParam(9, $float_vacio ,PDO::PARAM_STR);                  //@CantidadMaterial='',
	        $stmt->bindParam(10, $float_vacio ,PDO::PARAM_STR);                 //@CantidadMaterialEntregada='',

	        $stmt->bindParam(11, $float_vacio ,PDO::PARAM_STR);                 //@CantidadMaterialDevuelto='',
	        $stmt->bindParam(12, $float_vacio  ,PDO::PARAM_STR);                //@CostoUnitario='',
	        $stmt->bindParam(13, $vacio ,PDO::PARAM_STR);                     	//@IdSubAlmacen='',
	        $stmt->bindParam(14, $vacio ,PDO::PARAM_STR);                  		//@Activo='',
	        $stmt->bindParam(15, $vacio ,PDO::PARAM_STR);                  		//@UsuarioCreacion=''

	        $stmt->bindParam(16, $vacio ,PDO::PARAM_STR);                 		//@IndDescuento='',
	        $stmt->bindParam(17, $float_vacio  ,PDO::PARAM_STR);                //@MontoDescuento='',
	        $stmt->bindParam(18, $vacio ,PDO::PARAM_STR);                       //@DevuelveEpp='',
	        $stmt->bindParam(19, $vacio ,PDO::PARAM_STR);                       //@IdAutoriza='',
	        $stmt->bindParam(20, $vacio ,PDO::PARAM_STR);                       //@MotivoAutoriza=''
	        $stmt->bindParam(21, $vacio ,PDO::PARAM_STR);                       //@IdOARenovado='' 
	        $stmt->execute();



	        //STD.Isp_OrdenAsignacion_Material_IAE S

			$accion 						=  'S';
			foreach($respuesta as $obj){

				$data_id_oa_material 				= 	$obj['data_id_oa_material'];
				$data_id_material 					= 	$obj['data_id_material'];
				$data_id_oa 						= 	$obj['data_id_oa'];
				$data_ind_unidad_asignacion 		= 	$obj['data_ind_unidad_asignacion'];
				$data_id_unidad_asignacion 			= 	$obj['data_id_unidad_asignacion'];
				$data_id_unidad_medida 				= 	$obj['data_id_unidad_medida'];
				$data_cantidad_material_entregada 	= 	(float)$obj['data_cantidad_material_entregada'];
				$data_cantidad_material_devuelto 	= 	(float)$obj['data_cantidad_material_devuelto'];
				$data_costo_unitario 				= 	(float)$obj['data_costo_unitario'];
				$data_id_sub_almacen 				= 	$obj['data_id_sub_almacen'];
				$data_ind_descuento 				= 	(int)$obj['data_ind_descuento'];
				$data_monto_descuento 				= 	(float)$obj['data_monto_descuento'];
				$data_cantidad 						= 	(float)$obj['data_cantidad'];


		        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC STD.Isp_OrdenAsignacion_Material_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
		        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       		//@TipoOperacion='E',
		        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                      		//@Prefijo='1SI',
		        $stmt->bindParam(3, $data_id_oa_material ,PDO::PARAM_STR);                  //@Id='1CH000021834',
		        $stmt->bindParam(4, $data_id_material ,PDO::PARAM_STR);                   	//@IdMaterial='1CH000005093',
		        $stmt->bindParam(5, $data_id_oa ,PDO::PARAM_STR);          					//@IdOrdenAsignacion='1CH000010473'

		        $stmt->bindParam(6, $data_ind_unidad_asignacion ,PDO::PARAM_STR);           //@IndUnidadAsignacion='A',
		        $stmt->bindParam(7, $data_id_unidad_asignacion  ,PDO::PARAM_STR);           //@IdUnidadAsignacion='1CH000000006',
		        $stmt->bindParam(8, $data_id_unidad_medida ,PDO::PARAM_STR);                //@IdUnidadMedida='1PY000000011',
		        $stmt->bindParam(9, $data_cantidad ,PDO::PARAM_STR);     					//@CantidadMaterial=5.0000
		        $stmt->bindParam(10, $data_cantidad_material_entregada ,PDO::PARAM_STR);     //@CantidadMaterialEntregada=0,

		        $stmt->bindParam(11, $data_cantidad_material_devuelto ,PDO::PARAM_STR);     //@CantidadMaterialDevuelto=0,
		        $stmt->bindParam(12, $data_costo_unitario  ,PDO::PARAM_STR);                //@CostoUnitario=64.3220,
		        $stmt->bindParam(13, $data_id_sub_almacen ,PDO::PARAM_STR);                 //@IdSubAlmacen='1SI000000080',
		        $stmt->bindParam(14, $estado ,PDO::PARAM_STR);                  			//@Activo='1',
		        $stmt->bindParam(15, $usuario_id ,PDO::PARAM_STR);                  		//@UsuarioCreacion='1CH000000338'
		        $stmt->bindParam(16, $data_ind_descuento ,PDO::PARAM_STR);                 	//@IndDescuento=0,
		        $stmt->bindParam(17, $data_monto_descuento  ,PDO::PARAM_STR);               //@MontoDescuento=0,
		        $stmt->bindParam(18, $vacio ,PDO::PARAM_STR);                       		//@DevuelveEpp='',
		        $stmt->bindParam(19, $vacio ,PDO::PARAM_STR);                       		//@IdAutoriza='',
		        $stmt->bindParam(20, $vacio ,PDO::PARAM_STR);                       		//@MotivoAutoriza=''
		        $stmt->bindParam(21, $vacio ,PDO::PARAM_STR);                       		//@IdOARenovado='' 
		        $stmt->execute();

			}


			$accion 						=  	'I';
			$fecha_infinita 				=  	'1901-01-01 00:00:00';
			$materialservicio 				=  	'M';
			$idrequerimiento 				= 	'';
			$id_estado_requerimiento 		=  	'1CH000000002';
			$tipo_referencia 				=  	'ORDEN ASIGNACION';
			$descripcion 					=  	$unidad_asignada.' / ASIGNACION DE MATERIALES';
			$equipo_id 						=  	'';
			$trabajador_id					= 	'';
			$area_id 						= 	'';

			if($ind_unidad_asignada == 'V'){
				$equipo_id 		= $id_unidad_asignacion;
			}else{
				if($ind_unidad_asignada == 'A'){
					$area_id 		= $id_unidad_asignacion;
				}else{
					if($ind_unidad_asignada == 'T'){
						$trabajador_id 		= $id_unidad_asignacion;
					}	
				}
			}


			// 	CMP.Isp_Requerimiento_IAE 
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_Requerimiento_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   		//@TipoOperacion='I',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 		//@Prefijo='1SI',
	        $stmt->bindParam(3, $idrequerimiento ,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,12);   	//@Id='output',
	        $stmt->bindParam(4, $fecha_infinita ,PDO::PARAM_STR);                   				//@FechaAtencion='1901-01-01 00:00:00',
	        $stmt->bindParam(5, $materialservicio ,PDO::PARAM_STR);                   				//@MaterialServicio='M'
	        $stmt->bindParam(6, $id_estado_requerimiento ,PDO::PARAM_STR);          				//@IdEstadoRequerimiento='1CH000000002',
	        $stmt->bindParam(7, $descripcion  ,PDO::PARAM_STR);          							//@Descripcion='COORDINACIÓN  / ASIGNACION DE MATERIALES',
	        $stmt->bindParam(8, $tipo_referencia ,PDO::PARAM_STR);                           		//@TipoReferencia='ORDEN ASIGNACION',
	        $stmt->bindParam(9, $orden_asignacion_id ,PDO::PARAM_STR);                       		//@IdReferencia='1CH000009155',
	        $stmt->bindParam(10, $equipo_id ,PDO::PARAM_STR);										//@IdEquipo='',
	        $stmt->bindParam(11, $trabajador_id ,PDO::PARAM_STR);                                   //@IdTrabajador='',
	        $stmt->bindParam(12, $area_id  ,PDO::PARAM_STR);                    					//@IdArea='1CH000000022',
	        $stmt->bindParam(13, $vacio ,PDO::PARAM_STR);                      						//@GlosaAtencion='',
	        $stmt->bindParam(14, $vacio ,PDO::PARAM_STR);                   						//@IdUsuarioAtencion='',
	        $stmt->bindParam(15, $estado ,PDO::PARAM_STR);                   						//@Activo='1'
	        $stmt->bindParam(16, $usuario_id ,PDO::PARAM_STR);                  					//@UsuarioCreacion='1CH000000338',
	        $stmt->bindParam(17, $vacio  ,PDO::PARAM_STR);                  						//@IdTrabajadorAprobacion='',
	        $stmt->execute();
			
			$reque = $stmt->fetch(2);
			$id_requerimiento = $reque[''];


	        //STD.Isp_Requerimiento_Material_IAE I
			foreach($respuesta as $obj){

				$data_id_oa_material 				= 	$obj['data_id_oa_material'];
				$data_id_material 					= 	$obj['data_id_material'];
				$data_id_oa 						= 	$obj['data_id_oa'];
				$data_ind_unidad_asignacion 		= 	$obj['data_ind_unidad_asignacion'];
				$data_id_unidad_asignacion 			= 	$obj['data_id_unidad_asignacion'];
				$data_id_unidad_medida 				= 	$obj['data_id_unidad_medida'];
				$data_cantidad_material_entregada 	= 	$obj['data_cantidad_material_entregada'];
				$data_cantidad_material_devuelto 	= 	$obj['data_cantidad_material_devuelto'];
				$data_costo_unitario 				= 	$obj['data_costo_unitario'];
				$data_id_sub_almacen 				= 	$obj['data_id_sub_almacen'];
				$data_ind_descuento 				= 	$obj['data_ind_descuento'];
				$data_monto_descuento 				= 	$obj['data_monto_descuento'];
				$data_cantidad 						= 	(float)$obj['data_cantidad'];
				$data_id_almacen 					= 	$obj['data_id_almacen'];
				$cantida_atender 					= 	0;
				$indicador_aprobacion				= 	0;
				$indicador_regularizado				= 	0;
				$cantidad_regularizado				= 	0;
	        	$precio 							=   (float)$data_costo_unitario * (1 + (float)$igv);
	        	$id_estado_requerimiento 			= 	'1CH000000006';



		        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_Requerimiento_Material_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
		        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       		//@TipoOperacion='I',
		        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                      		//@Prefijo='1SI',
		        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                  				//@Id='',
		        $stmt->bindParam(4, $id_requerimiento ,PDO::PARAM_STR);                   	//@IdRequerimiento='1SI000010312',
		        $stmt->bindParam(5, $data_id_material ,PDO::PARAM_STR);          			//@IdMaterial='1CH000005993'
		        $stmt->bindParam(6, $data_cantidad ,PDO::PARAM_STR);           				//@Cantidad=2.0000,
		        $stmt->bindParam(7, $precio  ,PDO::PARAM_STR);           					//@Precio=6.9999,
		        $stmt->bindParam(8, $vacio ,PDO::PARAM_STR);                				//@Glosa='NULL',
		        $stmt->bindParam(9, $data_id_sub_almacen ,PDO::PARAM_STR);     				//@IdSubAlmacen='1SI000000080',
		        $stmt->bindParam(10, $vacio ,PDO::PARAM_STR);     							//@IdAlmacen='',
		        $stmt->bindParam(11, $cantida_atender  ,PDO::PARAM_STR);                	//@CantidadPorAtender=0,
		        $stmt->bindParam(12, $estado ,PDO::PARAM_STR);                 				//@Activo=1,
		        $stmt->bindParam(13, $usuario_id ,PDO::PARAM_STR);                  		//@UsuarioCreacion='1CH000000338',
		        $stmt->bindParam(14, $data_id_unidad_medida ,PDO::PARAM_STR);               //@IdUnidadMedida='1PY000000011'
		        $stmt->bindParam(15, $vacio ,PDO::PARAM_STR);                 				//@IdCentroCosto='',
		        $stmt->bindParam(16, $id_estado_requerimiento  ,PDO::PARAM_STR);          	//@IdEstadoRequerimiento='1CH000000006',
		        $stmt->bindParam(17, $indicador_aprobacion ,PDO::PARAM_STR);                //@IndicadorAprobacion=0,
		        $stmt->bindParam(18, $vacio ,PDO::PARAM_STR);                       		//@IdMantenimiento='',
		        $stmt->bindParam(19, $indicador_regularizado ,PDO::PARAM_STR);              //@IndRegularizado=0
		        $stmt->bindParam(20, $cantidad_regularizado ,PDO::PARAM_STR);               //@CantidadPorRegularizar=0
		        $stmt->bindParam(21, $fecha_infinita ,PDO::PARAM_STR);                      //@FechaAtencion='1901-01-01 00:00:00' 
		        $stmt->execute();

			}



	       //ALM.Isp_Material_Almacen_IAE S
			foreach($respuesta as $obj){

				$data_id_oa_material 				= 	$obj['data_id_oa_material'];
				$data_id_material 					= 	$obj['data_id_material'];
				$data_id_oa 						= 	$obj['data_id_oa'];
				$data_ind_unidad_asignacion 		= 	$obj['data_ind_unidad_asignacion'];
				$data_id_unidad_asignacion 			= 	$obj['data_id_unidad_asignacion'];
				$data_id_unidad_medida 				= 	$obj['data_id_unidad_medida'];
				$data_cantidad_material_entregada 	= 	$obj['data_cantidad_material_entregada'];
				$data_cantidad_material_devuelto 	= 	$obj['data_cantidad_material_devuelto'];
				$data_costo_unitario 				= 	$obj['data_costo_unitario'];
				$data_id_sub_almacen 				= 	$obj['data_id_sub_almacen'];
				$data_ind_descuento 				= 	$obj['data_ind_descuento'];
				$data_monto_descuento 				= 	$obj['data_monto_descuento'];
				$data_cantidad 						= 	$obj['data_cantidad'];
				$data_id_almacen 					= 	$obj['data_id_almacen'];
				$accion 							=  	'S';
				$stockminimo 						=  	1;
				$stockmaximo 						=  	1;

		        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC ALM.Isp_Material_Almacen_IAE ?,?,?,?,?,?,?,?,?,?,?,?');
		        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       		//@TipoOperacion='S',
		        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                      		//@Prefijo='1SI',
		        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                  				//@Id='',
		        $stmt->bindParam(4, $data_id_material ,PDO::PARAM_STR);                   	//@IdMaterial='1CH000004657',
		        $stmt->bindParam(5, $data_id_almacen ,PDO::PARAM_STR);          			//@IdAlmacen='1CH000000010'
		        $stmt->bindParam(6, $usuario_id ,PDO::PARAM_STR);           				//@UsuarioCreacion='1CH000000338',
		        $stmt->bindParam(7, $estado  ,PDO::PARAM_STR);           					//@Activo=1,
		        $stmt->bindParam(8, $stockminimo ,PDO::PARAM_STR);                			//@StockMinimo=1.0000,
		        $stmt->bindParam(9, $stockmaximo ,PDO::PARAM_STR);     						//@StockMaximo=1.0000,
		        $stmt->bindParam(10, $vacio ,PDO::PARAM_STR);     							//@IdZona='',
		        $stmt->bindParam(11, $vacio  ,PDO::PARAM_STR);                				//@IdUnidad='',
		        $stmt->bindParam(12, $vacio ,PDO::PARAM_STR);                 				//@TipoReferencia='', 
		        $stmt->execute();

			}



			DB::commit();

	 		return Redirect::to('/gestion-de-solicitud-materiales/'.$idopcion)->with('bienhecho', 'La Solicitud '.$orden_asignacion_nrooa.' genero requerimiento');

			}catch(Exception $ex){
				DB::rollback();
				return Redirect::to('/gestion-de-solicitud-materiales/'.$idOpcion)->with('errorcommit', 'Ocurrio un error inesperado. Porfavor contacte con el administrador del sistema : '.$ex);	
			}





	}

}
