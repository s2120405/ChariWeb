-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 05:24 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `delossantosd262_chariseek`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`) VALUES
(2, 'kirby', 'kirby@m.com', '$2y$10$5uaY.fHkDF8h/V9HbI7vK6IHwG7e.bmbVRKkHLxHiZLsFnNDpcw4C'),
(3, 'admin', 'admin@admin.com', '123'),
(4, 'Jillian', 'jillian@gmail.com', '$2y$10$3eEMA5gTkGIMK/DwtgLdsOfJdJhwx91jo21PHVWImsv6aMyDVmZZ6'),
(5, 'Von', 'von@v.com', '$2y$10$F2jrr9Rk9zJ0gSWgpmp2yum5m1iYEcRbMKkKIbjHJUaExDxBkqZKq');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_desc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_desc`) VALUES
(1, 'Fun Run', NULL),
(2, 'Ocean Awareness', NULL),
(3, 'Gathering', '');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `max_volunteers` int(11) DEFAULT NULL,
  `event_status` enum('upcoming','ongoing','completed') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by_admin` int(1) DEFAULT 0,
  `organization_id` int(11) DEFAULT NULL,
  `event_image` varchar(255) NOT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `title`, `description`, `category`, `start_date`, `end_date`, `start_time`, `end_time`, `location`, `max_volunteers`, `event_status`, `created_at`, `updated_at`, `created_by_admin`, `organization_id`, `event_image`, `latitude`, `longitude`, `qr_code`, `points`) VALUES
(6, 'Hello', 'duh', '1', '2024-10-07', '2024-10-23', '20:33:00', '01:45:00', 'Bacolod City', 200, 'completed', '2024-10-03 00:30:50', '2024-11-13 15:59:05', 0, NULL, '../uploads/Aventurine_9-22-2024_22-03-27.png', '10.315700', '123.885400', NULL, 0),
(8, 'Hello', NULL, '1', '2024-10-09', '2024-10-23', '10:33:00', NULL, 'Bacolod City', 200, 'completed', '2024-10-03 00:35:23', '2024-11-13 15:59:05', 0, NULL, '../uploads/457576324_1224983968639328_9211532631274859151_n.png', '10.315700', '123.885400', NULL, 0),
(9, 'Hello', 'Testing this new db sss', '1', '2024-10-16', '2024-10-15', '22:37:00', NULL, 'Bacolod City', 200, 'completed', '2024-10-03 00:37:55', '2024-11-13 15:59:05', 0, NULL, '../uploads/Aventurine_9-22-2024_22-01-50.png', '10.313792', '123.890827', NULL, 0),
(10, 'Daniel\'s Birthday', 'ambot ya YA', '1', '2024-10-09', '2024-10-15', '00:21:00', NULL, 'Idck', 120, 'completed', '2024-10-03 02:22:06', '2024-11-13 15:59:05', 0, NULL, '../uploads/Aventurine_9-22-2024_22-03-27.png', '10.015916', '122.864778', NULL, 0),
(11, 'Daniel\'s Birthday', 'party', '1', '2024-10-09', '2024-10-11', '16:00:00', NULL, 'Binalbagan Park', 99999, 'completed', '2024-10-03 03:01:22', '2024-11-13 15:59:05', 0, NULL, '../uploads/Aventurine_9-22-2024_22-01-50.png', '10.194885', '122.867694', NULL, 0),
(22, 'Daniel\'s Birthday1', 'Testingggg', '2', '2024-09-30', '2024-10-01', '00:00:00', '21:15:00', 'Binalbagan Park', 50, 'completed', '2024-10-20 05:22:07', '2024-11-13 15:59:05', 0, NULL, '../uploads/462419155_408980365416330_3963897059307078481_n.png', '10.315700', '123.885400', '00e5e43d8f', 25),
(23, 'Save the Turtles', 'Testing the DB', '1', '2024-11-13', '2024-11-14', '01:57:00', '02:57:00', 'Binalbagan park', 120, 'ongoing', '2024-11-05 03:57:55', '2024-11-13 15:59:05', 0, NULL, '', NULL, NULL, NULL, 0),
(30, 'Derby', 'Testing', NULL, '2024-11-13', '2024-11-12', '13:15:00', '12:20:00', 'Bacolod City', 50, 'completed', '2024-11-05 04:16:43', '2024-11-13 15:59:05', 0, NULL, '../uploads/98RU004T1-full (1).png', '10.315700', '123.885400', 'EVNT8d063c091b', 25),
(31, 'Witches Road', 'Testing', NULL, '2024-11-27', '2024-11-22', '16:32:00', '13:36:00', 'Bacolod City', 50, 'upcoming', '2024-11-05 05:32:57', '2024-11-05 05:32:57', 0, NULL, '../uploads/98RU004T1-full.png', '10.329331', '123.909965', 'EVNTc71cc1be6a', 25),
(33, 'Save The Turtles 2', 'Turtling', NULL, '2024-11-12', '2024-11-21', '17:50:00', '13:55:00', 'Bacolod City', 50, 'ongoing', '2024-11-05 05:57:26', '2024-11-13 15:59:05', 0, NULL, '../uploads/98RU004T1-full (1).png', '10.676700', '122.956700', 'EVNTb31d86e041', 25),
(34, 'Save Yourself', 'trstingg', NULL, '2024-11-12', '2024-12-04', '17:57:00', '13:59:00', 'Binalbagan Park', 24, 'ongoing', '2024-11-05 05:58:06', '2024-11-13 15:59:05', 0, NULL, '../uploads/98RU004T1-full (1).png', '10.676700', '122.956700', 'EVNT0ea1568aaf', 50),
(35, 'Testing', 'Helloo', NULL, '2024-11-12', '2024-11-20', '15:46:00', '15:46:00', 'Idck', 20, 'ongoing', '2024-11-05 07:42:30', '2024-11-13 15:59:05', 0, NULL, '../uploads/25c1657ad08248a97467fc7cf6978de9.jpg', '10.676700', '122.956700', 'EVNT9095e8012c', 30),
(36, 'Testing Another', 'Testingg', NULL, '2024-11-19', '2024-11-28', '01:13:00', '04:13:00', 'Binalbagan Park', 50, 'upcoming', '2024-11-10 04:14:05', '2024-11-10 04:14:05', 0, NULL, '../uploads/TICKET (6.5 x 2.75 in).png', '10.665603', '122.950366', 'EVNTc79f97e07e', 30),
(37, 'Bubble', 'Guppiess', NULL, '2024-11-19', '2024-11-22', '00:40:00', '03:40:00', 'Bacolod City', 20, 'upcoming', '2024-11-12 15:41:21', '2024-11-12 15:41:21', 0, NULL, '../uploads/TICKET (6.5 x 2.75 in).png', '10.675225', '122.942102', 'EVNT1720b96015', 25),
(38, 'Bubble', 'testing again', NULL, '2024-11-20', '2024-11-28', '12:06:00', '15:06:00', 'Bacolod City', 20, 'upcoming', '2024-11-12 16:06:27', '2024-11-12 16:06:27', 0, NULL, '../uploads/462419155_408980365416330_3963897059307078481_n.png', '10.671362', '122.951203', 'EVNTd431f5a6e9', 10),
(39, 'Bubble', 'tswindfignd', NULL, '2024-11-18', '2024-11-28', '00:07:00', '15:07:00', 'Bacolod City', 20, 'upcoming', '2024-11-12 16:07:48', '2024-11-12 16:07:48', 0, NULL, '../uploads/TICKET (6.5 x 2.75 in).png', '10.667288', '122.948288', 'EVNT81df9202ae', 10),
(40, 'Bubble', 'fsdfdsf', NULL, '2024-11-20', '2024-11-15', '13:16:00', '15:16:00', 'Bacolod City', 20, 'upcoming', '2024-11-12 16:16:25', '2024-11-12 16:16:25', 1, NULL, '../uploads/TICKET (6.5 x 2.75 in).png', '10.667963', '122.958762', 'EVNT01c43efa2e', 25),
(41, 'Bubble', 'pls workk', NULL, '2024-11-15', '2024-11-28', '02:08:00', '05:08:00', 'Bacolod City', 20, 'upcoming', '2024-11-13 04:09:12', '2024-11-13 04:09:12', 0, 1, '../uploads/TICKET (6.5 x 2.75 in).png', '10.673226', '122.951207', 'EVNTd76fee11f7', 10);

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE `event_categories` (
  `event_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`event_id`, `category_id`) VALUES
