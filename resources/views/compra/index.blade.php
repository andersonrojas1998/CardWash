@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div class="d-flex justify-content-start bd-highlight">
                <div class="p-4 bg-light">
                    <h4>COMPRAS</h4>
                </div>
            </div>
            <div class="d-flex justify-content-end bd-highlight">
                <div class="px-3 py-1">
                    <a class="d-block text-body-emphasis text-decoration-none" id="open-modal-create-compra" title="Crear compra" data-toggle="modal" data-target="#modal_create_buy">
                        <span class="mdi mdi-plus-circle-outline mdi-36px"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="table-compra" data-url="{{route('compra.data')}}" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2">Reg_op</th>
                        <th rowspan="2">F. Emision</th>
                        <th rowspan="2">Compracol</th>
                        <th rowspan="2">F. Vencimiento</th>
                        <th rowspan="2">No. Comprobante</th>
                        <th colspan="2" class="text-center">Proveedor</th>
                        <th rowspan="2">Compra o<br>gasto</th>
                        <th rowspan="2">Descuento IVA</th>
                        <th rowspan="2">Importe total</th>
                        <th rowspan="2">Condicion</th>
                        <th rowspan="2">Acci&oacute;n</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Raz&oacute;n social</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('compra.create')
    @include('compra.edit')
    @include('compra.addProducts')
    @include('unidad_de_medida.create')
</div>
@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/buy.js') !!}
@endpush