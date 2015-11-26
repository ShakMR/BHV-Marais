CREATE DATABASE  IF NOT EXISTS `bhv_dev` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `bhv_dev`;
-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: suilabs.com    Database: bhv_dev
-- ------------------------------------------------------
-- Server version	5.6.27-0ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Awards`
--

DROP TABLE IF EXISTS `Awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Awards` (
  `idAward` int(11) NOT NULL,
  `activation_date` datetime NOT NULL,
  `delivered` varchar(45) DEFAULT '0',
  PRIMARY KEY (`idAward`),
  UNIQUE KEY `activation_date_UNIQUE` (`activation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Awards`
--

LOCK TABLES `Awards` WRITE;
/*!40000 ALTER TABLE `Awards` DISABLE KEYS */;
INSERT INTO `Awards` VALUES (0,'2015-11-09 00:00:00','0');
/*!40000 ALTER TABLE `Awards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Code`
--

DROP TABLE IF EXISTS `Code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Code` (
  `idCode` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`idCode`),
  UNIQUE KEY `UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=500003 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Code`
--

LOCK TABLES `Code` WRITE;
/*!40000 ALTER TABLE `Code` DISABLE KEYS */;
INSERT INTO `Code` VALUES (5,'05TRG'),(15,'6P3S5'),(17,'7F65V'),(10,'7TXLS'),(3,'C5VVU'),(18,'D0IP0'),(19,'GMSZD'),(13,'I4D96'),(4,'JPD59'),(20,'KFIQI'),(7,'KU8D4'),(8,'L63GS'),(16,'OFQAJ'),(11,'RJ9RH'),(14,'TE6RR'),(9,'TW2YJ'),(6,'ZPAGF'),(12,'ZPAGS');
/*!40000 ALTER TABLE `Code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Inscription`
--

DROP TABLE IF EXISTS `Inscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inscription` (
  `idInscription` int(11) NOT NULL,
  `date` date NOT NULL,
  `Awards_idAward` int(11) DEFAULT NULL,
  `People_idPerson` int(11) NOT NULL,
  `Code_idCode` int(11) NOT NULL,
  PRIMARY KEY (`idInscription`),
  KEY `fk_Inscription_awards_idx` (`Awards_idAward`),
  KEY `fk_Inscription_people_idx` (`People_idPerson`),
  KEY `fk_Inscription_code_idx` (`Code_idCode`),
  CONSTRAINT `fk_Inscription_awards` FOREIGN KEY (`Awards_idAward`) REFERENCES `Awards` (`idAward`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Inscription_code` FOREIGN KEY (`Code_idCode`) REFERENCES `Code` (`idCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Inscription_people` FOREIGN KEY (`People_idPerson`) REFERENCES `People` (`idPerson`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inscription`
--

LOCK TABLES `Inscription` WRITE;
/*!40000 ALTER TABLE `Inscription` DISABLE KEYS */;
/*!40000 ALTER TABLE `Inscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `People`
--

DROP TABLE IF EXISTS `People`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `People` (
  `idPerson` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `lastname` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idPerson`),
  UNIQUE KEY `emails` (`email`),
  KEY `names` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `People`
--

LOCK TABLES `People` WRITE;
/*!40000 ALTER TABLE `People` DISABLE KEYS */;
/*!40000 ALTER TABLE `People` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access` (
  `username` varchar(10) NOT NULL,
  `password` char(64) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access`
--

LOCK TABLES `access` WRITE;
/*!40000 ALTER TABLE `access` DISABLE KEYS */;
INSERT INTO `access` VALUES ('bhv-marais','*1FB65153AD7C397E6ED323699D9682FAD01E9F98');
/*!40000 ALTER TABLE `access` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-11 19:36:18
