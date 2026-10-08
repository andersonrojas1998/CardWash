@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-header"><h3>Registrar Venta</h3></div>
    <form id="form_create_sell" action="{{route('venta.store')}}" method="POST">
        {{ csrf_field() }}

        @if (session('fail'))
            <div class="alert alert-warning">
               <ul><li>{{ session('fail') }}</li></ul> 
            </div>
        @endif



        <fieldset>
            <div class="card-body">
                <div class="d-flex justify-content-around mb-3">
                    <div class="col-lg-4">
                        <label for="input_name_customer">Cliente&nbsp;:</label>
                        <input type="text" id="input_name_customer" name="nombre_cliente" class="form-control text-uppercase" placeholder="Cliente" value="{{old('nombre_cliente')}}" required>
                        @if ($errors->any() && $errors->first('nombre_cliente'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('nombre_cliente')}}</span>
                        @endif
                    </div>

                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Fecha&nbsp;:</label>
                        </div>
                        <div class="row pl-3">
                            <label>{{ date('Y-m-d h:i A')}}</label>
                        </div>
                    </div>
                    
                </div>

                <div class="d-flex justify-content-around mb-3 pb-3">
                    <div class="col-lg-4">
                        <label for="input_license_plate" class="control-label">Placa&nbsp;:</label>
                        <input type="text" id="input_license_plate" name="placa" class="form-control text-uppercase" placeholder="Placa" value="{{old('placa')}}">
                        @if ($errors->any() && $errors->first('placa'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('placa')}}</span>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <label for="input_phone_number" class="control-label">Telefono&nbsp;:</label>
                        <input type="number" id="input_phone_number" name="numero_telefono" class="form-control text-uppercase" placeholder="Telefono del cliente" value="{{old('numero_telefono')}}" required>
                        @if ($errors->any() && $errors->first('numero_telefono'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('numero_telefono')}}</span>
                        @endif
                    </div>
                </div>

                {{-- Campos km para ficha de aceite --}}
                <div class="card mt-2 mb-3 border-warning" id="card-km-aceite" style="display:none!important;">
                    <div class="card-header text-center bg-warning text-dark">
                        <i class="mdi mdi-oil"></i>&nbsp;<strong>Datos para Ficha de Cambio de Aceite</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-around">
                            <div class="col-lg-4">
                                <label class="control-label">Km Actual&nbsp;:</label>
                                <input type="number" name="km_actual" id="input_km_actual" class="form-control" placeholder="Ej: 80000" value="{{old('km_actual')}}">
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label">Km Próximo Cambio&nbsp;:</label>
                                <input type="number" name="km_proximo_cambio" id="input_km_proximo" class="form-control" placeholder="Ej: 85000" value="{{old('km_proximo_cambio')}}">
                            </div>
                        </div>
                        <p class="text-center text-muted mt-2 mb-0"><small>Al guardar la venta podrás imprimir la etiqueta para el vidrio del vehículo</small></p>
                    </div>
                </div>

                <div class="d-flex justify-content-around mb-3 pb-3">
                    <div class="col-lg-4">
                        <label>¿Qui&eacute;n presta el servicio?&nbsp;:</label>
                        <select class="select2" name="id_usuario" style="width: 100%">
                            @foreach($usuarios as $usuario)
                            <option value="{{$usuario->id}}" {{ ($usuario->id==17)? 'selected':'' }}>{{$usuario->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="type-sale">Tipo de venta</label>
                        <select class="custom-select" name="id_estado_venta" id="type-sale">
                            <option value="1">Servicio y productos</option>
                            <option value="3">Productos</option>
                        </select>
                    </div>
                </div>

                <div class="card" id="card-vehicle-type">
                    <div class="card-header text-center">
                        Seleccione el tipo de vehiculo al que aplica el servicio
                    </div>
                    <div>
                        <div class="d-flex justify-content-center pt-4">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                @foreach($tipos_vehiculo as $tipo_vehiculo)
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="id_tipo_vehiculo" class="radio-btn-vehicle-type" value="{{$tipo_vehiculo->id}}" data-url="{{route('paquete.packagesByVehicleType',[$tipo_vehiculo->id])}}"> 
                                    <img src="{{asset($tipo_vehiculo->imagen)}}" class="rounded" alt="{{$tipo_vehiculo->descripcion}}" data-toggle="tooltip" title="{{$tipo_vehiculo->descripcion}}" height="90px" width="140px">
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 d-none" id="div-packages">
                    <div class="card-header text-center">
                        Seleccione el combo o servicio
                    </div>
                    <div>
                        <div class="d-flex justify-content-center pt-4">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" style="overflow-x: scroll" id="div-buttons-package">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4" id="card-products">
                    <div class="card-header text-center">
                        Agregar productos a la venta
                    </div>
                    <div>
                        <div class="d-flex justify-content-center pt-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Producto&nbsp;:</label>
                                    <select class="select2" id="select-product" style="width:100%;">
                                        @if(count($productos) != 0)
                                        <option value="">Seleccione el producto...</option>
                                        @foreach($productos as $producto)
                                            <!-- <option value="{{--$producto->id_detalle_compra--}}" data-price="{{--$producto->precio_venta--}}" data-buy-price="{{--$producto->precio_compra--}}" data-quantity="{{--$producto->cantidad_disponible--}}" data-text="{{--$producto->producto->nombre.' - '.$producto->producto->tipo_producto->descripcion.' - '.$producto->producto->presentacion->nombre--}}"></option> -->
                                            <option value="{{$producto->id}}" data-price="{{$producto->precio_venta}}" data-buy-price="{{$producto->precio_venta}}" data-quantity="{{$producto->cant_disponible}}" data-text="{{$producto->producto.' - '.$producto->tipo_producto.' - '.$producto->presentacion.' - $ '.$producto->precio_venta}}" >{{$producto->producto.' - '.$producto->tipo_producto.' - '.$producto->presentacion}}</option>
                                        @endforeach
                                    @else
                                    <option value="">Existencias agotadas</option>
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around pt-3">
                            <div class="col-lg-4">
                                <div>
                                    <label class="control-label">Disponible&nbsp;:</label>
                                    <input type="number" class="form-control" id="input-quantity-available-product" placeholder="Disponible" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label class="control-label">Cantidad&nbsp;:</label>
                                    <input type="number" class="form-control" id="input-quantity-product" placeholder="Cantidad">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-4">
                            <button type="button" class="btn btn-primary" id="btn-add-products" title="Agregar" data-toggle="tooltip">Agregar&nbsp;<i class="mdi mdi-plus-circle-outline mdi-18px"></i></button>
                        </div>
                    </div>
                </div>
                <div class="pb-2 pt-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-centered text-center mb-0" id="table-products">
                            <thead>
                                <tr>
                                    <th  class="header-pay" colspan="4">Detalle venta   <i class="mdi  mdi-cart-plus" ></i></th>
                                </tr>
                                <tr>
                                    <th>Productos/Servicios</th>
                                    <th>Precio unitario</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr-package"></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="font-weight-bold text-right" colspan="3">Total:<input type="hidden" name="importe_total" id="importe_total" value="0"></td>
                                    <td class="font-weight-bold td_importe_total">$<strong id="text_importe_total">0</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    <button type="submit" id="btn_create_sell" class="btn btn-success btn-w-all">Generar Venta <i  class="mdi mdi-content-save-all mdi-18px"></i></button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
@endsection
@push('style')    
<style>
.header-pay{
    background:#c9ddeb73;
    -webkit-text-stroke:thin;
    border-radius:8px;
}
</style>
@endpush
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/sell.js') !!}
    <script>
        // Mostrar card km cuando hay productos en el carrito
        function checkKmCard() {
            var rows = $('#table-products tbody tr').not('#tr-package').length;
            var pkgRow = $('#tr-package td').length;
            if (rows > 0 || pkgRow > 0) {
                $('#card-km-aceite').show();
            } else {
                $('#card-km-aceite').hide();
            }
        }
        $(document).on('click', '#btn-add-products', function() {
            setTimeout(checkKmCard, 300);
        });
        $(document).on('click', '.btn-remove-product', function() {
            setTimeout(checkKmCard, 300);
        });
        // Auto-calcular próximo km (km actual + 5000)
        $('#input_km_actual').on('input', function() {
            var kmActual = parseInt($(this).val());
            if (!isNaN(kmActual) && $('#input_km_proximo').val() === '') {
                $('#input_km_proximo').val(kmActual + 5000);
            }
        });
    </script>
@endpush