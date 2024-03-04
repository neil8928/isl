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

class NotificacionEnfermera extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:enfermera';
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


        $fecha_actual                   =   date("Y-m-d");
        $fecha_manana                   =   date("Y-m-d",strtotime($fecha_actual."+ 1 days"));
        $lista_encuesta                 =   WEBEncuesta::join('WEB.trabajadores', 'WEB.trabajadores.Id', '=', 'web.encuestas.persona_id')
                                            ->whereNotIn('WEB.trabajadores.centro_id', ['08','09'])
                                            ->where('web.encuestas.ind_enfermera','=',0)
                                            ->where('web.encuestas.fecha','>','2020-07-01')
                                            ->select('web.encuestas.*')
                                            ->get();

        $this->funciones                =   new Funcion();

        foreach($lista_encuesta as $item){

                // correos from(de)
            $emailfrom          =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
            // correos principales y  copias
            $email              =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00003')->first();


            $encuesta_id        =   Hashids::encode(substr($item->id, -8));

            $preguntas          =   WEBRespuestapersona::join('WEB.preguntarespuestas', 'WEB.respuestapersonas.preguntarespuesta_id', '=', 'WEB.preguntarespuestas.id')
                                    ->join('WEB.preguntas', 'WEB.preguntarespuestas.pregunta_id', '=', 'WEB.preguntas.id')
                                    ->join('WEB.respuestas', 'WEB.preguntarespuestas.respuesta_id', '=', 'WEB.respuestas.id')
                                    ->select('WEB.preguntas.*')
                                    ->where('WEB.respuestapersonas.encuesta_id','=',$item->id)
                                    ->where('WEB.preguntas.grupo','=','0003')
                                    ->where('WEB.respuestas.valoracion','=',1)
                                    ->get();

            $telefonos          =   STDTelefono::where('IdPersonaEmpresa','=',$item->persona_id)
                                    ->where('Activo','=',1)
                                    ->get();

            $array              =   Array(
                'encuesta'              =>  $item,
                'encuesta_id'           =>  $encuesta_id,
                'preguntas'             =>  $preguntas,
                'telefonos'             =>  $telefonos,
                'funcion'               =>  $this->funciones
            );

            Mail::send('emails.notificaciondoctor', $array, function($message) use ($emailfrom,$email,$item)
            {

                $emailprincipal     = explode(",", $email->correoprincipal);
                //$emailprincipal     = explode(",", $email->correoprincipal);
        
                $message->from($emailfrom->correoprincipal, 'El Trabajador '.$item->trabajador->NombreCompleto.' presenta sintomas.');

                if($email->correocopia<>''){
                    $emailcopias        = explode(",", ltrim(rtrim($email->correocopia)));
                    $message->to($emailprincipal)->bcc($emailcopias);
                }else{
                    $message->to($emailprincipal);                
                }
                $message->subject($email->descripcion);

            });

            $item->ind_enfermera       =   1;
            $item->save();
        }
                     
    }
}
