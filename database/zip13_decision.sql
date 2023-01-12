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
-- Cấu trúc bảng cho bảng `zip13_decision`
--

CREATE TABLE `zip13_decision` (
  `id` int(11) NOT NULL,
  `quytrinh_id` int(11) UNSIGNED DEFAULT NULL,
  `sapo` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `detail` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `giamsat_id` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_ma` int(3) UNSIGNED ZEROFILL NOT NULL DEFAULT 000,
  `maqdgs` int(10) DEFAULT NULL,
  `monitoring` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `zip13_decision`
--

INSERT INTO `zip13_decision` (`id`, `quytrinh_id`, `sapo`, `detail`, `role_id`, `giamsat_id`, `id_ma`, `maqdgs`, `monitoring`, `updated_at`, `created_at`) VALUES
(7, 16, 'test 5', 'test 5', '[2]', '', 006, NULL, NULL, '2019-09-12 10:23:30', '2019-09-12 10:23:30'),
(8, 16, 'test 6', 'test 6', '[2]', '', 007, NULL, NULL, '2019-09-12 10:25:11', '2019-09-12 10:25:11'),
(10, 16, 'test 6', 'test 6', '[2]', '', 009, NULL, NULL, '2019-09-12 10:26:03', '2019-09-12 10:26:03'),
(11, 16, 'test 7', 'tes7', '[2]', '', 010, NULL, NULL, '2019-09-12 10:27:48', '2019-09-12 10:27:48'),
(12, 16, 'test 8', 'test 8', '[2]', '2', 011, 10, NULL, '2019-09-13 16:02:19', '2019-09-13 16:02:19'),
(13, 16, 'test 9', 'test 9', '[3]', '2', 012, 11, NULL, '2019-09-13 16:02:53', '2019-09-13 16:02:53'),
(14, 16, 'test 10', 'test 10', '[1,2,3]', '2', 013, 11, NULL, '2019-09-14 01:27:11', '2019-09-14 01:27:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_decision`
--
ALTER TABLE `zip13_decision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zip13_decisionst` (`quytrinh_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_decision`
--
ALTER TABLE `zip13_decision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `zip13_decision`
--
ALTER TABLE `zip13_decision`
  ADD CONSTRAINT `zip13_decisionst` FOREIGN KEY (`quytrinh_id`) REFERENCES `zip13_quy_trinh` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
