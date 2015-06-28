# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.24)
# Database: checkout
# Generation Time: 2015-06-28 14:01:41 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table checkouts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `checkouts`;

CREATE TABLE `checkouts` (
  `co_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `person_id` int(11) unsigned NOT NULL,
  `co_start` datetime NOT NULL,
  `co_end` datetime NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`co_id`),
  KEY `rental_person` (`person_id`),
  CONSTRAINT `rental_person` FOREIGN KEY (`person_id`) REFERENCES `people` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `checkouts` WRITE;
/*!40000 ALTER TABLE `checkouts` DISABLE KEYS */;

INSERT INTO `checkouts` (`co_id`, `title`, `person_id`, `co_start`, `co_end`, `description`)
VALUES
	(1,'test',1,'2015-06-24 23:32:51','2015-06-30 23:32:51','this is a test');

/*!40000 ALTER TABLE `checkouts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table co_gear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `co_gear`;

CREATE TABLE `co_gear` (
  `co_gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gear_id` int(11) unsigned NOT NULL,
  `co_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`co_gear_id`),
  KEY `rental_gear` (`gear_id`),
  KEY `rental_gear_rental` (`co_id`),
  CONSTRAINT `rental_gear` FOREIGN KEY (`gear_id`) REFERENCES `gear` (`gear_id`) ON DELETE CASCADE,
  CONSTRAINT `rental_gear_rental` FOREIGN KEY (`co_id`) REFERENCES `checkouts` (`co_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `co_gear` WRITE;
/*!40000 ALTER TABLE `co_gear` DISABLE KEYS */;

INSERT INTO `co_gear` (`co_gear_id`, `gear_id`, `co_id`)
VALUES
	(1,1,1),
	(2,2,1);

/*!40000 ALTER TABLE `co_gear` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gear`;

CREATE TABLE `gear` (
  `gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gear_type_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`gear_id`),
  KEY `gear_type` (`gear_type_id`),
  CONSTRAINT `gear_type` FOREIGN KEY (`gear_type_id`) REFERENCES `gear` (`gear_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gear` WRITE;
/*!40000 ALTER TABLE `gear` DISABLE KEYS */;

INSERT INTO `gear` (`gear_id`, `gear_type_id`, `name`)
VALUES
	(1,1,'cam1'),
	(2,1,'cam2'),
	(3,1,'cam3');

/*!40000 ALTER TABLE `gear` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gear_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gear_types`;

CREATE TABLE `gear_types` (
  `gear_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`gear_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gear_types` WRITE;
/*!40000 ALTER TABLE `gear_types` DISABLE KEYS */;

INSERT INTO `gear_types` (`gear_type_id`, `type`)
VALUES
	(1,'Camera');

/*!40000 ALTER TABLE `gear_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table people
# ------------------------------------------------------------

DROP TABLE IF EXISTS `people`;

CREATE TABLE `people` (
  `person_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(32) NOT NULL DEFAULT '',
  `pw_salt` varchar(128) DEFAULT NULL,
  `pw_hash` varchar(128) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;

INSERT INTO `people` (`person_id`, `name`, `username`, `pw_salt`, `pw_hash`, `admin`)
VALUES
	(1,'Jake Dawkins','jake.dawkins',NULL,NULL,1);

/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
