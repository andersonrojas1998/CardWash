@extends('layout.master')
@section('content')
<div class="card">
<div class="p-4  bg-light text-center">        
        <h4 class="mb-0">Administraci&oacute;n de Servicios Prestados</h4>        
</div>
    <div class="card-body">    
    <div class="col-lg-12 grid-margin stretch-card">            
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Cantidad de servicios prestados por Empleados</h4>
             <div class="table-responsive">
                <table id="dt_sales_user" class="table table-striped">
                <thead>
                    <tr>
                        <th> # </th> 
                        <th> Identificacion</th> 
                        <th> Nombre </th>
                        <th>Cant. Servicios prestados</th>
                        <th>Cant. Pagos</th>
                        <th>Cant. Pend</th>
                        <th>Pend. Pagar </th></tr>                
                </thead>                 
            </table>
            </div>
            </div>
        </div>
    </div>
    </div>  
    </div>


<div class="modal fade" id="mdl_paySales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success d-block">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-uppercase text-center text-white" >Pago por Servicos Realizados   <i class="mdi  mdi-cash-usd text-white"></i></h5>
      </div>      
      <fieldset>
      <div class="modal-body" style="background:white;">        
        <div class="row">
            <input type="hidden" id="id_usuario">
        <div class="table-responsive">
                <table  class="table table-striped dt_pay_pending">
                <thead>
                    <tr>
                        <th> No. venta</th> 
                        <th> Nombre Cliente</th> 
                        <th> Combo</th>
                        <th>Vehiculo</th>
                        <th>Precio Venta</th>
                        <th>Porcentaje %</th>                        
                        <th>Valor </th>
                    </tr>                
                </thead>                 
            </table>
            </div>        
        </div>
    <br>
    <hr>
    <div class="row">
        <div class="card" style="border-bottom:outset;border-radius:15px;">
            <div class="card-body">
              <h4 class="card-title"><p class="text-muted text-success">Se debe realizar el pago del siguiente valor : </p></h4>
              <label>Total Pago :  <h2 class="payPending"> </h2>  </label>
          </div>
        </div>
     </div>  
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" id="btn_pay_sales" class="btn btn-success">Pagar    <i class="mdi  mdi-cash-usd text-white"></i></button>
      </div>
      </fieldset>          
    </div>
  </div>
</div>   


<div class="modal fade" id="mdl_pay_history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info d-block">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title text-uppercase text-center text-white" >Historial de Pagos   <i class="mdi  mdi-cash-usd text-white"></i></h5>
      </div>      
      <fieldset>
      <div class="modal-body" style="background:white;">        
        <div class="row">
            <input type="hidden" id="id_usuario">
        <div class="table-responsive">
                <table id="" class="table table-striped dt_pay_pending">
                <thead>
                    <tr>
                        <th> No. venta</th> 
                        <th> Nombre Cliente</th> 
                        <th> Combo</th>
                        <th>Vehiculo</th>
                        <th>Precio Venta</th>
                        <th>Porcentaje %</th>                        
                        <th>Valor Pagado </th>
                    </tr>                
                </thead>                 
            </table>
            </div>        
        </div>
    <br>
    <hr>
    <div class="row">
        <div class="card" style="border-bottom:outset;border-radius:15px;">
            <div class="card-body">
              <h4 class="card-title"><p class="text-muted text-success">Se realizo  el pago del siguiente valor : </p></h4>
              <label>Total Pago :  <h2 class="payPending"> </h2>  </label>
          </div>
        </div>
     </div>  
  
      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <!-- <button type="submit" id="btn_pay_sales" class="btn btn-success">Pagar    <i class="mdi  mdi-cash-usd text-white"></i></button> -->
      </div>
      </fieldset>          
    </div>
  </div>
</div>  

    
@endsection
@push('style') 
@endpush
@push('custom-scripts')     
    <script src="{{ asset('/lib/teacher.js') }}"></script>
@endpush
