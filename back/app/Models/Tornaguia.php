<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tornaguia extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'fecha',
        'numero',
        'hora',
        'departamento',
        'centroMinero',
        'yacimiento',
        'tranca',
        'trancaSalida',
        'cuadrilla',
        'tipoMaterial',
        'minerales',
        'peso',
        'sacos',
        'cantidad',
        'lote',
        'broza',
        'nit',
        'comprador',
        'destino',
        'transporte_id',
        'empresa_id',
        'contratista_id',
        'user_id',
        'driver_id',
    ];
    public function transporte(){ return $this->belongsTo(Transporte::class); }
    public function empresa(){ return $this->belongsTo(Empresa::class); }
    public function contratista(){ return $this->belongsTo(Contratista::class); }
    public function user(){ return $this->belongsTo(User::class); }
    public function driver(){ return $this->belongsTo(Driver::class); }
}
