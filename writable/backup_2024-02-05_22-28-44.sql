-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: mingala
-- ------------------------------------------------------
-- Server version 	10.4.28-MariaDB
-- Date: Mon, 05 Feb 2024 22:28:44 +0000

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carton_detail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carton_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carton` varchar(20) NOT NULL,
  `id_item` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carton_detail`
--

LOCK TABLES `carton_detail` WRITE;
/*!40000 ALTER TABLE `carton_detail` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `carton_detail` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `carton_detail` with 0 row(s)
--

--
-- Table structure for table `carton_mingala`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carton_mingala` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carton` varchar(20) NOT NULL,
  `nomor_carton` int(11) NOT NULL,
  `qty_per_carton` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carton_mingala`
--

LOCK TABLES `carton_mingala` WRITE;
/*!40000 ALTER TABLE `carton_mingala` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `carton_mingala` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `carton_mingala` with 0 row(s)
--

--
-- Table structure for table `item_mingala`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_mingala` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` varchar(20) NOT NULL,
  `style` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `size` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `mo` varchar(255) NOT NULL,
  `date_wh` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_mingala`
--

LOCK TABLES `item_mingala` WRITE;
/*!40000 ALTER TABLE `item_mingala` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `item_mingala` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `item_mingala` with 0 row(s)
--

--
-- Table structure for table `stock_item`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_item`
--

LOCK TABLES `stock_item` WRITE;
/*!40000 ALTER TABLE `stock_item` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `stock_item` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_item` with 0 row(s)
--

--
-- Table structure for table `tb_packing`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_packing` (
  `id_packing` varchar(255) NOT NULL,
  `id_carton` varchar(255) NOT NULL,
  `qty_carton` int(11) NOT NULL,
  `date` date DEFAULT curdate(),
  PRIMARY KEY (`id_packing`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_packing`
--

LOCK TABLES `tb_packing` WRITE;
/*!40000 ALTER TABLE `tb_packing` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `tb_packing` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tb_packing` with 0 row(s)
--

--
-- Table structure for table `user_mingala`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_mingala` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_mingala`
--

LOCK TABLES `user_mingala` WRITE;
/*!40000 ALTER TABLE `user_mingala` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `user_mingala` VALUES (1,'IAMMM','admin','iam12@gmail.com','iam123'),(2,'Adam','user','adam12@gmail.com','adam123'),(3,'Sister','packing','packing@gmail.com','packing123');
/*!40000 ALTER TABLE `user_mingala` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `user_mingala` with 3 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Mon, 05 Feb 2024 22:28:44 +0000
