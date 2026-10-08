<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial {{ strtoupper($placa) }} - JUANCHO'S</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #0f0f1a;
            color: #fff;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #12122a, #1c1c3a);
            padding: 24px 20px 20px;
            text-align: center;
            border-bottom: 2px solid rgba(220,30,30,0.4);
        }
        .header img { width: 60px; height: 60px; object-fit: contain; border-radius: 50%; background:#fff; margin-bottom: 10px; }
        .header h1 { font-size: 20px; font-weight: 900; letter-spacing: 1px; }
        .header p { font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 4px; }

        .plate-banner {
            background: linear-gradient(135deg, #dc1e1e, #9a0000);
            text-align: center;
            padding: 20px;
        }
        .plate-banner .label { font-size: 10px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.75; margin-bottom: 4px; }
        .plate-banner .plate { font-size: 38px; font-weight: 900; letter-spacing: 5px; }

        .container { max-width: 480px; margin: 0 auto; padding: 16px; }

        .stats-row {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        .stat-card {
            flex: 1;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
        }
        .stat-card .stat-num { font-size: 24px; font-weight: 800; color: #f5a623; }
        .stat-card .stat-label { font-size: 10px; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px; }

        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 10px;
            padding-left: 2px;
        }

        .venta-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .venta-card-header {
            background: rgba(220,30,30,0.12);
            border-bottom: 1px solid rgba(220,30,30,0.2);
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .venta-card-header .no { font-size: 12px; font-weight: 700; color: #ff6b6b; }
        .venta-card-header .fecha { font-size: 11px; color: rgba(255,255,255,0.5); }
        .venta-card-body { padding: 12px 14px; }

        .km-info {
            display: flex;
            gap: 14px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .km-info .km-box { }
        .km-info .km-label { font-size: 9px; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .km-info .km-val { font-size: 15px; font-weight: 700; }
        .km-info .km-val.next { color: #f5a623; }

        .producto-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            font-size: 13px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .producto-row:last-child { border-bottom: none; }
        .producto-dot { width: 6px; height: 6px; border-radius: 50%; background: #dc1e1e; flex-shrink: 0; }
        .producto-name { flex: 1; color: rgba(255,255,255,0.85); }
        .producto-qty { font-size: 11px; color: rgba(255,255,255,0.35); }
        .producto-price { font-size: 12px; color: rgba(255,255,255,0.6); font-weight: 600; }

        .venta-total {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: right;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
        }

        .empty { text-align: center; padding: 40px 20px; color: rgba(255,255,255,0.35); }
        .empty .icon { font-size: 48px; margin-bottom: 10px; }

        .footer-credits {
            text-align: center;
            padding: 24px;
            font-size: 11px;
            color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ asset('/icon.jpg') }}" alt="Logo">
    <h1>JUANCHO'S Car Wash</h1>
    <p>Historial de servicios del vehículo</p>
</div>

<div class="plate-banner">
    <div class="label">Placa</div>
    <div class="plate">{{ strtoupper($placa) }}</div>
</div>

<div class="container">

    @if($ventas->isEmpty())
        <div class="empty">
            <div class="icon">🚗</div>
            <p>No se encontraron servicios registrados para este vehículo.</p>
        </div>
    @else
        {{-- Stats --}}
        @php
            $totalServicios = $ventas->count();
            $ultimoKm = $ventas->whereNotNull('km_actual')->first();
        @endphp
        <div class="stats-row" style="margin-top: 16px;">
            <div class="stat-card">
                <div class="stat-num">{{ $totalServicios }}</div>
                <div class="stat-label">Visitas</div>
            </div>
            @if($ultimoKm)
            <div class="stat-card">
                <div class="stat-num">{{ number_format($ultimoKm->km_actual, 0, ',', '.') }}</div>
                <div class="stat-label">Último Km</div>
            </div>
            <div class="stat-card">
                <div class="stat-num" style="color:#4ade80;">{{ number_format($ultimoKm->km_proximo_cambio, 0, ',', '.') }}</div>
                <div class="stat-label">Próximo</div>
            </div>
            @endif
        </div>

        <div class="section-title">Historial de servicios</div>

        @foreach($ventas as $v)
        @php
            $prods = DB::SELECT("CALL sp_groupSalesProduct('{$v->id}')");
            $totalVenta = 0;
            if($v->detalle_paquete) $totalVenta += $v->detalle_paquete->precio_venta;
            foreach($prods as $p) $totalVenta += $p->total_venta;
        @endphp
        <div class="venta-card">
            <div class="venta-card-header">
                <span class="no">Servicio #{{ $v->id }}</span>
                <span class="fecha">{{ date('d/m/Y H:i', strtotime($v->fecha)) }}</span>
            </div>
            <div class="venta-card-body">
                @if($v->km_actual)
                <div class="km-info">
                    <div class="km-box">
                        <div class="km-label">Km al ingreso</div>
                        <div class="km-val">{{ number_format($v->km_actual, 0, ',', '.') }}</div>
                    </div>
                    <div class="km-box">
                        <div class="km-label">Próximo cambio</div>
                        <div class="km-val next">{{ number_format($v->km_proximo_cambio, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endif

                @if($v->detalle_paquete)
                <div class="producto-row">
                    <div class="producto-dot" style="background:#4ade80;"></div>
                    <div class="producto-name">{{ $v->detalle_paquete->paquete->nombre }}</div>
                    <div class="producto-price">$ {{ number_format($v->detalle_paquete->precio_venta, 0, ',', '.') }}</div>
                </div>
                @endif

                @foreach($prods as $p)
                <div class="producto-row">
                    <div class="producto-dot"></div>
                    <div class="producto-name" style="text-transform:uppercase;">{{ $p->producto }}</div>
                    <div class="producto-qty">x{{ $p->cantidad_vendida }}</div>
                    <div class="producto-price">$ {{ number_format($p->total_venta, 0, ',', '.') }}</div>
                </div>
                @endforeach

                <div class="venta-total">Total: $ {{ number_format($totalVenta, 0, ',', '.') }}</div>
            </div>
        </div>
        @endforeach
    @endif

    <div class="footer-credits">
        JUANCHO'S Car Wash &bull; Lavado y Mantenimiento Automotriz<br>
        NIT: 1.144.189.073-3
    </div>
</div>

</body>
</html>
