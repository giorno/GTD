<?PHP
/*
 * @file class.StuffListCell.php
 *
 * Custom methods and constants for application Stuff tab.
 *
 * @author giorno
 */

class StuffListCell
{
	const MAN_STUFFPRIORITY  = 'manStuffPriority';		// graphic indicator of task priority
	const MAN_STUFFDATETIME  = 'manStuffDateTime';		// date time handler, it uses framework method for data composition
	const MAN_STUFFICODONE   = 'manStuffIconDone';		// icon to move stuff into box Done
	const MAN_STUFF2M        = 'manStuff2m';			// 2 minute stuff icon

	/*
	 * Method to create data array for MAN_STUFFPRIORITY manager.
	 *
	 * @param value (int) priority value
	 * @param class custom CSS class to decorate item
	 * @return array
	 */
	public static function StuffPriority ( $value,  $class = '' )
	{
		return Array( 'value' => $value, 'class' => $class );
	}
}

?>