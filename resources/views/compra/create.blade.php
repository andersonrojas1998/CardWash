@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-header"><h1>Crear Compra</h1></div>
    <form id="form_create_buy" action="{{route('compra.store')}}" enctype="multipart/form-data" method="POST">
        {{ csrf_field() }}
        <fieldset>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="reg_op_compra">Reg op&nbsp;:</label>
                        <input type="text" id="reg_op_compra" name="reg_op" class="form-control text-uppercase" placeholder="Ingrese el Reg op de la compra" value="{{old('reg_op')}}" required>
                        @if ($errors->any() && $errors->first('reg_op'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('reg_op')}}</span>
                        @endif
                    </div>

                    <div class="col-lg-2">
                        <label for="fecha_emision_compra">Fecha&nbsp;de&nbsp;emisi&oacute;n&nbsp;:</label>
                        <input type="date" id="fecha_emision_compra" name="fecha_emision" class="form-control text-uppercase" placeholder="Ingrese la fecha de emision de la compra" value="{{old('fecha_emision')}}" required>
                        @if ($errors->any() && $errors->first('fecha_emision'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('fecha_emision')}}</span>
                        @endif
                    </div>

                    <div class="col-lg-4">
                        <label for="compracol" class="control-label">Compracol&nbsp;:</label>
                        <input type="text" id="compracol" name="compracol" class="form-control text-uppercase" placeholder="Ingrese la compracol de la compra" value="{{old('compracol')}}" required>
                        @if ($errors->any() && $errors->first('compracol'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('compracol')}}</span>
                        @endif
                    </div>
                    <div class="col-lg-2">
                        <label for="fecha_vencimiento_compra">Fecha&nbsp;de&nbsp;vencimiento:</label>
                        <input type="date" id="fecha_vencimiento_compra" name="fecha_vencimiento" class="form-control text-uppercase" placeholder="Ingrese la fecha de vencimiento de la compra" value="{{old('fecha_vencimiento')}}" required>
                        @if ($errors->any() && $errors->first('fecha_vencimiento'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('fecha_vencimiento')}}</span>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="no_comprobante_compra">No. Comprobante:</label>
                        <input type="text" id="no_comprobante_compra" name="no_comprobante" class="form-control text-uppercase" placeholder="Ingrese el No. comprobante de la compra" value="{{old('no_comprobante')}}" required>
                        @if ($errors->any() && $errors->first('no_comprobante'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('no_comprobante')}}</span>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <label for="descuentos_iva_compra">Descuento IVA:</label>
                        <input type="number" id="descuentos_iva_compra" name="descuentos_iva" class="form-control text-uppercase" placeholder="Ingrese el descuento IVA de la compra" value="{{old('descuentos_iva')}}" required>
                        @if ($errors->any() && $errors->first('descuentos_iva'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('descuentos_iva')}}</span>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <label for="condiciones_id_compra" class="control-label">Condiciones&nbsp;:</label>
                        <select class="custom-select select-condiciones" name="condiciones_id" id="condiciones_id_compra" required>
                            <option value="">Seleccione condicion</option>
                        </select>
                        @if ($errors->any() && $errors->first('condiciones_id'))
                            <span class="badge badge-pill badge-danger">{{$errors->first('condiciones_id')}}</span>
                        @endif
                        <input type="hidden" id="select-condicion-data-url" value="{{ route('condiciones.index') }}">
                        @if (old('condiciones_id'))
                            <input type="hidden" id="old-select-condicion" value="{{ old('condiciones_id') }}">
                        @endif
                    </div>
                </div>
                <div class="container p-1">
                    <div class="card">
                        <div class="card-header">Proveedor</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="id_proveedor_compra">ID:</label>
                                    <input type="text" id="id_proveedor_compra" name="id_proveedor" class="form-control text-uppercase" placeholder="Ingrese el ID del proveedor de la compra" value="{{old('id_proveedor')}}" required>
                                    @if ($errors->any() && $errors->first('fecha_emision'))
                                        <span class="badge badge-pill badge-danger">{{$errors->first('fecha_emision')}}</span>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label>Raz&oacute;n social:</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <input type="radio" name="razon_social_proveedor" id="razon_social_proveedor1" value="Natural" class="form-check-input" required @if (old('razon_social_proveedor') && old('razon_social_proveedor') == 'Natural') checked="true" @endif>
                                                <label for="razon_social_proveedor1">Natural</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <input type="radio" name="razon_social_proveedor" id="razon_social_proveedor0" value="Jur&iacute;dica" class="form-check-input" required @if (old('razon_social_proveedor') && old('razon_social_proveedor') != 'Natural') checked="true" @endif>
                                                <label for="razon_social_proveedor0">Jur&iacute;dica</label>
                                            </div>
                                        </div>
                                        @if ($errors->any() && $errors->first('fecha_emision'))
                                            <span class="badge badge-pill badge-danger">{{$errors->first('fecha_emision')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('compra.addProducts')
                @if ($errors->any() && $errors->first('id_producto'))
                    <div class="d-flex justify-content-center" id="products-validation-message">
                        <span class="badge badge-pill badge-danger">{{$errors->first('id_producto')}}</span>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <button type="submit" id="btn_create_buy" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
@include('unidad_de_medida.create')
@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/buy.js') !!}
@endpush