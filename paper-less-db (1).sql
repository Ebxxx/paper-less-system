-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Dec 09, 2024 at 03:39 PM
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
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(8, '2024_11_07_145839_drop_users_table_with_name_fields', 2),
(9, '2024_11_11_145413_add_is_online_to_users_table', 3),
(10, '2024_11_11_152326_drop_is_online_to_users_table', 4),
(11, '2024_11_20_115417_add_superadmin_table', 5),
(12, '2024_11_21_151728_add_job_details_to_users_table', 6);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `superadmins`
--

INSERT INTO `superadmins` (`id`, `username`, `fullname`, `email`, `password`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'superdev', 'Super Developer', 'superdev@company.com', '$2y$12$RAxOpdk6Dvq9dzz/ru/XIe0rzuhafXTXMdc5kA0rWteEBrk7Fmt/S', 1, NULL, NULL, '2024-11-20 04:20:17', '2024-11-20 04:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `middle_name`, `last_name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `signature_path`, `job_title`, `program`, `department`, `created_at`, `updated_at`) VALUES
(1, 'dev0012', 'ebszar', 'ana', 'lapaz', 'itsebs758@gmail.com', 'admin', NULL, '$2y$12$ZB2J52Y86DeGYkmcEjSi9umb0TrJb4XyEgtZD9KsuS4ORz4LA/rnq', NULL, 'signatures/TgbKF6H7ccXtQfLivnoXQxRPWSwhPQ5gME0X952T.jpg', NULL, NULL, NULL, '2024-11-07 07:00:18', '2024-11-27 05:06:10'),
(8, 'hello', 'test', 'test', 'test', 'helloworld@gmail.com', 'user', NULL, '$2y$12$HRgPh/mIj4/hwPIPDYoh/.WeOvQvSSJLOtIFDO99kUSrytFq2C0yi', NULL, 'signatures/I9Sp5ZHvfEF3M5VW0cmzFKlQkwkSHygG14hc6NS7.png', 'Program director', 'Filipino', 'College of education', '2024-09-11 07:19:44', '2024-11-28 06:20:19'),
(9, 'test', 'wayne', 'bat', 'bruce', 'iambatman@gmail.com', 'user', NULL, '$2y$12$8rOV93f.2IBCbyyUsQg/LesCqp5A/AbkD9o0FbQPfxtjHO.qePurK', NULL, NULL, NULL, NULL, NULL, '2024-12-18 05:56:38', '2024-12-10 05:56:38'),
(10, 'testonly', 'test', NULL, 'test', 'testonly@gmail.com', 'user', NULL, '$2y$12$CKiJXhI0XeSyWzeBWE5zwuUiIMYf.VVALiSBA/vuBeJ1uHyrfyhNO', NULL, NULL, 'test', 'test', 'ITE', '2023-11-15 07:16:31', '2024-11-27 04:32:05'),
(11, 'newadmin', NULL, NULL, NULL, 'newadmin@gmail.com', 'admin', NULL, '$2y$12$N2.eccdWMnDEbhUiMRPv6uRCFsEnDY01N9YCeSUO3JsFWpTh2Bl/i', NULL, NULL, NULL, NULL, NULL, '2024-10-23 07:44:57', '2024-11-20 07:44:57'),
(12, 'fasfdsf', 'dsfsdf', NULL, 'sfsdf', 'sample@gmail.com', 'user', NULL, '$2y$12$4JHRXWE3PqAflhKyC5/3hemf/2VEToaVe2XYZywqvoqY2JJL69VCS', NULL, NULL, NULL, NULL, NULL, '2024-11-27 06:44:21', '2024-11-27 06:44:21');

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
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmins`
--
ALTER TABLE `superadmins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
