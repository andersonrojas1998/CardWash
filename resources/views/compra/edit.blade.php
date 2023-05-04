<div class="modal fade" id="modal_edit_buy" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center">Editar Compra&nbsp;<span class="mdi mdi-package"></span></h5>
            </div>
            <form id="edit-product-form" action="{{ route('compra.update') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <fieldset>
                    <div class="modal-body" style="background:white;">
    
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="reg_op_compra_edit">Reg op&nbsp;:</label>
                                <input type="text" id="reg_op_compra_edit" name="reg_op" class="form-control text-uppercase" placeholder="Ingrese el Reg op de la compra" required>
                                <input type="hidden" id="id_compra" name="id">
                            </div>

                            <div class="col-lg-4">
                                <label for="fecha_emision_compra_edit">Fecha&nbsp;de&nbsp;emisi&oacute;n&nbsp;:</label>
                                <input type="date" id="fecha_emision_compra_edit" name="fecha_emision" class="form-control text-uppercase" placeholder="Ingrese la fecha de emision de la compra" required>
                            </div>

                            <div class="col-lg-4">
                                <label for="compracol_edit" class="control-label">Compracol&nbsp;:</label>
                                <input type="text" id="compracol_edit" name="compracol" class="form-control text-uppercase" placeholder="Ingrese la compracol de la compra" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="fecha_vencimiento_compra_edit">Fecha&nbsp;de&nbsp;vencimiento:</label>
                                <input type="date" id="fecha_vencimiento_compra_edit" name="fecha_vencimiento" class="form-control text-uppercase" placeholder="Ingrese la fecha de vencimiento de la compra" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="no_comprobante_compra_edit">No. Comprobante:</label>
                                <input type="text" id="no_comprobante_compra_edit" name="no_comprobante" class="form-control text-uppercase" placeholder="Ingrese el No. comprobante de la compra" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="id_proveedor_compra_edit">ID Proveedor:</label>
                                <input type="text" id="id_proveedor_compra_edit" name="id_proveedor" class="form-control text-uppercase" placeholder="Ingrese el ID del proveedor de la compra" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="razon_social_proveedor_compra_edit">Razon social proveedor:</label>
                                <select class="custom-select" id="razon_social_proveedor_compra_edit" name="razon_social_proveedor">
                                    <option value="Natural">Natural</option>
                                    <option value="Juridica">Juridica</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="compra_o_gasto_compra_edit">Compra&nbsp;o&nbsp;gasto:</label>
                                <select class="custom-select" id="compra_o_gasto_compra_edit" name="compra_o_gasto">
                                    <option value="Compra">Compra</option>
                                    <option value="Gasto">Gasto</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="descuentos_iva_compra_edit">Descuento IVA:</label>
                                <input type="text" id="descuentos_iva_compra_edit" name="descuentos_iva" class="form-control text-uppercase" placeholder="Ingrese el descuento IVA de la compra" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="importe_total_compra_edit">Importe total:</label>
                                <input type="text" id="importe_total_compra_edit" name="importe_total" class="form-control text-uppercase" placeholder="Ingrese el importe total de la compra" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="condiciones_id_compra_edit" class="control-label">Condiciones&nbsp;:</label>
                                <select class="custom-select select-condiciones" name="condiciones_id" id="condiciones_id_compra_edit">
                                    <option>Seleccione condicion</option>
                                </select>
                                <input type="hidden" id="select-condicion-data-url" value="{{ route('condiciones.index') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_edit_buy" class="btn btn-success">Guardar</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>