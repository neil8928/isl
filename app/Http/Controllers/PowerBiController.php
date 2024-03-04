<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\User,App\Grupoopcion,App\Rol,App\RolOpcion,App\Opcion,App\Documento;
use View;
use Session;
use Hashids;


class PowerBiController extends Controller
{

	public function actionListarPowerby($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		return View::make('powerbi/powerbi');
	}


}
