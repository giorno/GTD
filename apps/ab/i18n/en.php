<?PHP

/**
 * @file en.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * English language localization file for application Address Book.
 */

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgAb['icon']							= 'Address Book';

/**
 * CDES strings.
 */
$__msgAb['cdes']['fold']					= 'Labels';
$__msgAb['cdes']['title']					= 'Labels to tag your contacts';

$__msgAb['f_all']							= 'All contacts';
$__msgAb['t_all']							= 'People and organizations';

/**
 * Pretyped contact fields.
 */
$__msgAb['typed']['kind']					= 'Type';
$__msgAb['typed']['value']					= 'Number, Id, etc.';
$__msgAb['typed']['comment']				= 'Comment';
$__msgAb['typed']['add_row']				= 'Add row';
$__msgAb['typed']['del_row']				= 'Erase';
$__msgAb['typed']['types']['phone']			= 'Phone';
$__msgAb['typed']['types']['cell']			= 'Cell';
$__msgAb['typed']['types']['fax']			= 'Fax';
$__msgAb['typed']['types']['email']			= 'E-mail';
$__msgAb['typed']['types']['www']			= 'www';
$__msgAb['typed']['types']['SIP']			= 'SIP';
$__msgAb['typed']['types']['JabberID']		= 'JabberID';
$__msgAb['typed']['types']['MSN']			= 'MSN';
$__msgAb['typed']['types']['ICQ']			= 'ICQ';
$__msgAb['typed']['types']['Yahoo']			= 'Yahoo';
$__msgAb['typed']['types']['AOL']			= 'AOL';

/**
 * Addresses.
 */
$__msgAb['address']['address']				= 'Address';
$__msgAb['address']['no']					= ' No. ';
$__msgAb['address']['add_address']			= 'Add address';
$__msgAb['address']['del_address']			= 'Erase this address';
$__msgAb['address']['field']['address']		= $__msgAb['address']['address'];
$__msgAb['address']['field']['phones']		= 'Phone(s)';
$__msgAb['address']['field']['faxes']		= 'Fax(es)';
$__msgAb['address']['field']['zip']			= 'Zip code';
$__msgAb['address']['field']['city']		= 'City';
$__msgAb['address']['field']['country']		= 'Country';
$__msgAb['address']['field']['comment']		= $__msgAb['typed']['comment'];

/**
 * Person editor form.
 */
$__msgAb['perse']['bt_back']				= 'Back';
$__msgAb['perse']['bt_save']				= 'Save contact';
$__msgAb['perse']['title']					= 'New person';
$__msgAb['perse']['display']				= 'Display name';
$__msgAb['perse']['predef']					= 'use predefined format';
$__msgAb['perse']['labels']					= $__msgAb['cdes']['fold'];
$__msgAb['perse']['no_labels']				= 'No labels available.';
$__msgAb['perse']['personal']				= 'Personal information';
$__msgAb['perse']['first']					= 'First name';
$__msgAb['perse']['surname']				= 'Surname';
$__msgAb['perse']['nick']					= 'Nickname';
$__msgAb['perse']['titles']					= 'Title(s)';
$__msgAb['perse']['second']					= 'Second name';
$__msgAb['perse']['ssurname']				= 'Second surname';
$__msgAb['perse']['anames']					= 'Another forename(s)';
$__msgAb['perse']['asurnames']				= 'Another surname(s)';
$__msgAb['perse']['birthday']				= 'Birthday';
$__msgAb['perse']['comments']				= 'Description';
$__msgAb['perse']['phones']					= 'Phones, e-mails, etc.';
$__msgAb['perse']['js']['fmt'][0]			= 'Nickname [FirstName Surname]';
$__msgAb['perse']['js']['fmt'][10]			= 'FirstName Surname';
$__msgAb['perse']['js']['fmt'][20]			= 'Surname FirstName';
$__msgAb['perse']['js']['fmt'][30]			= 'FirstName SecondName Surname';
$__msgAb['perse']['js']['fmt'][40]			= 'Surname, FirstName SecondName';
$__msgAb['perse']['js']['fmt'][50]			= 'FirstName Surname-SecondSurname';
$__msgAb['perse']['js']['fmt'][60]			= 'FirstName SecondName Surname-SecondSurname';
$__msgAb['perse']['js']['fmt'][70]			= 'Surname-SecondSurname, FirstName SecondName';
$__msgAb['perse']['js']['types']			= $__msgAb['typed']['types'];
$__msgAb['perse']['js']['edit']				= 'Edit person';
$__msgAb['perse']['js']['create']			= $__msgAb['perse']['title'];
$__msgAb['perse']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['perse']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['perse']['js']['address']			= $__msgAb['address'];
$__msgAb['perse']['ind']['preparing']		= 'Preparing...';
$__msgAb['perse']['ind']['prepared']		= 'Prepared';
$__msgAb['perse']['ind']['saving']			= 'Saving...';
$__msgAb['perse']['ind']['saved']			= 'Saved';
$__msgAb['perse']['ind']['executing']		= 'Executing...';
$__msgAb['perse']['ind']['executed']		= 'Execute';
$__msgAb['perse']['ind']['e_unknown']		= 'Error: unknown error! Contact administrators.';
$__msgAb['perse']['ind']['e_bday']			= 'Error: incorrect value for field Birthday! Please correct date or disable Birthday field.';

/**
 * Organization-class contact editor form.
 */
$__msgAb['orge']['bt_back']					= $__msgAb['perse']['bt_back'];
$__msgAb['orge']['bt_save']					= $__msgAb['perse']['bt_save'];
$__msgAb['orge']['title']					= 'New company';
$__msgAb['orge']['disp_as']					= 'Display as';
$__msgAb['orge']['name']					= 'Company full name';
$__msgAb['orge']['ind']						= $__msgAb['perse']['ind'];
$__msgAb['orge']['js']['edit']				= 'Edit company';
$__msgAb['orge']['js']['create']			= $__msgAb['orge']['title'];
$__msgAb['orge']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['orge']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['orge']['js']['address']			= $__msgAb['address'];
$__msgAb['orge']['js']['types']				= $__msgAb['typed']['types'];

/**
 * List of search results.
 */
$__msgAb['list']['noname_pers']				= 'person not named!';
$__msgAb['list']['noname_org']				= 'company not named!';
$__msgAb['list']['name']					= 'Contact';
$__msgAb['list']['empty']					= 'You do not have any contacts.';
$__msgAb['list']['no_match']				= 'Not match found for search request.';
$__msgAb['list']['again']					= 'Change keywords and search again';
$__msgAb['list']['all']						= 'Show all content';
$__msgAb['list']['add_pers']				= 'Add new person';
$__msgAb['list']['add_org']					= 'organization';
$__msgAb['list']['question']				= "Do you really want to remove contact <b>%s</b>? This operation cannot be undone.";
$__msgAb['list']['warning']					= "Warning!";
$__msgAb['list']['bt_yes']					= 'Yes';
$__msgAb['list']['bt_no']					= 'No';
$__msgAb['list']['alt_remove']				= 'Remove contact';

$__msgAb['mi_add_person']					= 'Add new person';
$__msgAb['mi_add_company']					= 'Add new company';

/*$__msgAb['listAddNewCompanyTop']          = 'New company';
$__msgAb['listAddNewDepartment']          = 'new department';
$__msgAb['bubbleOk']                      = 'OK';
$__msgAb['tagPerson']                     = 'Person';
$__msgAb['tagCompany']                    = 'Company';
$__msgAb['noResultsNoContacts']           = "You have no contacts. Try to %s or %s.";*/

?>