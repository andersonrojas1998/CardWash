@extends('layout.master')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div class="d-flex justify-content-start bd-highlight">
                <div class="p-4 bg-light">
                    <h4>COMBOS / SERVICIOS</h4>
                </div>
            </div>
            <div class="d-flex justify-content-end bd-highlight">
                <div class="pr-3 pt-3" title="Crear combo o servicio" data-toggle="tooltip">
                    <a href="{{route('detalle-paquete.create')}}" class="text-body">
                        <span class="mdi mdi-plus-circle-outline mdi-36px"></span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Listar paquetes para carro -->
        @if(count($paquetes_carro) != 0)
            <div class="card-deck m-3">
        @endif
        @foreach($paquetes_carro as $key => $paquete)
            <div class="card border border-dark text-center text-light text-uppercase" style="border-radius: 1em; overflow:hidden; max-width:205.938px;">
                <div class="card-header px-2 py-0" style="background-color: black;">
                    <h1 class="m-0"><strong>{{$paquete->nombre}}</strong></h1>
                </div>
                <div class="card-body px-2 py-3" style="background: linear-gradient({{explode(',', $paquete->color)[0]}}, #a8a4a4); color: {{explode(',', $paquete->color)[1]}};">
                    @foreach($paquete->detalle_paquete->where('tipo_vehiculo.nomenclatura', 'C') as $detalle_paquete)
                        <h2 class="m-0"><strong>{{$detalle_paquete->tipo_vehiculo->descripcion}}</strong></h2>
                        <h2 class="m-0"><strong>$ {{$detalle_paquete->precio_venta}}</strong></h2>
                        <hr class="my-1">
                    @endforeach
                    <strong class="card-title" style="color: #fff;  text-shadow: 2px 0 #000, -2px 0 #000, 0 2px #000, 0 -2px #000, 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;">
                        @php $servicios_paquete = $paquete->detalle_paquete->where('tipo_vehiculo.nomenclatura', 'C')->first()->servicio_paquete; @endphp
                        @foreach($servicios_paquete as $key => $servicio_paquete)
                            {{$servicio_paquete->servicio->nombre}}
                            @if(isset($servicios_paquete[$key+1]))
                                &nbsp;-&nbsp;
                            @endif
                        @endforeach
                    </strong>
                </div>
                <div class="card-footer" style="background: linear-gradient(#a8a4a4, {{explode(',', $paquete->color)[0]}});">
                    <button type="button" class="btn btn-primary btn-block"><strong>Editar <i class="mdi mdi-pencil-box-outline"></i></strong></button>
                </div>
            </div>
        @endforeach

        @if(count($paquetes_carro) != 0)
            </div>
        @endif
        <!-- Fin listar paquetes para carro -->

        <!-- Listar paquetes para moto -->
        @if(count($paquetes_moto) != 0)
        <div class="card-deck m-3">
        @endif
        @foreach($paquetes_moto as $paquete)
            <div class="card border border-dark text-center text-light text-uppercase" style="border-radius: 1em; overflow:hidden; max-width:205.938px;">
                <div class="card-header px-2" style="background-color: black;">
                    <h1 class="m-0"><strong>{{$paquete->nombre}}</strong></h1>
                </div>
                <div class="card-body px-2 py-3" style="background: linear-gradient({{explode(',', $paquete->color)[0]}}, #a8a4a4); color:{{explode(',', $paquete->color)[1]}}">
                    @foreach($paquete->detalle_paquete->where('tipo_vehiculo.nomenclatura', 'M') as $detalle_paquete)
                        <h2 class="m-0"><strong>{{$detalle_paquete->tipo_vehiculo->descripcion}}</strong></h2>
                        <h2 class="m-0"><strong>$ {{$detalle_paquete->precio_venta}}</strong></h2>
                    @endforeach
                    <hr class="my-1">
                    <strong class="card-title" style="color: #fff; text-shadow: 2px 0 #000, -2px 0 #000, 0 2px #000, 0 -2px #000, 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;">
                        @php $servicios_paquete = $paquete->detalle_paquete->where('tipo_vehiculo.nomenclatura', 'M')->first()->servicio_paquete; @endphp
                        @foreach($servicios_paquete as $key => $servicio_paquete)
                            {{$servicio_paquete->servicio->nombre}}
                            @if(isset($servicios_paquete[$key+1]))
                                &nbsp;-&nbsp;
                            @endif
                        @endforeach
                    </strong>
                </div>
                <div class="card-footer" style="background: linear-gradient(#a8a4a4, {{explode(',', $paquete->color)[0]}});">
                    <button type="button" class="btn btn-primary btn-block"><strong>Editar <i class="mdi mdi-pencil-box-outline"></i></strong></button>
                </div>
            </div>
        @endforeach

        @if(count($paquetes_moto) != 0)
        </div>
        @endif
        
    <!-- Fin listar paquetes para moto -->

    <!-- Botones de paginado-->
    <div class="d-flex justify-content-center mt-5">
        @if($paquetes_carro->count() >= $paquetes_moto->count())
            {!!$paquetes_carro->links()!!}
        @else if($paquetes_moto->count() >= 0)
            {!!$paquetes_moto->links()!!}
        @endif
    </div>
    <!-- Fin botones de paginado-->
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
    {!! Html::script('lib/package.js') !!}
@endpush