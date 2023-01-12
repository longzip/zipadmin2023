-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 03, 2020 lúc 10:47 AM
-- Phiên bản máy phục vụ: 10.1.30-MariaDB
-- Phiên bản PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `zip_demo`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `zip13_chiphi`
--

CREATE TABLE `zip13_chiphi` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` text,
  `user_id` text,
  `role_id` text,
  `loaichiphi_id` int(11) DEFAULT NULL,
  `TongChiPhi` int(11) DEFAULT NULL,
  `TongTienThuc` int(11) DEFAULT NULL,
  `TinhTrang` text COMMENT '0: khẩn cấp, 1: ưu tiên,2:bình thường',
  `NgayApDung` datetime DEFAULT NULL,
  `duyet` int(11) DEFAULT '0' COMMENT '0: gửi, 1: chờ,2:kt duyet,3:gd duyet,4: trả  về,5: ko duyệt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `zip13_chiphi`
--

INSERT INTO `zip13_chiphi` (`id`, `name`, `user_id`, `role_id`, `loaichiphi_id`, `TongChiPhi`, `TongTienThuc`, `TinhTrang`, `NgayApDung`, `duyet`, `created_at`, `updated_at`) VALUES
(131, 'hjk', '1', '1', 10, 61, 158, '1', NULL, 0, '2020-01-21 10:21:21', '2020-02-03 07:23:11'),
(132, 'sdf', '1', '3', 10, 56, 56, '1', NULL, 1, '2020-01-21 11:00:46', '2020-02-03 09:37:34'),
(133, 'dsf', '3', '4', 10, 64, 45678901, '2', NULL, 3, '2020-01-21 11:01:44', '2020-02-03 08:13:53'),
(134, 'de xuat 1', '1', '1', 11, 8171991, 81719926, '1', NULL, 3, '2020-02-03 09:40:01', '2020-02-03 09:43:05');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_chiphi`
--
ALTER TABLE `zip13_chiphi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_chiphi`
--
ALTER TABLE `zip13_chiphi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
