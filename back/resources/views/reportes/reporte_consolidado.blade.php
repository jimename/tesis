<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Consolidado Dirección</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .titulo { text-align: center; font-weight: bold; font-size: 16px; margin-top: 20px; }
        .info { margin-top: 40px; text-align: center; }
    </style>
</head>
<body>
<div class="titulo">Reporte General Consolidado para la Dirección</div>
<div class="info">
    <p><strong>Cantidad Total de Mineral Procesado:</strong> {{ $data[0]['total_peso'] ?? 0 }} Kg</p>
    <p><strong>Cantidad Total de Sacos:</strong> {{ $data[0]['total_sacos'] ?? 0 }}</p>
</div>
</body>
</html>
