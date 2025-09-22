-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 28, 2025 at 09:03 PM
-- Server version: 10.6.23-MariaDB-log
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `funcam5_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `name`, `price`, `description`, `created_at`, `updated_at`) VALUES
(1, 'نطاطية', 200.00, NULL, '2025-07-29 14:45:34', '2025-07-29 14:45:34'),
(2, 'كيك جاهز', 500.00, NULL, '2025-08-10 19:41:24', '2025-08-10 19:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `response` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `user_id`, `response`, `created_at`, `updated_at`) VALUES
(5, 6, 1, 1, '2025-08-16 17:32:14', '2025-08-16 17:32:14'),
(6, 7, 1, 1, '2025-08-16 17:32:14', '2025-08-16 17:32:14'),
(7, 8, 1, 1, '2025-08-16 17:32:14', '2025-08-16 17:32:14'),
(8, 9, 1, 1, '2025-08-16 17:32:14', '2025-08-16 17:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `account_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `name`, `balance`, `account_number`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'QNB', 5788.00, '123', NULL, '2025-07-18 08:10:28', '2025-08-28 20:51:35'),
(2, 'AUCH', 954.00, '1232', NULL, '2025-07-18 08:10:28', '2025-08-26 17:17:32'),
(3, 'الحساب البنكي', 12875.00, NULL, NULL, '2025-08-10 16:33:12', '2025-08-26 03:05:36'),
(4, 'الحساب النقدي', -250.00, NULL, NULL, '2025-08-10 16:33:24', '2025-08-26 01:34:17'),
(6, 'الحساب البنكي 24-8-2025', 3000.00, NULL, NULL, '2025-08-24 21:40:15', '2025-08-26 01:50:56'),
(7, 'تجربة 25-8-2025', 200.00, NULL, NULL, '2025-08-26 00:24:14', '2025-08-26 02:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `name`, `image`, `desc`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '{\"ar\":\"\\u0628\\u0627\\u0646\\u0631 \\u062a\\u062c\\u0631\\u064a\\u0628\\u064a\",\"en\":\"test banner\"}', 'dashboard/assets/media/avatars/300-1.jpg', '{\"ar\":\"\\u0628\\u0627\\u0646\\u0631 \\u062a\\u062c\\u0631\\u064a\\u0628\\u064a\",\"en\":\"test banner\"}', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `camp_reports`
--

CREATE TABLE `camp_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `camp_name` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `general_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `camp_reports`
--

INSERT INTO `camp_reports` (`id`, `report_date`, `service_id`, `camp_name`, `created_by`, `general_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2025-08-13', 1, 'xzcv', 1, 'zxcv', '2025-08-13 23:17:26', '2025-08-13 23:19:11', '2025-08-13 23:19:11'),
(2, '2025-08-13', 1, NULL, 1, NULL, '2025-08-14 01:08:11', '2025-08-14 01:08:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `camp_report_items`
--

CREATE TABLE `camp_report_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `camp_report_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `audio_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `camp_report_items`
--

INSERT INTO `camp_report_items` (`id`, `camp_report_id`, `item_name`, `notes`, `photo_path`, `audio_path`, `video_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'الوسادات', NULL, NULL, NULL, NULL, '2025-08-13 23:17:26', '2025-08-13 23:19:11'),
(2, 2, 'تجربة', NULL, 'camp-reports/2/photos/TUSB4XPJZHhrmfcdAYh527h7EXpjGnOwVBfkCo4i.jpg', 'camp-reports/2/audios/N3l5myN0kKBadnLcEvmMTHmyG0YkEjR6xnRby8q1.webm', NULL, '2025-08-14 23:04:50', '2025-08-28 17:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `desc`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '{\"ar\":\" \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0628\\u0627\\u0634\\u0631\",\"en\":\"Direct Wash\"}', 'dashboard/assets/media/avatars/300-1.jpg', '{\"ar\":\" \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0628\\u0627\\u0634\\u0631\",\"en\":\"Direct Wash\"}', 1, NULL, NULL),
(2, '{\"ar\":\" \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0639 \\u062a\\u0639\\u0642\\u064a\\u064a\\u0645\",\"en\":\"Washing with sterilization\"}', 'dashboard/assets/media/avatars/300-1.jpg', '{\"ar\":\"  \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0639 \\u062a\\u0639\\u0642\\u064a\\u064a\\u0645\",\"en\":\"Washing with sterilization\"}', 1, NULL, NULL),
(3, '{\"ar\":\" \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0639 \\u062a\\u0643\\u064a\\u064a\\u0633\",\"en\":\"Washing with bag\"}', 'dashboard/assets/media/avatars/300-1.jpg', '{\"ar\":\" \\u063a\\u0633\\u064a\\u0644 \\u0645\\u0639 \\u062a\\u0643\\u064a\\u064a\\u0633\",\"en\":\"Washing with bag\"}', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, '{\"ar\":\"\\u0645\\u0643\\u0629\",\"en\":\"Makkah\"}', NULL, NULL, NULL),
(2, '{\"ar\":\"\\u0645\\u0635\\u0631\",\"en\":\"Eqypt\"}', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Judge Schinner', 'uae.29@hotmail.com', '1-801-901-0468', 'Nihil exercitationem odit sed. Tenetur quisquam consequatur provident reiciendis laborum hic. Sint fugit sed ea voluptatem.', '2025-07-18 08:10:28', '2025-08-26 03:05:16'),
(2, 'Lori Schuppe', 'gicasa5682@colimarl.com', '1-458-359-4298', 'Rerum est numquam quia debitis laborum voluptatem. Alias velit nam adipisci. Dolorem libero sed et aut ipsam natus. Voluptas asperiores nam accusamus.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(3, 'Salvador Lockman MD', 'tyler27@example.org', '1-614-791-1612', 'Sit et qui dolores voluptatem. Maiores molestiae et quasi nesciunt autem. Nulla quaerat aut aperiam sint atque occaecati ut voluptas. Maiores molestias quia corporis pariatur.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(4, 'Prof. Magali Torp DVM', 'mitchell.denis@example.org', '+1.724.738.2131', 'Cum nihil et aliquam in vel totam ex. In voluptates explicabo aut cum quia vitae blanditiis. Voluptas ipsa et molestiae modi perspiciatis. Officia nobis provident nobis possimus et placeat.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(5, 'Austyn Morar', 'janelle.veum@example.net', '(972) 619-4004', 'Ut quis repellendus perferendis et. Omnis omnis quo voluptas nulla ut. Amet eum vel voluptas.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(6, 'Anderson Ruecker I', 'lewis48@example.net', '254.482.6734', 'Quisquam sapiente sed autem. Eaque molestias veniam magnam rerum natus rerum. Quidem minus assumenda explicabo qui delectus provident. A veritatis tenetur voluptatem sit.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(7, 'Miss Erika Halvorson MD', 'london15@example.net', '(820) 863-3215', 'Illum quis et blanditiis. Aut quibusdam fugiat laudantium numquam. Optio ipsum aut iste.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(8, 'Kaylah Ernser', 'bettie.heaney@example.com', '+13644021550', 'Numquam quod vitae cupiditate omnis esse. Est fugiat dicta ut rerum porro totam earum.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(9, 'Jan Krajcik', 'chanel24@example.org', '+17146552024', 'Ratione at deleniti non veniam vero ipsum libero. Qui voluptatem necessitatibus quam eos. Rem consequatur aut soluta eum magni ut. Iure laboriosam sunt voluptate ipsam illo.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(10, 'Foster Welch', 'ofelia40@example.com', '+1.865.852.4944', 'Nulla eaque tempora dignissimos dicta. Est rerum corrupti molestiae. Quasi et eum et. Perspiciatis consequatur voluptatum ipsam odio deleniti voluptatem ut.', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(11, 'Ahmed', 'ahmed@gmail.com', '1555114156', NULL, '2025-08-02 03:48:48', '2025-08-02 03:48:48'),
(12, 'Abdulla', 'dyfhf@hotmail.com', '46354', NULL, '2025-08-10 00:37:20', '2025-08-10 00:37:20'),
(13, 'عبدالله', 'mm@hotmail.com', '0000000', NULL, '2025-08-10 00:37:51', '2025-08-10 00:37:51'),
(14, 'Saad', 'as.1@outlook.com', '0502342342', NULL, '2025-08-10 00:49:38', '2025-08-10 00:49:38'),
(15, 'Mohammed Alkhasibi', '2uae.d29@hotmail.com', '2222222', NULL, '2025-08-10 01:41:58', '2025-08-20 14:02:04'),
(16, 'Mohammed Alkhasibi', 'uaer.29@hotmail.com', '22222', NULL, '2025-08-10 02:19:11', '2025-08-10 02:19:11'),
(17, 'عبدالله أحمد', 'uaee.29@hotmail.com', '0566674766', 'تجربة', '2025-08-10 16:22:34', '2025-08-20 14:02:48'),
(18, 'Mohammed saif', 'u11ae.29@hotmail.com', '05036111703032', NULL, '2025-08-20 14:03:17', '2025-08-26 03:05:03'),
(19, 'عبدالله علي', 'ii@hotmail.com', '0503670808', NULL, '2025-08-25 17:32:25', '2025-08-25 17:32:25'),
(20, 'ن غ', 'a@po.outlook.com', '0505656565', NULL, '2025-08-25 17:32:56', '2025-08-25 17:32:56'),
(21, 'mohammed saeed', 'uaeddd.29@hotmail.com', '05088117745654', NULL, '2025-08-26 02:10:08', '2025-08-26 02:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE `daily_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `notes` text DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `audio_attachment` varchar(255) DEFAULT NULL,
  `video_attachment` varchar(255) DEFAULT NULL,
  `photo_attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`id`, `title`, `details`, `notes`, `employee_id`, `audio_attachment`, `video_attachment`, `photo_attachment`, `created_at`, `updated_at`) VALUES
(1, 'تجربة', 'تجرلة', NULL, 1, NULL, NULL, NULL, '2025-08-14 23:02:03', '2025-08-16 21:48:47'),
(2, 'safasasdff', 'asdafasadsf', 'safdasdf', 1, 'daily-reports/audios/uI1yehMkjno8YZb9XTRkjPViegUwcfTnqHPXkIMq.webm', 'daily-reports/videos/MMdrH7uytXgW8LYU8839G40Jtuww5ijBpQoN8KeT.mp4', 'daily-reports/photos/5NMOZLXyXnvFKOjUn1Tv9z6t3FsTrjOJn0W3PQaE.jpg', '2025-08-17 01:47:50', '2025-08-18 01:53:25'),
(3, 'Test2', 'Test2', NULL, 1, 'daily-reports/audios/FxLmfx4RnuT7tMpQH2Pi1jAkVydZdS6ZdQbOEmft.mp4', NULL, NULL, '2025-08-18 01:46:17', '2025-08-18 01:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_type` enum('ios','android','web') DEFAULT NULL,
  `device_id` longtext NOT NULL,
  `morph_id` int(11) NOT NULL,
  `morph_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_directories`
--

CREATE TABLE `equipment_directories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_directories`
--

INSERT INTO `equipment_directories` (`id`, `name`, `is_active`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'كهربائيات', 1, 1, '2025-08-13 23:29:49', '2025-08-13 23:29:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_directory_items`
--

CREATE TABLE `equipment_directory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `directory_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_directory_items`
--

INSERT INTO `equipment_directory_items` (`id`, `directory_id`, `type`, `name`, `location`, `quantity`, `notes`, `is_active`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'تجربة', 'تجربة', 'الباب', 1, NULL, 1, 1, '2025-08-14 23:10:35', '2025-08-14 23:10:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_directory_media`
--

CREATE TABLE `equipment_directory_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_directory_media`
--

INSERT INTO `equipment_directory_media` (`id`, `item_id`, `file_path`, `file_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'equipment-directory/1/1/24E6gosvl2cDAIgitnbic77sGH4D0tMlF0hFq5xc.jpg', 'image', '2025-08-14 23:10:35', '2025-08-14 23:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` double(8,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_item_id`, `price`, `payment_method`, `date`, `notes`, `verified`, `image`, `account_id`, `order_id`, `created_at`, `updated_at`) VALUES
(6, 2, 500.00, NULL, '2025-08-11', NULL, NULL, NULL, 3, NULL, '2025-08-11 17:06:05', '2025-08-11 17:06:05'),
(7, 3, 500.00, NULL, '2025-08-11', NULL, NULL, NULL, 4, NULL, '2025-08-11 17:06:25', '2025-08-11 17:06:25'),
(8, 3, 500.00, NULL, '2025-08-11', NULL, NULL, NULL, 4, NULL, '2025-08-11 17:07:36', '2025-08-11 17:07:36'),
(9, 4, 200.00, NULL, '2025-08-11', NULL, NULL, NULL, 3, NULL, '2025-08-11 17:08:20', '2025-08-11 17:08:20'),
(10, NULL, 25.00, NULL, '2025-08-14', NULL, NULL, NULL, 3, 8, '2025-08-15 05:15:29', '2025-08-15 05:15:29'),
(13, NULL, 470.00, NULL, '2025-08-19', NULL, NULL, NULL, 3, 10, '2025-08-19 23:26:20', '2025-08-19 23:26:20'),
(15, NULL, 222.00, 'payment_link', '2025-08-20', 'ww', NULL, NULL, 1, 10, '2025-08-20 08:47:53', '2025-08-20 08:47:53'),
(20, NULL, 50.00, 'visa', '2025-08-25', NULL, NULL, NULL, 2, 14, '2025-08-26 01:34:12', '2025-08-26 01:34:12'),
(21, NULL, 22.00, 'cash', '2025-08-28', NULL, NULL, NULL, 1, 17, '2025-08-28 20:51:35', '2025-08-28 20:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_items`
--

INSERT INTO `expense_items` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'إيجارط', NULL, '2025-07-18 08:10:28', '2025-08-10 19:44:38'),
(2, 'رواتب', 'رواتب الموظفين الشهرية', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(3, 'كهرباء', 'فاتورة الكهرباء', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(4, 'ماء', 'فاتورة الماء', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(5, 'إنترنت', 'فاتورة الإنترنت', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(6, 'مستلزمات مكتبية', 'مصاريف المستلزمات المكتبية', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(7, 'صيانة', 'مصاريف الصيانة', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(8, 'نقل', 'مصاريف النقل والمواصلات', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(9, 'تجربة  10-8-2025', NULL, '2025-08-10 19:44:57', '2025-08-10 19:44:57'),
(10, 'تجربة', NULL, '2025-08-26 20:01:46', '2025-08-26 20:01:46');

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
-- Table structure for table `general_payments`
--

CREATE TABLE `general_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `statement` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `verified` enum('0','1') NOT NULL DEFAULT '0',
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `initial_pages`
--

CREATE TABLE `initial_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `order` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_links`
--

CREATE TABLE `invoice_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_links`
--

INSERT INTO `invoice_links` (`id`, `order_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, '5Mct2fCZVgkEP84Mudcoo8rj1uKKQMtG', '2025-07-21 01:50:34', '2025-07-21 01:50:34'),
(2, 2, 'c3y2xYVnaAH3bGYy8NNCzkCf62sI0pbm', '2025-07-30 00:37:41', '2025-07-30 00:37:41'),
(3, 4, '3mE8jLrNPf0nFBAxE1KH0K7Wl5gLtoY3', '2025-08-02 02:44:55', '2025-08-02 02:44:55'),
(4, 5, 'ld6wEfAI3YUWvYIVC1qbhYXMY2k2gsGE', '2025-08-07 23:25:27', '2025-08-07 23:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `meeting_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `location_id`, `meeting_number`, `date`, `start_time`, `end_time`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'MTG-00001', '2025-08-13', '23:18:00', '23:21:00', NULL, 1, '2025-08-14 02:18:47', '2025-08-14 02:18:47'),
(2, 1, 'MTG-00002', '2025-08-26', '16:00:00', '23:00:00', NULL, 1, '2025-08-26 23:50:05', '2025-08-26 23:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_attendees`
--

CREATE TABLE `meeting_attendees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meeting_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meeting_attendees`
--

INSERT INTO `meeting_attendees` (`id`, `meeting_id`, `user_id`, `job_title`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2025-08-14 02:18:47', '2025-08-14 02:18:47'),
(4, 2, 2, NULL, '2025-08-28 15:58:48', '2025-08-28 15:58:48');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_locations`
--

CREATE TABLE `meeting_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meeting_locations`
--

INSERT INTO `meeting_locations` (`id`, `name`, `description`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'موقع رقم 15855', 'سشيبممتسشيب', 'شمسيبنسميشبي', 1, '2025-08-13 22:15:39', '2025-08-16 23:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_topics`
--

CREATE TABLE `meeting_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meeting_id` bigint(20) UNSIGNED NOT NULL,
  `topic` varchar(255) NOT NULL,
  `discussion` text NOT NULL,
  `action_items` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meeting_topics`
--

INSERT INTO `meeting_topics` (`id`, `meeting_id`, `topic`, `discussion`, `action_items`, `assigned_to`, `due_date`, `task_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'النظافه', 'تكتب', NULL, 1, NULL, 1, '2025-08-14 02:18:49', '2025-08-14 02:18:49'),
(4, 2, 'JJJ', '<p>JJJ</p>', NULL, 1, NULL, 8, '2025-08-28 15:58:48', '2025-08-28 15:58:48');

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
(1, '2014_07_01_192107_create_user_types_table', 1),
(2, '2014_07_03_052019_create_cities_table', 1),
(3, '2014_10_12_000000_create_users_table', 1),
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2022_07_01_210154_create_permission_tables', 1),
(8, '2022_07_03_045313_create_s_m_s_table', 1),
(9, '2022_07_03_214809_create_devices_table', 1),
(10, '2022_07_03_215312_create_banners_table', 1),
(11, '2022_07_03_221131_create_categories_table', 1),
(12, '2022_08_13_214424_create_notifications_table', 1),
(13, '2022_08_13_215146_create_settings_table', 1),
(14, '2022_08_15_123748_create_initial_pages_table', 1),
(15, '2023_03_04_145641_create_bank_accounts_table', 1),
(16, '2024_01_27_054415_create_stocks_table', 1),
(17, '2024_01_27_054657_create_stock_quantities_table', 1),
(18, '2024_01_27_055852_create_customers_table', 1),
(19, '2024_01_27_084031_create_services_table', 1),
(20, '2024_01_27_084124_create_service_stock_table', 1),
(21, '2024_01_27_135710_create_orders_table', 1),
(22, '2024_01_27_143527_create_order_stock_table', 1),
(23, '2024_01_28_053829_create_stock_activities_table', 1),
(24, '2024_01_28_055200_create_payments_table', 1),
(25, '2024_01_28_055201_create_general_payments_table', 1),
(26, '2024_01_28_075401_create_order_service_table', 1),
(27, '2024_01_29_141732_create_service_reports_table', 1),
(28, '2024_01_30_060849_create_order_reports_table', 1),
(29, '2024_03_06_123138_create_transactions_table', 1),
(30, '2024_06_12_105709_create_expense_items_table', 1),
(31, '2024_06_12_105713_create_expenses_table', 1),
(32, '2024_07_21_085509_create_addons_table', 1),
(33, '2024_07_21_090731_create_order_addon_table', 1),
(34, '2024_07_21_114914_create_pre_login_images_table', 1),
(35, '2024_07_21_114926_create_pre_logout_images_table', 1),
(36, '2024_07_29_093947_create_order_rates_table', 1),
(37, '2024_09_25_071652_add_percentage_to_stocks_table', 1),
(38, '2024_09_28_075332_add_created_by_to_orders_table', 1),
(39, '2024_10_01_100218_create_terms_sittngs_table', 1),
(40, '2024_10_01_181858_add__agree_to_orders_table', 1),
(41, '2024_10_03_191120_create_invoice_links_table', 1),
(42, '2024_10_07_112507_add_report_orders_to_service_reports_table', 1),
(43, '2024_10_20_052038_add_ordered_count_to_service_reports_table', 1),
(44, '2024_10_20_060311_add_ordered_count_to_order_reports_table', 1),
(45, '2024_10_21_160717_create_questions_table', 1),
(46, '2024_10_21_160811_create_answers_table', 1),
(50, '2025_08_07_165500_add_expired_price_offer_to_orders_table', 2),
(51, '2025_08_08_140411_change_agree_to_signature_in_orders_table', 2),
(52, '2025_08_08_192926_create_order_items_table', 2),
(53, '2025_08_09_192133_create_tasks_table', 3),
(54, '2025_08_10_195234_add_attachments_to_tasks_table', 3),
(55, '2025_08_11_012240_create_notices_table', 3),
(56, '2025_08_11_185426_create_daily_reports_table', 3),
(57, '2025_08_11_220053_create_equipment_directories', 3),
(58, '2025_08_12_011723_create_camp_reports_tables', 3),
(59, '2025_08_12_230030_create_meetings_table', 3),
(60, '2025_08_13_012627_create_violation_types_table', 3),
(61, '2025_08_13_012716_create_violations_table', 3),
(62, '2025_08_13_173520_create_meeting_locations_table', 4),
(63, '2025_08_13_193313_replace_location_with_location_id_in_meetings_table', 4),
(64, '2025_08_14_214006_add_verified_to_expenses_table', 5),
(65, '2025_08_14_224833_add_verified_to_order_addon_table', 5),
(66, '2025_08_15_011843_add_account_id_to_order_addon_table', 5),
(67, '2025_08_15_182118_add_columns_to_order_items_table', 6),
(68, '2025_08_16_172227_create_notice_types_table', 7),
(69, '2025_08_16_181058_add_notice_type_id_to_notices_table', 7),
(70, '2025_08_17_060106_add_voice_note_logout_and_video_note_logout_to_orders_table', 8),
(71, '2025_08_17_223222_add_delayed_reson_to_orders_table', 9),
(72, '2025_08_18_012226_add_image_to_expense_items_table', 9),
(73, '2024_12_19_000000_create_payment_links_table', 10),
(79, '2025_08_19_225417_add_payment_method_to_expenses_table', 11),
(80, '2024_12_19_120000_add_last_status_check_to_payment_links_table', 12),
(81, '2025_08_17_100000_create_surveys_table', 13),
(82, '2025_08_17_100001_create_survey_questions_table', 13),
(83, '2025_08_17_100002_create_survey_responses_table', 13),
(84, '2025_08_17_100003_create_survey_answers_table', 13),
(85, '2025_08_19_140000_create_survey_email_logs_table', 13),
(86, '2025_08_21_041242_add_is_complete_and_not_complete_reson_to_service_reports_table', 14),
(87, '2025_08_21_082755_add_is_complete_and_not_complete_reson_to_service_stock_table', 14),
(88, '2025_08_22_045158_add_additional_notes_fields_to_orders_table', 15),
(89, '2025_08_22_134738_add_people_count_to_orders_table', 15),
(90, '2025_08_24_023213_add_additional_data_columns', 15),
(91, '2025_08_24_042958_add_order_number_column_to_orders_table', 15),
(92, '2025_08_24_093423_create_registrationforms_table', 16),
(93, '2025_08_24_181306_add_registeration-forms_to_services_table', 16),
(94, '2025_08_27_152204_add_set_qty_to_service_reports_table', 17),
(95, '2025_08_27_154342_add_latest_activity_to_service_stock_table', 17),
(96, '2025_08_28_075716_add_new_columns_to_violations_table', 18),
(97, '2025_08_28_084952_create_task_types_table', 18),
(98, '2025_08_28_085141_add_task_type_id_to_tasks_table', 18),
(99, '2025_08_28_095153_add_due_date_to_meeting_topics_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notice` text NOT NULL,
  `notice_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `customer_id`, `order_id`, `notice`, `notice_type_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'test test', 3, 1, '2025-08-14 01:52:24', '2025-08-17 01:21:51'),
(2, 17, 6, 'تجربة', 4, 1, '2025-08-14 02:23:23', '2025-08-17 01:21:39'),
(3, 17, 6, 'تجربة', 3, 1, '2025-08-14 23:40:02', '2025-08-17 01:21:09'),
(4, 3, NULL, 'asdfasdfd', 3, 1, '2025-08-17 01:22:00', '2025-08-17 01:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `notice_types`
--

CREATE TABLE `notice_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notice_types`
--

INSERT INTO `notice_types` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'dsgfsdsdfg', 0, '2025-08-17 01:20:34', '2025-08-17 01:20:40'),
(2, 'sdfgsdgsdggf', 0, '2025-08-17 01:20:45', '2025-08-17 01:20:45'),
(3, 'test', 1, '2025-08-17 01:20:53', '2025-08-17 01:20:53'),
(4, 'test 2', 1, '2025-08-17 01:21:01', '2025-08-17 01:21:01');

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
('02fa063d-d25d-4998-84ca-4f89aa866b9e', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 4, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-26 03:45:46', '2025-08-26 03:45:46'),
('1a7b59af-c925-4e76-8c2a-cdcb62353c94', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":3,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/3\"}', '2025-08-18 16:36:58', '2025-08-18 01:46:17', '2025-08-18 16:36:58'),
('25d21b4a-ab90-400c-8523-ab410de1d97f', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":2,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/2\"}', NULL, '2025-08-17 01:47:51', '2025-08-17 01:47:51'),
('2925c401-bb96-454c-b5b8-abbc3114d232', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 1, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', '2025-08-26 04:37:10', '2025-08-26 02:08:12', '2025-08-26 04:37:10'),
('2caa3cdc-fa66-464f-8b4e-4bed0e69b5d8', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 1, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', '2025-08-25 15:20:03', '2025-08-25 06:31:20', '2025-08-25 15:20:03'),
('2f5957c5-97d4-453f-9e36-b4643147411b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":1,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: \\u0627\\u0644\\u0646\\u0638\\u0627\\u0641\\u0647\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-14 02:52:38', '2025-08-14 02:18:49', '2025-08-14 02:52:38'),
('32f44432-13c8-461a-bfbc-773b1ab07c97', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 1, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', '2025-08-26 06:08:36', '2025-08-26 03:45:46', '2025-08-26 06:08:36'),
('4d591ac7-4aa8-43b6-9e96-24bc2d16f2e4', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 3, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-26 03:45:46', '2025-08-26 03:45:46'),
('587b5889-8ad8-4189-979a-9472eb10f4eb', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":2,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/2\"}', '2025-08-17 02:10:01', '2025-08-17 01:47:51', '2025-08-17 02:10:01'),
('5f5a5706-0519-4fd7-8dae-b210ac7056c8', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 4, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-25 06:31:20', '2025-08-25 06:31:20'),
('6ae72fcb-c431-45de-be18-ef3d62a7e4dc', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 4, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-25 03:29:16', '2025-08-25 03:29:16'),
('6e6c17f2-787c-4808-884c-c554901bf516', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":5,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: Test 2\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-18 01:38:03', '2025-08-18 01:37:59', '2025-08-18 01:38:03'),
('72df6b1f-593b-45d2-83b6-f32c52cb0630', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":3,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/3\"}', NULL, '2025-08-18 01:46:17', '2025-08-18 01:46:17'),
('78a899f5-5187-4111-8bf1-fc593e25be0d', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 3, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-25 03:29:16', '2025-08-25 03:29:16'),
('7db78067-4509-4d48-8255-6237991260ca', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":4,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: Test\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-18 01:06:27', '2025-08-18 01:06:22', '2025-08-18 01:06:27'),
('82980c8d-4c31-4015-b8bf-d6795cdc8dbe', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":8,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: JJJ\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-28 15:58:52', '2025-08-28 15:58:48', '2025-08-28 15:58:52'),
('94fa8cb9-3eae-429a-98d3-c9f5f2b91143', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 1, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', '2025-08-25 04:42:58', '2025-08-25 03:29:16', '2025-08-25 04:42:58'),
('9908290e-3b58-461e-ac56-743c6c40cc4c', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 3, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-26 02:08:12', '2025-08-26 02:08:12'),
('abdf7a88-56cd-4031-a3ef-ad471645776b', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 4, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":3,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/3\"}', NULL, '2025-08-18 01:46:17', '2025-08-18 01:46:17'),
('bac99fa9-d52b-4d6f-8498-a87ab3aea0f7', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 4, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-26 02:08:12', '2025-08-26 02:08:12'),
('bdef57ac-df26-4f8b-8310-b22ea60cd252', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":3,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: Uu\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-17 20:25:39', '2025-08-17 01:25:10', '2025-08-17 20:25:39'),
('c9dcf3a1-5624-46b0-b4a7-17535a9e834f', 'App\\Notifications\\NewRegistrationformsNotification', 'App\\Models\\User', 3, '{\"title\":\"New Registration Form Submitted\",\"message\":\"A new registration form has been submitted.\",\"url\":\"https:\\/\\/system.funcamp.ae\\/orders\\/registeration-forms\"}', NULL, '2025-08-25 06:31:20', '2025-08-25 06:31:20'),
('d934642f-26cf-4a40-8708-8f8005247e94', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin admin\",\"report_id\":1,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/1\"}', '2025-08-14 23:14:14', '2025-08-14 23:02:03', '2025-08-14 23:14:14'),
('e53f2016-2705-4935-8df0-684132483d89', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":2,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: \\u062a\\u062c\\u0631\\u0628\\u0629\",\"url\":\"http:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-15 18:49:33', '2025-08-15 18:49:26', '2025-08-15 18:49:33'),
('f2479666-7015-44ab-90c1-04587631b578', 'App\\Notifications\\NewDailyReportNotification', 'App\\Models\\User', 4, '{\"title\":\"\\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f\",\"message\":\"\\u062a\\u0645 \\u0625\\u0631\\u0633\\u0627\\u0644 \\u062a\\u0642\\u0631\\u064a\\u0631 \\u064a\\u0648\\u0645\\u064a \\u062c\\u062f\\u064a\\u062f \\u0628\\u0648\\u0627\\u0633\\u0637\\u0629 admin\",\"report_id\":2,\"employee_id\":1,\"url\":\"https:\\/\\/system.funcamp.ae\\/daily-reports\\/2\"}', NULL, '2025-08-17 01:47:51', '2025-08-17 01:47:51'),
('fd73b7b1-301a-4306-9a13-86aac7211fd0', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 1, '{\"task_id\":6,\"title\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0645\\u0647\\u0645\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629\",\"message\":\"\\u062a\\u0645 \\u062a\\u0639\\u064a\\u064a\\u0646\\u0643 \\u0644\\u0644\\u0645\\u0647\\u0645\\u0629: Teat\",\"url\":\"https:\\/\\/system.funcamp.ae\\/employee\\/tasks\"}', '2025-08-19 10:46:18', '2025-08-18 19:08:58', '2025-08-19 10:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `expired_price_offer` date DEFAULT NULL,
  `deposit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `insurance_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `time_of_receipt` time DEFAULT NULL,
  `time_of_receipt_notes` text DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `delivery_time_notes` text DEFAULT NULL,
  `voice_note` varchar(255) DEFAULT NULL,
  `video_note` varchar(255) DEFAULT NULL,
  `image_before_receiving` varchar(255) DEFAULT NULL,
  `image_after_delivery` varchar(255) DEFAULT NULL,
  `status` enum('pending_and_show_price','pending_and_Initial_reservation','approved','canceled','delayed','completed') NOT NULL DEFAULT 'pending_and_show_price',
  `delayed_reson` mediumtext DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `refunds` enum('0','1') NOT NULL DEFAULT '0',
  `refunds_notes` text DEFAULT NULL,
  `voice_note_logout` varchar(255) DEFAULT NULL,
  `video_note_logout` varchar(255) DEFAULT NULL,
  `terms_notes` text DEFAULT NULL,
  `delayed_time` time DEFAULT NULL,
  `inventory_withdrawal` enum('0','1') NOT NULL DEFAULT '0',
  `insurance_status` enum('returned','confiscated_full','confiscated_partial') DEFAULT NULL,
  `confiscation_description` text DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `show_price_notes` text DEFAULT NULL,
  `order_data_notes` text DEFAULT NULL,
  `invoice_notes` text DEFAULT NULL,
  `receipt_notes` text DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `people_count` int(11) NOT NULL DEFAULT 0,
  `client_notes` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `created_by`, `price`, `expired_price_offer`, `deposit`, `insurance_amount`, `notes`, `date`, `time_from`, `time_to`, `time_of_receipt`, `time_of_receipt_notes`, `delivery_time`, `delivery_time_notes`, `voice_note`, `video_note`, `image_before_receiving`, `image_after_delivery`, `status`, `delayed_reson`, `signature`, `refunds`, `refunds_notes`, `voice_note_logout`, `video_note_logout`, `terms_notes`, `delayed_time`, `inventory_withdrawal`, `insurance_status`, `confiscation_description`, `report_text`, `show_price_notes`, `order_data_notes`, `invoice_notes`, `receipt_notes`, `order_number`, `people_count`, `client_notes`, `created_at`, `updated_at`, `signature_path`) VALUES
(1, 2, 1, 1500.00, NULL, 500.00, 500.00, NULL, '2025-07-21', '16:30:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080001', 0, NULL, '2025-07-21 01:44:52', '2025-08-25 00:28:43', NULL),
(2, 2, 1, 1500.00, NULL, 500.00, 500.00, NULL, '2025-12-20', '10:51:00', '10:55:00', '16:07:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080002', 0, NULL, '2025-07-29 13:51:51', '2025-08-25 00:28:43', NULL),
(3, 2, 1, 2000.00, NULL, 500.00, 500.00, NULL, '2025-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080003', 0, NULL, '2025-07-29 15:32:35', '2025-08-25 00:28:43', NULL),
(4, 2, 1, 2000.00, NULL, 400.00, 30.00, NULL, '2025-07-30', '02:52:00', '14:52:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080004', 0, NULL, '2025-07-29 15:50:31', '2025-08-25 00:28:43', NULL),
(5, 1, 1, 2000.00, NULL, 500.00, 0.00, NULL, '2025-08-07', '16:00:00', NULL, '21:46:00', NULL, NULL, NULL, 'voice_notes/G44MKEnrVO5n2jTvzHkuMBX3pyKd3V4xUUejMUL6.mp4', 'video_notes/cb2zTs0KTNnp8Gp6MKisT2TdSOkzMAA23qf7if7A.webm', NULL, NULL, 'delayed', NULL, '2025-08-10 01:52:22', '0', NULL, NULL, NULL, NULL, NULL, '0', 'returned', NULL, NULL, NULL, NULL, NULL, NULL, '25080005', 0, NULL, '2025-08-06 01:45:36', '2025-08-25 00:28:43', 'signatures/5-Ke5tAtxk.png'),
(6, 17, 1, 2000.00, '2025-08-11', 500.00, 0.00, 'تجربة 10-8-2025', '2025-08-11', '16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', 'returned', NULL, NULL, NULL, NULL, NULL, NULL, '25080006', 0, NULL, '2025-08-10 16:25:40', '2025-08-25 00:28:43', NULL),
(7, 17, 1, 2000.00, NULL, 500.00, 0.00, NULL, '2025-12-08', '16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', 'confiscated_full', NULL, NULL, NULL, NULL, NULL, NULL, '25080007', 0, NULL, '2025-08-11 20:58:12', '2025-08-25 00:28:43', NULL),
(8, 17, 1, 2000.00, NULL, 500.00, 500.00, NULL, '2025-12-08', '16:00:00', '24:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080008', 0, NULL, '2025-08-11 21:17:57', '2025-08-25 00:28:43', NULL),
(9, 17, 1, 2000.00, '2025-08-15', 500.00, 500.00, NULL, '2025-08-15', '16:00:00', '24:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending_and_Initial_reservation', NULL, '2025-08-24 15:56:49', '0', NULL, NULL, NULL, 'تجربة', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080009', 0, NULL, '2025-08-15 17:29:39', '2025-08-25 00:28:43', 'signatures/9-zGptHePn.png'),
(10, 17, 1, 2000.00, '2025-08-19', 500.00, 500.00, NULL, '2025-08-19', '16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2025-08-22 00:33:33', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080010', 1, NULL, '2025-08-18 19:56:30', '2025-08-25 15:24:08', 'signatures/10-JaEJwlmi.png'),
(11, 18, 1, 1700.00, '2025-08-20', 500.00, 0.00, NULL, '2025-08-20', '04:00:00', NULL, NULL, NULL, NULL, NULL, 'voice_notes/leOqSA0ciB6Itc3V5QzFJujKsgHpRkQlsogN9Xnn.mp4', 'video_notes/KS1xTU4Mce6fs9GxLsQtBxMl1q8Sht5QWG4FNC9Z.webm', NULL, NULL, 'delayed', 'تجربة', '2025-08-21 16:43:18', '0', NULL, NULL, NULL, NULL, NULL, '0', 'confiscated_full', NULL, NULL, NULL, NULL, NULL, NULL, '25080011', 0, NULL, '2025-08-20 14:05:00', '2025-08-25 00:28:43', 'signatures/11-QDX45MAE.png'),
(12, 1, 1, 2000.00, '2025-08-24', 200.00, 200.00, NULL, '2025-08-24', '16:09:00', '17:09:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080012', 3, NULL, '2025-08-25 00:25:23', '2025-08-27 05:32:22', NULL),
(13, 4, 1, 2000.00, '2025-08-24', 222.00, 222.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending_and_show_price', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080013', 12, NULL, '2025-08-25 00:30:15', '2025-08-26 02:52:52', NULL),
(14, 20, 1, 1500.00, '2025-08-25', 500.00, 0.00, 'dddd', '2025-08-29', '17:00:00', '01:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2025-08-25 10:34:34', '0', NULL, NULL, NULL, NULL, NULL, '0', 'returned', NULL, 'يي', NULL, NULL, NULL, NULL, '25080014', 30, NULL, '2025-08-25 03:49:19', '2025-08-26 01:03:23', 'signatures/14-LISMfq7K.png'),
(15, 1, 1, 4000.00, '2025-08-25', 500.00, 0.00, NULL, '2025-02-08', '16:00:00', '00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2025-08-25 21:30:46', '0', NULL, NULL, NULL, NULL, NULL, '0', 'returned', NULL, NULL, '<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>', '<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>', '<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>', '<p style=\"direction: rtl;\">تجربة 26-8-2025&nbsp;</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>\r\n<p style=\"direction: rtl;\">تجربة 26-8-2025</p>', '25080015', 20, NULL, '2025-08-26 02:53:44', '2025-08-28 04:25:01', 'signatures/15-DCkhqHc2.png'),
(16, 21, 1, 2000.00, '2025-08-25', 500.00, 500.00, NULL, '2025-08-24', '16:00:00', '24:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending_and_show_price', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080016', 15, NULL, '2025-08-28 04:26:25', '2025-08-28 04:26:25', NULL),
(17, 1, 1, 2000.00, '2025-08-28', 500.00, 500.00, NULL, '2025-08-28', '16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending_and_show_price', NULL, '2025-08-28 19:44:47', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '25080017', 14, NULL, '2025-08-28 14:19:57', '2025-08-29 02:44:47', 'signatures/17-kJo6iIWw.png');

-- --------------------------------------------------------

--
-- Table structure for table `order_addon`
--

CREATE TABLE `order_addon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `count` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_addon`
--

INSERT INTO `order_addon` (`id`, `order_id`, `addon_id`, `description`, `count`, `price`, `verified`, `created_at`, `updated_at`, `account_id`, `payment_method`) VALUES
(2, 6, 1, '', 1, 500.00, 1, '2025-08-10 17:33:58', '2025-08-19 10:33:15', NULL, NULL),
(3, 10, 1, '', 3, 600.00, 1, '2025-08-19 10:23:17', '2025-08-19 10:34:37', 1, 'visa'),
(4, 9, 1, '', 1, 200.00, 1, '2025-08-19 11:08:47', '2025-08-26 00:29:07', 1, 'cash'),
(9, 13, 1, '', 2, 400.00, 0, '2025-08-25 03:25:11', '2025-08-25 03:25:11', 1, 'cash'),
(10, 14, 1, '', 1, 200.00, 1, '2025-08-26 01:03:04', '2025-08-26 01:03:32', 4, 'cash'),
(12, 15, 1, '', 1, 200.00, 1, '2025-08-26 03:10:06', '2025-08-26 03:10:21', 1, 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,3) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `stock_id`, `quantity`, `total_price`, `notes`, `created_at`, `updated_at`, `account_id`, `payment_method`, `verified`) VALUES
(1, 6, 4, 1.000, 100.00, NULL, '2025-08-10 18:04:01', '2025-08-10 18:04:01', NULL, NULL, 0),
(2, 6, 6, 1.000, 100.00, NULL, '2025-08-10 18:04:21', '2025-08-10 18:04:21', NULL, NULL, 0),
(4, 6, 16, 1.000, 600.00, NULL, '2025-08-10 18:11:53', '2025-08-10 18:11:53', NULL, NULL, 0),
(6, 10, 3, 1.000, 10.00, NULL, '2025-08-18 21:25:59', '2025-08-18 21:25:59', 1, 'cash', 0),
(7, 10, 3, 1.000, 10.00, NULL, '2025-08-18 21:26:00', '2025-08-19 10:17:02', 1, 'cash', 1),
(8, 10, 15, 7.000, 7.00, NULL, '2025-08-19 10:15:47', '2025-08-19 10:15:55', 1, 'payment_link', 1),
(9, 10, 13, 1.000, 0.00, NULL, '2025-08-19 10:19:23', '2025-08-19 10:19:32', 1, 'cash', 1),
(12, 11, 1, 1.000, 10.00, NULL, '2025-08-24 23:26:59', '2025-08-24 23:26:59', 1, 'cash', 0),
(13, 9, 1, 1.000, 20.00, NULL, '2025-08-26 00:31:55', '2025-08-26 00:32:08', 1, 'cash', 1),
(14, 14, 3, 1.000, 500.00, NULL, '2025-08-26 01:02:25', '2025-08-26 01:02:25', 1, 'cash', 0),
(16, 15, 1, 1.000, 20.00, NULL, '2025-08-26 03:08:12', '2025-08-26 03:08:16', 1, 'cash', 1),
(17, 8, 1, 1.000, 20.00, NULL, '2025-08-26 12:44:20', '2025-08-26 12:44:28', 1, 'cash', 1),
(18, 15, 1, 25.000, 600.00, NULL, '2025-08-27 06:11:10', '2025-08-27 06:11:10', 1, 'cash', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_rates`
--

CREATE TABLE `order_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 1,
  `review` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_rates`
--

INSERT INTO `order_rates` (`id`, `order_id`, `rating`, `review`, `created_at`, `updated_at`) VALUES
(1, 5, 2, '/', '2025-08-09 18:59:13', '2025-08-09 18:59:13'),
(2, 4, 3, 'تجربة تجربة', '2025-08-09 18:59:44', '2025-08-09 18:59:44'),
(3, 9, 5, 'جميل', '2025-08-16 17:32:14', '2025-08-16 17:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_reports`
--

CREATE TABLE `order_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `service_report_id` bigint(20) UNSIGNED NOT NULL,
  `ordered_count` int(11) DEFAULT NULL,
  `is_completed` enum('no_action','completed','not_completed') NOT NULL DEFAULT 'no_action',
  `not_completed_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ordered_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_reports`
--

INSERT INTO `order_reports` (`id`, `order_id`, `service_report_id`, `ordered_count`, `is_completed`, `not_completed_reason`, `notes`, `created_at`, `updated_at`, `ordered_price`) VALUES
(1, 4, 151, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(2, 4, 152, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(3, 4, 153, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(4, 4, 154, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(5, 4, 155, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(6, 4, 156, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(7, 4, 157, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(8, 4, 158, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(9, 4, 159, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(10, 4, 160, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(11, 4, 161, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(12, 4, 162, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(13, 4, 163, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(14, 4, 164, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(15, 4, 165, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(16, 4, 166, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(17, 4, 167, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(18, 4, 168, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(19, 4, 169, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(20, 4, 170, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(21, 4, 171, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(22, 4, 172, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(23, 4, 173, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(24, 4, 174, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(25, 4, 175, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(26, 4, 176, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(27, 4, 177, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(28, 4, 178, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(29, 4, 179, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(30, 4, 180, NULL, 'completed', NULL, NULL, '2025-08-06 01:48:32', '2025-08-06 01:48:32', NULL),
(35, 8, 258, NULL, 'not_completed', NULL, NULL, '2025-08-12 01:19:13', '2025-08-12 01:19:26', NULL),
(36, 8, 259, NULL, 'not_completed', NULL, NULL, '2025-08-12 01:19:13', '2025-08-12 01:19:26', NULL),
(37, 8, 260, NULL, 'not_completed', NULL, NULL, '2025-08-12 01:19:13', '2025-08-12 01:19:26', NULL),
(38, 11, 2, NULL, 'completed', NULL, NULL, '2025-08-21 15:41:51', '2025-08-21 23:39:41', NULL),
(39, 11, 29, NULL, 'completed', NULL, NULL, '2025-08-21 15:41:51', '2025-08-21 23:39:41', NULL),
(40, 11, 1, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(41, 11, 3, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(42, 11, 4, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(43, 11, 5, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(44, 11, 6, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(45, 11, 7, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(46, 11, 8, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(47, 11, 9, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(48, 11, 10, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(49, 11, 11, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(50, 11, 12, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(51, 11, 13, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(52, 11, 14, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(53, 11, 15, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(54, 11, 16, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(55, 11, 17, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(56, 11, 18, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(57, 11, 19, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(58, 11, 20, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(59, 11, 21, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(60, 11, 22, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(61, 11, 23, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(62, 11, 24, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(63, 11, 25, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(64, 11, 26, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(65, 11, 27, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(66, 11, 28, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(67, 11, 30, NULL, 'completed', NULL, NULL, '2025-08-21 23:39:41', '2025-08-21 23:39:41', NULL),
(69, 14, 258, NULL, 'completed', NULL, NULL, '2025-08-25 06:02:20', '2025-08-25 06:02:20', NULL),
(70, 14, 259, NULL, 'completed', NULL, NULL, '2025-08-25 06:02:20', '2025-08-25 06:02:20', NULL),
(71, 14, 260, NULL, 'completed', NULL, NULL, '2025-08-25 06:02:20', '2025-08-25 06:02:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_service`
--

CREATE TABLE `order_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_service`
--

INSERT INTO `order_service` (`id`, `order_id`, `service_id`, `price`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 1500.00, NULL, NULL),
(11, 4, 6, 2000.00, NULL, NULL),
(13, 3, 1, 2000.00, NULL, NULL),
(18, 5, 1, 2000.00, NULL, NULL),
(19, 2, 4, 1500.00, NULL, NULL),
(20, 6, 1, 2000.00, NULL, NULL),
(22, 8, 15, 2000.00, NULL, NULL),
(23, 9, 1, 2000.00, NULL, NULL),
(24, 7, 1, 2000.00, NULL, NULL),
(27, 11, 1, 2000.00, NULL, NULL),
(38, 10, 1, 2000.00, NULL, NULL),
(40, 14, 15, 2000.00, NULL, NULL),
(41, 13, 1, 2000.00, NULL, NULL),
(48, 12, 1, 2000.00, NULL, NULL),
(49, 15, 1, 2000.00, NULL, NULL),
(50, 15, 15, 2000.00, NULL, NULL),
(51, 16, 15, 2000.00, NULL, NULL),
(54, 17, 1, 2000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_stock`
--

CREATE TABLE `order_stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `statement` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `verified` enum('0','1') NOT NULL DEFAULT '0',
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `price`, `payment_method`, `statement`, `notes`, `verified`, `account_id`, `created_at`, `updated_at`) VALUES
(2, 3, 500.00, 'visa', 'deposit', NULL, '0', 2, '2025-07-29 15:33:17', '2025-07-29 15:33:17'),
(7, 5, 1500.00, 'cash', 'complete the amount', NULL, '0', 1, '2025-08-10 00:52:15', '2025-08-16 04:12:28'),
(9, 6, 600.00, 'cash', 'deposit', NULL, '0', 1, '2025-08-10 16:42:07', '2025-08-10 17:07:03'),
(10, 6, 1500.00, 'visa', 'complete the amount', NULL, '0', 3, '2025-08-10 16:43:06', '2025-08-10 16:43:06'),
(12, 6, 400.00, 'cash', 'deposit', NULL, '0', 1, '2025-08-10 17:10:13', '2025-08-10 17:11:04'),
(13, 6, 600.00, 'cash', 'deposit', NULL, '0', 1, '2025-08-10 17:13:22', '2025-08-10 17:13:36'),
(14, 6, 500.00, 'visa', 'the_insurance', NULL, '1', 3, '2025-08-10 17:15:29', '2025-08-10 17:17:47'),
(16, 8, 1500.00, 'cash', 'complete the amount', NULL, '1', 1, '2025-08-15 04:29:04', '2025-08-26 12:40:59'),
(19, 10, 200.00, 'cash', 'deposit', NULL, '0', 1, '2025-08-18 21:28:03', '2025-08-19 11:02:48'),
(22, 10, 22.00, 'payment_link', 'the_insurance', NULL, '1', 1, '2025-08-19 10:25:05', '2025-08-19 11:01:42'),
(27, 11, 500.00, 'visa', 'deposit', NULL, '1', 6, '2025-08-24 21:45:48', '2025-08-24 21:54:59'),
(28, 11, 500.00, 'visa', 'deposit', NULL, '0', 6, '2025-08-24 21:55:12', '2025-08-24 21:55:12'),
(29, 11, 500.00, 'visa', 'the_insurance', NULL, '1', 6, '2025-08-24 21:56:15', '2025-08-24 21:58:27'),
(30, 9, 200.00, 'cash', 'deposit', NULL, '1', 1, '2025-08-26 00:29:39', '2025-08-26 00:29:45'),
(31, 14, 500.00, 'cash', 'deposit', 'تست', '1', 6, '2025-08-26 00:59:46', '2025-08-26 01:50:56'),
(34, 14, 500.00, 'cash', 'deposit', '25-8', '0', 1, '2025-08-26 01:50:39', '2025-08-26 01:50:39'),
(35, 15, 500.00, 'visa', 'deposit', NULL, '1', 3, '2025-08-26 03:05:36', '2025-08-26 03:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `payment_links`
--

CREATE TABLE `payment_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `checkout_id` varchar(255) NOT NULL,
  `checkout_key` varchar(255) DEFAULT NULL,
  `payment_url` text NOT NULL,
  `status` enum('pending','paid','cancelled','expired') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `request_id` varchar(255) DEFAULT NULL,
  `order_id_paymennt` varchar(255) DEFAULT NULL,
  `last_status_check` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_links`
--

INSERT INTO `payment_links` (`id`, `order_id`, `customer_id`, `amount`, `description`, `checkout_id`, `checkout_key`, `payment_url`, `status`, `paid_at`, `expires_at`, `request_id`, `order_id_paymennt`, `last_status_check`, `created_at`, `updated_at`) VALUES
(1, 9, 17, 200.00, NULL, '1840783376708493089', 'afba5b9b84cc5fe88a03293fd7676978286f8e5383d02bb8', 'https://pay.paymennt.com/checkout/afba5b9b84cc5fe88a03293fd7676978286f8e5383d02bb8', 'pending', NULL, NULL, 'PAY-68a2ec73a7ae5', '9', '2025-08-29 15:00:06', '2025-08-18 16:03:48', '2025-08-29 15:00:06'),
(2, 9, 17, 112.00, NULL, '1840786499669626564', '71df16fa8207250b53498d1f77b72feeb424291c1d447688', 'https://pay.paymennt.com/checkout/71df16fa8207250b53498d1f77b72feeb424291c1d447688', 'pending', NULL, NULL, 'PAY-68a2f815f2eef', '9', '2025-08-29 15:00:07', '2025-08-18 16:53:27', '2025-08-29 15:00:07'),
(3, 9, 17, 10.00, NULL, '1840791770966205235', 'e2ddc9679782439793a555bd044e8fb05417975b0eac353e', 'https://pay.paymennt.com/checkout/e2ddc9679782439793a555bd044e8fb05417975b0eac353e', 'pending', NULL, NULL, 'PAY-68a30bb90fd2d', '9', '2025-08-29 15:00:15', '2025-08-18 18:17:14', '2025-08-29 15:00:15'),
(4, 10, 17, 5.00, NULL, '1840798123292956820', '5a886a4f81b4b1bdad97fb347205d43543f22303d08fcad1', 'https://pay.paymennt.com/checkout/5a886a4f81b4b1bdad97fb347205d43543f22303d08fcad1', 'pending', NULL, NULL, 'PAY-68a32362ef85f', '10', '2025-08-29 15:00:18', '2025-08-18 19:58:12', '2025-08-29 15:00:18'),
(5, 10, 17, 100.00, NULL, '1840805152248282829', 'a6da5883a2c66f9abe4bc0d2769ca866473a4ec590387655', 'https://pay.paymennt.com/checkout/a6da5883a2c66f9abe4bc0d2769ca866473a4ec590387655', 'pending', NULL, NULL, 'PAY-68a33d9292b46', '10', '2025-08-29 15:00:23', '2025-08-18 21:49:55', '2025-08-29 15:00:23'),
(6, 10, 17, 22.00, NULL, '1840819523052656270', '637c1d033ac4570e308eb1d2c3da80885b4907a7922d44e9', 'https://pay.paymennt.com/checkout/637c1d033ac4570e308eb1d2c3da80885b4907a7922d44e9', 'pending', NULL, NULL, 'PAY-68a3731b9d221', '10', '2025-08-29 15:01:07', '2025-08-19 01:38:20', '2025-08-29 15:01:07'),
(7, 10, 17, 22.00, NULL, '1840819796956436769', '6d6cbccda32754720e1a750b1313e1395acd2a05869aad46', 'https://pay.paymennt.com/checkout/6d6cbccda32754720e1a750b1313e1395acd2a05869aad46', 'pending', NULL, NULL, 'PAY-68a37420d82a2', '10', '2025-08-29 15:00:22', '2025-08-19 01:42:41', '2025-08-29 15:00:22'),
(8, 10, 17, 22.00, NULL, '1840821598726679593', '3e57c69f740f50ba938595ea4b6f61926b0496ee32c479d8', 'https://pay.paymennt.com/checkout/3e57c69f740f50ba938595ea4b6f61926b0496ee32c479d8', 'pending', NULL, NULL, 'PAY-68a37ad72b712', '10', '2025-08-29 15:00:25', '2025-08-19 02:11:20', '2025-08-29 15:00:25'),
(9, 10, 17, 22.00, NULL, '1840821639547588906', '7bbdd66d3d04c6638560fd7bf38b2f21da059db723614bf7', 'https://pay.paymennt.com/checkout/7bbdd66d3d04c6638560fd7bf38b2f21da059db723614bf7', 'pending', NULL, NULL, 'PAY-68a37afde43af', '10', '2025-08-29 15:02:05', '2025-08-19 02:11:59', '2025-08-29 15:02:05'),
(10, 10, 17, 22.00, NULL, '1840821760050097651', '50ed8c326f364c35c10cf9b994b81aca304ae27030523d7f', 'https://pay.paymennt.com/checkout/50ed8c326f364c35c10cf9b994b81aca304ae27030523d7f', 'pending', NULL, NULL, 'PAY-68a37b71088e8', '10', '2025-08-29 15:02:06', '2025-08-19 02:13:53', '2025-08-29 15:02:06'),
(11, 10, 17, 1000.00, NULL, '1840821783394545269', '7a69ff76312f379d02d22dea896faf07b54c0f09c336b260', 'https://pay.paymennt.com/checkout/7a69ff76312f379d02d22dea896faf07b54c0f09c336b260', 'pending', NULL, NULL, 'PAY-68a37b87348aa', '10', '2025-08-29 15:01:05', '2025-08-19 02:14:16', '2025-08-29 15:01:05'),
(12, 10, 17, 10.00, NULL, '1840822419802429469', '5b2e57326e03fc7e1897b7fa67f075a834a6c898a7c4682f', 'https://pay.paymennt.com/checkout/5b2e57326e03fc7e1897b7fa67f075a834a6c898a7c4682f', 'pending', NULL, NULL, 'PAY-68a37de625821', '10', '2025-08-29 15:02:10', '2025-08-19 02:24:23', '2025-08-29 15:02:10'),
(13, 10, 17, 500.00, NULL, '1840822587778013737', '1fdb5d73acf81f8dceb14618150542bc65868e331bdb8435', 'https://pay.paymennt.com/checkout/1fdb5d73acf81f8dceb14618150542bc65868e331bdb8435', 'pending', NULL, NULL, 'PAY-68a37e864084f', '10', '2025-08-29 15:02:15', '2025-08-19 02:27:03', '2025-08-29 15:02:15'),
(14, 10, 17, 5.00, NULL, '1840822649674602669', 'e0841cb9e6dc71deee13639e3a677b27fca9c89f8614ef3a', 'https://pay.paymennt.com/checkout/e0841cb9e6dc71deee13639e3a677b27fca9c89f8614ef3a', 'pending', NULL, NULL, 'PAY-68a37ec148402', '10', '2025-08-29 15:02:16', '2025-08-19 02:28:02', '2025-08-29 15:02:16'),
(15, 10, 17, 5.00, NULL, '1840824139630805779', '35792ca1b84dce4ddfdba6e8158605307ea01a14343d1928', 'https://pay.paymennt.com/checkout/35792ca1b84dce4ddfdba6e8158605307ea01a14343d1928', 'pending', NULL, NULL, 'PAY-68a3844e5b81a', '10', '2025-08-29 15:02:17', '2025-08-19 02:51:43', '2025-08-29 15:02:17'),
(16, 10, 17, 5.00, NULL, '1840824183068960852', '219b71613ed27fa7562fb14ab30a01b1f239840729a29218', 'https://pay.paymennt.com/checkout/219b71613ed27fa7562fb14ab30a01b1f239840729a29218', 'pending', NULL, NULL, 'PAY-68a38477b9301', '10', '2025-08-29 15:03:05', '2025-08-19 02:52:24', '2025-08-29 15:03:05'),
(17, 10, 17, 5.00, NULL, '1840845491701103213', 'aecf0b741d39107fe03390e95676e8199a1dbaa2ea1372f8', 'https://pay.paymennt.com/checkout/aecf0b741d39107fe03390e95676e8199a1dbaa2ea1372f8', 'pending', NULL, NULL, 'PAY-68a3d3d948dfc', '10', '2025-08-29 15:03:06', '2025-08-19 08:31:06', '2025-08-29 15:03:06'),
(18, 10, 17, 10.00, NULL, '1840849737306733731', '43d1a19be2f10e72c427d49a09605f540f794d1940108f1c', 'https://pay.paymennt.com/checkout/43d1a19be2f10e72c427d49a09605f540f794d1940108f1c', 'paid', '2025-08-19 09:39:09', NULL, 'PAY-68a3e3aa2b296', '10', '2025-08-20 19:39:46', '2025-08-19 09:38:35', '2025-08-20 19:39:46'),
(19, 10, 17, 10.00, NULL, '1840849962741137084', 'aca1c4de6f1813da6179df72fd9c86dc7ad0f16413395efa', 'https://pay.paymennt.com/checkout/aca1c4de6f1813da6179df72fd9c86dc7ad0f16413395efa', 'paid', '2025-08-19 09:42:43', NULL, 'PAY-68a3e48130252', '10', '2025-08-20 19:43:17', '2025-08-19 09:42:10', '2025-08-20 19:43:17'),
(20, 10, 17, 4.00, NULL, '1840850211547250966', '3249f670fdb20eef6a484be51977c030a9b6ad1c2370f14d', 'https://pay.paymennt.com/checkout/3249f670fdb20eef6a484be51977c030a9b6ad1c2370f14d', 'pending', NULL, NULL, 'PAY-68a3e56e77e83', '10', '2025-08-29 15:02:12', '2025-08-19 09:46:07', '2025-08-29 15:02:12'),
(21, 10, 17, 10.00, NULL, '1840853800621539400', '4cf162064152b83f74b05d574ab52da51e88f919c3ad1d3e', 'https://pay.paymennt.com/checkout/4cf162064152b83f74b05d574ab52da51e88f919c3ad1d3e', 'pending', NULL, NULL, 'PAY-68a3f2cd392ba', '10', '2025-08-29 15:00:12', '2025-08-19 10:43:10', '2025-08-29 15:00:12'),
(22, 10, 17, 10.00, NULL, '1840854205876241416', '9adf6fa5c8de93cdebf63a761031215f7ed0091ced9a9c9c', 'https://pay.paymennt.com/checkout/9adf6fa5c8de93cdebf63a761031215f7ed0091ced9a9c9c', 'pending', NULL, NULL, 'PAY-68a3f44fc6972', '10', '2025-08-29 15:00:11', '2025-08-19 10:49:36', '2025-08-29 15:00:11'),
(23, 10, 17, 5.00, NULL, '1840854276815594527', '432ed5ae2f2e86862f73fdffccc3795b9e00a033ab790ea2', 'https://pay.paymennt.com/checkout/432ed5ae2f2e86862f73fdffccc3795b9e00a033ab790ea2', 'pending', NULL, NULL, 'PAY-68a3f4936a3b4', '10', '2025-08-29 15:00:20', '2025-08-19 10:50:44', '2025-08-29 15:00:20'),
(24, 10, 17, 222.00, NULL, '1840912066992152671', 'f1ba6d3d05c8c7615e147fd71153644661e94c1b9a538bdd', 'https://pay.paymennt.com/checkout/f1ba6d3d05c8c7615e147fd71153644661e94c1b9a538bdd', 'pending', NULL, NULL, 'PAY-68a4cbdc668c8', '10', '2025-08-29 15:02:08', '2025-08-20 06:09:17', '2025-08-29 15:02:08'),
(25, 10, 17, 5.00, NULL, '1840912642314348041', 'ce53540b674d02ee9bc73e01bcd582f0eb786209f8325faa', 'https://pay.paymennt.com/checkout/ce53540b674d02ee9bc73e01bcd582f0eb786209f8325faa', 'paid', '2025-08-20 02:19:01', NULL, 'PAY-68a4ce011d800', '10', '2025-08-21 00:19:53', '2025-08-20 06:18:25', '2025-08-21 00:19:53'),
(26, 10, 17, 100.00, NULL, '1840922138613939150', 'c518a7e3618e64ac4a8e67905e917c50f6ed4947930948ba', 'https://pay.paymennt.com/checkout/c518a7e3618e64ac4a8e67905e917c50f6ed4947930948ba', 'pending', NULL, NULL, 'PAY-68a4f1617df4b', '10', '2025-08-29 15:00:29', '2025-08-20 08:49:22', '2025-08-29 15:00:29'),
(27, 10, 17, 100.00, NULL, '1840922271569220999', '1016eaf5b621040a03f0f2c5a3029d66c4027e09714b6ee2', 'https://pay.paymennt.com/checkout/1016eaf5b621040a03f0f2c5a3029d66c4027e09714b6ee2', 'pending', NULL, NULL, 'PAY-68a4f1e04867a', '10', '2025-08-29 14:59:04', '2025-08-20 08:51:29', '2025-08-29 14:59:04'),
(28, 10, 17, 10.00, NULL, '1840940658185811921', '0b9ea3d692ef3774f853fd52a8f127fa5a37cd41fafed1c9', 'https://pay.paymennt.com/checkout/0b9ea3d692ef3774f853fd52a8f127fa5a37cd41fafed1c9', 'paid', '2025-08-20 13:50:04', NULL, 'PAY-68a5365f1ba94', '10', '2025-08-20 13:50:04', '2025-08-20 13:43:44', '2025-08-20 13:50:04'),
(29, 5, 1, 12.00, NULL, '1840941446400238007', '69ff40e7a9604d0d5a15c35ca835ea80d50ebf474cea6530', 'https://pay.paymennt.com/checkout/69ff40e7a9604d0d5a15c35ca835ea80d50ebf474cea6530', 'pending', NULL, NULL, 'PAY-68a5394ed8d82', '5', '2025-08-29 15:00:09', '2025-08-20 13:56:15', '2025-08-29 15:00:09'),
(30, 8, 17, 12.00, NULL, '1840941570056709110', 'f117d90cd697722ae6064059bdec91809e4a5ce50a931919', 'https://pay.paymennt.com/checkout/f117d90cd697722ae6064059bdec91809e4a5ce50a931919', 'pending', NULL, NULL, 'PAY-68a539c4b9087', '8', '2025-08-29 15:00:17', '2025-08-20 13:58:13', '2025-08-29 15:00:17'),
(31, 11, 18, 10.00, NULL, '1840942524967805213', 'e88163c01c4108ca3fc890282a0aec8ba8ae22bb14d11d0a', 'https://pay.paymennt.com/checkout/e88163c01c4108ca3fc890282a0aec8ba8ae22bb14d11d0a', 'pending', NULL, NULL, 'PAY-68a53d533c1aa', '11', '2025-08-29 15:01:06', '2025-08-20 14:13:24', '2025-08-29 15:01:06'),
(32, 11, 18, 10.00, NULL, '1840942620198235949', 'ce18a1204ddbe1a7c0d9df57b91b73ba9fa1d54143467217', 'https://pay.paymennt.com/checkout/ce18a1204ddbe1a7c0d9df57b91b73ba9fa1d54143467217', 'pending', NULL, NULL, 'PAY-68a53dae35f4f', '11', '2025-08-29 15:00:14', '2025-08-20 14:14:55', '2025-08-29 15:00:14'),
(33, 11, 18, 10.00, NULL, '1840943372615674360', 'd8c3bd7789f932f12fa339c136481ee9be6a071f9ec68f04', 'https://pay.paymennt.com/checkout/d8c3bd7789f932f12fa339c136481ee9be6a071f9ec68f04', 'pending', NULL, NULL, 'PAY-68a5407bce4ec', '11', '2025-08-29 15:00:10', '2025-08-20 14:26:52', '2025-08-29 15:00:10'),
(34, 11, 18, 10.00, NULL, '1840943906402686877', 'be96bf637d521977cc973e70ff3f64bc35574b19a5992d93', 'https://pay.paymennt.com/checkout/be96bf637d521977cc973e70ff3f64bc35574b19a5992d93', 'pending', NULL, NULL, 'PAY-68a54278cd147', '11', '2025-08-29 15:00:26', '2025-08-20 14:35:21', '2025-08-29 15:00:26'),
(35, 11, 18, 10.00, NULL, '1840944303956647089', '8f0a788d686748890ddd2e556bbb00029607a7c4c7f5d1f8', 'https://pay.paymennt.com/checkout/8f0a788d686748890ddd2e556bbb00029607a7c4c7f5d1f8', 'paid', '2025-08-20 15:06:04', NULL, 'PAY-68a543f4148e6', '11', '2025-08-20 15:06:04', '2025-08-20 14:41:40', '2025-08-20 15:06:04'),
(36, 11, 18, 10.00, NULL, '1840944621809239445', 'a2f79bd022b478cd036353d8d5ff3cfe884a7a647eb787d4', 'https://pay.paymennt.com/checkout/a2f79bd022b478cd036353d8d5ff3cfe884a7a647eb787d4', 'paid', '2025-08-20 14:52:06', NULL, 'PAY-68a5452329021', '11', '2025-08-20 14:52:06', '2025-08-20 14:46:44', '2025-08-20 14:52:06'),
(37, 11, 18, 5.00, NULL, '1840947046257468417', '37b48020eca2a601347c15a5c1c889072cd20e85c4cd3086', 'https://pay.paymennt.com/checkout/37b48020eca2a601347c15a5c1c889072cd20e85c4cd3086', 'paid', '2025-08-20 15:26:03', NULL, 'PAY-68a54e2b4f45a', '11', '2025-08-20 15:26:03', '2025-08-20 15:25:16', '2025-08-20 15:26:03'),
(38, 11, 18, 10.00, NULL, '1840948117045611758', '061abf4c7efc16ecc18153e2f22ba17a0c5a6ce8914ba31f', 'https://pay.paymennt.com/checkout/061abf4c7efc16ecc18153e2f22ba17a0c5a6ce8914ba31f', 'pending', NULL, NULL, 'PAY-68a552286fbe6', '11', '2025-08-29 15:02:07', '2025-08-20 15:42:17', '2025-08-29 15:02:07'),
(39, 11, 18, 2222.00, NULL, '1840952484360471890', '488726ba827b8d726ea44ce29e661b54d31f04651da71d59', 'https://pay.paymennt.com/checkout/488726ba827b8d726ea44ce29e661b54d31f04651da71d59', 'pending', NULL, NULL, 'PAY-68a5626d7be0a', '11', '2025-08-29 15:00:27', '2025-08-20 16:51:42', '2025-08-29 15:00:27'),
(40, 11, 18, 10.00, NULL, '1840954144836148815', 'acfb4ccaa7bb33b525e474daaffc0f674e6ac082930cef9e', 'https://pay.paymennt.com/checkout/acfb4ccaa7bb33b525e474daaffc0f674e6ac082930cef9e', 'pending', NULL, NULL, 'PAY-68a5689d0b644', 'funcamp_11', '2025-08-29 15:03:04', '2025-08-20 17:18:05', '2025-08-29 15:03:04'),
(41, 11, 18, 5.00, NULL, '1840955083562282681', 'f27a3a8439ef86c46c8c14229de84f39d58d18f81d5b7a0f', 'https://pay.paymennt.com/checkout/f27a3a8439ef86c46c8c14229de84f39d58d18f81d5b7a0f', 'paid', '2025-08-20 13:37:39', NULL, 'PAY-68a56c1c1c140', 'funcamp_11', '2025-08-20 17:37:42', '2025-08-20 17:33:01', '2025-08-20 17:37:42'),
(42, 11, 18, 5.00, NULL, '1840955170767106052', 'f5bec53c3694c98fde35085b967d3eadabd3bd236b7b0a60', 'https://pay.paymennt.com/checkout/f5bec53c3694c98fde35085b967d3eadabd3bd236b7b0a60', 'pending', NULL, NULL, 'PAY-68a56c6f66991', 'funcamp_11', '2025-08-29 15:02:04', '2025-08-20 17:34:24', '2025-08-29 15:02:04'),
(43, 11, 18, 30.00, NULL, '1840963916648629769', 'fc87cd9484ee7b85d6f3124a37dd2789892c477d48e8108b', 'https://pay.paymennt.com/checkout/fc87cd9484ee7b85d6f3124a37dd2789892c477d48e8108b', 'pending', NULL, NULL, 'PAY-68a58d04161ab', 'funcamp_11', '2025-08-29 15:02:13', '2025-08-20 19:53:25', '2025-08-29 15:02:13'),
(44, 11, 18, 55.00, NULL, '1840974784596147504', 'b250632187bf6a2d5d2042df72494e81a05db33d741a7caa', 'https://pay.paymennt.com/checkout/b250632187bf6a2d5d2042df72494e81a05db33d741a7caa', 'pending', NULL, NULL, 'PAY-68a5b5806eeff', 'funcamp_11', '2025-08-29 15:02:11', '2025-08-20 22:46:09', '2025-08-29 15:02:11'),
(45, 11, 18, 10.00, NULL, '1841034947165076146', '1ee2ee88ac1580550923512af9fe67a7953eb6a1f10645d3', 'https://pay.paymennt.com/checkout/1ee2ee88ac1580550923512af9fe67a7953eb6a1f10645d3', 'pending', NULL, NULL, 'PAY-68a695a024b46', 'funcamp_11', '2025-08-29 15:03:07', '2025-08-21 14:42:24', '2025-08-29 15:03:07'),
(46, 11, 18, 10.00, NULL, '1841035452210009460', '2e758574d3c5609f151d62d1e53aea7e77ae41c517038d86', 'https://pay.paymennt.com/checkout/2e758574d3c5609f151d62d1e53aea7e77ae41c517038d86', 'pending', NULL, NULL, 'PAY-68a69781c2812', 'funcamp_11', '2025-08-29 15:00:21', '2025-08-21 14:50:26', '2025-08-29 15:00:21'),
(47, 17, 1, 10.00, NULL, '1841692428270407972', 'b9d9c026434a705f8a6d26fef48b8ebdd5bf8b256cc03aeb', 'https://pay.paymennt.com/checkout/b9d9c026434a705f8a6d26fef48b8ebdd5bf8b256cc03aeb', 'pending', NULL, NULL, 'PAY-68b026eed6857', 'funcamp_17', '2025-08-29 15:00:16', '2025-08-28 20:52:47', '2025-08-29 15:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname_ar` varchar(255) NOT NULL,
  `nickname_en` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `nickname_ar`, `nickname_en`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ignition.healthCheck', 'ignition.healthCheck', 'ignition.healthCheck', 'web', NULL, NULL),
(2, 'ignition.executeSolution', 'ignition.executeSolution', 'ignition.executeSolution', 'web', NULL, NULL),
(3, 'ignition.updateConfig', 'ignition.updateConfig', 'ignition.updateConfig', 'web', NULL, NULL),
(4, 'rate', 'rate', 'rate', 'web', NULL, NULL),
(5, 'rate.save', 'rate.save', 'rate.save', 'web', NULL, NULL),
(6, 'orders.rate', 'orders.rate', 'orders.rate', 'web', NULL, NULL),
(7, 'show.login', 'show.login', 'show.login', 'web', NULL, NULL),
(8, 'admin-login', 'admin-login', 'admin-login', 'web', NULL, NULL),
(9, 'logout', 'logout', 'logout', 'web', NULL, NULL),
(10, 'edit-profile', 'edit-profile', 'edit-profile', 'web', NULL, NULL),
(11, 'update-profile', 'update-profile', 'update-profile', 'web', NULL, NULL),
(12, 'home', 'home', 'home', 'web', NULL, NULL),
(13, 'reprots', 'reprots', 'reprots', 'web', NULL, NULL),
(14, 'roles.index', 'roles.index', 'roles.index', 'web', NULL, NULL),
(15, 'roles.create', 'roles.create', 'roles.create', 'web', NULL, NULL),
(16, 'roles.store', 'roles.store', 'roles.store', 'web', NULL, NULL),
(17, 'roles.edit', 'roles.edit', 'roles.edit', 'web', NULL, NULL),
(18, 'roles.update', 'roles.update', 'roles.update', 'web', NULL, NULL),
(19, 'roles.destroy', 'roles.destroy', 'roles.destroy', 'web', NULL, NULL),
(20, 'roles.deleteAll', 'roles.deleteAll', 'roles.deleteAll', 'web', NULL, NULL),
(21, 'admins.index', 'admins.index', 'admins.index', 'web', NULL, NULL),
(22, 'admins.create', 'admins.create', 'admins.create', 'web', NULL, NULL),
(23, 'admins.store', 'admins.store', 'admins.store', 'web', NULL, NULL),
(24, 'admins.edit', 'admins.edit', 'admins.edit', 'web', NULL, NULL),
(25, 'admins.update', 'admins.update', 'admins.update', 'web', NULL, NULL),
(26, 'admins.destroy', 'admins.destroy', 'admins.destroy', 'web', NULL, NULL),
(27, 'admins.deleteAll', 'admins.deleteAll', 'admins.deleteAll', 'web', NULL, NULL),
(28, 'customers.index', 'customers.index', 'customers.index', 'web', NULL, NULL),
(29, 'customers.create', 'customers.create', 'customers.create', 'web', NULL, NULL),
(30, 'customers.store', 'customers.store', 'customers.store', 'web', NULL, NULL),
(31, 'customers.edit', 'customers.edit', 'customers.edit', 'web', NULL, NULL),
(32, 'customers.update', 'customers.update', 'customers.update', 'web', NULL, NULL),
(33, 'customers.destroy', 'customers.destroy', 'customers.destroy', 'web', NULL, NULL),
(34, 'customers.deleteAll', 'customers.deleteAll', 'customers.deleteAll', 'web', NULL, NULL),
(35, 'stocks.index', 'stocks.index', 'stocks.index', 'web', NULL, NULL),
(36, 'stocks.create', 'stocks.create', 'stocks.create', 'web', NULL, NULL),
(37, 'stocks.show', 'stocks.show', 'stocks.show', 'web', NULL, NULL),
(38, 'stocks.store', 'stocks.store', 'stocks.store', 'web', NULL, NULL),
(39, 'stocks.edit', 'stocks.edit', 'stocks.edit', 'web', NULL, NULL),
(40, 'stocks.update', 'stocks.update', 'stocks.update', 'web', NULL, NULL),
(41, 'stocks.destroy', 'stocks.destroy', 'stocks.destroy', 'web', NULL, NULL),
(42, 'stocks.deleteAll', 'stocks.deleteAll', 'stocks.deleteAll', 'web', NULL, NULL),
(43, 'addons.index', 'addons.index', 'addons.index', 'web', NULL, NULL),
(44, 'addons.create', 'addons.create', 'addons.create', 'web', NULL, NULL),
(45, 'addons.show', 'addons.show', 'addons.show', 'web', NULL, NULL),
(46, 'addons.store', 'addons.store', 'addons.store', 'web', NULL, NULL),
(47, 'addons.edit', 'addons.edit', 'addons.edit', 'web', NULL, NULL),
(48, 'addons.update', 'addons.update', 'addons.update', 'web', NULL, NULL),
(49, 'addons.destroy', 'addons.destroy', 'addons.destroy', 'web', NULL, NULL),
(50, 'addons.deleteAll', 'addons.deleteAll', 'addons.deleteAll', 'web', NULL, NULL),
(51, 'stock-quantities.index', 'stock-quantities.index', 'stock-quantities.index', 'web', NULL, NULL),
(52, 'stock-quantities.create', 'stock-quantities.create', 'stock-quantities.create', 'web', NULL, NULL),
(53, 'stock-quantities.store', 'stock-quantities.store', 'stock-quantities.store', 'web', NULL, NULL),
(54, 'stock-quantities.edit', 'stock-quantities.edit', 'stock-quantities.edit', 'web', NULL, NULL),
(55, 'stock-quantities.show', 'stock-quantities.show', 'stock-quantities.show', 'web', NULL, NULL),
(56, 'stock-quantities.update', 'stock-quantities.update', 'stock-quantities.update', 'web', NULL, NULL),
(57, 'stock-quantities.destroy', 'stock-quantities.destroy', 'stock-quantities.destroy', 'web', NULL, NULL),
(58, 'stock-quantities.deleteAll', 'stock-quantities.deleteAll', 'stock-quantities.deleteAll', 'web', NULL, NULL),
(59, 'services.index', 'services.index', 'services.index', 'web', NULL, NULL),
(60, 'services.create', 'services.create', 'services.create', 'web', NULL, NULL),
(61, 'services.store', 'services.store', 'services.store', 'web', NULL, NULL),
(62, 'services.edit', 'services.edit', 'services.edit', 'web', NULL, NULL),
(63, 'services.show', 'services.show', 'services.show', 'web', NULL, NULL),
(64, 'services.update', 'services.update', 'services.update', 'web', NULL, NULL),
(65, 'services.uploadImage', 'services.uploadImage', 'services.uploadImage', 'web', NULL, NULL),
(66, 'services.destroy', 'services.destroy', 'services.destroy', 'web', NULL, NULL),
(67, 'services.deleteAll', 'services.deleteAll', 'services.deleteAll', 'web', NULL, NULL),
(68, 'orders.index', 'orders.index', 'orders.index', 'web', NULL, NULL),
(69, 'orders.signin', 'orders.signin', 'orders.signin', 'web', NULL, NULL),
(70, 'orders.updatesignin', 'orders.updatesignin', 'orders.updatesignin', 'web', NULL, NULL),
(71, 'orders.uploadTemporaryImage', 'orders.uploadTemporaryImage', 'orders.uploadTemporaryImage', 'web', NULL, NULL),
(72, 'orders.removeImage', 'orders.removeImage', 'orders.removeImage', 'web', NULL, NULL),
(73, 'orders.logout', 'orders.logout', 'orders.logout', 'web', NULL, NULL),
(74, 'orders.updatelogout', 'orders.updatelogout', 'orders.updatelogout', 'web', NULL, NULL),
(75, 'orders.insurance', 'orders.insurance', 'orders.insurance', 'web', NULL, NULL),
(76, 'orders.updateInsurance', 'orders.updateInsurance', 'orders.updateInsurance', 'web', NULL, NULL),
(77, 'orders.reports', 'orders.reports', 'orders.reports', 'web', NULL, NULL),
(78, 'orders.addons', 'orders.addons', 'orders.addons', 'web', NULL, NULL),
(79, 'ordersStore.addons', 'ordersStore.addons', 'ordersStore.addons', 'web', NULL, NULL),
(80, 'ordersUpdate.addons', 'ordersUpdate.addons', 'ordersUpdate.addons', 'web', NULL, NULL),
(81, 'orders.removeAddon', 'orders.removeAddon', 'orders.removeAddon', 'web', NULL, NULL),
(82, 'update.reports', 'update.reports', 'update.reports', 'web', NULL, NULL),
(83, 'orders.create', 'orders.create', 'orders.create', 'web', NULL, NULL),
(84, 'orders.show', 'orders.show', 'orders.show', 'web', NULL, NULL),
(85, 'orders.store', 'orders.store', 'orders.store', 'web', NULL, NULL),
(86, 'orders.invoice', 'orders.invoice', 'orders.invoice', 'web', NULL, NULL),
(87, 'orders.receipt', 'orders.receipt', 'orders.receipt', 'web', NULL, NULL),
(88, 'orders.edit', 'orders.edit', 'orders.edit', 'web', NULL, NULL),
(89, 'orders.quote', 'orders.quote', 'orders.quote', 'web', NULL, NULL),
(90, 'orders.update', 'orders.update', 'orders.update', 'web', NULL, NULL),
(91, 'orders.destroy', 'orders.destroy', 'orders.destroy', 'web', NULL, NULL),
(92, 'orders.deleteAll', 'orders.deleteAll', 'orders.deleteAll', 'web', NULL, NULL),
(93, 'user-orders', 'user-orders', 'user-orders', 'web', NULL, NULL),
(94, 'bank-accounts.index', 'bank-accounts.index', 'bank-accounts.index', 'web', NULL, NULL),
(95, 'bank-accounts.create', 'bank-accounts.create', 'bank-accounts.create', 'web', NULL, NULL),
(96, 'bank-accounts.show', 'bank-accounts.show', 'bank-accounts.show', 'web', NULL, NULL),
(97, 'bank-accounts.export', 'bank-accounts.export', 'bank-accounts.export', 'web', NULL, NULL),
(98, 'bank-accounts.store', 'bank-accounts.store', 'bank-accounts.store', 'web', NULL, NULL),
(99, 'bank-accounts.edit', 'bank-accounts.edit', 'bank-accounts.edit', 'web', NULL, NULL),
(100, 'bank-accounts.update', 'bank-accounts.update', 'bank-accounts.update', 'web', NULL, NULL),
(101, 'bank-accounts.destroy', 'bank-accounts.destroy', 'bank-accounts.destroy', 'web', NULL, NULL),
(102, 'bank-accounts.deleteAll', 'bank-accounts.deleteAll', 'bank-accounts.deleteAll', 'web', NULL, NULL),
(103, 'payments.index', 'payments.index', 'payments.index', 'web', NULL, NULL),
(104, 'transactions.index', 'transactions.index', 'transactions.index', 'web', NULL, NULL),
(105, 'accounts.store', 'accounts.store', 'accounts.store', 'web', NULL, NULL),
(106, 'accounts.edit', 'accounts.edit', 'accounts.edit', 'web', NULL, NULL),
(107, 'accounts.update', 'accounts.update', 'accounts.update', 'web', NULL, NULL),
(108, 'payments.create', 'payments.create', 'payments.create', 'web', NULL, NULL),
(109, 'payments.show', 'payments.show', 'payments.show', 'web', NULL, NULL),
(110, 'payments.verified', 'payments.verified', 'payments.verified', 'web', NULL, NULL),
(111, 'payments.print', 'payments.print', 'payments.print', 'web', NULL, NULL),
(112, 'payments.store', 'payments.store', 'payments.store', 'web', NULL, NULL),
(113, 'payments.edit', 'payments.edit', 'payments.edit', 'web', NULL, NULL),
(114, 'payments.update', 'payments.update', 'payments.update', 'web', NULL, NULL),
(115, 'payments.destroy', 'payments.destroy', 'payments.destroy', 'web', NULL, NULL),
(116, 'transactions.destroy', 'transactions.destroy', 'transactions.destroy', 'web', NULL, NULL),
(117, 'payments.deleteAll', 'payments.deleteAll', 'payments.deleteAll', 'web', NULL, NULL),
(118, 'expense-items.index', 'expense-items.index', 'expense-items.index', 'web', NULL, NULL),
(119, 'expense-items.create', 'expense-items.create', 'expense-items.create', 'web', NULL, NULL),
(120, 'expense-items.show', 'expense-items.show', 'expense-items.show', 'web', NULL, NULL),
(121, 'expense-items.store', 'expense-items.store', 'expense-items.store', 'web', NULL, NULL),
(122, 'expense-items.edit', 'expense-items.edit', 'expense-items.edit', 'web', NULL, NULL),
(123, 'expense-items.update', 'expense-items.update', 'expense-items.update', 'web', NULL, NULL),
(124, 'expense-items.destroy', 'expense-items.destroy', 'expense-items.destroy', 'web', NULL, NULL),
(125, 'expenses.index', 'expenses.index', 'expenses.index', 'web', NULL, NULL),
(126, 'expenses.export', 'expenses.export', 'expenses.export', 'web', NULL, NULL),
(127, 'expenses.create', 'expenses.create', 'expenses.create', 'web', NULL, NULL),
(128, 'expenses.show', 'expenses.show', 'expenses.show', 'web', NULL, NULL),
(129, 'expenses.store', 'expenses.store', 'expenses.store', 'web', NULL, NULL),
(130, 'expenses.edit', 'expenses.edit', 'expenses.edit', 'web', NULL, NULL),
(131, 'expenses.update', 'expenses.update', 'expenses.update', 'web', NULL, NULL),
(132, 'expenses.destroy', 'expenses.destroy', 'expenses.destroy', 'web', NULL, NULL),
(133, 'set-lang', 'set-lang', 'set-lang', 'web', NULL, NULL),
(134, 'calender', 'calender', 'calender', 'web', NULL, NULL),
(135, 'serve.file', 'serve.file', 'serve.file', 'web', NULL, NULL),
(136, 'login', 'login', 'login', 'web', NULL, NULL),
(137, 'register', 'register', 'register', 'web', NULL, NULL),
(138, 'password.request', 'password.request', 'password.request', 'web', NULL, NULL),
(139, 'password.email', 'password.email', 'password.email', 'web', NULL, NULL),
(140, 'password.reset', 'password.reset', 'password.reset', 'web', NULL, NULL),
(141, 'password.update', 'password.update', 'password.update', 'web', NULL, NULL),
(142, 'general_payments.index', 'general_payments.index', 'general_payments.index', 'web', NULL, NULL),
(143, 'general_payments.create', 'general_payments.create', 'general_payments.create', 'web', NULL, NULL),
(144, 'general_payments.store', 'general_payments.store', 'general_payments.store', 'web', NULL, NULL),
(145, 'general_payments.show', 'general_payments.show', 'general_payments.show', 'web', NULL, NULL),
(146, 'general_payments.edit', 'general_payments.edit', 'general_payments.edit', 'web', NULL, NULL),
(147, 'general_payments.update', 'general_payments.update', 'general_payments.update', 'web', NULL, NULL),
(148, 'general_payments.destroy', 'general_payments.destroy', 'general_payments.destroy', 'web', NULL, NULL),
(149, 'terms_sittngs.index', 'terms_sittngs.index', 'terms_sittngs.index', 'web', NULL, NULL),
(150, 'terms_sittngs.create', 'terms_sittngs.create', 'terms_sittngs.create', 'web', NULL, NULL),
(151, 'terms_sittngs.store', 'terms_sittngs.store', 'terms_sittngs.store', 'web', NULL, NULL),
(152, 'terms_sittngs.show', 'terms_sittngs.show', 'terms_sittngs.show', 'web', NULL, NULL),
(153, 'terms_sittngs.edit', 'terms_sittngs.edit', 'terms_sittngs.edit', 'web', NULL, NULL),
(154, 'terms_sittngs.update', 'terms_sittngs.update', 'terms_sittngs.update', 'web', NULL, NULL),
(155, 'terms_sittngs.destroy', 'terms_sittngs.destroy', 'terms_sittngs.destroy', 'web', NULL, NULL),
(156, 'orders.terms_form', 'orders.terms_form', 'orders.terms_form', 'web', NULL, NULL),
(157, 'statistics.index', 'statistics.index', 'statistics.index', 'web', NULL, NULL),
(158, 'statistics.create', 'statistics.create', 'statistics.create', 'web', NULL, NULL),
(159, 'statistics.store', 'statistics.store', 'statistics.store', 'web', NULL, NULL),
(160, 'statistics.show', 'statistics.show', 'statistics.show', 'web', NULL, NULL),
(161, 'statistics.edit', 'statistics.edit', 'statistics.edit', 'web', NULL, NULL),
(162, 'statistics.update', 'statistics.update', 'statistics.update', 'web', NULL, NULL),
(163, 'statistics.destroy', 'statistics.destroy', 'statistics.destroy', 'web', NULL, NULL),
(164, 'questions.index', 'questions.index', 'questions.index', 'web', NULL, NULL),
(165, 'questions.create', 'questions.create', 'questions.create', 'web', NULL, NULL),
(166, 'questions.store', 'questions.store', 'questions.store', 'web', NULL, NULL),
(167, 'questions.show', 'questions.show', 'questions.show', 'web', NULL, NULL),
(168, 'questions.edit', 'questions.edit', 'questions.edit', 'web', NULL, NULL),
(169, 'questions.update', 'questions.update', 'questions.update', 'web', NULL, NULL),
(170, 'questions.destroy', 'questions.destroy', 'questions.destroy', 'web', NULL, NULL),
(171, 'questions.storeAnswer', 'questions.storeAnswer', 'questions.storeAnswer', 'web', NULL, NULL),
(172, 'questions.answers', 'questions.answers', 'questions.answers', 'web', NULL, NULL),
(173, 'answers.user', 'answers.user', 'answers.user', 'web', NULL, NULL),
(174, 'debugbar.openhandler', 'debugbar.openhandler', 'debugbar.openhandler', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(175, 'debugbar.clockwork', 'debugbar.clockwork', 'debugbar.clockwork', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(176, 'debugbar.assets.css', 'debugbar.assets.css', 'debugbar.assets.css', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(177, 'debugbar.assets.js', 'debugbar.assets.js', 'debugbar.assets.js', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(178, 'debugbar.cache.delete', 'debugbar.cache.delete', 'debugbar.cache.delete', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(179, 'debugbar.queries.explain', 'debugbar.queries.explain', 'debugbar.queries.explain', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(180, 'signature.show', 'signature.show', 'signature.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(181, 'signature.store', 'signature.store', 'signature.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(182, 'stocks.destroyServiceStock', 'stocks.destroyServiceStock', 'stocks.destroyServiceStock', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(183, 'stocks.destroyServiceReport', 'stocks.destroyServiceReport', 'stocks.destroyServiceReport', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(184, 'addons.print', 'addons.print', 'addons.print', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(185, 'services.reports.move', 'services.reports.move', 'services.reports.move', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(186, 'order.verified', 'order.verified', 'order.verified', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(187, 'orders.accept_terms', 'orders.accept_terms', 'orders.accept_terms', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(188, 'orders.updateNotes', 'orders.updateNotes', 'orders.updateNotes', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(189, 'payment-links.index', 'payment-links.index', 'payment-links.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(190, 'payment-links.create', 'payment-links.create', 'payment-links.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(191, 'payment-links.store', 'payment-links.store', 'payment-links.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(192, 'payment-links.show-created', 'payment-links.show-created', 'payment-links.show-created', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(193, 'payment-links.test-connection', 'payment-links.test-connection', 'payment-links.test-connection', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(194, 'payment-links.test-connection-debug', 'payment-links.test-connection-debug', 'payment-links.test-connection-debug', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(195, 'payment-links.test-simple-controller', 'payment-links.test-simple-controller', 'payment-links.test-simple-controller', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(196, 'payment-links.show', 'payment-links.show', 'payment-links.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(197, 'payment-links.resend', 'payment-links.resend', 'payment-links.resend', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(198, 'payment-links.resend-email', 'payment-links.resend-email', 'payment-links.resend-email', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(199, 'payment-links.cancel', 'payment-links.cancel', 'payment-links.cancel', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(200, 'payment-links.destroy', 'payment-links.destroy', 'payment-links.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(201, 'payment-links.qr-code', 'payment-links.qr-code', 'payment-links.qr-code', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(202, 'payment-links.copy', 'payment-links.copy', 'payment-links.copy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(203, 'payment-links.update-status', 'payment-links.update-status', 'payment-links.update-status', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(204, 'warehouse_sales.index', 'warehouse_sales.index', 'warehouse_sales.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(205, 'warehouse_sales.show', 'warehouse_sales.show', 'warehouse_sales.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(206, 'warehouse_sales.store', 'warehouse_sales.store', 'warehouse_sales.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(207, 'warehouse_sales.update', 'warehouse_sales.update', 'warehouse_sales.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(208, 'warehouse_sales.destroy', 'warehouse_sales.destroy', 'warehouse_sales.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(209, 'tasks.index', 'tasks.index', 'tasks.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(210, 'tasks.create', 'tasks.create', 'tasks.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(211, 'tasks.store', 'tasks.store', 'tasks.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(212, 'tasks.edit', 'tasks.edit', 'tasks.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(213, 'tasks.update', 'tasks.update', 'tasks.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(214, 'tasks.destroy', 'tasks.destroy', 'tasks.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(215, 'tasks.reports', 'tasks.reports', 'tasks.reports', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(216, 'tasks.exportReports', 'tasks.exportReports', 'tasks.exportReports', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(217, 'employee.tasks', 'employee.tasks', 'employee.tasks', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(218, 'tasks.updateStatus', 'tasks.updateStatus', 'tasks.updateStatus', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(219, 'notifications.read', 'notifications.read', 'notifications.read', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(220, 'notifications.destroy', 'notifications.destroy', 'notifications.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(221, 'notices.index', 'notices.index', 'notices.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(222, 'notices.store', 'notices.store', 'notices.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(223, 'notices.show', 'notices.show', 'notices.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(224, 'notices.edit', 'notices.edit', 'notices.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(225, 'notices.update', 'notices.update', 'notices.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(226, 'notices.destroy', 'notices.destroy', 'notices.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(227, 'notice-types.index', 'notice-types.index', 'notice-types.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(228, 'notice-types.create', 'notice-types.create', 'notice-types.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(229, 'notice-types.store', 'notice-types.store', 'notice-types.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(230, 'notice-types.show', 'notice-types.show', 'notice-types.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(231, 'notice-types.edit', 'notice-types.edit', 'notice-types.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(232, 'notice-types.update', 'notice-types.update', 'notice-types.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(233, 'notice-types.destroy', 'notice-types.destroy', 'notice-types.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(234, 'notices.get-customer-orders', 'notices.get-customer-orders', 'notices.get-customer-orders', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(235, 'orders.check-customer-notices', 'orders.check-customer-notices', 'orders.check-customer-notices', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(236, 'webhooks.paymennt', 'webhooks.paymennt', 'webhooks.paymennt', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(237, 'payment.callback', 'payment.callback', 'payment.callback', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(238, 'daily-reports.index', 'daily-reports.index', 'daily-reports.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(239, 'daily-reports.create', 'daily-reports.create', 'daily-reports.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(240, 'daily-reports.store', 'daily-reports.store', 'daily-reports.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(241, 'daily-reports.show', 'daily-reports.show', 'daily-reports.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(242, 'daily-reports.edit', 'daily-reports.edit', 'daily-reports.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(243, 'daily-reports.update', 'daily-reports.update', 'daily-reports.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(244, 'daily-reports.destroy', 'daily-reports.destroy', 'daily-reports.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(245, 'daily-reports.export', 'daily-reports.export', 'daily-reports.export', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(246, 'equipment-directories.index', 'equipment-directories.index', 'equipment-directories.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(247, 'equipment-directories.create', 'equipment-directories.create', 'equipment-directories.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(248, 'equipment-directories.store', 'equipment-directories.store', 'equipment-directories.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(249, 'equipment-directories.edit', 'equipment-directories.edit', 'equipment-directories.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(250, 'equipment-directories.update', 'equipment-directories.update', 'equipment-directories.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(251, 'equipment-directories.destroy', 'equipment-directories.destroy', 'equipment-directories.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(252, 'equipment-directories.items.index', 'equipment-directories.items.index', 'equipment-directories.items.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(253, 'equipment-directories.items.create', 'equipment-directories.items.create', 'equipment-directories.items.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(254, 'equipment-directories.items.store', 'equipment-directories.items.store', 'equipment-directories.items.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(255, 'equipment-directories.items.edit', 'equipment-directories.items.edit', 'equipment-directories.items.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(256, 'equipment-directories.items.update', 'equipment-directories.items.update', 'equipment-directories.items.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(257, 'equipment-directories.items.destroy', 'equipment-directories.items.destroy', 'equipment-directories.items.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(258, 'equipment-directories.media.destroy', 'equipment-directories.media.destroy', 'equipment-directories.media.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(259, 'equipment-directories.export', 'equipment-directories.export', 'equipment-directories.export', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(260, 'equipment-directories.items.export', 'equipment-directories.items.export', 'equipment-directories.items.export', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(261, 'camp-reports.index', 'camp-reports.index', 'camp-reports.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(262, 'camp-reports.create', 'camp-reports.create', 'camp-reports.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(263, 'camp-reports.store', 'camp-reports.store', 'camp-reports.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(264, 'camp-reports.edit', 'camp-reports.edit', 'camp-reports.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(265, 'camp-reports.update', 'camp-reports.update', 'camp-reports.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(266, 'camp-reports.destroy', 'camp-reports.destroy', 'camp-reports.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(267, 'camp-reports.show', 'camp-reports.show', 'camp-reports.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(268, 'meetings.index', 'meetings.index', 'meetings.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(269, 'meetings.create', 'meetings.create', 'meetings.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(270, 'meetings.store', 'meetings.store', 'meetings.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(271, 'meetings.show', 'meetings.show', 'meetings.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(272, 'meetings.edit', 'meetings.edit', 'meetings.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(273, 'meetings.update', 'meetings.update', 'meetings.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(274, 'meetings.destroy', 'meetings.destroy', 'meetings.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(275, 'meeting-locations.index', 'meeting-locations.index', 'meeting-locations.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(276, 'meeting-locations.create', 'meeting-locations.create', 'meeting-locations.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(277, 'meeting-locations.store', 'meeting-locations.store', 'meeting-locations.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(278, 'meeting-locations.show', 'meeting-locations.show', 'meeting-locations.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(279, 'meeting-locations.edit', 'meeting-locations.edit', 'meeting-locations.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(280, 'meeting-locations.update', 'meeting-locations.update', 'meeting-locations.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(281, 'meeting-locations.destroy', 'meeting-locations.destroy', 'meeting-locations.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(282, 'violation-types.index', 'violation-types.index', 'violation-types.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(283, 'violation-types.create', 'violation-types.create', 'violation-types.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(284, 'violation-types.store', 'violation-types.store', 'violation-types.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(285, 'violation-types.show', 'violation-types.show', 'violation-types.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(286, 'violation-types.edit', 'violation-types.edit', 'violation-types.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(287, 'violation-types.update', 'violation-types.update', 'violation-types.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(288, 'violation-types.destroy', 'violation-types.destroy', 'violation-types.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(289, 'violations.index', 'violations.index', 'violations.index', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(290, 'violations.create', 'violations.create', 'violations.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(291, 'violations.store', 'violations.store', 'violations.store', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(292, 'violations.show', 'violations.show', 'violations.show', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(293, 'violations.edit', 'violations.edit', 'violations.edit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(294, 'violations.update', 'violations.update', 'violations.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(295, 'violations.destroy', 'violations.destroy', 'violations.destroy', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(296, 'surveys.create', 'surveys.create', 'surveys.create', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(297, 'surveys.answer', 'surveys.answer', 'surveys.answer', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(298, 'surveys.update', 'surveys.update', 'surveys.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(299, 'surveys.results', 'surveys.results', 'surveys.results', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(300, 'surveys.statistics', 'surveys.statistics', 'surveys.statistics', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(301, 'surveys.settings', 'surveys.settings', 'surveys.settings', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(302, 'surveys.settings.update', 'surveys.settings.update', 'surveys.settings.update', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(303, 'surveys.thankyou', 'surveys.thankyou', 'surveys.thankyou', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(304, 'surveys.submit', 'surveys.submit', 'surveys.submit', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(305, 'surveys.public', 'surveys.public', 'surveys.public', 'web', '2025-08-20 08:17:51', '2025-08-20 08:17:51');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_login_images`
--

CREATE TABLE `pre_login_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_login_images`
--

INSERT INTO `pre_login_images` (`id`, `order_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 5, 'logins/PdN9Islpe3JM8y703bttnJ91tnhPdvzMa5bjbzuc.jpg', '2025-08-10 00:46:12', '2025-08-10 00:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `pre_logout_images`
--

CREATE TABLE `pre_logout_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `user_id`, `created_at`, `updated_at`) VALUES
(6, 'ما رأيك في المخيم عموما ؟', 1, '2025-08-11 16:31:24', '2025-08-11 16:31:24'),
(7, 'ما مو مستوى تقييمك للخدمة ؟', 1, '2025-08-11 16:31:42', '2025-08-11 16:31:42'),
(8, 'جودة الخدمات المقدمة ؟', 1, '2025-08-11 16:32:01', '2025-08-11 16:32:01'),
(9, '1', 1, '2025-08-16 17:28:28', '2025-08-16 17:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `registrationforms`
--

CREATE TABLE `registrationforms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `request_code` varchar(20) NOT NULL,
  `booking_date` date NOT NULL,
  `time_slot` varchar(255) NOT NULL,
  `checkin_time` time DEFAULT NULL,
  `checkout_time` time DEFAULT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `persons` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile_phone` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrationforms`
--

INSERT INTO `registrationforms` (`id`, `service_id`, `request_code`, `booking_date`, `time_slot`, `checkin_time`, `checkout_time`, `terms_accepted`, `persons`, `first_name`, `last_name`, `mobile_phone`, `email`, `notes`, `created_at`, `updated_at`) VALUES
(2, 15, 'REQ68AB6888A3B36', '2025-08-29', '5-1', NULL, NULL, 1, 30, 'ن', 'غ', '0505656565', 'a@po.outlook.com', NULL, '2025-08-25 06:31:20', '2025-08-25 06:31:20'),
(3, 15, 'REQ68AC7C5C4CDD2', '2025-09-06', 'other', '16:00:00', '03:00:00', 1, 50, 'mohammed', 'saeed', '05088117745654', 'uaeddd.29@hotmail.com', 'كراسي زيادى', '2025-08-26 02:08:12', '2025-08-26 02:08:12'),
(4, 15, 'REQ68AC933A1C870', '2025-08-26', '4-12', NULL, NULL, 1, 20, 'mohammed', 'saif', '0503670303', 'uae.29@hotmail.com', 'ff', '2025-08-26 03:45:46', '2025-08-26 03:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `nickname_ar` varchar(255) NOT NULL,
  `nickname_en` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `nickname_ar`, `nickname_en`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', 'سوبر أدمن', 'Super Admin', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(2, '', 'web', 'أدمن', 'Admin', '2025-07-18 08:10:28', '2025-08-19 01:43:22'),
(3, 'employee', 'web', 'موظف', 'Employee', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(4, 'admin', 'web', 'أدمن', 'Admin', '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(5, 'test-25-8-2025', 'web', 'تجربة', 'test 25-8-2025', '2025-08-26 00:31:47', '2025-08-26 00:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(14, 5),
(15, 1),
(16, 1),
(16, 5),
(17, 1),
(17, 5),
(18, 1),
(18, 5),
(19, 1),
(19, 5),
(20, 1),
(20, 5),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(192, 1),
(193, 1),
(194, 1),
(195, 1),
(196, 1),
(197, 1),
(198, 1),
(199, 1),
(200, 1),
(201, 1),
(202, 1),
(203, 1),
(204, 1),
(205, 1),
(206, 1),
(207, 1),
(208, 1),
(209, 1),
(210, 1),
(211, 1),
(212, 1),
(213, 1),
(214, 1),
(215, 1),
(216, 1),
(217, 1),
(218, 1),
(219, 1),
(220, 1),
(221, 1),
(222, 1),
(223, 1),
(224, 1),
(225, 1),
(226, 1),
(227, 1),
(228, 1),
(229, 1),
(230, 1),
(231, 1),
(232, 1),
(233, 1),
(234, 1),
(235, 1),
(236, 1),
(237, 1),
(238, 1),
(239, 1),
(240, 1),
(241, 1),
(242, 1),
(243, 1),
(244, 1),
(245, 1),
(246, 1),
(247, 1),
(248, 1),
(249, 1),
(250, 1),
(251, 1),
(252, 1),
(253, 1),
(254, 1),
(255, 1),
(256, 1),
(257, 1),
(258, 1),
(259, 1),
(260, 1),
(261, 1),
(262, 1),
(263, 1),
(264, 1),
(265, 1),
(266, 1),
(267, 1),
(268, 1),
(269, 1),
(270, 1),
(271, 1),
(272, 1),
(273, 1),
(274, 1),
(275, 1),
(276, 1),
(277, 1),
(278, 1),
(279, 1),
(280, 1),
(281, 1),
(282, 1),
(283, 1),
(284, 1),
(285, 1),
(286, 1),
(287, 1),
(288, 1),
(289, 1),
(290, 1),
(291, 1),
(292, 1),
(293, 1),
(294, 1),
(295, 1),
(296, 1),
(297, 1),
(298, 1),
(299, 1),
(300, 1),
(301, 1),
(302, 1),
(303, 1),
(304, 1),
(305, 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `registeration_forms` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT 24,
  `hour_from` time DEFAULT NULL,
  `hour_to` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `registeration_forms`, `price`, `hours`, `hour_from`, `hour_to`, `created_at`, `updated_at`) VALUES
(1, 'Golden Camp V.I.P', 'المخيم الذهبي', 0, 1500.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-08-28 20:29:32'),
(2, 'Silver Camp', 'المخيم الفضي', 0, 1500.00, 7, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(3, 'Bronze Camp', 'المخيم البرونزي', 0, 1200.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(4, 'Out Side Camp', 'مخيم خارجي', 0, 1500.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(5, 'Combined All camp', '', 0, 4700.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(6, 'Golden Camp V.I.P 2', '2المخيم الذهبي', 0, 2000.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(7, 'Silver Camp 2', '2المخيم الفضي', 0, 1500.00, 7, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(8, 'Bronze Camp 2', '2المخيم البرونزي', 0, 1200.00, 1, '08:00:00', '17:00:00', '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(15, 'المخيم الذهبي تجربة 11-8-2025', NULL, 1, 2000.00, 8, '16:00:00', '00:00:00', '2025-08-11 21:14:13', '2025-08-25 03:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `service_reports`
--

CREATE TABLE `service_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 1,
  `set_qty` int(11) NOT NULL,
  `ordered_count` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `not_completed_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `report_orders` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`report_orders`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_reports`
--

INSERT INTO `service_reports` (`id`, `name`, `count`, `set_qty`, `ordered_count`, `image`, `service_id`, `is_completed`, `not_completed_reason`, `created_at`, `updated_at`, `report_orders`) VALUES
(1, 'حمام كرفان (Caravan toilet)', 1, 0, 1, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(2, 'معطر اليدين (Hand freshener)', 8, 0, 2, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(3, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, 3, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(4, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, 4, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(5, 'معطر الجو (Toilet air freshener)', 9, 0, 5, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(6, 'حفرة البالوعة (Drain hole)', 1, 0, 6, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(7, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, 7, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(8, 'محلول البالوعة (B.T Chemical)', 1, 0, 8, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(9, 'صابون غسيل اليد (Hand washing soap)', 7, 0, 9, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(10, 'تعبئة خزان الماء (Filling water tank)', 1, 0, 10, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(11, 'معطر المرحاض (Toilet freshener)', 10, 0, 11, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(12, 'البرونزي (Bronze)', 12, 0, 12, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(13, 'جنريتر (Generator)', 13, 0, 13, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(14, 'دبة بترول (Petrol tank)', 14, 0, 14, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(15, 'إضاءة صفراء (Yellow lighting)', 16, 0, 15, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(16, 'رواق (Covered porch)', 15, 0, 16, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(17, 'كشاف أبيض (White flashlight)', 17, 0, 17, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(18, 'توصيلات (Connections)', 18, 0, 18, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(19, 'ستاند المخيم (Camp stand)', 19, 0, 19, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(20, 'إضاءة ستراب (Strap lighting)', 20, 0, 20, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(21, 'إضاءة زينة 1 (Decorative lighting 1)', 26, 0, 21, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(22, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, 22, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(23, 'عشب صناعي (Artificial grass)', 23, 0, 23, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(24, 'جلسة أرضية (Floor sitting)', 25, 0, 24, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(25, 'سجاد (Carpets)', 24, 0, 27, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(26, 'طقم جلسة (Sitting set)', 26, 0, 26, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(27, 'طاولة خشب كبير (Large wooden table)', 27, 0, 25, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(28, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, 29, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(29, 'مزهرية ورد كبير (Large rose vase)', 29, 0, 28, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(30, 'مناديل (Tissues)', 30, 0, 30, NULL, 1, 0, NULL, '2025-07-18 08:10:28', '2025-08-27 21:35:04', NULL),
(31, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(32, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(33, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(34, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(35, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(36, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(37, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(38, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(39, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(40, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(41, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(42, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(43, 'جنريتر (Generator)', 13, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(44, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(45, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(46, 'رواق (Covered porch)', 15, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(47, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(48, 'توصيلات (Connections)', 18, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(49, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(50, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(51, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(52, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(53, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(54, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(55, 'سجاد (Carpets)', 24, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(56, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(57, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(58, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(59, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(60, 'مناديل (Tissues)', 30, 0, NULL, NULL, 2, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(61, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(62, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(63, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(64, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(65, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(66, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(67, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(68, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(69, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(70, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(71, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(72, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(73, 'جنريتر (Generator)', 13, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(74, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(75, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(76, 'رواق (Covered porch)', 15, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(77, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(78, 'توصيلات (Connections)', 18, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(79, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(80, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(81, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(82, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(83, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(84, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(85, 'سجاد (Carpets)', 24, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(86, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(87, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(88, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(89, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(90, 'مناديل (Tissues)', 30, 0, NULL, NULL, 3, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(91, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(92, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(93, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(94, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(95, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(96, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(97, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(98, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(99, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(100, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(101, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(102, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(103, 'جنريتر (Generator)', 13, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(104, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(105, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(106, 'رواق (Covered porch)', 15, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(107, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(108, 'توصيلات (Connections)', 18, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(109, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(110, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(111, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(112, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(113, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(114, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(115, 'سجاد (Carpets)', 24, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(116, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(117, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(118, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(119, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(120, 'مناديل (Tissues)', 30, 0, NULL, NULL, 4, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(121, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(122, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(123, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(124, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(125, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(126, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(127, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(128, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(129, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(130, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(131, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(132, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(133, 'جنريتر (Generator)', 13, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(134, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(135, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(136, 'رواق (Covered porch)', 15, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(137, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(138, 'توصيلات (Connections)', 18, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(139, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(140, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(141, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(142, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(143, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(144, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(145, 'سجاد (Carpets)', 24, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(146, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(147, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(148, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(149, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(150, 'مناديل (Tissues)', 30, 0, NULL, NULL, 5, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(151, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(152, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(153, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(154, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(155, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(156, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(157, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(158, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(159, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(160, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(161, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(162, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(163, 'جنريتر (Generator)', 13, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(164, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(165, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(166, 'رواق (Covered porch)', 15, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(167, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(168, 'توصيلات (Connections)', 18, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(169, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(170, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(171, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(172, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(173, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(174, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(175, 'سجاد (Carpets)', 24, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(176, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(177, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(178, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(179, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(180, 'مناديل (Tissues)', 30, 0, NULL, NULL, 6, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(181, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(182, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(183, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(184, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(185, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(186, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(187, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(188, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(189, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(190, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(191, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(192, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(193, 'جنريتر (Generator)', 13, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(194, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(195, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(196, 'رواق (Covered porch)', 15, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(197, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(198, 'توصيلات (Connections)', 18, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(199, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(200, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(201, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(202, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(203, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(204, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(205, 'سجاد (Carpets)', 24, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(206, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(207, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(208, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(209, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(210, 'مناديل (Tissues)', 30, 0, NULL, NULL, 7, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(211, 'حمام كرفان (Caravan toilet)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(212, 'معطر اليدين (Hand freshener)', 8, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(213, 'شمعة + كريم لوشن (Candle + lotion)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(214, 'مزهرية ورد حمام + مناديل (Flower tissue + vase)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(215, 'معطر الجو (Toilet air freshener)', 9, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(216, 'حفرة البالوعة (Drain hole)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(217, 'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)', 11, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(218, 'محلول البالوعة (B.T Chemical)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(219, 'صابون غسيل اليد (Hand washing soap)', 7, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(220, 'تعبئة خزان الماء (Filling water tank)', 1, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(221, 'معطر المرحاض (Toilet freshener)', 10, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(222, 'البرونزي (Bronze)', 12, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(223, 'جنريتر (Generator)', 13, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(224, 'دبة بترول (Petrol tank)', 14, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(225, 'إضاءة صفراء (Yellow lighting)', 16, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(226, 'رواق (Covered porch)', 15, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(227, 'كشاف أبيض (White flashlight)', 17, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(228, 'توصيلات (Connections)', 18, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(229, 'ستاند المخيم (Camp stand)', 19, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(230, 'إضاءة ستراب (Strap lighting)', 20, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(231, 'إضاءة زينة 1 (Decorative lighting 1)', 21, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(232, 'إضاءة زينة 2 (Decorative lighting 2)', 22, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(233, 'عشب صناعي (Artificial grass)', 23, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(234, 'جلسة أرضية (Floor sitting)', 25, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(235, 'سجاد (Carpets)', 24, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(236, 'طقم جلسة (Sitting set)', 26, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(237, 'طاولة خشب كبير (Large wooden table)', 27, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(238, 'طاولة خشب صغيرة (Small wooden table)', 28, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(239, 'مزهرية ورد كبير (Large rose vase)', 29, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(240, 'مناديل (Tissues)', 30, 0, NULL, NULL, 8, 0, NULL, '2025-07-18 08:10:28', '2025-07-18 08:10:28', NULL),
(258, 'طاولات', 5, 5, 2, 'reports/z179FZGlgC6LiNWms3pap2k6bWWJdOQG6Qia8P1h.jpg', 15, 0, NULL, '2025-08-11 21:14:13', '2025-08-29 04:29:05', '1'),
(259, 'مزهرية ورد', 3, 3, 1, NULL, 15, 0, NULL, '2025-08-11 21:16:34', '2025-08-29 04:29:05', NULL),
(260, 'تجربة', 5, 5, 3, 'reports/2LSX5ZCMza2eglVenRuYzc1TbsgilODFc4tReWO4.png', 15, 0, NULL, '2025-08-11 21:16:34', '2025-08-29 04:29:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_stock`
--

CREATE TABLE `service_stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `not_completed_reason` varchar(255) DEFAULT NULL,
  `latest_activity` varchar(255) DEFAULT NULL,
  `required_qty` int(11) NOT NULL DEFAULT 0,
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_stock`
--

INSERT INTO `service_stock` (`id`, `service_id`, `stock_id`, `is_completed`, `not_completed_reason`, `latest_activity`, `required_qty`, `count`, `created_at`, `updated_at`) VALUES
(4, 1, 14, 1, NULL, 'increment', 1, 206666, NULL, '2025-08-27 23:38:41'),
(8, 1, 1, 1, NULL, NULL, 2, 10, NULL, '2025-08-22 07:30:58'),
(9, 15, 15, 1, NULL, 'increment', 1, 10, NULL, '2025-08-28 04:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name_ar', 'text', 'Icamp', NULL, NULL),
(2, 'app_name_en', 'text', 'icamp', NULL, NULL),
(3, 'firebase_key', 'text', 'AAAAi0Y_HnY:APA91bGeuHqUXsXiwWMDlJ-tenEOiKmRZ7pfifFPvI0XUzUiIRD6togg468docAR0gdTpY40Yvr50I8610Fdm9jG3RT-iYakNLthfVcxViBSJ6lIzt5gVh77Y_4VY3oqYyP64Svx6QxR', NULL, NULL),
(4, 'app_percentage', 'number', '10', NULL, NULL),
(5, 'logo', 'image', 'dashboard/assets/media/avatars/300-1.jpg', NULL, NULL),
(6, 'about_ar', 'textarea', 'Icamp', NULL, NULL),
(7, 'about_en', 'textarea', 'icamp', NULL, NULL),
(8, 'terms_ar', 'textarea', 'Icamp', NULL, NULL),
(9, 'terms_en', 'textarea', 'icamp', NULL, NULL),
(10, 'policy_ar', 'textarea', 'Icamp', NULL, NULL),
(11, 'policy_en', 'textarea', 'icamp', NULL, NULL),
(12, 'facebook', 'link', 'https://www.facebook.com/', NULL, NULL),
(13, 'twitter', 'link', 'https://www.twitter.com/', NULL, NULL),
(14, 'instagram', 'link', 'https://www.instagram.com/', NULL, NULL),
(15, 'slogan_en', 'text', 'icamp', NULL, NULL),
(16, 'slogan_ar', 'text', 'Icamp', NULL, NULL),
(17, 'email', 'text', 'admin@gmail.com', NULL, NULL),
(18, 'phone', 'text', '1346546464', NULL, NULL),
(19, 'address', 'text', 'العنوان', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `percentage` varchar(255) DEFAULT NULL,
  `selling_price` decimal(8,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `name`, `description`, `image`, `price`, `percentage`, `selling_price`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 'saepe', 'Debitis nesciunt aut voluptates voluptatem.', NULL, 12.32, NULL, 20.00, 18, '2025-07-18 08:10:28', '2025-08-26 12:44:20'),
(2, 'pariatur', 'Ea hic repudiandae architecto rerum.', NULL, 40.90, NULL, NULL, 62, '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(3, 'odit', 'Aspernatur optio dignissimos quam nemo omnis et minima.', NULL, 54.97, NULL, NULL, 7, '2025-07-18 08:10:28', '2025-08-26 01:02:25'),
(4, 'qui', 'Quisquam sunt optio ut quod qui ducimus.', NULL, 98.49, NULL, NULL, 0, '2025-07-18 08:10:28', '2025-08-10 00:41:44'),
(5, 'et', 'Quia vel vel tempore quia excepturi.', NULL, 89.24, NULL, NULL, 83, '2025-07-18 08:10:28', '2025-08-26 01:02:44'),
(6, 'aperiam', 'Quibusdam magni nobis aliquid id id quasi id.', NULL, 43.77, NULL, NULL, 14, '2025-07-18 08:10:28', '2025-08-10 18:04:21'),
(7, 'non', 'Libero aliquam ullam quas omnis autem dicta neque voluptas.', NULL, 32.39, NULL, NULL, 66, '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(8, 'suscipit', 'Blanditiis aperiam necessitatibus explicabo at perferendis suscipit eaque.', NULL, 19.98, NULL, NULL, 32, '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(9, 'qui', 'At eos maiores dignissimos iure.', NULL, 7.99, NULL, NULL, 49, '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(10, 'consequatur', 'Quaerat est recusandae recusandae nobis aliquam excepturi.', NULL, 89.29, NULL, NULL, 86, '2025-07-18 08:10:28', '2025-07-18 08:10:28'),
(11, 'قلم', NULL, NULL, 400.00, NULL, NULL, 1000, '2025-07-29 14:44:59', '2025-08-04 00:23:59'),
(12, 'دبة', NULL, NULL, 200.00, NULL, NULL, 40, '2025-08-06 01:40:20', '2025-08-06 01:40:20'),
(13, 'بترول', NULL, NULL, 200.00, '20', NULL, 9, '2025-08-06 02:34:55', '2025-08-19 10:19:23'),
(14, 'زيت', NULL, NULL, 100.00, NULL, NULL, 101, '2025-08-08 01:47:28', '2025-08-27 23:38:41'),
(15, 'كبريت', NULL, 'images/woOjoT78XW6wP8dxbgBGAsNU0wQpkcnZm5Kl4Xsw.jpg', 1300.00, NULL, 10.00, 577, '2025-08-10 01:17:17', '2025-08-28 04:29:43'),
(16, 'غاز تجربة النظام 10-8-2025', NULL, 'images/C9qHdt7l25UjQYkGht0OW3BgPWIXIzqKZa3ApewS.png', 500.00, NULL, 10.00, 998, '2025-08-10 18:08:10', '2025-08-10 18:11:53'),
(17, 'صابون 10-8-2025', NULL, 'images/xtkh2gRQJ9d43IcBgPREHi4WoaNg1sCesoQN0QLD.png', 500.00, '100', 10.00, 1000, '2025-08-10 19:34:21', '2025-08-10 19:34:21'),
(18, 'maxime', 'Ut fugit odit omnis quos aut qui rerum.', NULL, 5.90, NULL, NULL, 47, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(19, 'dolorem', 'Reprehenderit et nostrum optio qui nostrum ut et.', NULL, 34.12, NULL, NULL, 66, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(20, 'in', 'Maxime nulla minus velit dolores deserunt sit dicta dolores.', NULL, 80.69, NULL, NULL, 53, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(21, 'quam', 'Et autem ab tenetur perferendis possimus vero assumenda doloremque.', NULL, 82.89, NULL, NULL, 31, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(22, 'neque', 'Animi illo est molestiae dolores deserunt.', NULL, 280.64, NULL, NULL, 61, '2025-08-20 08:11:34', '2025-08-22 07:24:41'),
(23, 'iusto', 'Ratione ratione voluptas vel tempora expedita.', NULL, 45.08, NULL, NULL, 75, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(24, 'magnam', 'Eligendi reiciendis quis beatae natus illo consequatur veritatis eos.', NULL, 83.73, NULL, NULL, 86, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(25, 'animi', 'Quidem in voluptatem reprehenderit aut doloremque.', NULL, 63.70, NULL, NULL, 73, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(26, 'laudantium', 'Animi et recusandae molestiae occaecati ut.', NULL, 89.27, NULL, NULL, 49, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(27, 'maiores', 'Vel quo eligendi corporis nobis modi sit.', NULL, 46.46, NULL, NULL, 34, '2025-08-20 08:11:34', '2025-08-20 08:11:34'),
(28, 'تجربة 24-8-2025', NULL, NULL, 500.00, NULL, 5.00, 8, '2025-08-24 22:11:24', '2025-08-24 22:21:18'),
(29, 'تجربة 24-8-2025 نسبة مئوية', NULL, NULL, 1000.00, '100', 100.00, 5, '2025-08-24 23:11:32', '2025-08-24 23:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `stock_activities`
--

CREATE TABLE `stock_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_quantities`
--

CREATE TABLE `stock_quantities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_quantities`
--

INSERT INTO `stock_quantities` (`id`, `stock_id`, `quantity`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 11, 500, 100.00, NULL, '2025-08-04 00:23:59', '2025-08-04 00:23:59'),
(2, 15, 400, 1000.00, NULL, '2025-08-10 01:17:49', '2025-08-10 01:17:49'),
(3, 22, 10, 200.00, NULL, '2025-08-22 07:24:41', '2025-08-22 07:24:41');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `settings` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `description`, `settings`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'استبيان رضا العملاء', 'يساعدنا هذا الاستبيان في تحسين خدماتنا وتقديم تجربة أفضل لك', '{\"smtp_host\":\"smtp.gmail.com\",\"smtp_port\":\"587\",\"smtp_username\":\"elhareth0609@gmail.com\",\"smtp_password\":\"123456789\",\"smtp_encryption\":\"tls\",\"from_email\":\"elhareth0609@gmail.com\",\"from_name\":\"documents\",\"days_after_completion\":\"0\",\"send_time\":\"19:19\",\"enabled\":1}', '2025-08-19 14:23:36', '2025-08-20 02:19:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `survey_answers`
--

CREATE TABLE `survey_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_response_id` bigint(20) UNSIGNED NOT NULL,
  `survey_question_id` bigint(20) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL,
  `answer_option` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answer_option`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_answers`
--

INSERT INTO `survey_answers` (`id`, `survey_response_id`, `survey_question_id`, `answer_text`, `answer_option`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 2, 17, '3', NULL, '2025-08-21 13:55:16', '2025-08-21 13:55:16', NULL),
(5, 2, 16, '4', NULL, '2025-08-21 13:55:16', '2025-08-21 13:55:16', NULL),
(4, 2, 15, '2', NULL, '2025-08-21 13:55:16', '2025-08-21 13:55:16', NULL),
(7, 3, 15, '5', NULL, '2025-08-21 13:57:13', '2025-08-21 13:57:13', NULL),
(8, 3, 16, '5', NULL, '2025-08-21 13:57:13', '2025-08-21 13:57:13', NULL),
(9, 3, 17, '5', NULL, '2025-08-21 13:57:13', '2025-08-21 13:57:13', NULL),
(10, 4, 15, '1', NULL, '2025-08-21 14:00:08', '2025-08-21 14:00:08', NULL),
(11, 4, 16, '1', NULL, '2025-08-21 14:00:08', '2025-08-21 14:00:08', NULL),
(12, 4, 17, '1', NULL, '2025-08-21 14:00:08', '2025-08-21 14:00:08', NULL),
(13, 5, 15, '1', NULL, '2025-08-21 14:01:36', '2025-08-21 14:01:36', NULL),
(14, 5, 16, '1', NULL, '2025-08-21 14:01:36', '2025-08-21 14:01:36', NULL),
(15, 5, 17, '1', NULL, '2025-08-21 14:01:36', '2025-08-21 14:01:36', NULL),
(16, 6, 15, '5', NULL, '2025-08-21 17:22:31', '2025-08-21 17:22:31', NULL),
(17, 6, 16, '5', NULL, '2025-08-21 17:22:31', '2025-08-21 17:22:31', NULL),
(18, 6, 17, '5', NULL, '2025-08-21 17:22:31', '2025-08-21 17:22:31', NULL),
(19, 7, 15, '2', NULL, '2025-08-22 08:24:20', '2025-08-22 08:24:20', NULL),
(20, 7, 16, '4', NULL, '2025-08-22 08:24:20', '2025-08-22 08:24:20', NULL),
(21, 7, 17, '2', NULL, '2025-08-22 08:24:20', '2025-08-22 08:24:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `survey_email_logs`
--

CREATE TABLE `survey_email_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT '2025-08-20 08:45:55',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_email_logs`
--

INSERT INTO `survey_email_logs` (`id`, `order_id`, `sent_at`, `created_at`, `updated_at`) VALUES
(15, 6, '2025-08-20 09:20:44', '2025-08-20 09:20:44', '2025-08-20 09:20:44'),
(14, 4, '2025-08-20 09:20:43', '2025-08-20 09:20:43', '2025-08-20 09:20:43'),
(13, 2, '2025-08-20 09:20:42', '2025-08-20 09:20:42', '2025-08-20 09:20:42'),
(12, 1, '2025-08-20 09:20:38', '2025-08-20 09:20:38', '2025-08-20 09:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `question_type` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `placeholder` varchar(255) DEFAULT NULL,
  `help_text` text DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`id`, `survey_id`, `question_text`, `question_type`, `order`, `hidden`, `placeholder`, `help_text`, `error_message`, `options`, `settings`, `created_at`, `updated_at`, `deleted_at`) VALUES
(16, 1, '{\"ar\":\"\\u0643\\u0645 \\u062a\\u0642\\u064a\\u0645 \\u0645\\u0633\\u062a\\u0648\\u0649 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645 \\u0644\\u062f\\u064a\\u0646\\u0627\\u061f\",\"en\":\"Star Rating\"}', 'stars', 1, 0, '{\"ar\":null,\"en\":null}', '{\"ar\":null,\"en\":null}', NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:52:10', '2025-08-21 17:18:40', NULL),
(17, 1, '{\"ar\":\"\\u0645\\u0627 \\u0647\\u0648 \\u062a\\u0642\\u064a\\u064a\\u0643 \\u0644\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0645\\u0648\\u0638\\u064a\\u0641\\u0646\\u0627 \\u061f\",\"en\":\"Star Rating\"}', 'stars', 2, 0, '{\"ar\":null,\"en\":null}', '{\"ar\":null,\"en\":null}', NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:52:10', '2025-08-21 17:18:40', NULL),
(14, 1, '{\"ar\":\"\\u0645\\u0627 \\u0647\\u0648 \\u062a\\u0642\\u064a\\u064a\\u0643 \\u0644\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0645\\u0648\\u0638\\u064a\\u0641\\u0646\\u0627 \\u061f\",\"en\":\"Star Rating\"}', 'stars', 2, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:50:18', '2025-08-21 13:52:10', '2025-08-21 13:52:10'),
(13, 1, '{\"ar\":\"\\u0643\\u0645 \\u062a\\u0642\\u064a\\u0645 \\u0645\\u0633\\u062a\\u0648\\u0649 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645 \\u0644\\u062f\\u064a\\u0646\\u0627\\u061f\",\"en\":\"Star Rating\"}', 'stars', 1, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:50:18', '2025-08-21 13:52:10', '2025-08-21 13:52:10'),
(15, 1, '{\"ar\":\"\\u0645\\u0627 \\u0631\\u0623\\u064a\\u0643 \\u0628\\u062c\\u0648\\u062f\\u0629 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0642\\u062f\\u0645\\u0629\\u061f\",\"en\":\"Star Rating\"}', 'stars', 0, 0, '{\"ar\":null,\"en\":null}', '{\"ar\":null,\"en\":null}', NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:52:10', '2025-08-21 17:18:40', NULL),
(12, 1, '{\"ar\":\"\\u0645\\u0627 \\u0631\\u0623\\u064a\\u0643 \\u0628\\u062c\\u0648\\u062f\\u0629 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0642\\u062f\\u0645\\u0629\\u061f\",\"en\":\"Star Rating\"}', 'stars', 0, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:50:18', '2025-08-21 13:52:10', '2025-08-21 13:52:10'),
(11, 1, '{\"ar\":\"\\u0645\\u0627 \\u0647\\u0648 \\u062a\\u0642\\u064a\\u064a\\u0643 \\u0644\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0645\\u0648\\u0638\\u064a\\u0641\\u0646\\u0627 \\u061f\",\"en\":\"Star Rating\"}', 'stars', 2, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:46:51', '2025-08-21 13:50:18', '2025-08-21 13:50:18'),
(10, 1, '{\"ar\":\"\\u0643\\u0645 \\u062a\\u0642\\u064a\\u0645 \\u0645\\u0633\\u062a\\u0648\\u0649 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645 \\u0644\\u062f\\u064a\\u0646\\u0627\\u061f\",\"en\":\"Star Rating\"}', 'stars', 1, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:46:51', '2025-08-21 13:50:18', '2025-08-21 13:50:18'),
(9, 1, '{\"ar\":\"\\u0645\\u0627 \\u0631\\u0623\\u064a\\u0643 \\u0628\\u062c\\u0648\\u062f\\u0629 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0642\\u062f\\u0645\\u0629\\u061f\",\"en\":\"Star Rating\"}', 'stars', 0, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":true,\"points\":\"5\"}', '2025-08-21 13:46:51', '2025-08-21 13:50:18', '2025-08-21 13:50:18'),
(18, 1, '{\"ar\":\"\\u0645\\u0646\\u0637\\u0642\\u0629 \\u0646\\u0635\",\"en\":\"Textarea Question\"}', 'textarea', 3, 0, '{\"ar\":null,\"en\":null}', NULL, NULL, NULL, '{\"required\":false}', '2025-08-22 02:56:00', '2025-08-22 02:56:05', '2025-08-22 02:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reservation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`id`, `survey_id`, `user_id`, `reservation_id`, `ip_address`, `user_agent`, `submitted_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 1, 11, '5.195.235.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-21 13:55:16', '2025-08-21 13:55:16', '2025-08-21 13:55:16', NULL),
(3, 1, 1, 10, '5.195.235.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-21 13:57:13', '2025-08-21 13:57:13', '2025-08-21 13:57:13', NULL),
(4, 1, 1, 3, '5.195.235.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-21 14:00:08', '2025-08-21 14:00:08', '2025-08-21 14:00:08', NULL),
(5, 1, 1, 6, '5.195.235.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-21 14:01:36', '2025-08-21 14:01:36', '2025-08-21 14:01:36', NULL),
(6, 1, 1, 1, '197.205.54.69', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-21 17:22:29', '2025-08-21 17:22:29', '2025-08-21 17:22:29', NULL),
(7, 1, 1, 11, '197.205.93.210', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-22 08:24:20', '2025-08-22 08:24:20', '2025-08-22 08:24:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_m_s`
--

CREATE TABLE `s_m_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `s_m_s`
--

INSERT INTO `s_m_s` (`id`, `name`, `key`, `sender_name`, `user_name`, `password`, `active`, `created_at`, `updated_at`) VALUES
(1, 'باقة يمامة', 'yamamah', 'sender_name', 'user_name', '123456', 1, NULL, NULL),
(2, 'باقة فور جوالي', '4jawaly', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(3, 'باقة gateway', 'gateway', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(4, 'باقة hisms', 'hisms', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(5, 'باقة مسجات', 'msegat', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(6, 'باقة oursms', 'oursms', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(7, 'باقة unifonic', 'unifonic', 'sender_name', 'user_name', '123456', 0, NULL, NULL),
(8, 'باقة زين', 'zain', 'sender_name', 'user_name', '123456', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `status` enum('pending','in_progress','completed','failed') NOT NULL DEFAULT 'pending',
  `failure_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo_attachment` varchar(255) DEFAULT NULL,
  `video_attachment` varchar(255) DEFAULT NULL,
  `audio_attachment` varchar(255) DEFAULT NULL,
  `task_type_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `created_by`, `due_date`, `priority`, `status`, `failure_reason`, `created_at`, `updated_at`, `photo_attachment`, `video_attachment`, `audio_attachment`, `task_type_id`) VALUES
(1, 'النظافه', NULL, 1, 1, '2025-08-13', 'medium', 'in_progress', NULL, '2025-08-14 02:18:49', '2025-08-18 01:09:32', 'task_attachments/photos/SYRkOrFJGg04MVwPG66UA1iOCqsKAqSmk6myUdgE.jpg', 'task_attachments/videos/UYcOfj9vLYBnfYuUKbro24n8qOocoaQehE5QVWj1.mp4', 'task_attachments/audio/91Hb35AuANIE2TNd6nycFvHhiw9Qpt865cYpYm6P.mp4', NULL),
(2, 'تجربة', 'تجربة', 1, 1, '2025-08-16', 'high', 'pending', NULL, '2025-08-15 18:49:26', '2025-08-15 18:49:26', NULL, NULL, NULL, NULL),
(3, 'Uu', NULL, 1, 1, '2025-08-16', 'high', 'pending', NULL, '2025-08-17 01:24:52', '2025-08-18 01:10:04', NULL, NULL, 'task_attachments/audio/9DW2IsqsBBhBzVxXXoeFZyuFOOFOv8LdeW310Hc2.mp4', NULL),
(4, 'Test', NULL, 1, 1, '2025-08-17', 'medium', 'pending', NULL, '2025-08-18 01:06:22', '2025-08-18 01:25:28', NULL, NULL, NULL, NULL),
(5, 'Test 2', NULL, 1, 1, '2025-08-17', 'medium', 'pending', NULL, '2025-08-18 01:37:59', '2025-08-18 01:37:59', NULL, NULL, NULL, NULL),
(6, 'Teat', 'Test', 1, 1, '2025-08-18', 'medium', 'pending', NULL, '2025-08-18 19:08:58', '2025-08-18 19:09:43', NULL, NULL, 'task_attachments/audio/NzYEHIPHrE44OAAuIbcuxfHnV6pIOO48WuuCnCCF.mp4', NULL),
(8, 'JJJ', 'JJJ', 1, 1, '2025-08-28', 'medium', 'pending', NULL, '2025-08-28 15:58:48', '2025-08-28 17:16:52', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--

CREATE TABLE `task_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test desc', 'active', '2025-08-28 17:16:16', '2025-08-28 17:16:16');

-- --------------------------------------------------------

--
-- Table structure for table `terms_sittngs`
--

CREATE TABLE `terms_sittngs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `terms` longtext DEFAULT NULL,
  `commercial_license` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_sittngs`
--

INSERT INTO `terms_sittngs` (`id`, `logo`, `description`, `terms`, `commercial_license`, `company_name`, `order_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 'تجربة 24-8-2025تجربة 24-8-2025تجربة 24-8-2025', NULL, NULL, NULL, '2025-08-10 08:53:04', '2025-08-26 16:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `receiver_id`, `customer_id`, `order_id`, `date`, `amount`, `description`, `source`, `account_id`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, '2025-08-10', 25.00, NULL, NULL, 3, '2025-08-11 01:48:07', '2025-08-11 01:48:07'),
(2, NULL, NULL, NULL, '2025-08-10', 250.00, NULL, NULL, 4, '2025-08-11 01:55:41', '2025-08-11 01:55:41'),
(3, NULL, NULL, NULL, '2025-08-10', 25.00, NULL, NULL, 3, '2025-08-11 02:03:11', '2025-08-11 02:03:11'),
(4, NULL, NULL, NULL, '2025-08-11', 5100.00, NULL, NULL, 3, '2025-08-11 17:13:47', '2025-08-11 17:13:47'),
(5, NULL, NULL, NULL, '2025-08-11', 5100.00, NULL, NULL, 3, '2025-08-11 17:14:22', '2025-08-11 17:14:22'),
(6, NULL, NULL, NULL, '2025-08-24', 320.00, NULL, NULL, 3, '2025-08-25 04:09:41', '2025-08-25 04:09:41'),
(7, NULL, NULL, NULL, '2025-08-25', 100.00, NULL, NULL, 7, '2025-08-26 02:20:23', '2025-08-26 02:20:23'),
(8, NULL, NULL, NULL, '2025-08-26', 10.00, NULL, NULL, 1, '2025-08-26 17:17:04', '2025-08-26 17:17:04'),
(9, NULL, NULL, NULL, '2025-08-26', 4.00, NULL, NULL, 2, '2025-08-26 17:17:32', '2025-08-26 17:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) NOT NULL DEFAULT '+966',
  `phone` varchar(255) DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `user_type` bigint(20) UNSIGNED NOT NULL DEFAULT 2,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_notify` tinyint(1) NOT NULL DEFAULT 1,
  `lang` varchar(255) NOT NULL DEFAULT 'ar',
  `code` varchar(10) DEFAULT NULL,
  `code_expire` timestamp NULL DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(10,8) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `wallet_balance` decimal(9,2) NOT NULL DEFAULT 0.00,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `identity_image` varchar(255) DEFAULT NULL,
  `front_car_image` varchar(255) DEFAULT NULL,
  `back_car_image` varchar(255) DEFAULT NULL,
  `car_license` varchar(255) DEFAULT NULL,
  `driver_license` varchar(255) DEFAULT NULL,
  `car_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `email`, `country_code`, `phone`, `is_email_verified`, `is_phone_verified`, `password`, `job`, `remember_token`, `user_type`, `image`, `is_active`, `is_blocked`, `is_approved`, `is_notify`, `lang`, `code`, `code_expire`, `lat`, `lng`, `address`, `wallet_balance`, `city_id`, `identity_image`, `front_car_image`, `back_car_image`, `car_license`, `driver_license`, `car_image`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', 'admin@gmail.com', '+966', NULL, 1, 0, '$2y$10$MEC8MFXm6tA3WxHcXIft4OWkSLrCyj7WVHrEdsEwgWUul42BWNhIi', NULL, NULL, 1, NULL, 1, 0, 0, 1, 'ar', NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(2, 'manager', 'manager', 'manager', 'manager@gmail.com', '+966', NULL, 1, 0, '$2y$10$f7CjIo34LUlswLoaNEE6cuPYiOC4mAUTzbcnNthbuMTgKEWX/9Ufy', NULL, NULL, 2, NULL, 1, 0, 0, 1, 'ar', NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 08:17:51', '2025-08-20 08:17:51'),
(3, 'aa', NULL, NULL, 'aa20@hotmail.com', '+966', '0503670808', 0, 0, '$2y$10$muR/Qv0qz6yYgCmLwbboReJkkHX8qBwW9IEClIkfniQYKtoYRpk.q', NULL, NULL, 1, NULL, 1, 0, 0, 1, 'ar', NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-14 23:51:08', '2025-08-14 23:52:08'),
(4, 'محمد سيف', NULL, NULL, 'mm@hotmail.com', '+966', '05036787878', 0, 0, '$2y$10$fvBgzZDT1Q3MBLcJ1lvvVu90KKaPUzTyAjL.En/jYLGgas4uGATjK', NULL, NULL, 1, NULL, 1, 0, 0, 1, 'ar', NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 18:22:12', '2025-08-15 18:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL),
(3, 'delegate', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `violation_type_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `violation_date` date DEFAULT NULL,
  `violation_time` time DEFAULT NULL,
  `violation_place` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `employee_justification` text DEFAULT NULL,
  `action_taken` enum('warning','allowance','deduction') NOT NULL DEFAULT 'warning',
  `deduction_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `violation_type_id`, `employee_id`, `violation_date`, `violation_time`, `violation_place`, `photo_path`, `created_by`, `employee_justification`, `action_taken`, `deduction_amount`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, NULL, NULL, NULL, NULL, 1, NULL, 'warning', NULL, NULL, '2025-08-14 23:18:42', '2025-08-14 23:18:42', NULL),
(2, 2, 4, NULL, NULL, NULL, NULL, 1, NULL, 'deduction', 500.00, NULL, '2025-08-15 18:54:50', '2025-08-15 18:55:00', NULL),
(3, 3, 1, NULL, NULL, NULL, NULL, 1, NULL, 'warning', NULL, NULL, '2025-08-20 00:58:54', '2025-08-20 00:58:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `violation_types`
--

CREATE TABLE `violation_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violation_types`
--

INSERT INTO `violation_types` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'تجربة', NULL, 1, '2025-08-14 23:18:24', '2025-08-14 23:18:24', NULL),
(2, 'عدم الإهتمام بالنظافة الشخصية', NULL, 1, '2025-08-15 18:54:31', '2025-08-15 18:54:31', NULL),
(3, 'عدم الالتزام بالتعليمات', NULL, 1, '2025-08-20 00:58:17', '2025-08-20 00:58:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`),
  ADD KEY `answers_user_id_foreign` (`user_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_accounts_account_number_unique` (`account_number`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `camp_reports`
--
ALTER TABLE `camp_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `camp_reports_service_id_foreign` (`service_id`),
  ADD KEY `camp_reports_created_by_foreign` (`created_by`);

--
-- Indexes for table `camp_report_items`
--
ALTER TABLE `camp_report_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `camp_report_items_camp_report_id_foreign` (`camp_report_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_phone_unique` (`phone`);

--
-- Indexes for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_reports_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_directories`
--
ALTER TABLE `equipment_directories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_directories_created_by_foreign` (`created_by`);

--
-- Indexes for table `equipment_directory_items`
--
ALTER TABLE `equipment_directory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_directory_items_directory_id_foreign` (`directory_id`),
  ADD KEY `equipment_directory_items_created_by_foreign` (`created_by`);

--
-- Indexes for table `equipment_directory_media`
--
ALTER TABLE `equipment_directory_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_directory_media_item_id_foreign` (`item_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_item_id_foreign` (`expense_item_id`),
  ADD KEY `expenses_account_id_foreign` (`account_id`),
  ADD KEY `expenses_order_id_foreign` (`order_id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_payments`
--
ALTER TABLE `general_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `general_payments_order_id_foreign` (`order_id`),
  ADD KEY `general_payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `initial_pages`
--
ALTER TABLE `initial_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `initial_pages_order_unique` (`order`);

--
-- Indexes for table `invoice_links`
--
ALTER TABLE `invoice_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_links_link_unique` (`link`),
  ADD KEY `invoice_links_order_id_foreign` (`order_id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meetings_meeting_number_unique` (`meeting_number`) USING HASH,
  ADD KEY `meetings_created_by_foreign` (`created_by`),
  ADD KEY `meetings_location_id_foreign` (`location_id`);

--
-- Indexes for table `meeting_attendees`
--
ALTER TABLE `meeting_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_attendees_meeting_id_foreign` (`meeting_id`),
  ADD KEY `meeting_attendees_user_id_foreign` (`user_id`);

--
-- Indexes for table `meeting_locations`
--
ALTER TABLE `meeting_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_topics`
--
ALTER TABLE `meeting_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_topics_meeting_id_foreign` (`meeting_id`),
  ADD KEY `meeting_topics_assigned_to_foreign` (`assigned_to`),
  ADD KEY `meeting_topics_task_id_foreign` (`task_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notices_customer_id_foreign` (`customer_id`),
  ADD KEY `notices_order_id_foreign` (`order_id`),
  ADD KEY `notices_created_by_foreign` (`created_by`),
  ADD KEY `notices_notice_type_id_foreign` (`notice_type_id`);

--
-- Indexes for table `notice_types`
--
ALTER TABLE `notice_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `order_addon`
--
ALTER TABLE `order_addon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_addon_order_id_foreign` (`order_id`),
  ADD KEY `order_addon_addon_id_foreign` (`addon_id`),
  ADD KEY `order_addon_account_id_foreign` (`account_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_stock_id_foreign` (`stock_id`),
  ADD KEY `order_items_account_id_foreign` (`account_id`);

--
-- Indexes for table `order_rates`
--
ALTER TABLE `order_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_rates_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_reports`
--
ALTER TABLE `order_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_reports_order_id_foreign` (`order_id`),
  ADD KEY `order_reports_service_report_id_foreign` (`service_report_id`);

--
-- Indexes for table `order_service`
--
ALTER TABLE `order_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_service_order_id_foreign` (`order_id`),
  ADD KEY `order_service_service_id_foreign` (`service_id`);

--
-- Indexes for table `order_stock`
--
ALTER TABLE `order_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_stock_order_id_foreign` (`order_id`),
  ADD KEY `order_stock_stock_id_foreign` (`stock_id`),
  ADD KEY `order_stock_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `payment_links`
--
ALTER TABLE `payment_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_links_checkout_id_unique` (`checkout_id`) USING HASH,
  ADD KEY `payment_links_order_id_foreign` (`order_id`),
  ADD KEY `payment_links_status_created_at_index` (`status`,`created_at`),
  ADD KEY `payment_links_customer_id_status_index` (`customer_id`,`status`),
  ADD KEY `payment_links_status_last_status_check_index` (`status`,`last_status_check`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pre_login_images`
--
ALTER TABLE `pre_login_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_login_images_order_id_foreign` (`order_id`);

--
-- Indexes for table `pre_logout_images`
--
ALTER TABLE `pre_logout_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_logout_images_order_id_foreign` (`order_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_user_id_foreign` (`user_id`);

--
-- Indexes for table `registrationforms`
--
ALTER TABLE `registrationforms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registrationforms_request_code_unique` (`request_code`),
  ADD KEY `registrationforms_service_id_request_code_index` (`service_id`,`request_code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_reports`
--
ALTER TABLE `service_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_reports_service_id_foreign` (`service_id`);

--
-- Indexes for table `service_stock`
--
ALTER TABLE `service_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_stock_service_id_foreign` (`service_id`),
  ADD KEY `service_stock_stock_id_foreign` (`stock_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_activities`
--
ALTER TABLE `stock_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_activities_stock_id_foreign` (`stock_id`),
  ADD KEY `stock_activities_order_id_foreign` (`order_id`);

--
-- Indexes for table `stock_quantities`
--
ALTER TABLE `stock_quantities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_quantities_stock_id_foreign` (`stock_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_answers_survey_response_id_foreign` (`survey_response_id`),
  ADD KEY `survey_answers_survey_question_id_foreign` (`survey_question_id`);

--
-- Indexes for table `survey_email_logs`
--
ALTER TABLE `survey_email_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `survey_email_logs_order_id_unique` (`order_id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_questions_survey_id_foreign` (`survey_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_responses_survey_id_foreign` (`survey_id`),
  ADD KEY `survey_responses_user_id_foreign` (`user_id`),
  ADD KEY `survey_responses_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `s_m_s`
--
ALTER TABLE `s_m_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_task_type_id_foreign` (`task_type_id`);

--
-- Indexes for table `task_types`
--
ALTER TABLE `task_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_sittngs`
--
ALTER TABLE `terms_sittngs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `terms_sittngs_order_id_foreign` (`order_id`),
  ADD KEY `terms_sittngs_user_id_foreign` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_account_id_foreign` (`account_id`),
  ADD KEY `transactions_receiver_id_foreign` (`receiver_id`),
  ADD KEY `transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `transactions_order_id_foreign` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_city_id_foreign` (`city_id`),
  ADD KEY `users_user_type_index` (`user_type`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `violations_violation_type_id_foreign` (`violation_type_id`),
  ADD KEY `violations_employee_id_foreign` (`employee_id`),
  ADD KEY `violations_created_by_foreign` (`created_by`);

--
-- Indexes for table `violation_types`
--
ALTER TABLE `violation_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `camp_reports`
--
ALTER TABLE `camp_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `camp_report_items`
--
ALTER TABLE `camp_report_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_directories`
--
ALTER TABLE `equipment_directories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment_directory_items`
--
ALTER TABLE `equipment_directory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment_directory_media`
--
ALTER TABLE `equipment_directory_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_payments`
--
ALTER TABLE `general_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `initial_pages`
--
ALTER TABLE `initial_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_links`
--
ALTER TABLE `invoice_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `meeting_attendees`
--
ALTER TABLE `meeting_attendees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meeting_locations`
--
ALTER TABLE `meeting_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meeting_topics`
--
ALTER TABLE `meeting_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notice_types`
--
ALTER TABLE `notice_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_addon`
--
ALTER TABLE `order_addon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_rates`
--
ALTER TABLE `order_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_reports`
--
ALTER TABLE `order_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `order_service`
--
ALTER TABLE `order_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `order_stock`
--
ALTER TABLE `order_stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `payment_links`
--
ALTER TABLE `payment_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_login_images`
--
ALTER TABLE `pre_login_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pre_logout_images`
--
ALTER TABLE `pre_logout_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registrationforms`
--
ALTER TABLE `registrationforms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `service_reports`
--
ALTER TABLE `service_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT for table `service_stock`
--
ALTER TABLE `service_stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `stock_activities`
--
ALTER TABLE `stock_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_quantities`
--
ALTER TABLE `stock_quantities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `survey_answers`
--
ALTER TABLE `survey_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `survey_email_logs`
--
ALTER TABLE `survey_email_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `s_m_s`
--
ALTER TABLE `s_m_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `task_types`
--
ALTER TABLE `task_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terms_sittngs`
--
ALTER TABLE `terms_sittngs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `violation_types`
--
ALTER TABLE `violation_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expense_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `general_payments`
--
ALTER TABLE `general_payments`
  ADD CONSTRAINT `general_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `general_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_links`
--
ALTER TABLE `invoice_links`
  ADD CONSTRAINT `invoice_links_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_addon`
--
ALTER TABLE `order_addon`
  ADD CONSTRAINT `order_addon_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_addon_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_addon_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_rates`
--
ALTER TABLE `order_rates`
  ADD CONSTRAINT `order_rates_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_reports`
--
ALTER TABLE `order_reports`
  ADD CONSTRAINT `order_reports_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_reports_service_report_id_foreign` FOREIGN KEY (`service_report_id`) REFERENCES `service_reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_service`
--
ALTER TABLE `order_service`
  ADD CONSTRAINT `order_service_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_stock`
--
ALTER TABLE `order_stock`
  ADD CONSTRAINT `order_stock_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_stock_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_stock_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_login_images`
--
ALTER TABLE `pre_login_images`
  ADD CONSTRAINT `pre_login_images_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_logout_images`
--
ALTER TABLE `pre_logout_images`
  ADD CONSTRAINT `pre_logout_images_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_reports`
--
ALTER TABLE `service_reports`
  ADD CONSTRAINT `service_reports_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_stock`
--
ALTER TABLE `service_stock`
  ADD CONSTRAINT `service_stock_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_stock_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_activities`
--
ALTER TABLE `stock_activities`
  ADD CONSTRAINT `stock_activities_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_activities_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_quantities`
--
ALTER TABLE `stock_quantities`
  ADD CONSTRAINT `stock_quantities_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `terms_sittngs`
--
ALTER TABLE `terms_sittngs`
  ADD CONSTRAINT `terms_sittngs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `terms_sittngs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_user_type_foreign` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
