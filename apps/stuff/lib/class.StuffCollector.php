<?PHP
/* 
 * @file class.StuffCollect.php
 *
 * Object handling collecting of new stuff.
 *
 * @author giorno
 */

require_once N7_SOLUTION_LIB . 'libtz.php';
require_once APP_STUFF_LIB . 'class.StuffEditor.php';
require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'class.Wa.php';
require_once CHASSIS_LIB . 'libdb.php';

class StuffCollector extends StuffEditor
{
	/**
	 * Usability routine. Directly import Stuff array to be written into
	 * database instead of providing XML.
	 * 
	 * @param <array> Stuff details
	 */
	public function importStuff( $stuff )
	{
		$this->data = $stuff;

		/*
		 * If no flags were set in input, give them default value.
		 */
		if ( !isset( $this->data['flags'] ) )
			$this->data['flags'] = 0;

		/*
		 * If no serialized system data were passed, set them to default.
		 */
		if ( !isset( $this->data['data'] ) )
			$this->data['data'] = "";
	}
	
	/**
	 * Write internal data into database as new thing.
	 */
	public function add ( )
	{
		_db_query( 'BEGIN' );

			_db_query ( "INSERT INTO `" . self::T_STUFFINBOX . "`
						SET `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"" );
		
			$this->sid = _db_1field( "SELECT LAST_INSERT_ID()" );
			
			if ( (int) $this->sid == 0 )
			{
				_db_query( 'ROLLBACK' );
				return false;
			}

			_db_query ( "INSERT INTO `" . self::T_STUFFBOXES . "`
						SET `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\",
							`" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\",
							`" . self::F_STUFFSEQ . "` = \"0\",
							`" . self::F_STUFFBOX . "` = \"" . _db_escape( $this->data['box']) . "\",
							`" . self::F_STUFFRECORDED . "` = NOW(),
							`" . self::F_STUFFNAME . "` = \"" . _db_escape( $this->data['name'] ) . "\",
							`" . self::F_STUFFPLACE . "` = \"" . _db_escape( $this->data['place'] ) . "\",
							`" . self::F_STUFFDESC . "` = \"" . _db_escape( $this->data['desc'] ) . "\",
							`" . self::F_STUFFPRIORITY . "` = \"" . _db_escape( $this->data['priority'] ) . "\",
							`" . self::F_STUFFDATESET . "` = \"" . _db_escape( $this->data['date']['set'] ) . "\",
							`" . self::F_STUFFDATEVAL . "` = \"" . _db_escape( $this->data['date']['composed'] ) . "\",
							`" . self::F_STUFFTIMESET . "` = \"" . _db_escape( $this->data['time']['set'] ) . "\",
							`" . self::F_STUFFTIMEVAL . "` = \"" . _db_escape( $this->data['time']['composed'] ) . "\",
							`" . self::F_STUFFCTXS . "` = \"" . _db_escape( _cdes::Serialize($this->data['contexts'] ) ) . "\",
							`" . self::F_STUFFFLAGS . "` = \"" . _db_escape( $this->data['flags'] ) . "\",
							`" . self::F_STUFFDATA . "` = \"" . _db_escape( $this->data['data'] ) . "\"" );

			$this->updatePid( $this->data['pid'] );

		_db_query( 'COMMIT' );
		return true;
	}
}

?>