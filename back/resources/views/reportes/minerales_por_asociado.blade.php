<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte - Minerales por Asociado</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #e0e0e0; }
    </style>
</head>
<body>
<h2>Reporte - Minerales por Asociado</h2>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Asociado</th>
        <th>Mineral</th>
        <th>Total Peso (Kg)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->contratista->nombre ?? 'Sin nombre' }}</td>
            <td>{{ $item->minerales }}</td>
            <td>{{ number_format($item->total_peso, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
