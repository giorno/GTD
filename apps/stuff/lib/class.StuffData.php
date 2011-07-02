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
	 * Constructor. Takes serialized string and performs some basic extraction.
	 *
	 * @param <string> $serialized serialized record data from database
	 */
	public function  __construct ( $serialized )
	{
		$this->Data = unserialize( $serialized );
		$this->Id = $this->Data['ID'];
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
		global $__welcomeMsg;

		switch ($this->Id)
		{
			case self::ID_WELCOMEMSGv1:
				return $__welcomeMsg[$this->Data['l']]['s1'] . $__welcomeMsg[$this->Data['l']]['li1_1'] . $__welcomeMsg[$this->Data['l']]['li1_settings'] . $__welcomeMsg[$this->Data['l']]['li1_99'];
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