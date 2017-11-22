-- phpMyAdmin SQL Dump
-- version 4.3.7
-- http://www.phpmyadmin.net
--
-- Host: 10.123.0.133:3307
-- Generation Time: Apr 10, 2017 at 10:57 PM
-- Server version: 5.7.15
-- PHP Version: 5.4.45-0+deb7u4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gullydsm_wdv341`
--

-- --------------------------------------------------------

--
-- Table structure for table `wdv341_events`
--

CREATE TABLE IF NOT EXISTS `wdv341_events` (
  `event_id` int(11) NOT NULL,
  `event_name` text COLLATE utf8_unicode_ci NOT NULL,
  `event_description` text COLLATE utf8_unicode_ci NOT NULL,
  `event_presenter` text COLLATE utf8_unicode_ci NOT NULL,
  `event_day` date NOT NULL,
  `event_time` time NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wdv341_events`
--

INSERT INTO `wdv341_events` (`event_id`, `event_name`, `event_description`, `event_presenter`, `event_day`, `event_time`) VALUES
(1, 'PHP Class', '', '', '0000-00-00', '00:00:00'),
(2, 'Javascript Class', '', '', '0000-00-00', '00:00:00'),
(9, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '13:30:00'),
(4, 'HTML Class', 'XHTML, HTML and CSS', '', '0000-00-00', '00:00:00'),
(5, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '00:00:00'),
(6, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '00:00:00'),
(7, 'HTML Class', 'XHTML, HTML and CSS', '', '0000-00-00', '00:00:00'),
(8, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '13:30:00'),
(10, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '13:30:00'),
(11, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '13:30:00'),
(12, 'HTML Class', 'XHTML, HTML and CSS', '', '2014-09-18', '13:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wdv341_events`
--
ALTER TABLE `wdv341_events`
  ADD PRIMARY KEY (`event_id`), ADD UNIQUE KEY `event_id` (`event_id`), ADD UNIQUE KEY `event_id_3` (`event_id`), ADD KEY `event_id_2` (`event_id`), ADD KEY `event_id_4` (`event_id`), ADD KEY `event_id_5` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wdv341_events`
--
ALTER TABLE `wdv341_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
