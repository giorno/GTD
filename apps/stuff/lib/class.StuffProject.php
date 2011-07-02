<?php

require_once APP_STUFF_LIB . 'class.StuffConfig.php';
require_once APP_STUFF_LIB . 'class.StuffSearch.php';
require_once APP_STUFF_LIB . 'class.StuffListCell.php';

/**
 * Compare method to be used in StuffProject::Subtasks() result sorting by
 * localized boxes' names.
 *
 * @param <array> $st1 subtask 1 array
 * @param <array> $st2 subtask 2 array
 */
function subtasksBoxCmp ( &$st1, &$st2 )
{

	if ( $st1['key1'] == $st2['key1'] )
		return strcasecmp( $st1['key2'], $st2['key2'] );

	/*
	 * Order pattern.
	 */
	$order = Array(	StuffConfig::E_STUFFBOX_INBOX,
					StuffConfig::E_STUFFBOX_NA,
					StuffConfig::E_STUFFBOX_WF,
					StuffConfig::E_STUFFBOX_SD,
					StuffConfig::E_STUFFBOX_AR );

	foreach ( $order as $box )
	{
		/*
		 * $st2 box has to been found yet, but $st1 box has, so it is lesser one.
		 */
		if ( $st1['key1'] == $box )
			return -1;

		/*
		 * $st1 box has to been found yet, but $st2 box has, so it is greater one.
		 */
		if ( $st2['key1'] == $box )
			return 1;
	}

	return 0;
}

/**
 * Object responsible for handling Projects feature.
 *
 * @author giorno
 */
class StuffProject extends StuffSearch
{


	/**
	 * Constructor.
	 */
	public function __construct ( $UID )
	{
		parent::__construct( $UID );
	}

