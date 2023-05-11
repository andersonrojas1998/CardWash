<div class="container-fluid mx-1">
    <div class="card">
        <div class="card-header">Agregar productos</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-add-products">
                    <thead>
                        <tr>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="custom-select select-product-compra select_add_products" data-url-quantity="{{route('producto.index')}}">
                                            <option value="">Seleccione producto</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="custom-select select-unidad-de-medida select_add_products">
                                            <option>Seleccione unidad de medida</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" title="Agregar unidad de medida" data-toggle="modal" data-target="#modal_create_unit_measurement">
                                                <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-quantity input_add_products">
                                    </div>
                                </div>
                            </td>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-sell-price input_add_products">
                                    </div>
                                </div>
                            </td>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-buy-price input_add_products">
                                    </div>
                                </div>
                            </td>
                            <td class="p-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-success btn_add_product">Agregar</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Producto</th>
                            <th>Unidad de medida</th>
                            <th>Cantidad</th>
                            <th>Precio de venta</th>
                            <th>Precio de compra</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="font-weight-bold text-right" colspan="4">Total:<input type="hidden" name="importe_total" class="importe_total form_add_products" value="0"></td>
                            <td class="font-weight-bold td_importe_total">$<strong class="text_importe_total">0</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>