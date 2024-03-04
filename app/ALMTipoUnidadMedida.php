<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ALMTipoUnidadMedida extends Model
{
    protected $table = 'ALM.TipoUnidadMedida';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
