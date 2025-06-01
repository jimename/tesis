<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(){ return Driver::orderBy('name')->get(); }
    public function show(Driver $drive){ return $drive; }
    public function store(Request $request){ return Driver::create($request->all()); }
    public function update(Request $request, $id){
        return Driver::find($id)->update($request->all());
    }
    public function destroy($id){
        $drive=Driver::find($id);
        $drive->delete();
        return $drive;
    }
}
