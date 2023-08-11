<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Ticket-{{ $venta->id}}  </title>
        <meta name="description" content="CardWash">
        <meta name="viewport" content="width=device-width, initial-scale=1">                        
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <style type="text/css">
        .padding-1{
            padding:0  !important;            
            margin:0 !important;  
        }
        .padding-2{
            padding:2px 2px  !important;            
        }
        .size-w3{
            font-size: 26px !important;
            margin:0;
        }
        @page {
                margin-top: 20px;                
                padding:0;                
            }         
        </style> 
</head>
<header>

<div class="w3-row" >

<div class="w3-col  w3-center">
        <img class="rounded"  src="{{ asset('/icon.jpg') }}" height="205" width="198">
    </div>
<div class="w3-row"  >        
    <div class="w3-col w3-center size-w3 " >
           <p><b class="size-w3">JUANCHO'S </b> <br>
           Lavado y Mantenimiento de Vehiculos Automotores <br>
            JORGE ANDRES DIAZ CRUZ <br>          
            Nit: 1.144.189.073-3 <br>            
           <b class="size-w3"> No Responsable del IVA</b>
           </p>            
           <p class="size-w3">FACTURA DE VENTA  <b> No.  {{ $venta->id}}</b> </p>
    </div>
    

    <p class="size-w3 padding-1" style="margin:0;"><b>Fecha :</b>  {{ date('Y-m-d h:i',strtotime($venta->fecha)) }}</p>
    <p class="size-w3 padding-1" style="margin:0;text-transform: uppercase;"><b>Tipo Vehiculo :</b>   {{  isset($venta->detalle_paquete->tipo_vehiculo->descripcion)? $venta->detalle_paquete->tipo_vehiculo->descripcion:'' }}</p>
    <p class="size-w3 padding-1" style="margin:0;text-transform: uppercase;"><b>Placa : </b> {{ isset($venta->placa)? $venta->placa:''  }}  </p>    
    <p class="size-w3 padding-1" style="margin:0;text-transform: uppercase;"><b>Cliente : </b> {{ isset($venta->nombre_cliente)? $venta->nombre_cliente:$venta->nombre_cliente }}  </p>    
    <p class="size-w3 padding-1" style="margin:0;text-transform: uppercase;"><b>Numero : </b> {{ $venta->numero_telefono }}  </p>    
</div>
<hr>    
</div>
</header>
<body> 
<div class="w3-row">
<table class="w3-table w3-bordered"  >
<thead>
<tr class="w3-xlarge w3-center" >
            <th class="w3-light-grey padding-1 w3-center" >CONCEPTO</th>
            <th class="w3-light-grey padding-1 w3-center" >CANT.</th>
            <th class="w3-light-grey  padding-1 w3-center" >PRECIO</th>            
            <th class=" w3-light-grey  padding-1 w3-center">IMPORTE</th>            
        </tr>
</thead>
<tbody>

@php $total = 0; @endphp
                                @if($venta->detalle_paquete)
                                    @php
                                    $total += $venta->detalle_paquete->precio_venta;
                                    @endphp
                                    <tr  class="w3-xlarge  padding-1 w3-center">
                                        <td class="w3-xlarge padding-1 w3-center" style="text-transform: uppercase;" >{{$venta->detalle_paquete->paquete->nombre}}</td>
                                        <td  class="w3-xlarge padding-1 w3-center">1</td>
                                        <td class="w3-xlarge padding-1 w3-center">{{number_format($venta->detalle_paquete->precio_venta,0,',','.')}}</td>                                        
                                        <td class="w3-xlarge padding-1 w3-center">{{number_format($venta->detalle_paquete->precio_venta,0,',','.')}}</td>
                                    </tr>
                                @endif
                                @foreach($productos as $detalle_venta_producto)
                                    @php
                                    $total += $detalle_venta_producto->total_venta;
                                    @endphp
                                    <tr  class="w3-xlarge  padding-1 w3-center">
                                        <td class="w3-xlarge padding-1 w3-center">{{$detalle_venta_producto->producto}}</td>
                                        <td class="w3-xlarge padding-1 w3-center">{{$detalle_venta_producto->cantidad_vendida}}</td>
                                        <td class="w3-xlarge padding-1 w3-center">{{ number_format($detalle_venta_producto->precio_venta,0,',','.')}}</td>                                        
                                        <td class="w3-xlarge padding-1 w3-center">{{ number_format($detalle_venta_producto->total_venta,0,',','.')}}</td>
                                    </tr>
                                @endforeach      
</tbody>
<tfoot>
    <tr class="w3-xlarge padding-1">
        <th class=" w3-xxlarge w3-right-align  padding-1" colspan="3">TOTAL &nbsp;</th>
        <th class="w3-xxlarge padding-1">$ {{ number_format($total,0,',','.')}}</th>
    </tr>
</tfoot>
</table>                        
<hr>
    <p class="w3-xxlarge w3-serif  w3-center" style="margin:15px;">¡ GRACIAS POR SU COMPRA ! </p>
    </div> 
    </body>
</html>