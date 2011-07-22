<?php

/**
 * @file class.Installer.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once N7_SOLUTION_LIB . 'class.AppInstaller.php';

require_once N7_SOLUTION_APPS . 'ab/_cfg.php';
require_once APP_AB_LIB . 'class.AbCfgFactory.php';

/**
 * Installer for AddressBook app. Instantiated by N7 AI app.
 */
class Installer extends AppInstaller
{
	public function __construct( ) { parent::__construct( 'ab', N7_SOLUTION_ID . AbSettings::NS_EXT ); }
}

?>