	/**
	 * Method detects whether project is in subtree of given Stuff.
	 *
	 * @param <int> $PID StuffId of project
	 * @param <int> $SID StuffId of subtree root
	 */
	public function IsChild( $PID, $SID, $maxDepth = self::PRJMAXSUBTREEDEPTH )
	{
		if ( $maxDepth <= 0 )
		{
			// put stat trap here
			return true;
		}

		if ( ( $PID == 0 ) || ( $SID == 0 ) )
			return false;
		
		if ( $PID == $SID )
			return true;
		
		/*
		 * Check for subtree.
		 */
		$res = _db_query( "SELECT `" . self::F_STUFFSID . "` FROM `" . self::T_STUFFINBOX . "`
							WHERE `" . self::F_STUFFPID . "` = \"" . _db_escape( $SID ) . "\" AND
							`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"" );

		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				if ( $this->IsChild( $PID, $row[self::F_STUFFSID], $maxDepth - 1 ) === true )
					return true;
			}
		}


		return ( $maxDepth <= 0 );
	}

	/**
	 * Produce Javascrip code to be called upon onClick() event.
	 *
	 * @param $SID <int> StuffId
	 */
	private function JsOnClick ( $SID )
	{
		return "stuffShowTab( 'stFrmEdt' );frmEdtLoad( " . $SID . " );frmEdtBackBox = 'Projects';stuffRenderBack( frmEdtBackBox, 'txtFrmEdtBack' );";
	}

	/**
	 * Prepare data to be shown in Project section of form
	 *
	 * @param $SID <int> StuffId
	 */
	public function FromFragment( $cpe_js_var, $SID )
	{
		_db_query( 'BEGIN' );

		/*
		 * Load from other boxes history.
		 */
		 $row = _db_1line( "SELECT * FROM `" . self::T_STUFFBOXES . "`
							WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									AND `" . self::F_STUFFSID . "` = \"" . _db_escape( $SID ) . "\"
							ORDER BY `" . self::F_STUFFSEQ . "` DESC LIMIT 0,1" );
		 _db_query( 'FALSE' );

		 if ( $row && count( $row ) )
		 {
			 /* This has to have format usable for Stuff tab mFwListCustomManagers
			  * template as it is partly used for rendering.
			  */
			 $this->Contexts( );
			 $ctx = $this->Badges( $row[self::F_STUFFCTXS] );
			 $jsOnClick = $cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );";
			 //$jsOnClick = $this->JsOnClick( $row[self::F_STUFFSID] );
			 $ret['toRender'] = new _list_cell( _list_cell::deco( $row[self::F_STUFFNAME], '', $ctx, $row[self::F_STUFFSID], '', false, $jsOnClick ), _list_cell::MAN_DECO );
			 $ret['desc'] = $row[self::F_STUFFDESC];

			 return $ret;
		 }

		 return false;
	}
    
	/**
	 * Search Stuff for Project Picker
	 */
	public function SearchPrjPicker ( $js_var, $keyword, $pageSize, $page, $order, $dir )
	{
		if ( $dir != 'DESC' ) $dir = 'ASC';

		switch ( $order )
		{
			case 'timeframe':
				if ( $dir == 'DESC' )
					$orderBy = "`" . self::F_STUFFDATESET . "` ASC,`date` DESC,`" . self::F_STUFFTIMESET . "` ASC,`time` DESC";
				else
					$orderBy = "`" . self::F_STUFFDATESET . "` DESC,`date` ASC,`" . self::F_STUFFTIMESET . "` DESC,`time` ASC";

				$order = 'timeframe';
			break;

			case self::F_STUFFNAME:
			default:

				$order = self::F_STUFFNAME;
				$orderBy = "`" . $order . "` " . $dir;
			break;
		}

		$builder = new _list_builder( $this->app->getVcmpSearchId( 'PrjPicker' ) );
			$builder->registerJumper( StuffListCell::MAN_STUFFDATETIME );

			$builder->addField( self::F_STUFFBOX, $this->messages['editorHistoryBoxName'], self::LIST_HDRW_BOX, 1, 'left', false);
			$builder->addField( 'timeframe', $this->messages['schTimeFrame'], self::LIST_HDRW_TIMEFRAME, 2, '', true, ( $order == 'timeframe' ), $dir );
			$builder->addField( self::F_STUFFNAME, $this->messages['cpeDetails'], '*', 1, '', true, ( $order == self::F_STUFFNAME ), $dir );
			
			$builder->addField( 'task', $this->messages['naTask'], self::LIST_HDRW_PARENT, 1, '', false );

		if ( trim( $keyword ) != '' )
			$where = " ( sq2.`" . self::F_STUFFNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
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
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
												" . ( ( $where != '' ) ? " AND " . $where : "" ) . "
												) sq3 " );

		$pageCount = ceil( $itemCount / $pageSize );

		if ( $page > $pageCount )
			$page = $pageCount;
		elseif ( $page < 1 )
			$page = 1;

		$firstItem = ( $page - 1 ) * $pageSize;

		$builder->ComputePaging( $pageSize, $itemCount, $page, $pageCount, n7_globals::settings( )->get( 'usr.lst.pagerhalf' ));

		if ( $itemCount < 1 )
			return false;

		$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
							( SELECT inbox.`" . self::F_STUFFNAME . "` AS task,sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,sq1.prj
								FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj,CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`
												FROM `" . self::T_STUFFBOXES . "`
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` )
												GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
												ORDER BY `" . self::F_STUFFSEQ . "` DESC
									 ) sq1
									LEFT JOIN `" . self::T_STUFFBOXES . "` AS inbox ON ( inbox.`" . self::F_STUFFSID . "` = sq1.`" . self::F_STUFFSID . "` AND inbox.`" . self::F_STUFFSEQ . "` = 0 )
									WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
								 " . ( ( $where != '' ) ? "WHERE " . $where : "" ) . "
								ORDER BY {$orderBy}
								LIMIT " . _db_escape( $firstItem ) . "," . _db_escape( $pageSize ) );


