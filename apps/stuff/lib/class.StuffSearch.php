<?php

require_once CHASSIS_3RD . 'class.SimonsXmlWriter.php';
require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'class.Wa.php';
require_once CHASSIS_LIB . 'apps/_app_registry.php';

require_once N7_SOLUTION_LIB . 'libtz.php';

require_once APP_STUFF_LIB . 'class.StuffConfig.php';
require_once APP_STUFF_LIB . 'class.StuffData.php';
require_once APP_STUFF_LIB . 'class.StuffEditor.php';
require_once APP_STUFF_LIB . 'class.StuffListCell.php';
require_once APP_STUFF_LIB . 'class.StuffBpAlg.php';
require_once APP_STUFF_LIB . 'class.StuffCfgFactory.php';

/**
 * Basic search routines.
 *
 * @author giorno
 */
class StuffSearch extends StuffConfig
{
	/*
	 * User ID.
	 */
	protected $UID = NULL;

	/*
	 * Internal array of contexts data.
	 */
	protected $contexts = NULL;

	/**
	 * Reference to localization messages, should be initialized in the
	 * constructor.
	 * 
	 * @var <array>
	 */
	protected $messages = NULL;

	protected $app = NULL;

	/**
	 * Designation of server timezone. Value from N7 configuration.
	 *
	 * @var <string>
	 */
	protected $serverTz = NULL;

	/**
	 * Constructor.
	 */
	public function __construct ( $UID )
	{
		$this->UID = $UID;

		$this->app = _app_registry::getInstance( )->getById( '_app.Stuff' );
		
		if ( !is_null( $this->app ) )
			$this->messages = $this->app->getMessages( );

		//$this->settings = StuffCfgFactory::getInstance( );
		$this->serverTz = n7_globals::serverTz( )->getName( );
	}

	/**
	 * Provides fuzzy date or time information.
	 *
	 * 1. If stamp is today, only time portion is displayed.
	 * 2. If stamp is yesterday, 'Yesterday' string is diplayed.
	 * 3. If stamp is older thay year, slashed format is displayed.
	 * 4. Otherwiser only month abreviation and day number.
	 *
	 * @param stamp UNIX timestamp
	 * @param todayTime (bool) display time instead of string 'Today'
	 */
	protected function FuzzyDateTime ( $stamp, $todayTime = true )
	{
		$now = _tz_transformation( 'now' );

		if ( date( "Y-m-d", $now ) == date( "Y-m-d", $stamp ) )
		{
			if ( $todayTime === true )
				return strftime( $this->messages['dtFormat']['RECTIME'], $stamp );
			else
				return $this->messages['inboxDateToday'];
		}
		elseif ( date( "Y-m-d", $now - ( 3600*24 ) ) == date( "Y-m-d", $stamp ) )
			return $this->messages['inboxDateYesterday'];
		elseif ( date( "Y-m-d", $now + ( 3600*24 ) ) == date( "Y-m-d", $stamp ) )
			return $this->messages['inboxDateTomorrow'];
		elseif ( date( "Y-m-d", $now - ( 365*3600*24 ) ) >= date( "Y-m-d", $stamp ) )
			return strftime( $this->messages['dtFormat']['RECDATEwY'], $stamp );
		else
			return strftime( $this->messages['dtFormat']['RECDATE'], $stamp );
	}

	/**
	 * Return array with data for UI elements of custom managers. Another occult
	 * thing.
	 *
	 * @param <bool> $dateSet
	 * @param <YYYY-MM-DD> $date
	 * @param <bool> $timeSet
	 * @param <HH:MM:SS> $time
	 */
	protected function FuzzyTimeframe ( $dateSet, $dateVal, $timeSet, $timeVal )
	{
		$ret = Array( 'date' => '', 'time' => '', 'today' => false, 'passed' => false );

		if ( (bool)$dateSet === false ) return $ret;

		/*
		 * All timezone mangling is supposed to be done in SQL queries, so we
		 * can directly use data passed from caller.
		 */
		$ts = strtotime( $dateVal . " " . $timeVal );
		$ret['date'] = $this->FuzzyDateTime( $ts, false );

		if ( (bool)$timeSet === true )
			$ret['time'] = strftime( $this->messages['dtFormat']['RECTIME'], $ts );

		$class = '';
		$tsNow       = _tz_transformation( 'now' );
		$tsScheduled = $ts;

		if ( date( "Y-m-d", $tsNow ) == date( "Y-m-d", $tsScheduled ) )
			$ret['today'] = true;

		if ( $tsNow > $tsScheduled && ( ( date( "Y-m-d", $tsNow ) != date( "Y-m-d", $tsScheduled ) ) || ( (int)$timeSet == 1 ) ) )
			$ret['passed'] = true;

		return $ret;
	}

