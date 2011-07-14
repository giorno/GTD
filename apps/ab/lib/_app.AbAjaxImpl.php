<?php

/**
 * @file _app.AbAjaxImpl.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once APP_AB_LIB . '_app.Ab.php';

require_once APP_AB_LIB . 'class.AbConfig.php';
require_once APP_AB_LIB . 'class.AbCfgFactory.php';

/**
 * Ajax server implementation for Address Book application
 */
class AbAjaxImpl extends Ab
{
	public function exec ( )
	{
		switch ( $_POST['action'] )
		{
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
						/*require_once CHASSIS_LIB . '_cdes.php';
						require_once CHASSIS_LIB . 'uicmp/_uicmp_cdes_cloud.php';
						$cloud = new _uicmp_cdes_cloud( NULL, NULL, $_POST['js_var'], _cdes::allCtxs( _session_wrapper::getInstance( )->getUid( ), AbConfig::T_ABCTX ), $_POST['id'] );
						$cloud->setErrorMsg( $this->messages['perse']['no_labels'] );
						_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'USR_UICMP_CMP', $cloud );
						_smarty_wrapper::getInstance( )->setContent( $cloud->getRenderer( ) );
						_smarty_wrapper::getInstance( )->render( );*/
					break;
				
					/**
					 * Saves person editor comments textarea height into app
					 * namespace settings. This is unconfirmed service.
					 */
					case 'tah':
						/*$tah = (int)$_POST['val'];
						$min = n7_globals::getInstance()->get( 'config' )->get( 'usr.ta.h.min');
						if ( $tah < $min )
							$tah = $min;
						
						AbCfgFactory::getInstance( )->saveOne( 'usr.ta.h.perse', $tah );*/
						$this->handleTah( AbCfgFactory::getInstance( ), 'usr.ta.h.perse', (int)$_POST['val'] );
					break;
					
					/*
					 * Save new contact data for person. Data should be passed from client
					 * as XML document.
					 */
					case 'save':
						require_once APP_AB_LIB . 'class.AbPerson.php';
						$person = new AbPerson( _session_wrapper::getInstance( )->getUid( ) );
							$person->importXml( htmlspecialchars_decode( $_POST['data'] ) );
							$person->add( );
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
					 * Saves person editor comments textarea height into app
					 * namespace settings. This is unconfirmed service.
					 */
					case 'tah':
						$this->handleTah( AbCfgFactory::getInstance( ), 'usr.ta.h.orge', (int)$_POST['val'] );
					break;
				}
			break;
		
		}
	}
}

?>