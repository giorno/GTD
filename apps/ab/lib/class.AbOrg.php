<?php

	/**
 * @file class.AbOrg.php
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
 * Object handling details of Organization-class contact.
 */
class AbOrg extends AbScheme
{
	/*
	 * (Array) General information (names, etc.).
	 */
	private $display = null;

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
			$this->general = Array( );
			$nodeGlobal = $doc->xpath( '//company/global' );
				$companyId = (string)$nodeGlobal[0]['companyId'];
				if ( $companyId != '' )
					$this->id = (int) $companyId;
				else
					$this->id = null;
					
			$nodeCtx = $doc->xpath( '//company/global/general/ctxs' );
				$this->general['contexts'] = null;
					foreach ( $nodeCtx[0] as $context )
						$this->general['contexts'][(int)$context['id']] = true;

			$nodeGeneral = $doc->xpath( '//company/global/general' );
				$this->display['display'] = ( (string)$nodeGeneral[0]['display'] == 'true' ) ? true : false;
				$this->display['displayName'] = base64_decode( (string)$nodeGeneral[0]['displayName'] );
				$this->display['name'] = base64_decode( (string)$nodeGeneral[0]['name'] );
			$nodeComments = $doc->xpath( '//company/global/general/comments' );
				$this->display['comments'] = base64_decode( (string)$nodeComments[0] );
			
			$this->typed = null;
			$nodeTyped = $doc->xpath( '//company/global/tfields' );
				foreach ( $nodeTyped[0] as $field )
				{
					$this->typed[] = Array( 'type' => (string)$field['type'],
											'name' => (string)$field['name'],
											'number' => base64_decode( (string)$field['number'] ),
											'comment' => base64_decode( (string)$field['comment'] ) );
				}
			$this->custom = null;
			$nodeTyped = $doc->xpath( '//company/global/cfields' );
				foreach ( $nodeTyped[0] as $field )
				{
					$this->custom[] = Array( 'name' => base64_decode( (string)$field['name'] ),
											'number' => base64_decode( (string)$field['number'] ),
											'comment' => base64_decode( (string)$field['comment'] ) );
				}
			$this->addresses = null;
			$nodeAddresses = $doc->xpath( '//company/global/addresses' );
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

		if ( is_array( $this->display) )
		{
			$writer = new SimonsXmlWriter( "\t" );
				$writer->push( 'company' );
					$writer->push( 'global', array( 'companyId' => $this->id ) );
						$writer->push( 'general', array( 'display' => ( $this->display['display'] ) ? 'true' : 'false',
															'displayName' => $this->display['displayName'],
															'name' => $this->display['name'] ) );
							$writer->element( 'comments', $this->display['comments'] );

							$writer->push( 'ctxs' );

								//$contexts = Context::Unserialize( $record[self::F_STUFFCTXS] );

								if ( $this->general['contexts'] && is_array( $this->general['contexts'] ) )
								{
									foreach ( $this->general['contexts'] as $cid => $val )
										$writer->element( 'ctx', $cid );
								}

							$writer->pop( );

						$writer->pop( );
			
						$this->ExportXmlPartial( $writer );

					$writer->pop( );
				$writer->pop( );

			$ret = $writer->getXml( );
		}

