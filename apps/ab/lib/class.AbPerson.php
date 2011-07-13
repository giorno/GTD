<?PHP

/**
 * @file class.AbPerson.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_3RD . 'class.SimonsXmlWriter.php';
require_once CHASSIS_LIB . 'class.Wa.php';
require_once CHASSIS_LIB . '_cdes.php';

require_once APP_AB_LIB . 'class.AbScheme.php';

/**
 * Object handling details of person contact.
 */
class AbPerson extends AbScheme
{
	/*
	 * Array with display details.
	 */
	private $display = Array( );

	/*
	 * Array with personal details.
	 */
	private $personal = Array( );

	/*
	 * Constructor. Set UserId.
	 */
	public function __construct( $uid )
	{
		parent::__construct( $uid );
	}

	/**
	 * Import plaintext XML document and create internal representation of data.
	 *
	 * Since Javascript XML writer writes invalid 'standalone' header attribute
	 * it has be removed to avoid SimpleXML warnings.
	 *
	 * @param <string> $xmlDoc XML document with person's details
	 */
	function importXml ( $xml )
	{
		if ( ( $doc = simplexml_load_string( str_replace( ' standalone="false"', '', Wa::PlusSignWaDecode( $xml ) ) ) ) !== false )
		{
			$this->display = Array( );
			$nodeGlobal = $doc->xpath( '//person/global' );
				$personId = (string)$nodeGlobal[0]['personId'];
				if ( $personId != '' )
					$this->Id = (int) $personId;
				else
					$this->Id = null;

			$nodeCtx = $doc->xpath( '//person/global/display/ctxs' );
				$this->display['contexts'] = null;
					foreach ( $nodeCtx[0] as $context )
						$this->display['contexts'][(int)$context['id']] = true;

			$nodeDisplay = $doc->xpath( '//person/global/display' );
				$this->display['predefined'] = ( (string)$nodeDisplay[0]['predefined'] == 'true' ) ? true : false;
				$this->display['custom'] = base64_decode( (string)$nodeDisplay[0]['custom'] );
				$this->display['format'] = (int)$nodeDisplay[0]['format'];
			$this->personal = Array( );
			$nodePersonal = $doc->xpath( '//person/global/personal' );
				$this->personal['nick'] = base64_decode( (string)$nodePersonal[0]['nick'] );
				$this->personal['titles'] = base64_decode( (string)$nodePersonal[0]['titles'] );
				$this->personal['firstname'] = base64_decode( (string)$nodePersonal[0]['firstname'] );
				$this->personal['secondname'] = base64_decode( (string)$nodePersonal[0]['secondname'] );
				$this->personal['anothernames'] = base64_decode( (string)$nodePersonal[0]['anothernames'] );
				$this->personal['surname'] = base64_decode( (string)$nodePersonal[0]['surname'] );
				$this->personal['secondsurname'] = base64_decode( (string)$nodePersonal[0]['secondsurname'] );
				$this->personal['anothersurnames'] = base64_decode( (string)$nodePersonal[0]['anothersurnames'] );
			$nodeComments = $doc->xpath( '//person/global/personal/comments' );
				$this->personal['comments'] = base64_decode( (string)$nodeComments[0] );
			$nodeBirthday = $doc->xpath( '//person/global/personal/birthday' );
				$this->personal['birthday']['known'] = ( (string)$nodeBirthday[0]['known'] == 'true' ) ? true : false;
				$this->personal['birthday']['day'] = (int)$nodeBirthday[0]['day'];
				$this->personal['birthday']['month'] = (int)$nodeBirthday[0]['month'];
				$this->personal['birthday']['year'] = (int)$nodeBirthday[0]['year'];
			$this->typed = null;
			$nodeTyped = $doc->xpath( '//person/global/tfields' );
				foreach ( $nodeTyped[0] as $field )
				{
					$this->typed[] = Array( 'type' => (string)$field['type'],
											'name' => (string)$field['name'],
											'number' => base64_decode( (string)$field['number'] ),
											'comment' => base64_decode( (string)$field['comment'] ) );
				}
			$this->custom = null;
			$nodeTyped = $doc->xpath( '//person/global/cfields' );
				foreach ( $nodeTyped[0] as $field )
				{
					$this->custom[] = Array( 'name' => base64_decode( (string)$field['name'] ),
											'number' => base64_decode( (string)$field['number'] ),
											'comment' => base64_decode( (string)$field['comment'] ) );
				}
			$this->addresses = null;
			$nodeAddresses = $doc->xpath( '//person/global/addresses' );
				foreach ( $nodeAddresses[0] as $address )
				{
					$this->addresses[] = Array( 'desc' => base64_decode( (string)$address['desc'] ),
											'addr1' => base64_decode( (string)$address['addr1'] ),
											'addr2' => base64_decode( (string)$address['addr2'] ),
											'zip' => base64_decode( (string)$address['zip'] ),
											'city' => base64_decode( (string)$address['city'] ),
											'country' => base64_decode( (string)$address['country'] ),
											'phones' => base64_decode( (string)$address['phones'] ),
											'faxes' => base64_decode( (string)$address['faxes'] ) );
				}
		}

	}

