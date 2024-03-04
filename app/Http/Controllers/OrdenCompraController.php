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


class OrdenCompraController extends Controller
{

	public function actionAprobarOrdenCompraServicios($idopcion,Request $request)
	{


		//validacion montominimo
		$trabajador 						=  $this->funciones->trabajador_seguridad(Session::get('usuario')->IdTrabajador);

		if((float)$request['total'] > (float)$trabajador['CantidadLimite'] ){
			return Redirect::to('/gestion-de-orden-compra-servicios/'.$idopcion)->with('errorbd', 'No se puede aprobar ordenes de compra ya que sobre pasa el monto limite de aprobación de '.(float)$trabajador['CantidadLimite']);
		}


		try{

			DB::beginTransaction();


			$orden_compra_id 				=  	$request['orden_compra_id'];
			$orden_compra_nroorden 			=  	$request['orden_compra_nroorden'];
			$moneda_id 						=  	$request['moneda_id'];
			$trabajador_id 					=  	Session::get('usuario')->IdTrabajador;
			$proveedor_id 					=  	$request['proveedor_id'];
			$subtotal 						=  	(float)$request['subtotal'];
			$impuesto 						=  	(float)$request['impuesto'];
			$total 							=  	(float)$request['total'];
			$fechaorden 					=  	$request['fechaorden'];
			$fechaentrega 					=  	$request['fechaentrega'];
			$fechapago 						=  	$request['fechapago'];
			$glosa 							=  	$request['glosa'];
			$notas 							=  	$request['notas'];
			$centro_id 						=  	$request['centro_id'];
			$tipo_pago_id 					=  	$request['tipo_pago_id'];


			$respuesta 						=  	json_decode($request['xml_productos'], true);
			$prefijo 						=  	'1CH'; //queda pendiente logeo
			$vacio 							=  	'';
			$float_vacio 					=  	0.0000;
			$accion 						=  	'1';
			$orden_asignacion_estado 		=  	'1CH00014';
			$estado 						=  	1;
			$usuario_id						=  	Session::get('usuario')->IdUsuariaIsl;
			$indicador_aprobacion 			=  	1;
			$cero 							=  	0;
			$tipo_compra_id 				=  	'1PK000000003';
			$estado_orden_id 				=  	'1CH000000011';
		    $nulo 							=   Null;
			$fecha_hora 					= 	$this->fecha_hora;

	        
			// 	CMP.Isp_OrdenCompra_IAE
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_OrdenCompra_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@TIPOOPERACION='1',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 //@Prefijo='1SI',
	        $stmt->bindParam(3, $orden_compra_id ,PDO::PARAM_STR);                      	//@Id='1PI000008408',
	        $stmt->bindParam(4, $orden_compra_nroorden ,PDO::PARAM_STR);                    //@NroOrden='OCPI00008408',
	        $stmt->bindParam(5, $moneda_id ,PDO::PARAM_STR);                   				//@IdMoneda='1CH01'
	        $stmt->bindParam(6, $trabajador_id ,PDO::PARAM_STR);                  			//@IdTrabajador='1CH000000637',
	        $stmt->bindParam(7, $proveedor_id  ,PDO::PARAM_STR);                  			//@IdProveedor='1CH000012956',
	        $stmt->bindParam(8, $subtotal ,PDO::PARAM_STR);                                 //@SubTotal=1,
	        $stmt->bindParam(9, $impuesto ,PDO::PARAM_STR);                         		//@Impuesto=1.25,
	        $stmt->bindParam(10,$total ,PDO::PARAM_STR);                         			//@Total=102 

	        $stmt->bindParam(11, $vacio ,PDO::PARAM_STR);                              		//@FechaOrden='',
	        $stmt->bindParam(12, $vacio  ,PDO::PARAM_STR);                           		//@FechaEntrega='',
	        $stmt->bindParam(13, $vacio ,PDO::PARAM_STR);                     				//@FechaPago='',

	        $stmt->bindParam(14, $indicador_aprobacion ,PDO::PARAM_STR);                  	//@IndicadorAprobacion='1',
	        $stmt->bindParam(15, $tipo_compra_id ,PDO::PARAM_STR);                  		//@IdTipoOrdenCompra='OACH000010473'
	        $stmt->bindParam(16, $glosa ,PDO::PARAM_STR);                 					//@Glosa='1CH00014',
	        $stmt->bindParam(17, $notas  ,PDO::PARAM_STR);                 					//@Notas='EPSON L5190',
	        $stmt->bindParam(18, $estado ,PDO::PARAM_STR);                                 	//@Activo=1,
	        $stmt->bindParam(19, $estado_orden_id ,PDO::PARAM_STR);                         //@IdEstadoOrden='1CH000000338',
	        $stmt->bindParam(20, $centro_id ,PDO::PARAM_STR);                         		//@IdCentro='NULL' 

	        $stmt->bindParam(21, $tipo_pago_id ,PDO::PARAM_STR);                            //@IdTipoPago='1',
	        $stmt->bindParam(22, $cero  ,PDO::PARAM_STR);                                	//@IndicadorConsignacion='1SI',
	        $stmt->bindParam(23, $usuario_id ,PDO::PARAM_STR);                     			//@UsuarioCreacion='1CH000010473',
	        $stmt->bindParam(24, $cero ,PDO::PARAM_STR);                  					//@Percepcion='2019-10-18 15:57:47',
	        $stmt->bindParam(25, $cero ,PDO::PARAM_STR);                  					//@PercepcionPorc='OACH000010473'
	        $stmt->bindParam(26, $cero ,PDO::PARAM_STR);                 					//@Detraccion='1CH00014',
	        $stmt->bindParam(27, $cero  ,PDO::PARAM_STR);                 					//@DetraccionPorc='EPSON L5190',
	        $stmt->bindParam(28, $cero ,PDO::PARAM_STR);                                 	//@IndTipoCompra=1,
	        $stmt->bindParam(29, $cero ,PDO::PARAM_STR);                         			//@IndCotizacion='1CH000000338',
	        $stmt->bindParam(30, $nulo ,PDO::PARAM_STR);                         			//@IdViaje='NULL' 
	        $stmt->execute();


	        //CMP.Isp_OrdenCompraMaterial_IAE

			foreach($respuesta as $obj){


				$data_id_oc_servicio 				= 	$obj['data_id_oc_servicio'];
				$data_id_servicio 					= 	$obj['data_id_servicio'];
				$data_cantidad 						= 	(float)$obj['data_cantidad'];
				$data_precio_unitario 				= 	(float)$obj['data_precio_unitario'];
				$data_valor_venta 					= 	(float)$obj['data_valor_venta'];
				$data_ind_igv 						= 	$obj['data_ind_igv'];
				$data_centro_costo 					= 	$obj['data_centro_costo'];
				$data_glosa 						= 	$obj['data_glosa'];
				$data_notas 						= 	$obj['data_notas'];
				$data_area 							= 	$obj['data_area'];
				$data_requerimiento_servicio 		= 	$obj['data_requerimiento_servicio'];
				$data_id_equipo 					= 	$obj['data_id_equipo'];
				$data_id_gasto_funcion 				= 	$obj['data_id_gasto_funcion'];
				$data_id_placa 						= 	$obj['data_id_placa'];
				$data_id_viaje 						= 	$obj['data_id_viaje'];
				$data_id_flujo_caja 				= 	$obj['data_id_flujo_caja'];



		        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.ISP_OrdenCompraServicio_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
		        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       		//@TIPOOPERACION='1',
		        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                      		//@Prefijo='1SI',
		        $stmt->bindParam(3, $data_id_oc_servicio ,PDO::PARAM_STR);                  //@Id='1PI000014592',
		        $stmt->bindParam(4, $orden_compra_id ,PDO::PARAM_STR);                   	//@IdOrden='1CH000005093',
		        $stmt->bindParam(5, $data_id_servicio ,PDO::PARAM_STR);          			//@IdMaterial='1CH000010473'

		        $stmt->bindParam(6, $data_cantidad ,PDO::PARAM_STR);           				//@CantidadMaterial='A',
		        $stmt->bindParam(7, $data_precio_unitario  ,PDO::PARAM_STR);           		//@CostoUnitario='1CH000000006',
		        $stmt->bindParam(8, $data_valor_venta ,PDO::PARAM_STR);                		//@PrecioTotal='1PY000000011',
		        $stmt->bindParam(9, $data_ind_igv ,PDO::PARAM_STR);     					//@IdSubAlmacen=5.0000
		        $stmt->bindParam(10, $estado ,PDO::PARAM_STR);    							//@CantidadMaterialPendientes=0,

		        $stmt->bindParam(11, $data_centro_costo ,PDO::PARAM_STR);     				//@IndicadorIgv=1,
		        $stmt->bindParam(12, $data_glosa  ,PDO::PARAM_STR);                			//@Activo=1,
		        $stmt->bindParam(13, $data_notas ,PDO::PARAM_STR);               			//@IdUnidadMedida='1PY000000011',
		        $stmt->bindParam(14, $data_area ,PDO::PARAM_STR);                   		//@IdCentroCosto=null,
		        $stmt->bindParam(15, $data_requerimiento_servicio ,PDO::PARAM_STR);         //@IdRequerimientoMaterial='1CH000000338'

		        $stmt->bindParam(16, $data_id_equipo ,PDO::PARAM_STR);                 		//@Glosa=0,
		        $stmt->bindParam(17, $data_id_gasto_funcion  ,PDO::PARAM_STR);              //@Notas=0,
		        $stmt->bindParam(18, $data_id_placa ,PDO::PARAM_STR);                       //@IdArea='',
		        $stmt->bindParam(19, $data_id_viaje ,PDO::PARAM_STR);                       //@IdOrdenSalidaMaterial='',
		        $stmt->bindParam(20, $data_id_flujo_caja ,PDO::PARAM_STR);                  //@IdOrdenSalidaMaterial='',
		        $stmt->execute();


			}


			$accion 						=  	'I';
			$fechaaprobacion 				= 	$this->fin;
			$indicador_bloqueo				= 	1;

			// 	CMP.Isp_OrdenAprobacion_IAE
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_OrdenAprobacion_IAE ?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@TIPOOPERACION='I',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 //@Prefijo='1SI',
	        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                      				//@Id='',
	        $stmt->bindParam(4, $orden_compra_id ,PDO::PARAM_STR);                    		//@IdOrden='1PI000008408',
	        $stmt->bindParam(5, $trabajador_id ,PDO::PARAM_STR);                   			//@IdTrabajador='1CH000000637'
	        $stmt->bindParam(6, $fechaaprobacion ,PDO::PARAM_STR);                  		//@FechaAprobacion='2019-11-18',
	        $stmt->bindParam(7, $indicador_aprobacion  ,PDO::PARAM_STR);                  	//@IndAprobacion=1,
	        $stmt->bindParam(8, $indicador_bloqueo ,PDO::PARAM_STR);                        //@IndBloqueo=0,
	        $stmt->bindParam(9, $estado ,PDO::PARAM_STR);                         			//@Activo=1,
	        $stmt->bindParam(10,$usuario_id ,PDO::PARAM_STR);                         		//@UsuarioCreacion='1CH000000406' 
	        $stmt->execute();


			DB::commit();

	 		return Redirect::to('/gestion-de-orden-compra-servicios/'.$idopcion)->with('bienhecho', 'Orden de Compra '.$orden_compra_id.' se aprobo correctamente');

		}catch(Exception $ex){
			DB::rollback();
			return Redirect::to('/gestion-de-orden-compra-servicios/'.$idopcion)->with('errorbd', 'Ocurrio un error inesperado. Porfavor contacte con el administrador del sistema : '.$ex);	
		}




	}




