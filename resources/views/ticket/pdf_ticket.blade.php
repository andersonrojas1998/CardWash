<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $venta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: 58mm auto;
            margin: 2mm 2mm 4mm 2mm;
        }

        html {
            margin: 0;
            padding: 0;
        }

        body {
            width: 54mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        @media print {
            html, body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        .logo {
            display: block;
            margin: 0 auto 3px;
            width: 24mm;
            height: 24mm;
            object-fit: contain;
        }

        .business-name {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .business-sub {
            font-size: 9pt;
            line-height: 1.4;
        }

        .invoice-title {
            font-size: 11pt;
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
            font-size: 9.5pt;
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
            font-size: 9.5pt;
        }

        thead tr th {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding: 1px 0;
            text-align: left;
            font-size: 9pt;
        }

        .th-right { text-align: right; }

        /* Fila nombre del item */
        .td-concepto {
            text-transform: uppercase;
            font-weight: bold;
            padding: 2px 0 0 0;
            word-break: break-word;
        }

        /* Fila cant x precio = total (segunda línea) */
        .td-detalle {
            color: #333;
            padding: 0 0 2px 0;
            text-align: right;
            font-size: 9pt;
            border-bottom: 1px dotted #ccc;
        }

        .total-row {
            border-top: 1px solid #000;
            font-weight: bold;
            font-size: 12pt;
        }

        .total-row td {
            padding: 3px 0 !important;
        }

        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 5px;
            padding-top: 4px;
            font-size: 9pt;
        }

        .footer .gracias {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer .vuelva {
            font-size: 9pt;
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
                <th style="width:60%;">Descripcion</th>
                <th class="th-right" style="width:40%;">Cant x Precio</th>
            </tr>
        </thead>
        <tbody>
            @if($venta->detalle_paquete)
                @php $total += $venta->detalle_paquete->precio_venta; @endphp
                <tr>
                    <td class="td-concepto">{{ $venta->detalle_paquete->paquete->nombre }}</td>
                    <td class="td-detalle">
                        1 x {{ number_format($venta->detalle_paquete->precio_venta, 0, ',', '.') }}
                        = <b>{{ number_format($venta->detalle_paquete->precio_venta, 0, ',', '.') }}</b>
                    </td>
                </tr>
            @endif
            @foreach($productos as $dp)
                @php $total += $dp->total_venta; @endphp
                <tr>
                    <td class="td-concepto" style="text-transform:uppercase;">{{ $dp->producto }}</td>
                    <td class="td-detalle">
                        {{ $dp->cantidad_vendida }} x {{ number_format($dp->precio_venta, 0, ',', '.') }}
                        = <b>{{ number_format($dp->total_venta, 0, ',', '.') }}</b>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td style="text-align:right;">TOTAL:</td>
                <td style="text-align:right;">$ {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="gracias">¡ Gracias por su visita !</div>
        <div class="vuelva">Vuelva pronto.</div>
    </div>

   

</body>
</html>
