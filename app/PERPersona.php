<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PERPersona extends Model
{
    protected $table = 'PER.Persona';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
