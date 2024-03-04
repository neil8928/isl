<?php

namespace App\Console;

use App\Console\Commands\NotificacionDoctor;
use App\Console\Commands\NotificacionEnfermera;
use App\Console\Commands\NotificacionEnfermeraZona;
use App\Console\Commands\NotificacionTamizajeFinalDia;
use App\Console\Commands\NotificacionTamizajeFinalDiaRioja;
use App\Console\Commands\NotificacionTamizajeFinalDiaBellavista;
use App\Console\Commands\NotificacionTamizajeFinalDiaIsl;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        NotificacionDoctor::class,
        NotificacionEnfermera::class,
        NotificacionEnfermeraZona::class,
        NotificacionTamizajeFinalDia::class,
        NotificacionTamizajeFinalDiaRioja::class,
        NotificacionTamizajeFinalDiaBellavista::class,
        NotificacionTamizajeFinalDiaIsl::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('notificacion:doctor')->everyMinute(); // CADA MINUTO
        $schedule->command('notificacion:enfermera')->everyMinute(); // CADA MINUTO
        $schedule->command('notificacion:enfermerazona')->everyMinute(); // CADA MINUTO*/

        //LIMA Y CHICLAYO
        $schedule->command('notificacion:tamizajefinal')->dailyAt('09:30');
        $schedule->command('notificacion:tamizajefinal')->dailyAt('19:30');
        //RIOJA
        $schedule->command('notificacion:tamizajefinalrioja')->dailyAt('09:40');
        $schedule->command('notificacion:tamizajefinalrioja')->dailyAt('19:40');
        //BELLAVISTA
        $schedule->command('notificacion:tamizajefinalbellavista')->dailyAt('09:50');
        $schedule->command('notificacion:tamizajefinalbellavista')->dailyAt('19:50');
        //ISL
        $schedule->command('notificacion:tamizajefinalisl')->dailyAt('10:00');
        $schedule->command('notificacion:tamizajefinalisl')->dailyAt('20:00');


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
