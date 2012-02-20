<?PHP

// vim: ts=4

/**
 * @file class.StuffSearchBoxes.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'list/_list_builder.php';

require_once APP_STUFF_LIB . 'class.StuffSearch.php';

/**
 * Search backend for whole Stuff application boxes. Ugliest piece of code in
 * the solution.
 *
 * @todo icons in lists have a lot in common, centralize their composition
 */
class StuffSearchBoxes extends StuffSearch
{
	/*
	 * Advanced search display modes.
	 */
	const ADVSRCHDISP_LIST = 'list';			// simple list as search in boxes
	const ADVSRCHDISP_TREE = 'tree';			// grouping by contexts

	/*
	 * Fields to search in for advanced search.
	 */
	const ADVSRCHFIELD_NAME = self::F_STUFFNAME;
	const ADVSRCHFIELD_DESC = self::F_STUFFDESC;
	const ADVSRCHFIELD_CTX  = 'ctxs';
	const ADVSRCHFIELD_ALL  = 'All';
	
	/**
	 * Constructor.
	 *
	 * @param <int> $UID user Id
	 */
	public function __construct ( $UID ) { parent::__construct( $UID ); }

	/**
	 * Main search method for Schedule events.
	 *
	 * @param <int> $pageSize maximum size of page
	 * @param <string> $keyword search phrase
	 * @param <int> $page actual page to view
	 * @param <string> $order field to order list by
	 * @param <string $dir direction of ordering (ASC or DESC)
	 * @return <mixed> array with data for list, false on zero matches
	 */
	public function srchSchedule ( $cpe_js_var, $pageSize, $keyword, $page, $order, $dir )
	{
		/**
		 * Read ordering information and sort it out. Default ordering is
		 * descending by entry time frame.
		 */
		if ( $dir != 'ASC' ) $dir = 'DESC';

		switch ( $order )
		{
			case self::F_STUFFPRIORITY:
			case self::F_STUFFNAME:
				$orderBy = "`" . $order . "` " . $dir;
			break;

			/**
			 * Default ordering is by time frame for the event.
			 */
			case 'timeframe':
			default:
				$order = 'timeframe';

				if ( $dir == 'DESC' )
					$orderBy = "`date` DESC,`" . self::F_STUFFTIMESET . "` ASC,`time` DESC";
				else
					$orderBy = "`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC";
			break;
		}

		/**
		 * Initialize framework list builder.
		 */
		$builder = new _list_builder( $this->app->getVcmpSearchId( 'Schedule' ) );
			$builder->registerJumper( StuffListCell::MAN_STUFFDATETIME );

			$builder->addField( 'timeframe', $this->messages['schTimeFrame'], self::LIST_HDRW_TIMEFRAME, 2, 'left', true, ( $order == 'timeframe' ), $dir );
			$builder->addField( self::F_STUFFBOX, $this->messages['schInBox'], self::LIST_HDRW_BOX, 1, '', false );
			$builder->addField( self::F_STUFFPRIORITY, $this->messages['inboxListPriority'], self::LIST_HDRW_PRIORITY, 1, '', true, ( $order == self::F_STUFFPRIORITY ), $dir );
			$builder->addField( self::F_STUFFNAME, $this->messages['schAppointment'], '*', 1, '', true, ( $order == self::F_STUFFNAME ), $dir );
			$builder->addField( 'parent', $this->messages['prjParent'], self::LIST_HDRW_PARENT, 1, '', false );
			$builder->addField( '__2m', '', self::LIST_HDRW_ICON, 1, '', false );
			$builder->addField( '__done', '', self::LIST_HDRW_ICON, 1, '', false );
			$builder->addField( '__rem', '', self::LIST_HDRW_ICON, 1, '', false );

		/**
		 * Prepare search phrase.
		 */
		if ( trim( $keyword ) != '' )
			$where = "AND ( `" . self::F_STUFFNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
							OR `" . self::F_STUFFDESC . "` LIKE \"%" . _db_escape( $keyword ) . "%\" )";
		else
			$where = '';

		/*
		 * Compute paging.
		 */
		$itemCount = _db_1field( "SELECT COUNT(*) FROM ( SELECT * FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "` WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
																											GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC ORDER BY `" . self::F_STUFFSEQ . "` DESC) boxes
																								GROUP BY `" . self::F_STUFFSID . "`) noDnTh
																						WHERE `" . self::F_STUFFBOX . "` <> \"Ar\"  AND `" . self::F_STUFFDATESET . "` <> 0 {$where}
														" );

		$pageCount = ceil( $itemCount / $pageSize );
		if ( $page > $pageCount )
			$page = $pageCount;
		elseif ( $page < 1 )
			$page = 1;

		$firstItem = ( $page - 1 ) * $pageSize;

		$builder->computePaging( $pageSize, $itemCount, $page, $pageCount, n7_globals::settings( )->get( 'usr.lst.pagerhalf' ));

		/*
		 * Remember list configuration into database.
		 */
		StuffCfgFactory::getCfg( 'usr.lst.Schedule')->save( $keyword, $order, $dir, (int)$page );

		/**
		 * No match.
		 */
		if ( $itemCount < 1 )
			return false;

		/*
		 * Extract lines.
		 */
		$res = _db_query( "SELECT `" . self::F_STUFFBOX . "`,`" . self::F_STUFFSID . "`,`" . self::F_STUFFPRIORITY . "`,`" . self::F_STUFFNAME . "`,`" . self::F_STUFFDESC . "`,
								DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time`,
								`" . self::F_STUFFDATEVAL . "`,`" . self::F_STUFFTIMESET . "`,`" . self::F_STUFFTIMEVAL . "`,`" . self::F_STUFFCTXS . "`,
								`" . self::F_STUFFFLAGS . "`,`" . self::F_STUFFDATA . "`,`prj`,`parentName`,`parentId`
										FROM ( SELECT *, CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`
														FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj,
																		`parent`.`" . self::F_STUFFSID . "` as parentId,`parent`.`" . self::F_STUFFNAME . "` as parentName
																	FROM `" . self::T_STUFFBOXES . "`
																	JOIN `" . self::T_STUFFINBOX . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFSID . "` )
																	LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
																	LEFT JOIN `" . self::T_STUFFPROJECTS . "` parent ON ( `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFPID . "` = `parent`.`" . self::F_STUFFSID . "` )
																	WHERE `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
																	GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC ORDER BY `" . self::F_STUFFSEQ . "` DESC) boxes
														GROUP BY `" . self::F_STUFFSID . "`) noDnTh
										WHERE `" . self::F_STUFFBOX . "` <> \"Ar\" AND `" . self::F_STUFFDATESET . "` <> 0 {$where}							
								ORDER BY {$orderBy} LIMIT " . _db_escape( $firstItem ) . "," . _db_escape( $pageSize ) );

		if ( $res  && _db_rowcount( $res ) )
		{
			$this->Contexts( );
			while ( $row = _db_fetchrow ( $res ) )
			{
				$tf		= $this->FuzzyTimeframe( true, $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
				$class	= ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
				$class	.= ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );
				$ctx	= $this->badges( $row[self::F_STUFFCTXS] );

				if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
					$row[self::F_STUFFDESC] = $this->SysDataDetails( $row[self::F_STUFFDATA] );

				/**
				 * Common part of Javascript code for icons actions.
				 */
				$js_common = "var data = new Array();data['id']=" . $row[self::F_STUFFSID] . ";data['client_var']=_uicmp_lookup.lookup('" . $this->app->getVcmpSearchId( 'Schedule' ) . "');";

									/* Timeframe */
				$builder->addRow(	new _list_cell(	_list_cell::DateTime(	$tf['date'],
																			$tf['time'],
																			$class ),
													StuffListCell::MAN_STUFFDATETIME ),

									/* Box */
									new _list_cell(	_list_cell::Text(	$this->messages["box" . $row[self::F_STUFFBOX]],
																		$class )/*,
													_list_cell::MAN_DEFAULT */),

									/* Priority indicator */
									new _list_cell(	StuffListCell::StuffPriority(	$row[self::F_STUFFPRIORITY],
																					$class ),
													StuffListCell::MAN_STUFFPRIORITY ),

									/* Task description and details */
									new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																		$this->encode( $row[self::F_STUFFDESC] ),
																		$ctx,
																		$class,
																		$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );",
																		( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
													_list_cell::MAN_DECO ),

									/* Parent task (project) */
									new _list_cell(	_list_cell::deco(	$row['parentName'],
																		'',
																		null,
																		$class,
																		$cpe_js_var . ".process( " . $row['parentId'] . " );" ),
													_list_cell::MAN_DECO ),

									/* Actions */
									new _list_cell(	_list_cell::Code(	$js_common . "data['label']='F';_stuff_archive(data);",
																		$this->messages['arAltFinished'],
																		$class ),
													StuffListCell::MAN_STUFFICODONE ),
									new _list_cell(	_list_cell::Code(	$js_common . "data['label']='2';_stuff_archive(data);",
																		$this->messages['arAltMinutes'],
																		$class ),
													StuffListCell::MAN_STUFF2M ),
									new _list_cell(	_list_cell::Code(	$js_common . "data['label']='G';_stuff_archive(data);",
																		$this->messages['arAltGarbage'],
																		$class ),
													_list_cell::MAN_ICONREMOVE ) );
			}
		}
		return $builder->export( );
	}

	/**
	 * Main search method for another boxes.
	 *
	 * @param box table identifier of box ('Na', 'Wf', ...)
	 * @param pageSize maximum size of page
	 * @param keyword search phrase
	 * @param page actual page to view
	 * @param order field to order list by
	 * @param dir direction of ordering
	 *
	 * @return array with data for list, false on zero matches
	 */
	public function srchBox ( $box, $cpe_js_var, $pageSize = 20, $keyword = '', $page = 1, $order = self::F_STUFFRECORDED, $dir = 'DESC' )
	{
		if ( (int)$page < 1 )
			$page = 1;
		
		if ( $dir != 'ASC' ) $dir = 'DESC';

		switch ( $order )
		{
			case 'timeframe':
				if ( $dir == 'DESC' )
					$orderBy = "`" . self::F_STUFFDATESET . "` ASC,`date` DESC,`" . self::F_STUFFTIMESET . "` ASC,`time` DESC";
				else
					$orderBy = "`" . self::F_STUFFDATESET . "` DESC,`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC";
				$order = 'timeframe';
			break;

			default:
				$order = self::F_STUFFRECORDED;
			case self::F_STUFFRECORDED:
			case self::F_STUFFPRIORITY:
			case self::F_STUFFNAME:
			
				$orderBy = "`" . $order . "` " . $dir;
			break;
		}

		switch ($box)
		{
			case self::E_STUFFBOX_INBOX:
				$taskfield = $this->messages['naListThing'];
			break;
			case self::E_STUFFBOX_WF:
				$taskfield = $this->messages['naListWf'];
			break;

			case self::E_STUFFBOX_SD:
				$taskfield = $this->messages['naListSd'];
			break;

			case self::E_STUFFBOX_AR:
				$taskfield = $this->messages['naListComment'];
			break;

			case self::E_STUFFBOX_NA:
			default:
				$taskfield = $this->messages['naListAction'];
			break;
		}

		$builder = new _list_builder( $this->app->getVcmpSearchId( $box ) );
			$builder->registerJumper( StuffListCell::MAN_STUFFDATETIME );

			if ( $box == self::E_STUFFBOX_AR )
				$builder->addField( '__chbox', '', 0, 1, '', false );

			$builder->addField( self::F_STUFFRECORDED, ( $box=='Inbox' ) ? $this->messages['inboxListRecorded'] : $this->messages['inboxListMoved'], self::LIST_HDRW_RECORDED, /*( $box == 'Ar' ) ? 2 :*/ 1, 'left', true, ( $order == self::F_STUFFRECORDED ), $dir );
			$builder->addField( self::F_STUFFPRIORITY, $this->messages['inboxListPriority'], self::LIST_HDRW_PRIORITY, 1, '', true, ( $order == self::F_STUFFPRIORITY ), $dir );
			$builder->addField( self::F_STUFFNAME, $taskfield, '*', 1, '', true, ( $order == self::F_STUFFNAME ), $dir );
			$builder->addField( 'timeframe', $this->messages['schTimeFrame'], self::LIST_HDRW_TIMEFRAME, 2, '', true, ( $order == 'timeframe' ), $dir );
			$builder->addField( 'parent', $this->messages['prjParent'], self::LIST_HDRW_PARENT, 1, '', false );
			if ( $box != self::E_STUFFBOX_INBOX ) $builder->addField( 'task', $this->messages['naTask'], self::LIST_HDRW_PARENT, 1, '', false );


			if ( $box != self::E_STUFFBOX_AR )
			{
				$builder->addField( '__done', '', self::LIST_HDRW_ICON, 1, '', false );
				$builder->addField( '__2m', '', self::LIST_HDRW_ICON, 1, '', false );
			}
			
			$builder->addField( '__rem', '', self::LIST_HDRW_ICON, 1, '', false );

		if ( trim( $keyword ) != '' )
			$where = "AND ( sq2.`" . self::F_STUFFNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
							OR sq2.`" . self::F_STUFFDESC . "` LIKE \"%" . _db_escape( $keyword ) . "%\" )";
		else
			$where = '';

		$itemCount = _db_1field( "SELECT COUNT(*)
									FROM ( SELECT *
											FROM ( SELECT *
												FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "`
																GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
																ORDER BY `" . self::F_STUFFSEQ . "` DESC
													 ) sq1
											GROUP BY `" . self::F_STUFFSID . "` DESC ) sq2
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\" {$where}
														AND `" . self::F_STUFFBOX . "` = \"" . _db_escape( $box ) . "\"
												) sq3 " );

		$pageCount = ceil( $itemCount / $pageSize );

		if ( $page > $pageCount )
			$page = $pageCount;
		elseif ( $page < 1 )
			$page = 1;

		$firstItem = ( $page - 1 ) * $pageSize;

		$builder->ComputePaging( $pageSize, $itemCount, $page, $pageCount, n7_globals::settings( )->get( 'usr.lst.pagerhalf' ));

		/*
		 * Remember list configuration into database.
		 */
		StuffCfgFactory::getCfg( 'usr.lst.' . $box )->save( $keyword, $order, $dir, (int)$page );

		if ( $itemCount < 1 )
			return false;

		$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
							( SELECT inbox.`" . self::F_STUFFNAME . "` AS task,sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,sq1.`prj`,`parentId`,`parentName`
								FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj,CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`,`parent`.`" . self::F_STUFFSID . "` as parentId,`parent`.`" . self::F_STUFFNAME . "` as parentName
												FROM `" . self::T_STUFFBOXES . "`
												JOIN `" . self::T_STUFFINBOX . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFSID . "` )
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` parent ON ( `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFPID . "` = `parent`.`" . self::F_STUFFSID . "` )
												GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
												ORDER BY `" . self::F_STUFFSEQ . "` DESC
									 ) sq1
									LEFT JOIN `" . self::T_STUFFBOXES . "` AS inbox ON ( inbox.`" . self::F_STUFFSID . "` = sq1.`" . self::F_STUFFSID . "` AND inbox.`" . self::F_STUFFSEQ . "` = 0 )
									WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
								WHERE	sq2.`" . self::F_STUFFBOX . "` = \"" . _db_escape( $box ) . "\" {$where}
								ORDER BY {$orderBy}
								LIMIT " . _db_escape( $firstItem ) . "," . _db_escape( $pageSize ) );


		if ( $res  && _db_rowcount( $res ) )
		{
			$this->Contexts( );

			/*
			 * Build batch processing form for Archive.
			 */
			//if ( $box == self::E_STUFFBOX_AR )
				//$form = new _list_form( );

			while ( $row = _db_fetchrow ( $res ) )
			{
				if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
					$comment = $this->SysDataDetails( $row[self::F_STUFFDATA] );
				else
					$comment = $row[self::F_STUFFDESC];

				$tf		= $this->FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
				$class	= ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
				$class	.= ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );
				$ctx	= $this->Badges( $row[self::F_STUFFCTXS] );

				$recorded	= new _list_cell(	_list_cell::Text(	$this->FuzzyDateTime( _tz_transformation( $row[self::F_STUFFRECORDED] ) ),
																	$class ),
												_list_cell::MAN_DATEORTIME );
				
				$priority	= new _list_cell(	StuffListCell::StuffPriority(	$row[self::F_STUFFPRIORITY], $class ),
												StuffListCell::MAN_STUFFPRIORITY );

				$name		= new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																	$this->encode( $comment ),
																	$ctx,
																	$class,
																	$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );",
																	( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
												_list_cell::MAN_DECO );
				
				$frame		= new _list_cell(	_list_cell::DateTime(	$tf['date'],
																		$tf['time'],
																		$class ),
												StuffListCell::MAN_STUFFDATETIME );
				
				$parent		= new _list_cell(	_list_cell::deco(	$row['parentName'],
																	'',
																	null,
																	$class,
																	$cpe_js_var . ".process( " . $row['parentId'] . " );" ),
												_list_cell::MAN_DECO );
				
				$original	= new _list_cell(	_list_cell::deco(	$row['task'],
																	'',
																	null,
																	$class,
																	$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );" ),
												_list_cell::MAN_DECO );

				/**
				 * Common part of Javascript code for icons actions.
				 */
				$js_common = "var data = new Array();data['id']=" . $row[self::F_STUFFSID] . ";data['client_var']=_uicmp_lookup.lookup('" . $this->app->getVcmpSearchId( $box ) . "');";

				/**
				 * Action icons.
				 */
				$archive	= ( ( $box != self::E_STUFFBOX_AR )	?	new _list_cell(	_list_cell::Code(	$js_common . "data['label']='F';_stuff_archive(data);",
																										$this->messages['arAltFinished'],
																										$class ),
																					StuffListCell::MAN_STUFFICODONE )
																:	new _list_cell(	_list_cell::Text(	'', $class ) ) );
				
				$twomin		= ( ( $box != self::E_STUFFBOX_AR )	?	new _list_cell(	_list_cell::Code(	$js_common . "data['label']='2';_stuff_archive(data);",
																										$this->messages['arAltMinutes'],
																										$class ),
																					StuffListCell::MAN_STUFF2M )
																:	new _list_cell(	_list_cell::Text(	'', $class ) ) );

				$remove		= ( ( $box == self::E_STUFFBOX_AR )	?	new _list_cell(	_list_cell::Code(	$js_common . "var yes = new _sd_dlg_bt ( _stuff_purge, '{$this->messages['arBtYes']}', data );var no = new _sd_dlg_bt ( null, '{$this->messages['arBtNo']}', null );_wdg_dlg_yn.show( '{$this->messages['arWarning']}', '" . sprintf( $this->messages['arQuestion'], Wa::JsStringEscape( $row[self::F_STUFFNAME], ENT_QUOTES ) ) . "', yes, no );",
																										$this->messages['arAltRemove'],
																										$class ),
																					_list_cell::MAN_ICONREMOVE )
																:	new _list_cell(	_list_cell::Code(	$js_common . "data['label']='G';_stuff_archive(data);",
																										$this->messages['arAltGarbage'],
																										$class ),
																					_list_cell::MAN_ICONREMOVE ) );
								
				if ( $box == self::E_STUFFBOX_INBOX )
					$builder->AddRow( $recorded, $priority, $name, $frame, $parent, $archive, $twomin, $remove);
				elseif ( $box == self::E_STUFFBOX_AR )
					$builder->AddRow( new _list_cell(_list_cell::chkbox( '_ar_itm_' . $row[self::F_STUFFSID], $row[self::F_STUFFSID] ), _list_cell::MAN_CHECKBOX ), $recorded, $priority, $name, $frame, $parent, $original, $remove );
				else
					$builder->AddRow( $recorded, $priority, $name, $frame, $parent, $original, $archive, $twomin, $remove );
			}

			/* Render form items for Archive box to provide interface for batch processing (e.g. removal). */
			if ( $box == self::E_STUFFBOX_AR )
			{
				$form = $builder->getBp( );
				$form->addAction( $this->messages['arBpRemove'], '_stuff_purge_batch', $this->messages['arBpQuestion'] );
			}

		}

