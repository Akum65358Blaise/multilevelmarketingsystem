-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 19, 2019 at 01:14 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secureprimeinvestment`
--

-- --------------------------------------------------------

--
-- Table structure for table `bonus`
--

DROP TABLE IF EXISTS `bonus`;
CREATE TABLE IF NOT EXISTS `bonus` (
  `SN` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Amount` int(20) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Not Paid',
  `PaymentDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SN`),
  KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `helpreceived`
--

DROP TABLE IF EXISTS `helpreceived`;
CREATE TABLE IF NOT EXISTS `helpreceived` (
  `SN` varchar(10) NOT NULL,
  `MatchingNo` varchar(10) NOT NULL,
  `PledgeNo` varchar(10) NOT NULL,
  `AmountToReceive` int(20) NOT NULL,
  `AmountReceived` int(20) DEFAULT '0',
  `Balance` int(20) GENERATED ALWAYS AS ((`AmountToReceive` - `AmountReceived`)) VIRTUAL,
  `LastDateToReceive` datetime NOT NULL,
  `ModificationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  KEY `MatchingNo` (`MatchingNo`),
  KEY `PledgeNo` (`PledgeNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `helprequest`
--

DROP TABLE IF EXISTS `helprequest`;
CREATE TABLE IF NOT EXISTS `helprequest` (
  `SN` varchar(8) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Amount` int(20) NOT NULL,
  `PledgeDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Matched` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`SN`),
  KEY `Username` (`Username`),
  KEY `Amount` (`Amount`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `helptoreceive`
--

DROP TABLE IF EXISTS `helptoreceive`;
CREATE TABLE IF NOT EXISTS `helptoreceive` (
  `SN` varchar(10) NOT NULL,
  `PledgeNo` varchar(10) NOT NULL,
  `AmountToReceive` int(20) NOT NULL,
  `AmountReceived` int(20) DEFAULT '0',
  `Balance` int(20) GENERATED ALWAYS AS ((`AmountToReceive` - `AmountReceived`)) VIRTUAL,
  `LastDateToReceive` datetime NOT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Available',
  `ModificationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `PledgeNo_2` (`PledgeNo`),
  KEY `PledgeNo` (`PledgeNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matching`
--

DROP TABLE IF EXISTS `matching`;
CREATE TABLE IF NOT EXISTS `matching` (
  `SN` varchar(10) NOT NULL,
  `PledgeNo` varchar(10) NOT NULL,
  `MatchTo` varchar(100) NOT NULL,
  `MatchToNo` varchar(10) NOT NULL,
  `MatchDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LastDateToPay` datetime NOT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Not Paid',
  `Proof` varchar(255) DEFAULT NULL,
  `ConfirmationDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SN`),
  KEY `PledgeNo` (`PledgeNo`),
  KEY `MatchTo` (`MatchTo`),
  KEY `MatchToNo` (`MatchToNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Phone` int(9) NOT NULL,
  `MyReferalID` varchar(100) NOT NULL,
  `ReferalID` varchar(100) DEFAULT NULL,
  `RegistrationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `FirstName` (`FirstName`,`LastName`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Phone` (`Phone`),
  UNIQUE KEY `MyReferalID` (`MyReferalID`),
  KEY `ReferalID` (`ReferalID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`SN`, `FirstName`, `LastName`, `Username`, `Email`, `Phone`, `MyReferalID`, `ReferalID`, `RegistrationDate`) VALUES
(1, 'Ngoe', 'Kolle', 'Ngoe Kolle', 'kollengoe@outlook.com', 676080809, 'Ngoe Kolle', 'Belinda Itua', '2019-10-16 07:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `momoaccount`
--

DROP TABLE IF EXISTS `momoaccount`;
CREATE TABLE IF NOT EXISTS `momoaccount` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `MomoCarrier` varchar(50) NOT NULL,
  `MomoName` varchar(100) NOT NULL,
  `MomoNumber` int(9) NOT NULL,
  `ModificationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `MomoNumber` (`MomoNumber`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `News` varchar(655) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `Amount` (`News`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE IF NOT EXISTS `newsletter` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telephone` int(9) NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `Telephone` (`Telephone`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `Amount` int(20) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `Amount` (`Amount`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`SN`, `Amount`, `Active`, `Date`) VALUES
(1, 0, 0, '2019-10-19 10:26:52'),
(2, 5000, 1, '2019-10-16 08:21:41'),
(3, 10000, 1, '2019-10-16 08:21:45'),
(4, 15000, 1, '2019-10-16 08:21:51'),
(5, 20000, 1, '2019-10-16 08:21:56'),
(6, 25000, 1, '2019-10-16 08:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `referalbonus`
--

DROP TABLE IF EXISTS `referalbonus`;
CREATE TABLE IF NOT EXISTS `referalbonus` (
  `SN` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `PledgeNo` varchar(10) NOT NULL,
  `Amount` int(20) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Not Paid',
  `PaymentDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SN`),
  KEY `Username` (`Username`),
  KEY `PledgeNo` (`PledgeNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testimony`
--

DROP TABLE IF EXISTS `testimony`;
CREATE TABLE IF NOT EXISTS `testimony` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `Testimony` varchar(6555) NOT NULL,
  `SharedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `SN` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Password` varchar(128) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Profile` varchar(128) DEFAULT NULL,
  `RegistrationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`),
  UNIQUE KEY `Username_2` (`Username`),
  UNIQUE KEY `Username` (`Username`,`Email`,`Status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`SN`, `Username`, `Email`, `Password`, `Status`, `Active`, `Profile`, `RegistrationDate`) VALUES
(1, 'Kolle Ngoe', 'kollengoe@outlook.com', '$2y$10$tbLpS6ELyhHfMCIRYIvSiuewBgyO4Isv2uJPqThjifCDdm32P4xsS', 'Administrator', 1, 'profiles/Kolle Ngoe_2019_10_19 09-09-43.jpg', '2019-10-11 10:33:43');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bonus`
--
ALTER TABLE `bonus`
  ADD CONSTRAINT `bonus_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `members` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `helpreceived`
--
ALTER TABLE `helpreceived`
  ADD CONSTRAINT `helpreceived_ibfk_2` FOREIGN KEY (`MatchingNo`) REFERENCES `matching` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `helpreceived_ibfk_3` FOREIGN KEY (`PledgeNo`) REFERENCES `helprequest` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `helprequest`
--
ALTER TABLE `helprequest`
  ADD CONSTRAINT `helprequest_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `members` (`Username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `helprequest_ibfk_2` FOREIGN KEY (`Amount`) REFERENCES `package` (`Amount`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `helptoreceive`
--
ALTER TABLE `helptoreceive`
  ADD CONSTRAINT `helptoreceive_ibfk_1` FOREIGN KEY (`PledgeNo`) REFERENCES `helprequest` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `matching`
--
ALTER TABLE `matching`
  ADD CONSTRAINT `matching_ibfk_1` FOREIGN KEY (`PledgeNo`) REFERENCES `helprequest` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matching_ibfk_2` FOREIGN KEY (`MatchTo`) REFERENCES `members` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matching_ibfk_3` FOREIGN KEY (`MatchToNo`) REFERENCES `helprequest` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `momoaccount`
--
ALTER TABLE `momoaccount`
  ADD CONSTRAINT `momoaccount_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `members` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referalbonus`
--
ALTER TABLE `referalbonus`
  ADD CONSTRAINT `referalbonus_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `members` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `referalbonus_ibfk_2` FOREIGN KEY (`PledgeNo`) REFERENCES `helprequest` (`SN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `testimony`
--
ALTER TABLE `testimony`
  ADD CONSTRAINT `testimony_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `members` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
