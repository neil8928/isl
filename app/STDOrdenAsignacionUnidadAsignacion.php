<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class STDOrdenAsignacionUnidadAsignacion extends Model
{
    protected $table = 'STD.OrdenAsignacion_UnidadAsignacion';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
