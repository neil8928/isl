<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PEROcupacionTrabajador extends Model
{
    protected $table = 'PER.OcupacionTrabajador';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

}
