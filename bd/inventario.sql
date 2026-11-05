-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2026 a las 15:03:33
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
(1, 'tesoreria', '2026-09-29 17:31:21', '2026-10-02 19:48:42'),
(2, 'administracion', '2026-10-02 18:18:09', '2026-10-03 18:36:37'),
(3, 'contabilidad', '2026-10-02 18:23:03', '2026-10-02 19:28:27'),
(4, 'logistica', '2026-10-02 18:38:47', '2026-10-02 19:28:27'),
(5, 'biblioteca', '2026-10-02 18:39:11', '2026-10-02 19:28:27'),
(6, 'sistemas', '2026-10-22 17:47:23', '2026-10-22 17:47:23');

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
(1, 'Muni-Pc001', 'PC PRO', 'intel', '16 GB', 'NVIDIA', 'Windows 10', '512 GB SSD', '24 pulgadas', '192.198.155', 'logitech', 'logitech', 1, 'Mantenimiento', '', '2026-10-06 16:55:12', '2026-10-10 17:27:26');

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
(6, 'Muni-imp001', 'EPSON', 'Smart tank  790', 'Multifuncional', 'tinta', 1, 'Operativa', '', '2026-10-06 18:32:05', '2026-10-14 19:39:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_mantenimientos`
--

CREATE TABLE `tb_mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `id_impresora` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_mantenimiento` varchar(50) NOT NULL,
  `observacion` text NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ;

--
-- Volcado de datos para la tabla `tb_mantenimientos`
--

INSERT INTO `tb_mantenimientos` (`id_mantenimiento`, `id_equipo`, `id_impresora`, `id_usuario`, `fecha`, `tipo_mantenimiento`, `observacion`, `fecha_registro`) VALUES
(2, 1, NULL, 1, '2026-10-09', 'Preventivo', 'no enciende', '2026-10-09 17:42:11'),
(3, NULL, 6, 1, '2026-10-09', 'Correctivo', 'se rompio el escaner', '2026-10-09 18:03:19'),
(4, NULL, 6, 1, '2026-10-09', 'Correctivo', 'se apaga sola', '2026-10-09 18:21:37'),
(5, 1, NULL, 1, '2026-10-09', 'Correctivo', 'se apaga sola', '2026-10-09 18:22:08');

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

--
-- Volcado de datos para la tabla `tb_password_resets`
--

INSERT INTO `tb_password_resets` (`id_reset`, `id_usuario`, `token_hash`, `fecha_expiracion`, `fecha_uso`, `fecha_registro`) VALUES
(4, 1, '813eb57869e43ca99cb9112ef3bbe7e78ab3df473569a8a3ed91fc1ff8e5e3f3', '2026-10-16 19:44:48', '2026-10-16 18:46:01', '2026-10-16 18:44:48'),
(5, 1, 'd8ba194f4b89b66cb22af69292584ed1049db34177840270a14af19d452dd074', '2026-10-16 19:46:01', '2026-10-16 18:58:52', '2026-10-16 18:46:01'),
(6, 1, 'd253e16a4bac305642ee50d8f9b9f4b7e2a4552b2bdf82bd4173b8679f7f7fe9', '2026-10-16 19:58:52', '2026-10-16 18:59:42', '2026-10-16 18:58:52'),
(7, 1, 'b71dfb6f8b37087fb637df002d0cac0b491ca42694d13d885271654d6ac7637c', '2026-10-16 19:59:42', '2026-10-16 19:05:24', '2026-10-16 18:59:42'),
(8, 1, 'b043f931c2df9879afc7ec341e64f3ca8a0011058553745c0da4c43474f1efa5', '2026-10-16 20:05:24', '2026-10-16 19:11:53', '2026-10-16 19:05:24'),
(9, 1, '154b17eb9852ec5f1a669ef21e12658f92e3546d8aceab89cee91298b7202ce4', '2026-10-16 20:11:53', '2026-10-16 19:13:02', '2026-10-16 19:11:53'),
(10, 1, '838efcf3c471c68a30bcc7f8850cdb3964a1175f835b0527ef488aac3da005db', '2026-10-16 20:13:02', NULL, '2026-10-16 19:13:02');

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
(1, 'administrador', '2026-09-29 17:14:19', '2026-09-29 17:14:19'),
(3, 'usuario', '2026-10-02 18:18:28', '2026-10-24 22:39:29');

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
(1, 'jose', 'jr7392281@gmail.com', '$2y$10$9r4f9tOC7D0wOuLKOUNfDOonrEC0I4khbvKL55/9zCeALJoiWFFzi', 1, 6, '2026-09-29 18:05:55', '2026-10-22 17:47:35'),
(7, 'raul', 'raul@gmail.com', '$2y$10$fvVk2CiA0DL1CvfHgBuLUuS74lb0h2ERBloqdTA7PAtsWI9oVzBWy', 3, 1, '2026-10-22 17:49:10', '2026-10-22 17:49:10'),
(8, 'Juana', 'Jun@gmail.com', '$2y$10$K21o/TxrhMXsT/uHFsIZaupCWvPKEK9vF5OUZigKnJrfTbArnkzUK', 3, 5, '2026-10-24 22:40:04', '2026-10-24 22:40:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_areas`
--
ALTER TABLE `tb_areas`
  ADD PRIMARY KEY (`id_area`);

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
  ADD KEY `fk_mantenimiento_usuario` (`id_usuario`);

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
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_equipos`
--
ALTER TABLE `tb_equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_impresoras`
--
ALTER TABLE `tb_impresoras`
  MODIFY `id_impresora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_mantenimientos`
--
ALTER TABLE `tb_mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_password_resets`
--
ALTER TABLE `tb_password_resets`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `fk_mantenimientos_impresoras` FOREIGN KEY (`id_impresora`) REFERENCES `tb_impresoras` (`id_impresora`) ON UPDATE CASCADE;

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
