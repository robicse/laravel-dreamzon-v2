-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2020 at 01:31 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dreamzon_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `dues`
--

CREATE TABLE `dues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` enum('cash','online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `paid_amount` double(8,2) NOT NULL,
  `due_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dues`
--

INSERT INTO `dues` (`id`, `invoice_no`, `ref_id`, `user_id`, `store_id`, `party_id`, `payment_type`, `total_amount`, `paid_amount`, `due_amount`, `created_at`, `updated_at`) VALUES
(1, '1000', 6, 1, 1, 2, 'cash', 1200.00, 1000.00, 200.00, '2020-09-12 23:32:47', '2020-09-12 23:32:47'),
(2, '1001', 7, 1, 1, 2, 'cash', 350.00, 300.00, 50.00, '2020-09-12 23:35:15', '2020-09-12 23:35:15'),
(3, '1002', 9, 1, 1, 2, 'cash', 230.00, 200.00, 30.00, '2020-09-12 23:35:15', '2020-09-12 23:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_17_064316_create_permission_tables', 1),
(4, '2020_08_23_175830_create_stores_table', 2),
(5, '2020_09_08_072444_create_product_categories_table', 3),
(6, '2020_09_08_072634_create_product_sub_categories_table', 4),
(7, '2020_09_08_072804_create_product_brands_table', 5),
(8, '2020_09_08_072952_create_products_table', 6),
(9, '2020_09_08_073143_create_parties_table', 7),
(10, '2020_09_08_073313_create_product_purchases_table', 8),
(11, '2020_09_08_073602_create_product_purchase_details_table', 9),
(12, '2020_09_08_073736_create_transactions_table', 10),
(13, '2020_09_08_074003_create_stocks_table', 11),
(14, '2020_09_08_074332_create_product_sales_table', 12),
(15, '2020_09_08_074534_create_product_sale_details_table', 13),
(16, '2020_09_08_074822_create_product_sale_returns_table', 14),
(17, '2020_09_08_075008_create_product_sale_return_details_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(2, 'App\\User', 3),
(2, 'App\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('supplier','customer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `type`, `name`, `slug`, `phone`, `email`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'supplier', 'Rasel', 'rasel', '01700000000', 'rasel@gmail.com', 'kallyanpur', 1, '2020-09-08 02:01:00', '2020-09-08 02:01:00'),
(2, 'customer', 'Rakib', 'rakib', '01700000001', 'rakib@gmail.com', 'kallyanpur', 1, '2020-09-08 02:01:20', '2020-09-08 02:01:20'),
(3, 'customer', 'Joy', 'joy', '01700000001', 'joy@gmail.com', 'aaa', 1, '2020-09-12 05:13:52', '2020-09-12 05:13:52'),
(4, 'customer', 'moin', 'moin', '01722222222', 'moin@gmail.com', 'jjj', 1, '2020-09-12 05:17:57', '2020-09-12 05:17:57'),
(5, 'supplier', 'Mehedi', 'mehedi', '01700000003', 'mehedi@gmail.com', 'kallyanpur', 1, '2020-09-12 05:36:34', '2020-09-12 05:36:34'),
(6, 'supplier', 'Meem', 'meem', '01725930117', 'meem.starit@gmail.com', 'kallyanpur', 1, '2020-09-12 05:43:15', '2020-09-12 05:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `controller_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `controller_name`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'RoleController', 'role-list', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(2, 'RoleController', 'role-create', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(3, 'RoleController', 'role-edit', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(4, 'RoleController', 'role-delete', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(5, 'UserController', 'user-list', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(6, 'UserController', 'user-create', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(7, 'UserController', 'user-edit', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(8, 'UserController', 'user-delete', 'web', '2019-08-17 08:33:07', '2019-08-17 08:33:07'),
(9, 'StoreController', 'store-list', 'web', '2020-08-25 06:13:47', '2020-08-25 06:13:47'),
(10, 'StoreController', 'store-create', 'web', '2020-08-25 06:33:46', '2020-08-25 06:33:46'),
(11, 'StoreController', 'store-edit', 'web', '2020-08-25 06:34:38', '2020-08-25 06:34:38'),
(12, 'StoreController', 'store-delete', 'web', '2020-08-25 06:34:55', '2020-08-25 06:34:55'),
(13, 'ProductController', 'product-category-list', 'web', '2020-08-25 06:35:57', '2020-08-25 06:35:57'),
(14, 'ProductController', 'product-category-create', 'web', '2020-08-25 06:36:11', '2020-08-25 06:36:11'),
(15, 'ProductController', 'product-category-edit', 'web', '2020-08-25 06:36:38', '2020-08-25 06:36:38'),
(16, 'ProductController', 'product-category-delete', 'web', '2020-08-25 06:36:53', '2020-08-25 06:36:53'),
(17, 'ProductSubCategoryController', 'product-sub-category-list', 'web', '2020-08-25 06:37:25', '2020-08-25 06:37:25'),
(18, 'ProductSubCategoryController', 'product-sub-category-create', 'web', '2020-08-25 06:38:30', '2020-08-25 06:38:30'),
(19, 'ProductSubCategoryController', 'product-sub-category-edit', 'web', '2020-08-25 06:38:41', '2020-08-25 06:38:41'),
(20, 'ProductSubCategoryController', 'product-sub-category-delete', 'web', '2020-08-25 06:38:53', '2020-08-25 06:38:53'),
(21, 'ProductBrandController', 'product-brand-list', 'web', '2020-08-25 07:22:37', '2020-08-25 07:22:37'),
(22, 'ProductBrandController', 'product-brand-create', 'web', '2020-08-25 07:22:49', '2020-08-25 07:22:49'),
(23, 'ProductBrandController', 'product-brand-edit', 'web', '2020-08-25 07:23:00', '2020-08-25 07:23:00'),
(24, 'ProductBrandController', 'product-brand-delete', 'web', '2020-08-25 07:23:11', '2020-08-25 07:23:11'),
(25, 'ProductController', 'product-list', 'web', '2020-08-25 09:30:52', '2020-08-25 09:30:52'),
(26, 'ProductController', 'product-create', 'web', '2020-08-25 09:31:05', '2020-08-25 09:31:05'),
(27, 'ProductController', 'product-edit', 'web', '2020-08-25 09:31:17', '2020-08-25 09:31:17'),
(28, 'ProductController', 'product-delete', 'web', '2020-08-25 09:31:32', '2020-08-25 09:31:32'),
(29, 'PartyController', 'party-list', 'web', '2020-09-10 02:15:23', '2020-09-10 02:15:23'),
(30, 'PartyController', 'party-create', 'web', '2020-09-10 02:15:49', '2020-09-10 02:15:49'),
(31, 'PartyController', 'party-edit', 'web', '2020-09-10 02:16:09', '2020-09-10 02:16:09'),
(32, 'PartyController', 'party-delete', 'web', '2020-09-10 02:16:30', '2020-09-10 02:16:30'),
(33, 'ProductPurchaseController', 'product-purchase-list', 'web', '2020-09-10 02:17:24', '2020-09-10 02:17:24'),
(34, 'ProductPurchaseController', 'product-purchase-create', 'web', '2020-09-10 02:17:52', '2020-09-10 02:17:52'),
(35, 'ProductPurchaseController', 'product-purchase-edit', 'web', '2020-09-10 02:18:15', '2020-09-10 02:18:15'),
(36, 'ProductPurchaseController', 'product-purchase-delete', 'web', '2020-09-10 02:18:36', '2020-09-10 02:18:36'),
(37, 'ProductSaleController', 'product-sale-list', 'web', '2020-09-10 02:19:33', '2020-09-10 02:19:33'),
(38, 'ProductSaleController', 'product-sale-create', 'web', '2020-09-10 02:19:57', '2020-09-10 02:19:57'),
(39, 'ProductSaleController', 'product-sale-edit', 'web', '2020-09-10 02:20:16', '2020-09-10 02:20:16'),
(40, 'ProductSaleController', 'product-sale-delete', 'web', '2020-09-10 02:20:34', '2020-09-10 02:20:34'),
(41, 'ProductSaleReturnController', 'product-sale-return-list', 'web', '2020-09-10 02:21:15', '2020-09-10 02:21:15'),
(42, 'ProductSaleReturnController', 'product-sale-return-create', 'web', '2020-09-10 02:21:29', '2020-09-10 02:21:29'),
(43, 'ProductSaleReturnController', 'product-sale-return-edit', 'web', '2020-09-10 02:21:41', '2020-09-10 02:21:41'),
(44, 'ProductSaleReturnController', 'product-sale-return-delete', 'web', '2020-09-10 02:21:53', '2020-09-10 02:21:53'),
(45, 'StockController', 'stock-list', 'web', '2020-09-10 02:22:28', '2020-09-10 02:22:28'),
(46, 'StockController', 'stock-create', 'web', '2020-09-10 02:22:43', '2020-09-10 02:22:43'),
(47, 'StockController', 'stock-edit', 'web', '2020-09-10 02:22:54', '2020-09-10 02:22:54'),
(48, 'StockController', 'stock-delete', 'web', '2020-09-10 02:23:06', '2020-09-10 02:23:06'),
(49, 'TransactionController', 'transaction-list', 'web', '2020-09-10 02:23:38', '2020-09-10 02:23:38'),
(50, 'TransactionController', 'transaction-create', 'web', '2020-09-10 02:23:52', '2020-09-10 02:23:52'),
(51, 'TransactionController', 'transaction-edit', 'web', '2020-09-10 02:24:09', '2020-09-10 02:24:09'),
(52, 'TransactionController', 'transaction-delete', 'web', '2020-09-10 02:24:20', '2020-09-10 02:24:20'),
(53, 'HomeController', 'home-list', 'web', '2020-09-10 02:26:52', '2020-09-10 02:26:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `product_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_brand_id` bigint(20) UNSIGNED NOT NULL,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product.jpg',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `product_category_id`, `product_sub_category_id`, `product_brand_id`, `barcode`, `sku`, `model`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'product 1', 'product-1', 1, 1, 1, '123', NULL, NULL, '2020-09-08-5f5739fb6474b.jpg', 1, '2020-09-08 01:59:55', '2020-09-08 01:59:55'),
(2, 'product 2', 'product-2', 2, NULL, 2, '456', NULL, NULL, '2020-09-08-5f573a1b63cc5.jpeg', 1, '2020-09-08 02:00:27', '2020-09-08 02:00:27'),
(3, 'product 3', 'product-3', 2, NULL, 2, '365', NULL, NULL, '2020-09-14-5f5f12f409a72.jpg', 1, '2020-09-14 00:51:33', '2020-09-14 00:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Sunsilk', 'sunsilk', '2020-09-08 01:58:53', '2020-09-08 01:58:53'),
(2, 'Soft', 'soft', '2020-09-08 01:59:23', '2020-09-08 01:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Conditionals', 'conditionals', '2020-09-08 01:57:43', '2020-09-08 01:57:43'),
(2, 'Hair Cutting', 'hair-cutting', '2020-09-08 01:57:59', '2020-09-08 01:57:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_purchases`
--

CREATE TABLE `product_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` enum('cash','online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_purchases`
--

INSERT INTO `product_purchases` (`id`, `user_id`, `store_id`, `party_id`, `payment_type`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'cash', 2500.00, '2020-09-08 03:32:27', '2020-09-08 03:32:27'),
(2, 1, 1, 1, 'cash', 1500.00, '2020-09-14 01:13:40', '2020-09-14 01:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_purchase_details`
--

CREATE TABLE `product_purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `product_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_brand_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `sub_total` double(8,2) NOT NULL,
  `mrp_price` double(8,2) NOT NULL,
  `expired_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_purchase_details`
--

INSERT INTO `product_purchase_details` (`id`, `product_purchase_id`, `product_category_id`, `product_sub_category_id`, `product_brand_id`, `product_id`, `qty`, `price`, `sub_total`, `mrp_price`, `expired_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 10, 200.00, 2000.00, 250.00, NULL, '2020-09-08 03:32:27', '2020-09-08 03:32:27'),
(2, 1, 2, NULL, 2, 2, 5, 100.00, 500.00, 120.00, NULL, '2020-09-08 03:32:27', '2020-09-08 03:32:27'),
(3, 2, 2, NULL, 2, 3, 5, 300.00, 1500.00, 350.00, NULL, '2020-09-14 01:13:40', '2020-09-14 01:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_sales`
--

CREATE TABLE `product_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` enum('cash','online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_service_charge` double(8,2) NOT NULL,
  `discount_type` enum('flat','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` double(8,2) NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `paid_amount` double(8,2) NOT NULL,
  `due_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`id`, `invoice_no`, `user_id`, `store_id`, `party_id`, `payment_type`, `delivery_service`, `delivery_service_charge`, `discount_type`, `discount_amount`, `total_amount`, `paid_amount`, `due_amount`, `created_at`, `updated_at`) VALUES
(6, '1000', 1, 1, 2, 'cash', 'Sundorban Kuriar Service', 20.00, 'flat', 50.00, 1200.00, 1000.00, 200.00, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(7, '1001', 1, 1, 2, 'online', 'SA Paribahan', 30.00, 'flat', 10.00, 350.00, 300.00, 50.00, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(9, '1002', 1, 1, 2, 'cash', 'Sundorban Kuriar Service', 25.00, 'flat', 0.00, 230.00, 200.00, 30.00, '2020-09-12 00:54:09', '2020-09-14 01:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_details`
--

CREATE TABLE `product_sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_sale_id` bigint(20) UNSIGNED NOT NULL,
  `return_type` enum('returnable','not returnable') COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `product_sub_category_id` int(11) DEFAULT NULL,
  `product_brand_id` int(11) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `sub_total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sale_details`
--

INSERT INTO `product_sale_details` (`id`, `product_sale_id`, `return_type`, `product_category_id`, `product_sub_category_id`, `product_brand_id`, `product_id`, `qty`, `price`, `sub_total`, `created_at`, `updated_at`) VALUES
(3, 6, 'returnable', 1, 1, 1, 1, 4, 250.00, 1000.00, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(4, 6, 'not returnable', 2, NULL, 2, 2, 2, 125.00, 250.00, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(5, 7, 'returnable', 1, 1, 1, 1, 1, 240.00, 240.00, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(6, 7, 'returnable', 2, NULL, 2, 2, 1, 120.00, 120.00, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(8, 9, 'returnable', 1, 1, 1, 1, 1, 230.00, 230.00, '2020-09-12 00:54:09', '2020-09-12 00:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_returns`
--

CREATE TABLE `product_sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sale_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` enum('cash','online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('flat','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` double(8,2) NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sale_returns`
--

INSERT INTO `product_sale_returns` (`id`, `invoice_no`, `sale_invoice_no`, `product_sale_id`, `user_id`, `store_id`, `party_id`, `payment_type`, `discount_type`, `discount_amount`, `total_amount`, `created_at`, `updated_at`) VALUES
(5, 'return-1000', '1000', 6, 1, 1, 2, 'cash', 'flat', 0.00, 250.00, '2020-09-09 05:51:36', '2020-09-09 05:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_return_details`
--

CREATE TABLE `product_sale_return_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `product_sale_detail_id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `product_sub_category_id` int(11) DEFAULT NULL,
  `product_brand_id` int(11) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `reason` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sale_return_details`
--

INSERT INTO `product_sale_return_details` (`id`, `product_sale_return_id`, `product_sale_detail_id`, `product_category_id`, `product_sub_category_id`, `product_brand_id`, `product_id`, `qty`, `price`, `reason`, `created_at`, `updated_at`) VALUES
(5, 5, 3, 1, 1, 1, 1, 1, 250.00, 'test', '2020-09-09 05:51:36', '2020-09-09 05:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_categories`
--

CREATE TABLE `product_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sub_categories`
--

INSERT INTO `product_sub_categories` (`id`, `product_category_id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 'Men conditionals', 'men-conditionals', '2020-09-08 01:58:10', '2020-09-08 01:58:10'),
(2, 1, 'Women conditionals', 'women-conditionals', '2020-09-08 01:58:27', '2020-09-08 01:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2019-08-17 01:35:43', '2019-08-17 01:35:43'),
(2, 'User', 'web', '2019-08-17 01:41:49', '2019-08-17 01:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
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
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
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
(37, 2),
(38, 2),
(39, 2);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `stock_type` enum('purchase','sale','sale return') COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `stock_in` int(11) NOT NULL,
  `stock_out` int(11) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `ref_id`, `user_id`, `store_id`, `product_id`, `stock_type`, `previous_stock`, `stock_in`, `stock_out`, `current_stock`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 'purchase', 0, 10, 0, 10, '2020-09-08 03:32:27', '2020-09-08 03:32:27'),
(2, 1, 1, 1, 2, 'purchase', 0, 5, 0, 5, '2020-09-08 03:32:27', '2020-09-08 03:32:27'),
(5, 6, 1, 1, 1, 'sale', 10, 0, 4, 6, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(6, 6, 1, 1, 2, 'sale', 5, 0, 2, 3, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(7, 5, 1, 1, 1, 'sale return', 6, 1, 0, 7, '2020-09-09 05:51:36', '2020-09-09 05:51:36'),
(8, 7, 1, 1, 1, 'sale', 7, 0, 1, 6, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(9, 7, 1, 1, 2, 'sale', 3, 0, 1, 2, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(10, 9, 1, 1, 1, 'sale', 5, 0, 1, 4, '2020-09-12 00:54:09', '2020-09-14 01:52:29'),
(11, 2, 1, 1, 3, 'purchase', 0, 5, 0, 5, '2020-09-14 01:13:40', '2020-09-14 01:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `user_id`, `name`, `slug`, `phone`, `address`, `logo`, `created_at`, `updated_at`) VALUES
(1, 2, 'Store 1', 'store-1', '01725930111', 'kallyanpur', '2020-09-08-5f574f79c3f82.jpg', '2020-09-08 03:31:37', '2020-09-08 03:31:37'),
(2, 3, 'Store 2', 'store-2', '01725930111', 'kallyanpur', '2020-09-10-5f59fa3a140d3.jpg', '2020-09-10 04:04:42', '2020-09-10 04:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `ref_id` int(11) NOT NULL,
  `transaction_type` enum('purchase','sale','delivery charge','sale return') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` enum('cash','online') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `invoice_no`, `user_id`, `store_id`, `party_id`, `ref_id`, `transaction_type`, `payment_type`, `amount`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, 1, 1, 'purchase', 'cash', 2500.00, '2020-09-08 03:32:28', '2020-09-08 03:32:28'),
(3, '1000', 1, 1, 2, 6, 'sale', 'cash', 1200.00, '2020-09-09 03:52:31', '2020-09-09 03:52:31'),
(6, 'return-1000', 1, 1, 2, 5, 'sale return', 'cash', 250.00, '2020-09-09 05:51:36', '2020-09-09 05:51:36'),
(7, '1001', 1, 1, 2, 7, 'sale', 'online', 350.00, '2020-09-11 22:33:03', '2020-09-11 22:33:03'),
(8, '1002', 1, 1, 2, 9, 'sale', 'cash', 230.00, '2020-09-12 00:54:09', '2020-09-14 01:52:29'),
(9, NULL, 1, 1, 1, 2, 'purchase', 'cash', 1500.00, '2020-09-14 01:13:40', '2020-09-14 01:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Robeul Islam', 'robeul.starit@gmail.com', NULL, '$2y$10$StCKBWTKmRn1tTpVvPCTXeNEsuDbzacXiNjnmQNj.egjruH9w1/ee', NULL, 1, '2019-08-17 01:35:43', '2019-08-17 01:35:43'),
(2, 'Md. Robi Hasan', 'robicse8@gmail.com', NULL, '$2y$10$8mvdYDgwWAEaRGWLCqG.LOJQ5Ux7UErz6kbI3w9Fe6CMODIm9LpH.', NULL, 1, '2019-08-17 01:45:02', '2020-09-14 03:32:16'),
(3, 'Ashiq', 'ashiq.starit@gmail.com', NULL, '$2y$10$IF2Za58K4U6joEZ5WJ30IOPCn4aEQ3om.YBW.jxSyzMDlSDV0T/sC', NULL, 1, '2020-09-10 04:03:59', '2020-09-10 04:03:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dues`
--
ALTER TABLE `dues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dues_store_id_foreign` (`store_id`),
  ADD KEY `dues_party_id_foreign` (`party_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `products_product_category_id_foreign` (`product_category_id`),
  ADD KEY `products_product_sub_category_id_foreign` (`product_sub_category_id`),
  ADD KEY `products_product_brand_id_foreign` (`product_brand_id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchases_store_id_foreign` (`store_id`),
  ADD KEY `product_purchases_party_id_foreign` (`party_id`);

--
-- Indexes for table `product_purchase_details`
--
ALTER TABLE `product_purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchase_details_product_purchase_id_foreign` (`product_purchase_id`),
  ADD KEY `product_purchase_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_sales`
--
ALTER TABLE `product_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sales_store_id_foreign` (`store_id`),
  ADD KEY `product_sales_party_id_foreign` (`party_id`);

--
-- Indexes for table `product_sale_details`
--
ALTER TABLE `product_sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sale_details_product_sale_id_foreign` (`product_sale_id`),
  ADD KEY `product_sale_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_sale_returns`
--
ALTER TABLE `product_sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sale_returns_product_sale_id_foreign` (`product_sale_id`),
  ADD KEY `product_sale_returns_store_id_foreign` (`store_id`),
  ADD KEY `product_sale_returns_party_id_foreign` (`party_id`);

--
-- Indexes for table `product_sale_return_details`
--
ALTER TABLE `product_sale_return_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sale_return_details_product_sale_return_id_foreign` (`product_sale_return_id`),
  ADD KEY `product_sale_return_details_product_sale_detail_id_foreign` (`product_sale_detail_id`),
  ADD KEY `product_sale_return_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sub_categories_product_category_id_foreign` (`product_category_id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_store_id_foreign` (`store_id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_store_id_foreign` (`store_id`),
  ADD KEY `transactions_party_id_foreign` (`party_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dues`
--
ALTER TABLE `dues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_brands`
--
ALTER TABLE `product_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_purchases`
--
ALTER TABLE `product_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_purchase_details`
--
ALTER TABLE `product_purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_sales`
--
ALTER TABLE `product_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_sale_details`
--
ALTER TABLE `product_sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_sale_returns`
--
ALTER TABLE `product_sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_sale_return_details`
--
ALTER TABLE `product_sale_return_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dues`
--
ALTER TABLE `dues`
  ADD CONSTRAINT `dues_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dues_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_product_brand_id_foreign` FOREIGN KEY (`product_brand_id`) REFERENCES `product_brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_product_sub_category_id_foreign` FOREIGN KEY (`product_sub_category_id`) REFERENCES `product_sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD CONSTRAINT `product_purchases_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchases_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchase_details`
--
ALTER TABLE `product_purchase_details`
  ADD CONSTRAINT `product_purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchase_details_product_purchase_id_foreign` FOREIGN KEY (`product_purchase_id`) REFERENCES `product_purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sales`
--
ALTER TABLE `product_sales`
  ADD CONSTRAINT `product_sales_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sales_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sale_details`
--
ALTER TABLE `product_sale_details`
  ADD CONSTRAINT `product_sale_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sale_details_product_sale_id_foreign` FOREIGN KEY (`product_sale_id`) REFERENCES `product_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sale_returns`
--
ALTER TABLE `product_sale_returns`
  ADD CONSTRAINT `product_sale_returns_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sale_returns_product_sale_id_foreign` FOREIGN KEY (`product_sale_id`) REFERENCES `product_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sale_returns_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sale_return_details`
--
ALTER TABLE `product_sale_return_details`
  ADD CONSTRAINT `product_sale_return_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sale_return_details_product_sale_detail_id_foreign` FOREIGN KEY (`product_sale_detail_id`) REFERENCES `product_sale_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sale_return_details_product_sale_return_id_foreign` FOREIGN KEY (`product_sale_return_id`) REFERENCES `product_sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  ADD CONSTRAINT `product_sub_categories_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
