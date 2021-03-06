<?php

/**
 * @file _app.StuffAjaxImpl.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'ui/_smarty_wrapper.php';
require_once CHASSIS_LIB . 'list/_list_empty.php';

require_once N7_SOLUTION_LIB . 'sem/iface.SemApplicator.php';
require_once N7_SOLUTION_LIB . 'sem/sem_decoder.php';

require_once APP_STUFF_LIB . '_app.Stuff.php';
require_once APP_STUFF_LIB . '_wwg.Goals.php';
require_once APP_STUFF_LIB . 'uicmp/_uicmp_stuff_search_all_form.php';

/**
 * Ajax server side implementation of Stuff application.
 */
class StuffAjaxImpl extends Stuff implements SemApplicator
{
	public function exec ( )
	{		
		$smarty = _smarty_wrapper::getInstance( )->getEngine( );
		$smarty->assign( 'USR_LIST_CUST_MGR', APP_STUFF_UI . 'list/list_cust_mgr.html' );
		$smarty->assign( 'APP_STUFF_TEMPLATES', APP_STUFF_UI );
		$smarty->assignByRef( 'APP_STUFF_MSG', $this->messages );

		switch ( $_POST['action'] )
		{
			/*
			 * Provide XML with box sizes form updating UI with actual colors
			 * and data.
			 */
			case 'folds':
				echo $this->getSe( )->BoxSizesXml( );
			break;

			/**
			 * Matching UICMP search.
			 */
			case 'search':
				switch ( $_POST['method'] )
				{
					/**
					 * This method parasites on search solution instance
					 * configuration, so its data are delivered here.
					 *
					 * Removes batch of entries from Archive.
					 */
					case '_stuff_purge_batch':
						require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
						$processor = new StuffProcessor( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$processor->purgeBatch( $_POST['ids'] );
					break;

					/**
					 * Another 'hijack' method. Archives entry with appropriate
					 * label.
					 */
					case '_stuff_archive':
						require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
						$processor = new StuffProcessor( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$processor->archive( $_POST['id'], $_POST['label'] );
					break;
				
					/**
					 * Update labels <SELECT> box in Search All tab. This uses
					 * same data structure layout as when populated from main
					 * implementation.
					 */
					case 'ctxs_update':
						$data = NULL;
						_uicmp_stuff_search_all_form::getCtxs( $data );
						$data['selCtx'] = $_POST['ctx'];
						$smarty->assign( 'STUFF_SEARCH_FRM_DATA', $data );
						$smarty->assign( 'APP_STUFF_ADVSRCHID', $_POST['id'] );
						echo "<!--OK-->";
						echo $smarty->fetch( APP_STUFF_UI . 'x_advsrchfrmctxs.html' );
					break;

					/**
					 * Ordinary list search/refresh.
					 *
					 * @todo reengineer to use common code for rendering phase
					 */
					case 'refresh':
						switch ( $_POST['id'] )
						{
							/**
							 * Perform search in virtual box Schedule.
							 */
							case $this->getVcmpSearchId( 'Schedule' ):
								$results = $this->getSe( )->srchSchedule( $_POST['cpe_js_var'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['keywords'], $_POST['page'], $_POST['order'], $_POST['dir'] );

								if ( $results !== false )
								{
									$smarty->assignByRef( 'USR_LIST_DATA', $results );
									_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
									_smarty_wrapper::getInstance( )->render( );
								}
								else
								{
									$search_id = $this->getVcmpSearchId( 'Schedule' );
									if ( trim( $_POST['keywords'] ) != '' )
									{
										$empty = new _list_empty( $this->messages['nomatch']['Schedule'] );
										$empty->add( $this->messages['eo']['again'], "_uicmp_lookup.lookup( '{$search_id}' ).focus();" );
										$empty->add( $this->messages['eo']['all'], "_uicmp_lookup.lookup( '{$search_id}' ).showAll();" );
									}
									else
									{
										$empty = new _list_empty( $this->messages['empty']['Schedule'] );
										$empty->add( $this->messages['eo']['collect'], "{$_POST['cpe_js_var']}.collect();" );
									}
									$empty->render( );
								}
							break;

							/**
							 * Searching in virtual box Projects.
							 */
							case $this->getVcmpSearchId( 'Projects' ):
								require_once APP_STUFF_LIB . 'class.StuffProject.php';
								$engine = new StuffProject( \io\creat\chassis\session::getInstance( )->getUid( ) );

								$results = $engine->Search( $_POST['cpe_js_var'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['keywords'], $_POST['page'], $_POST['order'], $_POST['dir'] );

								if ( $results !== false )
								{
									$smarty->assignByRef( 'USR_LIST_DATA', $results );
									_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
									_smarty_wrapper::getInstance( )->render( );
								}
								else
								{
									
									$search_id = $this->getVcmpSearchId( 'Projects' );
									if ( trim( $_POST['keywords'] ) != '' )
									{
										$empty = new _list_empty( $this->messages['nomatch']['Projects'] );
										$empty->add( $this->messages['eo']['again'], "_uicmp_lookup.lookup( '{$search_id}' ).focus();" );
										$empty->add( $this->messages['eo']['all'], "_uicmp_lookup.lookup( '{$search_id}' ).showAll();" );
									}
									else
									{
										$empty = new _list_empty( $this->messages['empty']['Projects'] );
										$empty->add( $this->messages['eo']['collect'], "{$_POST['cpe_js_var']}.collect();" );
									}
									$empty->render( );
								}
							break;

							/**
							 * Perform search in standard boxes.
							 */
							case $this->getVcmpSearchId( 'Inbox' ):
							case $this->getVcmpSearchId( 'Na' ):
							case $this->getVcmpSearchId( 'Wf' ):
							case $this->getVcmpSearchId( 'Sd' ):
							case $this->getVcmpSearchId( 'Ar' ):
								$boxes = Array( 'Inbox', 'Na', 'Wf', 'Sd', 'Ar' );
								foreach ( $boxes as $box )
								{
									if ( $_POST['id'] == $this->getVcmpSearchId( $box ) )
									{
										$results = $this->getSe( )->srchBox( $box, $_POST['cpe_js_var'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['keywords'], $_POST['page'], $_POST['order'], $_POST['dir'] );
										break; // from loop
									}
								}

								if ( $results !== false )
								{
									$smarty->assignByRef( 'USR_LIST_DATA', $results );
									_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
									_smarty_wrapper::getInstance( )->render( );
								}
								else
								{
									$search_id = $_POST['id'];
									if ( trim( $_POST['keywords'] ) != '' )
									{
										$empty = new _list_empty( $this->messages['nomatch']['box'] );
										$empty->add( $this->messages['eo']['again'], "_uicmp_lookup.lookup( '{$search_id}' ).focus();" );
										$empty->add( $this->messages['eo']['all'], "_uicmp_lookup.lookup( '{$search_id}' ).showAll();" );
									}
									else
									{
										$empty = new _list_empty( $this->messages['empty']['box'] );
										$empty->add( $this->messages['eo']['collect'], "{$_POST['cpe_js_var']}.collect();" );
									}
									$empty->render( );
								}
							break;

							/**
							 * Advanced search form action - searching in all stuff.
							 */
							case $this->getVcmpSearchId( 'All' ):
								$results = $this->getSe( )->AdvSearch( $_POST['cpe_js_var'], $_POST['keywords'], $_POST['box'], $_POST['field'], $_POST['context'], $_POST['display'], $_POST['showCtxs'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['page'], $_POST['order'],  $_POST['dir'] );

								if ( $results !== false )
								{
									$smarty->assignByRef( 'USR_LIST_DATA', $results );
									switch ( $_POST['display'] )
									{
										case StuffSearchBoxes::ADVSRCHDISP_TREE:
											_smarty_wrapper::getInstance( )->setContent( APP_STUFF_UI . '/x_advsrchfrmtree.html' );
										break;

										case StuffSearchBoxes::ADVSRCHDISP_LIST:
										default:
											_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
										break;
									}
									_smarty_wrapper::getInstance( )->render( );
								}
								else
								{
									$search_id = $this->getVcmpSearchId( 'All' );
									if ( trim( $_POST['keywords'] ) != '' )
									{
										$empty = new _list_empty( $this->messages['nomatch']['All'] );
										$empty->add( $this->messages['eo']['again'], "_uicmp_lookup.lookup( '{$search_id}' ).focus();" );
										$empty->add( $this->messages['eo']['all'], "_uicmp_lookup.lookup( '{$search_id}' ).showAll();" );
									}
									else
									{
										$empty = new _list_empty( $this->messages['empty']['All'] );
										$empty->add( $this->messages['eo']['collect'], "{$_POST['cpe_js_var']}.collect();" );
									}
									$empty->render( );
								}
							break;
						}
					break;

					/**
					 * List size changed.
					 */
					case 'resize':
						$this->saveSize( (int)$_POST['size'] );
					break;
				}
			break;

			/**
			 * Handling of requests from CDES client code.
			 */
			case 'cdes':
				$this->handleCdes( StuffConfig::T_STUFFCTX, StuffCfgFactory::getCfg( 'usr.lst.Contexts' ), '_wwg_goals_refresh' );
			break;

			/**
			 * CPE form Ajax side implementation.
			 */
			case 'cpe':
				switch ( $_POST['method'] )
				{
					/**
					 * Saves CPE details textarea height into app namespace
					 * settings. This is unconfirmed service.
					 */
					case 'tah':
						$this->handleTah( StuffCfgFactory::getInstance( ), 'usr.ta.h.cpe', (int)$_POST['val'] );
					break;

					/**
					 * Provides actual cloud of contexts.
					 */
					case 'get':
						$this->getCdesCloud( StuffConfig::T_STUFFCTX, $_POST['js_var'], $_POST['id'], $this->messages['cpeNoCtxs'] );
					break;

					/**
					 * Search projects
					 */
					case 'refresh':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
							$engine = new StuffProject( \io\creat\chassis\session::getInstance( )->getUid( ) );

							$results = $engine->SearchPrjPicker( $_POST['cpe_js_var'], $_POST['keywords'], StuffConfig::PRJPICKPAGESIZE, $_POST['page'], $_POST['order'], $_POST['dir'] );

							if ( $results )
							{
								$smarty->assignByRef( 'USR_LIST_DATA', $results );
								_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
								_smarty_wrapper::getInstance( )->render( );
							}
							else
							{
								if ( trim( $_POST['keywords'] ) != '' )
									$empty = new _list_empty( $this->messages['nomatch']['All'] );
								else
										$empty = new _list_empty( $this->messages['empty']['All'] );
								$empty->render( );
							}
					break;

					case 'load_prj':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';

						$engine = new StuffProject( \io\creat\chassis\session::getInstance( )->getUid( ) );

						$result = $engine->FromFragment( $_POST['cpe_js_var'], $_POST['id'] );

						$smarty->assignByRef( 'USR_LIST_DATA', $results );
						
						$smarty->assign( 'cell', $result['toRender'] );
						$smarty->assign( 'DESC', $result['desc'] );

						_smarty_wrapper::getInstance( )->setContent( APP_STUFF_UI . 'uicmp/x_project.html' );
						_smarty_wrapper::getInstance( )->render( );
					break;

					/*
					 * Check if proposed structure isn't cycled loop.
					 */
					case 'prj_check_loop':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
							$engine = new StuffProject( \io\creat\chassis\session::getInstance( )->getUid( ) );
							echo "" . (int)$engine->IsChild( (int)$_POST['pid'], (int)$_POST['sid'] );
					break;

					/**
					 * Collect new thing.
					 */
					case 'collect':
						require_once APP_STUFF_LIB . 'class.StuffCollector.php';
						$collector = new StuffCollector( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$collector->importXml( htmlspecialchars_decode( $_POST['data'] ) );
						if ( $collector->add( ) )
							echo "OK";
						else
							echo "KO";
					break;

					/**
					 * Process stuff to another box - Go forward.
					 */
					case 'process':
						require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
						$processor = new StuffProcessor( \io\creat\chassis\session::getInstance( )->getUid( ), $_POST['sid'] );
							$processor->ImportXml( htmlspecialchars_decode( $_POST['data'] ) );
							$processor->add( );
					break;

					case 'edit':
						require_once APP_STUFF_LIB . 'class.StuffEditor.php';
						$editor = new StuffEditor( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$editor->ImportXml( htmlspecialchars_decode( $_POST['data'] ) );
							$editor->save( );
					break;

					/*
					 * Provide HTML table with stuff's history and XHTML fragment with most recent
					 * record data. Id of stuff is taken from $_POST['id'].
					 */
					case 'history_load':
						require_once APP_STUFF_LIB . 'class.StuffHistory.php';
							$history = new StuffHistory( \io\creat\chassis\session::getInstance( )->getUid( ), $_POST['id'] );
							$smarty->assign( 'HISTORY', $history->ExportArray( ) );
							$smarty->assign( 'DATA', $history->ExportLastData( ) );
							// continues to next case as subtasks are part of delivered content

					/*
					 * Provide HTML content for actual thing subtasks as separate content.
					 */
					case 'subtasks_load':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
						$project = new StuffProject( \io\creat\chassis\session::getInstance( )->getUid( ) );
							$smarty->assign( 'SUBTASKS', $project->Subtasks( $_POST['cpe_js_var'], $_POST['id'] ) );
							$smarty->assign( 'JS_VAR', $_POST['cpe_js_var'] );
							_smarty_wrapper::getInstance( )->setContent( APP_STUFF_UI . 'uicmp/x_history.html' );
							_smarty_wrapper::getInstance( )->render( );
					break;

					/*
					 * Provide XML document with data of one particular record
					 */
					case 'load':
						require_once APP_STUFF_LIB . 'class.StuffEditor.php';
							$editor = new StuffEditor( \io\creat\chassis\session::getInstance( )->getUid( ) );
								echo $editor->FragmentXml( $_POST['id'], $_POST['seq'] );
					break;
				}
			break;

			/**
			 * Handling Lifegoals webwidget actions.
			 */
			case 'goals':
				switch ($_POST['method'])
				{
					/**
					 * Provides cloud of Lifegoals.
					 */
					case 'refresh':
						$this->goals = new Goals( );
						_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'WWG_GOALS_MSG', $this->messages );
						$smarty->display( APP_STUFF_UI . '_wwg.Goals.ajx.html' );
					break;
				
					/**
					 * Saves new weight for item.
					 */
					case 'set_weight':
						$this->goals = new Goals( );
						$this->goals->setWeight( );
					break;
				}
			break;
		}
	}	

	/**
	 * Implements interface method.
	 * 
	 * @param sem_collection $coll
	 * @return boolean
	 */
	public function chkSemCollection ( &$coll )
	{		
		return true;	// nothing can go wrong
	}

	/**
	 * Set data from SEM collection using application specific settings
	 * instance.
	 * 
	 * @param type $coll 
	 */
	public function setSemCollection ( &$coll )
	{
		/**
		 * Process Lifegoals settings.
		 */
		if ( $atom = $coll->getById( 'usr.goals.on' ) )
			StuffCfgFactory::getInstance( )->saveOne( 'usr.goals.on', $atom->getValue( ) );
		if ( $atom = $coll->getById( 'usr.goals.box' ) )
			StuffCfgFactory::getInstance( )->saveOne( 'usr.goals.box', $atom->getValue( ) );
		
		/**
		 * Save Algorithm for boxes color.
		 */
		if ( $atom = $coll->getById( 'usr.alg' ) )
			StuffCfgFactory::getInstance( )->saveOne( 'usr.alg', $atom->getValue( ) );

		/**
		 * Time presets for CPE form.
		 */
		if ( $atom = $coll->getById( 'usr.cpe.times' ) )
			StuffCfgFactory::getInstance( )->saveOne( 'usr.cpe.times', $atom->getValue( ) );
		if ( $atom = $coll->getById( 'usr.cpe.sample' ) )
			StuffCfgFactory::getInstance( )->saveOne( 'usr.cpe.sample', $atom->getValue( ) );
	}
}

?>