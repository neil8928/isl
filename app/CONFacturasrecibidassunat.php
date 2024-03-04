<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CONFacturasrecibidassunat extends Model
{
    protected $table = 'CON.FacturasRecibidasSunat';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
