<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBTiporespuesta extends Model
{
    protected $table = 'WEB.tiporespuestas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    
    public function pregunta()
    {
        return $this->hasMany('App\WEBPregunta', 'tiporespuesta_id', 'id');
    }


}
