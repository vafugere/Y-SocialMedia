CREATE DATABASE  IF NOT EXISTS `y` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `y`;
-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: y
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follows` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  PRIMARY KEY (`follow_id`),
  KEY `FK_follows` (`from_id`),
  KEY `FK_follows2` (`to_id`),
  CONSTRAINT `FK_follows` FOREIGN KEY (`from_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `FK_follows2` FOREIGN KEY (`to_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES (4,33,30),(5,33,27),(6,33,31),(7,33,22),(8,33,20),(9,31,28),(10,31,20),(11,31,30),(13,31,23),(14,20,33),(15,27,33),(16,25,33);
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `tweet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`like_id`),
  KEY `FK_tweet_id_idx` (`tweet_id`),
  KEY `FK_user_id_idx` (`user_id`),
  CONSTRAINT `FK_tweet_id` FOREIGN KEY (`tweet_id`) REFERENCES `tweets` (`tweet_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (5,23,31,'2025-06-21 15:22:07'),(6,14,31,'2025-06-21 15:22:29');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tweets`
--

DROP TABLE IF EXISTS `tweets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tweets` (
  `tweet_id` int(11) NOT NULL AUTO_INCREMENT,
  `tweet_text` varchar(280) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `original_tweet_id` int(11) NOT NULL DEFAULT 0,
  `reply_to_tweet_id` int(11) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tweet_id`),
  KEY `FK_tweets` (`user_id`),
  CONSTRAINT `FK_tweets` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tweets`
--

LOCK TABLES `tweets` WRITE;
/*!40000 ALTER TABLE `tweets` DISABLE KEYS */;
INSERT INTO `tweets` VALUES (14,'Kindness is free. Sprinkle that stuff everywhere.',33,0,0,'2025-06-21 15:13:07'),(15,'Small progress is still progress.',33,0,0,'2025-06-21 15:13:18'),(16,'Don’t let yesterday take too much of today.',33,0,0,'2025-06-21 15:14:26'),(17,'I told my dog about my problems. He fell asleep.',27,0,0,'2025-06-21 15:15:29'),(18,'Nothing ruins a Friday more than realizing it’s actually Wednesday.',27,0,0,'2025-06-21 15:16:36'),(19,'Why do weekends go faster than weekdays?',27,0,0,'2025-06-21 15:17:08'),(20,'“I\'ll just watch one episode” — me, 4 hours ago.',27,0,0,'2025-06-21 15:17:19'),(21,'Running late is my cardio.',30,0,0,'2025-06-21 15:17:50'),(22,'My favorite exercise is a cross between a lunge and a crunch… I call it lunch.',30,0,0,'2025-06-21 15:18:04'),(23,'I put “take a break” on my to-do list so I can check something off.',30,0,0,'2025-06-21 15:19:02'),(24,'Me: I’ll get up early and be productive. Also me: Snooze x7.',31,0,0,'2025-06-21 15:19:37'),(25,'Why buy it for $7 when you can make it yourself for $92 in craft supplies?',31,0,0,'2025-06-21 15:20:13'),(26,'Create the life you can’t wait to wake up to.',31,0,0,'2025-06-21 15:20:45'),(27,'',31,14,0,'2025-06-21 15:22:25');
/*!40000 ALTER TABLE `tweets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `screen_name` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `postal_code` varchar(7) DEFAULT NULL,
  `contact_number` varchar(25) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `url` varchar(50) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (19,'Emma','Baker','emma','$2y$10$hb8ach6cVarMyiTyJQBVMeTHA7GOjZZYMxetwdn7/O8EnOra9SfDa',NULL,NULL,NULL,NULL,'emma@email.com',NULL,NULL,NULL,'2025-06-21 14:42:47','default_picture.jpg'),(20,'Lola','Jones','lola','$2y$10$5r1ysSRwyPw4/ITsjlcK5.g992qzVXuh0BqQ8T5aJmtDGOwWzo3KK',NULL,NULL,NULL,NULL,'lola@email.com',NULL,NULL,NULL,'2025-06-21 14:43:19','default_picture.jpg'),(21,'Blake','Henry','blake','$2y$10$fksPy4/dhc1ATM2wkx.pm.R6i.v3R9Tsfst8ssqXET9BVNatvyKEy',NULL,NULL,NULL,NULL,'blake@email.com',NULL,NULL,NULL,'2025-06-21 14:43:56','default_picture.jpg'),(22,'Mia','Thomas','mia','$2y$10$7.Kwz3MUDlBrqdLWehJ/6.EarFosrmktJ6JEgwmI0qzPiLdiPFIFC',NULL,NULL,NULL,NULL,'mia@email.com',NULL,NULL,NULL,'2025-06-21 14:44:29','default_picture.jpg'),(23,'Max','Cooper','max','$2y$10$73ZawS3mn3QSLjHNr30p5.tTVLh.azBAoDPt7MRreWkPi5PdR2PZ.',NULL,NULL,NULL,NULL,'max@email.com',NULL,NULL,NULL,'2025-06-21 14:47:28','default_picture.jpg'),(24,'Jack','Davis','jack','$2y$10$1kg/avMB3QPEzKXASbn2x.dh2vtEo6aaL0Bhq1IiPXYLxQTRTvOHO',NULL,NULL,NULL,NULL,'jack@email.com',NULL,NULL,NULL,'2025-06-21 14:48:06','default_picture.jpg'),(25,'Noah','Reed','noah','$2y$10$GNyVbbwm0PepQvJbVKTHT.SRkqBPGWAuzkhBVip8omWXnFCia/nbW',NULL,NULL,NULL,NULL,'noah@email.com',NULL,NULL,NULL,'2025-06-21 14:48:43','default_picture.jpg'),(26,'Luke','Harris','luke','$2y$10$U6q68kRz8.jWbiKOf2qOxeLHnMlIm9q0Auh9rCalDu6nMgiFxJEe2',NULL,NULL,NULL,NULL,'luke@email.com',NULL,NULL,NULL,'2025-06-21 14:49:13','default_picture.jpg'),(27,'Cole','Bryant','cole','$2y$10$jyLebW/USNK9r4RlyHeG/uPDILqdwAuTYaHpNpUwxlfnsvl54uHzW',NULL,NULL,NULL,NULL,'cole@email.com',NULL,NULL,NULL,'2025-06-21 14:49:50','default_picture.jpg'),(28,'Ava','Brooks','ava','$2y$10$UzWv3g3Gkija4i0U5nNpEO4m27VfZ2ktqXwK9Gl72jiWiAzQl6b1W',NULL,NULL,NULL,NULL,'ava@email.com',NULL,NULL,NULL,'2025-06-21 14:50:31','default_picture.jpg'),(29,'Ella','Scott','ella','$2y$10$j9Bveh0q3a2nxFACcvZ3peA4v9bqSh2K21oFRD1Aqis5Te23fCsCG',NULL,NULL,NULL,NULL,'ella@email.com',NULL,NULL,NULL,'2025-06-21 14:51:05','default_picture.jpg'),(30,'Zoe','Miller','zoe','$2y$10$ipdKpo2Rien/pp9zpBiWyuJjIRfP3EW43ycJltN8j5yqN7LRENmEC',NULL,NULL,NULL,NULL,'zoe@email.com',NULL,NULL,NULL,'2025-06-21 14:51:37','default_picture.jpg'),(31,'Ruby','Clark','ruby','$2y$10$LF5rZ/FL4t.Uf1O3rDQWROxECETyMP63EV5inzdtEI/xiTojY/vbW',NULL,NULL,NULL,NULL,'ruby@email.com',NULL,NULL,NULL,'2025-06-21 14:52:15','default_picture.jpg'),(32,'Ivy','Morgan','ivy','$2y$10$F3FnlRmK04zAVuJc4BwZfue1arw3B3POVG9C24Gs/oEiNw1ufFpP6',NULL,NULL,NULL,NULL,'ivy@email.com',NULL,NULL,NULL,'2025-06-21 14:52:54','default_picture.jpg'),(33,'Sadie','Hill','sadie','$2y$10$BqxjKNfw9t/4Dnf4VmaQHOFAiodTq3SEy1ITCtemR5oof42GFbSCe',NULL,NULL,NULL,NULL,'sadie@email.com',NULL,NULL,NULL,'2025-06-21 14:53:27','default_picture.jpg');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-21 13:57:07
