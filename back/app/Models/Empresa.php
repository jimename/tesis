<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'ciudad',
        'departamento',
        'telefono',
        'logo',
        'nit',
        'encabezado',
        'estado',
        'id_usuario',
    ];
}
