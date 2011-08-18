---
--- Changes in version Tobruk against previous version (Borodino)
---

CREATE TABLE `stuff_goals` (
  `SID` bigint(20) NOT NULL,
  `weight` int(2) NOT NULL,
  KEY `SID` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `stuff_goals`
  ADD CONSTRAINT `stuff_goals_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `stuff_inbox` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE;