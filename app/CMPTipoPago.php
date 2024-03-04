<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CMPTipoPago extends Model
{
    protected $table = 'CMP.TipoPago';
    public $timestamps=false;

    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $keyType = 'string';

}
