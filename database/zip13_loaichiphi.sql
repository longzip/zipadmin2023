-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 03, 2020 lúc 10:48 AM
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
-- Cấu trúc bảng cho bảng `zip13_loaichiphi`
--

CREATE TABLE `zip13_loaichiphi` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `zip13_loaichiphi`
--

INSERT INTO `zip13_loaichiphi` (`id`, `name`, `created_at`, `updated_at`) VALUES
(7, 'thiết bị IT', '2020-01-14 07:51:50', '2020-01-14 07:51:50'),
(8, 'Thiết bị văn phòng', '2020-01-14 07:52:03', '2020-01-14 07:52:03'),
(9, 'Camera', '2020-01-14 07:52:13', '2020-01-14 07:52:13'),
(10, 'Điện nước y', '2020-01-14 07:52:20', '2020-01-16 10:28:24'),
(11, 'Nhậu', '2020-01-14 07:52:32', '2020-01-14 07:52:32'),
(12, 'bàn ghế 1', '2020-01-14 07:58:44', '2020-01-14 08:49:10');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_loaichiphi`
--
ALTER TABLE `zip13_loaichiphi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_loaichiphi`
--
ALTER TABLE `zip13_loaichiphi`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
