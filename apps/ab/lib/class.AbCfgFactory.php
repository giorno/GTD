<?php

/**
 * @file class.AbCfgFactory.php
 * @author giorno
 * @package N7
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'list/_list_cfg.php';

/**
 * Settings abstraction for Address Book.
 */
class AbSettings extends _settings
{
	public function __construct ( ) { parent::__construct( _settings::SCOPE_USER, N7_SOLUTION_ID . '.AddressBook' ); }
}

/**
 * 
 * Factory providing settings and configuration instances for Address Book
 * application.
 */
class AbCfgFactory
{
	/**
	 * Width of Person/Organization name column.
	 */
	const LIST_HDRW_NAME		= '*';
	
	/**
	 * Width of contact field column (emial, phone).
	 */
	const LIST_HDRW_FIELD		= '180px';
	
	/**
	 * Cache used for created instances.
	 *
	 * @var array
	 */
	protected static $cfgs = NULL;

	/**
	 * Singleton instance.
	 * 
	 * @var AbSettings 
	 */
	protected static $instance = NULL;

	/**
	 * Hide constructors in accordance to Singleton guidelines.
	 */
	private function  __construct ( ) { }
	private function  __clone ( ) { }

	/**
	 * Settings singleton interface.
	 * 
	 * @return AbSettings 
	 */
	public static function getInstance ( )
	{
		if ( is_null( static::$instance ) )
			static::$instance = new AbSettings ( );

		return static::$instance;
	}

	/**
	 * List configuration isntances factory method.
	 * 
	 * @param string $key identifier of clist configuration
	 * @return _list_cfg 
	 */
	public static function getCfg ( $key )
	{
		if ( !is_array( static::$cfgs ) || !array_key_exists( $key, static::$cfgs ) )
			static::$cfgs[$key] = new _list_cfg ( static::getInstance( ), $key );
		
		return static::$cfgs[$key];
	}
}

?>