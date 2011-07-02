<?php



require_once CHASSIS_LIB . 'apps/_app_registry.php';

require_once N7_SOLUTION_LIB . '_app.N7App.php';
require_once APP_STUFF_LIB . 'class.StuffSearchBoxes.php';
require_once APP_STUFF_LIB . 'class.StuffCfgFactory.php';

/**
 * @file _app.Stuff.php
 * @author giorno
 * @package GTDtab
 *
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

}

?>