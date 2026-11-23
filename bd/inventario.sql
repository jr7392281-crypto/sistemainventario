-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2026 a las 01:10:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_areas`
--

CREATE TABLE `tb_areas` (
  `id_area` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_areas`
--

INSERT INTO `tb_areas` (`id_area`, `nombre`, `fecha_registro`, `fecha_actualizacion`) VALUES
(6, 'sistemas', '2026-10-22 17:47:23', '2026-10-22 17:47:23'),
(7, 'obras', '2026-11-20 19:38:03', '2026-11-20 19:38:03'),
(8, 'contabilidad', '2026-11-22 18:32:37', '2026-11-22 18:32:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_componentes`
--

CREATE TABLE `tb_componentes` (
  `id_componente` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `tipo_componente` varchar(50) NOT NULL,
  `aplica_a` enum('Equipo','Impresora','Ambos') NOT NULL DEFAULT 'Ambos',
  `marca` varchar(80) DEFAULT NULL,
  `especificacion` varchar(100) DEFAULT NULL,
  `stock_actual` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `stock_minimo` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_componentes`
--

INSERT INTO `tb_componentes` (`id_componente`, `codigo`, `nombre`, `tipo_componente`, `aplica_a`, `marca`, `especificacion`, `stock_actual`, `stock_minimo`, `estado`, `fecha_registro`, `fecha_actualizacion`) VALUES
(10, 'Comp001', 'monitor', 'Accesorio', 'Equipo', 'HP', '19 pulgadas', 7, 10, 1, '2026-11-22 18:57:24', '2026-11-22 19:07:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_email_verification`
--

CREATE TABLE `tb_email_verification` (
  `id_verificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `fecha_expiracion` datetime NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_equipos`
--

CREATE TABLE `tb_equipos` (
  `id_equipo` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre_dispositivo` varchar(150) NOT NULL,
  `procesador` varchar(150) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `tarjeta_grafica` varchar(150) DEFAULT NULL,
  `sistema_operativo` varchar(100) DEFAULT NULL,
  `almacenamiento` varchar(150) DEFAULT NULL,
  `monitor` varchar(150) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `mouse` varchar(100) DEFAULT NULL,
  `teclado` varchar(100) DEFAULT NULL,
  `id_area` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Operativo',
  `observacion` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_equipos`
--

INSERT INTO `tb_equipos` (`id_equipo`, `codigo`, `nombre_dispositivo`, `procesador`, `ram`, `tarjeta_grafica`, `sistema_operativo`, `almacenamiento`, `monitor`, `ip`, `mouse`, `teclado`, `id_area`, `estado`, `observacion`, `fecha_registro`, `fecha_actualizacion`) VALUES
(3, 'EQ-0001', 'Todhiba', 'intelcorrei3', '8 GB', 'NVIDIA', 'Windows 10', '500 GB SSD', '19 pulgadas', '192.168.198', 'HP', 'Logitech', 7, 'Operativo', 'sdfghj', '2026-11-22 18:55:54', '2026-11-22 18:55:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_impresoras`
--

CREATE TABLE `tb_impresoras` (
  `id_impresora` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `fabricante` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `tipo_impresora` varchar(100) DEFAULT NULL,
  `tipo_impresion` varchar(100) DEFAULT NULL,
  `id_area` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Operativa',
  `observacion` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_impresoras`
--

INSERT INTO `tb_impresoras` (`id_impresora`, `codigo`, `fabricante`, `modelo`, `tipo_impresora`, `tipo_impresion`, `id_area`, `estado`, `observacion`, `fecha_registro`, `fecha_actualizacion`) VALUES
(8, 'IMP-0001', 'EPSON', 'Smart tank 790', 'Multifuncional', 'Tinta', 7, 'Operativa', 'wertgfdsd', '2026-11-22 18:56:39', '2026-11-22 18:56:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_mantenimientos`
--

CREATE TABLE `tb_mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `id_impresora` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico_asignado` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `tipo_mantenimiento` varchar(50) NOT NULL,
  `observacion` text NOT NULL,
  `solucion` text DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'reportado',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ;

--
-- Volcado de datos para la tabla `tb_mantenimientos`
--

INSERT INTO `tb_mantenimientos` (`id_mantenimiento`, `id_equipo`, `id_impresora`, `id_usuario`, `id_tecnico_asignado`, `fecha`, `tipo_mantenimiento`, `observacion`, `solucion`, `estado`, `fecha_registro`) VALUES
(9, 3, NULL, 23, 22, '2026-11-20', 'Correctivo', '<x<xax<axaxa<x<axax', 'sasczscszcszc', 'resuelto', '2026-11-22 19:06:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_movimientos_stock`
--

CREATE TABLE `tb_movimientos_stock` (
  `id_movimiento` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mantenimiento` int(11) DEFAULT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL,
  `observacion` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_movimientos_stock`
--

INSERT INTO `tb_movimientos_stock` (`id_movimiento`, `id_componente`, `id_usuario`, `id_mantenimiento`, `tipo`, `cantidad`, `observacion`, `fecha_registro`) VALUES
(2, 10, 22, 9, 'salida', 2, NULL, '2026-11-22 19:07:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_notificaciones`
--

CREATE TABLE `tb_notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario_destino` int(11) NOT NULL,
  `id_usuario_origen` int(11) DEFAULT NULL,
  `id_mantenimiento` int(11) DEFAULT NULL,
  `tipo` varchar(40) NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_lectura` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_notificaciones`
--

INSERT INTO `tb_notificaciones` (`id_notificacion`, `id_usuario_destino`, `id_usuario_origen`, `id_mantenimiento`, `tipo`, `titulo`, `mensaje`, `leida`, `fecha_registro`, `fecha_lectura`) VALUES
(16, 1, 23, 9, 'nuevo_mantenimiento_reportado', 'Nuevo reporte de mantenimiento', 'El personal registró una solicitud que requiere revisión.', 1, '2026-11-22 19:06:43', '2026-11-22 19:07:14'),
(17, 22, 1, 9, 'nuevo_mantenimiento', 'Nuevo mantenimiento', 'Tienes un nuevo mantenimiento pendiente de atención. Revisa sus detalles.', 0, '2026-11-22 19:07:07', NULL),
(18, 23, 22, 9, 'mantenimiento_aceptado', 'Solicitud de mantenimiento aceptada', 'Tu solicitud de mantenimiento ha sido aceptada por Soporte técnico y será atendida.', 1, '2026-11-22 19:07:38', '2026-11-22 19:08:57'),
(19, 23, 22, 9, 'mantenimiento_resuelto', 'Mantenimiento finalizado', 'Tu mantenimiento fue resuelto por Soporte técnico.', 1, '2026-11-22 19:08:00', '2026-11-22 19:08:58'),
(20, 1, 22, 9, 'mantenimiento_resuelto_admin', 'Mantenimiento finalizado', 'Soporte técnico ha finalizado un mantenimiento. Puedes revisar sus detalles.', 1, '2026-11-22 19:08:00', '2026-11-22 19:08:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_password_resets`
--

CREATE TABLE `tb_password_resets` (
  `id_reset` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `fecha_expiracion` datetime NOT NULL,
  `fecha_uso` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_roles`
--

CREATE TABLE `tb_roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_roles`
--

INSERT INTO `tb_roles` (`id_rol`, `rol`, `fecha_registro`, `fecha_actualizacion`) VALUES
(1, 'administrador', '2026-09-29 17:14:19', '2026-11-19 17:25:13'),
(3, 'personal', '2026-10-02 18:18:28', '2026-11-21 21:00:40'),
(4, 'encargado de inventario', '2026-11-18 21:38:13', '2026-11-18 21:38:13'),
(5, 'super administrador', '2026-11-20 01:25:44', '2026-11-20 01:25:44'),
(7, 'soporte tecnico', '2026-11-20 01:27:12', '2026-11-20 01:27:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_user` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_area` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `email_verificado_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombres`, `email`, `password_user`, `id_rol`, `id_area`, `fecha_registro`, `fecha_actualizacion`, `email_verificado`, `email_verificado_at`) VALUES
(1, 'jose', 'jr7392281@gmail.com', '$2y$10$9r4f9tOC7D0wOuLKOUNfDOonrEC0I4khbvKL55/9zCeALJoiWFFzi', 5, 6, '2026-09-29 18:05:55', '2026-11-22 19:01:06', 1, NULL),
(21, 'uriel', 'los022573@gmail.com', '$2y$10$F5J6dHz76ws5J4okKEZPTu2Xv0zdQOYj.m/Xpss2uLTZ47rTaS6oy', 4, 6, '2026-11-22 18:39:10', '2026-11-22 18:42:38', 1, '2026-11-22 18:42:38'),
(22, 'sadasd', 'jotiro949@gmail.com', '$2y$10$LNZBvGj5RCBjcGsLpNGk1eS/cd6J2hIzi.q9/IvQ9F3Mx4acmAvKW', 7, 6, '2026-11-22 18:59:15', '2026-11-22 19:02:04', 1, '2026-11-22 19:02:04'),
(23, 'weadsd', 'jrr50419@gmail.com', '$2y$10$SNxpJLPkHQVVT3h3vdXaTuqaUZvdUuutYq2vRQa/k59EdPKIkxj4K', 3, 6, '2026-11-22 19:04:31', '2026-11-22 19:05:03', 1, '2026-11-22 19:05:03');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_areas`
--
ALTER TABLE `tb_areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `tb_componentes`
--
ALTER TABLE `tb_componentes`
  ADD PRIMARY KEY (`id_componente`),
  ADD UNIQUE KEY `uq_componentes_codigo` (`codigo`);

--
-- Indices de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD PRIMARY KEY (`id_verificacion`),
  ADD UNIQUE KEY `uq_email_verification_token` (`token_hash`),
  ADD UNIQUE KEY `uq_email_verification_usuario` (`id_usuario`);

--
-- Indices de la tabla `tb_equipos`
--
ALTER TABLE `tb_equipos`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `fk_equipos_areas` (`id_area`);

--
-- Indices de la tabla `tb_impresoras`
--
ALTER TABLE `tb_impresoras`
  ADD PRIMARY KEY (`id_impresora`),
  ADD KEY `fk_impresoras_areas` (`id_area`);

--
-- Indices de la tabla `tb_mantenimientos`
--
ALTER TABLE `tb_mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `fk_mantenimientos_equipos` (`id_equipo`),
  ADD KEY `fk_mantenimientos_impresoras` (`id_impresora`),
  ADD KEY `fk_mantenimiento_usuario` (`id_usuario`),
  ADD KEY `fk_mantenimientos_tecnico` (`id_tecnico_asignado`);

--
-- Indices de la tabla `tb_movimientos_stock`
--
ALTER TABLE `tb_movimientos_stock`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_componente` (`id_componente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mantenimiento` (`id_mantenimiento`);

--
-- Indices de la tabla `tb_notificaciones`
--
ALTER TABLE `tb_notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `idx_notificaciones_destino_leida_fecha` (`id_usuario_destino`,`leida`,`fecha_registro`),
  ADD KEY `idx_notificaciones_mantenimiento` (`id_mantenimiento`),
  ADD KEY `fk_notificaciones_origen` (`id_usuario_origen`);

--
-- Indices de la tabla `tb_password_resets`
--
ALTER TABLE `tb_password_resets`
  ADD PRIMARY KEY (`id_reset`),
  ADD KEY `fk_password_reset_usuario` (`id_usuario`);

--
-- Indices de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_roles` (`id_rol`),
  ADD KEY `fk_usuario_area` (`id_area`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_areas`
--
ALTER TABLE `tb_areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_componentes`
--
ALTER TABLE `tb_componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  MODIFY `id_verificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_equipos`
--
ALTER TABLE `tb_equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_impresoras`
--
ALTER TABLE `tb_impresoras`
  MODIFY `id_impresora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_mantenimientos`
--
ALTER TABLE `tb_mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_movimientos_stock`
--
ALTER TABLE `tb_movimientos_stock`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tb_notificaciones`
--
ALTER TABLE `tb_notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `tb_password_resets`
--
ALTER TABLE `tb_password_resets`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD CONSTRAINT `fk_email_verification_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_equipos`
--
ALTER TABLE `tb_equipos`
  ADD CONSTRAINT `fk_equipos_areas` FOREIGN KEY (`id_area`) REFERENCES `tb_areas` (`id_area`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_impresoras`
--
ALTER TABLE `tb_impresoras`
  ADD CONSTRAINT `fk_impresoras_areas` FOREIGN KEY (`id_area`) REFERENCES `tb_areas` (`id_area`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_mantenimientos`
--
ALTER TABLE `tb_mantenimientos`
  ADD CONSTRAINT `fk_mantenimiento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mantenimientos_equipos` FOREIGN KEY (`id_equipo`) REFERENCES `tb_equipos` (`id_equipo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mantenimientos_impresoras` FOREIGN KEY (`id_impresora`) REFERENCES `tb_impresoras` (`id_impresora`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mantenimientos_tecnico` FOREIGN KEY (`id_tecnico_asignado`) REFERENCES `tb_usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_movimientos_stock`
--
ALTER TABLE `tb_movimientos_stock`
  ADD CONSTRAINT `tb_movimientos_stock_ibfk_1` FOREIGN KEY (`id_componente`) REFERENCES `tb_componentes` (`id_componente`),
  ADD CONSTRAINT `tb_movimientos_stock_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`),
  ADD CONSTRAINT `tb_movimientos_stock_ibfk_3` FOREIGN KEY (`id_mantenimiento`) REFERENCES `tb_mantenimientos` (`id_mantenimiento`);

--
-- Filtros para la tabla `tb_notificaciones`
--
ALTER TABLE `tb_notificaciones`
  ADD CONSTRAINT `fk_notificaciones_destino` FOREIGN KEY (`id_usuario_destino`) REFERENCES `tb_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificaciones_mantenimiento` FOREIGN KEY (`id_mantenimiento`) REFERENCES `tb_mantenimientos` (`id_mantenimiento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificaciones_origen` FOREIGN KEY (`id_usuario_origen`) REFERENCES `tb_usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_password_resets`
--
ALTER TABLE `tb_password_resets`
  ADD CONSTRAINT `fk_password_reset_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `fk_usuario_area` FOREIGN KEY (`id_area`) REFERENCES `tb_areas` (`id_area`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
