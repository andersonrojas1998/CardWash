-- -----------------------------------------------------
-- Table `cardwash`.`marca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`marca` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `estado` INT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`tipo_producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`tipo_producto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`unidad_medida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`unidad_medida` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `abreviatura` VARCHAR(45) NULL,
  `estado` INT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`presentacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`presentacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`producto` (
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
    REFERENCES `cardwash`.`marca` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_tipo_producto1`
    FOREIGN KEY (`id_tipo_producto`)
    REFERENCES `cardwash`.`tipo_producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_unidad_medida1`
    FOREIGN KEY (`id_unidad_medida`)
    REFERENCES `cardwash`.`unidad_medida` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_presentacion1`
    FOREIGN KEY (`id_presentacion`)
    REFERENCES `cardwash`.`presentacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`estado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`condiciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`condiciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`compra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`compra` (
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
    REFERENCES `cardwash`.`condiciones` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_estado1`
    FOREIGN KEY (`estado_id`)
    REFERENCES `cardwash`.`estado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;




-- -----------------------------------------------------
-- Table `cardwash`.`detalle_compra_productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`detalle_compra_productos` (
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
    REFERENCES `cardwash`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_compra_compra1`
    FOREIGN KEY (`id_compra`)
    REFERENCES `cardwash`.`compra` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`servicio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`servicio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`tipo_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`tipo_vehiculo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `imagen` VARCHAR(45) NULL,
  `nomenclatura` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`paquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `color` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`detalle_paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`detalle_paquete` (
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
    REFERENCES `cardwash`.`tipo_vehiculo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_paquete_paquete1`
    FOREIGN KEY (`id_paquete`)
    REFERENCES `cardwash`.`paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`estado_venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`estado_venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL,
  `descripcion` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `cardwash`.`venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
    REFERENCES `cardwash`.`detalle_paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_venta_estado_venta1`
    FOREIGN KEY (`id_estado_venta`)
    REFERENCES `cardwash`.`estado_venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`servicio_paquete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`servicio_paquete` (
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
    REFERENCES `cardwash`.`servicio` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicio_tipo_vehiculo_paquete1`
    FOREIGN KEY (`id_paquete`)
    REFERENCES `cardwash`.`detalle_paquete` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`egresos_concepto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`egresos_concepto` (
  `id` INT NOT NULL,
  `concepto` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`egresos_mensuales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`egresos_mensuales` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NULL,
  `id_concepto` INT NOT NULL,
  `total_egreso` INT NULL,
  PRIMARY KEY (`id`, `id_concepto`),
  INDEX `fk_egresos_mensuales_egresos_concepto1_idx` (`id_concepto` ASC) ,
  CONSTRAINT `fk_egresos_mensuales_egresos_concepto1`
    FOREIGN KEY (`id_concepto`)
    REFERENCES `cardwash`.`egresos_concepto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardwash`.`detalle_venta_productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`detalle_venta_productos` (
  `id_venta` INT NOT NULL,
  `cantidad` INT NULL,
  `id_producto` INT NOT NULL,
  `precio_venta` FLOAT NULL,
  `margen_ganancia` FLOAT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_venta`, `id_producto`),
  INDEX `fk_venta_has_detalle_compra_productos_venta1_idx` (`id_venta` ASC) ,
  INDEX `fk_detalle_venta_productos_producto1_idx` (`id_producto` ASC) ,
  CONSTRAINT `fk_venta_has_detalle_compra_productos_venta1`
    FOREIGN KEY (`id_venta`)
    REFERENCES `cardwash`.`venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_venta_productos_producto1`
    FOREIGN KEY (`id_producto`)
    REFERENCES `cardwash`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificacion` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `estado` bigint(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `identificacion`, `name`, `estado`, `password`, `remember_token`, `email`, `created_at`, `updated_at`) VALUES
(2, '1143991688', 'Anderson David Rojas', 1, '$2y$10$odheLv9bS5EGTjxmIgFUmeaqy/GZrhT9UFn0lfUIpCX8tjc5Lo0ni', '3eUFknn3uTiY4Oeg98KHqzQBIjETvK75JD4vAeX53kYRK1QYsezMY2wf7b4P', 'rojasanderson07@gmail.com', NULL, '2021-11-02 21:57:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
)  ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `roles` (`id`, `name`, `slug`) VALUE(1, 'ADMIN', 'ADMIN');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_index` (`role_id`),
  KEY `role_user_user_id_index` (`user_id`)
)  ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_menu`
--

DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE IF NOT EXISTS `system_menu` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `system_menu`
--

INSERT INTO `system_menu` (`id`, `nombre`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Productos', 'mdi mdi-package', '2021-06-11 14:46:24', '2021-06-11 14:46:24'),
(2, 'Compras', 'mdi mdi-package', '2021-06-11 14:46:42', '2021-06-11 14:46:42'),
(3, 'Servicios', 'mdi mdi-format-list-bulleted-type', '2021-06-11 14:46:24', '2021-06-11 14:46:24'),
(4, 'Ventas', 'mdi mdi-format-list-bulleted-type', '2021-06-11 14:46:24', '2021-06-11 14:46:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_menu_role`
--

DROP TABLE IF EXISTS `system_menu_role`;
CREATE TABLE IF NOT EXISTS `system_menu_role` (
  `id_role` int(10) UNSIGNED DEFAULT NULL,
  `id_menu` int(10) UNSIGNED DEFAULT NULL,
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_role` (`id_role`),
  KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `system_menu_role`
--

INSERT INTO `system_menu_role` (`id_role`, `id_menu`, `id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(1, 5, 5),
(1, 6, 6),
(1, 7, 7),
(1, 8, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_submenu`
--

DROP TABLE IF EXISTS `system_submenu`;
CREATE TABLE IF NOT EXISTS `system_submenu` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_menu` int(10) UNSIGNED DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permiso_requerido` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_menu` (`id_menu`),
  KEY `permiso_requerido` (`permiso_requerido`)
) ENGINE=InnoDB;

--
-- Volcado de datos para la tabla `system_submenu`
--

INSERT INTO `system_submenu` (`id`, `id_menu`, `nombre`, `url`, `permiso_requerido`, `logo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Inicio', '/producto', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:31:16', '2021-10-21 04:31:16'),
(2, 2, 'Inicio', '/compra', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:30:44', '2021-10-21 04:30:44'),
(3, 3, 'Inicio', '/servicio', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:31:16', '2021-10-21 04:31:16'),
(4, 4, 'Inicio', '/venta', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:31:16', '2021-10-21 04:31:16');

-- -----------------------------------------------------
-- Table `cardwash`.`movimiento_pagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardwash`.`movimiento_pagos` (
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
    REFERENCES `cardwash`.`venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '	';


