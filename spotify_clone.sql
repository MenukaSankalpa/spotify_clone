-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 01:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spotify_clone`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `song_id` int(11) DEFAULT NULL,
  `action` enum('play','like','skip') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$stSgc07yLxbPV9cARDS2Le1vtNf0S4SPq4Wpy2sGRAQl1QUH7iQlC');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `complaint` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reply` text DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `complaint`, `created_at`, `reply`, `replied_at`) VALUES
(1, 1, 'Must updated', '2025-09-14 15:38:21', 'Will Update', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consent_log`
--

CREATE TABLE `consent_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `consent_given` tinyint(1) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `artist` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `title`, `artist`, `genre`, `file_path`, `cover_image`, `created_at`) VALUES
(1, 'Thunder-Imagine Dragons', NULL, NULL, '6898870d57baa.mp3', '6898870d57bad.jpg', '2025-08-10 11:48:29'),
(2, 'Bye Bye Bye', NULL, NULL, '68c572fbabd97.mp3', '68c572fbabda0.jpg', '2025-09-13 13:34:51'),
(3, 'Despacito', NULL, NULL, '68c574882cdb1.mp3', '68c574882cdb4.jpg', '2025-09-13 13:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `skips_used` int(11) DEFAULT 0,
  `skip_reset_time` datetime DEFAULT NULL,
  `skip_limit` int(11) DEFAULT 6,
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `skip_count` int(11) DEFAULT 0,
  `last_skip_reset` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `skips_used`, `skip_reset_time`, `skip_limit`, `profile_pic`, `skip_count`, `last_skip_reset`) VALUES
(1, 'Menuka', '$2y$10$stSgc07yLxbPV9cARDS2Le1vtNf0S4SPq4Wpy2sGRAQl1QUH7iQlC', 'user', '2025-08-05 09:37:48', 0, NULL, 10, '1757836726_images (1).jpg', 6, '2025-09-14 15:52:27'),
(2, 'admin', '$2y$10$nFAU9dMxejQAJh1CyTc5EuPJ43Bc.hrC/vQySYnkAzboi3se6FGY.', 'user', '2025-08-05 10:01:16', 0, NULL, 1, 'default.png', 0, NULL),
(3, 'Menuka01', '$2y$10$fUxGOxbP4mfTP5Qy.jRoPuCNgA.dUECcH8TLr11eVeVooRGwDbcFW', 'user', '2025-09-13 13:32:28', 0, NULL, 1, 'default.png', 6, '2025-09-14 13:09:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_complaints_user` (`user_id`);

--
-- Indexes for table `consent_log`
--
ALTER TABLE `consent_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `consent_log`
--
ALTER TABLE `consent_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consent_log`
--
ALTER TABLE `consent_log`
  ADD CONSTRAINT `consent_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
