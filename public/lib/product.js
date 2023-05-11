$(document).ready(function(){

    $('#table-product').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax:{
            url: $('#table-product').data('url'),
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
            {"className": "text-center", "targets": "_all"},
        ],
        columns:[
            {"data": "nombre"},
            {"data": "marca.nombre"},
            {"data": "tipo_producto.descripcion"},
            {"data": "es_de_venta", render(es_de_venta){
                return (es_de_venta == 1)? "Producto de venta" : "Uso interno";
            }},            
            { "data": "cantidad",render(data){ return '<h4><label class="badge text-white badge-success">'+ data  +'</label></h4>'; }},
            {"data": "actions", render(data, ps, producto){
                let div = $('<div>');
                let button = $("<a>", {
                    class: "btn_show_edit_product",
                });
                button.attr('data-id', producto.id);
                button.attr('data-nombre', producto.nombre);
                button.attr('data-id-marca', producto.id_marca);
                button.attr('data-id-tipo-producto', producto.id_tipo_producto);
                button.attr('data-es-de-venta', producto.es_de_venta);
                button.attr('data-toggle', 'modal');
                button.attr('data-target', '#modal_edit_product');
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

    $(document).on('click', '#save-brand', function(){
        let funTransitions = function(){
            if(!$('#save-brand').attr('disabled')){
                $('#save-brand').attr('disabled', true);
                $('#form-fields-brand').addClass('d-none');
                $('#spinner-brand').removeClass('d-none');
            }else{
                $('#save-brand').attr('disabled', false);
                $('#form-fields-brand').removeClass('d-none');
                $('#spinner-brand').addClass('d-none');
            }
        }
        funTransitions();
        let formData = new FormData($('#create-brand-form')[0]);
        $.ajax({
            url: $('#create-brand-form').attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            error: function(jqXHR, textStatus, errorThrown){
                funTransitions();
                if(jqXHR.status == 422){
                    let res = JSON.parse(jqXHR.responseText);
                    let output = '';
                    $.each(res, function(i, value){
                        output += value + '\n';
                    });
                    alert(output);
                }
            },
            success: function(data, textStatus, xhr){
                funTransitions();
                $(':input','#create-brand-form').val('');
                $(".select-marca :contains('Seleccione la marca')").remove();
                $('.select-marca').prepend($('<option>',{
                    value: data.marca.id,
                    text: data.marca.nombre
                }));
                $(".select-marca").prepend($('<option>', {
                    value: '',
                    text: 'Seleccione la marca'
                }));
                $('.select-marca').val(data.marca.id);
                alert(data.success);
            }
        });
    });

    $(document).on('click', '#save-product-type', function(){
        let funTransitions = function(){
            if(!$('#save-product-type').attr('disabled')){
                $('#save-product-type').attr('disabled', true);
                $('#form-fields-product-type').addClass('d-none');
                $('#spinner-product-type').removeClass('d-none');
            }else{
                $('#save-product-type').attr('disabled', false);
                $('#form-fields-product-type').removeClass('d-none');
                $('#spinner-product-type').addClass('d-none');
            }
        }
        funTransitions();
        let formData = new FormData($('#create-product-type-form')[0]);
        $.ajax({
            url: $('#create-product-type-form').attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            error: function(jqXHR, textStatus, errorThrown){
                funTransitions();
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
                funTransitions();
                $(':input','#create-product-type-form').val('');
                $(".select-tipo-producto :contains('Seleccione tipo de producto')").remove();
                $('.select-tipo-producto').prepend($('<option>',{
                    value: data.tipo_producto.id,
                    text: data.tipo_producto.descripcion
                }));
                $('.select-tipo-producto').prepend("<option>",{
                    value: '',
                    text:'Seleccione tipo de producto'
                });
                $('.select-tipo-producto').val(data.tipo_producto.id);
                alert(data.success);
            }
        });
    });

    $(document).on('click', '.btn_show_edit_product', function(){
        $('input:radio').removeAttr('checked');
        $("#id_producto").val($(this).data('id'));
        $("#name_product_edit").val($(this).data('nombre'));
        $('#select_tipo_producto_edit').val($(this).data('id-tipo-producto'));
        $('#select_marca_edit').val($(this).data('id-marca'));
        $('#edit-product-form input[value="' + $(this).data('es-de-venta') + '"]').attr('checked', true);
    });

    $.ajax({
        url: $('#select-brand-data-url').val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $.each(data.marcas, function(i, marca){
                $('.select-marca').append($('<option>',{
                    value: marca.id,
                    text: marca.nombre
                }));
            });
            if($('#old-select-brand').length)
                $('.select-marca').val($('#old-select-brand').val());
        }
    });

    $.ajax({
        url: $("#select-product-type-data-url").val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $.each(data.tipos_productos, function(i, tipo_producto){
                $('.select-tipo-producto').append($('<option>',{
                    value: tipo_producto.id,
                    text: tipo_producto.descripcion
                }));
            });
            if($('#old-select-product-type').length)
                $('.select-tipo-producto').val($('#old-select-product-type').val());
        }
    });
});