-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 11, 2016 at 10:01 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `track_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp` bigint(11) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `speed` float NOT NULL,
  `bearing` float NOT NULL,
  `altitude` float NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8074 ;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`index`, `id`, `time`, `timestamp`, `lat`, `lon`, `speed`, `bearing`, `altitude`, `type`, `info`) VALUES
(8072, 951342, '2016-04-11 02:59:46', 1460343574, 12.9304166, 100.879819, 0, 0, 0, 0, ''),
(8073, 951342, '2016-04-11 03:00:46', 1460343634, 12.9304096, 100.879821, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `last_lat` double NOT NULL,
  `last_lon` double NOT NULL,
  `info` text NOT NULL,
  `traccar_id` bigint(20) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `last_lat`, `last_lon`, `info`, `traccar_id`, `timestamp`, `type`) VALUES
(1, 'Scotty', 'test', 12.9304373, 100.8798317, 'Scotty is a cool dude.', 206648, 1460312949, 1),
(2, 'acer', 'test', 12.9304096, 100.879821, 'the white acer android phone used in test', 951342, 1460343634, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
