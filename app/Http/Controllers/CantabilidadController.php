<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\User,App\Grupoopcion,App\Rol,App\RolOpcion,App\Opcion,App\Documento;

use App\RespaldoDocSunat;

use View;
use Session;
use Hashids;
use ZipArchive;
use App\Biblioteca\BotTest;
use App\CONFacturasrecibidassunat;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Traits\GeneralesTraits;

class CantabilidadController extends Controller
{

    use GeneralesTraits;

	public function actionGuardarXmlPdfSunat()
	{
		$this->sunatarchivos();
	}

	public function actionGuardarListaContabilidadSunat()
	{

		$this->sunatlista();
	}


	public function actionAjaxListarFacturasEntreFechas(Request $request)
	{


		$finicio 		=  date_format(date_create($request['finicio']), 'Y-m-d');
		$ffin 			=  date_format(date_create($request['ffin']), 'Y-m-d');

	   	$bot = new BotTest();
		$bot->setUp($this->ruc,$this->usuario,$this->password);

		$finiciosunat 	= date_format(date_create($finicio), 'd/m/Y');
		$ffinsunat 		= date_format(date_create($ffin), 'd/m/Y');


		$listadocumento = $bot->testGetList($finiciosunat,$ffinsunat);
		$funcion 		= 	$this;

		//dd($listadocumento);
		return View::make('contabilidad/ajax/listafacturas',
						 [
						 	'listadocumento' 	=> $listadocumento,
						 	'funcion' 			=> $funcion,
						 	'ajax'  			=> true,
						 ]);

	}

	public function actionListarDocumentoProveedorSunat($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    if (Session::get('xmlmsj')){

	    	$obj = json_decode(Session::get('xmlmsj'));
	    	$pfi = array_search('FE', array_column($obj, 'tipo'));
	    	$pff = array_search('FE', array_column($obj, 'tipo'));
            $finicio = $obj[$pfi]->data_0;
            $ffin = $obj[$pff]->data_1;

	    }else{

		    $finicio 	= $this->fin;
		    $ffin 		= $this->fin;

	    }

	   	$bot = new BotTest();
		$bot->setUp($this->ruc,$this->usuario,$this->password);




		$finiciosunat 	= date_format(date_create($finicio), 'd/m/Y');
		$ffinsunat 		= date_format(date_create($ffin), 'd/m/Y');
		$listadocumento = $bot->testGetList($finiciosunat,$ffinsunat);

		//dd($listadocumento);

		$funcion 		= 	$this;

		return View::make('contabilidad/listadocumentosunat',
						 [
						 	'listadocumento' 	=> $listadocumento,
						 	'inicio' 			=> $finicio,
						 	'fin' 				=> $ffin,
						 	'idopcion' 			=> $idopcion,
						 	'funcion' 			=> $funcion,
						 ]);


	}


	public function actionDescargarFactura($idopcion,Request $request)
	{
	
		if($_POST)
		{

			$msjarray  			= array();
			$respuesta 			= json_decode($request['factura'], true);

			$finicio 			= $request['fechainicio'];
			$fechafin 			= $request['fechafin'];
			$conts 				= 0;

			/************ lista de id seleccionadas *******/
	        $listaid  	= array();
	        $i   		= 0;	

	   		$bot = new BotTest();
			$bot->setUp($this->ruc,$this->usuario,$this->password);

			foreach($respuesta as $obj){

				//try {

					$ruc = trim($obj['ruc']);
					$serie = trim($obj['serie']);
					$nro = trim($obj['nro']);
					$fecha = trim($obj['fecha']);
					$rz = trim($obj['rz']);
					$moneda = trim($obj['moneda']);
					$importe = trim($obj['importe']);
					$importe = str_replace("S/","",$importe);
					$importe = (float)str_replace(",","",$importe);
					$rz = str_replace($ruc." - ","",$rz);
					$fecha_cortar = substr($fecha, 3, 2);
					$mes = (int)$fecha_cortar;

					$nro_archivo = $this->funciones->getcodigofactura($nro);
					$nombre_archivo = $ruc.'-01-'.$serie.'-'.$nro_archivo.'.xml';
					$bot->testGetXmlFac($ruc,$serie,$nro,$nombre_archivo);
				    $conts 	= 	$conts + 1;

					$nombre_xml = $ruc.'-01-'.$serie.'-'.$nro_archivo;
				    //crear el archivo zip
					// Creamos un instancia de la clase ZipArchive
					$zip = new ZipArchive();
					// Creamos y abrimos un archivo zip temporal
					$zip->open("facturas/".$nombre_xml.".zip",ZipArchive::CREATE);
					 // Añadimos un directorio
					$dir = 'facturas';
					$zip->addFile($dir."/".$nombre_xml.".xml",$nombre_xml.".xml");
					$zip->close();


				    $anio = date("Y");
				    $mes  = $mes;
					$destino = "//10.1.0.20/bkcontafls$/CPEClientes/FACTURA/".$anio."/".$mes."/";
					$origen  = 'facturas/'.$nombre_xml.'.zip';
					$nombreArchivo = $nombre_xml.".zip";
					$pathFinal = $destino . $nombreArchivo;
					if(!file_exists($pathFinal)) {
					    rename($origen , $pathFinal);
					}

					$facturas_recibidas         =   CONFacturasrecibidassunat::where('Serie','=',$serie)
													->where('Numero','=',$nro_archivo)->first();

					if(count($facturas_recibidas)<=0){

						$id 					 	= 	$this->funciones->getCreateIdMaestra('CON.FacturasRecibidasSunat');
						$cabecera 					=  	new CONFacturasrecibidassunat;
						$cabecera->Id 				=  	$id;
						$cabecera->Ruc 				=  	$ruc;
						$cabecera->RazonSocial 		=  	$rz;
						$cabecera->FechaEmision 	=  	$fecha;
						$cabecera->TipoDoc      	= 	'01';
						$cabecera->Serie 			=  	$serie;
						$cabecera->Numero 			= 	$nro_archivo;
						$cabecera->Moneda 			= 	$moneda;
						$cabecera->Total 			= 	$importe;
						$cabecera->Estado 			= 	"GENERADO";
						$cabecera->Ind_anulado 		= 	'0';
						$cabecera->NumeroIdXml 		= 	'-';
						$cabecera->Fechareacion     = 	$this->fecha_sin_hora;
						$cabecera->UsuarioCreacion  =  	Session::get('usuario')->IdUsuariaIsl;
						$cabecera->activo 			=  	1;
						$cabecera->save();

					}


				/*}catch(\Exception $e){

					$msjarray[] = array("data_0" => $serie, 
										"data_1" => 'comprobante contienen errores '. $e->getMessage(), 
										"tipo" => 'D');
					$contd 		= 	  1;
				}*/

			}


	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'documentos descargados', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => 0, 
	    						"data_1" => 'documentos rechazados', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => 0, 
	    						"data_1" => 'documentos errados', 
	    						"tipo" => 'TD');


			$msjarray[] = array("data_0" => $finicio, 
								"data_1" => $fechafin, 
								"tipo" => 'FE');

			$msjjson = json_encode($msjarray);

			return Redirect::to('/documento-proveedor-sunat/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}






}
