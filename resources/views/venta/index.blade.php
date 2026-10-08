@extends('layout.master')
@section('content')

<style>
/* ── Paleta de marca ────────────────────────────────────── */
:root {
    --brand-red   : #c0392b;
    --brand-dark  : #2c3e50;
    --brand-light : #f4f6f8;
}

/* ── Layout general ─────────────────────────────────────── */
.venta-card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }

/* ── Filtro de fechas ───────────────────────────────────── */
.date-pills .btn { border-radius: 20px; font-size: .75rem; font-weight: 600;
    padding: 4px 14px; letter-spacing: .3px; transition: all .2s; }
.date-pills .btn.active, .date-pills .btn:active {
    background: var(--brand-red); border-color: var(--brand-red); color: #fff; }

/* ── Tabs ───────────────────────────────────────────────── */
.venta-tabs { border-bottom: 2px solid #e0e0e0; }
.venta-tabs .nav-link {
    font-weight: 700; font-size: .82rem; letter-spacing: .4px; color: #666;
    border: none; padding: 10px 20px; border-bottom: 3px solid transparent; margin-bottom: -2px;
    text-transform: uppercase; transition: color .2s, border-color .2s;
}
.venta-tabs .nav-link:hover  { color: var(--brand-red); }
.venta-tabs .nav-link.active {
    color: var(--brand-red); border-bottom-color: var(--brand-red); background: transparent;
}
.venta-tabs .badge { font-size: .65rem; border-radius: 8px; padding: 2px 7px; }

/* ── Tabla ──────────────────────────────────────────────── */
.table-venta thead th {
    font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
    color: #fff; background: var(--brand-dark); white-space: nowrap; padding: 10px 8px; border: none;
}
.table-venta tbody tr { transition: background .15s; }
.table-venta tbody tr:hover { background: #fef5f5 !important; }
.table-venta td { vertical-align: middle; font-size: .82rem; padding: 7px 8px; border-color: #f0f0f0; }
.table-venta tbody tr:nth-child(even) { background: #fafafa; }

/* ── Placa colombiana (fondo amarillo) ──────────────────── */
.placa-co {
    display: inline-flex; flex-direction: column; align-items: center;
    background: #f5c400;
    border: 2.5px solid #111; border-radius: 4px;
    padding: 3px 9px 2px; text-align: center; min-width: 80px;
    box-shadow: 1px 2px 6px rgba(0,0,0,.35);
    font-family: 'Arial Black', 'Arial', sans-serif; line-height: 1;
}
.placa-co .placa-num {
    display: block; font-size: 15px; font-weight: 900;
    letter-spacing: 3px; color: #000; text-transform: uppercase;
}
.placa-co .placa-pais {
    display: block; font-size: 6px; font-weight: 700; color: #000;
    letter-spacing: 2px; text-transform: uppercase;
    border-top: 1px solid #000; width: 100%;
    text-align: center; margin-top: 2px; padding-top: 1px;
}
.placa-co.sin-placa { background: #e8e8e8; border-color: #bbb; }
.placa-co.sin-placa .placa-num  { color: #aaa; font-size: 11px; letter-spacing: 1px; }
.placa-co.sin-placa .placa-pais { color: #bbb; border-color: #ccc; }

/* ── Badge estado ───────────────────────────────────────── */
.estado-pill { font-size: .68rem; border-radius: 10px; padding: 3px 9px;
    font-weight: 700; letter-spacing: .3px; text-transform: uppercase; }

/* ── Cliente link ───────────────────────────────────────── */
.td-cliente { font-weight: 700; color: var(--brand-dark); text-transform: uppercase; font-size: .8rem; }
.td-num     { font-weight: 800; color: var(--brand-dark); }

/* ── Acciones ───────────────────────────────────────────── */
.btn-acc { display: inline-flex; align-items:center; justify-content:center;
    width: 28px; height: 28px; border-radius: 6px; border: 1.5px solid transparent;
    transition: all .15s; margin: 0 1px; }
.btn-acc:hover { transform: scale(1.15); }
.btn-acc-view   { border-color: #f39c12; color: #f39c12; }
.btn-acc-edit   { border-color: #2980b9; color: #2980b9; }
.btn-acc-user   { border-color: #27ae60; color: #27ae60; }

/* ── Exportar dropdown ──────────────────────────────────── */
.btn-export {
    background: linear-gradient(135deg, var(--brand-dark) 0%, #3d5166 100%);
    color: #fff; border: none; border-radius: 22px;
    padding: 6px 18px; font-size: .78rem; font-weight: 600;
    letter-spacing: .3px; cursor: pointer;
    box-shadow: 0 3px 10px rgba(44,62,80,.35);
    transition: all .2s ease; display: inline-flex; align-items: center; gap: 6px;
}
.btn-export:hover, .btn-export:focus {
    background: linear-gradient(135deg, var(--brand-red) 0%, #e74c3c 100%);
    box-shadow: 0 5px 15px rgba(192,57,43,.45);
    transform: translateY(-1px); color: #fff; outline: none;
}
.btn-export .mdi { font-size: 1rem; }
.dropdown-menu.export-menu {
    border: none; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,.15);
    padding: 6px; min-width: 150px;
}
.dropdown-menu.export-menu .dropdown-item {
    border-radius: 7px; font-size: .8rem; padding: 7px 12px;
    font-weight: 500; transition: background .15s;
}
.dropdown-menu.export-menu .dropdown-item:hover { background: #f4f6f8; }

/* DataTables overrides */
#table-servicios_filter label, #table-productos_filter label { font-size:.8rem; }
#table-servicios_filter input, #table-productos_filter input {
    border-radius: 20px; border: 1.5px solid #ddd; padding: 3px 10px; font-size: .8rem; outline: none; }
#table-servicios_info, #table-productos_info { font-size: .75rem; color: #888; }
#table-servicios_paginate .paginate_button,
#table-productos_paginate  .paginate_button {
    border-radius: 6px !important; font-size: .75rem; padding: 3px 8px !important; }
#table-servicios_paginate .paginate_button.current,
#table-productos_paginate  .paginate_button.current {
    background: var(--brand-red) !important; color: #fff !important;
    border-color: var(--brand-red) !important; }
</style>

<div class="card venta-card">
<div class="card-body p-4">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold" style="color:var(--brand-dark);">REGISTRO DE VENTAS</h4>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('venta.create') }}" class="btn btn-sm btn-dark mr-2" title="Registrar lavado">
                <span class="mdi mdi-car-wash mr-1"></span>Carwash
            </a>
            <a href="{{ route('venta.create-market') }}" class="btn btn-sm btn-outline-dark" title="Registrar venta tienda">
                <span class="mdi mdi-shopping mr-1"></span>Tienda
            </a>
        </div>
    </div>

    {{-- ── Filtro de fechas ─────────────────────────────────── --}}
    <div class="d-flex align-items-center mb-3 date-pills" id="date-filter-row">
        <small class="text-muted mr-2 font-weight-600">Periodo:</small>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary active" data-range="today">Hoy</button>
            <button class="btn btn-outline-secondary" data-range="week">Semana</button>
            <button class="btn btn-outline-secondary" data-range="month">Mes</button>
            <button class="btn btn-outline-secondary" data-range="all">Todo</button>
        </div>
    </div>

    {{-- ── Tabs + Export ────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-0">
        <ul class="nav venta-tabs" id="ventas-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-servicios" role="tab">
                    <span class="mdi mdi-car-wash mr-1"></span>Servicios Carwash
                    <span class="badge badge-danger ml-1" id="badge-servicios">…</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-productos" role="tab">
                    <span class="mdi mdi-shopping mr-1"></span>Ventas Productos
                    <span class="badge badge-success ml-1" id="badge-productos">…</span>
                </a>
            </li>
        </ul>
        <div class="dropdown mb-1">
            <button class="btn-export dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">
                <span class="mdi mdi-export-variant"></span>
                Exportar
            </button>
            <div class="dropdown-menu dropdown-menu-right export-menu">
                <a class="dropdown-item export-btn" data-type="0" href="#">
                    <span class="mdi mdi-microsoft-excel text-success mr-2"></span>Excel (.xlsx)
                </a>
                <a class="dropdown-item export-btn" data-type="1" href="#">
                    <span class="mdi mdi-file-pdf-box text-danger mr-2"></span>PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ── Tab content ──────────────────────────────────────── --}}
    <div class="tab-content">

        {{-- Tab: Servicios --}}
        <div class="tab-pane fade show active" id="tab-servicios" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-venta mb-0" id="table-servicios">
                    <thead>
                        <tr>
                            <th style="width:55px">#</th>
                            <th style="width:118px">Fecha</th>
                            <th>Cliente</th>
                            <th style="width:105px">Placa</th>
                            <th style="width:115px">Vehículo</th>
                            <th>Servicio</th>
                            <th style="width:155px">Prestador</th>
                            <th style="width:108px">Total</th>
                            <th style="width:90px">Estado</th>
                            <th style="width:85px" class="text-center">Acc.</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- Tab: Ventas Productos --}}
        <div class="tab-pane fade" id="tab-productos" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-venta mb-0" id="table-productos">
                    <thead>
                        <tr>
                            <th style="width:55px">#</th>
                            <th style="width:118px">Fecha</th>
                            <th style="width:130px">Cliente</th>
                            <th>Productos</th>
                            <th style="width:155px">Atendido por</th>
                            <th style="width:108px">Total</th>
                            <th style="width:65px" class="text-center">Acc.</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>{{-- /tab-content --}}
</div>{{-- /card-body --}}

@include('venta.mdl_changeUser')
@if(session('success'))<input type="hidden" id="succes_message" value="{{ session('success') }}">@endif
@if(session('fail'))<input type="hidden" id="fail_message" value="{{ session('fail') }}">@endif
</div>{{-- /card --}}

@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/sell.js') !!}
@endpush
