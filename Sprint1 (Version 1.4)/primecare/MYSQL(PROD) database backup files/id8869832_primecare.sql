-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
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
-- Database: `id8869832_primecare`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `departmentID` int(11) NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(35) NOT NULL,
  PRIMARY KEY (`departmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`departmentID`, `departmentName`) VALUES
(1, 'General Care'),
(2, 'General Surgery'),
(3, 'Neurology'),
(4, 'Maternity'),
(5, 'Ophthalmology'),
(6, 'Orphaepedics'),
(7, 'Gastroentology'),
(8, 'Cardiology'),
(9, 'Critical Care'),
(10, 'Accident and Emergency'),
(11, 'Radiotherapy'),
(12, 'Intensive Care');

-- --------------------------------------------------------

--
-- Table structure for table `doctorassignedtopatient`
--

DROP TABLE IF EXISTS `doctorassignedtopatient`;
CREATE TABLE IF NOT EXISTS `doctorassignedtopatient` (
  `patientID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`patientID`,`userID`),
  KEY `FK_userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

DROP TABLE IF EXISTS `drug`;
CREATE TABLE IF NOT EXISTS `drug` (
  `drugID` int(11) NOT NULL AUTO_INCREMENT,
  `medicineName` varchar(35) NOT NULL,
  `amountRemaining` int(11) NOT NULL,
  `dose` decimal(20,1) NOT NULL,
  `warning` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`drugID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drug`
--

INSERT INTO `drug` (`drugID`, `medicineName`, `amountRemaining`, `dose`, `warning`, `description`) VALUES
(1, 'first medicine', 4, '0.7', 'First warning', 'This is medicine #1'),
(2, 'second medicine', 5, '0.8', 'second warning', 'This is medicine #2');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `patientID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(35) NOT NULL,
  `lastName` varchar(35) NOT NULL,
  `roomNumber` int(11) NOT NULL,
  PRIMARY KEY (`patientID`),
  KEY `FK_roomNumber` (`roomNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patientID`, `firstName`, `lastName`, `roomNumber`) VALUES
(1, 'John', 'Doe', 100),
(2, 'Nicholas', 'Cage', 110),
(10, 'Lord', 'Farquaad', 101),
(11, 'Shrek', 'Ogre', 300);

--
-- Triggers `patient`
--
DROP TRIGGER IF EXISTS `tPatientRoom`;
DELIMITER $$
CREATE TRIGGER `tPatientRoom` AFTER INSERT ON `patient` FOR EACH ROW UPDATE room
SET patientsAssigned = patientsAssigned + 1
WHERE roomNumber = (SELECT roomNumber FROM patient WHERE patientID = new.patientID)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `patientassignedtotest`
--

DROP TABLE IF EXISTS `patientassignedtotest`;
CREATE TABLE IF NOT EXISTS `patientassignedtotest` (
  `patientID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  PRIMARY KEY (`patientID`,`testID`),
  KEY `FK_testID` (`testID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patientassignedtotest`
--

INSERT INTO `patientassignedtotest` (`patientID`, `testID`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `patientassignedtotreatment`
--

DROP TABLE IF EXISTS `patientassignedtotreatment`;
CREATE TABLE IF NOT EXISTS `patientassignedtotreatment` (
  `patientID` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL,
  PRIMARY KEY (`patientID`,`treatmentID`),
  KEY `FK_treatmentID` (`treatmentID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patientassignedtotreatment`
--

INSERT INTO `patientassignedtotreatment` (`patientID`, `treatmentID`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

DROP TABLE IF EXISTS `prescription`;
CREATE TABLE IF NOT EXISTS `prescription` (
  `doctorOrderNumber` int(11) NOT NULL,
  `orderDetails` varchar(200) NOT NULL,
  `drugID` int(11) NOT NULL,
  PRIMARY KEY (`doctorOrderNumber`),
  KEY `FK_drugID` (`drugID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`doctorOrderNumber`, `orderDetails`, `drugID`) VALUES
