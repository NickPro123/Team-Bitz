-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2019 at 04:36 PM
-- Server version: 10.3.14-MariaDB
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
CREATE PROCEDURE `spAddDiagnosis` (IN `uID` INT, IN `pID` INT, IN `diag` VARCHAR(255), IN `notes` VARCHAR(255))  NO SQL
BEGIN
	INSERT INTO diagnosis(patientID, userID, diagnosis, doctorNotes, dateAssigned) VALUES (pID, uID, diag, notes, NOW());
END$$

CREATE PROCEDURE `spAddPatient` (IN `FN` VARCHAR(35), IN `LN` VARCHAR(35), IN `RN` INT)  NO SQL
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

CREATE PROCEDURE `spAddPrescription` (IN `pID` INT, IN `dID` INT, IN `dDose` DECIMAL, IN `tpd` INT, IN `endDate` DATE, IN `uID` INT)  NO SQL
BEGIN
 	INSERT INTO prescriptionassignedtopatient(patientID, drugID, assignDateStart, assignDateEnd, userID, dose, timesPerDay) VALUES (pID, dID, NOW(), endDate, uID, dDose, tpd);
END$$

CREATE PROCEDURE `spAddTest` (IN `pID` INT, IN `tID` INT, IN `tStart` DATE, IN `uID` INT)  NO SQL
BEGIN
	INSERT INTO patientassignedtotest(patientID, testID, assignDate, assignDateStart, userID) VALUES (pID, tID, NOW(), tStart, uID);
END$$

CREATE PROCEDURE `spAddTreatment` (IN `pID` INT, IN `tID` INT, IN `tStart` DATE, IN `inst` VARCHAR(255), IN `uID` INT)  NO SQL
BEGIN
	INSERT INTO patientassignedtotreatment (patientID, treatmentID, assignDate, assignDateStart, instructions, userID) VALUES (pID, tID, NOW(), tStart, inst, uID);
END$$

CREATE PROCEDURE `spAdmitPatient` (IN `ID` INT)  NO SQL
BEGIN
	INSERT INTO patienthistory (patientID, admittedDate) VALUES (ID, NOW());
END$$

CREATE PROCEDURE `spReleasePatient` (IN `ID` INT)  NO SQL
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

CREATE PROCEDURE `spSelectLastDischarge` (IN `ID` INT)  NO SQL
SELECT MAX(dischargeDate) FROM patientHistory WHERE patientID = ID$$

CREATE PROCEDURE `spUpdatePatient` (IN `ID` INT, IN `fName` VARCHAR(255), IN `lName` VARCHAR(255), IN `RM` INT)  NO SQL
BEGIN
	UPDATE patient 
    SET firstName = fName, lastName = lName
	WHERE patientID = ID;
    
	CALL spUpdatePatientRoom(ID, RM);
END$$

