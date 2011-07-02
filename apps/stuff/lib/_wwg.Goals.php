<?php

/**
 * @file __wwg.Goals.php
 * @author giorno
 *
 * Web widget providing user interface for Lifegoals.
 */

require_once CHASSIS_LIB . 'apps/_app_registry.php';
require_once CHASSIS_LIB . 'ui/_smarty_wrapper.php';
require_once CHASSIS_LIB . 'apps/_wwg_registry.php';
require_once CHASSIS_LIB . 'apps/_wwg.Wwg.php';

require_once APP_STUFF_LIB . 'class.StuffGoals.php';

class Goals extends Wwg
{
	/**
	 * Widget identifier.
	 */
	const ID = '_wwg.Goals';

	/**
	 * Instance of backend object handling Lifegoals.
	 *
	 * @var <StuffGoals>
	 */
	private $engine = NULL;

	public function __construct( $applId = NULL )
	{
		global $__SESSION;

		/**
		 * Only for RR phase.
		 */
		if ( !is_null( $applId ) )
		{
			$this->id = static::ID;
			$this->template = APP_STUFF_UI . '_wwg.Goals.html';
			_wwg_registry::getInstance( )->register( _wwg_registry::POOL_BOTTOM, $this->id, $this );

			_app_registry::getInstance( )->requireJs( 'inc/stuff/_wwg.Goals.js', $this->id );
			_app_registry::getInstance( )->requireCss( 'inc/stuff/_wwg.Goals.css', $this->id );
			_app_registry::getInstance( )->requireOnLoad( '_wwgGoalsStartup();' );
		}
		
		$this->engine = new StuffGoals( _session_wrapper::getInstance( )->getUid( ) );

		_smarty_wrapper::getInstance( )->getEngine( )->assign( 'WWG_GOALS', $this->engine->Lifegoals( StuffCfgFactory::getInstance( )->get( 'usr.goals.on' ), StuffCfgFactory::getInstance( )->get( 'usr.goals.box' ) ) );
	}

	public function setWeight ( )
	{
		$this->engine->SetWeight( $_POST['SID'], $_POST['weight'] );
	}
}

?>