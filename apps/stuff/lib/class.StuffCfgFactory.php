<?php

/**
 * @file class.StuffCfgFactory.php
 * @author giorno
 *
 * Lazy initialization of Stuff application settings and lists configuration
 * instances.
 */

require_once CHASSIS_LIB . 'list/_list_cfg.php';
require_once CHASSIS_LIB . 'session/_settings.php';

/**
 * Specialization of all general purpose list config for virtual box 'All'.
 */
class _stuff_all_cfg extends _list_cfg
{
	/**
	 * Overload of parent method. Stores data and writes them into the table.
	 *
	 * @param <string> $keywords search phrase
	 * @param <string> $order field, by which output is sorted
	 * @param <string> $dir direction of sorting ('ASC' or 'DESC')
	 * @param <int> $page current page
	 * @param <string> $box box to search in
	 * @param <string> $field field to search in
	 * @param <int> $context context to filter
	 * @param <string> $display display mode
	 * @param <int> $contexts whether context badges should be displayed or not
	 */
	public function save ( $keywords, $order, $dir, $page, $box, $field, $context, $display, $contexts )
	{
		$this->set( $keywords, $order, $dir, $page );
		$this->data['b']	= $box;
		$this->data['f']	= $field;
		$this->data['c']	= $context;
		$this->data['y']	= $display;
		$this->data['s']	= $contexts;
		$this->settings->saveOne( $this->key, serialize( $this->data ) );
	}
}

class StuffSettings extends _settings
{
	public function __construct ( ) { parent::__construct( _settings::SCOPE_USER, N7_SOLUTION_ID . '.Stuff' ); }
}


class StuffCfgFactory
{
	/**
	 * Cache used for created instances.
	 *
	 * @var <array>
	 */
	protected static $cfgs = NULL;

	protected static $instance = NULL;

	private function  __construct ( ) { }
	private function  __clone ( ) { }

	public static function getInstance ( )
	{
		if ( is_null( static::$instance ) )
			static::$instance = new StuffSettings ( );

		return static::$instance;
	}

	public static function getCfg ( $key )
	{
		if ( !is_array( static::$cfgs ) || !array_key_exists( $key, static::$cfgs ) )
		{
			switch ( $key )
			{
				/**
				 * Search for All stuff requires special version of list config.
				 */
				case 'usr.lst.All':
					static::$cfgs[$key] = new _stuff_all_cfg ( static::getInstance( ), $key );
				break;
			
				default:
					static::$cfgs[$key] = new _list_cfg ( static::getInstance( ), $key );
				break;
			}
		}

		return static::$cfgs[$key];
	}
}

?>
