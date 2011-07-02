<?php
/**
 * Class for handling edit operation on stuff. Also serves as parent for
 * classes StuffCollector and StuffProcessor.
 *
 * @author giorno
 */

require_once N7_SOLUTION_LIB . 'libtz.php';
require_once APP_STUFF_LIB . 'class.StuffConfig.php';
require_once CHASSIS_3RD . 'class.SimonsXmlWriter.php';
//require_once CHASSIS_LIB . 'class.Context.php';
require_once CHASSIS_LIB . 'class.Wa.php';
require_once CHASSIS_LIB . 'libdb.php';

class StuffEditor extends StuffConfig
{
	/*
	 * User's Id.
	 */
	protected $uid = null;

	/*
	 * (int) Stuff Id.
	 */
	protected $sid = null;

	/*
	 * (Array) Internal representation of handled stuff.
	 */
	protected $data;

    /*
	 * Constructor. Set UserId.
	 */
	public function __construct( $uid )
	{
		$this->uid = $uid;
	}

	/**
	 * Import plaintext XML document and create internal representation of data.
	 *
	 * Since Javascript XML writer writes invalid 'standalone' header attribute
	 * it has be removed to avoid SimpleXML warnings.
	 *
	 * @param <string> $xmlDoc XML document with new thing details
	 */
	function importXml ( $xml )
	{
		if ( ( $doc = simplexml_load_string( str_replace( ' standalone="false"', '', Wa::PlusSignWaDecode( $xml ) ) ) ) !== false )
		{
			$this->data = Array( );

			$nodeStuff = $doc->xpath( '//cpe' );
			$nodeName  = $doc->xpath( '//cpe/n' );
			$nodeCtx   = $doc->xpath( '//cpe/ctxs' );
			$nodePlace = $doc->xpath( '//cpe/pl' );
			$nodeDesc  = $doc->xpath( '//cpe/ds' );
			$nodeDate  = $doc->xpath( '//cpe/d' );
			$nodeTime  = $doc->xpath( '//cpe/t' );

				$this->sid = (int)$nodeStuff[0]['id'];
				$this->data['priority'] = (int)$nodeStuff[0]['pr'];
				$this->data['box'] = (string)$nodeStuff[0]['b'];
				$this->data['sequence'] = (int)$nodeStuff[0]['seq'];
				$this->data['pid'] = (int)$nodeStuff[0]['pid'];

				/*
				 * Transform to db table's enum values.
				 */
				/*switch ($this->data['box'])
				{
					case 'NextAction': $this->data['box'] = 'Na'; break;
					case 'WaitingFor': $this->data['box'] = 'Wf'; break;
					case 'Someday': $this->data['box'] = 'Sd'; break;
					case 'Archive': $this->data['box'] = 'Ar'; break;
				}*/

				$this->data['priority'] = (int)$nodeStuff[0]['pr'];
				$this->data['name'] = base64_decode( (string)$nodeName[0] );
				$this->data['place'] = base64_decode( (string)$nodePlace[0] );
				$this->data['desc'] = base64_decode( (string)$nodeDesc[0] );

				$this->data['contexts'] = null;
					foreach ( $nodeCtx[0] as $context )
						$this->data['contexts'][(int)$context['id']] = true;

				$this->data['date']['set'] = ( (string)$nodeDate[0]['s'] == 'true' ) ? true : false;
				$this->data['date']['year'] = (int)$nodeDate[0]['y'];
				$this->data['date']['month'] = (int)$nodeDate[0]['m'];
				$this->data['date']['day'] = (int)$nodeDate[0]['d'];
				$this->data['date']['composed'] = $this->data['date']['year'] . "-" . $this->data['date']['month'] . "-" . $this->data['date']['day'];

				$this->data['time']['set'] = ( (string)$nodeTime[0]['s'] == 'true' ) ? true : false;
				$this->data['time']['hour'] = (int)$nodeTime[0]['h'];
				$this->data['time']['minute'] = (int)$nodeTime[0]['m'];
				$this->data['time']['composed'] = $this->data['time']['hour'] . ":" . $this->data['time']['minute'] . ":00";

				/*
				 * Timezone scrum. This changes only composed date and time
				 * fields for next record into database. After this step these
				 * fragments may differ from their parts.
				 */
				$stamp = _tz_detransformation( $this->data['date']['composed'] . " " . $this->data['time']['composed'] );
				$this->data['date']['composed'] = date( "Y-m-d", $stamp );
				$this->data['time']['composed'] = date( "H:i:s", $stamp );
		}
	}

