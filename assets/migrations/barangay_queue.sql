-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 02:38 AM
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
(1, 1, 1, 'Logged in', 'Success', NULL, '2025-04-28 09:09:15'),
(2, 1, 1, 'Reset a password', 'Success', NULL, '2025-05-01 18:56:15'),
(3, 1, 1, 'Logged in', 'Pending', NULL, '2025-05-02 06:50:15'),
(4, 1, 1, 'Exported data', 'Pending', NULL, '2025-04-28 21:45:15'),
(5, 1, 1, 'Reset a password', 'Failed', NULL, '2025-04-27 16:00:15'),
(6, 1, 1, 'Changed role access', 'Success', NULL, '2025-04-30 08:57:15'),
(7, 1, 1, 'Logged in', 'Failed', NULL, '2025-04-27 00:13:15'),
(8, 1, 1, 'Changed role access', 'Failed', NULL, '2025-05-01 07:17:15'),
(9, 1, 1, 'Reset a password', 'Success', NULL, '2025-05-04 05:37:15'),
(10, 1, 1, 'Changed role access', 'Failed', NULL, '2025-05-05 00:36:15'),
(11, 1, 1, 'Reviewed reports', 'Failed', NULL, '2025-04-29 11:33:15'),
(12, 1, 1, 'Added a new user', 'Pending', NULL, '2025-05-01 22:34:15'),
(13, 1, 1, 'Exported data', 'Pending', NULL, '2025-04-28 06:53:15'),
(14, 1, 1, 'Generated daily summary', 'Success', NULL, '2025-04-27 19:45:15'),
(15, 1, 1, 'Reviewed reports', 'Pending', NULL, '2025-05-03 14:38:15'),
(16, 1, 1, 'Added a new user', 'Failed', NULL, '2025-04-27 13:41:15'),
(17, 1, 1, 'Reviewed user complaints', 'Pending', NULL, '2025-05-03 10:08:15'),
(18, 1, 1, 'Changed role access', 'Success', NULL, '2025-04-28 15:13:15'),
(19, 1, 1, 'Approved transaction', 'Pending', 1598, '2025-05-02 17:56:15'),
(20, 1, 1, 'Reviewed user complaints', 'Success', NULL, '2025-04-29 01:34:15'),
(21, 2, 2, 'Cancelled appointment', 'Success', NULL, '2025-05-04 05:30:15'),
(22, 2, 2, 'Updated profile', 'Failed', NULL, '2025-04-30 22:15:15'),
(23, 2, 2, 'Logged in', 'Success', NULL, '2025-04-29 09:08:15'),
(24, 2, 2, 'Booked a service', 'Success', 1923, '2025-05-01 02:04:15'),
(25, 2, 2, 'Viewed transaction history', 'Pending', NULL, '2025-04-28 00:12:15'),
(26, 2, 2, 'Booked a service', 'Failed', NULL, '2025-04-27 06:33:15'),
(27, 2, 2, 'Logged in', 'Pending', NULL, '2025-05-01 17:57:15'),
(28, 2, 2, 'Cancelled appointment', 'Failed', NULL, '2025-04-30 04:21:15'),
(29, 2, 2, 'Updated profile', 'Success', NULL, '2025-05-01 11:03:15'),
(30, 2, 2, 'Booked a service', 'Success', 1784, '2025-04-27 03:25:15');

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
(1, 'Male', '2025-02-17 08:00:00', '2025-02-17 08:00:00'),
(2, 'Female', '2025-02-17 08:00:00', '2025-02-17 08:00:00'),
(3, 'Other', '2025-02-17 08:00:00', '2025-02-17 08:00:00');

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
(1, 1, 1, 'Notification', 'User feedback received.', 0, '2025-04-27 02:45:15'),
(2, 1, 1, 'Notification', 'Critical alert: Disk space low.', 1, '2025-04-28 04:30:15'),
(3, 1, 1, 'Notification', 'System backup completed.', 0, '2025-05-01 01:20:15'),
(4, 1, 1, 'Notification', 'Admin password changed.', 0, '2025-05-03 05:45:15'),
(5, 1, 1, 'Notification', 'Scheduled maintenance notice.', 0, '2025-05-04 00:20:15'),
(6, 1, 1, 'Notification', 'New report available.', 1, '2025-05-01 02:10:15'),
(7, 1, 1, 'Notification', 'User feedback received.', 0, '2025-04-27 05:25:15'),
(8, 1, 1, 'Notification', 'Critical alert: Disk space low.', 0, '2025-05-02 23:15:15'),
(9, 1, 1, 'Notification', 'System backup completed.', 1, '2025-04-28 06:55:15'),
(10, 1, 1, 'Notification', 'Admin password changed.', 1, '2025-05-02 07:05:15'),
(11, 1, 1, 'Notification', 'Scheduled maintenance notice.', 0, '2025-05-03 02:00:15'),
(12, 1, 1, 'Notification', 'New report available.', 0, '2025-05-02 04:30:15'),
(13, 1, 1, 'Notification', 'User feedback received.', 0, '2025-05-03 22:25:15'),
(14, 1, 1, 'Notification', 'System backup completed.', 1, '2025-04-27 09:40:15'),
(15, 1, 1, 'Notification', 'Admin password changed.', 0, '2025-05-04 03:10:15'),
(16, 1, 1, 'Notification', 'Scheduled maintenance notice.', 1, '2025-05-03 12:15:15'),
(17, 1, 1, 'Notification', 'New report available.', 0, '2025-04-30 01:50:15'),
(18, 1, 1, 'Notification', 'Critical alert: Disk space low.', 1, '2025-04-29 00:00:15'),
(19, 1, 1, 'Notification', 'System backup completed.', 0, '2025-05-01 23:45:15'),
(20, 1, 1, 'Notification', 'User feedback received.', 0, '2025-04-29 22:10:15'),
(21, 2, 2, 'Notification', 'Your transaction is scheduled.', 0, '2025-04-29 03:00:15'),
(22, 2, 2, 'Notification', 'You missed your appointment.', 1, '2025-04-27 05:15:15'),
(23, 2, 2, 'Notification', 'Reminder: Upcoming service', 0, '2025-05-01 06:10:15'),
(24, 2, 2, 'Notification', 'Profile updated successfully.', 0, '2025-05-02 01:45:15'),
(25, 2, 2, 'Notification', 'Thank you for using our service!', 1, '2025-05-04 00:30:15'),
(26, 2, 2, 'Notification', 'You missed your appointment.', 0, '2025-04-30 02:20:15'),
(27, 2, 2, 'Notification', 'Your transaction is scheduled.', 0, '2025-04-27 23:55:15'),
(28, 2, 2, 'Notification', 'Reminder: Upcoming service', 1, '2025-05-03 08:40:15'),
(29, 2, 2, 'Notification', 'Thank you for using our service!', 0, '2025-05-01 10:35:15'),
(30, 2, 2, 'Notification', 'Profile updated successfully.', 0, '2025-05-03 04:00:15');

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
(13, 'Q-SVQVLTCB', 5, 1, 'Pending', '2025-05-04 09:00:00', '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL),
(17, 'Q-SVQVLT71', 5, 1, 'Pending', '2025-05-04 11:00:00', '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL),
(19, 'Q-SVQVLT58', 6, 1, 'Pending', '2025-05-04 12:00:00', '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL),
(20, 'Q-SVQVLT2B', 3, 1, 'Pending', '2025-05-04 12:30:00', '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL),
(49, 'Q-SVQWBI5D', 10, 2, '', '2025-05-05 12:00:00', '2025-05-05 00:26:06', '2025-05-05 00:26:06', NULL),
(50, 'Q-SVQWML16', 10, 2, '', '2025-05-06 12:00:00', '2025-05-05 00:32:45', '2025-05-05 00:32:45', NULL),
(51, 'Q-SVSMJCC9', 10, 2, '', '2025-05-07 12:00:00', '2025-05-05 22:50:00', '2025-05-05 22:50:00', NULL),
(52, 'Q-SVSMSVF3', 10, 2, '', '2025-05-17 12:00:00', '2025-05-05 22:55:43', '2025-05-05 22:55:43', NULL),
(53, 'Q-SW0N3752', 10, 2, '', '2025-05-10 08:00:00', '2025-05-10 06:42:43', '2025-05-10 06:42:43', NULL),
(54, 'Q-SW0SFQ97', 104, 2, '', '2025-05-17 12:00:00', '2025-05-10 08:38:14', '2025-05-10 08:38:14', NULL);

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
(4, 'Residency Certification', 'Proof of residency in the barangay.', '2025-02-13 15:17:08', '2025-05-09 21:48:10'),
(12, 'Account Verification', 'To access our services, please complete your verification using this feature. Once verified, you’ll gain full access to all available tools and benefits.', '2025-05-10 00:34:54', '2025-05-10 00:34:54');

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
(1, 'booking_time_start', '06:00', '2025-05-09 22:44:52'),
(2, 'booking_time_end', '16:30', '2025-05-09 16:30:55'),
(4, 'no_show_timeout_minutes', '25', '2025-05-09 16:30:55'),
(5, 'sms_sender_name', 'QPILAPH', '2025-05-09 16:49:52'),
(6, 'max_transactions_per_day', '3', '2025-05-09 18:44:29'),
(7, 'enable_sms_notifications', '0', '2025-05-09 22:44:13'),
(8, 'minimum_booking_lead_time_minutes', '30', '2025-05-09 17:01:13'),
(9, 'staff_update_cutoff_time', '17:00', '2025-05-09 20:37:45'),
(10, 'staff_update_start_time', '04:00', '2025-05-09 20:45:56');

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
(6, 'Q-SVQVLT0C', 6, 6, 2, 'In Progress', NULL, '2025-05-04 10:30:00', '2025-05-10 03:35:32', 1, NULL),
(7, 'Q-SVQVLT8F', 3, 7, 2, 'Pending', NULL, '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL, NULL),
(8, 'Q-SVQVLTBA', 6, 8, 2, 'Pending', NULL, '2025-05-04 11:30:00', '2025-05-04 11:30:00', NULL, NULL),
(9, 'Q-SVQVLT2D', 3, 9, 2, 'Pending', NULL, '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL, NULL),
(10, 'Q-SVQVLT52', 3, 10, 2, 'Closed', NULL, '2025-05-04 12:30:00', '2025-05-10 06:48:32', NULL, NULL),
(11, 'Q-SVQVLTD1', 4, 11, 1, 'Pending', NULL, '2025-05-04 08:00:00', '2025-05-04 08:00:00', NULL, NULL),
(13, 'Q-SVQVLTCB', 5, 13, 1, 'Pending', NULL, '2025-05-04 09:00:00', '2025-05-04 09:00:00', NULL, NULL),
(17, 'Q-SVQVLT71', 5, 17, 1, 'Pending', NULL, '2025-05-04 11:00:00', '2025-05-04 11:00:00', NULL, NULL),
(19, 'Q-SVQVLT58', 6, 19, 1, 'Pending', NULL, '2025-05-04 12:00:00', '2025-05-04 12:00:00', NULL, NULL),
(20, 'Q-SVQVLT2B', 3, 20, 1, 'Pending', NULL, '2025-05-04 12:30:00', '2025-05-04 12:30:00', NULL, NULL),
(49, 'Q-SVQWBI5D', 10, 49, 2, 'Open', NULL, '2025-05-05 12:00:00', '2025-05-05 00:26:06', NULL, NULL),
(50, 'Q-SVQWML16', 10, 50, 2, 'Open', NULL, '2025-05-06 12:00:00', '2025-05-05 00:32:45', NULL, NULL),
(51, 'Q-SVSMJCC9', 10, 51, 2, 'Open', NULL, '2025-05-07 12:00:00', '2025-05-05 22:50:00', NULL, NULL),
(52, 'Q-SVSMSVF3', 10, 52, 2, 'Closed', 4, '2025-05-17 12:00:00', '2025-05-10 06:52:23', NULL, NULL),
(53, 'Q-SW0N3752', 10, 53, 2, 'Open', NULL, '2025-05-10 08:00:00', '2025-05-10 06:42:43', NULL, NULL),
(54, 'Q-SW0SFQ97', 104, 54, 2, 'Open', NULL, '2025-05-17 12:00:00', '2025-05-10 08:38:14', NULL, NULL);

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
(341, 6, 1, 'dfaf', 'Closed', '2025-05-10 03:35:14'),
(342, 6, 3, '21q3', 'Closed', '2025-05-10 03:35:32'),
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
(361, 13, 2, NULL, 'Pending', NULL),
(362, 13, 3, NULL, 'Pending', NULL),
(363, 13, 1, NULL, 'Pending', NULL),
(373, 17, 2, NULL, 'Pending', NULL),
(374, 17, 3, NULL, 'Pending', NULL),
(375, 17, 1, NULL, 'Pending', NULL),
(379, 19, 2, NULL, 'Pending', NULL),
(380, 19, 3, NULL, 'Pending', NULL),
(381, 19, 1, NULL, 'Pending', NULL),
(382, 20, 3, NULL, 'Pending', NULL),
(383, 20, 1, NULL, 'Pending', NULL),
(384, 20, 2, NULL, 'Pending', NULL),
(385, 49, 1, NULL, 'Pending', NULL),
(386, 50, 2, NULL, 'Pending', NULL),
(387, 51, 2, NULL, 'Pending', NULL),
(388, 52, 2, NULL, 'Pending', NULL),
(389, 52, 3, NULL, 'Pending', NULL),
(390, 53, 1, NULL, 'Pending', NULL),
(391, 53, 2, NULL, 'Pending', NULL),
(392, 53, 3, NULL, 'Pending', NULL),
(393, 54, 12, NULL, 'Pending', NULL);

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
(2, 'alice_smith', 'alice@example.com', '$2y$10$abcdefg1234567890hashedpassword', 2, '1998-01-03', 'asd', 1, '../uploads/profile_681e7a5d977cd3.82780631.png', 1, 'Alice', 'Marie', 'Smith', '', 2, '09179876543', '2025-05-09 21:57:49', '2025-02-17 14:53:05'),
(3, 'testagain', 'mark@example.com', '$2y$10$6j9SFMu0i7vTmZ02gCsw5.6NiKx5QnkgAVthLmHtwqNFBprz1E8J.', 2, '1998-02-15', 'sdfdsf', 0, NULL, 2, 'Mark', 'Anthony', 'Gonzalez', 'III', 1, '', '2025-05-09 22:01:00', '2025-02-17 14:53:05'),
(4, 'sophia_lopez', 'sophia@example.com', '$2y$10$mcgWcHLgQXH9dxXym2KVC.QU2FJwYwsOiAg6vRFFUj1iMs62rxuRu', 2, '1998-02-17', '', 1, NULL, 2, 'Sophia', NULL, 'Lopez', NULL, 4, '09211231234', '2025-05-10 00:29:02', '2025-02-17 14:53:05'),
(5, 'testJohn', 'john@test.com', '$2y$10$j50lJr0HRfGjfSXN5V3q5uIQJI5l5gC7AkNaXkiR1pNVIgHLVoewa', 1, '1998-10-03', 'asdasdasdasd', 0, '../uploads/download.png', 2, 'John', 'Cena', 'Doe', '', 2, '', '2025-02-19 17:07:22', '2025-02-17 16:17:26'),
(6, 'testaccount', 'testaccount@gmail.com', '$2y$10$KxspuhRxXMPCnWdJafqGLOhqmeUmaSv6hE2RVg/r52rO5wANUruwO', 1, '1999-02-03', 'sadsadasd', 1, '../uploads/mqdefault_6s.webp', 2, 'John', 'Cena', 'Doe', '', 3, '', '2025-02-20 17:20:19', '2025-02-17 16:18:44'),
(10, 'luffy01', 'monkey.d@luffy.com', '$2y$10$wOCF5BR/OqtaDik4diuJ1eJ8H1mY5tqx4tAP.OivR8plFjCQvx7Du', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '639276542449', '2025-05-10 00:24:50', '2025-02-20 16:10:26'),
(101, 'johndoe', 'john.doe@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1990-05-15', '123 Main St, Barangay', 1, NULL, 3, 'John', 'A.', 'Doe', NULL, 1, '09171234567', '2025-03-16 08:43:40', '2025-03-16 08:40:46'),
(102, 'janesmith', 'jane.smith@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 2, '1988-08-25', '456 Elm St, Barangay', 1, NULL, 3, 'Jane', 'B.', 'Smith', NULL, 2, '09179876543', '2025-03-16 08:43:46', '2025-03-16 08:40:46'),
(103, 'michaelreyes', 'michael.reyes@barangay.gov', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', 1, '1995-02-10', '789 Oak St, Barangay', 1, NULL, 3, 'Michael', 'C.', 'Reyes', 'Jr.', 1, '09175678901', '2025-03-16 08:43:52', '2025-03-16 08:40:46'),
(104, 'asd', 'sdf', '$2y$10$98P6nDVjCx7bsw3ra2iYt.NCAGUO8ko54Sn/Yt0vsNFbHLRwXtZDa', 0, NULL, '', 0, NULL, 2, '', NULL, '', NULL, 1, NULL, '2025-05-08 17:51:27', '2025-05-08 17:51:27'),
(105, 'asdasd', 'sdfaa', '$2y$10$3lhL3DgmDNTcfnfKhKJ.heF5ZPJIqtch9kicH8l2tjlk.44G05uhW', 0, NULL, '', 0, NULL, 2, '', NULL, '', NULL, 1, NULL, '2025-05-08 17:53:17', '2025-05-08 17:53:17'),
(106, 'asdf', 'xcv@asdasd', '$2y$10$I0X9EnF05M26tdMgpePBhOhU5/hZSjtWz6ZS/6E1hyCgsGLn4RUlq', 0, NULL, '', 0, NULL, 2, '', NULL, '', NULL, 1, NULL, '2025-05-08 17:58:31', '2025-05-08 17:58:31'),
(112, 'test again', 'test@aerdfas', '$2y$10$Crjx.ZPwmeU3Byh6EEgZFeNjIqmFZyY2oX83tO8wsbys9jiocdwcm', 1, '2025-05-07', 'test again', 1, '../uploads/profile_681e7a8fcc2bd1.56219415.png', 3, 'test again', 'test again', 'test again', 'test again', 2, 'test again', '2025-05-09 21:58:39', '2025-05-09 21:58:39');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transaction_services`
--
ALTER TABLE `transaction_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=394;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

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
