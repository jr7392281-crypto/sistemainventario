-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2026 a las 21:31:55
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
(7, 'obras', '2026-11-20 19:38:03', '2026-11-20 19:38:03');

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
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombres`, `email`, `password_user`, `id_rol`, `id_area`, `fecha_registro`, `fecha_actualizacion`) VALUES
(1, 'jose', 'jr7392281@gmail.com', '$2y$10$9r4f9tOC7D0wOuLKOUNfDOonrEC0I4khbvKL55/9zCeALJoiWFFzi', 1, 6, '2026-09-29 18:05:55', '2026-10-22 17:47:35');

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
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tb_componentes`
--
ALTER TABLE `tb_componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tb_equipos`
--
ALTER TABLE `tb_equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tb_impresoras`
--
ALTER TABLE `tb_impresoras`
  MODIFY `id_impresora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tb_mantenimientos`
--
ALTER TABLE `tb_mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_movimientos_stock`
--
ALTER TABLE `tb_movimientos_stock`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_notificaciones`
--
ALTER TABLE `tb_notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

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
