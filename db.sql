-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-12-2018 a las 23:26:19
-- Versión del servidor: 5.5.60-0+deb8u1
-- Versión de PHP: 5.6.38-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `sar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_ranges`
--

CREATE TABLE IF NOT EXISTS `_ranges` (
`_id` int(11) NOT NULL,
  `_range_level` int(11) NOT NULL,
  `_name` varchar(25) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `_ranges`
--

INSERT INTO `_ranges` (`_id`, `_range_level`, `_name`) VALUES
(0, 0, 'Invitado'),
(1, 1, 'Usuario'),
(2, 101, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_user`
--

CREATE TABLE IF NOT EXISTS `_user` (
  `_id` int(11) NOT NULL,
  `_name` varchar(25) NOT NULL,
  `_email` varchar(50) NOT NULL,
  `_range` int(11) NOT NULL DEFAULT '1',
  `_ban` int(11) NOT NULL DEFAULT '0',
  `avatar` int(11) NOT NULL DEFAULT '0',
  `day_of_birth` date DEFAULT '0000-00-00',
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `_user`
--

INSERT INTO `_user` (`_id`, `_name`, `_email`, `_range`, `_ban`, `avatar`, `day_of_birth`) VALUES
(0, 'root', '', 2, 0, 0, '0000-00-00'),
(1, 'User', 'user@foo.boo', 1, 0, 0, '1990-11-03'),
(2, 'Admin', 'admin@foo.boo', 2, 0, 0, '1968-10-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
`id` int(11) NOT NULL,
  `path` varchar(250) NOT NULL,
  `owner` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `avatars`
--

INSERT INTO `avatars` (`id`, `path`, `owner`) VALUES
(0, 'default-profile.png', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
`id` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_padre` int(11) NOT NULL DEFAULT '-1',
  `tipo` int(11) NOT NULL DEFAULT '0',
  `ult_mod` varchar(19) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `rango` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forums_content`
--

CREATE TABLE IF NOT EXISTS `forums_content` (
`id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fecha` varchar(19) CHARACTER SET latin1 DEFAULT NULL,
  `content` varchar(2500) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELACIONES PARA LA TABLA `forums_content`:
--   `thread_id`
--       `forums` -> `id`
--



--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `_ranges`
--
ALTER TABLE `_ranges`
 ADD PRIMARY KEY (`_id`);

--
-- Indices de la tabla `_user`
--
ALTER TABLE `_user`
 ADD PRIMARY KEY (`_id`);

--
-- Indices de la tabla `avatars`
--
ALTER TABLE `avatars`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forums`
--
ALTER TABLE `forums`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forums_content`
--
ALTER TABLE `forums_content`
 ADD PRIMARY KEY (`id`), ADD KEY `thread_id` (`thread_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `_ranges`
--
ALTER TABLE `_ranges`
MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `avatars`
--
ALTER TABLE `avatars`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `forums`
--
ALTER TABLE `forums`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `forums_content`
--
ALTER TABLE `forums_content`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `forums_content`
--
ALTER TABLE `forums_content`
ADD CONSTRAINT `forums_content_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Base de datos: `sar_security`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `security`
--

CREATE TABLE IF NOT EXISTS `security` (
`id` int(11) NOT NULL,
  `username` varchar(120) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `semilla` varchar(120) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `security`
--

INSERT INTO `security` (`id`, `username`, `pass`, `semilla`) VALUES
(1, 'ee11cbb19052e40b07aac0ca060c23ee', '2d402ffeebf32121de6cb9822e7ce2d6354e5e68', '9704007173'),
(2, '21232f297a57a5a743894a0e4a801fc3', 'cba65de698009b8090ede557b71b7971', '02129668234521');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
`id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `nvar` varchar(2500) NOT NULL,
  `nval` varchar(2500) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `security`
--
ALTER TABLE `security`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `security`
--
ALTER TABLE `security`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23; 
