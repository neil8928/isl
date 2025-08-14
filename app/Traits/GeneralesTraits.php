<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use App\User;

use App\RespaldoDocSunat;


use View;
use Session;
use Hashids;
Use Nexmo;
use Keygen;
use Storage;
use File;
use ZipArchive;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Biblioteca\BotTest;

trait GeneralesTraits
{

	private function sunatlista() {

		$ruc = '20479729141';
		$usuario = 'SYST1NDU';
		$password='1ndu4m3r1c@';
		$ffin = date('Y-m-d');
		// 4 días antes de hoy
		$finicio = date('Y-m-d', strtotime('-4 days'));


	    $bot = new BotTest();
	    $bot->setUp($this->ruc, $this->usuario, $this->password);

	    $start = new DateTime($finicio);
	    $end   = new DateTime($ffin);
	    $end->modify('+1 day'); // Para incluir el último día

	    $interval = new DateInterval('P1D'); // Intervalo de 1 día
	    $period   = new DatePeriod($start, $interval, $end);

	    foreach ($period as $date) {
	        $fechaActual = $date->format('d/m/Y');
	        
	        // Obtener documentos de la fecha actual
	        $listadocumento = $bot->testGetList($fechaActual, $fechaActual);

	        foreach ($listadocumento as $item) {
	            $respaldo = RespaldoDocSunat::where('TipoDocumento', '=', '01')
	                ->where('Serie', '=', $item->nroSerie)
	                ->where('Numero', '=', $item->nroFactura)
	                ->where('FechaEmision', '=', $item->fechaEmisionDesc)
	                ->first();

	            if (!$respaldo) {
	                $dcontrol = new RespaldoDocSunat;
	                $dcontrol->TipoDocumento     = '01';
	                $dcontrol->Serie             = $item->nroSerie;
	                $dcontrol->Numero            = $item->nroFactura;
	                $dcontrol->FechaEmision      = $item->fechaEmisionDesc;
	                $dcontrol->Proveedor         = $item->nroRucEmisorDesc;
	                $dcontrol->Total             = $item->importeTotalDesc;
	                $dcontrol->Indpdf            = 0;
	                $dcontrol->Indxml            = 0;
	                $dcontrol->UsuarioCreacion   = 'SISTEMAS';
	                $dcontrol->save();
	            }
	        }
	    }


	}