		return $builder->export( );
	}


	/**
	 * Backend for Advanced Search feature. Results structure may vary by
	 * display mode.
	 *
	 * @param <type> $keyword search phrase
	 * @param <type> $box box Id to search in, empty string for search in all boxes
	 * @param <type> $field field of record to be searched in
	 * @param <type> $context Context Id to limit query, empty string or zero for any (even none) context
	 * @param <type> $display display mode constant
	 * @param <type> $showContexts whether context badges should be displayed in results
	 * @param <type> $pageSize size of page to be displayed, might be applicable only for certain display modes
	 * @param <type> $page page to display, applicable similary to $pageSize
	 * @param <type> $order table field to order by
	 * @param <type> $dir direction of ordering
	 */
	public function AdvSearch ( $cpe_js_var, $keyword, $box, $field, $context, $display, $showContexts, $pageSize, $page, $order, $dir )
	{
		switch ( $display )
		{
			case self::ADVSRCHDISP_TREE:
				return $this->AdvSearchTree( $cpe_js_var, $keyword, $box, $field, $context, $showContexts );
			break;
			
			case self::ADVSRCHDISP_LIST:
			default:
				return $this->AdvSearchList( $cpe_js_var, $keyword, $box, $field, $context, $showContexts, $pageSize, $page, $order, $dir );
			break;
		}
	}

	/**
	 * Helper routine for self::AdvSearchList() and self::AdvSearchTree() to
	 * compose parts of where clause for searching.
	 *
	 * @param <type> $keyword search phrase
	 * @param <type> $box box Id to search in, empty string for search in all boxes
	 * @param <type> $field field of record to be searched in
	 * @param <type> $context Context Id to limit query, empty string or zero for any (even none) context
	 */
	private function AdvSearchCommonWhereClause ( $keyword, $box, $field, $context )
	{
		/*
		 * Compose box restrictions for WHERE clause.
		 */
		if ( ( $box != '' ) && ( $box != 'All' ) )
			$where['box'] = "`" . self::F_STUFFBOX . "` = \"" . _db_escape( $box ) . "\"";
		else
			$where['box'] = '';

		/*
		 * Search contexts table if needed and prepare WHERE clause part if
		 * needed.
		 */
		$whereFieldCtxs = '';
		if ( ( $field == self::ADVSRCHFIELD_ALL ) || ( $field == self::ADVSRCHFIELD_CTX ) )
		{
			$res = _db_query( "SELECT `" . _cdes::F_CTXID . "` FROM `" . self::T_STUFFCTX . "` WHERE `" . _cdes::F_CTXNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"" );
			//echo( "SELECT `" . Context::F_CTXID . "` FROM `" . self::T_STUFFCTX . "` WHERE `" . Context::F_CTXNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"" );

			if ( $res && _db_rowcount( $res ) )
			{
				while ( $row = _db_fetchrow( $res ) )
				{
					$ctxs[] = " `" . self::F_STUFFCTXS . "` LIKE \"%|" . $row[_cdes::F_CTXID] . "|%\" ";
				}
				$whereFieldCtxs = "(" . implode( "OR", $ctxs ) . ")";
			}
		}

		/*
		 * Context restriction.
		 */
		$where['ctx'] = '';
		if ( (int)$context != 0 )
			$where['ctx'] = " `" . self::F_STUFFCTXS . "` LIKE \"%|" . _db_escape( (int)$context ) . "|%\" ";

		/*
		 * Compose search part for WHERE clause.
		 */
		if ( trim( $keyword ) != '' )
		{
			switch ( $field )
			{
				case self::ADVSRCHFIELD_NAME:
				case self::ADVSRCHFIELD_DESC:
					$where['keywords'] = "sq2.`" . _db_escape( $field ) . "` LIKE \"%" . _db_escape( $keyword ) . "%\"";
					//$where['fieldCtxs'] = '';
				break;

				case self::ADVSRCHFIELD_CTX:
					$where['keywords'] = $whereFieldCtxs;
				break;

				case self::ADVSRCHFIELD_ALL:
				default:
					$where['keywords'] = "( sq2.`" . _db_escape( self::ADVSRCHFIELD_NAME ) . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
											OR sq2.`" . _db_escape( self::ADVSRCHFIELD_DESC ) . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
											" . ( ( $whereFieldCtxs != '') ? " OR " . $whereFieldCtxs : "" ) . " )";
				break;
			}
		}
		else
			$where['keywords'] = '';

		/*
		 * Compose WHERE clause.
		 */
		$whereComposed = '';
		$element = NULL;
		foreach( $where as $partial )
		{
			if ( trim( $partial ) != '' )
				$element[] = $partial;
		}

		if ( is_array( $element ) )
			$whereComposed = "(" . implode( " AND ", $element ) . ")";
		else
			$whereComposed = '';

		return $whereComposed;
	}

	/**
	 * Advanced search with LIST of results.
	 *
	 * @param <type> $keyword search phrase
	 * @param <type> $box box Id to search in, empty string for search in all boxes
	 * @param <type> $field field of record to be searched in
	 * @param <type> $context Context Id to limit query, empty string or zero for any (even none) context
	 * @param <type> $showContexts whether context badges should be displayed in results
	 * @param <type> $pageSize size of page to be displayed, might be applicable only for certain display modes
	 * @param <type> $page page to display, applicable similary to $pageSize
	 * @param <type> $order table field to order by
	 * @param <type> $dir direction of ordering
	 */
	private function AdvSearchList ( $cpe_js_var, $keyword, $box, $field, $context, $showContexts, $pageSize, $page, $order = self::F_STUFFRECORDED, $dir = 'DESC' )
	{
		if ( $dir != 'ASC' ) $dir = 'DESC';

		switch ( $order )
		{
			case 'timeframe':
				if ( $dir == 'DESC' )
					$orderBy = "`" . self::F_STUFFDATESET . "` ASC,`date` DESC,`" . self::F_STUFFTIMESET . "` ASC,`time` DESC";
				else
					$orderBy = "`" . self::F_STUFFDATESET . "` DESC,`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC";

				$order = 'timeframe';
			break;

			case self::F_STUFFRECORDED:
			case self::F_STUFFPRIORITY:
			case self::F_STUFFNAME:
				$orderBy = "`" . $order . "` " . $dir;
			break;

			default:
				$order = self::F_STUFFRECORDED;
				$orderBy = "`" . $order . "` " . $dir;
			break;
		}

		$builder = new _list_builder( $this->app->getVcmpSearchId( 'All' ) );
			$builder->registerJumper( StuffListCell::MAN_STUFFDATETIME );
			

			$builder->addField( self::F_STUFFBOX, $this->messages['editorHistoryBoxName'], self::LIST_HDRW_BOX, 1, 'left', false);
			$builder->addField( self::F_STUFFRECORDED, $this->messages['inboxListMoved'], self::LIST_HDRW_RECORDED,  1, 'left', true, ( $order == self::F_STUFFRECORDED ), $dir );
			$builder->addField( self::F_STUFFPRIORITY, $this->messages['inboxListPriority'], self::LIST_HDRW_PRIORITY, 1, '', true, ( $order == self::F_STUFFPRIORITY ), $dir );
			$builder->addField( self::F_STUFFNAME, $this->messages['naListThing'], '*', 1, '', true, ( $order == self::F_STUFFNAME ), $dir );
			$builder->addField( 'timeframe', $this->messages['schTimeFrame'], self::LIST_HDRW_TIMEFRAME, 2, '', true, ( $order == 'timeframe' ), $dir );
			$builder->addField( 'parent', $this->messages['prjParent'], self::LIST_HDRW_PARENT, 1, '', false );
			$builder->addField( 'task', $this->messages['naTask'], self::LIST_HDRW_PARENT, 1, '', false );

		$whereComposed = $this->AdvSearchCommonWhereClause( $keyword, $box, $field, $context );

		$itemCount = _db_1field( "SELECT COUNT(*)
									FROM ( SELECT *
											FROM ( SELECT *
												FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "`
																GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
																ORDER BY `" . self::F_STUFFSEQ . "` DESC
													 ) sq1
											GROUP BY `" . self::F_STUFFSID . "` DESC ) sq2
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
												" . ( ( $whereComposed != '' ) ? " AND " . $whereComposed : "" ) . "
												) sq3 " );

		$pageCount = ceil( $itemCount / $pageSize );

		if ( $page > $pageCount )
			$page = $pageCount;
		elseif ( $page < 1 )
			$page = 1;

		$firstItem = ( $page - 1 ) * $pageSize;

		$builder->ComputePaging( $pageSize, $itemCount, $page, $pageCount, n7_globals::settings( )->get( 'usr.lst.pagerhalf' ));

		/*
		 * Remember list configuration into database.
		 */
		StuffCfgFactory::getCfg( 'usr.lst.All' )->save( $keyword, $order, $dir, (int)$page, $box, $field, $context, self::ADVSRCHDISP_LIST, $showContexts );

		if ( $itemCount < 1 )
			return false;
		
		$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
							( SELECT inbox.`" . self::F_STUFFNAME . "` AS task,sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,sq1.prj,sq1.parentId,sq1.parentName
								FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj,CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`,`parent`.`" . self::F_STUFFSID . "` as parentId,`parent`.`" . self::F_STUFFNAME . "` as parentName
												FROM `" . self::T_STUFFBOXES . "`
												JOIN `" . self::T_STUFFINBOX . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFSID . "` )
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` parent ON ( `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFPID . "` = `parent`.`" . self::F_STUFFSID . "` )
												GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
												ORDER BY `" . self::F_STUFFSEQ . "` DESC
									 ) sq1
									LEFT JOIN `" . self::T_STUFFBOXES . "` AS inbox ON ( inbox.`" . self::F_STUFFSID . "` = sq1.`" . self::F_STUFFSID . "` AND inbox.`" . self::F_STUFFSEQ . "` = 0 )
									WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
								 " . ( ( $whereComposed != '' ) ? "WHERE " . $whereComposed : "" ) . "
								ORDER BY {$orderBy}
								LIMIT " . _db_escape( $firstItem ) . "," . _db_escape( $pageSize ) );


		if ( $res  && _db_rowcount( $res ) )
		{
			$this->Contexts( );

			while ( $row = _db_fetchrow ( $res ) )
			{
				$ctx = NULL;
				if ( (int)$showContexts == 1 )
					$ctx = $this->Badges( $row[self::F_STUFFCTXS] );
				$tf = self::FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
				$class = ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
				$class .= ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );

				if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
					$row[self::F_STUFFDESC] = $this->SysDataDetails( $row[self::F_STUFFDATA] );

				$builder->AddRow(	new _list_cell(	_list_cell::Text(	$this->messages["editorHistoryBox" . $row[self::F_STUFFBOX]],
																		$class ),
													_list_cell::MAN_DEFAULT ),

									new _list_cell(	_list_cell::Text(	$this->FuzzyDateTime( _tz_transformation( $row[self::F_STUFFRECORDED] ) ),
																		$class ),
													_list_cell::MAN_DATEORTIME ),

									new _list_cell(	StuffListCell::StuffPriority(	$row[self::F_STUFFPRIORITY],
																					$class ),
													StuffListCell::MAN_STUFFPRIORITY ),
					
									new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																		$this->encode( $row[self::F_STUFFDESC] ),
																		$ctx,
																		$class,
																		$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );",
																		( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
													_list_cell::MAN_DECO ),

									new _list_cell(	_list_cell::DateTime(	$tf['date'],
																			$tf['time'],
																			$class ),
													StuffListCell::MAN_STUFFDATETIME ),

									new _list_cell(	_list_cell::deco(	$row['parentName'],
																		'',
																		null,
																		$class,
																		$cpe_js_var . ".process( " . $row['parentId'] . " );" ),
													_list_cell::MAN_DECO ),

									new _list_cell(	_list_cell::deco(	$row['task'],
																		'',
																		null,
																		$class,
																		$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );" ),
													_list_cell::MAN_DECO ) );
			}

		}

		return $builder->export( );
	}

	/**
	 * Advanced search with stuff organized in TREE by contexts.
	 *
	 * @param <type> $keyword search phrase
	 * @param <type> $box box Id to search in, empty string for search in all boxes
	 * @param <type> $field field of record to be searched in
	 * @param <type> $context Context Id to limit query, empty string or zero for any (even none) context
	 * @param <type> $showContexts whether context badges should be displayed in results
	 */
	private function AdvSearchTree ( $cpe_js_var, $keyword, $box, $field, $context, $showContexts )
	{
		$tree = false;

		/*
		 * Remember list configuration into database.
		 */
		StuffCfgFactory::getCfg( 'usr.lst.All' )->save( $keyword, $order, $dir, (int)$page, $box, $field, $context, self::ADVSRCHDISP_TREE, $showContexts );
		
		/*
		 * Obtain ordered list of contexts and their details and iterate search.
		 */
		$this->contexts( );

		/*
		 * Main search condition.
		 */
		$whereComposed = $this->AdvSearchCommonWhereClause( $keyword, $box, $field, $context );

		/*
		 * Perform search for all contexts.
		 */
		if ( is_array( $this->contexts ) )
		{
			$woutWhere = null;

			foreach ( $this->contexts as $cid => $data)
			{
				/*
				 * This array is later used in this method to select stuff
				 * marked with none context.
				 */
				$woutWhere[] = " `" . self::F_STUFFCTXS . "` NOT LIKE \"%|" . _db_escape( $cid ) . "|%\" ";

				/*
				 * Throw all not matching contexts if context restriction was
				 * set in the form.
				 */
				if ( ( (int)$context != 0 ) && ( (int)$cid != (int)$context ) )
					continue;

				$itemCount = _db_1field( "SELECT COUNT(*)
									FROM ( SELECT *
											FROM ( SELECT *
												FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "`
																GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
																ORDER BY `" . self::F_STUFFSEQ . "` DESC
													 ) sq1
											GROUP BY `" . self::F_STUFFSID . "` DESC ) sq2
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\" AND `" . self::F_STUFFCTXS . "` LIKE \"%|" . _db_escape( $cid ) . "|%\"
												" . ( ( $whereComposed != '' ) ? " AND " . $whereComposed : "" ) . "
												) sq3 " );

				if ( (int)$itemCount != 0 )
				{
					$tree[$cid]['data'] = $data;
					$tree[$cid]['count'] = $this->messages['advSearchTreeCount']->ToString( $itemCount );
					
					$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
								( SELECT sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,sq1.prj
									FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj,CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime` FROM `" . self::T_STUFFBOXES . "`
													LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
													GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
													ORDER BY `" . self::F_STUFFSEQ . "` DESC
										 ) sq1
										WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
										GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
									WHERE `" . self::F_STUFFCTXS . "` LIKE \"%|" . _db_escape( $cid ) . "|%\"" . ( ( $whereComposed != '' ) ? " AND " . $whereComposed : "" ) . "
									ORDER BY `" . self::F_STUFFDATESET . "` DESC,`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC ");

					if ( $res && _db_rowcount( $res ) )
					{
						while ( $row = _db_fetchrow( $res ) )
						{
							$item = null;

							$tf = $this->FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
							$class = ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
							$class = ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );
							if ( (int)$showContexts == 1 )
								$ctx = $this->Badges( $row[self::F_STUFFCTXS] );

							if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
								$row[self::F_STUFFDESC] = $this->SysDataDetails( $row[self::F_STUFFDATA] );

							switch ( $row[self::F_STUFFBOX] )
							{
								case 'Na': $jsBoxTab = 'stNextActions'; break;
								case 'Wf': $jsBoxTab = 'stWaitingFor'; break;
								case 'Sd': $jsBoxTab = 'stSomeday'; break;
								case 'Ar': $jsBoxTab = 'stArchive'; break;
								case 'Inbox': default: $jsBoxTab = 'stInbox'; break;
							}

							
							$item['priority']	= new _list_cell(	StuffListCell::StuffPriority(	$row[self::F_STUFFPRIORITY],
																									$class ),
																	StuffListCell::MAN_STUFFPRIORITY );

							$item['name']		= new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																						$this->encode( $row[self::F_STUFFDESC] ),
																						$ctx,
																						$class,
																						$cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );",
																						( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
																	_list_cell::MAN_DECO );

							$item['due']		= new _list_cell(	_list_cell::DateTime(	$tf['date'],
																							$tf['time'],
																							$class ),
																	StuffListCell::MAN_STUFFDATETIME );

							$item['box']		= new _list_cell(	_list_cell::Text(	$this->messages["box" . $row[self::F_STUFFBOX]],
																						$class ),
																	_list_cell::MAN_DEFAULT );

							$tree[$cid]['items'][$row[self::F_STUFFSID]] = $item;
						}
					}

				}
			}
		}

		/*
		 * Try to extract any possible stuff without any context.
		 */
		if ( ( (int)$context == 0 ) && ( $field != self::ADVSRCHFIELD_CTX ) )
		{
			$cid = 'none';
			$whereComposed = $this->AdvSearchCommonWhereClause( $keyword, $box, $field, 0 );
			
			if ( is_array( $this->Contexts ) && is_array( $woutWhere ) )
			{
				if ( trim( $whereComposed ) != '' )
					$whereComposed .= "AND ";
				$whereComposed .= "(" . implode( " AND ", $woutWhere ) . ")";
			}

			$itemCount = _db_1field( "SELECT COUNT(*)
									FROM ( SELECT *
											FROM ( SELECT *
												FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "`
																GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
																ORDER BY `" . self::F_STUFFSEQ . "` DESC
													 ) sq1
											GROUP BY `" . self::F_STUFFSID . "` DESC ) sq2
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
												" . ( ( $whereComposed != '' ) ? " AND " . $whereComposed : "" ) . "
												) sq3 " );

			if ( (int)$itemCount != 0 )
			{
				//$tree[$CID] = $data;
				$tree[$cid]['count'] = $this->messages['advSearchTreeCountWout']->ToString( $itemCount );

				$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
							( SELECT sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,sq1.prj
								FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj, CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`
												FROM `" . self::T_STUFFBOXES . "`
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
												GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
												ORDER BY `" . self::F_STUFFSEQ . "` DESC
									 ) sq1
									WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
								  " . ( ( $whereComposed != '' ) ? " WHERE " . $whereComposed : "" ) . "
									ORDER BY `" . self::F_STUFFDATESET . "` DESC,`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC " );

				if ( $res && _db_rowcount( $res ) )
				{
					while ( $row = _db_fetchrow( $res ) )
					{
						$item = null;

						$tf = $this->FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
						$class = ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
						$classTask = ( $tf['today'] === true ) ? 'stuffListToday' : '' ;
						if ( (int)$showContexts == 1 )
							$ctx = $this->Badges( $row[self::F_STUFFCTXS] );
						//var_dump($ctx);

						if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
							$row[self::F_STUFFDESC] = $this->SysDataDetails( $row[self::F_STUFFDATA] );
					
						switch ( $row[self::F_STUFFBOX] )
						{
							case 'Na': $jsBoxTab = 'stNextActions'; break;
							case 'Wf': $jsBoxTab = 'stWaitingFor'; break;
							case 'Sd': $jsBoxTab = 'stSomeday'; break;
							case 'Ar': $jsBoxTab = 'stArchive'; break;
							case 'Inbox': default: $jsBoxTab = 'stInbox'; break;
						}


						$item['priority'] = new _list_cell( StuffListCell::StuffPriority( $row[self::F_STUFFPRIORITY], $class . " " . $classTask ), StuffListCell::MAN_STUFFPRIORITY );
						$item['name'] = new _list_cell( _list_cell::deco( $row[self::F_STUFFNAME], $this->encode( $row[self::F_STUFFDESC] ), $ctx, $row[self::F_STUFFSID], $class . " " . $classTask, ( (int)$row['prj'] != 0 ), "stuffShowTab( 'stFrmEdt' );frmEdtLoad( " . $row[self::F_STUFFSID] . " );frmEdtBackBox = 'All';stuffRenderBack( frmEdtBackBox, 'txtFrmEdtBack' );" ), _list_cell::MAN_DECO );
						$item['due'] = new _list_cell(_list_cell::DateTime( $tf['date'], $tf['time'], $class . " " . $classTask ), StuffListCell::MAN_STUFFDATETIME );
						$item['box'] = new _list_cell(_list_cell::Javascript( $this->messages["box" . $row[self::F_STUFFBOX]], "window.scroll(0,0);stuffShowTab( '{$jsBoxTab}' )" , $class . " " . $classTask ),_list_cell::MAN_JAVASCRIPT );

						$tree[$cid]['items'][$row[self::F_STUFFSID]] = $item;
					}
				}

			}
		}

		return $tree;
	}
}

?>
