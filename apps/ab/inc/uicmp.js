/**
 * @file _uicmp_stuff.js
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Client side logic for Address Book application UICMP components.
 */

function _uicmp_perse ( layout, tab_id )
{
	/**
	 * Copy scope;
	 */
	var me = this;
	
	/**
	 * Indicates whether form is in edit mode. false value represents
	 * 'Add new person' mode.
	 */
	this.edit = false;
	
	/**
	 * Reference to layout instance.
	 */
	this.layout = layout;
	
	/**
	 * ID of UICMP tab component holding this form.
	 */
	this.tab_id = tab_id;
	
	/**
	 * Put form into Adding new person mode.
	 */
	this.add = function ( )
	{
		this.layout.show( this.tab_id );
		/*this.ind.show( 'preparing', '_uicmp_ind_gray' );*/
		this.edit = false;
		/*this.reset( );*/
	};
}