CREATE PROCEDURE `spUpdatePatientRoom` (IN `ID` INT, IN `RN` INT)  NO SQL
BEGIN
	IF((SELECT patientsAssigned FROM room WHERE roomNumber = RN) < (SELECT maxCapacity FROM room WHERE roomNumber = RN)) THEN
    	UPDATE room
        SET patientsAssigned = patientsAssigned - 1
        WHERE roomNumber = (SELECT roomNumber FROM patient WHERE patientID = ID);
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
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `patientID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `diagnosis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `doctorNotes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateAssigned` datetime NOT NULL,
  `isInactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`patientID`, `userID`, `diagnosis`, `doctorNotes`, `dateAssigned`, `isInactive`) VALUES
(1, 4, 'Frostbite', 'Patient was left in the freezing water for 3 hours, leading to full body frost bite', '2019-02-25 00:00:00', 0),
(1, 39, 'Gangrene', 'Keep a close eye on left leg, might need to be amputated if condition gets worse', '2019-05-01 20:37:06', 0),
(2, 4, 'Anxiety', NULL, '2019-03-25 00:00:00', 0),
(10, 4, 'Heart Failure', NULL, '2019-04-22 17:51:43', 0),
(14, 4, 'Liver Failure', NULL, '2019-04-22 17:45:50', 0),
(30, 4, 'Ruptured Spleen', 'Due to car accident, patient was punctured by a broken fence.', '2019-04-22 19:49:53', 0),
(35, 43, 'As', 'As', '2019-05-01 15:37:44', 0),
(48, 4, 'Abdominal pain', 'Unknown pain in stomach/intestine area', '2019-04-23 00:09:11', 0),
(53, 4, 'Newborn jaundice', 'Yellowing of skin', '2019-04-23 00:22:17', 0),
(54, 4, 'broken femur', 'car accident', '2019-04-24 15:22:59', 0),
(69, 43, 'asd', 'asd', '2019-05-01 15:42:35', 0),
(70, 43, 'feg', '43', '2019-05-01 15:51:03', 0),
(72, 44, '344', '12', '2019-05-01 15:59:38', 0),
(73, 43, 'sada', 'er3', '2019-05-01 16:02:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `doctorassignedtopatient`
--

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
(1, 'Oxycodone', '0.7', 'Can cause breathing problems, stomach pain, and drowsiness.', 'Used to treat moderate to severe pain.'),
(2, 'Xanax', '0.8', 'Can cause fatigue, headaches, and dizziness.', 'Used to treat anxiety and panic disorder.'),
(3, 'Ibuprofen', '0.9', 'Can cause bloating, rashs, and vomiting.', 'Used to treat fever and pain.'),
(4, 'Diazepam', '1.0', 'Can cause suicidal tought, muscle weakness, and tremor.', 'Used to treat anxiety and muscle spasms.'),
(5, 'Klonopin', '2.3', 'Can cause depression, drowsiness, headaches.', 'Used to treat seizures and panic disorder.');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

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
(1, 'Joe', 'Dough', 131),
(2, 'Nicky', 'Cagey', NULL),
(10, 'Lord', 'Farquaad', 210),
(11, 'Shrek', 'Ogre', 302),
(13, 'Shaq', 'Kazaam', NULL),
(14, 'Jon', 'Dough', 101),
(18, 'Jane', 'Doe', NULL),
(19, 'Jerry', 'Smith', NULL),
(21, 'Uuuhhhhgg', 'Buuuhh', 300),
(22, 'Invalido', 'Roomguy', NULL),
(23, 'Joe', 'Smo', 302),
(26, 'John', 'Smith', NULL),
(27, 'Nimda', 'Admin', NULL),
(28, 'Steve', 'Brule', 160),
(29, 'Jeff', 'Golden', NULL),
(30, 'asd', 'abc', 102),
(31, 'Test', 'Name', NULL),
(32, 'Billy', 'Joel', NULL),
(33, 'Jess', 'Smith', 102),
(34, 'Jamie', 'Lynn', 331),
(35, 'Barbara ', 'Zate ', NULL),
(36, 'Paul', 'Chen', NULL),
(37, 'Hess', 'Ham', NULL),
(38, 'Noah', 'Rawlings', NULL),
(39, 'Nicole', 'Dyer', NULL),
(40, 'Justin', 'Steele', 130),
(41, 'Ryan', 'Wormald', 130),
(42, 'Gordon', 'Freeman', 131),
(43, 'Adrian', 'Chambers', 341),
(44, 'Kim', '	Gardner', 171),
(45, 'Leah', 'Klein', 150),
(46, 'Leah', 'Klein', 150),
(47, 'Aaron', 'Hanks', 331),
(48, 'Charlie', 'Kelly', 172),
(49, 'Leah', 'Klein', NULL),
(50, 'Francis', 'Brady', 132),
(51, 'Dana', 'White', 151),
(52, 'Gabriel', 'Lopez', 211),
(53, 'Lille', 'Page', 160),
(54, 'nick', 'alpha', 131),
(55, 'John', 'Smithy', 200),
(56, 'Jane', 'Doughnut', 111),
(57, 'Maria', 'Sanchez', 170),
(58, 'Maria', 'Sanchez', 170),
(59, 'Maria', 'Sanchez', 170),
(60, 'Jason', 'John', 210),
(61, 'Jason', 'John', NULL),
(62, 'Jason', 'John', NULL),
(63, 'Jason', 'John', NULL),
(64, 'Jason', 'John', NULL),
(65, 'Test', 'Test', 115),
(66, 'Formatted', 'Name', 134),
(67, '1111111111111111111', '2222222222222222222222', NULL),
(68, 'sd', 'asd', 220),
(69, 'asd', 'qwe', 300),
(70, 'asdqweqwe', 't54342', NULL),
(71, 'asdqwe', 'qdas', NULL),
(72, 'Nick ', 'Cagey', NULL),
(73, '53yergf', 'faesc', 220),
(74, 'Ricky', 'Bobby', NULL),
(75, '####', 'ASf', 320),
(76, 'AS#', 'AS', 301),
(77, '$', 'f', NULL),
(78, 'sad', 'ad', NULL);

--
-- Triggers `patient`
--
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

CREATE TABLE `patientassignedtotest` (
  `patientID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `assignDate` date DEFAULT NULL,
  `assignDateStart` date DEFAULT NULL,
  `testResult` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patientassignedtotest`
--

INSERT INTO `patientassignedtotest` (`patientID`, `testID`, `assignDate`, `assignDateStart`, `testResult`, `userID`) VALUES
(1, 1, '2019-02-02', '2019-04-29', 'This patient is Healthy', 4),
(1, 4, '2019-04-14', '2019-04-12', 'Negative', 4),
(2, 2, NULL, NULL, NULL, 4),
(13, 5, '2019-04-07', '2019-04-07', 'Bad Actor', 4),
(18, 1, '2019-04-14', '2019-03-31', 'A', 4),
(19, 4, '2019-04-15', '2019-04-16', 'Fail', 4),
(21, 1, '2019-04-14', '2019-04-16', NULL, 4),
(23, 1, '2019-04-14', '2019-04-17', 'Positive', 4),
(28, 1, '2019-04-15', '2019-04-17', 'PASS', 4),
(30, 1, '2019-04-17', '2019-04-18', NULL, 4),
(30, 2, '2019-04-17', '2019-04-18', NULL, 4),
(50, 1, '2019-04-28', '2019-04-28', NULL, 4),
(54, 3, '2019-04-24', '2019-04-30', NULL, 4),
(68, 3, '2019-05-01', '2019-05-10', NULL, 41),
(70, 3, '2019-05-01', '2019-05-17', NULL, 43);

-- --------------------------------------------------------

--
-- Table structure for table `patientassignedtotreatment`
--

CREATE TABLE `patientassignedtotreatment` (
  `patientID` int(11) NOT NULL,
  `treatmentID` int(11) NOT NULL,
  `assignDate` date DEFAULT NULL,
  `assignDateStart` date DEFAULT NULL,
  `instructions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patientassignedtotreatment`
--

INSERT INTO `patientassignedtotreatment` (`patientID`, `treatmentID`, `assignDate`, `assignDateStart`, `instructions`, `userID`) VALUES
(1, 1, '2019-02-20', '2019-05-01', '1.5', 4),
(2, 2, NULL, NULL, '0.0', 4),
(13, 2, '2019-04-07', '2019-04-10', '10.0', 4),
(19, 1, '2019-04-15', '2019-04-14', '2', 4),
(28, 1, '2019-04-14', '2019-04-15', 'i', 4),
(30, 3, '2019-04-14', '2019-04-10', '\'Change bandage\'', 4),
(38, 1, '2019-04-28', '2017-01-17', 'Take it', 4),
(50, 1, '2019-04-28', '2019-04-29', 'Take it', 4),
(68, 1, '2019-05-01', '2019-05-02', 'Its up the butt medicine', 41),
(70, 1, '2019-05-01', '2019-05-14', 'xdZds', 43);

-- --------------------------------------------------------

--
-- Table structure for table `patienthistory`
--

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
(2, '2019-03-31 17:44:55', '2019-04-24 15:32:02'),
(1, '2019-03-01 00:00:00', '2019-03-02 00:00:00'),
(1, '2019-03-01 00:00:00', '2019-03-02 00:00:00'),
(1, '2019-03-06 00:00:00', '2019-04-17 04:19:49'),
(13, '2019-03-31 18:01:02', NULL),
(13, '2019-03-31 18:01:10', '2019-03-31 18:01:27'),
(10, '2019-04-01 00:45:45', '2019-04-17 03:17:27'),
(11, '2019-04-01 00:00:00', NULL),
(29, '2019-04-01 20:12:55', NULL),
(30, '2019-04-07 05:48:34', NULL),
(31, '2019-04-07 14:55:28', NULL),
(1, '2018-03-01 00:00:00', NULL),
(1, '2018-03-01 00:00:00', NULL),
(32, '2019-04-08 00:09:20', NULL),
(33, '2019-04-08 15:03:10', NULL),
(34, '2019-04-15 01:11:30', NULL),
(35, '2019-04-15 01:11:51', NULL),
(36, '2019-04-16 03:05:44', '2019-04-17 14:55:01'),
(37, '2019-04-16 03:10:29', '2019-04-17 03:22:26'),
(1, '2019-04-17 04:20:03', '2019-04-17 04:21:38'),
(1, '2019-04-17 04:21:48', '2019-04-17 12:48:04'),
(36, '2019-04-17 14:55:41', '2019-04-17 16:07:56'),
(1, '2019-04-18 00:09:50', '2019-04-18 18:51:45'),
(38, '2019-04-18 02:29:26', '2019-05-01 15:55:29'),
(39, '2019-04-18 02:29:43', '2019-05-01 15:55:54'),
(40, '2019-04-18 02:30:00', NULL),
(41, '2019-04-18 02:30:23', NULL),
(42, '2019-04-18 02:31:31', NULL),
(1, '2019-04-18 18:51:53', NULL),
(43, '2019-04-22 23:12:12', NULL),
(44, '2019-04-22 23:13:33', NULL),
(45, '2019-04-22 23:14:10', NULL),
(46, '2019-04-22 23:16:12', NULL),
(47, '2019-04-22 23:16:37', NULL),
(48, '2019-04-22 23:17:22', NULL),
(49, '2019-04-23 00:04:11', NULL),
(50, '2019-04-23 00:08:03', NULL),
(51, '2019-04-23 00:11:32', NULL),
(52, '2019-04-23 00:18:38', NULL),
(53, '2019-04-23 00:21:16', NULL),
(11, '2019-04-24 15:21:10', NULL),
(54, '2019-04-24 15:22:23', NULL),
(55, '2019-04-30 21:56:43', NULL),
(56, '2019-04-30 22:44:39', NULL),
(57, '2019-04-30 22:46:33', NULL),
(58, '2019-04-30 22:49:22', NULL),
(59, '2019-04-30 22:50:35', NULL),
(60, '2019-04-30 22:51:03', NULL),
(61, '2019-04-30 22:53:15', NULL),
(62, '2019-04-30 22:54:27', NULL),
(63, '2019-04-30 22:55:47', NULL),
(64, '2019-04-30 22:57:54', NULL),
(65, '2019-05-01 02:08:01', NULL),
(66, '2019-05-01 15:40:12', NULL),
(67, '2019-05-01 15:40:45', '2019-05-03 20:17:00'),
(68, '2019-05-01 15:42:21', NULL),
(69, '2019-05-01 15:42:29', NULL),
(70, '2019-05-01 15:50:52', '2019-05-03 20:16:49'),
(71, '2019-05-01 15:53:53', '2019-05-01 15:58:48'),
(72, '2019-05-01 15:59:27', '2019-05-01 15:59:50'),
(73, '2019-05-01 16:02:13', NULL),
(74, '2019-05-01 16:05:58', '2019-05-03 20:16:53'),
(75, '2019-05-02 20:50:36', NULL),
(76, '2019-05-02 20:53:25', NULL),
(77, '2019-05-03 17:58:37', NULL),
(78, '2019-05-03 17:58:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptionassignedtopatient`
--

