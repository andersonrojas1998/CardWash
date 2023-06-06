-- -----------------------------------------------------
-- Table `cardwash1`.`marca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`marca` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `estado` INT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`tipo_producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`tipo_producto` (
  `id` INT NOT NULL,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`unidad_medida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`unidad_medida` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `abrevitura` VARCHAR(45) NULL,
  `estado` INT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`presentacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`presentacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`producto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `id_marca` INT NOT NULL,
  `id_tipo_producto` INT NOT NULL,
  `id_unidad_medida` INT NOT NULL,
  `id_presentacion` INT NOT NULL,
  `cant_stock` INT NULL,
  `cant_stock_mov` INT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `id_tipo_producto`, `id_unidad_medida`),
  INDEX `fk_producto_marca_idx` (`id_marca` ASC) ,
  INDEX `fk_producto_tipo_producto1_idx` (`id_tipo_producto` ASC) ,
  INDEX `fk_producto_unidad_medida1_idx` (`id_unidad_medida` ASC) ,
  INDEX `fk_producto_presentacion1_idx` (`id_presentacion` ASC) ,
  CONSTRAINT `fk_producto_marca`
    FOREIGN KEY (`id_marca`)
    REFERENCES `cardwash1`.`marca` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_tipo_producto1`
    FOREIGN KEY (`id_tipo_producto`)
    REFERENCES `cardwash1`.`tipo_producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_unidad_medida1`
    FOREIGN KEY (`id_unidad_medida`)
    REFERENCES `cardwash1`.`unidad_medida` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_presentacion1`
    FOREIGN KEY (`id_presentacion`)
    REFERENCES `cardwash1`.`presentacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`estado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`condiciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`condiciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`compra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`compra` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reg_op` VARCHAR(45) NULL,
  `fecha_emision` DATETIME NULL,
  `compracol` VARCHAR(45) NULL,
  `fecha_vencimiento` DATETIME NULL,
  `no_comprobante` VARCHAR(45) NULL,
  `id_proveedor` VARCHAR(45) NULL,
  `razon_social_proveedor` VARCHAR(120) NULL,
  `descuentos_iva` VARCHAR(45) NULL,
  `importe_total` VARCHAR(45) NULL,
  `condiciones_id` INT NULL,
  `estado_id` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `estado_id`),
  INDEX `fk_compra_condiciones1_idx` (`condiciones_id` ASC) ,
  INDEX `fk_compra_estado1_idx` (`estado_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_compra_condiciones1`
    FOREIGN KEY (`condiciones_id`)
    REFERENCES `cardwash1`.`condiciones` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_estado1`
    FOREIGN KEY (`estado_id`)
    REFERENCES `cardwash1`.`estado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;




-- -----------------------------------------------------
-- Table `cardwash1`.`detalle_compra_productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`detalle_compra_productos` (
  `id_detalle_compra` INT NOT NULL AUTO_INCREMENT,
  `id_producto` INT NOT NULL,
  `id_compra` INT NOT NULL,
  `cantidad` INT NULL,
  `precio_compra` FLOAT NULL,
  `precio_venta` FLOAT NULL,
  `referencia` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_compra`),
  INDEX `fk_producto_has_compra_compra1_idx` (`id_compra` ASC) ,
  INDEX `fk_producto_has_compra_producto1_idx` (`id_producto` ASC) ,
  CONSTRAINT `fk_producto_has_compra_producto1`
    FOREIGN KEY (`id_producto`)
    REFERENCES `cardwash1`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_compra_compra1`
    FOREIGN KEY (`id_compra`)
    REFERENCES `cardwash1`.`compra` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`servicio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`servicio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`tipo_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`tipo_vehiculo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `imagen` VARCHAR(45) NULL,
  `nomenclatura` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`paquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `color` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`detalle_paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`detalle_paquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `precio_venta` INT NULL,
  `porcentaje` INT NULL,
  `id_tipo_vehiculo` INT NOT NULL,
  `id_paquete` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `id_tipo_vehiculo`, `id_paquete`),
  INDEX `fk_paquete_tipo_vehiculo1_idx` (`id_tipo_vehiculo` ASC) ,
  INDEX `fk_paquete_paquete1_idx` (`id_paquete` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_paquete_tipo_vehiculo1`
    FOREIGN KEY (`id_tipo_vehiculo`)
    REFERENCES `cardwash1`.`tipo_vehiculo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_paquete_paquete1`
    FOREIGN KEY (`id_paquete`)
    REFERENCES `cardwash1`.`paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`estado_venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`estado_venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `cardwash1`.`venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `nombre_cliente` VARCHAR(80) NOT NULL,
  `placa` VARCHAR(45) NULL,
  `numero_telefono` VARCHAR(45) NULL,
  `id_detalle_paquete` INT NULL,
  `id_usuario` INT NOT NULL,
  `id_estado_venta` INT NOT NULL COMMENT '1 = Pendiente por pagar , 2 = Servicio pagado , 3 = Venta Interna',
  `fecha_pago` DATE NULL,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `id_estado_venta`),
  INDEX `fk_venta_detalle_paquete1_idx` (`id_detalle_paquete` ASC) ,
  INDEX `fk_venta_estado_venta1_idx` (`id_estado_venta` ASC) ,
  CONSTRAINT `fk_venta_detalle_paquete1`
    FOREIGN KEY (`id_detalle_paquete`)
    REFERENCES `cardwash1`.`detalle_paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_venta_estado_venta1`
    FOREIGN KEY (`id_estado_venta`)
    REFERENCES `cardwash1`.`estado_venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`servicio_paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`servicio_paquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_servicio` INT NOT NULL,
  `id_paquete` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `id_paquete`),
  INDEX `fk_servicio_tipo_vehiculo_servicio1_idx` (`id_servicio` ASC) ,
  INDEX `fk_servicio_tipo_vehiculo_paquete1_idx` (`id_paquete` ASC) ,
  CONSTRAINT `fk_servicio_tipo_vehiculo_servicio1`
    FOREIGN KEY (`id_servicio`)
    REFERENCES `cardwash1`.`servicio` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicio_tipo_vehiculo_paquete1`
    FOREIGN KEY (`id_paquete`)
    REFERENCES `cardwash1`.`detalle_paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`egresos_concepto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`egresos_concepto` (
  `id` INT NOT NULL,
  `concepto` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`egresos_mensuales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`egresos_mensuales` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NULL,
  `id_concepto` INT NOT NULL,
  `total_egreso` INT NULL,
  PRIMARY KEY (`id`, `id_concepto`),
  INDEX `fk_egresos_mensuales_egresos_concepto1_idx` (`id_concepto` ASC) ,
  CONSTRAINT `fk_egresos_mensuales_egresos_concepto1`
    FOREIGN KEY (`id_concepto`)
    REFERENCES `cardwash1`.`egresos_concepto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`detalle_venta_productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`detalle_venta_productos` (
  `id_venta` INT NOT NULL,
  `cantidad` INT NULL,
  `id_producto` INT NOT NULL,
  `precio_venta` FLOAT NULL,
  `margen_ganacia` FLOAT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_venta`, `id_producto`),
  INDEX `fk_venta_has_detalle_compra_productos_venta1_idx` (`id_venta` ASC) ,
  INDEX `fk_detalle_venta_productos_producto1_idx` (`id_producto` ASC) ,
  CONSTRAINT `fk_venta_has_detalle_compra_productos_venta1`
    FOREIGN KEY (`id_venta`)
    REFERENCES `cardwash1`.`venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_venta_productos_producto1`
    FOREIGN KEY (`id_producto`)
    REFERENCES `cardwash1`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash1`.`movimiento_pagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash1`.`movimiento_pagos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_pago` DATE NULL,
  `valor_pago` FLOAT NULL,
  `venta_id` INT NOT NULL,
  `id_user` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `venta_id`, `id_user`),
  INDEX `fk_movimiento_pagos_venta1_idx` (`venta_id` ASC) ,
  CONSTRAINT `fk_movimiento_pagos_venta1`
    FOREIGN KEY (`venta_id`)
    REFERENCES `cardwash1`.`venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '	';


