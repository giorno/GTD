<?php
/**
 * @file class.StuffConfig.php
 *
 * Common configuration for app Stuff, mainly names of MySQL tables and fields.
 * 
 * @todo merge into StuffCfgFactory
 *
 * @author giorno
 */

class StuffConfig
{
	/**
	 * Constants describing Stuff application database tables.
	 */
    const T_STUFFINBOX					= 'stuff_inbox';
	const T_STUFFBOXES					= 'stuff_boxes';
	const T_STUFFCTX					= 'stuff_tags';
	const T_STUFFGOALS					= 'stuff_goals';
	const T_STUFFPROJECTS				= 'stuff_projects';

	/**
	 * Constants describing Stuff application database tables fields.
	 */
	const F_STUFFSID					= 'SID';
	const F_STUFFUID					= 'UID';
	const F_STUFFRECORDED				= 'recorded';
	const F_STUFFNAME					= 'name';
	const F_STUFFPLACE					= 'place';
	const F_STUFFDESC					= 'desc';
	const F_STUFFPRIORITY				= 'priority';
	const F_STUFFDATESET				= 'dateSet';
	const F_STUFFDATEVAL				= 'dateValue';
	const F_STUFFTIMESET				= 'timeSet';
	const F_STUFFTIMEVAL				= 'timeValue';
	const F_STUFFSEQ					= 'sequence';
	const F_STUFFBOX					= 'box';
	const F_STUFFCTXS					= 'contexts';
	const F_STUFFWEIGHT					= 'weight';
	const F_STUFFPID					= 'parent';

	/**
	 * Special column in boxes table to store bitfield flags of record.
	 *
	 * Masks:
	 * 1 - s/c RO flag, indicates author of the record [user=0, system=1], used
	 *     e.g. to prevent user from editing system generated records such as
	 *     first invitation message or birthday reminder record.
	 *
	 */
	const F_STUFFFLAGS					= 'flags';

	/**
	 * System data. Content of this field is supposed to be non-editable and
	 * reserved for application of handling and rendering.
	 */
	const F_STUFFDATA					= 'data';

	/**
	 * Mask values for self::F_STUFFFLAGS (see above).
	 */
	const M_SYSTEM						= 1;

	/**
	 * Constants describing Stuff application database fields enum values.
	 */
	const E_STUFFBOX_INBOX				= 'Inbox';
	const E_STUFFBOX_NA					= 'Na';
	const E_STUFFBOX_WF					= 'Wf';
	const E_STUFFBOX_SD					= 'Sd';
	const E_STUFFBOX_AR					= 'Ar';

	/**
	 * Pagesize of projects in Project Picker
	 */
	const PRJPICKPAGESIZE				= 15;

	/**
	 * Maximal level of project subtree. Everything over this is considered
	 * as cycled loop and is illegal.
	 */
	const PRJMAXSUBTREEDEPTH			= 32;

	/**
	 * Lists headers fields widths.
	 */
	const LIST_HDRW_TIMEFRAME			= '120px';
	const LIST_HDRW_RECORDED			= '72px';
	const LIST_HDRW_BOX					= '90px';
	const LIST_HDRW_PRIORITY			= '50px';
	const LIST_HDRW_PARENT				= '150px';
	const LIST_HDRW_ICON				= '0px';

	/**
	 * Ids for data field of boxes table to describe system generated records.
	 * Version part should be used for keeping backward compatibility in the
	 * future. Nothing more, just that. Values are directly used in
	 * x_history.html, so on any change follow it up.
	 */
	const ID_WELCOMEMSGv1				= '__WELCOMEMSGv1__';
	const ID_BIRTHDAYv1					= '__BIRTHDAYv1__';
}

?>