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
-- Cấu trúc bảng cho bảng `zip13_thongbao`
--

CREATE TABLE `zip13_thongbao` (
  `id` int(11) NOT NULL,
  `id_chuyenmuc` int(11) NOT NULL,
  `noidung` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1:chiphi,2:nghiphep,3:vipham,4:quydinh',
  `user_see` int(11) NOT NULL,
  `user_tao` int(11) NOT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `zip13_thongbao`
--

INSERT INTO `zip13_thongbao` (`id`, `id_chuyenmuc`, `noidung`, `type`, `user_see`, `user_tao`, `action`, `created_at`, `updated_at`) VALUES
(15, 133, 'dsf', 1, 9184, 3, 'da them de xuat', '2020-02-03 07:28:24', '2020-02-03 07:28:24'),
(16, 133, 'thuc te dsf', 1, 9185, 9184, 'BPKT da duyet', '2020-02-03 07:30:21', '2020-02-03 07:30:21'),
(17, 133, 'thuc te dsf', 1, 9003, 9184, 'BPKT da duyet', '2020-02-03 07:30:21', '2020-02-03 07:30:21'),
(18, 133, 'dsf', 1, 9184, 9003, 'BPGD da duyet', '2020-02-03 07:39:12', '2020-02-03 07:39:12'),
(19, 133, 'thuc te dsf', 1, 9185, 9184, 'BPKT da sua de xuat', '2020-02-03 08:02:22', '2020-02-03 08:02:22'),
(20, 133, 'thuc te dsf', 1, 9003, 9184, 'BPKT da sua de xuat', '2020-02-03 08:02:22', '2020-02-03 08:02:22'),
(21, 133, 'thuc te dsf', 1, 9185, 9184, 'BPKT da sua de xuat', '2020-02-03 08:09:04', '2020-02-03 08:09:04'),
(22, 133, 'thuc te dsf', 1, 9003, 9184, 'BPKT da sua de xuat', '2020-02-03 08:09:04', '2020-02-03 08:09:04'),
(23, 133, 'thuc te dsf', 1, 9185, 9184, 'BPKT da sua de xuat', '2020-02-03 08:13:30', '2020-02-03 08:13:30'),
(24, 133, 'thuc te dsf', 1, 9003, 9184, 'BPKT da sua de xuat', '2020-02-03 08:13:30', '2020-02-03 08:13:30'),
(25, 133, 'dsf', 1, 9184, 9003, 'BPGD da duyet', '2020-02-03 08:13:53', '2020-02-03 08:13:53'),
(26, 132, 'sdf', 1, 9184, 268, 'da them de xuat', '2020-02-03 09:37:34', '2020-02-03 09:37:34'),
(27, 134, 'de xuat 1', 1, 9184, 268, 'da them de xuat', '2020-02-03 09:40:16', '2020-02-03 09:40:16'),
(28, 134, 'thuc te de xuat 1', 1, 9185, 9184, 'BPKT da duyet', '2020-02-03 09:41:30', '2020-02-03 09:41:30'),
(29, 134, 'thuc te de xuat 1', 1, 9003, 9184, 'BPKT da duyet', '2020-02-03 09:41:30', '2020-02-03 09:41:30'),
(30, 134, 'de xuat 1', 1, 9184, 9003, 'BPGD da duyet', '2020-02-03 09:41:56', '2020-02-03 09:41:56'),
(31, 134, 'thuc te de xuat 1', 1, 9185, 9184, 'BPKT da sua de xuat', '2020-02-03 09:42:52', '2020-02-03 09:42:52'),
(32, 134, 'thuc te de xuat 1', 1, 9003, 9184, 'BPKT da sua de xuat', '2020-02-03 09:42:52', '2020-02-03 09:42:52'),
(33, 134, 'de xuat 1', 1, 9184, 9003, 'BPGD da duyet', '2020-02-03 09:43:05', '2020-02-03 09:43:05');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_thongbao`
--
ALTER TABLE `zip13_thongbao`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_thongbao`
--
ALTER TABLE `zip13_thongbao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
