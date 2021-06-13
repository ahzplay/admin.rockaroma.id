-- MySQL dump 10.17  Distrib 10.3.24-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: rockaroma
-- ------------------------------------------------------
-- Server version	10.3.24-MariaDB-1:10.3.24+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_1_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_2_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_3_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_4_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_5_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
