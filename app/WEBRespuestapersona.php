<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBRespuestapersona extends Model
{
    protected $table = 'WEB.respuestapersonas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function preguntarespuesta()
    {
        return $this->belongsTo('App\WEBPreguntarespuesta', 'preguntarespuesta_id', 'id');
    }




}
