<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PERArea extends Model
{
    protected $table = 'PER.Area';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

    public function trabajador()
    {
        return $this->hasMany('App\WEBTrabajador', 'IdArea', 'id');
    }    

}
