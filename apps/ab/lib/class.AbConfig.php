<?PHP

/**
 * @file class.AbConfig.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 */

/**
 * Application specific settings or strings, e.g. database tables and fiels
 * names.
 * 
 * @todo merge with AbCfgFactory
 */
class AbConfig
{
	/*
	 * Database table names.
	 */
	const T_AB					= 'tAddrBook';
	const T_ABPERSONS			= 'tAddrBookPersons';
	const T_ABCOMPANIES			= 'tAddrBookCompanies';
	const T_ABNUMBERS			= 'tAddrBookNumbers';
	const T_ABADDRESSES			= 'tAddrBookAddresses';
	const T_ABSEARCHINDEX		= 'tAddrBookSearchIndex';
	const T_ABCTX				= 'tAddrBookContexts';

	/*
	 * Database field names.
	 */
	const F_ABUID				= 'uid';
	const F_ABID				= 'id';
	const F_ABSCHEME			= 'scheme';
	const F_ABDPREDEF			= 'disp_predef';
	const F_ABDCUSTOM			= 'disp_cust';
	const F_ABDFORMAT			= 'disp_fmt';
	const F_ABDNAME				= 'disp_name';
	const F_ABNAME				= 'name';
	const F_ABPNICK				= 'nick';
	const F_ABPTITLES			= 'titles';
	const F_ABPFIRSTNAME		= 'first_name';
	const F_ABPSECNAME			= 'sec_name';
	const F_ABPANAMES			= 'another_names';
	const F_ABPSURNAME			= 'surname';
	const F_ABPSECSURNAME		= 'sec_surname';
	const F_ABPASURNAMES		= 'another_surnames';
	const F_ABPCOMMENTS			= 'comments';
	const F_ABCOMMENTS			= self::F_ABPCOMMENTS;
	const F_ABPBIRTHDAY			= 'birthday';
	const F_ABPBIRTHDAYDAY		= 'birthday_date';
	const F_ABNTYPE				= 'type';
	const F_ABNNAME				= self::F_ABNAME;
	const F_ABNNUMBER			= 'number';
	const F_ABNCOMMENT			= 'comment';
	const F_ABNDESC				= 'desc';
	const F_ABNADDR1			= 'addr1';
	const F_ABNADDR2			= 'addr2';
	const F_ABNZIP				= 'zip';
	const F_ABNCITY				= 'city';
	const F_ABNCOUNTRY			= 'country';
	const F_ABNPHONES			= 'phones';
	const F_ABNFAXES			= 'faxes';
	const F_ABDISPLAY			= 'display';
	const F_ABCOMMENT			= 'comment';
	const F_ABCTXS				= 'contexts';

	/*
	 * Values for enum F_ABSCHEME.
	 */
	const V_ABSCHPERSON			= 'pers';
	const V_ABSCHCOMPANY		= 'comp';
	const V_ABSCHDEPARTMENT		= 'dep';
}

?>