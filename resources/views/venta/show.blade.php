@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-header"><h1>Factura No. {{$venta->id}}</h1></div>
    
            <div class="card-body">
                <div class="d-flex justify-content-around mb-3">
                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Cliente&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            <label>{{$venta->nombre_cliente}}</label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Fecha&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            <label>{{$venta->fecha}}</label>
                        </div>
                    </div>
                    
                </div>

                <div class="d-flex justify-content-around mb-3 pb-3">
                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Placa&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            @if($venta->placa)
                                <label>{{$venta->placa}}</label>
                            @else
                                <label>Sin registro</label>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Telefono&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            @if($venta->numero_telefono)
                                <label>{{$venta->numero_telefono}}</label>
                            @else
                                <label>Sin registro</label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-around mb-3 pb-3">
                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Estado&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            <label>{{$venta->estado_venta->nombre}}</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row pl-2">
                            <label>Tipo vehiculo&nbsp;:</label>
                        </div>
                        <div class="row pl-2">
                            @if($venta->detalle_paquete != null)
                                <label>{{$venta->detalle_paquete->tipo_vehiculo->descripcion}}</label>
                            @else
                                <label>No aplica</label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pb-2 pt-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-centered text-center mb-0" id="table-products">
                            <thead>
                                <tr>
                                    <th colspan="4">Detalle venta</th>
                                </tr>
                                <tr>
                                    <th>Productos/Servicios</th>
                                    <th>Precio unitario</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @if($venta->detalle_paquete)
                                    @php
                                    $total += $venta->detalle_paquete->precio_venta;
                                    @endphp
                                    <tr>
                                        <td>{{$venta->detalle_paquete->paquete->nombre.' - '.$venta->detalle_paquete->tipo_vehiculo->descripcion}}</td>
                                        <td>{{$venta->detalle_paquete->precio_venta}}</td>
                                        <td>1</td>
                                        <td>{{$venta->detalle_paquete->precio_venta}}</td>
                                    </tr>
                                @endif
                                @foreach($venta->detalle_venta_productos as $detalle_venta_producto)
                                    @php
                                    $total += $detalle_venta_producto->precio_venta;
                                    @endphp
                                    <tr>
                                        <td>{{$detalle_venta_producto->producto->nombre.' - '.$detalle_venta_producto->producto->presentacion->nombre}}</td>
                                        <td>{{$detalle_venta_producto->precio_venta / $detalle_venta_producto->cantidad}}</td>
                                        <td>{{$detalle_venta_producto->cantidad}}</td>
                                        <td>{{$detalle_venta_producto->precio_venta}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="font-weight-bold text-right" colspan="3">Total:</td>
                                    <td class="font-weight-bold td_importe_total">$<strong id="text_importe_total">{{$total}}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
            </div>
</div>

@if(session('success'))
<input type="hidden" id="succes_message" value="{{session('success')}}">
@endif
@endsection
@push('custom-scripts')
    {!! Html::script('js/validate.min.js') !!}
    {!! Html::script('js/validator.messages.js') !!}
    {!! Html::script('lib/sell.js') !!}
@endpush