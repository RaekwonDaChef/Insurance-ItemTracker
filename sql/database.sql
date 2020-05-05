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
