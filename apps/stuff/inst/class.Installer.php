<?php

/**
 * @file class.Installer.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once N7_SOLUTION_LIB . 'class.AppInstaller.php';

require_once N7_SOLUTION_APPS . 'stuff/_cfg.php';
require_once APP_STUFF_LIB . 'class.StuffCfgFactory.php';

/**
 * Installer for Stuff app. Instantiated by N7 AI app.
 */
class Installer extends AppInstaller
{
	public function __construct( ) { parent::__construct( 'stuff', N7_SOLUTION_ID . StuffSettings::NS_EXT ); }
}
		
?>