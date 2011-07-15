<?PHP

/**
 * @file class.AbOrg.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

/**
 * Custom methods and constants for application RosterTab.
 */
class RosterListCell 
{
	const MAN_ROSTERTAGJS      = 'manRosterTagJs';			// tagged javascript text

	const TAG_PERS    = 'tagPerson';						// tag for person (PERS)
	const TAG_COMP    = 'tagCompany';						// tag for company (COMP)

	/**
	 * Method to create data array for MAN_ROSTERTAGJS manager.
	 *
	 * @param text (string) title field
	 * @param code (string) script field
	 * @param code (string) tag field (TAG_* mebmer constants)
	 * @return array
	 */
	public static function TaggedJs ( $text, $code, $tag )
	{
		return Array( 'text' => $text, 'code' => $code, 'tag' => $tag );
	}
}

?>