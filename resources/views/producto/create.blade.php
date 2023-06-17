<div class="modal fade" id="modal_create_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary d-block">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-uppercase text-center text-light" >Crear producto&nbsp;<span class="mdi mdi-package"></span></h5>
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
                                    <label class="control-label">Tipo de producto&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="select2-create select-tipo-producto" name="id_tipo_producto" id="select-tipo-producto" style="width: 90%;" required>
                                        </select>
                                        
                                        <div class="input-group-append" title="Agregar tipo de producto" data-toggle="tooltip">
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modal_create_product_type">
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
                        <div class="d-flex justify-content-center">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Marca&nbsp;:</label>
                                    <div class="input-group">
                                        <select class="select2-create select-marca" name="id_marca" id="select-marca" required style="width: 90%">
                                        </select>

                                        <div class="input-group-append" title="Agregar marca" data-toggle="tooltip">
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modal_create_brand">
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
                        </div>    
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <label class="control-label">Unidad de medida&nbsp;:</label>
                                <select class="select2-create select-unidad-de-medida" name="id_unidad_medida" style="width: 100%">
                                    <option>Seleccione unidad de medida</option>
                                </select>
                                @if ($errors->any() && $errors->first('id_unidad_medida'))
                                    <span class="badge badge-pill badge-danger">{{$errors->first('id_unidad_medida')}}</span>
                                @endif
                                @if (old('id_unidad_medida'))
                                    <input type="hidden" id="old-select-unit-measurement" value="{{ old('id_unidad_medida') }}">
                                @endif
                                <input type="hidden" id="select-unit-measurement-data-url" value="{{ route('unidad-de-medida.index') }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label">Presentacion&nbsp;:</label>
                                <select class="select2-create select-presentation" name="id_presentacion" style="width: 100%">
                                    <option>Seleccione la presentaci&oacute;n</option>
                                </select>
                                @if ($errors->any() && $errors->first('id_presentacion'))
                                    <span class="badge badge-pill badge-danger">{{$errors->first('id_presentacion')}}</span>
                                @endif
                                @if (old('id_presentacion'))
                                    <input type="hidden" id="old-select-presentation" value="{{ old('id_presentacion') }}">
                                @endif
                                <input type="hidden" id="select-presentation-data-url" value="{{ route('presentacion.index') }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label">Area :</label>
                                <select class="select2-create select-area" name="id_area" style="width: 100%">
                                    <option>Seleccione la Area</option>
                                </select>
                                @if ($errors->any() && $errors->first('id_area'))
                                    <span class="badge badge-pill badge-danger">{{$errors->first('id_area')}}</span>
                                @endif
                                @if (old('id_area'))
                                    <input type="hidden" id="old-select-presentation" value="{{ old('id_area') }}">
                                @endif
                                <input type="hidden" id="select-area-data-url" value="{{ route('area.index') }}">
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