<?php

namespace App\Http\Controllers;

use App\Models\Contratista;
use App\Models\Empresa;
use App\Models\Tornaguia;
use App\Http\Requests\StoreTornaguiaRequest;
use App\Http\Requests\UpdateTornaguiaRequest;
use App\Models\Transporte;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class TornaguiaController extends Controller{
    public function vistaWeb($id) {
        $tg = Tornaguia::with(['transporte', 'empresa', 'contratista', 'user', 'driver'])->findOrFail($id);
        return view('tornaguia.vista', compact('tg'));
    }
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
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'nullable|date',
            'hora' => 'nullable',
            'departamento' => 'nullable|string|max:100',
            'centroMinero' => 'nullable|string|max:100',
            'yacimiento' => 'nullable|string|max:255',
            'tranca' => 'nullable|string|max:255',
            'trancaSalida' => 'nullable|string|max:255',
            'cuadrilla' => 'nullable|string|max:255',
            'tipoMaterial' => 'nullable|string|max:100',
            'minerales' => 'nullable|string',
            'peso' => 'nullable|numeric',
            'sacos' => 'nullable|integer',
            'cantidad' => 'nullable|integer',
            'lote' => 'nullable|string|max:100',
            'broza' => 'nullable|string|max:255',
            'nit' => 'nullable|string|max:100',
            'comprador' => 'nullable|string|max:255',
            'destino' => 'nullable|string|max:255',
            'empresa_id' => 'nullable|exists:empresas,id',
            'contratista_id' => 'nullable|exists:contratistas,id',
            'transporte_id' => 'nullable|exists:transportes,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Asegurar formato correcto de hora
        $request->merge([
            'hora' => date('H:i:s', strtotime($request->hora))
        ]);

        // Crear la tornaguía
        $tornaguia = Tornaguia::create($request->all());

        // Devolver con relaciones cargadas
        return Tornaguia::with([
            'transporte',
            'empresa',
            'contratista',
            'user',
            'driver'
        ])->find($tornaguia->id);
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
    public function imprimir($id)
    {
        $tg = Tornaguia::with(['empresa', 'contratista', 'transporte', 'driver', 'user'])->findOrFail($id);
        $url = env('APP_URL') . '/api/tornaguia/vista/' . $tg->id;

        // 🟢 Generar imagen PNG del QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->size(150)
            ->build();

        // 🖼️ Convertir a base64 para insertarlo en el PDF
        $qr = 'data:image/png;base64,' . base64_encode($result->getString());

        $logo1 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo.png')));
        $logo2 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('logo2.jpg')));

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('tornaguia.print', compact('tg', 'qr', 'logo1', 'logo2'))
            ->stream('tornaguia-'.$tg->numero.'.pdf');
    }
}
