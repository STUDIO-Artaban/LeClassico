-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 26, 2016 at 01:22 PM
-- Server version: 5.5.47-0+deb7u1-log
-- PHP Version: 5.4.45-0+deb7u2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `le_classico`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_camarade`(IN `pseudo` VARCHAR(30) CHARSET latin1)
    NO SQL
BEGIN
INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade) VALUES (`pseudo`,'Webmaster');
INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade) VALUES (`pseudo`,`pseudo`);
INSERT INTO Albums (ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_EventID,ALB_Remark,ALB_Date) VALUES ('Journal',`pseudo`,0,0,'Album de publication',CURRENT_TIMESTAMP);
INSERT INTO Actualites (ACT_ActuID,ACT_Pseudo,ACT_Date,ACT_Camarade,ACT_Text,ACT_Link,ACT_Fichier) VALUES (NULL,'Webmaster',CURRENT_TIMESTAMP,NULL,CONCAT('CECI EST UN MESSAGE DU WEBMASTER! STOP!\nAJOUT D''UN NOUVEAU CAMARADE! STOP!\nPSEUDO DU NOUVEAU CAMARADE: ',`pseudo`,'! STOP!\nFIN DU MESSAGE! STOP!...STOP! STOP!'),NULL,NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_new_photo`(IN `album` VARCHAR(30), IN `photo` INT(4))
    MODIFIES SQL DATA
BEGIN
DECLARE shared INT(1);
DECLARE pseudo VARCHAR(30);
DECLARE walk CURSOR FOR SELECT ALB_Shared,ALB_Pseudo FROM Albums WHERE CONVERT(ALB_Nom USING latin1) = CONVERT(album USING latin1);
OPEN walk;
FETCH walk INTO shared,pseudo;
IF shared <> 0 THEN
INSERT INTO `Notifications` (NOT_Pseudo,NOT_Date,NOT_ObjType,NOT_ObjID) VALUES (pseudo,CURRENT_TIMESTAMP,'P',photo);
END IF;
CLOSE walk;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `purge_notifications`()
    MODIFIES SQL DATA
BEGIN
DELETE FROM Notifications WHERE NOT_LuFlag <> 0 AND DATE(DATE_ADD(NOT_Date, INTERVAL +1 YEAR)) < CURRENT_TIMESTAMP;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_object_owner`(`type` VARCHAR(1) CHARSET latin1, `id` INT(4)) RETURNS varchar(30) CHARSET latin1
    READS SQL DATA
BEGIN
DECLARE pseudo VARCHAR(30);
DECLARE actu CURSOR FOR SELECT ACT_Pseudo FROM Actualites WHERE ACT_ActuID = id;
DECLARE photo CURSOR FOR SELECT PHT_Pseudo FROM Photos WHERE PHT_FichierID = id;
IF CONVERT(type USING latin1) = CONVERT('A' USING latin1) THEN
OPEN actu;
FETCH actu INTO pseudo;
CLOSE actu;
ELSE
OPEN photo;
FETCH photo INTO pseudo;
CLOSE photo;
END IF;
RETURN pseudo;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Abonnements`
--

