<?php

/**
 * @file _app.AbAjaxImpl.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once APP_AB_LIB . '_app.Ab.php';

require_once APP_AB_LIB . 'class.AbConfig.php';
require_once APP_AB_LIB . 'class.AbCfgFactory.php';

/**
 * Ajax server implementation for Address Book application
 */
class AbAjaxImpl extends Ab
{
	public function exec ( )
	{
		switch ( $_POST['action'] )
		{
			/**
			 * Handling of requests from CDES client code.
			 */
			case 'cdes':
				$this->handleCdes( AbConfig::T_ABCTX, AbCfgFactory::getCfg( 'usr.lst.Contexts' ) );
			break;
		}
	}
}

?>