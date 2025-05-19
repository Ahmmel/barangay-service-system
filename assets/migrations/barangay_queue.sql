-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 07:04 AM
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
  `entity_type` varchar(50) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `status` enum('Success','Failed','Pending') NOT NULL DEFAULT 'Success',
  `reference_id` int(11) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `role_id`, `entity_type`, `activity`, `status`, `reference_id`, `meta`, `created_at`) VALUES
(1, 13, NULL, 'auth', 'Successful login for user \'luffy01\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-15 20:12:21'),
(2, NULL, NULL, 'auth', 'Failed login for user \'luffy012\'', 'Failed', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-15 20:12:36'),
(3, 16, NULL, 'auth', 'Successful login for user \'luffy012\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-15 20:12:41'),
(4, 1, NULL, 'auth', 'Successful login for admin/staff \'johndoe\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-16 00:58:34'),
(5, 1, 3, 'auth', 'User \'johndoe\' (ID: 1) logged out', 'Success', 1, NULL, '2025-05-16 00:58:42'),
(6, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-16 00:58:46'),
(7, 19, 2, 'user', 'User \'test02131\' registered', 'Success', 19, '{\"email\":\"test02131@test02131.com\"}', '2025-05-16 01:00:13'),
(8, 19, NULL, 'auth', 'Successful login for user \'test02131@test02131.com\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-16 01:01:31'),
(9, 3, NULL, 'auth', 'User \'admin\' (ID: 3) logged out', 'Success', 3, NULL, '2025-05-16 01:01:49'),
(10, 1, NULL, 'auth', 'Successful login for admin/staff \'johndoe\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-16 01:01:54'),
(11, 1, 3, 'user', 'Edited user \'test02131\' (ID: 19)', 'Success', 19, '{\"changes\":{\"Gender\":{\"old\":null,\"new\":2},\"Marital Status\":{\"old\":null,\"new\":2},\"Address\":{\"old\":\"\",\"new\":\"asd\"}}}', '2025-05-16 01:02:56'),
(12, 19, 2, 'transaction', 'Created Scheduled transaction #Q-SWBXN7A5 for user ID 19', 'Success', 1, '{\"services\":[\"1\"],\"scheduled_at\":\"2025-05-16 09:20\"}', '2025-05-16 01:04:19'),
(13, 1, 3, 'transaction', 'Updated transaction (ID: 1) status to Closed', 'Success', 1, '{\"status\":\"Closed\",\"reason\":\"ok\"}', '2025-05-16 01:04:41'),
(14, 1, 3, 'user', 'Edited user \'test02131\' (ID: 19)', 'Success', 19, '{\"changes\":{\"Gender\":{\"old\":null,\"new\":2},\"Marital Status\":{\"old\":null,\"new\":2},\"Verification Status\":{\"old\":0,\"new\":1}}}', '2025-05-16 01:04:55'),
(15, 19, NULL, 'auth', 'Successful login for user \'test02131@test02131.com\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-16 01:05:08'),
(16, NULL, 2, 'transaction', 'Rated transaction (ID: Q-SWBXN7A5) with 5 stars', 'Success', NULL, '{\"rating\":\"5\"}', '2025-05-16 01:22:51'),
(17, 20, 2, 'user', 'User \'sdfzxczxc\' registered', 'Success', 20, '{\"email\":\"zxczxc@zxczxc.com\"}', '2025-05-17 02:47:44');

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
(1, 'Maria Santos', 'msantos', 'maria@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', '2025-05-13 16:31:18', '2025-05-14 11:00:10'),
(2, 'Juan dela Cruz', 'jdelacruz', 'juan@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', '2025-05-13 16:31:18', '2025-05-14 11:00:08'),
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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `role_id`, `type`, `reference_id`, `title`, `message`, `is_read`, `created_at`, `read_at`) VALUES
(1, 1, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-16 00:58:34', NULL),
(2, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-16 00:58:46', NULL),
(3, 19, NULL, 'welcome', 19, 'Welcome to QPILA', 'Hi tesasdt, your account has been created! Please verify your email to get started.', 0, '2025-05-16 01:00:13', NULL),
(4, NULL, 2, 'new_registration', 19, 'New User Registered', 'User \'test02131\' (ID 19) just signed up.', 0, '2025-05-16 01:00:13', NULL),
(5, 1, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-16 01:01:54', NULL),
(6, 19, NULL, 'account_updated', 19, 'Profile Updated', 'Your profile was updated by johndoe. Changed: Gender, Marital Status, Address. If you didn\'t request this, please contact support.', 0, '2025-05-16 01:02:56', NULL),
(7, 19, 2, 'booking_confirmed', 1, 'Scheduled Booking Confirmed', 'Your booking (ID: Q-SWBXN7A5) for services [1] on 2025-05-16 09:20 has been confirmed.', 0, '2025-05-16 01:04:19', NULL),
(8, 1, 3, 'transaction_status_updated', 1, 'Transaction Status Updated', 'Transaction (ID: 1) status has been updated to Closed.', 0, '2025-05-16 01:04:41', NULL),
(9, 19, NULL, 'account_updated', 19, 'Profile Updated', 'Your profile was updated by johndoe. Changed: Gender, Marital Status, Verification Status. If you didn\'t request this, please contact support.', 0, '2025-05-16 01:04:55', NULL),
(10, NULL, 2, 'transaction_rated', NULL, 'Transaction Rated', 'Your transaction (ID: Q-SWBXN7A5) has been rated with 5 stars.', 0, '2025-05-16 01:22:01', NULL),
(11, NULL, 2, 'transaction_rated', NULL, 'Transaction Rated', 'Your transaction (ID: Q-SWBXN7A5) has been rated with 5 stars.', 0, '2025-05-16 01:22:51', NULL),
(12, 20, NULL, 'welcome', 20, 'Welcome to QPILA', 'Hi test, your account has been created! Please verify your email to get started.', 0, '2025-05-17 02:47:44', NULL),
(13, NULL, 2, 'new_registration', 20, 'New User Registered', 'User \'sdfzxczxc\' (ID 20) just signed up.', 0, '2025-05-17 02:47:44', NULL);

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
(1, 'Q-SWBXN7A5', 19, 2, 'Assigned', '2025-05-16 09:20:00', '2025-05-16 09:04:19', '2025-05-16 09:04:41', 1);

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
(7, 'enable_sms_notifications', '0', '2025-05-16 00:58:53'),
(8, 'minimum_booking_lead_time_minutes', '10', '2025-05-15 16:08:46'),
(9, 'staff_update_cutoff_time', '23:59', '2025-05-14 13:55:18'),
(10, 'staff_update_start_time', '04:00', '2025-05-09 12:45:56'),
(11, 'enable_saturday', '0', '2025-05-15 15:30:56'),
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
(1, 'Q-SWBXN7A5', 19, 1, 2, 'Closed', 5, '2025-05-16 09:20:00', '2025-05-16 09:20:17', 1, '2025-05-16 09:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_services`
--

CREATE TABLE `transaction_services` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','ToBeFollowed','Cancelled','Closed') NOT NULL DEFAULT 'Pending',
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_services`
--

INSERT INTO `transaction_services` (`id`, `transaction_id`, `service_id`, `reason`, `status`, `completed_at`) VALUES
(1, 1, 1, 'ok', 'Closed', '2025-05-16 09:04:41');

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
(2, 'janesmith1', 'jane.smith1@example.com', '$2y$10$MkS3Xej4Q3GL0hGlHiCox.aX9HnOrYckN7DbZWk0spP2K5SF7FXim', 3, '1985-11-02', '456 Elm St. some', 1, '../uploads/profile_682456a23fed97.42166748.png', 1, 'Janeth', 'Shu', 'Smit', 'II', 2, '09179876542', '2025-05-14 08:41:34', '2025-05-13 16:31:18'),
(3, 'staff01', 'staff1@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1992-07-15', '789 Oak St.', 1, NULL, 3, 'Alice', 'B.', 'Staff', NULL, 1, '09170001111', '2025-05-13 16:34:44', '2025-05-13 16:31:18'),
(4, 'markjones', 'mark.jones@example.com', '$2y$10$uvgiKyFaUNEDuk5/kjHYVel46VHmc/oq2s.rGB.rpyTO3.lz.i56i', 1, '1993-03-12', '12 Pine St.', 1, NULL, 2, 'Mark', 'T.', 'Jones', NULL, 1, '09276542422', '2025-05-15 18:07:02', '2025-05-13 16:33:01'),
(5, 'lindagem', 'linda.g@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1988-08-22', '34 Cedar Ave.', 0, NULL, 2, 'Linda', NULL, 'Gonzalez', NULL, 2, '09171230005', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(6, 'robertlee', 'robert.lee@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1975-12-05', '56 Birch Rd.', 1, NULL, 2, 'Robert', 'C.', 'Lee', NULL, 3, '09171230006', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(7, 'emilybrown', 'emily.brown@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '2000-06-30', '78 Spruce Ln.', 0, NULL, 2, 'Emily', NULL, 'Brown', NULL, 1, '09171230007', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(8, 'davidmartin', 'david.martin@example.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1982-01-17', '90 Maple Blvd.', 1, NULL, 2, 'David', 'L.', 'Martin', NULL, 2, '09171230008', '2025-05-13 16:34:44', '2025-05-13 16:33:01'),
(9, 'staff02', 'staff2@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1994-04-10', '23 Willow St.', 1, NULL, 3, 'Bob', NULL, 'Staff', NULL, 1, '09171230009', '2025-05-13 16:34:44', '2025-05-13 16:33:27'),
(10, 'staff03', 'staff3@qpila.local', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1991-09-25', '45 Poplar Dr.', 1, NULL, 3, 'Carol', 'D.', 'Staff', NULL, 1, '09171230010', '2025-05-13 16:34:44', '2025-05-13 16:33:27'),
(13, 'luffy01', 'monkey.d@luffy.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '639274542449', '2025-05-15 18:05:42', '2025-02-20 08:10:26'),
(14, 'johndoestaff', 'john.doe@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1990-05-15', '123 Main St, Barangay', 1, NULL, 3, 'John', 'A.', 'Doe', NULL, 1, '09171234567', '2025-03-16 00:43:40', '2025-03-16 00:40:46'),
(16, 'luffy012', 'john.do2e@example.com', '$2y$10$NL2ZVHptchtWp/RsuYrCEOFkBZ6uxT0a2rDgvQiWnoYcM2g6rLBMK', 1, '2025-05-16', '', 0, NULL, 2, 'testt', NULL, 'testt', NULL, 1, '09171234524', '2025-05-15 16:06:31', '2025-05-15 16:06:31'),
(17, 'Zoro01', 'zoro@roronoa.com', '$2y$10$v1lnFIsrb5YrRClM6bb0peTRwIA59RPT/ad5lI2mv5n6pDTax5oHy', 1, '1992-05-13', '', 0, NULL, 2, 'Zoro', NULL, 'Roronoa', NULL, 1, '09276542441', '2025-05-15 18:12:04', '2025-05-15 18:07:27'),
(18, 'final', 'final@test.com', '$2y$10$xmM.45zsmDbHdz2JJBXzje9b9SidjgqiDDGsXFT5PkmtalpKyMyh2', 1, '2025-05-16', '', 0, NULL, 2, 'Final', NULL, 'Test', NULL, 1, '09276542449', '2025-05-15 18:13:15', '2025-05-15 18:13:15'),
(19, 'test02131', 'test02131@test02131.com', '$2y$10$YTAdljlhEBjBdmp5m1KEKuRfHofDgfkL4snR2JVcW4ecSDEgS1wLi', 2, '2000-05-14', 'asd', 1, NULL, 2, 'tesasdt', '', 'testdfghb', '', 2, '09276542332', '2025-05-16 01:04:55', '2025-05-16 01:00:13'),
(20, 'sdfzxczxc', 'zxczxc@zxczxc.com', '$2y$10$M3ym35VaFAD81Tg5.mndXuu/cRVEgeMWmh60UiJZpgwES6GdvI/Vq', 1, '2025-05-16', '', 0, NULL, 2, 'test', NULL, 'test', NULL, 1, '12345564534', '2025-05-17 02:47:44', '2025-05-17 02:47:44');

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_details`  AS SELECT `u`.`id` AS `id`, concat(ifnull(`u`.`first_name`,''),' ',ifnull(`u`.`middle_name`,''),' ',ifnull(`u`.`last_name`,''),' ',ifnull(`u`.`suffix`,'')) AS `full_name`, `u`.`email` AS `email`, `u`.`username` AS `username`, `u`.`profile_picture` AS `profile_picture`, `u`.`address` AS `address`, `u`.`mobile_number` AS `mobile_number`, `u`.`birthdate` AS `birthdate`, (select `g`.`gender_name` from `genders` `g` where `g`.`id` = `u`.`gender_id` limit 1) AS `gender`, (select `m`.`status_name` from `marital_statuses` `m` where `m`.`id` = `u`.`marital_status_id` limit 1) AS `marital_status_name`, `u`.`is_verified` AS `is_verified` FROM `users` AS `u` WHERE `u`.`role_id` = 2 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_user_ref` (`user_id`,`reference_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user_read` (`user_id`,`is_read`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

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
