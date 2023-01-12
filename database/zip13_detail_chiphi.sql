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
-- Cấu trúc bảng cho bảng `zip13_detail_chiphi`
--

CREATE TABLE `zip13_detail_chiphi` (
  `id` int(11) UNSIGNED NOT NULL,
  `chiphi_id` int(11) DEFAULT NULL,
  `NoiDung` varchar(255) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `ĐonVi` text,
  `GiaTien` int(11) NOT NULL,
  `TongTien` int(11) NOT NULL,
  `TienThucTe` int(11) DEFAULT NULL,
  `MucDich` varchar(255) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `zip13_detail_chiphi`
--

INSERT INTO `zip13_detail_chiphi` (`id`, `chiphi_id`, `NoiDung`, `SoLuong`, `ĐonVi`, `GiaTien`, `TongTien`, `TienThucTe`, `MucDich`, `GhiChu`, `user_id`, `created_at`, `updated_at`) VALUES
(581, 131, 'm,', 7, NULL, 7, 49, 1, '7', '7', 268, '2020-01-21 10:38:13', '2020-01-21 10:38:13'),
(582, 131, 'hjkl', 7, NULL, 6, 42, 2, '5', '7', 268, '2020-01-21 10:38:13', '2020-01-21 10:38:13'),
(583, 131, '789', 6, NULL, 6, 36, 19, '6', '6', 268, '2020-01-21 10:47:14', '2020-01-21 10:47:14'),
(584, 131, 'ui', 8, NULL, 7, 56, 1, '7', '7', 268, '2020-01-21 10:38:13', '2020-01-21 10:38:13'),
(585, 131, 'jk', 5, NULL, 5, 25, 1, '5', '6', 268, '2020-01-21 10:38:13', '2020-01-21 10:38:13'),
(586, 131, 'yui', 6, NULL, 6, 36, 134, '6', '6', 268, '2020-01-21 11:01:56', '2020-01-21 11:01:56'),
(588, 132, 'yui', 8, NULL, 7, 56, 56, '7', '7', 268, '2020-01-21 11:00:46', '2020-01-21 11:00:46'),
(589, 133, 'hjk', 8, NULL, 8, 64, 45678901, '8', '8', 268, '2020-02-03 08:13:30', '2020-02-03 08:13:30'),
(590, 134, 'ăn uống', 8, NULL, 9000, 72000, 720008, 'nhậu', 'ok', 268, '2020-02-03 09:42:52', '2020-02-03 09:42:52'),
(591, 134, 'hát', 9, NULL, 899999, 8099991, 80999918, 'ok', 'ok', 268, '2020-02-03 09:42:52', '2020-02-03 09:42:52');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_detail_chiphi`
--
ALTER TABLE `zip13_detail_chiphi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_detail_chiphi`
--
ALTER TABLE `zip13_detail_chiphi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=592;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
