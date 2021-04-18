-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2019 at 12:18 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `directoryplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `rel_favorites`
--

CREATE TABLE IF NOT EXISTS `rel_favorites` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT '0',
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rel_favorites`
--

ALTER TABLE `rel_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rel_favorites`
--
ALTER TABLE `rel_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `rel_favorites`
--
ALTER TABLE `rel_favorites`
  ADD CONSTRAINT `rel_favorites_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_favorites_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `contact_msgs` ADD `recipient_id` INT(11) NULL AFTER `place_id`;
ALTER TABLE `contact_msgs` CHANGE `place_id` `place_id` INT(11) NULL;
