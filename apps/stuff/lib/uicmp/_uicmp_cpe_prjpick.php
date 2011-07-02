<?php

/**
 * @file _uicmp_cpe_prjpick.php
 * @author giorno
 *
 * Project picker dialog rendered on SkyDome widget. It provides same features
 * as _uicmp_tab.
 */

require_once CHASSIS_LIB . 'uicmp/_uicmp_tab.php';

class _uicmp_cpe_prjpick extends _uicmp_tab
{
	/**
	 * Path to content of SkyDome dialog. Real tab renderer.
	 * 
	 * @var <string>
	 */
	protected $content = NULL;

	public function  __construct ( &$parent, $id )
	{
		parent::__construct( $parent, $id );
		$this->content	= parent::getRenderer( );
		$this->renderer	= APP_STUFF_UI . 'uicmp/cpe_prjpick.html';
		$this->jsPrefix	= '_uicmp_stuff_prjpick_i_';
		$this->show( );
	}

	public function getContent ( ) { return $this->content; }
}

?>