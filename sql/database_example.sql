-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2020 at 04:45 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insurance_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `item` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `description` varchar(200) NOT NULL,
  `quantity` tinyint(4) NOT NULL DEFAULT '1',
  `unit_price` decimal(7,2) NOT NULL DEFAULT '0.00',
  `lost_depracation_amount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `spend_amount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `acv_paid` decimal(7,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`item`, `status`, `description`, `quantity`, `unit_price`, `lost_depracation_amount`, `spend_amount`, `acv_paid`) VALUES
(1, 5, 'Dell Precision M4700 Laptop', 1, '700.00', '703.11', '791.00', '87.89'),
(2, 1, 'Dell Dock/Port Replicator', 1, '110.00', '27.62', '124.30', '96.68'),
(3, 5, 'Dell Basic Wired Mouse', 1, '20.00', '20.09', '22.60', '2.51'),
(4, 1, 'Dell Vostro 3550 Laptop', 1, '200.00', '150.66', '226.00', '75.34'),
(6, 5, 'Microsoft Wireless Mouse', 1, '30.00', '7.54', '33.90', '26.36'),
(7, 3, 'Laptop Adapters', 2, '40.00', '20.09', '90.40', '70.31'),
(8, 5, 'Logitech WebCam', 1, '60.00', '60.26', '67.80', '7.54'),
(9, 5, 'Western Digital Hard Drive', 2, '100.00', '45.20', '226.00', '180.80'),
(10, 5, 'Logitech G930 Headset', 1, '120.00', '120.54', '135.60', '15.06'),
(11, 5, 'HTC One M7 Case', 1, '40.00', '18.08', '45.20', '27.12'),
(12, 5, 'EVGA GeForce GTX Video Card', 1, '270.00', '271.20', '305.10', '33.90'),
(13, 1, '8GB RAM Computer Component', 2, '110.00', '165.74', '248.60', '82.86'),
(14, 1, 'Digital Kitchen Scale', 1, '35.00', '23.73', '39.55', '15.82'),
(15, 5, 'Bathroom Scale', 1, '30.00', '10.17', '33.90', '23.73'),
(16, 1, 'Defender Wireless 2 Camera Sys.', 1, '200.00', '90.40', '226.00', '135.60'),
(17, 5, 'Digital Alarm Clock', 1, '20.00', '0.18', '22.60', '22.42'),
(18, 5, 'Bluetooth Speaker', 1, '30.00', '0.27', '33.90', '33.63'),
(19, 5, 'Voltage Tester', 1, '30.00', '0.29', '33.90', '33.61'),
(20, 1, 'US Backlit Laptop Keyboard', 1, '30.00', '1.28', '33.90', '32.62'),
(21, 5, 'Headphone Stand', 1, '15.00', '1.70', '16.95', '15.25'),
(22, 5, 'Bluetooth/Aux Reciever Adapters', 1, '20.00', '2.26', '22.60', '20.34'),
(23, 5, 'Everbrite 2pack Tactical pen light', 1, '20.00', '4.52', '22.60', '18.08'),
(24, 5, 'Brother PTD-400 Desktop Labeller', 1, '75.00', '37.66', '84.75', '47.09'),
(25, 4, 'ThermalTake Laptop Cooler Pad', 1, '35.00', '23.73', '39.55', '15.82'),
(26, 5, 'Panasonic Sound Bar with Subwoofer', 1, '200.00', '67.80', '226.00', '158.20'),
(27, 5, 'HDMI Splitter Hub', 1, '40.00', '13.56', '45.20', '31.64'),
(28, 5, 'Paper Shredder', 1, '60.00', '20.34', '67.80', '47.46'),
(29, 5, '24\" Blacklight Lightbulb Fixture', 2, '30.00', '61.02', '67.80', '6.78'),
(30, 5, 'Steel Utility Shelf', 1, '50.00', '8.48', '56.50', '48.02'),
(31, 5, 'Bicycle Stand', 1, '100.00', '56.50', '113.00', '56.50'),
(32, 5, 'Leather Futon Couch', 1, '200.00', '135.60', '226.00', '90.40'),
(33, 2, 'Queen Size Boxspring and Mattress', 1, '1900.00', '536.75', '2147.00', '1610.25'),
(34, 5, 'Night Stand', 1, '80.00', '31.64', '90.40', '58.76'),
(35, 1, 'Glass Shelf (4 Shelves)', 1, '150.00', '16.95', '169.50', '152.55'),
(36, 5, 'Garbage Can', 2, '20.00', '13.56', '45.20', '31.64'),
(37, 5, 'Corner Shelf', 3, '60.00', '40.68', '203.40', '162.72'),
(38, 5, 'Clothing Hanger Racks', 2, '40.00', '18.08', '90.40', '72.32'),
(39, 2, 'Home Theatre / Wall Unit', 1, '350.00', '79.10', '395.50', '316.40'),
(40, 5, 'Leather 2 Seater Couch', 1, '800.00', '723.20', '904.00', '180.80'),
(41, 2, 'Leather Ottoman', 1, '500.00', '452.00', '565.00', '113.00'),
(42, 5, 'Matercraft Toolbox', 1, '60.00', '10.17', '67.80', '57.63'),
(43, 5, 'Misc Tools', 1, '280.00', '31.64', '316.40', '284.76'),
(44, 5, 'SentrySafe Fire Chest', 1, '70.00', '9.49', '79.10', '69.61'),
(45, 5, 'SentrySafe 1cu ft. Safe', 1, '100.00', '11.30', '113.00', '101.70'),
(46, 5, 'Masterlock Lock Box', 1, '35.00', '19.78', '39.55', '19.77'),
(47, 5, 'SentrySafe Lock Box', 1, '25.00', '2.26', '28.25', '25.99'),
(48, 5, 'Laundry Hamper', 1, '60.00', '6.78', '67.80', '61.02'),
(49, 1, 'Handcuffs and Holster', 1, '40.00', '9.04', '45.20', '36.16'),
(50, 5, 'Special Ops Military Gloves', 1, '30.00', '6.78', '33.90', '27.12'),
(51, 5, 'Cheetah Print Throw Blanket', 1, '60.00', '20.34', '67.80', '47.46'),
(52, 4, 'College Textbooks', 1, '800.00', '504.00', '840.00', '336.00'),
(53, 1, 'Air Jordan Winter Jacket', 1, '250.00', '17.66', '282.50', '264.84'),
(54, 4, 'Calvin Klein Boxers', 3, '50.00', '113.00', '169.50', '56.50'),
(55, 4, 'Tommy Hilfiger Boxers', 3, '50.00', '113.00', '169.50', '56.50'),
(56, 4, 'Snapback Baseball Hats', 11, '35.00', '163.15', '435.05', '271.90'),
(57, 5, 'Black Tank Tops', 6, '15.00', '50.85', '101.70', '50.85'),
(58, 5, 'White Tank Tops', 3, '15.00', '25.43', '50.85', '25.42'),
(59, 4, 'Belt', 2, '30.00', '8.48', '67.80', '59.32'),
(60, 5, 'Guess Wallet', 1, '25.00', '25.43', '28.25', '2.82'),
(61, 5, 'Reebok Safety Shoes', 1, '175.00', '32.96', '197.75', '164.79'),
(62, 5, 'Air Jordan Sweaters', 3, '70.00', '118.65', '237.30', '118.65'),
(63, 5, 'Air Jordan Shorts', 4, '50.00', '197.75', '226.00', '28.25'),
(64, 2, 'Air Jordan Shoes', 4, '200.00', '301.34', '904.00', '602.66'),
(65, 4, 'Winter Gloves', 2, '20.00', '5.65', '45.20', '39.55'),
(66, 3, 'Air Jordan Spring Jacket', 5, '100.00', '353.13', '565.00', '211.87'),
(67, 5, 'Long Sleeve Shirts', 3, '40.00', '67.80', '135.60', '67.80'),
(68, 5, 'Laptop Backpack', 1, '60.00', '2.27', '67.80', '65.53'),
(69, 5, 'Adidas Gym Bag', 1, '60.00', '5.09', '67.80', '62.71'),
(70, 1, 'Levis Jeans', 1, '100.00', '14.13', '113.00', '98.87'),
(71, 5, 'Dickies Shirts', 6, '30.00', '50.85', '203.40', '152.55'),
(72, 5, 'Pillow', 1, '25.00', '8.48', '28.25', '19.77'),
(73, 5, 'Pillow', 1, '25.00', '8.48', '28.25', '19.77'),
(74, 1, 'Plastic Hangers', 40, '0.50', '1.13', '22.60', '21.47'),
(99, 3, 'Prescription Medication', 1, '34.00', '1.03', '38.32', '37.29'),
(149, 3, 'Belt', 1, '35.00', '9.89', '39.55', '29.66'),
(175, 4, 'Misc Content', 1, '20.00', '4.52', '22.60', '18.08'),
(237, 4, 'Misc Content Out of Dresser Drawer', 1, '20.00', '11.30', '22.60', '11.30'),
(254, 4, 'Box Of Lightbulbs', 1, '7.00', '0.63', '7.91', '7.28'),
(266, 4, 'James Patterson Big Bad Wolf Book', 1, '15.00', '7.88', '15.75', '7.87'),
(281, 4, 'Novel', 1, '7.00', '3.68', '7.35', '3.67'),
(286, 4, 'Pencil Case', 1, '5.00', '0.57', '5.65', '5.08'),
(310, 4, 'Canadian Firearms Safety Course Handbook', 1, '15.00', '7.88', '15.75', '7.87'),
(351, 4, 'Plastic Containers', 5, '2.00', '3.39', '11.30', '7.91'),
(398, 3, 'Toothpaste and Toothbrush', 2, '5.00', '2.83', '11.30', '8.47'),
(453, 4, 'Mouthwash', 1, '8.00', '0.72', '9.04', '8.32'),
(466, 3, 'Towels', 2, '10.00', '1.49', '22.60', '21.11'),
(599, 4, 'Halloween Decoration', 1, '10.00', '2.26', '11.30', '9.04'),
(637, 4, 'Nails', 1, '10.00', '5.65', '11.30', '5.65'),
(689, 4, 'Car Stereo', 1, '200.00', '203.40', '226.00', '22.60'),
(701, 4, 'Misc Content', 1, '20.00', '4.52', '22.60', '18.08'),
(708, 4, 'Phone Book', 1, '2.00', '1.05', '2.10', '1.05'),
(745, 3, 'Safety Glasses', 2, '4.00', '1.13', '9.04', '7.91'),
(781, 4, 'Book', 1, '20.00', '10.50', '21.00', '10.50'),
(807, 4, 'Christmas Decoration', 1, '10.00', '2.26', '11.30', '9.04'),
(841, 4, 'Books', 3, '10.00', '18.90', '31.50', '12.60'),
(853, 4, 'Converter', 1, '10.00', '7.54', '11.30', '3.76'),
(877, 3, 'Golf Bag Cover', 1, '40.00', '13.56', '45.20', '31.64'),
(892, 4, 'Books', 1, '10.00', '5.25', '10.50', '5.25'),
(917, 4, 'Decoration', 1, '10.00', '1.13', '11.30', '10.17'),
(966, 3, 'Empty Wooden Box', 1, '12.00', '5.42', '13.56', '8.14'),
(991, 4, 'Picture', 1, '40.00', '18.08', '45.20', '27.12'),
(1005, 4, 'Glove', 1, '12.00', '3.39', '13.56', '10.17'),
(1010, 5, '32 Inch Samsung TV', 1, '350.00', '158.20', '395.50', '237.30'),
(1012, 5, 'HTC Cell Phone', 1, '220.00', '223.74', '248.60', '24.86'),
(1013, 1, 'Samsung Cell Phone', 1, '350.00', '158.20', '395.50', '237.30'),
(1015, 5, 'Levis Strauss Jeans', 1, '90.00', '38.14', '101.70', '63.56'),
(1016, 1, 'Levis Strauss Jeans', 1, '90.00', '38.14', '101.70', '63.56'),
(1017, 4, 'Black Dickies Shorts', 1, '30.00', '12.71', '33.90', '21.19'),
(1018, 5, 'Blackk Tank Top', 1, '15.00', '2.12', '16.95', '14.83'),
(1019, 5, 'Red Jordan Tank Top', 1, '35.00', '9.89', '39.55', '29.66'),
(1020, 5, 'White Tommy Hilfiger Tank Top', 1, '15.00', '2.12', '16.95', '14.83'),
(1021, 5, 'Fine Leather Binder', 1, '70.00', '71.19', '79.10', '7.91'),
(1022, 5, 'White Tommy Hilfiger Tank Top', 2, '15.00', '4.24', '33.90', '29.66'),
(1023, 3, 'Black Fruit Of The Loom Socks', 6, '2.00', '9.04', '13.56', '4.52'),
(1024, 3, 'Gray Sock', 1, '1.00', '0.76', '1.13', '0.37'),
(1025, 3, 'Pair Of White Socks', 3, '4.00', '9.04', '13.56', '4.52'),
(1027, 4, 'Calvin Klein Boxers', 2, '35.00', '71.19', '79.10', '7.91'),
(1028, 5, 'Tommy Hilfiger Boxers', 2, '35.00', '71.19', '79.10', '7.91'),
(1029, 5, 'Various Prescription Meds', 1, '10.00', '1.89', '11.30', '9.41'),
(1031, 3, 'White Nikes / Jordans', 1, '180.00', '135.60', '203.40', '67.80'),
(1032, 5, 'Nintendo 64 With 2 Controllers', 1, '175.00', '79.10', '197.75', '118.65'),
(1033, 5, '8 Outlet Power Bar', 1, '30.00', '8.48', '33.90', '25.42'),
(1034, 5, 'Dirt Devil Handheld Vacuum', 1, '40.00', '13.56', '45.20', '31.64'),
(1035, 5, 'HP Laptop Bag', 1, '50.00', '16.95', '56.50', '39.55'),
(1036, 5, 'Misc. Bag', 1, '30.00', '16.95', '33.90', '16.95'),
(1038, 5, 'Nasal Spray', 1, '10.00', '1.89', '11.30', '9.41'),
(1040, 5, 'HP Deskjet Printer 3050A', 1, '60.00', '61.02', '67.80', '6.78'),
(1041, 5, 'Urban Rags Camo Hoodie', 1, '20.00', '5.65', '22.60', '16.95'),
(1042, 5, 'Pillow Cases', 2, '5.00', '9.04', '11.30', '2.26'),
(1043, 5, 'Blue Towel', 1, '5.00', '4.52', '5.65', '1.13'),
(1044, 5, 'Southpole Jeans', 2, '60.00', '33.90', '135.60', '101.70'),
(1045, 5, 'Pillowcase', 1, '3.00', '2.71', '3.39', '0.68'),
(1046, 4, 'Black Sweatpants', 1, '15.00', '6.36', '16.95', '10.59'),
(1047, 4, 'Southpole Jeans', 1, '30.00', '8.48', '33.90', '25.42'),
(1048, 5, 'Addidas Duffle Bag', 1, '60.00', '3.39', '67.80', '64.41'),
(1049, 4, 'File Folders', 20, '2.00', '1.13', '45.20', '44.07'),
(1050, 3, 'Fruit Of The Loom Sock pack', 1, '12.00', '9.04', '13.56', '4.52'),
(1051, 5, 'Packaged Bedding', 2, '50.00', '22.60', '113.00', '90.40'),
(1052, 5, 'Over the Counter Medication', 1, '10.00', '3.76', '11.30', '7.54'),
(1053, 1, 'BMX Bike', 1, '400.00', '135.60', '452.00', '316.40'),
(1054, 1, 'Switch Blade', 1, '100.00', '11.30', '113.00', '101.70'),
(1055, 5, 'Logitech Keyboard', 1, '60.00', '15.06', '67.80', '52.74'),
(1056, 5, 'Misc. Power Cords', 13, '10.00', '130.58', '146.90', '16.32'),
(1057, 5, 'Laundry Basket', 1, '10.00', '2.26', '11.30', '9.04'),
(1058, 5, 'Assorted Computer Cables', 1, '75.00', '75.34', '84.75', '9.41'),
(1059, 5, 'Assorted Computer Parts', 1, '175.00', '87.89', '197.75', '109.86'),
(1060, 1, 'Samsung Phone In Otterbox Case', 1, '200.00', '90.40', '226.00', '135.60'),
(1061, 5, 'Pack of 100 White Mailing Labels', 1, '20.00', '4.52', '22.60', '18.08'),
(1062, 5, 'Various Car Power Adapters', 4, '10.00', '6.78', '45.20', '38.42'),
(1063, 5, 'Various Computer Tools', 1, '70.00', '2.66', '79.10', '76.44'),
(1064, 5, 'Blue Kleton Storage Bin', 1, '15.00', '3.39', '16.95', '13.56'),
(1065, 5, 'APC Backup Power Supply', 1, '3.00', '1.50', '3.39', '1.89'),
(1066, 1, 'Assorted Mugs and Cups', 16, '2.00', '3.62', '36.16', '32.54'),
(1067, 5, 'Sterilite Tote', 1, '15.00', '3.39', '16.95', '13.56'),
(1068, 1, 'Samsung Cell Phone', 1, '250.00', '169.50', '282.50', '113.00'),
(1069, 5, 'Misc. Office Supplies', 1, '100.00', '11.30', '113.00', '101.70'),
(1070, 5, 'Nightstand / Side Table', 1, '80.00', '22.60', '90.40', '67.80'),
(1071, 1, 'Soldering Kit', 1, '50.00', '1.41', '56.50', '55.09'),
(1072, 5, 'Glass Shelf Side Table', 1, '100.00', '11.30', '113.00', '101.70'),
(1073, 5, 'HP Printer', 1, '150.00', '75.34', '169.50', '94.16'),
(1074, 2, '32 Inch TV', 1, '400.00', '90.40', '452.00', '361.60'),
(1075, 5, 'Xbox Controller', 1, '40.00', '2.98', '45.20', '42.22'),
(1076, 5, 'Pack Of Printing Paper', 1, '10.00', '0.00', '11.30', '11.30'),
(1077, 1, 'Set of 4 Rims and Tires', 4, '300.00', '1220.40', '1356.00', '135.60'),
(1078, 5, '5 Drawer Dresser', 1, '200.00', '67.80', '226.00', '158.20'),
(1086, 5, 'Exco Jeans Winter Jacket', 1, '200.00', '203.40', '226.00', '22.60'),
(1099, 4, 'Assorted Books', 8, '10.00', '42.00', '84.00', '42.00'),
(1111, 4, 'Picture Frane', 1, '25.00', '8.48', '28.25', '19.77'),
(1125, 3, 'Medication', 30, '0.50', '1.86', '16.95', '15.09'),
(1126, 3, 'Medication', 30, '1.00', '5.65', '33.90', '28.25'),
(1130, 4, 'Ceiling Light Bulbs', 2, '5.00', '10.17', '11.30', '1.13'),
(1149, 4, 'Rogers Cloth Bag', 1, '10.00', '8.48', '11.30', '2.82'),
(1171, 2, 'Gaming Desktop Computer', 1, '2902.99', '2186.92', '3280.38', '1093.46'),
(1176, 1, 'WD External Hard Drive', 1, '74.99', '6.22', '84.74', '78.52'),
(1178, 1, 'HP Compaq Laptop', 1, '399.98', '42.18', '451.98', '409.80'),
(1197, 3, '17inch LCD Monitor', 1, '89.98', '67.79', '101.68', '33.89'),
(1202, 4, 'Rasfox HDMI Hub', 1, '89.89', '67.72', '101.58', '33.86'),
(1205, 1, 'Philips Subwoofer', 1, '59.97', '20.33', '67.77', '47.44'),
(1207, 4, 'APC UPS Battery Backup', 1, '251.94', '189.79', '284.69', '94.90'),
(1209, 4, 'Generic Power Bar', 2, '14.38', '13.00', '32.50', '19.50'),
(1210, 4, 'Logitech Corded Gaming Mouse', 1, '49.99', '50.22', '56.49', '6.27'),
(1218, 1, 'Epson Printer', 1, '129.99', '130.57', '146.89', '16.32'),
(1231, 4, 'PS3 Game System', 1, '179.99', '162.71', '203.39', '40.68'),
(1234, 4, 'Computer Speakers', 1, '24.99', '25.10', '28.24', '3.14'),
(1237, 4, 'D-Link Router', 1, '49.99', '37.66', '56.49', '18.83'),
(1240, 4, 'Generic Power Bar', 1, '14.99', '6.78', '16.94', '10.16'),
(1242, 4, 'Conair Nose Hair Trimmer', 1, '19.97', '9.03', '22.57', '13.54'),
(1243, 4, 'Gillette Beard Hair Trimmer', 1, '24.97', '14.12', '28.22', '14.10'),
(1247, 4, 'Generic Power Bar', 1, '14.99', '3.39', '16.94', '13.55'),
(1250, 1, 'HP Desktop Computer', 1, '499.99', '376.66', '564.99', '188.33'),
(1254, 4, 'Nintendo 64', 1, '249.98', '225.98', '282.48', '56.50'),
(1255, 4, 'Belkin Power Bar', 1, '51.39', '23.23', '58.07', '34.84'),
(1257, 4, 'Samsung Cell Phone', 1, '229.99', '233.90', '259.89', '25.99'),
(1258, 4, 'HTC Cell Phone', 1, '199.99', '180.79', '225.99', '45.20'),
(1262, 1, 'APC Power Bar', 1, '27.49', '12.43', '31.06', '18.63'),
(1264, 1, 'HDMI Cable or Adapter', 1, '30.99', '14.01', '35.02', '21.01'),
(1265, 1, 'Brother Label Printer', 1, '87.98', '39.77', '99.42', '59.65'),
(1266, 3, 'Staples Power Surge Adapter', 1, '39.99', '18.08', '45.19', '27.11'),
(1268, 4, 'Generic 4 Port Hub', 1, '39.98', '30.12', '45.18', '15.06'),
(1269, 1, 'Defender Security Camera', 1, '119.99', '40.68', '135.59', '94.91'),
(1270, 4, 'Logitech Corded Mouse', 1, '24.99', '25.10', '28.24', '3.14'),
(1272, 1, 'TV', 1, '199.99', '90.40', '225.99', '135.59'),
(1273, 1, 'Cable AV To HDMI Adapter', 1, '54.99', '12.43', '62.14', '49.71'),
(1274, 1, 'Computer RAM', 1, '109.99', '82.86', '124.29', '41.43'),
(1275, 4, 'HP Corded Mouse', 1, '21.58', '16.26', '24.39', '8.13'),
(1276, 4, 'Creative Webcam', 1, '34.99', '26.36', '39.54', '13.18'),
(1277, 4, 'Microsoft Webcam for Xbox', 1, '19.67', '19.75', '22.23', '2.48'),
(1279, 3, 'Linksys Router', 1, '132.24', '99.62', '149.43', '49.81'),
(1280, 4, 'USB Cables', 10, '9.98', '75.18', '112.77', '37.59'),
(1281, 3, 'D-Slot Power Cables', 5, '12.99', '22.02', '73.39', '51.37'),
(1282, 4, 'RCA Cables', 6, '11.99', '32.52', '81.29', '48.77'),
(1283, 4, 'AV Cables', 4, '5.64', '10.19', '25.49', '15.30'),
(1284, 4, 'Power Cables', 5, '9.99', '22.58', '56.44', '33.86'),
(1285, 4, 'Ethernet Cables', 10, '4.97', '37.44', '56.16', '18.72'),
(1303, 4, 'DeWalt Cordless Circular Saw', 1, '115.00', '25.99', '129.95', '103.96');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `pushStateAddr` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page`, `title`, `pushStateAddr`) VALUES
('all', 'All Items', 'index.html?view=all'),
('finalized', 'Finalized', 'index.html?view=finalized'),
('notreplaced', 'Not Replaced', 'index.html?view=notreplaced'),
('partial', 'Partial', 'index.html?view=partial'),
('replaced', 'Replaced', 'index.html?view=replaced'),
('search', 'Search', 'index.html?view=search'),
('stats', 'Stats', 'index.html'),
('submitted', 'Submitted', 'index.html?view=submitted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`item`),
  ADD UNIQUE KEY `item` (`item`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page`),
  ADD UNIQUE KEY `page` (`page`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
