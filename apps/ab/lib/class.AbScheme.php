<?PHP

/**
 * @file class.AbScheme.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once APP_AB_LIB . 'class.AbConfig.php';

/**
 * Ancestor for AbPerson and AbCompany classes. It has to provide
 * methods and variables for common data structures and operations.
 */
class AbScheme extends AbConfig
{
	/*
	 * Id of company record.
	 */
	protected  $id = null;

	/*
	 * User's ID.
	 */
	protected $uid = null;

	/*
	 * (Array) Typed fields (e-mails, phones, etc.).
	 */
	protected $typed = null;

	/*
	 * (Array) Custom (not yet used) fields.
	 */
	protected $custom = null;

	/*
	 * (Addresses) Postal addresses.
	 */
	protected $addresses = null;

	/*
	 * Constructor. Set UserId.
	 */
	public function __construct( $uid )
	{
		$this->uid = $uid;
	}

	/*
	 * Write common partial informations structure into database. Structure
	 * consists of pretyped and custom contact details, addresses, etc.
	 *
	 * No protection through transactions is used here, caller must implement
	 * its own.
	 */
	protected function writePartial ( )
	{
		_db_query( "DELETE FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );

			/*
			 * Save pretyped fields.
			 */
			if ( $this->typed && count( $this->typed) )
			{
				foreach ( $this->typed as $field )
				{
					if ( trim( $field['number'] ) != '' )
						_db_query( "INSERT INTO `" . self::T_ABNUMBERS . "` SET
									`" . self::F_ABNTYPE . "` = \"" . _db_escape( $field['type'] ) . "\",
									`" . self::F_ABNNAME . "` = \"" . _db_escape( $field['name'] ) . "\",
									`" . self::F_ABNNUMBER . "` = \"" . _db_escape( $field['number'] ) . "\",
									`" . self::F_ABNCOMMENT . "` = \"" . _db_escape( $field['comment'] ) . "\",
									`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );

				}
			}

			/*
			 * Save custom fields.
			 */
			if ( $this->custom && count( $this->custom) )
			{
				foreach ( $this->custom as $field )
				{
					if ( trim( $field['number'] ) != '' )
						_db_query( "INSERT INTO `" . self::T_ABNUMBERS . "` SET
									`" . self::F_ABNTYPE . "` = \"" . _db_escape( $field['name'] ) . "\",
									`" . self::F_ABNNAME . "` = \"" . _db_escape( $field['name'] ) . "\",
									`" . self::F_ABNNUMBER . "` = \"" . _db_escape( $field['number'] ) . "\",
									`" . self::F_ABNCOMMENT . "` = \"" . _db_escape( $field['comment'] ) . "\",
									`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );

				}
			}

		_db_query( "DELETE FROM `" . self::T_ABADDRESSES . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );

			/*
			 * Save addresses.
			 */
			if ( $this->addresses && count( $this->addresses) )
			{
				foreach ( $this->addresses as $address )
				{
					// is there at least one non empty character?
					if ( trim( $address['desc'] . $address['addr1'] . $address['addr2'] . $address['zip']
								. $address['city'] . $address['country'] . $address['phones'] . $address['faxes'] ) != '' )
						_db_query( "INSERT INTO `" . self::T_ABADDRESSES . "` SET
									`" . self::F_ABNDESC . "` = \"" . _db_escape( $address['desc'] ) . "\",
									`" . self::F_ABNADDR1 . "` = \"" . _db_escape( $address['addr1'] ) . "\",
									`" . self::F_ABNADDR2 . "` = \"" . _db_escape( $address['addr2'] ) . "\",
									`" . self::F_ABNZIP . "` = \"" . _db_escape( $address['zip'] ) . "\",
									`" . self::F_ABNCITY . "` = \"" . _db_escape( $address['city'] ) . "\",
									`" . self::F_ABNCOUNTRY . "` = \"" . _db_escape( $address['country'] ) . "\",
									`" . self::F_ABNPHONES . "` = \"" . _db_escape( $address['phones'] ) . "\",
									`" . self::F_ABNFAXES . "` = \"" . _db_escape( $address['faxes'] ) . "\",
									`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );

				}
			}
	}

	/*
	 * Load common partial informations (addresses, numbers, etc.) from database
	 * into internal structures. Transaction protection has to done in caller.
	 */
	protected function loadPartial ( )
	{
		/*
		 * At this time only Typed are used, becouse Custom has became Typed after writing them into database.
		 */
		$this->typed = null;
		$this->custom = null;
		$res = _db_query( "SELECT * FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$this->typed[] = Array( 'type' => (string)$row[self::F_ABNTYPE],
										'name' => (string)$row[self::F_ABNNAME],
										'number' => (string)$row[self::F_ABNNUMBER],
										'comment' => (string)$row[self::F_ABNCOMMENT] );
			}
		}

		$this->addresses = null;
		$res = _db_query( "SELECT * FROM `" . self::T_ABADDRESSES . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$this->addresses[] = Array( 'desc' => (string)$row[self::F_ABNDESC],
										'addr1' => (string)$row[self::F_ABNADDR1],
										'addr2' => (string)$row[self::F_ABNADDR2],
										'zip' => (string)$row[self::F_ABNZIP],
										'city' => (string)$row[self::F_ABNCITY],
										'country' => (string)$row[self::F_ABNCOUNTRY],
										'phones' => (string)$row[self::F_ABNPHONES],
										'faxes' => (string)$row[self::F_ABNFAXES] );
			}
		}
	}

	/*
	 * Write partial information by given XML writer. Writer has to be reference
	 * to Simon's XML Writer.
	 */
	protected function exportXmlPartial ( &$writer )
	{
		if ( is_array( $this->typed ) )
		{
			$writer->push( 'tfields' );

				foreach ( $this->typed as $typed )
				{
					$writer->push( 'field', array( 'type' => $typed['type'],
													'name' => $typed['name'],
													'number' => $typed['number'],
													'comment' => $typed['comment'] ) );
					$writer->pop( );
				}
			$writer->pop( );
		}

		if ( is_array( $this->addresses ) )
		{
			$writer->push( 'addresses' );

				foreach ( $this->addresses as $address )
				{
					$writer->push( 'address', array( 'desc' => $address['desc'],
													'addr1' => $address['addr1'],
													'addr2' => $address['addr2'],
													'zip' => $address['zip'],
													'city' => $address['city'],
													'country' => $address['country'],
													'phones' => $address['phones'],
													'faxes' => $address['faxes'] ) );
					$writer->pop( );
				}
			$writer->pop( );
		}
	}

	/*
	 * Remove partial structures from database for instance's contact id.
	 */
	protected function removePartial ( )
	{
		_db_query( "DELETE FROM `" . self::T_ABADDRESSES . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
		_db_query( "DELETE FROM `" . self::T_ABNUMBERS . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
	}

	/*
	 * Function to provide list of codes/names for pretyped fields in forms.
	 * List is to be ordered by popularity/frequency of usage.
	 *
	 * @param UID (int) id of app user
	 * @return Javscript code (must be executed via eval() on client side
	 */
	static function typedNames ( $UID )
	{
		global $__ACTYPES;
		
		/*
		 * Load from database.
		 */
		$res = _db_query( "SELECT `" . self::T_ABNUMBERS . "`.`" . self::F_ABNNAME . "`,
							`" . self::T_ABNUMBERS . "`.`" . self::F_ABNTYPE . "`,
							COUNT(*) as frequency
							FROM `" . self::T_ABNUMBERS . "`
							JOIN `" . self::T_AB. "` ON ( `" . self::T_AB. "`.`" . self::F_ABID . "` = `" . self::T_ABNUMBERS. "`.`" . self::F_ABID . "` )
							WHERE `" . self::T_AB. "`.`" . self::F_ABUID . "` = \"" . _db_escape( $UID ) . "\"
							GROUP BY `" . self::T_ABNUMBERS . "`.`" . self::F_ABNTYPE . "`
							ORDER BY frequency DESC" );

		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$ret[$row[self::F_ABNTYPE]] = $row[self::F_ABNNAME];
			}
		}

		/*
		 * Append defaults if there is no usage of each of them.
		 */
		if ( $__ACTYPES && count( $__ACTYPES ) )
		{
			foreach ( $__ACTYPES as $type => $name )
			{
				if ( !array_key_exists( $type, $ret ) )
				{
					$ret[$type] = $name;
				}
			}
		}

		if ( $ret && count( $ret ) )
		{
			$code = "__AcPredefinedTypes = new Object( );\n";

			foreach ( $ret as $type => $name )
			{
				if ( trim( $type ) != '' )
					$code .= "\t__AcPredefinedTypes['{$type}'] = '{$name}';\n";
			}
		}
		return $code;
	}
}

?>