-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 10, 2019 lúc 11:18 AM
-- Phiên bản máy phục vụ: 10.4.8-MariaDB
-- Phiên bản PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `zip`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `zip13_chi_phi`
--

CREATE TABLE `zip13_chi_phi` (
  `id` int(11) NOT NULL,
  `id_tp` int(11) DEFAULT NULL COMMENT '1 là hà nội, 2 là sài gòn',
  `id_donhang` int(11) NOT NULL,
  `thue_xe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp_thue_xe_nt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'hà nội và sai gon',
  `cp_thue_xe_tinh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xang_xe_nha_may` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luong_laixe_nha_may` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp_tc_ngoai_tinh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ngoại tỉnh và showroom',
  `phat_sinh_loi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chi_phi_khac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tong_tien` int(20) DEFAULT NULL,
  `so_dh_moi` int(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `zip13_chi_phi`
--

INSERT INTO `zip13_chi_phi` (`id`, `id_tp`, `id_donhang`, `thue_xe`, `cp_thue_xe_nt`, `cp_thue_xe_tinh`, `xang_xe_nha_may`, `luong_laixe_nha_may`, `cp_tc_ngoai_tinh`, `phat_sinh_loi`, `chi_phi_khac`, `tong_tien`, `so_dh_moi`, `created_at`, `updated_at`) VALUES
(5, NULL, 29, '12321', '123213', '13213', '1231231', '1232131', '2112312', '2121212sda', '131231', NULL, NULL, '2019-12-10 10:11:45', '2019-12-10 10:11:45'),
(6, NULL, 13, '12312', '12312', '312123', '131231', '131231', '123123', '31123', '131212', NULL, NULL, '2019-12-10 10:13:44', '2019-12-10 10:13:44');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `zip13_chi_phi`
--
ALTER TABLE `zip13_chi_phi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `zip13_chi_phi`
--
ALTER TABLE `zip13_chi_phi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
