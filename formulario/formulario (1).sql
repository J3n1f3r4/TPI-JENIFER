-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-02-2025 a las 19:51:17
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
-- Base de datos: `formulario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `DNI` int(8) NOT NULL,
  `curso` varchar(10) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id`, `nombre`, `email`, `contraseña`, `DNI`, `curso`, `rol`) VALUES
(7, 'Jenifer', 'jeniferaltamirano54@gmail.com', '1234567', 46971413, '-', 'admin'),
(10, 'Ariel', 'ariel23@gmail.com', '1234567', 46971411, '7mo 1ra', 'alumno'),
(11, 'Joaquin', 'joaalta1234@gmail.com', '1234567', 46980566, '4to 4ta', 'alumno'),
(12, 'Jesica', 'jesica123@gmail.com', '1234567', 32683969, '-', 'profesor'),
(13, 'Natalia Espinoza', 'natu867@gmail.com', '1234567', 0, '7mo 3ra', 'alumno'),
(14, 'debora', 'debo123@gmail.com', '1234567', 0, '5to 2da', 'alumno'),
(15, 'veronica', 'vero123@gmail.com', '1234567', 0, '-', 'profesor'),
(16, 'lican', 'lican123@gmail.com', '1234567', 0, '7mo 2ra', 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_10`
--

CREATE TABLE `notas_10` (
  `id` int(11) NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `nota01` int(11) DEFAULT NULL,
  `nota02` int(11) DEFAULT NULL,
  `notaC1` int(11) DEFAULT NULL,
  `nota1` int(11) DEFAULT NULL,
  `nota2` int(11) DEFAULT NULL,
  `notaC2` int(11) DEFAULT NULL,
  `notaF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_10`
--

INSERT INTO `notas_10` (`id`, `materia`, `nota01`, `nota02`, `notaC1`, `nota1`, `nota2`, `notaC2`, `notaF`) VALUES
(2, 'Programacion', 9, 8, 8, 6, 4, 6, 6),
(3, 'Redes', 9, 8, 8, 7, 8, 8, 8),
(9, 'Ingles', 8, 8, 8, 9, 9, 9, 9),
(10, 'Historia', 9, 10, 10, 10, 9, 9, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_11`
--

CREATE TABLE `notas_11` (
  `id` int(11) NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `nota01` int(11) DEFAULT NULL,
  `nota02` int(11) DEFAULT NULL,
  `notaC1` int(11) DEFAULT NULL,
  `nota1` int(11) DEFAULT NULL,
  `nota2` int(11) DEFAULT NULL,
  `notaC2` int(11) DEFAULT NULL,
  `notaF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_11`
--

INSERT INTO `notas_11` (`id`, `materia`, `nota01`, `nota02`, `notaC1`, `nota1`, `nota2`, `notaC2`, `notaF`) VALUES
(1, 'Matemáticas', 8, 8, 7, 7, 9, 8, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_13`
--

CREATE TABLE `notas_13` (
  `id` int(11) NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `nota01` int(11) DEFAULT NULL,
  `nota02` int(11) DEFAULT NULL,
  `notaC1` int(11) DEFAULT NULL,
  `nota1` int(11) DEFAULT NULL,
  `nota2` int(11) DEFAULT NULL,
  `notaC2` int(11) DEFAULT NULL,
  `notaF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_13`
--

INSERT INTO `notas_13` (`id`, `materia`, `nota01`, `nota02`, `notaC1`, `nota1`, `nota2`, `notaC2`, `notaF`) VALUES
(2, 'Redes', 9, 9, 9, 9, 9, 9, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_14`
--

CREATE TABLE `notas_14` (
  `id` int(11) NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `nota01` int(11) DEFAULT NULL,
  `nota02` int(11) DEFAULT NULL,
  `notaC1` int(11) DEFAULT NULL,
  `nota1` int(11) DEFAULT NULL,
  `nota2` int(11) DEFAULT NULL,
  `notaC2` int(11) DEFAULT NULL,
  `notaF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_16`
--

CREATE TABLE `notas_16` (
  `id` int(11) NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `nota01` int(11) DEFAULT NULL,
  `nota02` int(11) DEFAULT NULL,
  `notaC1` int(11) DEFAULT NULL,
  `nota1` int(11) DEFAULT NULL,
  `nota2` int(11) DEFAULT NULL,
  `notaC2` int(11) DEFAULT NULL,
  `notaF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_16`
--

INSERT INTO `notas_16` (`id`, `materia`, `nota01`, `nota02`, `notaC1`, `nota1`, `nota2`, `notaC2`, `notaF`) VALUES
(1, 'Matemáticas', 9, 10, 10, 10, 9, 8, 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_10`
--
ALTER TABLE `notas_10`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_11`
--
ALTER TABLE `notas_11`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_13`
--
ALTER TABLE `notas_13`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_14`
--
ALTER TABLE `notas_14`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_16`
--
ALTER TABLE `notas_16`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `datos`
--
ALTER TABLE `datos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `notas_10`
--
ALTER TABLE `notas_10`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notas_11`
--
ALTER TABLE `notas_11`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `notas_13`
--
ALTER TABLE `notas_13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notas_14`
--
ALTER TABLE `notas_14`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas_16`
--
ALTER TABLE `notas_16`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
