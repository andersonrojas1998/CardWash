<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $venta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: 58mm auto;
            margin: 3mm 2mm;
        }

        body {
            width: 54mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt;
            color: #000;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        .logo {
            width: 36mm;
            height: auto;
            margin-bottom: 3px;
        }

        .business-name {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .business-sub {
            font-size: 7pt;
            line-height: 1.3;
        }

        .invoice-title {
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin: 4px 0 2px;
            text-transform: uppercase;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 2px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 7.5pt;
            padding: 1px 0;
        }

        .info-row .label { font-weight: bold; }

        .section-divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        thead tr th {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding: 1px 2px;
            text-align: left;
            font-size: 7pt;
        }

        thead tr th:last-child,
        thead tr th:nth-child(2),
        thead tr th:nth-child(3) {
            text-align: right;
        }

        tbody tr td {
            padding: 2px 2px;
            vertical-align: top;
        }

        tbody tr td:nth-child(2),
        tbody tr td:nth-child(3),
        tbody tr td:nth-child(4) {
            text-align: right;
            white-space: nowrap;
        }

        .td-concepto {
            max-width: 22mm;
            word-break: break-word;
            text-transform: uppercase;
        }

        .total-row {
            border-top: 1px solid #000;
            font-weight: bold;
            font-size: 9pt;
        }

        .total-row td {
            padding: 3px 2px !important;
        }

        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 5px;
            padding-top: 4px;
            font-size: 7.5pt;
        }

        .footer .gracias {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer .vuelva {
            font-size: 7pt;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img class="logo" src="{{ asset('/icon.jpg') }}" alt="Logo">
        <div class="business-name">JUANCHO'S</div>
        <div class="business-sub">
            Lavado y Mantenimiento<br>
            de Vehiculos Automotores<br>
            JORGE ANDRES DIAZ CRUZ<br>
            NIT: 1.144.189.073-3<br>
            No Responsable del IVA
        </div>
    </div>

    <div class="invoice-title">
        Factura de Venta No. {{ $venta->id }}
    </div>

    <div style="margin: 4px 0;">
        <div class="info-row">
            <span class="label">Fecha:</span>
            <span>{{ date('d/m/Y H:i', strtotime($venta->fecha)) }}</span>
        </div>
        @if(isset($venta->detalle_paquete->tipo_vehiculo->descripcion))
        <div class="info-row">
            <span class="label">Vehículo:</span>
            <span style="text-transform:uppercase;">{{ $venta->detalle_paquete->tipo_vehiculo->descripcion }}</span>
        </div>
        @endif
        @if(isset($venta->placa) && $venta->placa)
        <div class="info-row">
            <span class="label">Placa:</span>
            <span style="text-transform:uppercase;">{{ $venta->placa }}</span>
        </div>
        @endif
        @if(isset($venta->nombre_cliente) && $venta->nombre_cliente)
        <div class="info-row">
            <span class="label">Cliente:</span>
            <span style="text-transform:uppercase;">{{ $venta->nombre_cliente }}</span>
        </div>
        @endif
        @if($venta->numero_telefono)
        <div class="info-row">
            <span class="label">Tel:</span>
            <span>{{ $venta->numero_telefono }}</span>
        </div>
        @endif
    </div>

    <div class="section-divider"></div>

    @php $total = 0; @endphp
    <table>
        <thead>
            <tr>
                <th style="width:45%;">Concepto</th>
                <th style="width:10%;">Cant</th>
                <th style="width:20%;">P.Unit</th>
                <th style="width:25%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($venta->detalle_paquete)
                @php $total += $venta->detalle_paquete->precio_venta; @endphp
                <tr>
                    <td class="td-concepto">{{ $venta->detalle_paquete->paquete->nombre }}</td>
                    <td>1</td>
                    <td>{{ number_format($venta->detalle_paquete->precio_venta, 0, ',', '.') }}</td>
                    <td>{{ number_format($venta->detalle_paquete->precio_venta, 0, ',', '.') }}</td>
                </tr>
            @endif
            @foreach($productos as $dp)
                @php $total += $dp->total_venta; @endphp
                <tr>
                    <td class="td-concepto">{{ $dp->producto }}</td>
                    <td>{{ $dp->cantidad_vendida }}</td>
                    <td>{{ number_format($dp->precio_venta, 0, ',', '.') }}</td>
                    <td>{{ number_format($dp->total_venta, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align:right; padding-right:4px;">TOTAL:</td>
                <td>$ {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="gracias">¡ Gracias por su visita !</div>
        <div class="vuelva">Vuelva pronto.</div>
    </div>

</body>
</html>
