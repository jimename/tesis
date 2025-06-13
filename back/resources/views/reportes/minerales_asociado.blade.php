<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Minerales por Asociado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="titulo">Reporte - Minerales por Asociado</div>

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
    @foreach ($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['Asociado'] }}</td>
            <td>{{ $item['Mineral'] }}</td>
            <td>{{ $item['Total Peso (Kg)'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
