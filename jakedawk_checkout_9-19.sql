-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 19, 2015 at 10:43 AM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jakedawk_checkout`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE IF NOT EXISTS `checkouts` (
  `co_id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `co_start` datetime NOT NULL,
  `co_end` datetime NOT NULL,
  `description` varchar(256) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `checkouts`
--

INSERT INTO `checkouts` (`co_id`, `title`, `person_id`, `co_start`, `co_end`, `description`) VALUES
(1, 'test', 2, '2015-06-24 23:32:51', '2015-06-30 23:32:51', 'this is a test'),
(17, 'more updates', 2, '2015-10-15 01:00:00', '2015-10-15 03:00:00', 'just wanna check out some fun stuff.'),
(18, 'my new checkout!', 2, '2012-12-12 01:00:00', '2012-12-13 01:00:00', 'This is a test of the full system'),
(19, 'test', 2, '2015-07-15 12:00:00', '2015-07-17 12:00:00', 'this is a test'),
(21, 'Test', 2, '2015-12-12 00:00:01', '2015-12-12 00:00:02', 'Bduskasbs'),
(22, 'My secure test', 2, '2015-06-01 00:00:01', '2015-06-02 00:00:01', 'this is an awesome description'),
(26, 'my tester1', 2, '2015-10-12 00:00:01', '2015-10-13 00:00:01', 'fdhsaf\r\ndfs\r\nds\r\nfdshjfklsdghfjdkh'),
(27, '4 Type Test', 2, '2015-09-20 00:00:01', '2015-09-21 00:00:01', 'fdsg sjdk fd df ds da'),
(28, '5 Type Test', 2, '2015-09-22 00:00:01', '2015-09-23 00:00:01', 'ghjfsklgsfhd'),
(29, 'Test', 2, '2015-09-28 00:00:01', '2105-09-29 00:00:01', 'fdhjgklh gjkfbvjkdf'),
(30, 'Long Item name Test', 2, '2015-09-14 00:15:00', '2015-09-18 15:00:00', 'Thfhasdjkgdh');

-- --------------------------------------------------------

--
-- Table structure for table `co_gear`
--

