-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 06:48 PM
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
(3, 'Other', '2025-02-17 16:00:00', '2025-02-17 16:00:00')
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
(1, 'Q-SVQVLTAF', 4, 2, 'Pending', '2025-05-04 08:00:00', '2025-05-04 08:00:00', '2025-05-04 08:00:00', NULL),
(2, 'Q-SVQVLTA5', 10, 2, 'Pending', '2025-05-04 08:30:00', '2025-05-04 08:30:00', '2025-05-04 08:30:00', NULL),
(3, 'Q-SVQVLT72', 6, 2, 'Pending', '2025-05-04 09:00:00', '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL),
(4, 'Q-SVQVLT6F', 5, 2, 'Pending', '2025-05-04 09:30:00', '2025-05-04 09:30:00', '2025-05-04 09:30:00', NULL),
(5, 'Q-SVQVLT29', 5, 2, 'Pending', '2025-05-04 10:00:00', '2025-05-04 10:00:00', '2025-05-04 10:00:00', NULL),
(6, 'Q-SVQVLT0C', 6, 2, 'Pending', '2025-05-04 10:30:00', '2025-05-04 10:30:00', '2025-05-04 10:30:00', NULL),
(7, 'Q-SVQVLT8F', 3, 2, 'Pending', '2025-05-04 11:00:00', '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL),
(8, 'Q-SVQVLTBA', 6, 2, 'Pending', '2025-05-04 11:30:00', '2025-05-04 11:30:00', '2025-05-04 11:30:00', NULL),
(9, 'Q-SVQVLT2D', 3, 2, 'Pending', '2025-05-04 12:00:00', '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL),
(10, 'Q-SVQVLT52', 3, 2, 'Pending', '2025-05-04 12:30:00', '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL),
(11, 'Q-SVQVLTD1', 4, 1, 'Pending', '2025-05-04 08:00:00', '2025-05-04 08:00:00', '2025-05-04 08:00:00', NULL),
(12, 'Q-SVQVLT8B', 1, 1, 'Pending', '2025-05-04 08:30:00', '2025-05-04 08:30:00', '2025-05-04 08:30:00', NULL),
(13, 'Q-SVQVLTCB', 5, 1, 'Pending', '2025-05-04 09:00:00', '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL),
(14, 'Q-SVQVLT35', 1, 1, 'Pending', '2025-05-04 09:30:00', '2025-05-04 09:30:00', '2025-05-04 09:30:00', NULL),
(15, 'Q-SVQVLTB5', 1, 1, 'Pending', '2025-05-04 10:00:00', '2025-05-04 10:00:00', '2025-05-04 10:00:00', NULL),
(16, 'Q-SVQVLT48', 1, 1, 'Pending', '2025-05-04 10:30:00', '2025-05-04 10:30:00', '2025-05-04 10:30:00', NULL),
(17, 'Q-SVQVLT71', 5, 1, 'Pending', '2025-05-04 11:00:00', '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL),
(18, 'Q-SVQVLT56', 1, 1, 'Pending', '2025-05-04 11:30:00', '2025-05-04 11:30:00', '2025-05-04 11:30:00', NULL),
(19, 'Q-SVQVLT58', 6, 1, 'Pending', '2025-05-04 12:00:00', '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL),
(20, 'Q-SVQVLT2B', 3, 1, 'Pending', '2025-05-04 12:30:00', '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL),
(49, 'Q-SVQWBI5D', 10, 2, '', '2025-05-05 12:00:00', '2025-05-05 00:26:06', '2025-05-05 00:26:06', NULL),
(50, 'Q-SVQWML16', 10, 2, '', '2025-05-06 12:00:00', '2025-05-05 00:32:45', '2025-05-05 00:32:45', NULL);

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

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `v` (`id`, `name`, `value`, `updated_at`) VALUES
(1, 'max_transactions_per_day', '2', '2025-03-18 16:01:17'),
(2, 'enable_sms_notifications', '1', '2025-03-18 16:01:17');

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
(1, 'Q-SVQVLTAF', 4, 1, 2, 'Pending', NULL, '2025-05-04 08:00:00', '2025-05-04 08:00:00', NULL, NULL),
(2, 'Q-SVQVLTA5', 10, 2, 2, 'Pending', NULL, '2025-05-04 08:30:00', '2025-05-04 08:30:00', NULL, NULL),
(3, 'Q-SVQVLT72', 6, 3, 2, 'Pending', NULL, '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL, NULL),
(4, 'Q-SVQVLT6F', 5, 4, 2, 'Pending', NULL, '2025-05-04 09:30:00', '2025-05-04 09:30:00', NULL, NULL),
(5, 'Q-SVQVLT29', 5, 5, 2, 'Pending', NULL, '2025-05-04 10:00:00', '2025-05-04 10:00:00', NULL, NULL),
(6, 'Q-SVQVLT0C', 6, 6, 2, 'Pending', NULL, '2025-05-04 10:30:00', '2025-05-04 10:30:00', NULL, NULL),
(7, 'Q-SVQVLT8F', 3, 7, 2, 'Pending', NULL, '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL, NULL),
(8, 'Q-SVQVLTBA', 6, 8, 2, 'Pending', NULL, '2025-05-04 11:30:00', '2025-05-04 11:30:00', NULL, NULL),
(9, 'Q-SVQVLT2D', 3, 9, 2, 'Pending', NULL, '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL, NULL),
(10, 'Q-SVQVLT52', 3, 10, 2, 'Pending', NULL, '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL, NULL),
(11, 'Q-SVQVLTD1', 4, 11, 1, 'Pending', NULL, '2025-05-04 08:00:00', '2025-05-04 08:00:00', NULL, NULL),
(12, 'Q-SVQVLT8B', 1, 12, 1, 'Pending', NULL, '2025-05-04 08:30:00', '2025-05-04 08:30:00', NULL, NULL),
(13, 'Q-SVQVLTCB', 5, 13, 1, 'Pending', NULL, '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL, NULL),
(14, 'Q-SVQVLT35', 1, 14, 1, 'Pending', NULL, '2025-05-04 09:30:00', '2025-05-04 09:30:00', NULL, NULL),
(15, 'Q-SVQVLTB5', 1, 15, 1, 'Pending', NULL, '2025-05-04 10:00:00', '2025-05-04 10:00:00', NULL, NULL),
(16, 'Q-SVQVLT48', 1, 16, 1, 'Pending', NULL, '2025-05-04 10:30:00', '2025-05-04 10:30:00', NULL, NULL),
(17, 'Q-SVQVLT71', 5, 17, 1, 'Pending', NULL, '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL, NULL),
(18, 'Q-SVQVLT56', 1, 18, 1, 'Pending', NULL, '2025-05-04 11:30:00', '2025-05-04 11:30:00', NULL, NULL),
(19, 'Q-SVQVLT58', 6, 19, 1, 'Pending', NULL, '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL, NULL),
(20, 'Q-SVQVLT2B', 3, 20, 1, 'Pending', NULL, '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL, NULL),
(49, 'Q-SVQWBI5D', 10, 49, 2, 'Open', NULL, '2025-05-05 12:00:00', '2025-05-05 00:26:06', NULL, NULL),
(50, 'Q-SVQWML16', 10, 50, 2, 'Open', NULL, '2025-05-06 12:00:00', '2025-05-05 00:32:45', NULL, NULL);

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
(325, 1, 1, NULL, 'Pending', NULL),
(326, 1, 2, NULL, 'Pending', NULL),
(327, 1, 3, NULL, 'Pending', NULL),
(328, 2, 1, NULL, 'Pending', NULL),
(329, 2, 3, NULL, 'Pending', NULL),
(330, 2, 2, NULL, 'Pending', NULL),
(331, 3, 3, NULL, 'Pending', NULL),
(332, 3, 1, NULL, 'Pending', NULL),
(333, 3, 2, NULL, 'Pending', NULL),
(334, 4, 2, NULL, 'Pending', NULL),
(335, 4, 3, NULL, 'Pending', NULL),
(336, 4, 1, NULL, 'Pending', NULL),
(337, 5, 1, NULL, 'Pending', NULL),
(338, 5, 2, NULL, 'Pending', NULL),
(339, 5, 3, NULL, 'Pending', NULL),
(340, 6, 2, NULL, 'Pending', NULL),
(341, 6, 1, NULL, 'Pending', NULL),
(342, 6, 3, NULL, 'Pending', NULL),
(343, 7, 2, NULL, 'Pending', NULL),
(344, 7, 3, NULL, 'Pending', NULL),
(345, 7, 1, NULL, 'Pending', NULL),
(346, 8, 2, NULL, 'Pending', NULL),
(347, 8, 1, NULL, 'Pending', NULL),
(348, 8, 3, NULL, 'Pending', NULL),
(349, 9, 1, NULL, 'Pending', NULL),
(350, 9, 3, NULL, 'Pending', NULL),
(351, 9, 2, NULL, 'Pending', NULL),
(352, 10, 2, NULL, 'Pending', NULL),
(353, 10, 3, NULL, 'Pending', NULL),
(354, 10, 1, NULL, 'Pending', NULL),
(355, 11, 1, NULL, 'Pending', NULL),
(356, 11, 2, NULL, 'Pending', NULL),
(357, 11, 3, NULL, 'Pending', NULL),
(358, 12, 2, NULL, 'Pending', NULL),
(359, 12, 1, NULL, 'Pending', NULL),
(360, 12, 3, NULL, 'Pending', NULL),
(361, 13, 2, NULL, 'Pending', NULL),
(362, 13, 3, NULL, 'Pending', NULL),
(363, 13, 1, NULL, 'Pending', NULL),
(364, 14, 3, NULL, 'Pending', NULL),
(365, 14, 2, NULL, 'Pending', NULL),
(366, 14, 1, NULL, 'Pending', NULL),
(367, 15, 1, NULL, 'Pending', NULL),
(368, 15, 3, NULL, 'Pending', NULL),
(369, 15, 2, NULL, 'Pending', NULL),
(370, 16, 3, NULL, 'Pending', NULL),
(371, 16, 1, NULL, 'Pending', NULL),
(372, 16, 2, NULL, 'Pending', NULL),
(373, 17, 2, NULL, 'Pending', NULL),
(374, 17, 3, NULL, 'Pending', NULL),
(375, 17, 1, NULL, 'Pending', NULL),
(376, 18, 2, NULL, 'Pending', NULL),
(377, 18, 1, NULL, 'Pending', NULL),
(378, 18, 3, NULL, 'Pending', NULL),
(379, 19, 2, NULL, 'Pending', NULL),
(380, 19, 3, NULL, 'Pending', NULL),
(381, 19, 1, NULL, 'Pending', NULL),
(382, 20, 3, NULL, 'Pending', NULL),
(383, 20, 1, NULL, 'Pending', NULL),
(384, 20, 2, NULL, 'Pending', NULL),
(385, 49, 1, NULL, 'Pending', NULL),
(386, 50, 2, NULL, 'Pending', NULL);

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
(10, 'luffy01', 'monkey.d@luffy.com', '$2y$10$j50lJr0HRfGjfSXN5V3q5uIQJI5l5gC7AkNaXkiR1pNVIgHLVoewa', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '09276542449', '2025-05-04 16:05:41', '2025-02-20 16:10:26'),
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
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_scheduled_date` (`scheduled_date`),
  ADD KEY `idx_queue_status_scheduled` (`status`,`scheduled_date`);

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
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=387;

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
