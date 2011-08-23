<?php

/**
 * @file _uicmp_orge_frm.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/uicmp.php';
require_once CHASSIS_LIB . 'uicmp/strings.php';

require_once APP_AB_LIB . 'uicmp/_uicmp_perse_frm.php';

/**
 * Organization-class contact editor form. Derived from Person-class editor to
 * code less.
 */
class _uicmp_orge_frm extends _uicmp_perse_frm
{	
	/**
	 * Constructor. Overriden.
	 * 
	 * @param _uicmp_body $parent parent element
	 * @param string $id identifier of the component
	 * @param array $messages localization messages
	 * @param int $tah height of comments textarea
	 */
	public function __construct ( &$parent, $id, $messages, $tah )
	{
		parent::__construct( $parent, $id, $messages, $tah );
		$this->jsPrefix	= '_uicmp_orge_frm';
		$this->renderer	= APP_AB_UI . 'uicmp/orge_frm.html';
	}
}

?>