-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 30, 2025 lúc 06:12 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `kho_hang`
--
CREATE DATABASE IF NOT EXISTS `kho_hang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kho_hang`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account`
--

CREATE TABLE `tbl_account` (
  `account_id` int(11) NOT NULL,
  `account_username` varchar(100) NOT NULL,
  `account_password` varchar(255) NOT NULL,
  `account_role` enum('admin','nhanvien') DEFAULT 'nhanvien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_account`
--

INSERT INTO `tbl_account` (`account_id`, `account_username`, `account_password`, `account_role`) VALUES
(1, 'admin', '123', 'admin'),
(2, 'nva', '123', 'nhanvien');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_bienthe_sanpham`
--

CREATE TABLE `tbl_bienthe_sanpham` (
  `bienthe_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL,
  `mau_ten` varchar(255) DEFAULT NULL,
  `size_ten` varchar(255) DEFAULT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_bienthe_sanpham`
--

INSERT INTO `tbl_bienthe_sanpham` (`bienthe_id`, `sanpham_id`, `mau_ten`, `size_ten`, `soluong`) VALUES
(18, 16, 'Trắng', 'M', 10),
(19, 16, 'Trắng', 'L', 11),
(20, 16, 'Đen', 'M', 8),
(21, 16, 'Đen', 'S', 6),
(22, 16, 'Đen', 'L', 10),
(23, 17, 'Đen', 'S', 10),
(24, 18, 'Đỏ', 'M', 10),
(25, 19, 'Đen', 'S', 10),
(26, 20, 'Đen', 'S', 10),
(27, 20, 'Đỏ', 'M', 10),
(28, 20, 'Trắng', 'M', 10),
(29, 20, 'Trắng', 'L', 10),
(30, 21, 'Đen', 'XL', 10),
(31, 21, 'Trắng', 'L', 10),
(32, 24, 'Đen', 'M', 10),
(33, 24, 'Đen', 'S', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_chitiethoadonxuat`
--

