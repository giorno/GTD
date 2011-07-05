<?php
/**
 * @file class.StuffData.php
 *
 * Class handling content of system data field of records in boxes table. This
 * approach was chosen to avoid future redesigning non-object libray, which may
 * serve the purpose.
 *
 * @author giorno
 */

require_once APP_STUFF_LIB . 'class.StuffConfig.php';

class StuffData extends StuffConfig
{
	/**
	 * Unserialized structure from record in boxes table.
	 * 
	 * @var <array> 
	 */
	private $Data;

	/**
	 * Id of system data.
	 *
	 * @var <string>
	 */
	private $Id;

	/**
	 * Reference to localization messages and formats.
	 *
	 * @var <refarray>
	 */
	private $messages = null;

	/**
	 * Constructor. Takes serialized string and performs some basic extraction.
	 *
	 * @param <string> $serialized serialized record data from database
	 */
	public function  __construct ( $serialized )
	{
		$this->Data = unserialize( $serialized );
		$this->Id = $this->Data['ID'];

		/**
		 * Getting array of localization messages containing date and time
		 * formats.
		 */
		$app = _app_registry::getInstance()->getById( Stuff::APP_ID );

		if ( !is_null( $app ) )
			$this->messages = &$app->getMessages( );
	}

	/**
	 * Read access to Data member.
	 *
	 * @return <array>
	 */
	public function GetData ( ) { return $this->Data; }

	/**
	 * Provides plain text for details field of system message.
	 * @return <string>
	 */
	public function GetDetails ( )
	{
		switch ($this->Id)
		{
			case self::ID_WELCOMEMSGv1:
				return $this->messages['wlc']['intro'] . ' ' . $this->messages['wlc']['li1'];
			break;

			case self::ID_BIRTHDAYv1:
			break;
		}

		return '';
	}

	/**
	 * Prepare structure for welcome message and serialize it.
	 *
	 * @param <string> $lang two-char identification of language
	 * @return <string> serialized structure
	 */
	static public function EncodeWelcomeMsgV1 ( $lang ) { return serialize( Array( 'ID' => self::ID_WELCOMEMSGv1, 'l' => $lang ) ); }

	/**
	 * Prepare structure for birthday reminder message and serializ it.
	 *
	 * @param <string> $lang two-char identification of language
	 * @param <int> $personId Id of person from Persons table (AddressBook)
	 * @param <string> $date YYYY-MM-DD formatted birthday
	 * @return <string> serialized structure
	 */
	static public function EncodeBirthdayV1 ( $lang, $personId, $date ) { return serialize( Array( 'ID' => self::ID_BIRTHDAYv1, 'l' => $lang, 'p' => $personId, 'd' => $date) ); }

}

?>