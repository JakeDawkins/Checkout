# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.42)
# Database: checkout
# Generation Time: 2015-11-16 16:51:06 +0000
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

LOCK TABLES `checkouts` WRITE;
/*!40000 ALTER TABLE `checkouts` DISABLE KEYS */;

INSERT INTO `checkouts` (`co_id`, `title`, `person_id`, `co_start`, `co_end`, `description`, `returned`, `dr_number`, `location`)
VALUES
	(1,'test',2,'2015-06-24 23:32:51','2015-06-30 23:32:51','this is a test',NULL,NULL,NULL),
	(18,'my new checkout!',2,'2012-12-12 01:00:00','2012-12-13 01:00:00','This is a test of the full system',NULL,NULL,NULL),
	(19,'test',2,'2015-07-15 12:00:00','2015-07-17 12:00:00','this is a test',NULL,NULL,NULL),
	(22,'My secure test',2,'2015-06-01 00:00:01','2015-06-02 00:00:01','this is an awesome description',NULL,NULL,NULL),
	(27,'4 Type Test',2,'2015-09-20 00:00:01','2015-09-21 00:00:01','fdsg sjdk fd df ds da',NULL,NULL,NULL),
	(28,'5 Type Test',2,'2015-09-22 00:00:01','2015-09-23 00:00:01','ghjfsklgsfhd',NULL,NULL,NULL),
	(30,'Long Item name Test',2,'2015-09-14 00:15:00','2015-09-18 15:00:00','Thfhasdjkgdh',NULL,NULL,NULL),
	(32,'test',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','fdsahjkd',NULL,NULL,NULL),
	(33,'jgk',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','ghj',NULL,NULL,NULL),
	(34,'fdghdfghd',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','fghj',NULL,NULL,NULL),
	(35,'',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','',NULL,NULL,NULL),
	(36,'dsafhjkl',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','fhsdalk',NULL,NULL,NULL),
	(37,'dsafhjkl',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','fhsdalk',NULL,NULL,NULL),
	(38,'sdfhjk',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','sd',NULL,NULL,NULL),
	(39,'fds',2,'0000-00-00 00:00:00','0000-00-00 00:00:00','lkjkljklsdjklfjdsklfjdskl',NULL,NULL,NULL),
	(41,'hjkl',2,'2015-01-01 01:00:00','2015-01-03 01:00:00','ruheisfdj',NULL,NULL,NULL),
	(49,'gyrgdhfjkl',2,'2015-01-01 01:00:00','2015-01-03 01:00:00','ffdgjkfgd',NULL,NULL,NULL),
	(50,'test of new checkout class',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','1234567890',NULL,NULL,NULL),
	(56,'123456789',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','987654321',NULL,NULL,NULL),
	(57,'abcdefg',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','gfedcba',NULL,NULL,NULL),
	(58,'49782345894358',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','asdfghjklkjhgfdsasdfghjklkjhgfdsa',NULL,NULL,NULL),
	(59,'jake is a boss',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','afjdsakglhdsfjgkhdsfjkghsdfjkl afjdsakglhdsfjgkhdsfjkghsdfjkl\r\nafjdsakglhdsfjgkhdsfjkghsdfjkl afjdsakglhdsfjgkhdsfjkghsdfjkl\r\nafjdsakglhdsfjgkhdsfjkghsdfjkl afjdsakglhdsfjgkhdsfjkghsdfjkl',NULL,NULL,NULL),
	(60,'jake is a boss 2',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','dfahdsjkfghdsjkfghjdkshgjkhdfkjl','2015-10-14 07:50:00',NULL,NULL),
	(61,'test2',2,'2015-10-05 01:00:00','2015-10-05 19:00:00','fhjsdklghfdsjgklhdfjkl','2015-10-14 07:50:00',NULL,NULL),
	(63,'test edit',2,'2015-03-01 09:00:00','2015-03-03 09:00:00','hello world!\r\nabcde 12345',NULL,NULL,NULL),
	(64,'djskfl;sd',2,'2015-03-01 01:00:00','2015-03-03 01:00:00','dhajsdklh',NULL,NULL,NULL),
	(65,'fdsahgjkl',2,'2015-03-01 01:00:00','2015-03-03 01:00:00','safjdklfhjsdklghdjfklghjksdlhfgjkldh',NULL,NULL,NULL),
	(66,'forTesting TEST',2,'2015-05-03 01:00:00','2015-05-04 01:00:00','i like tomatos',NULL,NULL,NULL),
	(68,'now test',2,'2015-10-13 01:00:00','2015-10-16 01:00:00','fdajgklh','2015-10-14 14:16:00',NULL,NULL),
	(70,'happy days',2,'2015-10-22 01:00:00','2015-10-23 01:00:00','1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890\r\n1234567890',NULL,NULL,NULL),
	(71,'123456489',2,'2015-02-01 01:00:00','2015-02-03 01:00:00','fhjkl',NULL,NULL,NULL),
	(72,'Nov Test',2,'2015-11-01 01:00:00','2015-11-04 01:00:00','november',NULL,NULL,NULL),
	(73,'vfjkl;',2,'2015-01-01 01:00:00','2015-01-02 01:00:00','vdsjkl;',NULL,NULL,NULL),
	(74,'fdsg',2,'2015-11-15 01:00:00','2015-11-17 01:00:00','fdsgdshgf','2015-11-16 10:43:49',NULL,NULL),
	(75,'dsfgh',4,'2015-01-01 01:00:00','2015-01-03 01:00:00','dfgh',NULL,NULL,NULL),
	(76,'10501 Perry Podcast',4,'2015-11-22 08:00:00','2015-11-22 17:00:00','2 people and 3 cameras in the Conference Room in Anderson.',NULL,NULL,NULL);

/*!40000 ALTER TABLE `checkouts` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `co_gear` WRITE;
/*!40000 ALTER TABLE `co_gear` DISABLE KEYS */;

INSERT INTO `co_gear` (`co_gear_id`, `gear_id`, `co_id`, `qty`)
VALUES
	(71,7,22,1),
	(88,7,27,1),
	(91,14,27,1),
	(92,18,27,1),
	(96,7,28,1),
	(98,14,28,1),
	(99,17,28,1),
	(100,19,28,1),
	(101,20,28,1),
	(105,31,30,1),
	(106,19,30,1),
	(107,58,30,1),
	(108,64,30,1),
	(109,73,30,1),
	(110,77,30,1),
	(111,87,30,1),
	(112,105,30,1),
	(113,131,30,1),
	(114,133,30,1),
	(152,44,41,1),
	(153,37,41,1),
	(154,38,41,1),
	(155,39,41,1),
	(156,27,41,1),
	(157,28,41,1),
	(158,29,41,1),
	(159,30,41,1),
	(160,31,41,1),
	(161,71,41,1),
	(162,72,41,1),
	(163,73,41,1),
	(164,74,41,1),
	(165,107,41,1),
	(166,108,41,1),
	(167,109,41,1),
	(168,23,41,1),
	(169,43,41,1),
	(170,112,41,1),
	(178,136,41,1),
	(179,135,41,1),
	(180,134,41,1),
	(181,99,41,1),
	(182,14,49,1),
	(183,26,50,1),
	(184,17,50,1),
	(185,22,50,1),
	(186,24,50,1),
	(209,136,56,0),
	(210,128,56,0),
	(211,91,56,0),
	(212,92,56,0),
	(213,136,57,0),
	(214,128,57,0),
	(215,129,57,0),
	(216,136,58,0),
	(217,128,58,0),
	(218,129,58,0),
	(235,135,64,10),
	(236,135,65,3),
	(240,136,63,2),
	(241,135,63,2),
	(247,136,66,3),
	(248,135,66,1),
	(249,18,66,2),
	(250,27,66,1),
	(251,36,66,1),
	(252,37,66,1),
	(253,22,66,2),
	(254,23,66,1),
	(255,113,66,1),
	(256,122,66,3),
	(257,51,66,1),
	(258,44,66,1),
	(259,97,66,1),
	(260,89,66,1),
	(268,136,60,3),
	(269,130,60,1),
	(270,18,60,1),
	(271,42,60,1),
	(272,105,60,1),
	(297,136,68,1),
	(298,135,68,1),
	(299,134,68,1),
	(300,128,68,1),
	(301,129,68,1),
	(302,132,68,1),
	(303,131,68,1),
	(304,130,68,1),
	(317,136,61,4),
	(318,135,61,5),
	(319,134,61,1),
	(320,132,61,1),
	(321,131,61,1),
	(322,18,61,2),
	(323,27,61,1),
	(324,98,61,1),
	(325,68,61,1),
	(326,136,59,1),
	(327,128,59,1),
	(328,129,59,1),
	(329,133,59,1),
	(330,132,59,1),
	(331,136,70,1),
	(332,135,70,1),
	(333,134,70,1),
	(334,128,70,1),
	(335,129,70,1),
	(336,133,70,1),
	(337,132,70,1),
	(338,131,70,1),
	(339,136,71,2),
	(340,135,71,2),
	(341,134,71,1),
	(342,128,71,1),
	(343,129,71,1),
	(344,133,71,1),
	(345,132,71,1),
	(346,131,71,1),
	(347,136,72,1),
	(348,135,72,1),
	(349,134,72,1),
	(350,128,72,1),
	(351,129,72,1),
	(352,133,72,1),
	(353,132,72,1),
	(354,131,72,1),
	(355,136,73,2),
	(356,135,73,5),
	(357,131,73,4),
	(367,136,74,1),
	(368,135,74,1),
	(369,134,74,1),
	(370,128,74,1),
	(371,129,74,1),
	(372,133,74,1),
	(373,132,74,1),
	(374,131,74,1),
	(375,130,74,1),
	(376,25,75,1),
	(377,21,75,1),
	(396,40,76,1),
	(397,38,76,1),
	(398,39,76,1),
	(399,107,76,6),
	(400,45,76,1),
	(401,47,76,1),
	(402,75,76,1),
	(403,78,76,1),
	(404,59,76,1);

/*!40000 ALTER TABLE `co_gear` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `gear` WRITE;
/*!40000 ALTER TABLE `gear` DISABLE KEYS */;

INSERT INTO `gear` (`gear_id`, `gear_type_id`, `name`, `qty`, `isDisabled`, `notes`)
VALUES
	(7,2,'my item 1',1,0,NULL),
	(14,8,'C100 Z-Finder',2,0,NULL),
	(17,8,'Slate',3,0,NULL),
	(18,8,'5D Z-Finder',2,0,NULL),
	(19,9,'Kino-Flo Diva 200 Kit',1,0,NULL),
	(20,9,'Kino-Flo Diva 400 Kit',1,0,NULL),
	(21,1,'RED EPIC',1,0,NULL),
	(22,1,'Canon 5D',4,0,NULL),
	(23,1,'Canon 7D',1,0,NULL),
	(24,1,'GoPro Hero 3',2,0,NULL),
	(25,1,'GoPro Hero 3+',1,0,NULL),
	(26,8,'Samsung 24&quot; TV',1,0,NULL),
	(27,8,'AC7',1,0,NULL),
	(28,8,'DP7',1,0,NULL),
	(29,8,'DP4',1,0,NULL),
	(30,8,'Paralinx Wireless',1,0,NULL),
	(31,8,'RedRock Wireless Follow Focus',1,0,NULL),
	(36,7,'Hi-Hat Tripod',1,0,NULL),
	(37,7,'Large Gitzo',1,0,NULL),
	(38,7,'Miller DS10',1,0,NULL),
	(39,7,'Miller DS20',1,0,NULL),
	(40,7,'Manfrotto w/501',1,0,NULL),
	(41,7,'Manfrotto w/701',1,0,NULL),
	(42,7,'Manfrotto Monopod',1,0,NULL),
	(43,7,'Small Gitzo',1,0,NULL),
	(44,2,'16-35mm',1,0,NULL),
	(45,2,'24-105mm',3,0,NULL),
	(46,2,'70-200mm',1,0,NULL),
	(47,2,'70-200mm (2)',1,0,NULL),
	(48,2,'24-70mm',1,0,NULL),
	(49,2,'50mm 1.4',1,0,NULL),
	(50,2,'50mm 1.2',1,0,NULL),
	(51,2,'100mm 2.8',1,0,NULL),
	(52,2,'Rokinon 16mm',1,0,NULL),
	(53,2,'Rokinon 24mm',1,0,NULL),
	(54,2,'Rokinon 35mm',1,0,NULL),
	(55,2,'Rokinon 50mm',1,0,NULL),
	(56,2,'Rokinon 85mm',1,0,NULL),
	(57,9,'Kino-Flo Diva 200 Kit',1,0,NULL),
	(58,9,'Kino-Flo Diva 400 Kit',1,0,NULL),
	(59,9,'Kino-Flo Gaffer Kit',1,0,NULL),
	(60,9,'Kino-Flo Single Kit',2,0,NULL),
	(61,9,'Ellipsoidal',1,0,NULL),
	(62,9,'1K Par',4,0,NULL),
	(63,9,'Arri 150',5,0,NULL),
	(64,9,'Arri 300',1,0,NULL),
	(65,9,'Arri 650',4,0,NULL),
	(66,9,'Joker 400',1,0,NULL),
	(67,9,'Joker 800',1,0,NULL),
	(68,9,'Triolet',3,0,NULL),
	(69,9,'Alzo LED Kit',1,0,NULL),
	(70,9,'Chimera Medium Pancake',2,0,NULL),
	(71,9,'Chimera Large Pancake',1,0,NULL),
	(72,9,'Chimera Small Softbox',1,0,NULL),
	(73,9,'Chimera Xtra Small Softbox for Joker 400',1,0,NULL),
	(74,9,'Chimera Large Softbox with Egg Crate',1,0,NULL),
	(75,9,'4x4 Floppy Cutters',2,0,NULL),
	(76,9,'4x4 Cutter',1,0,NULL),
	(77,9,'36x36 Cutter',1,0,NULL),
	(78,9,'4x4 Silk',2,0,NULL),
	(79,9,'4x4 China Silk',1,0,NULL),
	(80,9,'4x4 Double Scrim',2,0,NULL),
	(81,9,'4x4 Single Scrim',1,0,NULL),
	(82,9,'30x36 Cutter',1,0,NULL),
	(83,9,'24x36 Cutter',2,0,NULL),
	(84,9,'18x24 Cutter',1,0,NULL),
	(85,9,'18x24 Silk',2,0,NULL),
	(86,9,'18x24 Frame',1,0,NULL),
	(87,9,'18x24 Modifier Kit',1,0,NULL),
	(88,9,'8x8 Frame',1,0,NULL),
	(89,9,'12x12 Frame',1,0,NULL),
	(90,9,'8x8 Ultrabounce/Flag',1,0,NULL),
	(91,9,'8x8 Silent Full Grid',1,0,NULL),
	(92,9,'8x8 Silk',1,0,NULL),
	(93,9,'8x8 Blue/Green Screen',1,0,NULL),
	(94,9,'12x12 Ultrabounce/Flag',1,0,NULL),
	(95,9,'12x12 Silk',1,0,NULL),
	(96,9,'12x12 Quarter Stop Silk',1,0,NULL),
	(97,9,'12x12 Blue/Green Screen',1,0,NULL),
	(98,9,'Matthboard',1,0,NULL),
	(99,9,'Sunswatter',1,0,NULL),
	(100,9,'4x4 Collapsible Square Bounce',1,0,NULL),
	(101,9,'Applebox Sets',3,0,NULL),
	(102,9,'1K Dimmer',7,0,NULL),
	(103,9,'600W Dimmer',3,0,NULL),
	(104,16,'Easy Rig',1,0,NULL),
	(105,16,'RedRock Shoulder Rig',1,0,NULL),
	(106,16,'Stand - Junior Low Boy 2XR',2,0,NULL),
	(107,16,'C-Stand',12,0,NULL),
	(108,16,'Stand - Low Boy',1,0,NULL),
	(109,16,'Stand - Slider',2,0,NULL),
	(110,16,'Stand - Rolling Combo',2,0,NULL),
	(111,16,'Stand - Combo 3XR',2,0,NULL),
	(112,16,'Stand - Combo 2XR',2,0,NULL),
	(113,16,'Avenger Junior Boom',1,0,NULL),
	(114,16,'Jib',1,0,NULL),
	(115,16,'Dana Dolly',1,0,NULL),
	(116,16,'Kessler CineSlider',1,0,NULL),
	(117,16,'Steadicam',1,0,NULL),
	(118,16,'Glidecam',1,0,NULL),
	(119,16,'Hazer',1,0,NULL),
	(120,16,'Fan',1,0,NULL),
	(121,16,'Orange Sandbag',10,0,NULL),
	(122,16,'Black Sandbag',7,0,NULL),
	(123,16,'Boa Bag',4,0,NULL),
	(124,16,'Tool Bag',1,0,NULL),
	(125,16,'Joker Box',1,0,NULL),
	(126,16,'Empty Pelican 1510',2,0,NULL),
	(128,17,'Kit 1 - 744T',1,0,NULL),
	(129,17,'Kit 2 - 702T',1,0,NULL),
	(130,17,'Shotgun Kit - CMIT5U',1,0,NULL),
	(131,17,'Sennheiser Wireless Kit',4,0,NULL),
	(132,17,'Sennheiser Mic Cube',1,0,''),
	(133,17,'Sennheiser ME64 Kit',1,0,NULL),
	(134,17,'CMC6U Mic',1,0,NULL),
	(135,17,'Battery - LP-E6',19,0,NULL),
	(136,17,'Battery - C100',8,0,'this thing sucks');

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
	(1,'Cameras'),
	(2,'Lenses'),
	(7,'Camera Support'),
	(8,'Camera Accessories'),
	(9,'Lighting'),
	(16,'Grip'),
	(17,'Audio');

/*!40000 ALTER TABLE `gear_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `pkg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`pkg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;

INSERT INTO `packages` (`pkg_id`, `title`, `description`)
VALUES
	(7,'All Audio','All of the audio things are in here.');

/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `packages_gear` WRITE;
/*!40000 ALTER TABLE `packages_gear` DISABLE KEYS */;

INSERT INTO `packages_gear` (`pkg_gear_id`, `gear_id`, `pkg_id`)
VALUES
	(121,136,7),
	(122,135,7),
	(123,134,7),
	(124,128,7),
	(125,129,7),
	(126,133,7),
	(127,132,7),
	(128,131,7),
	(129,130,7);

/*!40000 ALTER TABLE `packages_gear` ENABLE KEYS */;
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
	(3,2,1),
	(4,2,2),
	(6,4,1),
	(7,4,3),
	(8,5,1),
	(9,5,3);

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
	(2,'jakedawkins','Jake Dawkins','e144c9a7b36ccc7308b88e4b4f2009481da6e33e9833e91009abe6507719ccb27','dawkinsjh@gmail.com','7245cc23f1d3593dd1691573d2b464e7',1437159856,0,1,'Site Owner',1437159856,1447689705),
	(4,'jackson','jackson','fa195fe8ae2fb379a69ae6b323c7e6a1d50483e181042181cd948bad3999db466','jacksod@clemson.edu','dedb4890cd916bc4197df36fc81e17fa',1442512203,0,1,'New Member',1442512203,1447688671),
	(5,'mommy','Mommy','76d4bd0f82e3dabe41c0a73d2b27c19e4e7b2a86e5a2d0884f209768d4148c070','123@232.com','8af6c1ec76245b6bea6e37abdb1ddf17',1447689669,0,1,'New Member',1447689669,1447689675);

/*!40000 ALTER TABLE `uc_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
