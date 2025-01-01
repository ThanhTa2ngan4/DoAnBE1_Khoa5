-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th1 01, 2025 lúc 02:42 PM
-- Phiên bản máy phục vụ: 8.0.31
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `khoa5_doan_be1`
--
CREATE DATABASE IF NOT EXISTS `khoa5_doan_be1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `khoa5_doan_be1`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Hoa quả tươi'),
(2, 'Hoa quả sấy khô'),
(3, 'Nước ép và sinh tố'),
(4, 'Rau củ'),
(5, 'Sản phẩm nhập khẩu'),
(6, 'Giỏ trái cây');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_product`
--

DROP TABLE IF EXISTS `category_product`;
CREATE TABLE IF NOT EXISTS `category_product` (
  `category_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`category_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `category_product`
--

INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
(1, 10),
(1, 41),
(1, 42),
(1, 48),
(2, 11),
(2, 14),
(2, 15),
(2, 16),
(2, 41),
(2, 42),
(2, 48),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(3, 21),
(3, 22),
(4, 23),
(4, 24),
(4, 25),
(4, 26),
(4, 27),
(4, 28),
(4, 29),
(4, 30),
(4, 31),
(4, 32),
(4, 47),
(5, 24),
(5, 30),
(5, 32),
(6, 33),
(6, 34),
(6, 35),
(6, 36),
(6, 37),
(6, 49);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `content`, `product_id`, `user_id`, `created_at`) VALUES
(10, 'dddddddddd', 10, 7, '2024-12-29 20:42:24'),
(11, 'ffffff', 10, 7, '2024-12-29 20:48:22'),
(12, 'ffffffffffffff', 10, 7, '2024-12-29 20:48:24'),
(13, 'fffffffffffffffffffffff', 10, 7, '2024-12-29 20:48:27'),
(14, 'fffffffffffffffffffffffffffffffffff', 10, 7, '2024-12-29 20:48:30'),
(15, 'dd', 10, 6, '2024-12-29 21:04:43'),
(16, 'đ', 10, 7, '2024-12-29 21:46:12'),
(17, '1111111', 10, 7, '2024-12-29 21:46:34'),
(18, 'dd', 10, 7, '2024-12-29 21:47:33'),
(19, 'hello', 10, 5, '2024-12-29 22:53:43'),
(20, 'hello', 14, 5, '2024-12-29 23:04:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_user`
--

DROP TABLE IF EXISTS `comment_user`;
CREATE TABLE IF NOT EXISTS `comment_user` (
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `views`, `likes`, `status`) VALUES
(48, 'Ổi trắng đen', 20000000, '<div>xin chao moi nguoi </div>', 'oi.jpg', 0, 0, 1),
(49, 'Giỏ hàng số 4444', 500000, '<div>xin chao moi nguoi </div>', 'gio4.jpg', 0, 0, 1),
(10, 'Ổi trắng', 20000, '<div>xin chao moi nguoi </div>', 'oi.jpg', 0, 1, 1),
(11, 'Dâu tây sấy', 120000, '<div>xin chao moi nguoi </div>', 'dausay.jpg', 0, 1, 1),
(14, 'Mận sấy', 40000, '<div>xin chao moi nguoi </div>', 'mansay.jpg', 0, 0, 1),
(15, 'Chuối sấy', 25000, '<div>xin chao moi nguoi </div>', 'chuoisay.jpg', 0, 0, 1),
(16, 'Hồng sấy', 200000, '<div>xin chao moi nguoi </div>', 'hongsay.jpg', 0, 0, 1),
(17, 'Nước ép dứa', 20000, '<div>xin chao moi nguoi </div>', 'nuocepdua.jpg', 0, 0, 1),
(18, 'Nước ép cà rốt', 20000, '<div>xin chao moi nguoi </div>', 'nuocepcam.jpg', 0, 0, 1),
(19, 'Nước ép cam', 20000, '<div>xin chao moi nguoi </div>', 'nuocepcarot.jpg', 0, 0, 1),
(20, 'Nước ép ổi', 20000, '<div>xin chao moi nguoi </div>', 'nuocepoi.jpg', 0, 0, 1),
(21, 'Nước ép dưa hấu', 20000, '<div>xin chao moi nguoi </div>', 'nuocepduhau.jpg', 0, 0, 1),
(22, 'Nước ép cần tây', 30000, '<div>xin chao moi nguoi </div>', 'nuocepcantay.jpg', 0, 0, 1),
(23, 'Rau xà lách', 10000, '<div>xin chao moi nguoi </div>', 'xalach.jpg', 0, 0, 1),
(24, 'Rau xà lách Mỹ', 15000, '<div>xin chao moi nguoi </div>', 'xalachmy.jpg', 0, 0, 1),
(25, 'Rau xà lách xoong', 12000, '<div>xin chao moi nguoi </div>', 'xalachxong.jpg', 0, 0, 1),
(26, 'Rau cải thìa', 15000, '<div>xin chao moi nguoi </div>', 'caithia.jpg', 0, 0, 1),
(27, 'Rau muống', 5000, '<div>xin chao moi nguoi </div>', 'raumuong.jpg', 0, 0, 1),
(28, 'Rau càng cua', 15000, '<div>xin chao moi nguoi </div>', 'raucangcu.jpg', 0, 0, 1),
(29, 'Khoai tây', 13000, '<div>xin chao moi nguoi </div>', 'khoaitay.jpg', 0, 0, 1),
(30, 'Khoai tây Mỹ', 20000, '<div>xin chao moi nguoi </div>', 'khoaitaymy.jpg', 0, 0, 1),
(31, 'Cà rốt', 15000, '<div>xin chao moi nguoi </div>', 'carot.jpg', 0, 0, 1),
(32, 'Cà rốt Mỹ', 25000, '<div>xin chao moi nguoi </div>', 'carotmy.jpg', 0, 0, 1),
(33, 'Giỏ hàng số 1', 500000, '<div>xin chao moi nguoi </div>', 'gio1.jpg', 0, 0, 1),
(34, 'Giỏ hàng số 2', 500000, '<div>xin chao moi nguoi </div>', 'gio2.jpg', 0, 1, 1),
(35, 'Giỏ hàng số 3', 500000, '<div>xin chao moi nguoi </div>', 'gio3.jpg', 0, 0, 1),
(36, 'Giỏ hàng số 4', 500000, '<div>xin chao moi nguoi </div>', 'gio4.jpg', 0, 0, 1),
(37, 'Giỏ hàng số 5', 500000, '<div>xin chao moi nguoi </div>', 'gio5.jpg', 0, 0, 1),
(47, 'Rau muống xào tỏi', 5000, '<div>xin chao moi nguoi </div>', 'raumuong.jpg', 0, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `created_at`) VALUES
(6, 'admin ', '$2y$10$pH/h71X51.iTGKrk75i1wuuHiIkZ7xitBXISTXWPs95shTB8kucVW', 1, '2024-12-28 06:28:42'),
(5, 'thanh123', '$2y$10$3Aw/Azk4wz4YL3VQdIIKKO8aGmgq72ek8NB8l3mTAJKXw1ndQtH66', 2, '2024-12-28 05:56:34'),
(7, 'quoc', '$2y$10$LBO8rS4GM7VE2cRj9eV3ouN1KHdpI32GsHtMbmpyJ1OXz1.LLHtWC', 2, '2024-12-29 13:33:26'),
(8, 'nhathanh', '$2y$10$vDOjoFBKFjFbF3fWT0Pp1e84o5MxYK3H.M76BZ4UuKqK60Nu1h5Me', 2, '2024-12-29 15:36:03'),
(9, 'tahynhatthanh', '$2y$10$2CIHJNeKK7d77lUjI8TvMu/SUmZN.sPcY6rRGrXoAVDHC30yub7hm', 2, '2024-12-29 15:36:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
