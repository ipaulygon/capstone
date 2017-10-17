-- MySQL dump 10.13  Distrib 5.7.14, for Win32 (AMD64)
--
-- Host: localhost    Database: rapide-capstone
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `json` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_userid_foreign` (`userId`),
  CONSTRAINT `audit_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit`
--

LOCK TABLES `audit` WRITE;
/*!40000 ALTER TABLE `audit` DISABLE KEYS */;
INSERT INTO `audit` VALUES (1,1,'Create Purchase Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"date\":\"10\\/18\\/2017\",\"supplierId\":\"2\",\"productId\":null,\"product\":[\"2\"],\"qty\":[\"15\"],\"modelId\":[null],\"price\":[\"100.00\"],\"remarks\":null,\"computed\":\"1,500.00\"}','2017-10-17 18:06:27','2017-10-17 18:06:27'),(2,1,'Create Purchase Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"date\":\"10\\/18\\/2017\",\"supplierId\":\"1\",\"productId\":null,\"product\":[\"3\",\"1\",\"2\",\"4\"],\"qty\":[\"50\",\"20\",\"15\",\"36\"],\"modelId\":[null,null,null,null],\"price\":[\"300.00\",\"800.00\",\"100.00\",\"400.00\"],\"remarks\":null,\"computed\":\"32,400.00\"}','2017-10-17 18:11:06','2017-10-17 18:11:06'),(3,1,'Finalize Purchase Order','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:11:17','2017-10-17 18:11:17'),(4,1,'Receive Delivery','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"date\":\"10\\/18\\/2017\",\"order\":[\"ORDER00003\"],\"supplierId\":\"1\",\"purchaseId\":null,\"product\":[\"3\",\"1\",\"2\",\"4\"],\"qty\":[\"50\",\"15\",\"13\",\"36\"]}','2017-10-17 18:11:52','2017-10-17 18:11:52'),(5,1,'Check Delivery','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:12:01','2017-10-17 18:12:01'),(6,1,'Return Items','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"date\":\"10\\/18\\/2017\",\"order\":[\"DELIVERY00002\"],\"supplierId\":\"1\",\"deliveryId\":null,\"product\":[\"3\",\"1\",\"2\",\"4\"],\"delivery\":[\"DELIVERY00002\",\"DELIVERY00002\",\"DELIVERY00002\",\"DELIVERY00002\"],\"qty\":[\"5\",\"0\",\"0\",\"0\"],\"remarks\":null}','2017-10-17 18:14:21','2017-10-17 18:14:21'),(7,1,'Create Sales','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"firstName\":\"Khen\",\"middleName\":null,\"lastName\":\"Gaviola\",\"contact\":\"+63 999 9999 999\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"Mandaluyong City\",\"productId\":null,\"packageId\":null,\"promoId\":null,\"discountId\":null,\"promo\":[\"2\"],\"promoQty\":[\"1\"],\"product\":[\"2\"],\"productQty\":[\"5\"],\"computed\":\"3,751.13\"}','2017-10-17 18:25:49','2017-10-17 18:25:49'),(8,1,'Create Inspection','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"firstName\":\"Khen\",\"middleName\":null,\"lastName\":\"Gaviola\",\"contact\":\"+63 999 9999 999\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"Mandaluyong City\",\"technician\":[\"5\"],\"rackId\":\"1\",\"plate\":\"WMX 289\",\"modelId\":\"5,1\",\"mileage\":\"15000 km\",\"radio-group-1495719902602\":\"1\",\"text-1495719902626\":null,\"typeId\":[\"1\",\"1\",\"2\",\"2\",\"2\",\"2\"],\"typeName\":[\"Lights\",\"Lights\",\"Tires\",\"Tires\",\"Tires\",\"Tires\"],\"itemId\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"],\"itemName\":[\"Head Light\",\"Tail Light\",\"Front Left Tire\",\"Front Right Tire\",\"Rear Left Tire\",\"Rear Right Tire\"],\"form\":[\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"className\\\":\\\"\\\",\\\"name\\\":\\\"radio-group-1495719902602\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"text\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1495719902626\\\",\\\"subtype\\\":\\\"text\\\",\\\"maxlength\\\":\\\"100\\\"}]\",\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"className\\\":\\\"\\\",\\\"name\\\":\\\"radio-group-1495720031404\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"text\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1495720031415\\\",\\\"subtype\\\":\\\"text\\\",\\\"maxlength\\\":\\\"100\\\"}]\",\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"name\\\":\\\"radio-group-1502452774374\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"textarea\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"textarea-1502452774397\\\",\\\"subtype\\\":\\\"textarea\\\",\\\"maxlength\\\":\\\"100\\\"},{\\\"type\\\":\\\"text\\\",\\\"required\\\":true,\\\"label\\\":\\\"PSI\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1502452795394\\\",\\\"subtype\\\":\\\"text\\\",\\\"value\\\":\\\"29\\\"}]\",\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"name\\\":\\\"radio-group-1502452815378\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"textarea\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"textarea-1502452815386\\\",\\\"subtype\\\":\\\"textarea\\\",\\\"maxlength\\\":\\\"100\\\"},{\\\"type\\\":\\\"text\\\",\\\"required\\\":true,\\\"label\\\":\\\"PSI\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1502452818386\\\",\\\"subtype\\\":\\\"text\\\",\\\"value\\\":\\\"29\\\"}]\",\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"name\\\":\\\"radio-group-1502452839609\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"textarea\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"textarea-1502452839621\\\",\\\"subtype\\\":\\\"textarea\\\",\\\"maxlength\\\":\\\"100\\\"},{\\\"type\\\":\\\"text\\\",\\\"required\\\":true,\\\"label\\\":\\\"PSI\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1502452842526\\\",\\\"subtype\\\":\\\"text\\\",\\\"value\\\":\\\"29\\\"}]\",\"[{\\\"type\\\":\\\"radio-group\\\",\\\"required\\\":true,\\\"label\\\":\\\"Rating\\\",\\\"inline\\\":true,\\\"name\\\":\\\"radio-group-1502452861959\\\",\\\"values\\\":[{\\\"label\\\":\\\"\\ud83d\\ude03\\\",\\\"value\\\":\\\"1\\\",\\\"selected\\\":true},{\\\"label\\\":\\\"\\ud83d\\ude10\\\",\\\"value\\\":\\\"2\\\"},{\\\"label\\\":\\\"\\u2639\\ufe0f\\\",\\\"value\\\":\\\"3\\\"}]},{\\\"type\\\":\\\"textarea\\\",\\\"label\\\":\\\"Condition\\\",\\\"placeholder\\\":\\\"Condition\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"textarea-1502452861966\\\",\\\"subtype\\\":\\\"textarea\\\",\\\"maxlength\\\":\\\"100\\\"},{\\\"type\\\":\\\"text\\\",\\\"required\\\":true,\\\"label\\\":\\\"PSI\\\",\\\"className\\\":\\\"form-control\\\",\\\"name\\\":\\\"text-1502452865914\\\",\\\"subtype\\\":\\\"text\\\",\\\"value\\\":\\\"29\\\"}]\"],\"radio-group-1495720031404\":\"1\",\"text-1495720031415\":null,\"radio-group-1502452774374\":\"1\",\"textarea-1502452774397\":null,\"text-1502452795394\":\"29\",\"radio-group-1502452815378\":\"1\",\"textarea-1502452815386\":null,\"text-1502452818386\":\"29\",\"radio-group-1502452839609\":\"1\",\"textarea-1502452839621\":null,\"text-1502452842526\":\"29\",\"radio-group-1502452861959\":\"1\",\"textarea-1502452861966\":null,\"text-1502452865914\":\"29\",\"remarks\":null}','2017-10-17 18:30:02','2017-10-17 18:30:02'),(9,1,'Create Job Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"start\":\"2017-10-18\",\"firstName\":\"Rexielyn\",\"middleName\":\"Santos\",\"lastName\":\"Salvador\",\"contact\":\"+63 997 5212 842\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"Pasig City\",\"technician\":[\"7\",\"1\"],\"rackId\":\"1\",\"plate\":\"ASD 123\",\"modelId\":\"1,1\",\"mileage\":\"5000 km\",\"productId\":null,\"serviceId\":null,\"packageId\":null,\"promoId\":null,\"discountId\":null,\"product\":[\"3\"],\"productQty\":[\"1\"],\"package\":[\"2\"],\"packageQty\":[\"1\"],\"promo\":[\"2\"],\"promoQty\":[\"1\"],\"remarks\":null,\"computed\":\"3,850.25\"}','2017-10-17 18:34:06','2017-10-17 18:34:06'),(10,1,'Create Job Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"start\":\"2017-10-18\",\"firstName\":\"Ma. Nicole Ann\",\"middleName\":\"San Jose\",\"lastName\":\"Ungco\",\"contact\":\"+63 999 9991 234\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"Pasig City\",\"technician\":[\"4\"],\"rackId\":\"2\",\"plate\":\"ZXC 456\",\"modelId\":\"3,0\",\"mileage\":\"1200 km\",\"productId\":null,\"serviceId\":null,\"packageId\":null,\"promoId\":null,\"discountId\":null,\"service\":[\"6\"],\"product\":[\"2\"],\"productQty\":[\"2\"],\"remarks\":null,\"computed\":\"940.70\"}','2017-10-17 18:36:18','2017-10-17 18:36:18'),(11,1,'Create Estimate','[]','2017-10-17 18:36:35','2017-10-17 18:36:35'),(12,1,'Create Estimate','[]','2017-10-17 18:36:54','2017-10-17 18:36:54'),(13,1,'Finalize Job Order','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:37:19','2017-10-17 18:37:19'),(14,1,'Create Job Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"start\":\"2017-10-18\",\"firstName\":\"Madelyn\",\"middleName\":\"Pambid\",\"lastName\":\"Recio\",\"contact\":\"+63 998 4123 460\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"San Juan City\",\"technician\":[\"1\"],\"rackId\":\"3\",\"plate\":\"DPG 235\",\"modelId\":\"2,0\",\"mileage\":\"1000 km\",\"productId\":null,\"serviceId\":null,\"packageId\":null,\"promoId\":null,\"discountId\":null,\"product\":[\"3\"],\"productQty\":[\"1\"],\"remarks\":null,\"computed\":\"750.25\"}','2017-10-17 18:38:08','2017-10-17 18:38:08'),(15,1,'Finalize Job Order','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:38:17','2017-10-17 18:38:17'),(16,1,'Job Order Payment','{\"id\":\"5\",\"payment\":\"750.25\",\"method\":\"0\",\"cardName\":null,\"cardNumber\":null,\"cardExp\":null,\"cardSec\":null}','2017-10-17 18:38:29','2017-10-17 18:38:29'),(17,1,'Job Order Product Update','{\"detailId\":\"5\",\"productId\":\"3\",\"detailQty\":\"1\"}','2017-10-17 18:38:32','2017-10-17 18:38:32'),(18,1,'Release Job Order Vehicle','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:38:50','2017-10-17 18:38:50'),(19,1,'Create Job Order','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"start\":\"2017-10-18\",\"firstName\":\"Ethelyn\",\"middleName\":null,\"lastName\":\"Consista\",\"contact\":\"+63 123 1231 231\",\"email\":null,\"card\":null,\"street\":null,\"brgy\":null,\"city\":\"Mandaluyong City\",\"technician\":[\"2\"],\"rackId\":\"3\",\"plate\":\"JHK 685\",\"modelId\":\"3,1\",\"mileage\":\"1200 km\",\"productId\":null,\"serviceId\":null,\"packageId\":null,\"promoId\":null,\"promo\":[\"2\"],\"promoQty\":[\"1\"],\"discount\":[\"2\"],\"remarks\":null,\"computed\":\"1,714.29\"}','2017-10-17 18:40:16','2017-10-17 18:40:16'),(20,1,'Finalize Job Order','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:40:25','2017-10-17 18:40:25'),(21,1,'Job Order Payment','{\"id\":\"6\",\"payment\":\"1714.29\",\"method\":\"1\",\"cardName\":\"Ethelyn\",\"cardNumber\":\"4343 4343 4343 4322\",\"cardExp\":\"12\\/20\",\"cardSec\":\"123\"}','2017-10-17 18:40:59','2017-10-17 18:40:59'),(22,1,'Job Order Promo Update','{\"detailId\":\"2\",\"promoId\":\"2\",\"detailQty\":\"1\"}','2017-10-17 18:41:01','2017-10-17 18:41:01'),(23,1,'Release Job Order Vehicle','{\"_method\":\"PATCH\",\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\"}','2017-10-17 18:41:12','2017-10-17 18:41:12'),(24,1,'Create Job Warranty','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"jobId\":\"6\",\"promoProduct\":[\"4\",\"4\"],\"jobProductPromo\":[\"2\",\"2\"],\"promoProductQty\":[\"2\",\"0\"]}','2017-10-17 18:44:47','2017-10-17 18:44:47'),(25,1,'Disposed Items','{\"_token\":\"gLgdeaD55vHfvZ3oWZJTXc3QvRvRJTWXM1zAyavt\",\"productId\":\"1\",\"quantity\":\"4\",\"remarks\":\"Sira\"}','2017-10-17 18:45:51','2017-10-17 18:45:51');
/*!40000 ALTER TABLE `audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middleName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` text COLLATE utf8mb4_unicode_ci,
  `brgy` text COLLATE utf8mb4_unicode_ci,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_firstname_middlename_lastname_unique` (`firstName`,`middleName`,`lastName`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Madelyn','Pambid','Recio','','','San Juan City','+63 998 4123 460',NULL,NULL),(2,'Paul','','Cruz','','','San Juan City','+63 998 4123 460',NULL,NULL),(3,'Khen','','Gaviola','','','Mandaluyong City','+63 999 9999 999',NULL,NULL),(4,'Rexielyn','Santos','Salvador','','','Pasig City','+63 997 5212 842',NULL,NULL),(5,'Ma. Nicole Ann','San Jose','Ungco','','','Pasig City','+63 999 9991 234',NULL,NULL),(6,'Ethelyn','','Consista','','','Mandaluyong City','+63 123 1231 231',NULL,NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `damage_product`
--

DROP TABLE IF EXISTS `damage_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damage_product_productid_foreign` (`productId`),
  CONSTRAINT `damage_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `damage_product`
--

LOCK TABLES `damage_product` WRITE;
/*!40000 ALTER TABLE `damage_product` DISABLE KEYS */;
INSERT INTO `damage_product` VALUES (1,1,4,'Sira','2017-10-17 18:45:51','2017-10-17 18:45:51');
/*!40000 ALTER TABLE `damage_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_detail`
--

DROP TABLE IF EXISTS `delivery_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deliveryId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `returned` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_detail_deliveryid_foreign` (`deliveryId`),
  KEY `delivery_detail_productid_foreign` (`productId`),
  CONSTRAINT `delivery_detail_deliveryid_foreign` FOREIGN KEY (`deliveryId`) REFERENCES `delivery_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `delivery_detail_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_detail`
--

LOCK TABLES `delivery_detail` WRITE;
/*!40000 ALTER TABLE `delivery_detail` DISABLE KEYS */;
INSERT INTO `delivery_detail` VALUES (1,'DELIVERY00001',1,30,0,1,'2017-10-17 17:58:56','2017-10-17 17:58:56'),(2,'DELIVERY00001',2,30,0,1,'2017-10-17 17:58:56','2017-10-17 17:58:56'),(3,'DELIVERY00002',3,50,5,1,'2017-10-17 18:11:52','2017-10-17 18:14:21'),(4,'DELIVERY00002',1,15,0,1,'2017-10-17 18:11:52','2017-10-17 18:11:52'),(5,'DELIVERY00002',2,13,0,1,'2017-10-17 18:11:52','2017-10-17 18:11:52'),(6,'DELIVERY00002',4,36,0,1,'2017-10-17 18:11:52','2017-10-17 18:11:52');
/*!40000 ALTER TABLE `delivery_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_header`
--

DROP TABLE IF EXISTS `delivery_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_header` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplierId` int(10) unsigned NOT NULL,
  `dateMake` date NOT NULL,
  `isReceived` tinyint(1) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_header_supplierid_foreign` (`supplierId`),
  CONSTRAINT `delivery_header_supplierid_foreign` FOREIGN KEY (`supplierId`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_header`
--

LOCK TABLES `delivery_header` WRITE;
/*!40000 ALTER TABLE `delivery_header` DISABLE KEYS */;
INSERT INTO `delivery_header` VALUES ('DELIVERY00001',1,'2017-08-06',1,1,'2017-10-17 17:58:56','2017-10-17 17:58:56'),('DELIVERY00002',1,'2017-10-18',1,1,'2017-10-17 18:11:52','2017-10-17 18:12:01');
/*!40000 ALTER TABLE `delivery_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_order`
--

DROP TABLE IF EXISTS `delivery_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deliveryId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchaseId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_order_deliveryid_foreign` (`deliveryId`),
  KEY `delivery_order_purchaseid_foreign` (`purchaseId`),
  CONSTRAINT `delivery_order_deliveryid_foreign` FOREIGN KEY (`deliveryId`) REFERENCES `delivery_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `delivery_order_purchaseid_foreign` FOREIGN KEY (`purchaseId`) REFERENCES `purchase_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_order`
--

LOCK TABLES `delivery_order` WRITE;
/*!40000 ALTER TABLE `delivery_order` DISABLE KEYS */;
INSERT INTO `delivery_order` VALUES (1,'DELIVERY00001','ORDER00001','2017-10-17 17:58:57','2017-10-17 17:58:57'),(2,'DELIVERY00002','ORDER00003','2017-10-17 18:11:52','2017-10-17 18:11:52');
/*!40000 ALTER TABLE `delivery_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(5,2) NOT NULL,
  `isWhole` tinyint(1) NOT NULL,
  `isVatExempt` tinyint(1) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount`
--

LOCK TABLES `discount` WRITE;
/*!40000 ALTER TABLE `discount` DISABLE KEYS */;
INSERT INTO `discount` VALUES (1,'Summer Sale',10.00,0,0,1),(2,'Senior Citizen',20.00,1,1,1),(3,'5%',5.00,0,1,1);
/*!40000 ALTER TABLE `discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_product`
--

DROP TABLE IF EXISTS `discount_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount_product` (
  `discountId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `discount_product_discountid_foreign` (`discountId`),
  KEY `discount_product_productid_foreign` (`productId`),
  CONSTRAINT `discount_product_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `discount_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_product`
--

LOCK TABLES `discount_product` WRITE;
/*!40000 ALTER TABLE `discount_product` DISABLE KEYS */;
INSERT INTO `discount_product` VALUES (1,2,1,'2017-10-17 17:58:55','2017-10-17 17:58:55'),(3,1,1,'2017-10-17 18:06:00','2017-10-17 18:06:00');
/*!40000 ALTER TABLE `discount_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_rate`
--

DROP TABLE IF EXISTS `discount_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount_rate` (
  `discountId` int(10) unsigned NOT NULL,
  `rate` double(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `discount_rate_discountid_foreign` (`discountId`),
  CONSTRAINT `discount_rate_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_rate`
--

LOCK TABLES `discount_rate` WRITE;
/*!40000 ALTER TABLE `discount_rate` DISABLE KEYS */;
INSERT INTO `discount_rate` VALUES (1,10.00,'2017-10-17 17:58:55','2017-10-17 17:58:55'),(2,20.00,'2017-10-17 17:58:55','2017-10-17 17:58:55'),(3,5.00,'2017-10-17 18:06:00','2017-10-17 18:06:00');
/*!40000 ALTER TABLE `discount_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_service`
--

DROP TABLE IF EXISTS `discount_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount_service` (
  `discountId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `discount_service_discountid_foreign` (`discountId`),
  KEY `discount_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `discount_service_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `discount_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_service`
--

LOCK TABLES `discount_service` WRITE;
/*!40000 ALTER TABLE `discount_service` DISABLE KEYS */;
INSERT INTO `discount_service` VALUES (1,1,1,'2017-10-17 17:58:55','2017-10-17 17:58:55');
/*!40000 ALTER TABLE `discount_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_discount`
--

DROP TABLE IF EXISTS `estimate_discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `discountId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `estimate_discount_estimateid_foreign` (`estimateId`),
  KEY `estimate_discount_discountid_foreign` (`discountId`),
  CONSTRAINT `estimate_discount_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_discount_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_discount`
--

LOCK TABLES `estimate_discount` WRITE;
/*!40000 ALTER TABLE `estimate_discount` DISABLE KEYS */;
/*!40000 ALTER TABLE `estimate_discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_header`
--

DROP TABLE IF EXISTS `estimate_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `customerId` int(10) unsigned NOT NULL,
  `vehicleId` int(10) unsigned NOT NULL,
  `rackId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estimate_header_jobid_foreign` (`jobId`),
  KEY `estimate_header_customerid_foreign` (`customerId`),
  KEY `estimate_header_vehicleid_foreign` (`vehicleId`),
  KEY `estimate_header_rackid_foreign` (`rackId`),
  CONSTRAINT `estimate_header_customerid_foreign` FOREIGN KEY (`customerId`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_header_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_header_rackid_foreign` FOREIGN KEY (`rackId`) REFERENCES `rack` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_header_vehicleid_foreign` FOREIGN KEY (`vehicleId`) REFERENCES `vehicle` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_header`
--

LOCK TABLES `estimate_header` WRITE;
/*!40000 ALTER TABLE `estimate_header` DISABLE KEYS */;
INSERT INTO `estimate_header` VALUES (1,4,5,4,2,'2017-10-17 18:36:35','2017-10-17 18:36:35'),(2,3,4,3,1,'2017-10-17 18:36:53','2017-10-17 18:36:53');
/*!40000 ALTER TABLE `estimate_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_package`
--

DROP TABLE IF EXISTS `estimate_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `packageId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `estimate_package_estimateid_foreign` (`estimateId`),
  KEY `estimate_package_packageid_foreign` (`packageId`),
  CONSTRAINT `estimate_package_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_package_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_package`
--

LOCK TABLES `estimate_package` WRITE;
/*!40000 ALTER TABLE `estimate_package` DISABLE KEYS */;
INSERT INTO `estimate_package` VALUES (1,2,2,1,1);
/*!40000 ALTER TABLE `estimate_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_product`
--

DROP TABLE IF EXISTS `estimate_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `estimate_product_estimateid_foreign` (`estimateId`),
  KEY `estimate_product_productid_foreign` (`productId`),
  CONSTRAINT `estimate_product_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_product`
--

LOCK TABLES `estimate_product` WRITE;
/*!40000 ALTER TABLE `estimate_product` DISABLE KEYS */;
INSERT INTO `estimate_product` VALUES (1,1,2,2,1),(2,2,3,1,1);
/*!40000 ALTER TABLE `estimate_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_promo`
--

DROP TABLE IF EXISTS `estimate_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `promoId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `estimate_promo_estimateid_foreign` (`estimateId`),
  KEY `estimate_promo_promoid_foreign` (`promoId`),
  CONSTRAINT `estimate_promo_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_promo_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_promo`
--

LOCK TABLES `estimate_promo` WRITE;
/*!40000 ALTER TABLE `estimate_promo` DISABLE KEYS */;
INSERT INTO `estimate_promo` VALUES (1,2,2,1,1);
/*!40000 ALTER TABLE `estimate_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_service`
--

DROP TABLE IF EXISTS `estimate_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `estimate_service_estimateid_foreign` (`estimateId`),
  KEY `estimate_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `estimate_service_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_service`
--

LOCK TABLES `estimate_service` WRITE;
/*!40000 ALTER TABLE `estimate_service` DISABLE KEYS */;
INSERT INTO `estimate_service` VALUES (1,1,6,1);
/*!40000 ALTER TABLE `estimate_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_technician`
--

DROP TABLE IF EXISTS `estimate_technician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_technician` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estimateId` int(10) unsigned NOT NULL,
  `technicianId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estimate_technician_estimateid_foreign` (`estimateId`),
  KEY `estimate_technician_technicianid_foreign` (`technicianId`),
  CONSTRAINT `estimate_technician_estimateid_foreign` FOREIGN KEY (`estimateId`) REFERENCES `estimate_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `estimate_technician_technicianid_foreign` FOREIGN KEY (`technicianId`) REFERENCES `technician` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_technician`
--

LOCK TABLES `estimate_technician` WRITE;
/*!40000 ALTER TABLE `estimate_technician` DISABLE KEYS */;
INSERT INTO `estimate_technician` VALUES (1,1,4,1,'2017-10-17 18:36:35','2017-10-17 18:36:35'),(2,2,7,1,'2017-10-17 18:36:54','2017-10-17 18:36:54'),(3,2,1,1,'2017-10-17 18:36:54','2017-10-17 18:36:54');
/*!40000 ALTER TABLE `estimate_technician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspection_detail`
--

DROP TABLE IF EXISTS `inspection_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inspection_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inspectionId` int(10) unsigned NOT NULL,
  `itemId` int(10) unsigned NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_detail_inspectionid_foreign` (`inspectionId`),
  KEY `inspection_detail_itemid_foreign` (`itemId`),
  CONSTRAINT `inspection_detail_inspectionid_foreign` FOREIGN KEY (`inspectionId`) REFERENCES `inspection_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_detail_itemid_foreign` FOREIGN KEY (`itemId`) REFERENCES `inspection_item` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_detail`
--

LOCK TABLES `inspection_detail` WRITE;
/*!40000 ALTER TABLE `inspection_detail` DISABLE KEYS */;
INSERT INTO `inspection_detail` VALUES (1,1,1,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"className\":\"\",\"name\":\"radio-group-1495719902602\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"text\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"text-1495719902626\",\"subtype\":\"text\",\"maxlength\":\"100\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02'),(2,1,2,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"className\":\"\",\"name\":\"radio-group-1495720031404\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"text\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"text-1495720031415\",\"subtype\":\"text\",\"maxlength\":\"100\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02'),(3,1,3,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"name\":\"radio-group-1502452774374\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"textarea\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"textarea-1502452774397\",\"subtype\":\"textarea\",\"maxlength\":\"100\"},{\"type\":\"text\",\"required\":true,\"label\":\"PSI\",\"className\":\"form-control\",\"name\":\"text-1502452795394\",\"subtype\":\"text\",\"value\":\"29\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02'),(4,1,4,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"name\":\"radio-group-1502452815378\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"textarea\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"textarea-1502452815386\",\"subtype\":\"textarea\",\"maxlength\":\"100\"},{\"type\":\"text\",\"required\":true,\"label\":\"PSI\",\"className\":\"form-control\",\"name\":\"text-1502452818386\",\"subtype\":\"text\",\"value\":\"29\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02'),(5,1,5,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"name\":\"radio-group-1502452839609\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"textarea\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"textarea-1502452839621\",\"subtype\":\"textarea\",\"maxlength\":\"100\"},{\"type\":\"text\",\"required\":true,\"label\":\"PSI\",\"className\":\"form-control\",\"name\":\"text-1502452842526\",\"subtype\":\"text\",\"value\":\"29\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02'),(6,1,6,'[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Rating\",\"inline\":true,\"name\":\"radio-group-1502452861959\",\"values\":[{\"label\":\"?\",\"value\":\"1\",\"selected\":true},{\"label\":\"?\",\"value\":\"2\"},{\"label\":\"☹️\",\"value\":\"3\"}]},{\"type\":\"textarea\",\"label\":\"Condition\",\"placeholder\":\"Condition\",\"className\":\"form-control\",\"name\":\"textarea-1502452861966\",\"subtype\":\"textarea\",\"maxlength\":\"100\"},{\"type\":\"text\",\"required\":true,\"label\":\"PSI\",\"className\":\"form-control\",\"name\":\"text-1502452865914\",\"subtype\":\"text\",\"value\":\"29\"}]',1,'2017-10-17 18:30:02','2017-10-17 18:30:02');
/*!40000 ALTER TABLE `inspection_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspection_header`
--

DROP TABLE IF EXISTS `inspection_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inspection_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerId` int(10) unsigned NOT NULL,
  `vehicleId` int(10) unsigned NOT NULL,
  `rackId` int(10) unsigned NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_header_customerid_foreign` (`customerId`),
  KEY `inspection_header_vehicleid_foreign` (`vehicleId`),
  KEY `inspection_header_rackid_foreign` (`rackId`),
  CONSTRAINT `inspection_header_customerid_foreign` FOREIGN KEY (`customerId`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_header_rackid_foreign` FOREIGN KEY (`rackId`) REFERENCES `rack` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_header_vehicleid_foreign` FOREIGN KEY (`vehicleId`) REFERENCES `vehicle` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_header`
--

LOCK TABLES `inspection_header` WRITE;
/*!40000 ALTER TABLE `inspection_header` DISABLE KEYS */;
INSERT INTO `inspection_header` VALUES (1,3,2,1,'','2017-10-17 18:30:01','2017-10-17 18:30:01');
/*!40000 ALTER TABLE `inspection_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspection_item`
--

DROP TABLE IF EXISTS `inspection_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inspection_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `typeId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `inspection_item_typeid_foreign` (`typeId`),
  CONSTRAINT `inspection_item_typeid_foreign` FOREIGN KEY (`typeId`) REFERENCES `inspection_type` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_item`
--

LOCK TABLES `inspection_item` WRITE;
/*!40000 ALTER TABLE `inspection_item` DISABLE KEYS */;
INSERT INTO `inspection_item` VALUES (1,'Head Light','[\n                            {\n                                \"type\": \"radio-group\",\n                                \"required\": true,\n                                \"label\": \"Rating\",\n                                \"inline\": true,\n                                \"className\": \"\",\n                                \"name\": \"radio-group-1495719902602\",\n                                \"values\": [\n                                    {\n                                        \"label\": \"?\",\n                                        \"value\": \"1\",\n                                        \"selected\": true\n                                    },\n                                    {\n                                        \"label\": \"?\",\n                                        \"value\": \"2\"\n                                    },\n                                    {\n                                        \"label\": \"☹️\",\n                                        \"value\": \"3\"\n                                    }\n                                ]\n                            },\n                            {\n                                \"type\": \"text\",\n                                \"label\": \"Condition\",\n                                \"placeholder\": \"Condition\",\n                                \"className\": \"form-control\",\n                                \"name\": \"text-1495719902626\",\n                                \"subtype\": \"text\",\n                                \"maxlength\": \"100\"\n                            }\n                        ]',1,1),(2,'Tail Light','[\n                            {\n                                \"type\": \"radio-group\",\n                                \"required\": true,\n                                \"label\": \"Rating\",\n                                \"inline\": true,\n                                \"className\": \"\",\n                                \"name\": \"radio-group-1495720031404\",\n                                \"values\": [\n                                    {\n                                        \"label\": \"?\",\n                                        \"value\": \"1\",\n                                        \"selected\": true\n                                    },\n                                    {\n                                        \"label\": \"?\",\n                                        \"value\": \"2\"\n                                    },\n                                    {\n                                        \"label\": \"☹️\",\n                                        \"value\": \"3\"\n                                    }\n                                ]\n                            },\n                            {\n                                \"type\": \"text\",\n                                \"label\": \"Condition\",\n                                \"placeholder\": \"Condition\",\n                                \"className\": \"form-control\",\n                                \"name\": \"text-1495720031415\",\n                                \"subtype\": \"text\",\n                                \"maxlength\": \"100\"\n                            }\n                        ]',1,1),(3,'Front Left Tire','[\n                    {\n                        \"type\": \"radio-group\",\n                        \"required\": true,\n                        \"label\": \"Rating\",\n                        \"inline\": true,\n                        \"name\": \"radio-group-1502452774374\",\n                        \"values\": [\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"1\",\n                                \"selected\": true\n                            },\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"2\"\n                            },\n                            {\n                                \"label\": \"☹️\",\n                                \"value\": \"3\"\n                            }\n                        ]\n                    },\n                    {\n                        \"type\": \"textarea\",\n                        \"label\": \"Condition\",\n                        \"placeholder\": \"Condition\",\n                        \"className\": \"form-control\",\n                        \"name\": \"textarea-1502452774397\",\n                        \"subtype\": \"textarea\",\n                        \"maxlength\": \"100\"\n                    },\n                    {\n                        \"type\": \"text\",\n                        \"required\": true,\n                        \"label\": \"PSI\",\n                        \"className\": \"form-control\",\n                        \"name\": \"text-1502452795394\",\n                        \"subtype\": \"text\"\n                    }\n                ]',2,1),(4,'Front Right Tire','[\n                    {\n                        \"type\": \"radio-group\",\n                        \"required\": true,\n                        \"label\": \"Rating\",\n                        \"inline\": true,\n                        \"name\": \"radio-group-1502452815378\",\n                        \"values\": [\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"1\",\n                                \"selected\": true\n                            },\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"2\"\n                            },\n                            {\n                                \"label\": \"☹️\",\n                                \"value\": \"3\"\n                            }\n                        ]\n                    },\n                    {\n                        \"type\": \"textarea\",\n                        \"label\": \"Condition\",\n                        \"placeholder\": \"Condition\",\n                        \"className\": \"form-control\",\n                        \"name\": \"textarea-1502452815386\",\n                        \"subtype\": \"textarea\",\n                        \"maxlength\": \"100\"\n                    },\n                    {\n                        \"type\": \"text\",\n                        \"required\": true,\n                        \"label\": \"PSI\",\n                        \"className\": \"form-control\",\n                        \"name\": \"text-1502452818386\",\n                        \"subtype\": \"text\"\n                    }\n                ]',2,1),(5,'Rear Left Tire','[\n                    {\n                        \"type\": \"radio-group\",\n                        \"required\": true,\n                        \"label\": \"Rating\",\n                        \"inline\": true,\n                        \"name\": \"radio-group-1502452839609\",\n                        \"values\": [\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"1\",\n                                \"selected\": true\n                            },\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"2\"\n                            },\n                            {\n                                \"label\": \"☹️\",\n                                \"value\": \"3\"\n                            }\n                        ]\n                    },\n                    {\n                        \"type\": \"textarea\",\n                        \"label\": \"Condition\",\n                        \"placeholder\": \"Condition\",\n                        \"className\": \"form-control\",\n                        \"name\": \"textarea-1502452839621\",\n                        \"subtype\": \"textarea\",\n                        \"maxlength\": \"100\"\n                    },\n                    {\n                        \"type\": \"text\",\n                        \"required\": true,\n                        \"label\": \"PSI\",\n                        \"className\": \"form-control\",\n                        \"name\": \"text-1502452842526\",\n                        \"subtype\": \"text\"\n                    }\n                ]',2,1),(6,'Rear Right Tire','[\n                    {\n                        \"type\": \"radio-group\",\n                        \"required\": true,\n                        \"label\": \"Rating\",\n                        \"inline\": true,\n                        \"name\": \"radio-group-1502452861959\",\n                        \"values\": [\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"1\",\n                                \"selected\": true\n                            },\n                            {\n                                \"label\": \"?\",\n                                \"value\": \"2\"\n                            },\n                            {\n                                \"label\": \"☹️\",\n                                \"value\": \"3\"\n                            }\n                        ]\n                    },\n                    {\n                        \"type\": \"textarea\",\n                        \"label\": \"Condition\",\n                        \"placeholder\": \"Condition\",\n                        \"className\": \"form-control\",\n                        \"name\": \"textarea-1502452861966\",\n                        \"subtype\": \"textarea\",\n                        \"maxlength\": \"100\"\n                    },\n                    {\n                        \"type\": \"text\",\n                        \"required\": true,\n                        \"label\": \"PSI\",\n                        \"className\": \"form-control\",\n                        \"name\": \"text-1502452865914\",\n                        \"subtype\": \"text\"\n                    }\n                ]',2,1);
/*!40000 ALTER TABLE `inspection_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspection_technician`
--

DROP TABLE IF EXISTS `inspection_technician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inspection_technician` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inspectionId` int(10) unsigned NOT NULL,
  `technicianId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_technician_inspectionid_foreign` (`inspectionId`),
  KEY `inspection_technician_technicianid_foreign` (`technicianId`),
  CONSTRAINT `inspection_technician_inspectionid_foreign` FOREIGN KEY (`inspectionId`) REFERENCES `inspection_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_technician_technicianid_foreign` FOREIGN KEY (`technicianId`) REFERENCES `technician` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_technician`
--

LOCK TABLES `inspection_technician` WRITE;
/*!40000 ALTER TABLE `inspection_technician` DISABLE KEYS */;
INSERT INTO `inspection_technician` VALUES (1,1,5,1,'2017-10-17 18:30:02','2017-10-17 18:30:02');
/*!40000 ALTER TABLE `inspection_technician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspection_type`
--

DROP TABLE IF EXISTS `inspection_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inspection_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_type`
--

LOCK TABLES `inspection_type` WRITE;
/*!40000 ALTER TABLE `inspection_type` DISABLE KEYS */;
INSERT INTO `inspection_type` VALUES (1,'Lights',1),(2,'Tires',1);
/*!40000 ALTER TABLE `inspection_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_productid_foreign` (`productId`),
  CONSTRAINT `inventory_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,1,35,'2017-10-17 17:58:49','2017-10-17 18:45:51'),(2,2,22,'2017-10-17 17:58:49','2017-10-17 18:25:49'),(3,3,44,'2017-10-17 17:58:49','2017-10-17 18:38:32'),(4,4,28,'2017-10-17 18:02:39','2017-10-17 18:41:01');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_discount`
--

DROP TABLE IF EXISTS `job_discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `discountId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_discount_jobid_foreign` (`jobId`),
  KEY `job_discount_discountid_foreign` (`discountId`),
  CONSTRAINT `job_discount_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_discount_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_discount`
--

LOCK TABLES `job_discount` WRITE;
/*!40000 ALTER TABLE `job_discount` DISABLE KEYS */;
INSERT INTO `job_discount` VALUES (1,1,2,1,NULL,NULL),(2,6,2,1,NULL,NULL);
/*!40000 ALTER TABLE `job_discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_header`
--

DROP TABLE IF EXISTS `job_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerId` int(10) unsigned NOT NULL,
  `vehicleId` int(10) unsigned NOT NULL,
  `rackId` int(10) unsigned NOT NULL,
  `isFinalize` tinyint(1) NOT NULL DEFAULT '0',
  `isComplete` tinyint(1) NOT NULL DEFAULT '0',
  `isVoid` tinyint(1) NOT NULL DEFAULT '0',
  `total` double(15,2) NOT NULL,
  `paid` double(15,2) NOT NULL,
  `start` timestamp NOT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `release` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_header_customerid_foreign` (`customerId`),
  KEY `job_header_vehicleid_foreign` (`vehicleId`),
  KEY `job_header_rackid_foreign` (`rackId`),
  CONSTRAINT `job_header_customerid_foreign` FOREIGN KEY (`customerId`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_header_rackid_foreign` FOREIGN KEY (`rackId`) REFERENCES `rack` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_header_vehicleid_foreign` FOREIGN KEY (`vehicleId`) REFERENCES `vehicle` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_header`
--

LOCK TABLES `job_header` WRITE;
/*!40000 ALTER TABLE `job_header` DISABLE KEYS */;
INSERT INTO `job_header` VALUES (1,1,1,1,1,1,0,5551.06,5551.06,'2017-10-17 17:58:57','2017-10-17 17:58:57','2017-10-17 17:58:57',NULL,'2017-10-17 17:58:57','2017-10-17 17:58:57'),(2,2,1,1,1,1,0,1970.48,1970.48,'2017-10-17 17:58:57','2017-10-17 17:58:57','2017-10-17 17:58:57',NULL,'2017-10-17 17:58:57','2017-10-17 17:58:57'),(3,4,3,1,1,0,0,3850.25,0.00,'2017-10-17 18:34:06',NULL,NULL,'','2017-10-17 18:34:06','2017-10-17 18:37:19'),(4,5,4,2,0,0,0,940.70,0.00,'2017-10-17 18:36:18',NULL,NULL,'','2017-10-17 18:36:18','2017-10-17 18:36:18'),(5,1,1,3,1,1,0,750.25,750.25,'2017-10-17 18:38:08','2017-10-17 18:38:33','2017-10-17 18:38:50','','2017-10-17 18:38:08','2017-10-17 18:38:50'),(6,6,5,3,1,1,0,1714.29,1714.29,'2017-10-17 18:40:16','2017-10-17 18:41:02','2017-10-17 18:41:12','','2017-10-17 18:40:16','2017-10-17 18:41:12'),(7,6,5,3,1,0,1,0.00,0.00,'2017-10-17 18:44:47',NULL,NULL,'On warranty','2017-10-17 18:44:47','2017-10-17 18:44:47');
/*!40000 ALTER TABLE `job_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_package`
--

DROP TABLE IF EXISTS `job_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `packageId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `completed` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isComplete` tinyint(1) NOT NULL DEFAULT '0',
  `isVoid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_package_jobid_foreign` (`jobId`),
  KEY `job_package_packageid_foreign` (`packageId`),
  CONSTRAINT `job_package_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_package_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_package`
--

LOCK TABLES `job_package` WRITE;
/*!40000 ALTER TABLE `job_package` DISABLE KEYS */;
INSERT INTO `job_package` VALUES (1,1,1,5,5,1,1,0,'2017-10-17 17:58:59','2017-10-17 17:58:59'),(2,2,2,1,1,1,1,0,'2017-10-17 17:58:59','2017-10-17 17:58:59'),(3,3,2,1,0,1,0,0,'2017-10-17 18:34:06','2017-10-17 18:34:06');
/*!40000 ALTER TABLE `job_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_payment`
--

DROP TABLE IF EXISTS `job_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `paid` double(15,2) NOT NULL,
  `creditName` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creditNumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creditExpiry` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creditCode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isCredit` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_payment_jobid_foreign` (`jobId`),
  CONSTRAINT `job_payment_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_payment`
--

LOCK TABLES `job_payment` WRITE;
/*!40000 ALTER TABLE `job_payment` DISABLE KEYS */;
INSERT INTO `job_payment` VALUES (1,1,5551.06,'','','','',0,'2017-10-17 17:59:00','2017-10-17 17:59:00'),(2,2,1970.48,'','','','',0,'2017-10-17 17:59:00','2017-10-17 17:59:00'),(3,5,750.25,'','','','$2y$10$1AYKPKcQb73XgBVnzGL7SuA4D0EDv0zEdPzuc/rHvnIbqYJ0rCMFm',0,'2017-10-17 18:38:29','2017-10-17 18:38:29'),(4,6,1714.29,'','','','$2y$10$Sq0790j.2ZddLMGGOvACR.1pQyJNRUNOX0XTsgG9FOPowiFWl4z8W',1,'2017-10-17 18:40:59','2017-10-17 18:40:59');
/*!40000 ALTER TABLE `job_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_product`
--

DROP TABLE IF EXISTS `job_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `completed` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isComplete` tinyint(1) NOT NULL DEFAULT '0',
  `isVoid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_product_jobid_foreign` (`jobId`),
  KEY `job_product_productid_foreign` (`productId`),
  CONSTRAINT `job_product_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_product`
--

LOCK TABLES `job_product` WRITE;
/*!40000 ALTER TABLE `job_product` DISABLE KEYS */;
INSERT INTO `job_product` VALUES (1,1,1,5,5,1,1,0,'2017-10-17 17:58:57','2017-10-17 17:58:57'),(2,2,1,1,1,1,1,0,'2017-10-17 17:58:58','2017-10-17 17:58:58'),(3,3,3,1,0,1,0,0,'2017-10-17 18:34:06','2017-10-17 18:34:06'),(4,4,2,2,0,1,0,0,'2017-10-17 18:36:18','2017-10-17 18:36:18'),(5,5,3,1,1,1,1,0,'2017-10-17 18:38:08','2017-10-17 18:38:32'),(6,7,4,2,0,1,0,1,'2017-10-17 18:44:47','2017-10-17 18:44:47');
/*!40000 ALTER TABLE `job_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_promo`
--

DROP TABLE IF EXISTS `job_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `promoId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `completed` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isComplete` tinyint(1) NOT NULL DEFAULT '0',
  `isVoid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_promo_jobid_foreign` (`jobId`),
  KEY `job_promo_promoid_foreign` (`promoId`),
  CONSTRAINT `job_promo_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_promo_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_promo`
--

LOCK TABLES `job_promo` WRITE;
/*!40000 ALTER TABLE `job_promo` DISABLE KEYS */;
INSERT INTO `job_promo` VALUES (1,3,2,1,0,1,0,0,'2017-10-17 18:34:06','2017-10-17 18:34:06'),(2,6,2,1,1,1,1,0,'2017-10-17 18:40:16','2017-10-17 18:41:01');
/*!40000 ALTER TABLE `job_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_refund`
--

DROP TABLE IF EXISTS `job_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `refund` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_refund_jobid_foreign` (`jobId`),
  CONSTRAINT `job_refund_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_refund`
--

LOCK TABLES `job_refund` WRITE;
/*!40000 ALTER TABLE `job_refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_refund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_service`
--

DROP TABLE IF EXISTS `job_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isComplete` tinyint(1) NOT NULL DEFAULT '0',
  `isVoid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_service_jobid_foreign` (`jobId`),
  KEY `job_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `job_service_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_service`
--

LOCK TABLES `job_service` WRITE;
/*!40000 ALTER TABLE `job_service` DISABLE KEYS */;
INSERT INTO `job_service` VALUES (1,1,1,1,1,0,'2017-10-17 17:58:58','2017-10-17 17:58:58'),(2,2,1,1,1,0,'2017-10-17 17:58:58','2017-10-17 17:58:58'),(3,4,6,1,0,0,'2017-10-17 18:36:18','2017-10-17 18:36:18');
/*!40000 ALTER TABLE `job_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_technician`
--

DROP TABLE IF EXISTS `job_technician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_technician` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `technicianId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_technician_jobid_foreign` (`jobId`),
  KEY `job_technician_technicianid_foreign` (`technicianId`),
  CONSTRAINT `job_technician_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `job_technician_technicianid_foreign` FOREIGN KEY (`technicianId`) REFERENCES `technician` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_technician`
--

LOCK TABLES `job_technician` WRITE;
/*!40000 ALTER TABLE `job_technician` DISABLE KEYS */;
INSERT INTO `job_technician` VALUES (1,1,1,1,'2017-10-17 17:58:59','2017-10-17 17:58:59'),(2,1,2,1,'2017-10-17 17:59:00','2017-10-17 17:59:00'),(3,3,7,1,'2017-10-17 18:34:06','2017-10-17 18:34:06'),(4,3,1,1,'2017-10-17 18:34:06','2017-10-17 18:34:06'),(5,4,4,1,'2017-10-17 18:36:18','2017-10-17 18:36:18'),(6,5,1,1,'2017-10-17 18:38:08','2017-10-17 18:38:08'),(7,6,2,1,'2017-10-17 18:40:16','2017-10-17 18:40:16');
/*!40000 ALTER TABLE `job_technician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (171,'2014_10_12_000000_create_users_table',1),(172,'2014_10_12_100000_create_password_resets_table',1),(173,'2017_04_05_150000_create_utilities_table',1),(174,'2017_04_05_151000_create_vehicle_make_table',1),(175,'2017_04_05_151001_create_vehicle_model_table',1),(176,'2017_04_05_151240_create_supplier_table',1),(177,'2017_04_05_152048_create_supplier_person_table',1),(178,'2017_04_05_153214_create_supplier_contact_table',1),(179,'2017_04_09_145017_create_product_type_table',1),(180,'2017_04_09_145812_create_product_brand_table',1),(181,'2017_04_09_150603_create_type_brand_table',1),(182,'2017_04_11_074625_create_product_unit_table',1),(183,'2017_04_11_074800_create_product_variance_table',1),(184,'2017_04_11_074840_create_product_table',1),(185,'2017_04_11_074848_create_type_variance_table',1),(186,'2017_04_14_094855_create_product_price_table',1),(187,'2017_04_14_095145_create_inventory_table',1),(188,'2017_04_14_095200_create_product_vehicle_table',1),(189,'2017_04_14_145250_create_service_category_table',1),(190,'2017_04_14_145316_create_service_table',1),(191,'2017_04_14_145338_create_service_price_table',1),(192,'2017_04_25_124721_create_rack_table',1),(193,'2017_04_25_142722_create_inspection_type_table',1),(194,'2017_04_25_142950_create_inspection_item_table',1),(195,'2017_04_26_142503_create_package_table',1),(196,'2017_04_26_142545_create_package_product_table',1),(197,'2017_04_26_142802_create_package_service_table',1),(198,'2017_04_26_142810_create_package_price_table',1),(199,'2017_05_11_010452_create_promo_table',1),(200,'2017_05_11_010524_create_promo_product_table',1),(201,'2017_05_11_010532_create_promo_service_table',1),(202,'2017_05_11_010627_create_promo_price_table',1),(203,'2017_05_15_151727_create_discount_table',1),(204,'2017_05_15_151728_create_discount_rate_table',1),(205,'2017_05_15_151829_create_discount_product_table',1),(206,'2017_05_15_151840_create_discount_service_table',1),(207,'2017_05_16_030919_create_technician_table',1),(208,'2017_05_16_030920_create_technician_skill_table',1),(209,'2017_05_19_054841_create_purchase_header_table',1),(210,'2017_05_19_054941_create_purchase_detail_table',1),(211,'2017_05_23_203535_create_delivery_header_table',1),(212,'2017_05_23_204434_create_delivery_detail_table',1),(213,'2017_05_23_205225_create_delivery_order_table',1),(214,'2017_05_23_205226_create_return_header_table',1),(215,'2017_05_23_205227_create_return_detail_table',1),(216,'2017_05_23_205228_create_return_delivery_table',1),(217,'2017_05_24_125755_create_customer_table',1),(218,'2017_05_24_125756_create_vehicle_table',1),(219,'2017_05_24_132722_create_inspection_header_table',1),(220,'2017_05_24_132729_create_inspection_detail_table',1),(221,'2017_05_24_132738_create_inspection_technician_table',1),(222,'2017_05_31_101906_create_job_header_table',1),(223,'2017_05_31_110048_create_job_product_table',1),(224,'2017_05_31_110056_create_job_service_table',1),(225,'2017_05_31_110105_create_job_package_table',1),(226,'2017_05_31_110111_create_job_promo_table',1),(227,'2017_05_31_110118_create_job_discount_table',1),(228,'2017_05_31_135642_create_job_payment_table',1),(229,'2017_05_31_135643_create_job_refund_table',1),(230,'2017_05_31_135644_create_job_technician_table',1),(231,'2017_05_31_173746_create_estimate_header_table',1),(232,'2017_05_31_173747_create_estimate_product_table',1),(233,'2017_05_31_173748_create_estimate_service_table',1),(234,'2017_05_31_173749_create_estimate_package_table',1),(235,'2017_05_31_173750_create_estimate_promo_table',1),(236,'2017_05_31_173751_create_estimate_discount_table',1),(237,'2017_05_31_173752_create_estimate_technician_table',1),(238,'2017_08_21_003818_create_sales_header_table',1),(239,'2017_08_21_004213_create_sales_product_table',1),(240,'2017_08_21_004531_create_sales_package_table',1),(241,'2017_08_21_004538_create_sales_promo_table',1),(242,'2017_08_21_004549_create_sales_discount_table',1),(243,'2017_09_20_183454_create_warranty_sales_header',1),(244,'2017_10_01_234722_create_warranty_sales_product_table',1),(245,'2017_10_01_235440_create_warranty_sales_package_table',1),(246,'2017_10_01_235550_create_warranty_sales_promo_table',1),(247,'2017_10_03_181425_create_warranty_job_header_table',1),(248,'2017_10_03_181613_create_warranty_job_product_table',1),(249,'2017_10_03_181621_create_warranty_job_service_table',1),(250,'2017_10_03_181627_create_warranty_job_package_product_table',1),(251,'2017_10_03_181628_create_warranty_job_package_service_table',1),(252,'2017_10_03_181634_create_warranty_job_promo_product_table',1),(253,'2017_10_03_184419_create_warranty_job_promo_service_table',1),(254,'2017_10_10_220710_create_damage_product_table',1),(255,'2017_10_15_142727_create_audit_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(15,2) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package`
--

LOCK TABLES `package` WRITE;
/*!40000 ALTER TABLE `package` DISABLE KEYS */;
INSERT INTO `package` VALUES (1,'Summer Package',500.00,1),(2,'Change Oil Package',700.00,1),(3,'Dunlop Tire Promo',2400.00,1);
/*!40000 ALTER TABLE `package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_price`
--

DROP TABLE IF EXISTS `package_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_price` (
  `packageId` int(10) unsigned NOT NULL,
  `price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `package_price_packageid_foreign` (`packageId`),
  CONSTRAINT `package_price_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_price`
--

LOCK TABLES `package_price` WRITE;
/*!40000 ALTER TABLE `package_price` DISABLE KEYS */;
INSERT INTO `package_price` VALUES (1,500.00,'2017-10-17 17:58:54','2017-10-17 17:58:54'),(2,700.00,'2017-10-17 17:58:54','2017-10-17 17:58:54'),(3,2400.00,'2017-10-17 18:04:21','2017-10-17 18:04:21');
/*!40000 ALTER TABLE `package_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_product`
--

DROP TABLE IF EXISTS `package_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packageId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `package_product_packageid_foreign` (`packageId`),
  KEY `package_product_productid_foreign` (`productId`),
  CONSTRAINT `package_product_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `package_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_product`
--

LOCK TABLES `package_product` WRITE;
/*!40000 ALTER TABLE `package_product` DISABLE KEYS */;
INSERT INTO `package_product` VALUES (1,1,2,2,1),(2,2,2,1,1),(3,3,4,4,1);
/*!40000 ALTER TABLE `package_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_service`
--

DROP TABLE IF EXISTS `package_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packageId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `package_service_packageid_foreign` (`packageId`),
  KEY `package_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `package_service_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `package_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_service`
--

LOCK TABLES `package_service` WRITE;
/*!40000 ALTER TABLE `package_service` DISABLE KEYS */;
INSERT INTO `package_service` VALUES (1,2,1,1);
/*!40000 ALTER TABLE `package_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(15,2) NOT NULL,
  `reorder` int(11) NOT NULL,
  `typeId` int(10) unsigned NOT NULL,
  `brandId` int(10) unsigned NOT NULL,
  `varianceId` int(10) unsigned NOT NULL,
  `isOriginal` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isWarranty` tinyint(1) NOT NULL DEFAULT '1',
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_typeid_foreign` (`typeId`),
  KEY `product_brandid_foreign` (`brandId`),
  KEY `product_varianceid_foreign` (`varianceId`),
  CONSTRAINT `product_brandid_foreign` FOREIGN KEY (`brandId`) REFERENCES `product_brand` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `product_typeid_foreign` FOREIGN KEY (`typeId`) REFERENCES `product_type` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `product_varianceid_foreign` FOREIGN KEY (`varianceId`) REFERENCES `product_variance` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'FB-6PK1110','',1000.25,10,3,3,2,'type1',1,1,0,0,1),(2,'Ultron','',300.25,10,1,1,1,NULL,1,1,0,0,1),(3,'Motolite 4500','',750.25,10,4,5,3,'type1',1,1,0,0,1),(4,'Dunlop P215/65 R15','',633.25,16,5,6,4,'type1',1,0,0,0,1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_brand`
--

DROP TABLE IF EXISTS `product_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_brand`
--

LOCK TABLES `product_brand` WRITE;
/*!40000 ALTER TABLE `product_brand` DISABLE KEYS */;
INSERT INTO `product_brand` VALUES (1,'Petron',1),(2,'Shell',1),(3,'Bando',1),(4,'Gates',1),(5,'Motolite',1),(6,'Dunlop',1),(7,'Bosch',1),(8,'Outlast',1);
/*!40000 ALTER TABLE `product_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_price`
--

DROP TABLE IF EXISTS `product_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_price` (
  `productId` int(10) unsigned NOT NULL,
  `price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `product_price_productid_foreign` (`productId`),
  CONSTRAINT `product_price_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_price`
--

LOCK TABLES `product_price` WRITE;
/*!40000 ALTER TABLE `product_price` DISABLE KEYS */;
INSERT INTO `product_price` VALUES (1,1000.25,'2017-10-17 17:58:48','2017-10-17 17:58:48'),(2,300.25,'2017-10-17 17:58:48','2017-10-17 17:58:48'),(3,750.25,'2017-10-17 17:58:48','2017-10-17 17:58:48'),(4,633.25,'2017-10-17 18:02:39','2017-10-17 18:02:39');
/*!40000 ALTER TABLE `product_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_type`
--

LOCK TABLES `product_type` WRITE;
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
INSERT INTO `product_type` VALUES (1,'Oil','category2',1),(2,'Fuel','category2',1),(3,'Fan Belt','category1',1),(4,'Batteries/Electrical','category1',1),(5,'Tires','category1',1),(6,'Brakes','category1',1);
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_unit`
--

DROP TABLE IF EXISTS `product_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` tinyint(4) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_unit`
--

LOCK TABLES `product_unit` WRITE;
/*!40000 ALTER TABLE `product_unit` DISABLE KEYS */;
INSERT INTO `product_unit` VALUES (1,'mm','Millimeter',1,1),(2,'cm','Centimeters',1,1),(3,'m','Meters',1,1),(4,'mL','Milliliter',3,1),(5,'l','Liter',3,1),(6,'ribs','Ribs',1,1),(7,'kg','Kilogram',2,1),(8,'watts','Watts',3,1);
/*!40000 ALTER TABLE `product_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variance`
--

DROP TABLE IF EXISTS `product_variance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `units` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variance`
--

LOCK TABLES `product_variance` WRITE;
/*!40000 ALTER TABLE `product_variance` DISABLE KEYS */;
INSERT INTO `product_variance` VALUES (1,'500 mL','500','4',1),(2,'6PK1110','1110,6','1,6',1),(3,'4500 watts','4500','8',1),(4,'P215/65 R15','215,65','2,2',1);
/*!40000 ALTER TABLE `product_variance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_vehicle`
--

DROP TABLE IF EXISTS `product_vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_vehicle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productId` int(10) unsigned NOT NULL,
  `modelId` int(10) unsigned NOT NULL,
  `isManual` tinyint(1) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_vehicle_productid_foreign` (`productId`),
  KEY `product_vehicle_modelid_foreign` (`modelId`),
  CONSTRAINT `product_vehicle_modelid_foreign` FOREIGN KEY (`modelId`) REFERENCES `vehicle_model` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `product_vehicle_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_vehicle`
--

LOCK TABLES `product_vehicle` WRITE;
/*!40000 ALTER TABLE `product_vehicle` DISABLE KEYS */;
INSERT INTO `product_vehicle` VALUES (1,1,1,0,1),(2,1,2,0,1),(3,1,3,0,1);
/*!40000 ALTER TABLE `product_vehicle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo`
--

DROP TABLE IF EXISTS `promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(15,2) NOT NULL,
  `dateStart` date DEFAULT NULL,
  `dateEnd` date DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo`
--

LOCK TABLES `promo` WRITE;
/*!40000 ALTER TABLE `promo` DISABLE KEYS */;
INSERT INTO `promo` VALUES (1,'Summer Promo',800.00,'2017-10-18','2017-10-18',10,1),(2,'Dunlop Tire Promo',2400.00,'2017-10-18','2017-10-31',28,1);
/*!40000 ALTER TABLE `promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_price`
--

DROP TABLE IF EXISTS `promo_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_price` (
  `promoId` int(10) unsigned NOT NULL,
  `price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `promo_price_promoid_foreign` (`promoId`),
  CONSTRAINT `promo_price_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_price`
--

LOCK TABLES `promo_price` WRITE;
/*!40000 ALTER TABLE `promo_price` DISABLE KEYS */;
INSERT INTO `promo_price` VALUES (1,800.00,'2017-10-17 17:58:55','2017-10-17 17:58:55'),(2,2400.00,'2017-10-17 18:05:39','2017-10-17 18:05:39');
/*!40000 ALTER TABLE `promo_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_product`
--

DROP TABLE IF EXISTS `promo_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `promoId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `freeQuantity` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `promo_product_promoid_foreign` (`promoId`),
  KEY `promo_product_productid_foreign` (`productId`),
  CONSTRAINT `promo_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `promo_product_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_product`
--

LOCK TABLES `promo_product` WRITE;
/*!40000 ALTER TABLE `promo_product` DISABLE KEYS */;
INSERT INTO `promo_product` VALUES (1,1,2,1,1,1),(2,2,4,2,2,1);
/*!40000 ALTER TABLE `promo_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_service`
--

DROP TABLE IF EXISTS `promo_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `promoId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `isFree` tinyint(1) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `promo_service_promoid_foreign` (`promoId`),
  KEY `promo_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `promo_service_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `promo_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_service`
--

LOCK TABLES `promo_service` WRITE;
/*!40000 ALTER TABLE `promo_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `promo_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_detail`
--

DROP TABLE IF EXISTS `purchase_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchaseId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `modelId` int(10) unsigned DEFAULT NULL,
  `isManual` tinyint(1) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `delivered` int(11) NOT NULL DEFAULT '0',
  `price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `purchase_detail_purchaseid_foreign` (`purchaseId`),
  KEY `purchase_detail_productid_foreign` (`productId`),
  KEY `purchase_detail_modelid_foreign` (`modelId`),
  CONSTRAINT `purchase_detail_modelid_foreign` FOREIGN KEY (`modelId`) REFERENCES `vehicle_model` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `purchase_detail_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `purchase_detail_purchaseid_foreign` FOREIGN KEY (`purchaseId`) REFERENCES `purchase_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_detail`
--

LOCK TABLES `purchase_detail` WRITE;
/*!40000 ALTER TABLE `purchase_detail` DISABLE KEYS */;
INSERT INTO `purchase_detail` VALUES (1,'ORDER00001',1,2,1,10,10,900.00,'2017-10-17 17:58:56','2017-10-17 17:58:56',1),(2,'ORDER00001',2,NULL,NULL,20,10,100.00,'2017-10-17 17:58:56','2017-10-17 17:58:56',1),(3,'ORDER00002',2,NULL,NULL,15,0,100.00,'2017-10-17 18:06:27','2017-10-17 18:06:27',1),(4,'ORDER00003',3,NULL,NULL,50,45,300.00,'2017-10-17 18:11:06','2017-10-17 18:14:21',1),(5,'ORDER00003',1,NULL,NULL,20,15,800.00,'2017-10-17 18:11:06','2017-10-17 18:11:52',1),(6,'ORDER00003',2,NULL,NULL,15,13,100.00,'2017-10-17 18:11:06','2017-10-17 18:11:52',1),(7,'ORDER00003',4,NULL,NULL,36,36,400.00,'2017-10-17 18:11:06','2017-10-17 18:11:52',1);
/*!40000 ALTER TABLE `purchase_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_header`
--

DROP TABLE IF EXISTS `purchase_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_header` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplierId` int(10) unsigned NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateMake` date NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isFinalize` tinyint(1) NOT NULL,
  `isDelivered` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_header_supplierid_foreign` (`supplierId`),
  CONSTRAINT `purchase_header_supplierid_foreign` FOREIGN KEY (`supplierId`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_header`
--

LOCK TABLES `purchase_header` WRITE;
/*!40000 ALTER TABLE `purchase_header` DISABLE KEYS */;
INSERT INTO `purchase_header` VALUES ('ORDER00001',1,'','2017-08-06',1,1,1,'2017-10-17 17:58:55','2017-10-17 17:58:55'),('ORDER00002',2,'','2017-10-18',1,0,0,'2017-10-17 18:06:27','2017-10-17 18:06:27'),('ORDER00003',1,'','2017-10-18',1,1,0,'2017-10-17 18:11:06','2017-10-17 18:14:21');
/*!40000 ALTER TABLE `purchase_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rack`
--

DROP TABLE IF EXISTS `rack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rack`
--

LOCK TABLES `rack` WRITE;
/*!40000 ALTER TABLE `rack` DISABLE KEYS */;
INSERT INTO `rack` VALUES (1,'Rack 1','',1),(2,'Rack 2','',1),(3,'Rack 3','',1);
/*!40000 ALTER TABLE `rack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_delivery`
--

DROP TABLE IF EXISTS `return_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_delivery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `returnId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deliveryId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_delivery_returnid_foreign` (`returnId`),
  KEY `return_delivery_deliveryid_foreign` (`deliveryId`),
  CONSTRAINT `return_delivery_deliveryid_foreign` FOREIGN KEY (`deliveryId`) REFERENCES `delivery_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `return_delivery_returnid_foreign` FOREIGN KEY (`returnId`) REFERENCES `return_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_delivery`
--

LOCK TABLES `return_delivery` WRITE;
/*!40000 ALTER TABLE `return_delivery` DISABLE KEYS */;
INSERT INTO `return_delivery` VALUES (1,'RETURN00001','DELIVERY00002','2017-10-17 18:14:21','2017-10-17 18:14:21');
/*!40000 ALTER TABLE `return_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_detail`
--

DROP TABLE IF EXISTS `return_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `returnId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `deliveryId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_detail_returnid_foreign` (`returnId`),
  KEY `return_detail_productid_foreign` (`productId`),
  KEY `return_detail_deliveryid_foreign` (`deliveryId`),
  CONSTRAINT `return_detail_deliveryid_foreign` FOREIGN KEY (`deliveryId`) REFERENCES `delivery_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `return_detail_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `return_detail_returnid_foreign` FOREIGN KEY (`returnId`) REFERENCES `return_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_detail`
--

LOCK TABLES `return_detail` WRITE;
/*!40000 ALTER TABLE `return_detail` DISABLE KEYS */;
INSERT INTO `return_detail` VALUES (1,'RETURN00001',3,'DELIVERY00002',5,1,'2017-10-17 18:14:21','2017-10-17 18:14:21');
/*!40000 ALTER TABLE `return_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_header`
--

DROP TABLE IF EXISTS `return_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_header` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplierId` int(10) unsigned NOT NULL,
  `dateMake` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_header_supplierid_foreign` (`supplierId`),
  CONSTRAINT `return_header_supplierid_foreign` FOREIGN KEY (`supplierId`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_header`
--

LOCK TABLES `return_header` WRITE;
/*!40000 ALTER TABLE `return_header` DISABLE KEYS */;
INSERT INTO `return_header` VALUES ('RETURN00001',1,'2017-10-18',NULL,1,'2017-10-17 18:14:21','2017-10-17 18:14:21');
/*!40000 ALTER TABLE `return_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_discount`
--

DROP TABLE IF EXISTS `sales_discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salesId` int(10) unsigned NOT NULL,
  `discountId` int(10) unsigned NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_discount_salesid_foreign` (`salesId`),
  KEY `sales_discount_discountid_foreign` (`discountId`),
  CONSTRAINT `sales_discount_discountid_foreign` FOREIGN KEY (`discountId`) REFERENCES `discount` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_discount_salesid_foreign` FOREIGN KEY (`salesId`) REFERENCES `sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_discount`
--

LOCK TABLES `sales_discount` WRITE;
/*!40000 ALTER TABLE `sales_discount` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_header`
--

DROP TABLE IF EXISTS `sales_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerId` int(10) unsigned NOT NULL,
  `total` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_header_customerid_foreign` (`customerId`),
  CONSTRAINT `sales_header_customerid_foreign` FOREIGN KEY (`customerId`) REFERENCES `customer` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_header`
--

LOCK TABLES `sales_header` WRITE;
/*!40000 ALTER TABLE `sales_header` DISABLE KEYS */;
INSERT INTO `sales_header` VALUES (1,2,2702.25,'2017-10-17 17:59:00','2017-10-17 17:59:00'),(2,3,3751.13,'2017-10-17 18:25:49','2017-10-17 18:25:49');
/*!40000 ALTER TABLE `sales_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_package`
--

DROP TABLE IF EXISTS `sales_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salesId` int(10) unsigned NOT NULL,
  `packageId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_package_salesid_foreign` (`salesId`),
  KEY `sales_package_packageid_foreign` (`packageId`),
  CONSTRAINT `sales_package_packageid_foreign` FOREIGN KEY (`packageId`) REFERENCES `package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_package_salesid_foreign` FOREIGN KEY (`salesId`) REFERENCES `sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_package`
--

LOCK TABLES `sales_package` WRITE;
/*!40000 ALTER TABLE `sales_package` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_product`
--

DROP TABLE IF EXISTS `sales_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salesId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_product_salesid_foreign` (`salesId`),
  KEY `sales_product_productid_foreign` (`productId`),
  CONSTRAINT `sales_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_product_salesid_foreign` FOREIGN KEY (`salesId`) REFERENCES `sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_product`
--

LOCK TABLES `sales_product` WRITE;
/*!40000 ALTER TABLE `sales_product` DISABLE KEYS */;
INSERT INTO `sales_product` VALUES (1,1,2,5,1,'2017-10-17 17:59:01','2017-10-17 17:59:01'),(2,2,2,5,1,'2017-10-17 18:25:49','2017-10-17 18:25:49');
/*!40000 ALTER TABLE `sales_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_promo`
--

DROP TABLE IF EXISTS `sales_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salesId` int(10) unsigned NOT NULL,
  `promoId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_promo_salesid_foreign` (`salesId`),
  KEY `sales_promo_promoid_foreign` (`promoId`),
  CONSTRAINT `sales_promo_promoid_foreign` FOREIGN KEY (`promoId`) REFERENCES `promo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_promo_salesid_foreign` FOREIGN KEY (`salesId`) REFERENCES `sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_promo`
--

LOCK TABLES `sales_promo` WRITE;
/*!40000 ALTER TABLE `sales_promo` DISABLE KEYS */;
INSERT INTO `sales_promo` VALUES (1,2,2,1,1,'2017-10-17 18:25:49','2017-10-17 18:25:49');
/*!40000 ALTER TABLE `sales_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(15,2) NOT NULL,
  `size` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_name_size_unique` (`name`,`size`),
  KEY `service_categoryid_foreign` (`categoryId`),
  CONSTRAINT `service_categoryid_foreign` FOREIGN KEY (`categoryId`) REFERENCES `service_category` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'Change Oil',300.25,'Sedan',1,1,0,0,1),(2,'Change Oil',500.00,'Large',1,1,0,0,1),(3,'Adjust Timing',600.00,'Large',1,1,0,0,1),(4,'Replace Bell Crank',400.50,'Sedan',2,1,0,0,1),(5,'Replace Shock Absorber Bushing',600.25,'Sedan',2,1,0,0,1),(6,'Replace Compressor',400.25,'Sedan',3,1,0,0,1),(7,'Replace Compressor',525.25,'Large',3,1,0,0,1);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_category`
--

DROP TABLE IF EXISTS `service_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_category`
--

LOCK TABLES `service_category` WRITE;
/*!40000 ALTER TABLE `service_category` DISABLE KEYS */;
INSERT INTO `service_category` VALUES (1,'Maintenance','',1),(2,'Suspension','',1),(3,'Aircon','',1);
/*!40000 ALTER TABLE `service_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_price`
--

DROP TABLE IF EXISTS `service_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_price` (
  `serviceId` int(10) unsigned NOT NULL,
  `price` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `service_price_serviceid_foreign` (`serviceId`),
  CONSTRAINT `service_price_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_price`
--

LOCK TABLES `service_price` WRITE;
/*!40000 ALTER TABLE `service_price` DISABLE KEYS */;
INSERT INTO `service_price` VALUES (1,300.25,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(2,500.00,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(3,600.00,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(4,400.50,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(5,600.25,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(6,400.25,'2017-10-17 17:58:50','2017-10-17 17:58:50'),(7,525.25,'2017-10-17 17:58:50','2017-10-17 17:58:50');
/*!40000 ALTER TABLE `service_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` VALUES (1,'Kwikparts','','','San Juan City',1),(2,'Petron Inc.','','','Batangas City',1);
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_contact`
--

DROP TABLE IF EXISTS `supplier_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_contact` (
  `scId` int(10) unsigned NOT NULL,
  `scNo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `supplier_contact_scid_foreign` (`scId`),
  CONSTRAINT `supplier_contact_scid_foreign` FOREIGN KEY (`scId`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_contact`
--

LOCK TABLES `supplier_contact` WRITE;
/*!40000 ALTER TABLE `supplier_contact` DISABLE KEYS */;
INSERT INTO `supplier_contact` VALUES (1,'+63 905 4090 523'),(2,'+63 905 4090 523'),(2,'(02) 999 9998');
/*!40000 ALTER TABLE `supplier_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_person`
--

DROP TABLE IF EXISTS `supplier_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_person` (
  `spId` int(10) unsigned NOT NULL,
  `spName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spContact` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isMain` tinyint(1) NOT NULL,
  KEY `supplier_person_spid_foreign` (`spId`),
  CONSTRAINT `supplier_person_spid_foreign` FOREIGN KEY (`spId`) REFERENCES `supplier` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_person`
--

LOCK TABLES `supplier_person` WRITE;
/*!40000 ALTER TABLE `supplier_person` DISABLE KEYS */;
INSERT INTO `supplier_person` VALUES (1,'Paul Cruz','+63 905 4090 523',1),(2,'Aeron Bunag','+63 999 9999 999',1);
/*!40000 ALTER TABLE `supplier_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician`
--

DROP TABLE IF EXISTS `technician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middleName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `contact` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `technician_firstname_middlename_lastname_unique` (`firstName`,`middleName`,`lastName`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician`
--

LOCK TABLES `technician` WRITE;
/*!40000 ALTER TABLE `technician` DISABLE KEYS */;
INSERT INTO `technician` VALUES (1,'Paul Andrei','Navarro','Cruz','521 D.Santiago St.','Brgy. Pedro Cruz','San Juan City','1999-01-28','+63 905 4090 523','','pics/20170828054534.jpg',1),(2,'Mariella','Reyes','Capispisan','Blk. 9 Lot 3 Tagupo St.','Brgy. Tatalon','Quezon City','1998-02-23','+63 926 9243 001','','pics/20170828054856.jpg',1),(3,'Khen Khen','Barrera','Gaviola','01 Apple St.','Welfarevill Compound','Mandaluyong City','1998-03-25','+63 916 3073 315','','pics/20170828055300.jpg',1),(4,'Jefferson Van','Lao','Lapuz','2844 Aurora Blvd.','Brgy. Di Mamahalin','Manila','1997-09-09','+63 905 8883 169','','pics/20170828055612.jpg',1),(5,'Alexandra Corrine','Nabu-ab','Alcantara','11-A, A.Bonifacio St.','Brgy. Hagdan Bato Libis','Quezon City','1998-06-27','+63 915 6439 450','','pics/20170828055804.jpg',1),(6,'Aeron Paul','','Bunag','Bldg. 69','LRT 2','Walter Mart','1999-08-27','+63 995 4794 505','','pics/20170828060354.jpg',1),(7,'Aljur','','Abrenica','151 C R. Lagmay St.','Balong Bato','San Juan City','1999-10-17','+63 999 9999 999','','pics/20171018020333.jpg',1);
/*!40000 ALTER TABLE `technician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_skill`
--

DROP TABLE IF EXISTS `technician_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_skill` (
  `technicianId` int(10) unsigned NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  KEY `technician_skill_technicianid_foreign` (`technicianId`),
  KEY `technician_skill_categoryid_foreign` (`categoryId`),
  CONSTRAINT `technician_skill_categoryid_foreign` FOREIGN KEY (`categoryId`) REFERENCES `service_category` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `technician_skill_technicianid_foreign` FOREIGN KEY (`technicianId`) REFERENCES `technician` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_skill`
--

LOCK TABLES `technician_skill` WRITE;
/*!40000 ALTER TABLE `technician_skill` DISABLE KEYS */;
INSERT INTO `technician_skill` VALUES (1,1),(1,2),(1,3),(2,1),(2,2),(2,3),(3,1),(3,2),(3,3),(4,1),(4,2),(4,3),(5,1),(5,2),(5,3),(6,1),(6,2),(6,3),(7,1);
/*!40000 ALTER TABLE `technician_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_brand`
--

DROP TABLE IF EXISTS `type_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_brand` (
  `typeId` int(10) unsigned NOT NULL,
  `brandId` int(10) unsigned NOT NULL,
  KEY `type_brand_typeid_foreign` (`typeId`),
  KEY `type_brand_brandid_foreign` (`brandId`),
  CONSTRAINT `type_brand_brandid_foreign` FOREIGN KEY (`brandId`) REFERENCES `product_brand` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `type_brand_typeid_foreign` FOREIGN KEY (`typeId`) REFERENCES `product_type` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_brand`
--

LOCK TABLES `type_brand` WRITE;
/*!40000 ALTER TABLE `type_brand` DISABLE KEYS */;
INSERT INTO `type_brand` VALUES (1,1),(1,2),(2,1),(2,2),(3,3),(3,4),(4,5),(5,6),(6,7),(4,8);
/*!40000 ALTER TABLE `type_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_variance`
--

DROP TABLE IF EXISTS `type_variance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_variance` (
  `typeId` int(10) unsigned NOT NULL,
  `varianceId` int(10) unsigned NOT NULL,
  KEY `type_variance_typeid_foreign` (`typeId`),
  KEY `type_variance_varianceid_foreign` (`varianceId`),
  CONSTRAINT `type_variance_typeid_foreign` FOREIGN KEY (`typeId`) REFERENCES `product_type` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `type_variance_varianceid_foreign` FOREIGN KEY (`varianceId`) REFERENCES `product_variance` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_variance`
--

LOCK TABLES `type_variance` WRITE;
/*!40000 ALTER TABLE `type_variance` DISABLE KEYS */;
INSERT INTO `type_variance` VALUES (1,1),(2,1),(3,2),(4,3),(5,4);
/*!40000 ALTER TABLE `type_variance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','','$2y$10$1bRf/d.xQZC9LCZQsS1XMOSM1rGUzXTl2lK/YUSGNLzgll/2.vqlW',1,NULL,'2017-10-17 17:58:44','2017-10-17 17:58:44'),(2,'TECH-0001','','$2y$10$rFheW3au9bYS6t13gZAR1eKfwuxXbeP3VixIVqumcT27PG6boC8E.',2,NULL,'2017-10-17 17:58:44','2017-10-17 17:58:44'),(3,'TECH-0002','','$2y$10$qxjMVfKXNjtWxNq7NRD.AOdoR47wYBUFBHj55mkzfB6IEk6uP/FFO',2,NULL,'2017-10-17 17:58:44','2017-10-17 17:58:44'),(4,'TECH-0003','','$2y$10$gqfLxAq4u0CWGFw6A2Gux.SS63U08WzSZTBr9VbDh2re4Plu4z2h2',2,NULL,'2017-10-17 17:58:44','2017-10-17 17:58:44'),(5,'TECH-0004','','$2y$10$nK0yS0Sw9svmDxz27uTrbulPMUzGIouEFNk85WJoDfbfKZ6Za70H2',2,NULL,'2017-10-17 17:58:44','2017-10-17 17:58:44'),(6,'TECH-0005','','$2y$10$yT34Uu8QDWKrHFjeDNebZu2khAiPeAvVxtkPbIJkGVwmFw8mvr8H2',2,NULL,'2017-10-17 17:58:45','2017-10-17 17:58:45'),(7,'TECH-0006','','$2y$10$Iw/DCFCvPenIa6SGVqLlL.MDjPalF6oc/A7465NmEqb8bpd8gREUS',2,NULL,'2017-10-17 17:58:45','2017-10-17 17:58:45'),(8,'TECH-0007','','$2y$10$bKLHCc7737GGiT6Av14aZ.uO1qjjSjTT5J05n6dHir3vJngY4Hi0S',2,NULL,'2017-10-17 18:03:33','2017-10-17 18:03:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilities`
--

DROP TABLE IF EXISTS `utilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'iRepair',
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Parts',
  `category2` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Supplies',
  `type1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Original',
  `type2` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Replacement',
  `max` int(11) NOT NULL DEFAULT '100',
  `backlog` int(11) NOT NULL DEFAULT '7',
  `isVat` tinyint(1) NOT NULL DEFAULT '1',
  `vat` int(11) NOT NULL DEFAULT '12',
  `isWarranty` tinyint(1) NOT NULL DEFAULT '1',
  `year` int(11) NOT NULL DEFAULT '1',
  `month` int(11) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilities`
--

LOCK TABLES `utilities` WRITE;
/*!40000 ALTER TABLE `utilities` DISABLE KEYS */;
INSERT INTO `utilities` VALUES (1,'pics/logo.png','iAyos','Anonas St., Sta. Mesa, Manila','Parts','Supplies','Original','Replacement',100,7,1,12,1,1,0,0,'2017-10-16 16:00:00','2017-10-16 16:00:00');
/*!40000 ALTER TABLE `utilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle`
--

DROP TABLE IF EXISTS `vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelId` int(10) unsigned NOT NULL,
  `isManual` tinyint(1) NOT NULL,
  `mileage` double(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_modelid_foreign` (`modelId`),
  CONSTRAINT `vehicle_modelid_foreign` FOREIGN KEY (`modelId`) REFERENCES `vehicle_model` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle`
--

LOCK TABLES `vehicle` WRITE;
/*!40000 ALTER TABLE `vehicle` DISABLE KEYS */;
INSERT INTO `vehicle` VALUES (1,'DPG 235',2,0,1000.00),(2,'WMX 289',5,1,15000.00),(3,'ASD 123',1,1,5000.00),(4,'ZXC 456',3,0,1200.00),(5,'JHK 685',3,1,1200.00);
/*!40000 ALTER TABLE `vehicle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_make`
--

DROP TABLE IF EXISTS `vehicle_make`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_make` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_make`
--

LOCK TABLES `vehicle_make` WRITE;
/*!40000 ALTER TABLE `vehicle_make` DISABLE KEYS */;
INSERT INTO `vehicle_make` VALUES (1,'Toyota',1),(2,'Honda',1);
/*!40000 ALTER TABLE `vehicle_make` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_model`
--

DROP TABLE IF EXISTS `vehicle_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `makeId` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasAuto` tinyint(1) NOT NULL,
  `hasManual` tinyint(1) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_model`
--

LOCK TABLES `vehicle_model` WRITE;
/*!40000 ALTER TABLE `vehicle_model` DISABLE KEYS */;
INSERT INTO `vehicle_model` VALUES (1,1,'Corolla 1998',1,1,1),(2,1,'Wigo 2010',1,0,1),(3,1,'Vios 2012',1,1,1),(4,1,'Vios 2010',1,1,1),(5,2,'City 2015',1,1,1);
/*!40000 ALTER TABLE `vehicle_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_header`
--

DROP TABLE IF EXISTS `warranty_job_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobId` int(10) unsigned NOT NULL,
  `warrantyJobId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_header_jobid_foreign` (`jobId`),
  KEY `warranty_job_header_warrantyjobid_foreign` (`warrantyJobId`),
  CONSTRAINT `warranty_job_header_jobid_foreign` FOREIGN KEY (`jobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_header_warrantyjobid_foreign` FOREIGN KEY (`warrantyJobId`) REFERENCES `job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_header`
--

LOCK TABLES `warranty_job_header` WRITE;
/*!40000 ALTER TABLE `warranty_job_header` DISABLE KEYS */;
INSERT INTO `warranty_job_header` VALUES (1,6,7,'2017-10-17 18:44:47','2017-10-17 18:44:47');
/*!40000 ALTER TABLE `warranty_job_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_package_product`
--

DROP TABLE IF EXISTS `warranty_job_package_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_package_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobPackageId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_package_product_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_package_product_jobpackageid_foreign` (`jobPackageId`),
  KEY `warranty_job_package_product_productid_foreign` (`productId`),
  CONSTRAINT `warranty_job_package_product_jobpackageid_foreign` FOREIGN KEY (`jobPackageId`) REFERENCES `job_package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_package_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_package_product_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_package_product`
--

LOCK TABLES `warranty_job_package_product` WRITE;
/*!40000 ALTER TABLE `warranty_job_package_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_job_package_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_package_service`
--

DROP TABLE IF EXISTS `warranty_job_package_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_package_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobPackageId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_package_service_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_package_service_jobpackageid_foreign` (`jobPackageId`),
  KEY `warranty_job_package_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `warranty_job_package_service_jobpackageid_foreign` FOREIGN KEY (`jobPackageId`) REFERENCES `job_package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_package_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_package_service_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_package_service`
--

LOCK TABLES `warranty_job_package_service` WRITE;
/*!40000 ALTER TABLE `warranty_job_package_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_job_package_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_product`
--

DROP TABLE IF EXISTS `warranty_job_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobProductId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_product_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_product_jobproductid_foreign` (`jobProductId`),
  KEY `warranty_job_product_productid_foreign` (`productId`),
  CONSTRAINT `warranty_job_product_jobproductid_foreign` FOREIGN KEY (`jobProductId`) REFERENCES `job_product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_product_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_product`
--

LOCK TABLES `warranty_job_product` WRITE;
/*!40000 ALTER TABLE `warranty_job_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_job_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_promo_product`
--

DROP TABLE IF EXISTS `warranty_job_promo_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_promo_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobPromoId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_promo_product_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_promo_product_jobpromoid_foreign` (`jobPromoId`),
  KEY `warranty_job_promo_product_productid_foreign` (`productId`),
  CONSTRAINT `warranty_job_promo_product_jobpromoid_foreign` FOREIGN KEY (`jobPromoId`) REFERENCES `job_promo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_promo_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_promo_product_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_promo_product`
--

LOCK TABLES `warranty_job_promo_product` WRITE;
/*!40000 ALTER TABLE `warranty_job_promo_product` DISABLE KEYS */;
INSERT INTO `warranty_job_promo_product` VALUES (1,1,2,4,2,'2017-10-17 18:44:47','2017-10-17 18:44:47'),(2,1,2,4,0,'2017-10-17 18:44:47','2017-10-17 18:44:47');
/*!40000 ALTER TABLE `warranty_job_promo_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_promo_service`
--

DROP TABLE IF EXISTS `warranty_job_promo_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_promo_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobPromoId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_promo_service_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_promo_service_jobpromoid_foreign` (`jobPromoId`),
  KEY `warranty_job_promo_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `warranty_job_promo_service_jobpromoid_foreign` FOREIGN KEY (`jobPromoId`) REFERENCES `job_promo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_promo_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_promo_service_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_promo_service`
--

LOCK TABLES `warranty_job_promo_service` WRITE;
/*!40000 ALTER TABLE `warranty_job_promo_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_job_promo_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_job_service`
--

DROP TABLE IF EXISTS `warranty_job_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_job_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `jobServiceId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_job_service_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_job_service_jobserviceid_foreign` (`jobServiceId`),
  KEY `warranty_job_service_serviceid_foreign` (`serviceId`),
  CONSTRAINT `warranty_job_service_jobserviceid_foreign` FOREIGN KEY (`jobServiceId`) REFERENCES `job_service` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_service_serviceid_foreign` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_job_service_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_job_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_job_service`
--

LOCK TABLES `warranty_job_service` WRITE;
/*!40000 ALTER TABLE `warranty_job_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_job_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_sales_header`
--

DROP TABLE IF EXISTS `warranty_sales_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_sales_header` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salesId` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_sales_header_salesid_foreign` (`salesId`),
  CONSTRAINT `warranty_sales_header_salesid_foreign` FOREIGN KEY (`salesId`) REFERENCES `sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_sales_header`
--

LOCK TABLES `warranty_sales_header` WRITE;
/*!40000 ALTER TABLE `warranty_sales_header` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_sales_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_sales_package`
--

DROP TABLE IF EXISTS `warranty_sales_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_sales_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `salesPackageId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_sales_package_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_sales_package_salespackageid_foreign` (`salesPackageId`),
  KEY `warranty_sales_package_productid_foreign` (`productId`),
  CONSTRAINT `warranty_sales_package_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_package_salespackageid_foreign` FOREIGN KEY (`salesPackageId`) REFERENCES `sales_package` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_package_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_sales_package`
--

LOCK TABLES `warranty_sales_package` WRITE;
/*!40000 ALTER TABLE `warranty_sales_package` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_sales_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_sales_product`
--

DROP TABLE IF EXISTS `warranty_sales_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_sales_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `salesProductId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_sales_product_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_sales_product_salesproductid_foreign` (`salesProductId`),
  KEY `warranty_sales_product_productid_foreign` (`productId`),
  CONSTRAINT `warranty_sales_product_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_product_salesproductid_foreign` FOREIGN KEY (`salesProductId`) REFERENCES `sales_product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_product_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_sales_product`
--

LOCK TABLES `warranty_sales_product` WRITE;
/*!40000 ALTER TABLE `warranty_sales_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_sales_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_sales_promo`
--

DROP TABLE IF EXISTS `warranty_sales_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warranty_sales_promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warrantyId` int(10) unsigned NOT NULL,
  `salesPromoId` int(10) unsigned NOT NULL,
  `productId` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warranty_sales_promo_warrantyid_foreign` (`warrantyId`),
  KEY `warranty_sales_promo_salespromoid_foreign` (`salesPromoId`),
  KEY `warranty_sales_promo_productid_foreign` (`productId`),
  CONSTRAINT `warranty_sales_promo_productid_foreign` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_promo_salespromoid_foreign` FOREIGN KEY (`salesPromoId`) REFERENCES `sales_promo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `warranty_sales_promo_warrantyid_foreign` FOREIGN KEY (`warrantyId`) REFERENCES `warranty_sales_header` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_sales_promo`
--

LOCK TABLES `warranty_sales_promo` WRITE;
/*!40000 ALTER TABLE `warranty_sales_promo` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_sales_promo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-18  2:50:08
