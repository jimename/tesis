<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tornaguia extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'numero',
        'yacimiento',
        'tranca',
        'peso',
        'sacos',
        'cuadrilla',
        'tipoMaterial',
        'minerales',
        'transporte_id',
        'empresa_id',
        'contratista_id',
        'user_id',
        'driver_id',
        'hora'
    ];
    public function transporte(){ return $this->belongsTo(Transporte::class); }
    public function empresa(){ return $this->belongsTo(Empresa::class); }
    public function contratista(){ return $this->belongsTo(Contratista::class); }
    public function user(){ return $this->belongsTo(User::class); }
    public function driver(){ return $this->belongsTo(Driver::class); }
}
