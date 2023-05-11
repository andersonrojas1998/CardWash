<div class="modal fade" id="modal_create_buy" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center" >Crear compra&nbsp;<span class="mdi mdi-package"></span></h5>
            </div>
            <form id="form_create_buy" action="{{route('compra.store')}}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <fieldset>
                    <div class="modal-body" style="background:white;">
    
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="reg_op_compra">Reg op&nbsp;:</label>
                                <input type="text" id="reg_op_compra" name="reg_op" class="form-control text-uppercase" placeholder="Ingrese el Reg op de la compra" required>
                            </div>

                            <div class="col-lg-2">
                                <label for="fecha_emision_compra">Fecha&nbsp;de&nbsp;emisi&oacute;n&nbsp;:</label>
                                <input type="date" id="fecha_emision_compra" name="fecha_emision" class="form-control text-uppercase" placeholder="Ingrese la fecha de emision de la compra" required>
                            </div>

                            <div class="col-lg-4">
                                <label for="compracol" class="control-label">Compracol&nbsp;:</label>
                                <input type="text" id="compracol" name="compracol" class="form-control text-uppercase" placeholder="Ingrese la compracol de la compra" required>
                            </div>
                            <div class="col-lg-2">
                                <label for="fecha_vencimiento_compra">Fecha&nbsp;de&nbsp;vencimiento:</label>
                                <input type="date" id="fecha_vencimiento_compra" name="fecha_vencimiento" class="form-control text-uppercase" placeholder="Ingrese la fecha de vencimiento de la compra" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="no_comprobante_compra">No. Comprobante:</label>
                                <input type="text" id="no_comprobante_compra" name="no_comprobante" class="form-control text-uppercase" placeholder="Ingrese el No. comprobante de la compra" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="descuentos_iva_compra">Descuento IVA:</label>
                                <input type="number" id="descuentos_iva_compra" name="descuentos_iva" class="form-control text-uppercase" placeholder="Ingrese el descuento IVA de la compra" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="condiciones_id_compra" class="control-label">Condiciones&nbsp;:</label>
                                <select class="custom-select select-condiciones" name="condiciones_id" id="condiciones_id_compra" required>
                                    <option value="">Seleccione condicion</option>
                                </select>
                                <input type="hidden" id="select-condicion-data-url" value="{{ route('condiciones.index') }}">
                            </div>
                        </div>
                        <div class="container p-1">
                            <div class="card">
                                <div class="card-header">Proveedor</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="id_proveedor_compra">ID:</label>
                                            <input type="text" id="id_proveedor_compra" name="id_proveedor" class="form-control text-uppercase" placeholder="Ingrese el ID del proveedor de la compra" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Raz&oacute;n social:</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-check">
                                                        <input type="radio" name="razon_social_proveedor" id="razon_social_proveedor1" value="Natural" class="form-check-input" required @if (old('razon_social_proveedor') && old('razon_social_proveedor') == 'Natural') checked="true" @endif>
                                                        <label for="razon_social_proveedor1">Natural</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-check">
                                                        <input type="radio" name="razon_social_proveedor" id="razon_social_proveedor0" value="Jur&iacute;dica" class="form-check-input" required @if (old('razon_social_proveedor') && old('razon_social_proveedor') == 'Juridica') checked="true" @endif>
                                                        <label for="razon_social_proveedor0">Jur&iacute;dica</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="select-unit-measurement-data-url" value="{{ route('unidad-de-medida.index') }}">
                        <input type="hidden" id="select-product-data-url" value="{{ route('producto.data') }}">
                        @include('compra.addProducts')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_create_buy" class="btn btn-success">Guardar</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>