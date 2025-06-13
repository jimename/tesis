<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Almacenamiento y Tratamiento</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .titulo { text-align: center; font-size: 16px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
<div class="titulo">Reporte - Almacenamiento y Tratamiento</div>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Fecha</th>
        <th>Minerales</th>
        <th>Tipo</th>
        <th>Estado Administrativo</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->fecha }}</td>
            <td>{{ $item->minerales }}</td>
            <td>{{ $item->tipo }}</td>
            <td>{{ $item->estadoAdministrativo }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
