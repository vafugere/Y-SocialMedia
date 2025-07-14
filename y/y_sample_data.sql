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
  KEY `FK_follows_from_id_idx` (`from_id`),
  KEY `FK_follows_to_id_idx` (`to_id`),
  CONSTRAINT `FK_follows_from_id` FOREIGN KEY (`from_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_follows_to_id` FOREIGN KEY (`to_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES (53,47,57),(54,47,44),(55,47,43),(56,47,52),(58,54,52),(59,54,43),(60,54,46),(61,54,44),(62,54,47),(63,44,52),(64,44,54),(65,44,43),(66,44,56),(67,44,57),(70,52,43),(71,52,46),(72,52,57),(73,52,44),(74,52,47),(75,43,52),(76,43,57),(77,43,46),(78,43,44),(79,43,56),(80,46,53),(81,46,43),(82,46,52),(83,46,56),(84,46,44),(85,53,56),(86,53,47),(87,53,57),(88,53,46),(89,53,44),(90,56,47),(91,56,44),(92,56,43),(93,56,52),(94,56,53),(96,47,53),(97,57,43),(98,57,47),(99,43,47),(100,45,44),(101,45,48),(102,45,55),(103,44,45),(104,48,45),(105,55,45);
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
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`like_id`),
  KEY `FK_likes_post_id_idx` (`post_id`),
  KEY `FK_likes_user_id_idx` (`user_id`),
  CONSTRAINT `FK_likes_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_likes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (71,92,43,'2025-07-14 17:22:23');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_text` mediumtext DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `original_post_id` int(11) NOT NULL DEFAULT 0,
  `reply_to_post_id` int(11) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`post_id`),
  KEY `FK_follows_from_id_idx` (`user_id`),
  KEY `FK_follows_to_id_idx` (`user_id`),
  CONSTRAINT `FK_posts_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (84,'<p><strong class=\"ql-size-large\">Link Hover</strong></p><p><span class=\"ql-font-monospace\">&lt;a href=\"index.html\" class=\"</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">links</strong><span class=\"ql-font-monospace\">\"&gt;Link&lt;/a&gt;</span></p><p><br></p><p><span class=\"ql-font-monospace\">a.</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">links</strong><span class=\"ql-font-monospace\"> {</span></p><p><span class=\"ql-font-monospace\">	color: #000;</span></p><p><span class=\"ql-font-monospace\">	text-decoration: none;</span></p><p><span class=\"ql-font-monospace\">}</span></p><p><span class=\"ql-font-monospace\">a:</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">links:hover</strong><span class=\"ql-font-monospace\"> {</span></p><p><span class=\"ql-font-monospace\">	color: #fff;</span></p><p><span class=\"ql-font-monospace\">}</span></p>',44,0,0,'2025-07-14 16:42:02'),(87,'<p><strong class=\"ql-size-large\">Block Links</strong></p><p><span class=\"ql-font-monospace\">&lt;a href=\"#\" class=\"</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">link</strong><span class=\"ql-font-monospace\">\"&gt;link 1&lt;/a&gt;</span></p><p><span class=\"ql-font-monospace\">&lt;a href=\"#\" class=\"</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">link</strong><span class=\"ql-font-monospace\">\"&gt;link 2&lt;/a&gt;</span></p><p><span class=\"ql-font-monospace\">&lt;a href=\"#\" class=\"</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">link</strong><span class=\"ql-font-monospace\">\"&gt;link 3&lt;/a&gt;</span></p><p><br></p><p><span class=\"ql-font-monospace\">a.</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">link</strong><span class=\"ql-font-monospace\"> {</span></p><p><span class=\"ql-font-monospace\">	display: inline-block;</span></p><p><span class=\"ql-font-monospace\">	color: #000;</span></p><p><span class=\"ql-font-monospace\">	font-size: 1em;</span></p><p><span class=\"ql-font-monospace\">	background-color: #c084fc;</span></p><p><span class=\"ql-font-monospace\">	text-decoration: none;</span></p><p><span class=\"ql-font-monospace\">	padding: 10px;</span></p><p><span class=\"ql-font-monospace\">	margin-right: 2px;</span></p><p><span class=\"ql-font-monospace\">}</span></p><p><span class=\"ql-font-monospace\">a.</span><strong class=\"ql-font-monospace\" style=\"color: rgb(230, 0, 0);\">link:hover</strong><span class=\"ql-font-monospace\"> {</span></p><p><span class=\"ql-font-monospace\">	color: #c084fc;</span></p><p><span class=\"ql-font-monospace\">	background-color: #000;</span></p><p><span class=\"ql-font-monospace\">}</span></p>',44,0,0,'2025-07-14 17:02:54'),(89,'<p class=\"ql-align-center\"><span class=\"ql-size-large ql-font-comic\" style=\"color: rgb(255, 153, 0);\">Why can\'t your nose be 12 inches long? </span></p><p class=\"ql-align-center\"><span class=\"ql-size-large ql-font-comic\" style=\"color: rgb(255, 153, 0);\">Because then it would be a foot.</span></p>',45,0,0,'2025-07-14 17:16:16'),(90,'<p class=\"ql-align-center\"><span class=\"ql-font-comic ql-size-large\" style=\"color: rgb(0, 102, 204);\">Parallel lines have so much in common… </span></p><p class=\"ql-align-center\"><span class=\"ql-font-comic ql-size-large\" style=\"color: rgb(0, 102, 204);\">it’s a shame they’ll never meet.</span></p>',45,0,0,'2025-07-14 17:17:13'),(91,'<p class=\"ql-align-center\"><span class=\"ql-font-comic ql-size-large\" style=\"color: rgb(230, 0, 0);\">What do you call fake spaghetti? An impasta.</span></p>',45,0,0,'2025-07-14 17:17:43'),(92,'<p class=\"ql-align-center\"><strong class=\"ql-size-large ql-font-monospace\" style=\"color: rgb(153, 51, 255);\">Hi Everyone!</strong></p>',43,0,0,'2025-07-14 17:22:18'),(95,NULL,45,92,0,'2025-07-14 17:32:47'),(96,'Welcome!',45,0,92,'2025-07-14 17:35:02');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
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
  `display_name` varchar(80) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(250) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (43,'Emma','Baker','Emma Baker','emma','$2y$10$QUe58o6n9EziF4Rcgf2okO3MRUrLw5PbbbBQDgSHmFVtH3JN91Y.K','emma@email.com','2025-07-11 18:10:30','default_picture.jpg'),(44,'Lola','Jones','Lola Jones','lola','$2y$10$HRNltEkhHV3Pco3gYC66x.lAIcoyUjLgiB2mbv84XU2n/8qSTtne2','lola@email.com','2025-07-11 18:10:58','default_picture.jpg'),(45,'Blake','Henry','Blake Henry','blake','$2y$10$U6xCm8JCHxmcn9tqSxHv3eyrxTPZ6Njn3B3DbFnnU.it1gskzwscG','blake@email.com','2025-07-11 18:11:28','default_picture.jpg'),(46,'Mia','Thomas','Mia Thomas','mia','$2y$10$zx4cHRiHhWXvBbgcrnwQgeiJccDDeMo8fqKICMsnM5GRrwyhNTbXe','mia@email.com','2025-07-11 18:12:12','default_picture.jpg'),(47,'Max','Cooper','Max Cooper','max','$2y$10$jFIhp2XgqtYm2PWW3doiIO6stoVhmm0n8GJ.GA7KDJZFkpUSKJOY6','max@email.com','2025-07-11 18:12:42','default_picture.jpg'),(48,'Jake','Davis','Jake Davis','jake','$2y$10$cmaxcn2CExsduWv2BqW7AORjhzC8eGGOsdn8UrLQa9S9SU8NRHDga','jake@email.com','2025-07-11 18:13:20','default_picture.jpg'),(49,'Noah','Reed','Noah Reed','noah','$2y$10$AN7PNvD4ahYu5tt/zML3Ae8jUBLZaMyr/mz/bHIi50mWqfbnH8WzC','noah@email.com','2025-07-11 18:13:51','default_picture.jpg'),(50,'Luke','Harris','Luke Harris','luke','$2y$10$p8FbXAyStipSJAhBY3RiXuTBreCCHXRGSvJ4r4JGFLRoaAdv.EDRu','luke@email.com','2025-07-11 18:14:33','default_picture.jpg'),(51,'Cole','Bryant','Cole Bryant','cole','$2y$10$Gnr6hACAbIuj9SzZ4c0T8.RmgG01llWeHlCPCsnGDeZcZ/a0LsR1y','cole@email.com','2025-07-11 18:15:05','default_picture.jpg'),(52,'Ava','Brooks','Ava Brooks','ava','$2y$10$EhpZzcU5u1nYKX98p5SJJOSC0mdj8a87BaZulM1mum0gRfpX3mVFi','ava@email.com','2025-07-11 18:15:29','default_picture.jpg'),(53,'Ella','Scott','Ella Scott','ella','$2y$10$KhqY/xsGueJG0Cax.z/HWedq6xACWku1t2xai5AoY5wjexA27BKYC','ella@email.com','2025-07-11 18:15:59','default_picture.jpg'),(54,'Zoe','Miller','Zoe Miller','zoe','$2y$10$KVzGYAQqYV5JsuVUR35IAeKEIxjbXVN1sDX3y4AWvScTaXMb72QCm','zoe@email.com','2025-07-11 20:50:26','default_picture.jpg'),(55,'Ruby','Clark','Ruby Clark','ruby','$2y$10$hpT3g8xURKiUhbztOvGsRe8.WTXHRf0zJOfZDaFX70ta7zcvlB0na','ruby@email.com','2025-07-11 20:51:04','default_picture.jpg'),(56,'Ivy','Morgan','Ivy Morgan','ivy','$2y$10$6hgVBxv9YalmnoFURgtTyeDjC/sLPfIbo1IEa3ByF6SLNM4bzqcAe','ivy@email.com','2025-07-11 20:51:46','default_picture.jpg'),(57,'Saddie','Hill','Saddie Hill','saddie','$2y$10$qAa4OE0zsmQwnPQuWtnx2eTOmqENVty/YMR4vTtnmFtbKiWykfgvW','saddie@email.com','2025-07-11 20:52:12','default_picture.jpg');
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

-- Dump completed on 2025-07-14 15:38:53
