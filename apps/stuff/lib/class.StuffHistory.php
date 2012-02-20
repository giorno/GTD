<?php

// vim: ts=4

/**
 * @file class.StuffHistory.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'apps/_app_registry.php';

require_once N7_SOLUTION_LIB . 'libtz.php';

require_once APP_STUFF_LIB . 'class.StuffConfig.php';
require_once APP_STUFF_LIB . 'class.StuffEditor.php';
require_once APP_STUFF_LIB . 'class.StuffSearch.php';
require_once APP_STUFF_LIB . 'class.StuffData.php';

/**
 * Facility to provide Stuff's history in form of (very complex) array.
 */
class StuffHistory extends StuffConfig
{
	/*
	 * (int) Id of stuff.
	 */
	private $SID;

	/*
	 * (int) User's Id.
	 */
	private $UID;

	/*
	 * (array) Internal representation for data from database.
	 */
	private $Data;

	/*
	 * This variable has two purposes: 1 - to be temporary cache for data later
	 * passed to $this->Data, 2 - to provide very actual data for form purpose.
	 */
	private $LastData;

	/**
	 * Reference to localization messages and formats.
	 *
	 * @var <refarray>
	 */
	private $messages = null;

	/*
	 * Constructor. Initializer and loader (from database).
	 */
	public function __construct( $UID, $SID )
	{
		$this->UID = (int)$UID;
		$this->SID = (int)$SID;

		/**
		 * Getting array of localization messages containing date and time
		 * formats.
		 */
		$app = _app_registry::getInstance()->getById( Stuff::APP_ID );

		if ( !is_null( $app ) )
			$this->messages = &$app->getMessages( );

		/**
		 * Load history of the Stuff.
		 */
		$this->Load( );
	}

	/*
	 * Process $this->LastData for proper timezone values.
	 */
	private function TzScrum ( )
	{
		global $__LC_TIME;

		setlocale( LC_TIME, $__LC_TIME );
		
		/*
		 * See StuffCollector::ImportXml() for more.
		 */
		$stamp = _tz_transformation( $this->LastData['date'] . " " . $this->LastData['time'] );
		
		$this->LastData['dateVal'] = strftime( $this->messages['dtFormat']['HISTDATE'], $stamp );
		$this->LastData['timeVal'] = strftime( $this->messages['dtFormat']['HISTTIME'], $stamp );
		$this->LastData['date'] = date( "Y-m-d", $stamp );
		$this->LastData['time'] = date( "H:i:s", $stamp );
	}

