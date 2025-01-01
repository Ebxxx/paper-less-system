-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jan 01, 2025 at 11:00 AM
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
-- Database: `paper-less-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` bigint(20) UNSIGNED NOT NULL,
  `to_user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `is_starred` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `subject`, `content`, `parent_id`, `read_at`, `is_archived`, `is_starred`, `created_at`, `updated_at`) VALUES
(131, 2, 3, 'sample letter', '123 Winner\'s Road\r\nNew Employee Town, PA 12345\r\n\r\nMarch 16, 2001\r\n\r\nErnie English\r\n1234 Writing Lab Lane\r\nWrite City, IN 12345\r\n\r\nDear Mr. English:\r\n\r\nThe first paragraph of a typical business letter is used to state the main point of the letter. Begin with a friendly opening, then quickly transition into the purpose of your letter. Use a couple of sentences to explain the purpose, but do not go in to detail until the next paragraph.\r\n\r\nBeginning with the second paragraph, state the supporting details to justify your purpose. These may take the form of background information, statistics or first-hand accounts. A few short paragraphs within the body of the letter should be enough to support your reasoning.\r\n\r\nFinally, in the closing paragraph, briefly restate your purpose and why it is important. If the purpose of your letter is employment related, consider ending your letter with your contact information. However, if the purpose is informational, think about closing with gratitude for the reader\'s time.\r\n\r\nSincerely,\r\n\r\n\r\nLucy Letter', NULL, '2024-12-29 20:51:16', 0, 0, '2024-12-29 20:51:08', '2024-12-29 21:02:34'),
(132, 3, 2, 'Re: sample letter', 'need revistion', 131, '2024-12-29 20:52:05', 0, 0, '2024-12-29 20:51:56', '2024-12-30 06:57:39'),
(133, 3, 2, 'Re: sample letter', 'ok', 131, '2024-12-29 21:03:43', 0, 0, '2024-12-29 21:03:34', '2024-12-30 06:57:39'),
(134, 2, 3, 'Re: sample letter', 'ok ok', 132, '2024-12-29 21:14:23', 0, 0, '2024-12-29 21:14:18', '2024-12-29 23:35:18'),
(135, 3, 2, 'Fwd: Re: sample letter', '---------- Forwarded message ----------\r\nFrom: ebszarUser <user1@gmail.com>\r\nDate: Mon, Dec 30, 2024 05:14 AM\r\nSubject: Re: sample letter\r\nTo: test <test1@gmail.com>\r\n\r\nok ok', NULL, '2024-12-29 22:42:42', 0, 0, '2024-12-29 22:42:22', '2024-12-30 06:57:39'),
(136, 3, 2, 'Fwd: Re: sample letter', '---------- Forwarded message ----------\r\nFrom: ebszarUser <user1@gmail.com>\r\nDate: Mon, Dec 30, 2024 05:14 AM\r\nSubject: Re: sample letter\r\nTo: test <test1@gmail.com>\r\n\r\nok ok', NULL, '2024-12-29 23:03:31', 0, 0, '2024-12-29 23:03:23', '2024-12-30 06:57:39'),
(137, 3, 2, 'Fwd: Fwd: Re: sample letter', '---------- Forwarded message ----------\r\nFrom: test <test1@gmail.com>\r\nDate: Mon, Dec 30, 2024 07:03 AM\r\nSubject: Fwd: Re: sample letter\r\nTo: ebszarUser <user1@gmail.com>\r\n\r\n---------- Forwarded message ----------\r\nFrom: ebszarUser <user1@gmail.com>\r\nDate: Mon, Dec 30, 2024 05:14 AM\r\nSubject: Re: sample letter\r\nTo: test <test1@gmail.com>\r\n\r\nok ok', NULL, '2024-12-29 23:32:25', 0, 0, '2024-12-29 23:32:17', '2024-12-30 06:57:39'),
(138, 3, 2, 'Re: sample letter', 'reply 2', 134, '2024-12-30 00:06:39', 0, 1, '2024-12-30 00:06:27', '2024-12-30 06:57:39'),
(139, 2, 3, 'hello', '🍑', NULL, '2024-12-30 06:11:31', 0, 0, '2024-12-30 06:11:26', '2024-12-30 06:11:31'),
(140, 2, 3, 'hello', 'sadsadsadsa**dasdasd**', NULL, '2024-12-30 06:39:42', 0, 0, '2024-12-30 06:39:36', '2024-12-30 06:39:42'),
(141, 2, 3, 'hello', '🫥', NULL, '2024-12-30 06:41:29', 0, 0, '2024-12-30 06:41:24', '2024-12-30 06:41:29'),
(142, 3, 2, 'Re: hello', 'ew', 141, '2024-12-30 06:41:43', 0, 0, '2024-12-30 06:41:38', '2024-12-30 06:57:39'),
(143, 2, 3, 'Re: hello', 'wffsdf', 142, '2024-12-30 07:27:15', 0, 0, '2024-12-30 07:27:06', '2024-12-30 07:27:15'),
(144, 2, 3, 'sdasdsadsad', 'asdsfsfdsfsdf', NULL, NULL, 0, 0, '2025-01-01 00:58:16', '2025-01-01 00:58:16'),
(145, 2, 6, 'sdasdsadsad', 'asdsfsfdsfsdf', NULL, NULL, 0, 0, '2025-01-01 00:58:22', '2025-01-01 00:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_attachments`
--

