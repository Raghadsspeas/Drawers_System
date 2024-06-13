-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 09 مايو 2024 الساعة 22:53
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drawersystem`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admint`
--

CREATE TABLE `admint` (
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admint`
--

INSERT INTO `admint` (`Email`, `Password`) VALUES
('Admin@gmail.com', '$2y$10$6R.VuJ/sZR0MTtWJsWIMRuO3OhXiOeZHdUjNggwwkooT/BLlrZ9TC');

-- --------------------------------------------------------

--
-- بنية الجدول `ownert`
--

CREATE TABLE `ownert` (
  `Name` text NOT NULL,
  `OwnerID` varchar(10) NOT NULL,
  `PhoneNumber` varchar(10) NOT NULL,
  `Address` text NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `MACAddress` varchar(20) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `ownert`
--

INSERT INTO `ownert` (`Name`, `OwnerID`, `PhoneNumber`, `Address`, `Password`, `Email`, `MACAddress`, `reset_token`) VALUES
('بيادر حسين اليوبي', '1067429876', '0587329764', '(21.561808867938176, 39.158363342285156)', '$2y$10$gHxV7/W0oDMMelnLxVq1wOdXWhZOc0juSKna4REN4.lYvCL4f7SUe', 'byder@gmail.com', '03:0D:3F:5D:02:5F', NULL),
('رغد حمدان المطيري', '1076542987', '0598342187', '(21.533461452045486, 39.2113208770752)', '$2y$10$dD6CQ4DAdLEkcgEVhAv77uIo4Jd8iLhuhjxqBzmmqeWV/Xei40cti', 'raghad@gmail.com', '00:B0:D0:63:C2:26', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `police`
--

CREATE TABLE `police` (
  `Name` varchar(255) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Code` varchar(6) NOT NULL,
  `availability` enum('متاح','غير متاح') NOT NULL,
  `Position` text NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `police`
--

INSERT INTO `police` (`Name`, `EmployeeID`, `Email`, `Password`, `Code`, `availability`, `Position`, `reset_token`) VALUES
('محمد عمر مالك', 1011165342, 'mohmmmed@moi.gov.sa', '$2y$10$/LmzqfeE4u9p/KioAMVubuCX5OnQwVqtnt1LFQOi7TeNCaMi8YB6O', 'PS1234', 'متاح', 'officer', NULL),
('ابراهيم حسن علي', 1082543765, 'ibrahem@moi.gov.sa', '$2y$10$QW6iDlzfS9EieL4GGJ0WGOfNgP6m7FdzxuKsZYhqcmDj3YF1JNiJy', 'PS1243', 'متاح', 'police', NULL),
('عبدالله عمر احمد', 1087631097, 'abdullah@moi.gov.sa', '$2y$10$l741OOnn7SOwshjmEad1YuQMiueEnI8d3LNh9lQl3tMgL4OudCnju', 'PS1232', 'متاح', 'police', NULL),
('نبيل عاطي', 1098760321, 'nabeel@moi.gov.sa', '$2y$10$mO4Zl9mk/5vHSGEMJfX93O4vJs/A0R7LDLZvPTQecc8M.QoRzteHS', 'PS1243', 'متاح', 'police', NULL),
('حسين علي', 1987520987, 'hasean@moi.gov.sa', '$2y$10$FGCnoorPaasytJ67U3QTZOQhpx8sGvwNMH6kiX7m3FnS/z2qxrKXG', 'PS4123', 'متاح', 'police', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `policemember`
--

CREATE TABLE `policemember` (
  `Name` varchar(255) NOT NULL,
  `EmployeeID` varchar(10) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Code` varchar(6) NOT NULL,
  `Position` varchar(255) DEFAULT 'police'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `policemember`
--

INSERT INTO `policemember` (`Name`, `EmployeeID`, `Email`, `Code`, `Position`) VALUES
('محمد عمر مالك', '1011165342', 'mohmmmed@moi.gov.sa', 'PS1234', 'officer'),
('ابراهيم حسن علي', '1082543765', 'ibrahem@moi.gov.sa', 'PS1243', 'police'),
('عبدالله عمر احمد', '1087631097', 'abdullah@moi.gov.sa', 'PS1232', 'police'),
('نبيل عاطي', '1098760321', 'nabeel@moi.gov.sa', 'PS1243', 'police'),
('حسين علي', '1987520987', 'hasean@moi.gov.sa', 'PS4123', 'police');

-- --------------------------------------------------------

--
-- بنية الجدول `policestation`
--

CREATE TABLE `policestation` (
  `PhoneNumber` varchar(10) NOT NULL,
  `Address` text NOT NULL,
  `Code` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `policestation`
--

INSERT INTO `policestation` (`PhoneNumber`, `Address`, `Code`) VALUES
('0533333333', '(21.55507652135688, 39.16968226432801)', 'PS1232'),
('0522222222', '(21.583818442205967, 39.16692495346069)', 'PS1234'),
('0544444444', '(21.511214329771807, 39.183141589164734)', 'PS1243'),
('0511111111', '(21.695846422452583, 39.11055564880371)', 'PS2222'),
('0566666666', '(21.512636690439724, 39.188511371612556)', 'PS4123');

-- --------------------------------------------------------

--
-- بنية الجدول `police_permissions`
--

CREATE TABLE `police_permissions` (
  `permission_id` int(11) NOT NULL,
  `policeId` int(11) NOT NULL,
  `ReportID` int(11) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_print` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `police_permissions`
--

INSERT INTO `police_permissions` (`permission_id`, `policeId`, `ReportID`, `can_view`, `can_edit`, `can_print`) VALUES
(60, 1087631097, 100011, 1, 1, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `reports`
--

CREATE TABLE `reports` (
  `ReportID` int(11) NOT NULL,
  `Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `MACAddress` varchar(20) NOT NULL,
  `policeId` varchar(11) NOT NULL,
  `acceptance_status` varchar(50) DEFAULT 'جديد',
  `Note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `reports`
--

INSERT INTO `reports` (`ReportID`, `Time`, `MACAddress`, `policeId`, `acceptance_status`, `Note`) VALUES
(100011, '2024-05-09 20:40:22', '03:0D:3F:5D:02:5F', '1087631097', 'تم الإنتهاء', 'تم الاشراف على البلاغ');

--
-- القوادح `reports`
--
DELIMITER $$
CREATE TRIGGER `insert_police_permissions` AFTER INSERT ON `reports` FOR EACH ROW BEGIN
    INSERT INTO police_permissions (ReportID, policeId, can_view, can_edit, can_print)
    VALUES (NEW.ReportID, NEW.policeId, 1, 1, 1);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_police_permissions` AFTER UPDATE ON `reports` FOR EACH ROW BEGIN
    UPDATE police_permissions
    SET policeId = NEW.policeId
    WHERE ReportID = NEW.ReportID;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admint`
--
ALTER TABLE `admint`
  ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `ownert`
--
ALTER TABLE `ownert`
  ADD PRIMARY KEY (`OwnerID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `OwnerID` (`OwnerID`),
  ADD KEY `MACAddress` (`MACAddress`);

--
-- Indexes for table `police`
--
ALTER TABLE `police`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `EmployeeID` (`EmployeeID`),
  ADD KEY `Code` (`Code`);

--
-- Indexes for table `policemember`
--
ALTER TABLE `policemember`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `policestation`
--
ALTER TABLE `policestation`
  ADD PRIMARY KEY (`Code`),
  ADD KEY `Code` (`Code`),
  ADD KEY `PhoneNumber` (`PhoneNumber`);

--
-- Indexes for table `police_permissions`
--
ALTER TABLE `police_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `ReportID` (`ReportID`),
  ADD KEY `EmployeeID` (`policeId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `MACAddress` (`MACAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `police_permissions`
--
ALTER TABLE `police_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100012;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `police_permissions`
--
ALTER TABLE `police_permissions`
  ADD CONSTRAINT `police_permissions_ibfk_1` FOREIGN KEY (`ReportID`) REFERENCES `reports` (`ReportID`);

--
-- قيود الجداول `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`MACAddress`) REFERENCES `ownert` (`MACAddress`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
