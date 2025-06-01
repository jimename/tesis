<?php

namespace App\Http\Controllers;

use App\Models\Contratista;
use App\Http\Requests\StoreContratistaRequest;
use App\Http\Requests\UpdateContratistaRequest;
use Illuminate\Http\Request;

class ContratistaController extends Controller
{
    public function index(){ return Contratista::orderBy('nombre')->get(); }
    public function show(Contratista $contratista){ return $contratista; }
    public function store(Request $request){ return Contratista::create($request->all()); }
    public function update(Request $request, $id){
        $contratista=Contratista::find($id);
        $contratista->update($request->all());
        return $contratista;
    }
    public function destroy($id){
        $contratista=Contratista::find($id);
        $contratista->delete();
        return $contratista;
    }
}
