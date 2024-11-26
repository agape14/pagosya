
<!DOCTYPE html>
<html>
<head>
    <title>Pago {{ $pago->id }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table, .table th, .table td { border: 1px solid #6418C3 ; }
        .table th, .table td { padding: 8px; text-align: left; }
        .logo {
            width: 150px;
            height: auto;
        }
    </style>

</head>
<body>
    <table width="100%">
        <tr>
            <td>
                <img src="{{ public_path('images/logo_g07.jpeg') }}" alt="Torre G7" class="logo">
                {{--<h1>Torre G6</h1>--}}
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="30%">
                <table class="table">
                    <tr>
                        <td colspan="2" style="text-align: center;background-color: #ECDCFF">
                            TORRE {{ $torre->nombre_torre }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <small>COMPROBANTE DE PAGO</small>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;background-color: #ECDCFF">
                            N° {{ str_pad($pago->correlativo, 5, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($pago->fecha)->format('Y') }}
                        </td>
                    </tr>
                </table >

            </td>
        </tr>

    </table >
</br>
    <table class="table">
        <tr >
            <th style="background-color: #ECDCFF">Señores:</th>
            <td>{{ $pago->propietario->nombre }} {{ $pago->propietario->apellido }}</td>
        </tr>
        <tr >
            <th style="background-color: #ECDCFF">Departamento</th>
            <td>{{ $pago->propietario->departamento }}</td>
        </tr>
        <tr >
            <th style="background-color: #ECDCFF">Fecha Pago</th>
            <td>{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th style="background-color: #ECDCFF"> Estado</th>
            <td>{{ $pago->estado->nombre }}</td>
        </tr>
    </table>
</br></br></br>
    <table class="table">
        <thead>
            <tr style="background-color: #ECDCFF">
                <th>Cant.</th>
                <th>Detalle del Pago</th>
                <th style="text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
                @if ($detalle->concepto->mes>0 && $detalle->concepto->anio>0)
                    <tr>
                        <td>1</td>
                        <td>{{ $detalle->concepto->descripcion_concepto ." ".$detalle->concepto->nombreMes->nombremes." ".$detalle->concepto->anio }}<br>
                            <span style="color: #ec0047">{{ $detalle->observacion ?? '' }}</span></td>
                        <td style="text-align: right;">S/.
                            @if ($detalle->monto_pagado == 0.00)
                                {{ number_format($detalle->monto, 2) }}
                            @else
                                {{ number_format($detalle->monto_pagado, 2) }}
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>1</td>
                        <td>{{ $detalle->concepto->descripcion_concepto  }}<br>
                            <span class="text-danger">{{ $detalle->observacion ?? '' }}</span></td>
                        <td style="text-align: right;">S/.
                            @if ($detalle->monto_pagado == 0.00)
                                {{ number_format($detalle->monto, 2) }}
                            @else
                                {{ number_format($detalle->monto_pagado, 2) }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <th>Total:</th>
            <td style="background-color: #ECDCFF;text-align: right; font-weight: bold" colspan="2" >S/. {{ number_format($pago->total, 2) }}</td>
        </tfoot>
    </table>
    <br>
    <br>
    <table width="60%">
        <tr>
            <td><strong>JUNTA DIRECTIVA:</strong></td>
            <td>{{ $juntasdirectivas->nombre }}</td>
        </tr>
        <tr>
            <td><strong>DELEGADA:</strong></td>
            <td>{{ $delegada ?? 'No asignada' }}</td>
        </tr>
        <tr>
            <td><strong>TESORERA:</strong></td>
            <td>{{ $tesorera ?? 'No asignada' }}</td>
        </tr>
    </table>
</body>
</html>
