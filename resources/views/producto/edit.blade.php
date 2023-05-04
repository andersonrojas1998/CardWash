<div class="modal fade" id="modal_edit_product" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center">Editar producto&nbsp;<span class="mdi mdi-package"></span></h5>
            </div>
            <form id="edit-product-form" action="{{ route('producto.update') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <fieldset>
                    <div class="modal-body" style="background:white;">
    
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name_product_edit">Nombre :</label>
                                <input type="text" id="name_product_edit" name="nombre" class="form-control text-uppercase" placeholder="Ingrese el nombre del producto" required>
                                <input type="hidden" id="id_producto" name="id">
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="select_tipo_producto_edit" class="control-label">Tipo de producto&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-tipo-producto" id="select_tipo_producto_edit" name="id_tipo_producto">
                                            <option>Seleccione tipo de producto</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" title="Agregar tipo de producto" data-toggle="modal" data-target="#modal_create_product_type">
                                                <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="select-product-type-data-url" value="{{ route('tipo-producto.index') }}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="select_marca_edit" class="control-label">Marca&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-marca" id="select_marca_edit" name="id_marca">
                                            <option>Seleccione la marca</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" title="Agregar marca" data-toggle="modal" data-target="#modal_create_brand">
                                                <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="select-brand-data-url" value="{{ route('marca.index') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="select_unidad_medida_edit" class="control-label">Unidad de medida&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-unidad-de-medida" id="select_unidad_medida_edit" name="id_unidad_de_medida">
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="update-product">Guardar</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>