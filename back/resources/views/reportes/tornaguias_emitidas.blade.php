<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Tornaguías Emitidas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .titulo { text-align: center; font-size: 16px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
<div class="titulo">Reporte - Tornaguías Emitidas</div>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Número</th>
        <th>Fecha</th>
        <th>Tipo Material</th>
        <th>Minerales</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->numero }}</td>
            <td>{{ $item->fecha }}</td>
            <td>{{ $item->tipoMaterial }}</td>
            <td>{{ $item->minerales }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
