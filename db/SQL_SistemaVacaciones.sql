-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-03-2024 a las 23:37:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_vacaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

CREATE TABLE `archivos` (
  `id_archivo` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_aprueba` int(11) NOT NULL,
  `id_registra` int(11) NOT NULL,
  `descripcion_solicita` varchar(500) NOT NULL,
  `descripcion_aprueba` varchar(500) NOT NULL,
  `descripcion_registra` varchar(500) NOT NULL,
  `ruta_solicita` varchar(500) NOT NULL,
  `ruta_aprueba` varchar(500) NOT NULL,
  `ruta_registra` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `idConfiguracion` int(11) NOT NULL,
  `limiteVacaciones` double DEFAULT NULL,
  `diasPorAño` double DEFAULT NULL,
  `diasAnuales` int(11) DEFAULT NULL,
  `numero` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`idConfiguracion`, `limiteVacaciones`, `diasPorAño`, `diasAnuales`, `numero`) VALUES
(1, 60, 15, 360, 1.36363636363636);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_trabajo`
--

CREATE TABLE `dias_trabajo` (
  `id_trabajo` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `dias_laborados` double NOT NULL,
  `horas_trabajadas` double DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_actual` date NOT NULL DEFAULT curdate(),
  `dias_totales_acu_user` double NOT NULL,
  `dias_totales_vac_user` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dias_trabajo`
--

INSERT INTO `dias_trabajo` (`id_trabajo`, `id_usuarios`, `dias_laborados`, `horas_trabajadas`, `fecha_inicio`, `fecha_actual`, `dias_totales_acu_user`, `dias_totales_vac_user`) VALUES
(92, 2, 33, 264, '2024-02-01', '2024-03-05', 0, 0);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `permisosregistrados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `permisosregistrados` (
`id_usuarios` int(11)
,`cedula` varchar(10)
,`nombres` varchar(200)
,`apellidos` varchar(200)
,`tiempo_trabajo` int(11)
,`id_registra` int(11)
,`ruta_registra` varchar(500)
,`id_permisos` int(11)
,`fecha_permiso` date
,`motivo_permiso` varchar(200)
,`permiso_aceptado` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_permisos`
--

CREATE TABLE `registros_permisos` (
  `id_permisos` int(11) NOT NULL,
  `id_usuarios` int(11) DEFAULT NULL,
  `provincia` varchar(250) DEFAULT NULL,
  `regimen` varchar(250) DEFAULT NULL,
  `coordinacion_zonal` varchar(250) DEFAULT NULL,
  `direccion_unidad` varchar(250) DEFAULT NULL,
  `fecha_permiso` date DEFAULT NULL,
  `observaciones` varchar(250) DEFAULT NULL,
  `motivo_permiso` varchar(200) DEFAULT NULL CHECK (cast(`motivo_permiso` as char charset binary) in ('LICENCIA_POR_CALAMIDAD_DOMESTICA','LICENCIA_POR_ENFERMEDAD','LICENCIA_POR_MATERNIDAD','LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO','LICENCIA_POR_PATERNIDAD','PERMISO_PARA_ESTUDIOS_REGULARES','PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES','PERMISO_POR_ASUNTOS_OFICIALES','PERMISO_POR_ASUNTOS_OFICIALES','PERMISO_PARA_ATENCION_MEDICA','OTROS')),
  `tiempo_motivo` varchar(400) DEFAULT NULL,
  `desc_motivo` varchar(950) DEFAULT NULL,
  `dias_solicitados` int(11) DEFAULT NULL,
  `dias_totales` double DEFAULT NULL,
  `horas_solicitadas` time DEFAULT NULL,
  `fecha_permisos_desde` date DEFAULT NULL,
  `fecha_permiso_hasta` date DEFAULT NULL,
  `horas_permiso_desde` time DEFAULT NULL,
  `horas_permiso_hasta` time DEFAULT NULL,
  `usuario_solicita` varchar(250) DEFAULT NULL,
  `usuario_aprueba` varchar(250) DEFAULT NULL,
  `usuario_registra` varchar(250) DEFAULT NULL,
  `permiso_aceptado` tinyint(1) DEFAULT NULL,
  `horas_ocupadas` double DEFAULT NULL,
  `motivo_rechazo` varchar(400) DEFAULT NULL,
  `ruta_solicita` varchar(500) DEFAULT NULL,
  `ruta_aprueba` varchar(500) DEFAULT NULL,
  `ruta_registra` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `usuario` varchar(250) NOT NULL,
  `contraseña` varchar(250) NOT NULL,
  `rol` varchar(400) NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `tiempo_trabajo` int(11) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `cedula`, `nombres`, `apellidos`, `email`, `usuario`, `contraseña`, `rol`, `fecha_ingreso`, `tiempo_trabajo`) VALUES
(1, '0250072444', 'Daniel', 'Sanchez', 'danielsanchez@gmail.com', 'admin', '$2y$10$mKv/GfYocdgXZOqftAXk2.np1Hi0dss6Adw9rSYy9fNSRyU/ynjai', 'admin', '0000-00-00', 0),
(2, '0250062444', 'Santiago Alexisis', 'Sanchez Orosco', 'user@gmail.com', 'user', '$2y$10$gh23jHGgYyhCJI7KeWmtWOsNkhfIb2SXFm7wXPOZYf2HSUn.mTq/u', 'Funcionario', '2024-02-01', 8),
(3, '0350072443', 'Sofia Andrea ', 'Ramirez Santana', 'jefe@gmail.com', 'jefe', '$2y$10$Ob581L2lXAjJuFHRRu4FF.oao16LNQE9oE7C8gydt/NCl1iN/OCqu', 'jefe', '2024-02-27', 0),
(4, '0242210510', 'Juan ', 'Perez', 'talento@gmail.com', 'talento', '$2y$10$y1iBKBisRToAOlYzQzroiO3S.DTA8zt.37ztjk/nD3gB.Lo5mFgXa', 'Talento_Humano', '0000-00-00', 0);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `before_delete_usuario` BEFORE DELETE ON `usuarios` FOR EACH ROW BEGIN
    IF OLD.id_usuarios = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar el primer usuario';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista1`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista1` (
`id_usuarios` int(11)
,`cedula` varchar(10)
,`nombres` varchar(200)
,`apellidos` varchar(200)
,`tiempo_trabajo` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista2`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista2` (
`id_usuarios` int(11)
,`cedula` varchar(10)
,`nombres` varchar(200)
,`apellidos` varchar(200)
,`tiempo_trabajo` int(11)
,`id_registra` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista3`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista3` (
`id_usuarios` int(11)
,`cedula` varchar(10)
,`nombres` varchar(200)
,`apellidos` varchar(200)
,`tiempo_trabajo` int(11)
,`id_aprueba` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `permisosregistrados`
--
DROP TABLE IF EXISTS `permisosregistrados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `permisosregistrados`  AS SELECT `usuarios`.`id_usuarios` AS `id_usuarios`, `usuarios`.`cedula` AS `cedula`, `usuarios`.`nombres` AS `nombres`, `usuarios`.`apellidos` AS `apellidos`, `usuarios`.`tiempo_trabajo` AS `tiempo_trabajo`, `archivos`.`id_registra` AS `id_registra`, `archivos`.`ruta_registra` AS `ruta_registra`, `registros_permisos`.`id_permisos` AS `id_permisos`, `registros_permisos`.`fecha_permiso` AS `fecha_permiso`, `registros_permisos`.`motivo_permiso` AS `motivo_permiso`, `registros_permisos`.`permiso_aceptado` AS `permiso_aceptado` FROM ((`usuarios` join `registros_permisos` on(`usuarios`.`id_usuarios` = `registros_permisos`.`id_usuarios`)) join `archivos` on(`registros_permisos`.`id_permisos` = `archivos`.`id_permiso`)) WHERE `usuarios`.`rol` = 'Funcionario' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista1`
--
DROP TABLE IF EXISTS `vista1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista1`  AS SELECT `usuarios`.`id_usuarios` AS `id_usuarios`, `usuarios`.`cedula` AS `cedula`, `usuarios`.`nombres` AS `nombres`, `usuarios`.`apellidos` AS `apellidos`, `usuarios`.`tiempo_trabajo` AS `tiempo_trabajo` FROM (`usuarios` join `registros_permisos` on(`usuarios`.`id_usuarios` = `registros_permisos`.`id_usuarios`)) WHERE `usuarios`.`rol` = 'Funcionario' GROUP BY `usuarios`.`id_usuarios`, `usuarios`.`cedula`, `usuarios`.`nombres`, `usuarios`.`apellidos`, `usuarios`.`tiempo_trabajo` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista2`
--
DROP TABLE IF EXISTS `vista2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista2`  AS SELECT `usuarios`.`id_usuarios` AS `id_usuarios`, `usuarios`.`cedula` AS `cedula`, `usuarios`.`nombres` AS `nombres`, `usuarios`.`apellidos` AS `apellidos`, `usuarios`.`tiempo_trabajo` AS `tiempo_trabajo`, `archivos`.`id_registra` AS `id_registra` FROM ((`usuarios` join `registros_permisos` on(`usuarios`.`id_usuarios` = `registros_permisos`.`id_usuarios`)) join `archivos` on(`registros_permisos`.`id_permisos` = `archivos`.`id_permiso`)) WHERE `usuarios`.`rol` = 'Funcionario' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista3`
--
DROP TABLE IF EXISTS `vista3`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista3`  AS SELECT `usuarios`.`id_usuarios` AS `id_usuarios`, `usuarios`.`cedula` AS `cedula`, `usuarios`.`nombres` AS `nombres`, `usuarios`.`apellidos` AS `apellidos`, `usuarios`.`tiempo_trabajo` AS `tiempo_trabajo`, `archivos`.`id_aprueba` AS `id_aprueba` FROM ((`usuarios` join `registros_permisos` on(`usuarios`.`id_usuarios` = `registros_permisos`.`id_usuarios`)) join `archivos` on(`registros_permisos`.`id_permisos` = `archivos`.`id_permiso`)) WHERE `usuarios`.`rol` = 'Funcionario' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`id_archivo`),
  ADD UNIQUE KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`idConfiguracion`);

--
-- Indices de la tabla `dias_trabajo`
--
ALTER TABLE `dias_trabajo`
  ADD PRIMARY KEY (`id_trabajo`),
  ADD UNIQUE KEY `unique_id_usuarios` (`id_usuarios`);

--
-- Indices de la tabla `registros_permisos`
--
ALTER TABLE `registros_permisos`
  ADD PRIMARY KEY (`id_permisos`),
  ADD KEY `registros_permisos_ibfk_1` (`id_usuarios`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos`
--
ALTER TABLE `archivos`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `idConfiguracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `dias_trabajo`
--
ALTER TABLE `dias_trabajo`
  MODIFY `id_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `registros_permisos`
--
ALTER TABLE `registros_permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD CONSTRAINT `fk_id_permisos` FOREIGN KEY (`id_permiso`) REFERENCES `registros_permisos` (`id_permisos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dias_trabajo`
--
ALTER TABLE `dias_trabajo`
  ADD CONSTRAINT `dias_trabajo_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registros_permisos`
--
ALTER TABLE `registros_permisos`
  ADD CONSTRAINT `registros_permisos_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
