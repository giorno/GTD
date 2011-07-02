---
--- Changes in version Borodino against previous version (Ardennes)
---

CREATE TABLE `tStuffContexts` (
  `UID` bigint(20) NOT NULL,
  `CID` bigint(20) NOT NULL auto_increment,
  `name` varchar(1024) collate utf8_unicode_ci NOT NULL,
  `scheme` char(8) collate utf8_unicode_ci NOT NULL,
  `desc` varchar(1024) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`CID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

ALTER TABLE `tStuffContexts`
  ADD CONSTRAINT `tStuffContexts_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `tUsers` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tStuffBoxes` ADD `contexts` TEXT NOT NULL ;
ALTER TABLE `tStuffInbox` ADD `contexts` TEXT NOT NULL ;
ALTER TABLE `tStuffBoxes` CHANGE `box` `box` ENUM( 'Inbox', 'Na', 'Wf', 'Sd', 'Ar' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Na'
UPDATE `tStuffBoxes` SET `box`="Ar" WHERE `box` = ""

INSERT INTO `tStuffBoxes`
	(
		SELECT `SID` , `UID` , '0' AS sequence, 'Inbox' AS box, `recorded` , `name` , `place` , `desc` , `priority` , `dateSet` , `dateValue` , `timeSet` , `timeValue` , `contexts`
		FROM `tStuffInbox`
	)

ALTER TABLE `tStuffInbox` DROP `recorded`, DROP `name`, DROP `place`, DROP `desc`, DROP `priority`, DROP `dateSet`, DROP `dateValue`, DROP `timeSet`, DROP `timeValue`, DROP `contexts`;

