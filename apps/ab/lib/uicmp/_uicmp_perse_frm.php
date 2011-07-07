<?php

/**
 * @file _app.AbMainImpl.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/_uicmp_comp.php';

class _uicmp_perse_frm extends _uicmp_comp
{
	public function __construct ( &$parent, $id )
	{
		parent::__construct( $parent, $id );
		$this->jsPrefix	= '_uicmp_perse_frm';
		$this->renderer	= APP_AB_UI . 'uicmp/perse_frm.html';
	}
	
	public function generateJs ( )
	{
	}
}

?>