$(function(){

    $('.btn_save_edit_product_values').toggle();

    $('#table-compra').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax:{
            url: $('#table-compra').data('url'),
            method: "GET",
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataSrc: function(json){
                if(!json.data){
                    return [];
                } else {
                    return json.data;
                }
            }
        },
        columnDefs: [
            //{width: "2%", target: 0},
            {"className": "text-center", "targets": "_all"},
        ],
        columns:[
            {"data": "reg_op"},
            {"data": "fecha_emision"},
            {"data": "compracol"},
            {"data": "fecha_vencimiento"},
            {"data": "no_comprobante"},
            {"data": "id_proveedor"},
            {"data": "razon_social_proveedor"},
            {"data": "compra_o_gasto"},
            {"data": "descuentos_iva"},
            {"data": "importe_total"},
            {"data": "condicion.descripcion"},
            {"data": "actions", render(data, ps, compra){
                let div = $('<div>');
                let button = $("<a>", {
                    href: compra.route_edit,
                    class: "btn_show_edit_compra",
                });
                let i = $("<i>", {
                    class : "mdi mdi-pencil-box-outline text-primary mdi-24px"
                });
                button.append(i);
                div.append(button);
                return div.html();
            }
        }
        ]
    });

    if($("#succes_message").length)
        sweetMessage('\u00A1Advertencia!', $("#succes_message").val());

    if($("#fail_message").length)
        sweetMessage('\u00A1Advertencia!', $("#fail_message").val(), 'error');

    $.ajax({
        url: $('#select-condicion-data-url').val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $('.select-condiciones').empty();
            $.each(data.condiciones, function(i, condicion){
                $('.select-condiciones').append($('<option>',{
                    value: condicion.id,
                    text: condicion.descripcion
                }));
            });
            if($('#old-select-condicion').length)
                $('.select-condiciones').val($('#old-select-condicion').val());
        }
    });

    var loadProductOptions = function(){
        $.ajax({
            url: $("#select-product-data-url").val(),
            type: "GET",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function(data, textStatus, xhr){
                $('.select-product-compra').empty();
                $.each(data.data, function(i, producto){
                    $('.select-product-compra').append($('<option>',{
                        value: producto.id,
                        text: producto.nombre + " (" + producto.tipo_producto.descripcion + ")"
                    }));
                });
                $('.id_producto_table').each(function(){
                    $('.select-product-compra > option[value="' + $(this).val() + '"]').remove();
                });
            }
        });
    }

    loadProductOptions();

    var loadUnitMeasurementOptions = function(){
        $.ajax({
            url: $("#select-unit-measurement-data-url").val(),
            type: "GET",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function(data, textStatus, xhr){
                $('.select-unidad-de-medida').empty();
                $.each(data.unidades_de_medida, function(i, unidad_de_medida){
                    $('.select-unidad-de-medida').append($('<option>',{
                        value: unidad_de_medida.id,
                        text: unidad_de_medida.nombre + " (" + unidad_de_medida.abreviatura + ")"
                    }));
                });
            }
        });
    }

    loadUnitMeasurementOptions();

    $(document).on('click', '#save-unit-measurement', function(){
        let funTransitions = function(){
            if(!$('#save-unit-measurement').attr('disabled')){
                $('#save-unit-measurement').attr('disabled', true);
                $('#form-fields-unit-measurement').addClass('d-none');
                $('#spinner-unit-measurement').removeClass('d-none');
            }else{
                $('#save-unit-measurement').attr('disabled', false);
                $('#form-fields-unit-measurement').removeClass('d-none');
                $('#spinner-unit-measurement').addClass('d-none');
            }
        }
        funTransitions();
        let formData = new FormData($('#create-unit-measurement-form')[0]);
        $.ajax({
            url: $('#create-unit-measurement-form').attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            error: function(jqXHR, textStatus, errorThrown){
                funTransitions(1);
                if(jqXHR.status == 422){
                    let res = JSON.parse(jqXHR.responseText);
                    let output = '';
                    $.each( res, function(i, value){
                        output += value + '\n';
                    });
                    sweetMessage('', output, 'warning');
                }
            },
            success: function(data, textStatus, xhr){
                funTransitions(1);
                $(':input','#create-unit-measurement-form').val('');
                $(".select-unidad-de-medida :contains('Seleccione unidad de medida')").remove();
                $('.select-unidad-de-medida').prepend($('<option>',{
                    value: data.unidad_de_medida.id,
                    text: data.unidad_de_medida.nombre + ' (' + data.unidad_de_medida.abreviatura + ')'
                }));
                $(".select-unidad-de-medida").prepend($("<option>",{
                    value: '',
                    text: 'Seleccione unidad de medida'
                }));
                $(".select-unidad-de-medida").val(data.unidad_de_medida.id);
                sweetMessage('', data.success);
            }
        });
    });

    $(document).on('click', ".btn_add_product", function(){
        let form = $(this).parents('form');
        if(form.find(".select-product-compra :selected").val() != '' && form.find('.select-unidad-de-medida :selected').val() != '' &&
        form.find('.input-quantity').val() != '' && form.find('.input-buy-price').val() != '' && form.find('.input-sell-price').val() != ''){
            $('#products-validation-message').remove();
            let id_producto = form.find(".select-product-compra :selected").val();
            let text_producto = form.find(".select-product-compra :selected").text();
            form.find(".select-product-compra :selected").remove();
            let id_unidad_medida = form.find('.select-unidad-de-medida :selected').val();
            let text_unidad_medida = form.find('.select-unidad-de-medida :selected').text();
            let cantidad = parseInt(form.find('.input-quantity').val());
            form.find('.input-quantity').val('');
            let precio_compra = parseFloat(form.find('.input-buy-price').val());
            form.find('.input-buy-price').val('');
            let precio_venta = parseFloat(form.find('.input-sell-price').val());
            form.find('.input-sell-price').val('');
            let importe_total = parseFloat("0");
            let tr = $('<tr>');
            let td1 = $('<td>', {
                text: text_producto
            });
            let hidden0 = $('<input>', {
                type: "hidden",
                name: "id_detalle_compra_producto[]",
                value: -1
            });
            td1.append(hidden0);
            let hidden1 = $('<input>', {
                type: "hidden",
                class: "id_producto_table",
                name: "id_producto[]",
                value: id_producto
            });
            hidden1.attr('data-text', text_producto);
            td1.append(hidden1);
            tr.append(td1);
            let td2 = $('<td>', {
                text: text_unidad_medida
            });
            let hidden2 = $('<input>', {
                type: "hidden",
                class: "id_unidad_medida_table",
                name: "id_unidad_de_medida[]",
                value: id_unidad_medida
            });
            hidden2.attr('data-text', text_unidad_medida);
            td2.append(hidden2);
            tr.append(td2);
            let td3 = $('<td>', {
                text: cantidad
            });
            let hidden3 = $('<input>', {
                type: "hidden",
                class: "cantidad_producto_table",
                name: "cantidad[]",
                value: cantidad
            });
            td3.append(hidden3);
            tr.append(td3);

            let td4 = $('<td>', {
                text: precio_venta
            });
            let hidden4 = $('<input>', {
                type: "hidden",
                class: "precio_venta_producto_tabla",
                name: "precio_venta[]",
                value: precio_venta
            });
            td4.append(hidden4);
            tr.append(td4);

            let td5 = $('<td>', {
                text: precio_compra
            });
            let hidden5 = $('<input>', {
                type: "hidden",
                class: "precio_compra_producto_table",
                name: "precio_compra[]",
                value: precio_compra
            });
            td5.append(hidden5);
            tr.append(td5);

            let td6 = $('<td>');
            let button_remove = $('<a>',{
                class: 'btn-remove-product'
            });
            let i = $("<i>", {
                class : "mdi mdi-minus-box text-danger mdi-24px"
            });
            button_remove.append(i);
            td6.append(button_remove);
            tr.append(td6);
            form.find('.table-add-products > tbody').append(tr);
            $('.precio_compra_producto_table').each(function(){
                importe_total += parseFloat($(this).val());
            });
            form.find(".importe_total").val(importe_total);
            form.find(".text_importe_total").text(importe_total);
        }else{
            sweetMessage('\u00A1Advertencia!', '\u00A1 Por favor complete los campos!', 'warning');
        }
    });

    $(document).on('click', '.btn-remove-product', function(){
        let tr = $(this).parents('tr');
        let table = $(this).parents('table');
        table.find(".select-product-compra").append($('<option>', {
            value : tr.find('.id_producto_table').val(),
            text: tr.find('.id_producto_table').data('text')
        }));
        let precio_compra = parseInt(tr.find('.precio_compra_producto_table').val());
        let total = parseInt(table.find('.importe_total').val());
        table.find('.importe_total').val(total - precio_compra);
        table.find('.text_importe_total').text(total - precio_compra);
        tr.remove();
    });

    $('.btn_edit_product_values').on('click', function(){
        $(this).toggle();
        let tr = $(this).parents('tr');
        tr.find('.btn_save_edit_product_values').toggle();
        let unitMeasurement = tr.find('.td_unit_measurement > input').val();
        let quantity = tr.find('.td_quantity > input').val();
        let sell_price = tr.find('.td_sell_price > input').val();
        let buy_price = tr.find('.td_buy_price > input').val();
        tr.find('.td_unit_measurement').html($('<select>', {
            class: "custom-select select-unidad-de-medida"
        }));
        loadUnitMeasurementOptions();
        tr.find('.td_unit_measurement > .select-unidad-de-medida > option[value="' + unitMeasurement + '"]').attr('selected', true);
        tr.find('.td_quantity').html($('<input>', {
            type:"number",
            class:"form-control input-quantity",
            value: quantity
        }));
        tr.find('.td_quantity').css('width', '130px');
        tr.find('.td_quantity').css('display', 'inline-block');
        tr.find('.td_sell_price').html($('<input>', {
            type:"number",
            class:"form-control input-sell-price",
            value: sell_price
        }));
        tr.find('.td_buy_price').html($('<input>', {
            type:"number",
            class:"form-control input-buy-price",
            value: buy_price
        }));
    });

    $('.btn_save_edit_product_values').click(function(){
        $(this).toggle();
        let tr = $(this).parents('tr');
        tr.find('.btn_edit_product_values').toggle();
        let text_unitMeasurement = tr.find('.td_unit_measurement > select > :selected').text();
        let unitMeasurement = tr.find('.td_unit_measurement > select > :selected').val();
        let quantity = tr.find('.td_quantity > input').val();
        let sell_price = tr.find('.td_sell_price > input').val();
        let buy_price = tr.find('.td_buy_price > input').val();
        tr.find('.td_unit_measurement').text(text_unitMeasurement);
        tr.find('.td_unit_measurement').append($('<input>', {
            type: "hidden",
            name: "id_unidad_de_medida[]",
            value: unitMeasurement
        }));
        tr.find('.td_quantity').text(quantity);
        tr.find('.td_quantity').append($('<input>', {
            type:"hidden",
            name:"cantidad[]",
            value: quantity
        }));
        tr.find('.td_sell_price').text(sell_price);
        tr.find('.td_sell_price').append($('<input>', {
            type:"hidden",
            name:"precio_venta[]",
            value: sell_price
        }));
        tr.find('.td_buy_price').text(buy_price);
        tr.find('.td_buy_price').append($('<input>', {
            type:"hidden",
            class:"precio_compra_producto_table",
            name: "precio_compra[]",
            value: buy_price
        }));
        let importe_total = parseFloat("0");
        $('.precio_compra_producto_table').each(function(){
            importe_total += parseFloat($(this).val());
        });
        $(".importe_total").val(importe_total);
        $(".text_importe_total").text(importe_total);
    });

    $(document).on('click', '.btn_show_edit_compra', function(){
        $('input:radio').removeAttr('checked');
        $('#id_compra').val($(this).data('id'));
        $('#reg_op_compra_edit').val($(this).data('reg-op'));
        $('#fecha_emision_compra_edit').val($(this).data('fecha-emision'));
        $('#compracol_edit').val($(this).data('compracol'));
        $('#fecha_vencimiento_compra_edit').val($(this).data('fecha-vencimiento'));
        $('#no_comprobante_compra_edit').val($(this).data('no-comprobante'));
        $('#id_proveedor_compra_edit').val($(this).data('id-proveedor'));
        $('#edit-buy-form input[value="' + $(this).data('razon-social-proveedor') + '"]').attr("checked", true);
        $('#descuentos_iva_compra_edit').val($(this).data('descuentos-iva'));
        let importe_total = $(this).data('importe-total');
        $('#edit-buy-form .text_importe_total').text(importe_total);
        $('#edit-buy-form .importe_total').val(importe_total);
        $('#condiciones_id_compra_edit').val($(this).data('condiciones-id'));
        loadProductOptions();
        $.ajax({
            url: $(this).data('url'),
            type: "get",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function(data, textStatus, xhr){
                $('#edit-buy-form .table-add-products > tbody').empty();
                if(data.length != 0){
                    $.each(data, function(i, det_comp_prod){
                        let text_unidad_medida = det_comp_prod.unidad_de_medida.nombre + " (" + det_comp_prod.unidad_de_medida.abreviatura + ")";
                        let tr = $('<tr>');
                        let td1 = $('<td>', {
                            text: det_comp_prod.producto.nombre + " (" + det_comp_prod.producto.tipo_producto.descripcion + ")"
                        });
                        let hidden0 = $('<input>', {
                            type: "hidden",
                            class: "id_detalle_compra_producto_table",
                            name: "id_detalle_compra_producto[]",
                            value: det_comp_prod.id
                        });
                        td1.append(hidden0);
                        let hidden1 = $('<input>', {
                            type: "hidden",
                            class: "id_producto_table",
                            name: "id_producto[]",
                            value: det_comp_prod.producto.id
                        });
                        hidden1.attr('data-text', det_comp_prod.producto.nombre);
                        td1.append(hidden1);
                        tr.append(td1);
                        let td2 = $('<td>', {
                            text: text_unidad_medida
                        });
                        let hidden2 = $('<input>', {
                            type: "hidden",
                            class: "id_unidad_medida_table",
                            name: "id_unidad_de_medida[]",
                            value: det_comp_prod.unidad_de_medida.id
                        });
                        hidden2.attr('data-text', text_unidad_medida);
                        td2.append(hidden2);
                        tr.append(td2);
                        let td3 = $('<td>', {
                            text: det_comp_prod.cantidad
                        });
                        let hidden3 = $('<input>', {
                            type: "hidden",
                            class: "cantidad_producto_table",
                            name: "cantidad[]",
                            value: det_comp_prod.cantidad
                        });
                        td3.append(hidden3);
                        tr.append(td3);
                        let td4 = $('<td>', {
                            text: det_comp_prod.precio_venta
                        });
                        let hidden4 = $('<input>', {
                            type: "hidden",
                            class: "precio_venta_producto_tabla",
                            name: "precio_venta[]",
                            value: det_comp_prod.precio_venta
                        });
                        td4.append(hidden4);
                        tr.append(td4);
                        let td5 = $('<td>', {
                            text: det_comp_prod.precio_compra
                        });
                        let hidden5 = $('<input>', {
                            type: "hidden",
                            class: "precio_compra_producto_table",
                            name: "precio_compra[]",
                            value: det_comp_prod.precio_compra
                        });
                        td5.append(hidden5);
                        tr.append(td5);
                        let td6 = $('<td>');
                        let button_remove = $('<a>',{
                            class: 'btn-remove-product'
                        });
                        button_remove.attr('data-id', det_comp_prod.id);
                        let i2 = $("<i>", {
                            class : "mdi mdi-minus-box text-danger mdi-24px"
                        });
                        button_remove.append(i2);
                        td6.append(button_remove);
                        //tr.append(td6);
                        $('#edit-buy-form .table-add-products > tbody').append(tr);
                        $('#edit-buy-form .select-product-compra > option[value="' + det_comp_prod.producto.id + '"]').remove();
                    });
                }
            }
        });
    });
});