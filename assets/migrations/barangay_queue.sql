-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 08:47 PM
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
  `activity` varchar(255) NOT NULL,
  `status` enum('Success','Failed','Pending') DEFAULT 'Success',
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `role_id`, `activity`, `status`, `reference_id`, `created_at`) VALUES
(1, 1, 2, 'Logged in', 'Success', NULL, '2025-05-13 16:31:18'),
(2, 3, 3, 'Assigned TXN1002', 'Success', 1, '2025-05-13 16:31:18');

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
(1, 'Maria Santos', 'msantos', 'maria@example.com', '$2y$10$examplehash1', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 'Juan dela Cruz', 'jdelacruz', 'juan@example.com', '$2y$10$examplehash2', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 'Super Admin', 'admin', 'admin@admin.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', '2025-02-11 08:59:47', '2025-03-08 06:59:19');

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
(1, 'Male', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 'Female', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 'Other', '2025-05-13 16:31:18', '2025-05-13 16:31:18');

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
(1, 'Single', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 'Married', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 'Widowed', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(4, 'Divorced', '2025-05-13 16:31:18', '2025-05-13 16:31:18');

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

--
-- Dumping data for table `message_logs`
--

INSERT INTO `message_logs` (`id`, `user_id`, `queue_number`, `message`, `status`, `created_at`) VALUES
(1, 1, 101, 'Your turn is coming up!', 'Sent', '2025-05-13 16:31:18'),
(2, 2, 102, 'Please proceed to window 1.', 'Sent', '2025-05-13 16:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `role_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 2, 'Queue Reminder', 'You are next in line.', 0, '2025-05-13 16:31:18'),
(2, 3, 3, 'New Assignment', 'Please attend to TXN1003.', 0, '2025-05-13 16:31:18');

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
(1, 'TXN1001', 1, 2, 'Pending', '2025-05-15 09:00:00', '2025-05-14 00:31:18', '2025-05-14 00:31:18', NULL),
(2, 'TXN1002', 2, 1, 'Assigned', '2025-05-14 10:30:00', '2025-05-14 00:31:18', '2025-05-14 00:31:18', 3),
(3, 'Q-SW7MPGCE', 13, 2, '', '2025-05-14 12:00:00', '2025-05-14 01:17:40', '2025-05-14 01:17:40', NULL),
(4, 'Q-SW7MQDA4', 13, 2, '', '2025-05-15 12:00:00', '2025-05-14 01:18:13', '2025-05-14 01:18:13', NULL),
(5, 'Q-SW7MZL3E', 13, 2, '', '2025-05-16 12:00:00', '2025-05-14 01:23:45', '2025-05-14 01:23:45', NULL),
(6, 'Q-SW7NQ8CE', 13, 2, '', '2025-05-19 09:00:00', '2025-05-14 01:39:44', '2025-05-14 01:39:44', NULL),
(7, 'Q-SW7NZL25', 13, 2, '', '2025-05-20 09:00:00', '2025-05-14 01:45:21', '2025-05-14 01:45:21', NULL),
(8, 'Q-SW7OG1C0', 13, 2, '', '2025-05-15 12:00:00', '2025-05-14 01:55:13', '2025-05-14 01:55:13', NULL);

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
(1, 1, 'Valid government-issued ID', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 1, 'Proof of address (utility bill)', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 2, 'Barangay Clearance application form', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(4, 3, 'Residency application form', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(5, 4, 'Community Tax form', '2025-05-13 16:31:18', '2025-05-13 16:31:18');

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
(1, 'Account Verification', 'Verify your account to unlock all services', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 'Barangay Clearance', 'Obtain a Barangay Clearance certificate', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 'Residency Certificate', 'Proof of residency document', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(4, 'Community Tax Certificate', 'Also known as Cedula', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(5, 'Business Permit Application', 'Process new business permit', '2025-05-13 16:31:18', '2025-05-13 16:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `value`, `updated_at`) VALUES
(1, 'booking_time_start', '06:00', '2025-05-09 14:44:52'),
(2, 'booking_time_end', '16:30', '2025-05-09 08:30:55'),
(4, 'no_show_timeout_minutes', '25', '2025-05-09 08:30:55'),
(5, 'sms_sender_name', 'Qpila', '2025-05-12 07:14:25'),
(6, 'max_transactions_per_day', '3', '2025-05-09 10:44:29'),
(7, 'enable_sms_notifications', '0', '2025-05-09 14:44:13'),
(8, 'minimum_booking_lead_time_minutes', '30', '2025-05-09 09:01:13'),
(9, 'staff_update_cutoff_time', '17:00', '2025-05-09 12:37:45'),
(10, 'staff_update_start_time', '04:00', '2025-05-09 12:45:56'),
(11, 'enable_saturday', '1', '2025-05-12 07:14:58'),
(12, 'saturday_start_time', '08:00', '2025-05-12 06:58:37'),
(13, 'saturday_end_time', '12:00', '2025-05-12 06:58:37');

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
(1, 'TXN1002', 2, 2, 1, 'Closed', 5, '2025-05-14 00:31:18', '2025-05-14 00:31:18', 3, '2025-05-14 11:00:00'),
(2, 'TXN1003', 1, 1, 2, 'Pending', NULL, '2025-05-14 00:31:18', '2025-05-14 00:55:28', 3, NULL),
(3, 'Q-SW7MPGCE', 13, 3, 2, 'Open', NULL, '2025-05-14 12:00:00', '2025-05-14 01:17:40', NULL, NULL),
(4, 'Q-SW7MQDA4', 13, 4, 2, 'Open', NULL, '2025-05-15 12:00:00', '2025-05-14 01:18:13', NULL, NULL),
(5, 'Q-SW7MZL3E', 13, 5, 2, 'Open', NULL, '2025-05-16 12:00:00', '2025-05-14 01:23:45', NULL, NULL),
(6, 'Q-SW7NQ8CE', 13, 6, 2, 'Open', NULL, '2025-05-19 09:00:00', '2025-05-14 01:39:44', NULL, NULL),
(7, 'Q-SW7NZL25', 13, 7, 2, 'Open', NULL, '2025-05-20 09:00:00', '2025-05-14 01:45:21', NULL, NULL),
(8, 'Q-SW7OG1C0', 13, 8, 2, 'Open', NULL, '2025-05-15 12:00:00', '2025-05-14 01:55:13', NULL, NULL);

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
(1, 1, 2, NULL, 'Closed', '2025-05-14 10:45:00'),
(2, 2, 1, NULL, 'Pending', NULL),
(3, 3, 2, NULL, 'Pending', NULL),
(4, 4, 2, NULL, 'Pending', NULL),
(5, 5, 2, NULL, 'Pending', NULL),
(6, 6, 2, NULL, 'Pending', NULL),
(7, 7, 2, NULL, 'Pending', NULL),
(8, 8, 5, NULL, 'Pending', NULL);

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
(1, 'johndoe', 'john.doe@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1990-05-20', '123 Main St.', 1, NULL, 3, 'John', 'A.', 'Doe', NULL, 1, '09171234567', '2025-05-13 16:37:12', '2025-05-13 16:31:18'),
(2, 'janesmith', 'jane.smith@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1985-11-02', '456 Elm St.', 0, NULL, 2, 'Jane', NULL, 'Smith', NULL, 2, '09179876543', '2025-05-13 16:34:44', '2025-05-13 16:31:18'),
(3, 'staff01', 'staff1@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1992-07-15', '789 Oak St.', 1, NULL, 3, 'Alice', 'B.', 'Staff', NULL, 1, '09170001111', '2025-05-13 16:34:44', '2025-05-13 16:31:18'),
(4, 'markjones', 'mark.jones@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1993-03-12', '12 Pine St.', 1, NULL, 2, 'Mark', 'T.', 'Jones', NULL, 1, '09171230004', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(5, 'lindagem', 'linda.g@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1988-08-22', '34 Cedar Ave.', 0, NULL, 2, 'Linda', NULL, 'Gonzalez', NULL, 2, '09171230005', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(6, 'robertlee', 'robert.lee@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1975-12-05', '56 Birch Rd.', 1, NULL, 2, 'Robert', 'C.', 'Lee', NULL, 3, '09171230006', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(7, 'emilybrown', 'emily.brown@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '2000-06-30', '78 Spruce Ln.', 0, NULL, 2, 'Emily', NULL, 'Brown', NULL, 1, '09171230007', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(8, 'davidmartin', 'david.martin@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1982-01-17', '90 Maple Blvd.', 1, NULL, 2, 'David', 'L.', 'Martin', NULL, 2, '09171230008', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(9, 'staff02', 'staff2@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1994-04-10', '23 Willow St.', 1, NULL, 3, 'Bob', NULL, 'Staff', NULL, 1, '09171230009', '2025-05-13 16:34:44', '2025-05-13 16:33:27'),
(10, 'staff03', 'staff3@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1991-09-25', '45 Poplar Dr.', 1, NULL, 3, 'Carol', 'D.', 'Staff', NULL, 1, '09171230010', '2025-05-13 16:34:44', '2025-05-13 16:33:27'),
(13, 'luffy01', 'monkey.d@luffy.com', '$2y$10$jFooeBX.AbfK9EF00RBKMOyCvXQ2s0FrezfzSx2TSQ934uNvPyHm6', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '639276542449', '2025-05-13 18:06:59', '2025-02-20 08:10:26'),
(14, 'johndoestaff', 'john.doe@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1990-05-15', '123 Main St, Barangay', 1, NULL, 3, 'John', 'A.', 'Doe', NULL, 1, '09171234567', '2025-03-16 00:43:40', '2025-03-16 00:40:46');

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
(1, 'Admin', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(2, 'User', '2025-05-13 16:31:18', '2025-05-13 16:31:18'),
(3, 'Staff', '2025-05-13 16:31:18', '2025-05-13 16:31:18');

-- --------------------------------------------------------

--
-- Structure for view `user_details`
--
DROP TABLE IF EXISTS `user_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_details`  AS SELECT `u`.`id` AS `id`, concat(`u`.`first_name`,' ',`u`.`middle_name`,' ',`u`.`last_name`,' ',`u`.`suffix`) AS `full_name`, `u`.`email` AS `email`, `u`.`username` AS `username`, `u`.`profile_picture` AS `profile_picture`, `u`.`address` AS `address`, `u`.`mobile_number` AS `mobile_number`, `u`.`birthdate` AS `birthdate`, (select `g`.`gender_name` from `genders` `g` where `g`.`id` = `u`.`gender_id` limit 1) AS `gender`, (select `m`.`status_name` from `marital_statuses` `m` where `m`.`id` = `u`.`marital_status_id` limit 1) AS `marital_status_name`, `u`.`is_verified` AS `is_verified` FROM `users` AS `u` WHERE `u`.`role_id` = 2 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admins_email` (`email`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marital_statuses`
--
ALTER TABLE `marital_statuses`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message_logs`
--
ALTER TABLE `message_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
