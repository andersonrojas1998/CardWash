@extends('layout.master')
@section('content')
<div class="card">
<div class="p-4  bg-light text-center">        
        <h4 class="mb-0">Administraci&oacute;n de Egresos y Ingresos Mensuales</h4>        
</div>
    <div class="card-body">  
        
    <div class="row">

    <div class="col-lg-6 grid-margin stretch-card">            
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Egresos Registrados</h4>
             <div class="table-responsive">
                <table id="dt_expenses_month" class="table table-striped">
                <thead>
                    <tr>
                        <th> # </th> 
                        <th>Concepto</th>                         
                        <th>Valor</th></tr>                
                </thead>                 
            </table>
            </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
        <h4 class="card-title mb-0">Ingresos por Ventas (Servicios/Productos)</h4>
        <div id="pie-chart-legend" class="mr-4"></div>
      </div>
      <div class="card-body d-flex">
        <canvas class="my-auto" id="chart_income_service" height="130"></canvas>
      </div>
      <div class="card-footer">
        <label>Total Ingresos : <h3 id="total_income"></h3></label>
      
      </div>
    </div>
  </div>


    </div>
    
    </div>  
    </div>

  
@endsection
@push('style') 
@endpush
@push('custom-scripts')     
<script src="{{ asset('/lib/report.js') }}"></script>    
@endpush

@push('plugin-scripts')
  {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
@endpush

