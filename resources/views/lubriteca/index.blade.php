@extends('layout.master')

@push('style')
<style>
/* ═══════════════════════════════════════════
   LUBRITECA POS — Flujo 2 pasos
   ═══════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }
.footer-block, .main-panel > footer { display: none !important; }

/* ── Raíz fija ── */
#pos-root {
    position: fixed;
    top: 62px; left: 235px; right: 0; bottom: 0;
    z-index: 100;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    background: #F4F6F9;
    overflow: hidden;
}

/* ══════════════════════════════
   PASO 1 — CATÁLOGO
   ══════════════════════════════ */
#pos-catalog {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

/* Header */
.cat-header {
    background: #fff;
    border-bottom: 1px solid #E4E7EB;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}

.cat-brand {
    display: flex; align-items: center; gap: 9px; flex-shrink: 0;
}

.cat-brand-icon {
    width: 38px; height: 38px; background: #C0392B;
    border-radius: 10px; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
}
.cat-brand-icon svg { fill: #fff; }
.cat-brand-text { line-height: 1.2; }
.cat-brand-text strong { display: block; font-size: .88rem; font-weight: 800; color: #1A202C; }
.cat-brand-text small  { font-size: .6rem; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: .5px; }

.cat-search { flex: 1; position: relative; }
.cat-search svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9CA3AF; pointer-events: none; }
.cat-search input {
    width: 100%; height: 36px; padding: 0 12px 0 34px;
    border: 1.5px solid #E4E7EB; border-radius: 8px;
    font-size: .82rem; color: #1A202C; background: #F9FAFB;
    outline: none; transition: border-color .15s;
}
.cat-search input:focus { border-color: #C0392B; background: #fff; }
.cat-search input::placeholder { color: #BEC3CC; }

.cat-scanner-link {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 0 12px; height: 36px; border: 1.5px solid #E4E7EB;
    border-radius: 8px; color: #6B7280 !important; background: #F9FAFB;
    text-decoration: none !important; white-space: nowrap;
    font-size: .72rem; font-weight: 600; flex-shrink: 0;
    transition: border-color .15s, color .15s;
}
.cat-scanner-link:hover { border-color: #C0392B; color: #C0392B !important; }

/* Subheader: columnas */
.list-header {
    display: grid;
    grid-template-columns: 56px 1fr 96px 72px 108px;
    gap: 12px;
    padding: 5px 16px;
    background: #F8FAFC;
    border-bottom: 1px solid #E4E7EB;
    flex-shrink: 0;
}

.list-header span {
    font-size: .57rem; font-weight: 800; color: #9CA3AF;
    text-transform: uppercase; letter-spacing: .8px;
}

.list-header .lh-qty { text-align: center; }
.list-header .lh-price { text-align: right; }

/* Lista de productos */
#prod-list {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
}

#prod-list::-webkit-scrollbar { width: 5px; }
#prod-list::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

/* Fila de producto */
.prod-row {
    display: grid;
    grid-template-columns: 56px 1fr 96px 72px 108px;
    gap: 12px;
    align-items: center;
    padding: 7px 16px;
    border-bottom: 1px solid #F1F5F9;
    transition: background .1s;
    border-left: 3px solid transparent;
    background: #fff;
}

.prod-row:hover { background: #FAFBFC; }

.prod-row.active {
    background: #FFF5F5;
    border-left-color: #C0392B;
}

/* Imagen del producto */
.pr-img {
    width: 50px; height: 50px; border-radius: 8px;
    overflow: hidden; background: #F4F6F9;
    border: 1px solid #E8EDF3; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}

.pr-img img { width: 100%; height: 100%; object-fit: cover; display: block; }

.pr-img .pr-ph {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 1px;
    background: linear-gradient(135deg, #F8FAFC, #EEF2F7);
}
.pr-img .pr-ph svg { width: 22px; height: 22px; fill: #D1D5DB; }
.pr-img .pr-ph span { font-size: .42rem; color: #D1D5DB; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }

/* Info del producto */
.pr-info { min-width: 0; }

.pr-code {
    font-size: .6rem; color: #9CA3AF; font-weight: 600;
    letter-spacing: .3px; margin-bottom: 1px;
}

.pr-name {
    font-size: .78rem; font-weight: 700; color: #1A202C;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 2px;
}

.pr-meta {
    font-size: .62rem; color: #9CA3AF;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Precio */
.pr-price {
    font-size: .82rem; font-weight: 800; color: #C0392B;
    font-variant-numeric: tabular-nums;
    text-align: right; white-space: nowrap;
}

/* Stock */
.pr-stock {
    text-align: center;
}

.pr-stock-badge {
    display: inline-block; font-size: .62rem; font-weight: 700;
    padding: 2px 7px; border-radius: 99px; white-space: nowrap;
}
.pr-stock-badge.ok  { background: #DCFCE7; color: #15803D; }
.pr-stock-badge.low { background: #FEF9C3; color: #A16207; }
.pr-stock-badge.out { background: #FEE2E2; color: #DC2626; }

/* Controles de cantidad */
.pr-qty {
    display: flex; align-items: center; gap: 5px; justify-content: center;
}

.pr-qty-btn {
    width: 26px; height: 26px; border-radius: 50%;
    border: 1.5px solid #E4E7EB; background: #F9FAFB;
    color: #6B7280; font-size: .9rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .12s; padding: 0; line-height: 1; font-weight: 700;
}
.pr-qty-btn:hover { border-color: #C0392B; color: #C0392B; background: #FFF5F5; }
.prod-row.active .pr-qty-btn { border-color: #C0392B; }

.pr-qty-val {
    min-width: 24px; text-align: center;
    font-size: .82rem; font-weight: 800; color: #1A202C;
    font-variant-numeric: tabular-nums;
}

/* Estado vacío / cargando */
.prod-list-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 60px 20px;
    color: #BEC3CC; gap: 12px; text-align: center;
}
.prod-list-empty svg { width: 56px; height: 56px; fill: #E2E8F0; }
.prod-list-empty p { font-size: .88rem; color: #9CA3AF; margin: 0; }

@@keyframes spin { to { transform: rotate(360deg); } }

/* ══════════════════════════════
   BARRA FLOTANTE
   ══════════════════════════════ */
#float-bar {
    position: fixed;
    bottom: -80px;
    left: 235px; right: 0;
    z-index: 150;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    background: linear-gradient(135deg, #1A202C 0%, #2D3748 100%);
    box-shadow: 0 -4px 24px rgba(0,0,0,.25);
    transition: bottom .3s cubic-bezier(.4,0,.2,1);
    gap: 16px;
}

#float-bar.visible { bottom: 0; }

.fb-info {
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0;
}

.fb-icon {
    width: 36px; height: 36px; background: rgba(255,255,255,.1);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.fb-icon svg { fill: #fff; width: 18px; height: 18px; }

.fb-text { line-height: 1.2; }
.fb-text strong { display: block; font-size: .9rem; font-weight: 800; color: #fff; }
.fb-text small   { font-size: .68rem; color: #9CA3AF; }

.fb-total {
    font-size: 1.3rem; font-weight: 900; color: #fff;
    font-variant-numeric: tabular-nums; letter-spacing: -.5px;
}

#btn-ver-ticket {
    display: flex; align-items: center; gap: 8px;
    padding: 11px 24px;
    background: #C0392B; color: #fff;
    border: none; border-radius: 10px;
    font-size: .88rem; font-weight: 800;
    cursor: pointer; letter-spacing: .3px;
    text-transform: uppercase;
    transition: background .15s, transform .12s;
    white-space: nowrap; flex-shrink: 0;
}
#btn-ver-ticket:hover { background: #9B2335; transform: scale(1.02); }

/* ══════════════════════════════
   PASO 2 — PANEL TICKET
   ══════════════════════════════ */
#ticket-panel {
    position: fixed;
    top: 62px; left: 235px; right: 0; bottom: 0;
    z-index: 200;
    display: flex;
    flex-direction: column;
    background: #fff;
    transform: translateX(100%);
    transition: transform .32s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
}

#ticket-panel.open { transform: translateX(0); }

/* Top bar del ticket */
.tkt-topbar {
    display: flex; align-items: center; gap: 16px;
    padding: 10px 20px;
    background: #fff;
    border-bottom: 1px solid #E4E7EB;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}

#btn-back {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px; border: 1.5px solid #E4E7EB;
    border-radius: 8px; background: #F9FAFB;
    color: #374151; font-size: .78rem; font-weight: 700;
    cursor: pointer; transition: all .15s; flex-shrink: 0;
}
#btn-back:hover { border-color: #C0392B; color: #C0392B; background: #FFF5F5; }

.tkt-topbar h2 {
    font-size: .9rem; font-weight: 800; color: #1A202C;
    margin: 0; flex: 1;
}

.tkt-topbar .tkt-date {
    font-size: .68rem; color: #9CA3AF;
    font-variant-numeric: tabular-nums;
    flex-shrink: 0;
}

/* Cuerpo del ticket — 2 columnas */
.tkt-body {
    flex: 1;
    display: grid;
    grid-template-columns: 320px 1fr;
    overflow: hidden;
    min-height: 0;
}

/* ── Columna izquierda: formulario cliente ── */
.tkt-left {
    background: #0F172A;
    display: flex; flex-direction: column;
    overflow-y: auto; overflow-x: hidden;
    padding: 20px 18px;
    gap: 0;
    border-right: 1px solid #1E293B;
}

.tkt-left::-webkit-scrollbar { width: 4px; }
.tkt-left::-webkit-scrollbar-thumb { background: #1E293B; }

.tkt-brand-mini {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 18px; padding-bottom: 16px;
    border-bottom: 1px dashed rgba(255,255,255,.07);
}

.tkt-brand-mini .ico {
    width: 38px; height: 38px; background: #C0392B;
    border-radius: 9px; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
}
.tkt-brand-mini .ico svg { fill: #fff; }
.tkt-brand-mini strong { display: block; font-size: .8rem; font-weight: 800; color: #F1F5F9; }
.tkt-brand-mini small   { font-size: .6rem; color: #475569; text-transform: uppercase; letter-spacing: .5px; }

.tkt-section-title {
    font-size: .57rem; font-weight: 800; letter-spacing: 1.5px;
    text-transform: uppercase; color: #475569;
    margin-bottom: 10px; margin-top: 16px;
}
.tkt-section-title:first-of-type { margin-top: 0; }

.tkt-field {
    display: flex; flex-direction: column; gap: 3px;
    margin-bottom: 8px;
}

.tkt-field label {
    font-size: .54rem; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: .8px;
}

.tkt-field input {
    background: #1E293B; border: 1px solid #334155;
    border-radius: 7px; color: #E2E8F0;
    font-size: .8rem; padding: 7px 10px;
    outline: none; transition: border-color .15s; width: 100%;
}
.tkt-field input:focus { border-color: #C0392B; }
.tkt-field input::placeholder { color: #334155; }

.tkt-fields-2 {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
}

.tkt-user-chip {
    display: flex; align-items: center; gap: 8px;
    background: #1E293B; border: 1px solid #334155;
    border-radius: 7px; padding: 7px 10px; margin-top: 4px;
}
.tkt-user-chip svg { fill: #475569; width: 14px; height: 14px; flex-shrink: 0; }
.tkt-user-chip span { font-size: .78rem; color: #94A3B8; font-weight: 600; }

/* ── Columna derecha: productos ── */
.tkt-right {
    display: flex; flex-direction: column; overflow: hidden;
    background: #F8FAFC;
}

.tkt-products-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px 8px;
    flex-shrink: 0;
}

.tkt-products-header h3 {
    font-size: .8rem; font-weight: 800; color: #1A202C; margin: 0;
}

.tkt-products-header .cnt {
    font-size: .7rem; font-weight: 700; color: #C0392B;
    background: #FFF5F5; padding: 2px 8px; border-radius: 99px;
}

/* Tabla de productos */
.tkt-table-wrap {
    flex: 1; min-height: 0;
    overflow-y: auto; padding: 0 20px 8px;
}
.tkt-table-wrap::-webkit-scrollbar { width: 4px; }
.tkt-table-wrap::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 3px; }

table.tkt-t {
    width: 100%; border-collapse: collapse;
    font-size: .76rem; background: #fff;
    border-radius: 10px; overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

table.tkt-t thead th {
    background: #1A202C; color: #9CA3AF;
    font-size: .56rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 8px 10px; white-space: nowrap; text-align: left;
}

table.tkt-t thead th.tc { text-align: center; }
table.tkt-t thead th.tr { text-align: right; }

table.tkt-t tbody td {
    padding: 8px 10px;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle; color: #374151;
}

table.tkt-t tbody tr:last-child td { border-bottom: none; }
table.tkt-t tbody tr:hover td { background: #FAFBFC; }

.tc-name  { font-weight: 600; color: #1A202C; max-width: 180px; }
.tc-brand { font-size: .64rem; color: #9CA3AF; }
.tc-price { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; color: #6B7280; }
.tc-sub   { text-align: right; font-weight: 700; color: #1A202C; font-variant-numeric: tabular-nums; white-space: nowrap; }
.tc-del   { text-align: center; }

.qty-ctrl-t { display: flex; align-items: center; gap: 5px; justify-content: center; }

.qty-btn-t {
    width: 24px; height: 24px; border-radius: 50%;
    border: 1.5px solid #E4E7EB; background: #F9FAFB;
    color: #6B7280; font-size: .85rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .12s; padding: 0; line-height: 1; font-weight: 700;
}
.qty-btn-t:hover { border-color: #C0392B; color: #C0392B; background: #FFF5F5; }

.qty-v-t {
    min-width: 22px; text-align: center; font-size: .82rem;
    font-weight: 800; color: #1A202C; font-variant-numeric: tabular-nums;
}

.del-btn {
    background: none; border: none; color: #E2E8F0;
    cursor: pointer; font-size: .85rem; padding: 2px 4px;
    transition: color .12s; line-height: 1;
}
.del-btn:hover { color: #EF4444; }

/* Footer del ticket: totales + generar */
.tkt-footer {
    padding: 12px 20px 16px;
    background: #fff;
    border-top: 1px solid #E4E7EB;
    flex-shrink: 0;
}

.tkt-total-row {
    display: flex; justify-content: space-between; align-items: baseline;
    padding: 2px 0;
}

.tkt-total-row .l { font-size: .66rem; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: .5px; }
.tkt-total-row .v { font-size: .8rem; font-weight: 700; color: #6B7280; font-variant-numeric: tabular-nums; }

.tkt-total-row.grand { margin-top: 6px; padding-top: 8px; border-top: 2px solid #F1F5F9; }
.tkt-total-row.grand .l { font-size: .76rem; color: #374151; }
.tkt-total-row.grand .v { font-size: 1.6rem; font-weight: 900; color: #1A202C; letter-spacing: -.5px; }

.tkt-ef-row {
    display: flex; align-items: center;
    background: #F8FAFC; border: 1.5px solid #E4E7EB;
    border-radius: 8px; margin: 10px 0 8px; overflow: hidden;
    transition: border-color .15s;
}
.tkt-ef-row:focus-within { border-color: #C0392B; }

.ef-pfx {
    padding: 0 10px; font-size: .8rem; font-weight: 800; color: #9CA3AF;
    border-right: 1px solid #E4E7EB; height: 38px;
    display: flex; align-items: center; flex-shrink: 0;
}

#pos-efectivo {
    flex: 1; background: transparent; border: none;
    color: #1A202C; font-size: .9rem; font-weight: 700;
    padding: 0 10px; outline: none; height: 38px;
    font-variant-numeric: tabular-nums;
}
#pos-efectivo::placeholder { color: #CBD5E1; font-weight: 400; font-size: .8rem; }

.btn-generar {
    width: 100%; padding: 13px;
    background: #C0392B; color: #fff;
    border: none; border-radius: 10px;
    font-size: .92rem; font-weight: 800; letter-spacing: .5px;
    cursor: pointer; text-transform: uppercase;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background .15s;
}
.btn-generar:hover:not(:disabled) { background: #9B2335; }
.btn-generar:disabled { opacity: .3; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div></div>

<div id="pos-root">

    {{-- ══ PASO 1: CATÁLOGO ══ --}}
    <div id="pos-catalog">

        <div class="cat-header">
            <div class="cat-brand">
                <div class="cat-brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path d="M12 2C8.5 2 6 4.5 6 8c0 4 5 10 6 11.4.3.4.7.4 1 0C14 17 18 11 18 8c0-3.5-2.5-6-6-6zm0 8.5c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>
                </div>
                <div class="cat-brand-text">
                    <strong>Lubriteca</strong>
                    <small>Selecciona los productos</small>
                </div>
            </div>

            <div class="cat-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="pos-search" placeholder="Buscar por nombre, marca, código…" autocomplete="off">
            </div>

            <a href="{{ route('lubriteca.scanner') }}" class="cat-scanner-link" target="_blank" title="Leer QR del sticker de cambio anterior">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z"/></svg>
                Leer QR anterior
            </a>
        </div>

        {{-- Encabezados de columna --}}
        <div class="list-header">
            <span></span>
            <span>Producto</span>
            <span class="lh-price">Precio</span>
            <span class="lh-qty" style="text-align:center">Stock</span>
            <span class="lh-qty">Cantidad</span>
        </div>

        <div id="prod-list">
            <div class="prod-list-empty">
                <svg viewBox="0 0 24 24"><path d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8z" style="animation:spin 1s linear infinite;transform-origin:center"/></svg>
                <p>Cargando catálogo…</p>
            </div>
        </div>

    </div>

    {{-- ══ BARRA FLOTANTE ══ --}}
    <div id="float-bar">
        <div class="fb-info">
            <div class="fb-icon">
                <svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.16 14.26l.04.04L8.1 16h7.45c.75 0 1.41-.41 1.75-1.03l3.24-5.88a1 1 0 00-.88-1.47H5.21l-.94-2H1v2h2l3.6 7.59z"/></svg>
            </div>
            <div class="fb-text">
                <strong id="fb-count">0 ítems</strong>
                <small>seleccionados</small>
            </div>
        </div>
        <div class="fb-total" id="fb-total">$ 0</div>
        <button id="btn-ver-ticket">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Ver ticket y generar venta
        </button>
    </div>

    {{-- ══ PASO 2: PANEL TICKET ══ --}}
    <div id="ticket-panel">

        <div class="tkt-topbar">
            <button id="btn-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver al catálogo
            </button>
            <h2>Nueva Venta — Lubriteca</h2>
            <span class="tkt-date">{{ date('d/m/Y H:i') }}</span>
        </div>

        <div class="tkt-body">

            {{-- Columna izquierda: datos del cliente --}}
            <div class="tkt-left">
                <div class="tkt-brand-mini">
                    <div class="ico">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path d="M12 2C8.5 2 6 4.5 6 8c0 4 5 10 6 11.4.3.4.7.4 1 0C14 17 18 11 18 8c0-3.5-2.5-6-6-6zm0 8.5c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>
                    </div>
                    <div>
                        <strong>Lubriteca</strong>
                        <small>Venta de productos</small>
                    </div>
                </div>

                <p class="tkt-section-title">Datos del cliente</p>

                <div class="tkt-field">
                    <label>Nombre completo *</label>
                    <input type="text" id="pos-nombre" placeholder="Nombre del cliente" autocomplete="off">
                </div>

                <div class="tkt-fields-2">
                    <div class="tkt-field">
                        <label>Teléfono *</label>
                        <input type="number" id="pos-telefono" placeholder="3001234567">
                    </div>
                    <div class="tkt-field">
                        <label>Placa</label>
                        <input type="text" id="pos-placa" placeholder="ABC 123" style="text-transform:uppercase">
                    </div>
                    <div class="tkt-field">
                        <label>Km actual</label>
                        <input type="number" id="pos-km-actual" placeholder="80 000">
                    </div>
                    <div class="tkt-field">
                        <label>Km próx. cambio</label>
                        <input type="number" id="pos-km-proximo" placeholder="85 000">
                    </div>
                </div>

                <p class="tkt-section-title">Atendido por</p>
                <div class="tkt-user-chip">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <input type="hidden" id="pos-usuario" value="{{ Auth::user()->id }}">
            </div>

            {{-- Columna derecha: productos + totales --}}
            <div class="tkt-right">
                <div class="tkt-products-header">
                    <h3>Productos seleccionados</h3>
                    <span class="cnt" id="tkt-prod-cnt"></span>
                </div>

                <div class="tkt-table-wrap">
                    <table class="tkt-t">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="tc">Cantidad</th>
                                <th class="tr">P. Unit</th>
                                <th class="tr">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tkt-tbody"></tbody>
                    </table>
                </div>

                <div class="tkt-footer">
                    <div class="tkt-total-row">
                        <span class="l">Subtotal</span>
                        <span class="v" id="t-subtotal">$ 0</span>
                    </div>
                    <div class="tkt-total-row grand">
                        <span class="l">Total</span>
                        <span class="v" id="t-total">$ 0</span>
                    </div>

                    <div class="tkt-ef-row">
                        <span class="ef-pfx">$</span>
                        <input type="number" id="pos-efectivo" placeholder="Efectivo recibido (opcional)" min="0">
                    </div>

                    <button class="btn-generar" id="btn-generar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Generar venta
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

<input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
@endsection

@push('custom-scripts')
{!! Html::script('lib/lubriteca.js') !!}
@endpush