INSERT INTO `message_attachments` (`id`, `message_id`, `filename`, `original_filename`, `mime_type`, `file_size`, `created_at`, `updated_at`) VALUES
(45, 131, 'attachments/677226bc7f8b2_The divine comedy.jpg', 'The divine comedy.jpg', 'image/jpeg', 7650, '2024-12-29 20:51:08', '2024-12-29 20:51:08'),
(46, 138, 'attachments/67725483bdaee_b428dbfe260b156e9ab22754b922d9fd.png', 'b428dbfe260b156e9ab22754b922d9fd.png', 'image/png', 75356, '2024-12-30 00:06:29', '2024-12-30 00:06:29'),
(47, 144, 'attachments/677503a8085bb_LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1) (1).docx', 'LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1) (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 57657, '2025-01-01 00:58:17', '2025-01-01 00:58:17'),
(48, 144, 'attachments/677503a9426c9_LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1).docx', 'LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 57657, '2025-01-01 00:58:17', '2025-01-01 00:58:17'),
(49, 144, 'attachments/677503a943c6b_LABORATORY EXERCISE 12 Performance and Scalability in Online Bookstore System Design.docx', 'LABORATORY EXERCISE 12 Performance and Scalability in Online Bookstore System Design.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 70300, '2025-01-01 00:58:17', '2025-01-01 00:58:17'),
(50, 145, 'attachments/677503ae7332d_LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1) (1).docx', 'LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1) (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 57657, '2025-01-01 00:58:22', '2025-01-01 00:58:22'),
(51, 145, 'attachments/677503ae748ca_LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1).docx', 'LABORATORY EXERCISE 2 ADVANCED SOCIAL MEDIA DESIGN AUTHENTICATION AND PROFILE MANAGEMENT (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 57657, '2025-01-01 00:58:22', '2025-01-01 00:58:22'),
(52, 145, 'attachments/677503ae764da_LABORATORY EXERCISE 12 Performance and Scalability in Online Bookstore System Design.docx', 'LABORATORY EXERCISE 12 Performance and Scalability in Online Bookstore System Design.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 70300, '2025-01-01 00:58:22', '2025-01-01 00:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `message_marks`
--

CREATE TABLE `message_marks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT 0,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `deadline` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_marks`
--

INSERT INTO `message_marks` (`id`, `message_id`, `is_important`, `is_urgent`, `deadline`, `created_at`, `updated_at`) VALUES
(94, 131, 1, 0, '2024-12-30 12:50:00', '2024-12-29 20:51:08', '2024-12-29 20:51:08'),
(95, 132, 0, 1, '2024-12-30 12:51:00', '2024-12-29 20:51:56', '2024-12-29 20:51:56'),
(96, 133, 1, 0, NULL, '2024-12-29 21:03:34', '2024-12-29 21:03:34'),
(97, 134, 0, 0, NULL, '2024-12-29 21:14:18', '2024-12-29 21:14:18'),
(98, 137, 1, 1, NULL, '2024-12-29 23:32:17', '2024-12-29 23:32:17'),
(99, 138, 1, 1, NULL, '2024-12-30 00:06:27', '2024-12-30 00:06:27'),
(100, 139, 0, 0, NULL, '2024-12-30 06:11:26', '2024-12-30 06:11:26'),
(101, 140, 0, 0, NULL, '2024-12-30 06:39:36', '2024-12-30 06:39:36'),
(102, 141, 0, 0, NULL, '2024-12-30 06:41:24', '2024-12-30 06:41:24'),
(103, 142, 1, 0, NULL, '2024-12-30 06:41:38', '2024-12-30 06:41:38'),
(104, 143, 0, 0, NULL, '2024-12-30 07:27:06', '2024-12-30 07:27:06'),
(105, 144, 0, 0, NULL, '2025-01-01 00:58:16', '2025-01-01 00:58:16'),
(106, 145, 0, 0, NULL, '2025-01-01 00:58:22', '2025-01-01 00:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_01_143516_create_messages_table', 1),
(6, '2024_11_03_130718_add_role_to_users_table', 1),
(7, '2024_11_06_104644_add_fields_to_users_table', 1),
(8, '2024_11_07_145839_drop_users_table_with_name_fields', 1),
(9, '2024_11_20_115417_add_superadmin_table', 1),
(10, '2024_11_21_151728_add_job_details_to_users_table', 1),
(11, '2024_12_09_144013_add_is_online_to_users_table', 1),
(12, '2024_12_10_130227_add_maintenance_mode_to_superadmins_table', 1),
(13, '2024_12_10_130253_add_maintenance_mode_to_superadmins_table', 1),
(14, '2024_12_23_142203_modify_message_table', 1),
(15, '2024_12_23_144537_drop_old_message_table', 2),
(16, '2024_12_23_144656_create_message_table', 2),
(17, '2024_12_23_144759_drop_messages_table', 3),
(18, '2024_12_23_145240_create_messages_table', 4),
(19, '2024_12_23_145350_create_messages_table', 5),
(20, '2024_12_23_000000_create_message_attachments_table', 6),
(21, '2024_12_25_163834_add_is_read_to_messages_table', 7),
(22, '2024_01_20_create_message_marks_table', 8),
(23, '2024_12_29_053506_add_parent_id_to_messages_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmins`
--

CREATE TABLE `superadmins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `superadmins`
--

INSERT INTO `superadmins` (`id`, `username`, `fullname`, `email`, `password`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `maintenance_mode`) VALUES
(1, 'superdev', 'Super Developer', 'superdev@company.com', '$2y$12$hiO7kar5taYMjGFvzOzguuF1/d5LU8FniGY.K1uFH8Q4VGzwt0GTO', 1, NULL, NULL, '2024-12-23 06:33:02', '2024-12-23 06:33:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `middle_name`, `last_name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `signature_path`, `job_title`, `program`, `department`, `created_at`, `updated_at`, `is_online`) VALUES
(1, 'ebszar', 'asdsad', 'asdasd', 'asdasdas', 'itsebs758@gmail.com', 'admin', NULL, '$2y$12$ZO3bNUmKiOTtrfQD6LlHI.3JoNnUPYYMveRxVxoHrA0P3Vbq3wB3O', NULL, 'signatures/K0vE2kjjVbjGOtjdnWX7LAb3pxNUxXn9m6W4eVUl.png', NULL, 'head math', NULL, '2024-12-23 06:33:58', '2025-01-01 00:11:44', 1),
(2, 'ebszarUser', 'ebs', 'ana', 'lapaz', 'user1@gmail.com', 'user', NULL, '$2y$12$nfRJA9J3Oz4jTDiBy01WZOFiz4xYarRMXr2nOzj4rlk8bbNqgelx2', NULL, NULL, 'faculty', 'IT', 'Department of Engenering and technology', '2024-12-23 06:35:24', '2025-01-01 00:56:53', 1),
(3, 'test', 'First name', 'Middle name', 'Last name', 'test1@gmail.com', 'user', NULL, '$2y$12$puj.dvAgtwuvL/jpWjJG8ePSGXjpP0nJU/XvFjmkopEu3tVmeFQCi', NULL, NULL, 'Faculty', 'CS', 'Department of Engenering and technology', '2024-12-23 06:59:39', '2024-12-30 07:46:10', 0),
(6, 'user2', NULL, NULL, NULL, 'user2@gmail.com', 'user', NULL, '$2y$12$72U0lqm0n.6w6PSAa8PHfutEC7L92/LUSOUOt5u9guxTeOz88tml2', NULL, NULL, NULL, NULL, NULL, '2024-12-26 23:30:38', '2024-12-26 23:30:52', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_from_user_id_index` (`from_user_id`),
  ADD KEY `messages_to_user_id_index` (`to_user_id`),
  ADD KEY `messages_read_at_index` (`read_at`),
  ADD KEY `messages_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_attachments_message_id_foreign` (`message_id`);

--
-- Indexes for table `message_marks`
--
ALTER TABLE `message_marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_marks_message_id_foreign` (`message_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `superadmins`
--
ALTER TABLE `superadmins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `superadmins_username_unique` (`username`),
  ADD UNIQUE KEY `superadmins_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `message_marks`
--
ALTER TABLE `message_marks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmins`
--
ALTER TABLE `superadmins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `message_attachments_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_marks`
--
ALTER TABLE `message_marks`
  ADD CONSTRAINT `message_marks_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
