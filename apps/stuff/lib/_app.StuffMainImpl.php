<?php

/**
 * @file _app.StuffMainImpl.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'apps/_app_registry.php';
require_once CHASSIS_LIB . 'apps/_wwg_registry.php';

require_once CHASSIS_LIB . 'uicmp/_uicmp_dlgs.php';
require_once CHASSIS_LIB . 'uicmp/_uicmp_layout.php';
require_once CHASSIS_LIB . 'uicmp/_uicmp_title.php';
require_once CHASSIS_LIB . 'uicmp/_vcmp_cdes.php';

require_once N7_SOLUTION_LIB . 'n7_requirer.php';

require_once N7_SOLUTION_LIB . 'sem/iface.SemProvider.php';
require_once N7_SOLUTION_LIB . 'sem/sem.php';
require_once N7_SOLUTION_LIB . 'sem/sem_atom.php';
require_once N7_SOLUTION_LIB . 'sem/sem_collection.php';

require_once N7_SOLUTION_LIB . 'wwg/_wwg.MenuItem.php';
require_once N7_SOLUTION_LIB . 'wwg/_wwg.Menu.php';

require_once APP_STUFF_LIB . '_app.Stuff.php';
require_once APP_STUFF_LIB . '_wwg.Goals.php';
require_once APP_STUFF_LIB . 'uicmp/_vcmp_stuff_search_all.php';
require_once APP_STUFF_LIB . 'class.StuffCfgFactory.php';

/**
 * Main implementation of Stuff application for Request-Response phase. Ajax
 * server implementation is placed in separate class.
 */
class StuffMainImpl extends Stuff implements SemProvider
{

	/**
	 * Reference to UICMP Layout.
	 * 
	 * @var <_uicmp_layout>
	 */
	protected $layout = NULL;

	/**
	 * Dialogs holder.
	 * 
	 * @var <_uicmp_dlgs>
	 */
	protected $dlgs = NULL;

	protected function __construct ( )
	{
		parent::__construct( );
		
		$this->firstLogin( );
		_app_registry::getInstance( )->setDefault( $this->id );
		$this->indexTemplatePath = APP_STUFF_UI . 'index.html';
		_smarty_wrapper::getInstance( )->getEngine( )->assign( 'APP_STUFF_TEMPLATES', APP_STUFF_UI );
		
	}

	/**
	 * Providing structured information used later to render application icon.
	 */
	public function icon ( )
	{
		$sizes = $this->getSe( )->BoxSizes( );

		return Array( 'id' => $this->id,	// shoud be consistent with what is passed to _uicmp_stuff_fold class
					  'title' => $this->messages['tabNumbers']->ToString( $sizes['size']['Total'] ) );
	}

	public function event ( $event )
	{
		/**
		 * Actions performed upon app registration.
		 */
		if ( $event & _app_registry::EV_REGISTERED )
		{
			/**
			 * Initialize Lifegoals widget if Lifegoals were configured.
			 */
			$goals_on = StuffCfgFactory::getInstance( )->get( 'usr.goals.on' );
			if ( (int)$goals_on > 0 )
				$this->goals = new Goals( $this );
		}
	}

