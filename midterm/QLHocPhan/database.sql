-- Tạo cơ sở dữ liệu QLHocPhan
CREATE DATABASE IF NOT EXISTS `QLHocPhan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `QLHocPhan`;

-- Bảng GiangVien: IDGV, Fullname, Image, SumClass
DROP TABLE IF EXISTS `GiangVien`;
CREATE TABLE `GiangVien` (
    `IDGV` VARCHAR(20) NOT NULL PRIMARY KEY,
    `Fullname` VARCHAR(100) NOT NULL,
    `Image` VARCHAR(255) NULL,
    `SumClass` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Dữ liệu mẫu ban đầu
INSERT INTO `GiangVien` (`IDGV`, `Fullname`, `Image`, `SumClass`) VALUES
('GV01', 'TS. Trần Văn Đại', 'gv01.jpg', 4),
('GV02', 'ThS. Ngô Lê Quân', 'gv02.jpg', 6),
('GV03', 'ThS. Nguyễn Ngọc Huyền Trân', 'gv03.jpg', 5);