CREATE TABLE IF NOT EXISTS `Abonnements` (
  `ABO_Pseudo` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ABO_Camarade` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ABO_Status` int(1) NOT NULL DEFAULT '0',
  `ABO_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Actualites`
--

CREATE TABLE IF NOT EXISTS `Actualites` (
  `ACT_ActuID` int(4) NOT NULL,
  `ACT_Pseudo` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ACT_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ACT_Camarade` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `ACT_Text` text CHARACTER SET latin1 COLLATE latin1_general_ci,
  `ACT_Link` varchar(256) CHARACTER SET ascii DEFAULT NULL,
  `ACT_Fichier` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `ACT_Status` int(1) NOT NULL DEFAULT '0',
  `ACT_StatusDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

--
-- Triggers `Actualites`
--
DELIMITER $$
CREATE TRIGGER `ACTU_NOTIFICATION` AFTER INSERT ON `Actualites`
 FOR EACH ROW IF NEW.ACT_Camarade IS NOT NULL THEN
INSERT INTO `Notifications` (NOT_Pseudo,NOT_Date,NOT_ObjType,NOT_ObjID) VALUES (NEW.ACT_Camarade,CURRENT_TIMESTAMP,'A',NEW.ACT_ActuID);
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `RMV_COMMENTS` AFTER DELETE ON `Actualites`
 FOR EACH ROW DELETE FROM Commentaires WHERE COM_ObjID = OLD.ACT_ActuID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Albums`
--

CREATE TABLE IF NOT EXISTS `Albums` (
  `ALB_Nom` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ALB_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ALB_Shared` tinyint(1) NOT NULL DEFAULT '0',
  `ALB_EventID` int(4) NOT NULL DEFAULT '0',
  `ALB_Remark` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `ALB_Date` date DEFAULT NULL,
  `ALB_Status` int(1) NOT NULL DEFAULT '0',
  `ALB_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Albums`
--
DELIMITER $$
CREATE TRIGGER `ALB_STATUS_UPDATE` BEFORE UPDATE ON `Albums`
 FOR EACH ROW IF NEW.ALB_Status <> 2 THEN
SET NEW.ALB_Status = 1, NEW.ALB_StatusDate = CURRENT_TIMESTAMP;
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `PURGE_NOTIFICATIONS` AFTER INSERT ON `Albums`
 FOR EACH ROW CALL purge_notifications()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Camarades`
--

CREATE TABLE IF NOT EXISTS `Camarades` (
  `CAM_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `CAM_CodeConf` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `CAM_Nom` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Prenom` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Sexe` tinyint(1) DEFAULT NULL,
  `CAM_BornDate` date DEFAULT NULL,
  `CAM_Adresse` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Ville` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Postal` varchar(5) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Email` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Hobbies` text COLLATE latin1_general_ci,
  `CAM_APropos` text COLLATE latin1_general_ci,
  `CAM_LogDate` datetime DEFAULT NULL,
  `CAM_Admin` tinyint(1) NOT NULL DEFAULT '1',
  `CAM_Profile` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Banner` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `CAM_Located` tinyint(1) NOT NULL DEFAULT '0',
  `CAM_Latitude` double DEFAULT NULL,
  `CAM_Longitude` double DEFAULT NULL,
  `CAM_Status` int(1) NOT NULL DEFAULT '0',
  `CAM_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Camarades`
--
DELIMITER $$
CREATE TRIGGER `CAM_STATUS_UPDATE` BEFORE UPDATE ON `Camarades`
 FOR EACH ROW IF NEW.CAM_Status <> 2 THEN
SET NEW.CAM_Status = 1, NEW.CAM_StatusDate = CURRENT_TIMESTAMP;
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `INSERT_NEW_CAM` AFTER INSERT ON `Camarades`
 FOR EACH ROW CALL create_camarade(NEW.CAM_Pseudo)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Commentaires`
--

CREATE TABLE IF NOT EXISTS `Commentaires` (
  `COM_ObjType` varchar(1) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'P',
  `COM_ObjID` int(4) NOT NULL DEFAULT '0',
  `COM_Pseudo` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `COM_Date` datetime NOT NULL,
  `COM_Text` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `COM_Status` int(1) NOT NULL DEFAULT '0',
  `COM_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `Commentaires`
--
DELIMITER $$
CREATE TRIGGER `COMMENT_NOTIFICATION` AFTER INSERT ON `Commentaires`
 FOR EACH ROW BEGIN
DECLARE pseudo VARCHAR(30);
SET pseudo = get_object_owner(NEW.COM_ObjType,NEW.COM_ObjID);
IF CONVERT(pseudo USING latin1) NOT LIKE CONVERT(NEW.COM_Pseudo USING latin1) THEN
INSERT INTO `Notifications` (NOT_Pseudo,NOT_Date,NOT_ObjType,NOT_ObjID,NOT_ObjDate,NOT_ObjFrom) VALUES (pseudo,CURRENT_TIMESTAMP,NEW.COM_ObjType,NEW.COM_ObjID,NEW.COM_Date,NEW.COM_Pseudo);
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Evenements`
--

CREATE TABLE IF NOT EXISTS `Evenements` (
  `EVE_EventID` int(4) NOT NULL DEFAULT '0',
  `EVE_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `EVE_Nom` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `EVE_Lieu` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `EVE_Date` date NOT NULL DEFAULT '0000-00-00',
  `EVE_Flyer` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `EVE_Remark` text COLLATE latin1_general_ci,
  `EVE_Status` int(1) NOT NULL DEFAULT '0',
  `EVE_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Evenements`
--
DELIMITER $$
CREATE TRIGGER `EVE_STATUS_UPDATE` BEFORE UPDATE ON `Evenements`
 FOR EACH ROW IF NEW.EVE_Status <> 2 THEN
SET NEW.EVE_Status = 1, NEW.EVE_StatusDate = CURRENT_TIMESTAMP;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FlyerNumber`
--

CREATE TABLE IF NOT EXISTS `FlyerNumber` (
  `FNU_FlyerID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Forum`
--

CREATE TABLE IF NOT EXISTS `Forum` (
  `FRM_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `FRM_Message` text COLLATE latin1_general_ci NOT NULL,
  `FRM_Date` date NOT NULL DEFAULT '0000-00-00',
  `FRM_Time` time NOT NULL DEFAULT '00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Messagerie`
--

CREATE TABLE IF NOT EXISTS `Messagerie` (
  `MSG_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSG_From` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSG_Message` text COLLATE latin1_general_ci NOT NULL,
  `MSG_Date` date NOT NULL DEFAULT '0000-00-00',
  `MSG_Time` time NOT NULL DEFAULT '00:00:00',
  `MSG_LuFlag` tinyint(1) NOT NULL DEFAULT '0',
  `MSG_ReadStk` tinyint(1) NOT NULL DEFAULT '1',
  `MSG_WriteStk` tinyint(1) NOT NULL DEFAULT '0',
  `MSG_Objet` varchar(50) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Messagerie`
--
DELIMITER $$
CREATE TRIGGER `MESSAGE_NOTIFICATION` AFTER INSERT ON `Messagerie`
 FOR EACH ROW INSERT INTO `Notifications` (NOT_Pseudo,NOT_Date,NOT_ObjFrom,NOT_ObjDate) VALUES (NEW.MSG_Pseudo,CURRENT_TIMESTAMP,NEW.MSG_From,CONCAT_WS(' ',New.MSG_Date,New.MSG_Time))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Music`
--

CREATE TABLE IF NOT EXISTS `Music` (
  `MSC_Fichier` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Artiste` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Album` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Morceau` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Source` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `MSC_Status` int(1) NOT NULL DEFAULT '0',
  `MSC_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Music`
--
DELIMITER $$
CREATE TRIGGER `MSC_STATUS_UPDATE` BEFORE UPDATE ON `Music`
 FOR EACH ROW IF NEW.MSC_Status <> 2 THEN
SET NEW.MSC_Status = 1, NEW.MSC_StatusDate = CURRENT_TIMESTAMP;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MusicNumber`
--

CREATE TABLE IF NOT EXISTS `MusicNumber` (
  `MNU_MusicID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE IF NOT EXISTS `Notifications` (
  `NOT_Pseudo` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `NOT_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NOT_ObjType` varchar(1) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `NOT_ObjID` int(4) DEFAULT NULL,
  `NOT_ObjDate` datetime DEFAULT NULL,
  `NOT_ObjFrom` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `NOT_LuFlag` tinyint(1) NOT NULL DEFAULT '0',
  `NOT_Status` int(1) NOT NULL DEFAULT '0',
  `NOT_StatusDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `Notifications`
--
DELIMITER $$
CREATE TRIGGER `NOT_STATUS_UPDATE` BEFORE UPDATE ON `Notifications`
 FOR EACH ROW IF NEW.NOT_Status <> 2 THEN
SET NEW.NOT_Status = 1, NEW.NOT_StatusDate = CURRENT_TIMESTAMP;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PhotoNumber`
--

CREATE TABLE IF NOT EXISTS `PhotoNumber` (
  `PNU_PhotoID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Photos`
--

CREATE TABLE IF NOT EXISTS `Photos` (
  `PHT_Album` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `PHT_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `PHT_Fichier` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `PHT_FichierID` int(4) NOT NULL DEFAULT '0',
  `PHT_Status` int(1) NOT NULL DEFAULT '0',
  `PHT_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Triggers `Photos`
--
DELIMITER $$
CREATE TRIGGER `NEW_PHOTO_NOTIFICATION` AFTER INSERT ON `Photos`
 FOR EACH ROW CALL notify_new_photo(NEW.PHT_Album,NEW.PHT_FichierID)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Presents`
--

CREATE TABLE IF NOT EXISTS `Presents` (
  `PRE_EventID` int(4) NOT NULL DEFAULT '0',
  `PRE_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `PRE_Status` int(1) NOT NULL DEFAULT '0',
  `PRE_StatusDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Votes`
--

CREATE TABLE IF NOT EXISTS `Votes` (
  `VOT_Pseudo` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `VOT_Fichier` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `VOT_Note` tinyint(1) NOT NULL DEFAULT '0',
  `VOT_Total` int(4) NOT NULL DEFAULT '0',
  `VOT_Date` date NOT NULL DEFAULT '0000-00-00',
  `VOT_Type` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Abonnements`
--
ALTER TABLE `Abonnements`
  ADD PRIMARY KEY (`ABO_Pseudo`,`ABO_Camarade`);

--
-- Indexes for table `Actualites`
--
ALTER TABLE `Actualites`
  ADD UNIQUE KEY `ACT_ActuID` (`ACT_ActuID`);

--
-- Indexes for table `Albums`
--
ALTER TABLE `Albums`
  ADD KEY `ALB_Nom` (`ALB_Nom`),
  ADD KEY `ALB_Pseudo` (`ALB_Pseudo`,`ALB_Shared`,`ALB_EventID`);

--
-- Indexes for table `Camarades`
--
ALTER TABLE `Camarades`
  ADD UNIQUE KEY `CAM_Pseudo` (`CAM_Pseudo`);

--
-- Indexes for table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD PRIMARY KEY (`COM_ObjType`,`COM_ObjID`,`COM_Pseudo`,`COM_Date`);

--
-- Indexes for table `Evenements`
--
ALTER TABLE `Evenements`
  ADD UNIQUE KEY `EVE_EventID` (`EVE_EventID`),
  ADD KEY `EVE_Date` (`EVE_Date`);

--
-- Indexes for table `FlyerNumber`
--
ALTER TABLE `FlyerNumber`
  ADD UNIQUE KEY `FNU_FlyerID` (`FNU_FlyerID`);

--
-- Indexes for table `Forum`
--
ALTER TABLE `Forum`
  ADD KEY `FRM_Date` (`FRM_Date`,`FRM_Time`);

--
-- Indexes for table `Messagerie`
--
ALTER TABLE `Messagerie`
  ADD KEY `MSG_Pseudo` (`MSG_Pseudo`,`MSG_ReadStk`,`MSG_WriteStk`);

--
-- Indexes for table `Music`
--
ALTER TABLE `Music`
  ADD UNIQUE KEY `MSC_Fichier` (`MSC_Fichier`);

--
-- Indexes for table `MusicNumber`
--
ALTER TABLE `MusicNumber`
  ADD UNIQUE KEY `MNU_MusicID` (`MNU_MusicID`);

--
-- Indexes for table `Notifications`
--
ALTER TABLE `Notifications`
  ADD UNIQUE KEY `NOT_Date` (`NOT_Date`);

--
-- Indexes for table `PhotoNumber`
--
ALTER TABLE `PhotoNumber`
  ADD UNIQUE KEY `PNU_PhotoID` (`PNU_PhotoID`);

--
-- Indexes for table `Photos`
--
ALTER TABLE `Photos`
  ADD KEY `PHT_Album` (`PHT_Album`);

--
-- Indexes for table `Presents`
--
ALTER TABLE `Presents`
  ADD KEY `PRE_EventID` (`PRE_EventID`);

--
-- Indexes for table `Votes`
--
ALTER TABLE `Votes`
  ADD KEY `VOT_Pseudo` (`VOT_Pseudo`,`VOT_Fichier`,`VOT_Date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Actualites`
--
ALTER TABLE `Actualites`
  MODIFY `ACT_ActuID` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
