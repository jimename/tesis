<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Illuminate\Http\Request;

class EmpresaController extends Controller{
    public function index(){ return Empresa::orderBy('nombre')->get(); }
    public function show(Empresa $empresa){ return $empresa; }
    public function store(Request $request){ return Empresa::create($request->all()); }
    public function update(Request $request, Empresa $empresa){ $empresa->update($request->all()); return $empresa; }
    public function destroy(Empresa $empresa){ $empresa->delete(); return $empresa; }
}
