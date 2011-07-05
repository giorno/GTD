<?php

/**
 * @file _app.Stuff.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_CFG . 'class.Config.php';
require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'apps/_app_registry.php';

require_once N7_SOLUTION_LIB . '_app.N7App.php';

require_once APP_STUFF_LIB . 'class.StuffSearchBoxes.php';
require_once APP_STUFF_LIB . 'class.StuffCfgFactory.php';
require_once APP_STUFF_LIB . 'class.StuffCollector.php';
require_once APP_STUFF_LIB . 'class.StuffConfig.php';
require_once APP_STUFF_LIB . 'class.StuffData.php';

/**
 * Common part for implementations of Stuff application. It should not be
 * instantiated directly.
 */
abstract class Stuff extends N7App
{
	const APP_ID = '_app.Stuff';
	
	/**
	 * Instance of search engine.
	 *
	 * @var <StuffSearchBoxes>
	 */
	protected $searchEngine = NULL;

	/**
	 * Reference to Goals widget.
	 * 
	 * @var <_wwg.Goals>
	 */
	protected $goals = NULL;

	/**
	 * Instance of class or its descendant.
	 * @var <App>
	 */
	protected static $instance = NULL;

	/**
	 * Common initialization of application instance.
	 */
	protected function __construct ( )
	{
		$this->id = self::APP_ID;

		/**
		 * Setting up localization messages.
		 */
		include APP_STUFF_ROOT . "i18n/" . n7_globals::lang( ) . ".php";
		$this->messages = &$__msgStuff;
		$this->firstLogin( );
	}

	/**
	 * Singleton interface.
	 *
	 * @return <App>
	 */
	static public function getInstance ( )
	{
		if ( static::$instance == NULL )
		{
			static::$instance = new static( );
			_app_registry::getInstance()->register( static::$instance );
		}

		return static::$instance;
	}

	/**
	 * Fake implementation to conform abstract parent. It is not used in all
	 * descendants.
	 */
	public function icon ( ) { return null; }

	/**
	 * Fake implementation to conform abstract parent. It is not used in all
	 * descendants.
	 */
	public function event ( $event ) { return null; }

	/**
	 * On-demand initialization of search engine.
	 *
	 * @return <StuffSearch>
	 */
	protected function getSe ( )
	{
		if ( is_null( $this->searchEngine ) )
			$this->searchEngine = new StuffSearchBoxes( _session_wrapper::getInstance( )->getUid( ) );

		return $this->searchEngine;
	}

	/**
	 * Detects whether this used has signed in first time and create invitation
	 * record and appropriate contexts.
	 */
	protected function firstLogin ( )
	{
		$uid = _session_wrapper::getInstance( )->getUid( );
		
		/**
		 * Check if this user has signed first time.
		 */
		$count = _db_1field( "SELECT COUNT(*) FROM `" . Config::T_LOGINS . "`
								WHERE `" . Config::F_UID . "` = \"" . _db_escape( $uid ) . "\"
									AND `" . Config::F_NS . "` = \"" . _db_escape( N7_SOLUTION_ID ) . "\"" );
		
		if ( $count <= 1 )
		{
			/**
			 * Check for existence of Stuff app contexts. There should be none
			 * at the time of first login.
			 */
			$ctxs = _cdes::allCtxs( $uid, StuffConfig::T_STUFFCTX );
			if ( !is_array( $ctxs ) || !count( $ctxs ) )
			{
				_db_query( "INSERT INTO `" . Config::T_LOGINS . "`
								SET `" . Config::F_UID . "` = \"" . _db_escape( $uid ) . "\",
									`" . Config::F_NS . "` = \"" . _db_escape( N7_SOLUTION_ID ) . "\",
									`" . Config::F_STAMP . "` = NOW()" );
				
				/**
				 * Create base set of contexts.
				 */
				$set = $this->messages['1st_login'];
				$cdes = new _cdes( $uid, StuffConfig::T_STUFFCTX );
				$ctx_id = 0;
				foreach ( $set as $data )
					$ctx_id = $cdes->add( 0, $data[1], $data[0], $data[2] );
				
				/**
				 * Create welcome message.
				 */
				$collector = new StuffCollector( $uid );
				$collector->importStuff( array(
												'box' => 'Inbox',
												'name' => $this->messages['wlc']['cap'],
												'desc' => '',
												'priority' => 4,
												'place' => '',
												'pid' => 0,
												'date' => array( 'set' => true, 'composed' => date( "Y-m-d" ) ),
												'time' => array( 'set' => false, 'composed' => '00:00' ),
												'contexts' => array( $ctx_id => $ctx_id ),
												'flags' => ( 0 | StuffConfig::M_SYSTEM ),	// turning RO to prevent this particular record from editing
												'data' => StuffData::EncodeWelcomeMsgV1( n7_globals::lang( ) )	// setting system data for welcome message
												) );
				$collector->add( );
			}
		}
	}
}

?>