	/**
	 * Main execution body for Stuff application.
	 */
	public function exec ( )
	{
		//require_once APP_STUFF_LIB . 'uicmp/_uicmp_cpe_cal.php';
		require_once APP_STUFF_LIB . 'uicmp/_uicmp_stuff_fold.php';
		require_once APP_STUFF_LIB . 'uicmp/_vcmp_cpe.php';

		/**
		 * Color sizes of boxes.
		 */
		$sizes = $this->getSe( )->BoxSizes( );
		$pageSize = n7_globals::settings( )->get( 'usr.lst.len' );

		/**
		 * Build UI.
		 */
		$this->layout = n7_ui::getInstance( )->getLayout( );
		$this->dlgs = n7_ui::getInstance( )->getDlgs( );

			$this->layout->createSep( );

			$url	= n7_globals::getInstance()->get( 'url' )->myUrl( ) . 'ajax.php';	// Ajax server URL
			$params	= Array( 'app' => $this->id, 'action' => 'folds' );					// Ajax request parameters

			_uicmp_stuff_fold::setParams( $url, $params, 'app' . $this->id . '.icoTxt' );	// third param should be composed in same was as rendered in template
			_uicmp_stuff_fold::initializeJs( n7_requirer::getInstance( ) );				// explicit intialization of Javascript variable so it is accessible to CPE form instance

			$params['action'] = 'cpe';													// redefine action for CPE form methods

			/**
			 * CPE form tab.
			 */
			$tab = $this->layout->createTab( $this->id . '.Cpe' );
				$tab->unstack( );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['cpeTitle'] ) );
				$cpe = new _vcmp_cpe( $tab, $tab->getId( ) . '.Sol', _uicmp_stuff_fold::getJsName( ), $this->getVcmpSearchId( 'PrjPicker' ), $this->dlgs, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.PrjPicker' ), $this->messages, StuffCfgFactory::getInstance( )->get( 'usr.ta.h.cpe' ), n7_globals::userTz( ), $this->getTimePresets( ) );
				$tab->addVcmp( $cpe );

			/**
			 * Redefine Ajax parameters 'action' value for search solutions.
			 */
			$params['action'] = 'search';
			$params['cpe_js_var'] = $cpe->getJsVar( );	// to be sent by search solutions for items onClick event processing

			$tab = $this->layout->createTab( $this->id . '.Schedule', FALSE );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxSchedule'], 'Schedule', $sizes['size']['Schedule'], $sizes['avg']['Schedule'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capSchedule'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Schedule' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Schedule' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.Projects' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxProjects'], 'Projects', $sizes['size']['Projects'], $sizes['avg']['Projects'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capProjects'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Projects' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Projects' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.All' );
				$tab->createFold( $this->messages['tabAllStuff'] );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capAllStuff'] ) );
				$srch = $tab->addVcmp( new _vcmp_stuff_search_all( $this->getVcmpSearchId( 'All' ), $tab, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.All' ), StuffCfgFactory::getInstance( ), $this->messages ) );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['miScroll2Top'], 'window.scrollTo( 0, 0 );', '_uicmp_gi_top' ) );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.S1', _uicmp_gi::IT_TXT, '|' ));
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi2', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$this->layout->createSep( );

			$tab = $this->layout->createTab( $this->id . '.Inbox' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxInbox'], 'Inbox', $sizes['size']['Inbox'], $sizes['avg']['Inbox'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capInbox'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Inbox' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Inbox' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.Na' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxNextActions'], 'Na', $sizes['size']['Na'], $sizes['avg']['Na'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capNextActions'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Na' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Na' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.Wf' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxWaitingFor'], 'Wf', $sizes['size']['Wf'], $sizes['avg']['Wf'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capWaitingFor'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Wf' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Wf' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.Sd' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxSomeday'], 'Sd', $sizes['size']['Sd'], $sizes['avg']['Sd'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capSomeday'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Sd' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Sd' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			$tab = $this->layout->createTab( $this->id . '.Ar' );
				$tab->setFold( new _uicmp_stuff_fold( $tab, $tab->getId( ) . '.Fold', $this->messages['boxArchive'], 'Ar', $sizes['size']['Ar'], $sizes['avg']['Ar'] ) );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['capArchive'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'Ar' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Ar' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );

			/**
			 * Redefine Ajax parameters 'action' value for CDES.
			 */
			$params['action'] = 'cdes';

			/**
			 * Create CDES.
			 */
			$cdes = new _vcmp_cdes( $this->layout, $this->id . '.Cdes', Array( 'cdesFold' => $this->messages['cdesFold'], 'cdesTitle' => $this->messages['cdesTitle'] ), $url, $params, StuffCfgFactory::getCfg( 'usr.lst.Contexts' ), $pageSize );

			$this->layout->createSep( );
			

		if ( ( $_app_registry = _app_registry::getInstance( ) ) != NULL )
		{
			$_app_registry->requireJs( "inc/chassis/3rd/XMLWriter-1.0.0-min.js",	$this->id );
			$_app_registry->requireJs( "inc/chassis/3rd/tinyxmlsax.js",				$this->id );
			$_app_registry->requireJs( "inc/chassis/3rd/tinyxmlw3cdom.js",			$this->id );
			$_app_registry->requireJs( "inc/chassis/3rd/base64.js",					$this->id );
			$_app_registry->requireJs( "inc/stuff/stuff.js",						$this->id );

			$_app_registry->requireCss( "inc/chassis/css/_list.css",				$this->id );
			$_app_registry->requireCss( "inc/stuff/stuff.css",						$this->id );

			$_app_registry->requireBodyChild( CHASSIS_UI . '_wdg.html',				$this->id );
		}

		$smarty = _smarty_wrapper::getInstance( )->getEngine( );

		n7_ui::getInstance( )->getMenu( )->register(	new MenuItem(	MenuItem::TYPE_JS,
														$this->messages['cpeMenuItem'],
														$cpe->getJsVar( ) . '.collect( );',
														'_uicmp_blue' ) );

		$smarty->assignByRef( 'APP_STUFF_MSG',      $this->messages );
	}

	/**
	 * Find in the database user's most used times for fast options next to time
	 * select widget in the CPE form.
	 *
	 * @return <array>
	 */
	public function getTimePresets ( )
	{
		$ret = NULL;
		
		$times = (int)StuffCfgFactory::getInstance( )->get( 'usr.cpe.times' );
		$sample = (int)StuffCfgFactory::getInstance( )->get( 'usr.cpe.sample' );

		if ( $times == 0 )
			return null;

		/**
		 * Apply possible history restriction.
		 */
		$history = '';
		if ( (int)$sample > 0 )
			$history = "AND`" . StuffConfig::F_STUFFRECORDED . "` > ( NOW() - INTERVAL " . _db_escape( (int)$sample ) . " DAY )";

		/**
		 * Extract data.
		 */
		$res = _db_query( "SELECT `" . StuffConfig::F_STUFFTIMEVAL . "` FROM `" . StuffConfig::T_STUFFBOXES . "` WHERE `" . StuffConfig::F_STUFFUID . "` = \"" . _session_wrapper::getInstance( )->getUid( ) . "\" {$history} AND `" . StuffConfig::F_STUFFTIMEVAL . "` != \"0\" GROUP BY HOUR(`" . StuffConfig::F_STUFFTIMEVAL . "`), MOD(MINUTE(`" . StuffConfig::F_STUFFTIMEVAL . "`), 30)  LIMIT 0," . _db_escape( $times ) );
		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$hour = date( "H", strtotime( $row[0] ) );
				$minute = sprintf( "%02s", floor( (int)date( "i", strtotime( $row[0] ) ) / 30 ) * 30 );
				$ret[$hour . ':' . $minute] = strftime( $this->messages['dtFormat']['PRESET'], strtotime( $hour . ":" . $minute ) );
			}
		}
		
		/**
		 * Fill remaining positions by presets.
		 */
		$presets = Array( "09:00", "11:00", "14:00", "10:00", "13:00", "15:00", "12:00" );
		$from = 0;
		if ( is_array( $ret ) )
			$from = count( $ret );
		for ( $i = $from;  $i < $times; $i++ )
		{
			foreach ( $presets as $preset )
				if ( !is_array( $ret ) || !array_key_exists( $preset, $ret ) )
				{
					$ret[$preset] = strftime( $this->messages['dtFormat']['PRESET'], strtotime( $preset ) );
					break;
				}
		}

		if ( is_array( $ret ) )
			ksort( $ret );
		
		return $ret;
	}
	
	/**
	 * Implements interface. Provides SEM collection instance for this
	 * application.
	 * 
	 * @todo make first record column in lists optional
	 * 
	 * @return <sem_collection>
	 */
	public function getSemCollection ( )
	{
		$coll = new sem_collection( $this->id, $this->messages['sem']['title'] );

			/**
			 * Time presets.
			 */
			$atom = new sem_atom( sem_atom::AT_SELECT, 'usr.cpe.times', StuffCfgFactory::getInstance( )->get( 'usr.cpe.times'), $this->messages['sem']['aPresets'], $this->messages['sem']['dPresets'] );
				foreach( $this->messages['sem']['oPresetsNo'] as $value => $name )
					$atom->addOption( $value, $name );
				
					$subatom = new sem_atom( sem_atom::AT_SELECT, 'usr.cpe.sample', StuffCfgFactory::getInstance( )->get( 'usr.cpe.sample'), '' );
						foreach ( $this->messages['sem']['oPresetsBy'] as $value => $name )
							$subatom->addOption( $value , $name );
						$atom->addParticle( $subatom );
						
				$coll->add( $atom );

			/**
			 * Algorithm for folds info.
			 */
			$atom = new sem_atom( sem_atom::AT_SELECT, 'usr.alg', StuffCfgFactory::getInstance( )->get( 'usr.alg'), $this->messages['sem']['aAlg'], $this->messages['sem']['dAlg'] );
				foreach( $this->messages['sem']['oAlg'] as $value => $name )
					$atom->addOption( $value , $name );
				$coll->add( $atom );

			/**
			 * Configuration of Lifegoals.
			 */
			$atom = new sem_atom( sem_atom::AT_SELECT, 'usr.goals.on', StuffCfgFactory::getInstance( )->get( 'usr.goals.on'), $this->messages['sem']['aLg'], $this->messages['sem']['dLg'] );
				$atom->addOption( 0, $this->messages['sem']['oNoLg'] );
				
				$ctxs = _cdes::allCtxs( _session_wrapper::getInstance()->getUid( ), StuffConfig::T_STUFFCTX );
				if ( is_array( $ctxs ) )
					foreach( $ctxs as $ctx )
						$atom->addOption( $ctx->id, $ctx->disp );
				
					$subatom = new sem_atom( sem_atom::AT_SELECT, 'usr.goals.box', StuffCfgFactory::getInstance( )->get( 'usr.goals.box'), '' );
						foreach ( $this->messages['sem']['oLg'] as $value => $name )
							$subatom->addOption( $value , $name );
						$atom->addParticle( $subatom );
				
				$coll->add( $atom );
				
			
		return $coll;
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
				/*_db_query( "INSERT INTO `" . Config::T_LOGINS . "`
								SET `" . Config::F_UID . "` = \"" . _db_escape( $uid ) . "\",
									`" . Config::F_NS . "` = \"" . _db_escape( N7_SOLUTION_ID ) . "\",
									`" . Config::F_STAMP . "` = NOW()" );*/
				
				/**
				 * Create base set of contexts.
				 */
				$set = $this->messages['1st_login'];
				$cdes = new _cdes( $uid, StuffConfig::T_STUFFCTX, n7_globals::getInstance( )->get('io.creat.chassis.i18n') );
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