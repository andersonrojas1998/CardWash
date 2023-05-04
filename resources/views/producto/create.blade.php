<div class="modal fade" id="modal_create_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center" >Crear producto&nbsp;<span class="mdi mdi-package"></span></h5>
            </div>
            <form id="form_create_product" action="{{route('producto.store')}}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <fieldset>
                    <div class="modal-body" style="background:white;">
    
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Nombre :</label>
                                <input type="text" id="nombre_producto" name="nombre" class="form-control text-uppercase" placeholder="Ingrese el nombre del producto" required>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tipo-producto" class="control-label">Tipo de producto&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-tipo-producto" name="id_tipo_producto" id="tipo-producto">
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
                                    <label for="marca" class="control-label">Marca&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-marca" name="id_marca">
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
                                    <label for="unidad-medida" class="control-label">Unidad de medida&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-unidad-de-medida" name="id_unidad_de_medida">
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
                        <button type="submit" id="btn_create_product" class="btn btn-success">Guardar</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>