
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
                <img src="{{ public_path('images/logoG06.png') }}" alt="Torre G6" class="logo">
                {{--<h1>Torre G6</h1>--}}
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="30%">
                <table class="table">
                    <tr>
                        <th style="background-color: #ECDCFF">F. Pago</th>
                            <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <p>La administración</p>
                        </td>
                    </tr>
                </table >

            </td>
        </tr>

    </table >
</br>
    <table class="table">
        <tr >
            <th style="background-color: #ECDCFF">Departamento</th>
            <td>{{ $pago->propietario->departamento }}</td>
        </tr>
        <tr>
            <th style="background-color: #ECDCFF">Descripcion</th>
            <td>Detalle de los pagos realizados</td>
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
                <th>Detalle</th>
                <th style="text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
                @if ($detalle->concepto->mes>0 && $detalle->concepto->anio>0)
                    <tr>
                        <td>1</td>
                        <td>{{ $detalle->concepto->descripcion_concepto ." ".$detalle->concepto->nombreMes->nombremes." ".$detalle->concepto->anio }}</td>
                        <td style="text-align: right;">S/. {{ number_format($detalle->monto, 2) }}</td>
                    </tr>
                @else
                    <tr>
                        <td>1</td>
                        <td>{{ $detalle->concepto->descripcion_concepto  }}</td>
                        <td style="text-align: right;">S/. {{ number_format($detalle->monto, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <th>Total:</th>
            <td style="background-color: #ECDCFF;text-align: right; font-weight: bold" colspan="2" >S/. {{ number_format($pago->total, 2) }}</td>
        </tfoot>
    </table>
</body>
</html>
