@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div class="d-flex justify-content-start bd-highlight">
                <div class="p-4 bg-light">
                    <h4>VENTAS</h4>
                </div>
            </div>
            <div class="d-flex justify-content-end bd-highlight">
                <div class="pr-3 pt-3" title="Registrar venta" data-toggle="tooltip">
                    <a href="{{route('venta.create')}}" class="text-body">
                        <span class="mdi mdi-plus-circle-outline mdi-36px"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="table-sell">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Placa</th>
                        <th># Telefono</th>
                        <th>Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)

                    <tr>
                        <td>{{$venta->fecha}}</td>
                        <td>{{$venta->nombre_cliente}}</td>
                        <td>{{$venta->placa}}</td>
                        <td>{{$venta->numero_telefono}}</td>
                        <td>
                            <a href="{{route('venta.edit',[$venta->id])}}" title="Editar venta" data-toggle="tooltip">
                                <i class="mdi mdi-pencil-box-outline text-primary mdi-24px"></i>
                            </a>
                            <a href="{{route('venta.show',[$venta->id])}}" title="Ver detalle" data-toggle="tooltip">
                                <i class="mdi mdi-point-of-sale text-warning mdi-24px"></i>
                            </a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
    {!! Html::script('lib/sell.js') !!}
@endpush