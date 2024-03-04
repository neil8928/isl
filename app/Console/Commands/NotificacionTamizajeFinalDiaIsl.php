<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\WEBMaestro;
use App\WEBEncuesta;
use App\WEBRespuestapersona;
use App\STDTelefono;
use Mail;
use Hashids;
use App\Biblioteca\Funcion;
use Maatwebsite\Excel\Facades\Excel;

class NotificacionTamizajeFinalDiaIsl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:tamizajefinalisl';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        //fecha actual -1 dias
        $fechasuno          = date('Y-m-j');
        $nuevafechauno      = strtotime ( '-1 day' , strtotime($fechasuno));
        $nuevafechauno      = date ('Y-m-j' , $nuevafechauno);
        $fecha_menos_uno    = date_format(date_create($nuevafechauno), 'Y-m-d');
        $fecha_menos_uno    = date_format(date_create(date('Y-m-d')), 'Y-m-d');

            // correos from(de)
        $emailfrom          =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
        // correos principales y  copias
        $email              =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00008')->first();

        $nombre_archivo     =   'Tamizaje'.$fecha_menos_uno.'.xls';
        $file               =   storage_path(). "/exports/".$nombre_archivo;

        $array              =   Array();

        //file_get_contents('http://localhost:81/isl/tamizaje-del-dia');
        file_get_contents('http://10.1.50.2:8080/isl/tamizaje-del-dia-isl');

        Mail::send('emails.notificaciontamizaje', $array, function($message) use ($emailfrom,$email,$file,$nombre_archivo)
        {

            $emailprincipal     = explode(",", $email->correoprincipal);

            $message->from($emailfrom->correoprincipal, 'Tamizaje Resumido')->attach($file, [
                    'as' => $nombre_archivo,
                    'mime' => 'application/xls',
                ]);

            if($email->correocopia<>''){
                $emailcopias        = explode(",", ltrim(rtrim($email->correocopia)));
                $message->to($emailprincipal)->bcc($emailcopias);
            }else{
                $message->to($emailprincipal);                
            }
            $message->subject($email->descripcion);

        });




                     
    }
}
