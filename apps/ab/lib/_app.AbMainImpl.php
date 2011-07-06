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
		
		$layout = n7_ui::getInstance( )->getLayout( );
		
			$layout->createSep( );
			
			$url		= n7_globals::getInstance()->get( 'url' )->myUrl( ) . 'ajax.php';	// Ajax server URL
			$params		= Array( 'app' => $this->id, 'action' => 'pers' );					// Ajax request parameters
			$pageSize	= n7_globals::settings( )->get( 'usr.lst.len' );
			
			$tab = $layout->createTab( $this->id . '.Cpe' );
				$tab->unstack( );
			
			$params['action'] = 'search';
			
			$tab = $layout->createTab( $this->id . '.All', FALSE );
				$tab->createFold( $this->messages['f_all'] );
				$tab->getHead( )->add( new _uicmp_title( $tab, $tab->getId( ) . '.Title', $this->messages['t_all'] ) );
				$srch = $tab->createSearch( $this->getVcmpSearchId( 'All' ), 0, $url, $params, StuffCfgFactory::getCfg( 'usr.lst.All' ), $pageSize );
				$rszr = $srch->getResizer( );
				
				/*$rszr->add( new _uicmp_gi( $rszr, $rszr->getId( ) . '.mi1', _uicmp_gi::IT_A,  $this->messages['cpeMenuItem'], $cpe->getJsVar() . '.collect();', '_uicmp_gi_add' ) );*/
				
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
		
		$smarty->assignByRef( 'APP_AB_LAYOUT', $layout );
	}
}

?>