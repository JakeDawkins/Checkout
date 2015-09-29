-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 29, 2015 at 08:00 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

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
(30, 'Long Item name Test', 2, '2015-09-14 00:15:00', '2015-09-18 15:00:00', 'Thfhasdjkgdh'),
(32, 'test', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'fdsahjkd'),
(33, 'jgk', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'ghj'),
(34, 'fdghdfghd', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'fghj'),
(35, '', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(36, 'dsafhjkl', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'fhsdalk'),
(37, 'dsafhjkl', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'fhsdalk'),
(38, 'sdfhjk', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'sd'),
(39, 'fds', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'lkjkljklsdjklfjdsklfjdskl'),
(40, 'hjgk', 2, '2015-01-01 01:00:00', '2015-01-02 01:00:00', 'dfshjfaksldhfjfhsdjkhfsjk'),
(41, 'hjkl', 2, '2015-01-01 01:00:00', '2015-01-03 01:00:00', 'ruheisfdj');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`co_id`), ADD KEY `rental_person` (`person_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `co_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
ADD CONSTRAINT `checkout_person` FOREIGN KEY (`person_id`) REFERENCES `uc_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
