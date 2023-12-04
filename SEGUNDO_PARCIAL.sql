-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-12-2023 a las 17:31:27
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
(684431, 'Nick', 'Mason', 'DNI', '33456789', '$2y$10$k0AWZGm/WyPMneVrfS7Kb.Q520aB/o4yPdM9WedlDXnziqPqk4uY2', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 19:30:57', NULL, './ImagenesDeClientes/684431CO.png'),
(684432, 'Richard', 'Write', 'DNI', '33456789', '$2y$10$J5hs2f9EtVObdQ2g0w0mG.R153Z0c67rli2HnJYAZ0mSHrUd.xU1u', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 19:32:07', NULL, './ImagenesDeClientes/684432CO.png'),
(684433, 'Roger', 'Waters', 'DNI', '33456789', '$2y$10$9FrS4nFItcvCsXYe/naskephGj3BP9W6bBVozi5fAoIjwyQWfr.dO', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 19:33:36', NULL, './ImagenesDeClientes/684433CO.png'),
(684434, 'David', 'Gilmore', 'DNI', '33456789', '$2y$10$xEGSOIwc08j9cp/RUNjI6eWsBmeG2aFWOpWXbaRjiLwOitKLqYSIG', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 19:34:46', NULL, './ImagenesDeClientes/684434CO.png'),
(684457, 'Moe', 'Howard', 'DNI', '33456789', '$2y$10$ywNqnbnmZAld3BTEE9gaqepg1trWdhHEwBn5UpyRV9MHY1dauugyq', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 21:10:57', NULL, './ImagenesDeClientes/684457CO.png'),
(684458, 'Curly', 'Howard', 'DNI', '33456789', '$2y$10$J79OIeiyQaAJ5xXayTpjTuKsqFYFBohtztK1MSsqYybzWMil41GHK', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 21:11:19', NULL, './ImagenesDeClientes/684458CO.png'),
(684459, 'Shemp', 'Howard', 'DNI', '33456789', '$2y$10$stGYNk8sQjg7WasqdCmdyubI1QmN5kwcf90jt7evf9CKEOBzEqAOq', 'Burzaco', 'Wilde', 'correo@correo.com', 3115426, 'CORPO-DNI', 'Activo', '2023-12-02 21:13:22', NULL, './ImagenesDeClientes/684459CO.png');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `ID` int(11) NOT NULL,
  `User` varchar(50) NOT NULL,
  `Pass` varchar(256) NOT NULL,
  `Rol` varchar(50) NOT NULL,
  `Alta` datetime NOT NULL,
  `Baja` datetime DEFAULT NULL,
  `Estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`ID`, `User`, `Pass`, `Rol`, `Alta`, `Baja`, `Estado`) VALUES
(1, 'eljefe01', '$2y$10$2M5VFJsaKaEN.eFM8rAc6.1/JBxziHjZGxDMd4Ov3VG.HJQxU0bia', 'Gerente', '2023-12-02 16:05:14', NULL, 'Activo'),
(2, 'recep01', '$2y$10$jC1qZXRLe1WydBvcS5THJe9hhA4RjjB4LKWnULoJTJMLAw/a4QVtS', 'Recepcionista', '2023-12-02 16:21:46', NULL, 'Activo');

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
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=684460;

--
-- AUTO_INCREMENT de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58662;

--
-- AUTO_INCREMENT de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