	/*
	 * Load one record from database and return it as XML document.
	 */
	public function FragmentXml( $SID, $seq )
	{

		$ret = false;

		$record = _db_1line( "SELECT * FROM `" . self::T_STUFFBOXES . "`
								WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"
										AND `" . self::F_STUFFSID . "` = \"" . _db_escape( $SID) . "\"
										AND `" . self::F_STUFFSEQ . "` = \"" . _db_escape( $seq ) . "\"" );

		$PID = (int)_db_1field( "SELECT `" . self::F_STUFFPID . "` FROM `" . self::T_STUFFINBOX . "`
								WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
								`" . self::F_STUFFSID . "` = \"" . _db_escape( $SID ) . "\"" );

		if ( $record && is_array ( $record ) )
		{
			$writer = new SimonsXmlWriter( "\t" );

				$box = $record[self::F_STUFFBOX];
			
				$writer->push( 'cpe', array( 'id' => $record[self::F_STUFFSID],
											 'pr' => $record[self::F_STUFFPRIORITY],
											 'b' => $box,
											 'seq' => $record[self::F_STUFFSEQ],
											 'pid' => $PID ) );

					$writer->element( 'n', base64_encode( $record[self::F_STUFFNAME] ) );
					$writer->element( 'pl', base64_encode( $record[self::F_STUFFPLACE] ) );
					$writer->element( 'ds', base64_encode( $record[self::F_STUFFDESC] ) );

					$writer->push( 'ctxs' );

						$contexts = _cdes::Unserialize( $record[self::F_STUFFCTXS] );

						if ( $contexts && is_array( $contexts ) )
						{
							foreach ( $contexts as $cid => $val )
								$writer->element( 'ctx', $cid );
						}

					$writer->pop( );

					/*
					 * TZ handling.
					 */
					$stamp = _tz_transformation( $record[self::F_STUFFDATEVAL] . " " . $record[self::F_STUFFTIMEVAL] );
					$y = (int)date( "Y", $stamp );
					$m = (int)date( "m", $stamp );
					$d = (int)date( "d", $stamp );
					$h = (int)date( "H", $stamp );
					$i = (int)date( "i", $stamp );

					if ( $record[self::F_STUFFDATESET] != 0 )
					{
						$writer->push( 'd', array( 's' => 'true', 'y' => $y, 'm' => $m, 'd' => $d ) );
						$writer->pop( );
						if ( $record[self::F_STUFFTIMESET] != 0 )
						{
							$writer->push( 't', array( 's' => 'true', 'h' => $h, 'm' => $i ) );
							$writer->pop( );
						}
						else
						{

							$writer->push( 't', array( 's' => 'false' ) );
							$writer->pop( );
						}

					}
					else
					{
						$writer->push( 'd', array( 's' => 'false' ) );
						$writer->pop( );
						$writer->push( 't', array( 's' => 'false' ) );
						$writer->pop( );
					}

				$writer->pop( );

			return $ret = $writer->getXml( );
		}

		return $ret;
	}

