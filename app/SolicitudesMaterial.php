<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudesMaterial extends Model
{
    protected $table = 'solicitudesmateriales';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

}
