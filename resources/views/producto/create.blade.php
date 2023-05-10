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
                                <label>Nombre/Referencia :</label>
                                <input type="text" id="nombre_producto" name="nombre" class="form-control text-uppercase" value="{{old('nombre')}}" placeholder="Ingrese el nombre del producto" required>
                                @if ($errors->any() && $errors->first('nombre'))
                                    <span class="badge badge-pill badge-danger">{{$errors->first('nombre')}}</span>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tipo-producto" class="control-label">Tipo de producto&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-tipo-producto" name="id_tipo_producto" id="tipo-producto" aria-describedby="type_product_create_help" required>
                                            <option value="">Seleccione tipo de producto</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" title="Agregar tipo de producto" data-toggle="modal" data-target="#modal_create_product_type">
                                                <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <small id="type_product_create_help" class="form-text text-muted">Ejemplo: Filtro de aire, Filtro de aceite, etc.</small>
                                    @if ($errors->any() && $errors->first('id_tipo_producto'))
                                        <span class="badge badge-pill badge-danger">{{$errors->first('id_tipo_producto')}}</span>
                                    @endif
                                    <input type="hidden" id="select-product-type-data-url" value="{{ route('tipo-producto.index') }}">
                                    @if (old('id_tipo_producto'))
                                        <input type="hidden" id="old-select-product-type" value="{{ old('id_tipo_producto') }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="marca" class="control-label">Marca&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="custom-select select-marca" name="id_marca" required>
                                            <option value="">Seleccione la marca</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" title="Agregar marca" data-toggle="modal" data-target="#modal_create_brand">
                                                <span class="mdi mdi-plus-circle-outline mdi-24px"></span>
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->any() && $errors->first('id_marca'))
                                        <span class="badge badge-pill badge-danger">{{$errors->first('id_marca')}}</span>
                                    @endif
                                    <input type="hidden" id="select-brand-data-url" value="{{ route('marca.index') }}">
                                    @if (old('id_marca'))
                                        <input type="hidden" id="old-select-brand" value="{{ old('id_marca') }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input type="radio" name="es_de_venta" id="es_de_venta_create1" value="1" class="form-check-input" required @if (old('es_de_venta') && old('es_de_venta') == 1) checked="true" @endif>
                                    <label for="es_de_venta_create1">Producto de venta</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="es_de_venta" id="es_de_venta_create2" value="0" class="form-check-input" @if (old('es_de_venta') && old('es_de_venta') == 0) checked="true" @endif>
                                    <label for="es_de_venta_create2">Producto de uso interno</label>
                                </div>
                                @if ($errors->any() && $errors->first('es_de_venta'))
                                    <span class="badge badge-pill badge-danger">{{$errors->first('es_de_venta')}}</span>
                                @endif
                            </div>
                            <!--<div class="col-lg-6">
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
                                    <input type="hidden" id="select-unit-measurement-data-url" value="{ route('unidad-de-medida.index') }}">
                                </div>
                            </div>-->
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