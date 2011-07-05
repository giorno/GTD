
--- @file tables.sql
--- @author giorno
--- @package GTD
--- @subpackage AB
--- @license Apache License, Version 2.0, see LICENSE file
---
--- Script installing database tables specific for AddressBook application.

--
-- Table structure for table `tAddrBook`
--

CREATE TABLE IF NOT EXISTS `tAddrBook` (
  `uid` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `scheme` enum('pers','comp','dep') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `tAddrBookAddresses`
--

CREATE TABLE IF NOT EXISTS `tAddrBookAddresses` (
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
-- Table structure for table `tAddrBookCompanies`
--

CREATE TABLE IF NOT EXISTS `tAddrBookCompanies` (
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
-- Table structure for table `tAddrBookContexts`
--

CREATE TABLE IF NOT EXISTS `tAddrBookContexts` (
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
-- Table structure for table `tAddrBookNumbers`
--

CREATE TABLE IF NOT EXISTS `tAddrBookNumbers` (
  `id` bigint(20) NOT NULL,
  `type` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tAddrBookPersons`
--

CREATE TABLE IF NOT EXISTS `tAddrBookPersons` (
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
-- Table structure for table `tAddrBookSearchIndex`
--

CREATE TABLE IF NOT EXISTS `tAddrBookSearchIndex` (
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
-- Constraints for table `tAddrBook`
--
ALTER TABLE `tAddrBook`
  ADD CONSTRAINT `tAddrBook_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tAddrBookAddresses`
--
ALTER TABLE `tAddrBookAddresses`
  ADD CONSTRAINT `tAddrBookAddresses_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tAddrBook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tAddrBookCompanies`
--
ALTER TABLE `tAddrBookCompanies`
  ADD CONSTRAINT `tAddrBookCompanies_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tAddrBook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tAddrBookContexts`
--
ALTER TABLE `tAddrBookContexts`
  ADD CONSTRAINT `tAddrBookContexts_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tAddrBookNumbers`
--
ALTER TABLE `tAddrBookNumbers`
  ADD CONSTRAINT `tAddrBookNumbers_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tAddrBook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tAddrBookPersons`
--
ALTER TABLE `tAddrBookPersons`
  ADD CONSTRAINT `tAddrBookPersons_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tAddrBook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