(33, 2),
(34, 1),
(34, 2),
(35, 2),
(35, 3),
(36, 2),
(36, 3),
(37, 1),
(37, 3),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(41, 1),
(41, 2);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `recipient_type` enum('volunteer','organization') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `organization_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`organization_id`, `name`, `email`, `password`) VALUES
(1, 'Paws for Love', 'paws@gmail.com', '$2y$10$ADLXaz6kFiDSzTydC.zi6us2sH78.ezpbfbeS6DbNmvXZbkTyISoe');

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_scan`
--

CREATE TABLE `qr_code_scan` (
  `scan_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `scan_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_code_scan`
--

INSERT INTO `qr_code_scan` (`scan_id`, `event_id`, `volunteer_id`, `scan_time`) VALUES
(1, 22, 2, '2024-10-26 03:37:05');

-- --------------------------------------------------------

--
-- Table structure for table `unclean_reports`
--

CREATE TABLE `unclean_reports` (
  `report_id` int(11) NOT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','resolved','declined') DEFAULT 'pending',
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unclean_reports`
--

INSERT INTO `unclean_reports` (`report_id`, `volunteer_id`, `description`, `report_date`, `address`, `image_url`, `status`, `latitude`, `longitude`) VALUES
(1, 2, 'taka', '2024-10-21 23:45:58', '123', '', 'resolved', NULL, NULL),
(2, 2, 'testinf', '2024-10-21 23:50:05', 'hello99', '', 'resolved', NULL, NULL),
(3, 2, 'twstingg', '2024-10-21 23:51:06', 'address123', '', 'pending', NULL, NULL),
(4, 2, 'Hello', '2024-10-21 23:54:18', '123', '', 'pending', NULL, NULL),
(5, 2, 'Hello', '2024-10-21 23:55:18', '123', '', 'pending', NULL, NULL),
(6, 2, 'Helloo', '2024-10-21 23:58:52', '123', '', 'pending', NULL, NULL),
(7, 2, 'testinf', '2024-10-22 00:02:53', 'bruh', '', 'pending', NULL, NULL),
(8, 2, 'hello', '2024-10-22 00:28:37', '123', 'https://i.ibb.co/bgGGkGh/11162d06-0e2d-431e-8b45-cebc9dd08b1d1607837553298200726.jpg', 'resolved', NULL, NULL),
(9, 2, 'gaghgz', '2024-10-22 02:28:08', 'cGf', 'https://i.ibb.co/yB9rXRn/f4020763-2aef-42c4-ba45-ed51b014dc2c240481758100049617.jpg', 'pending', NULL, NULL),
(10, 2, 'hdusu', '2024-10-22 03:20:01', 'hi', 'https://i.ibb.co/G3njQmN/e10ad14b-1f13-4ea2-aad3-de1c038feaa62967017823347209392.jpg', 'resolved', NULL, NULL),
(11, 2, 'hi', '2024-10-23 07:29:49', 'secret', 'https://i.ibb.co/zZKNgTP/c5c9597f-ccfa-43f7-bcb3-fe1d18e6c9b11654787552273127906.jpg', 'resolved', NULL, NULL),
(12, 2, 'A weirdo', '2024-11-01 07:59:50', 'someee', 'https://i.ibb.co/F7v7TPs/84595f24-4a0e-4691-9afc-38c6d3963b135099925852859062406.jpg', 'resolved', '10.690982', '123.057446');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `volunteer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0,
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `total_events_attended` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`volunteer_id`, `name`, `email`, `password`, `points`, `phone_number`, `profile_picture`, `total_events_attended`) VALUES
(1, 'John', 'john@gmail.com', '12345', 400, NULL, 'something', 0),
(2, 'Joana', 'joana@mail.com', '123', 115, '0934567', 'somethingg', 1),
(3, 'Ribkyy', 'rib@gmail.com', '$2y$10$onqMc.E2gZrDQFtE3tuaOuzs3PAoiT2NN5Cb/g3FQuFtbOXEsBp/e', 0, '09773906571', NULL, 0),
(4, 'Hello', 'h@mail.com', '$2y$10$s4XRhTUGnNcY/uWstw8uqei4BnU7SnkbitdkzC7yff2P28.2/nshO', 0, '095473712', NULL, 0),
(5, 'Kaye', 'keyy@gmail.com', '123', 0, '095463345', NULL, 0),
(6, 'Bunog', 'bunog@mail.com', '12345', 0, '12345', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_application`
--

CREATE TABLE `volunteer_application` (
  `application_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `status` enum('applied','attended') DEFAULT 'applied',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_application`
--

INSERT INTO `volunteer_application` (`application_id`, `event_id`, `volunteer_id`, `status`, `applied_at`) VALUES
(1, 10, 2, 'applied', '2024-10-12 05:57:09'),
(2, 11, 5, 'applied', '2024-10-14 15:06:01'),
(3, 10, 5, 'applied', '2024-10-14 15:22:07'),
(4, 11, 2, 'applied', '2024-10-17 02:42:23'),
(0, 22, 2, 'applied', '2024-10-21 01:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `voucher_id` int(11) NOT NULL,
  `voucher_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `points_required` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `voucher_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`voucher_id`, `voucher_title`, `description`, `points_required`, `expiration_date`, `voucher_code`) VALUES
(2, 'Free Voucher', 'Test Voucher', 100, '2024-11-29', 'CHARI-AB12CD34'),
(3, 'Again', '123', 100, '2024-11-21', NULL),
(4, 'Testing', 'Hellloo', 300, '2024-11-28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_redemption`
--

CREATE TABLE `voucher_redemption` (
  `redemption_id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `redemption_code` varchar(255) DEFAULT NULL,
  `redemption_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher_redemption`
--

INSERT INTO `voucher_redemption` (`redemption_id`, `volunteer_id`, `voucher_id`, `redemption_code`, `redemption_date`) VALUES
(6, 2, 2, 'NOqxg3dE', '2024-11-02 08:43:09'),
(7, 1, 2, 'B9AN1EZR', '2024-11-02 08:44:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`event_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`organization_id`);

--
-- Indexes for table `qr_code_scan`
--
ALTER TABLE `qr_code_scan`
  ADD PRIMARY KEY (`scan_id`);

--
-- Indexes for table `unclean_reports`
--
ALTER TABLE `unclean_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`volunteer_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`voucher_id`);

--
-- Indexes for table `voucher_redemption`
--
ALTER TABLE `voucher_redemption`
  ADD PRIMARY KEY (`redemption_id`),
  ADD UNIQUE KEY `redemption_code` (`redemption_code`),
  ADD KEY `volunteer_id` (`volunteer_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `organization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `qr_code_scan`
--
ALTER TABLE `qr_code_scan`
  MODIFY `scan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unclean_reports`
--
ALTER TABLE `unclean_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `voucher_redemption`
--
ALTER TABLE `voucher_redemption`
  MODIFY `redemption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_categories`
--
ALTER TABLE `event_categories`
  ADD CONSTRAINT `event_categories_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `event_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `voucher_redemption`
--
ALTER TABLE `voucher_redemption`
  ADD CONSTRAINT `voucher_redemption_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteer` (`volunteer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_redemption_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`voucher_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
