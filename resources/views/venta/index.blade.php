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
                        <span class="mdi mdi-car-wash mdi-36px"></span>
                    </a>
                </div>
                <div class="pr-3 pt-3" title="Registrar venta de la tienda" data-toggle="tooltip">
                    <a href="{{route('venta.create-market')}}" class="text-body">
                        <span class="mdi mdi-shopping mdi-36px"></span>
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
                        <th>Tipo vehiculo</th>
                        <th>Atendido por</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)

                    <tr>
                        <td>{{$venta->fecha}}</td>
                        <td>{{$venta->nombre_cliente}}</td>
                        <td>
                            @if($venta->placa)
                                <div class="bg-warning py-2 px-1 text-center border border-dark rounded">
                                    {{$venta->placa}}
                                </div>
                            @else
                                Sin registro
                            @endif
                        </td>
                        <td>
                            @if($venta->numero_telefono)
                                {{$venta->numero_telefono}}
                            @else
                                Sin registro
                            @endif
                        </td>
                        <td>
                            @if($venta->detalle_paquete != null)
                                {{$venta->detalle_paquete->tipo_vehiculo->descripcion}}
                            @else
                                No aplica
                            @endif
                        </td>
                        <td>{{$venta->user->name}}</td>
                        <td>
                            @php $color="danger"; 
                            if($venta->estado_venta->id==1) 
                                $color="primary"; 
                             else $color="success";
                            @endphp
                        <label class="badge  badge-lg text-white badge-{{$color}}">{{$venta->estado_venta->nombre  }}</label>
                        </td>
                        <td>
                            <!--<a href="{{route('venta.edit',[$venta->id])}}" title="Editar venta" data-toggle="tooltip">
                                <i class="mdi mdi-pencil-box-outline text-primary mdi-24px"></i>
                            </a>-->
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