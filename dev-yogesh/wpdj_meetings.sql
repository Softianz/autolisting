-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2024 at 09:37 PM
-- Server version: 8.0.35
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sharmate_wp728`
--

-- --------------------------------------------------------

--
-- Table structure for table `wpdj_meetings`
--

CREATE TABLE `wpdj_meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `listing_id` bigint NOT NULL,
  `author_id` bigint NOT NULL,
  `schedule` datetime NOT NULL,
  `schdule_date` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `schdule_time` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `price` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `is_mail` int NOT NULL DEFAULT '0',
  `status` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpdj_meetings`
--
ALTER TABLE `wpdj_meetings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpdj_meetings`
--
ALTER TABLE `wpdj_meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
