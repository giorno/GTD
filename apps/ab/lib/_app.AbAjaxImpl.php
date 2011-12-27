<?php

/**
 * @file _app.AbAjaxImpl.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once APP_AB_LIB . '_app.Ab.php';

require_once APP_AB_LIB . 'class.AbScheme.php';
require_once APP_AB_LIB . 'class.AbConfig.php';
require_once APP_AB_LIB . 'class.AbCfgFactory.php';

/**
 * Ajax server implementation for Address Book application
 */
class AbAjaxImpl extends Ab
{
	public function exec ( )
	{
		$smarty = _smarty_wrapper::getInstance( )->getEngine( );
		
		switch ( $_POST['action'] )
		{
			/**
			 * Performs search operation and returns results.
			 * 
			 * @todo redesign to use some kind of template in N7App
			 */
			case 'search':
				
				switch ( $_POST['method'] )
				{
					case 'refresh':

					require_once APP_AB_LIB . 'class.AbSearch.php';
					$engine = new AbSearch( \io\creat\chassis\session::getInstance( )->getUid( ), $this );

					$results = $engine->search( $_POST['perse_js_var'], $_POST['orge_js_var'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['keywords'], $_POST['page'], $_POST['order'], $_POST['dir'] );

					if ( $results !== false )
					{
						$smarty->assignByRef( 'USR_LIST_DATA', $results );
						_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
						_smarty_wrapper::getInstance( )->render( );
					}
					else
					{
						$search_id = $this->getVcmpSearchId( 'All' );
						if ( trim( $_POST['keywords'] ) != '' )
						{
							$empty = new _list_empty( $this->messages['list']['no_match'], n7_globals::getInstance( )->get('io.creat.chassis.i18n') );
							$empty->add( $this->messages['list']['again'], "_uicmp_lookup.lookup( '{$search_id}' ).focus();" );
							$empty->add( $this->messages['list']['all'], "_uicmp_lookup.lookup( '{$search_id}' ).showAll();" );
						}
						else
						{
							$empty = new _list_empty( $this->messages['list']['empty'], n7_globals::getInstance( )->get('io.creat.chassis.i18n') );
							$empty->add( $this->messages['list']['add_pers'], "{$_POST['perse_js_var']}.add();" );
							$empty->add( $this->messages['list']['add_org'], "{$_POST['orge_js_var']}.add();" );
						}
						$empty->render( );
					}
					break;
					
					case '_ab_rm_batch':
						require_once APP_AB_LIB . 'class.AbPerson.php';
						require_once APP_AB_LIB . 'class.AbOrg.php';
						
						$ids = explode( ',', $_POST['ids'] );
						$id = $ids[0];
						
						if ( $_POST['class'] == 'pers' )
							$contact = new AbPerson( \io\creat\chassis\session::getInstance( )->getUid( ) );
						else
							$contact = new AbOrg( \io\creat\chassis\session::getInstance( )->getUid( ) );
						
						$contact->remove( $id );
					break;
				}
			break;
		
			/**
			 * Handling of requests from CDES client code.
			 */
			case 'cdes':
				$this->handleCdes( AbConfig::T_ABCTX, AbCfgFactory::getCfg( 'usr.lst.Contexts' ) );
			break;
		
			/**
			 * Person-class contact editor.
			 */
			case 'perse':
				
				switch ($_POST['method'])
				{
					/**
					 * Provides actual cloud of contexts.
					 */
					case 'get':
						$this->getCdesCloud( AbConfig::T_ABCTX, $_POST['js_var'], $_POST['id'], $this->messages['perse']['no_labels'] );
					break;
				
					/**
					 * Saves person editor comments textarea height into app
					 * namespace settings. This is unconfirmed service.
					 */
					case 'tah':
						$this->handleTah( AbCfgFactory::getInstance( ), 'usr.ta.h.perse', (int)$_POST['val'] );
					break;
				
					/**
					 * Provide serialized names for pre-typed fields.
					 */
					case 'names_get':
						echo AbScheme::jsonNumberNames( \io\creat\chassis\session::getInstance( )->getUid( ), $this->messages['typed']['types'] );
					break;
					
					/*
					 * Save new contact data for person. Data should be passed from client
					 * as XML document.
					 */
					case 'save':
						require_once APP_AB_LIB . 'class.AbPerson.php';
						$person = new AbPerson( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$person->importXml( htmlspecialchars_decode( $_POST['data'] ) );
							$person->add( );
					break;
				
					/**
					 * Provides contact information in XML to be parsed by
					 * client side logic.
					 */
					case 'load':
						require_once APP_AB_LIB . 'class.AbPerson.php';
						$person = new AbPerson( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$person->load( $_POST['id'] );
							echo $person->exportXml( );
					break;
				}
			break;
		
			/**
			 * Requests coming from Organization-class contact editor.
			 */
			case 'orge':
				switch ($_POST['method'])
				{
					/**
					 * Provides actual cloud of contexts.
					 */
					case 'get':
						$this->getCdesCloud( AbConfig::T_ABCTX, $_POST['js_var'], $_POST['id'], $this->messages['perse']['no_labels'] );
					break;
				
					/**
					 * Saves person editor comments textarea height into app
					 * namespace settings. This is unconfirmed service.
					 */
					case 'tah':
						$this->handleTah( AbCfgFactory::getInstance( ), 'usr.ta.h.orge', (int)$_POST['val'] );
					break;
				
					/**
					 * Provide serialized names for pre-typed fields.
					 */
					case 'names_get':
						echo AbScheme::jsonNumberNames( \io\creat\chassis\session::getInstance( )->getUid( ), $this->messages['typed']['types'] );
					break;
					
					/*
					 * Save new contact data for organization. Data should be passed from client
					 * as XML document.
					 */
					case 'save':
						require_once APP_AB_LIB . 'class.AbOrg.php';
						$org = new AbOrg( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$org->importXml( htmlspecialchars_decode( $_POST['data'] ) );
							$org->add( );
					break;
				
					/**
					 * Provides contact information in XML to be parsed by
					 * client side logic.
					 */
					case 'load':
						require_once APP_AB_LIB . 'class.AbOrg.php';
						$org = new AbOrg( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$org->load( $_POST['id'] );
							echo $org->exportXml( );
					break;
				}
			break;
		
		}
	}
}

?>