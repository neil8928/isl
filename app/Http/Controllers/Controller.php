<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Biblioteca\Funcion;
use DateTime;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $funciones;
	public $inicio;
	public $fin;
	public $fechaactual;

	public $ruc;
	public $usuario;
	public $password;


	public function __construct()
	{
	    $this->funciones = new Funcion();
		$fecha = new DateTime();
		$fecha->modify('first day of this month');

		//fecha actual -7 dias
		$fechasiete = date('Y-m-j');
		$nuevafecha = strtotime ( '-7 day' , strtotime($fechasiete));
		$nuevafecha = date ('Y-m-j' , $nuevafecha);

		//fecha actual -1 dias
		$fechasuno 		= date('Y-m-j');
		$nuevafechauno	= strtotime ( '-1 day' , strtotime($fechasuno));
		$nuevafechauno 	= date ('Y-m-j' , $nuevafechauno);



		$this->fecha_menos_siete_dias 	= date_format(date_create($nuevafecha), 'Y-m-d');
		$this->inicio 					= date_format(date_create($fecha->format('Y-m-d')), 'Y-m-d');

		$this->fechaactual 				= date('d-m-Y H:i:s');
		$this->fin 						= date_format(date_create(date('Y-m-d')), 'Y-m-d');
		$this->fecha_hora 				= date_format(date_create(date('Y-m-d')), 'Y-m-d H:m:s');
		$this->fecha_sin_hora 			= date('d-m-Y');

		$this->fecha_menos_uno 			= date_format(date_create(date('Y-m-d')), 'Y-m-d');


		$this->ruc = '20479729141';
		$this->usuario = 'SYST1NDU';
		$this->password = '1ndu4m3r1c@';


	}




}
