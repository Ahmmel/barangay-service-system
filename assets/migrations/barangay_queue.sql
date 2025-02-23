-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 07:03 PM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `modified_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy', '2025-02-11 16:59:47', '2025-02-11 16:59:47');

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
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `requirement_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`id`, `service_id`, `requirement_name`, `description`, `created_at`, `modified_at`) VALUES
(1, 1, 'Valid Government-Issued ID', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(2, 1, 'Proof of Residency (Utility Bill or Lease Contract)', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(3, 1, 'Duly Accomplished Application Form', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(4, 2, 'DTI or SEC Business Registration', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(5, 2, 'Barangay Clearance', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(6, 2, 'Valid Government-Issued ID', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(7, 3, 'Affidavit of Indigency', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(8, 3, 'Valid Government-Issued ID', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(9, 3, 'Proof of Residency', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(10, 4, 'Valid Government-Issued ID', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(11, 4, 'Proof of Residency', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(12, 5, 'Written Statement of Incident', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(13, 5, 'Valid Government-Issued ID', NULL, '2025-02-13 15:17:08', '2025-02-13 15:17:08');

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
(4, 'Residency Certification', 'Proof of residency in the barangay.', '2025-02-13 15:17:08', '2025-02-13 15:17:08'),
(5, 'Barangay Blotter Report', 'Request for incident reports or records from the barangay blotter.', '2025-02-13 15:17:08', '2025-02-13 15:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `service_logs`
--

CREATE TABLE `service_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `status` enum('Pending','Processing','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'john_doe', 'john@example.com', '$2y$10$abcdefg1234567890hashedpassword', 1, '1998-02-03', '', 1, NULL, 2, 'John', 'Michael', 'Doe', 'Jr.', 1, '09171234567', '2025-02-19 17:06:54', '2025-02-17 14:53:05'),
(2, 'alice_smith', 'alice@example.com', '$2y$10$abcdefg1234567890hashedpassword', 2, '1998-01-03', '', 1, NULL, 2, 'Alice', 'Marie', 'Smith', NULL, 2, '09179876543', '2025-02-19 17:07:01', '2025-02-17 14:53:05'),
(3, 'mark_gonzalez', 'mark@example.com', '$2y$10$abcdefg1234567890hashedpassword', 1, '1998-02-15', '', 0, NULL, 2, 'Mark', 'Anthony', 'Gonzalez', 'III', 1, NULL, '2025-02-19 17:07:05', '2025-02-17 14:53:05'),
(4, 'sophia_lopez', 'sophia@example.com', '$2y$10$abcdefg1234567890hashedpassword', 2, '1998-02-17', '', 1, NULL, 2, 'Sophia', NULL, 'Lopez', NULL, 4, '09211231234', '2025-02-19 17:07:09', '2025-02-17 14:53:05'),
(5, 'testJohn', 'john@test.com', '$2y$10$j50lJr0HRfGjfSXN5V3q5uIQJI5l5gC7AkNaXkiR1pNVIgHLVoewa', 1, '1998-10-03', 'asdasdasdasd', 0, '../uploads/download.png', 2, 'John', 'Cena', 'Doe', '', 2, '', '2025-02-19 17:07:22', '2025-02-17 16:17:26'),
(6, 'testaccount', 'testaccount@gmail.com', '$2y$10$KxspuhRxXMPCnWdJafqGLOhqmeUmaSv6hE2RVg/r52rO5wANUruwO', 1, '1999-02-03', 'sadsadasd', 1, '../uploads/mqdefault_6s.webp', 2, 'John', 'Cena', 'Doe', '', 3, '', '2025-02-20 17:20:19', '2025-02-17 16:18:44'),
(8, 'again@again.com', 'again@again.com', '$2y$10$CzAjpyXRfCQxmOyOskk5DuFJze6XDn.O4j7JzKDezceUv/JtzgyNK', 1, '2000-02-03', 'again@again.com', 1, '../uploads/download.png', 2, 'again', 'test', 'test', '', 2, '12312`34234', '2025-02-20 17:20:03', '2025-02-18 15:10:15'),
(10, 'luffy01', 'monkey.d@luffy.com', '$2y$10$FCC.VI1fPyRqJE6lJ2dx4.8h7ze/cocxgQew0ijztP.aFIyUowuKS', 1, '2025-02-20', 'sea', 1, '../uploads/one-piece-icons-by-me-v0-qweam8vkaxv91.jpg', 2, 'Luffy', 'Dreamer', 'Monkey', '', 1, '123123', '2025-02-20 16:10:26', '2025-02-20 16:10:26');

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

--
-- Indexes for dumped tables
--

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
-- Indexes for table `service_logs`
--
ALTER TABLE `service_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_admin` (`admin_id`),
  ADD KEY `idx_logs_transaction` (`transaction_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transactions_user` (`user_id`),
  ADD KEY `idx_transactions_service` (`service_id`),
  ADD KEY `idx_transactions_status` (`status`);

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
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_logs`
--
ALTER TABLE `service_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `requirements_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_logs`
--
ALTER TABLE `service_logs`
  ADD CONSTRAINT `service_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_logs_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