		return $ret;
	}

	/**
	 * This routine had to be written becouse of lack of SUPER privilege on
	 * hosting site klenot.cz.
	 *
	 * Transaction protection should be done in caller.
	 *
	 * @param <int> if not NULL, its value is used as company Id
	 */
	function updateSearchIndex ( $Id = NULL )
	{
		if ( $Id == NULL )
			$Id = $this->id;

		$UID = $this->uid;

		$record = _db_1line( "SELECT * FROM `" . self::T_ABCOMPANIES . "` JOIN `" . self::T_AB . "` ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABCOMPANIES . "`.`" . self::F_ABID . "` ) WHERE `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $UID ) . "\" AND `" . self::T_ABCOMPANIES . "`.`" . self::F_ABID . "` = \"" . _db_escape( $Id ) . "\" LIMIT 0,1");

		if ( $record !== false )
		{
			if ( (int)$record[self::F_ABDCUSTOM] == 0 )
				$display = $record[self::F_ABNAME];
			else
				$display = $record[self::F_ABDNAME];
				
			_db_query( "DELETE FROM `" . self::T_ABSEARCHINDEX . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $Id ) . "\"" );
			_db_query( "INSERT INTO `" . self::T_ABSEARCHINDEX . "` SET
						`" . self::F_ABCOMMENT . "` = \"" . _db_escape( $record[self::F_ABCOMMENTS] ) . "\",
						`" . self::F_ABID . "` = \"" . _db_escape( $Id ) . "\",
						`" . self::F_ABDISPLAY . "` = \"" . _db_escape( $display ) . "\",
						`" . self::F_ABCTXS . "` = \"" . _db_escape( $record[self::F_ABCTXS] ) . "\"" );
		}
	}

	/*
	 * Globaly-wide variant of UpdateSearchIndex(). Perform update of fulltext
	 * search table for every person. It is recommended to be ran on maintenance
	 * events. It is not ran for one user, but for all recorded companies in the
	 * table.
	 *
	 * @warning This may take a huge amount of time!
	 */
	static function updateSearchIndexAll ( )
	{
		_db_query( 'BEGIN' );
		
		$res = _db_query( "SELECT `" . self::F_ABUID ."`,`" . self::T_ABCOMPANIES . "`.`" . self::F_ABID ."` FROM `" . self::T_ABCOMPANIES . "` JOIN `" . self::T_AB . "` ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABCOMPANIES . "`.`" . self::F_ABID . "` )" );

		if ( $res && _db_rowcount( $res ) )
			while ( $row = _db_fetchrow ( $res ) )
			{
				$rc = new AbOrg( $row[self::F_ABUID] );
				$rc->updateSearchIndex( $row[self::F_ABID] );
			}

		_db_query( 'COMMIT' );
	}

	/*
	 * Write/update internal details of company into database.
	 */
	function add ( )
	{
		$insertData = " `" . self::F_ABDCUSTOM . "` = \"" . _db_escape( $this->display['display'] ) . "\",
						`" . self::F_ABDNAME . "` = \"" . _db_escape( $this->display['displayName'] ) . "\",
						`" . self::F_ABNAME . "` = \"" . _db_escape( $this->display['name'] ) . "\",
						`" . self::F_ABCOMMENTS . "` = \"" . _db_escape( $this->display['comments'] ) . "\",
						`" . self::F_ABCTXS . "` = \"" . _db_escape( _cdes::serialize($this->general['contexts'] ) ) . "\"";

		_db_query( 'BEGIN' );

			/*
			 * Company details.
			 */
			if ( is_null( $this->id ) || ( (int)$this->id == 0 ) )
			{
				_db_query( "INSERT INTO `" . self::T_AB . "`
							SET `" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\",
							`" . self::F_ABSCHEME . "` = \"" . self::V_ABSCHCOMPANY . "\"" );

				$this->id = _db_1field( "SELECT LAST_INSERT_ID()" );
			}
			/*
			 *
			 * Cannot create new record.
			 */
			if ( (int) $this->id == 0 )
			{
				_db_query( 'ROLLBACK' );
				return false;
			}

			_db_query( "DELETE FROM `" . self::T_ABCOMPANIES . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"" );
			
			_db_query( "INSERT INTO `" . self::T_ABCOMPANIES . "` SET
							`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\",
							{$insertData}" );

			$this->updateSearchIndex( );

			$this->writePartial( );
			

		_db_query( 'COMMIT' );
	}

	/*
	 * Load data from database into internal representation.
	 *
	 * @param $companyId company's Id
	 */
	function load ( $companyId )
	{
		$this->id = $companyId;

		_db_query( 'BEGIN' );

		$Display = _db_1line( "SELECT `" . self::T_ABCOMPANIES . "`.*
								FROM `" . self::T_ABCOMPANIES . "`
								JOIN `" . self::T_AB . "`
									ON ( `" . self::T_AB . "`.`" . self::F_ABID . "` = `" . self::T_ABCOMPANIES . "`.`" . self::F_ABID . "`)
								WHERE `" . self::T_ABCOMPANIES . "`.`" . self::F_ABID . "` = \"" . _db_escape( $this->id ) . "\"
									AND `" . self::T_AB . "`.`" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\"" );

		if ( $Display !== false )
		{
			$this->display = null;

			$this->display['display']     = (bool)$Display[self::F_ABDCUSTOM];
			$this->display['displayName'] = (string)$Display[self::F_ABDNAME];
			$this->display['name']        = (string)$Display[self::F_ABNAME];
			$this->display['comments']    = (string)$Display[self::F_ABCOMMENTS];

			$this->general['contexts'] = _cdes::unserialize( $Display[self::F_ABCTXS] );
//var_dump($this->General);
			$this->LoadPartial( );
//			var_dump($this->General);
			_db_query( 'COMMIT' );
			return true;
		}

		_db_query( 'ROLLBACK' );
		return false;
	}

	/*
	 * Remove person records from database. There is no Undo.
	 *
	 * @param $personId id of person
	 */
	function remove ( $companyId )
	{
		_db_query( 'BEGIN' );

			$id = _db_1field( "SELECT `" . self::F_ABUID . "` FROM `" . self::T_AB . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $companyId ) . "\" AND `" . self::F_ABUID . "` = \"" . _db_escape( $this->uid ) . "\"" );
			if ( $id != $this->uid )
			{
				_db_query( 'ROLLBACK' );
				return false;
			}
			$this->removePartial( );
			_db_query( "DELETE FROM `" . self::T_ABSEARCHINDEX . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $companyId ) . "\"" );
			_db_query( "DELETE FROM `" . self::T_ABCOMPANIES . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $companyId ) . "\"" );
			_db_query( "DELETE FROM `" . self::T_AB . "` WHERE `" . self::F_ABID . "` = \"" . _db_escape( $companyId ) . "\"" );

		_db_query( 'COMMIT' );

		return true;
	}
}

?>