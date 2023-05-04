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

    $(document).on('click', '.btn_show_edit_compra', function(){
        $('#id_compra').val($(this).data('id'));
        $('#reg_op_compra_edit').val($(this).data('reg-op'));
        $('#fecha_emision_compra_edit').val($(this).data('fecha-emision'));
        $('#compracol_edit').val($(this).data('compracol'));
        $('#fecha_vencimiento_compra_edit').val($(this).data('fecha-vencimiento'));
        $('#no_comprobante_compra_edit').val($(this).data('no-comprobante'));
        $('#id_proveedor_compra_edit').val($(this).data('id-proveedor'));
        $('#razon_social_proveedor_compra_edit').val($(this).data('razon-social-proveedor'));
        $('#compra_o_gasto_compra_edit').val($(this).data('compra-o-gasto'));
        $('#descuentos_iva_compra_edit').val($(this).data('descuentos-iva'));
        $('#importe_total_compra_edit').val($(this).data('importe-total'));
        $('#condiciones_id_compra_edit').val($(this).data('condiciones-id'));
    });
});