-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2019 at 04:23 PM
-- Server version: 10.3.13-MariaDB
-- PHP Version: 7.3.2

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

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `spAddPatient`$$
CREATE DEFINER=`id8869832_admin`@`%` PROCEDURE `spAddPatient` (IN `FN` VARCHAR(35), IN `LN` VARCHAR(35), IN `RN` INT)  NO SQL
BEGIN
	IF EXISTS (SELECT roomNumber FROM room WHERE RoomNumber = RN)THEN
		IF((SELECT patientsAssigned FROM room WHERE roomNumber = RN) < (SELECT maxCapacity FROM room WHERE roomNumber = RN)) 		 THEN
    	INSERT INTO patient (firstName, lastName, roomNumber) 		VALUES (FN, LN, RN);
        INSERT INTO patienthistory (patientID, admittedDate) VALUES ((SELECT patientID FROM patient WHERE patientID = LAST_INSERT_ID()), NOW());  
    	ELSE
        	INSERT INTO patient (firstName, lastName) VALUES (FN, LN);
            INSERT INTO patienthistory (patientID, admittedDate) VALUES ((SELECT patientID FROM patient WHERE patientID = LAST_INSERT_ID()), NOW());
    	END IF;
	ELSE
        INSERT INTO patient (firstName, lastName) VALUES (FN, LN);
        INSERT INTO patienthistory (patientID, admittedDate) VALUES ((SELECT patientID FROM patient WHERE patientID = LAST_INSERT_ID()), NOW());
    END IF;
END$$

DROP PROCEDURE IF EXISTS `spAdmitPatient`$$
CREATE DEFINER=`id8869832_admin`@`%` PROCEDURE `spAdmitPatient` (IN `ID` INT)  NO SQL
BEGIN
	INSERT INTO patienthistory (patientID, admittedDate) VALUES (ID, NOW());
END$$

DROP PROCEDURE IF EXISTS `spReleasePatient`$$
CREATE DEFINER=`id8869832_admin`@`%` PROCEDURE `spReleasePatient` (IN `ID` INT)  NO SQL
BEGIN
	UPDATE room 
    SET patientsAssigned = patientsAssigned - 1
    WHERE roomNumber = (SELECT roomNumber FROM patient WHERE patientID = ID);
    UPDATE patient 
    SET roomNumber = NULL 
    WHERE patientID = ID;
    UPDATE patienthistory
    SET dischargeDate = NOW()
    WHERE patientID = ID AND admittedDate = (SELECT MAX(admittedDate) FROM patienthistory WHERE patientID = ID);
END$$

DROP PROCEDURE IF EXISTS `spUpdatePatientRoom`$$
CREATE DEFINER=`id8869832_admin`@`%` PROCEDURE `spUpdatePatientRoom` (IN `ID` INT, IN `RN` INT)  NO SQL
BEGIN
	IF((SELECT patientsAssigned FROM room WHERE roomNumber = RN) < (SELECT maxCapacity FROM room WHERE roomNumber = RN)) THEN
		UPDATE room 
    	SET patientsAssigned = patientsAssigned + 1
    	WHERE roomNumber = RN;
    	UPDATE patient 
    	SET roomNumber = RN 
    	WHERE patientID = ID;
    	-- SET Stat = "Patient room updated";
    -- ELSE
    	-- SET Stat = "Patient room has not been updated. Room at max capacity";
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `departmentID` int(11) NOT NULL,
  `departmentName` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