	public function actionListarOrdenCompraServicios($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		/******************* validar permiso aporbacion **********************/
		$proceso_id = '1CH000000025';
		$validarpermiso = $this->funciones->permiso_aprobacion_requerimiento($proceso_id);
	    if($validarpermiso <> 'true'){return $validarpermiso;}
	    /******************************************************/


	    $area_id 		= 	'';
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


	    $fecha_menos_siete_dias  	= 	$this->fecha_menos_siete_dias;
	    $fechafin  					= 	$this->fin;
	    $idtipoordencompra          = 	'1PK000000003';
		$listaordencompra 			= 	$this->funciones->lista_orden_compra($fecha_menos_siete_dias,$fechafin,$idtipoordencompra,$area_id);
		$funcion 					= 	$this;


		return View::make('ordencompra/listaservicios',
						 [
						 	'listaordencompra' 			=> $listaordencompra,
						 	'idopcion' 					=> $idopcion,
						 	'fecha_menos_siete_dias' 	=> $fecha_menos_siete_dias,
						 	'fechafin' 					=> $fechafin,
						 	'funcion'   				=> $funcion,
						 ]);


	}

	public function actionAjaxListarOrdenCompraServicios(Request $request)
	{

		$finicio 				=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 					=  date_format(date_create($request['ffin']), 'd-m-Y');
		$idopcion 				=  $request['idopcion'];


	    $area_id 		= 	'';
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



	    $idtipoordencompra      = 	'1PK000000003';
		$listaordencompra 		= 	$this->funciones->lista_orden_compra($finicio,$ffin,$idtipoordencompra,$area_id);
		$funcion 				= 	$this;	

		return View::make('ordencompra/ajax/alistaservicios',
						 [
						 	'listaordencompra' 	=> $listaordencompra,
						 	'ajax'   			=> true,
						 	'funcion'   		=> $funcion,
						 ]);

	}

	

