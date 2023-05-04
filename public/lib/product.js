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
            //{width: "2%", target: 0},
            {"className": "text-center", "targets": "_all"},
        ],
        columns:[
            {"data": "nombre"},
            {"data": "marca.nombre"},
            {"data": "tipo_producto.descripcion"},
            {"data": "unidad_de_medida", render(unidad_de_medida){
                return unidad_de_medida.nombre + " (" + unidad_de_medida.abreviatura + ")";
            }},
            {"data": "actions", render(data, ps, producto){
                let div = $('<div>');
                let button = $("<a>", {
                    class: "btn_show_edit_product",
                });
                button.attr('data-id', producto.id);
                button.attr('data-nombre', producto.nombre);
                button.attr('data-id-marca', producto.id_marca);
                button.attr('data-id-tipo-producto', producto.id_tipo_producto);
                button.attr('data-id-unidad-medida', producto.id_unidad_de_medida);
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
        let url = $(this).data('url');
        let funTransitions = function(transition){
            if(transition == 0){
                $('#save-brand').attr('disabled', true);
                $('#form-fields-brand').addClass('d-none');
                $('#spinner-brand').removeClass('d-none');
            }else{
                $('#save-brand').attr('disabled', false);
                $('#form-fields-brand').removeClass('d-none');
                $('#spinner-brand').addClass('d-none');
            }
        }
        funTransitions(0);
        $('#create-brand-form').validate({
            rules:{
                nombre: {required: true},
            },
            invalidHandler: function(event, validator) {
                funTransitions(1);
            },
            submitHandler: function(form){
                let formData = new FormData($('#create-brand-form')[0]);
                $.ajax({
                    url: url,
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
                            $.each(res, function(i, value){
                                output += value + '\n';
                            });
                            alert(output);
                        }
                    },
                    success: function(data, textStatus, xhr){
                        funTransitions(1);
                        $(':input','#create-brand-form').val('');
                        $(".select-marca :contains('Seleccione la marca')").remove();
                        $('.select-marca').prepend($('<option>',{
                            value: data.marca.id,
                            text: data.marca.nombre
                        }));
                        $(".select-marca").prepend($('<option>', {
                            text: 'Seleccione la marca'
                        }));
                        $('.select-marca').val(data.marca.id);
                        alert(data.success);
                    }
                });
            }
        });
    });

    $(document).on('click', '#save-product-type', function(){
        let url = $(this).data('url');
        let funTransitions = function(transition){
            if(transition == 0){
                $('#save-product-type').attr('disabled', true);
                $('#form-fields-product-type').addClass('d-none');
                $('#spinner-product-type').removeClass('d-none');
            }else{
                $('#save-product-type').attr('disabled', false);
                $('#form-fields-product-type').removeClass('d-none');
                $('#spinner-product-type').addClass('d-none');
            }
        }
        funTransitions(0);
        $('#create-product-type-form').validate({
            rules:{
                descripcion: {required: true},
            },
            invalidHandler: function(event, validator) {
                funTransitions(1);
            },
            submitHandler: function(form){
                let formData = new FormData($('#create-product-type-form')[0]);
                $.ajax({
                    url: url,
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
                        $(':input','#create-product-type-form').val('');
                        $(".select-tipo-producto :contains('Seleccione tipo de producto')").remove();
                        $('.select-tipo-producto').prepend($('<option>',{
                            value: data.tipo_producto.id,
                            text: data.tipo_producto.descripcion
                        }));
                        $('.select-tipo-producto').prepend("<option>",{
                            text:'Seleccione tipo de producto'
                        });
                        $('.select-tipo-producto').val(data.tipo_producto.id);
                        alert(data.success);
                    }
                });
            }
        });
    });

    $(document).on('click', '#save-unit-measurement', function(){
        let url = $(this).data('url');
        let funTransitions = function(transition){
            if(transition == 0){
                $('#save-unit-measurement').attr('disabled', true);
                $('#form-fields-unit-measurement').addClass('d-none');
                $('#spinner-unit-measurement').removeClass('d-none');
            }else{
                $('#save-unit-measurement').attr('disabled', false);
                $('#form-fields-unit-measurement').removeClass('d-none');
                $('#spinner-unit-measurement').addClass('d-none');
            }
        }
        funTransitions(0);
        $('#create-unit-measurement-form').validate({
            rules:{
                nombre: {required: true},
                abreviatura: {required: true}
            },
            invalidHandler: function(event, validator) {
                funTransitions(1);
            },
            submitHandler: function(form){
                let formData = new FormData($('#create-unit-measurement-form')[0]);
                $.ajax({
                    url: url,
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
                            text: 'Seleccione unidad de medida'
                        }));
                        $(".select-unidad-de-medida").val(data.unidad_de_medida.id);
                        alert(data.success);
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn_show_edit_product', function(){
        $("#id_producto").val($(this).data('id'));
        $("#name_product_edit").val($(this).data('nombre'));
        $('#select_tipo_producto_edit').val($(this).data('id-tipo-producto'));
        $('#select_marca_edit').val($(this).data('id-marca'));
        $('#select_unidad_medida_edit').val($(this).data('id-unidad-medida'));
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
        }
    });

    $.ajax({
        url: $("#select-unit-measurement-data-url").val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $.each(data.unidades_de_medida, function(i, unidad_de_medida){
                $('.select-unidad-de-medida').append($('<option>',{
                    value: unidad_de_medida.id,
                    text: unidad_de_medida.nombre + " (" + unidad_de_medida.abreviatura + ")"
                }));
            });
        }
    });
});