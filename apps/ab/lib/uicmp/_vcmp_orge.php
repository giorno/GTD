<?php

/**
 * @file _vcmp_orge.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/vcmp.php';

require_once APP_AB_LIB . 'uicmp/_uicmp_orge_frm.php';
require_once APP_AB_LIB . 'uicmp/_vcmp_perse.php';

/**
 * Virtual component building Organization-class contact editor. Derived from
 * Person-class virtual component to code less.
 */
class _vcmp_orge extends \io\creat\chassis\uicmp\vcmp
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
		
		$this->parent->getHead( )->add( $this->title = new \io\creat\chassis\uicmp\headline( $this->parent, $this->parent->getId( ) . '.Title', $this->messages['title'] ) );
		$this->frm = new _uicmp_orge_frm( $this->parent->getBody( ), $this->id . '.Frm', $this->messages, $tah );
			$this->parent->getBody( )->add( $this->frm );
		
		$buttons = new \io\creat\chassis\uicmp\buttons( $this->parent->getHead( ), $this->parent->getHead( )->getId( ) . '.Buttons' );
			$buttons->add( new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.Back', \io\creat\chassis\uicmp\grpitem::IT_A, $this->messages['bt_back'], $this->parent->getLayoutJsVar( ) . '.back( );', '_uicmp_gi_back' ) );
			$buttons->add( new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.S1', \io\creat\chassis\uicmp\grpitem::IT_TXT, '|' ) );
			$buttons->add( $this->bt = new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.Save', \io\creat\chassis\uicmp\grpitem::IT_BT, $this->messages['bt_save'], $this->frm->getJsVar() . '.save( );' ) );
				$this->ind = new \io\creat\chassis\uicmp\indicator( $buttons, $buttons->getId( ) . '.Ind', \io\creat\chassis\uicmp\grpitem::IT_IND, $this->messages['ind'] );
					$buttons->add( $this->ind );

			$this->parent->getHead( )->add( $buttons );
	}
	
	/**
	 * Generate client side logic and requirements.
	 */
	public function generateReqs ( )
	{
		
		$requirer = $this->frm->getRequirer( );
		if ( !is_null( $requirer ) )
		{
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( $requirer->getRelative( ) . '3rd/XMLWriter-1.0.0-min.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( $requirer->getRelative( ) . '3rd/base64.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( 'inc/ab/uicmp.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_CSS, array( 'inc/ab/uicmp.css' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . $this->frm->getJsVar( ) . ' = new _uicmp_ab_orge( ' . $this->parent->getLayoutJsVar( ) . ', \'' . $this->parent->getHtmlId( ) . '\', \'' . $this->frm->getJsVar( ) . '\', \'' . $this->frm->getHtmlId( ) . '\', \'' . $this->title->getHtmlId( ) . '\', \'' . $this->url . '\', ' . $this->getJsAjaxParams( ) . ', \'' . $this->frm->getStrings( )->getHtmlId( ) . '\', ' . $this->ind->getJsVar( ) . ' );' );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, $this->parent->getLayoutJsVar( ) . '.registerTabCb( \'' . $this->parent->getHtmlId( ) . '\', \'onLoad\', ' . $this->frm->getJsVar( ) . '.startup );' );
		}
	}
}

?>