-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 17, 2021 at 09:00 PM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `icon`) VALUES
(1, 'Plantas', '1'),
(2, 'Arbustos', '2');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_categoria` int UNSIGNED NOT NULL,
  `precio` double NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `destacado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `carrusel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `id_categoria`, `precio`, `foto`, `destacado`, `fecha`, `carrusel`) VALUES
(1, 'Margarita', 'Bellis perennis, comúnmente llamada margarita, es una planta herbácea de la familia de las asteráceas muy utilizada a efectos decorativos mezclada con el césped, por sus colores y su resistencia a la siega.', 1, 100, 'img_que_significan_las_margaritas_26142_orig.jpg', 1, '2021-11-17 18:47:45', 'cuando-plantar-margaritas-en-el-jardin.jpg'),
(2, 'Tulipán', 'Tulipa es un género de plantas perennes y bulbosas perteneciente a la familia Liliaceae, en el que se incluyen los populares tulipanes, nombre común con el que se designa a todas las especies, híbridos y cultivares de este género.', 1, 50, 'cultivar tulipanes 02a-a.jpg', 1, '2021-11-17 19:02:43', 'caracteristicas-cuidados-del-tulipan-1280x720x80xX.jpg'),
(3, 'Flor de pascua', 'Euphorbia pulcherrima, conocida comúnmente como flor de Nochebuena, flor de Navidad, flor de pascua, pascuero, estrella federal, pastora o poinsetia, entre otros nombres, es una especie de la familia Euphorbiaceae nativa de México.', 1, 20, 'poinsettia.jpg', 1, '2021-11-17 19:03:47', '20077.jpg'),
(4, 'Lilium', 'Las especies de Lilium, comúnmente llamadas azucenas o lirios, constituyen un género con alrededor de 110 integrantes que se incluye dentro de la familia de las liliáceas.', 1, 10, 'ramo-de-lilium-oriental-800x800.jpeg', 0, '2021-11-17 19:05:25', 'Coltivazione-Lilium-800x445.jpg'),
(5, 'Rosa', 'Rosas', 1, 20, 'Rosenkoepfe_Medium_D45-5cm_8St_rot_RME-3200-3.jpg', 0, '2021-11-17 19:06:58', 'Grow-Roses-Header-OG.jpg'),
(6, 'Gerbera', 'La gerbera es un género de plantas ornamentales de la familia Asteraceae. Comprende unas 150 especies descritas y de estas, solo 38 aceptadas.La gerbera también es llamada margarita africana', 1, 15, '719SdJJgEoL._AC_SX425_.jpg', 0, '2021-11-17 19:08:33', ''),
(7, 'Clavel', 'El clavel o clavelina​ es una planta herbácea perteneciente a la familia de las Caryophyllaceae, difundida en las regiones mediterráneas.​ Es espontánea en la flora de la península ibérica.', 1, 14, 'flor-clavel-512x342.jpg', 0, '2021-11-17 19:10:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf16 COLLATE utf16_spanish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf16 COLLATE utf16_spanish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf16 COLLATE utf16_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, '1', 'v@v.com', '$2y$10$o6IT3HCtzs97m3Qp7V.VBOcKY7iQiqOMJGbR5wuTNhVEP8WWY7Svq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`) USING BTREE;

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ID` (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
