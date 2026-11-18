/* ================================================
   Registrar venta (venta/create) — carwash + productos
   Vista independiente de sell.js.
   ================================================ */
$(function () {

    var $form = $('#form_create_sell');
    if (!$form.length) return;

    var fmt = function (n) { return '$ ' + new Intl.NumberFormat('es-CO').format(Math.round(n) || 0); };
    var esc = function (s) {
        return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    };

    /* Estado de la venta */
    var pkg = null;        // { id, nombre, vehiculo, precio, porcentaje }
    var items = {};        // { [id]: { id, nombre, marca, tipo, price, stock, qty } }
    var order = [];        // orden en que se agregaron

    /* ══════════ Tipo de venta ══════════ */
    $('#vc-sale-type button').on('click', function () {
        $('#vc-sale-type button').removeClass('active');
        $(this).addClass('active');
        var type = $(this).data('type');
        $('#id_estado_venta').val(type);

        var soloProductos = type == 3;
        $('#vc-service-section').toggle(!soloProductos);
        $('#vc-step-products').text(soloProductos ? '2' : '3');
        if (soloProductos) clearPackage();
    });

    /* ══════════ Vehículo → combos ══════════ */
    $('.vc-vehicle-radio').on('change', function () {
        clearPackage();
        $('#vc-packages-wrap').show();
        $('#vc-packages').html('<div class="vc-loading"><i class="mdi mdi-loading mdi-spin"></i> Cargando combos…</div>');

        $.get($(this).data('url'), function (data) {
            var html = '';
            $.each(data.paquetes || [], function (i, p) {
                var colors = String(p.color || '#374151,#ffffff').split(',');
                var servicios = (p.servicios_paquete || []).map(function (sp) {
                    return sp.servicio ? sp.servicio.nombre : '';
                }).filter(Boolean).join(' · ');

                html +=
                    '<label class="vc-package">' +
                        '<input type="radio" name="id_detalle_paquete" value="' + esc(p.id_detalle_paquete) + '"' +
                            ' data-nombre="' + esc(p.nombre) + '"' +
                            ' data-vehiculo="' + esc(p.tipo_vehiculo ? p.tipo_vehiculo.descripcion : '') + '"' +
                            ' data-price="' + (parseFloat(p.precio) || 0) + '"' +
                            ' data-percent="' + (parseFloat(p.porcentaje) || 0) + '">' +
                        '<div class="vc-package-box">' +
                            '<div class="vc-package-top" style="background:linear-gradient(135deg,' + esc(colors[0]) + ',#1f2937);">' +
                                '<b>' + esc(p.nombre) + '</b>' +
                                '<span class="vc-package-price">' + fmt(p.precio) + '</span>' +
                            '</div>' +
                            '<div class="vc-package-services">' + (esc(servicios) || 'Sin servicios configurados') + '</div>' +
                        '</div>' +
                    '</label>';
            });
            $('#vc-packages').html(html || '<div class="vc-loading">No hay combos para este vehículo.</div>');
        }).fail(function () {
            $('#vc-packages').html('<div class="vc-loading text-danger">No se pudieron cargar los combos.</div>');
        });
    });

    $(document).on('change', 'input[name="id_detalle_paquete"]', function () {
        var $r = $(this);
        pkg = {
            id: $r.val(),
            nombre: $r.data('nombre'),
            vehiculo: $r.data('vehiculo'),
            precio: parseFloat($r.data('price')) || 0,
            porcentaje: parseFloat($r.data('percent')) || 0
        };
        $('#precio_venta_paquete').val(pkg.precio);
        $('#porcentaje_paquete').val(pkg.porcentaje);
        render();
    });

    function clearPackage() {
        pkg = null;
        $('input[name="id_detalle_paquete"]').prop('checked', false);
        $('#precio_venta_paquete, #porcentaje_paquete').val(0);
        render();
    }

    /* ══════════ Productos ══════════ */
    var $select = $('#pos-product');

    function optData(el) {
        var $o = $(el);
        return {
            id: String($o.val()),
            nombre: $o.data('nombre'),
            marca: $o.data('marca'),
            tipo: $o.data('tipo'),
            unidad: $o.data('unidad'),
            price: parseFloat($o.data('price')) || 0,
            stock: parseInt($o.data('stock')) || 0
        };
    }

    function stockBadge(stock) {
        var cls = stock <= 0 ? 'out' : (stock <= 3 ? 'low' : '');
        return '<span class="pos-opt-stock ' + cls + '">' + (stock <= 0 ? 'Agotado' : stock + ' disp.') + '</span>';
    }

    $select.select2({
        placeholder: 'Buscar producto por nombre, marca o tipo…',
        allowClear: true,
        width: '100%',
        templateResult: function (item) {
            if (!item.id) return item.text;
            var d = optData(item.element);
            return $(
                '<div class="pos-opt">' +
                    '<div class="pos-opt-main">' +
                        '<div class="pos-opt-name">' + esc(d.nombre) + '</div>' +
                        '<div class="pos-opt-meta">' + esc(d.marca) + ' · ' + esc(d.tipo) + ' · ' + esc(d.unidad) + '</div>' +
                    '</div>' +
                    '<div class="pos-opt-price">' + fmt(d.price) + '</div>' +
                    stockBadge(d.stock) +
                '</div>'
            );
        },
        templateSelection: function (item) {
            if (!item.id) return item.text;
            var d = optData(item.element);
            return d.nombre + ' — ' + fmt(d.price);
        }
    });

    function current() {
        var el = $select.find(':selected')[0];
        return el && el.value ? optData(el) : null;
    }
    function enVenta(id) { return items[id] ? items[id].qty : 0; }

    function refreshPanel() {
        var d = current();
        if (!d) { $('#pos-selected').hide(); return; }

        var libre = d.stock - enVenta(d.id);
        var $qty = $('#pos-qty');
        var qty = Math.max(1, Math.min(parseInt($qty.val()) || 1, Math.max(libre, 1)));
        $qty.val(qty);

        $('#pos-sel-name').text(d.nombre);
        $('#pos-sel-meta').text(d.marca + ' · ' + d.tipo + ' · ' + d.unidad);
        $('#pos-sel-price').text(fmt(d.price));
        $('#pos-sel-stock').html(libre + (enVenta(d.id) ? ' <small class="text-muted">(' + enVenta(d.id) + ' en venta)</small>' : ''));
        $('#pos-sel-subtotal').text(fmt(d.price * qty));
        $('#pos-add').prop('disabled', libre <= 0);
        $('#pos-selected').css('display', 'flex');
    }

    $select.on('change', function () { $('#pos-qty').val(1); refreshPanel(); });
    $('#pos-qty').on('input', refreshPanel);
    $('.pos-qty-btn').on('click', function () {
        var $q = $('#pos-qty');
        $q.val((parseInt($q.val()) || 1) + parseInt($(this).data('step')));
        refreshPanel();
    });

    $('#pos-add').on('click', function () {
        var d = current();
        if (!d) return;
        var qty = parseInt($('#pos-qty').val()) || 0;
        var libre = d.stock - enVenta(d.id);

        if (qty <= 0) { sweetMessage('¡Advertencia!', 'La cantidad no es válida', 'warning'); return; }
        if (qty > libre) { sweetMessage('¡Advertencia!', 'Solo hay ' + libre + ' unidades disponibles', 'warning'); return; }

        if (items[d.id]) {
            items[d.id].qty += qty;
        } else {
            d.qty = qty;
            items[d.id] = d;
            order.push(d.id);
        }
        render();
        $select.val(null).trigger('change');
    });

    /* ══════════ Resumen ══════════ */
    function render() {
        var html = '';
        var total = 0;
        var count = 0;

        if (pkg) {
            total += pkg.precio;
            count++;
            html +=
                '<div class="vc-item">' +
                    '<div class="vc-item-icon pkg"><i class="mdi mdi-car-wash"></i></div>' +
                    '<div class="vc-item-main">' +
                        '<div class="vc-item-name">' + esc(pkg.nombre) + '</div>' +
                        '<div class="vc-item-sub">' + esc(pkg.vehiculo) + '</div>' +
                    '</div>' +
                    '<div class="vc-item-total">' + fmt(pkg.precio) + '</div>' +
                    '<a class="vc-item-del" data-pkg="1" title="Quitar"><i class="mdi mdi-close-circle"></i></a>' +
                '</div>';
        }

        order.forEach(function (id) {
            var it = items[id];
            if (!it) return;
            var sub = it.price * it.qty;
            total += sub;
            count++;
            html +=
                '<div class="vc-item">' +
                    '<div class="vc-item-icon"><i class="mdi mdi-oil"></i></div>' +
                    '<div class="vc-item-main">' +
                        '<div class="vc-item-name" title="' + esc(it.nombre) + '">' + esc(it.nombre) + '</div>' +
                        '<div class="vc-item-sub">' + fmt(it.price) + ' c/u · ' + esc(it.marca) + '</div>' +
                    '</div>' +
                    '<div class="vc-item-qty">' +
                        '<a data-step="-1" data-id="' + esc(id) + '"><i class="mdi mdi-minus-circle-outline"></i></a>' +
                        '<b>' + it.qty + '</b>' +
                        '<a data-step="1" data-id="' + esc(id) + '"><i class="mdi mdi-plus-circle-outline"></i></a>' +
                    '</div>' +
                    '<div class="vc-item-total">' + fmt(sub) + '</div>' +
                    '<a class="vc-item-del" data-id="' + esc(id) + '" title="Quitar"><i class="mdi mdi-close-circle"></i></a>' +
                    '<input type="hidden" name="id_producto[]" value="' + esc(id) + '">' +
                    '<input type="hidden" name="precio_venta[]" value="' + it.price + '">' +
                    '<input type="hidden" name="cantidad[]" value="' + it.qty + '">' +
                '</div>';
        });

        if (!count) {
            html = '<div class="vc-empty"><i class="mdi mdi-cart-outline"></i><p>Selecciona un combo o agrega productos</p></div>';
        }

        $('#vc-items').html(html);
        $('#vc-count').text(count);
        $('#importe_total').val(total);
        $('#text_importe_total').text(fmt(total));
        $('#card-km-aceite').toggle(order.length > 0);
    }

    $(document).on('click', '.vc-item-qty a', function () {
        var it = items[$(this).data('id')];
        var next = it.qty + parseInt($(this).data('step'));
        if (next < 1) return;
        if (next > it.stock) { sweetMessage('¡Advertencia!', 'Solo hay ' + it.stock + ' unidades disponibles', 'warning'); return; }
        it.qty = next;
        render();
        refreshPanel();
    });

    $(document).on('click', '.vc-item-del', function () {
        if ($(this).data('pkg')) {
            clearPackage();
            return;
        }
        var id = String($(this).data('id'));
        delete items[id];
        order = order.filter(function (x) { return x !== id; });
        render();
        refreshPanel();
    });

    /* ══════════ Km ══════════ */
    $('#input_km_actual').on('input', function () {
        var km = parseInt($(this).val());
        if (!isNaN(km)) $('#input_km_proximo').val(km + 5000);
    });

    /* ══════════ Enviar ══════════ */
    $form.on('submit', function (e) {
        if (!pkg && !order.length) {
            e.preventDefault();
            sweetMessage('¡Advertencia!', 'Selecciona un combo o agrega al menos un producto', 'warning');
            return;
        }
        var actual = parseInt($('#input_km_actual').val());
        var prox = parseInt($('#input_km_proximo').val());
        if (!isNaN(actual) && !isNaN(prox) && prox <= actual) {
            e.preventDefault();
            sweetMessage('¡Advertencia!', 'El km del próximo cambio debe ser mayor al km actual', 'warning');
            return;
        }
        $('#btn_create_sell').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Guardando…');
    });

    render();
});
