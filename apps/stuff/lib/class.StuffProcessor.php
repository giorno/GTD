<?php
/**
 * @file class.FormDataProcessor.php
 *
 * Handling the Process form. This is core object of FormData tab app.
 *
 * @author giorno
 */

require_once N7_SOLUTION_LIB . 'libtz.php';
require_once APP_STUFF_LIB . 'class.StuffEditor.php';
require_once APP_STUFF_LIB . 'class.StuffHistory.php';
require_once CHASSIS_LIB . '_cdes.php';
require_once CHASSIS_LIB . 'class.Wa.php';
require_once CHASSIS_LIB . 'libdb.php';

class StuffProcessor extends StuffEditor
{
	/**
	 * Write internal representation into the database as new step in FormData
	 * development.
	 */
	public function add ( )
	{
		_db_query( 'BEGIN' );

		/*
		 * Security check
		 */
		if ( $this->uid == _db_1field( "SELECT `" . self::F_STUFFUID . "` FROM `" . self::T_STUFFINBOX . "` WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"" ) )
		{
			/*
			 * Retrieve sequence number. If there is not yet record in boxes table, new seq. number will be 1.
			 */
			$seq = (int) _db_1field( "SELECT MAX(`" . self::F_STUFFSEQ . "`) FROM `" . self::T_STUFFBOXES . "` WHERE `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"");
			$seq++;

			_db_query ( "INSERT INTO `" . self::T_STUFFBOXES . "`
						SET `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\",
							`" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\",
							`" . self::F_STUFFSEQ . "` = \"" . _db_escape( $seq ) . "\",
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
							`" . self::F_STUFFCTXS . "` = \"" . _db_escape( _cdes::Serialize($this->data['contexts'] ) ) . "\"" );

			/*
			 * Set item's parent.
			 */
			$this->UpdatePid( $this->data['pid'] );
			
			/*
			 * Update item's name if it is a project.
			 */
			$this->UpdateProjectName( $this->sid );
			
		}
		else
		{
			_db_query( 'ROLLBACK' );
			return;
		}

		_db_query( 'COMMIT' );
	}

	/**
	 * Archive stuff with specified label used as caption/comment. This is
	 * routine for OneClick icons in lists of boxes.
	 *
	 * @param SID Stuff If to move
	 * @param label designation of localized string to be used as caption
	 */
	public function archive ( $sid, $label )
	{
		$app = &_app_registry::getInstance( )->getById( Stuff::APP_ID );

		if ( !is_null( $app ) )
			$messages = &$app->getMessages( );
		else
			return;

		$this->sid = $sid;

		switch ( $label )
		{
			/*
			 * Marked as 2 minutes job.
			 */
			case '2': $caption = $messages['arMinutes']; break;

			/*
			 * Marked as Garbage.
			 */
			case 'G': $caption = $messages['arGarbage']; break;

			/*
			 * Marked as Finished.
			 */
			case 'F':
			default:
				$caption = $messages['arFinished'];
			break;
		}

		$History = new StuffHistory( $this->uid, $this->sid );
			$last = $History->ExportLastData( );

			$this->data['box']              = 'Ar';
			$this->data['pid']              = $last['pid'];
			$this->data['priority']         = $last['priority'];
			$this->data['name']             = '[' . $caption . '] ' . $last['task'];
			$this->data['place']            = $last['place'];
			$this->data['contexts']         = $last['contexts'];
			$this->data['desc']             = '';
			$this->data['date']['set']      = $last['dateSet'];
			$this->data['date']['composed'] = $last['date'];
			$this->data['time']['set']      = $last['timeSet'];
			$this->data['time']['composed'] = $last['time'];

		$this->Add( );
	}

	/**
	 * Total removal of the stuff. This cannot be undone.
	 *
	 * @param SID Stuff Id of thing to remove
	 */
	public function purge ( $sid )
	{
		$this->sid = $sid;

		/*
		 * Some safety.
		 */
		$history = new StuffHistory( $this->uid, $this->sid );
			$last = $history->ExportLastData( );
		//	var_dump($sid);

		/*
		 * Security precaution.
		 */
		if ( $last['box'] != 'Ar' ) return false;

		/*
		 * Bytes to bytes...
		 */
		_db_query( 'BEGIN' );

			$this->UpdatePid( 0 );

			_db_query( "DELETE FROM `" . self::T_STUFFBOXES . "`
							WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"
									AND `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"" );
			_db_query( "DELETE FROM `" . self::T_STUFFINBOX . "`
							WHERE `" . self::F_STUFFUID . "` = \"" . _db_escape( $this->uid ) . "\"
									AND `" . self::F_STUFFSID . "` = \"" . _db_escape( $this->sid ) . "\"" );
		_db_query( 'COMMIT' );
	}

	/**
	 * Batch purge of more items.
	 *
	 * @param <string> $ids comma separated list of Stuff Id-s
	 */
	public function purgeBatch ( $ids )
	{
		$items = explode( ',', $ids );
		if ( is_array( $items ) )
			foreach ( $items as $sid )
				$this->purge( $sid );
	}

}

?>