	/**
	 * Create XML document ( in the same format as for importXml() ) to be provided to client.
	 *
	 * @return XML document
	 */
	function exportXml ( )
	{
		$ret = false;
		
		if ( is_array( $this->personal ) )
		{
			$writer = new SimonsXmlWriter( "\t" );
				$writer->push( 'person' );
					$writer->push( 'global', array( 'personId' => $this->Id ) );
						$writer->push( 'display', array( 'predefined' => ( $this->display['predefined'] ) ? 'true' : 'false',
															'format' => $this->display['format'],
															'custom' => $this->display['custom'] ) );

							$writer->push( 'ctxs' );

								//$contexts = Context::Unserialize( $record[self::F_STUFFCTXS] );

								if ( $this->display['contexts'] && is_array( $this->display['contexts'] ) )
								{
									foreach ( $this->display['contexts'] as $cid => $val )
										$writer->element( 'ctx', $cid );
								}

							$writer->pop( );

						$writer->pop( );
						
						$writer->push( 'personal', array( 'nick' => $this->personal['nick'],
															'titles' => $this->personal['titles'],
															'firstname' => $this->personal['firstname'],
															'secondname' => $this->personal['secondname'],
															'anothernames' => $this->personal['anothernames'],
															'surname' => $this->personal['surname'],
															'secondsurname' => $this->personal['secondsurname'],
															'anothersurnames' => $this->personal['anothersurnames'],
															'titles' => $this->personal['titles'],
															'titles' => $this->personal['titles'], ) );
							$writer->element( 'comments', $this->personal['comments'] );
							//$writer->pop( );
						$writer->pop( );
						$writer->push( 'birthday', array( 'known' => ( $this->personal['birthday']['known'] ) ? 'true' : 'false',
															'day' => $this->personal['birthday']['day'],
															'month' => $this->personal['birthday']['month'],
															'year' => $this->personal['birthday']['year'] ) );
						$writer->pop( );

						$this->exportXmlPartial( $writer );

					$writer->pop( );
				$writer->pop( );

			$ret = $writer->getXml( );
		}

		return $ret;
	}

