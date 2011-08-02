<?php

/**
 * @file _uicmp_cpe_prjpick.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/_uicmp_tab.php';

/**
 * Project picker dialog rendered on SkyDome widget. It provides same features
 * as _uicmp_tab.
 */
class _uicmp_cpe_prjpick extends _uicmp_tab
{
	/**
	 * Constructor. Specializes this implementation.
	 * 
	 * @param _uicmp_dlgs $parent parent component (dialogs layout)
	 * @param string $id identifier of the component
	 */
	public function  __construct ( &$parent, $id )
	{
		parent::__construct( $parent, $id );
		$this->renderer	= APP_STUFF_UI . 'uicmp/cpe_prjpick.html';
		$this->jsPrefix	= '_uicmp_stuff_prjpick_i_';
		$this->show( );
	}
}

?>