	/*
	 * Save data into database.
	 */
	public function save ( )
	{
		_db_query( 'BEGIN' );

			_db_query ( "UPDATE `" . self::T_STUFFBOXES . "`
						SET 
							`" . self::F_STUFFBOX . "` = \"" . _db_escape( $this->data['box']) . "\",
							`" . self::F_STUFFNAME . "` = \"" . _db_escape( $this->data['name'] ) . "\",
							`" . self::F_STUFFPLACE . "` = \"" . _db_escape( $this->data['place'] ) . "\",
							`" . self::F_STUFFDESC . "` = \"" . _db_escape( $this->data['desc'] ) . "\",
							`" . self::F_STUFFPRIORITY . "` = \"" . _db_escape( $this->data['priority'] ) . "\",
							`" . self::F_STUFFDATESET . "` = \"" . _db_escape( $this->data['date']['set'] ) . "\",
							`" . self::F_STUFFDATEVAL . "` = \"" . _db_escape( $this->data['date']['composed'] ) . "\",
							`" . self::F_STUFFTIMESET . "` = \"" . _db_escape( $this->data['time']['set'] ) . "\",
							`" . self::F_STUFFTIMEVAL . "` = \"" . _db_escape( $this->data['time']['composed'] ) . "\",
							`" . self::F_STUFFCTXS . "` = \"" . _db_escape( _cdes::Serialize($this->data['contexts'] ) ) . "\"
						WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
							  `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\" AND
							  `" . self::F_STUFFSEQ . "` = \"" . _db_escape( $this->data['sequence'] ) . "\"" );

			/*
			 * Set item's parent.
			 */
			$this->updatePid( $this->data['pid'] );

			/*
			 * Update item's name if it is a project.
			 */
			$this->updateProjectName( $this->sid );

		_db_query( 'COMMIT' );
	}

	/*
	 * Sets new parent id on the item. This is transaction-less method as well
	 * as routines used inside it. Caller should implement transaction
	 * protection.
	 */
	public function updatePid ( $pid )
	{
		//return false;
		$OldPID = (int)_db_1field( "SELECT `" . self::F_STUFFPID . "` FROM `" . self::T_STUFFINBOX . "`
								WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
								`" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"" );

		_db_query ( "UPDATE `" . self::T_STUFFINBOX . "`
						SET
							`" . self::F_STUFFPID . "` = \"" . _db_escape( $this->data['pid'] ) . "\"
						WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
							  `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"" );
		if ( (int)$pid == 0 )
		{
			/*
			 * Check other items in Inbox if they have parent with same id.
			 */
			$references = (int)_db_1field( "SELECT COUNT(*) FROM `" . self::T_STUFFINBOX . "`
											WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
											`" . self::F_STUFFPID . "` = \"" . _db_escape( $OldPID ) . "\"" );

			/*
			 * If not, remove record.
			 */
			if ( $references == 0 )
				_db_query( "DELETE FROM `" . self::T_STUFFPROJECTS . "`
							WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
							`" . self::F_STUFFSID . "` = \"" . _db_escape( $OldPID ) . "\"" );
		}
		else
		{
			/*
			 * If there is no record, create it.
			 */
			$exists = (int)_db_1field( "SELECT COUNT(*) FROM `" . self::T_STUFFPROJECTS . "`
										WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\" AND
										`" . self::F_STUFFSID . "` = \"" . _db_escape( $pid ) . "\"" );

			if ( $exists == 0 )
			{
				_db_query( "INSERT INTO `" . self::T_STUFFPROJECTS . "`
							SET `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\",
							`" . self::F_STUFFSID . "` = \"" . _db_escape( $pid ) . "\"" );
				
				$this->updateProjectName( $pid );
			}
		}
	}

	/**
	 * Updates Project name cache in Projects table. Should be called from all
	 * places changing item's name.
	 *
	 * @param <id> $pid StuffId in projects table
	 * @todo cache project size and use this value in project picker list
	 */
	public function updateProjectName ( $pid )
	{
		if ( (int)$pid != 0 )
		{
			$name = _db_1field( "SELECT `" . self::F_STUFFNAME . "` FROM `" . self::T_STUFFBOXES . "`
								WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $pid ) . "\"
										AND `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"
								ORDER BY `" . self::F_STUFFSEQ . "` DESC
								LIMIT 0,1" );

			if ( $name )
			{
				_db_query( "UPDATE `" . self::T_STUFFPROJECTS . "` SET `" . self::F_STUFFNAME . "` = \"" . _db_escape( $name ) . "\"
							WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $pid ) . "\"
										AND `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"" );
			}
		}
	}

	/**
	 * Algorithm to extract RO information from record flags. Serves also as
	 * implementation of (imaginary) method IsSystem().
	 *
	 * @param <int> $flags
	 * @return <bool>
	 */
	static public function isRo ( $flags ) { return ( ( (int)$flags & self::M_SYSTEM ) != 0 ); }
}

?>