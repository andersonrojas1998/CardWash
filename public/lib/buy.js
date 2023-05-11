$(document).ready(function(){
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
                    class: "btn_show_edit_compra",
                });
                button.attr('data-id', compra.id);
                button.attr('data-reg-op', compra.reg_op);
                button.attr('data-fecha-emision', compra.fecha_emision);
                button.attr('data-compracol', compra.compracol);
                button.attr('data-fecha-vencimiento', compra.fecha_vencimiento);
                button.attr('data-no-comprobante', compra.no_comprobante);
                button.attr('data-id-proveedor', compra.id_proveedor);
                button.attr('data-razon-social-proveedor', compra.razon_social_proveedor);
                button.attr('data-compra-o-gasto', compra.compra_o_gasto);
                button.attr('data-descuentos-iva', compra.descuentos_iva);
                button.attr('data-importe-total', compra.importe_total);
                button.attr('data-condiciones-id', compra.condiciones_id);
                button.attr('data-url', compra.route_products);
                button.attr('data-toggle', 'modal');
                button.attr('data-target', '#modal_edit_buy');
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

    $.ajax({
        url: $('#select-condicion-data-url').val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $.each(data.condiciones, function(i, condicion){
                $('.select-condiciones').append($('<option>',{
                    value: condicion.id,
                    text: condicion.descripcion
                }));
            });
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
                    alert(output);
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
                alert(data.success);
            }
        });
    });

    $(document).on('click', ".btn_add_product", function(){
        let div = $(this).parents('.modal-body');
        if(div.find(".select-product-compra :selected").val() != '' && div.find('.select-unidad-de-medida :selected').val() != '' &&
        div.find('.input-quantity').val() != '' && div.find('.input-buy-price').val() != '' && div.find('.input-sell-price').val() != ''){
            let id_producto = div.find(".select-product-compra :selected").val();
            let text_producto = div.find(".select-product-compra :selected").text();
            div.find(".select-product-compra :selected").remove();
            let id_unidad_medida = div.find('.select-unidad-de-medida :selected').val();
            let text_unidad_medida = div.find('.select-unidad-de-medida :selected').text();
            let cantidad = parseInt(div.find('.input-quantity').val());
            div.find('.input-quantity').val('');
            let precio_compra = parseFloat(div.find('.input-buy-price').val());
            div.find('.input-buy-price').val('');
            let precio_venta = parseFloat(div.find('.input-sell-price').val());
            div.find('.input-sell-price').val('');
            let importe_total = parseInt(div.find(".importe_total").val());
            let tr = $('<tr>');
            let td1 = $('<td>', {
                text: text_producto
            });
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
            div.find('.table-add-products > tbody').append(tr);
            div.find(".importe_total").val(importe_total+precio_compra);
            div.find(".text_importe_total").text(importe_total+precio_compra);
        }else{
            alert('Por favor complete los campos');
        }
    });

    $(document).on('click', '#open-modal-create-compra', function(){
        $('#table-add-products > tbody').empty();
    });

    $(document).on('click', '#btn_add_products', function(){
        let form_to_add_products = $($('#form_to_add_products').val());
        let importe_total = 0;
        form_to_add_products.empty();
        $('.id_producto_table').each(function(i){
            let hidden = $('<input>',{
                type: 'hidden',
                value: $(this).val(),
                name: 'id_producto[]'
            });
            hidden.attr('data-text', $(this).data('text'));
            form_to_add_products.append(hidden);
        });
        $('.id_unidad_medida_table').each(function(i){
            let hidden = $('<input>',{
                type: 'hidden',
                value: $(this).val(),
                name: 'id_unidad_medida[]'
            });
            hidden.attr('data-text', $(this).data('text'));
            form_to_add_products.append(hidden);
        });
        $('.cantidad_producto_table').each(function(i){
            form_to_add_products.append($('<input>',{
                type: 'hidden',
                value: parseInt($(this).val()),
                name: 'cantidad_producto[]'
            }));
        });
        $('.precio_compra_producto_table').each(function(i){
            importe_total += parseInt($(this).val());
            form_to_add_products.append($('<input>',{
                type: 'hidden',
                value: parseFloat($(this).val()),
                name: 'precio_compra_producto[]'
            }));
        });
        form_to_add_products.append($('<input>',{
            type: 'hidden',
            value: importe_total,
            name: 'importe_total'
        }));
        $('.precio_venta_producto_tabla').each(function(i){
            form_to_add_products.append($('<input>',{
                type: 'hidden',
                value: parseFloat($(this).val()),
                name: 'precio_venta_producto[]'
            }));
        });
        if($('#form_to_add_products').val() == '#products-create'){
            $('#importe_total_compra').text("$" + importe_total);
        }else{
            $('#importe_total_compra_edit').text("$" + importe_total);
            $("#btn_edit_buy").removeAttr('disabled');
        }
        $('#modal_add_products').modal('hide');
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