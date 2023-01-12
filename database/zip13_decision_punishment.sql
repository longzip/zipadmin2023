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
-- Cấu trúc bảng cho bảng `zip13_decision_punishment`
--

CREATE TABLE `zip13_decision_punishment` (
  `id` int(11) NOT NULL,
  `chetai_id1` int(11) DEFAULT NULL,
  `chetai_id2` int(11) DEFAULT NULL,
  `chetai_id3` int(11) DEFAULT NULL,
  `chetai_id4` int(11) DEFAULT NULL,
  `chetai_id5` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `zip13_decision_punishment`
--

INSERT INTO `zip13_decision_punishment` (`id`, `chetai_id1`, `chetai_id2`, `chetai_id3`, `chetai_id4`, `chetai_id5`, `created_at`, `updated_at`) VALUES
(11, 7, 7, NULL, NULL, NULL, '2019-09-12 10:27:48', '2019-09-12 10:27:48'),
(12, 4, NULL, NULL, NULL, NULL, '2019-09-13 16:02:19', '2019-09-13 16:02:19'),
(13, 4, NULL, NULL, NULL, NULL, '2019-09-13 16:02:53', '2019-09-13 16:02:53'),
(14, 4, NULL, NULL, NULL, NULL, '2019-09-14 01:27:11', '2019-09-14 01:27:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_decision_punishment`
--
ALTER TABLE `zip13_decision_punishment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_decision_punishment`
--
ALTER TABLE `zip13_decision_punishment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