CREATE TABLE `tbl_chitiethoadonxuat` (
  `chitiethoadonxuat_id` int(11) NOT NULL,
  `hoadonxuat_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL,
  `bienthe_id` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `dongia` decimal(10,2) NOT NULL,
  `thanhtien` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_chitiethoadonxuat`
--

INSERT INTO `tbl_chitiethoadonxuat` (`chitiethoadonxuat_id`, `hoadonxuat_id`, `sanpham_id`, `bienthe_id`, `soluong`, `dongia`, `thanhtien`) VALUES
(12, 19, 16, 18, 1, 150000.00, 150000.00),
(13, 19, 16, 19, 1, 150000.00, 150000.00),
(14, 19, 16, 20, 1, 150000.00, 150000.00),
(15, 20, 16, 18, 4, 150000.00, 600000.00),
(16, 20, 16, 19, 2, 150000.00, 300000.00),
(17, 20, 16, 20, 3, 150000.00, 450000.00),
(18, 22, 16, 19, 3, 150000.00, 450000.00),
(19, 22, 16, 20, 2, 150000.00, 300000.00),
(20, 22, 16, 21, 4, 150000.00, 600000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhmuc`
--

CREATE TABLE `tbl_danhmuc` (
  `danhmuc_id` int(11) NOT NULL,
  `danhmuc_ten` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhmuc`
--

INSERT INTO `tbl_danhmuc` (`danhmuc_id`, `danhmuc_ten`) VALUES
(1, 'Quần Nữ'),
(2, 'Quần Nam'),
(3, 'Áo Nữ'),
(4, 'Áo Nam'),
(5, 'Áo Khoắc');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_hoadonxuat`
--

CREATE TABLE `tbl_hoadonxuat` (
  `hoadonxuat_id` int(11) NOT NULL,
  `khachhang_id` int(11) NOT NULL,
  `nhanvien_id` int(11) NOT NULL,
  `ngayxuat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_hoadonxuat`
--

INSERT INTO `tbl_hoadonxuat` (`hoadonxuat_id`, `khachhang_id`, `nhanvien_id`, `ngayxuat`) VALUES
(19, 1, 2, '2025-08-30 08:16:32'),
(20, 1, 2, '2025-08-30 08:20:02'),
(22, 1, 2, '2025-08-30 08:22:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_khachhang`
--

CREATE TABLE `tbl_khachhang` (
  `khachhang_id` int(11) NOT NULL,
  `khachhang_ten` varchar(255) NOT NULL,
  `khachhang_dienthoai` varchar(20) DEFAULT NULL,
  `khachhang_email` varchar(255) DEFAULT NULL,
  `khachhang_diachi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_khachhang`
--

INSERT INTO `tbl_khachhang` (`khachhang_id`, `khachhang_ten`, `khachhang_dienthoai`, `khachhang_email`, `khachhang_diachi`) VALUES
(1, 'Nguyễn A', '0968438573', 'na@gmail.com', 'TP. HCM'),
(2, 'Nguyễn Văn B', '0123454675', 'nvb@gmail.com', 'Huế'),
(3, 'Cao Thị C', '0123854732', 'ctc@gmail.com', 'Đà Nẵng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_nhanvien`
--

CREATE TABLE `tbl_nhanvien` (
  `nhanvien_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `nhanvien_ten` varchar(255) NOT NULL,
  `nhanvien_dienthoai` varchar(20) DEFAULT NULL,
  `nhanvien_email` varchar(255) DEFAULT NULL,
  `nhanvien_diachi` text DEFAULT NULL,
  `nhanvien_isactive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_nhanvien`
--

INSERT INTO `tbl_nhanvien` (`nhanvien_id`, `account_id`, `nhanvien_ten`, `nhanvien_dienthoai`, `nhanvien_email`, `nhanvien_diachi`, `nhanvien_isactive`) VALUES
(1, 1, 'Nguyễn Vỹ Nguyên', '0968015029', 'nguyen@gmail.com', 'Thành phố Huế', 1),
(2, 2, 'Nguyễn Văn A', '0968932141', 'nva@gmail.com', 'Hà Nội', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sanpham`
--

CREATE TABLE `tbl_sanpham` (
  `sanpham_id` int(11) NOT NULL,
  `sanpham_ma` varchar(255) NOT NULL,
  `danhmuc_id` int(11) NOT NULL,
  `sanpham_tieude` varchar(255) NOT NULL,
  `sanpham_gia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sanpham`
--

INSERT INTO `tbl_sanpham` (`sanpham_id`, `sanpham_ma`, `danhmuc_id`, `sanpham_tieude`, `sanpham_gia`) VALUES
(16, 'PL01', 4, 'Áo Polo 1', 150000),
(17, 'QJ01', 2, 'Quần Jean 1', 200000),
(18, 'PL03', 4, 'Áo Polo 3', 150000),
(19, 'PL02', 4, 'Quần Jean 2', 150000),
(20, 'QJ02', 2, 'Quần Jean 2', 200000),
(21, 'QT01', 2, 'Quần tây 1', 200000),
(24, 'PL04', 3, 'Áo Polo 4', 150000);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_username` (`account_username`);

--
-- Chỉ mục cho bảng `tbl_bienthe_sanpham`
--
ALTER TABLE `tbl_bienthe_sanpham`
  ADD PRIMARY KEY (`bienthe_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `tbl_chitiethoadonxuat`
--
ALTER TABLE `tbl_chitiethoadonxuat`
  ADD PRIMARY KEY (`chitiethoadonxuat_id`),
  ADD KEY `hoadonxuat_id` (`hoadonxuat_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  ADD PRIMARY KEY (`danhmuc_id`);

--
-- Chỉ mục cho bảng `tbl_hoadonxuat`
--
ALTER TABLE `tbl_hoadonxuat`
  ADD PRIMARY KEY (`hoadonxuat_id`),
  ADD KEY `khachhang_id` (`khachhang_id`),
  ADD KEY `nhanvien_id` (`nhanvien_id`);

--
-- Chỉ mục cho bảng `tbl_khachhang`
--
ALTER TABLE `tbl_khachhang`
  ADD PRIMARY KEY (`khachhang_id`);

--
-- Chỉ mục cho bảng `tbl_nhanvien`
--
ALTER TABLE `tbl_nhanvien`
  ADD PRIMARY KEY (`nhanvien_id`),
  ADD KEY `fk_nhanvien_account` (`account_id`);

--
-- Chỉ mục cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  ADD PRIMARY KEY (`sanpham_id`),
  ADD UNIQUE KEY `sanpham_ma` (`sanpham_ma`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_account`
--
ALTER TABLE `tbl_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_bienthe_sanpham`
--
ALTER TABLE `tbl_bienthe_sanpham`
  MODIFY `bienthe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `tbl_chitiethoadonxuat`
--
ALTER TABLE `tbl_chitiethoadonxuat`
  MODIFY `chitiethoadonxuat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `tbl_danhmuc`
--
ALTER TABLE `tbl_danhmuc`
  MODIFY `danhmuc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbl_hoadonxuat`
--
ALTER TABLE `tbl_hoadonxuat`
  MODIFY `hoadonxuat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `tbl_khachhang`
--
ALTER TABLE `tbl_khachhang`
  MODIFY `khachhang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_nhanvien`
--
ALTER TABLE `tbl_nhanvien`
  MODIFY `nhanvien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  MODIFY `sanpham_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_chitiethoadonxuat`
--
ALTER TABLE `tbl_chitiethoadonxuat`
  ADD CONSTRAINT `tbl_chitiethoadonxuat_ibfk_1` FOREIGN KEY (`hoadonxuat_id`) REFERENCES `tbl_hoadonxuat` (`hoadonxuat_id`),
  ADD CONSTRAINT `tbl_chitiethoadonxuat_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `tbl_sanpham` (`sanpham_id`);

--
-- Các ràng buộc cho bảng `tbl_hoadonxuat`
--
ALTER TABLE `tbl_hoadonxuat`
  ADD CONSTRAINT `tbl_hoadonxuat_ibfk_1` FOREIGN KEY (`khachhang_id`) REFERENCES `tbl_khachhang` (`khachhang_id`),
  ADD CONSTRAINT `tbl_hoadonxuat_ibfk_2` FOREIGN KEY (`nhanvien_id`) REFERENCES `tbl_nhanvien` (`nhanvien_id`);

--
-- Các ràng buộc cho bảng `tbl_nhanvien`
--
ALTER TABLE `tbl_nhanvien`
  ADD CONSTRAINT `fk_nhanvien_account` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`account_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
