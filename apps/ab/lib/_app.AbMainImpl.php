<?php

/**
 * @file _app.AbMainImpl.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once APP_AB_LIB . '_app.Ab.php';
require_once APP_AB_LIB . 'class.AbCfgFactory.php';
require_once APP_AB_LIB . 'uicmp/_vcmp_perse.php';
require_once APP_AB_LIB . 'uicmp/_vcmp_orge.php';

/**
 * Main implementation of AddressBook application for Request-Response phase.
 * Ajax server implementation is placed in separate class.
 */
class AbMainImpl extends Ab
{
	public function __construct()
	{
		parent::__construct();
	
		$this->indexTemplatePath = APP_AB_UI . 'index.html';
		_smarty_wrapper::getInstance( )->getEngine( )->assign( 'APP_AB_TEMPLATES', APP_AB_UI );
		_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'APP_AB_MSG', $this->messages );
	}
	
	/**
	 * Providing structured information used later to render application icon.
	 */
	public function icon ( )
	{
		return Array( 'id' => $this->id,	// shoud be consistent with what is passed to _uicmp_stuff_fold class
					  'title' => $this->messages['icon'] );
	}

	public function exec ( )
	{
		$smarty = _smarty_wrapper::getInstance( )->getEngine( );
		$settings = AbCfgFactory::getInstance( );
		
		$layout = n7_ui::getInstance( )->getLayout( );
		
			$layout->createSep( );
			
			$url		= n7_globals::getInstance()->get( 'url' )->myUrl( ) . 'ajax.php';	// Ajax server URL
			$params		= Array( 'app' => $this->id, 'action' => 'perse' );					// Ajax request parameters
			$pageSize	= n7_globals::settings( )->get( 'usr.lst.len' );

			/**
			 * Person-class editor tab.
			 */
			$tab = $layout->createTab( $this->id . '.PersE' );
				$tab->unstack( );
				$perse = new _vcmp_perse( $tab, $tab->getId( ) . '.Sol', $url, $params, $this->messages['perse'], $settings->get( 'usr.ta.h.perse' ) );
				$tab->addVcmp( $perse );
				
			/**
			 * Organization-class editor tab.
			 */
			$params['action'] = 'orge';
			$tab = $layout->createTab( $this->id . '.OrgE' );
				$tab->unstack( );
				$orge = new _vcmp_orge( $tab, $tab->getId( ) . '.Sol', $url, $params, $this->messages['orge'], $settings->get( 'usr.ta.h.orge' ) );
				$tab->addVcmp( $orge );
			
			$params['action'] = 'search';
			
			$tab = $layout->createTab( $this->id . '.All', FALSE );
				$tab->createFold( $this->messages['f_all'] );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['t_all'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'All' ), 0, $url, $params, AbCfgFactory::getCfg( 'usr.lst.All' ), $pageSize );
				$rszr = $srch->getResizer( );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['mi_add_person'], $perse->getJsVar() . '.add();', '_uicmp_gi_add' ) );
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.S1', _uicmp_gi::IT_TXT, '|' ));
				$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi2', _uicmp_gi::IT_A,  $this->messages['mi_add_company'], $orge->getJsVar() . '.add();', '_uicmp_gi_add' ) );
				
				
			/**
			 * Redefine Ajax parameters 'action' value for CDES.
			 */
			$params['action'] = 'cdes';

			/**
			 * Create CDES.
			 */
			$cdes = new _vcmp_cdes( $layout, $this->id . '.Cdes', Array( 'cdesFold' => $this->messages['cdes']['fold'], 'cdesTitle' => $this->messages['cdes']['title'] ), $url, $params, abCfgFactory::getCfg( 'usr.lst.Contexts' ), $pageSize );
			
			$layout->createSep( );
		
		$layout->init( );
		
		n7_ui::getInstance( )->getMenu( )->register(	new MenuItem(	MenuItem::TYPE_JS,
														$this->messages['mi_add_person'],
														$perse->getJsVar( ) . '.add( );',
														'_uicmp_blue' ) );
		
		n7_ui::getInstance( )->getMenu( )->register(	new MenuItem(	MenuItem::TYPE_JS,
														$this->messages['mi_add_company'],
														$orge->getJsVar( ) . '.add( );',
														'_uicmp_blue' ) );
		
		$smarty->assignByRef( 'APP_AB_LAYOUT', $layout );
	}
}

?>