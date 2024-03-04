<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPreguntarespuesta extends Model
{
    protected $table = 'WEB.preguntarespuestas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function pregunta()
    {
        return $this->belongsTo('App\WEBPregunta', 'pregunta_id', 'id');
    }
    public function respuesta()
    {
        return $this->belongsTo('App\WEBRespuesta', 'respuesta_id', 'id');
    }
    
    public function respuestapersona()
    {
        return $this->hasMany('App\WEBRespuestapersona', 'preguntarespuesta_id', 'id');
    }

}