	/**
	 * Provide array structured info about number of items in boxes. Since
	 * Guadalcanal it provides also 'average' of priority for each box to be
	 * used in Ui for coloring.
	 *
	 * @return array
	 */
	public function BoxSizes ( )
	{
		/*
		 * Prepare dummy data.
		 */
		$sizes = Array( 'Total' => 0, 'Inbox' => 0, 'Schedule' => 0, 'Projects' => 0, /*'Calendar' =>0, */'Na' => 0, 'Wf' => 0, 'Sd' => 0, 'Ar' => 0 );
		$avg = Array( 'Inbox' => 0, 'Schedule' => 0, 'Projects' => 0, 'Na' => 0, 'Wf' => 0, 'Sd' => 0, 'Ar' => 0 );

		/*
		 * Collect data for virtual tab Schedule.
		 */
		$sch = _db_1line( "SELECT AVG(`" . self::F_STUFFPRIORITY . "`) as rating,COUNT(*) as size FROM ( SELECT * FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "` WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
																											GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC ORDER BY `" . self::F_STUFFSEQ . "` DESC) boxes
																								GROUP BY `" . self::F_STUFFSID . "`) noDnTh
																						WHERE `" . self::F_STUFFBOX . "` <> \"Ar\" AND `" . self::F_STUFFDATESET . "` <> 0
														" );

		if ( is_array( $sch ) )
		{
			$sizes['Schedule'] = (int)$sch['size'];
			$avg['Schedule'] = (float)$sch['rating'];
		}

		/*
		 * Collect information for Projects tab.
		 */
		$prj = _db_1line( "SELECT AVG(`" . self::F_STUFFPRIORITY . "`) as rating,COUNT(*) as size FROM ( SELECT * FROM ( SELECT `" . self::T_STUFFBOXES . "`.* FROM `" . self::T_STUFFBOXES . "`
																														JOIN `" . self::T_STUFFPROJECTS . "` ON ( `" . self::T_STUFFPROJECTS . "`.`" . self::F_STUFFSID . "` = `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "` )
																														WHERE `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
																														GROUP BY `" . self::T_STUFFBOXES . "`.`" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC ORDER BY `" . self::F_STUFFSEQ . "` DESC) boxes
																								GROUP BY `" . self::F_STUFFSID . "`) noDnTh" );

		if ( is_array( $prj ) )
		{
			$sizes['Projects'] = (int)$prj['size'];
			$avg['Projects'] = (float)$prj['rating'];
		}

		/*
		 * Collect informations from stuff_boxes.
		 */
		$res = _db_query( "SELECT `" . self::F_STUFFBOX . "`,AVG(`" . self::F_STUFFPRIORITY . "`) as rating,COUNT(*) as size
								FROM ( SELECT *
									FROM ( SELECT * FROM `" . self::T_STUFFBOXES . "`
													GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "` DESC
													ORDER BY `" . self::F_STUFFSEQ . "` DESC
										 ) sq1
									WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\" AND `" . self::F_STUFFBOX . "` <> \"\"
									GROUP BY `" . self::F_STUFFSID . "`) sq2 GROUP BY `" . self::F_STUFFBOX . "`" );

		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$sizes[$row[self::F_STUFFBOX]] = $row['size'];
				$avg[$row[self::F_STUFFBOX]] = /*round*/( (float) $row['rating'] );
			}
		}

		//var_dump($avg);
		$avg = StuffBpAlg::Proxy( $avg, StuffCfgFactory::getInstance( )->get( 'usr.alg' ) );
		//var_dump($avg);
		//var_dump($avg);

		$total = 0;
		foreach( $sizes as $box => $size )
		{
			if ( !array_key_exists( $box, $avg ) )
				continue;
			
			if ( ( ( $box != 'Ar' ) && ( $box != 'Schedule' ) ) && ( $box != 'Projects' ) )	// only open things to do (done and trashed are not counted)
				$total += $size;

			/* For disabled colors */
			if ( $avg[$box] == 99 )
				$avg[$box] = 1;
			/*else
				$class[$box] = 'C' . $avg[$box] ;*/
		}
		$sizes['Total'] = $total;
		$sizes['i18n'] = $this->messages['tabNumbers']->ToString( $sizes['Total'] );

		return Array( 'size' => $sizes, 'avg' => $avg/*, 'class' => $class */);
	}


	/*
	 * Provide XML structured info about usage of the boxes.
	 *
	 * @return XML document (veeeeeeery simple)
	 */
	public function BoxSizesXml ( )
	{
		$sizes = $this->BoxSizes( );
		/*
		 * Compose Xml
		 */
		$writer = new SimonsXmlWriter( "\t" );
			$writer->push( 'sizes' );
				$writer->push( 'size', $sizes['size'] );
				$writer->pop( );
				$writer->push( 'avg', $sizes['avg'] );
				$writer->pop( );
				/*$writer->push( 'class', $sizes['class'] );
				$writer->pop( );*/
			$writer->pop( );

		return $writer->getXml( );
	}

	/*
	 * Load contexes information into internal array;
	 */
	public function contexts ( ) { $this->contexts = _cdes::allCtxs( $this->UID, self::T_STUFFCTX ); }

	/*
	 * Transform serialized data from table into badges arrays for items.
	 */
	public function badges ( $serialized ) { return _cdes::badges( $this->contexts , $serialized ); }

	/**
	 * Provide plain text details portion of system messages for listing.
	 *
	 * @param <string> $serialized
	 * @return <string>
	 */
	protected function SysDataDetails ( $serialized )
	{
		$sysData = new StuffData( $serialized );
		return $sysData->GetDetails( );
	}
}

?>