	/*
	 * Format name by given format value from given names.
	 */
	static public function formatName ( $format, $nick, $name, $surname, $secName, $secSurname )
	{
		switch ( $format )
		{
			/*
			 * nick [FirstName Surname]
			 */
			case 0:
				if ( trim( $nick ) == '' )
					return trim( $name . " " . $surname );
				elseif ( trim( $name . $surname ) == '' )
					return trim( $nick );
				else
					return trim( $nick . " [" . trim( $name . " " . $surname ) . "]" );
			break;

			/*
			 * FirstName Surname
			 */
			case 10:
				return trim( $name . " " . $surname );
			break;

			/*
			 * Surname FirstName
			 */
			case 20:
				return trim( $surname . " " . $name );
			break;

			/*
			 * FirstName SecondName Surname
			 */
			case 30:
				if ( trim( $surname ) == '' )
					return trim( $name . " " . $secName );
				else
					return trim( trim( $name . " " . $secName ) . " " . $surname );
			break;

			/*
			 * Surname, FirstName SecondName
			 */
			case 40:
				if ( trim( $name . $secName ) == '' )
					return trim( $secSurname );
				elseif ( trim( $secSurname ) == '' )
					return trim( $name . " " . $secName );
				else
					return trim( $secSurname . ", " . trim( $name . " " . $secName ) );
			break;

			/*
			 * FirstName Surname-SecondSurname
			 */
			case 50:
				if ( trim( $surname . $secSurname ) == '' )
					return trim( $name );
				elseif ( trim( $surname  ) == '' )
					return trim( $name . " " . $secSurname );
				elseif ( trim( $secSurname  ) == '' )
					return trim( $name . " " . $surname );
				else
					return trim( $name . " " . $surname . "-" . $secSurname );
			break;

			/*
			 * FirstName SecondName Surname-SecondSurname
			 */
			case 60:
				if ( trim( $surname . $secSurname ) == '' )
					return trim( $name . " " . $secName);
				elseif ( trim( $surname  ) == '' )
					return trim( trim( $name . " " . $secName) . " " . $secSurname );
				elseif ( trim( $secSurname  ) == '' )
					return trim( trim( $name . " " . $secName) . " " . $surname );
				else
					return trim( trim( $name . " " . $secName) . " " . $surname . "-" . $secSurname );
			break;

			/*
			 * Surname-SecondSurname, FirstName SecondName
			 */
			case 70:
				if ( trim( $surname . $secSurname ) == '' )
					return trim( $name . " " . $secName);
				elseif ( trim( $surname  ) == '' )
					return trim( $secSurname . ", " . trim( $name . " " . $secName) );
				elseif ( trim( $secSurname  ) == '' )
					return trim( $surname . ", " . trim( $name . " " . $secName) );
				else
					return trim( $surname . "-" . $secSurname . ", " . trim( $name . " " . $secName) );
			break;
		}
	}

	/**
	 * This routine had to be written becouse of lack of SUPER privilege on
	 * hosting site klenot.cz.
	 *
	 * Transaction protection should be done in caller.
	 *
	 * @param <int> if not NULL, its value is used as company Id
	 */
	function updateSearchIndex ( $id = NULL )
	{
		if ( $id == NULL )
			$id = $this->id;

		$uid = $this->uid;

		$record = _db_1line( "SELECT * FROM `" . self::T_ABPERSONS . "` JOIN `" . self::T_AB . "` ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABPERSONS . "`.`" . self::F_ABID . "` ) WHERE `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $uid ) . "\" AND `" . self::T_ABPERSONS . "`.`" . self::F_ABID . "` = \"" . _db_escape( $id ) . "\" LIMIT 0,1");