	/*
	 * Load data from database and create internal representation.
	 */
	private function Load ( )
	{
		global $__LC_TIME, $__welcomeMsg, $__BLOGS;

		setlocale( LC_TIME, $__LC_TIME );
		
		_db_query( 'BEGIN' );

		/*
		 * Load from other boxes history.
		 */
		$res = _db_query( "SELECT * FROM `" . self::T_STUFFBOXES . "`
							WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\"
									AND `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->SID ) . "\"
							ORDER BY `" . self::F_STUFFSEQ . "` ASC" );

		$PID = (int)_db_1field( "SELECT `" . self::F_STUFFPID . "` FROM `" . self::T_STUFFINBOX . "`
								WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->UID ) . "\" AND
								`" . self::F_STUFFSID . "` = \"" . _db_escape( $this->SID ) . "\"" );

		if ( $res && _db_rowcount( $res ) )
		{
			$Search = new StuffSearch( $this->UID );
			$Search->Contexts( );
			while( $row = _db_fetchrow ( $res ) )
			{
				$stamp = strtotime( $row[self::F_STUFFRECORDED] );

				$this->LastData = null;

				$this->LastData['box']      = $row[self::F_STUFFBOX];
				$this->LastData['pid']      = $PID;
				$this->LastData['sequence'] = $row[self::F_STUFFSEQ];
				$this->LastData['task']     = $row[self::F_STUFFNAME];
				$this->LastData['desc']     = $row[self::F_STUFFDESC];
				$this->LastData['descF']    = self::Format( $row[self::F_STUFFDESC] );
				$this->LastData['place']    = $row[self::F_STUFFPLACE];
				$this->LastData['priority'] = $row[self::F_STUFFPRIORITY];
				$this->LastData['dateSet']  = ( (int)$row[self::F_STUFFDATESET] == 1 );
				$this->LastData['date']     = $row[self::F_STUFFDATEVAL];
				$this->LastData['timeSet']  = ( (int)$row[self::F_STUFFTIMESET] == 1 );
				$this->LastData['time']     = $row[self::F_STUFFTIMEVAL];
				//$this->LastData['recorded'] = strftime( $__STUFFINBOXRECORDEDDATETIME, _tz_transformation( $row[self::F_STUFFRECORDED] ) );
				$this->LastData['recDate']  = strftime( $this->messages['dtFormat']['RECDATESHORT'], _tz_transformation( $row[self::F_STUFFRECORDED] ) );
				$this->LastData['recTime']  = strftime( $this->messages['dtFormat']['RECTIME'], _tz_transformation( $row[self::F_STUFFRECORDED] ) );
				$this->LastData['contexts'] = _cdes::unserialize( $row[self::F_STUFFCTXS] );
				$this->LastData['badges']   = $Search->Badges( $row[self::F_STUFFCTXS] );
				
				$this->LastData['flags']['all'] = $row[self::F_STUFFFLAGS];
				$this->LastData['flags']['ro'] = StuffEditor::IsRo( $row[self::F_STUFFFLAGS] ); // system generated messages must not be editable

				/*
				 * System messages have to be rendered differently.
				 */
				if ( $this->LastData['flags']['ro'] === true )
				{
					$sysData = new StuffData( $row[self::F_STUFFDATA] );
					$this->LastData['sysData'] = $sysData->GetData( );

					// extracting language for system message
					$sdLang = $this->LastData['sysData']['l'];
					if ( !array_key_exists( $sdLang, n7_globals::languages( ) ) )
						$sdLang = 'en';

					// give localization strings for system message
					$this->LastData['sysData']['blogUrl'] = $__BLOGS[$sdLang];
					switch ( $this->LastData['sysData']['ID'] )
					{
						case self::ID_WELCOMEMSGv1: $this->LastData['sysData']['i18n'] = $this->messages['wlc']; break;
					}
				}
				
				$this->TzScrum( );
				
				$this->Data[$row[self::F_STUFFSEQ]] = $this->LastData;
				$this->Data[$row[self::F_STUFFSEQ]]['desc'] = nl2br( $this->Data[$row[self::F_STUFFSEQ]]['desc'] );
			}
		}
		
		$this->Data = array_reverse( $this->Data );

		_db_query( 'COMMIT' );
	}

	/*
	 * Read access to data representation for purpose of building UI.
	 */
	public function ExportArray ( )
	{
		return $this->Data;
	}

	/*
	 * Read access to XML like data representation of last step.
	 */
	public function ExportLastData ( )
	{
		/*
		 * Postprocessing.
		 */
		if ( $this->LastData['dateSet'] === true )
		{
			$elements = explode( '-', $this->LastData['date'] );
			$this->LastData['dateYear'] = (int)$elements[0];
			$this->LastData['dateMonth'] = (int)$elements[1];
			$this->LastData['dateDay'] = (int)$elements[2];

			if ( $this->LastData['timeSet'] === true )
			{
				$elements = explode( ':', $this->LastData['time'] );
				$this->LastData['timeHour'] = (int)$elements[0];
				$this->LastData['timeMinute'] = (int)$elements[1];
			}
		}
		$this->LastData['id'] = $this->SID;

		/*
		 * Export.
		 */
		return $this->LastData;
	}

	/**
	 * Add formatting tags into plain description text.
	 *
	 * @param <string> $plain input text
	 * @return <string>
	 */
	private static function Format ( $plain )
	{
		// Encode HTML entities, match URLs and convert them into links.
		$buf = preg_replace( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\-\.]*(\?\S+)?)?)?)@', '<a target="_blank" href="$1">$1</a>', htmlspecialchars( $plain ) );

		// Apply new line characters.
		return nl2br( trim( $buf ) );
	}
}

?>
