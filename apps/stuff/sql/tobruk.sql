---
--- Changes in version Tobruk against previous version (Borodino)
---

CREATE TABLE `tStuffGoals` (
  `SID` bigint(20) NOT NULL,
  `weight` int(2) NOT NULL,
  KEY `SID` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `tStuffGoals`
  ADD CONSTRAINT `tStuffGoals_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `tStuffInbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE;