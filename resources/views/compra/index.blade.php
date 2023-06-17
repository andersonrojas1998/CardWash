@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div class="d-flex justify-content-start bd-highlight">
                <div class="p-4 bg-light">
                    <h4>COMPRAS Y PAGOS    <i class="mdi mdi-cash"></i></h4>
                </div>
            </div>
            <div class="d-flex justify-content-end bd-highlight">
                <div class="pr-3 pt-3">
                    <a href="{{route('compra.create')}}" class="text-body" title="Crear compra">
                        <span class="mdi mdi-plus-circle-outline mdi-36px"></span>
                    </a>
                </div>
                <div class="pr-3 pt-3">
                    <a href="{{route('compra.create')}}" class="text-body" title="Crear Pago">
                        <span class="mdi mdi-alert-octagram mdi-36px"></span>
                    </a>
                </div>
            </div>
        </div>
        <table cellspacing="5" cellpadding="5">
            <tbody><tr>
                <td>Fecha inicial:</td>
                <td><input type="text" id="min" name="min" value="06-01-2023" placeholder="MM-DD-AAAA"></td>
            </tr>
            <tr>
                <td>Fecha fin:</td>
                <td><input type="text" id="max" name="max" value="{{date('m-d-Y')}}" placeholder="MM-DD-AAAA"></td>
            </tr>
        </tbody></table>
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="table-compra" data-url="{{route('compra.data')}}" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2"># de Factura</th>
                        <th rowspan="2">F. Emision</th>
                        <th rowspan="2">Descripci&oacute;n</th>
                        <th rowspan="2">No. Comprobante</th>
                        <th colspan="2" class="text-center">Proveedor</th>
                        <th rowspan="2">Descuento IVA</th>
                        <th rowspan="2">Importe total</th>
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
</div>
@if(session('success'))
    <input type="hidden" id="success_message" value="{{session('success')}}">
@endif

@if(session('fail'))
    <input type="hidden" id="fail_message" value="{{session('fail')}}">
@endif
@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/buy.js') !!}
@endpush