/* ================================================
   LUBRITECA POS — v5  (flujo 2 pasos)
   ================================================ */

/* ─── Estado global ─── */
var cart = {};          // { [id]: { id, nombre, precio, qty, stock, marca, unidad } }
var allProducts = [];   // lista raw de la API

/* ─── Formatear moneda COP ─── */
function fmt(n) {
    return '$ ' + new Intl.NumberFormat('es-CO').format(Math.round(n) || 0);
}

/* ─── Escape HTML básico ─── */
function escHtml(s) {
    return String(s || '')
        .replace(/&/g,  '&amp;')
        .replace(/"/g,  '&quot;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;');
}

/* ─── Helpers de carrito ─── */
function cartItems() {
    return Object.values(cart).filter(function(i) { return i.qty > 0; });
}
function cartTotal() {
    return cartItems().reduce(function(s, i) { return s + i.precio * i.qty; }, 0);
}
function cartCount() {
    return cartItems().reduce(function(s, i) { return s + i.qty; }, 0);
}

/* ══════════════════════════════════════════
   CATÁLOGO — cargar y renderizar productos
   ══════════════════════════════════════════ */
function loadProducts() {
    $.ajax({
        url:    '/producto/data/1',
        method: 'GET',
        success: function(res) {
            allProducts = (res.data || []).filter(function(p) {
                return parseInt(p.cant_disponible) > 0;
            });
            /* Inicializar cart state para cada producto */
            allProducts.forEach(function(p) {
                var id = String(p.id);
                cart[id] = {
                    id:     id,
                    nombre: p.producto  || '',
                    precio: parseFloat(p.precio_venta) || 0,
                    qty:    0,
                    stock:  parseInt(p.cant_disponible) || 0,
                    marca:  p.marca          || '',
                    unidad: p.unidad_medida  || '',
                    tipo:   p.tipo_producto  || '',
                    imagen: p.imagen || null
                };
            });
            renderList(allProducts);
        },
        error: function() {
            $('#prod-list').html(
                '<div class="prod-list-empty">'
                + '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>'
                + '<p>Error al cargar productos. Verifica la conexión y recarga.</p></div>'
            );
        }
    });
}

/* ─── Placeholder ─── */
function phHtml() {
    return '<div class="pr-ph">'
        + '<svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>'
        + '<span>Sin img</span>'
        + '</div>';
}

function renderList(products) {
    if (!products || !products.length) {
        $('#prod-list').html(
            '<div class="prod-list-empty">'
            + '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/></svg>'
            + '<p>No se encontraron productos con stock disponible.</p></div>'
        );
        return;
    }

    var html = products.map(function(p) {
        var id    = String(p.id);
        var stock = parseInt(p.cant_disponible) || 0;
        var sc    = stock === 0 ? 'out' : stock <= 5 ? 'low' : 'ok';
        var txt   = stock === 0 ? 'Sin stock' : stock + ' und';
        var qty   = cart[id] ? cart[id].qty : 0;
        var sel   = qty > 0 ? ' active' : '';

        /* Imagen */
        var imgContent;
        if (p.imagen) {
            imgContent = '<img src="' + escHtml(p.imagen) + '" loading="lazy" '
                + 'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';">'
                + '<div class="pr-ph" style="display:none">'
                +   '<svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>'
                +   '<span>Sin img</span>'
                + '</div>';
        } else {
            imgContent = phHtml();
        }

        /* Busqueda */
        var search = (p.id + ' ' + (p.producto || '') + ' ' + (p.marca || '') + ' ' + (p.tipo_producto || '')).toLowerCase();

        return '<div class="prod-row' + sel + '" '
            + 'data-id="' + id + '" '
            + 'data-search="' + escHtml(search) + '">'

            /* Imagen */
            + '<div class="pr-img">' + imgContent + '</div>'

            /* Info */
            + '<div class="pr-info">'
            +   '<div class="pr-code">#' + escHtml(String(p.id)) + ' · ' + escHtml(p.unidad_medida || '') + '</div>'
            +   '<div class="pr-name">' + escHtml(p.producto || 'Sin nombre') + '</div>'
            +   '<div class="pr-meta">' + escHtml(p.marca || '') + (p.tipo_producto ? ' · ' + escHtml(p.tipo_producto) : '') + '</div>'
            + '</div>'

            /* Precio */
            + '<div class="pr-price">' + fmt(p.precio_venta) + '</div>'

            /* Stock */
            + '<div class="pr-stock">'
            +   '<span class="pr-stock-badge ' + sc + '">' + txt + '</span>'
            + '</div>'

            /* Qty controls */
            + '<div class="pr-qty">'
            +   '<button class="pr-qty-btn" onclick="rowQtyDelta(\'' + id + '\',-1)" tabindex="-1">−</button>'
            +   '<span class="pr-qty-val" id="rq-' + id + '">' + qty + '</span>'
            +   '<button class="pr-qty-btn" onclick="rowQtyDelta(\'' + id + '\',1)" tabindex="-1">+</button>'
            + '</div>'

            + '</div>';
    }).join('');

    $('#prod-list').html(html);
}

/* ─── Ajustar cantidad desde la fila ─── */
window.rowQtyDelta = function(id, delta) {
    if (!cart[id]) return;
    var newQty = (cart[id].qty || 0) + delta;
    if (newQty < 0) newQty = 0;
    if (newQty > cart[id].stock) {
        showToast('Stock máximo disponible: ' + cart[id].stock + ' und', 'warn');
        return;
    }
    setQty(id, newQty);
};

/* ─── Establecer cantidad (estado central) ─── */
function setQty(id, qty) {
    if (!cart[id]) return;
    cart[id].qty = qty;

    /* Actualizar UI de la fila */
    var $row = $('.prod-row[data-id="' + id + '"]');
    $row.find('#rq-' + id).text(qty);
    if (qty > 0) {
        $row.addClass('active');
    } else {
        $row.removeClass('active');
    }

    updateFloatBar();

    /* Si el ticket está abierto, actualizar tabla */
    if ($('#ticket-panel').hasClass('open')) {
        renderTicketTable();
    }
}

/* ─── Barra flotante ─── */
function updateFloatBar() {
    var items = cartItems();
    var $bar  = $('#float-bar');

    if (!items.length) {
        $bar.removeClass('visible');
        return;
    }

    var count = cartCount();
    var total = cartTotal();
    $('#fb-count').text(count + ' ítem' + (count !== 1 ? 's' : ''));
    $('#fb-total').text(fmt(total));
    $bar.addClass('visible');
}

/* ══════════════════════════════
   BÚSQUEDA
   ══════════════════════════════ */
$(document).on('input', '#pos-search', function() {
    var q = $(this).val().toLowerCase().trim();
    if (!q) {
        $('.prod-row').show();
        return;
    }
    $('.prod-row').each(function() {
        var s = $(this).data('search') || '';
        $(this).toggle(s.includes(q));
    });
});

/* ══════════════════════════════
   PANEL DE TICKET
   ══════════════════════════════ */
function openTicket() {
    if (!cartItems().length) {
        showToast('Selecciona al menos un producto.', 'warn');
        return;
    }
    renderTicketTable();
    $('#ticket-panel').addClass('open');
}

function closeTicket() {
    $('#ticket-panel').removeClass('open');
}

function renderTicketTable() {
    var items = cartItems();

    $('#tkt-prod-cnt').text(items.length + ' producto' + (items.length !== 1 ? 's' : ''));

    if (!items.length) {
        closeTicket();
        return;
    }

    var rows = items.map(function(item) {
        var id = item.id;
        return '<tr>'
            + '<td>'
            +   '<div class="tc-name" title="' + escHtml(item.nombre) + '">' + escHtml(item.nombre) + '</div>'
            +   '<div class="tc-brand">' + escHtml(item.marca) + (item.unidad ? ' · ' + escHtml(item.unidad) : '') + '</div>'
            + '</td>'
            + '<td class="tc">'
            +   '<div class="qty-ctrl-t">'
            +     '<button class="qty-btn-t" onclick="tktQtyDelta(\'' + id + '\',-1)">−</button>'
            +     '<span class="qty-v-t" id="tq-' + id + '">' + item.qty + '</span>'
            +     '<button class="qty-btn-t" onclick="tktQtyDelta(\'' + id + '\',1)">+</button>'
            +   '</div>'
            + '</td>'
            + '<td class="tc-price">' + fmt(item.precio) + '</td>'
            + '<td class="tc-sub">' + fmt(item.precio * item.qty) + '</td>'
            + '<td class="tc-del"><button class="del-btn" onclick="tktRemove(\'' + id + '\')" title="Quitar">✕</button></td>'
            + '</tr>';
    }).join('');

    $('#tkt-tbody').html(rows);

    var total = cartTotal();
    $('#t-subtotal').text(fmt(total));
    $('#t-total').text(fmt(total));
}

window.tktQtyDelta = function(id, delta) {
    if (!cart[id]) return;
    var newQty = cart[id].qty + delta;
    if (newQty < 0) newQty = 0;
    if (newQty > cart[id].stock) {
        showToast('Stock máximo: ' + cart[id].stock, 'warn');
        return;
    }
    setQty(id, newQty);
    renderTicketTable();
};

window.tktRemove = function(id) {
    setQty(id, 0);
    renderTicketTable();
    if (!cartItems().length) closeTicket();
};

/* ══════════════════════════
   GENERAR VENTA
   ══════════════════════════ */
$(document).on('click', '#btn-generar', function() {
    var nombre   = $('#pos-nombre').val().trim();
    var telefono = $('#pos-telefono').val().trim();
    var usuario  = $('#pos-usuario').val();
    var items    = cartItems();

    if (!nombre) {
        showToast('Ingresa el nombre del cliente.', 'warn');
        $('#pos-nombre').focus();
        return;
    }
    if (!telefono) {
        showToast('Ingresa el teléfono del cliente.', 'warn');
        $('#pos-telefono').focus();
        return;
    }
    if (!items.length) {
        showToast('El carrito está vacío.', 'warn');
        return;
    }

    var total = cartTotal();
    var ef    = parseFloat($('#pos-efectivo').val()) || 0;

    if (ef > 0 && ef < total) {
        if (!confirm('El efectivo (' + fmt(ef) + ') es menor al total (' + fmt(total) + '). ¿Continuar?')) return;
    }

    var payload = {
        _token:            $('#csrf-token').val(),
        nombre_cliente:    nombre,
        numero_telefono:   telefono,
        placa:             ($('#pos-placa').val() || '').toUpperCase(),
        km_actual:         $('#pos-km-actual').val()   || null,
        km_proximo_cambio: $('#pos-km-proximo').val()  || null,
        id_usuario:        usuario,
        total_venta:       total,
        efectivo:          ef || null,
        productos: items.map(function(i) {
            return { id_producto: i.id, cantidad: i.qty, precio_venta: i.precio };
        })
    };

    var $btn = $('#btn-generar');
    $btn.prop('disabled', true).html(
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="animation:spin 1s linear infinite">'
        + '<path d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8z"/></svg> Guardando…'
    );

    $.ajax({
        url:         '/lubriteca',
        method:      'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': $('#csrf-token').val() },
        success: function(res) {
            if (res.id) {
                window.location.href = '/lubriteca/sticker/' + res.id;
            } else {
                showToast('Respuesta inesperada del servidor.', 'error');
                resetBtn($btn);
            }
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message))
                      || 'Error al guardar la venta.';
            showToast(msg, 'error');
            resetBtn($btn);
        }
    });
});

