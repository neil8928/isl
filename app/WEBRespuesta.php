<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBRespuesta extends Model
{
    protected $table = 'WEB.respuestas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function preguntarespuesta()
    {
        return $this->hasMany('App\WEBPreguntarespuesta', 'respuesta_id', 'id');
    }

}
