-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-10-2024 a las 20:16:32
-- Versión del servidor: 10.6.18-MariaDB-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `DB_huk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accessos`
--

CREATE TABLE `accessos` (
  `id` bigint(20) NOT NULL,
  `vehicle_id` bigint(20) DEFAULT NULL,
  `parquing_id` bigint(20) DEFAULT NULL,
  `data_entrada` datetime NOT NULL,
  `data_sortida` datetime DEFAULT NULL,
  `estat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text DEFAULT NULL,
  `correo_electronico` text NOT NULL,
  `telefono` text DEFAULT NULL,
  `edad` varchar(255) NOT NULL,
  `direccion` text DEFAULT NULL,
  `contraseña` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `correo_electronico`, `telefono`, `edad`, `direccion`, `contraseña`) VALUES
(11, 'Kevin', 'Cardenas', 'kcardenas1@gmail.com', '698954212', '28', 'Pere Martell', '$2y$10$9Uvq//hVcivHFBjoCiiMM.1ywMZVmrWoRyGjMuVoOaAxSDEXRbEBK'),
(12, 'Unai', 'Oliver', 'uolivermillan@gmail.com', '611468448', '22', 'El Vendrell, Muertos', '$2y$10$yUa0tE878rKqkSmFpehf8uiKTwe2pyYZfSn4RVnzlsvc1lVzkTs7W'),
(14, 'Laura', 'Codina', 'lcodina4@xtec.cat', '655555555', '20', 'Valencia', '$2y$10$Fld6Ezwbn/xo7hHkXKXoQ.UuXpE9pmx2vY3jsocNK97W2.Hc9qx5q'),
(15, 'Admin', 'Admin', 'admin@huking.com', '555555555', '100', 'Vidal i Barraquer', '$2y$10$t8EY0etgs3qnEplRauyNPuODKTrS9RrYAq0f32DZYbGPKKEbKxiZq'),
(16, 'Mister', 'Cagón', 'cagon@gmail.com', '611527545', '23', 'El Vendrell, Cocaina', '$2y$10$iSffTFv.xXCxZurT0cabr.t0S15gai.5Nov.ellmYtw8lV61bmrfW'),
(17, 'Oriol', 'Martínez', 'omartinez@gmail.com', '611983475', '20', 'El Vendrell', '$2y$10$ZN6nmViI1gVRgSLwDerY/OJBSDL3ONuYAJ6fTkrkheYUeICkQ9Ida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `identificadors`
--

CREATE TABLE `identificadors` (
  `id` bigint(20) NOT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `vehicle_id` bigint(20) DEFAULT NULL,
  `parquing_id` bigint(20) DEFAULT NULL,
  `reserva_id` bigint(20) DEFAULT NULL,
  `place_id` bigint(20) DEFAULT NULL,
  `access_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parquings`
--

CREATE TABLE `parquings` (
  `id` bigint(20) NOT NULL,
  `nom` text NOT NULL,
  `ubicacio` text NOT NULL,
  `places_disponibles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parquings`
--

INSERT INTO `parquings` (`id`, `nom`, `ubicacio`, `places_disponibles`) VALUES
(1, 'Sata', 'Pere Martell', 10),
(2, 'GreenPark', 'valve', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `places`
--

CREATE TABLE `places` (
  `id` bigint(20) NOT NULL,
  `parquing_id` bigint(20) DEFAULT NULL,
  `numero_plaza` text NOT NULL,
  `estat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `places`
--

INSERT INTO `places` (`id`, `parquing_id`, `numero_plaza`, `estat`) VALUES
(1, 1, '1', 'Libre'),
(2, 1, '2', 'Libre'),
(3, 1, '3', 'Libre'),
(4, 1, '4', 'Libre'),
(5, 1, '5', 'Libre'),
(6, 1, '6', 'Libre'),
(7, 1, '7', 'Libre'),
(8, 1, '8', 'Libre'),
(9, 1, '9', 'Libre'),
(10, 1, '10', 'Libre'),
(11, 2, '1', 'Libre'),
(12, 2, '2', 'Libre'),
(13, 2, '3', 'Libre'),
(14, 2, '4', 'Libre'),
(15, 2, '5', 'Libre'),
(16, 2, '6', 'Libre'),
(17, 2, '7', 'Libre'),
(18, 2, '8', 'Libre'),
(19, 2, '9', 'Libre'),
(20, 2, '10', 'Libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` bigint(20) NOT NULL,
  `client_id` bigint(20) DEFAULT NULL,
  `vehicle_id` bigint(20) DEFAULT NULL,
  `parquing_id` bigint(20) DEFAULT NULL,
  `data_entrada` datetime NOT NULL,
  `data_sortida` datetime NOT NULL,
  `estat` text NOT NULL,
  `preu` decimal(10,2) NOT NULL,
  `numero_reserva` text NOT NULL,
  `place_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `client_id`, `vehicle_id`, `parquing_id`, `data_entrada`, `data_sortida`, `estat`, `preu`, `numero_reserva`, `place_id`) VALUES
(33, 12, NULL, 2, '2024-11-15 12:39:00', '2024-11-16 00:39:00', 'Ocupado', '27.20', 'RGt47205798', 13),
(37, 11, 47, 1, '2024-10-25 01:10:00', '2024-10-25 20:10:00', 'Ocupado', '43.30', 'CYL53090559', 5),
(38, 11, 46, 1, '2024-10-30 01:10:00', '2024-10-30 06:11:00', 'Ocupado', '13.40', 'SCr12765426', 5),
(39, 11, 51, 1, '2024-10-22 04:41:00', '2024-10-22 11:42:00', 'Ocupado', '18.00', 'fzQ32446296', 8),
(40, 11, 47, 2, '2024-10-20 01:42:00', '2024-10-23 01:42:00', 'Ocupado', '165.20', 'lAt42698948', 15),
(41, 11, 45, 1, '2024-10-02 02:05:00', '2024-10-03 02:05:00', 'Ocupado', '54.80', 'PgA38594164', 5),
(42, 11, 45, 2, '2024-10-26 02:05:00', '2024-10-30 02:05:00', 'Ocupado', '222.70', 'FVL41889044', 18),
(43, 11, 45, 2, '2025-01-01 00:06:00', '2025-01-01 02:05:00', 'Ocupado', '4.20', 'XzB46271562', 14),
(44, 11, 47, 2, '2024-10-11 02:06:00', '2024-10-13 02:06:00', 'Ocupado', '110.00', 'Yfk96209513', 15),
(45, 11, 46, 2, '2024-10-18 02:06:00', '2024-10-27 02:06:00', 'Ocupado', '498.70', 'GQY67129845', 17),
(46, 11, 51, 1, '2024-10-19 02:07:00', '2024-10-27 02:07:00', 'Ocupado', '443.50', 'Eqv35338101', 5),
(47, 11, 45, 1, '2024-10-25 02:07:00', '2024-10-27 02:07:00', 'Ocupado', '112.30', 'Bgf01271223', 4),
(49, 11, 46, 2, '2024-10-01 03:33:00', '2024-10-01 06:33:00', 'Ocupado', '6.50', 'wwz45053170', 17),
(50, 12, 60, 1, '2024-10-20 17:22:00', '2024-10-20 21:22:00', 'Ocupado', '8.80', 'pzc68633049', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) NOT NULL,
  `matricula` text NOT NULL,
  `marca` text NOT NULL,
  `modelo` text NOT NULL,
  `client_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehicles`
--

INSERT INTO `vehicles` (`id`, `matricula`, `marca`, `modelo`, `client_id`) VALUES
(36, '2688ETS', 'Peugeot', '308', 14),
(45, '4364RBS', 'BMW', 'M5', 11),
(46, '3453UTR', 'Toyota', 'Maruja', 11),
(47, '9567HDJ', 'Chevrolet', 'Corvette C8', 11),
(51, '6295YTG', 'Honda', 'Civic TYPE-R (1994)', 11),
(55, '9723SGD', 'Chevrolet', 'Impreza TONTO', 12),
(56, '0293SJN', 'Kia', 'Stinger', 12),
(57, '7230QFG', 'Honda', 'Integra', 12),
(58, '7853JCA', 'Changan', 'Deel', 11),
(59, '5323PSD', 'BMW', 'M7', 14),
(60, '7777CLY', 'Chevrolet', 'GAY', 12);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accessos`
--
ALTER TABLE `accessos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `parquing_id` (`parquing_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `identificadors`
--
ALTER TABLE `identificadors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `parquing_id` (`parquing_id`),
  ADD KEY `reserva_id` (`reserva_id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `access_id` (`access_id`);

--
-- Indices de la tabla `parquings`
--
ALTER TABLE `parquings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parquing_id` (`parquing_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `parquing_id` (`parquing_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indices de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accessos`
--
ALTER TABLE `accessos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `identificadors`
--
ALTER TABLE `identificadors`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parquings`
--
ALTER TABLE `parquings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `places`
--
ALTER TABLE `places`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accessos`
--
ALTER TABLE `accessos`
  ADD CONSTRAINT `accessos_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `accessos_ibfk_2` FOREIGN KEY (`parquing_id`) REFERENCES `parquings` (`id`);

--
-- Filtros para la tabla `identificadors`
--
ALTER TABLE `identificadors`
  ADD CONSTRAINT `identificadors_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `identificadors_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `identificadors_ibfk_3` FOREIGN KEY (`parquing_id`) REFERENCES `parquings` (`id`),
  ADD CONSTRAINT `identificadors_ibfk_4` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`),
  ADD CONSTRAINT `identificadors_ibfk_5` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`),
  ADD CONSTRAINT `identificadors_ibfk_6` FOREIGN KEY (`access_id`) REFERENCES `accessos` (`id`);

--
-- Filtros para la tabla `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`parquing_id`) REFERENCES `parquings` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`parquing_id`) REFERENCES `parquings` (`id`),
  ADD CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`);

--
-- Filtros para la tabla `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
