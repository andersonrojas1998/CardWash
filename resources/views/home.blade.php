@extends('layout.master')
@push('plugin-styles')
<style>  
  .carousel-inner img {
    width: 100%;
    height: 385px;
  }
  </style>
@endpush
@section('content')
<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Bienvenido  {{ auth()->user()->name }}</h3>
         <p class="text-muted">Indicadores Informativos </p>
      </div>
      </div>
      </div>    
      </div>


<div class="row">

<div class="col-lg-5 grid-margin stretch-card">
    <div class="card">
      <div class="p-4 border-bottom bg-light">
        <h4 class="card-title mb-0">Cantidad de ventas x Mes</h4>
      </div>
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-center pb-4">
          <h4 class="card-title mb-0">ventas para el año {{ date('Y') }}</h4>
          <div id="bar-traffic-legend"></div>
        </div>        
        <canvas id="chart_salesxmonth" style="height:250px"></canvas>
      </div>
    </div>
  </div>


  <div class="col-lg-7 grid-margin stretch-card">
    <div class="card">
      <div class="p-4 border-bottom bg-light">
        <h4 class="card-title mb-0">Cantidad de ventas x dias</h4>
      </div>
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-center pb-4">
          <h4 class="card-title mb-0">Registros</h4>
          <div id="line-traffic-legend"></div>
        </div>        
        <canvas id="chart_salesxday" style="height:250px"></canvas>
      </div>
    </div>
  </div>



</div>
@endsection

@push('plugin-scripts')
  {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
@endpush
@push('custom-scripts') 
  <script src="{{ asset('/lib/report.js') }}"></script>    
@endpush