-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 21-04-2023 a las 22:29:23
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cardwash`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE IF NOT EXISTS `compra` (
  `id_compra` int(11) NOT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `reg_op` varchar(45) DEFAULT NULL,
  `fecha_emision` datetime DEFAULT NULL,
  `compracol` varchar(45) DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `no_comprobante` varchar(45) DEFAULT NULL,
  `id_proveedor` varchar(45) DEFAULT NULL,
  `razon_social_proveedor` varchar(45) DEFAULT NULL,
  `compra_o_gasto` varchar(45) DEFAULT NULL,
  `descuentos_iva` varchar(45) DEFAULT NULL,
  `importe_total` varchar(45) DEFAULT NULL,
  `condiciones_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `fk_compra_estado1_idx` (`id_estado`),
  KEY `fk_compra_condiciones1_idx` (`condiciones_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condiciones`
--

DROP TABLE IF EXISTS `condiciones`;
CREATE TABLE IF NOT EXISTS `condiciones` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra_productos`
--

DROP TABLE IF EXISTS `detalle_compra_productos`;
CREATE TABLE IF NOT EXISTS `detalle_compra_productos` (
  `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `referencia` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_compra`),
  KEY `fk_producto_has_compra_compra1_idx` (`id_compra`),
  KEY `fk_producto_has_compra_producto1_idx` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_productos`
--

DROP TABLE IF EXISTS `detalle_venta_productos`;
CREATE TABLE IF NOT EXISTS `detalle_venta_productos` (
  `id_venta` int(11) NOT NULL,
  `detalle_productos` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_venta`,`detalle_productos`),
  KEY `fk_detalle_venta_servicios_venta1_idx` (`id_venta`),
  KEY `fk_detalle_venta_servicios_detalle_compra_productos1_idx` (`detalle_productos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos_concepto`
--

DROP TABLE IF EXISTS `egresos_concepto`;
CREATE TABLE IF NOT EXISTS `egresos_concepto` (
  `id` int(11) NOT NULL,
  `concepto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos_mensuales`
--

DROP TABLE IF EXISTS `egresos_mensuales`;
CREATE TABLE IF NOT EXISTS `egresos_mensuales` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_concepto` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_concepto`),
  KEY `fk_egresos_mensuales_egresos_concepto1_idx` (`id_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos_productos`
--

DROP TABLE IF EXISTS `egresos_productos`;
CREATE TABLE IF NOT EXISTS `egresos_productos` (
  `id_egresos` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_detalle_compra` int(11) NOT NULL,
  PRIMARY KEY (`id_egresos`,`id_detalle_compra`),
  KEY `fk_egresos_productos_detalle_compra_productos1_idx` (`id_detalle_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

DROP TABLE IF EXISTS `estado`;
CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

DROP TABLE IF EXISTS `marca`;
CREATE TABLE IF NOT EXISTS `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_de_medida`
--

DROP TABLE IF EXISTS `unidad_de_medida`;
CREATE TABLE IF NOT EXISTS `unidad_de_medida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `abreviatura` varchar(45) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `id_tipo_producto` int(11) NOT NULL,
  `id_unidad_de_medida` int(11) NOT NULL,
  PRIMARY KEY (`id_producto`,`id_tipo_producto`),
  KEY `fk_producto_marca_idx` (`id_marca`),
  KEY `fk_producto_tipo_producto1_idx` (`id_tipo_producto`),
  KEY `fk_producto_unidad_de_medida_idx` (`id_unidad_de_medida`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

DROP TABLE IF EXISTS `servicio`;
CREATE TABLE IF NOT EXISTS `servicio` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_tipo_vehiculo`
--

DROP TABLE IF EXISTS `servicio_tipo_vehiculo`;
CREATE TABLE IF NOT EXISTS `servicio_tipo_vehiculo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_servicio` int(11) NOT NULL,
  `id_tipo_vehiculo` int(11) NOT NULL,
  `precio_venta` float DEFAULT NULL,
  `porcentaje_trabajador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servicio_tipo_vehiculo_servicio1_idx` (`id_servicio`),
  KEY `fk_servicio_tipo_vehiculo_tipo_vehiculo1_idx` (`id_tipo_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Activo'),
(2, 'Pendiente'),
(3, 'Cancelada');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `system_menu`
--

INSERT INTO `system_menu` (`id`, `nombre`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Usuarios', 'mdi mdi-account-card-details', '2021-06-11 14:46:24', '2021-06-11 14:46:24'),
(2, 'Tareas', 'mdi mdi-format-list-bulleted-type', '2021-06-11 14:46:42', '2021-06-11 14:46:42');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `system_submenu`
--

INSERT INTO `system_submenu` (`id`, `id_menu`, `nombre`, `url`, `permiso_requerido`, `logo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Inicio', '/User', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:31:16', '2021-10-21 04:31:16'),
(2, 2, 'Inicio', '/task', NULL, 'mdi mdi-chevron-double-right', '2021-10-21 04:30:44', '2021-10-21 04:30:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

DROP TABLE IF EXISTS `tipo_producto`;
CREATE TABLE IF NOT EXISTS `tipo_producto` (
  `id_tipo_producto` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculo`
--

DROP TABLE IF EXISTS `tipo_vehiculo`;
CREATE TABLE IF NOT EXISTS `tipo_vehiculo` (
  `id_tipo_vehiculo` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `imagen_url` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `identificacion`, `name`, `estado`, `password`, `remember_token`, `email`, `created_at`, `updated_at`) VALUES
(2, '1143991688', 'Anderson David Rojas', 1, '$2y$10$odheLv9bS5EGTjxmIgFUmeaqy/GZrhT9UFn0lfUIpCX8tjc5Lo0ni', '3eUFknn3uTiY4Oeg98KHqzQBIjETvK75JD4vAeX53kYRK1QYsezMY2wf7b4P', 'rojasanderson07@gmail.com', NULL, '2021-11-02 21:57:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE IF NOT EXISTS `venta` (
  `id_venta` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `nombre_cliente` varchar(45) DEFAULT NULL,
  `placa` varchar(45) DEFAULT NULL,
  `numero_telefono` varchar(45) DEFAULT NULL,
  `tipo_servicio` int(11) NOT NULL,
  `total_venta` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_venta`,`tipo_servicio`),
  KEY `fk_venta_servicio_tipo_vehiculo1_idx` (`tipo_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fk_compra_condiciones1` FOREIGN KEY (`condiciones_id`) REFERENCES `condiciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_compra_estado1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_compra_productos`
--
ALTER TABLE `detalle_compra_productos`
  ADD CONSTRAINT `fk_producto_has_compra_compra1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_has_compra_producto1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_venta_productos`
--
ALTER TABLE `detalle_venta_productos`
  ADD CONSTRAINT `fk_detalle_venta_servicios_detalle_compra_productos1` FOREIGN KEY (`detalle_productos`) REFERENCES `detalle_compra_productos` (`id_detalle_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_venta_servicios_venta1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `egresos_mensuales`
--
ALTER TABLE `egresos_mensuales`
  ADD CONSTRAINT `fk_egresos_mensuales_egresos_concepto1` FOREIGN KEY (`id_concepto`) REFERENCES `egresos_concepto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `egresos_productos`
--
ALTER TABLE `egresos_productos`
  ADD CONSTRAINT `fk_egresos_productos_detalle_compra_productos1` FOREIGN KEY (`id_detalle_compra`) REFERENCES `detalle_compra_productos` (`id_detalle_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_tipo_producto1` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_unidad_de_medida` FOREIGN KEY (`id_unidad_de_medida`) REFERENCES `unidad_de_medida` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `servicio_tipo_vehiculo`
--
ALTER TABLE `servicio_tipo_vehiculo`
  ADD CONSTRAINT `fk_servicio_tipo_vehiculo_servicio1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_servicio_tipo_vehiculo_tipo_vehiculo1` FOREIGN KEY (`id_tipo_vehiculo`) REFERENCES `tipo_vehiculo` (`id_tipo_vehiculo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_servicio_tipo_vehiculo1` FOREIGN KEY (`tipo_servicio`) REFERENCES `servicio_tipo_vehiculo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
