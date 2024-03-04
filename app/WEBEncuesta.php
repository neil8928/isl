<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBEncuesta extends Model
{
    protected $table = 'WEB.encuestas';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function trabajador()
    {
        return $this->belongsTo('App\WEBTrabajador', 'persona_id', 'id');
    }
    

}
