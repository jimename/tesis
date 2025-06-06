<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratista extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'telefono',
        'celular',
        'direccion',
        'ci',
        'fechaSocio',
        'estado',
        'user_id',
        'cuadrilla'
    ];
}
