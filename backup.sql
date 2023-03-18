-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.51 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Дамп структуры для таблица test_parse.tenders_parse
CREATE TABLE IF NOT EXISTS `tenders_parse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenderNumber` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `tenderOrganizator` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tenderViewUrl` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dateApplicationStart` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filesResult` text COLLATE utf8mb4_unicode_ci,
  `requestSiteUrl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requestSiteFieldsData` text COLLATE utf8mb4_unicode_ci,
  `dateParse` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `tenderOrganizator` (`tenderOrganizator`),
  FULLTEXT KEY `dateApplicationStart` (`dateApplicationStart`),
  FULLTEXT KEY `tenderNumber` (`tenderNumber`),
  FULLTEXT KEY `requestSiteDomain` (`requestSiteUrl`,`requestSiteFieldsData`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы test_parse.tenders_parse: ~0 rows (приблизительно)
DELETE FROM `tenders_parse`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
