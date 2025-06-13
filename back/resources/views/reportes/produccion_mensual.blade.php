<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Producción Mensual</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .titulo { text-align: center; font-size: 16px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
<div class="titulo">Reporte - Producción Mensual</div>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Mes</th>
        <th>Año</th>
        <th>Mineral</th>
        <th>Total Peso</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->mes }}</td>
            <td>{{ $item->anio }}</td>
            <td>{{ $item->minerales }}</td>
            <td>{{ $item->total }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
