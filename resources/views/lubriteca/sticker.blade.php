<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sticker #{{ $venta->id }} — {{ $venta->placa }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ══════════════════════════════════════════
           PANTALLA: preview con fondo gris
           ══════════════════════════════════════════ */
        body {
            font-family: Arial, Helvetica, sans-serif;
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
            max-width: 400px;
            margin-bottom: 12px;
        }
        .actions-bar h4 { font-size: .9rem; font-weight: 800; color: #1A202C; }
        .actions-bar .btns { display: flex; gap: 8px; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 14px; border: 1.5px solid #D1D5DB;
            border-radius: 8px; background: #fff;
            color: #374151; font-size: .78rem; font-weight: 600;
            text-decoration: none; cursor: pointer;
        }
        .btn-back:hover { border-color: #6B7280; text-decoration: none; }

        .btn-print {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; background: #C0392B; color: #fff;
            border: none; border-radius: 8px;
            font-size: .78rem; font-weight: 800; cursor: pointer;
        }
        .btn-print:hover { background: #9B2335; }

        /* ── Sombra en pantalla ── */
        .sticker-wrap {
            box-shadow: 0 14px 45px rgba(0,0,0,.28), 0 3px 10px rgba(0,0,0,.12);
            border-radius: 10px;
            overflow: hidden;
        }

        /* ══════════════════════════════════════════
           STICKER — tema OSCURO en pantalla
           ══════════════════════════════════════════ */
        .sticker {
            width: 76mm;
            background: #0C1020;
            display: flex;
            flex-direction: column;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        /* Decoraciones de fondo */
        .sticker::before {
            content: '';
            position: absolute; top: -18mm; right: -14mm;
            width: 52mm; height: 52mm; border-radius: 50%;
            background: radial-gradient(circle, rgba(220,30,30,.2) 0%, transparent 65%);
            pointer-events: none;
        }

        /* ─ Header ─ */
        .s-header {
            display: flex; align-items: center; gap: 2.5mm;
            padding: 2.5mm 3.5mm 2mm;
            border-bottom: .5mm solid rgba(200,30,30,.6);
            background: rgba(255,255,255,.04);
            flex-shrink: 0;
        }
        .s-logo {
            width: 9mm; height: 9mm; border-radius: 50%;
            object-fit: contain; background: #fff; flex-shrink: 0;
        }
        .s-brand { flex: 1; min-width: 0; }
        .s-brand-name  { font-size: 7.5pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: .3px; }
        .s-brand-sub   { font-size: 4.5pt; color: rgba(255,255,255,.42); letter-spacing: .3px; }
        .s-badge {
            background: #DC1E1E; color: #fff;
            font-size: 4.5pt; font-weight: 900;
            padding: .8mm 1.8mm; border-radius: .8mm;
            letter-spacing: .5px; text-transform: uppercase; flex-shrink: 0;
        }

        /* ─ Placa ─ */
        .s-plate { text-align: center; padding: 3.5mm 3mm 2.5mm; flex-shrink: 0; }
        .s-plate-label {
            font-size: 4.5pt; color: rgba(255,255,255,.38);
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1mm;
        }
        .s-plate-number {
            font-size: 32pt; font-weight: 900; color: #fff;
            letter-spacing: 3px; text-transform: uppercase; line-height: 1;
        }

        /* ─ Divisor ─ */
        .s-div {
            height: .3mm; margin: 0 3.5mm; flex-shrink: 0;
            background: linear-gradient(to right, transparent, rgba(220,30,30,.65), transparent);
        }

        /* ─ KM ─ */
        .s-km { padding: 2.5mm 3.5mm; flex-shrink: 0; }
        .s-km-row { display: flex; align-items: center; margin-bottom: 1.5mm; }
        .s-km-item { flex: 1; }
        .s-km-lbl { font-size: 4.5pt; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-bottom: .5mm; }
        .s-km-val { font-size: 14pt; font-weight: 900; color: #fff; line-height: 1; }
        .s-km-val.next { color: #F59E0B; }
        .s-km-sep { width: .25mm; height: 8mm; background: rgba(255,255,255,.1); margin: 0 3mm; }
        .s-prog-track { background: rgba(255,255,255,.07); border-radius: 2mm; height: 2mm; overflow: hidden; }
        .s-prog-fill  { height: 100%; border-radius: 2mm; background: linear-gradient(to right, #DC1E1E, #F59E0B); }

        /* ─ Productos ─ */
        .s-products { padding: 2mm 3.5mm; flex: 1; overflow: hidden; }
        .s-prod-lbl { font-size: 4pt; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 1.2mm; }
        .s-prod-item {
            display: flex; align-items: flex-start; gap: 1.5mm;
            padding: .8mm 0; border-bottom: .15mm solid rgba(255,255,255,.05);
        }
        .s-prod-item:last-child { border-bottom: none; }
        .s-prod-dot { width: 1.3mm; height: 1.3mm; border-radius: 50%; background: #DC1E1E; flex-shrink: 0; margin-top: .6mm; }
        .s-prod-name { font-size: 6.5pt; color: rgba(255,255,255,.9); line-height: 1.3; text-transform: uppercase; font-weight: 600; flex: 1; }
        .s-prod-qty  { font-size: 5.5pt; color: rgba(255,255,255,.35); flex-shrink: 0; }

        /* ─ Footer + QR ─ */
        .s-footer {
            display: flex; align-items: flex-start; gap: 3mm;
            padding: 2.5mm 3.5mm;
            border-top: .3mm solid rgba(255,255,255,.06);
            background: rgba(0,0,0,.2); flex-shrink: 0;
        }
        .s-qr { width: 26mm; height: 26mm; background: #fff; padding: 1.5mm; border-radius: 1.5mm; flex-shrink: 0; display: block; }
        .s-qr-info { flex: 1; min-width: 0; }
        .s-scan-cta { font-size: 6.5pt; font-weight: 900; color: #F59E0B; text-transform: uppercase; letter-spacing: .3px; line-height: 1.3; margin-bottom: 1mm; }
        .s-scan-sub { font-size: 5pt; color: rgba(255,255,255,.48); line-height: 1.4; }
        .s-scan-meta { font-size: 4.5pt; color: rgba(255,255,255,.25); margin-top: 1.5mm; line-height: 1.4; }

        /* ══════════════════════════════════════════
           IMPRESIÓN — tema BLANCO (sin fondo oscuro)
           El fondo blanco imprime en Chrome, Firefox,
           Edge sin necesitar "Gráficos de fondo".
           Solo el QR (imagen) y el texto/bordes.
           ══════════════════════════════════════════ */
        @page { size: auto; margin: 0; }

        @media print {
            html, body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                min-height: 0 !important;
            }

            .actions-bar { display: none !important; }

            .sticker-wrap {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100%;
            }

            /* Sticker ocupa toda la hoja / etiqueta */
            .sticker {
                width: 100% !important;
                min-height: 100vh !important;
                background: #fff !important;
                color: #111 !important;
                border-top: 5pt solid #C0392B;
            }
            .sticker::before, .sticker::after { display: none !important; }

            /* Header */
            .s-header { background: #F7F7F7 !important; border-bottom-color: #C0392B !important; }
            .s-brand-name  { color: #111 !important; }
            .s-brand-sub   { color: #888 !important; }
            .s-badge       { background: #C0392B !important; color: #fff !important; }

            /* Placa */
            .s-plate-label  { color: #888 !important; }
            .s-plate-number { color: #111 !important; text-shadow: none !important; }

            /* Divisor */
            .s-div { background: #e0e0e0 !important; }

            /* KM */
            .s-km-lbl { color: #777 !important; }
            .s-km-val { color: #111 !important; }
            .s-km-val.next { color: #C0392B !important; }
            .s-km-sep { background: #e0e0e0 !important; }
            .s-prog-track { background: #ebebeb !important; }
            /* La barra de progreso usa background-color por lo que necesita el flag */
            .s-prog-fill {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: linear-gradient(to right, #C0392B, #E67E22) !important;
            }

            /* Productos */
            .s-prod-lbl  { color: #888 !important; }
            .s-prod-name { color: #111 !important; }
            .s-prod-qty  { color: #888 !important; }
            .s-prod-dot  {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #C0392B !important;
            }
            .s-prod-item { border-bottom-color: #eee !important; }

            /* Footer */
            .s-footer { background: #F7F7F7 !important; border-top-color: #e0e0e0 !important; }
            .s-scan-cta  { color: #C0392B !important; }
            .s-scan-sub  { color: #555 !important; }
            .s-scan-meta { color: #999 !important; }
        }
    </style>
</head>
<body>

@php
    $historialUrl = config('app.url') . '/historial/' . urlencode($venta->placa ?: 'SIN-PLACA');
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($historialUrl) . '&color=000000&bgcolor=ffffff&margin=4';

    $kmActual  = intval($venta->km_actual);
    $kmProximo = intval($venta->km_proximo_cambio);
    $intervalo = 5000;
    $kmBase    = $kmProximo - $intervalo;
    $progreso  = ($kmBase > 0 && $kmActual > $kmBase)
                    ? min(100, round(($kmActual - $kmBase) / $intervalo * 100))
                    : 0;
    $tieneKm   = $kmActual > 0 && $kmProximo > 0;
    $tienePlaca = !empty(trim($venta->placa ?? ''));
@endphp

{{-- Barra de acciones (solo pantalla) --}}
<div class="actions-bar">
    <h4>Preview del sticker #{{ $venta->id }}</h4>
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

    {{-- HEADER --}}
    <div class="s-header">
        <img class="s-logo" src="{{ asset('/icon.jpg') }}" alt="Logo">
        <div class="s-brand">
            <div class="s-brand-name">Juancho's Car Wash</div>
            <div class="s-brand-sub">Lavado &amp; Lubriteca Automotriz</div>
        </div>
        <div class="s-badge">Lubriteca</div>
    </div>

    {{-- PLACA --}}
    <div class="s-plate">
        @if($tienePlaca)
        <div class="s-plate-label">Placa del veh&iacute;culo</div>
        <div class="s-plate-number">{{ $venta->placa }}</div>
        @else
        <div class="s-plate-label">Cliente</div>
        <div class="s-plate-number" style="font-size:16pt;letter-spacing:1px;">{{ $venta->nombre_cliente }}</div>
        @endif
    </div>

    <div class="s-div"></div>

    {{-- KM --}}
    @if($tieneKm)
    <div class="s-km">
        <div class="s-km-row">
            <div class="s-km-item">
                <div class="s-km-lbl">Km actual</div>
                <div class="s-km-val">{{ number_format($kmActual, 0, ',', '.') }}</div>
            </div>
            <div class="s-km-sep"></div>
            <div class="s-km-item" style="text-align:right">
                <div class="s-km-lbl">Pr&oacute;ximo cambio</div>
                <div class="s-km-val next">{{ number_format($kmProximo, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="s-prog-track">
            <div class="s-prog-fill" style="width:{{ $progreso }}%"></div>
        </div>
    </div>
    @else
    <div style="padding:2mm 3.5mm;">
        <div class="s-km-lbl">Fecha del servicio</div>
        <div class="s-km-val" style="font-size:10pt;">{{ date('d/m/Y', strtotime($venta->fecha)) }}</div>
    </div>
    @endif

    <div class="s-div"></div>

    {{-- PRODUCTOS --}}
    <div class="s-products">
        <div class="s-prod-lbl">Productos / Servicios realizados</div>
        @forelse($productos as $p)
            <div class="s-prod-item">
                <div class="s-prod-dot"></div>
                <div class="s-prod-name">
                    {{ $p->producto }}@if(!empty($p->marca ?? ''))&nbsp;<span style="opacity:.45;font-weight:400">{{ $p->marca }}</span>@endif
                </div>
                @if(intval($p->cantidad_vendida ?? 0) > 1)
                <div class="s-prod-qty">×{{ $p->cantidad_vendida }}</div>
                @endif
            </div>
        @empty
            <div class="s-prod-item"><div class="s-prod-dot"></div><div class="s-prod-name">Sin detalle</div></div>
        @endforelse
    </div>

    {{-- FOOTER + QR --}}
    <div class="s-footer">
        <img class="s-qr" src="{{ $qrUrl }}" alt="QR historial vehículo">
        <div class="s-qr-info">
            <div class="s-scan-cta">&#128247; Escanear para<br>ver historial</div>
            <div class="s-scan-sub">Historial completo de<br>servicios del veh&iacute;culo</div>
            <div class="s-scan-meta">
                {{ date('d/m/Y H:i', strtotime($venta->fecha)) }}<br>
                Factura #{{ $venta->id }}
            </div>
        </div>
    </div>

</div>
</div>

<script>
/* Auto-ajuste del font-size en impresión según área disponible */
window.addEventListener('beforeprint', function () {
    /* Escala la fuente de la placa para que no se corte */
    var plate = document.querySelector('.s-plate-number');
    if (plate) {
        var len = (plate.textContent || '').trim().length;
        if (len <= 6) plate.style.fontSize = '30pt';
        else if (len <= 8) plate.style.fontSize = '24pt';
        else plate.style.fontSize = '18pt';
    }
});
window.addEventListener('afterprint', function () {
    var plate = document.querySelector('.s-plate-number');
    if (plate) plate.style.fontSize = '';
});
</script>
</body>
</html>
