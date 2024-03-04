<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBTrabajador extends Model
{
    protected $table = 'WEB.trabajadores';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function encuesta()
    {
        return $this->hasMany('App\WEBEncuesta', 'persona_id', 'id');
    }

    public function cargo()
    {
        return $this->belongsTo('App\STDCargo', 'IdCargo', 'id');
    }

    public function area()
    {
        return $this->belongsTo('App\PERArea', 'IdArea', 'id');
    }


}