	public function actionAjaxMantenimientoOrdenCompraServicios(Request $request)
	{


		$orden_compra_id 			=  $request['data_ioc'];
		$proveedor_id 				=  $request['data_proveedor'];
		$idopcion 					=  $request['idopcion'];


		$ordencompra 				=  $this->funciones->orden_compra($orden_compra_id);
		$proveedor 					=  $this->funciones->proveedor($proveedor_id);
		$listaordencompraservicio 	=  $this->funciones->lista_orden_compra_servicios($orden_compra_id);


		return View::make('ordencompra/ajax/mantenimientoordencompraservicio',
						 [
						 	'ordencompra' 					=> $ordencompra,
						 	'proveedor' 					=> $proveedor,
						 	'listaordencompraservicio' 		=> $listaordencompraservicio,
						 	'idopcion' 						=> $idopcion,
						 	'ajax'   						=> true,
						 ]);


	}







	public function actionAprobarOrdenCompraMateriales($idopcion,Request $request)
	{


		//validacion montominimo
		$trabajador 						=  $this->funciones->trabajador_seguridad(Session::get('usuario')->IdTrabajador);
		$cantidad_minima 					=  (float)$trabajador['CantidadLimite'];


		if((float)$request['total'] > $cantidad_minima ){
			return Redirect::to('/gestion-de-orden-compra-materiales/'.$idopcion)->with('errorbd', 'No se puede aprobar ordenes de compra ya que sobre pasa el monto limite de aprobación de '.$cantidad_minima);
		}


		try{

			DB::beginTransaction();


			$orden_compra_id 				=  	$request['orden_compra_id'];
			$orden_compra_nroorden 			=  	$request['orden_compra_nroorden'];
			$moneda_id 						=  	$request['moneda_id'];
			$trabajador_id 					=  	Session::get('usuario')->IdTrabajador;
			$proveedor_id 					=  	$request['proveedor_id'];
			$subtotal 						=  	(float)$request['subtotal'];
			$impuesto 						=  	(float)$request['impuesto'];
			$total 							=  	(float)$request['total'];
			$fechaorden 					=  	$request['fechaorden'];
			$fechaentrega 					=  	$request['fechaentrega'];
			$fechapago 						=  	$request['fechapago'];
			$glosa 							=  	$request['glosa'];
			$notas 							=  	$request['notas'];
			$centro_id 						=  	$request['centro_id'];
			$tipo_pago_id 					=  	$request['tipo_pago_id'];


			$respuesta 						=  	json_decode($request['xml_productos'], true);
			$prefijo 						=  	'1CH'; //queda pendiente logeo
			$vacio 							=  	'';
			$float_vacio 					=  	0.0000;
			$accion 						=  	'1';
			$orden_asignacion_estado 		=  	'1CH00014';
			$estado 						=  	1;
			$usuario_id						=  	Session::get('usuario')->IdUsuariaIsl;
			$indicador_aprobacion 			=  	1;
			$cero 							=  	0;
			$tipo_compra_id 				=  	'1PK000000001';
			$estado_orden_id 				=  	'1CH000000011';
		    $nulo 							=   Null;
			$fecha_hora 					= 	$this->fecha_hora;

	        
			// 	CMP.Isp_OrdenCompra_IAE
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_OrdenCompra_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@TIPOOPERACION='1',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 //@Prefijo='1SI',
	        $stmt->bindParam(3, $orden_compra_id ,PDO::PARAM_STR);                      	//@Id='1PI000008408',
	        $stmt->bindParam(4, $orden_compra_nroorden ,PDO::PARAM_STR);                    //@NroOrden='OCPI00008408',
	        $stmt->bindParam(5, $moneda_id ,PDO::PARAM_STR);                   				//@IdMoneda='1CH01'
	        $stmt->bindParam(6, $trabajador_id ,PDO::PARAM_STR);                  			//@IdTrabajador='1CH000000637',
	        $stmt->bindParam(7, $proveedor_id  ,PDO::PARAM_STR);                  			//@IdProveedor='1CH000012956',
	        $stmt->bindParam(8, $subtotal ,PDO::PARAM_STR);                                 //@SubTotal=1,
	        $stmt->bindParam(9, $impuesto ,PDO::PARAM_STR);                         		//@Impuesto=1.25,
	        $stmt->bindParam(10,$total ,PDO::PARAM_STR);                         			//@Total=102 

	        $stmt->bindParam(11, $vacio ,PDO::PARAM_STR);                              		//@FechaOrden='',
	        $stmt->bindParam(12, $vacio  ,PDO::PARAM_STR);                           		//@FechaEntrega='',
	        $stmt->bindParam(13, $vacio ,PDO::PARAM_STR);                     				//@FechaPago='',

	        $stmt->bindParam(14, $indicador_aprobacion ,PDO::PARAM_STR);                  	//@IndicadorAprobacion='1',
	        $stmt->bindParam(15, $tipo_compra_id ,PDO::PARAM_STR);                  		//@IdTipoOrdenCompra='OACH000010473'
	        $stmt->bindParam(16, $glosa ,PDO::PARAM_STR);                 					//@Glosa='1CH00014',
	        $stmt->bindParam(17, $notas  ,PDO::PARAM_STR);                 					//@Notas='EPSON L5190',
	        $stmt->bindParam(18, $estado ,PDO::PARAM_STR);                                 	//@Activo=1,
	        $stmt->bindParam(19, $estado_orden_id ,PDO::PARAM_STR);                         //@IdEstadoOrden='1CH000000338',
	        $stmt->bindParam(20, $centro_id ,PDO::PARAM_STR);                         		//@IdCentro='NULL' 

	        $stmt->bindParam(21, $tipo_pago_id ,PDO::PARAM_STR);                            //@IdTipoPago='1',
	        $stmt->bindParam(22, $cero  ,PDO::PARAM_STR);                                	//@IndicadorConsignacion='1SI',
	        $stmt->bindParam(23, $usuario_id ,PDO::PARAM_STR);                     			//@UsuarioCreacion='1CH000010473',
	        $stmt->bindParam(24, $cero ,PDO::PARAM_STR);                  					//@Percepcion='2019-10-18 15:57:47',
	        $stmt->bindParam(25, $cero ,PDO::PARAM_STR);                  					//@PercepcionPorc='OACH000010473'
	        $stmt->bindParam(26, $cero ,PDO::PARAM_STR);                 					//@Detraccion='1CH00014',
	        $stmt->bindParam(27, $cero  ,PDO::PARAM_STR);                 					//@DetraccionPorc='EPSON L5190',
	        $stmt->bindParam(28, $cero ,PDO::PARAM_STR);                                 	//@IndTipoCompra=1,
	        $stmt->bindParam(29, $cero ,PDO::PARAM_STR);                         			//@IndCotizacion='1CH000000338',
	        $stmt->bindParam(30, $nulo ,PDO::PARAM_STR);                         			//@IdViaje='NULL' 
	        $stmt->execute();




	        //CMP.Isp_OrdenCompraMaterial_IAE

			foreach($respuesta as $obj){

				$data_id_oc_material 				= 	$obj['data_id_oc_material'];
				$data_id_material 					= 	$obj['data_id_material'];
				$data_cantidad_material 			= 	(float)$obj['data_cantidad_material'];
				$data_costo_unitario 				= 	(float)$obj['data_costo_unitario'];
				$data_precio_total 					= 	(float)$obj['data_precio_total'];
				$data_id_sub_almacen 				= 	$obj['data_id_sub_almacen'];
				$data_cantidad_material_pendiente 	= 	(float)$obj['data_cantidad_material_pendiente'];

				$data_ind_igv 						= 	$obj['data_ind_igv'];
				$data_id_unidad_medida 				= 	$obj['data_id_unidad_medida'];
				$data_centro_costo 					= 	$obj['data_centro_costo'];
				$data_requerimiento_material 		= 	$obj['data_requerimiento_material'];
				$data_glosa 						= 	$obj['data_glosa'];
				$data_notas 						= 	$obj['data_notas'];
				$data_area 							= 	$obj['data_area'];


		        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_OrdenCompraMaterial_IAE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
		        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                       		//@TIPOOPERACION='1',
		        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                      		//@Prefijo='1SI',
		        $stmt->bindParam(3, $data_id_oc_material ,PDO::PARAM_STR);                  //@Id='1PI000014592',
		        $stmt->bindParam(4, $orden_compra_id ,PDO::PARAM_STR);                   	//@IdOrden='1CH000005093',
		        $stmt->bindParam(5, $data_id_material ,PDO::PARAM_STR);          			//@IdMaterial='1CH000010473'
		        $stmt->bindParam(6, $data_cantidad_material ,PDO::PARAM_STR);           	//@CantidadMaterial='A',
		        $stmt->bindParam(7, $data_costo_unitario  ,PDO::PARAM_STR);           		//@CostoUnitario='1CH000000006',
		        $stmt->bindParam(8, $data_precio_total ,PDO::PARAM_STR);                	//@PrecioTotal='1PY000000011',
		        $stmt->bindParam(9, $data_id_sub_almacen ,PDO::PARAM_STR);     				//@IdSubAlmacen=5.0000
		        $stmt->bindParam(10, $data_cantidad_material_pendiente ,PDO::PARAM_STR);    //@CantidadMaterialPendientes=0,
		        $stmt->bindParam(11, $data_ind_igv ,PDO::PARAM_STR);     					//@IndicadorIgv=1,
		        $stmt->bindParam(12, $estado  ,PDO::PARAM_STR);                				//@Activo=1,
		        $stmt->bindParam(13, $data_id_unidad_medida ,PDO::PARAM_STR);               //@IdUnidadMedida='1PY000000011',
		        $stmt->bindParam(14, $data_centro_costo ,PDO::PARAM_STR);                   //@IdCentroCosto=null,
		        $stmt->bindParam(15, $data_requerimiento_material ,PDO::PARAM_STR);         //@IdRequerimientoMaterial='1CH000000338'
		        $stmt->bindParam(16, $data_glosa ,PDO::PARAM_STR);                 			//@Glosa=0,
		        $stmt->bindParam(17, $data_notas  ,PDO::PARAM_STR);               			//@Notas=0,
		        $stmt->bindParam(18, $data_area ,PDO::PARAM_STR);                       	//@IdArea='',
		        $stmt->bindParam(19, $nulo ,PDO::PARAM_STR);                       			//@IdOrdenSalidaMaterial='',
		        $stmt->execute();



			}


			$accion 						=  	'I';
			$fechaaprobacion 				= 	$this->fin;
			$indicador_bloqueo				= 	0;

			// 	CMP.Isp_OrdenAprobacion_IAE
	        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.Isp_OrdenAprobacion_IAE ?,?,?,?,?,?,?,?,?,?');
	        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@TIPOOPERACION='I',
	        $stmt->bindParam(2, $prefijo  ,PDO::PARAM_STR);                                 //@Prefijo='1SI',
	        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                      				//@Id='',
	        $stmt->bindParam(4, $orden_compra_id ,PDO::PARAM_STR);                    		//@IdOrden='1PI000008408',
	        $stmt->bindParam(5, $trabajador_id ,PDO::PARAM_STR);                   			//@IdTrabajador='1CH000000637'
	        $stmt->bindParam(6, $fechaaprobacion ,PDO::PARAM_STR);                  		//@FechaAprobacion='2019-11-18',
	        $stmt->bindParam(7, $indicador_aprobacion  ,PDO::PARAM_STR);                  	//@IndAprobacion=1,
	        $stmt->bindParam(8, $indicador_bloqueo ,PDO::PARAM_STR);                        //@IndBloqueo=0,
	        $stmt->bindParam(9, $estado ,PDO::PARAM_STR);                         			//@Activo=1,
	        $stmt->bindParam(10,$usuario_id ,PDO::PARAM_STR);                         		//@UsuarioCreacion='1CH000000406' 
	        $stmt->execute();


			DB::commit();

	 		return Redirect::to('/gestion-de-orden-compra-materiales/'.$idopcion)->with('bienhecho', 'Orden de Compra '.$orden_compra_id.' se aprobo correctamente');

		}catch(Exception $ex){
			DB::rollback();
			return Redirect::to('/gestion-de-orden-compra-materiales/'.$idopcion)->with('errorbd', 'Ocurrio un error inesperado. Porfavor contacte con el administrador del sistema : '.$ex);	
		}




	}






