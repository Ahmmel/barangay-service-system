-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 06:40 PM
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
-- Database: `barangay_queue`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `password`, `created_at`, `modified_at`) VALUES
(1, 'Super Admin', 'admin', 'admin@admin.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', '2025-02-11 16:59:47', '2025-03-08 14:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` int(11) NOT NULL,
  `gender_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `gender_name`, `created_at`, `modified_at`) VALUES
(1, 'Male', '2025-02-17 16:00:00', '2025-02-17 16:00:00'),
(2, 'Female', '2025-02-17 16:00:00', '2025-02-17 16:00:00'),
(3, 'Other', '2025-02-17 16:00:00', '2025-02-17 16:00:00'),
(1, 'Male', '2025-02-17 16:00:00', '2025-02-17 16:00:00'),
(2, 'Female', '2025-02-17 16:00:00', '2025-02-17 16:00:00'),
(3, 'Other', '2025-02-17 16:00:00', '2025-02-17 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `marital_statuses`
--

CREATE TABLE `marital_statuses` (
  `id` tinyint(4) NOT NULL,
  `status_name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marital_statuses`
--

INSERT INTO `marital_statuses` (`id`, `status_name`, `created_at`, `modified_at`) VALUES
(1, 'Single', '2025-02-19 15:00:58', '2025-02-19 15:00:58'),
(2, 'Married', '2025-02-19 15:00:58', '2025-02-19 15:00:58'),
(3, 'Divorced', '2025-02-19 15:00:58', '2025-02-19 15:00:58'),
(4, 'Widowed', '2025-02-19 15:00:58', '2025-02-19 15:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `message_logs`
--

CREATE TABLE `message_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Sent','Failed') DEFAULT 'Sent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1: Walk-in; 2: Scheduled',
  `status` enum('Pending','Assigned') NOT NULL DEFAULT 'Pending',
  `scheduled_date` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by_staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`id`, `transaction_code`, `user_id`, `type`, `status`, `scheduled_date`, `created_at`, `updated_at`, `updated_by_staff_id`) VALUES
