<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class STDCargo extends Model
{
    protected $table = 'STD.Cargo';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

    public function trabajador()
    {
        return $this->hasMany('App\WEBTrabajador', 'IdCargo', 'id');
    }   

}
