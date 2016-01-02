# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.42)
# Database: checkout
# Generation Time: 2016-01-02 17:55:04 +0000
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
  `returned` datetime DEFAULT NULL,
  `dr_number` varchar(32) DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`co_id`),
  KEY `rental_person` (`person_id`),
  CONSTRAINT `checkout_person` FOREIGN KEY (`person_id`) REFERENCES `uc_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table co_gear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `co_gear`;

CREATE TABLE `co_gear` (
  `co_gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gear_id` int(11) unsigned NOT NULL,
  `co_id` int(11) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`co_gear_id`),
  KEY `rental_gear` (`gear_id`),
  KEY `rental_gear_rental` (`co_id`),
  CONSTRAINT `rental_gear` FOREIGN KEY (`gear_id`) REFERENCES `gear` (`gear_id`) ON DELETE CASCADE,
  CONSTRAINT `rental_gear_rental` FOREIGN KEY (`co_id`) REFERENCES `checkouts` (`co_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table gear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gear`;

CREATE TABLE `gear` (
  `gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gear_type_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `qty` int(11) unsigned NOT NULL DEFAULT '1',
  `isDisabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`gear_id`),
  KEY `gear_type` (`gear_type_id`),
  CONSTRAINT `gear_types` FOREIGN KEY (`gear_type_id`) REFERENCES `gear_types` (`gear_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table gear_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gear_types`;

CREATE TABLE `gear_types` (
  `gear_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`gear_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `pkg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`pkg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table packages_gear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages_gear`;

CREATE TABLE `packages_gear` (
  `pkg_gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gear_id` int(11) unsigned NOT NULL,
  `pkg_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`pkg_gear_id`),
  KEY `rental_gear` (`gear_id`),
  KEY `rental_gear_rental` (`pkg_id`),
  CONSTRAINT `pkg_gear` FOREIGN KEY (`gear_id`) REFERENCES `gear` (`gear_id`) ON DELETE CASCADE,
  CONSTRAINT `pkg_pkg` FOREIGN KEY (`pkg_id`) REFERENCES `packages` (`pkg_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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
	(2,'website_url','http://mysite.com/'),
	(3,'email','hello@mysite.com'),
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
	(13,'login.php',0),
	(14,'logout.php',1),
	(15,'register.php',0),
	(16,'resend-activation.php',0),
	(17,'user_settings.php',1),
	(19,'checkout.php',1),
	(20,'checkouts.php',1),
	(21,'inventory.php',1),
	(25,'edit-checkout.php',1),
	(26,'edit-gear-types.php',1),
	(27,'new-checkout.php',1),
	(28,'new-gear.php',1),
	(29,'edit-gear.php',1),
	(30,'test.php',0),
	(31,'gear-item.php',1),
	(32,'edit-package.php',1),
	(33,'new-package.php',1),
	(34,'package.php',1),
	(35,'packages.php',1),
	(36,'checkouts-table.php',1),
	(37,'feedback.php',1);

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
	(27,2,21),
	(28,3,21),
	(30,2,19),
	(31,3,19),
	(33,2,20),
	(44,2,28),
	(46,2,28),
	(48,2,27),
	(49,3,27),
	(50,2,26),
	(52,2,25),
	(53,3,25),
	(54,3,20),
	(55,3,17),
	(56,3,1),
	(57,2,29),
	(59,2,31),
	(60,3,31),
	(61,2,34),
	(62,3,34),
	(63,2,32),
	(64,3,32),
	(65,2,33),
	(66,3,33),
	(67,2,35),
	(68,3,35),
	(69,2,37),
	(70,3,37),
	(71,2,36),
	(72,3,36);

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
	(10,6,1),
	(11,6,2),
	(12,6,3);

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
	(6,'admin','Admin','eb80ede90abfd235b3d71dcf5af8b5cca1e8f8aef0cfa635c2eeb5e39d18963fc','admin@admin.com','30b50c0eb796aab293b761ca37097ba6',1451757115,0,1,'New Member',1451757115,1451757226);

/*!40000 ALTER TABLE `uc_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
