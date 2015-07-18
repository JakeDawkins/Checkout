# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.24)
# Database: checkout
# Generation Time: 2015-07-18 01:49:08 +0000
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
  `person_id` int(11) DEFAULT NULL,
  `co_start` datetime NOT NULL,
  `co_end` datetime NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`co_id`),
  KEY `rental_person` (`person_id`),
  CONSTRAINT `checkout_person` FOREIGN KEY (`person_id`) REFERENCES `uc_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `checkouts` WRITE;
/*!40000 ALTER TABLE `checkouts` DISABLE KEYS */;

INSERT INTO `checkouts` (`co_id`, `title`, `person_id`, `co_start`, `co_end`, `description`)
VALUES
	(1,'test',2,'2015-06-24 23:32:51','2015-06-30 23:32:51','this is a test'),
	(17,'more updates',2,'2015-10-15 01:00:00','2015-10-15 03:00:00','just wanna check out some fun stuff.'),
	(18,'my new checkout!',2,'2012-12-12 01:00:00','2012-12-13 01:00:00','This is a test of the full system'),
	(19,'test',2,'2015-07-15 12:00:00','2015-07-17 12:00:00','this is a test'),
	(20,'test',2,'2015-07-15 00:00:01','2015-07-19 00:00:01','dfhsjkldhsjakfldshjfklads'),
	(21,'Test',2,'2015-12-12 00:00:01','2015-12-12 00:00:02','Bduskasbs');

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
	(2,2,1),
	(57,1,17),
	(58,4,17),
	(59,3,17),
	(60,1,18),
	(61,2,18),
	(62,1,19),
	(63,4,19),
	(64,3,20),
	(65,5,20),
	(66,4,21),
	(67,7,21);

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
  CONSTRAINT `gear_types` FOREIGN KEY (`gear_type_id`) REFERENCES `gear_types` (`gear_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gear` WRITE;
/*!40000 ALTER TABLE `gear` DISABLE KEYS */;

INSERT INTO `gear` (`gear_id`, `gear_type_id`, `name`)
VALUES
	(1,1,'cam1'),
	(2,1,'cam2'),
	(3,1,'cam3'),
	(4,1,'cam4'),
	(5,1,'camera4-id'),
	(6,1,'test-name'),
	(7,2,'my item 1');

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
	(1,'Camera'),
	(2,'Lens'),
	(3,'Tripod'),
	(4,'Battery');

/*!40000 ALTER TABLE `gear_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_configuration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_configuration`;

CREATE TABLE `uc_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `uc_configuration` WRITE;
/*!40000 ALTER TABLE `uc_configuration` DISABLE KEYS */;

INSERT INTO `uc_configuration` (`id`, `name`, `value`)
VALUES
	(1,'website_name','Checkout'),
	(2,'website_url','http://dev.jakedawkins.com/'),
	(3,'email','hello@jakedawkins.com'),
	(4,'activation','false'),
	(5,'resend_activation_threshold','0'),
	(6,'language','models/languages/en.php'),
	(7,'template','models/site-templates/default.css');

/*!40000 ALTER TABLE `uc_configuration` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_pages`;

CREATE TABLE `uc_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `uc_pages` WRITE;
/*!40000 ALTER TABLE `uc_pages` DISABLE KEYS */;

INSERT INTO `uc_pages` (`id`, `page`, `private`)
VALUES
	(1,'account.php',1),
	(2,'activate-account.php',0),
	(3,'admin_configuration.php',1),
	(4,'admin_page.php',1),
	(5,'admin_pages.php',1),
	(6,'admin_permission.php',1),
	(7,'admin_permissions.php',1),
	(8,'admin_user.php',1),
	(9,'admin_users.php',1),
	(10,'forgot-password.php',0),
	(11,'index.php',0),
	(12,'left-nav.php',0),
	(13,'login.php',0),
	(14,'logout.php',1),
	(15,'register.php',0),
	(16,'resend-activation.php',0),
	(17,'user_settings.php',1),
	(18,'example.php',1);

/*!40000 ALTER TABLE `uc_pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_permission_page_matches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_permission_page_matches`;

CREATE TABLE `uc_permission_page_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `uc_permission_page_matches` WRITE;
/*!40000 ALTER TABLE `uc_permission_page_matches` DISABLE KEYS */;

INSERT INTO `uc_permission_page_matches` (`id`, `permission_id`, `page_id`)
VALUES
	(1,1,1),
	(2,1,14),
	(3,1,17),
	(4,2,1),
	(5,2,3),
	(6,2,4),
	(7,2,5),
	(8,2,6),
	(9,2,7),
	(10,2,8),
	(11,2,9),
	(12,2,14),
	(13,2,17),
	(23,1,18),
	(24,2,18),
	(25,3,18);

/*!40000 ALTER TABLE `uc_permission_page_matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_permissions`;

CREATE TABLE `uc_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `uc_permissions` WRITE;
/*!40000 ALTER TABLE `uc_permissions` DISABLE KEYS */;

INSERT INTO `uc_permissions` (`id`, `name`)
VALUES
	(1,'New Member'),
	(2,'Administrator'),
	(3,'Team');

/*!40000 ALTER TABLE `uc_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_user_permission_matches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_user_permission_matches`;

CREATE TABLE `uc_user_permission_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `uc_user_permission_matches` WRITE;
/*!40000 ALTER TABLE `uc_user_permission_matches` DISABLE KEYS */;

INSERT INTO `uc_user_permission_matches` (`id`, `user_id`, `permission_id`)
VALUES
	(1,1,2),
	(2,1,1),
	(3,2,1),
	(4,2,2);

/*!40000 ALTER TABLE `uc_user_permission_matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uc_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uc_users`;

CREATE TABLE `uc_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `title` varchar(150) NOT NULL,
  `sign_up_stamp` int(11) NOT NULL,
  `last_sign_in_stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `uc_users` WRITE;
/*!40000 ALTER TABLE `uc_users` DISABLE KEYS */;

INSERT INTO `uc_users` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `title`, `sign_up_stamp`, `last_sign_in_stamp`)
VALUES
	(2,'jakedawkins','jakedawkins','8ea511aaf8f9903814f2134caa17b42b45ed37901bb394749a49c9d5c6476efaf','dawkinsjh@gmail.com','7245cc23f1d3593dd1691573d2b464e7',1437159856,0,1,'Site Owner',1437159856,1437165451);

/*!40000 ALTER TABLE `uc_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
