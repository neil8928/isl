<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPregunta extends Model
{
    protected $table = 'WEB.preguntas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    

    public function tiporespuesta()
    {
        return $this->belongsTo('App\WEBTiporespuesta', 'tiporespuesta_id', 'id');
    }


    public function preguntarespuesta()
    {
        return $this->hasMany('App\WEBPreguntarespuesta', 'pregunta_id', 'id');
    }



}
