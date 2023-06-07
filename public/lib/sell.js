$(function(){

    $('#table-sell').DataTable(
        {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],}
    );

    if($('#succes_message').length)
        sweetMessage('', $('#succes_message').val());

    if($('#fail_message').length)
        sweetMessage('', $('#fail_message').val(), 'error');

    $('.radio-btn-vehicle-type').on('change', function(){
        $.ajax({
            url: $(this).data('url'),
            type: "GET",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function(data, textStatus, xhr){
                $("#div-packages").removeClass("d-none");
                $("#div-buttons-package").empty();
                $.each(data.paquetes, function(i, paquete){
                    $("#div-buttons-package").append([
                        $("<label>", {
                            class: "btn btn-outline-primary",
                            style: "min-width:240.938px;",
                            html: [
                                $("<input>", {
                                    type: "radio",
                                    name: "id_detalle_paquete",
                                    value: paquete.id_detalle_paquete,
                                    class: "button_package"
                                }).attr({
                                    "data-price": paquete.precio,
                                    "data-id": paquete.id_detalle_paquete,
                                    "data-text" : paquete.nombre + " - " + paquete.tipo_vehiculo.descripcion
                                }),
                                $("<div>", {
                                    class: "card border border-dark text-center text-light",
                                    style: "border-radius: 1em; overflow:hidden;",
                                    html: [
                                        $("<div>", {
                                            class: "card-header bg-dark px-2",
                                            html: "<h4><strong>" + paquete.nombre + "</strong></h4>"
                                        }),
                                        $("<div>", {
                                            class: "card-body px-2 py-3",
                                            style: "background: linear-gradient(" + paquete.color.split(',')[0] + ", #a8a4a4); color: " + paquete.color.split(',')[1] + ";",
                                            html: [
                                                $('<strong>', {
                                                    text: paquete.tipo_vehiculo.descripcion
                                                }),
                                                '<br><br>',
                                                $('<strong>', {
                                                    text: "$ " + paquete.precio
                                                }),
                                                '<hr>',
                                                $("<strong>", {
                                                    class: "card-title",
                                                    style: "color: " + paquete.color.split(',')[2],
                                                    id: "servicios-" + paquete.id,
                                                })
                                            ]
                                        })
                                    ]
                                })
                            ]
                        }),
                    ]);
                    $.each(paquete.servicios_paquete, function(j, servicio_paquete){
                        $("#servicios-" + paquete.id).append(servicio_paquete.servicio.nombre);
                        if(paquete.servicios_paquete[j+1]){
                            $("#servicios-" + paquete.id).append(" - ");
                        }
                    })
                });
            }
        });
    });

    $(document).on("click", ".button_package", function(){
        if(!$("#tr-package").is(":empty")){
            $("#importe_total").val(parseFloat($("#importe_total").val()) - $("#tr-package .btn-remove-package").data("total"));
        }
        $("#tr-package").html([
            $("<td>", {
                html: [
                    $(this).data("text")
                ]
            }),
            $("<td>", {
                text: "$ " + $(this).data("price")
            }),
            $("<td>", {
                text: 1
            }),
            $("<td>", {
                text: "$ " + $(this).data("price")
            }),
            $("<td>", {
                html: $('<a>',{
                    class: 'btn-remove-package',
                    html: $("<i>", {
                        class : "mdi mdi-minus-box text-danger mdi-24px"
                    })
                }).attr({
                    "data-id": $(this).val(),
                    "data-total": $(this).data("price")
                })
            })
        ]);
        $("#importe_total").val(parseFloat($("#importe_total").val()) + parseFloat($(this).data("price")));
        $("#text_importe_total").text($("#importe_total").val());
    });

    $(document).on('click', '.btn-remove-package', function(){
        let tr = $(this).parents('tr');
        $(".button_package[value='" + $(this).data('id') + "']").prop('checked', false).trigger("change");
        $(".button_package[value='" + $(this).data('id') + "']").parent("label").removeClass("active");

        $("#importe_total").val(parseFloat($("#importe_total").val()) - parseFloat($(this).data("total")));
        $("#text_importe_total").text($("#importe_total").val());
        tr.empty();
    });

    $(document).on("change", "#select-product", function(){
        if($(this).val() != ""){
            $("#input-quantity-available-product").val($(this).find(":selected").data('quantity'));
        }
    });

    $(document).on("click", "#btn-add-products", function(){
        if($("#select-product").val() != '' && $("#input-quantity-product").val() != ""){
            if($("#input-quantity-product").val() != 0){
                if(parseInt($("#input-quantity-product").val()) <= parseInt($("#input-quantity-available-product").val())){
                    let total = parseFloat($("#select-product :selected").data("price")) * parseFloat($("#input-quantity-product").val());
                    let margen_ganancia = parseFloat($("#select-product :selected").data("price")) - parseFloat($("#select-product :selected").data("buy-price"));
                    $("#table-products tbody").append(
                        $("<tr>", {
                            html: [
                                $("<td>",{
                                    html: [
                                        $("#select-product :selected").data("text"),
                                        $("<input>", {
                                            type: "hidden",
                                            name: "id_producto[]",
                                            value: $("#select-product").val(),
                                        })
                                    ]
                                }),
                                $("<td>",{
                                    html: [
                                        "$ " + $("#select-product :selected").data("price"),
                                        $("<input>", {
                                            type: "hidden",
                                            name: "precio_venta[]",
                                            value: $("#select-product :selected").data("price"),
                                        }),
                                        $("<input>", {
                                            type: "hidden",
                                            name: "margen_ganancia[]",
                                            value: margen_ganancia,
                                        })
                                    ]
                                }),
                                $("<td>",{
                                    html: [
                                        $("#input-quantity-product").val(),
                                        $("<input>", {
                                            type: "hidden",
                                            name: "cantidad[]",
                                            value: $("#input-quantity-product").val(),
                                        })
                                    ]
                                }),
                                $("<td>",{
                                    text: "$ " + total
                                }),
                                $("<td>",{
                                    html: [
                                        $('<a>',{
                                            class: 'btn-remove-product',
                                            html: $("<i>", {
                                                class : "mdi mdi-minus-box text-danger mdi-24px"
                                            })
                                        }).attr({
                                            "data-id": $("#select-product").val(),
                                            "data-text": $("#select-product :selected").data("text"),
                                            "data-quantity": $("#select-product :selected").data("quantity"),
                                            "data-price": $("#select-product :selected").data("price"),
                                            "data-total": total
                                        })
                                    ]
                                }),
                            ]
                        })
                    );
                    $("#select-product :selected").remove().trigger("change");
                    $("#importe_total").val(parseFloat($("#importe_total").val()) + parseFloat(total));
                    $("#text_importe_total").text($("#importe_total").val());
                    $("#input-quantity-available-product").val("");
                    $("#input-quantity-product").val("");
                }else{
                    sweetMessage('¡Advertencia!', 'La cantidad ingresada supera la disponible', 'warning');
                }
            }else{
                sweetMessage('¡Advertencia!', 'La cantidad no es valida', 'warning');
            }
        }else{
            sweetMessage('¡Advertencia!', 'Por favor complete los campos requeridos para el producto', 'warning');
        }
    });

    $(document).on('click', '.btn-remove-product', function(){
        let tr = $(this).parents('tr');
        $("#select-product").append($('<option>', {
            value : $(this).data("id"),
            text: $(this).data('text') + " - $ " + $(this).data("price")
        }).attr({
            "data-price": $(this).data("price"),
            "data-quantity": $(this).data("quantity"),
            "data-text": $(this).data("text")
        }));
        $("#importe_total").val(parseFloat($("#importe_total").val()) - parseFloat($(this).data("total")));
        $("#text_importe_total").text($("#importe_total").val());
        tr.remove();
    });

    $(document).on("change", "#type-sale", function(){
        if($(this).val() == 1){
            $("#card-vehicle-type").toggle();
            $("#card-products").toggle();
        }else{
            $("#card-vehicle-type").toggle();
            $("#card-products").toggle();
        }
    });
});