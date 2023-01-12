-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th9 14, 2019 lúc 04:49 AM
-- Phiên bản máy phục vụ: 10.4.6-MariaDB
-- Phiên bản PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `zip_furniture`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `zip13_che_tai`
--

CREATE TABLE `zip13_che_tai` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `huong_dan` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'NULL',
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `zip13_che_tai`
--

INSERT INTO `zip13_che_tai` (`id`, `name`, `huong_dan`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 'cảnh cáo', 'mô tả hướng dẫn chế tài cảnh cáo', 1, '2019-09-05 03:00:08', '2019-09-05 03:00:47'),
(6, 'phạt tiền lần 1', 'tttt', 1, '2019-09-05 03:32:52', '2019-09-06 04:02:11'),
(7, 'phạt tiền lần 2', 'giá 50k', 1, '2019-09-06 04:02:24', '2019-09-06 04:02:24'),
(8, 'Phat 100k', 'NULL', 1, '2019-09-12 06:26:37', '2019-09-12 06:26:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_che_tai`
--
ALTER TABLE `zip13_che_tai`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_che_tai`
--
ALTER TABLE `zip13_che_tai`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
