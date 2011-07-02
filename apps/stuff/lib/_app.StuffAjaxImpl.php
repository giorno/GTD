<?php

/**
 * @file _app.StuffAjaxImpl.php
 * @author giorno
 *
 * Ajax server side implementation of Stuff application.
 */

require_once CHASSIS_LIB . 'ui/_smarty_wrapper.php';
require_once CHASSIS_LIB . 'list/_list_empty.php';

require_once N7_SOLUTION_LIB . 'sem/iface.SemApplicator.php';
require_once N7_SOLUTION_LIB . 'sem/sem_decoder.php';

require_once APP_STUFF_LIB . '_app.Stuff.php';
require_once APP_STUFF_LIB . '_wwg.Goals.php';

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
						$processor = new StuffProcessor( _session_wrapper::getInstance( )->getUid( ) );
							$processor->purgeBatch( $_POST['ids'] );
					break;

					/**
					 * Another 'hijack' method. Archives entry with appropriate
					 * label.
					 */
					case '_stuff_archive':
						require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
						$processor = new StuffProcessor( _session_wrapper::getInstance( )->getUid( ) );
							$processor->archive( $_POST['id'], $_POST['label'] );
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
								$engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );

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
									//var_dump($_POST);
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
				
				switch ( $_POST['method'] )
				{
					/**
					 * This comes from CDES search part, so it should be handled
					 * same way as normal search.
					 */
					case 'refresh':
						require_once CHASSIS_LIB . '_cdes.php';
						//$settings = new StuffSettings( );
						$cdes = new _cdes( _session_wrapper::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX, n7_globals::lang( ) );
						$cdes->display( StuffCfgFactory::getCfg( 'usr.lst.Contexts'), $_POST['id'], $_POST['cdes_ed'], n7_globals::settings( )->get( 'usr.lst.len' ), n7_globals::settings( )->get( 'usr.lst.pagerhalf' ), $_POST['keywords'], $_POST['page'], $_POST['order'], $_POST['dir'] );

						/*if ( $results !== false )
						{
							$smarty->assignByRef( 'USR_LIST_DATA', $results );
							_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
							_smarty_wrapper::getInstance( )->render( );
						}
						else
						{
							
						}*/
					break;

					/**
					 * Copy of standard UICMP logic handling resize event.
					 */
					case 'resize':
						$this->saveSize( (int)$_POST['size'] );
					break;

					/**
					 * Save context editor data.
					 */
					case 'save':
						require_once CHASSIS_LIB . '_cdes.php';
						$cdes = new _cdes( _session_wrapper::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX, n7_globals::lang( ) );
						if ( trim( $_POST['disp'] ) == '' )
							echo "e_format";
						elseif ( ( $_POST['ctx'] == 0 ) && ( $cdes->exists( $_POST['disp'] ) ) )
							echo "e_exists";
						elseif ( $cdes->add( $_POST['ctx'], $_POST['sch'], $_POST['disp'], $_POST['desc'] ) )
							echo "saved";
						else
							echo "e_unknown";
					break;

					/**
					 * Remove context.
					 */
					case 'remove':
						require_once CHASSIS_LIB . '_cdes.php';
						$cdes = new _cdes( _session_wrapper::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX, n7_globals::lang( ) );
						$cdes->remove( $_POST['ctx'] );
					break;
				}
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
						$tah = (int)$_POST['val'];
						$min = n7_globals::getInstance()->get( 'config' )->get( 'usr.ta.h.min');
						if ( $tah < $min )
							$tah = $min;
						
						StuffCfgFactory::getInstance( )->saveOne( 'usr.ta.h.cpe', $tah );
					break;

					/**
					 * Provides actual cloud of contexts.
					 */
					case 'get':
						require_once CHASSIS_LIB . '_cdes.php';
						require_once CHASSIS_LIB . 'uicmp/_uicmp_cdes_cloud.php';
						$cloud = new _uicmp_cdes_cloud( NULL, NULL, $_POST['js_var'], _cdes::allCtxs( _session_wrapper::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX ), $_POST['id'] );

						_smarty_wrapper::getInstance( )->getEngine( )->assignByRef( 'USR_UICMP_CMP', $cloud );
						_smarty_wrapper::getInstance( )->setContent( $cloud->getRenderer( ) );
						_smarty_wrapper::getInstance( )->render( );
					break;

					/**
					 * Search projects
					 */
					case 'refresh':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
							$engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );

							$results = $engine->SearchPrjPicker( $_POST['cpe_js_var'], $_POST['keywords'], StuffConfig::PRJPICKPAGESIZE, $_POST['page'], $_POST['order'], $_POST['dir'] );

							if ( $results )
							{
								$smarty->assignByRef( 'USR_LIST_DATA', $results );
								_smarty_wrapper::getInstance( )->setContent( CHASSIS_UI . '/list/list.html' );
								_smarty_wrapper::getInstance( )->render( );
							}

							/*if ( $results !== false )
							{
								$__SMARTY->assign( 'mFwListData', $results );
								$__SMARTY->display( CHASSIS_UI . '/list.html' );
							}
							else
							{
								if ( trim( $_POST['search'] ) != '' )
									$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoMatch'], "<span onClick=\"stuffBoxes[9].showAll( );\" class=\"_uicmp_blue_b\">" . $this->messages['btShowAll'] . "</span>", "<span onClick=\"stuffBoxes[9].focus( );\" class=\"_uicmp_blue_b\">" . $this->messages['noResultsChangePhrase'] . "</span>" ) );
								else
									$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsEmptyBox'], $boxName ) );

								$__SMARTY->display( 'x_empty.html' );
							}*/
					break;

					case 'load_prj':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';

						$engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );

						$result = $engine->FromFragment( $_POST['cpe_js_var'], $_POST['id'] );

						$smarty->assignByRef( 'USR_LIST_DATA', $results );

						
						$smarty->assign( 'cell', $result['toRender'] );
						$smarty->assign( 'DESC', $result['desc'] );
						//$smarty->assign( 'JS_VAR', $_POST['cpe_js_var'] );		// item action to show picker
						//$smarty->assign( 'FORM_ID', $_POST['form_id'] );	// to generate HTML IDs
						_smarty_wrapper::getInstance( )->setContent( APP_STUFF_UI . 'uicmp/x_project.html' );
						_smarty_wrapper::getInstance( )->render( );
					break;

					/*
					 * Check if proposed structure isn't cycled loop.
					 */
					case 'prj_check_loop':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
							$engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );
							echo "" . (int)$engine->IsChild( (int)$_POST['pid'], (int)$_POST['sid'] );
					break;

					/**
					 * Collect new thing.
					 */
					case 'collect':
						require_once APP_STUFF_LIB . 'class.StuffCollector.php';
						$collector = new StuffCollector( _session_wrapper::getInstance( )->getUid( ) );
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
						$processor = new StuffProcessor( _session_wrapper::getInstance( )->getUid( ), $_POST['sid'] );
							$processor->ImportXml( htmlspecialchars_decode( $_POST['data'] ) );
							$processor->add( );
					break;

					case 'edit':
						//var_dump($_POST);break;
						require_once APP_STUFF_LIB . 'class.StuffEditor.php';
						$editor = new StuffEditor( _session_wrapper::getInstance( )->getUid( ) );
							$editor->ImportXml( htmlspecialchars_decode( $_POST['data'] ) );
							$editor->save( );
					break;

					/*
					 * Provide HTML table with stuff's history and XHTML fragment with most recent
					 * record data. Id of stuff is taken from $_POST['id'].
					 */
					case 'history_load':
						require_once APP_STUFF_LIB . 'class.StuffHistory.php';
							$history = new StuffHistory( _session_wrapper::getInstance( )->getUid( ), $_POST['id'] );
							$smarty->assign( 'HISTORY', $history->ExportArray( ) );
							$smarty->assign( 'DATA', $history->ExportLastData( ) );
							// continues to next case as subtasks are part of delivered content

					/*
					 * Provide HTML content for actual thing subtasks as separate content.
					 */
					case 'subtasks_load':
						require_once APP_STUFF_LIB . 'class.StuffProject.php';
						$project = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );
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
							$editor = new StuffEditor( _session_wrapper::getInstance( )->getUid( ) );
								echo $editor->FragmentXml( $_POST['id'], $_POST['seq'] );
					break;
				}
			break;

			

			/*
			 * Move stuff to the Archive box.
			 */
		/*	case 'archive':
				require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
				$processor = new StuffProcessor( _session_wrapper::getInstance( )->getUid( ) );
					$processor->Archive( $_POST['SID'], $_POST['label'] );
			break;*/

			/*
			 * Definitive removal of stuff. This cannot be undone. All data from boxes
			 * are about to lost.
			 */
			/*case 'purgeStuff':
				require_once APP_STUFF_LIB . 'class.StuffProcessor.php';
				$processor = new StuffProcessor( _session_wrapper::getInstance( )->getUid( ) );
					$processor->Purge( $_POST['SID'] );
			break;*/

			/*
			 * Definitive removal of more than one stuff. Applicable only on archived
			 * items.
			 */
			

			/*
			 * Search projects and return list of projects
			 */
			/*case 'searchPrj':
				
			break;*/

			/*
			 * Provide list of items for Project to be picked from
			 */
			/*case 'searchPrjToPick':
				require_once APP_STUFF_LIB . 'class.StuffProject.php';
					$Engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );

					$results = $Engine->SearchPrjPicker( $_POST['search'], StuffConfig::PRJPICKPAGESIZE, $_POST['page'], $_POST['field'], $_POST['dir'] );

					if ( $results !== false )
					{
						$__SMARTY->assign( 'mFwListData', $results );
						$__SMARTY->display( CHASSIS_UI . '/list.html' );
					}
					else
					{
						if ( trim( $_POST['search'] ) != '' )
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoMatch'], "<span onClick=\"stuffBoxes[9].showAll( );\" class=\"_uicmp_blue_b\">" . $this->messages['btShowAll'] . "</span>", "<span onClick=\"stuffBoxes[9].focus( );\" class=\"_uicmp_blue_b\">" . $this->messages['noResultsChangePhrase'] . "</span>" ) );
						else
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsEmptyBox'], $boxName ) );

						$__SMARTY->display( 'x_empty.html' );
					}
			break;*/

			/*
			 * Project fragment (name, details) to be shown in form Project section
			 */
			/*case 'frmPrjFrag':
				require_once APP_STUFF_LIB . 'class.StuffProject.php';

					$engine = new StuffProject( _session_wrapper::getInstance( )->getUid( ) );

					$result = $engine->FromFragment( $_POST['id'] );
					$__SMARTY->assign( 'cell', $result['toRender'] );
					$__SMARTY->assign( 'DESC', $result['desc'] );
					$__SMARTY->assign( 'APP_STUFF_UI', APP_STUFF_UI );
					$__SMARTY->display( APP_STUFF_UI . 'x_project.html' );
			break;*/

			

			/*
			 * Search in other boxes.
			 */
			case 'searchInbox':
			case 'searchNa':
			case 'searchWf':
			case 'searchSd':
			case 'searchAr':
				//require_once APP_STUFF_LIB . 'class.StuffSearchBoxes.php';
					//$Engine = new StuffSearchBoxes( _session_wrapper::getInstance( )->getUid( ) );

					switch ( $_POST['action'] )
					{
						case 'searchInbox':
							$box = 'Inbox';
							$boxName = $this->messages['boxInbox'];
							$boxJsInstance = 'stuffBoxes[0]';
						break;

						case 'searchNa':
							$box = 'Na';
							$boxName = $this->messages['boxNextActions'];
							$boxJsInstance = 'stuffBoxes[2]';
						break;

						case 'searchWf':
							$box = 'Wf';
							$boxName = $this->messages['boxWaitingFor'];
							$boxJsInstance = 'stuffBoxes[3]';
						break;

						case 'searchSd':
							$box = 'Sd';
							$boxName = $this->messages['boxSomeday'];
							$boxJsInstance = 'stuffBoxes[4]';
						break;

						case 'searchAr':
							$box = 'Ar';
							$boxName = $this->messages['boxArchive'];
							$boxJsInstance = 'stuffBoxes[5]';
						break;
					}

					$results = $this->getSe( )->srchBox( $box, n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['search'], $_POST['page'], $_POST['field'], $_POST['dir'] );
					if ( $results !== false )
					{
						$__SMARTY->assign( 'mFwListData', $results );
						$__SMARTY->display( CHASSIS_UI . '/list.html' );
					}
					else
					{
						if ( trim( $_POST['search'] ) != '' )
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoMatch'], "<span onClick=\"{$boxJsInstance}.showAll( );\" class=\"_uicmp_blue_b\">" . $this->messages['btShowAll'] . "</span>", "<span onClick=\"{$boxJsInstance}.focus( );\" class=\"_uicmp_blue_b\">" . $this->messages['noResultsChangePhrase'] . "</span>" ) );
						else
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsEmptyBox'], $boxName ) );

						$__SMARTY->display( 'x_empty.html' );
					}
			break;

			

			

			/*
			 * Save context.
			 */
			/*case 'saveCtx':
				require_once APP_STUFF_LIB . 'class.StuffContext.php';
					$Context = new StuffContext( _session_wrapper::getInstance( )->getUid( ) );
					$Context->ImportXml( htmlspecialchars_decode( $_POST['data'] ) );
					if ( is_array( $Context->Errors ) )
					{
						echo implode( ',', $Context->Errors );
					}
					else
					{
						$Context->Add( );
						echo 'OK';
					}
			break;/

			/*
			 * Search/list contexts.
			 */
			/*case 'searchCtx':
				require_once APP_STUFF_LIB . 'class.StuffContext.php';
					$Context = new StuffContext( _session_wrapper::getInstance( )->getUid( ) );
					$results = $Context->Search( n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['search'], $_POST['page'], $_POST['field'], $_POST['dir'] );

					if ( $results !== false )
					{
						$__SMARTY->assign( 'mFwListData', $results );
						$__SMARTY->display( CHASSIS_UI . '/list.html' );
					}
					else
					{
						if ( trim( $_POST['search'] ) != '' )
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoMatch'], "<span onClick=\"stuffBoxes[6].showAll( );\" class=\"_uicmp_blue_b\">" . $this->messages['btShowAll'] . "</span>", "<span onClick=\"stuffBoxes[6].focus( );\" class=\"_uicmp_blue_b\">" . $this->messages['noResultsChangePhrase'] . "</span>" ) );
						else
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoContexts'], "<span onClick=\"stuffShowTab('stFrmCtx');ctxHelper.setModeCreate(  );\" class=\"_uicmp_blue_b\">" . $this->messages['editorCapNewCtx'] . "</span>" ) );

						$__SMARTY->display( 'x_empty.html' );
					}
			break;*/

			/*
			 * Permanently remove context.
			 */
			/*case 'removeCtx':
				require_once APP_STUFF_LIB . 'class.StuffContext.php';
					$Context = new StuffContext( _session_wrapper::getInstance( )->getUid( ) );
					$Context->Remove( $_POST['id'] );
			break;*/

			/*
			 * Provide cloud with all contexts.
			 */
			/*case 'loadContextsCloud':
				require_once APP_STUFF_LIB . 'class.StuffContext.php';
					$Context = new StuffContext( _session_wrapper::getInstance( )->getUid( ) );
					$contexts = $Context->All( $_POST['prefix'], $_POST['instance'] );

					if ( is_array( $contexts ) )
					{
						$__SMARTY->assign( 'DATA', $contexts );
						$__SMARTY->assign( 'PREFIX', $_POST['prefix'] );
					}
					else
					{
						$__SMARTY->assign( 'NOCONTEXTS', $__msgCommon['editorNoContexts'] );
					}

					$__SMARTY->display( APP_STUFF_UI . 'x_contexts.html' );
			break;*/

			/*
			 * Set weight of goal to given value.
			 */
			case 'setGoalWeight':
				$this->goals = new Goals( );
				$this->goals->setWeight( );
			break;

			/*
			 * Load contexts cloud for the form.
			 */
			case 'loadGoals':
				//var_dump($_POST );
				//break;
					$this->goals = new Goals( );
					//$__SMARTY->assign( 'LIFEGOALS', $this->getSe( )->Lifegoals( n7_globals::settings( )->get( 'Lifegoals' ), n7_globals::settings( )->get( 'LifegoalsBox' ) ) );
					$smarty->display( APP_STUFF_UI . '_wwg.Goals.ajx.html' );
			break;

			/*
			 * Perform advanced search.
			 */
			case 'advSearch':
					$results = $this->getSe( )->AdvSearch( $_POST['search'], $_POST['box'], $_POST['in'], $_POST['ctx'], $_POST['display'], $_POST['showCtxs'], n7_globals::settings( )->get( 'usr.lst.len' ), $_POST['page'], $_POST['field'],  $_POST['dir'] );

					if ( $results !== false )
					{
						$__SMARTY->assign( 'mFwListData', $results );
						switch ( $_POST['display'] )
						{
							case StuffSearchBoxes::ADVSRCHDISP_TREE:
								$__SMARTY->display( APP_STUFF_UI . '/x_advsrchfrmtree.html' );
							break;

							case StuffSearchBoxes::ADVSRCHDISP_LIST:
							default:
								$__SMARTY->display( CHASSIS_UI . '/list.html' );
							break;
						}

					}
					else
					{
						//$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['emptyBox'], $boxName ) );
						//
						if ( trim( $_POST['search'] ) != '' )
							$__SMARTY->assign( 'MESSAGE', sprintf( $this->messages['noResultsNoMatch'], "<span onClick=\"asShowAll( );\" class=\"_uicmp_blue_b\">" . $this->messages['btShowAll'] . "</span>", "<span onClick=\"stuffBoxes[7].focus( );\" class=\"_uicmp_blue_b\">" . $this->messages['noResultsChangePhrase'] . "</span>" ) );

						$__SMARTY->display( 'x_empty.html' );
					}
			break;

			/*
			 * Return SELECT box with prefilled contexts for Advanced search form.
			 */
			case 'advSearchCtxs':
				require_once N7_SOLUTION_LIB . 'class.GtdContext.php';
				require_once APP_STUFF_LIB . 'class.StuffConfig.php';

					$ADVSRCHFRM['selCtx'] = (string)$_POST['sel'];

					$ADVSRCHFRM['ctx']['0']  = $this->messages['advSrchAllCtxs'];
					$ctxs = _cdes::allCtxs( _session_wrapper::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX );
					if ( is_array( $ctxs ) )
					{
						foreach ( $ctxs as $CID => $data )
							$ADVSRCHFRM['ctx']["{$CID}"]  = $data['name'];
					}

					$__SMARTY->assign( 'ADVSRCHFRM', $ADVSRCHFRM );
					$__SMARTY->display( APP_STUFF_UI . 'x_advsrchfrmctxs.html' );
			break;

			default:
			break;
		}
	}	

	public function chkSemCollection ( $coll )
	{		
		return true;	// nothing can go wrong
	}

	public function setSemCollection ( $coll )
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