(1, 'First order', 1),
(2, 'Second order', 2);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `roomNumber` int(11) NOT NULL AUTO_INCREMENT,
  `departmentID` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `maxCapacity` int(11) NOT NULL,
  `patientsAssigned` int(11) DEFAULT NULL,
  PRIMARY KEY (`roomNumber`),
  KEY `FK_deptID` (`departmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=345 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`roomNumber`, `departmentID`, `description`, `maxCapacity`, `patientsAssigned`) VALUES
(100, 9, '4 bed room on the first floor', 4, 1),
(101, 9, '4 bed room on the first floor', 4, 1),
(102, 9, '4 bed room on the first floor', 4, 0),
(103, 9, '4 bed room on the first floor', 4, 0),
(104, 9, '4 bed room on the first floor', 4, 0),
(105, 9, '4 bed room on the first floor', 4, 0),
(110, 12, '2 bed room on the first floor', 2, 1),
(111, 12, '2 bed room on the first floor', 2, 0),
(112, 12, '2 bed room on the first floor', 2, 0),
(113, 12, '2 bed room on the first floor', 2, 0),
(114, 12, '2 bed room on the first floor', 2, 0),
(115, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(116, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(117, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(118, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(119, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(130, 1, '4 bed room on the first floor', 4, 0),
(131, 1, '4 bed room on the first floor', 4, 0),
(132, 1, '4 bed room on the first floor', 4, 0),
(133, 1, '4 bed room on the first floor', 4, 0),
(134, 1, '4 bed room on the first floor', 4, 0),
(150, 4, '2 bed room for maternity department', 2, 0),
(151, 4, '2 bed room for maternity department', 2, 0),
(152, 4, '2 bed room for maternity department', 2, 0),
(153, 4, '2 bed room for maternity department', 2, 0),
(154, 4, '2 bed room for maternity department', 2, 0),
(160, 4, 'Nursery', 20, 0),
(170, 10, 'Waiting Area', 30, 0),
(171, 10, '4 bed room for emergency', 4, 0),
(172, 10, '4 bed room for emergency', 4, 0),
(173, 10, '4 bed room for emergency', 4, 0),
(174, 10, '4 bed room for emergency', 4, 0),
(200, 1, '2 bed room on the second floor', 2, 0),
(201, 1, '2 bed room on the second floor', 2, 0),
(202, 1, '2 bed room on the second floor', 2, 0),
(203, 1, '2 bed room on the second floor', 2, 0),
(204, 1, '2 bed room on the second floor', 2, 0),
(205, 1, '2 bed room on the second floor', 2, 0),
(210, 2, '2 bed room on the second floor', 2, 0),
(211, 2, '2 bed room on the second floor', 2, 0),
(212, 2, '2 bed room on the second floor', 2, 0),
(213, 2, '2 bed room on the second floor', 2, 0),
(214, 2, '2 bed room on the second floor', 2, 0),
(220, 3, '4 bed room on the second floor', 4, 0),
(221, 3, '4 bed room on the second floor', 4, 0),
(222, 3, '4 bed room on the second floor', 4, 0),
(223, 3, '4 bed room on the second floor', 4, 0),
(224, 3, '4 bed room on the second floor', 4, 0),
(300, 5, '2 bed room on the third floor', 2, 1),
(301, 5, '2 bed room on the third floor', 2, 0),
(302, 5, '2 bed room on the third floor', 2, 0),
(303, 5, '2 bed room on the third floor', 2, 0),
(304, 5, '2 bed room on the third floor', 2, 0),
(310, 6, '2 bed room on the third floor', 2, 0),
(311, 6, '2 bed room on the third floor', 2, 0),
(312, 6, '2 bed room on the third floor', 2, 0),
(313, 6, '2 bed room on the third floor', 2, 0),
(314, 6, '2 bed room on the third floor', 2, 0),
(320, 7, '2 bed room on the third floor', 2, 0),
(321, 7, '2 bed room on the third floor', 2, 0),
(322, 7, '2 bed room on the third floor', 2, 0),
(323, 7, '2 bed room on the third floor', 2, 0),
(324, 7, '2 bed room on the third floor', 2, 0),
(330, 8, '4 bed room on the third floor', 4, 0),
(331, 8, '4 bed room on the third floor', 4, 0),
(332, 8, '4 bed room on the third floor', 4, 0),
(333, 8, '4 bed room on the third floor', 4, 0),
(334, 8, '4 bed room on the third floor', 4, 0),
(340, 11, '4 bed room on the third floor', 4, 0),
(341, 11, '4 bed room on the third floor', 4, 0),
(342, 11, '4 bed room on the third floor', 4, 0),
(343, 11, '4 bed room on the third floor', 4, 0),
(344, 11, '4 bed room on the third floor', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `testID` int(11) NOT NULL AUTO_INCREMENT,
  `testName` varchar(100) NOT NULL,
  `testResult` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`testID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testID`, `testName`, `testResult`) VALUES
(1, 'Test1', 'Healthy'),
(2, 'Test2', 'Cancer');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

DROP TABLE IF EXISTS `treatment`;
CREATE TABLE IF NOT EXISTS `treatment` (
  `treatmentID` int(11) NOT NULL AUTO_INCREMENT,
  `treatmentName` varchar(100) NOT NULL,
  `recommendedAmount` decimal(20,1) NOT NULL,
  PRIMARY KEY (`treatmentID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`treatmentID`, `treatmentName`, `recommendedAmount`) VALUES
(1, 'treatment1', '1.5'),
(2, 'treatment2', '2.5');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `departmentID` int(11) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `salt1` varchar(100) NOT NULL,
  `salt2` varchar(100) NOT NULL,
  `userName` varchar(35) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `firstName`, `lastName`, `type`, `departmentID`, `password`, `salt1`, `salt2`, `userName`) VALUES
(1, 'John', 'Scotti', '', NULL, 'd7a48a3ad42931789952965c0d36eff3', 'ntbUoTMiCw', 'DnCLhnBmnw', 'scotj6'),
(2, 'John', 'Scotti', '', NULL, '6501c564c2b2f218c8f2aa907eefa5d2', 'nDuZVbmFZx', 'rJAWfWfOIQ', 'scotj8'),
(4, 'Nimda', 'Admin', '', NULL, '7fee3e6ca55a0a5b6e9e220c5ea4abef', 'hXnANcKrEW', 'ApexClLVeg', 'admin'),
(5, 'Danielle', 'Hyland', '', NULL, 'cc5cfe699fb67af71860e72ae48eb719', 'VPCyJLlDxO', 'YhxwfSEQxX', 'hylad7'),
(6, 'Danielle', 'Hyland', '', NULL, 'e8cd6cd17d1ed854d0c7201f7f444139', 'UxpmSKVTbL', 'ThZDPoMXtJ', 'hylad8'),
(7, 'John', 'Smith', '', NULL, 'b8f2fffa90e0d1e62660fd3f9cb427c3', 'gTRNxDhAnD', 'qWMePeurTK', 'smitj4');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctorassignedtopatient`
--
ALTER TABLE `doctorassignedtopatient`
  ADD CONSTRAINT `FK_patient` FOREIGN KEY (`patientID`) REFERENCES `patient` (`patientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_userID` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_roomNumber` FOREIGN KEY (`roomNumber`) REFERENCES `room` (`roomNumber`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `FK_drugID` FOREIGN KEY (`drugID`) REFERENCES `drug` (`drugID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `FK_deptID` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
