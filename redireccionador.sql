-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 01-09-2022 a las 20:58:44
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `redireccionador`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejemplo`
--

DROP TABLE IF EXISTS `ejemplo`;
CREATE TABLE IF NOT EXISTS `ejemplo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(16) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ejemplo`
--

INSERT INTO `ejemplo` (`id`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `estado`) VALUES
(1, 'walter', 'cruzado', '12345678', 'jr pablo ', '123456789', 0),
(2, 'edwin', 'carranza', '87654321', 'margaritas', '987654321', 0),
(3, 'walter', 'cruzado', '12345678', 'jr pablo ', '123456789', 0),
(4, 'edwin', 'carranza', '87654321', 'margaritas', '987654321', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `urls`
--

DROP TABLE IF EXISTS `urls`;
CREATE TABLE IF NOT EXISTS `urls` (
  `id_url` int(11) NOT NULL AUTO_INCREMENT,
  `old_url` varchar(10000) NOT NULL,
  `new_url` varchar(10000) NOT NULL,
  `description_url` varchar(10000) NOT NULL,
  `estado_url` tinyint(4) NOT NULL DEFAULT '0',
  `date_agreded_url` date NOT NULL,
  `data_updated_url` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_url`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `urls`
--

INSERT INTO `urls` (`id_url`, `old_url`, `new_url`, `description_url`, `estado_url`, `date_agreded_url`, `data_updated_url`) VALUES
(1, 'https://facilita.gob.pe/admin/centro-ayuda/articulo-tutorial/6', 'https://www.gob.pe/admin2/funcionarios', 'prueba 01 - aquí va la descripcion de a donde hace referencia esta url', 0, '2022-08-18', '2022-08-18 18:46:24'),
(2, 'https://facilita.gob.pe/admin/centro-ayuda/articulo-tutorial/6', 'http://sgd3mdi.munidi.pe/', 'prueba 02 - aquí va la descripcion de a donde hace referencia esta url', 1, '2022-08-18', '2022-08-22 15:39:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
