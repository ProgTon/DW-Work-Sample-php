-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2017 at 08:25 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank_transactions`
--

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `Filename` varchar(255) NOT NULL COMMENT 'This is the name of the CSV file.',
  `TotalNumberOfRows` int(11) DEFAULT NULL COMMENT 'The number of rows in the file.',
  `RowsWithErrors` int(11) DEFAULT NULL COMMENT 'The number of rows that could not be parsed.',
  `StartDate` timestamp NULL DEFAULT NULL COMMENT 'Timestamp for when the parsing of the file was initiated.',
  `EndDate` timestamp NULL DEFAULT NULL COMMENT 'Timestamp for when the parsing of the file was finished.',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp for when the row was created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactiondescription`
--

CREATE TABLE `transactiondescription` (
  `TransactionDescriptionPK` int(11) NOT NULL,
  `Description` varchar(33) NOT NULL COMMENT 'Corresponds to the column “Description” in the CSV file.',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp for when the row was created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` int(11) NOT NULL COMMENT 'Corresponds to the column “ID” in the CSV file.',
  `TransactionDate` date NOT NULL COMMENT 'Corresponds to the column “Date” in the CSV file.',
  `Amount` decimal(11,2) NOT NULL COMMENT 'Corresponds to the column “Amount” in the CSV file.',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp for when the row was created.',
  `TransactionDescriptionPK` int(11) NOT NULL COMMENT 'Foreign key to the TransactionDescription table.',
  `FilePK` varchar(255) NOT NULL COMMENT 'Foreign key to the File table.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`Filename`);

--
-- Indexes for table `transactiondescription`
--
ALTER TABLE `transactiondescription`
  ADD PRIMARY KEY (`TransactionDescriptionPK`),
  ADD UNIQUE KEY `Description` (`Description`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `TransactionDescriptionPK` (`TransactionDescriptionPK`),
  ADD KEY `FilePK` (`FilePK`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactiondescription`
--
ALTER TABLE `transactiondescription`
  MODIFY `TransactionDescriptionPK` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
