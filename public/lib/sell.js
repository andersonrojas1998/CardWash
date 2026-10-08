

$(function(){

    // -- Helpers --------------------------------------------------
    var fmt = function(n){ return '$ ' + new Intl.NumberFormat('es-CO').format(Math.round(n)); };

    var vehicleIcons = {
        'AUTOMOVIL':'mdi-car','AUTO':'mdi-car',
        'MOTO':'mdi-motorbike','MOTOCICLETA':'mdi-motorbike',
        'CAMIONETA':'mdi-car-pickup','PICKUP':'mdi-car-pickup',
        'CAMION':'mdi-truck','BUS':'mdi-bus','BUSETA':'mdi-bus-side',
        'VAN':'mdi-van-passenger'
    };

    function vehicleIcon(tipo) {
        if (!tipo) return 'mdi-car-question';
        var key = tipo.toUpperCase().trim();
        for (var k in vehicleIcons) { if (key.indexOf(k) !== -1) return vehicleIcons[k]; }
        return 'mdi-car-question';
    }

    function titleCase(str) {
        if (!str) return '-';
        return str.toLowerCase().replace(/\b\w/g, function(c){ return c.toUpperCase(); });
    }

    var _placaWrap   = 'display:inline-flex;flex-direction:column;align-items:center;background:#f5c400;border:2.5px solid #111;border-radius:4px;padding:3px 9px 2px;min-width:78px;box-shadow:1px 2px 6px rgba(0,0,0,.35);font-family:"Arial Black",Arial,sans-serif;line-height:1;text-align:center;';
    var _placaNum    = 'display:block;font-size:14px;font-weight:900;letter-spacing:3px;color:#000;text-transform:uppercase;';
    var _placaPais   = 'display:block;font-size:6px;font-weight:700;color:#000;letter-spacing:2px;text-transform:uppercase;border-top:1px solid #000;width:100%;text-align:center;margin-top:2px;padding-top:1px;';
    var _placaWrapSR = 'display:inline-flex;flex-direction:column;align-items:center;background:#e8e8e8;border:2px solid #bbb;border-radius:4px;padding:3px 9px 2px;min-width:78px;font-family:"Arial Black",Arial,sans-serif;line-height:1;text-align:center;';
    var _placaNumSR  = 'display:block;font-size:11px;font-weight:700;letter-spacing:1px;color:#aaa;';
    var _placaPaisSR = 'display:block;font-size:6px;font-weight:600;color:#ccc;letter-spacing:2px;text-transform:uppercase;border-top:1px solid #ccc;width:100%;text-align:center;margin-top:2px;padding-top:1px;';

    function renderPlaca(p) {
        if (!p || p === '-') {
            return '<div style="'+_placaWrapSR+'"><span style="'+_placaNumSR+'">- - -</span><span style="'+_placaPaisSR+'">COLOMBIA</span></div>';
        }
        return '<div style="'+_placaWrap+'"><span style="'+_placaNum+'">'+p.toUpperCase()+'</span><span style="'+_placaPais+'">COLOMBIA</span></div>';
    }

    function renderEstado(d, id) {
        var map = {1:'primary',2:'success',3:'secondary',4:'warning',5:'info'};
        return '<span class="estado-pill badge badge-'+(map[id]||'secondary')+' text-white">'+d+'</span>';
    }

    function renderAcciones(id, idEstado, idUsuario, withUser) {
        var h = '';
        if (withUser && idEstado != 2 && idEstado != 3) {
            h += '<a class="btn-acc btn-acc-user btn_show_change_user" data-venta="'+id+'" data-id="'+idUsuario+'" data-toggle="modal" data-target="#modal_edit_user_service" title="Cambiar prestador"><i class="mdi mdi-account-convert mdi-18px"></i></a>';
            h += '<a class="btn-acc btn-acc-edit" href="/venta/'+id+'/edit" title="Editar venta"><i class="mdi mdi-pencil-box-outline mdi-18px"></i></a>';
        }
        h += '<a class="btn-acc btn-acc-view" href="/venta/'+id+'" title="Ver detalle"><i class="mdi mdi-point-of-sale mdi-18px"></i></a>';
        return h;
    }

    // -- Filtro de fecha (client-side) ----------------------------
    var currentRange = 'today';

    function parseFecha(str) {
        var p = str.split(' ')[0].split('/');
        return new Date(+p[2], p[1]-1, +p[0]);
    }

    function inRange(dateStr, range) {
        if (range === 'all') return true;
        var d = parseFecha(dateStr);
        var today = new Date(); today.setHours(0,0,0,0);
        if (range === 'today') { return d >= today; }
        if (range === 'week')  { var w = new Date(today); w.setDate(w.getDate()-7);  return d >= w; }
        if (range === 'month') { var m = new Date(today); m.setMonth(m.getMonth()-1); return d >= m; }
        return true;
    }

    $.fn.dataTable.ext.search.push(function(settings, data) {
        if (settings.nTable.id !== 'table-servicios' && settings.nTable.id !== 'table-productos') return true;
        return inRange(data[1], currentRange);
    });

    function updateBadges() {
        if (typeof dtServicios !== 'undefined') $('#badge-servicios').text(dtServicios.rows({search:'applied'}).count());
        if (typeof dtProductos !== 'undefined') $('#badge-productos').text(dtProductos.rows({search:'applied'}).count());
    }

    var dtLang = {
        search: '', searchPlaceholder: 'Buscar...',
        zeroRecords: 'Sin resultados para este periodo',
        info: 'Mostrando _START_-_END_ de _TOTAL_', infoEmpty: 'Sin registros',
        paginate: { previous: '&#8249;', next: '&#8250;' }
    };

    // -- DataTable: Servicios Carwash -----------------------------
    var dtServicios = $('#table-servicios').DataTable({
        dom: '<"d-none"B>frtip',
        destroy: true,
        autoWidth: false,
        buttons: [
            { extend: 'excel', title: 'Servicios Carwash' },
            { extend: 'pdf',   title: 'Servicios Carwash' }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: dtLang,
        ajax: {
            url: '/venta/data/servicios',
            dataSrc: 'data',
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        },
        columns: [
            { data: 'id',            className: 'td-num text-center' },
            { data: 'fecha',         className: 'text-nowrap' },
            { data: 'cliente',       render: function(d){ return '<span class="td-cliente">'+titleCase(d)+'</span>'; } },
            { data: 'placa',         className: 'text-center', render: function(d){ return renderPlaca(d); } },
            { data: 'tipo_vehiculo', className: 'text-center text-uppercase', render: function(d){
                return '<span class="mdi '+vehicleIcon(d)+' mdi-18px text-primary d-block"></span><small style="font-size:.68rem;">'+d+'</small>';
            }},
            { data: 'paquete',       render: function(d){ return '<b class="text-uppercase" style="font-size:.8rem;">'+d+'</b>'; } },
            { data: 'atendido_por',  className: 'text-uppercase', render: function(d){ return '<small>'+d+'</small>'; } },
            { data: 'total',         className: 'text-right', render: function(d){ return '<b class="text-danger">'+fmt(d)+'</b>'; } },
            { data: 'estado',        className: 'text-center', render: function(d,t,row){ return renderEstado(d, row.id_estado); } },
            { data: 'id',            className: 'text-center', orderable: false,
              render: function(d,t,row){ return renderAcciones(d, row.id_estado, row.id_usuario, true); } }
        ]
    });
    dtServicios.on('draw.dt', updateBadges);

    // -- DataTable: Ventas Productos ------------------------------
    var dtProductos = $('#table-productos').DataTable({
        dom: '<"d-none"B>frtip',
        destroy: true,
        autoWidth: false,
        buttons: [
            { extend: 'excel', title: 'Ventas Productos' },
            { extend: 'pdf',   title: 'Ventas Productos' }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: dtLang,
        ajax: {
            url: '/venta/data/productos',
            dataSrc: 'data',
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        },
        columns: [
            { data: 'id',           className: 'td-num text-center' },
            { data: 'fecha',        className: 'text-nowrap' },
            { data: 'cliente',      render: function(d){ return '<span class="td-cliente">'+titleCase(d)+'</span>'; } },
            { data: 'productos',    render: function(d){ return '<span class="text-uppercase" style="font-size:.78rem;">'+d+'</span>'; } },
            { data: 'atendido_por', className: 'text-uppercase', render: function(d){ return '<small>'+d+'</small>'; } },
            { data: 'total',        className: 'text-right', render: function(d){ return '<b class="text-danger">'+fmt(d)+'</b>'; } },
            { data: 'id',           className: 'text-center', orderable: false,
              render: function(d,t,row){ return renderAcciones(d, row.id_estado, null, false); } }
        ]
    });
    dtProductos.on('draw.dt', updateBadges);

    // -- Filtro de fecha -----------------------------------------
    $('#date-filter-row .btn').on('click', function() {
        $('#date-filter-row .btn').removeClass('active');
        $(this).addClass('active');
        currentRange = $(this).data('range');
        dtServicios.draw();
        dtProductos.draw();
    });

    // -- Exportar (actua sobre la tab activa) --------------------
    $(document).on('click', '.export-btn', function(e){
        e.preventDefault();
        var idx = parseInt($(this).data('type'));
        var active = $('.venta-tabs .nav-link.active').attr('href');
        var dt = active === '#tab-servicios' ? dtServicios : dtProductos;
        dt.button(idx).trigger();
    });

    // Ajustar columnas al cambiar de tab
    $('a[href="#tab-productos"]').on('shown.bs.tab', function(){
        dtProductos.columns.adjust().draw(false);
    });

    if($('#succes_message').length)
        sweetMessage('', $('#succes_message').val());

    if($('#fail_message').length)
        sweetMessage('', $('#fail_message').val(), 'error');


    $(".navbar, #sidebar, .main-panel>footer").addClass("d-print-none");

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
                            html: [
                                $("<input>", {
                                    type: "radio",
                                    name: "id_detalle_paquete",
                                    value: paquete.id_detalle_paquete,
                                    class: "button_package"
                                }).attr({
                                    "data-price": paquete.precio,
                                    "data-percent": paquete.porcentaje,
                                    "data-id": paquete.id_detalle_paquete,
                                    "data-text" : paquete.nombre + " - " + paquete.tipo_vehiculo.descripcion
                                }),
                                $("<div>", {
                                    class: "card border border-dark text-center text-light",
                                    style: "border-radius: 1em; overflow:hidden; max-width:205.938px;",
                                    html: [
                                        $("<div>", {
                                            class: "card-header px-2",
                                            style: "background-color: black;",
                                            html: "<h1 class='m-0 text-uppercase'><strong>" + paquete.nombre + "</strong></h1>"
                                        }),
                                        $("<div>", {
                                            class: "card-body px-2 py-3",
                                            style: "background: linear-gradient(" + paquete.color.split(',')[0] + ", #a8a4a4); color: " + paquete.color.split(',')[1] + ";",
                                            html: [
                                                '<h2 class="m-0"><strong>' + paquete.tipo_vehiculo.descripcion + '</strong></h2>',
                                                '<h2 class="m-0"><strong>$ ' + paquete.precio + '</strong></h2>',
                                                '<hr class="my-3">',
                                                $("<strong>", {
                                                    class: "card-title",
                                                    style: "color: #fff; text-shadow: 2px 0 #000, -2px 0 #000, 0 2px #000, 0 -2px #000, 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;",
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



    if($('.edit-sell').length){

        var pack=$('.edit-sell').attr('data-pack');

        $.ajax({
            url: $('.edit-sell').attr('data-url-old'),
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
                    var conditional="";
                    if(parseInt(paquete.id) == parseInt(pack)){
                        conditional+=" active";
                    }
                    $("#div-buttons-package").append([
                        $("<label>", {
                            class: "btn btn-outline-primary" + conditional ,
                            html: [
                                $("<input>", {
                                    type: "radio",
                                    name: "id_detalle_paquete",
                                    value: paquete.id_detalle_paquete,
                                    class: "button_package"
                                }).attr({
                                    "data-cc":conditional,
                                    "data-price": paquete.precio,
                                    "data-percent": paquete.porcentaje,
                                    "data-id": paquete.id_detalle_paquete,
                                    "data-text" : paquete.nombre + " - " + paquete.tipo_vehiculo.descripcion
                                }),
                                $("<div>", {
                                    class: "card border border-dark text-center text-light",
                                    style: "border-radius: 1em; overflow:hidden; max-width:205.938px;",
                                    html: [
                                        $("<div>", {
                                            class: "card-header px-2",
                                            style: "background-color: black;",
                                            html: "<h1 class='m-0 text-uppercase'><strong>" + paquete.nombre + "</strong></h1>"
                                        }),
                                        $("<div>", {
                                            class: "card-body px-2 py-3",
                                            style: "background: linear-gradient(" + paquete.color.split(',')[0] + ", #a8a4a4); color: " + paquete.color.split(',')[1] + ";",
                                            html: [
                                                '<h2 class="m-0"><strong>' + paquete.tipo_vehiculo.descripcion + '</strong></h2>',
                                                '<h2 class="m-0"><strong>$ ' + paquete.precio + '</strong></h2>',
                                                '<hr class="my-3">',
                                                $("<strong>", {
                                                    class: "card-title",
                                                    style: "color: #fff; text-shadow: 2px 0 #000, -2px 0 #000, 0 2px #000, 0 -2px #000, 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;",
                                                    id: "servicios-" + paquete.id,
                                                })
                                            ]
                                        })
                                    ]
                                })
                            ]
                        }),
                    ]);

                    if(parseInt(paquete.id) == parseInt(pack)){
                        $('input[name="id_detalle_paquete"][value='+parseInt(paquete.id_detalle_paquete)+']').prop("checked",true).click();
                    }
                    $.each(paquete.servicios_paquete, function(j, servicio_paquete){
                        $("#servicios-" + paquete.id).append(servicio_paquete.servicio.nombre);
                        if(paquete.servicios_paquete[j+1]){
                            $("#servicios-" + paquete.id).append(" - ");
                        }
                    });

                });
            }
        });
    }

    $(document).on("click", ".button_package", function(){

        if(!$("#tr-package").is(":empty")){
            $("#importe_total").val(parseFloat($("#importe_total").val()) - $("#tr-package .btn-remove-package").data("total"));
        }
        $("#tr-package").html([
            $("<td>", { html: [ $(this).data("text") ] }),
            $("<td>", { text: "$ " + $(this).data("price") }),
            $("<td>", { text: 1 }),
            $("<td>", { text: "$ " + $(this).data("price") }),
            $("<input>", { type: "hidden", name: "precio_venta_paquete", value: $(this).data("price") }),
            $("<input>", { type: "hidden", name: "porcentaje_paquete",   value: $(this).data("percent") }),
            $("<td>", {
                html: $('<a>',{
                    class: 'btn-remove-package',
                    html: $("<i>", { class : "mdi mdi-minus-box text-danger mdi-24px" })
                }).attr({ "data-id": $(this).val(), "data-total": $(this).data("price") })
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
                                $("<td>",{ html: [ $("#select-product :selected").data("text"), $("<input>", { type: "hidden", name: "id_producto[]", value: $("#select-product").val() }) ] }),
                                $("<td>",{ html: [ "$ " + $("#select-product :selected").data("price"), $("<input>", { type: "hidden", name: "precio_venta[]", value: $("#select-product :selected").data("price") }), $("<input>", { type: "hidden", name: "margen_ganancia[]", value: margen_ganancia }) ] }),
                                $("<td>",{ html: [ $("#input-quantity-product").val(), $("<input>", { type: "hidden", name: "cantidad[]", value: $("#input-quantity-product").val() }) ] }),
                                $("<td>",{ text: "$ " + total }),
                                $("<td>",{ html: [ $('<a>',{ class: 'btn-remove-product', html: $("<i>", { class : "mdi mdi-minus-box text-danger mdi-24px" }) }).attr({ "data-id": $("#select-product").val(), "data-text": $("#select-product :selected").data("text"), "data-quantity": $("#select-product :selected").data("quantity"), "data-price": $("#select-product :selected").data("price"), "data-total": total }) ] }),
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
        }).attr({ "data-price": $(this).data("price"), "data-quantity": $(this).data("quantity"), "data-text": $(this).data("text") }));
        $("#importe_total").val(parseFloat($("#importe_total").val()) - parseFloat($(this).data("total")));
        $("#text_importe_total").text($("#importe_total").val());
        tr.remove();
    });

    $(document).on("change", "#type-sale", function(){
        if($(this).val() == 1){
            $("#card-vehicle-type").toggle();
        }else{
            $("#card-vehicle-type").toggle();
        }
    });

    $(document).on("click","#btn_show_change_user",function(){
        let id=$(this).attr('data-id');
        $('#id_venta').val($(this).attr('data-venta'));
        $('#user_service >option[value='+ id +']').attr('selected',true).trigger('change');
    });

    $(document).on("click",".btn_user_service",function(){
        let id_user=$('#user_service').val();
        let id_venta=$('#id_venta').val();
        $.ajax({
            url:'/update_user',
            type: "POST",
            data:{'id_user':id_user,'id_venta':id_venta},
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success:function(data){
                if(data==1){
                    sweetMessage('¡Registro exitoso!', '¡ Se ha realizado con éxito su solicitud!');
                    setTimeout(function () { location.reload() }, 2000);
                }
            }
        });
    });

    $(document).on("click",".btn_generateTicket",function(){
        let venta=$(this).attr('data-id');
        let url='/ticketPrint/'+venta;
        var xhr = new XMLHttpRequest();
        xhr.open("GET",url);
        xhr.responseType = 'arraybuffer';
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(null);
        sweetMessageTimeOut('Procesando ...', '¡  Su solicitud  se encuentra en ejecución ! ',5000);
        xhr.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                var blobURL = new Blob([this.response], {type:'text/html'});
                var objFra = document.createElement('iframe');
                objFra.style.visibility = "hidden";
                objFra.onload = function() {
                    try { this.contentWindow && this.contentWindow.print(); return; } catch (e) {}
                    console.error('in a protective iframe?');
                };
                objFra.src = URL.createObjectURL(blobURL);
                document.body.appendChild(objFra);
            }
            if (this.status === 500) { sweetMessage("ERROR!", "Error al generar el pdf !", "error", "#1976D2", false); }
        };
    });

});

function printIframe() {
    var iframe = document.getElementById('theFrame');
    var iframeWindow = iframe.contentWindow;
    iframeWindow.focus();
    iframeWindow.print();
}
