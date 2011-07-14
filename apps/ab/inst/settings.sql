
--- @file settings.sql
--- @author giorno
--- @package GTD
--- @subpackage AB
--- @license Apache License, Version 2.0, see LICENSE file

--- Install global user settings for Address Book application. For rules
--- applying here read comments in solution settings.sql file.
--- {$__1} table name
--- {$__2} solution settings namespace

--- Person editor form textarea height.
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.ta.h.perse", `value` = "160";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.ta.h.orge", `value` = "160";

--- Default values for boxes lists.
--- INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.All", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:9:\"timeframe\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Contexts", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:4:\"name\";s:1:\"d\";s:3:\"ASC\";s:1:\"p\";i:1;}";
