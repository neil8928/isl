<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class STDTelefono extends Model
{
    protected $table = 'STD.Telefono';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