	private function sunatarchivos() {


		$hoy = date('d/m/Y');
		// 4 días antes de hoy, en formato d/m/Y
		$finicio = date('d/m/Y', strtotime('-4 days'));

		$documentos 	=   RespaldoDocSunat::where(function ($query) {
					        $query->where('indpdf', 0)
					              ->orWhere('indxml', 0);
						    })
							->where('FechaEmision','>=',$finicio)
							->where('FechaEmision','<=',$hoy )
						    ->orderBy('FechaEmision', 'asc')
						    ->get();


		$fetoken 		= 	DB::table('FE_TOKEN')->first();

		foreach($documentos as $index=>$item){

			$indpdf = $item->indpdf;
			$indxml = $item->indxml;
			$fechaemision = $item->FechaEmision;


			$texto  = $item->Proveedor;
			list($ruc, $proveedor) = explode(' - ', $texto);
			$ruc 	= trim($ruc);
			$serie 	= trim($item->Serie);
			$correlativo 	= trim($item->Numero);
			$td 	= '01';

			if($indxml==0){
				$urlxml 					= 	'https://api-cpe.sunat.gob.pe/v1/contribuyente/consultacpe/comprobantes/'.$ruc.'-'.$td.'-'.$serie.'-'.$correlativo.'-2/02';
				$respuetaxml 				=	$this->buscar_archivo_sunat($urlxml,$fetoken);

				if($respuetaxml['cod_error'] == '0'){

					$response_array 	= 	json_decode($respuetaxml['archivo'], true);
			        $fileName 			= 	$response_array['nomArchivo'];
			        $base64File 		= 	$response_array['valArchivo'];
					$anio 				= 	substr($fechaemision, 0, 4);  // Extrae desde el índice 0, 4 caracteres (año)
					$mes 				= 	substr($fechaemision, 5, 2);   // Extrae desde el índice 5, 2 caracteres (me)
					//$rutafile 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio;
					$rutafile        =      '\\\\10.1.0.20\\bkcontafls$\\CPEClientes\\FACTURASUNAT\\'.$anio;
                    $valor           	=    $this->versicarpetanoexiste($rutafile);
					$rutafile 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio."/".$mes;
                    $valor           	=    $this->versicarpetanoexiste($rutafile);
					$destino 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio."/".$mes."/";
					// Asegúrate que la carpeta destino exista, si no, la creas
					if (!file_exists($destino)) {
					    mkdir($destino, 0777, true); // true para crear directorios recursivamente
					}
					// Decodificamos el contenido base64
					$fileData = base64_decode($base64File);
					// Guardamos el archivo
					$filePath = $destino . $fileName;
					file_put_contents($filePath, $fileData);
					//print_r("xml");

					DB::table('RespaldoDocSunat')
					    ->where('Proveedor', $texto)
					    ->where('Serie', $serie)
					    ->where('Numero', $correlativo)
					    ->where('TipoDocumento', '01')
					    ->update([
					        'rutaxml' => $filePath,
					        'indxml' => 1
					    ]);



				}

			}
			if($indpdf==0){

				$urlxml 					= 	'https://api-cpe.sunat.gob.pe/v1/contribuyente/consultacpe/comprobantes/'.$ruc.'-'.$td.'-'.$serie.'-'.$correlativo.'-2/01';
				$respuetapdf 				=	$this->buscar_archivo_sunat($urlxml,$fetoken);

				if($respuetapdf['cod_error'] == '0'){

					$response_array 	= 	json_decode($respuetapdf['archivo'], true);
			        $fileName 			= 	$response_array['nomArchivo'];
			        $base64File 		= 	$response_array['valArchivo'];
					$anio 				= 	substr($fechaemision, 0, 4);  // Extrae desde el índice 0, 4 caracteres (año)
					$mes 				= 	substr($fechaemision, 5, 2);   // Extrae desde el índice 5, 2 caracteres (me)
					//$rutafile 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio;
					$rutafile        =      '\\\\10.1.0.20\\bkcontafls$\\CPEClientes\\FACTURASUNAT\\'.$anio;
                    $valor           	=    $this->versicarpetanoexiste($rutafile);
					$rutafile 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio."/".$mes;
                    $valor           	=    $this->versicarpetanoexiste($rutafile);
					$destino 			= 	"//10.1.0.20/bkcontafls$/CPEClientes/FACTURASUNAT/".$anio."/".$mes."/";
					// Asegúrate que la carpeta destino exista, si no, la creas
					if (!file_exists($destino)) {
					    mkdir($destino, 0777, true); // true para crear directorios recursivamente
					}
					// Decodificamos el contenido base64
					$fileData = base64_decode($base64File);
					// Guardamos el archivo
					$filePath = $destino . $fileName;
					file_put_contents($filePath, $fileData);
					//print_r("pdf");
					DB::table('RespaldoDocSunat')
					    ->where('Proveedor', $texto)
					    ->where('Serie', $serie)
					    ->where('Numero', $correlativo)
					    ->where('TipoDocumento', '01')
					    ->update([
					        'rutapdf' => $filePath,
					        'indpdf' => 1
					    ]);

				}

			}


		}


	}

	private function versicarpetanoexiste($ruta) {
		$valor = false;
		if (!file_exists($ruta)) {
		    mkdir($ruta, 0777, true);
		    $valor=true;
		}
		return $valor;
	}

	private function buscar_archivo_sunat($urlxml,$fetoken) {

		$array_nombre_archivo = array();
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $urlxml,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer '.$fetoken->TOKEN_MASIVO
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response_array = json_decode($response, true);
		if (!isset($response_array['nomArchivo'])) {
			$array_nombre_archivo = [
				'cod_error' => 1,
				'nombre_archivo' => '',
				'mensaje' => 'Hubo un problema de sunat buscar nuevamente'
			];
		}else{
	        $fileName = $response_array['nomArchivo'];
	        $base64File = $response_array['valArchivo'];
			$array_nombre_archivo = [
				'cod_error' => 0,
				'nombre_archivo' => $response_array['nomArchivo'],
				'archivo' => $response,
				'mensaje' => 'encontrado con exito'
			];
		}

	 	return  $array_nombre_archivo;

	}


}