CREATE TABLE `doctorassignedtopatient` (
  `patientID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `assignDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctorassignedtopatient`
--

INSERT INTO `doctorassignedtopatient` (`patientID`, `userID`, `assignDate`) VALUES
(1, 12, '2019-02-02');

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

DROP TABLE IF EXISTS `drug`;
CREATE TABLE `drug` (
  `drugID` int(11) NOT NULL,
  `medicineName` varchar(35) NOT NULL,
  `baseDose` decimal(20,1) DEFAULT NULL,
  `warning` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drug`
--

INSERT INTO `drug` (`drugID`, `medicineName`, `baseDose`, `warning`, `description`) VALUES
(1, 'first medicine', 0.7, 'First warning', 'This is medicine #1'),
(2, 'second medicine', 0.8, 'second warning', 'This is medicine #2'),
(3, 'Third medicine', 0.9, 'Third warning', 'This is medicine #3'),
(4, 'Fourth medicine', 1.0, 'Fourth warning', 'This is medicine #4'),
(5, 'Fifth medicine', 2.3, 'Fifth warning', 'This is medicine #5');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `patientID` int(11) NOT NULL,
  `firstName` varchar(35) NOT NULL,
  `lastName` varchar(35) NOT NULL,
  `roomNumber` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patientID`, `firstName`, `lastName`, `roomNumber`) VALUES
(1, 'John', 'Doe', 101),
(2, 'Nicholas', 'Cage', NULL),
(10, 'Lord', 'Farquaad', 101),
(11, 'Shrek', 'Ogre', NULL),
(13, 'Shaq', 'Kazaam', NULL),
(14, 'Jon', 'Dough', 100),
(18, 'Jane', 'Doe', 301),
(19, 'Jerry', 'Smith', 301),
(21, 'Uuuhhhhgg', 'Buuuhh', 300),
(22, 'Invalido', 'Roomguy', NULL),
(23, 'Joe', 'Smo', 300),
(26, 'John', 'Smith', NULL),
(27, 'Nimda', 'Admin', NULL),
(28, 'Doctor', 'Brule', 160);

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
CREATE TABLE `patientassignedtotest` (
  `patientID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `assignDate` date DEFAULT NULL,
  `assignDateStart` date DEFAULT NULL,
  `testResult` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patientassignedtotest`
--

INSERT INTO `patientassignedtotest` (`patientID`, `testID`, `assignDate`, `assignDateStart`, `testResult`) VALUES
(1, 1, '2019-02-02', '2019-02-15', 'Healthy'),
(2, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patientassignedtotreatment`
--

DROP TABLE IF EXISTS `patientassignedtotreatment`;
CREATE TABLE `patientassignedtotreatment` (
  `patientID` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL,
  `assignDate` date DEFAULT NULL,
  `assignDateStart` date DEFAULT NULL,
  `recommendedAmount` decimal(20,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patientassignedtotreatment`
--

INSERT INTO `patientassignedtotreatment` (`patientID`, `treatmentID`, `assignDate`, `assignDateStart`, `recommendedAmount`) VALUES
(1, 1, '2019-02-20', '2019-02-25', 1.5),
(2, 2, NULL, NULL, 0.0);

-- --------------------------------------------------------

--
-- Table structure for table `patienthistory`
--

DROP TABLE IF EXISTS `patienthistory`;
CREATE TABLE `patienthistory` (
  `patientID` int(11) NOT NULL,
  `admittedDate` datetime NOT NULL,
  `dischargeDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patienthistory`
--

INSERT INTO `patienthistory` (`patientID`, `admittedDate`, `dischargeDate`) VALUES
(28, '2019-03-31 00:00:00', '2019-03-31 17:57:27'),
(28, '2019-03-31 00:00:00', '2019-03-31 17:57:27'),
(2, '2019-03-31 00:00:00', '2019-03-31 17:50:20'),
(2, '2019-03-31 17:44:55', '2019-03-31 17:50:20'),
(1, '2019-03-01 00:00:00', '2019-03-02 00:00:00'),
(1, '2019-03-01 00:00:00', '2019-03-02 00:00:00'),
(1, '2019-03-06 00:00:00', '2019-03-09 00:00:00'),
(13, '2019-03-31 18:01:02', NULL),
(13, '2019-03-31 18:01:10', '2019-03-31 18:01:27'),
(10, '2019-04-01 00:45:45', NULL),
(11, '2019-04-01 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

DROP TABLE IF EXISTS `prescription`;
CREATE TABLE `prescription` (
  `doctorOrderNumber` int(11) NOT NULL,
  `drugID` int(11) NOT NULL,
  `dose` decimal(20,1) NOT NULL,
  `timesPerDay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`doctorOrderNumber`, `drugID`, `dose`, `timesPerDay`) VALUES
(1, 1, 0.0, 0),
(2, 2, 0.0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptionassignedtopatient`
--

DROP TABLE IF EXISTS `prescriptionassignedtopatient`;
CREATE TABLE `prescriptionassignedtopatient` (
  `doctorOrderNumber` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `assignDateStart` date DEFAULT NULL,
  `assignDateEnd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prescriptionassignedtopatient`
--

INSERT INTO `prescriptionassignedtopatient` (`doctorOrderNumber`, `patientID`, `assignDateStart`, `assignDateEnd`) VALUES
(1, 1, '2019-03-01', '2019-04-01'),
(2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `roomNumber` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `maxCapacity` int(11) NOT NULL,
  `patientsAssigned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`roomNumber`, `departmentID`, `description`, `maxCapacity`, `patientsAssigned`) VALUES
(100, 9, '4 bed room on the first floor', 4, 2),
(101, 9, '4 bed room on the first floor', 4, 1),
(102, 9, '4 bed room on the first floor', 4, 0),
(103, 9, '4 bed room on the first floor', 4, 0),
(104, 9, '4 bed room on the first floor', 4, 0),
(105, 9, '4 bed room on the first floor', 4, 0),
(110, 12, '2 bed room on the first floor', 2, 0),
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
(300, 5, '2 bed room on the third floor', 2, 2),
(301, 5, '2 bed room on the third floor', 2, 2),
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
CREATE TABLE `test` (
  `testID` int(11) NOT NULL,
  `testName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testID`, `testName`) VALUES
(1, 'Test1'),
(2, 'Test2'),
(3, 'test3'),
(4, 'test4'),
(5, 'test5');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

DROP TABLE IF EXISTS `treatment`;
CREATE TABLE `treatment` (
  `treatmentID` int(11) NOT NULL,
  `treatmentName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`treatmentID`, `treatmentName`) VALUES
(1, 'treatment1'),
(2, 'treatment2'),
(3, 'treatment3'),
(4, 'treatment4'),
(5, 'treatment5');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `departmentID` int(11) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `salt1` varchar(100) NOT NULL,
  `salt2` varchar(100) NOT NULL,
  `userName` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `firstName`, `lastName`, `type`, `departmentID`, `password`, `salt1`, `salt2`, `userName`) VALUES
(1, 'John', 'Scotti', 'nurse', NULL, 'd7a48a3ad42931789952965c0d36eff3', 'ntbUoTMiCw', 'DnCLhnBmnw', 'scotj6'),
(2, 'John', 'Scotti', '', NULL, '6501c564c2b2f218c8f2aa907eefa5d2', 'nDuZVbmFZx', 'rJAWfWfOIQ', 'scotj8'),
(4, 'Nimda', 'Admin', 'doctor', NULL, '7fee3e6ca55a0a5b6e9e220c5ea4abef', 'hXnANcKrEW', 'ApexClLVeg', 'admin'),
(5, 'Danielle', 'Hyland', '', NULL, 'cc5cfe699fb67af71860e72ae48eb719', 'VPCyJLlDxO', 'YhxwfSEQxX', 'hylad7'),
(6, 'Danielle', 'Hyland', '', NULL, 'e8cd6cd17d1ed854d0c7201f7f444139', 'UxpmSKVTbL', 'ThZDPoMXtJ', 'hylad8'),
(7, 'John', 'Smith', '', NULL, 'b8f2fffa90e0d1e62660fd3f9cb427c3', 'gTRNxDhAnD', 'qWMePeurTK', 'smitj4'),
(8, 'Danielle', 'Hyland', '', NULL, 'ef037ca03c6fd6a2cada104f3e1adc1a', 'iybOOikaQA', 'XoeNpXbFhs', 'hylad'),
(9, 'Cee', 'Look', '', NULL, '0074cc486cf559c7fe82cc5fda51a1ee', 'tQtNNfFeCJ', 'LJKoZRwxqQ', 'lookc'),
(10, 'Cee', 'Look', 'doctor', 7, 'c99108bc448e32aba9050c5f0da7576f', 'kiqlInjKIa', 'SfDCksnVAF', 'lookc1'),
(11, 'Jerry', 'Smith', 'nurse', 12, 'bafa15e548133e7750b2ac8c246f717e', 'xdXyffyicN', 'HvXukNrFCd', 'smitj'),
(12, 'John', 'Smith', 'doctor', 3, 'd4e4192daa0e17428e604e74cdf8107b', 'RWRDYtcaes', 'HwsEUCPrgK', 'smitj1'),
(15, 'John', 'Schmo', 'doctor', 1, '92161b8c863296b36f1efc3729d252f7', 'EVfNnoSIEY', 'WbGVHTRQqd', 'schmj'),
(16, 'John', 'Schmo', 'doctor', 1, '8b0dc95db12ed1d5fc56fdf280911853', 'fEhAgWQxmN', 'TwJjUreYae', 'schmj1'),
(17, 'Nicholas', 'Amond', 'nurse', 1, '59c6c8f890d2ed002a55164f2a42b210', 'hsbuBjxLBW', 'ayZBmAjEKf', 'amonn'),
(18, 'Jim', 'Mark', 'doctor', 6, '1d85db131937d49e185bf9827906bfd2', 'RVRDmqCRsi', 'PlKsWrCjFs', 'markj'),
(19, 'Esrun', 'Nurse', 'nurse', 1, '7fe1c77c4ab893fde6e25451a9db21b5', 'JPYigtmDML', 'RoeZrdOgcT', 'nurse'),
(20, 'Danielle', 'Alloy', 'nurse', 1, 'e9f67978ef234e298df3d9d67409c674', 'xJpUkKsQBu', 'pnASLIwtmo', 'allod'),
(21, 'Mark', 'Doctor', 'doctor', 1, '0bfd2e41e892a1b4e23c8ebde0e72a56', 'yokfHFewei', 'yhSjLnIPjX', 'doctm'),
(22, 'Testy', 'admin', 'doctor', 1, '0d52694a8ced391f238ab712019186b5', 'YJaldLjlMS', 'QraUkOYkMy', 'admit'),
(23, 'Testy', 'admin', 'doctor', 1, '3a674aea54ce8f5aad3848c4b3ed8420', 'PBItAGJhId', 'TbQkbLZoVJ', 'admit1'),
(24, 'Testy', 'admin', 'doctor', 1, '6e3309f7003cf0e3c850ddfdc35423c7', 'yRKKrDjvro', 'AAlDAwjUdV', 'admit2'),
(25, 'Testy', 'admin', 'doctor', 1, '5357c2b96ab5696c42a5f1d44afd4668', 'CLSCpweiOv', 'ughatHYXfv', 'admit3'),
(26, 'Testy', 'admin', 'doctor', 1, '0d6d3d5b776c6b0afea68e28ad8c3831', 'fsMaebaPuj', 'KpXZBBaqNl', 'admit4'),
(27, 'Testy', 'admin', 'doctor', 1, '573a8ac990f2a74ee0c81332f0ed40b2', 'rBduyylYcX', 'TvjlgViawI', 'admit5'),
(28, 'Testy', 'admin', 'doctor', 1, '179319f3c99c97aa870fd370fe62179c', 'ScFrDyyoiL', 'ivMdcRJiAb', 'admit6'),
(29, 'Testy', 'admin', 'doctor', 1, 'a8de0d3f42f1615f2f3c9d422aeb065e', 'wGyDHsYrNZ', 'GDgkyyQWyp', 'admit7'),
(30, 'Testy', 'admin', 'doctor', 1, 'c9a416d49c7ec30a45f005c636aa8118', 'WOHDmUpywr', 'VWfXxWtGOt', 'admit8'),
(31, 'Danielle', 'admin', 'doctor', 1, 'b2cb668e5105bc9689bf01410875b62f', 'fdGxTxmhvW', 'FHmgVskYro', 'admid'),
(32, 'Danielle', 'admin', 'doctor', 1, 'b9bfff120285876b0095551d66a6d195', 'msTppFtAXX', 'jVJDKfxfHP', 'admid1'),
(33, 'Testy', 'admin', 'doctor', 1, '22f78101002b013348b32f4bc699713b', 'XymTTYmILM', 'hxfQGABnCy', 'admit9'),
(34, 'Danielle', 'admin', 'doctor', 1, '829005290dbb8db9f1ffecd0a9af62db', 'UZxMTPnRqL', 'KxhfArQCdv', 'admid2'),
(35, 'Danielle', 'admin', 'doctor', 1, 'c2af319cc8ed6a4dfb0f8030837b2ca0', 'VkXfTWodTq', 'GWTYmGWrPd', 'admid3'),
(36, 'Danielle', 'admin', 'doctor', 1, 'b1ab0141ec7e859adf304495faa9e734', 'AuPLnQbWvx', 'JjCkgNgBIy', 'admid4'),
(37, 'Jerkface', 'admin', 'doctor', 1, 'f2b08ad8f903dbcbb165edc4bf0ffcc1', 'gcIkoQQrIG', 'obLptkXLxp', 'admij');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentID`);

--
-- Indexes for table `doctorassignedtopatient`
--
ALTER TABLE `doctorassignedtopatient`
  ADD PRIMARY KEY (`patientID`,`userID`),
  ADD KEY `FK_userID` (`userID`);

--
-- Indexes for table `drug`
--
ALTER TABLE `drug`
  ADD PRIMARY KEY (`drugID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patientID`),
  ADD KEY `FK_roomNumber` (`roomNumber`);

--
-- Indexes for table `patientassignedtotest`
--
ALTER TABLE `patientassignedtotest`
  ADD PRIMARY KEY (`patientID`,`testID`),
  ADD KEY `FK_testID` (`testID`);

--
-- Indexes for table `patientassignedtotreatment`
--
ALTER TABLE `patientassignedtotreatment`
  ADD PRIMARY KEY (`patientID`,`treatmentID`),
  ADD KEY `FK_treatmentID` (`treatmentID`);

--
-- Indexes for table `patienthistory`
--
ALTER TABLE `patienthistory`
  ADD KEY `patientID` (`patientID`) USING BTREE;

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`doctorOrderNumber`),
  ADD KEY `FK_drugID` (`drugID`);

--
-- Indexes for table `prescriptionassignedtopatient`
--
ALTER TABLE `prescriptionassignedtopatient`
  ADD PRIMARY KEY (`doctorOrderNumber`,`patientID`),
  ADD KEY `prescriptionassignedtopatient_patientID_FK` (`patientID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`roomNumber`),
  ADD KEY `FK_deptID` (`departmentID`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`testID`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`treatmentID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `user_department_FK` (`departmentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `departmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `drug`
--
ALTER TABLE `drug`
  MODIFY `drugID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `doctorOrderNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `roomNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `testID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `treatmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
-- Constraints for table `patientassignedtotest`
--
ALTER TABLE `patientassignedtotest`
  ADD CONSTRAINT `FK_patientassignedtotest_patient_patientID` FOREIGN KEY (`patientID`) REFERENCES `patient` (`patientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_testID` FOREIGN KEY (`testID`) REFERENCES `test` (`testID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patientassignedtotreatment`
--
ALTER TABLE `patientassignedtotreatment`
  ADD CONSTRAINT `FK_patientID` FOREIGN KEY (`patientID`) REFERENCES `patient` (`patientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_treatmentID` FOREIGN KEY (`treatmentID`) REFERENCES `treatment` (`treatmentID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patienthistory`
--
ALTER TABLE `patienthistory`
  ADD CONSTRAINT `FK_patientHistory_patientID` FOREIGN KEY (`patientID`) REFERENCES `patient` (`patientID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `FK_drugID` FOREIGN KEY (`drugID`) REFERENCES `drug` (`drugID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prescriptionassignedtopatient`
--
ALTER TABLE `prescriptionassignedtopatient`
  ADD CONSTRAINT `prescriptionassignedtopatient_doctorOrderNumber_FK` FOREIGN KEY (`doctorOrderNumber`) REFERENCES `prescription` (`doctorOrderNumber`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prescriptionassignedtopatient_patientID_FK` FOREIGN KEY (`patientID`) REFERENCES `patient` (`patientID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `FK_deptID` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_department_FK` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
