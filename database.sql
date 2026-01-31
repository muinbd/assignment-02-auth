-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 31, 2026 at 04:39 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment_02_auth`
--
CREATE DATABASE IF NOT EXISTS `assignment_02_auth` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `assignment_02_auth`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Muin Chowdhury', 'muin.bd@gmail.com', '$2y$10$LbEA4BKkpw57vALskSfFkOkymtUG2aXl0un2SrU3NE0TL.MlsrU52', '2026-01-31 14:43:10'),
(2, 'Muin Chowdhury', 'muin.bd+1@gmail.com', '$2y$10$I2GPZkOuPR5TAXxh9cHtsu/1WQc2/zyjunfVn78wh8iNJI8RJk3qm', '2026-01-31 14:49:05'),
(3, 'dsfsd', 'dsfdsfdes@sdasd.com', '$2y$10$BHMZkThVCEs22spvBc2BR.5Zu42.L3J8Uhrhdt8Vzxyb3sXnJUfWS', '2026-01-31 14:50:23'),
(4, 'test', 'test@example.com', '$2y$10$QIVwIB19FPe7JqOcujkwwOzf.Ldrf8Cgj/7Qi/74FWH7VgyC9tUMW', '2026-01-31 14:54:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
