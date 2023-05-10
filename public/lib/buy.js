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
                $('#select-product-compra').empty();
                $.each(data.data, function(i, producto){
                    $('#select-product-compra').append($('<option>',{
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

    $(document).on('change', "#select-product-compra", function(){
        let form = $($("#form_to_add_products").val()).parents('form');
        if(form.find('input[value="Gasto"]').is(':checked')){
            $.ajax({
                url: $(this).data('url-quantity') + "/" + $(this).val(),
                type: "get",
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                success: function(data, textStatus, xhr){
                    $("#hidden-quantity-product").val(data);
                }
            });
        }
    });

    $(document).on('click', "#btn_add_product", function(){
        if($("#select-product-compra :selected").val() != '' && $('#select-unidad-de-medida :selected').val() != '' &&
        $('#input-quantity').val() != '' && $('#input-buy-price').val() != '' && $('#input-sell-price').val() != ''){
            let form = $($("#form_to_add_products").val()).parents('form');
            if(form.find('input[value="Gasto"]').is(':checked') && parseInt($('#input-quantity').val()) > parseInt($("#hidden-quantity-product").val()) ){
                alert('La cantidad ' + $('#input-quantity').val() + ' es superior a la(s) ' + $("#hidden-quantity-product").val() + ' registradas del producto ' + $("#select-product-compra :selected").text());
                return false;
            }
            let id_producto = $("#select-product-compra :selected").val();
            let text_producto = $("#select-product-compra :selected").text();
            $("#select-product-compra :selected").remove();
            let id_unidad_medida = $('#select-unidad-de-medida :selected').val();
            let text_unidad_medida = $('#select-unidad-de-medida :selected').text();
            let cantidad = parseInt($('#input-quantity').val());
            $('#input-quantity').val('');
            let precio_compra = parseFloat($('#input-buy-price').val());
            $('#input-buy-price').val('');
            let precio_venta = parseFloat($('#input-sell-price').val());
            $('#input-sell-price').val('');
            let tr = $('<tr>');
            let td1 = $('<td>', {
                text: text_producto
            });
            let hidden1 = $('<input>', {
                type: "hidden",
                class: "id_producto_table",
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
                value: cantidad
            });
            td3.append(hidden3);
            tr.append(td3);
            let td4 = $('<td>', {
                text: precio_compra
            });
            let hidden4 = $('<input>', {
                type: "hidden",
                class: "precio_compra_producto_table",
                value: precio_compra
            });
            td4.append(hidden4);
            tr.append(td4);
            let td5 = $('<td>', {
                text: precio_venta
            });
            let hidden5 = $('<input>', {
                type: "hidden",
                class: "precio_venta_producto_tabla",
                value: precio_venta
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
            $('#table-add-products > tbody').append(tr);
            $('#btn_add_products').removeAttr('disabled');
        }else{
            alert('Por favor complete los campos');
        }
    });

    $(document).on('click', '#open-modal-create-compra', function(){
        $('#form_to_add_products').val('#products-create');
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
            $("#btn_create_buy").removeAttr('disabled');
        }else{
            $('#importe_total_compra_edit').text("$" + importe_total);
            $("#btn_edit_buy").removeAttr('disabled');
        }
        $('#modal_add_products').modal('hide');
    });

    $(document).on('click', '.btn-remove-product', function(){
        let tr = $(this).parents('tr');
        $("#select-product-compra").append($('<option>', {
            value : tr.find('.id_producto_table').val(),
            text: tr.find('.id_producto_table').data('text')
        }));
        tr.remove();
        if($('#table-add-products > tbody').text() == ''){
            $('#btn_add_products').attr('disabled', 'disabled');
            $("#btn_create_buy").removeAttr('disabled');
        }
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
        $('#edit-product-form input[value="' + $(this).data('razon-social-proveedor') + '"]').attr("checked", true);
        $('#edit-product-form input[value="' + $(this).data('compra-o-gasto') + '"]').attr("checked", true);
        $('#descuentos_iva_compra_edit').val($(this).data('descuentos-iva'));
        $('#importe_total_compra_edit').text('$' + $(this).data('importe-total'));
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
                $('#table-add-products > tbody').empty();
                if(data.length != 0){
                    $.each(data, function(i, det_comp_prod){
                        let text_unidad_medida = det_comp_prod.unidad_de_medida.nombre + " (" + det_comp_prod.unidad_de_medida.abreviatura + ")";
                        let tr = $('<tr>');
                        let td1 = $('<td>', {
                            text: det_comp_prod.producto.nombre
                        });
                        let hidden1 = $('<input>', {
                            type: "hidden",
                            class: "id_producto_table",
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
                            value: det_comp_prod.cantidad
                        });
                        td3.append(hidden3);
                        tr.append(td3);
                        let td4 = $('<td>', {
                            text: det_comp_prod.precio_compra
                        });
                        let hidden4 = $('<input>', {
                            type: "hidden",
                            class: "precio_compra_producto_table",
                            value: det_comp_prod.precio_compra
                        });
                        td4.append(hidden4);
                        tr.append(td4);
                        let td5 = $('<td>', {
                            text: det_comp_prod.precio_venta
                        });
                        let hidden5 = $('<input>', {
                            type: "hidden",
                            class: "precio_venta_producto_tabla",
                            value: det_comp_prod.precio_venta
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
                        tr.append(td6);
                        $('#table-add-products > tbody').append(tr);
                        $('#select-product-compra > option[value="' + det_comp_prod.producto.id + '"]').remove();
                    });
                    $('#btn_add_products').removeAttr('disabled');
                }else{
                    $('#btn_add_products').attr('disabled', true);
                    $('#btn_edit_buy').attr('disabled', true);
                }
                $('#form_to_add_products').val('#products-edit');
            }
        });
    });
});