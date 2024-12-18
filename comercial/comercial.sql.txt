-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-12-2024 a las 12:06:22
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `intran23_comercial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientesleads`
--

CREATE TABLE `clientesleads` (
  `id` int(11) NOT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `giro` varchar(100) DEFAULT NULL,
  `localidad` varchar(255) DEFAULT NULL,
  `sucursal` varchar(255) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientesleads`
--

INSERT INTO `clientesleads` (`id`, `contacto`, `correo`, `telefono`, `empresa`, `giro`, `localidad`, `sucursal`, `fechaCreacion`, `id_usuario`, `id_detalle`) VALUES
(9, 'Humberto Fraire ', 'humberto.fraire@outlook.com', 4441268330, 'DIPAJ SLP', 'Iglesia', 'San Luis', '9', '2024-12-02 01:52:20', 1, 0),
(10, 'Edgar Galindo', 'egalindo@gmail.com', 8717262617, '', 'Manufactura', '', NULL, '2024-12-04 12:49:49', 1, 0),
(11, 'Edgar Gaindo', 'egalindo@gmail.com', 8717262617, '', 'Manufactura', '', NULL, '2024-12-04 13:00:07', 1, 0),
(12, 'Jose Chuy', 'jose@gmail.com', 717262617162, '', '', '', NULL, '2024-12-04 16:22:27', 1, 0),
(13, 'Jose Chuy', 'jose@gmail.com', 717262617162, '', '', '', NULL, '2024-12-04 16:23:38', 1, 0),
(15, 'Jose Chuy', 'usgsgddjdjd@sjhs.com', 8717262617, '', 'Manufactura', '', NULL, '2024-12-05 08:53:01', 1, 0),
(16, 'Edgar Gaindo', 'usgsgddjdjd@sjhs.com', 0, '', '', '', NULL, '2024-12-05 09:39:08', 1, 0),
(17, 'Jose Chuy', 'ebetancourt@drg.mx', 8717262617, '', '', '', NULL, '2024-12-05 10:11:21', 1, 0),
(18, 'Jose Chuy', 'ebetancourt@drg.mx', 8717262617, '', '', '', NULL, '2024-12-05 10:20:40', 1, 0),
(19, 'Jose Chuy', 'ebetancourt@drg.mx', 8717262617, '', '', '', NULL, '2024-12-05 10:21:10', 1, 0),
(20, 'Jose Chuy', 'usgsgddjdjd@sjhs.com', 8717262617, '', 'Manufactura', '', NULL, '2024-12-05 10:54:43', 1, 0),
(21, 'Jose Chuy', 'usgsgddjdjd@sjhs.com', 8717262617, '', 'Manufactura', '', NULL, '2024-12-05 11:11:26', 1, 0),
(22, 'Humberto Fraire', 'usgsgddjdjd@sjhs.com', 8717262617, '', '', '', NULL, '2024-12-05 11:32:50', 1, 0),
(23, 'Jose Luis', 'jose@gmail.com', 8711238474, '', 'Alimentos', '', NULL, '2024-12-05 12:55:27', 1, 0),
(25, 'axbsjxnskksmls', 'mcarrillo@drg.mx', 0, 'DIPAJ SLP', '', '', NULL, '2024-12-05 13:00:22', 1, 0),
(26, 'Humberto Fraire', '', 717262617162, 'asddfhsbhsbhjsns', '', '', NULL, '2024-12-05 13:02:24', 1, 0),
(27, 'PRUEBA1', '', 0, 'PRUEBA', '', '', NULL, '2024-12-10 12:11:17', 1, 0),
(46, 'PRUEBA', 'ESTO@PRUEBA.COM', 123456789, 'aaaaa', 'PRUEBA', '', NULL, '2024-12-16 17:26:22', 1, 0),
(47, 'Jose Alfredo', 'jose@lala.com', 8717262617, 'LALA', '', '', NULL, '2024-12-17 08:12:39', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactoleads`
--

CREATE TABLE `contactoleads` (
  `id_contacto` int(11) NOT NULL,
  `contacto` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contactoleads`
--

INSERT INTO `contactoleads` (`id_contacto`, `contacto`) VALUES
(1, 'Expo'),
(2, 'Llamada'),
(3, 'WhatsApp'),
(4, 'Página Web'),
(5, 'Generación Propia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatusleads`
--

CREATE TABLE `estatusleads` (
  `id_estatus` int(11) NOT NULL,
  `estatus` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estatusleads`
--

INSERT INTO `estatusleads` (`id_estatus`, `estatus`) VALUES
(1, 'Prospecto'),
(2, 'Interesado'),
(3, 'Contactado'),
(4, 'En Seguimiento'),
(5, 'Cotización Enviada'),
(6, 'Presentación Realizada'),
(7, 'No contesta'),
(8, 'No Viable'),
(9, 'Cerrado-Ganado'),
(12, 'Cerrado-Perdido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gerentesleads`
--

CREATE TABLE `gerentesleads` (
  `id_gerente` int(11) NOT NULL,
  `gerente` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `gerentesleads`
--

INSERT INTO `gerentesleads` (`id_gerente`, `gerente`) VALUES
(1, 'Ana Velez - Saltillo / Mty'),
(2, 'Bertha Diaz - Cd. Juárez'),
(3, 'Pamela Hernández - Durango'),
(4, 'Iván Martínez - Puebla'),
(5, 'Sin Gerente - León'),
(6, 'Yaneth Gonzáles - Tijuana'),
(7, 'Ajelet Sanchez - Chihuahua'),
(8, 'Paola Martínez - Querétaro / San Luis'),
(9, 'Nadia Villanueva - Laguna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `girocomercial`
--

CREATE TABLE `girocomercial` (
  `id_giro` int(11) NOT NULL,
  `giro` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `periodo` int(11) DEFAULT NULL,
  `gerente_responsable` int(11) DEFAULT NULL,
  `id_sucursal` int(11) DEFAULT NULL,
  `fecha_generacion` datetime DEFAULT NULL,
  `medio_contacto` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `cotizacion` decimal(10,2) DEFAULT NULL,
  `linea_negocio` int(11) DEFAULT NULL,
  `giro_id` int(11) DEFAULT NULL,
  `notas` text,
  `archivo` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `historial_cambios` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `leads`
--

INSERT INTO `leads` (`id`, `periodo`, `gerente_responsable`, `id_sucursal`, `fecha_generacion`, `medio_contacto`, `estatus`, `cotizacion`, `linea_negocio`, `giro_id`, `notas`, `archivo`, `id_usuario`, `id_cliente`, `historial_cambios`) VALUES
(3, 1, 8, 9, '2024-12-02 01:52:20', 3, 4, 12344.00, 1, 0, 'Esta interesado', NULL, 1, 9, NULL),
(5, 2, 9, 1, '2024-12-04 13:00:07', 3, 12, 3245.00, 2, 0, 'Interesado en Docuware', NULL, 1, 11, NULL),
(9, 2, 3, 4, '2024-12-05 08:53:01', 4, 5, 345.00, 2, 0, 'hola', NULL, 1, 15, NULL),
(11, 2, 9, 1, '2024-12-05 11:11:26', 1, 1, 3452.00, 3, 0, 'hola', NULL, 1, 21, NULL),
(13, 1, 6, 6, '2024-12-05 12:55:27', 2, 9, 2345.00, 1, 0, 'Hola', NULL, 1, 23, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `negocioleads`
--

CREATE TABLE `negocioleads` (
  `id_negocio` int(11) NOT NULL,
  `negocio` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `negocioleads`
--

INSERT INTO `negocioleads` (`id_negocio`, `negocio`) VALUES
(1, 'MPS'),
(2, 'GESTIÓN DOCUMENTAL'),
(3, 'ETIQUETADO Y CODIFICADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodosleads`
--

CREATE TABLE `periodosleads` (
  `id_periodo` int(11) NOT NULL,
  `periodo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `periodosleads`
--

INSERT INTO `periodosleads` (`id_periodo`, `periodo`) VALUES
(1, 'Noviembre 2024'),
(2, 'Diciembre 2024'),
(3, 'Enero 2025'),
(4, 'Febrero 2025'),
(5, 'Marzo 2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_productos` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `proveedor` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `detalles` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id_puestos` int(11) NOT NULL,
  `puesto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id_puestos`, `puesto`) VALUES
(1, 'Programador'),
(2, 'Gerente de Sucursal'),
(3, 'Generador de Demanda'),
(4, 'Gerente Comercial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_roles` int(11) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_roles`, `rol`) VALUES
(0, 'Usuario'),
(1, 'Administrador'),
(2, 'Gerente Comercial'),
(3, 'Gerente Sucursal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `id_sucursales` int(11) NOT NULL,
  `sucursal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id_sucursales`, `sucursal`) VALUES
(1, 'Laguna'),
(2, 'Cd. Juárez'),
(3, 'Chihuahua'),
(4, 'Durango'),
(5, 'León'),
(6, 'Puebla'),
(7, 'Querétaro'),
(8, 'Saltillo'),
(9, 'San Luis Potosí'),
(10, 'Tijuana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `correo` varchar(250) DEFAULT NULL,
  `contraseña` varchar(250) NOT NULL,
  `rol` int(11) NOT NULL DEFAULT '0',
  `puesto` int(11) DEFAULT NULL,
  `sucursal` int(11) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `intentos_fallidos` int(11) NOT NULL DEFAULT '0',
  `ultimo_intento` timestamp NULL DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `nombre`, `correo`, `contraseña`, `rol`, `puesto`, `sucursal`, `estado`, `ultimo_acceso`, `intentos_fallidos`, `ultimo_intento`, `fecha_creacion`, `foto_perfil`, `genero`) VALUES
(1, 'Siclary Arreola', 'programador01@drg.mx', '$2y$10$oiBY0I/PoxLF46KTxcr1lecS69VCQPAthOghgGdPiNTmvp/T9zqFm', 1, 1, 1, 'Activo', '2024-12-18 17:41:19', 0, NULL, '2024-11-27 18:08:06', NULL, NULL),
(11, 'Edgar Cruz', 'ecruz@drg.mx', '$2y$10$5lzYMenFNc.efwLlATJ8quH90G8mi1FuJZ1Hzg5gcxeb6mtPWYAHa', 0, 3, 1, 'Activo', '2024-12-02 08:15:46', 0, NULL, '2024-11-27 18:08:06', NULL, NULL),
(21, 'Edgar Carrizalez', 'ecarrizalez@drg.mx', '$2y$10$WNQa2ycx3pw0pxwQwE11g.nAuTS2WVxPMpKpvd2DnrwhFDe.OcAii', 1, 3, 1, 'Activo', '2024-12-03 22:10:36', 0, NULL, '2024-11-28 15:57:08', NULL, NULL),
(22, 'Cristina Castruita', 'ccastruita@drg.mx', '$2y$10$6U7w046JSBmnU/BBRHZvxuQ/B5kmYIhuINLsa17NUiCiRVyG41TF6', 2, 4, 1, 'Activo', '2024-12-17 15:07:28', 0, NULL, '2024-12-02 08:15:01', NULL, NULL),
(53, 'Gloria Carrasco', 'gcarrasco@drg.mx', '$2y$10$sckhrQ1GgIHa3QD5qdSrJOL7fo0HWnhibwAnHR/u/qlD1pVA3F92S', 1, 1, 1, 'Activo', '2024-12-11 17:39:42', 0, NULL, '2024-12-11 17:35:54', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientesleads`
--
ALTER TABLE `clientesleads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `contactoleads`
--
ALTER TABLE `contactoleads`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Indices de la tabla `estatusleads`
--
ALTER TABLE `estatusleads`
  ADD PRIMARY KEY (`id_estatus`);

--
-- Indices de la tabla `gerentesleads`
--
ALTER TABLE `gerentesleads`
  ADD PRIMARY KEY (`id_gerente`);

--
-- Indices de la tabla `girocomercial`
--
ALTER TABLE `girocomercial`
  ADD PRIMARY KEY (`id_giro`);

--
-- Indices de la tabla `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_estatus` (`estatus`),
  ADD KEY `fk_gerente_responsable` (`gerente_responsable`),
  ADD KEY `fk_periodo` (`periodo`),
  ADD KEY `fk_linea_negocio` (`linea_negocio`),
  ADD KEY `id_sucursal` (`id_sucursal`),
  ADD KEY `fk_medio_contacto` (`medio_contacto`),
  ADD KEY `giro_id` (`giro_id`);

--
-- Indices de la tabla `negocioleads`
--
ALTER TABLE `negocioleads`
  ADD PRIMARY KEY (`id_negocio`);

--
-- Indices de la tabla `periodosleads`
--
ALTER TABLE `periodosleads`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_productos`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id_puestos`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_roles`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id_sucursales`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`),
  ADD KEY `id_rol` (`rol`),
  ADD KEY `id_puesto` (`puesto`),
  ADD KEY `id_sucursal` (`sucursal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientesleads`
--
ALTER TABLE `clientesleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `contactoleads`
--
ALTER TABLE `contactoleads`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estatusleads`
--
ALTER TABLE `estatusleads`
  MODIFY `id_estatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `gerentesleads`
--
ALTER TABLE `gerentesleads`
  MODIFY `id_gerente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `negocioleads`
--
ALTER TABLE `negocioleads`
  MODIFY `id_negocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `periodosleads`
--
ALTER TABLE `periodosleads`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_productos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id_puestos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id_sucursales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientesleads`
--
ALTER TABLE `clientesleads`
  ADD CONSTRAINT `clientesleads_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `girocomercial`
--
ALTER TABLE `girocomercial`
  ADD CONSTRAINT `fk_giro_leads` FOREIGN KEY (`id_giro`) REFERENCES `leads` (`giro_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `fk_estatus` FOREIGN KEY (`estatus`) REFERENCES `estatusleads` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gerente_responsable` FOREIGN KEY (`gerente_responsable`) REFERENCES `gerentesleads` (`id_gerente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_linea_negocio` FOREIGN KEY (`linea_negocio`) REFERENCES `negocioleads` (`id_negocio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_medio_contacto` FOREIGN KEY (`medio_contacto`) REFERENCES `contactoleads` (`id_contacto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_periodo` FOREIGN KEY (`periodo`) REFERENCES `periodosleads` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientesleads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursales`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id_roles`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`puesto`) REFERENCES `puestos` (`id_puestos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`sucursal`) REFERENCES `sucursales` (`id_sucursales`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
