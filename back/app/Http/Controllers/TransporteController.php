<?php

namespace App\Http\Controllers;

use App\Models\Transporte;
use App\Http\Requests\StoreTransporteRequest;
use App\Http\Requests\UpdateTransporteRequest;
use Illuminate\Http\Request;

class TransporteController extends Controller{
    public function index(){ return Transporte::orderBy('placa')->get(); }
    public function show(Transporte $transporte){ return $transporte; }
    public function store(Request $request){ return Transporte::create($request->all()); }
    public function update(Request $request, Transporte $transporte){ $transporte->update($request->all()); return $transporte; }
    public function destroy(Transporte $transporte){ $transporte->delete(); return $transporte; }
}
