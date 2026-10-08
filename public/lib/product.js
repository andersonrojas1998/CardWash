

$(function(){

   

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
                    let res = jqXHR.responseJSON.errors;
                    let output = '';
                    $.each(res, function(i, value){
                        output += value + '\n';
                    });
                    sweetMessage('', output, 'warning');
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
                $('.select-marca').trigger('change.select2');
                sweetMessage('', data.success);
            }
        });
    });

    $(document).on('change', '#sel_area_option', function(){
        let val=$(this).val();
        dt_product(val);       
    });

    $(document).on('focus', '.precio_venta', function(){
        var t=0;
        let rr=$('#rr').val();
        let cc=$('#cc').val();
        console.log(parseInt(rr),parseInt(cc));
         t = (parseInt(cc) * (100/(100-parseInt(rr))));
        
        $(this).val(parseInt(t));
               
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
                    sweetMessage('', output, 'warning');
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
                $('.select-tipo-producto').trigger('change.select2');
                sweetMessage('', data.success);
            }
        });
    });

    $(document).on('click', '.btn_show_edit_product', function(){
        $('input:radio').removeAttr('checked');
        $("#id_producto").val($(this).data('id'));
        $("#name_product_edit").val($(this).data('nombre'));
        $('#select_tipo_producto_edit').val($(this).data('id-tipo-producto'));
        $('#select_marca_edit').val($(this).data('id-marca'));
        $('#select-unidad-de-medida-edit').val($(this).data('id-unidad-medida'));
        $('#select-presentation-edit').val($(this).data('id-presentacion'));
        $('.select2-edit').trigger('change.select2');
        $("#input-price-edit").val($(this).data('precio_venta'));
        // Mostrar imagen actual en modal de edición
        var img = $(this).data('imagen');
        if(img){
            $('#preview-edit-img').attr('src', img).show();
            $('#preview-edit-placeholder').hide();
        } else {
            $('#preview-edit-img').attr('src','').hide();
            $('#preview-edit-placeholder').show();
        }
        $('#input-imagen-edit').val('');
    });

    // Preview imagen en modal editar
    $(document).on('change', '#input-imagen-edit', function(){
        var file = this.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#preview-edit-img').attr('src', e.target.result).show();
                $('#preview-edit-placeholder').hide();
            };
            reader.readAsDataURL(file);
        }
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
                var newOption = new Option(marca.nombre, marca.id, false, false);
                $('.select-marca').append(newOption).trigger('change');
            });
            if($('#old-select-brand').length){
                $('.select-marca').val($('#old-select-brand').val());
                $('.select-marca').trigger('change.select2');
            }
        }
    });

    if($('#succes_message').length)
        sweetMessage('', $('#succes_message').val());

    if($('#fail_message').length)
        sweetMessage('', $('#fail_message').val(), 'error');

    $('.select2-create').select2({
        dropdownParent: $('#modal_create_product')
    });

    $('.select2-edit').select2({
        dropdownParent: $('#modal_edit_product')
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
                var newOption = new Option(tipo_producto.descripcion, tipo_producto.id, false, false);
                $('.select-tipo-producto').append(newOption).trigger('change');
            });
            if($('#old-select-product-type').length){
                $('.select-tipo-producto').val($('#old-select-product-type').val());
                $('.select-tipo-producto').trigger('change.select2');
            }
        }
    });

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
                if($('#old-select-unit-measurement').length)
                    $('.select-unidad-de-medida').val($('#old-select-unit-measurement').val());
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
});


var loadPresentationOptions = function(){
    $.ajax({
        url: $("#select-presentation-data-url").val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $('.select-presentation').empty();
            $.each(data.presentaciones, function(i, presentacion){
                $('.select-presentation').append($('<option>',{
                    value: presentacion.id,
                    text: presentacion.nombre
                }));
            });
            if($('#old-select-presentation').length)
                $('.select-presentacion').val($('#old-select-presentation').val());
        }
    });
}

var loadAreaOptions = function(){
    $.ajax({
        url: $("#select-area-data-url").val(),
        type: "GET",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        success: function(data, textStatus, xhr){
            $('.select-area').empty();
            $('#sel_area_option').append($('<option>',{
                value: -1,
                text: "Todas las \u00e1reas"
            }));
            $.each(data.presentaciones, function(i, presentacion){
                $('.select-area').append($('<option>',{
                    value: presentacion.id,
                    text: presentacion.nombre
                }));
            });
            //if($('#old-select-presentation').length)
             //   $('.select-presentacion').val($('#old-select-presentation').val());
        }
    });
}
 function dt_product(area){

    $('#table-product').DataTable({
        dom: 'Bfrtip',
        destroy:true,
        buttons: [
             'csv', 'excel', 'pdf'
        ],
        ajax:{
            url:  '/producto/data/'+area,
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
        autoWidth: false,
        columnDefs: [
            {"className": "text-center align-middle", "targets": "_all"},
        ],
        columns:[
            {"data": "imagen", "width": "80px", render(data){
                var src = data ? data : '/img/default-product.svg';
                return '<img src="'+src+'" alt="producto" style="width:60px;height:60px;object-fit:cover;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.15);" onerror="this.src=\'/img/default-product.svg\'">';
            }},
            {"data": "producto", "width": "22%", render(data){ return '<b class="text-primary text-uppercase">'+ data +'</b>' ;  }},
            {"data": "tipo_producto", "width": "13%", render(data){ return '<span class="text-uppercase">'+ data +'</span>' ;  } },
            {"data": "marca", "width": "10%", render(data){ return '<span class="text-uppercase">'+ data +'</span>' ;  }},
            {"data": "unidad_medida", "width": "11%", render(data){ return '<span class="text-uppercase">'+ data +'</span>' ;  }},
            {"data": "presentacion", "width": "10%", render(data){ return '<span class="text-uppercase">'+ data +'</span>' ;  }},
            { "data": "cant_disponible", "width": "9%", render(data){
                let color=(data<5)? 'badge-warning':'badge-success';
                return '<span class="badge text-white '+color+'" style="font-size:13px;padding:5px 8px;">'+ data +'</span>';
             }},
            { "data": "precio_venta", "width": "10%", render(data){ return '<b class="text-danger">$'+ new Intl.NumberFormat('es-CO').format(parseInt(data))+'</b>' ; }},
            {"data": "actions", "width": "55px", render(data, ps, producto){
                
                let div = $('<div>',{
                    html: $("<a>", {
                        class: "btn_show_edit_product",
                        html:$("<i>", {
                            class : "mdi mdi-pencil-box-outline text-primary mdi-24px",
                            title: "Editar producto"
                        }).attr('data-toggle', 'tooltip')
                    }).attr({
                        'data-id': producto.id,
                        'data-nombre': producto.producto,
                        'data-id-tipo-producto': producto.id_tipo_producto,
                        'data-id-marca': producto.id_marca,
                        'data-id-unidad-medida': producto.id_unidad_medida,
                        'data-id-presentacion': producto.id_presentacion,
                        'data-precio_venta': producto.precio_venta,
                        'data-imagen': producto.imagen || '',
                        'data-toggle': 'modal',
                        'data-target': '#modal_edit_product',
                    })
                });
                return div.html();
            }
        }
        ],

        rowCallback:function(row,data,index){
            let cantidad=data.cantidad;
            switch(true){                
                case (parseInt(cantidad) >= 0 && parseInt(cantidad) <= 5):
                    $('td', row).css('background-color', 'rgba(238, 249, 71, 0.35)');
                break;                
            }
        }

    });
 }
 dt_product(-1);
loadPresentationOptions();
loadAreaOptions();