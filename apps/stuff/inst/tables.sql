
--- @file tables.sql
--- @author giorno
--- @package GTD
--- @subpackage Stuff
--- @license Apache License, Version 2.0, see LICENSE file
---
--- Script installing database tables specific for Stuff application.

--
-- Table of entries.
--

CREATE TABLE IF NOT EXISTS `stuff_boxes` (
  `SID` bigint(20) NOT NULL,
  `UID` bigint(20) NOT NULL,
  `sequence` int(11) NOT NULL,
  `box` enum('Inbox','Na','Wf','Sd','Ar') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Na',
  `recorded` datetime NOT NULL,
  `name` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `place` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `dateSet` tinyint(4) NOT NULL,
  `dateValue` date NOT NULL,
  `timeSet` tinyint(4) NOT NULL,
  `timeValue` time NOT NULL,
  `contexts` text COLLATE utf8_unicode_ci NOT NULL,
  `flags` bigint(20) NOT NULL DEFAULT '0',
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `SID` (`SID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Labels.
--

CREATE TABLE IF NOT EXISTS `stuff_tags` (
  `UID` bigint(20) NOT NULL,
  `CID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `scheme` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`CID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Goals.
--

CREATE TABLE IF NOT EXISTS `stuff_goals` (
  `SID` bigint(20) NOT NULL,
  `weight` int(2) NOT NULL,
  KEY `SID` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Inbox.
--

CREATE TABLE IF NOT EXISTS `stuff_inbox` (
  `SID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UID` bigint(20) NOT NULL,
  `parent` bigint(20) NOT NULL,
  PRIMARY KEY (`SID`),
  KEY `UID` (`UID`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Projects.
--

CREATE TABLE IF NOT EXISTS `stuff_projects` (
  `SID` bigint(20) NOT NULL,
  `UID` bigint(20) NOT NULL,
  `name` varchar(1024) NOT NULL,
  UNIQUE KEY `SID` (`SID`),
  KEY `uid` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stuff_boxes`
--
ALTER TABLE `stuff_boxes`
  ADD CONSTRAINT `stuff_boxes_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stuff_boxes_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stuff_tags`
--
ALTER TABLE `stuff_tags`
  ADD CONSTRAINT `stuff_tags_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stuff_goals`
--
ALTER TABLE `stuff_goals`
  ADD CONSTRAINT `stuff_goals_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stuff_inbox`
--
ALTER TABLE `stuff_inbox`
  ADD CONSTRAINT `stuff_inbox_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stuff_projects`
--
ALTER TABLE `stuff_projects`
  ADD CONSTRAINT `stuff_projects_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stuff_projects_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;
