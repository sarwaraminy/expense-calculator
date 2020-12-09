-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2020 at 07:22 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecalc`
--

-- --------------------------------------------------------

--
-- Table structure for table `banktrans`
--

CREATE TABLE `banktrans` (
  `ID` int(11) NOT NULL,
  `ACCT` varchar(20) NOT NULL,
  `BNK` varchar(20) NOT NULL,
  `TYPE` varchar(20) NOT NULL,
  `TCODE` varchar(20) NOT NULL,
  `TSEQ` varchar(20) NOT NULL,
  `TAMOUNT` decimal(20,0) NOT NULL,
  `TRNBY` varchar(20) NOT NULL,
  `BRANCH` varchar(20) NOT NULL,
  `INSERTED_BY` varchar(20) NOT NULL,
  `TDATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CATEGORY` varchar(50) NOT NULL,
  `INSERTED_BY` varchar(20) NOT NULL,
  `INSERTED_DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `homespend`
--

CREATE TABLE `homespend` (
  `ID_NO` int(11) NOT NULL,
  `THINGS_NAME` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(150) NOT NULL,
  `CATEGORY` varchar(50) NOT NULL,
  `PRICE` decimal(10,0) NOT NULL,
  `NUMBER_OF` int(11) NOT NULL,
  `TOTAL_PRICE` int(11) NOT NULL,
  `SPEND_BY` varchar(20) NOT NULL,
  `INSERTED_BY` varchar(20) NOT NULL,
  `INSERT_DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `homespend`
--

INSERT INTO `homespend` (`ID_NO`, `THINGS_NAME`, `DESCRIPTION`, `CATEGORY`, `PRICE`, `NUMBER_OF`, `TOTAL_PRICE`, `SPEND_BY`, `INSERTED_BY`, `INSERT_DATE`) VALUES
(0, '', '', '', '0', 0, 0, '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `ID` int(11) NOT NULL,
  `USD` decimal(20,0) NOT NULL,
  `EXCHANGE` decimal(20,0) NOT NULL,
  `EXCHRESULT` decimal(20,0) NOT NULL,
  `AFN` decimal(20,0) NOT NULL,
  `TOTAL_INC` decimal(20,0) NOT NULL,
  `INCOME_BY` varchar(20) NOT NULL,
  `INSETED_BY` varchar(20) NOT NULL,
  `INSEDED_DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `User_FName` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `User_LName` varchar(20) DEFAULT NULL,
  `Register_Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `User_FName`, `password`, `User_LName`, `Register_Date`) VALUES
('samini', 'Mohammad Sarwar', '123456', '', '2020-12-05 13:54:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