CREATE TABLE `prescriptionassignedtopatient` (
  `drugID` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `assignDateStart` date DEFAULT NULL,
  `assignDateEnd` date DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `dose` decimal(10,0) NOT NULL,
  `timesPerDay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prescriptionassignedtopatient`
--

INSERT INTO `prescriptionassignedtopatient` (`drugID`, `patientID`, `assignDateStart`, `assignDateEnd`, `userID`, `dose`, `timesPerDay`) VALUES
(1, 1, '2019-03-01', '2019-04-01', 4, '4', 1),
(1, 2, '2019-05-01', '2019-05-14', 43, '1', 1),
(1, 28, '2019-04-14', '2019-04-16', 4, '2', 2),
(1, 30, '2019-04-17', '2019-04-20', 4, '5', 2),
(1, 38, '2019-04-30', '2019-04-30', 4, '0', 0),
(1, 39, '2019-05-01', '2019-05-01', 4, '3', 2),
(1, 50, '2019-04-28', '2019-04-29', 4, '2', 2),
(1, 51, '2019-04-30', '2019-04-16', 39, '0', 0),
(2, 28, '2019-04-15', '2019-04-15', 4, '3', 3),
(2, 30, '2019-04-17', '2019-04-19', 4, '4', 1),
(3, 1, '2019-04-24', '2019-04-29', 4, '2', 1),
(4, 1, '2019-04-24', '2019-04-10', 4, '4', 2),
(5, 13, '2019-04-09', '2019-04-10', 4, '0', 0),
(6, 1, '2019-04-09', '2019-04-17', 4, '0', 0),
(7, 1, '2019-04-09', '0000-00-00', 4, '0', 0),
(8, 2, '2019-04-09', '2019-04-30', 4, '0', 0),
(9, 33, '2019-04-09', '2019-04-15', 4, '0', 0),
(10, 33, '2019-04-09', '2019-04-15', 4, '0', 0),
(11, 1, '2019-04-09', '2022-01-02', 4, '0', 0),
(12, 1, '2019-04-09', '2019-04-25', 4, '0', 0),
(13, 23, '2019-04-10', '2019-04-23', 4, '0', 0),
(14, 18, '2019-04-10', '2019-04-18', 4, '0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

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
(100, 9, '4 bed room on the first floor', 4, 0),
(101, 9, '4 bed room on the first floor', 4, 1),
(102, 9, '4 bed room on the first floor', 4, 2),
(103, 9, '4 bed room on the first floor', 4, 0),
(104, 9, '4 bed room on the first floor', 4, 0),
(105, 9, '4 bed room on the first floor', 4, 0),
(110, 12, '2 bed room on the first floor', 2, 0),
(111, 12, '2 bed room on the first floor', 2, 1),
(112, 12, '2 bed room on the first floor', 2, 0),
(113, 12, '2 bed room on the first floor', 2, 0),
(114, 12, '2 bed room on the first floor', 2, 0),
(115, 12, '1 bed room on the first floor to avoid contamination', 1, 1),
(116, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(117, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(118, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(119, 12, '1 bed room on the first floor to avoid contamination', 1, 0),
(130, 1, '4 bed room on the first floor', 4, 2),
(131, 1, '4 bed room on the first floor', 4, 3),
(132, 1, '4 bed room on the first floor', 4, 1),
(133, 1, '4 bed room on the first floor', 4, 0),
(134, 1, '4 bed room on the first floor', 4, 1),
(150, 4, '2 bed room for maternity department', 2, 2),
(151, 4, '2 bed room for maternity department', 2, 1),
(152, 4, '2 bed room for maternity department', 2, 0),
(153, 4, '2 bed room for maternity department', 2, 0),
(154, 4, '2 bed room for maternity department', 2, 0),
(160, 4, 'Nursery', 20, 1),
(170, 10, 'Waiting Area', 30, 3),
(171, 10, '4 bed room for emergency', 4, 1),
(172, 10, '4 bed room for emergency', 4, 1),
(173, 10, '4 bed room for emergency', 4, 0),
(174, 10, '4 bed room for emergency', 4, 0),
(200, 1, '2 bed room on the second floor', 2, 1),
(201, 1, '2 bed room on the second floor', 2, 0),
(202, 1, '2 bed room on the second floor', 2, 0),
(203, 1, '2 bed room on the second floor', 2, 0),
(204, 1, '2 bed room on the second floor', 2, 0),
(205, 1, '2 bed room on the second floor', 2, 0),
(210, 2, '2 bed room on the second floor', 2, 2),
(211, 2, '2 bed room on the second floor', 2, 1),
(212, 2, '2 bed room on the second floor', 2, 0),
(213, 2, '2 bed room on the second floor', 2, 0),
(214, 2, '2 bed room on the second floor', 2, 0),
(220, 3, '4 bed room on the second floor', 4, 2),
(221, 3, '4 bed room on the second floor', 4, 0),
(222, 3, '4 bed room on the second floor', 4, 0),
(223, 3, '4 bed room on the second floor', 4, 0),
(224, 3, '4 bed room on the second floor', 4, 0),
(300, 5, '2 bed room on the third floor', 2, 2),
(301, 5, '2 bed room on the third floor', 2, 1),
(302, 5, '2 bed room on the third floor', 2, 2),
(303, 5, '2 bed room on the third floor', 2, 0),
(304, 5, '2 bed room on the third floor', 2, 0),
(310, 6, '2 bed room on the third floor', 2, 0),
(311, 6, '2 bed room on the third floor', 2, 0),
(312, 6, '2 bed room on the third floor', 2, 0),
(313, 6, '2 bed room on the third floor', 2, 0),
(314, 6, '2 bed room on the third floor', 2, 0),
(320, 7, '2 bed room on the third floor', 2, 1),
(321, 7, '2 bed room on the third floor', 2, 0),
(322, 7, '2 bed room on the third floor', 2, 0),
(323, 7, '2 bed room on the third floor', 2, 0),
(324, 7, '2 bed room on the third floor', 2, 0),
(330, 8, '4 bed room on the third floor', 4, 0),
(331, 8, '4 bed room on the third floor', 4, 2),
(332, 8, '4 bed room on the third floor', 4, 0),
(333, 8, '4 bed room on the third floor', 4, 0),
(334, 8, '4 bed room on the third floor', 4, 0),
(340, 11, '4 bed room on the third floor', 4, 0),
(341, 11, '4 bed room on the third floor', 4, 1),
(342, 11, '4 bed room on the third floor', 4, 0),
(343, 11, '4 bed room on the third floor', 4, 0),
(344, 11, '4 bed room on the third floor', 4, 0),
(700, 7, 'John\\\'s Room', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `testID` int(11) NOT NULL,
  `testName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testID`, `testName`) VALUES
(1, 'Cystoscopy Test'),
(2, 'Responsive Test'),
(3, 'Kidney Function Test'),
(4, 'Allergy Test'),
(5, 'Blood Test');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `treatmentID` int(11) NOT NULL,
  `treatmentName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`treatmentID`, `treatmentName`) VALUES
(1, 'Fluticasone Propionate'),
(2, 'Clearasil'),
(3, 'Neutrogena'),
(4, 'Donepezil'),
(5, 'Flonase');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

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
(1, 'John', 'Scotti', 'nurse', 3, 'd7a48a3ad42931789952965c0d36eff3', 'ntbUoTMiCw', 'DnCLhnBmnw', 'scotj6'),
(2, 'John', 'Scotti', '', 2, '6501c564c2b2f218c8f2aa907eefa5d2', 'nDuZVbmFZx', 'rJAWfWfOIQ', 'scotj8'),
(4, 'Nimda', 'Admin', 'doctor', 1, '7fee3e6ca55a0a5b6e9e220c5ea4abef', 'hXnANcKrEW', 'ApexClLVeg', 'admin'),
(5, 'Danielle', 'Hyland', '', 7, 'cc5cfe699fb67af71860e72ae48eb719', 'VPCyJLlDxO', 'YhxwfSEQxX', 'hylad7'),
(6, 'Danielle', 'Hyland', '', 12, 'e8cd6cd17d1ed854d0c7201f7f444139', 'UxpmSKVTbL', 'ThZDPoMXtJ', 'hylad8'),
(7, 'John', 'Smith', '', 4, 'b8f2fffa90e0d1e62660fd3f9cb427c3', 'gTRNxDhAnD', 'qWMePeurTK', 'smitj4'),
(8, 'Danielle', 'Hyland', '', 5, 'ef037ca03c6fd6a2cada104f3e1adc1a', 'iybOOikaQA', 'XoeNpXbFhs', 'hylad'),
(9, 'Cee', 'Look', '', 8, '0074cc486cf559c7fe82cc5fda51a1ee', 'tQtNNfFeCJ', 'LJKoZRwxqQ', 'lookc'),
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
(37, 'Jerkface', 'admin', 'doctor', 1, 'f2b08ad8f903dbcbb165edc4bf0ffcc1', 'gcIkoQQrIG', 'obLptkXLxp', 'admij'),
(38, 'Test', 'Account', 'doctor', 7, '654fc5a675d502a596b7d9331caefa73', 'UqKsaGUcTW', 'mqnGaGmAfS', 'accot'),
(39, 'John', 'Scotti', 'doctor', 3, '99df1acd2060383afff55f9a552a4766', 'QYPNiiffMQ', 'ywPHeDdKAc', 'scotj'),
(40, 'Taxman', 'Cometh', 'nurse', 4, 'afa78740d00e77c954698da33663c0c8', 'FnLfSgjENH', 'sHfQcIfJuC', 'comet'),
(41, 'FilthyActsAtAReasonable', 'Price', 'doctor', 3, '2de62b3b70b8d02bfc0738f1e86c55dd', 'wQolIZVPft', 'hyBhaJbQeh', 'pricf'),
(42, 'SuchDoge', 'Wow', 'nurse', 10, '733fbae9c514e4149f57ef7fc581ec17', 'NqgokBIhGD', 'JHQTWRsdpk', 'wows'),
(43, 'John', 'Choi', 'doctor', 6, '7c61e7493528b89b952898f238600de4', 'xmruBAJpje', 'HsemVweIBp', 'choij'),
(44, 'Mild', 'Pain', 'doctor', 1, 'da03fd7774aa8e8792681b4a9d7dd769', 'cwNxYCeWZm', 'QURmCNVcUH', 'painm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentID`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`patientID`,`userID`) USING BTREE,
  ADD KEY `FK_user` (`userID`);

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
-- Indexes for table `prescriptionassignedtopatient`
--
ALTER TABLE `prescriptionassignedtopatient`
  ADD PRIMARY KEY (`drugID`,`patientID`) USING BTREE,
  ADD KEY `prescriptionassignedtopatient_patientID_FK` (`patientID`),
  ADD KEY `drug_FK` (`drugID`);

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
  MODIFY `patientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `roomNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895;

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
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

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
-- Constraints for table `prescriptionassignedtopatient`
--
ALTER TABLE `prescriptionassignedtopatient`
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
