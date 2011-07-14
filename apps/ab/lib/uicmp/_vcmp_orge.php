<?php

/**
 * @file _vcmp_orge.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/_vcmp_comp.php';

require_once APP_AB_LIB . 'uicmp/_uicmp_orge_frm.php';
require_once APP_AB_LIB . 'uicmp/_vcmp_perse.php';

/**
 * Virtual component building Organization-class contact editor. Derived from
 * Person-class virtual component to code less.
 */
class _vcmp_orge extends _vcmp_perse
{	
	/**
	 * Constructor. Overriding parent constructor.
	 * 
	 * @param _uicmp_comp $parent parent component
	 * @param string $id identifier of the component
	 * @param string  $url Ajax request URL
	 * @param array $params Ajax request parameters
	 * @param array $messages localization messages for editor
	 * @param int $tah height of comments textarea
	 */
	public function __construct( &$parent, $id, $url, $params, $messages, $tah )
	{
		$this->parent	= $parent;		// bypassing call of constructors, this breaks inheritance
		$this->id		= $id;
		$this->messages	= $messages;
		$this->setAjaxProperties( $url, $params );
		
		$this->parent->getHead( )->add( $this->title = new _uicmp_title( $this->parent, $this->parent->getId( ) . '.Title', $this->messages['title'] ) );
		$this->frm = new _uicmp_orge_frm( $this->parent->getBody( ), $this->id . '.Frm', $this->messages, $tah );
			$this->parent->getBody( )->add( $this->frm );
		
		$buttons = new _uicmp_buttons( $this->parent->getHead( ), $this->parent->getHead( )->getId( ) . '.Buttons' );
			$buttons->add( new _uicmp_gi( $buttons, $buttons->getId( ) . '.Back', _uicmp_gi::IT_A, $this->messages['bt_back'], $this->parent->getLayoutJsVar( ) . '.back( );', '_uicmp_gi_back' ) );
			$buttons->add( new _uicmp_gi( $buttons, $buttons->getId( ) . '.S1', _uicmp_gi::IT_TXT, '|' ) );
			$buttons->add( $this->bt = new _uicmp_gi( $buttons, $buttons->getId( ) . '.Save', _uicmp_gi::IT_BT, $this->messages['bt_save'], $this->frm->getJsVar() . '.save( );' ) );
				$this->ind = new _uicmp_gi_ind( $buttons, $buttons->getId( ) . '.Ind', _uicmp_gi::IT_IND, $this->messages['ind'] );
					$buttons->add( $this->ind );

			$this->parent->getHead( )->add( $buttons );
	}
	
	/**
	 * Generate client side logic and requirements.
	 */
	public function generateJs ( )
	{
		
		$requirer = $this->frm->getRequirer( );
		if ( !is_null( $requirer ) )
		{
			$requirer->call( _uicmp_layout::RES_JS, array( $requirer->getRelative( ) . '3rd/XMLWriter-1.0.0-min.js' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_JS, array( $requirer->getRelative( ) . '3rd/base64.js' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_JS, array( 'inc/ab/uicmp.js' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_CSS, array( 'inc/ab/uicmp.css' , __CLASS__ ) );
			$requirer->call( _uicmp_layout::RES_JSPLAIN, 'var ' . $this->frm->getJsVar( ) . ' = new _uicmp_ab_orge( ' . $this->parent->getLayoutJsVar( ) . ', \'' . $this->parent->getHtmlId( ) . '\', \'' . $this->frm->getJsVar( ) . '\', \'' . $this->frm->getHtmlId( ) . '\', \'' . $this->title->getHtmlId( ) . '\', \'' . $this->url . '\', ' . $this->getJsAjaxParams( ) . ', \'' . $this->frm->getStrings( )->getHtmlId( ) . '\', ' . $this->ind->getJsVar( ) . ' );' );
			$requirer->call( _uicmp_layout::RES_JSPLAIN, $this->parent->getLayoutJsVar( ) . '.registerTabCb( \'' . $this->parent->getHtmlId( ) . '\', \'onLoad\', ' . $this->frm->getJsVar( ) . '.startup );' );
		}
	}
}

?>