		if ( $record !== false )
		{
			if ( (int)$record[self::F_ABDPREDEF] == 1 )
				$display = self::formatName( (int)$record[self::F_ABDFORMAT],
												$record[self::F_ABPNICK],
												$record[self::F_ABPFIRSTNAME],
												$record[self::F_ABPSURNAME],
												$record[self::F_ABPSECNAME],
												$record[self::F_ABPSECSURNAME]);
			else
				$display = $record[self::F_ABDCUSTOM];
				
			_db_query( "DELETE FROM `" . self::T_ABSEARCHINDEX . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $id ) . "\"" );
			_db_query( "INSERT INTO `" . self::T_ABSEARCHINDEX . "` SET
						`" . self::F_ABCOMMENT . "` = \"" . _db_escape( $record[self::F_ABPCOMMENTS] ) . "\",
						`" . self::F_ABID . "` = \"" . _db_escape( $id ) . "\",
						`" . self::F_ABDISPLAY . "` = \"" . _db_escape( $display ) . "\",
						`" . self::F_ABCTXS . "` = \"" . _db_escape( $record[self::F_ABCTXS] ) . "\"" );
		}
	}

	/*
	 * Globaly-wide variant of UpdateFulltext(). Perform update of fulltext
	 * search table for every person. It is recommended to be ran on maintenance
	 * events.
	 *
	 * @warning This may take a huge amount of time!
	 */
	static function updateSearchIndexAll ( )
	{
		/*$res = db_Query ( "SELECT `" . self::F_ABID ."` FROM `" . self::T_ABPERSONS . "`");
		if ( $res && db_RowCount( $res ) )
			while ( $row = db_FetchRow ( $res ) )
				db_Query( "CALL rosterPersUpdateFulltext( " . db_Escape( $row[self::F_ABID] ) . " )" );

		db_Query( 'BEGIN' );*/

		$res = _db_query( "SELECT `" . self::F_ABUID ."`,`" . self::T_ABPERSONS . "`.`" . self::F_ABID ."` FROM `" . self::T_ABPERSONS . "` JOIN `" . self::T_AB . "` ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABPERSONS . "`.`" . self::F_ABID . "` )" );

		if ( $res && _db_rowcount( $res ) )
			while ( $row = _db_fetchrow ( $res ) )
			{
				$rc = new RosterPerson( $row[self::F_ABUID] );
				$rc->UpdateSearchIndex( $row[self::F_ABID] );
			}

		_db_query( 'COMMIT' );
	}

	/*
	 * Load data from database into internal representation.
	 *
	 * @param $personId person's Id
	 */
	function load ( $personId )
	{
		$this->Id = $personId;
		_db_query( 'BEGIN' );
        $Personal = _db_1line( "SELECT `" . self::T_ABPERSONS . "`.*
								FROM `" . self::T_ABPERSONS . "`
								JOIN `" . self::T_AB . "`
									ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABPERSONS . "`.`" . self::F_ABID . "` )
								WHERE `" . self::T_ABPERSONS . "`.`" . self::F_ABID . "` = \"" . _db_escape( $this->Id ) . "\"
									AND `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $this->UID ) . "\"" );

//var_dump( $Personal  );
		//$Personal = db_1Line( "SELECT * FROM `" . self::T_ABPERSONS . "` WHERE `" . self::F_ABID . "` = \"" . db_Escape( $this->Id ) . "\" AND `" . self::F_ABUID . "` = \"" . db_Escape( $this->UID ) . "\"" );

		if ( $Personal !== false )
		{
			$this->personal = null;
			$this->display = null;

			$this->display['predefined'] =   (bool)$Personal[self::F_ABDPREDEF];
			$this->display['custom']     = (string)$Personal[self::F_ABDCUSTOM];
			$this->display['format']     =    (int)$Personal[self::F_ABDFORMAT];

			$this->personal['nick']            = (string)$Personal[self::F_ABPNICK];
			$this->personal['titles']          = (string)$Personal[self::F_ABPTITLES];
			$this->personal['firstname']       = (string)$Personal[self::F_ABPFIRSTNAME];
			$this->personal['secondname']      = (string)$Personal[self::F_ABPSECNAME];
			$this->personal['anothernames']    = (string)$Personal[self::F_ABPANAMES];
			$this->personal['surname']         = (string)$Personal[self::F_ABPSURNAME];
			$this->personal['secondsurname']   = (string)$Personal[self::F_ABPSECSURNAME];
			$this->personal['anothersurnames'] = (string)$Personal[self::F_ABPASURNAMES];
			$this->personal['comments']        = (string)$Personal[self::F_ABPCOMMENTS];

			$this->personal['birthday']['known'] = (bool)$Personal[self::F_ABPBIRTHDAY];
			$this->personal['birthday']['day']   = (int)substr( $Personal[self::F_ABPBIRTHDAYDAY], 8, 2 );
			$this->personal['birthday']['month'] = (int)substr( $Personal[self::F_ABPBIRTHDAYDAY], 5, 2 );
			$this->personal['birthday']['year']  = (int)substr( $Personal[self::F_ABPBIRTHDAYDAY], 0, 4 );

			$this->display['contexts'] = _cdes::unserialize( $Personal[self::F_ABCTXS] );

			$this->loadPartial( );

			_db_query( 'COMMIT' );
			return true;
        }

		_db_query( 'ROLLBACK' );
		return false;
	}

	/**
	 * Write/update internal details of person into database.
	 */
	function add ( )
	{
		$insertData = "`" . self::F_ABDPREDEF . "` = \"" . _db_escape( $this->display['predefined'] ) . "\",
						`" . self::F_ABDCUSTOM . "` = \"" . _db_escape( $this->display['custom'] ) . "\",
						`" . self::F_ABDFORMAT . "` = \"" . _db_escape( $this->display['format'] ) . "\",
						`" . self::F_ABPNICK . "` = \"" . _db_escape( $this->personal['nick'] ) . "\",
						`" . self::F_ABPTITLES . "` = \"" . _db_escape( $this->personal['titles'] ) . "\",
						`" . self::F_ABPFIRSTNAME . "` = \"" . _db_escape( $this->personal['firstname'] ) . "\",
						`" . self::F_ABPSECNAME . "` = \"" . _db_escape( $this->personal['secondname'] ) . "\",
						`" . self::F_ABPANAMES . "` = \"" . _db_escape( $this->personal['anothernames'] ) . "\",
						`" . self::F_ABPSURNAME . "` = \"" . _db_escape( $this->personal['surname'] ) . "\",
						`" . self::F_ABPSECSURNAME . "` = \"" . _db_escape( $this->personal['secondsurname'] ) . "\",
						`" . self::F_ABPASURNAMES . "` = \"" . _db_escape( $this->personal['anothersurnames'] ) . "\",
						`" . self::F_ABPBIRTHDAY . "` = \"" . _db_escape( $this->personal['birthday']['known'] ) . "\",
						`" . self::F_ABPBIRTHDAYDAY . "` = \"" . _db_escape( $this->personal['birthday']['year'] . '-' . $this->personal['birthday']['month'] . '-' . $this->personal['birthday']['day'] ) . "\",
						`" . self::F_ABPCOMMENTS . "` = \"" . _db_escape( $this->personal['comments'] ) . "\",
						`" . self::F_ABCTXS . "` = \"" . _db_escape( _cdes::serialize( $this->display['contexts'] ) ) . "\"";
		_db_query( 'BEGIN' );

			/*
			 * Personal details.
			 */
			if ( is_null( $this->id ) )
			{
				_db_query( "INSERT INTO `" . self::T_AB . "`
							SET `" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\",
							`" . self::F_ABSCHEME . "` = \"" . self::V_ABSCHPERSON . "\"" );

				$this->id = _db_1field( "SELECT LAST_INSERT_ID()" );
			}

			/*
			 * Cannot create new record.
			 */
			if ( (int) $this->id == 0 )
			{
				_db_query( 'ROLLBACK' );
				return false;
			}

			_db_query( "DELETE FROM `" . self::T_ABPERSONS . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
			_db_query( "INSERT INTO `" . self::T_ABPERSONS . "` SET
							`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\",
							{$insertData}" );

			$this->updateSearchIndex( );

			$this->writePartial( );
			
		_db_query( 'COMMIT' );
	}

	/*
	 * Remove person records from database. There is no Undo.
	 *
	 * @param $personId id of person
	 */
	function remove ( $personId )
	{
		_db_query( 'BEGIN' );
		
			$id = db_1Field( "SELECT `" . self::F_ABUID . "` FROM `" . self::T_AB . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $personId ) . "\" AND `" . self::F_ABUID . "` = \"" . _db_escape( $this->UID ) . "\"" );
			if ( $id != $this->UID )
			{
				db_Query( 'ROLLBACK' );
				return false;
			}
			$this->RemovePartial( );
			_db_query( "DELETE FROM `" . self::T_ABSEARCHINDEX . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $personId ) . "\"" );
			_db_query( "DELETE FROM `" . self::T_ABPERSONS . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $personId ) . "\"" );
			_db_query( "DELETE FROM `" . self::T_AB . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $personId ) . "\"" );

		_db_query( 'COMMIT' );
		
		return true;
	}
}

?>