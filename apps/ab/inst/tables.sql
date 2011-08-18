
--- @file tables.sql
--- @author giorno
--- @package GTD
--- @subpackage AB
--- @license Apache License, Version 2.0, see LICENSE file
---
--- Script installing database tables specific for AddressBook application.

--
-- Table structure for table `addrbook`
--

CREATE TABLE IF NOT EXISTS `addrbook` (
  `uid` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `scheme` enum('pers','comp','dep') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_addresses`
--

CREATE TABLE IF NOT EXISTS `addrbook_addresses` (
  `id` bigint(20) NOT NULL,
  `desc` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `addr1` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `addr2` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `phones` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `faxes` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_comp`
--

CREATE TABLE IF NOT EXISTS `addrbook_comp` (
  `id` bigint(20) NOT NULL,
  `disp_cust` tinyint(1) NOT NULL,
  `disp_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `contexts` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_tags`
--

CREATE TABLE IF NOT EXISTS `addrbook_tags` (
  `UID` bigint(20) NOT NULL,
  `CID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `scheme` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`CID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_numbers`
--

CREATE TABLE IF NOT EXISTS `addrbook_numbers` (
  `id` bigint(20) NOT NULL,
  `type` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_pers`
--

CREATE TABLE IF NOT EXISTS `addrbook_pers` (
  `id` bigint(20) NOT NULL,
  `disp_predef` tinyint(1) NOT NULL,
  `disp_cust` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `disp_fmt` int(2) NOT NULL,
  `nick` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `titles` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `another_names` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `sec_surname` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `another_surnames` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `birthday` tinyint(1) NOT NULL,
  `birthday_date` date NOT NULL,
  `contexts` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addrbook_index`
--

CREATE TABLE IF NOT EXISTS `addrbook_index` (
  `id` bigint(20) NOT NULL,
  `display` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `numbers` text COLLATE utf8_unicode_ci NOT NULL,
  `contexts` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`),
  FULLTEXT KEY `display` (`display`),
  FULLTEXT KEY `comment` (`comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addrbook`
--
ALTER TABLE `addrbook`
  ADD CONSTRAINT `addrbook_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addrbook_addresses`
--
ALTER TABLE `addrbook_addresses`
  ADD CONSTRAINT `addrbook_addresses_ibfk_1` FOREIGN KEY (`id`) REFERENCES `addrbook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addrbook_comp`
--
ALTER TABLE `addrbook_comp`
  ADD CONSTRAINT `addrbook_comp_ibfk_1` FOREIGN KEY (`id`) REFERENCES `addrbook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addrbook_tags`
--
ALTER TABLE `addrbook_tags`
  ADD CONSTRAINT `addrbook_tags_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addrbook_numbers`
--
ALTER TABLE `addrbook_numbers`
  ADD CONSTRAINT `addrbook_numbers_ibfk_1` FOREIGN KEY (`id`) REFERENCES `addrbook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addrbook_pers`
--
ALTER TABLE `addrbook_pers`
  ADD CONSTRAINT `addrbook_pers_ibfk_1` FOREIGN KEY (`id`) REFERENCES `addrbook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
