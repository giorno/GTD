--
-- Štruktúra tabuľky pre tabuľku `stuff_boxes`
--

CREATE TABLE `stuff_boxes` (
  `SID` bigint(20) NOT NULL,
  `UID` bigint(20) NOT NULL,
  `sequence` int(11) NOT NULL,
  `box` enum('Inbox','Na','Wf','Sd','Ar') collate utf8_unicode_ci NOT NULL default 'Na',
  `recorded` datetime NOT NULL,
  `name` varchar(1024) collate utf8_unicode_ci NOT NULL,
  `place` varchar(1024) collate utf8_unicode_ci NOT NULL,
  `desc` text collate utf8_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `dateSet` tinyint(4) NOT NULL,
  `dateValue` date NOT NULL,
  `timeSet` tinyint(4) NOT NULL,
  `timeValue` time NOT NULL,
  `contexts` text collate utf8_unicode_ci NOT NULL,
  KEY `SID` (`SID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `stuff_tags`
--

CREATE TABLE `stuff_tags` (
  `UID` bigint(20) NOT NULL,
  `CID` bigint(20) NOT NULL auto_increment,
  `name` varchar(1024) collate utf8_unicode_ci NOT NULL,
  `scheme` char(8) collate utf8_unicode_ci NOT NULL,
  `desc` varchar(1024) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`CID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `stuff_inbox`
--

CREATE TABLE `stuff_inbox` (
  `SID` bigint(20) NOT NULL auto_increment,
  `UID` bigint(20) NOT NULL,
  PRIMARY KEY  (`SID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

--
-- Štruktúra tabuľky pre tabuľku `stuff_goals`
--

CREATE TABLE `stuff_goals` (
  `SID` bigint(20) NOT NULL,
  `weight` int(2) NOT NULL,
  KEY `SID` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Obmedzenie pre tabuľku `stuff_boxes`
--
ALTER TABLE `stuff_boxes`
  ADD CONSTRAINT `stuff_boxes_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stuff_boxes_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `stuff_tags`
--
ALTER TABLE `stuff_tags`
  ADD CONSTRAINT `stuff_tags_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `stuff_inbox`
--
ALTER TABLE `stuff_inbox`
  ADD CONSTRAINT `stuff_inbox_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `stuff_goals`
--
ALTER TABLE `stuff_goals`
  ADD CONSTRAINT `stuff_goals_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE;

