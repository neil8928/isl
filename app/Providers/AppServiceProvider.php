<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use View;
use Illuminate\Support\Facades\DB;
use App\User,App\WEBGrupoopcion,App\WEBRol,App\WEBRolOpcion,App\WEBOpcion;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        /*Validator::extend('usuario_encontrado', function($attribute, $value, $parameters) {
            return $value;
        });﻿*/

        Schema::defaultStringLength(191);    
        View::share('capeta', '/isl');  
        View::share('version', '57');  


        Validator::extend('unico_menos', function($attribute, $value, $parameters , $validator){

            $tabla      = $parameters[0].'.'.$parameters[1];
            $attr       = $parameters[2];
            $valor      = $parameters[3];

            $count  = DB::table($tabla)->where($attribute,'=',$value)->where($attr,'<>',$valor)->count();

            if( $count > 0 ){
                return false;
            }else{
                return true;
            }

        });

        Validator::extend('unico', function($attribute, $value, $parameters , $validator){

            $tabla = $parameters[0].'.'.$parameters[1];
            $count  = DB::table($tabla)->where($attribute,'=',$value)->count();
            if( $count > 0 ){
                return false;
            }else{
                return true;
            }

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
