<?PHP

/**
 * @file class.AbScheme.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'list/_list_builder.php';
require_once CHASSIS_LIB . 'list/_list_cell.php';

require_once APP_AB_LIB . 'class.AbCfgFactory.php';
require_once APP_AB_LIB . 'class.AbConfig.php';
require_once APP_AB_LIB . 'class.AbPerson.php';
require_once APP_AB_LIB . 'class.AbOrg.php';

/**
 * Search operation on Address Book contacts.
 */
class AbSearch extends AbConfig
{
	/*
	 * Name for field containing composed name of person.
	 */
	const COMPOSED = 'composed';
	
	/**
	 * ID of user performing the search.
	 * 
	 * @var int
	 */
	private $uid = NULL;
	
	/**
	 * Application instance.
	 * 
	 * @var Ab
	 */
	protected $app = NULL;


	/**
	 * Constructor.
	 * 
	 * @param int $uid ID of user performing the search
	 * @param Ab$uid Address Book application instance
	 */
	public function __construct ( $uid, &$app )
	{
		$this->uid = $uid;
		$this->app = $app;
	}

	/**
	 * Prepare data for list of search results given by search conditions.
	 * 
	 * @param string $perse_js_var client side Javascript variable for Person-class editor instance
	 * @param string $orge_js_var client side Javascript variable for Organization-class editor instance
	 * @param int $page_size list size
	 * @param string $keyword search phrase
	 * @param int $page page of search results to display
	 * @param string $order field to order by
	 * @param string $dir 'ASC' or 'DESC', direction of ordering
	 * @return mixed 
	 */
	public function search ( $perse_js_var, $orge_js_var, $page_size, $keyword, $page, $order, $dir )
	{
		AbPerson::updateSearchIndexAll();
		AbOrg::updateSearchIndexAll();
		
		$messages = $this->app->getMessages( );

		if ( $dir != 'DESC' ) $dir = 'ASC';

		$builder = new _list_builder( $this->app->getVcmpSearchId( 'All' ), n7_globals::getInstance( )->get('io.creat.chassis.i18n') );

			$builder->addField( 'composed', $messages['list']['name'], AbCfgFactory::LIST_HDRW_NAME, 1, '', true, true, $dir );
			$builder->addField( 'phone', $messages['typed']['types']['phone'], AbCfgFactory::LIST_HDRW_FIELD, 1, '', false );
			$builder->addField( 'cell', $messages['typed']['types']['cell'], AbCfgFactory::LIST_HDRW_FIELD, 1, '', false );
			$builder->addField( 'email', $messages['typed']['types']['email'], AbCfgFactory::LIST_HDRW_FIELD, 1, '', false );
			$builder->addField( '__rem', '', 1, 1, '', false );

		$where = '';
		if ( trim( $keyword ) != '' )
			$where = "AND `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABDISPLAY . "` LIKE \"%" . _db_escape( $keyword ) . "%\"";

		$record_count = _db_1field( "SELECT COUNT(*)
							FROM `" . self::T_AB . "`
							JOIN `" . self::T_ABSEARCHINDEX . "`
								ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABID . "` )
							WHERE `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\" {$where} " );

		$page_count = ceil( $record_count / $page_size );

		if ( $page > $page_count )
			$page = $page_count;
		elseif ( $page < 1 )
			$page = 1;
			
		$record_first = ( $page - 1 ) * $page_size;

		$builder->computePaging( $page_size, $record_count, $page, $page_count, n7_globals::settings( )->get( 'usr.lst.pagerhalf' ) );
		
		/*
		 * Remember list configuration into database.
		 */
		AbCfgFactory::getCfg( 'usr.lst.All')->save( $keyword, $order, $dir, (int)$page );

		if ( $record_count < 1 )
			return false;

		$res = _db_query( "SELECT `" . self::T_ABSEARCHINDEX . "`.*,`" . self::T_AB . "`.`" . self::F_ABSCHEME . "`,
							( SELECT `" . self::F_ABNNUMBER . "` FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::T_ABNUMBERS . "`.`" . self::F_ABID . "` = `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABID . "` AND type=\"phone\" LIMIT 0,1 ) as phone,
							( SELECT `" . self::F_ABNNUMBER . "` FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::T_ABNUMBERS . "`.`" . self::F_ABID . "` = `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABID . "` AND type=\"email\" LIMIT 0,1 ) as email,
							( SELECT `" . self::F_ABNNUMBER . "` FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::T_ABNUMBERS . "`.`" . self::F_ABID . "` = `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABID . "` AND type=\"cell\" LIMIT 0,1 ) as cell
							FROM `" . self::T_ABSEARCHINDEX . "`
							JOIN `" . self::T_AB . "`
								ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABID . "` )
							WHERE `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\" {$where}
							ORDER BY `" . self::T_ABSEARCHINDEX . "`.`" . self::F_ABDISPLAY  . "` {$dir}
							LIMIT " . _db_escape( $record_first ) . "," . _db_escape( $page_size ) );

		if ( $res && _db_rowcount( $res ) )
		{
			$ctxs = _cdes::allCtxs( $this->uid, self::T_ABCTX );

			while ( $row = _db_fetchrow( $res ) )
			{
				switch ( $row[self::F_ABSCHEME] )
				{
					case self::V_ABSCHCOMPANY:
						$edit = $orge_js_var . ".edit(" . $row[self::F_ABID] . ");";
						$rm = "data['class']='org';";
						if ( trim( $row[self::F_ABDISPLAY] ) == '' )
							$row[self::F_ABDISPLAY] = $messages['list']['noname_org'];
					break;

					case self::V_ABSCHPERSON:
						$edit = $perse_js_var . ".edit(" . $row[self::F_ABID] . ");";
						$rm = "data['class']='pers';";
						if ( trim( $row[self::F_ABDISPLAY] ) == '' )
							$row[self::F_ABDISPLAY] = $messages['list']['noname_pers'];
					break;

					default:
						$onClick = '';
					break;
				}
				
				$ctx = _cdes::badges( $ctxs, $row[self::F_ABCTXS] );
				/**
				 * Common part of Javascript code for icons actions.
				 */
				$js_common = "var data = new Array();data['id']=" . $row[self::F_ABID] . ";data['client_var']=_uicmp_lookup.lookup('" . $this->app->getVcmpSearchId( 'All' ) . "');";
				
				$builder->addRow( new _list_cell( _list_cell::deco( $row[self::F_ABDISPLAY], $row[self::F_ABCOMMENT], $ctx, '', $edit ), _list_cell::MAN_DECO ),
									new _list_cell( _list_cell::Text( $row['phone'] ), _list_cell::MAN_DEFAULT ),
									new _list_cell( _list_cell::Text( $row['cell'] ), _list_cell::MAN_DEFAULT ),
									new _list_cell( _list_cell::Text( $row['email'] ), ( trim( $row['email'] ) != '' ) ? _list_cell::MAN_EMAIL : _list_cell::MAN_DEFAULT ),
					
									new _list_cell(	_list_cell::Code(	$js_common . $rm . "var yes = new _sd_dlg_bt ( _ab_rm_single, '{$messages['list']['bt_yes']}', data );var no = new _sd_dlg_bt ( null, '{$messages['list']['bt_no']}', null );_wdg_dlg_yn.show( '{$messages['list']['warning']}', '" . sprintf( $messages['list']['question'], Wa::JsStringEscape( $row[self::F_ABDISPLAY], ENT_QUOTES ) ) . "', yes, no );",
																		$messages['list']['alt_remove'],
																		'' ), _list_cell::MAN_ICONREMOVE ) );

//									new _list_cell( _list_cell::Code( "iFwDlgYnShow( '" . $__msgAb['listCaptionRemove'] . "', '" . sprintf( $__msgAb['listQuestionRemove'], Wa::JsStringEscape( $row[self::F_ABDISPLAY] ) ) . "', '" . $__msgAb['bubbleYes'] . "', '" . $__msgAb['bubbleNo'] . "', frmAcRemove, " . $row[self::F_ABID] . " );" )

			}
		}
		return $builder->export( );
	}
}

?>