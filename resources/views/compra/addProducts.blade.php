<div class="modal fade" id="modal_add_products" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center" >Agregar productos&nbsp;<span class="mdi mdi-package-variant-closed-plus"></span></h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-add-products">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Unidad de medida</th>
                                    <th>Cantidad</th>
                                    <th>Precio de compra</th>
                                    <th>Precio de venta</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="custom-select" id="select-product-compra" data-url-quantity="{{route('producto.index')}}">
                                                    <option value="">Seleccione producto</option>
                                                </select>
                                            </div>
                                            <input type="hidden" id="hidden-quantity-product">
                                            <input type="hidden" id="select-product-data-url" value="{{ route('producto.data') }}">
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="custom-select select-unidad-de-medida" name="id_unidad_de_medida" id="select-unidad-de-medida">
                                                    <option>Seleccione unidad de medida</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" title="Agregar unidad de medida" data-toggle="modal" data-target="#modal_create_unit_measurement">
                                                        <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="select-unit-measurement-data-url" value="{{ route('unidad-de-medida.index') }}">
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number" id="input-quantity" class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number" id="input-buy-price" class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number" id="input-sell-price" class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <button class="btn btn-success" id="btn_add_product" >Agregar</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="form_to_add_products" value="#products-create">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btn_add_products" class="btn btn-success" disabled>Guardar</button>
            </div>
        </div>
    </div>
</div>