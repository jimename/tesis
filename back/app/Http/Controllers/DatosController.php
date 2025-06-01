<?php

namespace App\Http\Controllers;

use App\Models\Contratista;
use App\Models\Driver;
use App\Models\Empresa;
use App\Models\Transporte;
use Illuminate\Http\Request;

class DatosController extends Controller
{
    function datos(){
        $empresasActivos = Empresa::where('estado', 'Activo')->get();
        $transportesActivos = Transporte::where('estado', 'Activo')->get();
        $contratistasActivo = Contratista::where('estado', 'Activo')->get();
        $driversActivo = Driver::where('estado', 'Activo')->get();
        return response()->json([
            'empresas' => $empresasActivos,
            'transportes' => $transportesActivos,
            'contratistas' => $contratistasActivo,
            'drivers' => $driversActivo,
        ]);
    }
}
