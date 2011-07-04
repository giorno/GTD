<?php
/**
 * @file class.StuffGoals.php
 *
 * Class to manipulate goals table. Goals listing is done in the file
 * class.StuffSearch.php.
 *
 * @author sk1u01p1
 */

require_once APP_STUFF_LIB . 'class.StuffSearch.php';

class StuffGoals extends StuffSearch
{

	public function __construct ( $UID )
	{
		parent::__construct( $UID );
	}

	/*
	 * Set new value of goal weight for Stuff.
	 */
	public function setWeight( $SID, $weight )
	{
		_db_query( 'BEGIN' );

		/*
		 * Security check.
		 */
		if ( $this->UID != (int)_db_1field( "SELECT `" . self::F_STUFFUID . "` FROM `" . self::T_STUFFINBOX . "` WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $SID ) . "\"" ) )
		{
			_db_query( 'ROLLBACK' );
			return;
		}

		if ( (int) $weight > 4 )
			$weight = 4;
			
		_db_query( "DELETE FROM `" . self::T_STUFFGOALS . "` WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $SID ) . "\"" );
		_db_query( "INSERT INTO `" . self::T_STUFFGOALS . "` SET `" . self::F_STUFFSID . "` = \"" . _db_escape( $SID ) . "\", `" . self::F_STUFFWEIGHT . "` = \"" . _db_escape( $weight ) . "\"" );
		
		_db_query( 'COMMIT' );
	}

	/**
	 * Provide array with Stuff labeled with context set as lifegoals context.
	 *
	 * @param <int> ctxId id of context used as mark for life goals
	 * @param <int> boxes 0-Sd, 1-all except Ar or 2-all
	 * @return <array>
	 */
	public function Lifegoals ( $ctxId, $boxes )
	{
		$ret = null;

		/**
		 * Check if Id of Lifegoal context still exists.
		 */
		$this->contexts( );
		if ( !is_array( $this->contexts ) || ( !array_key_exists( (int)$ctxId, $this->contexts ) ) )
			return $ret;

		/**
		 * Logic to select proper boxes by user setting.
		 */
		$where = '';
		switch ( $boxes )
		{
			case 0:
				$where = "AND sq2.`" . self::F_STUFFBOX . "` = \"Sd\"";
			break;

			case 1:
				$where = "AND sq2.`" . self::F_STUFFBOX . "` <> \"Ar\"";
			break;
		}

		$res = _db_query( "SELECT sq2.*,`" . self::T_STUFFGOALS . "`.`" . self::F_STUFFWEIGHT . "` FROM( SELECT * FROM ( SELECT `" . self::T_STUFFBOXES . "`.* FROM `" . self::T_STUFFBOXES . "`

																GROUP BY `" . self::F_STUFFSID . "`,`" . self::F_STUFFSEQ . "`
																ORDER BY `" . self::F_STUFFSEQ . "` DESC )
												sq1
												GROUP BY `" . self::F_STUFFSID . "`
											) sq2
							LEFT JOIN `" . self::T_STUFFGOALS . "` ON ( `" . self::T_STUFFGOALS . "`.`" . self::F_STUFFSID . "` = sq2.`" . self::F_STUFFSID . "` )
							WHERE `" . self::F_STUFFCTXS . "` LIKE \"%|" . _db_escape( $ctxId ) . "|%\" {$where}
							 ORDER BY `" . self::F_STUFFNAME . "`" );

		if ( $res && _db_rowcount( $res ) )
		{
			while ( $row = _db_fetchrow( $res ) )
			{
				$ret[$row[self::F_STUFFSID]]['name'] = $row[self::F_STUFFNAME];
				$ret[$row[self::F_STUFFSID]]['box'] = $row[self::F_STUFFBOX];
				$ret[$row[self::F_STUFFSID]]['weight'] = (int)$row[self::F_STUFFWEIGHT];
			}
		}

		return $ret;
	}

	/**
	 * Provide Javascript coded array from Lifegoals( ).
	 *
	 * @param <int> ctxId id of context used as mark for life goals
	 * @param <int> boxes 0-Sd, 1-all except Ar or 2-all
	 * @return <string>
	 */
	/*public function LifegoalsJs ( $ctxId, $boxes )
	{
		$array = $this->Lifegoals( $ctxId, $boxes );

		$ret = "/*\n* Array containing weights of goals. Acting as local cache.\n*//*\nvar goalsWeights = new Object( );\n";

		if ( $array && is_array( $array) )
		{
			foreach ( $array as $SID => $values )
				$ret .= "goalsWeights[" . $SID . "] = " . (int) $values['weight'] . ";\n";
		}

		return $ret;
	}*/
}

?>