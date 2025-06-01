<?php

namespace App\Http\Controllers;

use App\Models\Contratista;
use App\Models\Empresa;
use App\Models\Tornaguia;
use App\Http\Requests\StoreTornaguiaRequest;
use App\Http\Requests\UpdateTornaguiaRequest;
use App\Models\Transporte;
use Illuminate\Http\Request;

class TornaguiaController extends Controller{
    function tornaguiasGet(Request $request){
        $fechaIni = $request->fechaIni;
        $fechaFin = $request->fechaFin;
        $tornaguias = Tornaguia::selectRaw('DATE(fecha) as fecha, count(*) as total')
            ->where('fecha', '>=', $fechaIni)
            ->where('fecha', '<=', $fechaFin)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        return $tornaguias;
    }
     public function tornaguiaSearch(Request $request){
         return Tornaguia::where('fecha', '>=', $request->fechaDesde)
             ->where('fecha', '<=', $request->fechaHasta)
             ->with('transporte')
             ->with('empresa')
             ->with('contratista')
             ->with('user')
             ->with('driver')
             ->orderBy('id','desc')
             ->get();
    }
    public function totales(){
        return response()->json([
            'totalTornaguias' => Tornaguia::count(),
            'totalTrasportes' => Transporte::count(),
            'totalContratistas' => Contratista::count(),
            'totalEmpresas' => Empresa::count(),
        ]);
    }

    public function index(){
        return Tornaguia::orderBy('id', 'desc')
            ->with('transporte')
            ->with('empresa')
            ->with('contratista')
            ->with('user')
            ->with('driver')
            ->orderBy('id','desc')
            ->get();
    }
    public function show($id){
        return Tornaguia::with('transporte')
            ->with('empresa')
            ->with('contratista')
            ->with('user')
            ->with('driver')
            ->find($id);
    }
    public function store(Request $request){
        $request->validate([
//            'fecha'=>'required',
//            'numero'=>'required',
//            'yacimiento'=>'required',
//            'tranca'=>'required',
//            'cuadrilla'=>'required',
//            'tipoMaterial'=>'required',
            'minerales'=>'required',
//            'peso'=>'required',
//            'sacos'=>'required',
//            'transporte_id'=>'required',
//            'empresa_id'=>'required',
//            'contratista_id'=>'required',
//            'user_id'=>'required',
//            'driver_id'=>'required',
        ]);
        $request->merge(['hora' => date('H:i:s', strtotime($request->hora))]);
        $tornaguia= Tornaguia::create($request->all());
        return Tornaguia::with('transporte')
            ->with('empresa')
            ->with('contratista')
            ->with('user')
            ->with('driver')
            ->find($tornaguia->id);
    }
    public function update(Request $request,$id){
        $tornaguia = Tornaguia::findOrFail($id);
        $tornaguia->update($request->all());
        return $tornaguia;
    }
    public function destroy($id){
        $tornaguia = Tornaguia::findOrFail($id);
        $tornaguia->delete();
        return $tornaguia;
    }
}
