-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 01:28 PM
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
-- Database: `whub`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `freelancer_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `rating` int(11) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `review` text DEFAULT NULL,
  `decline_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `freelancer_id`, `customer_id`, `date`, `time`, `name`, `address`, `contact`, `notes`, `created_at`, `updated_at`, `status`, `rating`, `completed_at`, `post_id`, `review`, `decline_reason`) VALUES
(30, 24, 20, '2024-12-24', '04:00 PM', 'me', 'minglanilla ,cebu', '0927672090', 'hi gwapa', '2024-12-13 01:02:53', '2024-12-13 01:04:31', 'completed', NULL, '2024-12-13 01:04:31', 19, NULL, NULL),
(32, 25, 26, '2024-12-14', '09:30 AM', 'markdoe', 'minglanilla ,cebu', '978675662134', NULL, '2024-12-13 07:14:05', '2024-12-13 07:14:05', 'pending', NULL, NULL, 21, NULL, NULL),
(33, 25, 26, '2024-12-14', '09:30 AM', 'markdoe', 'minglanilla ,cebu', '978675662134', NULL, '2024-12-13 07:14:18', '2024-12-13 07:14:53', 'declined', NULL, NULL, 21, NULL, NULL),
(34, 10, 26, '2024-12-14', '09:30 AM', 'markdoe', 'minglanilla ,cebu', '9786756621', NULL, '2024-12-13 07:42:42', '2024-12-13 19:13:03', 'accepted', NULL, NULL, 7, NULL, NULL),
(35, 25, 27, '2024-12-17', '09:00 AM', 'angel waypako', 'minglanilla ,cebu', '9786756621', NULL, '2024-12-13 18:13:30', '2024-12-13 18:19:11', 'completed', 5, '2024-12-13 18:16:29', 21, NULL, NULL),
(36, 10, 27, '2024-12-15', '09:00 AM', 'angel doe', 'minglanilla ,cebu', '9786756621', NULL, '2024-12-13 19:10:15', '2024-12-13 19:12:05', 'declined', NULL, NULL, 7, NULL, NULL),
(37, 25, 9, '2025-02-27', '12:30 PM', 'eljohn adlawan', 'cebu,naga', '9887654321', NULL, '2025-02-23 02:34:10', '2025-02-23 02:44:03', 'completed', 3, '2025-02-23 02:43:06', 21, NULL, NULL),
(38, 10, 15, '2025-02-28', '04:30 PM', 'aljun delantar', 'cebu,naga', '9887434344', NULL, '2025-02-25 02:46:31', '2025-02-25 02:49:55', 'completed', 4, '2025-02-25 02:49:28', 7, NULL, NULL),
(39, 25, 9, '2025-03-28', '07:00 PM', 'eljohn adlawan', 'cebu,naga', '9887654321', NULL, '2025-03-01 22:13:02', '2025-03-01 22:30:05', 'completed', 5, '2025-03-01 22:17:00', 21, 'quality ang trabaho ani', NULL),
(43, 10, 47, '2025-04-27', '08:00', 'jhonardtest cabizares', 'cebu,minglanilla', '9912236143', NULL, '2025-04-24 05:05:51', '2025-04-26 20:28:58', 'completed', 3, NULL, 31, 'nice og trabaho', NULL),
(44, 10, 9, '2025-04-26', '08:00 AM', 'eljohn adlawan', 'cebu,naga', '9887654321', NULL, '2025-04-25 02:38:59', '2025-04-25 02:38:59', 'pending', NULL, NULL, 31, NULL, NULL),
(45, 10, 47, '2025-05-04', '08:00 AM', 'jhonardtest cabizares', 'cebu,minglanilla', '9912236143', NULL, '2025-05-01 00:11:11', '2025-05-01 07:41:03', 'accepted', NULL, NULL, 31, NULL, NULL),
(46, 25, 47, '2025-05-09', '03:00 PM', 'jhonardtest cabizares', 'cebu,minglanilla', '9912236143', NULL, '2025-05-01 05:58:14', '2025-05-01 06:27:14', 'declined', NULL, NULL, 21, NULL, 'plss reschedule due to time conflict'),
(47, 10, 47, '2025-05-07', '8:00 AM', 'jhonard cabizares', 'cebu minglanilla', '9912236143', NULL, '2025-05-06 00:37:42', '2025-05-06 01:13:28', 'completed', 5, '2025-05-06 00:41:11', 31, 'quality works', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dannagellica@gmail.com|127.0.0.1', 'i:1;', 1746359218),
('dannagellica@gmail.com|127.0.0.1:timer', 'i:1746359218;', 1746359218),
('victorillojhonard@gmail.com|127.0.0.1', 'i:1;', 1746359208),
('victorillojhonard@gmail.com|127.0.0.1:timer', 'i:1746359208;', 1746359208);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'welder', '2024-11-24 06:48:56', '2024-11-24 06:48:56'),
(3, 'Event designer', '2024-11-24 07:02:14', '2024-11-24 07:02:14'),
(4, 'housekeeping', '2024-11-24 07:11:53', '2024-11-24 07:11:53'),
(5, 'Grooming', '2024-11-24 07:12:48', '2024-11-24 07:12:48'),
(6, 'Electricals', '2024-11-24 07:18:33', '2024-11-24 07:18:33'),
(7, 'Gardening', '2024-11-24 22:27:36', '2024-11-24 22:27:36'),
(8, 'graphic designer', '2024-11-25 00:43:37', '2024-11-25 00:43:37'),
(11, 'technicians', '2024-12-11 20:07:14', '2024-12-12 06:35:45'),
(23, 'Carpentry', '2025-03-12 22:25:27', '2025-03-12 22:25:27'),
(24, 'Masonry', '2025-03-12 22:26:34', '2025-03-12 22:26:34'),
(25, 'Plumbing', '2025-03-12 22:27:03', '2025-03-12 22:27:03'),
(26, 'Tutoring', '2025-05-04 03:02:25', '2025-05-04 03:02:25');

-- --------------------------------------------------------

--
-- Table structure for table `category_user`
--

CREATE TABLE `category_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_user`
--

