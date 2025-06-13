<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }
        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            color: #0a8b4c;
        }
        .subtitulo {
            text-align: center;
            font-size: 12px;
            margin-top: -5px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .header-table td {
            border: none;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo {
            width: 80px;
        }
        .sin-borde td {
            border: none;
        }
        .boldRed {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td class="header-logo">
            <img src="{{ $logo1 }}" alt="Logo Izquierdo" width="70">
        </td>
        <td>
            <div class="titulo">TORNA - GUIA PARA TRANSPORTE DE MINERALES</div>
            <div class="subtitulo">Personería Jurídica 104/08 - Testimonio N° 457/2020</div>
            <div class="boldRed">Numero de Tornaguia: {{ $tg->numero }}</div>
        </td>
        <td class="header-logo">
            <img src="{{ $logo2 }}" alt="Logo Derecho" width="70">
        </td>
    </tr>
</table>

<table>
    <tr>
        <td><strong>Fecha:</strong> {{ $tg->fecha }}</td>
        <td><strong>Departamento:</strong> {{ $tg->departamento }}</td>
        <td><strong>Centro minero:</strong> {{ $tg->centroMinero }}</td>
        <td><strong>Yacimiento:</strong> {{ $tg->yacimiento }}</td>
    </tr>
    <tr>
        <td><strong>Tranca de Salida:</strong> {{ $tg->trancaSalida }}</td>
        <td colspan="3"><strong>Cuadrilla:</strong> {{ $tg->cuadrilla }}</td>
    </tr>
    <tr>
        <td><strong>Medio de transporte:</strong> {{ $tg->transporte->tipo ?? '' }}</td>
        <td><strong>Marca:</strong> {{ $tg->transporte->marca ?? '' }}</td>
        <td><strong>Color:</strong> {{ $tg->transporte->color ?? '' }}</td>
        <td><strong>Placa:</strong> {{ $tg->transporte->placa ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>Nombre del Chofer:</strong> {{ $tg->driver->name ?? '' }}</td>
        <td><strong>C.I.:</strong> {{ $tg->driver->licencia ?? '' }}</td>
        <td colspan="2"><strong>Lic. Conducir:</strong> {{ $tg->driver->licencia ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>Cooperativa Minera:</strong> {{ $tg->empresa->nombre ?? '' }}</td>
        <td><strong>NIT:</strong> {{ $tg->nit ?? '' }}</td>
        <td><strong>Comprador:</strong> {{ $tg->comprador ?? '' }}</td>
        <td><strong>Destino:</strong> {{ $tg->destino ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>Tipo de Mineral:</strong> {{ $tg->tipoMaterial }}</td>
        <td><strong>Broza:</strong> {{ $tg->broza }}</td>
        <td><strong>Sacos:</strong> {{ $tg->sacos }}</td>
        <td><strong>Peso (Kg):</strong> {{ $tg->peso }}</td>
    </tr>
    <tr>
        <td><strong>Mineral:</strong> {{ $tg->minerales }}</td>
        <td><strong>Cantidad:</strong> {{ $tg->cantidad }}</td>
        <td colspan="2"><strong>N° de Lote:</strong> {{ $tg->lote }}</td>
    </tr>
</table>

<br><br>

<table class="sin-borde">
    <tr>
        <td><strong>Responsable:</strong> _________________________</td>
        <td><strong>Encargado de emitir:</strong> _________________________</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Sello de la Cooperativa:</strong> _________________________</td>
    </tr>
    <tr>
        <td><strong>Recepción en Empresa:</strong></td>
        <td><strong>Fecha:</strong> _________________________</td>
    </tr>
    <tr>
        <td><strong>Comprador:</strong> _________________________</td>
        <td><strong>Peso (Kg):</strong> _________________________</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Encargado de la Empresa Receptora:</strong> _________________________</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Fecha de Retorno:</strong> _________________________</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: right;">
            <div style="width: 130px; float: right;">
                <img src="{{ $qr }}" width="120">
                <div style="font-size: 10px; text-align: center;">Verificación QR</div>
            </div>
        </td>
    </tr>
</table>
<br><br>
</body>
</html>
