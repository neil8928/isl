<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuariosIsl extends Model
{
    protected $table = 'usuariosisls';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


}
