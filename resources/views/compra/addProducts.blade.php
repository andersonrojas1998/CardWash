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
                                        <div class="input-group-append" title="Agregar unidad de medida" data-toggle="tooltip">
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modal_create_unit_measurement">
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
                            <td>
                                <button type="button" class="btn btn-icons btn-rounded btn-success btn_add_product" title="Agregar" data-toggle="tooltip"><i class="mdi mdi-plus-circle-outline mdi-18px"></i></button>
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
                        @if(isset($compra))
                            @foreach($compra->detalle_compra_productos as $detalle_compra_producto)
                                <tr>
                                    <td>
                                        {{$detalle_compra_producto->producto->nombre . ' (' . $detalle_compra_producto->producto->tipo_producto->descripcion . ')'}}
                                        <input type="hidden" class="id_producto_table" name="id_producto[]" value="{{$detalle_compra_producto->producto->id}}">
                                        <input type="hidden" name="id_detalle_compra_producto[]" value="{{$detalle_compra_producto->id}}">
                                    </td>
                                    <td class="td_unit_measurement">
                                        {{$detalle_compra_producto->unidad_de_medida->nombre . ' (' . $detalle_compra_producto->unidad_de_medida->abreviatura . ')'}}
                                        <input type="hidden" name="id_unidad_de_medida[]" value="{{$detalle_compra_producto->unidad_de_medida->id}}">
                                    </td>
                                    <td class="td_quantity">
                                        {{$detalle_compra_producto->cantidad}}
                                        <input type="hidden" name="cantidad[]" value="{{$detalle_compra_producto->cantidad}}">
                                    </td>
                                    <td class="td_sell_price">
                                        {{$detalle_compra_producto->precio_venta}}
                                        <input type="hidden" name="precio_venta[]" value="{{$detalle_compra_producto->precio_venta}}">
                                    </td>
                                    <td class="td_buy_price">
                                        {{$detalle_compra_producto->precio_compra}}
                                        <input type="hidden" class="precio_compra_producto_table" name="precio_compra[]" value="{{$detalle_compra_producto->precio_compra}}">
                                    </td>
                                    <td>
                                        <a class="btn_edit_product_values" data-toggle="tooltip" title="Editar">
                                            <i class="mdi mdi-pencil-box-outline text-primary mdi-18px"></i>
                                        </a>
                                        <a class="btn_save_edit_product_values" data-toggle="tooltip" title="Guardar">
                                            <i class="mdi mdi-checkbox-marked-outline text-success mdi-18px"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="font-weight-bold text-right" colspan="4">Total:<input type="hidden" name="importe_total" class="importe_total form_add_products" value="@if(isset($compra)) {{$compra->importe_total}} @else 0 @endif"></td>
                            <td class="font-weight-bold td_importe_total">$<strong class="text_importe_total">@if(isset($compra)) {{$compra->importe_total}} @else 0 @endif</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="select-unit-measurement-data-url" value="{{ route('unidad-de-medida.index') }}">
<input type="hidden" id="select-product-data-url" value="{{ route('producto.data') }}">