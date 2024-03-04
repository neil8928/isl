<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ALMUnidadMedida extends Model
{
    protected $table = 'ALM.UnidadMedida';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
