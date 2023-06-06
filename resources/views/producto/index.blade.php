@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div class="d-flex justify-content-start bd-highlight">
                <div class="p-4 bg-light">
                    <h4>PRODUCTOS</h4>
                </div>
            </div>
            <div class="d-flex justify-content-end bd-highlight">
                <div class="px-3 py-1" title="Crear producto" data-toggle="tooltip">
                    <a class="d-block text-body-emphasis text-decoration-none" data-toggle="modal" data-target="#modal_create_product">
                        <span class="mdi mdi-plus-circle-outline mdi-36px"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="table-product" data-url="{{route('producto.data')}}">
                <thead>
                    <tr>
                        <th>Nombre (Referencia)</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Unidad de medida</th>
                        <th>Presentacion</th>
                        <th>Cantidad</th>
                        <th>Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('producto.create')
    @include('producto.edit')
    @include('marca.create')
    @include('tipo_producto.create')
    @include('unidad_de_medida.create')
    @if(session('success'))
        <input type="hidden" id="succes_message" value="{{session('success')}}">
    @endif

    @if(session('fail'))
        <input type="hidden" id="fail_message" value="{{session('fail')}}">
    @endif
</div>
@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/product.js') !!}
@endpush