-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 27-11-2023 a las 03:29:32
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `SEGUNDO_PARCIAL`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente`
--

CREATE TABLE `Cliente` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `TipoDocumento` varchar(50) NOT NULL,
  `NroDocumento` varchar(50) NOT NULL,
  `Clave` varchar(256) NOT NULL,
  `Pais` varchar(50) NOT NULL,
  `Ciudad` varchar(80) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Telefono` int(11) NOT NULL,
  `TipoCliente` varchar(50) NOT NULL,
  `Estado` varchar(50) NOT NULL,
  `Alta` datetime NOT NULL,
  `Baja` datetime DEFAULT NULL,
  `ImagenURL` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Cliente`
--

INSERT INTO `Cliente` (`ID`, `Nombre`, `Apellido`, `TipoDocumento`, `NroDocumento`, `Clave`, `Pais`, `Ciudad`, `Email`, `Telefono`, `TipoCliente`, `Estado`, `Alta`, `Baja`, `ImagenURL`) VALUES
(684412, 'Juan Carlos', 'Batman', 'Pasaporte', '25654123', '$2y$10$VVo/.myHY5K6cqtVY1lCEe6VCBUSggeEMywTJrtYK7bJrtju.Iy3u', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-PASAPORTE', 'Inactivo', '2023-11-27 00:35:38', '2023-11-27 01:17:54', './ImagenesDeClientes/684412CO.jpeg'),
(684418, 'Max', 'Payne', 'DNI', '33456789', '$2y$10$jR/xLPkzlMnN9nXdpngyBeVx4k5CKcjmdRMmXg0Cjq01.2X0RQmm2', 'EEUU', 'New York', 'mail@mail.com', 5556013, 'CORPO-DNI', 'Inactivo', '2023-11-27 00:45:29', '2023-11-27 01:15:09', './ImagenesDeClientes/684418CO.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva`
--

CREATE TABLE `Reserva` (
  `ID` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `TipoCliente` varchar(50) NOT NULL,
  `TipoHabitacion` varchar(50) NOT NULL,
  `Ingreso` date NOT NULL,
  `Salida` date NOT NULL,
  `ImporteTotal` float NOT NULL,
  `Pago` varchar(50) NOT NULL,
  `FechaAlta` date NOT NULL,
  `FechaBaja` date DEFAULT NULL,
  `Estado` varchar(50) NOT NULL,
  `Comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Reserva`
--

INSERT INTO `Reserva` (`ID`, `ID_Cliente`, `TipoCliente`, `TipoHabitacion`, `Ingreso`, `Salida`, `ImporteTotal`, `Pago`, `FechaAlta`, `FechaBaja`, `Estado`, `Comentario`) VALUES
(58656, 684418, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Efectivo', '2023-11-27', '2023-11-27', 'Cancelado', NULL),
(58657, 684418, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Efectivo', '2023-11-27', '2023-11-27', 'Cancelado', NULL),
(58658, 684418, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Efectivo', '2023-11-27', '2023-11-27', 'Cancelado', NULL),
(58659, 684412, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Efectivo', '2023-11-27', '2023-11-27', 'Cancelado', NULL),
(58660, 684412, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Efectivo', '2023-11-27', '2023-11-27', 'Cancelado', NULL),
(58661, 684412, 'Corporativo', 'Doble', '2023-11-28', '2023-12-10', 3600, 'Guita del estanciero', '2023-11-27', '2023-11-27', 'Activo', 'Cambio en la forma de pago');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=684419;

--
-- AUTO_INCREMENT de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58662;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