	public function actionListarOrdenCompraMateriales($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		/******************* validar permiso aporbacion **********************/
		$proceso_id = '1CH000000025';
		$validarpermiso = $this->funciones->permiso_aprobacion_requerimiento($proceso_id);
	    if($validarpermiso <> 'true'){return $validarpermiso;}
	    /******************************************************/

	    $area_id 					= 	'';
	    $fecha_menos_siete_dias  	= 	$this->fecha_menos_siete_dias;
	    $fechafin  					= 	$this->fin;
	    $idtipoordencompra          = 	'1PK000000001';
		$listaordencompra 			= 	$this->funciones->lista_orden_compra($fecha_menos_siete_dias,$fechafin,$idtipoordencompra,$area_id);
		$funcion 					= 	$this;


		return View::make('ordencompra/listamateriales',
						 [
						 	'listaordencompra' 			=> $listaordencompra,
						 	'idopcion' 					=> $idopcion,
						 	'fecha_menos_siete_dias' 	=> $fecha_menos_siete_dias,
						 	'fechafin' 					=> $fechafin,
						 	'funcion' 					=> $funcion
						 ]);


	}



	public function actionAjaxListarOrdenCompraMateriales(Request $request)
	{

		$finicio 				=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 					=  date_format(date_create($request['ffin']), 'd-m-Y');
		$idopcion 				=  $request['idopcion'];

	    $area_id 				= 	'';
	    $idtipoordencompra      = 	'1PK000000001';
		$listaordencompra 		= 	$this->funciones->lista_orden_compra($finicio,$ffin,$idtipoordencompra,$area_id);
		$funcion 				= 	$this;	

		return View::make('ordencompra/ajax/alistamateriales',
						 [
						 	'listaordencompra' 	=> $listaordencompra,
						 	'ajax'   			=> true,
						 	'funcion' 			=> $funcion
						 ]);

	}




	public function actionAjaxMantenimientoOrdenCompraMateriales(Request $request)
	{

		$orden_compra_id 			=  $request['data_ioc'];
		$proveedor_id 				=  $request['data_proveedor'];
		$idopcion 					=  $request['idopcion'];


		$ordencompra 				=  $this->funciones->orden_compra($orden_compra_id);
		$proveedor 					=  $this->funciones->proveedor($proveedor_id);
		$listaordencompramaterial 	=  $this->funciones->lista_orden_compra_material($orden_compra_id);


		return View::make('ordencompra/ajax/mantenimientoordencompramaterial',
						 [
						 	'ordencompra' 					=> $ordencompra,
						 	'proveedor' 					=> $proveedor,
						 	'listaordencompramaterial' 		=> $listaordencompramaterial,
						 	'idopcion' 						=> $idopcion,
						 	'ajax'   						=> true,
						 ]);


	}







}
