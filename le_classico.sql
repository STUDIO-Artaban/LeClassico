-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2016 at 07:13 PM
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

--
-- Dumping data for table `Abonnements`
--

INSERT INTO `Abonnements` (`ABO_Pseudo`, `ABO_Camarade`, `ABO_Status`, `ABO_StatusDate`) VALUES
('Ana', 'Ana', 0, '2016-07-30 19:49:50'),
('Ana', 'Azerty', 0, '2016-07-30 19:49:50'),
('Ana', 'Benoit', 0, '2016-07-30 19:49:50'),
('Ana', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Ana', 'Fab', 0, '2016-07-30 19:49:50'),
('Ana', 'Fred', 0, '2016-07-30 19:49:50'),
('Ana', 'Gautier', 0, '2016-07-30 19:49:50'),
('Ana', 'James', 0, '2016-07-30 19:49:50'),
('Ana', 'JM', 0, '2016-07-30 19:49:50'),
('Ana', 'JPA', 0, '2016-07-30 19:49:50'),
('Ana', 'Julie', 0, '2016-07-30 19:49:50'),
('Ana', 'Karine', 0, '2016-07-30 19:49:50'),
('Ana', 'Laurent', 0, '2016-07-30 19:49:50'),
('Ana', 'Pascal', 0, '2016-07-30 19:49:50'),
('Ana', 'Rico', 0, '2016-07-30 19:49:50'),
('Ana', 'Sam', 0, '2016-07-30 19:49:50'),
('Ana', 'Seik', 0, '2016-07-30 19:49:50'),
('Ana', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Ana', 'Tonio', 0, '2016-07-30 19:49:50'),
('Ana', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Ana', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Azerty', 'Ana', 0, '2016-07-30 19:49:50'),
('Azerty', 'Azerty', 0, '2016-07-30 19:49:50'),
('Azerty', 'Benoit', 0, '2016-07-30 19:49:50'),
('Azerty', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Azerty', 'Fab', 0, '2016-07-30 19:49:50'),
('Azerty', 'Fred', 0, '2016-07-30 19:49:50'),
('Azerty', 'Gautier', 0, '2016-07-30 19:49:50'),
('Azerty', 'James', 0, '2016-07-30 19:49:50'),
('Azerty', 'JM', 0, '2016-07-30 19:49:50'),
('Azerty', 'JPA', 0, '2016-07-30 19:49:50'),
('Azerty', 'Julie', 0, '2016-07-30 19:49:50'),
('Azerty', 'Karine', 0, '2016-07-30 19:49:50'),
('Azerty', 'Laurent', 0, '2016-07-30 19:49:50'),
('Azerty', 'Pascal', 0, '2016-07-30 19:49:50'),
('Azerty', 'Rico', 0, '2016-07-30 19:49:50'),
('Azerty', 'Sam', 0, '2016-07-30 19:49:50'),
('Azerty', 'Seik', 0, '2016-07-30 19:49:50'),
('Azerty', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Azerty', 'Tonio', 0, '2016-07-30 19:49:50'),
('Azerty', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Azerty', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Benoit', 'Ana', 0, '2016-07-30 19:49:50'),
('Benoit', 'Azerty', 0, '2016-07-30 19:49:50'),
('Benoit', 'Benoit', 0, '2016-07-30 19:49:50'),
('Benoit', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Benoit', 'Fab', 0, '2016-07-30 19:49:50'),
('Benoit', 'Fred', 0, '2016-07-30 19:49:50'),
('Benoit', 'Gautier', 0, '2016-07-30 19:49:50'),
('Benoit', 'James', 0, '2016-07-30 19:49:50'),
('Benoit', 'JM', 0, '2016-07-30 19:49:50'),
('Benoit', 'JPA', 0, '2016-07-30 19:49:50'),
('Benoit', 'Julie', 0, '2016-07-30 19:49:50'),
('Benoit', 'Karine', 0, '2016-07-30 19:49:50'),
('Benoit', 'Laurent', 0, '2016-07-30 19:49:50'),
('Benoit', 'Pascal', 0, '2016-07-30 19:49:50'),
('Benoit', 'Rico', 0, '2016-07-30 19:49:50'),
('Benoit', 'Sam', 0, '2016-07-30 19:49:50'),
('Benoit', 'Seik', 0, '2016-07-30 19:49:50'),
('Benoit', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Benoit', 'Tonio', 0, '2016-07-30 19:49:50'),
('Benoit', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Benoit', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Ana', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Azerty', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Benoit', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Fab', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Fred', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Gautier', 0, '2016-07-30 19:49:50'),
('Chrispski', 'James', 0, '2016-07-30 19:49:50'),
('Chrispski', 'JM', 0, '2016-07-30 19:49:50'),
('Chrispski', 'JPA', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Julie', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Karine', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Laurent', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Pascal', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Rico', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Sam', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Seik', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Tonio', 0, '2016-07-30 19:49:50'),
('Chrispski', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Chrispski', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Fab', 'Ana', 0, '2016-07-30 19:49:50'),
('Fab', 'Azerty', 0, '2016-07-30 19:49:50'),
('Fab', 'Benoit', 0, '2016-07-30 19:49:50'),
('Fab', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Fab', 'Fab', 0, '2016-07-30 19:49:50'),
('Fab', 'Fred', 0, '2016-07-30 19:49:50'),
('Fab', 'Gautier', 0, '2016-07-30 19:49:50'),
('Fab', 'James', 0, '2016-07-30 19:49:50'),
('Fab', 'JM', 0, '2016-07-30 19:49:50'),
('Fab', 'JPA', 0, '2016-07-30 19:49:50'),
('Fab', 'Julie', 0, '2016-07-30 19:49:50'),
('Fab', 'Karine', 0, '2016-07-30 19:49:50'),
('Fab', 'Laurent', 0, '2016-07-30 19:49:50'),
('Fab', 'Pascal', 0, '2016-07-30 19:49:50'),
('Fab', 'Rico', 0, '2016-07-30 19:49:50'),
('Fab', 'Sam', 0, '2016-07-30 19:49:50'),
('Fab', 'Seik', 0, '2016-07-30 19:49:50'),
('Fab', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Fab', 'Tonio', 0, '2016-07-30 19:49:50'),
('Fab', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Fab', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Fred', 'Ana', 0, '2016-07-30 19:49:50'),
('Fred', 'Azerty', 0, '2016-07-30 19:49:50'),
('Fred', 'Benoit', 0, '2016-07-30 19:49:50'),
('Fred', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Fred', 'Fab', 0, '2016-07-30 19:49:50'),
('Fred', 'Fred', 0, '2016-07-30 19:49:50'),
('Fred', 'Gautier', 0, '2016-07-30 19:49:50'),
('Fred', 'James', 0, '2016-07-30 19:49:50'),
('Fred', 'JM', 0, '2016-07-30 19:49:50'),
('Fred', 'JPA', 0, '2016-07-30 19:49:50'),
('Fred', 'Julie', 0, '2016-07-30 19:49:50'),
('Fred', 'Karine', 0, '2016-07-30 19:49:50'),
('Fred', 'Laurent', 0, '2016-07-30 19:49:50'),
('Fred', 'Pascal', 0, '2016-07-30 19:49:50'),
('Fred', 'Rico', 0, '2016-07-30 19:49:50'),
('Fred', 'Sam', 0, '2016-07-30 19:49:50'),
('Fred', 'Seik', 0, '2016-07-30 19:49:50'),
('Fred', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Fred', 'Tonio', 0, '2016-07-30 19:49:50'),
('Fred', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Fred', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Gautier', 'Ana', 0, '2016-07-30 19:49:50'),
('Gautier', 'Azerty', 0, '2016-07-30 19:49:50'),
('Gautier', 'Benoit', 0, '2016-07-30 19:49:50'),
('Gautier', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Gautier', 'Fab', 0, '2016-07-30 19:49:50'),
('Gautier', 'Fred', 0, '2016-07-30 19:49:50'),
('Gautier', 'Gautier', 0, '2016-07-30 19:49:50'),
('Gautier', 'James', 0, '2016-07-30 19:49:50'),
('Gautier', 'JM', 0, '2016-07-30 19:49:50'),
('Gautier', 'JPA', 0, '2016-07-30 19:49:50'),
('Gautier', 'Julie', 0, '2016-07-30 19:49:50'),
('Gautier', 'Karine', 0, '2016-07-30 19:49:50'),
('Gautier', 'Laurent', 0, '2016-07-30 19:49:50'),
('Gautier', 'Pascal', 0, '2016-07-30 19:49:50'),
('Gautier', 'Rico', 0, '2016-07-30 19:49:50'),
('Gautier', 'Sam', 0, '2016-07-30 19:49:50'),
('Gautier', 'Seik', 0, '2016-07-30 19:49:50'),
('Gautier', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Gautier', 'Tonio', 0, '2016-07-30 19:49:50'),
('Gautier', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Gautier', 'Webmaster', 0, '2016-07-30 19:49:50'),
('James', 'Ana', 0, '2016-07-30 19:49:50'),
('James', 'Azerty', 0, '2016-07-30 19:49:50'),
('James', 'Benoit', 0, '2016-07-30 19:49:50'),
('James', 'Chrispski', 0, '2016-07-30 19:49:50'),
('James', 'Fab', 0, '2016-07-30 19:49:50'),
('James', 'Fred', 0, '2016-07-30 19:49:50'),
('James', 'Gautier', 0, '2016-07-30 19:49:50'),
('James', 'James', 0, '2016-07-30 19:49:50'),
('James', 'JM', 0, '2016-07-30 19:49:50'),
('James', 'JPA', 0, '2016-07-30 19:49:50'),
('James', 'Julie', 0, '2016-07-30 19:49:50'),
('James', 'Karine', 0, '2016-07-30 19:49:50'),
('James', 'Laurent', 0, '2016-07-30 19:49:50'),
('James', 'Pascal', 0, '2016-07-30 19:49:50'),
('James', 'Rico', 0, '2016-07-30 19:49:50'),
('James', 'Sam', 0, '2016-07-30 19:49:50'),
('James', 'Seik', 0, '2016-07-30 19:49:50'),
('James', 'Sylvain', 0, '2016-07-30 19:49:50'),
('James', 'Tonio', 0, '2016-07-30 19:49:50'),
('James', 'TotenFest', 0, '2016-07-30 19:49:50'),
('James', 'Webmaster', 0, '2016-07-30 19:49:50'),
('JM', 'Ana', 0, '2016-07-30 19:49:50'),
('JM', 'Azerty', 0, '2016-07-30 19:49:50'),
('JM', 'Benoit', 0, '2016-07-30 19:49:50'),
('JM', 'Chrispski', 0, '2016-07-30 19:49:50'),
('JM', 'Fab', 0, '2016-07-30 19:49:50'),
('JM', 'Fred', 0, '2016-07-30 19:49:50'),
('JM', 'Gautier', 0, '2016-07-30 19:49:50'),
('JM', 'James', 0, '2016-07-30 19:49:50'),
('JM', 'JM', 0, '2016-07-30 19:49:50'),
('JM', 'JPA', 0, '2016-07-30 19:49:50'),
('JM', 'Julie', 0, '2016-07-30 19:49:50'),
('JM', 'Karine', 0, '2016-07-30 19:49:50'),
('JM', 'Laurent', 0, '2016-07-30 19:49:50'),
('JM', 'Pascal', 0, '2016-07-30 19:49:50'),
('JM', 'Rico', 0, '2016-07-30 19:49:50'),
('JM', 'Sam', 0, '2016-07-30 19:49:50'),
('JM', 'Seik', 0, '2016-07-30 19:49:50'),
('JM', 'Sylvain', 0, '2016-07-30 19:49:50'),
('JM', 'Tonio', 0, '2016-07-30 19:49:50'),
('JM', 'TotenFest', 0, '2016-07-30 19:49:50'),
('JM', 'Webmaster', 0, '2016-07-30 19:49:50'),
('JPA', 'Ana', 0, '2016-07-30 19:49:50'),
('JPA', 'Azerty', 0, '2016-07-30 19:49:50'),
('JPA', 'Benoit', 0, '2016-07-30 19:49:50'),
('JPA', 'Chrispski', 0, '2016-07-30 19:49:50'),
('JPA', 'Fab', 0, '2016-07-30 19:49:50'),
('JPA', 'Fred', 0, '2016-07-30 19:49:50'),
('JPA', 'Gautier', 0, '2016-07-30 19:49:50'),
('JPA', 'James', 0, '2016-07-30 19:49:50'),
('JPA', 'JM', 0, '2016-07-30 19:49:50'),
('JPA', 'JPA', 0, '2016-07-30 19:49:50'),
('JPA', 'Julie', 0, '2016-07-30 19:49:50'),
('JPA', 'Karine', 0, '2016-07-30 19:49:50'),
('JPA', 'Laurent', 0, '2016-07-30 19:49:50'),
('JPA', 'Pascal', 0, '2016-07-30 19:49:50'),
('JPA', 'Rico', 0, '2016-07-30 19:49:50'),
('JPA', 'Sam', 0, '2016-07-30 19:49:50'),
('JPA', 'Seik', 0, '2016-07-30 19:49:50'),
('JPA', 'Sylvain', 0, '2016-07-30 19:49:50'),
('JPA', 'Tonio', 0, '2016-07-30 19:49:50'),
('JPA', 'TotenFest', 0, '2016-07-30 19:49:50'),
('JPA', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Julie', 'Ana', 0, '2016-07-30 19:49:50'),
('Julie', 'Azerty', 0, '2016-07-30 19:49:50'),
('Julie', 'Benoit', 0, '2016-07-30 19:49:50'),
('Julie', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Julie', 'Fab', 0, '2016-07-30 19:49:50'),
('Julie', 'Fred', 0, '2016-07-30 19:49:50'),
('Julie', 'Gautier', 0, '2016-07-30 19:49:50'),
('Julie', 'James', 0, '2016-07-30 19:49:50'),
('Julie', 'JM', 0, '2016-07-30 19:49:50'),
('Julie', 'JPA', 0, '2016-07-30 19:49:50'),
('Julie', 'Julie', 0, '2016-07-30 19:49:50'),
('Julie', 'Karine', 0, '2016-07-30 19:49:50'),
('Julie', 'Laurent', 0, '2016-07-30 19:49:50'),
('Julie', 'Pascal', 0, '2016-07-30 19:49:50'),
('Julie', 'Rico', 0, '2016-07-30 19:49:50'),
('Julie', 'Sam', 0, '2016-07-30 19:49:50'),
('Julie', 'Seik', 0, '2016-07-30 19:49:50'),
('Julie', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Julie', 'Tonio', 0, '2016-07-30 19:49:50'),
('Julie', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Julie', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Karine', 'Ana', 0, '2016-07-30 19:49:50'),
('Karine', 'Azerty', 0, '2016-07-30 19:49:50'),
('Karine', 'Benoit', 0, '2016-07-30 19:49:50'),
('Karine', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Karine', 'Fab', 0, '2016-07-30 19:49:50'),
('Karine', 'Fred', 0, '2016-07-30 19:49:50'),
('Karine', 'Gautier', 0, '2016-07-30 19:49:50'),
('Karine', 'James', 0, '2016-07-30 19:49:50'),
('Karine', 'JM', 0, '2016-07-30 19:49:50'),
('Karine', 'JPA', 0, '2016-07-30 19:49:50'),
('Karine', 'Julie', 0, '2016-07-30 19:49:50'),
('Karine', 'Karine', 0, '2016-07-30 19:49:50'),
('Karine', 'Laurent', 0, '2016-07-30 19:49:50'),
('Karine', 'Pascal', 0, '2016-07-30 19:49:50'),
('Karine', 'Rico', 0, '2016-07-30 19:49:50'),
('Karine', 'Sam', 0, '2016-07-30 19:49:50'),
('Karine', 'Seik', 0, '2016-07-30 19:49:50'),
('Karine', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Karine', 'Tonio', 0, '2016-07-30 19:49:50'),
('Karine', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Karine', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Kriss', 'Kriss', 0, '2016-07-30 19:49:50'),
('Kriss', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Laurent', 'Ana', 0, '2016-07-30 19:49:50'),
('Laurent', 'Azerty', 0, '2016-07-30 19:49:50'),
('Laurent', 'Benoit', 0, '2016-07-30 19:49:50'),
('Laurent', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Laurent', 'Fab', 0, '2016-07-30 19:49:50'),
('Laurent', 'Fred', 0, '2016-07-30 19:49:50'),
('Laurent', 'Gautier', 0, '2016-07-30 19:49:50'),
('Laurent', 'James', 0, '2016-07-30 19:49:50'),
('Laurent', 'JM', 0, '2016-07-30 19:49:50'),
('Laurent', 'JPA', 0, '2016-07-30 19:49:50'),
('Laurent', 'Julie', 0, '2016-07-30 19:49:50'),
('Laurent', 'Karine', 0, '2016-07-30 19:49:50'),
('Laurent', 'Laurent', 0, '2016-07-30 19:49:50'),
('Laurent', 'Pascal', 0, '2016-07-30 19:49:50'),
('Laurent', 'Rico', 0, '2016-07-30 19:49:50'),
('Laurent', 'Sam', 0, '2016-07-30 19:49:50'),
('Laurent', 'Seik', 0, '2016-07-30 19:49:50'),
('Laurent', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Laurent', 'Tonio', 0, '2016-07-30 19:49:50'),
('Laurent', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Laurent', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Pascal', 'Ana', 0, '2016-07-30 19:49:50'),
('Pascal', 'Benoit', 0, '2016-07-30 19:49:50'),
('Pascal', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Pascal', 'Fab', 0, '2016-07-30 19:49:50'),
('Pascal', 'Fred', 0, '2016-07-30 19:49:50'),
('Pascal', 'Gautier', 0, '2016-07-30 19:49:50'),
('Pascal', 'JM', 0, '2016-07-30 19:49:50'),
('Pascal', 'JPA', 0, '2016-07-30 19:49:50'),
('Pascal', 'Julie', 0, '2016-07-30 19:49:50'),
('Pascal', 'Karine', 0, '2016-07-30 19:49:50'),
('Pascal', 'Kriss', 0, '2016-07-30 19:49:50'),
('Pascal', 'Laurent', 0, '2016-07-30 19:49:50'),
('Pascal', 'Pascal', 0, '2016-07-30 19:49:50'),
('Pascal', 'Rico', 0, '2016-07-30 19:49:50'),
('Pascal', 'Sam', 0, '2016-07-30 19:49:50'),
('Pascal', 'Seik', 0, '2016-07-30 19:49:50'),
('Pascal', 'Tonio', 0, '2016-07-30 19:49:50'),
('Pascal', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Pascal', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Rico', 'Ana', 0, '2016-07-30 19:49:50'),
('Rico', 'Azerty', 0, '2016-07-30 19:49:50'),
('Rico', 'Benoit', 0, '2016-07-30 19:49:50'),
('Rico', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Rico', 'Fab', 0, '2016-07-30 19:49:50'),
('Rico', 'Fred', 0, '2016-07-30 19:49:50'),
('Rico', 'Gautier', 0, '2016-07-30 19:49:50'),
('Rico', 'James', 0, '2016-07-30 19:49:50'),
('Rico', 'JM', 0, '2016-07-30 19:49:50'),
('Rico', 'JPA', 0, '2016-07-30 19:49:50'),
('Rico', 'Julie', 0, '2016-07-30 19:49:50'),
('Rico', 'Karine', 0, '2016-07-30 19:49:50'),
('Rico', 'Laurent', 0, '2016-07-30 19:49:50'),
('Rico', 'Pascal', 0, '2016-07-30 19:49:50'),
('Rico', 'Rico', 0, '2016-07-30 19:49:50'),
('Rico', 'Sam', 0, '2016-07-30 19:49:50'),
('Rico', 'Seik', 0, '2016-07-30 19:49:50'),
('Rico', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Rico', 'Tonio', 0, '2016-07-30 19:49:50'),
('Rico', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Rico', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Sam', 'Ana', 0, '2016-07-30 19:49:50'),
('Sam', 'Azerty', 0, '2016-07-30 19:49:50'),
('Sam', 'Benoit', 0, '2016-07-30 19:49:50'),
('Sam', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Sam', 'Fab', 0, '2016-07-30 19:49:50'),
('Sam', 'Fred', 0, '2016-07-30 19:49:50'),
('Sam', 'Gautier', 0, '2016-07-30 19:49:50'),
('Sam', 'James', 0, '2016-07-30 19:49:50'),
('Sam', 'JM', 0, '2016-07-30 19:49:50'),
('Sam', 'JPA', 0, '2016-07-30 19:49:50'),
('Sam', 'Julie', 0, '2016-07-30 19:49:50'),
('Sam', 'Karine', 0, '2016-07-30 19:49:50'),
('Sam', 'Laurent', 0, '2016-07-30 19:49:50'),
('Sam', 'Pascal', 0, '2016-07-30 19:49:50'),
('Sam', 'Rico', 0, '2016-07-30 19:49:50'),
('Sam', 'Sam', 0, '2016-07-30 19:49:50'),
('Sam', 'Seik', 0, '2016-07-30 19:49:50'),
('Sam', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Sam', 'Tonio', 0, '2016-07-30 19:49:50'),
('Sam', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Sam', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Seik', 'Ana', 0, '2016-07-30 19:49:50'),
('Seik', 'Azerty', 0, '2016-07-30 19:49:50'),
('Seik', 'Benoit', 0, '2016-07-30 19:49:50'),
('Seik', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Seik', 'Fab', 0, '2016-07-30 19:49:50'),
('Seik', 'Fred', 0, '2016-07-30 19:49:50'),
('Seik', 'Gautier', 0, '2016-07-30 19:49:50'),
('Seik', 'James', 0, '2016-07-30 19:49:50'),
('Seik', 'JM', 0, '2016-07-30 19:49:50'),
('Seik', 'JPA', 0, '2016-07-30 19:49:50'),
('Seik', 'Julie', 0, '2016-07-30 19:49:50'),
('Seik', 'Karine', 0, '2016-07-30 19:49:50'),
('Seik', 'Laurent', 0, '2016-07-30 19:49:50'),
('Seik', 'Pascal', 0, '2016-07-30 19:49:50'),
('Seik', 'Rico', 0, '2016-07-30 19:49:50'),
('Seik', 'Sam', 0, '2016-07-30 19:49:50'),
('Seik', 'Seik', 0, '2016-07-30 19:49:50'),
('Seik', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Seik', 'Tonio', 0, '2016-07-30 19:49:50'),
('Seik', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Seik', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Ana', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Azerty', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Benoit', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Fab', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Fred', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Gautier', 0, '2016-07-30 19:49:50'),
('Sylvain', 'James', 0, '2016-07-30 19:49:50'),
('Sylvain', 'JM', 0, '2016-07-30 19:49:50'),
('Sylvain', 'JPA', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Julie', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Karine', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Laurent', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Pascal', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Rico', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Sam', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Seik', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Tonio', 0, '2016-07-30 19:49:50'),
('Sylvain', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Sylvain', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Tonio', 'Ana', 0, '2016-07-30 19:49:50'),
('Tonio', 'Azerty', 0, '2016-07-30 19:49:50'),
('Tonio', 'Benoit', 0, '2016-07-30 19:49:50'),
('Tonio', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Tonio', 'Fab', 0, '2016-07-30 19:49:50'),
('Tonio', 'Fred', 0, '2016-07-30 19:49:50'),
('Tonio', 'Gautier', 0, '2016-07-30 19:49:50'),
('Tonio', 'James', 0, '2016-07-30 19:49:50'),
('Tonio', 'JM', 0, '2016-07-30 19:49:50'),
('Tonio', 'JPA', 0, '2016-07-30 19:49:50'),
('Tonio', 'Julie', 0, '2016-07-30 19:49:50'),
('Tonio', 'Karine', 0, '2016-07-30 19:49:50'),
('Tonio', 'Laurent', 0, '2016-07-30 19:49:50'),
('Tonio', 'Pascal', 0, '2016-07-30 19:49:50'),
('Tonio', 'Rico', 0, '2016-07-30 19:49:50'),
('Tonio', 'Sam', 0, '2016-07-30 19:49:50'),
('Tonio', 'Seik', 0, '2016-07-30 19:49:50'),
('Tonio', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Tonio', 'Tonio', 0, '2016-07-30 19:49:50'),
('Tonio', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Tonio', 'Webmaster', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Ana', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Azerty', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Benoit', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Chrispski', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Fab', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Fred', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Gautier', 0, '2016-07-30 19:49:50'),
('TotenFest', 'James', 0, '2016-07-30 19:49:50'),
('TotenFest', 'JM', 0, '2016-07-30 19:49:50'),
('TotenFest', 'JPA', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Julie', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Karine', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Laurent', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Pascal', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Rico', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Sam', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Seik', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Sylvain', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Tonio', 0, '2016-07-30 19:49:50'),
('TotenFest', 'TotenFest', 0, '2016-07-30 19:49:50'),
('TotenFest', 'Webmaster', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Ana', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Azerty', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Benoit', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Chrispski', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Fab', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Fred', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Gautier', 0, '2016-07-30 19:49:50'),
('Webmaster', 'James', 0, '2016-07-30 19:49:50'),
('Webmaster', 'JM', 0, '2016-07-30 19:49:50'),
('Webmaster', 'JPA', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Julie', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Karine', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Laurent', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Pascal', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Rico', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Sam', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Seik', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Sylvain', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Tonio', 0, '2016-07-30 19:49:50'),
('Webmaster', 'TotenFest', 0, '2016-07-30 19:49:50'),
('Webmaster', 'Webmaster', 0, '2016-07-30 19:49:50');

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
-- Dumping data for table `Actualites`
--

INSERT INTO `Actualites` (`ACT_ActuID`, `ACT_Pseudo`, `ACT_Date`, `ACT_Camarade`, `ACT_Text`, `ACT_Link`, `ACT_Fichier`, `ACT_Status`, `ACT_StatusDate`) VALUES
(25, 'Pascal', '2016-07-08 22:18:09', 'Seik', 'Post sûr le mur du camarade Seik!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(30, 'Ana', '2016-07-09 09:35:45', NULL, 'Je teste le post de Ana sur son mur!\r\nOk!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(31, 'Pascal', '2016-07-10 14:27:00', NULL, 'Mon premier post sur le fil d''actualité!', 'http://www.voila.fr', NULL, 0, '2016-07-30 21:55:30'),
(32, 'Seik', '2016-07-10 14:41:12', NULL, 'Je suis Seik et je viens de partager... ce qui suit!', NULL, 'LC0207.jpg', 0, '2016-07-30 21:55:30'),
(35, 'Webmaster', '2016-07-11 12:42:39', NULL, 'CECI EST UN MESSAGE DU WEBMASTER! STOP!\nAJOUT D''UN NOUVEAU CAMARADE! STOP!\nPSEUDO DU NOUVEAU CAMARADE: Kriss! STOP!\nFIN DU MESSAGE! STOP!...STOP! STOP!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(36, 'Pascal', '2016-07-11 12:44:45', NULL, 'Autre test avec\r\nretour chariot!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(37, 'Pascal', '2016-07-11 13:01:03', 'Kriss', 'Ola Kriss!\r\nBienvenue sur le site du classico.\r\nA+', NULL, NULL, 0, '2016-07-30 21:55:30'),
(38, 'fab', '2005-11-18 05:45:53', 'Seik', 'c''est vrai je suis trop impatient....c''est l''halu les photo de seik,j''ai jamais des trucs aussi enormes!!!\r\nsur mon petit bout de terre perdu au milieu de l''ocean indien j''ai du mal a réaliser qu''il existe des regroupements aussi garguentuesque!!!\r\nbiz les copains', NULL, NULL, 0, '2016-07-30 21:55:30'),
(39, 'Seik', '2005-11-25 12:02:42', 'Fab', 'Malheureusemnt c''est grosse teufs n''existent plus en France, il faut faire quelques kilometre pour les trouver! Ces photos sont toutes des soirées où je suis allé, en Hollande. Musique top et tres tres bonne ambiance. C''est sur que ca fait un peu loin de l''ocean indien... Mais si t''as l''occasion de venir en europe et que tu souhaite d''eclter en Hollande, fais moi signe... @+ camarade!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(40, 'Fab', '2005-12-08 06:16:47', 'Seik', 'salut seik c''est vrai tout ceci n''existe plus en france.....a mon grand desespoir,a une epoque ce genre de regroupement existait a montpellier(boréalis)on pouvait y voir jeff mills qui debutait a 2 platines ,laurent garnier qui faisait hurler la foule avec "ecstasy" .....nostalgique le fab....!\r\nen tout cas ta proposition n''est pas rentée dans l''oreillle d''un sourd!!!je laccepte avec joie!!\r\na+camarade!!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(41, 'Pascal', '2005-12-08 15:35:04', 'Fab', 'Oui, camarade Fab !! Mais dès que ton jet privé sera réparé, et que ton pilote aura décuvé !! MDR\r\nBye, Camarades...', NULL, NULL, 0, '2016-07-30 21:55:30'),
(42, 'Seik', '2005-12-20 15:52:54', 'Fab', 't''as ka venir en train wwhhaa!!!!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(43, 'Fab', '2005-12-22 16:48:45', 'Seik', 'MDR, ben pourquoi pas le tram ?!!!j''hesite ,je vous tiens au courant...en attendant bonnes fetes et piano piano sur la gouache   ;)', NULL, NULL, 0, '2016-07-30 21:55:30'),
(44, 'Pascal', '2005-12-23 11:51:04', 'Fab', 'Bonnes fêtes de fin d''année à toi aussi Fab !!!\r\nA+', NULL, NULL, 0, '2016-07-30 21:55:30'),
(45, 'Fab', '2005-12-26 07:26:04', 'Pascal', 'yes merci pascal!!le reggae coule a flots ainsi que le rhum et le zamal,a bon entendeur....', NULL, NULL, 0, '2016-07-30 21:55:30'),
(46, 'Fab', '2005-12-28 11:53:54', NULL, 'eh les gars!ya le volcan la fournaise qui pete et la lave arrive bientot a la mer !!c pas cool comme cadeau de noel ca??', NULL, NULL, 0, '2016-07-30 21:55:30'),
(47, 'Fab', '2006-01-01 12:17:07', NULL, 'bonne année a tous les camarades gling gling voeux!!!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(48, 'Pascal', '2006-01-04 10:46:25', NULL, 'Ouais, très bonne année 2006 à tous !!!...', NULL, NULL, 0, '2016-07-30 21:55:30'),
(49, 'Seik', '2006-01-13 17:33:54', NULL, 'Salut!!! Bonne année a tous!!!!\r\nJ''arrive pas a poser les photos d''une teuf que j''ai fais le 01 janvier (en hollande encore, bien sur hhaa!!!) probleme de serveur??', NULL, NULL, 0, '2016-07-30 21:55:30'),
(50, 'Pascal', '2006-01-18 15:24:49', 'Seik', 'Effectivement il y a un PB.\r\nJe regarde ça et je te tien au courant. A+', NULL, NULL, 0, '2016-07-30 21:55:30'),
(51, 'Seik', '2006-01-18 20:03:14', 'Pascal', 'ok camarade Pascal, envoie moi un email quand ca fonctionne a nouveau @+', NULL, NULL, 0, '2016-07-30 21:55:30'),
(52, 'Pascal', '2006-02-01 14:06:41', 'Seik', 'C bon ça fonctionne...', NULL, NULL, 0, '2016-07-30 21:55:30'),
(53, 'Seik', '2006-02-01 20:36:50', NULL, 'ok! Nouvelles photos en ligne, "teufs", crazyland le 1 janvier... Bon surf à tous!', NULL, NULL, 0, '2016-07-30 21:55:30'),
(54, 'Seik', '2006-02-02 17:21:23', NULL, 'Et aujourd''hui, encore des nouvelles photos d''une teufs early hardcore/oldschool où je suis allé le 21 janvier. Les prochaines ce sera en mars.', NULL, NULL, 0, '2016-07-30 21:55:30'),
(55, 'Pascal', '2006-02-10 15:52:10', NULL, 'Je m''avancerai pas trop, mais on dirait qu''il y a du son sur le site du Cl@ssico !!!\r\nJe vous en dirais plus dès lundi prochain.\r\nBon week-end. Bz', NULL, NULL, 0, '2016-07-30 21:55:30'),
(56, 'Pascal', '2006-02-17 11:29:39', NULL, 'Ou plutôt le lundi de la semaine d''aprés...', NULL, NULL, 0, '2016-07-30 21:55:30'),
(57, 'Pascal', '2006-05-04 13:10:48', NULL, 'Voilà ce qui peut arriver quand on administre une base de données sans réelles précautions: Il n''y a plus aucun commentaire sur les photos !!! \r\nDésolé.', NULL, NULL, 0, '2016-07-30 21:55:30'),
(58, 'Julie', '2016-07-21 09:56:19', NULL, 'Très bon site web! :)', 'http://studio-artaban.com', NULL, 0, '2016-07-30 21:55:30'),
(59, 'Pascal', '2016-07-21 10:04:55', NULL, 'De quel film est-ce tiré?', NULL, 'LC0212.jpg', 0, '2016-07-30 21:55:30'),
(61, 'Pascal', '2016-08-01 13:09:01', 'Ana', 'Ola! Voici un lien qui pourrait t''intéresser...\r\n;)', 'http://www.perdu.com', NULL, 0, '2016-08-01 15:09:01');

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
-- Dumping data for table `Albums`
--

INSERT INTO `Albums` (`ALB_Nom`, `ALB_Pseudo`, `ALB_Shared`, `ALB_EventID`, `ALB_Remark`, `ALB_Date`, `ALB_Status`, `ALB_StatusDate`) VALUES
('Tilllate.com', 'Pascal', 0, 0, 'Photos provenant du site HTTP://TILLLATE.COM', '2005-04-01', 1, '2016-07-31 21:57:57'),
('ToNiO En DéLiRe', 'Pascal', 1, 1, 'Tonio en délire !!!', '2005-04-01', 0, '2016-07-30 19:57:16'),
('Le Classico - En Vrac...', 'Pascal', 1, 0, NULL, '2005-04-25', 0, '2016-07-30 19:57:16'),
('prises de vue insulaires', 'Fab', 1, 0, NULL, '2005-06-10', 0, '2016-07-30 19:57:16'),
('année 80', 'JM', 1, 0, NULL, '2005-07-25', 0, '2016-07-30 19:57:16'),
('Teufs', 'Seik', 0, 0, '', '2005-11-08', 0, '2016-07-30 19:57:16'),
('Journal', 'Pascal', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Webmaster', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Tonio', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Sam', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'JM', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'James', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Fred', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Fab', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Ana', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'JPA', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Gautier', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Benoit', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Chrispski', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Azerty', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'TotenFest', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Sylvain', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Julie', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Karine', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Rico', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Seik', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Laurent', 0, 0, 'Album de publication', '2016-07-04', 0, '2016-07-30 19:57:16'),
('Journal', 'Kriss', 0, 0, 'Album de publication', '2016-07-11', 0, '2016-07-30 19:57:16');

--
-- Triggers `Albums`
--
DELIMITER $$
CREATE TRIGGER `ALB_STATUS_UPDATE` BEFORE UPDATE ON `Albums`
 FOR EACH ROW SET NEW.ALB_Status = 1, NEW.ALB_StatusDate = CURRENT_TIMESTAMP
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
-- Dumping data for table `Camarades`
--

INSERT INTO `Camarades` (`CAM_Pseudo`, `CAM_CodeConf`, `CAM_Nom`, `CAM_Prenom`, `CAM_Sexe`, `CAM_BornDate`, `CAM_Adresse`, `CAM_Ville`, `CAM_Postal`, `CAM_Email`, `CAM_Hobbies`, `CAM_APropos`, `CAM_LogDate`, `CAM_Admin`, `CAM_Profile`, `CAM_Banner`, `CAM_Located`, `CAM_Latitude`, `CAM_Longitude`, `CAM_Status`, `CAM_StatusDate`) VALUES
('Webmaster', 'bipbip', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-11-09 00:00:00', 0, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Pascal', 'ras34', 'Viguié', 'Pascal', 2, '1975-12-17', '3 rue du Château', 'Clapiers', '34830', 'scalpas@hotmail.fr', 'Ne rien faire, ou regarder la TV. Ce qui revient au même!', 'Trop bon, trop con...', '2016-08-01 14:53:48', 0, 'LC0205.jpg', 'LC0204.jpg', 0, NULL, NULL, 1, '2016-08-01 12:53:48'),
('Tonio', 'lc05303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-05-01 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Sam', 'lc0523', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('JM', 'lc04303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-07-25 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('James', 'zoufeli', NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'Tél: 06 03 45 00 46', '2005-04-20 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Fred', 'lc0323', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-09-18 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Fab', 'lc303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2008-04-12 00:00:00', 0, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Ana', 'lc0400', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-05 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('JPA', 'lc01303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-05-19 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Gautier', 'lc0123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-14 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Benoit', 'finnmark', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Chrispski', 'lc23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-20 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Azerty', 'lc040', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-14 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('TotenFest', 'lc103', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-11 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Sylvain', 'lc0301', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'je suis un ancien de l''iut et pote de Carine et de la grande Julie pour ceux qui me remettent pas', '2005-04-22 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Julie', 'lc1430', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-04-15 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Karine', 'lc333', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Rico', 'lc451', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Seik', 'lc0621', NULL, 'cedric', 2, NULL, NULL, NULL, NULL, 'cgnial34@hotmail.com', 'voyages, sorties (surtout en Hollande!), lectures...', NULL, '2016-08-01 17:46:36', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 15:46:36'),
('Laurent', 'lc4078', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2006-02-08 00:00:00', 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35'),
('Kriss', 'lc0412', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, 1, '2016-08-01 10:08:35');

--
-- Triggers `Camarades`
--
DELIMITER $$
CREATE TRIGGER `CAM_STATUS_UPDATE` BEFORE UPDATE ON `Camarades`
 FOR EACH ROW SET NEW.CAM_Status = 1, NEW.CAM_StatusDate = CURRENT_TIMESTAMP
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
-- Dumping data for table `Commentaires`
--

INSERT INTO `Commentaires` (`COM_ObjType`, `COM_ObjID`, `COM_Pseudo`, `COM_Date`, `COM_Text`, `COM_Status`, `COM_StatusDate`) VALUES
('A', 25, 'Ana', '2016-07-09 12:36:00', 'Il''y a une faute! sûr: "mûr".', 0, '2016-07-30 20:00:59'),
('A', 25, 'JM', '2016-07-09 12:16:00', 'ola!', 0, '2016-07-30 20:00:59'),
('A', 25, 'Pascal', '2016-07-09 12:29:32', 'test l''ajout "AVEC" !!!', 0, '2016-07-30 20:00:59'),
('A', 34, 'Pascal', '2016-07-11 13:00:14', 'Ajout comment...', 0, '2016-07-30 20:00:59'),
('A', 36, 'Ana', '2016-08-01 10:40:16', 'Avec retour charriot!?!... et des boeufs? :p', 0, '2016-08-01 08:41:23'),
('A', 37, 'Kriss', '2016-07-11 15:25:31', 'Merci! On se voit bientôt. Bye', 0, '2016-07-30 20:00:59'),
('A', 37, 'Rico', '2016-07-21 05:09:07', 'Je pourrais être de la partie?', 0, '2016-07-30 20:00:59'),
('A', 47, 'Pascal', '2016-07-11 15:38:58', 'Merci!... Avec un peu de retard ;)', 0, '2016-07-30 20:00:59'),
('A', 58, 'Ana', '2016-07-21 13:21:51', 'Pareil!... lol', 0, '2016-07-30 20:00:59'),
('A', 58, 'Karine', '2016-07-21 13:43:16', 'Idem! ;)', 0, '2016-07-30 20:00:59'),
('A', 58, 'Pascal', '2016-07-21 11:59:35', 'Je suis d''accord!', 0, '2016-07-30 20:00:59'),
('A', 59, 'JM', '2016-07-21 17:17:31', 'Aucune idée !?!', 0, '2016-07-30 20:00:59'),
('A', 59, 'Karine', '2016-07-21 17:36:48', 'Je sais! "Delicatessen" de Jeunet et Caro.', 0, '2016-07-30 20:00:59'),
('A', 59, 'Pascal', '2016-07-23 10:14:14', 'Exact! Bravo Karine! :)', 0, '2016-07-30 20:00:59'),
('A', 61, 'Seik', '2016-08-01 19:04:59', 'Effectivement!... Très utile ce lien :D', 0, '2016-08-01 17:04:59'),
('P', 2, 'Pascal', '2005-04-15 20:16:04', 'Et Hop !!! D''un bras...', 0, '2016-07-30 20:00:59'),
('P', 9, 'Pascal', '2005-06-09 09:57:43', 'Miss Kittin à la Villa...', 0, '2016-07-30 20:00:59'),
('P', 11, 'Pascal', '2016-06-30 18:16:16', 'Jennifer à droite, et à gauche... je sais plus! :p', 0, '2016-07-30 20:00:59'),
('P', 13, 'Pascal', '2005-06-09 09:58:58', 'On se calme les filles! :p', 0, '2016-07-30 20:00:59'),
('P', 24, 'Seik', '2016-08-01 19:08:25', 'Sexy! ;)', 0, '2016-08-01 17:08:25'),
('P', 29, 'Pascal', '2005-04-03 10:52:10', 'Sam & Co.', 0, '2016-07-30 20:00:59'),
('P', 32, 'Pascal', '2005-03-05 16:30:01', 'Monsieur "Laurent Garnier"', 0, '2016-07-30 20:00:59'),
('P', 46, 'Pascal', '2005-04-03 10:55:08', 'Whaou!! :)', 0, '2016-07-30 20:00:59'),
('P', 66, 'Pascal', '2005-06-10 12:02:04', 'Vous me faites une petite place?', 0, '2016-07-30 20:00:59'),
('P', 67, 'Seik', '2005-02-07 21:23:36', 'GRRRrrr! ;o)', 0, '2016-07-30 20:00:59'),
('P', 75, 'Pascal', '2005-01-21 18:32:41', 'Sven väth !!.. Yes', 0, '2016-07-30 20:00:59'),
('P', 104, 'Pascal', '2016-07-11 12:23:33', 'Tiens! Ils me disent quelque chose ces 2 là... ;)', 0, '2016-07-30 20:00:59'),
('P', 107, 'Pascal', '2005-01-21 18:34:12', 'Miam Miam!! :p', 0, '2016-07-30 20:00:59'),
('P', 109, 'Pascal', '2005-04-15 20:15:14', 'Ellen Allien...', 0, '2016-07-30 20:00:59'),
('P', 112, 'Pascal', '2016-06-30 16:24:12', 'Grosse soirée!', 0, '2016-07-30 20:00:59'),
('P', 112, 'Seik', '2005-01-21 18:42:51', 'Qlimax party!!!', 0, '2016-07-30 20:00:59'),
('P', 130, 'Pascal', '2005-04-15 19:03:06', 'D''oh!!! :p', 0, '2016-07-30 20:00:59'),
('P', 130, 'Pascal', '2005-04-15 19:04:27', '...elle est maké :(', 0, '2016-07-30 20:00:59'),
('P', 157, 'JM', '2005-04-28 11:25:30', 'Coquine!!!.. lol', 0, '2016-07-30 20:00:59'),
('P', 179, 'Pascal', '2005-03-05 17:02:48', 'La barre de fer!!!.. lol', 0, '2016-07-30 20:00:59'),
('P', 180, 'Pascal', '2016-06-30 16:26:18', 'Belle! Belle!', 0, '2016-07-30 20:00:59');

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
-- Dumping data for table `Evenements`
--

INSERT INTO `Evenements` (`EVE_EventID`, `EVE_Pseudo`, `EVE_Nom`, `EVE_Lieu`, `EVE_Date`, `EVE_Flyer`, `EVE_Remark`, `EVE_Status`, `EVE_StatusDate`) VALUES
(1, 'Pascal', 'Nashville Festival', 'Le Ranch de Tonio', '2004-12-31', 'FL3.jpg', 'De la Deep Country dans le champagne !!', 0, '2016-07-30 20:02:46'),
(2, 'Pascal', 'The Prodigy DJ set Leeroy', 'La Villa-Rouge', '2005-04-08', NULL, 'PAF: 15 euros\r\nMais j''en connais qui vont prendre la bouteille !!', 0, '2016-07-30 20:02:46'),
(3, 'Pascal', 'Vitalic Night', 'La Villa-Rouge', '2005-04-15', NULL, 'PAF: 15 &euro;\r\nAge minimum: 18\r\nStyle: Techno/Trance', 0, '2016-07-30 20:02:46'),
(4, 'Pascal', 'Lesbian & Gay Pride', 'Montpellier', '2005-06-04', 'FL6.jpg', 'Les RDV du Peyrou, la MARCHE et la "RAINBOW NIGHT VII" \r\n\r\nRendez-vous au Jardin du Peyrou:\r\n\r\n12 H 00 : Pique-nique organisé par le Collectif contre l''Homophobie\r\n\r\n13 H 00 : Forum des associations organisé par Angel\r\n\r\n15 H 00 : Départ de la Marche\r\n\r\n18 H 00 : Apéro au café de la Mer\r\n\r\n23 H 00 : Soirée de clôture "Rainbow Night VII" à la Villa Rouge (Navette gratuite en rotation toute la nuit à partir de minuit jusqu''à 6h30, au départ du Jardin du peyrou).', 0, '2016-07-30 20:02:46'),
(5, 'Pascal', 'APERO - DESERTICO', '37, Allée de Corfou - Apt n°103 - Mtp', '2005-06-10', 'FL11.jpg', 'A partir de 19h00, il n''y aura personne, pas de musique, rien à boire ni à manger.\r\nEt oui, c''est le Désertico...', 0, '2016-07-30 20:02:46'),
(6, 'Seik', 'Qlimax', 'Hollande', '2005-11-19', NULL, 'Grosse grosse rave\r\nwww.q-dance.nl', 0, '2016-07-30 20:02:46'),
(7, 'Seik', 'Jack de Marseille', 'Daytona', '2006-02-17', NULL, 'Il mix a partir de 1.30', 0, '2016-07-30 20:02:46');

--
-- Triggers `Evenements`
--
DELIMITER $$
CREATE TRIGGER `EVE_STATUS_UPDATE` BEFORE UPDATE ON `Evenements`
 FOR EACH ROW SET NEW.EVE_Status = 1, NEW.EVE_StatusDate = CURRENT_TIMESTAMP
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FlyerNumber`
--

CREATE TABLE IF NOT EXISTS `FlyerNumber` (
  `FNU_FlyerID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `FlyerNumber`
--

INSERT INTO `FlyerNumber` (`FNU_FlyerID`) VALUES
(13);

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

--
-- Dumping data for table `Forum`
--

INSERT INTO `Forum` (`FRM_Pseudo`, `FRM_Message`, `FRM_Date`, `FRM_Time`) VALUES
('Seik', 'Malheureusemnt c''est grosse teufs n''existent plus en France, il faut faire quelques kilometre pour les trouver! Ces photos sont toutes des soirées où je suis allé, en Hollande. Musique top et tres tres bonne ambiance. C''est sur que ca fait un peu loin de l''ocean indien... Mais si t''as l''occasion de venir en europe et que tu souhaite d''eclter en Hollande, fais moi signe... @+ camarade!', '2005-11-25', '13:02:42'),
('Fab', 'salut seik c''est vrai tout ceci n''existe plus en france.....a mon grand desespoir,a une epoque ce genre de regroupement existait a montpellier(boréalis)on pouvait y voir jeff mills qui debutait a 2 platines ,laurent garnier qui faisait hurler la foule avec "ecstasy" .....nostalgique le fab....!\r\nen tout cas ta proposition n''est pas rentée dans l''oreillle d''un sourd!!!je laccepte avec joie!!\r\na+camarade!!', '2005-12-08', '07:16:47'),
('Pascal', 'Oui, camarade Fab !! Mais dès que ton jet privé sera réparé, et que ton pilote aura décuvé !! MDR\r\nBye, Camarades...', '2005-12-08', '16:35:04'),
('Seik', 't''as ka venir en train wwhhaa!!!!', '2005-12-20', '16:52:54'),
('Fab', 'MDR, ben pourquoi pas le tram ?!!!j''hesite ,je vous tiens au courant...en attendant bonnes fetes et piano piano sur la gouache   ;)', '2005-12-22', '17:48:45'),
('Pascal', 'Bonnes fêtes de fin d''année à toi aussi Fab !!!\r\nA+', '2005-12-23', '12:51:04'),
('Fab', 'yes merci pascal!!le reggae coule a flots ainsi que le rhum et le zamal,a bon entendeur....', '2005-12-26', '08:26:04'),
('Fab', 'eh les gars!ya le volcan la fournaise qui pete et la lave arrive bientot a la mer !!c pas cool comme cadeau de noel ca??', '2005-12-28', '12:53:54'),
('Fab', 'bonne année a tous les camarades gling gling voeux!!!', '2006-01-01', '13:17:07'),
('Pascal', 'Ouais, très bonne année 2006 à tous !!!...', '2006-01-04', '11:46:25'),
('Seik', 'Salut!!! Bonne année a tous!!!!\r\nJ''arrive pas a poser les photos d''une teuf que j''ai fais le 01 janvier (en hollande encore, bien sur hhaa!!!) probleme de serveur??', '2006-01-13', '18:33:54'),
('Pascal', 'Effectivement il y a un PB.\r\nJe regarde ça et je te tien au courant. A+', '2006-01-18', '16:24:49'),
('Seik', 'ok camarade Pascal, envoie moi un email quand ca fonctionne a nouveau @+', '2006-01-18', '21:03:14'),
('Pascal', 'C bon ça fonctionne...', '2006-02-01', '15:06:41'),
('Seik', 'ok! Nouvelles photos en ligne, "teufs", crazyland le 1 janvier... Bon surf à tous!', '2006-02-01', '21:36:50'),
('Seik', 'Et aujourd''hui, encore des nouvelles photos d''une teufs early hardcore/oldschool où je suis allé le 21 janvier. Les prochaines ce sera en mars.', '2006-02-02', '18:21:23'),
('Pascal', 'Je m''avancerai pas trop, mais on dirait qu''il y a du son sur le site du Cl@ssico !!!\r\nJe vous en dirais plus dès lundi prochain.\r\nBon week-end. Bz', '2006-02-10', '16:52:10'),
('Pascal', 'Ou plutôt le lundi de la semaine d''aprés...', '2006-02-17', '12:29:39'),
('Pascal', 'Voilà ce qui peut arriver quand on administre une base de données sans réelles précautions: Il n''y a plus aucun commentaire sur les photos !!! \r\nDésolé.', '2006-05-04', '15:10:48'),
('Webmaster', 'CECI EST UN MESSAGE DU <b>WEBMASTER</b>! STOP!\nAJOUT D''UN NOUVEAU CAMARADE! STOP!\nPSEUDO DU NOUVEAU CAMARADE: <b>Free</b>! STOP!\nFIN DU MESSAGE! STOP!...STOP! STOP!', '2012-04-06', '01:10:21'),
('Webmaster', 'CECI EST UN MESSAGE DU <b>WEBMASTER</b>! STOP!\nAJOUT D''UN NOUVEAU CAMARADE! STOP!\nPSEUDO DU NOUVEAU CAMARADE: <b>Recruteur</b>! STOP!\nFIN DU MESSAGE! STOP!...STOP! STOP!', '2012-08-28', '19:31:43'),
('Pascal', 'Testage...', '2016-06-29', '18:07:07'),
('Webmaster', 'CECI EST UN MESSAGE DU <b>WEBMASTER</b>! STOP!\nAJOUT D''UN NOUVEAU CAMARADE! STOP!\nPSEUDO DU NOUVEAU CAMARADE: <b>Kriss</b>! STOP!\nFIN DU MESSAGE! STOP!...STOP! STOP!', '2016-07-11', '14:42:39');

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
-- Dumping data for table `Messagerie`
--

INSERT INTO `Messagerie` (`MSG_Pseudo`, `MSG_From`, `MSG_Message`, `MSG_Date`, `MSG_Time`, `MSG_LuFlag`, `MSG_ReadStk`, `MSG_WriteStk`, `MSG_Objet`) VALUES
('seik', 'Pascal', 'Alors,\r\n\r\nComment tu trouves le site du Classico. Pas mal, non ?\r\n\r\nEn tout cas tes photos sont excellentes !!!!\r\nCa ces des teufs... Grosses teufs pour gros fêtard !!\r\nTerrible !!!!\r\n\r\nMerci et A+...\r\n', '2005-11-09', '16:45:24', 1, 1, 1, 'Bienvenu à toi Camarade Seik !!'),
('pascal', 'Seik', 'C''est des teufs en Hollande où je suis allé. Eh on! Les meufs c pas des copines a moi helas ;)  Les photos provienne de sites néerlandais. \r\nPas mal le site, le seul truc chiant c''est que ca s''affiche mal au format 800*600, t vois ce que je veux dire? mais bon.. pas grave! @+', '2005-11-10', '09:57:09', 1, 1, 0, NULL),
('pascal', 'Seik', 'Salut Camarade!\r\nJe viens de mettre des photos d ela teufs de ce week end. Est ce qu''on est limité en nombre? Y''en a une qui n''est pas sur le site.. chelou... D''autres sont a venir.\r\n@+\r\n', '2005-11-14', '18:39:41', 1, 1, 0, 'nouvelles photos'),
('seik', 'Pascal', 'Salutation Camarade !!\r\n\r\nThéoriquement, tu peux mettre autant de photos\r\nque tu le désire. \r\n\r\nMaintenant, si il venait à y avoir un PB, fait moi le\r\nsavoir...\r\n\r\nMerci et @+ (Chouette soirée !!!!)', '2005-11-15', '16:06:24', 1, 1, 1, 'Y o Ré Til 1 bug ?'),
('pascal', 'Seik', 'ok, en fait ce qu''il s''est passé c''est qu''au lieu que les dernieres photos soient poacées sur la derniere page, elles se sont retrouvé sur la premiere... \r\nLa teuf etait mortelle, que d ela vielle techno toute la nuit, on a dansé non stop de 22h a 7h :)', '2005-11-16', '12:15:19', 1, 1, 0, NULL),
('pascal', 'Seik', 'Plus moyen de mettre des photos, ca dit "espace limité"... Tiens moi au courant, je les mettrai plus tard,\r\n@+', '2005-11-16', '17:41:30', 1, 1, 0, 'c plein??'),
('pascal', 'Seik', 'Salut Camarade Pascal!\r\nJe viens de mettre de nouvelles photos en ligne, Qlimax 2005, j''ay suis allé ce week end. 25.000 personnes!\r\nFais circuler l''info pour les camarades que caz interesse...\r\n@+', '2005-11-22', '13:19:43', 1, 1, 0, 'nouvelles photos :)'),
('Webmaster', 'Pascal', 'Salut,\r\nEst-ce que <b>LeClassico</b> est compatible avec Netscape?\r\nMerci.\r\nBye', '2012-04-05', '23:33:37', 0, 1, 0, 'Firefox compatible?'),
('Webmaster', 'Pascal', 'Alors, ça en est où?', '2012-04-06', '00:02:31', 0, 1, 0, 'Firefox compatible???'),
('Ana', 'Pascal', 'test...', '2012-04-06', '00:52:52', 0, 1, 0, NULL),
('Gautier', 'Pascal', 'test...', '2012-04-06', '00:53:38', 0, 1, 1, NULL),
('TotenFest', 'Pascal', '<table border=1 cellspacing=0 cellpadding=0>\r\n<tr>\r\n<td>Test</td>\r\n<td>Html</td>\r\n</tr>\r\n<tr>\r\n<td colspan=2>Compatible</td>\r\n</tr>\r\n</table>\r\n', '2012-04-06', '12:21:47', 0, 1, 1, 'Test... HTML'),
('Julie', 'Pascal', 'Ola Julie!\r\n...mais laquelle de Julie es-tu?\r\nLOL\r\n', '2016-06-29', '18:37:05', 0, 1, 0, 'Testage...'),
('JPA', 'Pascal', 'Ok...', '2016-06-29', '18:49:16', 0, 1, 0, 'Euh!'),
('Azerty', 'Pascal', 'Ok...', '2016-06-29', '18:49:56', 0, 1, 0, NULL),
('Benoit', 'Pascal', 'Ola!', '2016-06-29', '18:56:24', 0, 1, 0, 'Testage...'),
('Azerty', 'Pascal', 'test', '2016-07-01', '12:13:00', 0, 1, 0, 'test'),
('Ana', 'Pascal', 'Salut Ana!\r\nComment tu vas bien?\r\nBine?\r\n:)', '2016-08-01', '14:21:21', 0, 1, 1, 'Test trigger');

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
-- Dumping data for table `Music`
--

INSERT INTO `Music` (`MSC_Fichier`, `MSC_Pseudo`, `MSC_Artiste`, `MSC_Album`, `MSC_Morceau`, `MSC_Source`, `MSC_Status`, `MSC_StatusDate`) VALUES
('MSC1.wma', 'Pascal', '???', '???', '???', 'http://vp.magellan.free.fr/Temp/03 Piste 3.wma', 0, '2016-07-30 20:06:54'),
('MSC2.mp3', 'Pascal', 'Miss Kittin & Goldenboy', '???', 'Rippin Kittin (Vinylasyl RMX)', 'http://vp.magellan.free.fr/Temp/Miss Kittin & Goldenboy - Rippin Kittin (Vinylasyl RMX).mp3', 0, '2016-07-30 20:06:54'),
('MSC15.mp3', 'Pascal', 'Silicone Soul', 'Dancefloor FG', 'Right On, Right On', 'http://vp.magellan.free.fr/Temp/Dancefloor FG - CD2 - Piste 1 - Right On, Right On (Silicone Soul).mp3', 0, '2016-07-30 20:06:54'),
('MSC14.mp3', 'Pascal', 'Ellen Allien', '???', 'Sehnsucht', 'http://vp.magellan.free.fr/Temp/Ellen Allien - Sehnsucht.mp3', 0, '2016-07-30 20:06:54'),
('MSC13.mp3', 'Pascal', 'Midfield General', '???', 'Coatnoise (Dave Clarke Mix)', 'http://vp.magellan.free.fr/Temp/Midfield General - Coatnoise (Dave Clarke Mix).mp3', 0, '2016-07-30 20:06:54'),
('MSC9.wma', 'Pascal', 'TIEFSCHWARZ', 'Mish Mash', 'Dead Eyes Opened (Severed Heads)', 'http://vp.magellan.free.fr/Temp/TIEFSCHWARZ - CD1 - Piste 15 - Dead Eyes Opened (Severed Heads).wma', 0, '2016-07-30 20:06:54'),
('MSC12.mp3', 'Pascal', 'Miss Kittin & The Hacker', '???', 'Zombie Nation', 'http://vp.magellan.free.fr/Temp/Miss kittin & The hacker - Zombie Nation.mp3', 0, '2016-07-30 20:06:54'),
('MSC19.mp3', 'Pascal', 'FCommunications', 'Aurora Borealis', 'The Milky Way (Scan X Mix)', 'http://vp.magellan.free.fr/Temp/Aurora Borealis - The Milky Way (Scan X Mix).mp3', 0, '2016-07-30 20:06:54'),
('MSC17.mp3', 'Pascal', 'Coldplay', '???', 'Don''t and panic', 'http://vp.magellan.free.fr/Temp/Coldplay - Don''t and panic.mp3', 0, '2016-07-30 20:06:54'),
('MSC20.mp3', 'Pascal', 'Dee Lite', '???', 'Groove Is In The Heart', 'http://vp.magellan.free.fr/Temp/Dee Lite - Groove Is In The Heart.mp3', 0, '2016-07-30 20:06:54'),
('MSC21.mp3', 'Pascal', 'Lisa Miskovsky', '???', 'Still Alive', 'http://vp.magellan.free.fr/Temp/Lisa Miskovsky - Still Alive.mp3', 0, '2016-07-30 20:06:54'),
('MSC22.mp3', 'Pascal', 'Sia', '???', 'Breathe Me', 'http://vp.magellan.free.fr/Temp/Sia - Breathe Me.mp3', 0, '2016-07-30 20:06:54'),
('MSC23.mp3', 'Pascal', 'Lily Allen', 'It''s not me, it''s you', 'The Fear', 'http://vp.magellan.free.fr/Temp/Lily Allen - The Fear.mp3', 0, '2016-07-30 20:06:54'),
('MSC24.mp3', 'Pascal', 'Brian Eno', '???', 'An Ending (Ascent)', 'http://vp.magellan.free.fr/Temp/28 Days Later Soundtrack - 10 - An Ending (Ascent) (By Brian Eno).mp3', 0, '2016-07-30 20:06:54');

--
-- Triggers `Music`
--
DELIMITER $$
CREATE TRIGGER `MSC_STATUS_UPDATE` BEFORE UPDATE ON `Music`
 FOR EACH ROW SET NEW.MSC_Status = 1, NEW.MSC_StatusDate = CURRENT_TIMESTAMP
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MusicNumber`
--

CREATE TABLE IF NOT EXISTS `MusicNumber` (
  `MNU_MusicID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `MusicNumber`
--

INSERT INTO `MusicNumber` (`MNU_MusicID`) VALUES
(25);

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
  `NOT_LuFlag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Notifications`
--

INSERT INTO `Notifications` (`NOT_Pseudo`, `NOT_Date`, `NOT_ObjType`, `NOT_ObjID`, `NOT_ObjDate`, `NOT_ObjFrom`, `NOT_LuFlag`) VALUES
('Ana', '2016-08-01 12:21:21', NULL, NULL, '2016-08-01 14:21:21', 'Pascal', 0),
('Ana', '2016-08-01 13:09:01', 'A', 61, NULL, NULL, 0),
('Pascal', '2016-08-01 17:04:59', 'A', 61, '2016-08-01 19:04:59', 'Seik', 0),
('JM', '2016-08-01 17:08:25', 'P', 24, '2016-08-01 19:08:25', 'Seik', 0);

-- --------------------------------------------------------

--
-- Table structure for table `PhotoNumber`
--

CREATE TABLE IF NOT EXISTS `PhotoNumber` (
  `PNU_PhotoID` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `PhotoNumber`
--

INSERT INTO `PhotoNumber` (`PNU_PhotoID`) VALUES
(215);

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
-- Dumping data for table `Photos`
--

INSERT INTO `Photos` (`PHT_Album`, `PHT_Pseudo`, `PHT_Fichier`, `PHT_FichierID`, `PHT_Status`, `PHT_StatusDate`) VALUES
('Tilllate.com', 'Pascal', 'LC0001.jpg', 1, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0002.jpg', 2, 0, '2016-07-30 20:09:18'),
('ToNiO En DéLiRe', 'Pascal', 'LC0003.jpg', 3, 0, '2016-07-30 20:09:18'),
('ToNiO En DéLiRe', 'Pascal', 'LC0005.jpg', 5, 0, '2016-07-30 20:09:18'),
('ToNiO En DéLiRe', 'Pascal', 'LC0006.jpg', 6, 0, '2016-07-30 20:09:18'),
('ToNiO En DéLiRe', 'Pascal', 'LC0007.gif', 7, 0, '2016-07-30 20:09:18'),
('ToNiO En DéLiRe', 'Pascal', 'LC0008.gif', 8, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0009.jpg', 9, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0010.jpg', 10, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0011.jpg', 11, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0012.jpg', 12, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0013.jpg', 13, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0014.jpg', 14, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0015.jpg', 15, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0016.jpg', 16, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0017.jpg', 17, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0018.jpg', 18, 0, '2016-07-30 20:09:18'),
('Le Classico - En Vrac...', 'Pascal', 'LC0019.jpg', 19, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0020.jpg', 20, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0022.jpg', 22, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0023.jpg', 23, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0024.jpg', 24, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0025.jpg', 25, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0026.jpg', 26, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0027.jpg', 27, 0, '2016-07-30 20:09:18'),
('année 80', 'JM', 'LC0028.jpg', 28, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0029.jpg', 29, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0030.jpg', 30, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0031.jpg', 31, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0032.jpg', 32, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0033.jpg', 33, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0034.jpg', 34, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0035.jpg', 35, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0036.jpg', 36, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0037.jpg', 37, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0038.jpg', 38, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0039.jpg', 39, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0040.jpg', 40, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0041.jpg', 41, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0042.jpg', 42, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0043.jpg', 43, 0, '2016-07-30 20:09:18'),
('prises de vue insulaires', 'Fab', 'LC0044.jpg', 44, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Seik', 'LC0045.jpg', 45, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0046.jpg', 46, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0047.jpg', 47, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0048.jpg', 48, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0049.jpg', 49, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0050.jpg', 50, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0051.jpg', 51, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0052.jpg', 52, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0053.jpg', 53, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0054.jpg', 54, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0055.jpg', 55, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0056.jpg', 56, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0057.jpg', 57, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0058.jpg', 58, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0059.jpg', 59, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0060.jpg', 60, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0061.jpg', 61, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0062.jpg', 62, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0063.jpg', 63, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0064.jpg', 64, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0065.jpg', 65, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0066.jpg', 66, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0067.jpg', 67, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0068.jpg', 68, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0069.jpg', 69, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0070.jpg', 70, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0071.jpg', 71, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0072.jpg', 72, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0073.jpg', 73, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0074.jpg', 74, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0075.jpg', 75, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0076.jpg', 76, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0077.jpg', 77, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0078.jpg', 78, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0079.jpg', 79, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0080.jpg', 80, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0081.jpg', 81, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0082.jpg', 82, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0083.jpg', 83, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0084.jpg', 84, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0085.jpg', 85, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0086.jpg', 86, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0087.jpg', 87, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0088.jpg', 88, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0089.jpg', 89, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0090.jpg', 90, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0091.jpg', 91, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0092.jpg', 92, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0093.jpg', 93, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0094.jpg', 94, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0095.jpg', 95, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0096.jpg', 96, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0097.jpg', 97, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0098.jpg', 98, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0099.jpg', 99, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0100.jpg', 100, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0101.jpg', 101, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0102.jpg', 102, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0103.jpg', 103, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0104.jpg', 104, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0106.jpg', 106, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0107.jpg', 107, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0108.jpg', 108, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0109.jpg', 109, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0110.jpg', 110, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0111.jpg', 111, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0112.jpg', 112, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0113.jpg', 113, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0114.jpg', 114, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0115.jpg', 115, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0116.jpg', 116, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0117.jpg', 117, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0118.jpg', 118, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0119.jpg', 119, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0120.jpg', 120, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0121.jpg', 121, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0122.jpg', 122, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0123.jpg', 123, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0124.jpg', 124, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0125.jpg', 125, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0126.jpg', 126, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0127.jpg', 127, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0128.jpg', 128, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0129.jpg', 129, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0130.jpg', 130, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0131.jpg', 131, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0132.jpg', 132, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0133.jpg', 133, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0134.jpg', 134, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0136.jpg', 136, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0137.jpg', 137, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0138.jpg', 138, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0139.jpg', 139, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0140.jpg', 140, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0141.jpg', 141, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0142.jpg', 142, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0143.jpg', 143, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0144.jpg', 144, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0145.jpg', 145, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0146.jpg', 146, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0147.jpg', 147, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0148.jpg', 148, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0149.jpg', 149, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0150.jpg', 150, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0151.jpg', 151, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0152.jpg', 152, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0153.jpg', 153, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0154.jpg', 154, 0, '2016-07-30 20:09:18'),
('Teufs', 'Seik', 'LC0155.jpg', 155, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0156.jpg', 156, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0157.jpg', 157, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0158.jpg', 158, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0159.jpg', 159, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0160.jpg', 160, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0161.jpg', 161, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0162.jpg', 162, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0163.jpg', 163, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0176.jpg', 176, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0177.jpg', 177, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0179.jpg', 179, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0180.jpg', 180, 0, '2016-07-30 20:09:18'),
('Tilllate.com', 'Pascal', 'LC0181.jpg', 181, 0, '2016-07-30 20:09:18'),
('Journal', 'Seik', 'LC0207.jpg', 207, 0, '2016-07-30 20:09:18'),
('Journal', 'Pascal', 'LC0212.jpg', 212, 0, '2016-07-30 20:09:18');

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

--
-- Dumping data for table `Presents`
--

INSERT INTO `Presents` (`PRE_EventID`, `PRE_Pseudo`, `PRE_Status`, `PRE_StatusDate`) VALUES
(1, 'Pascal', 0, '2016-07-30 20:10:59'),
(1, 'JPA', 0, '2016-07-30 20:10:59'),
(2, 'Pascal', 0, '2016-07-30 20:10:59'),
(4, 'Pascal', 0, '2016-07-30 20:10:59'),
(7, 'Pascal', 0, '2016-07-30 20:10:59');

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
-- Dumping data for table `Votes`
--

INSERT INTO `Votes` (`VOT_Pseudo`, `VOT_Fichier`, `VOT_Note`, `VOT_Total`, `VOT_Date`, `VOT_Type`) VALUES
('Pascal', 'LC0003.jpg', -2, 2, '2005-11-09', 0),
('Pascal', 'LC0001.jpg', -2, -1, '2006-03-15', 0),
('Pascal', 'LC0002.jpg', -2, 0, '2005-11-09', 0),
('Gautier', 'LC0001.jpg', 1, 0, '2005-04-14', 0),
('Gautier', 'LC0002.jpg', 1, 0, '2005-04-14', 0),
('Pascal', 'LC0005.jpg', -2, 2, '2005-11-09', 0),
('Pascal', 'LC0016.jpg', 2, 0, '2005-04-25', 0),
('Pascal', 'LC0025.jpg', -2, 0, '2005-11-23', 0),
('Webmaster', 'LC0025.jpg', 2, 0, '2005-07-29', 0),
('Webmaster', 'LC0001.jpg', -2, 0, '2005-07-29', 0),
('Webmaster', 'LC0005.jpg', -2, 0, '2005-07-29', 0),
('Webmaster', 'LC0016.jpg', -2, 0, '2005-07-29', 0),
('Pascal', 'LC0029.jpg', -2, 2, '2005-11-09', 0),
('Pascal', 'LC0036.jpg', -2, 2, '2005-11-09', 0),
('Webmaster', 'LC0036.jpg', 1, 0, '2005-08-22', 0),
('Webmaster', 'LC0028.jpg', 2, 0, '2005-09-27', 0),
('Seik', 'LC0045.jpg', 2, -1, '2005-11-08', 0),
('Pascal', 'LC0051.jpg', -2, 2, '2005-11-23', 0),
('Pascal', 'LC0056.jpg', -2, 2, '2005-11-23', 0),
('Pascal', 'LC0067.jpg', 1, 2, '2016-06-29', 0),
('Pascal', 'LC0028.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0006.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0007.gif', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0008.gif', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0022.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0023.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0024.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0026.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0027.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0010.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0011.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0012.jpg', -2, -2, '2012-04-05', 0),
('Pascal', 'LC0020.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0032.jpg', 2, -2, '2012-04-05', 0),
('Pascal', 'LC0033.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0045.jpg', -2, -1, '2006-03-14', 0),
('Pascal', 'LC0009.jpg', -2, 0, '2005-11-09', 0),
('Pascal', 'LC0077.jpg', -2, 2, '2005-11-23', 0),
('Pascal', 'LC0050.jpg', -2, 0, '2012-04-05', 0),
('Pascal', 'LC0049.jpg', -2, 2, '2012-04-05', 0),
('Pascal', 'LC0107.jpg', 2, 0, '2005-11-23', 0),
('Pascal', 'LC0112.jpg', 2, 2, '2005-11-24', 0),
('Pascal', 'LC0130.jpg', 1, 2, '2016-06-07', 0),
('Pascal', 'MSC1.wma', -2, 12, '2007-03-14', 1),
('Pascal', 'MSC9.wma', -1, 10, '2007-03-14', 1),
('Pascal', 'MSC2.mp3', 2, 7, '2007-03-14', 1),
('Pascal', 'LC0157.jpg', -2, 4, '2007-03-14', 0),
('Pascal', 'LC0163.jpg', 2, 0, '2006-03-14', 0),
('Pascal', 'MSC14.mp3', 2, 0, '2007-03-14', 1),
('Pascal', 'MSC13.mp3', -1, 0, '2007-03-14', 1),
('Pascal', 'MSC12.mp3', 1, 0, '2007-03-14', 1),
('Pascal', 'MSC15.mp3', -1, 0, '2007-03-14', 1),
('Pascal', 'MSC17.mp3', 2, 0, '2007-03-14', 1),
('Pascal', 'MSC24.mp3', 2, 2, '2016-06-29', 1),
('Pascal', 'LC0046.jpg', -2, 0, '2012-04-05', 0),
('Pascal', 'LC0047.jpg', -2, 0, '2012-04-05', 0),
('Pascal', 'LC0048.jpg', -2, 0, '2012-04-05', 0),
('Pascal', 'LC0179.jpg', 1, 0, '2012-04-05', 0),
('Pascal', 'LC0180.jpg', 2, 2, '2016-06-07', 0),
('Seik', 'LC0014.jpg', -2, 0, '2016-08-01', 0);

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
