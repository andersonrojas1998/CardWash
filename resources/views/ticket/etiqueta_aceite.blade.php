<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Etiqueta #{{ $venta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        html, body {
            width: 100mm;
            height: 150mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #0f0f1a;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        @media print {
            html, body { margin: 0 !important; padding: 0 !important; }
        }

        .sticker {
            width: 100mm;
            height: 150mm;
            background: linear-gradient(160deg, #12122a 0%, #1a1a3a 60%, #0d0d1f 100%);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Decoración de fondo */
        .sticker::before {
            content: '';
            position: absolute;
            top: -20mm;
            right: -10mm;
            width: 60mm;
            height: 60mm;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(220,30,30,0.18) 0%, transparent 70%);
            pointer-events: none;
        }
        .sticker::after {
            content: '';
            position: absolute;
            bottom: -15mm;
            left: -10mm;
            width: 50mm;
            height: 50mm;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(220,30,30,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            padding: 3mm 4mm 2mm;
            border-bottom: 0.4mm solid rgba(220,30,30,0.5);
            gap: 2mm;
        }
        .header img {
            width: 12mm;
            height: 12mm;
            object-fit: contain;
            border-radius: 50%;
            background: #fff;
        }
        .header-text .biz-name {
            font-size: 9pt;
            font-weight: 900;
            color: #fff;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-text .biz-sub {
            font-size: 5.5pt;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.3px;
        }
        .header-badge {
            margin-left: auto;
            background: #dc1e1e;
            color: #fff;
            font-size: 5pt;
            font-weight: bold;
            padding: 1mm 2mm;
            border-radius: 1mm;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── PLACA ── */
        .plate-section {
            text-align: center;
            padding: 4mm 4mm 2mm;
        }
        .plate-label {
            font-size: 5.5pt;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 1mm;
        }
        .plate-number {
            font-size: 28pt;
            font-weight: 900;
            color: #fff;
            letter-spacing: 3px;
            text-transform: uppercase;
            line-height: 1;
            text-shadow: 0 0 8mm rgba(220,30,30,0.4);
        }
        .plate-tipo {
            font-size: 6pt;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        /* ── DIVIDER ── */
        .divider {
            height: 0.3mm;
            background: linear-gradient(to right, transparent, rgba(220,30,30,0.6), transparent);
            margin: 0 4mm;
        }

        /* ── KM SECTION ── */
        .km-section {
            padding: 3mm 4mm;
        }
        .km-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2mm;
        }
        .km-item { flex: 1; }
        .km-item-label {
            font-size: 5pt;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .km-item-value {
            font-size: 11pt;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .km-item-value.next {
            color: #f5a623;
        }
        .km-separator {
            width: 0.3mm;
            height: 8mm;
            background: rgba(255,255,255,0.15);
            margin: 0 3mm;
        }
        .progress-bar-wrap {
            background: rgba(255,255,255,0.1);
            border-radius: 2mm;
            height: 2mm;
            overflow: hidden;
            margin-top: 1.5mm;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 2mm;
            background: linear-gradient(to right, #dc1e1e, #f5a623);
        }

        /* ── PRODUCTOS ── */
        .productos-section {
            padding: 2mm 4mm;
            flex: 1;
        }
        .productos-label {
            font-size: 5pt;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5mm;
        }
        .producto-item {
            font-size: 6.5pt;
            color: rgba(255,255,255,0.85);
            padding: 0.8mm 0;
            border-bottom: 0.2mm solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 1.5mm;
            text-transform: uppercase;
        }
        .producto-dot {
            width: 1.5mm;
            height: 1.5mm;
            border-radius: 50%;
            background: #dc1e1e;
            flex-shrink: 0;
        }

        /* ── FOOTER / QR ── */
        .footer {
            padding: 2mm 4mm 3mm;
            display: flex;
            align-items: center;
            gap: 3mm;
            border-top: 0.4mm solid rgba(255,255,255,0.08);
        }
        .footer img.qr {
            width: 22mm;
            height: 22mm;
            background: #fff;
            padding: 1mm;
            border-radius: 1.5mm;
            flex-shrink: 0;
        }
        .footer-info { flex: 1; }
        .footer-date {
            font-size: 5.5pt;
            color: rgba(255,255,255,0.45);
            margin-bottom: 1mm;
        }
        .footer-scan {
            font-size: 5.5pt;
            color: rgba(255,255,255,0.65);
            line-height: 1.4;
        }
        .footer-scan strong {
            color: #f5a623;
            display: block;
            font-size: 6.5pt;
        }
        .footer-no {
            font-size: 4.5pt;
            color: rgba(255,255,255,0.3);
            margin-top: 1.5mm;
        }
    </style>
</head>
<body>

@php
    $historialUrl = config('app.url') . '/historial/' . urlencode($venta->placa);
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($historialUrl) . '&color=000000&bgcolor=ffffff&margin=2';

    // Calcular % progreso del km
    $kmActual    = intval($venta->km_actual);
    $kmProximo   = intval($venta->km_proximo_cambio);
    $intervalo   = 5000;
    $kmBase      = $kmProximo - $intervalo;
    $progreso    = ($kmBase > 0 && $kmActual > $kmBase)
                    ? min(100, round(($kmActual - $kmBase) / $intervalo * 100))
                    : 0;
@endphp

<div class="sticker">

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset('/icon.jpg') }}" alt="Logo">
        <div class="header-text">
            <div class="biz-name">JUANCHO'S Car Wash</div>
            <div class="biz-sub">Lavado &amp; Mantenimiento Automotriz</div>
        </div>
        <div class="header-badge">Aceite</div>
    </div>

    {{-- PLACA --}}
    <div class="plate-section">
        <div class="plate-label">Placa del vehículo</div>
        <div class="plate-number">{{ $venta->placa ?: '---' }}</div>
        @if(isset($venta->detalle_paquete->tipo_vehiculo->descripcion))
        <div class="plate-tipo">{{ $venta->detalle_paquete->tipo_vehiculo->descripcion }}</div>
        @endif
    </div>

    <div class="divider"></div>

    {{-- KM --}}
    <div class="km-section">
        <div class="km-row">
            <div class="km-item">
                <div class="km-item-label">Km actual</div>
                <div class="km-item-value">{{ number_format($kmActual, 0, ',', '.') }}</div>
            </div>
            <div class="km-separator"></div>
            <div class="km-item" style="text-align:right;">
                <div class="km-item-label">Próximo cambio</div>
                <div class="km-item-value next">{{ number_format($kmProximo, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="progress-bar-wrap">
            <div class="progress-bar-fill" style="width: {{ $progreso }}%"></div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- PRODUCTOS --}}
    <div class="productos-section">
        <div class="productos-label">Trabajos realizados</div>
        @if($venta->detalle_paquete)
            <div class="producto-item">
                <div class="producto-dot"></div>
                {{ $venta->detalle_paquete->paquete->nombre }}
            </div>
        @endif
        @foreach($productos as $p)
            <div class="producto-item">
                <div class="producto-dot"></div>
                {{ $p->producto }}
                @if($p->cantidad_vendida > 1)
                    &nbsp;<span style="color:rgba(255,255,255,0.4)">x{{ $p->cantidad_vendida }}</span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- FOOTER + QR --}}
    <div class="footer">
        <img class="qr" src="{{ $qrUrl }}" alt="QR Historial">
        <div class="footer-info">
            <div class="footer-date">
                Fecha: {{ date('d/m/Y H:i', strtotime($venta->fecha)) }}
            </div>
            <div class="footer-scan">
                <strong>Escanea para ver historial</strong>
                Conoce todos los servicios realizados a este vehículo
            </div>
            <div class="footer-no">Factura No. {{ $venta->id }} &bull; {{ config('app.url') }}</div>
        </div>
    </div>

</div>

<script>
    window.onload = function() { window.print(); };
</script>
</body>
</html>
