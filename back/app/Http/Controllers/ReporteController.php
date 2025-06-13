<?php

namespace App\Http\Controllers;

use App\Models\Tornaguia;
use App\Models\Contratista;
use App\Models\Empresa;
use App\Models\Driver;
use App\Models\Transporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

class ReporteController extends Controller
{
    public function mineralesPorAsociado(Request $request)
    {
        $query = Tornaguia::with('contratista')
            ->select('contratista_id', 'minerales', DB::raw('SUM(peso) as total_peso'))
            ->groupBy('contratista_id', 'minerales');

        if ($request->filled('fechaDesde') && $request->filled('fechaHasta')) {
            $query->whereBetween('fecha', [$request->fechaDesde, $request->fechaHasta]);
        }

        $result = $query->get()->map(function ($item) {
            return [
                'Asociado' => $item->contratista->nombre ?? '—',
                'Mineral' => $item->minerales,
                'Total Peso (Kg)' => number_format($item->total_peso, 2),
            ];
        });

        $headings = ['Asociado', 'Mineral', 'Total Peso (Kg)'];

        return $this->export($result, $request, 'minerales_asociado', $headings);
    }


    public function tornaguiasEmitidas(Request $request)
    {
        $query = Tornaguia::query();

        if ($request->filled('fechaDesde') && $request->filled('fechaHasta')) {
            $query->whereBetween('fecha', [$request->fechaDesde, $request->fechaHasta]);
        }

        return $this->export($query->get(), $request, 'tornaguias_emitidas');
    }

    public function almacenamientoTratamiento(Request $request)
    {
        $query = Tornaguia::select('minerales', 'fecha', 'tipo', 'estadoAdministrativo')
            ->whereNotNull('tipo');

        return $this->export($query->get(), $request, 'almacenamiento_tratamiento');
    }

    public function entregasComercializadoras(Request $request)
    {
        $query = Tornaguia::with('empresa')
            ->where('tipo', 'Comercializacion');

        return $this->export($query->get(), $request, 'entregas_comercializadoras');
    }

    public function vehiculosChoferes(Request $request)
    {
        $query = Tornaguia::with('transporte', 'driver')
            ->select('transporte_id', 'driver_id', 'fecha');

        return $this->export($query->get(), $request, 'vehiculos_choferes');
    }

    public function produccionMensual(Request $request)
    {
        $query = Tornaguia::select(DB::raw('MONTH(fecha) as mes'), DB::raw('YEAR(fecha) as anio'), 'minerales', DB::raw('SUM(peso) as total'))
            ->groupBy(DB::raw('MONTH(fecha)'), DB::raw('YEAR(fecha)'), 'minerales');

        return $this->export($query->get(), $request, 'produccion_mensual');
    }

    public function controlDocumental(Request $request)
    {
        $query = Tornaguia::whereNotNull('aprobado');

        return $this->export($query->get(), $request, 'control_documental');
    }

    public function consolidadoDireccion(Request $request)
    {
        $query = Tornaguia::select(DB::raw('SUM(peso) as total_peso'), DB::raw('SUM(sacos) as total_sacos'));

        return $this->export($query->get(), $request, 'reporte_consolidado');
    }

    private function export($data, Request $request, $filename)
    {
        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('reportes.' . $filename, ['data' => $data]);
            return $pdf->download("{$filename}.pdf");
        }

        return Excel::download(new GenericExport($data), "{$filename}.xlsx");
    }
}
