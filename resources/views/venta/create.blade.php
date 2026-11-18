@extends('layout.master')
@section('content')

<form id="form_create_sell" action="{{ route('venta.store') }}" method="POST" autocomplete="off">
{{ csrf_field() }}
<input type="hidden" name="importe_total" id="importe_total" value="0">
<input type="hidden" name="precio_venta_paquete" id="precio_venta_paquete" value="0">
<input type="hidden" name="porcentaje_paquete" id="porcentaje_paquete" value="0">
<input type="hidden" name="id_estado_venta" id="id_estado_venta" value="1">

<div class="vc-wrap">

    {{-- ── Encabezado ── --}}
    <div class="vc-head">
        <div class="vc-head-title">
            <div class="vc-head-icon"><i class="mdi mdi-car-wash"></i></div>
            <div>
                <h3>Registrar venta</h3>
                <span>Lavado, combos y productos</span>
            </div>
        </div>
        <div class="vc-head-date">
            <i class="mdi mdi-calendar-clock"></i> {{ $date->format('d/m/Y h:i A') }}
        </div>
    </div>

    @if (session('fail'))
        <div class="vc-alert"><i class="mdi mdi-alert-circle"></i> {!! session('fail') !!}</div>
    @endif

    <div class="row">
        {{-- ════════════ COLUMNA PRINCIPAL ════════════ --}}
        <div class="col-lg-8">

            {{-- 1. Cliente --}}
            <section class="vc-card">
                <div class="vc-card-head"><span class="vc-step">1</span> Cliente y vehículo</div>
                <div class="vc-card-body">
                    <div class="row">
                        <div class="col-md-6 vc-field">
                            <label for="input_name_customer">Cliente *</label>
                            <input type="text" id="input_name_customer" name="nombre_cliente" class="vc-input text-uppercase" placeholder="Nombre del cliente" value="{{ old('nombre_cliente') }}" required>
                            @if ($errors->first('nombre_cliente'))<small class="text-danger">{{ $errors->first('nombre_cliente') }}</small>@endif
                        </div>
                        <div class="col-md-6 vc-field">
                            <label for="input_phone_number">Teléfono *</label>
                            <input type="number" id="input_phone_number" name="numero_telefono" class="vc-input" placeholder="300 123 4567" value="{{ old('numero_telefono') }}" required>
                        </div>
                        <div class="col-md-6 vc-field">
                            <label for="input_license_plate">Placa</label>
                            <div class="vc-plate">
                                <input type="text" id="input_license_plate" name="placa" maxlength="7" placeholder="ABC123" value="{{ old('placa') }}">
                                <span>COLOMBIA</span>
                            </div>
                        </div>
                        <div class="col-md-6 vc-field">
                            <label>¿Quién presta el servicio? *</label>
                            <select class="select2" name="id_usuario" style="width:100%">
                                @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ (old('id_usuario', 17) == $usuario->id) ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <label class="vc-sub-label">Tipo de venta</label>
                    <div class="vc-segment" id="vc-sale-type">
                        <button type="button" class="active" data-type="1"><i class="mdi mdi-car-wash"></i> Servicio y productos</button>
                        <button type="button" data-type="3"><i class="mdi mdi-oil"></i> Solo productos</button>
                    </div>
                </div>
            </section>

            {{-- 2. Vehículo + combo --}}
            <section class="vc-card" id="vc-service-section">
                <div class="vc-card-head"><span class="vc-step">2</span> Tipo de vehículo y combo</div>
                <div class="vc-card-body">
                    <div class="vc-vehicles">
                        @foreach($tipos_vehiculo as $tipo_vehiculo)
                        <label class="vc-vehicle">
                            <input type="radio" name="id_tipo_vehiculo" class="vc-vehicle-radio" value="{{ $tipo_vehiculo->id }}" data-url="{{ route('paquete.packagesByVehicleType', [$tipo_vehiculo->id]) }}">
                            <div class="vc-vehicle-box">
                                <img src="{{ asset($tipo_vehiculo->imagen) }}" alt="{{ $tipo_vehiculo->descripcion }}">
                                <span>{{ $tipo_vehiculo->descripcion }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div id="vc-packages-wrap" style="display:none;">
                        <label class="vc-sub-label mt-3">Combo o servicio</label>
                        <div class="vc-packages" id="vc-packages"></div>
                    </div>
                </div>
            </section>

            {{-- 3. Productos --}}
            <section class="vc-card">
                <div class="vc-card-head"><span class="vc-step" id="vc-step-products">3</span> Productos <small>aceites, filtros, aditivos…</small></div>
                <div class="vc-card-body">
                    <select id="pos-product" style="width:100%;">
                        <option value=""></option>
                        @foreach($productos as $producto)
                            @php $stock = intval($producto->cant_disponible); @endphp
                            <option value="{{ $producto->id }}"
                                data-nombre="{{ $producto->producto }}"
                                data-marca="{{ $producto->marca }}"
                                data-tipo="{{ $producto->tipo_producto }}"
                                data-unidad="{{ $producto->presentacion }}"
                                data-price="{{ floatval($producto->precio_venta) }}"
                                data-stock="{{ $stock }}"
                                {{ $stock <= 0 ? 'disabled' : '' }}>{{ $producto->producto }} {{ $producto->marca }} {{ $producto->tipo_producto }}</option>
                        @endforeach
                    </select>

                    <div class="pos-selected" id="pos-selected" style="display:none;">
                        <div class="pos-sel-info">
                            <div class="pos-sel-name" id="pos-sel-name"></div>
                            <div class="pos-sel-meta" id="pos-sel-meta"></div>
                        </div>
                        <div class="pos-sel-stat"><span>Precio</span><b id="pos-sel-price"></b></div>
                        <div class="pos-sel-stat"><span>Disponible</span><b id="pos-sel-stock"></b></div>
                        <div class="pos-sel-stat">
                            <span>Cantidad</span>
                            <div class="pos-qty">
                                <button type="button" class="pos-qty-btn" data-step="-1">&minus;</button>
                                <input type="number" id="pos-qty" min="1" value="1">
                                <button type="button" class="pos-qty-btn" data-step="1">+</button>
                            </div>
                        </div>
                        <div class="pos-sel-stat"><span>Subtotal</span><b class="vc-red" id="pos-sel-subtotal"></b></div>
                        <button type="button" class="vc-btn-add" id="pos-add"><i class="mdi mdi-cart-plus"></i> Agregar</button>
                    </div>
                </div>
            </section>
        </div>

        {{-- ════════════ RESUMEN (sticky) ════════════ --}}
        <div class="col-lg-4">
            <aside class="vc-summary">
                <div class="vc-summary-head">
                    <i class="mdi mdi-receipt"></i> Resumen de la venta
                    <span class="vc-count" id="vc-count">0</span>
                </div>

                <div class="vc-items" id="vc-items">
                    <div class="vc-empty" id="vc-empty">
                        <i class="mdi mdi-cart-outline"></i>
                        <p>Selecciona un combo o agrega productos</p>
                    </div>
                </div>

                {{-- Ficha de aceite: junto al total, aparece al agregar productos --}}
                <div class="vc-km" id="card-km-aceite" style="display:none;">
                    <div class="vc-km-title"><i class="mdi mdi-oil"></i> Ficha de cambio de aceite <small>(opcional)</small></div>
                    <div class="vc-km-grid">
                        <div>
                            <label for="input_km_actual">Km actual</label>
                            <input type="number" name="km_actual" id="input_km_actual" placeholder="80000" value="{{ old('km_actual') }}">
                        </div>
                        <div>
                            <label for="input_km_proximo">Próximo cambio</label>
                            <input type="number" name="km_proximo_cambio" id="input_km_proximo" placeholder="85000" value="{{ old('km_proximo_cambio') }}">
                        </div>
                    </div>
                    <small class="vc-km-hint">Con km podrás imprimir el sticker para el vehículo.</small>
                </div>

                <div class="vc-total">
                    <span>Total</span>
                    <strong id="text_importe_total">$ 0</strong>
                </div>

                <button type="submit" id="btn_create_sell" class="vc-btn-submit">
                    <i class="mdi mdi-check-circle"></i> Generar venta
                </button>
            </aside>
        </div>
    </div>
</div>
</form>

@endsection

@push('style')
<style>
.vc-wrap { --vc-dark:#111827; --vc-navy:#1f2937; --vc-red:#c0392b; --vc-red2:#e74c3c; --vc-line:#e5e7eb; --vc-muted:#6b7280; }
.vc-red { color: var(--vc-red); }

/* Encabezado */
.vc-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
.vc-head-title { display:flex; align-items:center; gap:12px; }
.vc-head-icon { width:46px; height:46px; border-radius:12px; background:var(--vc-red); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 6px 16px rgba(192,57,43,.35); }
.vc-head h3 { margin:0; font-weight:800; color:var(--vc-dark); font-size:1.35rem; }
.vc-head span { font-size:.78rem; color:var(--vc-muted); text-transform:uppercase; letter-spacing:.5px; }
.vc-head-date { background:#fff; border:1px solid var(--vc-line); border-radius:20px; padding:6px 14px; font-size:.8rem; font-weight:600; color:var(--vc-navy); }
.vc-alert { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; border-radius:10px; padding:10px 14px; margin-bottom:14px; font-size:.85rem; }

/* Tarjetas */
.vc-card { background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:18px; overflow:hidden; }
.vc-card-head { padding:14px 20px; border-bottom:1px solid var(--vc-line); font-weight:800; color:var(--vc-dark); display:flex; align-items:center; gap:10px; font-size:.95rem; }
.vc-card-head small { font-weight:500; color:var(--vc-muted); }
.vc-step { width:26px; height:26px; border-radius:50%; background:var(--vc-dark); color:#fff; font-size:.78rem; display:inline-flex; align-items:center; justify-content:center; }
.vc-card-body { padding:18px 20px; }

/* Campos */
.vc-field { margin-bottom:14px; }
.vc-field label, .vc-sub-label { display:block; font-size:.7rem; font-weight:800; color:var(--vc-muted); text-transform:uppercase; letter-spacing:.6px; margin-bottom:6px; }
.vc-input { width:100%; height:42px; border:1.5px solid var(--vc-line); border-radius:10px; padding:0 12px; font-size:.9rem; transition:border-color .15s, box-shadow .15s; }
.vc-input:focus { outline:none; border-color:var(--vc-red); box-shadow:0 0 0 3px rgba(192,57,43,.12); }

/* Placa colombiana */
.vc-plate { display:flex; flex-direction:column; align-items:center; background:#f5c400; border:2.5px solid #111; border-radius:8px; padding:3px 10px 2px; max-width:200px; box-shadow:1px 2px 6px rgba(0,0,0,.25); }
.vc-plate input { width:100%; border:none; background:transparent; text-align:center; font-family:'Arial Black',Arial,sans-serif; font-size:1.35rem; font-weight:900; letter-spacing:4px; text-transform:uppercase; color:#000; outline:none; padding:0; }
.vc-plate input::placeholder { color:rgba(0,0,0,.3); }
.vc-plate span { font-size:.5rem; font-weight:800; letter-spacing:3px; border-top:1px solid #000; width:100%; text-align:center; }

/* Select2 alineado al estilo */
.vc-wrap .select2-container .select2-selection--single { height:42px; border:1.5px solid var(--vc-line); border-radius:10px; }
.vc-wrap .select2-container .select2-selection--single .select2-selection__rendered { line-height:40px; padding-left:12px; }
.vc-wrap .select2-container .select2-selection--single .select2-selection__arrow { height:40px; }

/* Segmento tipo de venta */
.vc-segment { display:inline-flex; background:#f3f4f6; border-radius:12px; padding:4px; gap:4px; flex-wrap:wrap; }
.vc-segment button { border:none; background:transparent; padding:8px 16px; border-radius:9px; font-weight:700; font-size:.82rem; color:var(--vc-muted); cursor:pointer; transition:all .15s; }
.vc-segment button.active { background:#fff; color:var(--vc-red); box-shadow:0 2px 8px rgba(0,0,0,.08); }

/* Vehículos */
.vc-vehicles { display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:12px; }
.vc-vehicle { margin:0; cursor:pointer; }
.vc-vehicle input { display:none; }
.vc-vehicle-box { border:2px solid var(--vc-line); border-radius:12px; padding:10px; text-align:center; transition:all .15s; background:#fff; }
.vc-vehicle-box img { width:100%; height:70px; object-fit:contain; }
.vc-vehicle-box span { display:block; margin-top:6px; font-size:.75rem; font-weight:800; text-transform:uppercase; color:var(--vc-navy); letter-spacing:.5px; }
.vc-vehicle:hover .vc-vehicle-box { border-color:#9ca3af; }
.vc-vehicle input:checked + .vc-vehicle-box { border-color:var(--vc-red); background:#fef5f5; box-shadow:0 4px 14px rgba(192,57,43,.18); }

/* Combos */
.vc-packages { display:grid; grid-template-columns:repeat(auto-fill, minmax(190px, 1fr)); gap:12px; }
.vc-package { margin:0; cursor:pointer; }
.vc-package input { display:none; }
.vc-package-box { border:2px solid var(--vc-line); border-radius:12px; overflow:hidden; height:100%; display:flex; flex-direction:column; transition:all .15s; background:#fff; }
.vc-package-top { padding:10px 12px; color:#fff; display:flex; justify-content:space-between; align-items:center; gap:6px; }
.vc-package-top b { text-transform:uppercase; font-size:.9rem; letter-spacing:.5px; text-shadow:0 1px 2px rgba(0,0,0,.4); }
.vc-package-price { background:rgba(0,0,0,.35); border-radius:8px; padding:2px 8px; font-weight:800; font-size:.85rem; white-space:nowrap; }
.vc-package-services { padding:10px 12px; font-size:.75rem; color:var(--vc-muted); line-height:1.5; flex:1; }
.vc-package:hover .vc-package-box { transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.1); }
.vc-package input:checked + .vc-package-box { border-color:var(--vc-red); box-shadow:0 0 0 3px rgba(192,57,43,.2); }
.vc-package input:checked + .vc-package-box .vc-package-services::before { content:'✓ Seleccionado'; display:block; color:var(--vc-red); font-weight:800; margin-bottom:4px; }
.vc-loading { color:var(--vc-muted); font-size:.85rem; }

/* Selector de productos */
.pos-opt { display:flex; align-items:center; gap:10px; }
.pos-opt-main { flex:1; min-width:0; }
.pos-opt-name { font-weight:700; text-transform:uppercase; }
.pos-opt-meta { font-size:.72rem; opacity:.7; }
.pos-opt-price { font-weight:800; white-space:nowrap; }
.pos-opt-stock { font-size:.68rem; font-weight:700; border-radius:10px; padding:2px 8px; white-space:nowrap; background:#dcfce7; color:#166534; }
.pos-opt-stock.low { background:#fef3c7; color:#92400e; }
.pos-opt-stock.out { background:#fee2e2; color:#991b1b; }
.pos-selected { display:flex; align-items:center; flex-wrap:wrap; gap:18px; background:#f8fafc; border:1px solid var(--vc-line); border-radius:12px; padding:14px 18px; margin-top:14px; }
.pos-sel-info { flex:1; min-width:160px; }
.pos-sel-name { font-weight:800; text-transform:uppercase; }
.pos-sel-meta { font-size:.75rem; color:var(--vc-muted); }
.pos-sel-stat { display:flex; flex-direction:column; align-items:center; }
.pos-sel-stat span { font-size:.62rem; text-transform:uppercase; letter-spacing:.5px; color:#9ca3af; font-weight:800; }
.pos-qty { display:flex; align-items:center; gap:4px; }
.pos-qty input { width:52px; text-align:center; border:1px solid #d1d5db; border-radius:8px; height:32px; font-weight:700; }
.pos-qty-btn { width:30px; height:32px; border:1px solid #d1d5db; background:#fff; border-radius:8px; font-weight:800; cursor:pointer; }
.vc-btn-add { border:none; background:var(--vc-dark); color:#fff; border-radius:10px; padding:9px 16px; font-weight:700; font-size:.85rem; cursor:pointer; }
.vc-btn-add:hover { background:var(--vc-red); }
.vc-btn-add:disabled { opacity:.4; cursor:not-allowed; }

/* Resumen */
.vc-summary { position:sticky; top:80px; background:var(--vc-dark); color:#fff; border-radius:16px; padding:18px; box-shadow:0 10px 30px rgba(17,24,39,.25); margin-bottom:18px; }
.vc-summary-head { display:flex; align-items:center; gap:8px; font-weight:800; font-size:.95rem; padding-bottom:12px; border-bottom:1px solid rgba(255,255,255,.1); }
.vc-count { margin-left:auto; background:var(--vc-red); border-radius:10px; font-size:.7rem; padding:2px 9px; }
.vc-items { max-height:42vh; overflow-y:auto; margin:6px -4px; padding:0 4px; }
.vc-empty { text-align:center; padding:24px 0; color:rgba(255,255,255,.4); }
.vc-empty i { font-size:2rem; }
.vc-empty p { margin:4px 0 0; font-size:.8rem; }
.vc-item { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.07); }
.vc-item-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(255,255,255,.08); }
.vc-item-icon.pkg { background:rgba(192,57,43,.25); color:#ff8a80; }
.vc-item-main { flex:1; min-width:0; }
.vc-item-name { font-weight:700; font-size:.82rem; text-transform:uppercase; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.vc-item-sub { font-size:.7rem; color:rgba(255,255,255,.5); }
.vc-item-qty { display:flex; align-items:center; gap:6px; }
.vc-item-qty a { color:rgba(255,255,255,.6); font-size:1.05rem; cursor:pointer; }
.vc-item-qty a:hover { color:#fff; }
.vc-item-qty b { min-width:16px; text-align:center; font-size:.82rem; }
.vc-item-total { font-weight:800; font-size:.85rem; min-width:72px; text-align:right; }
.vc-item-del { color:rgba(255,255,255,.35); cursor:pointer; font-size:1.1rem; }
.vc-item-del:hover { color:var(--vc-red2); }

.vc-km { background:rgba(245,158,11,.12); border:1px solid rgba(245,158,11,.35); border-radius:12px; padding:12px; margin-top:10px; }
.vc-km-title { font-weight:800; font-size:.8rem; color:#fbbf24; margin-bottom:8px; }
.vc-km-title small { color:rgba(255,255,255,.45); font-weight:500; }
.vc-km-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.vc-km label { display:block; font-size:.62rem; text-transform:uppercase; letter-spacing:.5px; color:rgba(255,255,255,.55); font-weight:800; margin-bottom:3px; }
.vc-km input { width:100%; height:38px; border-radius:8px; border:1px solid rgba(255,255,255,.15); background:rgba(255,255,255,.07); color:#fff; padding:0 10px; font-weight:700; }
.vc-km input:focus { outline:none; border-color:#fbbf24; }
.vc-km-hint { display:block; margin-top:6px; color:rgba(255,255,255,.45); font-size:.68rem; }

.vc-total { display:flex; justify-content:space-between; align-items:baseline; padding:14px 0 12px; margin-top:8px; border-top:1px dashed rgba(255,255,255,.2); }
.vc-total span { font-size:.75rem; text-transform:uppercase; letter-spacing:1px; color:rgba(255,255,255,.6); font-weight:800; }
.vc-total strong { font-size:1.8rem; font-weight:900; }
.vc-btn-submit { width:100%; border:none; border-radius:12px; padding:13px; background:linear-gradient(135deg, var(--vc-red), var(--vc-red2)); color:#fff; font-weight:800; font-size:.95rem; letter-spacing:.5px; text-transform:uppercase; cursor:pointer; box-shadow:0 6px 18px rgba(192,57,43,.4); transition:transform .15s; }
.vc-btn-submit:hover { transform:translateY(-1px); }
.vc-btn-submit:disabled { opacity:.6; cursor:wait; }

@media (max-width: 991px) {
    .vc-summary { position:static; }
}
</style>
@endpush

@push('custom-scripts')
    {!! Html::script('lib/sell_products.js') !!}
@endpush