function resetBtn($btn) {
    $btn.prop('disabled', false).html(
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">'
        + '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg> Generar venta'
    );
}

/* ── Auto-calcular km próximo ── */
$(document).on('input', '#pos-km-actual', function() {
    var km = parseInt($(this).val()) || 0;
    if (km > 0 && !$('#pos-km-proximo').val()) {
        $('#pos-km-proximo').val(km + 5000);
    }
});

/* ── Placa uppercase ── */
$(document).on('input', '#pos-placa', function() {
    var v = $(this).val().toUpperCase();
    $(this).val(v);
});

/* ══════════════════════════
   TOAST HELPER
   ══════════════════════════ */
function showToast(msg, type) {
    var bg = type === 'warn'  ? '#F59E0B'
           : type === 'error' ? '#EF4444'
           : '#10B981';
    var $t = $('<div>').css({
        position:   'fixed',
        bottom:     '78px',
        left:       '50%',
        transform:  'translateX(-50%)',
        background: bg,
        color:      '#fff',
        borderRadius: '8px',
        padding:    '10px 20px',
        fontWeight: '700',
        fontSize:   '.84rem',
        zIndex:     99999,
        boxShadow:  '0 4px 20px rgba(0,0,0,.3)',
        whiteSpace: 'nowrap',
        fontFamily: 'system-ui,sans-serif'
    }).text(msg);
    $('body').append($t);
    setTimeout(function() { $t.fadeOut(300, function() { $t.remove(); }); }, 2600);
}

/* ══════════════════════════
   INIT
   ══════════════════════════ */
$(function() {
    /* Ajustar top/left al sidebar y navbar reales */
    var sidebar = document.getElementById('sidebar');
    var navbar  = document.querySelector('.navbar, .navbar-fixed-top');
    var navH    = navbar  ? navbar.offsetHeight : 62;
    var sideW   = sidebar ? sidebar.offsetWidth : 235;

    /* Aplicar left a todos los paneles fijos */
    ['pos-root', 'float-bar', 'ticket-panel'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.style.left = sideW + 'px'; }
    });

    /* Aplicar top (excepto float-bar que tiene su propia posición vertical) */
    ['pos-root', 'ticket-panel'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.style.top = navH + 'px'; }
    });

    loadProducts();

    /* Float bar → abrir ticket */
    $('#btn-ver-ticket').on('click', openTicket);

    /* Volver al catálogo */
    $('#btn-back').on('click', closeTicket);
});
