<?php

/**
 * @file _uicmp_perse_frm.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/_uicmp_comp.php';
require_once CHASSIS_LIB . 'uicmp/_uicmp_strings.php';

/**
 * Person class contact editor form.
 */
class _uicmp_perse_frm extends _uicmp_comp
{
	/**
	 * Textarea height.
	 *
	 * @var int
	 */
	protected $taH = 0;
	
	/**
	 * Data subcomponent carrying localization messages for client side logic.
	 * 
	 * @var _uicmp_strings 
	 */
	protected $strings = NULL;
	
	/**
	 * Constructor.
	 * 
	 * @param _uicmp_body $parent parent element
	 * @param string $id identifier of the component
	 * @param array $messages localization messages
	 * @param int $tah height of comments textarea
	 */
	public function __construct ( &$parent, $id, $messages, $tah )
	{
		parent::__construct( $parent, $id );
		$this->jsPrefix	= '_uicmp_perse_frm';
		$this->renderer	= APP_AB_UI . 'uicmp/perse_frm.html';
		$this->taH		= $tah;
		$this->strings	= new _uicmp_strings( $this, $this->id . '.Strings', $messages['js'] );
	}
	
	/**
	 * Read interface for initial (saved) textarea height.
	 *
	 * @return int
	 */
	public function getTaH ( ) { return $this->taH; }
	
	/**
	 * Read interface for strings component.
	 * 
	 * @return _uicmp_strings 
	 */
	public function getStrings ( ) { return $this->strings; }
	
	/**
	 * Dummy implementation to conform abstract parent.
	 */
	public function generateJs ( ) { }
}

?>