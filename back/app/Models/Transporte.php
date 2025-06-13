<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'marca',
        'placa',
        'color',
        'estado',
        'user_id',
        'libre',
    ];
}