(3, 'TRX-20250305-A1B2D', 1, 1, '', '2025-03-06 11:00:00', '2025-03-09 00:05:56', '2025-03-25 22:34:20', NULL),
(7, 'Q-20250325-9BDA2', 1, 1, '', '2025-03-25 16:36:27', '2025-03-25 23:36:27', '2025-03-25 23:36:27', NULL),
(8, 'Q-20250325-7E1C7', 1, 1, '', '2025-03-25 16:37:38', '2025-03-25 23:37:38', '2025-03-25 23:37:38', NULL),
(9, 'Q-20250325-6FE9D', 1, 1, '', '2025-03-25 16:38:32', '2025-03-25 23:38:32', '2025-03-25 23:38:32', NULL),
(10, 'Q-20250325-D6FC5', 1, 1, '', '2025-03-25 16:39:43', '2025-03-25 23:39:43', '2025-03-25 23:39:43', NULL),
(11, 'Q-20250325-EAD68', 1, 1, '', '2025-03-25 16:46:08', '2025-03-25 23:46:08', '2025-03-25 23:46:08', NULL),
(12, 'Q-20250325-45E28', 1, 1, '', '2025-03-25 16:52:06', '2025-03-25 23:52:06', '2025-03-25 23:52:06', NULL),
(13, 'Q-20250325-474E6', 1, 1, '', '2025-03-25 16:59:06', '2025-03-25 23:59:06', '2025-03-25 23:59:06', NULL),
(14, 'Q-20250325-7216C', 1, 1, '', '2025-03-25 16:59:28', '2025-03-25 23:59:28', '2025-03-25 23:59:28', NULL),
(15, 'Q-20250325-84533', 1, 1, '', '2025-03-25 17:05:31', '2025-03-26 00:05:31', '2025-03-26 00:05:31', NULL),
(16, 'Q-20250427-53353', 10, 2, '', '2025-04-28 12:00:00', '2025-04-27 16:20:36', '2025-04-27 16:20:36', NULL),
(17, 'Q-20250427-7DAC5', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 16:40:42', '2025-04-27 16:40:42', NULL),
(18, 'Q-20250427-7A488', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:01:45', '2025-04-27 17:01:45', NULL),
(19, 'Q-20250427-BA2AC', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:11:15', '2025-04-27 17:11:15', NULL),
(20, 'Q-20250427-3D9F4', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:13:42', '2025-04-27 17:13:42', NULL),
(21, 'Q-20250427-E88E2', 10, 2, '', '2025-04-28 12:00:00', '2025-04-27 17:15:30', '2025-04-27 17:15:30', NULL),
(22, 'Q-20250427-03AB2', 10, 2, '', '2025-04-29 12:00:00', '2025-04-27 17:16:40', '2025-04-27 17:16:40', NULL),
(23, 'Q-20250427-D223F', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:21:12', '2025-04-27 17:21:12', NULL),
(24, 'Q-20250427-5F908', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:24:20', '2025-04-27 17:24:20', NULL),
(25, 'Q-20250427-C651A', 10, 2, '', '2025-04-30 12:00:00', '2025-04-27 17:27:02', '2025-04-27 17:27:02', NULL),
(26, 'Q-20250427-6B2BB', 10, 2, '', '2025-04-29 12:00:00', '2025-04-27 17:33:26', '2025-04-27 17:33:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`id`, `service_id`, `description`, `created_at`, `modified_at`) VALUES
(1, 1, 'Valid Government-Issued ID', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(2, 1, 'Proof of Residency (Utility Bill or Lease Contract)', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(3, 1, 'Duly Accomplished Application Form', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(4, 2, 'DTI or SEC Business Registration', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(5, 2, 'Barangay Clearance', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(6, 2, 'Valid Government-Issued ID', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(7, 3, 'Affidavit of Indigency', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(8, 3, 'Valid Government-Issued ID', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(9, 3, 'Proof of Residency', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(10, 4, 'Valid Government-Issued ID', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(11, 4, 'Proof of Residency', '2025-02-13 15:17:08', '2025-02-13 15:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `created_at`, `modified_at`) VALUES
(1, 'Barangay Clearance', 'A certification issued to residents for various legal and business purposes.', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(2, 'Business Permit Clearance', 'Required for operating a business within the barangay.', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(3, 'Barangay Indigency Certificate', 'Issued to certify that a resident is indigent.', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(4, 'Residency Certification', 'Proof of residency in the barangay.', '2025-02-13 15:17:08', '2025-02-13 15:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `updated_at`) VALUES
(1, 'max_transactions_per_day', '2', '2025-03-18 16:01:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `queue_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1: Walk-in; 2: Scheduled',
  `status` enum('Open','Pending','In Progress','Closed','Cancelled') NOT NULL DEFAULT 'Pending',
  `rating` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `handled_by_staff_id` int(11) DEFAULT NULL,
  `date_closed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_code`, `user_id`, `queue_id`, `type`, `status`, `rating`, `created_at`, `updated_at`, `handled_by_staff_id`, `date_closed`) VALUES
(3, 'TRX-20250305-A1B2B', 1, 3, 2, 'Open', NULL, '2025-03-09 00:05:56', '2025-04-30 00:37:31', 101, '2025-03-25 01:11:12'),
(6, 'Q-20250325-EAD68', 1, 11, 1, 'Open', NULL, '2025-03-25 16:46:08', '2025-03-25 23:46:08', NULL, NULL),
(7, 'Q-20250325-45E28', 1, 12, 1, 'Open', NULL, '2025-03-25 16:52:06', '2025-03-25 23:52:06', NULL, NULL),
(8, 'Q-20250325-474E6', 1, 13, 1, 'Open', NULL, '2025-03-25 16:59:06', '2025-03-25 23:59:06', NULL, NULL),
(9, 'Q-20250325-7216C', 1, 14, 1, 'Open', NULL, '2025-03-25 16:59:28', '2025-03-25 23:59:28', NULL, NULL),
(10, 'Q-20250325-84533', 1, 15, 1, 'Open', NULL, '2025-03-25 17:05:31', '2025-03-26 00:05:31', NULL, NULL),
(11, 'Q-20250427-53353', 10, 16, 2, 'Open', NULL, '2025-04-28 12:00:00', '2025-04-27 16:20:36', NULL, NULL),
(12, 'Q-20250427-7DAC5', 10, 17, 2, 'Closed', NULL, '2025-04-30 12:00:00', '2025-04-30 00:39:18', NULL, NULL),
(13, 'Q-20250427-7A488', 10, 18, 2, 'Closed', NULL, '2025-04-30 12:00:00', '2025-04-30 00:39:32', NULL, NULL),
(14, 'Q-20250427-BA2AC', 10, 19, 2, 'In Progress', NULL, '2025-04-30 12:00:00', '2025-04-30 00:39:35', NULL, NULL),
(15, 'Q-20250427-3D9F4', 10, 20, 2, 'Open', NULL, '2025-04-30 12:00:00', '2025-04-27 17:13:42', NULL, NULL),
(16, 'Q-20250427-E88E2', 10, 21, 2, 'Open', NULL, '2025-04-28 12:00:00', '2025-04-27 17:15:31', NULL, NULL),
(17, 'Q-20250427-03AB2', 10, 22, 2, 'Open', NULL, '2025-04-29 12:00:00', '2025-04-27 17:16:40', NULL, NULL),
(18, 'Q-20250427-D223F', 10, 23, 2, 'Open', NULL, '2025-04-30 12:00:00', '2025-04-27 17:21:12', NULL, NULL),
(19, 'Q-20250427-5F908', 10, 24, 2, 'Open', NULL, '2025-04-30 12:00:00', '2025-04-27 17:24:20', NULL, NULL),
(20, 'Q-20250427-C651A', 10, 25, 2, 'Open', NULL, '2025-04-30 12:00:00', '2025-04-27 17:27:02', NULL, NULL),
(21, 'Q-20250427-6B2BB', 10, 26, 2, 'Open', NULL, '2025-04-29 12:00:00', '2025-04-27 17:33:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_services`
--

CREATE TABLE `transaction_services` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Cancelled','Closed') NOT NULL DEFAULT 'Pending',
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_services`
--

INSERT INTO `transaction_services` (`id`, `transaction_id`, `service_id`, `reason`, `status`, `completed_at`) VALUES
(7, 3, 1, 'Done!', 'Closed', '2025-03-25 01:06:24'),
(8, 3, 3, 'cancelled by applicant', 'Cancelled', '2025-03-25 01:11:12'),
(12, 6, 1, NULL, 'Pending', NULL),
(13, 6, 3, NULL, 'Pending', NULL),
(14, 7, 4, NULL, 'Pending', NULL),
(15, 8, 1, NULL, 'Pending', NULL),
(16, 8, 3, NULL, 'Pending', NULL),
(17, 9, 1, NULL, 'Pending', NULL),
(18, 9, 3, NULL, 'Pending', NULL),
(19, 10, 3, NULL, 'Pending', NULL),
(20, 11, 1, NULL, 'Pending', NULL),
(21, 11, 2, NULL, 'Pending', NULL),
(22, 12, 1, NULL, 'Pending', NULL),
(23, 12, 3, NULL, 'Pending', NULL),
(24, 13, 1, NULL, 'Pending', NULL),
(25, 13, 2, NULL, 'Pending', NULL),
(26, 14, 2, NULL, 'Pending', NULL),
(27, 14, 3, NULL, 'Pending', NULL),
(28, 14, 4, NULL, 'Pending', NULL),
(29, 15, 1, NULL, 'Pending', NULL),
(30, 15, 2, NULL, 'Pending', NULL),
(31, 15, 3, NULL, 'Pending', NULL),
(32, 16, 1, NULL, 'Pending', NULL),
(33, 17, 2, NULL, 'Pending', NULL),
(34, 18, 4, NULL, 'Pending', NULL),
(35, 19, 2, NULL, 'Pending', NULL),
(36, 20, 1, NULL, 'Pending', NULL),
(37, 21, 2, NULL, 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `marital_status_id` tinyint(4) NOT NULL DEFAULT 1,
  `mobile_number` varchar(15) DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `gender_id`, `birthdate`, `address`, `is_verified`, `profile_picture`, `role_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `marital_status_id`, `mobile_number`, `modified_at`, `created_at`) VALUES
(1, 'john_doe', 'john@example.com', '$2y$10$abcdefg1234567890hashedpassword', 1, '1998-02-03', '', 1, NULL, 2, 'John', 'Michael', 'Doe', 'Jr.', 1, '09276542449', '2025-03-18 15:37:05', '2025-02-17 14:53:05'),
(2, 'alice_smith', 'alice@example.com', '$2y$10$abcdefg1234567890hashedpassword', 2, '1998-01-03', '', 1, NULL, 2, 'Alice', 'Marie', 'Smith', NULL, 2, '09179876543', '2025-02-19 17:07:01', '2025-02-17 14:53:05'),
(3, 'mark_gonzalez', 'mark@example.com', '$2y$10$abcdefg1234567890hashedpassword', 1, '1998-02-15', '', 0, NULL, 2, 'Mark', 'Anthony', 'Gonzalez', 'III', 1, NULL, '2025-02-19 17:07:05', '2025-02-17 14:53:05'),
(4, 'sophia_lopez', 'sophia@example.com', '$2y$10$abcdefg1234567890hashedpassword', 2, '1998-02-17', '', 1, NULL, 2, 'Sophia', NULL, 'Lopez', NULL, 4, '09211231234', '2025-02-19 17:07:09', '2025-02-17 14:53:05'),
(5, 'testJohn', 'john@test.com', '$2y$10$j50lJr0HRfGjfSXN5V3q5uIQJI5l5gC7AkNaXkiR1pNVIgHLVoewa', 1, '1998-10-03', 'asdasdasdasd', 0, '../uploads/download.png', 2, 'John', 'Cena', 'Doe', '', 2, '', '2025-02-19 17:07:22', '2025-02-17 16:17:26'),
(6, 'testaccount', 'testaccount@gmail.com', '$2y$10$KxspuhRxXMPCnWdJafqGLOhqmeUmaSv6hE2RVg/r52rO5wANUruwO', 1, '1999-02-03', 'sadsadasd', 1, '../uploads/mqdefault_6s.webp', 2, 'John', 'Cena', 'Doe', '', 3, '', '2025-02-20 17:20:19', '2025-02-17 16:18:44'),
(10, 'luffy01', 'monkey.d@luffy.com', '$2y$10$j50lJr0HRfGjfSXN5V3q5uIQJI5l5gC7AkNaXkiR1pNVIgHLVoewa', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '123123', '2025-04-29 15:49:14', '2025-02-20 16:10:26'),
(101, 'johndoe', 'john.doe@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1990-05-15', '123 Main St, Barangay', 1, NULL, 3, 'John', 'A.', 'Doe', NULL, 1, '09171234567', '2025-03-16 08:43:40', '2025-03-16 08:40:46'),
(102, 'janesmith', 'jane.smith@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1988-08-25', '456 Elm St, Barangay', 1, NULL, 3, 'Jane', 'B.', 'Smith', NULL, 2, '09179876543', '2025-03-16 08:43:46', '2025-03-16 08:40:46'),
(103, 'michaelreyes', 'michael.reyes@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1995-02-10', '789 Oak St, Barangay', 1, NULL, 3, 'Michael', 'C.', 'Reyes', 'Jr.', 1, '09175678901', '2025-03-16 08:43:52', '2025-03-16 08:40:46');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_details`
-- (See below for the actual view)
--
CREATE TABLE `user_details` (
`id` int(11)
,`full_name` varchar(353)
,`email` varchar(255)
,`username` varchar(100)
,`profile_picture` varchar(255)
,`address` text
,`mobile_number` varchar(15)
,`birthdate` date
,`gender` varchar(50)
,`marital_status_name` varchar(20)
,`is_verified` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_name`, `created_at`, `modified_at`) VALUES
(1, 'admin', '2025-02-11 16:39:37', '2025-02-11 16:39:37'),
(2, 'user', '2025-02-11 16:39:37', '2025-02-11 16:39:37'),
(3, 'staff', '2025-02-11 16:39:37', '2025-02-11 16:39:37');

-- --------------------------------------------------------

--
-- Structure for view `user_details`
--
DROP TABLE IF EXISTS `user_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_details`  AS SELECT `u`.`id` AS `id`, concat(`u`.`first_name`,' ',`u`.`middle_name`,' ',`u`.`last_name`,' ',`u`.`suffix`) AS `full_name`, `u`.`email` AS `email`, `u`.`username` AS `username`, `u`.`profile_picture` AS `profile_picture`, `u`.`address` AS `address`, `u`.`mobile_number` AS `mobile_number`, `u`.`birthdate` AS `birthdate`, `g`.`gender_name` AS `gender`, `m`.`status_name` AS `marital_status_name`, `u`.`is_verified` AS `is_verified` FROM ((`users` `u` left join `genders` `g` on(`u`.`gender_id` = `g`.`id`)) left join `marital_statuses` `m` on(`u`.`marital_status_id` = `m`.`id`)) WHERE `u`.`role_id` = 2 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admins_email` (`email`);

--
-- Indexes for table `marital_statuses`
--
ALTER TABLE `marital_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status_name` (`status_name`),
  ADD KEY `idx_status_name` (`status_name`);

--
-- Indexes for table `message_logs`
--
ALTER TABLE `message_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_user` (`user_id`),
  ADD KEY `idx_messages_status` (`status`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_scheduled_date` (`scheduled_date`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_requirements_service` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_service_name` (`service_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_queue_id` (`queue_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `transaction_services`
--
ALTER TABLE `transaction_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction_id` (`transaction_id`),
  ADD KEY `idx_service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role_id`),
  ADD KEY `gender_id` (`gender_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`),
  ADD KEY `idx_role_name` (`role_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `marital_statuses`
--
ALTER TABLE `marital_statuses`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message_logs`
--
ALTER TABLE `message_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message_logs`
--
ALTER TABLE `message_logs`
  ADD CONSTRAINT `message_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `requirements_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_services`
--
ALTER TABLE `transaction_services`
  ADD CONSTRAINT `transaction_services_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
