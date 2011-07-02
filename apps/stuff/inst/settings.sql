--- Install global user settings for Stuff application. For rules applying here
--- read comments in solution settings.sql file.
--- {$__2} app namespace
--- {$__3} solution namespace

--- Lifegoals defaults, box: 0=Sd, 1=all except Ar, 2=all.
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.goals.on", `value` = "0";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.goals.box", `value` = "0";

--- CPE form textarea height.
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.ta.h.cpe", `value` = "160";

--- Algorithm for tab fold count information.
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.alg", `value` = "hofstadter";

--- Time preset instances configuration (for display in CPE form).
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.cpe.times", `value` = "5";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.cpe.sample", `value` = "30";

--- Default values for boxes lists.
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Schedule", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:9:\"timeframe\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Projects", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:4:\"name\";s:1:\"d\";s:3:\"ASC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.All", `value` = "a:9:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;s:1:\"b\";s:3:\"All\";s:1:\"f\";s:3:\"All\";s:1:\"c\";i:0;s:1:\"y\";s:4:\"list\";s:1:\"s\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Inbox", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Na", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Wf", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Sd", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Ar", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:8:\"recorded\";s:1:\"d\";s:4:\"DESC\";s:1:\"p\";i:1;}";
INSERT INTO `{$__1}` SET `scope` = "G", `ns` = "{$__2}", `key` = "usr.lst.Contexts", `value` = "a:4:{s:1:\"k\";s:0:\"\";s:1:\"o\";s:4:\"name\";s:1:\"d\";s:3:\"ASC\";s:1:\"p\";i:1;}";
