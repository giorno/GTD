<?php

/**
 * @file _uicmp_cpe_frm.php
 * @author giorno
 * @subpackage Stuff
 *
 * CPE form component.
 */

require_once CHASSIS_LIB . 'uicmp/_uicmp_comp.php';

class _uicmp_cpe_frm extends _uicmp_comp
{
	/**
	 * Textarea height.
	 *
	 * @var <int>
	 */
	protected $taH = 0;

	/**
	 * Array of most used time presets.
	 *
	 * @var <array> 
	 */
	protected $presets = NULL;
	
	public function  __construct( &$parent, $id, $tah, $presets )
	{
		parent::__construct( $parent, $id );
		$this->type		= __CLASS__;
		$this->renderer	= APP_STUFF_UI . 'uicmp/cpe.html';
		$this->taH		= $tah;
		$this->jsPrefix	= '_uicmp_stuff_cpe_i';
		$this->presets	= $presets;
	}

	/**
	 * Read interface for initial (saved) textarea height.
	 *
	 * @return <int>
	 */
	public function getTaH ( ) { return $this->taH; }

	/**
	 * Read interface for array of most used time presets.
	 * @return <array>
	 */
	public function getPresets ( ) { return $this->presets; }

	/**
	 * Dummy implementation to conform abstract parent.
	 */
	public function generateJs ( ) { }
}

?>