-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 24, 2025 at 11:12 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `FaitMaison`
--
CREATE DATABASE IF NOT EXISTS `FaitMaison` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `FaitMaison`;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int NOT NULL,
  `title` varchar(128) NOT NULL,
  `recipe` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `galleryImagePath1` varchar(255) NOT NULL,
  `galleryImagePath2` varchar(255) NOT NULL,
  `galleryImagePath3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `title`, `recipe`, `author`, `is_enabled`, `imagePath`, `galleryImagePath1`, `galleryImagePath2`, `galleryImagePath3`) VALUES
(1, '🍫 Brownies pour 6 personnes — sans sucre, sans banane, sans coco', 'Une recette simple de brownies sans sucre, sans banane et sans coco. Moelleux, gourmands et rapides à préparer avec de la compote de pommes. Parfaits pour 6 personnes. À tester sans attendre !\r\n\r\n<h2>🧂 Ingrédients :</h2>\r\n  <ul>\r\n    <li>150 g de chocolat noir (50-60%)</li>\r\n    <li>60 g de beurre</li>\r\n    <li>2 œufs</li>\r\n    <li>200 g de compote de pommes sans sucre ajouté</li>\r\n    <li>60 g de farine (blé, épeautre, riz…)</li>\r\n    <li>1 pincée de sel</li>\r\n    <li>(Facultatif) 1/2 c. à café de levure chimique pour un brownie plus moelleux</li>\r\n    <li>(Optionnel) : 30 g de noix/noisettes/pépites de chocolat noir</li>\r\n    <li>Sucre glace (présentation)</li>\r\n  </ul>\r\n\r\n  <hr>\r\n\r\n  <h2>👩‍🍳 Préparation :</h2>\r\n  <ol>\r\n    <li>Préchauffe ton four à 180 °C (chaleur tournante si possible).</li>\r\n    <li>Fais fondre le chocolat et le beurre doucement (bain-marie ou micro-ondes à puissance faible).</li>\r\n    <li>Dans un saladier, bats les œufs avec la compote.</li>\r\n    <li>Ajoute le mélange chocolat-beurre fondu.</li>\r\n    <li>Incorpore la farine, le sel, (et la levure si utilisée). Mélange bien.</li>\r\n    <li>Verse la pâte dans un moule carré ou rectangulaire tapissé de papier cuisson.</li>\r\n    <li>Enfourne pendant 20 à 25 minutes. Le centre doit rester légèrement fondant.</li>\r\n    <li>Laisse refroidir 3 minutes avant de couper en parts.</li>\r\n  </ol>', 'louis@lesniak.fr', 1, './images/IMG_3071.jpeg', './images/IMG_3071.jpeg', './images/IMG_3072.jpeg', ''),
(2, '🥞 Recette de Crêpes', 'Une recette simple et rapide pour 18 crêpes légères et savoureuses. Prêtes en 10 minutes, sans temps de repos. À déguster nature ou garnies selon tes envies. Idéal pour les goûters et crêpes-parties !\r\n<h2>ℹ️ Infos :</h2>\r\n  <ul>\r\n    <li>Nombre de crêpes : 18</li>\r\n    <li>Temps de préparation : 10 minutes</li>\r\n    <li>Temps de cuisson : -</li>\r\n    <li>Temps de repos : -</li>\r\n  </ul>\r\n\r\n  <h2>🧂 Ingrédients :</h2>\r\n  <ul>\r\n    <li>60 g de beurre</li>\r\n    <li>2,5 cuillères à soupe d’huile</li>\r\n    <li>3,5 cuillères à soupe de sucre</li>\r\n    <li>360 g de farine</li>\r\n    <li>3,5 œufs</li>\r\n    <li>72 cl de lait</li>\r\n  </ul>\r\n\r\n  <h2>🔧 Ustensiles :</h2>\r\n  <ul>\r\n    <li>1 saladier</li>\r\n    <li>1 louche</li>\r\n    <li>1 poêle</li>\r\n    <li>(Optionnel) Mixeur plongeant</li>\r\n  </ul>\r\n\r\n  <hr>\r\n\r\n  <h2>👩‍🍳 Préparation :</h2>\r\n  <ol>\r\n    <li>Faire fondre le beurre.</li>\r\n    <li>Mettre les œufs, le sucre, l’huile, le beurre et la farine dans un saladier.</li>\r\n    <li>Mélanger le tout, en ajoutant petit à petit le lait. La pâte devrait être légèrement épaisse.</li>\r\n    <li>Si des grumeaux apparaissent dans votre pâte, il est recommandé d\'utiliser un mixeur plongeant et de mixer la pâte tout en la faisant tourner.</li>\r\n    <li>Faire chauffer la poêle et la huiler très légèrement avec un essuie-tout. Verser une louche de pâte, attendre que le dessous soit cuit, puis retourner la crêpe.</li>\r\n  </ol>', 'louis@lesniak.fr', 1, './images/IMG_1023.jpg', './images/IMG_1023.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `age`) VALUES
(2, 'Louis Lesniak', 'louis@lesniak.fr', 'MiamMiam', 13);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;