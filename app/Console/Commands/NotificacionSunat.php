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
use App\Traits\GeneralesTraits;

class NotificacionSunat extends Command
{

    use GeneralesTraits;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:sunat';
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
        $this->sunatlista();
    }
}
