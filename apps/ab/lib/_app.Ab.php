<?php

/**
 * @file _app.Ab.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'apps/_app_registry.php';

require_once N7_SOLUTION_LIB . '_app.N7App.php';

/**
 * Common part of AddressBook application instances.
 */
abstract class Ab extends N7App
{
	/**
	 * Application identifier in registry.
	 */
	const APP_ID = '_app.AddressBook';
	
	/**
	 * Singleton instance.
	 * 
	 * @var Ab
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
		include APP_AB_ROOT . "i18n/" . n7_globals::lang( ) . ".php";
		$this->messages = &$__msgAb;
	}

	/**
	 * Singleton interface.
	 *
	 * @return Ab
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
	public function icon ( ) { return NULL; }

	/**
	 * Fake implementation to conform abstract parent. It is not used in all
	 * descendants.
	 */
	public function event ( $event ) { return NULL; }

}

?>