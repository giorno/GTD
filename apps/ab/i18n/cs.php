<?PHP

/**
 * @file cs.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Czech language localization file for application Address Book.
 */

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgAb['icon']							= 'Adresář';

/**
 * CDES strings.
 */
$__msgAb['cdes']['fold']					= 'Nálepky';
$__msgAb['cdes']['title']					= 'Nálepky pro označování kontaktů';

$__msgAb['f_all']							= 'Všechny kontakty';
$__msgAb['t_all']							= 'Lidé a organizace';


/**
 * Pretyped contact fields.
 */
$__msgAb['typed']['kind']					= 'Typ';
$__msgAb['typed']['value']					= 'Číslo, Id, atd.';
$__msgAb['typed']['comment']				= 'Poznámka';
$__msgAb['typed']['add_row']				= 'Přidat řádek';
$__msgAb['typed']['del_row']				= 'Odebrat';
$__msgAb['typed']['types']['phone']			= 'Telefón';
$__msgAb['typed']['types']['cell']			= 'Mobil';
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
$__msgAb['address']['address']				= 'Adresa';
$__msgAb['address']['no']					= ' č. ';
$__msgAb['address']['add_address']			= 'Přidat adresu';
$__msgAb['address']['del_address']			= 'Odebrat tuto adresu';
$__msgAb['address']['field']['address']		= $__msgAb['address']['address'];
$__msgAb['address']['field']['phones']		= 'Telefón(y)';
$__msgAb['address']['field']['faxes']		= 'Fax(y)';
$__msgAb['address']['field']['zip']			= 'PSČ';
$__msgAb['address']['field']['city']		= 'Město';
$__msgAb['address']['field']['country']		= 'Krajina';
$__msgAb['address']['field']['comment']		= $__msgAb['typed']['comment'];

/**
 * Person editor form.
 */
$__msgAb['perse']['bt_back']				= 'Zpět';
$__msgAb['perse']['bt_save']				= 'Uložit kontakt';
$__msgAb['perse']['title']					= 'Nová osoba';
$__msgAb['perse']['display']				= 'Zobrazovať jako';
$__msgAb['perse']['predef']					= 'použít předvolený formát';
$__msgAb['perse']['labels']					= $__msgAb['cdes']['fold'];
$__msgAb['perse']['no_labels']				= 'Nálepky nejsou k dispozici.';
$__msgAb['perse']['personal']				= 'Osobní informace';
$__msgAb['perse']['first']					= 'Křestní jméno';
$__msgAb['perse']['surname']				= 'Přijmení';
$__msgAb['perse']['nick']					= 'Přezdívka';
$__msgAb['perse']['titles']					= 'Titul(y)';
$__msgAb['perse']['second']					= 'Druhé jméno';
$__msgAb['perse']['ssurname']				= 'Druhé přijmení';
$__msgAb['perse']['anames']					= 'Ďalší jména';
$__msgAb['perse']['asurnames']				= 'Ďalší přijmení';
$__msgAb['perse']['birthday']				= 'Narozeniny';
$__msgAb['perse']['comments']				= 'Poznámka';
$__msgAb['perse']['phones']					= 'Telefóny, e-maily, atd.';
$__msgAb['perse']['js']['fmt'][0]			= 'Přezdívka [Křestní jméno Přijmení]';
$__msgAb['perse']['js']['fmt'][10]			= 'Křestní jméno Přijmení';
$__msgAb['perse']['js']['fmt'][20]			= 'Přijmení Křestní jméno';
$__msgAb['perse']['js']['fmt'][30]			= 'Křestní jméno Druhé jméno Přijmení';
$__msgAb['perse']['js']['fmt'][40]			= 'Přijmení, Křestní jméno Druhé jméno';
$__msgAb['perse']['js']['fmt'][50]			= 'Křestní jméno Přijmení-Druhé přijmení';
$__msgAb['perse']['js']['fmt'][60]			= 'Křestní jméno Druhé meno Přijmení-Druhé přijmení';
$__msgAb['perse']['js']['fmt'][70]			= 'Přijmení-Druhé přijmení, Krstné jméno Druhé jméno';
$__msgAb['perse']['js']['types']			= $__msgAb['typed']['types'];
$__msgAb['perse']['js']['edit']				= 'Upravit osobu';
$__msgAb['perse']['js']['create']			= $__msgAb['perse']['title'];
$__msgAb['perse']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['perse']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['perse']['js']['address']			= $__msgAb['address'];
$__msgAb['perse']['ind']['preparing']		= 'Připravuji...';
$__msgAb['perse']['ind']['prepared']		= 'Připraveno';
$__msgAb['perse']['ind']['saving']			= 'Ukládám...';
$__msgAb['perse']['ind']['saved']			= 'Uloženo';
$__msgAb['perse']['ind']['e_unknown']		= 'Chyba: neznáma chyba! Kontaktujte správce.';
$__msgAb['perse']['ind']['e_bday']			= 'Chyba: nesprávna hodnota v poli Narozeniny! Opravte datum nebo vypněte pole zcela.';

/*
$__msgAb['editfrmFieldComments']          = 'Poznámky';
$__msgAb['editfrmSepAddress']             = 'Adresa';
$__msgAb['editfrmSaveAsNew']              = 'Uložit jako nový';
$__msgAb['editfrmReset']                  = 'Reset';
$__msgAb['editfrmCaptionError']           = "Chyba!";

$__msgAb['compfrmCapAdd']                 = 'Nová společnost';
$__msgAb['compfrmCapEdit']                = 'Upravit společnost';
$__msgAb['compfrmFieldName']              = 'Úplný název';
$__msgAb['compfrmFieldDisplay']           = 'Zobrazovat ako';*/

/*$__msgAb['listCap']                       = 'Kontakty';*/

$__msgAb['mi_add_person']					= 'Přidat novou osobu';
$__msgAb['mi_add_company']					= 'Přidat novou spoleočnost';

/*$__msgAb['listAddNewCompanyTop']          = 'Nová společnost';
$__msgAb['listAddNewDepartment']          = 'nové oddělení';
$__msgAb['listEmptyPerson']               = 'nepojmenovaná osoba!';
$__msgAb['listEmptyCompany']              = 'nepojmenovaná společnost!';
$__msgAb['listFieldName']                 = 'Jméno';
$__msgAb['listi18nOf']                    = 'z';
$__msgAb['listi18nTo']                    = '-';
$__msgAb['listi18nItems']                 = 'Kontakty';
$__msgAb['listQuestionRemove']            = "Skutečně si přejte odstránit kontakt <b>%s</b>? Tato operace je nevratná.";
$__msgAb['listCaptionRemove']             = "Varovanie!";

$__msgAb['bubbleYes']                     = 'Ano';
$__msgAb['bubbleNo']                      = 'Ne';
$__msgAb['bubbleOk']                      = 'OK';

$__msgAb['tagPerson']                     = 'Osoba';
$__msgAb['tagCompany']                    = 'Společnost';

$__msgAb['noResultsNoContacts']           = "Nemáte žádné kontakty. Zkuste %s nebo %s.";*/



?>