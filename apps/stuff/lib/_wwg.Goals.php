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
require_once CHASSIS_LIB . 'uicmp/grpitem.php';

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

	/**
	 * Constructor.
	 * 
	 * @param Stuff $app reference to application instance
	 */
	public function __construct( &$app = NULL )
	{
		/**
		 * Only for RR phase.
		 */
		if ( !is_null( $app ) )
		{
			$this->id = static::ID;
			$this->template = APP_STUFF_UI . '_wwg.Goals.html';
			$messages = $app->getMessages( );
			
			/**
			 * Instance of indicator is not connected to any particular layout
			 * hierarchy.
			 */
			$ind = new \io\creat\chassis\uicmp\grpitem( $this, '_wwg.Goals.Ind', \io\creat\chassis\uicmp\grpitem::IT_IND, '', $messages['goals']['i'] );
			$ind->generateReqs( );
			/**
			 * Stolen from _uicmp_gi_ind and terribly bent.
			 */
			$layout = n7_ui::getInstance( )->getLayout( );
			$layout->getRequirer( )->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . $ind->getJsVar( ) . ' = new _uicmp_ind( \'' . $ind->getHtmlId( ) . '\', null, ' . \io\creat\chassis\uicmp\uicmp::toJsArray( $messages['goals']['i'] ) . ' );' );
			
			$url	= n7_globals::getInstance()->get( 'url' )->myUrl( ) . 'ajax.php';	// Ajax server URL
			$params	= Array( 'app' => $app->getId( ), 'action' => 'goals' );					// Ajax request parameters
			$layout->getRequirer( )->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var _wwgGoals_i = new _wwgGoals( \'' . $url . '\', ' . \io\creat\chassis\uicmp\uicmp::toJsArray( $params ) . ', ' . $ind->getJsVar( ) . ' );' );
			
			_wwg_registry::getInstance( )->register( _wwg_registry::POOL_BOTTOM, $this->id, $this );

			_app_registry::getInstance( )->requireJs( 'inc/stuff/_wwg.Goals.js', $this->id );
			_app_registry::getInstance( )->requireCss( 'inc/stuff/_wwg.Goals.css', $this->id );
			_app_registry::getInstance( )->requireOnLoad( '_wwgGoals_i.startup( );' );
			
			
			_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'WWG_GOALS_MSG', $messages );
			_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'WWG_GOALS_IND', $ind );
		}
		
		$this->engine = new StuffGoals( \io\creat\chassis\session::getInstance( )->getUid( ) );

		_smarty_wrapper::getInstance( )->getEngine( )->assign( 'WWG_GOALS', $this->engine->Lifegoals( StuffCfgFactory::getInstance( )->get( 'usr.goals.on' ), StuffCfgFactory::getInstance( )->get( 'usr.goals.box' ) ) );
	}

	public function setWeight ( )
	{
		$this->engine->SetWeight( $_POST['sid'], $_POST['weight'] );
	}
}

?>