		if ( $res  && _db_rowcount( $res ) )
		{
			$this->Contexts( );

			while ( $row = _db_fetchrow ( $res ) )
			{
				//if ( (int)$showContexts == 1 )
					$ctx = $this->Badges( $row[self::F_STUFFCTXS] );
				$tf = $this->FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
				$class = ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
				$class .= ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );

				if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
					$row[self::F_STUFFDESC] = $this->SysDataDetails( $row[self::F_STUFFDATA] );

				$builder->AddRow(	new _list_cell(	_list_cell::Text(	$this->messages["editorHistoryBox" . $row[self::F_STUFFBOX]],
																		$class ),
													_list_cell::MAN_DEFAULT ),

									new _list_cell(	_list_cell::DateTime(	$tf['date'],
																			$tf['time'],
																			$class ),
													StuffListCell::MAN_STUFFDATETIME ),

									new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																		$row[self::F_STUFFDESC],
																		$ctx,
																		$class,
																		$js_var. ".prj_pick( " . $row[self::F_STUFFSID] . " );",
																		( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
													_list_cell::MAN_DECO ),
									
									new _list_cell(	_list_cell::deco(	$row['task'],
																		'',
																		null,
																		$class,
																		$js_var. ".prj_pick( " . $row[self::F_STUFFSID] . " );" ),
													_list_cell::MAN_DECO ) );
			}

		}

		return $builder->export( );
	}

	/**
	 * Main search method for projects.
	 *
	 * @param pageSize maximum size of page
	 * @param keyword search phrase
	 * @param page actual page to view
	 * @param order field to order list by
	 * @param dir direction of ordering
	 *
	 * @return array with data for list, false on zero matches
	 */
	public function Search ( $cpe_js_var, $pageSize = 20, $keyword = '', $page = 1, $order = self::F_STUFFRECORDED, $dir = 'DESC' )
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
			default:
				$orderBy = "`" . $order . "` " . $dir;
			break;
		}

		$builder = new _list_builder( $this->app->getVcmpSearchId( 'Projects' ) );
			$builder->registerJumper( StuffListCell::MAN_STUFFDATETIME );

			$builder->addField( self::F_STUFFBOX, $this->messages['schInBox'], self::LIST_HDRW_BOX, 1, '', false );
			$builder->addField( self::F_STUFFRECORDED, $this->messages['inboxListMoved'], self::LIST_HDRW_RECORDED, 1, 'left', true, ( $order == self::F_STUFFRECORDED ), $dir );
			$builder->addField( self::F_STUFFPRIORITY, $this->messages['inboxListPriority'], self::LIST_HDRW_PRIORITY, 1, '', true, ( $order == self::F_STUFFPRIORITY ), $dir );
			$builder->addField( self::F_STUFFNAME, $this->messages['naListThing'], '*', 1, '', true, ( $order == self::F_STUFFNAME ), $dir );
			$builder->addField( 'timeframe', $this->messages['schTimeFrame'], self::LIST_HDRW_TIMEFRAME, 2, '', true, ( $order == 'timeframe' ), $dir );
			$builder->addField( 'parent', $this->messages['prjParent'], self::LIST_HDRW_PARENT, 1, '', false );
			$builder->addField( 'task', $this->messages['naTask'], self::LIST_HDRW_PARENT, 1, '', false );

			$builder->addField( '__done', '', '0px', 1, '', false );
			$builder->addField( '__2m', '', '0px', 1, '', false );
			$builder->addField( '__rem', '', self::LIST_HDRW_ICON, 1, '', false );

		if ( trim( $keyword ) != '' )
			$where = "( sq2.`" . self::F_STUFFNAME . "` LIKE \"%" . _db_escape( $keyword ) . "%\"
							OR sq2.`" . self::F_STUFFDESC . "` LIKE \"%" . _db_escape( $keyword ) . "%\" )";
		else
			$where = '';

		$itemCount = (int)_db_1field( "SELECT COUNT(*)
									FROM ( SELECT *
											FROM ( SELECT *
												FROM ( SELECT `" . self::T_STUFFBOXES . "`.* FROM `" . self::T_STUFFBOXES . "`
																JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` )
																GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
																ORDER BY `" . self::F_STUFFSEQ . "` DESC
													 ) sq1
											GROUP BY `" . self::F_STUFFSID . "` DESC ) sq2
												WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"" . ( ( $where != '' ) ? " AND {$where}" : "" ) . "
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
		StuffCfgFactory::getCfg( 'usr.lst.Projects')->save( $keyword, $order, $dir, (int)$page );
		//$this->settings->saveBatch( 'Prj', $keyword, (int)$page, $order, $dir );

		if ( $itemCount < 1 )
			return false;

		$res = _db_query( "SELECT *,DATE( `rawDateTime` ) as `date`, IF( ( `" . self::F_STUFFTIMESET . "` = 1 ), DATE_FORMAT( `rawDateTime`, '%H:%i:%s' ), '00:00:00' ) as `time` FROM
							( SELECT inbox.`" . self::F_STUFFNAME . "` AS task,sq1.`rawDateTime`,sq1.`" . self::F_STUFFSID . "`,sq1.`" . self::F_STUFFBOX . "`,sq1.`" . self::F_STUFFNAME . "`,sq1.`" . self::F_STUFFRECORDED . "`,sq1.`" . self::F_STUFFDESC . "`,sq1.`" . self::F_STUFFPRIORITY . "`,sq1.`" . self::F_STUFFDATESET . "`,sq1.`" . self::F_STUFFDATEVAL . "`,sq1.`" . self::F_STUFFTIMESET . "`,sq1.`" . self::F_STUFFTIMEVAL . "`,sq1.`" . self::F_STUFFCTXS . "`,sq1.`" . self::F_STUFFFLAGS . "`,sq1.`" . self::F_STUFFDATA . "`,`parentId`,`parentName`
								FROM ( SELECT `" . self::T_STUFFBOXES . "`.*,CONVERT_TZ( CONCAT( `" . self::F_STUFFDATEVAL . "`, ' ', `" . self::F_STUFFTIMEVAL . "` ), '" . $this->serverTz . "', '" . n7_globals::userTz( )->getName( ) . "' ) as `rawDateTime`,`parent`.`" . self::F_STUFFSID . "` as parentId,`parent`.`" . self::F_STUFFNAME . "` as parentName
												FROM `" . self::T_STUFFBOXES . "`
												JOIN `" . self::T_STUFFINBOX . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFSID . "` )
												JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` )
												LEFT JOIN `" . self::T_STUFFPROJECTS . "` parent ON ( `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFPID . "` = `parent`.`" . self::F_STUFFSID . "` )
												GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
												ORDER BY `" . self::F_STUFFSEQ . "` DESC
									 ) sq1
									LEFT JOIN `" . self::T_STUFFBOXES . "` AS inbox ON ( inbox.`" . self::F_STUFFSID . "` = sq1.`" . self::F_STUFFSID . "` AND inbox.`" . self::F_STUFFSEQ . "` = 0 )
									WHERE sq1.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									GROUP BY sq1.`" . self::F_STUFFSID . "` ) sq2
								" . ( ( $where != '' ) ? " WHERE {$where} " : "" ) . "
								ORDER BY {$orderBy}
								LIMIT " . _db_escape( $firstItem ) . "," . _db_escape( $pageSize ) );


		if ( $res  && _db_rowcount( $res ) )
		{
			$this->Contexts( );

			/*
			 * Build batch processing form for Archive.
			 */
			/*if ( $box == 'Ar' )
			{
				$Form = new ListForm( );
			}*/

			while ( $row = _db_fetchrow ( $res ) )
			{
				if ( StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ) )
					$comment = $this->SysDataDetails( $row[self::F_STUFFDATA] );
				/*elseif ( trim( $row[self::F_STUFFDESC] ) == '' )
					$comment = $row['ocomm'];*/
				else
					$comment = $row[self::F_STUFFDESC];

				$tf = $this->FuzzyTimeframe( $row[self::F_STUFFDATESET], $row["date"], $row[self::F_STUFFTIMESET], $row["time"] );
				$class = ( $tf['passed'] === true ) ? 'stuffListTooLate' : '' ;
				$class .= ' ' . ( ( $tf['today'] === true ) ? 'stuffListToday' : '' );
				$ctx = $this->Badges( $row[self::F_STUFFCTXS] );

				$jsOnClick = $cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );";

				$box		= new _list_cell(	_list_cell::Text(	$this->messages["box" . $row[self::F_STUFFBOX]],
																	$class ),
												_list_cell::MAN_DEFAULT );

				$recorded	= new _list_cell(	_list_cell::Text(	$this->FuzzyDateTime( _tz_transformation( $row[self::F_STUFFRECORDED] ) ),
																	$class ),
												_list_cell::MAN_DATEORTIME );

				$priority	= new _list_cell(	StuffListCell::StuffPriority(	$row[self::F_STUFFPRIORITY],
																				$class ),
												StuffListCell::MAN_STUFFPRIORITY );

				$name		= new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																	$comment,
																	$ctx,
																	$class,
																	$jsOnClick,
																	'stuffPrjIcon' ),
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
																	$jsOnClick ),
												_list_cell::MAN_DECO );

				/**
				 * Common part of Javascript code for icons actions.
				 */
				$js_common = "var data = new Array();data['id']=" . $row[self::F_STUFFSID] . ";data['client_var']=_uicmp_lookup.lookup('" . $this->app->getVcmpSearchId( 'Projects' ) . "');";

				$archive	= new _list_cell(	_list_cell::Code(	$js_common . "data['label']='F';_stuff_archive(data);",
																	$this->messages['arAltFinished'],
																	$class ),
												StuffListCell::MAN_STUFFICODONE );

				$twomin		= new _list_cell(	_list_cell::Code(	$js_common . "data['label']='2';_stuff_archive(data);",
																	$this->messages['arAltMinutes'],
																	$class ),
												StuffListCell::MAN_STUFF2M );

				$remove		= new _list_cell(	_list_cell::Code(	$js_common . "data['label']='G';_stuff_archive(data);",
																	$this->messages['arAltGarbage'],
																	$class ),
												_list_cell::MAN_ICONREMOVE );

				$builder->AddRow( $box, $recorded, $priority, $name, $frame, $parent, $original, $archive, $twomin, $remove);
			}

		}

		return $builder->export( );
	}

	/**
	 * Returns array of subtasks for gived project.
	 *
	 * @param <int> $PID StuffId of project item
	 */
	public function Subtasks( $cpe_js_var, $PID )
	{
		
		$subtasks = false;

		$res = _db_query( "SELECT * FROM ( SELECT `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFCTXS . "`,`" . self::T_STUFFBOXES . "`.`" . self::F_STUFFNAME . "`,`" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::T_STUFFBOXES . "`.`" . self::F_STUFFBOX . "`,`" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` as prj
											FROM `" . self::T_STUFFBOXES . "`
											LEFT JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` )
											JOIN `" . self::T_STUFFINBOX . "` ON ( `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` )
											WHERE `" . self::T_STUFFINBOX . "`.`" . self::F_STUFFPID . "` = \"" . _db_escape( $PID ) . "\"
											GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
											ORDER BY `" . self::F_STUFFSEQ . "` DESC )sq1
							GROUP BY `" . self::F_STUFFSID . "` ORDER BY `" . self::F_STUFFNAME . "`" );

		if ( $res && _db_rowcount( $res ) )
		{
			$this->Contexts( );

			while( $row = _db_fetchrow( $res ) )
			{
				$jsOnClick = $cpe_js_var . ".process( " . $row[self::F_STUFFSID] . " );";
				$ctx = $this->Badges( $row[self::F_STUFFCTXS] );
				$subtasks[] = Array(	'key1'		=> $row[self::F_STUFFBOX],
										'key2'		=> $row[self::F_STUFFNAME],
										'box'		=> new _list_cell(	_list_cell::Text(	$this->messages["cpe"]["box"][$row[self::F_STUFFBOX]],
																							"stuffSubtaskBox" ),
																		_list_cell::MAN_DEFAULT ),
										'subtask'	=> new _list_cell(	_list_cell::deco(	$row[self::F_STUFFNAME],
																							'',
																							$ctx,
																							'stuffFrmEdtSubtask',
																							$jsOnClick,
																							( (int)$row['prj'] != 0 ) ? 'stuffPrjIcon' : '' ),
																		_list_cell::MAN_DECO ) );
			}
		}
		
		if ( is_array( $subtasks ) )
			uasort( $subtasks, "subtasksBoxCmp" );

		return $subtasks;
	}
}

?>