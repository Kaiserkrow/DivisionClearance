-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: divisionclearance
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `entries`
--

DROP TABLE IF EXISTS `entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `school` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `purposeOfClearance` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `additionalNote` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateOfAction` date DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `divisionSigned` date NOT NULL,
  `schoolDistrictSigned` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entries`
--

LOCK TABLES `entries` WRITE;
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT INTO `entries` VALUES (66,'Example 4','example 4','Labo National High School','LABO - A','resigned','N/A','2025-07-31',NULL,NULL,'2025-07-23','2025-07-22'),(67,'example 5','Teacher I','Gumaus ES','PARACALE','sick Leave','N/A',NULL,'2025-07-21','2025-07-23','2025-07-21','2025-07-20'),(68,'example 6','Teacher II','Salvacion Big ES','SAN VICENTE - SAN LORENZO RUIZ','transferred Out','Papunta na sa example','2025-07-24',NULL,NULL,'2025-07-23','2025-07-22'),(69,'example 7 ','Teacher III','Macolabo ES','PARACALE','travel','Example 7',NULL,'2025-07-24','2025-07-28','2025-07-24','2025-07-23'),(70,'Example 8','example 8 ','Mancruz ES','DAET SOUTH','retirement','N/A','2025-08-09',NULL,NULL,'2025-07-26','2025-07-25'),(71,'Example 9 updated','example 9','Potenciano Juego ES','CAPALONGA','sick Leave','N/A',NULL,'2025-07-24','2025-07-28','2025-07-22','2025-07-19'),(72,'Andrew Garcia update','Principal I','Salvacion Big ES','SAN VICENTE - SAN LORENZO RUIZ','travel','Mapunta ako sa Legazpi, para magpasyal',NULL,'2025-08-19','2025-08-26','2025-08-17','2025-08-16');
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-11 16:45:26