CREATE TABLE IF NOT EXISTS `co_gear` (
  `co_gear_id` int(11) unsigned NOT NULL,
  `gear_id` int(11) unsigned NOT NULL,
  `co_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `co_gear`
--

INSERT INTO `co_gear` (`co_gear_id`, `gear_id`, `co_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(57, 1, 17),
(58, 4, 17),
(59, 3, 17),
(60, 1, 18),
(61, 2, 18),
(62, 1, 19),
(63, 4, 19),
(66, 4, 21),
(67, 7, 21),
(68, 1, 22),
(69, 3, 22),
(70, 4, 22),
(71, 7, 22),
(80, 1, 26),
(81, 2, 26),
(82, 4, 26),
(83, 7, 26),
(84, 12, 26),
(85, 13, 26),
(86, 1, 27),
(87, 3, 27),
(88, 7, 27),
(89, 12, 27),
(90, 13, 27),
(91, 14, 27),
(92, 18, 27),
(93, 1, 28),
(94, 3, 28),
(95, 4, 28),
(96, 7, 28),
(97, 12, 28),
(98, 14, 28),
(99, 17, 28),
(100, 19, 28),
(101, 20, 28),
(102, 11, 29),
(103, 17, 29),
(104, 19, 29),
(105, 31, 30),
(106, 19, 30),
(107, 58, 30),
(108, 64, 30),
(109, 73, 30),
(110, 77, 30),
(111, 87, 30),
(112, 105, 30),
(113, 131, 30),
(114, 133, 30);

-- --------------------------------------------------------

--
-- Table structure for table `gear`
--

CREATE TABLE IF NOT EXISTS `gear` (
  `gear_id` int(11) unsigned NOT NULL,
  `gear_type_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gear`
--

INSERT INTO `gear` (`gear_id`, `gear_type_id`, `name`) VALUES
(1, 1, 'cam1'),
(2, 1, 'cam2'),
(3, 1, 'cam3'),
(4, 1, 'cam4'),
(7, 2, 'my item 1'),
(11, 1, 'Tester'),
(12, 7, 'tripod1'),
(13, 7, 'tripod2'),
(14, 8, 'C100 Z-Finder'),
(17, 8, 'Slate'),
(18, 8, '5D Z-Finder'),
(19, 9, 'Kino-Flo Diva 200 Kit'),
(20, 9, 'Kino-Flo Diva 400 Kit'),
(21, 1, 'RED EPIC'),
(22, 1, 'Canon 5D'),
(23, 1, 'Canon 7D'),
(24, 1, 'GoPro Hero 3'),
(25, 1, 'GoPro Hero 3+'),
(26, 8, 'Samsung 24&quot; TV'),
(27, 8, 'AC7'),
(28, 8, 'DP7'),
(29, 8, 'DP4'),
(30, 8, 'Paralinx Wireless'),
(31, 8, 'RedRock Wireless Follow Focus'),
(36, 7, 'Hi-Hat Tripod'),
(37, 7, 'Large Gitzo'),
(38, 7, 'Miller DS10'),
(39, 7, 'Miller DS20'),
(40, 7, 'Manfrotto w/501'),
(41, 7, 'Manfrotto w/701'),
(42, 7, 'Manfrotto Monopod'),
(43, 7, 'Small Gitzo'),
(44, 2, '16-35mm'),
(45, 2, '24-105mm'),
(46, 2, '70-200mm'),
(47, 2, '70-200mm (2)'),
(48, 2, '24-70mm'),
(49, 2, '50mm 1.4'),
(50, 2, '50mm 1.2'),
(51, 2, '100mm 2.8'),
(52, 2, 'Rokinon 16mm'),
(53, 2, 'Rokinon 24mm'),
(54, 2, 'Rokinon 35mm'),
(55, 2, 'Rokinon 50mm'),
(56, 2, 'Rokinon 85mm'),
(57, 9, 'Kino-Flo Diva 200 Kit'),
(58, 9, 'Kino-Flo Diva 400 Kit'),
(59, 9, 'Kino-Flo Gaffer Kit'),
(60, 9, 'Kino-Flo Single Kit'),
(61, 9, 'Ellipsoidal'),
(62, 9, '1K Par'),
(63, 9, 'Arri 150'),
(64, 9, 'Arri 300'),
(65, 9, 'Arri 650'),
(66, 9, 'Joker 400'),
(67, 9, 'Joker 800'),
(68, 9, 'Triolet'),
(69, 9, 'Alzo LED Kit'),
(70, 9, 'Chimera Medium Pancake'),
(71, 9, 'Chimera Large Pancake'),
(72, 9, 'Chimera Small Softbox'),
(73, 9, 'Chimera Xtra Small Softbox for Joker 400'),
(74, 9, 'Chimera Large Softbox with Egg Crate'),
(75, 9, '4x4 Floppy Cutters'),
(76, 9, '4x4 Cutter'),
(77, 9, '36x36 Cutter'),
(78, 9, '4x4 Silk'),
(79, 9, '4x4 China Silk'),
(80, 9, '4x4 Double Scrim'),
(81, 9, '4x4 Single Scrim'),
(82, 9, '30x36 Cutter'),
(83, 9, '24x36 Cutter'),
(84, 9, '18x24 Cutter'),
(85, 9, '18x24 Silk'),
(86, 9, '18x24 Frame'),
(87, 9, '18x24 Modifier Kit'),
(88, 9, '8x8 Frame'),
(89, 9, '12x12 Frame'),
(90, 9, '8x8 Ultrabounce/Flag'),
(91, 9, '8x8 Silent Full Grid'),
(92, 9, '8x8 Silk'),
(93, 9, '8x8 Blue/Green Screen'),
(94, 9, '12x12 Ultrabounce/Flag'),
(95, 9, '12x12 Silk'),
(96, 9, '12x12 Quarter Stop Silk'),
(97, 9, '12x12 Blue/Green Screen'),
(98, 9, 'Matthboard'),
(99, 9, 'Sunswatter'),
(100, 9, '4x4 Collapsible Square Bounce'),
(101, 9, 'Applebox Sets'),
(102, 9, '1K Dimmer'),
(103, 9, '600W Dimmer'),
(104, 16, 'Easy Rig'),
(105, 16, 'RedRock Shoulder Rig'),
(106, 16, 'Stand - Junior Low Boy 2XR'),
(107, 16, 'C-Stand'),
(108, 16, 'Stand - Low Boy'),
(109, 16, 'Stand - Slider'),
(110, 16, 'Stand - Rolling Combo'),
(111, 16, 'Stand - Combo 3XR'),
(112, 16, 'Stand - Combo 2XR'),
(113, 16, 'Avenger Junior Boom'),
(114, 16, 'Jib'),
(115, 16, 'Dana Dolly'),
(116, 16, 'Kessler CineSlider'),
(117, 16, 'Steadicam'),
(118, 16, 'Glidecam'),
(119, 16, 'Hazer'),
(120, 16, 'Fan'),
(121, 16, 'Orange Sandbag'),
(122, 16, 'Black Sandbag'),
(123, 16, 'Boa Bag'),
(124, 16, 'Tool Bag'),
(125, 16, 'Joker Box'),
(126, 16, 'Empty Pelican 1510'),
(128, 17, 'Kit 1 - 744T'),
(129, 17, 'Kit 2 - 702T'),
(130, 17, 'Shotgun Kit - CMIT5U'),
(131, 17, 'Sennheiser Wireless Kit'),
(132, 17, 'Sennheiser Mic Cube'),
(133, 17, 'Sennheiser ME64 Kit'),
(134, 17, 'CMC6U Mic'),
(135, 17, 'Battery - LP-E6'),
(136, 17, 'Battery - C100');

-- --------------------------------------------------------

--
-- Table structure for table `gear_types`
--

CREATE TABLE IF NOT EXISTS `gear_types` (
  `gear_type_id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gear_types`
--

INSERT INTO `gear_types` (`gear_type_id`, `type`) VALUES
(1, 'Cameras'),
(2, 'Lenses'),
(7, 'Camera Support'),
(8, 'Camera Accessories'),
(9, 'Lighting'),
(16, 'Grip'),
(17, 'Audio');

-- --------------------------------------------------------

--
-- Table structure for table `uc_configuration`
--

CREATE TABLE IF NOT EXISTS `uc_configuration` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_configuration`
--

INSERT INTO `uc_configuration` (`id`, `name`, `value`) VALUES
(1, 'website_name', 'Checkout'),
(2, 'website_url', 'http://dev.jakedawkins.com/'),
(3, 'email', 'hello@jakedawkins.com'),
(4, 'activation', 'false'),
(5, 'resend_activation_threshold', '0'),
(6, 'language', 'models/languages/en.php'),
(7, 'template', 'models/site-templates/default.css');

-- --------------------------------------------------------

--
-- Table structure for table `uc_pages`
--

CREATE TABLE IF NOT EXISTS `uc_pages` (
  `id` int(11) NOT NULL,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_pages`
--

INSERT INTO `uc_pages` (`id`, `page`, `private`) VALUES
(1, 'account.php', 1),
(2, 'activate-account.php', 0),
(3, 'admin_configuration.php', 1),
(4, 'admin_page.php', 1),
(5, 'admin_pages.php', 1),
(6, 'admin_permission.php', 1),
(7, 'admin_permissions.php', 1),
(8, 'admin_user.php', 1),
(9, 'admin_users.php', 1),
(10, 'forgot-password.php', 0),
(11, 'index.php', 0),
(13, 'login.php', 0),
(14, 'logout.php', 1),
(15, 'register.php', 0),
(16, 'resend-activation.php', 0),
(17, 'user_settings.php', 1),
(19, 'checkout.php', 1),
(20, 'checkouts.php', 1),
(21, 'inventory.php', 1),
(25, 'edit-checkout.php', 1),
(26, 'edit-gear-types.php', 1),
(27, 'new-checkout.php', 1),
(28, 'new-gear.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `uc_permissions`
--

CREATE TABLE IF NOT EXISTS `uc_permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_permissions`
--

INSERT INTO `uc_permissions` (`id`, `name`) VALUES
(1, 'New Member'),
(2, 'Administrator'),
(3, 'Team');

-- --------------------------------------------------------

--
-- Table structure for table `uc_permission_page_matches`
--

CREATE TABLE IF NOT EXISTS `uc_permission_page_matches` (
  `id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_permission_page_matches`
--

INSERT INTO `uc_permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
(1, 1, 1),
(2, 1, 14),
(3, 1, 17),
(4, 2, 1),
(5, 2, 3),
(6, 2, 4),
(7, 2, 5),
(8, 2, 6),
(9, 2, 7),
(10, 2, 8),
(11, 2, 9),
(12, 2, 14),
(13, 2, 17),
(27, 2, 21),
(28, 3, 21),
(30, 2, 19),
(31, 3, 19),
(33, 2, 20),
(44, 2, 28),
(45, 3, 28),
(46, 2, 28),
(47, 3, 28),
(48, 2, 27),
(49, 3, 27),
(50, 2, 26),
(51, 3, 26),
(52, 2, 25),
(53, 3, 25),
(54, 3, 20),
(55, 3, 17),
(56, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `uc_users`
--

CREATE TABLE IF NOT EXISTS `uc_users` (
  `id` int(11) NOT NULL,
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
  `last_sign_in_stamp` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_users`
--

INSERT INTO `uc_users` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `title`, `sign_up_stamp`, `last_sign_in_stamp`) VALUES
(2, 'jakedawkins', 'Jake Dawkins', 'e144c9a7b36ccc7308b88e4b4f2009481da6e33e9833e91009abe6507719ccb27', 'dawkinsjh@gmail.com', '7245cc23f1d3593dd1691573d2b464e7', 1437159856, 0, 1, 'Site Owner', 1437159856, 1442677190),
(4, 'jackson', 'jackson', 'fa195fe8ae2fb379a69ae6b323c7e6a1d50483e181042181cd948bad3999db466', 'jacksod@clemson.edu', 'dedb4890cd916bc4197df36fc81e17fa', 1442512203, 0, 1, 'New Member', 1442512203, 1442581328);

-- --------------------------------------------------------

--
-- Table structure for table `uc_user_permission_matches`
--

CREATE TABLE IF NOT EXISTS `uc_user_permission_matches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_user_permission_matches`
--

INSERT INTO `uc_user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
(1, 1, 2),
(2, 1, 1),
(3, 2, 1),
(4, 2, 2),
(6, 4, 1),
(7, 4, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`co_id`), ADD KEY `rental_person` (`person_id`);

--
-- Indexes for table `co_gear`
--
ALTER TABLE `co_gear`
  ADD PRIMARY KEY (`co_gear_id`), ADD KEY `rental_gear` (`gear_id`), ADD KEY `rental_gear_rental` (`co_id`);

--
-- Indexes for table `gear`
--
ALTER TABLE `gear`
  ADD PRIMARY KEY (`gear_id`), ADD KEY `gear_type` (`gear_type_id`);

--
-- Indexes for table `gear_types`
--
ALTER TABLE `gear_types`
  ADD PRIMARY KEY (`gear_type_id`);

--
-- Indexes for table `uc_configuration`
--
ALTER TABLE `uc_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_pages`
--
ALTER TABLE `uc_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_permissions`
--
ALTER TABLE `uc_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_permission_page_matches`
--
ALTER TABLE `uc_permission_page_matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_users`
--
ALTER TABLE `uc_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_user_permission_matches`
--
ALTER TABLE `uc_user_permission_matches`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `co_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `co_gear`
--
ALTER TABLE `co_gear`
  MODIFY `co_gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=124;
--
-- AUTO_INCREMENT for table `gear`
--
ALTER TABLE `gear`
  MODIFY `gear_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=137;
--
-- AUTO_INCREMENT for table `gear_types`
--
ALTER TABLE `gear_types`
  MODIFY `gear_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `uc_configuration`
--
ALTER TABLE `uc_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `uc_pages`
--
ALTER TABLE `uc_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `uc_permissions`
--
ALTER TABLE `uc_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `uc_permission_page_matches`
--
ALTER TABLE `uc_permission_page_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `uc_users`
--
ALTER TABLE `uc_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `uc_user_permission_matches`
--
ALTER TABLE `uc_user_permission_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
ADD CONSTRAINT `checkout_person` FOREIGN KEY (`person_id`) REFERENCES `uc_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `co_gear`
--
ALTER TABLE `co_gear`
ADD CONSTRAINT `rental_gear` FOREIGN KEY (`gear_id`) REFERENCES `gear` (`gear_id`) ON DELETE CASCADE,
ADD CONSTRAINT `rental_gear_rental` FOREIGN KEY (`co_id`) REFERENCES `checkouts` (`co_id`) ON DELETE CASCADE;

--
-- Constraints for table `gear`
--
ALTER TABLE `gear`
ADD CONSTRAINT `gear_types` FOREIGN KEY (`gear_type_id`) REFERENCES `gear_types` (`gear_type_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