INSERT INTO `category_user` (`id`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
(8, 8, 3, NULL, NULL),
(10, 10, 2, NULL, NULL),
(11, 12, 3, NULL, NULL),
(13, 17, 4, NULL, NULL),
(14, 19, 8, NULL, NULL),
(15, 21, 6, NULL, NULL),
(16, 24, 7, NULL, NULL),
(17, 25, 7, NULL, NULL),
(20, 29, 11, NULL, NULL),
(21, 30, 5, NULL, NULL),
(22, 48, 7, NULL, NULL),
(23, 49, 6, NULL, NULL);

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(10, '2024_10_31_110744_add_contact_number_to_users_table', 2),
(19, '0001_01_01_000000_create_users_table', 3),
(20, '0001_01_01_000001_create_cache_table', 3),
(21, '0001_01_01_000002_create_jobs_table', 3),
(22, '2024_11_11_101614_add_profile_completed_to_users_table', 3),
(23, '2024_11_12_023232_add_profile_fields_to_users_table', 4),
(24, '2024_11_24_061716_add_profile_picture_to_users_table', 5),
(25, '2024_11_24_064515_add_profile_picture_to_users_table', 6),
(28, '2024_11_24_095558_create_categories_table', 7),
(29, '2024_11_24_103428_create_category_user_table', 7),
(30, '2024_11_24_113117_remove_category_from_users_table', 8),
(31, '2024_11_27_074250_create_posts_table', 9),
(32, '2024_12_05_091621_create_appointments_table', 10),
(33, '2024_12_06_072808_create_notifications_table', 11),
(35, '2024_12_06_075023_add_status_to_appointments_table', 12),
(36, '2024_12_10_143908_add_post_id_to_appointments_table', 13),
(37, '2024_12_11_074258_add_completed_at_to_appointments_table', 14),
(38, '2024_12_11_075822_add_rating_to_appointments_table', 15),
(39, '2025_02_25_062044_add_status_to_posts_table', 16),
(40, '2025_03_02_053135_add_review_to_appointments_table', 17),
(41, '2025_03_19_141852_add_decline_reason_to_appointments_table', 18),
(43, '2025_03_28_125946_create_post_pictures_table', 19),
(44, '2025_03_28_125959_create_post_sub_services_table', 19),
(45, '2025_04_04_095150_add_is_verified_to_users_table', 20),
(46, '2025_04_19_171838_add_email_verification_token_to_users_table', 21),
(47, '2025_04_24_162826_add_experience_level_to_users_table', 22),
(48, '2025_04_27_133952_add_password_reset_columns_to_users_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('01e57109-dc4b-4d14-af95-83be169b0e19', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":36,\"customer_name\":\"angel doe\",\"date\":\"2024-12-15\",\"time\":\"09:00 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"9786756621\"}', '2025-03-04 03:55:24', '2024-12-13 19:10:15', '2025-03-04 03:55:24'),
('03f59f4c-7f0f-4530-bf75-fde9c9c441c9', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":7}', '2025-03-04 03:27:07', '2025-02-25 02:43:34', '2025-03-04 03:27:07'),
('0c82b55b-b955-44f3-8f89-34e6f81bf837', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 17, '{\"appointment_id\":22,\"customer_name\":\"RICA BLANCE\",\"date\":\"2024-12-13\",\"time\":\"01:30 PM\",\"address\":\"mayana city of naga cebu\",\"contact\":\"9912236143\"}', NULL, '2024-12-11 22:42:00', '2024-12-11 22:42:00'),
('0dbe4859-a127-4a7e-8c0b-d8c8bcbc1de6', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 26, '{\"appointment_id\":34,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', NULL, '2024-12-13 19:13:03', '2024-12-13 19:13:03'),
('0edf76a3-87e8-4281-bb35-b97b971b4047', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 27, '{\"appointment_id\":35,\"status\":\"accepted\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been accepted.\"}', NULL, '2024-12-13 18:15:05', '2024-12-13 18:15:05'),
('13715c3e-f4e1-42a6-b2e8-4e6b4c20e96b', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2024-12-13 01:16:39', '2024-12-13 01:16:39'),
('154100e7-c35b-4281-b47e-bb3d67fa28b4', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":42,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2025-03-19 07:43:07', '2025-03-19 07:43:07'),
('1c6a76de-5538-494f-bf09-0807c1d8ab66', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":44,\"customer_name\":\"eljohn adlawan\",\"date\":\"2025-04-26\",\"time\":\"08:00 AM\",\"address\":\"cebu,naga\",\"contact\":\"9887654321\"}', '2025-04-30 19:33:49', '2025-04-25 02:39:15', '2025-04-30 19:33:49'),
('237d441c-0f77-4c66-a5a1-a437069342e3', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":33,\"customer_name\":\"markdoe\",\"date\":\"2024-12-14\",\"time\":\"09:30 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"978675662134\"}', '2025-05-01 06:00:15', '2024-12-13 07:14:18', '2025-05-01 06:00:15'),
('25a5879f-2b43-4a1b-859d-c651a6fcb379', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 27, '{\"appointment_id\":36,\"status\":\"declined\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been declined.\"}', NULL, '2024-12-13 19:12:05', '2024-12-13 19:12:05'),
('2c415396-3af5-407b-ba2c-2c9521ba40cf', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":47,\"customer_name\":\"jhonard cabizares\",\"date\":\"2025-05-07\",\"time\":\"8:00 AM\",\"address\":\"cebu minglanilla\",\"contact\":\"9912236143\",\"message\":\"You have a new appointment request from jhonard cabizares on 2025-05-07 at 8:00 AM.\"}', NULL, '2025-05-06 00:38:00', '2025-05-06 00:38:00'),
('2fd06237-0b70-49df-a1d6-9251427b41fd', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":21,\"status\":\"completed\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been completed.\"}', NULL, '2024-12-11 22:43:13', '2024-12-11 22:43:13'),
('3044527e-6c79-4f95-b467-b0d72198acce', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":42,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2025-03-19 23:37:40', '2025-03-19 23:37:40'),
('3070da9d-cb17-4cc3-8680-01150fd27e83', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":45,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', '2025-05-03 06:19:32', '2025-05-01 07:41:09', '2025-05-03 06:19:32'),
('30a6351b-8307-4267-970f-75900a223b74', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":31}', '2025-04-30 19:33:45', '2025-03-09 23:00:01', '2025-04-30 19:33:45'),
('32697a0f-9e24-4973-931e-625945f6c1b3', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":34,\"customer_name\":\"markdoe\",\"date\":\"2024-12-14\",\"time\":\"09:30 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"9786756621\"}', '2025-04-30 19:33:51', '2024-12-13 07:42:42', '2025-04-30 19:33:51'),
('358c6283-ce07-4f77-b401-6fc05d5f6984', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":41,\"status\":\"accepted\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been accepted.\"}', NULL, '2025-03-19 05:59:15', '2025-03-19 05:59:15'),
('36162f9e-edda-481e-9fb1-5e9c2c30153f', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":30,\"status\":\"completed\",\"freelancer_name\":\"Iris gwapa\",\"message\":\"Your appointment with Iris gwapa has been completed.\"}', NULL, '2024-12-13 01:04:31', '2024-12-13 01:04:31'),
('38e1052f-4699-4bb5-a33e-654cf78d82ed', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 22, '{\"appointment_id\":25,\"status\":\"accepted\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been accepted.\"}', NULL, '2024-12-12 23:36:38', '2024-12-12 23:36:38'),
('3c4f82b0-0cb7-48bc-87b2-366156f12eea', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 22, '{\"appointment_id\":25,\"status\":\"completed\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been completed.\"}', NULL, '2024-12-12 23:37:35', '2024-12-12 23:37:35'),
('4394229d-6eb8-45e6-9c0c-49be99fc92fa', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":39,\"status\":\"accepted\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been accepted.\"}', NULL, '2025-03-01 22:16:57', '2025-03-01 22:16:57'),
('45853d0c-6c89-4f23-b800-370286f3e01c', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":37,\"customer_name\":\"eljohn adlawan\",\"date\":\"2025-02-27\",\"time\":\"12:30 PM\",\"address\":\"cebu,naga\",\"contact\":\"9887654321\"}', '2025-05-01 06:00:14', '2025-02-23 02:34:23', '2025-05-01 06:00:14'),
('49ea78ae-65b8-4374-835b-3fed0707c680', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":40,\"customer_name\":\"eljohn adlawan\",\"date\":\"2025-03-13\",\"time\":\"09:00 AM\",\"address\":\"cebu,naga\",\"contact\":\"9887654321\"}', '2025-03-04 03:27:10', '2025-03-01 22:53:12', '2025-03-04 03:27:10'),
('4e1dadf8-41bc-4139-932e-dbb3e2ef84fa', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"completed\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been completed.\"}', NULL, '2024-12-13 04:49:35', '2024-12-13 04:49:35'),
('520d5daf-c56c-49ea-95b1-35d9822ac3e9', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":30,\"status\":\"accepted\",\"freelancer_name\":\"Iris gwapa\",\"message\":\"Your appointment with Iris gwapa has been accepted.\"}', NULL, '2024-12-13 01:04:13', '2024-12-13 01:04:13'),
('59db6634-c43d-4ad0-8c88-6c1006e36b59', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":45,\"status\":\"declined\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been declined.\"}', '2025-05-03 06:19:45', '2025-05-01 01:09:41', '2025-05-03 06:19:45'),
('5c486370-778c-4100-a829-6606dbb2acac', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2024-12-13 01:47:08', '2024-12-13 01:47:08'),
('6f1e9b2f-4592-40db-8fdf-56dfcc79b4f2', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2024-12-13 05:27:16', '2024-12-13 05:27:16'),
('71b9970e-9017-42ff-b70c-a5af8c0eb664', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 8, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":2}', '2025-02-26 00:17:48', '2025-02-25 00:52:11', '2025-02-26 00:17:48'),
('7e2ace61-4a63-4843-b1c1-2d250a3d0b6d', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":46,\"status\":\"declined\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been declined.\"}', '2025-05-03 06:19:41', '2025-05-01 05:59:45', '2025-05-03 06:19:41'),
('8173635e-df1a-48c3-af32-2c39e49ecbcb', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":23,\"customer_name\":\"delantar\",\"date\":\"2024-12-12\",\"time\":\"09:30 AM\",\"address\":\"skenalangit\",\"contact\":\"123123123123\"}', '2025-04-30 19:33:42', '2024-12-12 02:00:40', '2025-04-30 19:33:42'),
('84174719-c8e7-4120-a474-88019ba14f25', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":45,\"customer_name\":\"jhonardtest cabizares\",\"date\":\"2025-05-04\",\"time\":\"08:00 AM\",\"address\":\"cebu,minglanilla\",\"contact\":\"9912236143\",\"message\":\"You have a new appointment request from jhonardtest cabizares on 2025-05-04 at 08:00 AM.\"}', '2025-05-01 00:22:58', '2025-05-01 00:11:27', '2025-05-01 00:22:58'),
('8ff28d1c-8167-4e7b-a3c1-a7e41aaf842a', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":42,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2025-03-19 23:36:30', '2025-03-19 23:36:30'),
('93ce578c-e773-4b1a-87f7-09072ce88d21', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":40,\"status\":\"completed\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been completed.\"}', NULL, '2025-03-01 22:53:47', '2025-03-01 22:53:47'),
('96595f86-bbd4-41ca-a8c1-0f080e45cc9f', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":38,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', NULL, '2025-02-25 02:49:15', '2025-02-25 02:49:15'),
('9a604d45-2364-46cf-83f8-62206ec53727', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":20,\"status\":\"accepted\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been accepted.\"}', NULL, '2024-12-10 23:36:21', '2024-12-10 23:36:21'),
('9a9f34ca-b474-4e16-aa37-e5f794ca04cd', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":47,\"status\":\"completed\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been completed.\"}', NULL, '2025-05-06 00:41:17', '2025-05-06 00:41:17'),
('9b45913a-f870-4084-bd3f-009957b90c07', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":41,\"status\":\"accepted\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been accepted.\"}', NULL, '2025-03-19 05:59:45', '2025-03-19 05:59:45'),
('9c454d9e-227f-4642-8983-140d9a241610', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":30}', '2025-03-09 22:58:36', '2025-03-09 20:23:56', '2025-03-09 22:58:36'),
('9d310426-b449-4bf8-b06d-e8b4e008120e', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":42,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2025-03-19 23:15:52', '2025-03-19 23:15:52'),
('a1880628-fc13-4b42-8e3c-8dc09e2ecf51', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 8, '{\"appointment_id\":41,\"customer_name\":\"eljohn adlawan\",\"date\":\"2025-03-20\",\"time\":\"01:00 PM\",\"address\":\"cebu,naga\",\"contact\":\"9887654321\"}', NULL, '2025-03-18 07:19:18', '2025-03-18 07:19:18'),
('a4fbe8a7-19ab-4a86-a7e1-ce68110d99ab', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":35,\"customer_name\":\"angel waypako\",\"date\":\"2024-12-17\",\"time\":\"09:00 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"9786756621\"}', '2025-05-01 06:00:16', '2024-12-13 18:13:30', '2025-05-01 06:00:16'),
('a601bbbd-3984-4d00-b6ed-cd4b11bdb6de', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 23, '{\"appointment_id\":29,\"status\":\"declined\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been declined.\"}', NULL, '2024-12-13 04:18:38', '2024-12-13 04:18:38'),
('a69bc8e1-887f-40d9-8920-8bd0a15f0adb', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":23,\"status\":\"completed\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been completed.\"}', NULL, '2024-12-12 02:02:40', '2024-12-12 02:02:40'),
('a7529fa0-e6b9-4827-a33a-f5e3fe3895c9', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 24, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":19}', NULL, '2025-02-25 02:44:59', '2025-02-25 02:44:59'),
('a9e24aa0-38a2-46c5-9b16-4860442a0430', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":41,\"status\":\"completed\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been completed.\"}', NULL, '2025-03-19 07:01:07', '2025-03-19 07:01:07'),
('abc72a43-5b64-4195-86a6-0d2ad64257bd', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 27, '{\"appointment_id\":35,\"status\":\"completed\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been completed.\"}', NULL, '2024-12-13 18:16:29', '2024-12-13 18:16:29'),
('af279ffe-a880-410d-8836-a144b680e130', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":39,\"status\":\"completed\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been completed.\"}', NULL, '2025-03-01 22:17:00', '2025-03-01 22:17:00'),
('b2b0a6d1-69eb-4187-bc37-21be8beec3f7', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 8, '{\"appointment_id\":42,\"customer_name\":\"rica mae laspuna\",\"date\":\"2025-03-25\",\"time\":\"11:00 AM\",\"address\":\"cebu,minglanilla\",\"contact\":\"9988776578\"}', NULL, '2025-03-19 06:57:34', '2025-03-19 06:57:34'),
('be824719-fb8c-4bb6-88b9-d118396383ff', 'App\\Notifications\\PostApprovedNotification', 'App\\Models\\User', 29, '{\"message\":\"Your post titled \\\"\\\" has been approved!\",\"post_id\":23}', NULL, '2025-02-25 03:16:40', '2025-02-25 03:16:40'),
('c2d05573-547f-4de0-9fe5-42c620385d8c', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":45,\"status\":\"declined\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been declined.\"}', '2025-05-03 06:19:38', '2025-05-01 01:09:48', '2025-05-03 06:19:38'),
('c47b7d4a-064e-4f29-978e-ec96dccfdbb3', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":47,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', NULL, '2025-05-06 00:40:32', '2025-05-06 00:40:32'),
('c69f6199-0d40-49ba-9aea-86b4cdf561b3', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":43,\"customer_name\":\"jhonardtest cabizares\",\"date\":\"2025-04-25\",\"time\":\"09:00 AM\",\"address\":\"cebu,minglanilla\",\"contact\":\"9912236143\"}', '2025-04-30 19:33:50', '2025-04-24 05:06:09', '2025-04-30 19:33:50'),
('c8ded713-7c13-47be-807d-a93eda0878b0', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 10, '{\"appointment_id\":38,\"customer_name\":\"aljun delantar\",\"date\":\"2025-02-28\",\"time\":\"04:30 PM\",\"address\":\"cebu,naga\",\"contact\":\"9887434344\"}', '2025-03-04 03:27:10', '2025-02-25 02:46:31', '2025-03-04 03:27:10'),
('c95e645d-2d34-45b5-8d3c-f51c17b09dd3', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":23,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', NULL, '2024-12-12 02:01:49', '2024-12-12 02:01:49'),
('cbf350ec-6b42-4452-9d44-50a6fedafc9c', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":32,\"customer_name\":\"markdoe\",\"date\":\"2024-12-14\",\"time\":\"09:30 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"978675662134\"}', '2025-05-01 06:00:16', '2024-12-13 07:14:18', '2025-05-01 06:00:16'),
('ccfb4631-0e89-4dd1-b8ba-48fa9ed74057', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":46,\"status\":\"declined\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been declined.\"}', '2025-05-04 03:45:06', '2025-05-01 06:27:18', '2025-05-04 03:45:06'),
('d0b245c8-a4ae-4b85-b23e-62bdf306151f', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 14, '{\"appointment_id\":26,\"customer_name\":\"apple\",\"date\":\"2024-12-25\",\"time\":\"01:00 PM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"978675662134\"}', NULL, '2024-12-12 23:33:21', '2024-12-12 23:33:21'),
('d141e340-c1b8-4145-aa2d-c815204ab8ca', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":27,\"status\":\"accepted\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been accepted.\"}', NULL, '2024-12-12 23:56:15', '2024-12-12 23:56:15'),
('d180300a-1da2-463e-af8b-2be58850a898', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"accepted\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been accepted.\"}', NULL, '2024-12-13 02:03:14', '2024-12-13 02:03:14'),
('d3134284-8625-49ad-89b8-75b160edcf30', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":38,\"status\":\"completed\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been completed.\"}', NULL, '2025-02-25 02:49:28', '2025-02-25 02:49:28'),
('d61bf590-eb36-431d-bec7-d957c342b93c', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":37,\"status\":\"accepted\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been accepted.\"}', NULL, '2025-02-23 02:43:02', '2025-02-23 02:43:02'),
('d6668ec4-7659-4f04-b92c-9950d1e49548', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":40,\"status\":\"accepted\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been accepted.\"}', NULL, '2025-03-01 22:53:40', '2025-03-01 22:53:40'),
('dbc12ebd-f6ff-45d8-a6e3-de3ad4c52a66', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 15, '{\"appointment_id\":24,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2024-12-13 01:58:31', '2024-12-13 01:58:31'),
('dbd3eb1f-1263-432e-9845-2a66fa675195', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 26, '{\"appointment_id\":33,\"status\":\"declined\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been declined.\"}', NULL, '2024-12-13 07:14:53', '2024-12-13 07:14:53'),
('dcea83b9-e893-421f-835c-294531e49f9c', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":19,\"status\":\"completed\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been completed.\"}', NULL, '2024-12-10 23:50:36', '2024-12-10 23:50:36'),
('dd306674-12e4-4946-975d-bf8a34c54f20', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":39,\"customer_name\":\"eljohn adlawan\",\"date\":\"2025-03-28\",\"time\":\"07:00 PM\",\"address\":\"cebu,naga\",\"contact\":\"9887654321\"}', '2025-05-01 06:00:13', '2025-03-01 22:13:15', '2025-05-01 06:00:13'),
('e0884374-309c-41d4-9fe6-97ddc893a832', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":27,\"status\":\"completed\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been completed.\"}', NULL, '2024-12-13 00:01:22', '2024-12-13 00:01:22'),
('e1dc5967-5054-4622-ba9f-a60caf79666b', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 47, '{\"appointment_id\":45,\"status\":\"declined\",\"freelancer_name\":\"jake renan\",\"message\":\"Your appointment with jake renan has been declined.\"}', '2025-05-03 06:35:03', '2025-05-01 05:43:10', '2025-05-03 06:35:03'),
('e6e8e1c3-4d26-48cf-af37-66fe8f546554', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 18, '{\"appointment_id\":42,\"status\":\"declined\",\"freelancer_name\":\"jhonard victorillo\",\"message\":\"Your appointment with jhonard victorillo has been declined.\"}', NULL, '2025-03-19 23:15:53', '2025-03-19 23:15:53'),
('ecf1795d-d976-4325-a2f4-9b69c2e741b0', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 20, '{\"appointment_id\":28,\"status\":\"accepted\",\"freelancer_name\":\"rohanna villiagracia\",\"message\":\"Your appointment with rohanna villiagracia has been accepted.\"}', NULL, '2024-12-13 00:06:08', '2024-12-13 00:06:08'),
('edf4345d-cbac-47ff-a0bb-c91a5cc805fa', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 25, '{\"appointment_id\":46,\"customer_name\":\"jhonardtest cabizares\",\"date\":\"2025-05-09\",\"time\":\"03:00 PM\",\"address\":\"cebu,minglanilla\",\"contact\":\"9912236143\",\"message\":\"You have a new appointment request from jhonardtest cabizares on 2025-05-09 at 03:00 PM.\"}', '2025-05-01 06:00:18', '2025-05-01 05:58:18', '2025-05-01 06:00:18'),
('effb1492-ed3b-4baa-a64a-baed35c35dee', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 8, '{\"appointment_id\":24,\"customer_name\":\"aljun delantar\",\"date\":\"2024-12-18\",\"time\":\"09:30 AM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"9897877866\"}', '2025-02-26 00:17:56', '2024-12-12 20:16:16', '2025-02-26 00:17:56'),
('f6043fb0-f801-477f-97f1-3d7b7f4af1ea', 'App\\Notifications\\AppointmentRequest', 'App\\Models\\User', 14, '{\"appointment_id\":27,\"customer_name\":\"EljohnDave\",\"date\":\"2024-12-13\",\"time\":\"06:30 PM\",\"address\":\"minglanilla ,cebu\",\"contact\":\"978675662134\"}', NULL, '2024-12-12 23:55:21', '2024-12-12 23:55:21'),
('fd80e4dc-7c30-4294-aac9-fcdcfb2324a7', 'App\\Notifications\\AppointmentStatusUpdated', 'App\\Models\\User', 9, '{\"appointment_id\":37,\"status\":\"completed\",\"freelancer_name\":\"edraly gereldez\",\"message\":\"Your appointment with edraly gereldez has been completed.\"}', NULL, '2025-02-23 02:43:07', '2025-02-23 02:43:07');

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `freelancer_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `freelancer_id`, `status`, `description`, `created_at`, `updated_at`) VALUES
(7, 10, 'approved', 'Unleash the Welding Genius Within.', '2024-12-02 19:52:42', '2025-02-25 02:43:34'),
(19, 24, 'approved', 'sfsfdfs', '2024-12-13 01:00:15', '2025-02-25 02:44:59'),
(21, 25, 'approved', 'minglanilla area only homes service', '2024-12-13 06:42:18', '2025-02-24 23:13:24'),
(23, 29, 'approved', 'somewhere in minglanilla', '2025-02-25 03:15:35', '2025-02-25 03:16:40'),
(31, 10, 'approved', 'test', '2025-03-09 22:58:26', '2025-03-09 23:00:00'),
(38, 8, 'pending', 'testing post edit', '2025-03-28 06:13:07', '2025-03-28 07:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `post_pictures`
--

CREATE TABLE `post_pictures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_pictures`
--

INSERT INTO `post_pictures` (`id`, `post_id`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 38, 'post_pictures/R8N1W4aIlX7y3GrKkIHy84hj8ZEUSsPm3JRYeKMK.jpg', '2025-03-28 06:13:08', '2025-03-28 06:13:08'),
(2, 38, 'post_pictures/AkeazxUOrPuwC5GoVeMYbya5E8wphPC1lS3Z3XXy.jpg', '2025-03-28 06:13:08', '2025-03-28 06:13:08'),
(4, 7, 'post_pictures/Jh9BJtMAowy61oquMgsUWbPFRS6ASpU59pexP4Ab.jpg', '2025-03-28 07:23:39', '2025-03-28 07:23:39'),
(5, 7, 'post_pictures/9xl3OeANBsDYkEns4MMRjItYRGIpoIDmh3yAHgts.jpg', '2025-03-28 07:23:39', '2025-03-28 07:23:39'),
(6, 7, 'post_pictures/zH8CmtvyYxMwlPUnVWGlFlYNpsfBqQyY0N3Ix5Rs.jpg', '2025-03-28 07:23:39', '2025-03-28 07:23:39'),
(7, 21, 'post_pictures/VIfyCPclwaVlW954f8a6Q3RbrywzaEI4zNX25j1K.jpg', '2025-03-28 07:27:54', '2025-03-28 07:27:54'),
(8, 31, 'post_pictures/D6gteVfem0HX8vmghemrDNWoRiUJwnjnPV2EhkZJ.jpg', '2025-05-01 00:09:58', '2025-05-01 00:09:58'),
(9, 31, 'post_pictures/A5Cp5Av3ZNpB8Xd96Bxr3MTaqh7iqza5acd4RDdh.jpg', '2025-05-01 00:09:58', '2025-05-01 00:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `post_sub_services`
--

CREATE TABLE `post_sub_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `sub_service` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_sub_services`
--

INSERT INTO `post_sub_services` (`id`, `post_id`, `sub_service`, `created_at`, `updated_at`) VALUES
(71, 7, 'gate-welding', '2025-03-28 07:23:39', '2025-03-28 07:23:39'),
(72, 7, 'car canopy', '2025-03-28 07:23:39', '2025-03-28 07:23:39'),
(73, 21, 'landscaping', '2025-03-28 07:27:54', '2025-03-28 07:27:54'),
(156, 38, 'organizer', '2025-04-03 00:52:37', '2025-04-03 00:52:37'),
(157, 38, 'beauty gromming', '2025-04-03 00:52:37', '2025-04-03 00:52:37'),
(158, 31, 'plasma arc welding', '2025-05-01 00:09:57', '2025-05-01 00:09:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CP7eil4hVRaTSeWCyl8KTogm8rx0uUxKaUg3PKHQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR280NWZHQjVRcENEdEE0VXZ3cXg3QXI0NXhRclREWUtlV1dxYUJFMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1746529774),
('HN5PDz8LjgICva7gy3sL696m8PA6JvRqlqU2p3JA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0dzTlhUenVDejY2c2JkbEx1NkgzdmVDaEdBcW9zTUFPbXZwdVk4YyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1746529783);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `experience_level` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `email_verification_token` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_completed` tinyint(1) NOT NULL DEFAULT 0,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `google_map_link` text DEFAULT NULL,
  `id_front` varchar(255) DEFAULT NULL,
  `id_back` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `experience_level`, `email_verified_at`, `email_verification_token`, `password`, `role`, `contact_number`, `remember_token`, `created_at`, `updated_at`, `profile_completed`, `province`, `city`, `zipcode`, `google_map_link`, `id_front`, `id_back`, `profile_picture`, `is_verified`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'Jhonard', 'victorillo', 'Admin@gmail.com', NULL, '2025-04-24 14:35:32', NULL, '$2y$12$eMs1ey6NWXMSyjOZNQpEJeNuEziSOrCytpjHUN6NoFOhdCK1eaaLu', 'admin', '9912236143', NULL, '2024-11-11 19:11:24', '2024-11-13 17:35:47', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/uL4SD966DsBQKwlb52YFtCP2mTMgPmELb2Nmze9s.jpg', 'id_uploads/cWUV2uNYZeRIXUXLnTn3qkN42l1700CXAIPKpbdD.jpg', NULL, 0, NULL, NULL),
(8, 'jhonard', 'victorillo', 'jhonard@gmail.com', 'Intermediate', '2025-04-24 14:36:08', NULL, '$2y$12$jELctrUR1LBTzf3cNisdkeMKfK7OGdzMHt4kALmNCH0ujY16kaNKy', 'freelancer', '9876543211', NULL, '2024-11-27 17:22:26', '2025-04-24 08:46:41', 1, 'cebu', 'naga', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/qIdJKDAWux6tCsJKFkYSmmiQfkwQdDp4VXhXjR3Z.png', 'id_uploads/EJxziP7DrduWpF450sTmm6vaepW3kUTQgymzXeFo.jpg', 'profile_pictures/H6AjSbY87xIiCjlTnPQIF4mGSFQ1iO1WSoILqbdj.jpg', 0, NULL, NULL),
(9, 'eljohn', 'adlawan', 'eljohn@gmail.com', NULL, '2025-04-24 16:53:51', NULL, '$2y$12$OzYpyO2gCtz1XcfeYLhGVeWc6eWqNDzuQepDqhdH5U5GnAxCPyMOa', 'customer', '9887654321', NULL, '2024-11-27 17:59:49', '2024-11-27 18:10:10', 1, 'cebu', 'naga', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/eJVeAvfUMweAa9vNAzvyyCyTMy7K5ix8brbnFCwc.png', 'id_uploads/qvhDgsrEQ3lU3lyEiHfNgVpaKfk3lVn9zV5E0H9c.jpg', 'profile_pictures/eQ9Ui6elj3pluJN1aETtkBg2OXAUwbLZrJmrBfZM.jpg', 0, NULL, NULL),
(10, 'jake', 'renan', 'jake@gmail.com', NULL, '2025-04-24 16:52:12', NULL, '$2y$12$4cBwRrLiGubgLkzjNP2DLOOypvj2Pvsx5KhAoIzlQf9s3nhizYw2q', 'freelancer', '9987654321', NULL, '2024-11-27 18:35:42', '2025-04-04 05:58:23', 1, 'cebu', 'naga', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/hSx6vBGJCPBBTeOHsoP1rlpcPEN6wqRfdOfZyeVL.jpg', 'id_uploads/M0UBeAGMnfGrSAh86mTJ611kIDPgYXEpC0jAVOcB.png', 'profile_pictures/eCbomH2UuD2yXW4rjLyZN9vnZhGVJloG4QBrrhiX.jpg', 1, NULL, NULL),
(11, 'romeo', 'albarando', 'romeo@gmail.com', NULL, NULL, NULL, '$2y$12$9zQ9b8Gor1i1lMYS9wNqF.Bwk7xf0reYmN4zVOWGhJlJqXpNqxqk.', 'customer', '9987764332', NULL, '2024-11-28 03:09:42', '2024-11-28 03:10:51', 1, 'cebu', 'minglanilla', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/MeDqwt2MMcY04IBy1q6G2oyiqfqUP3BcyXxBHN5u.png', 'id_uploads/8VZ8DVabdNTwwBn2YGVqWsWEVx1M4k4yEhKSDwsg.png', 'profile_pictures/IzIls4aWLnf7zwZkVS2XyEicrG9AT0lLWGBkVJSr.jpg', 0, NULL, NULL),
(12, 'aya', 'ybas', 'aya@gmail.com', NULL, NULL, NULL, '$2y$12$0XuUYECMCEoMB61mbswMKeTlTDyopcCWB/1m5XpzCoSD0OQXtBCN6', 'freelancer', '9988744343', NULL, '2024-11-30 05:17:44', '2024-11-30 18:23:25', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/nIxszODfuArMJagpvLhwd9nLk6VrhVhmmmpo0oJU.png', 'id_uploads/q3ZfUevojFnaCDDAKXdFYNdDtXJkhJ5KLGOb1dEF.png', 'profile_pictures/GXGYQPseAeRRz9GkfwnC7vqqeRLYGoMySMZvH37Y.jpg', 0, NULL, NULL),
(15, 'aljun', 'delantar', 'aljun@gmail.com', NULL, NULL, NULL, '$2y$12$QViHSjt8Fh7gyUuVRG3Z/elX8dLTZjxVlk.wZPMX8g24g0qqUxlDC', 'customer', '9887434344', NULL, '2024-12-01 02:23:09', '2024-12-01 02:26:15', 1, 'cebu', 'naga', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/cpWGgRfn3pUS4YN4DZAncHVTavFqIwoLpYC4ZhQZ.png', 'id_uploads/7E14yi5AoeGAl9O7aI5lqS4PuX6fnNs30VH2QjS3.png', 'profile_pictures/EbS4p7ZHJQ9RWTWMUlz0vj4v6wBhSeleCkv5ttVu.jpg', 0, NULL, NULL),
(16, 'Ian', 'Tradio', 'iantrads@gmail.com', NULL, NULL, NULL, '$2y$12$7noHGYr8xgw.xYI1nmplEeq.Z5AvHFgju9lcT7Cmu29a6F/JAdQgC', 'customer', '9192934712', NULL, '2024-12-06 21:16:51', '2024-12-06 21:19:17', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/zDA8fWr97IQlji2eGSlDVs2qLAd52MBGPERUYdc5.png', 'id_uploads/np99q1hlKy2tWk0tcLudGGIJniDhn1YtWLCkHH7F.png', 'profile_pictures/4IvJf5uMJGXOcZfaWDZMxw07LsXJwRagaQQnnZO1.jpg', 0, NULL, NULL),
(17, 'yang', 'test', 'yang@gmail.com', NULL, NULL, NULL, '$2y$12$OMfioL5QHSW6qFNU0wVbm.gb7qjQHvQR2cdeSddA8BGkVt3pX1uvy', 'freelancer', '9988776578', NULL, '2024-12-06 22:42:20', '2024-12-06 22:44:45', 1, 'cebu', 'naga', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/JS5oHYnjyy4OTClbMUt97tmdPzDAUx9qVOpVPeE7.png', 'id_uploads/N0aR9QXCoJ1CcBamHy3RqQJy7mMvqGiQxbkomSCu.jpg', 'profile_pictures/oa4hDAQ6z1vFtWLlq3Rcqu0HtnTWdpJ4VkJtfgOq.jpg', 0, NULL, NULL),
(18, 'rica mae', 'laspuna', 'rica@gmail.com', NULL, NULL, NULL, '$2y$12$UvFy7ElPnVwcHOm/r6ijaeYUdkllUmYoiYphIQkmoOyncg18h6vgm', 'customer', '9988776578', NULL, '2024-12-08 18:58:14', '2024-12-08 18:58:56', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, 'profile_pictures/tNGK12H3zyXLTP0JydbEP5igpdhhiiVUtJmfRjIx.jpg', 0, NULL, NULL),
(19, 'Angel', 'blance', 'ange@gmail.com', NULL, NULL, NULL, '$2y$12$j0e2YwlTUkF5dhts4FXVmuyLC6bkSMoSWIqZXXfNiWso/5/rJNlHO', 'freelancer', '9988776578', NULL, '2024-12-08 19:03:56', '2024-12-08 19:09:55', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/13mTQ5ETEHyb8M7obnkgUirz9VHKGrJcgbzhDeTU.png', 'id_uploads/0sXgXBKij8sCNNYy6xvfRNnxKaKHHvpb9QSDUdB5.png', 'profile_pictures/ZX1NLZGxRZRmDrp6iPtiJlAd7VdVCtThtQIByWZL.jpg', 0, NULL, NULL),
(20, 'deboy', 'adlawan', 'deboy@gmail.com', NULL, NULL, NULL, '$2y$12$PzfhxB5Zn0tp77zh3koat.FnClPe0G0SMj11mrqubX4LDNzMpwAhS', 'customer', '0919292382', NULL, '2024-12-11 23:19:04', '2024-12-11 23:22:13', 1, 'cebu', 'toledo', '6036', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, 'profile_pictures/8bjtkkRWbgsWaZ5N09NN2W4YwknA6iKEorva30B3.jpg', 0, NULL, NULL),
(21, 'Dave', 'Adlawan', 'dave@gmail.com', NULL, NULL, NULL, '$2y$12$hTqYSGf6P2QInyoK59tlu.4hyqEZLoQQTwdI3A88OnvMw2bTM8raC', 'freelancer', '9192934712', NULL, '2024-12-12 01:31:30', '2024-12-12 01:34:02', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/TYlcIIiOl221oZUznU7RbGFTqyvvqRTvJJ5v060B.jpg', 'id_uploads/rVGTzXQlbJdiLN2xfnIPcn7sSkBU4yTx4O7xFUNc.jpg', 'profile_pictures/rr1fG7LfzWdYCasIiFMJON6EcsuCLKwDEISdadXP.jpg', 0, NULL, NULL),
(22, 'Hanna', 'Villagracia', 'han@gmail.com', NULL, NULL, NULL, '$2y$12$oLeSsVtBDzYXDRmq47I2w.Xe5U4VX/r4Qcnow.OO1u6whLtzWqYE2', 'customer', '0919297657', NULL, '2024-12-12 23:29:45', '2024-12-12 23:31:09', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, 'profile_pictures/96ybXwGy5oT8PxP5PENEcriavqc5GnpDESzsFjyG.jpg', 0, NULL, NULL),
(23, 'aya', 'ybas', 'ayaybas@gmail.com', NULL, NULL, NULL, '$2y$12$zMn/A.wy3Jk85jNzORtVp.hUC6HRjnhavsULWTERhHZwGwRBj2sqa', 'customer', '0927672090', NULL, '2024-12-13 00:52:54', '2024-12-13 00:53:45', 1, 'cebu', 'minglanilla', '6046', NULL, NULL, NULL, 'profile_pictures/QxlC9ldiH6kPQ9zdU5A05gSTPIydTRztdHylPZFA.jpg', 0, NULL, NULL),
(24, 'Iris', 'gwapa', 'irisgwapa@gm', NULL, NULL, NULL, '$2y$12$WmR.7Z/wLFzvQ7e/8XiOXu2urXP0wFgrhx3XxVYTFXIb6O17g03Xu', 'freelancer', '9786756621', NULL, '2024-12-13 00:58:34', '2024-12-13 00:59:32', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/C4P8IPan0wALhusuMYjvAqu6Ja3M6ZVDBMx2bFGI.jpg', 'id_uploads/I6fde6vTyYqENCqiFgdl6mjxr5Y5rzblsxgq2ikS.jpg', 'profile_pictures/ws81beATOTSpy9liSOg7N9flVGDc3crTEsoMzKHn.jpg', 0, NULL, NULL),
(25, 'edraly', 'gereldez', 'edralyn@gmail.com', NULL, '2025-04-24 16:54:45', NULL, '$2y$12$6Op3S.ROOVB4TEGE4oBdOO76FyBp03bU4Esdls.jiZcLRVp6ybCwu', 'freelancer', '9786756621', NULL, '2024-12-13 06:39:46', '2025-04-04 06:29:08', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/HvRm6WDxIvaLC8yblua1PTUpbtqdIUWmIOqhHsd6.png', 'id_uploads/a2lXZhx5KGkO4acWeTPX14cMCP47buQV1Sdx4r7Q.jpg', 'profile_pictures/ZOPbljFkDLqAZfcBhZW9gg0LyZxFTmpJeMWgoatJ.jpg', 1, NULL, NULL),
(26, 'mark', 'doe', 'mark@gmail.com', NULL, NULL, NULL, '$2y$12$g42KlQ2b98IfBGIs25cVn.nJ5Lr06dDF.hHFATJII.Ic/o9g6OJ/q', 'customer', '9786756621', NULL, '2024-12-13 07:13:03', '2024-12-13 07:13:39', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, 'profile_pictures/mvACbthIJJwdgYLorEA0gdV8nfPJEeESrOlJaTo4.jpg', 0, NULL, NULL),
(27, 'angel', 'waypako', 'angel@gmail.com', NULL, NULL, NULL, '$2y$12$H/Hu/QF/Zlh4JHHN4klIIexKMnm0aiyfnUh0BjQd/WzlX0SDHoPc6', 'customer', '0919293901', NULL, '2024-12-13 18:01:32', '2024-12-13 18:02:02', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', NULL, NULL, 'profile_pictures/l1Svp1Y1byxUAERTo93FRVWyYchtfgO2i9zJ2fMB.jpg', 0, NULL, NULL),
(28, 'Ursula', 'Cochran', 'baxu@mailinator.com', NULL, NULL, NULL, '$2y$12$ygRsuKNoGSkv69gLR0WDz.UYH0IKbhkYOoI3TRPIoII6gKrBZ/uGe', NULL, '0919292382', NULL, '2025-02-22 20:07:59', '2025-02-22 20:07:59', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(29, 'cristian', 'dejan', 'cristian@gmail.com', NULL, NULL, NULL, '$2y$12$a8bJC8dmoAN2L.uvoDMvQuGIj/t42GaYPmWz5EzMgyH05/Z47dWTC', 'freelancer', '0912345667', NULL, '2025-02-25 03:01:23', '2025-02-25 03:10:48', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/place/Minglanilla,+Cebu/@10.2766114,123.7368158,13z/data=!3m1!4b1!4m6!3m5!1s0x33a977e4598c638d:0xd2016057b1f9cd28!8m2!3d10.2454075!4d123.7959226!16zL20vMDZoNGhs?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D', 'id_uploads/I5QYiHdlmlZ2ShtX7NWsUpOZUiFAt2XmWcc2C8DU.jpg', 'id_uploads/5bkAUxGS3tu2MrjwuRiOR5Llxu5CHef2ToWRSj00.jpg', 'profile_pictures/YFRvCSE4G8GNwZ2slvz0kNUOucXMNonsRm2JPuRX.jpg', 0, NULL, NULL),
(30, 'test map', 'testing', 'map@gmail.com', NULL, NULL, NULL, '$2y$12$IJE2kB.oXEkjC79jTh2.c.ljxrpdWmkb7mBS5O5e.RVhEBbeOTpgW', 'freelancer', '9883223332', NULL, '2025-04-03 23:45:08', '2025-04-04 01:17:24', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31405.28549524478!2d123.67617636735018!3d10.288899575118005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9712f6e322741%3A0x9e9019d7f73bad33!2sMayana%2C%20Cebu!5e0!3m2!1sen!2sph!4v1743756400256!5m2!1sen!2sph', 'id_uploads/SVFBb3DO2emwikoHfFQTmxTCPliIcAD47xqTXnrL.jpg', 'id_uploads/JBHkM1xzQyhGbzAhip7ulDNrNOBghCHg5BjbSPFp.jpg', 'profile_pictures/E49LQtAUycVM7HLRluK5HVK9csylSP1IzMLjZgui.jpg', 0, NULL, NULL),
(31, 'jhonard', 'Victorillo', 'jhonardVictorillo@gmail.com', NULL, NULL, NULL, '$2y$12$TCxxie8gXNJBKyvxnT3dzu0Pzk75RS6JIqRuJsVK0B0zgId9DTOnS', NULL, '9912236143', NULL, '2025-04-19 08:08:26', '2025-04-19 08:08:26', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(46, 'jhonard', 'cabizares', 'victorillojhonard+2@gmail.com', NULL, NULL, '9lLzFk1fnDwNdr5EQKJZ2exQxd993eB8bUZ0pwMTH8WsVmDRcgMdyl7CAfvH', '$2y$12$ljhW3tzll01qqqWksa8p5eAJ79iDvqr61w4aepv.nwpsWi2d2SMaS', NULL, '9912236143', NULL, '2025-04-19 09:28:04', '2025-04-19 09:28:04', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(47, 'jhonard', 'cabizares', 'jhonardVictorillo+1@gmail.com', NULL, '2025-04-19 09:32:43', NULL, '$2y$12$Ew8rGZsGEgKFE.wUsUXWquo.2Hg/UtSXwIGtBBs9umXRpyaNsVmfe', 'customer', '9912236143', NULL, '2025-04-19 09:31:16', '2025-05-03 21:45:17', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31405.28549524478!2d123.67617636735018!3d10.288899575118005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9712f6e322741%3A0x9e9019d7f73bad33!2sMayana%2C%20Cebu!5e0!3m2!1sen!2sph!4v1743756400256!5m2!1sen!2sph', NULL, NULL, 'profile_pictures/9RBLIwFUThWf1da8dLMqlbc6MsYnAc9XCcAMoM5Q.jpg', 0, NULL, NULL),
(48, 'Danna', 'Gellica', 'dannagellica@gmail.com', NULL, '2025-04-24 06:40:35', NULL, '$2y$12$w1aVk5cQHtRWlYZoSXsZlu/yYolrMMC1rHRN.dX3WdEeyb0spB2m6', 'freelancer', '9878764545', NULL, '2025-04-24 06:39:10', '2025-04-27 05:53:20', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15702.643773767091!2d123.68645483500737!3d10.288878949407925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9712f6e322741%3A0x9e9019d7f73bad33!2sMayana%2C%20Cebu!5e0!3m2!1sen!2sph!4v1745497078714!5m2!1sen!2sph', 'id_uploads/3bBxAhmzpRMX8SlZbcn96ZJXJqG3Qm0ZMgMWJIy5.jpg', 'id_uploads/KqPgEdz2QR2i3xlT85WoQibyPhCt4YjqDBM5koS3.jpg', 'profile_pictures/PeeGwapnuoHRuuoymGYFF0UodoxUKtEHSQVa3Diu.jpg', 0, NULL, NULL),
(49, 'dejan', 'delantar', 'victorillojhonard+5@gmail.com', 'Expert', '2025-04-30 04:41:48', NULL, '$2y$12$DCGOscOugcLIpCgqna7vdOOweI.P.BJjG6AUhCa9Rk0UNncScoWFy', 'freelancer', '9887434342', NULL, '2025-04-30 04:40:16', '2025-05-03 23:31:07', 1, 'cebu', 'minglanilla', '6046', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62813.01493338469!2d123.73673004108085!3d10.276611395168302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a977e4598c638d%3A0xd2016057b1f9cd28!2sMinglanilla%2C%20Cebu!5e0!3m2!1sen!2sph!4v1746016998122!5m2!1sen!2sph', 'id_uploads/xLptCBp7deVKJ6y0aGc1i2FOar1m5cbfbQzh9mPI.jpg', 'id_uploads/ziDoBKjsagmmdq4DDrPmh3o1iBp9fbfVDW9QTnYj.jpg', 'profile_pictures/NPr0qzKH2JMbcwbuIbnEQMrKGZ7DHb1urGMchdw7.jpg', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_freelancer_id_foreign` (`freelancer_id`),
  ADD KEY `appointments_customer_id_foreign` (`customer_id`),
  ADD KEY `appointments_post_id_foreign` (`post_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `category_user`
--
ALTER TABLE `category_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_user_user_id_foreign` (`user_id`),
  ADD KEY `category_user_category_id_foreign` (`category_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_freelancer_id_foreign` (`freelancer_id`);

--
-- Indexes for table `post_pictures`
--
ALTER TABLE `post_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_pictures_post_id_foreign` (`post_id`);

--
-- Indexes for table `post_sub_services`
--
ALTER TABLE `post_sub_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_sub_services_post_id_foreign` (`post_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `category_user`
--
ALTER TABLE `category_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `post_pictures`
--
ALTER TABLE `post_pictures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `post_sub_services`
--
ALTER TABLE `post_sub_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_freelancer_id_foreign` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_user`
--
ALTER TABLE `category_user`
  ADD CONSTRAINT `category_user_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_freelancer_id_foreign` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_pictures`
--
ALTER TABLE `post_pictures`
  ADD CONSTRAINT `post_pictures_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_sub_services`
--
ALTER TABLE `post_sub_services`
  ADD CONSTRAINT `post_sub_services_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
