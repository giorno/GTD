<?php

/**
 * @file _vcmp_perse.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/_vcmp_comp.php';

require_once APP_AB_LIB . 'uicmp/_uicmp_perse_frm.php';

/**
 * Virtual component building person-class contact editor.
 */
class _vcmp_perse extends _vcmp_comp
{
	/**
	 * Identifier used to generate HTML ID's.
	 * 
	 * @var string 
	 */
	protected $id = NULL;
	
	/**
	 * Person editor form UICMP component instance.
	 * 
	 * @var _uicmp_perse_frm 
	 */
	protected $frm = NULL;
	
	/**
	 * Localization messages for UICMP components.
	 * 
	 * @var array 
	 */
	protected $messages = NULL;
	
	public function __construct( &$parent, $id, $url, $params, $messages )
	{
		parent::__construct( $parent );
		$this->id		= $id;
		$this->messages	= $messages;
		$this->setAjaxProperties( $url, $params );
		
		$this->parent->getHead( )->add( new _uicmp_title( $this->parent, $this->parent->getId( ) . '.Title', $this->messages['title'] ) );
		$this->frm = new _uicmp_perse_frm( $this->parent->getBody( ), $this->id . '.Frm' );
			$this->parent->getBody( )->add( $this->frm );
		
		$buttons = new _uicmp_buttons( $this->parent->getHead( ), $this->parent->getHead( )->getId( ) . '.Buttons' );
			$buttons->add( new _uicmp_gi( $buttons, $buttons->getId( ) . '.Back', _uicmp_gi::IT_A, $this->messages['bt_back'], $this->parent->getLayoutJsVar( ) . '.back( );', '_uicmp_gi_back' ) );
			$buttons->add( new _uicmp_gi( $buttons, $buttons->getId( ) . '.S1', _uicmp_gi::IT_TXT, '|' ) );
			$this->parent->getHead( )->add( $buttons );
	}
	
	/**
	 * Read access to form's Javascript variable name.
	 * 
	 * @return <string>
	 */
	public function getJsVar() { return $this->frm->getJsVar( ); }
	
	public function generateJs ( )
	{
		$requirer = $this->frm->getRequirer( );
		if ( !is_null( $requirer ) )
		{
			/*$requirer->call( _uicmp_layout::RES_JS, array( $requirer->getRelative( ) . '3rd/XMLWriter-1.0.0-min.js' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_JS, array( $requirer->getRelative( ) . '3rd/base64.js' , __CLASS__ ) );*/
			$requirer->call( _uicmp_layout::RES_JS, array( 'inc/ab/uicmp.js' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_CSS, array( 'inc/ab/uicmp.css' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_JSPLAIN, 'var ' . $this->frm->getJsVar( ) . ' = new _uicmp_perse( ' . $this->parent->getLayoutJsVar( ) . ', \'' . $this->parent->getHtmlId( ) . '\' );' );
		}
	}
}

?>