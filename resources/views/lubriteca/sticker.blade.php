<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sticker #{{ $venta->id }} — {{ $venta->placa }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ══════════════════════════════════════════
           PANTALLA: preview ampliado con fondo gris
           ══════════════════════════════════════════ */
        body {
            font-family: "Arial Narrow", Arial, Helvetica, sans-serif;
            background: #DDDFE4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px 48px;
            min-height: 100vh;
        }

        /* ── Barra de acciones (solo pantalla) ── */
        .actions-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 440px;
            margin-bottom: 18px;
        }
        .actions-bar h4 { font-family: Arial, sans-serif; font-size: .9rem; font-weight: 800; color: #1A202C; }
        .actions-bar small { display: block; font-weight: 500; color: #6B7280; font-size: .7rem; margin-top: 2px; }
        .actions-bar .btns { display: flex; gap: 8px; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 14px; border: 1.5px solid #D1D5DB;
            border-radius: 8px; background: #fff;
            color: #374151; font-family: Arial, sans-serif; font-size: .78rem; font-weight: 600;
            text-decoration: none; cursor: pointer;
        }
        .btn-back:hover { border-color: #6B7280; text-decoration: none; }

        .btn-print {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; background: #C0392B; color: #fff;
            border: none; border-radius: 8px;
            font-family: Arial, sans-serif; font-size: .78rem; font-weight: 800; cursor: pointer;
        }
        .btn-print:hover { background: #9B2335; }

        /* ── Contenedor del preview: reserva el espacio del sticker escalado ── */
        .sticker-wrap {
            --zoom: 2.2;
            width: calc(50mm * var(--zoom));
            height: calc(25mm * var(--zoom));
        }
        .sticker-wrap .sticker {
            transform: scale(var(--zoom));
            transform-origin: top left;
            box-shadow: 0 2px 8px rgba(0,0,0,.18);
            border-radius: 1.2mm;
        }

        /* ══════════════════════════════════════════
           STICKER — tamaño real 50 × 25 mm
           ══════════════════════════════════════════ */
        .sticker {
            width: 50mm;
            height: 25mm;
            background: #fff;
            color: #111;
            display: flex;
            align-items: stretch;
            gap: 1.6mm;
            padding: 1.4mm 1.6mm 1.4mm 1.4mm;
            overflow: hidden;
        }

        /* ─ QR ─ */
        .s-qr-box {
            position: relative;
            width: 22.2mm;
            height: 22.2mm;
            flex-shrink: 0;
        }
        .s-qr {
            width: 100%;
            height: 100%;
            display: block;
            image-rendering: pixelated;
        }
        /* Logo centrado: ocupa ~22% del QR (seguro con corrección de errores H) */
        .s-qr-logo {
            position: absolute;
            top: 50%; left: 50%;
            width: 5.2mm; height: 5.2mm;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 50%;
            padding: .35mm;
            object-fit: contain;
        }

        /* ─ Info ─ */
        .s-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-left: .3mm solid #111;
            padding-left: 1.6mm;
        }

        .s-brand {
            display: flex; align-items: center; gap: .8mm;
            font-size: 4.6pt; font-weight: 800;
            text-transform: uppercase; letter-spacing: .25px;
            white-space: nowrap; overflow: hidden;
            padding-bottom: .5mm;
            border-bottom: .2mm solid #BBB;
        }
        .s-brand-dot {
            width: 1.3mm; height: 1.3mm; border-radius: .3mm;
            background: #C0392B; flex-shrink: 0;
        }
        .s-brand span { overflow: hidden; text-overflow: ellipsis; }

        .s-next-lbl {
            font-size: 4.3pt; font-weight: 700; color: #555;
            text-transform: uppercase; letter-spacing: .9px;
            margin-top: .6mm;
        }
        .s-next-val {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14pt; font-weight: 900; line-height: 1;
            letter-spacing: -.2px; white-space: nowrap;
        }
        .s-next-val small { font-size: 6pt; font-weight: 800; margin-left: .4mm; letter-spacing: 0; }

        .s-meta {
            font-size: 4.8pt; color: #333; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .s-meta b { color: #111; }

        .s-line {
            font-size: 5.2pt; font-weight: 800; text-transform: uppercase;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .s-line .plate {
            border: .25mm solid #111; border-radius: .5mm;
            padding: 0 .6mm; margin-right: .6mm; letter-spacing: .4px;
        }

        .s-scan {
            font-size: 4pt; color: #555; text-transform: uppercase;
            letter-spacing: .5px; white-space: nowrap; overflow: hidden;
        }

        /* ══════════════════════════════════════════
           IMPRESIÓN — etiqueta térmica 50 × 25 mm
           Solo negro: sin fondos ni degradados.
           ══════════════════════════════════════════ */
        @page { size: 50mm 25mm; margin: 0; }

        @media print {
            html, body {
                width: 50mm !important;
                height: 25mm !important;
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                min-height: 0 !important;
                overflow: hidden !important;
            }

            .actions-bar { display: none !important; }

            .sticker-wrap { width: 50mm !important; height: 25mm !important; }
            .sticker-wrap .sticker {
                transform: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-after: avoid;
                break-after: avoid;
            }

            .s-brand-dot {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #000 !important;
            }
            .s-next-lbl, .s-meta, .s-scan { color: #000 !important; }
            /* Térmica monocroma: logo en escala de grises con contraste para que no salga como mancha */
            .s-qr-logo { filter: grayscale(1) contrast(1.4); }
            .s-brand { border-bottom-color: #000 !important; }
        }
    </style>
</head>
<body>

@php
    $historialUrl = config('app.url') . '/historial/' . urlencode($venta->placa ?: 'SIN-PLACA');
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($historialUrl) . '&color=000000&bgcolor=ffffff&margin=0&qzone=1&ecc=H';

    $kmActual   = intval($venta->km_actual);
    $kmProximo  = intval($venta->km_proximo_cambio);
    $tieneKm    = $kmProximo > 0;
    $tienePlaca = !empty(trim($venta->placa ?? ''));

    $fechaServicio  = \Carbon\Carbon::parse($venta->fecha);
    $fechaSugerida  = $fechaServicio->copy()->addMonths(3);

    $primerProducto = count($productos) ? $productos[0]->producto : '';
    $masProductos   = max(0, count($productos) - 1);
@endphp

{{-- Barra de acciones (solo pantalla) --}}
<div class="actions-bar">
    <h4>
        Sticker #{{ $venta->id }}
        <small>Tamaño real 50 × 25 mm (vista ampliada)</small>
    </h4>
    <div class="btns">
        <a href="{{ route('lubriteca.index') }}" class="btn-back">&#8592; Nueva venta</a>
        <button class="btn-print" onclick="window.print()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            Imprimir
        </button>
    </div>
</div>

<div class="sticker-wrap">
<div class="sticker">

    <div class="s-qr-box">
        <img class="s-qr" src="{{ $qrUrl }}" alt="QR historial vehículo">
        <img class="s-qr-logo" src="{{ asset('/icon.jpg') }}" alt="">
    </div>

    <div class="s-info">
        <div class="s-brand">
            <div class="s-brand-dot"></div>
            <span>Juancho's Car Wash</span>
        </div>

        <div>
            @if($tieneKm)
            <div class="s-next-lbl">Pr&oacute;ximo cambio</div>
            <div class="s-next-val" id="nextVal">{{ number_format($kmProximo, 0, ',', '.') }}<small>KM</small></div>
            @else
            <div class="s-next-lbl">Pr&oacute;x. cambio sugerido</div>
            <div class="s-next-val" id="nextVal">{{ $fechaSugerida->format('d/m/Y') }}</div>
            @endif
        </div>

        <div class="s-meta">
            @if($kmActual > 0)<b>Actual</b> {{ number_format($kmActual, 0, ',', '.') }} &middot; @endif{{ $fechaServicio->format('d/m/Y') }}
        </div>

        <div class="s-line">
            @if($tienePlaca)<span class="plate">{{ $venta->placa }}</span>@else{{ $venta->nombre_cliente }} &middot; @endif{{ $primerProducto }}@if($masProductos > 0) +{{ $masProductos }}@endif
        </div>

        <div class="s-scan">&#9656; Escanea: historial &middot; #{{ $venta->id }}</div>
    </div>

</div>
</div>

<script>
/* Reduce la fuente del valor principal si no cabe en el ancho disponible */
(function () {
    var el = document.getElementById('nextVal');
    if (!el) return;
    var size = 14;
    while (el.scrollWidth > el.clientWidth && size > 8) {
        size -= 0.5;
        el.style.fontSize = size + 'pt';
    }
})();
</script>
</body>
</html>
