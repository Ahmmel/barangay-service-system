-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 06:01 PM
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
(1, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 07:49:45'),
(2, NULL, NULL, 'user', 'Edited user \'janesmith1\' (ID: 2)', 'Success', 2, '{\"changes\":{\"First Name\":{\"old\":\"Jane\",\"new\":\"Janeth\"},\"Middle Name\":{\"old\":null,\"new\":\"Shu\"},\"Last Name\":{\"old\":\"Smith\",\"new\":\"Smit\"},\"Suffix\":{\"old\":null,\"new\":\"II\"},\"Username\":{\"old\":\"janesmith\",\"new\":\"janesmith1\"},\"Email Address\":{\"old\":\"jane.smith@example.com\",\"new\":\"jane.smith1@example.com\"},\"Gender\":{\"old\":null,\"new\":3},\"Marital Status\":{\"old\":null,\"new\":1},\"Mobile Number\":{\"old\":\"09179876543\",\"new\":\"09179876542\"},\"Address\":{\"old\":\"456 Elm St.\",\"new\":\"456 Elm St. some\"},\"Verification Status\":{\"old\":0,\"new\":1},\"Password\":{\"old\":\"(hidden)\",\"new\":\"(updated)\"},\"Profile Picture\":{\"old\":null,\"new\":\"..\\/uploads\\/profile_682456a23fed97.42166748.png\"}}}', '2025-05-14 08:38:58'),
(3, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 09:06:30'),
(4, NULL, NULL, 'user', 'Created user \'jda\' (ID: 15)', 'Success', 15, '{\"email\":\"test@test.com\",\"role_id\":2}', '2025-05-14 09:12:00'),
(5, 3, NULL, 'user', 'Edited user \'jdaa\' (ID: 15)', 'Success', 15, '{\"changes\":{\"Gender\":{\"old\":null,\"new\":1},\"Marital Status\":{\"old\":null,\"new\":1}}}', '2025-05-14 09:17:16'),
(6, 3, NULL, 'user', 'Deleted user “jdaa” (ID: 15)', 'Success', 15, NULL, '2025-05-14 09:28:58'),
(7, 3, NULL, 'service', 'Created service “test” (ID: 8)', 'Success', 8, NULL, '2025-05-14 09:51:58'),
(8, 3, NULL, 'service', 'Deleted service “test” (ID: 8)', 'Success', 8, NULL, '2025-05-14 09:52:07'),
(9, 3, NULL, 'service', 'Deleted service “test” (ID: 7)', 'Success', 7, NULL, '2025-05-14 09:52:12'),
(10, 3, NULL, 'service', 'Deleted service “test” (ID: 6)', 'Success', 6, NULL, '2025-05-14 09:52:15'),
(11, 3, NULL, 'service', 'Created service “test” (ID: 9)', 'Success', 9, NULL, '2025-05-14 09:52:26'),
(12, 3, NULL, 'service', 'Updated service “test54” (ID: 9)', 'Success', 9, '{\"changes\":{\"Name\":{\"old\":\"test\",\"new\":\"test54\"},\"Description\":{\"old\":\"test\",\"new\":\"test32\"}}}', '2025-05-14 09:52:43'),
(13, 3, NULL, 'service', 'Deleted service “test54” (ID: 9)', 'Success', 9, NULL, '2025-05-14 09:52:47'),
(14, NULL, NULL, 'requirement', 'Updated requirement ID 6', 'Success', 6, '{\"changes\":{\"Description\":{\"old\":\"test\",\"new\":\"test22\"}}}', '2025-05-14 10:00:24'),
(15, NULL, NULL, 'requirement', 'Deleted requirement \'test22\' (ID: 6)', 'Success', 6, NULL, '2025-05-14 10:00:29'),
(16, 3, NULL, 'transaction', 'Updated transaction (ID: 4) status to Closed', 'Success', 4, '{\"status\":\"Closed\",\"reason\":\"done!\"}', '2025-05-14 10:49:02'),
(17, 3, NULL, 'auth', 'Successful login for admin/staff \'staff1@qpila.local\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 11:10:56'),
(18, 3, 3, 'auth', 'User \'staff01\' (ID: 3) logged out', 'Success', 3, NULL, '2025-05-14 11:18:19'),
(19, 3, NULL, 'auth', 'Successful login for admin/staff \'staff1@qpila.local\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 11:19:30'),
(20, 3, 3, 'auth', 'User \'staff01\' (ID: 3) logged out', 'Success', 3, NULL, '2025-05-14 11:37:12'),
(21, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 11:37:16'),
(22, 3, NULL, 'auth', 'User \'admin\' (ID: 3) logged out', 'Success', 3, NULL, '2025-05-14 11:54:15'),
(23, 3, NULL, 'auth', 'Successful login for admin/staff \'staff1@qpila.local\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 12:16:56'),
(24, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 12:25:48'),
(25, 3, 3, 'transaction', 'Updated transaction (ID: 21) status to Closed', 'Success', 21, '{\"status\":\"Closed\",\"reason\":\"DONE!\"}', '2025-05-14 12:57:30'),
(26, 3, 3, 'transaction', 'Updated transaction (ID: 22) status to Closed', 'Success', 22, '{\"status\":\"Closed\",\"reason\":\"nice\"}', '2025-05-14 13:00:35'),
(27, 3, 3, 'transaction', 'Updated transaction (ID: 3) status to Closed', 'Success', 3, '{\"status\":\"Closed\",\"reason\":\"Okay\"}', '2025-05-14 13:07:05'),
(28, 3, 3, 'transaction', 'Updated transaction (ID: 23) status to Cancelled', 'Success', 23, '{\"status\":\"Cancelled\",\"reason\":\"cancelled\"}', '2025-05-14 13:12:00'),
(29, 3, 3, 'transaction', 'Updated transaction (ID: 24) status to Cancelled', 'Success', 24, '{\"status\":\"Cancelled\",\"reason\":\"sadf\"}', '2025-05-14 13:14:03'),
(30, 3, 3, 'transaction', 'Updated transaction (ID: 25) status to Cancelled', 'Success', 25, '{\"status\":\"Cancelled\",\"reason\":\"asdgfg\"}', '2025-05-14 13:15:04'),
(31, 3, 3, 'transaction', 'Updated transaction (ID: 26) status to Closed', 'Success', 26, '{\"status\":\"Closed\",\"reason\":\"OK\"}', '2025-05-14 13:16:58'),
(32, 3, 3, 'transaction', 'Updated transaction (ID: 27) status to Closed', 'Success', 27, '{\"status\":\"Closed\",\"reason\":\"done!!\"}', '2025-05-14 13:17:43'),
(33, 3, 3, 'transaction', 'Updated transaction (ID: 28) status to Closed', 'Success', 28, '{\"status\":\"Closed\",\"reason\":\"DON!\"}', '2025-05-14 13:18:09'),
(34, 3, 3, 'transaction', 'Updated transaction (ID: 9) status to Closed', 'Success', 9, '{\"status\":\"Closed\",\"reason\":\"ok\"}', '2025-05-14 13:22:26'),
(35, 3, 3, 'transaction', 'Updated transaction (ID: 10) status to Cancelled', 'Success', 10, '{\"status\":\"Cancelled\",\"reason\":\"asd\"}', '2025-05-14 13:26:04'),
(36, 3, 3, 'transaction', 'Updated transaction (ID: 12) status to Closed', 'Success', 12, '{\"status\":\"Closed\",\"reason\":\"OK!\"}', '2025-05-14 13:27:05'),
(37, 3, 3, 'transaction', 'Updated transaction (ID: 13) status to Cancelled', 'Success', 13, '{\"status\":\"Cancelled\",\"reason\":\"tsk\"}', '2025-05-14 13:29:00'),
(38, 3, 3, 'transaction', 'Updated transaction (ID: 14) status to Closed', 'Success', 14, '{\"status\":\"Closed\",\"reason\":\"test\"}', '2025-05-14 13:34:13'),
(39, 3, 3, 'transaction', 'Updated transaction (ID: 15) status to Closed', 'Success', 15, '{\"status\":\"Closed\",\"reason\":\"test\"}', '2025-05-14 13:42:34'),
(40, 3, NULL, 'auth', 'Successful login for admin/staff \'admin\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 13:55:04'),
(41, 3, NULL, 'auth', 'Successful login for admin/staff \'staff1@qpila.local\'', 'Success', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/136.0.0.0 Safari\\/537.36\"}', '2025-05-14 13:56:07'),
(42, 3, 3, 'transaction', 'Updated transaction (ID: 16) status to Closed', 'Success', 16, '{\"status\":\"Closed\",\"reason\":\"dfsdf\"}', '2025-05-14 13:56:26'),
(43, 3, 3, 'transaction', 'Updated transaction (ID: 19) status to Closed', 'Success', 19, '{\"status\":\"Closed\",\"reason\":\"ad\"}', '2025-05-14 13:58:53'),
(44, 3, 3, 'transaction', 'Updated transaction (ID: 9) status to Cancelled', 'Success', 9, '{\"status\":\"Cancelled\",\"reason\":\"asd\"}', '2025-05-14 13:59:08'),
(45, 3, 3, 'transaction', 'Updated transaction (ID: 10) status to Cancelled', 'Success', 10, '{\"status\":\"Cancelled\",\"reason\":\"test\"}', '2025-05-14 14:06:56'),
(46, 3, 3, 'transaction', 'Updated transaction (ID: 11) status to Closed', 'Success', 11, '{\"status\":\"Closed\",\"reason\":\"asdfsdfa\"}', '2025-05-14 14:07:37'),
(47, 3, 3, 'transaction', 'Updated transaction (ID: 12) status to Cancelled', 'Success', 12, '{\"status\":\"Cancelled\",\"reason\":\"test\"}', '2025-05-14 14:20:07'),
(48, 3, 3, 'transaction', 'Updated transaction (ID: 20) status to Closed', 'Success', 20, '{\"status\":\"Closed\",\"reason\":\"asd\"}', '2025-05-14 14:21:07'),
(49, 3, 3, 'transaction', 'Updated transaction (ID: 21) status to Closed', 'Success', 21, '{\"status\":\"Closed\",\"reason\":\"test\"}', '2025-05-14 14:22:23'),
(50, 3, 3, 'transaction', 'Updated transaction (ID: 22) status to Closed', 'Success', 22, '{\"status\":\"Closed\",\"reason\":\"asd\"}', '2025-05-14 14:33:50'),
(51, 3, 3, 'transaction', 'Updated transaction (ID: 16) status to Closed', 'Success', 16, '{\"status\":\"Closed\",\"reason\":\"testt\"}', '2025-05-14 14:46:27'),
(52, 3, 3, 'transaction', 'Updated transaction (ID: 17) status to Cancelled', 'Success', 17, '{\"status\":\"Cancelled\",\"reason\":\"ad\"}', '2025-05-14 14:46:48'),
(53, 3, 3, 'transaction', 'Updated transaction (ID: 18) status to Closed', 'Success', 18, '{\"status\":\"Closed\",\"reason\":\"asd\"}', '2025-05-14 14:47:24'),
(54, 3, 3, 'transaction', 'Updated transaction (ID: 2) status to Closed', 'Success', 2, '{\"status\":\"Closed\",\"reason\":\"asd\"}', '2025-05-14 14:48:05'),
(55, 3, 3, 'transaction', 'Updated transaction (ID: 19) status to Closed', 'Success', 19, '{\"status\":\"Closed\",\"reason\":\"test\"}', '2025-05-14 14:54:03'),
(56, 3, 3, 'transaction', 'Updated transaction (ID: 20) status to Cancelled', 'Success', 20, '{\"status\":\"Cancelled\",\"reason\":\"test\"}', '2025-05-14 15:00:51'),
(57, 3, 3, 'transaction', 'Updated transaction (ID: 22) status to Closed', 'Success', 22, '{\"status\":\"Closed\",\"reason\":\"test\"}', '2025-05-14 15:12:08'),
(58, 3, 3, 'transaction', 'Updated transaction (ID: 23) status to Cancelled', 'Success', 23, '{\"status\":\"Cancelled\",\"reason\":\"testtt\"}', '2025-05-14 15:13:11'),
(59, 3, 3, 'transaction', 'Updated transaction (ID: 24) status to Closed', 'Success', 24, '{\"status\":\"Closed\",\"reason\":\"dd\"}', '2025-05-14 15:13:21'),
(60, 3, 3, 'transaction', 'Updated transaction (ID: 10) status to Closed', 'Success', 10, '{\"status\":\"Closed\",\"reason\":\"asd\"}', '2025-05-14 15:13:50'),
(61, 3, 3, 'transaction', 'Updated transaction (ID: 11) status to Pending', 'Success', 11, '{\"status\":\"Pending\",\"reason\":\"test\"}', '2025-05-14 15:22:50'),
(62, 3, 3, 'transaction', 'Updated transaction (ID: 12) status to ToBeFollowed', 'Success', 12, '{\"status\":\"ToBeFollowed\",\"reason\":\"test\"}', '2025-05-14 15:25:37'),
(63, 3, 3, 'transaction', 'Updated transaction (ID: 13) status to ToBeFollowed', 'Success', 13, '{\"status\":\"ToBeFollowed\",\"reason\":\"tbd\"}', '2025-05-14 15:27:18'),
(64, 3, 3, 'transaction', 'Updated transaction (ID: 14) status to Cancelled', 'Success', 14, '{\"status\":\"Cancelled\",\"reason\":\"test\"}', '2025-05-14 15:28:08'),
(65, 3, 3, 'transaction', 'Updated transaction (ID: 15) status to ToBeFollowed', 'Success', 15, '{\"status\":\"ToBeFollowed\",\"reason\":\"tbd\"}', '2025-05-14 15:29:38'),
(66, 3, 3, 'transaction', 'Updated transaction (ID: 16) status to ToBeFollowed', 'Success', 16, '{\"status\":\"ToBeFollowed\",\"reason\":\"asdg\"}', '2025-05-14 15:30:27'),
(67, 3, 3, 'transaction', 'Updated transaction (ID: 17) status to ToBeFollowed', 'Success', 17, '{\"status\":\"ToBeFollowed\",\"reason\":\"test\"}', '2025-05-14 15:33:09'),
(68, 3, 3, 'transaction', 'Updated transaction (ID: 18) status to ToBeFollowed', 'Success', 18, '{\"status\":\"ToBeFollowed\",\"reason\":\"TBD\"}', '2025-05-14 15:33:54'),
(69, 3, 3, 'transaction', 'Updated transaction (ID: 2) status to ToBeFollowed', 'Success', 2, '{\"status\":\"ToBeFollowed\",\"reason\":\"asd\"}', '2025-05-14 15:34:13');

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
(1, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 07:49:45', NULL),
(2, 2, NULL, 'account_updated', 2, 'Profile Updated', 'Your profile was updated by An administrator. Changed: First Name, Middle Name, Last Name, Suffix, Username, Email Address, Gender, Marital Status, Mobile Number, Address, Verification Status, Password, Profile Picture. If you didn\'t request this, please contact support.', 0, '2025-05-14 08:38:58', NULL),
(3, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 09:06:30', NULL),
(4, 15, NULL, 'account_created', 15, 'Welcome to QPILA', 'Hi Johnes, your account has been created successfully.', 0, '2025-05-14 09:12:00', NULL),
(5, NULL, 2, 'new_user_registered', 15, 'New User Registered', 'Admin (ID: ) added user \'jda\' (ID: 15).', 0, '2025-05-14 09:12:00', NULL),
(6, 15, NULL, 'account_updated', 15, 'Profile Updated', 'Your profile was updated by admin. Changed: Username, Gender, Marital Status. If you didn\'t request this, please contact support.', 0, '2025-05-14 09:13:40', NULL),
(7, 15, NULL, 'account_updated', 15, 'Profile Updated', 'Your profile was updated by admin. Changed: Gender, Marital Status. If you didn\'t request this, please contact support.', 0, '2025-05-14 09:13:41', NULL),
(8, 15, NULL, 'account_updated', 15, 'Profile Updated', 'Your profile was updated by admin. Changed: Gender, Marital Status. If you didn\'t request this, please contact support.', 0, '2025-05-14 09:17:16', NULL),
(9, NULL, 2, 'user_deleted', 15, 'User Removed', 'User “jdaa” (ID: 15) was removed by admin.', 0, '2025-05-14 09:28:58', NULL),
(10, NULL, 2, 'service_created', 8, 'New Service Added', 'Service “test” (ID: 8) was created by admin.', 0, '2025-05-14 09:51:58', NULL),
(11, NULL, 2, 'service_deleted', 8, 'Service Deleted', 'Service “test” (ID: 8) was deleted by admin.', 0, '2025-05-14 09:52:07', NULL),
(12, NULL, 2, 'service_deleted', 7, 'Service Deleted', 'Service “test” (ID: 7) was deleted by admin.', 0, '2025-05-14 09:52:12', NULL),
(13, NULL, 2, 'service_deleted', 6, 'Service Deleted', 'Service “test” (ID: 6) was deleted by admin.', 0, '2025-05-14 09:52:15', NULL),
(14, NULL, 2, 'service_created', 9, 'New Service Added', 'Service “test” (ID: 9) was created by admin.', 0, '2025-05-14 09:52:26', NULL),
(15, NULL, 2, 'service_updated', 9, 'Service Updated', 'Service “test54” (ID: 9) was updated by admin.', 0, '2025-05-14 09:52:43', NULL),
(16, NULL, 2, 'service_deleted', 9, 'Service Deleted', 'Service “test54” (ID: 9) was deleted by admin.', 0, '2025-05-14 09:52:47', NULL),
(17, NULL, 2, 'requirement_updated', 6, 'Requirement Updated', 'Requirement (ID: 6) was updated by An administrator.', 0, '2025-05-14 10:00:24', NULL),
(18, NULL, 2, 'requirement_deleted', 6, 'Requirement Deleted', 'Requirement \'test22\' (ID: 6) was deleted by An administrator.', 0, '2025-05-14 10:00:29', NULL),
(19, 3, NULL, 'transaction_status_updated', 3, 'Transaction Status Updated', 'Transaction (ID: 3) status has been updated to Closed.', 0, '2025-05-14 10:47:37', NULL),
(20, 3, NULL, 'transaction_status_updated', 4, 'Transaction Status Updated', 'Transaction (ID: 4) status has been updated to Closed.', 0, '2025-05-14 10:49:02', NULL),
(21, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 11:10:56', NULL),
(22, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 11:19:30', NULL),
(23, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 11:37:16', NULL),
(24, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 12:16:56', NULL),
(25, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 12:25:48', NULL),
(26, 3, 3, 'transaction_status_updated', 21, 'Transaction Status Updated', 'Transaction (ID: 21) status has been updated to Closed.', 0, '2025-05-14 12:57:30', NULL),
(27, 3, 3, 'transaction_status_updated', 22, 'Transaction Status Updated', 'Transaction (ID: 22) status has been updated to Closed.', 0, '2025-05-14 13:00:35', NULL),
(28, 3, 3, 'transaction_status_updated', 3, 'Transaction Status Updated', 'Transaction (ID: 3) status has been updated to Closed.', 0, '2025-05-14 13:07:05', NULL),
(29, 3, 3, 'transaction_status_updated', 23, 'Transaction Status Updated', 'Transaction (ID: 23) status has been updated to Cancelled.', 0, '2025-05-14 13:12:00', NULL),
(30, 3, 3, 'transaction_status_updated', 24, 'Transaction Status Updated', 'Transaction (ID: 24) status has been updated to Cancelled.', 0, '2025-05-14 13:14:03', NULL),
(31, 3, 3, 'transaction_status_updated', 25, 'Transaction Status Updated', 'Transaction (ID: 25) status has been updated to Cancelled.', 0, '2025-05-14 13:15:04', NULL),
(32, 3, 3, 'transaction_status_updated', 26, 'Transaction Status Updated', 'Transaction (ID: 26) status has been updated to Closed.', 0, '2025-05-14 13:16:58', NULL),
(33, 3, 3, 'transaction_status_updated', 27, 'Transaction Status Updated', 'Transaction (ID: 27) status has been updated to Closed.', 0, '2025-05-14 13:17:43', NULL),
(34, 3, 3, 'transaction_status_updated', 28, 'Transaction Status Updated', 'Transaction (ID: 28) status has been updated to Closed.', 0, '2025-05-14 13:18:09', NULL),
(35, 3, 3, 'transaction_status_updated', 9, 'Transaction Status Updated', 'Transaction (ID: 9) status has been updated to Closed.', 0, '2025-05-14 13:22:26', NULL),
(36, 3, 3, 'transaction_status_updated', 10, 'Transaction Status Updated', 'Transaction (ID: 10) status has been updated to Cancelled.', 0, '2025-05-14 13:26:04', NULL),
(37, 3, 3, 'transaction_status_updated', 12, 'Transaction Status Updated', 'Transaction (ID: 12) status has been updated to Closed.', 0, '2025-05-14 13:27:05', NULL),
(38, 3, 3, 'transaction_status_updated', 13, 'Transaction Status Updated', 'Transaction (ID: 13) status has been updated to Cancelled.', 0, '2025-05-14 13:29:00', NULL),
(39, 3, 3, 'transaction_status_updated', 14, 'Transaction Status Updated', 'Transaction (ID: 14) status has been updated to Closed.', 0, '2025-05-14 13:34:13', NULL),
(40, 3, 3, 'transaction_status_updated', 15, 'Transaction Status Updated', 'Transaction (ID: 15) status has been updated to Closed.', 0, '2025-05-14 13:42:34', NULL),
(41, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 13:55:04', NULL),
(42, 3, NULL, 'login_success', NULL, 'New Sign-In', 'You just signed in from IP ::1.', 0, '2025-05-14 13:56:07', NULL),
(43, 3, 3, 'transaction_status_updated', 16, 'Transaction Status Updated', 'Transaction (ID: 16) status has been updated to Closed.', 0, '2025-05-14 13:56:26', NULL),
(44, 3, 3, 'transaction_status_updated', 19, 'Transaction Status Updated', 'Transaction (ID: 19) status has been updated to Closed.', 0, '2025-05-14 13:58:53', NULL),
(45, 3, 3, 'transaction_status_updated', 9, 'Transaction Status Updated', 'Transaction (ID: 9) status has been updated to Cancelled.', 0, '2025-05-14 13:59:08', NULL),
(46, 3, 3, 'transaction_status_updated', 10, 'Transaction Status Updated', 'Transaction (ID: 10) status has been updated to Cancelled.', 0, '2025-05-14 14:06:56', NULL),
(47, 3, 3, 'transaction_status_updated', 11, 'Transaction Status Updated', 'Transaction (ID: 11) status has been updated to Closed.', 0, '2025-05-14 14:07:37', NULL),
(48, 3, 3, 'transaction_status_updated', 12, 'Transaction Status Updated', 'Transaction (ID: 12) status has been updated to Cancelled.', 0, '2025-05-14 14:20:07', NULL),
(49, 3, 3, 'transaction_status_updated', 20, 'Transaction Status Updated', 'Transaction (ID: 20) status has been updated to Closed.', 0, '2025-05-14 14:21:07', NULL),
(50, 3, 3, 'transaction_status_updated', 21, 'Transaction Status Updated', 'Transaction (ID: 21) status has been updated to Closed.', 0, '2025-05-14 14:22:23', NULL),
(51, 3, 3, 'transaction_status_updated', 22, 'Transaction Status Updated', 'Transaction (ID: 22) status has been updated to Closed.', 0, '2025-05-14 14:33:50', NULL),
(52, 3, 3, 'transaction_status_updated', 16, 'Transaction Status Updated', 'Transaction (ID: 16) status has been updated to Closed.', 0, '2025-05-14 14:46:27', NULL),
(53, 3, 3, 'transaction_status_updated', 17, 'Transaction Status Updated', 'Transaction (ID: 17) status has been updated to Cancelled.', 0, '2025-05-14 14:46:48', NULL),
(54, 3, 3, 'transaction_status_updated', 18, 'Transaction Status Updated', 'Transaction (ID: 18) status has been updated to Closed.', 0, '2025-05-14 14:47:24', NULL),
(55, 3, 3, 'transaction_status_updated', 2, 'Transaction Status Updated', 'Transaction (ID: 2) status has been updated to Closed.', 0, '2025-05-14 14:48:05', NULL),
(56, 3, 3, 'transaction_status_updated', 19, 'Transaction Status Updated', 'Transaction (ID: 19) status has been updated to Closed.', 0, '2025-05-14 14:54:03', NULL),
(57, 3, 3, 'transaction_status_updated', 20, 'Transaction Status Updated', 'Transaction (ID: 20) status has been updated to Cancelled.', 0, '2025-05-14 15:00:51', NULL),
(58, 3, 3, 'transaction_status_updated', 22, 'Transaction Status Updated', 'Transaction (ID: 22) status has been updated to Closed.', 0, '2025-05-14 15:12:08', NULL),
(59, 3, 3, 'transaction_status_updated', 23, 'Transaction Status Updated', 'Transaction (ID: 23) status has been updated to Cancelled.', 0, '2025-05-14 15:13:11', NULL),
(60, 3, 3, 'transaction_status_updated', 24, 'Transaction Status Updated', 'Transaction (ID: 24) status has been updated to Closed.', 0, '2025-05-14 15:13:21', NULL),
(61, 3, 3, 'transaction_status_updated', 10, 'Transaction Status Updated', 'Transaction (ID: 10) status has been updated to Closed.', 0, '2025-05-14 15:13:50', NULL),
(62, 3, 3, 'transaction_status_updated', 11, 'Transaction Status Updated', 'Transaction (ID: 11) status has been updated to Pending.', 0, '2025-05-14 15:22:50', NULL),
(63, 3, 3, 'transaction_status_updated', 12, 'Transaction Status Updated', 'Transaction (ID: 12) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:25:37', NULL),
(64, 3, 3, 'transaction_status_updated', 13, 'Transaction Status Updated', 'Transaction (ID: 13) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:27:18', NULL),
(65, 3, 3, 'transaction_status_updated', 14, 'Transaction Status Updated', 'Transaction (ID: 14) status has been updated to Cancelled.', 0, '2025-05-14 15:28:08', NULL),
(66, 3, 3, 'transaction_status_updated', 15, 'Transaction Status Updated', 'Transaction (ID: 15) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:29:38', NULL),
(67, 3, 3, 'transaction_status_updated', 16, 'Transaction Status Updated', 'Transaction (ID: 16) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:30:27', NULL),
(68, 3, 3, 'transaction_status_updated', 17, 'Transaction Status Updated', 'Transaction (ID: 17) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:33:09', NULL),
(69, 3, 3, 'transaction_status_updated', 18, 'Transaction Status Updated', 'Transaction (ID: 18) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:33:54', NULL),
(70, 3, 3, 'transaction_status_updated', 2, 'Transaction Status Updated', 'Transaction (ID: 2) status has been updated to ToBeFollowed.', 0, '2025-05-14 15:34:13', NULL);

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
(1, 'WALKIN-W3W5WJAS', 4, 1, 'Assigned', '2025-05-14 08:00:00', '2025-05-14 08:00:00', '2025-05-14 08:12:00', 3),
(2, 'WALKIN-EDBP8HCD', 2, 1, 'Assigned', '2025-05-14 08:15:00', '2025-05-14 08:15:00', '2025-05-14 08:29:00', 3),
(3, 'WALKIN-UM91FCJW', 4, 1, 'Assigned', '2025-05-14 08:30:00', '2025-05-14 08:30:00', '2025-05-14 08:52:00', 3),
(4, 'WALKIN-22UF1R10', 4, 1, 'Assigned', '2025-05-14 08:45:00', '2025-05-14 08:45:00', '2025-05-14 09:14:00', 3),
(5, 'WALKIN-N0W8HKOK', 1, 1, 'Assigned', '2025-05-14 09:00:00', '2025-05-14 09:00:00', '2025-05-14 09:30:00', 4),
(6, 'WALKIN-DO7GASN6', 1, 1, 'Assigned', '2025-05-14 09:15:00', '2025-05-14 09:15:00', '2025-05-14 09:31:00', NULL),
(7, 'WALKIN-X7BGL1Y7', 1, 1, 'Assigned', '2025-05-14 09:30:00', '2025-05-14 09:30:00', '2025-05-14 09:58:00', NULL),
(8, 'WALKIN-GYWH943O', 5, 1, 'Assigned', '2025-05-14 09:45:00', '2025-05-14 09:45:00', '2025-05-14 10:11:00', NULL),
(9, 'WALKIN-052G5ULH', 4, 1, 'Assigned', '2025-05-14 10:00:00', '2025-05-14 10:00:00', '2025-05-14 10:24:00', 4),
(10, 'WALKIN-KW9KMJR8', 4, 1, 'Assigned', '2025-05-14 10:15:00', '2025-05-14 10:15:00', '2025-05-14 10:45:00', 4),
(11, 'WALKIN-SSTDOJQO', 5, 1, 'Assigned', '2025-05-14 10:30:00', '2025-05-14 10:30:00', '2025-05-14 10:49:00', 4),
(12, 'WALKIN-XYDWV5E9', 4, 1, 'Assigned', '2025-05-14 10:45:00', '2025-05-14 10:45:00', '2025-05-14 11:11:00', 3),
(13, 'WALKIN-VUKBP0TS', 1, 1, 'Assigned', '2025-05-14 11:00:00', '2025-05-14 11:00:00', '2025-05-14 11:17:00', 4),
(14, 'WALKIN-CZDMRZOY', 2, 1, 'Assigned', '2025-05-14 11:15:00', '2025-05-14 11:15:00', '2025-05-14 11:42:00', NULL),
(15, 'WALKIN-RTMVBK7I', 2, 1, 'Assigned', '2025-05-14 11:30:00', '2025-05-14 11:30:00', '2025-05-14 11:46:00', NULL),
(16, 'SCHED-4YJNILAF', 1, 2, 'Assigned', '2025-05-14 11:45:00', '2025-05-14 11:45:00', '2025-05-14 12:06:00', NULL),
(17, 'SCHED-NXEMV0SH', 2, 2, 'Assigned', '2025-05-14 12:00:00', '2025-05-14 12:00:00', '2025-05-14 12:27:00', 3),
(18, 'SCHED-3DTYY8GX', 2, 2, 'Assigned', '2025-05-14 12:15:00', '2025-05-14 12:15:00', '2025-05-14 12:35:00', 4),
(19, 'SCHED-XSJ35B5F', 1, 2, 'Assigned', '2025-05-14 12:30:00', '2025-05-14 12:30:00', '2025-05-14 12:55:00', 3),
(20, 'SCHED-U0APKBGE', 5, 2, 'Assigned', '2025-05-14 12:45:00', '2025-05-14 12:45:00', '2025-05-14 12:57:00', 4),
(21, 'SCHED-JAE6Q8LK', 3, 2, 'Assigned', '2025-05-14 13:00:00', '2025-05-14 13:00:00', '2025-05-14 13:22:00', NULL),
(22, 'SCHED-5QOU286H', 5, 2, 'Assigned', '2025-05-14 13:15:00', '2025-05-14 13:15:00', '2025-05-14 13:28:00', NULL),
(23, 'SCHED-3BUPZET4', 1, 2, 'Assigned', '2025-05-14 13:30:00', '2025-05-14 13:30:00', '2025-05-14 13:54:00', NULL),
(24, 'SCHED-IWL44V0A', 5, 2, 'Assigned', '2025-05-14 13:45:00', '2025-05-14 13:45:00', '2025-05-14 14:10:00', NULL),
(25, 'SCHED-6HLYBUCR', 1, 2, 'Assigned', '2025-05-14 14:00:00', '2025-05-14 14:00:00', '2025-05-14 14:14:00', 4),
(26, 'SCHED-MA68NIY9', 1, 2, 'Assigned', '2025-05-14 14:15:00', '2025-05-14 14:15:00', '2025-05-14 14:34:00', 3),
(27, 'SCHED-IDLJXXQK', 2, 2, 'Assigned', '2025-05-14 14:30:00', '2025-05-14 14:30:00', '2025-05-14 14:40:00', 3),
(28, 'SCHED-L025VHSO', 1, 2, 'Assigned', '2025-05-14 14:45:00', '2025-05-14 14:45:00', '2025-05-14 15:08:00', NULL),
(29, 'SCHED-ETOZTDUI', 4, 2, 'Assigned', '2025-05-14 15:00:00', '2025-05-14 15:00:00', '2025-05-14 15:22:00', 3),
(30, 'SCHED-P8GIZCGB', 5, 2, 'Assigned', '2025-05-14 15:15:00', '2025-05-14 15:15:00', '2025-05-14 15:36:00', 4);

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
(7, 'enable_sms_notifications', '0', '2025-05-14 01:04:32'),
(8, 'minimum_booking_lead_time_minutes', '30', '2025-05-09 09:01:13'),
(9, 'staff_update_cutoff_time', '23:59', '2025-05-14 13:55:18'),
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
(1, 'WALKIN-W3W5WJAS', 4, 1, 1, 'Open', NULL, '2025-05-14 08:00:00', '2025-05-14 23:51:53', 3, '2025-05-14 08:12:00'),
(2, 'WALKIN-EDBP8HCD', 2, 2, 1, 'Open', NULL, '2025-05-14 08:15:00', '2025-05-14 23:51:53', 3, '2025-05-14 08:29:00'),
(3, 'WALKIN-UM91FCJW', 4, 3, 1, 'Open', NULL, '2025-05-14 08:30:00', '2025-05-14 23:51:53', 3, NULL),
(4, 'WALKIN-22UF1R10', 4, 4, 1, 'Open', NULL, '2025-05-14 08:45:00', '2025-05-14 23:51:53', 3, '2025-05-14 09:14:00'),
(5, 'WALKIN-N0W8HKOK', 1, 5, 1, 'Open', NULL, '2025-05-14 09:00:00', '2025-05-14 09:30:00', 4, '2025-05-14 09:30:00'),
(6, 'WALKIN-DO7GASN6', 1, 6, 1, 'Open', NULL, '2025-05-14 09:15:00', '2025-05-14 23:51:53', NULL, '2025-05-14 09:31:00'),
(7, 'WALKIN-X7BGL1Y7', 1, 7, 1, 'Open', NULL, '2025-05-14 09:30:00', '2025-05-14 23:51:53', NULL, NULL),
(8, 'WALKIN-GYWH943O', 5, 8, 1, 'Open', NULL, '2025-05-14 09:45:00', '2025-05-14 23:51:53', NULL, '2025-05-14 10:11:00'),
(9, 'WALKIN-052G5ULH', 4, 9, 1, 'Open', NULL, '2025-05-14 10:00:00', '2025-05-14 10:24:00', 4, '2025-05-14 10:24:00'),
(10, 'WALKIN-KW9KMJR8', 4, 10, 1, 'Open', NULL, '2025-05-14 10:15:00', '2025-05-14 23:51:53', 4, '2025-05-14 10:45:00'),
(11, 'WALKIN-SSTDOJQO', 5, 11, 1, 'Open', NULL, '2025-05-14 10:30:00', '2025-05-14 23:51:53', 4, '2025-05-14 10:49:00'),
(12, 'WALKIN-XYDWV5E9', 4, 12, 1, 'Open', NULL, '2025-05-14 10:45:00', '2025-05-14 23:51:53', 3, '2025-05-14 11:11:00'),
(13, 'WALKIN-VUKBP0TS', 1, 13, 1, 'Open', NULL, '2025-05-14 11:00:00', '2025-05-14 23:51:53', 4, '2025-05-14 11:17:00'),
(14, 'WALKIN-CZDMRZOY', 2, 14, 1, 'Open', NULL, '2025-05-14 11:15:00', '2025-05-14 11:42:00', NULL, '2025-05-14 11:42:00'),
(15, 'WALKIN-RTMVBK7I', 2, 15, 1, 'Open', NULL, '2025-05-14 11:30:00', '2025-05-14 23:51:53', NULL, '2025-05-14 11:46:00'),
(16, 'SCHED-4YJNILAF', 1, 16, 2, 'Open', NULL, '2025-05-14 11:45:00', '2025-05-14 12:06:00', NULL, '2025-05-14 12:06:00'),
(17, 'SCHED-NXEMV0SH', 2, 17, 2, 'Open', NULL, '2025-05-14 12:00:00', '2025-05-14 23:51:53', 3, NULL),
(18, 'SCHED-3DTYY8GX', 2, 18, 2, 'Open', NULL, '2025-05-14 12:15:00', '2025-05-14 23:51:53', 4, '2025-05-14 12:35:00'),
(19, 'SCHED-XSJ35B5F', 1, 19, 2, 'Open', NULL, '2025-05-14 12:30:00', '2025-05-14 23:51:53', 3, '2025-05-14 12:55:00'),
(20, 'SCHED-U0APKBGE', 5, 20, 2, 'Open', NULL, '2025-05-14 12:45:00', '2025-05-14 23:51:53', 4, NULL),
(21, 'SCHED-JAE6Q8LK', 3, 21, 2, 'Open', NULL, '2025-05-14 13:00:00', '2025-05-14 13:22:00', NULL, '2025-05-14 13:22:00'),
(22, 'SCHED-5QOU286H', 5, 22, 2, 'Open', NULL, '2025-05-14 13:15:00', '2025-05-14 23:51:53', NULL, NULL),
(23, 'SCHED-3BUPZET4', 1, 23, 2, 'Open', NULL, '2025-05-14 13:30:00', '2025-05-14 23:51:53', NULL, NULL),
(24, 'SCHED-IWL44V0A', 5, 24, 2, 'Open', NULL, '2025-05-14 13:45:00', '2025-05-14 23:51:53', NULL, '2025-05-14 14:10:00'),
(25, 'SCHED-6HLYBUCR', 1, 25, 2, 'Open', NULL, '2025-05-14 14:00:00', '2025-05-14 23:51:53', 4, NULL),
(26, 'SCHED-MA68NIY9', 1, 26, 2, 'Open', NULL, '2025-05-14 14:15:00', '2025-05-14 23:51:53', 3, '2025-05-14 14:34:00'),
(27, 'SCHED-IDLJXXQK', 2, 27, 2, 'Open', NULL, '2025-05-14 14:30:00', '2025-05-14 23:51:53', 3, '2025-05-14 14:40:00'),
(28, 'SCHED-L025VHSO', 1, 28, 2, 'Open', NULL, '2025-05-14 14:45:00', '2025-05-14 23:51:53', NULL, '2025-05-14 15:08:00'),
(29, 'SCHED-ETOZTDUI', 4, 29, 2, 'Open', NULL, '2025-05-14 15:00:00', '2025-05-14 23:51:53', 3, '2025-05-14 15:22:00'),
(30, 'SCHED-P8GIZCGB', 5, 30, 2, 'Open', NULL, '2025-05-14 15:15:00', '2025-05-14 23:51:53', 4, '2025-05-14 15:36:00');

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
(1, 1, 4, NULL, 'Pending', NULL),
(2, 1, 5, NULL, 'Pending', NULL),
(3, 1, 1, NULL, 'Pending', NULL),
(4, 2, 1, NULL, 'Pending', NULL),
(5, 2, 2, NULL, 'Pending', NULL),
(6, 2, 3, NULL, 'Pending', NULL),
(7, 3, 2, NULL, 'Pending', NULL),
(8, 3, 1, NULL, 'Pending', NULL),
(9, 4, 5, NULL, 'Pending', NULL),
(10, 4, 2, NULL, 'Pending', NULL),
(11, 5, 2, NULL, 'Pending', NULL),
(12, 5, 5, NULL, 'Pending', NULL),
(13, 5, 3, NULL, 'Pending', NULL),
(14, 6, 4, NULL, 'Pending', NULL),
(15, 6, 5, NULL, 'Pending', NULL),
(16, 7, 4, NULL, 'Pending', NULL),
(17, 7, 3, NULL, 'Pending', NULL),
(18, 8, 1, NULL, 'Pending', NULL),
(19, 8, 5, NULL, 'Pending', NULL),
(20, 8, 4, NULL, 'Pending', NULL),
(21, 9, 2, NULL, 'Pending', NULL),
(22, 9, 5, NULL, 'Pending', NULL),
(23, 10, 2, NULL, 'Pending', NULL),
(24, 10, 4, NULL, 'Pending', NULL),
(25, 10, 1, NULL, 'Pending', NULL),
(26, 11, 4, NULL, 'Pending', NULL),
(27, 11, 1, NULL, 'Pending', NULL),
(28, 12, 1, NULL, 'Pending', NULL),
(29, 12, 2, NULL, 'Pending', NULL),
(30, 13, 4, NULL, 'Pending', NULL),
(31, 13, 2, NULL, 'Pending', NULL),
(32, 13, 5, NULL, 'Pending', NULL),
(33, 14, 4, NULL, 'Pending', NULL),
(34, 14, 3, NULL, 'Pending', NULL),
(35, 15, 5, NULL, 'Pending', NULL),
(36, 15, 4, NULL, 'Pending', NULL),
(37, 15, 1, NULL, 'Pending', NULL),
(38, 16, 5, NULL, 'Pending', NULL),
(39, 16, 1, NULL, 'Pending', NULL),
(40, 17, 1, NULL, 'Pending', NULL),
(41, 17, 4, NULL, 'Pending', NULL),
(42, 18, 1, NULL, 'Pending', NULL),
(43, 18, 5, NULL, 'Pending', NULL),
(44, 19, 2, NULL, 'Pending', NULL),
(45, 19, 4, NULL, 'Pending', NULL),
(46, 20, 5, NULL, 'Pending', NULL),
(47, 20, 1, NULL, 'Pending', NULL),
(48, 21, 5, NULL, 'Pending', NULL),
(49, 21, 3, NULL, 'Pending', NULL),
(50, 21, 2, NULL, 'Pending', NULL),
(51, 22, 2, NULL, 'Pending', NULL),
(52, 22, 3, NULL, 'Pending', NULL),
(53, 22, 5, NULL, 'Pending', NULL),
(54, 23, 4, NULL, 'Pending', NULL),
(55, 23, 3, NULL, 'Pending', NULL),
(56, 23, 1, NULL, 'Pending', NULL),
(57, 24, 1, NULL, 'Pending', NULL),
(58, 24, 2, NULL, 'Pending', NULL),
(59, 25, 3, NULL, 'Pending', NULL),
(60, 25, 1, NULL, 'Pending', NULL),
(61, 26, 2, NULL, 'Pending', NULL),
(62, 26, 4, NULL, 'Pending', NULL),
(63, 26, 3, NULL, 'Pending', NULL),
(64, 27, 5, NULL, 'Pending', NULL),
(65, 27, 3, NULL, 'Pending', NULL),
(66, 28, 3, NULL, 'Pending', NULL),
(67, 28, 1, NULL, 'Pending', NULL),
(68, 29, 3, NULL, 'Pending', NULL),
(69, 29, 5, NULL, 'Pending', NULL),
(70, 30, 2, NULL, 'Pending', NULL),
(71, 30, 3, NULL, 'Pending', NULL),
(72, 30, 4, NULL, 'Pending', NULL);

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
(4, 'markjones', 'mark.jones@example.com', '$2y$10$8GK1BRWEtbdHWHyH8qFXX.K/fNf6nMaHybVQVQRf1Hglm1rY2Coje', 1, '1993-03-12', '12 Pine St.', 1, NULL, 2, 'Mark', 'T.', 'Jones', NULL, 1, '09276542449', '2025-05-14 01:00:45', '2025-05-13 16:33:01'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
