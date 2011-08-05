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
$__msgAb['perse']['ind']['executing']		= 'Vykonávám...';
$__msgAb['perse']['ind']['executed']		= 'Vykonáno';
$__msgAb['perse']['ind']['e_unknown']		= 'Chyba: neznáma chyba! Kontaktujte správce.';
$__msgAb['perse']['ind']['e_bday']			= 'Chyba: nesprávna hodnota v poli Narozeniny! Opravte datum nebo vypněte pole zcela.';

/**
 * Organization-class contact editor form.
 */
$__msgAb['orge']['bt_back']					= $__msgAb['perse']['bt_back'];
$__msgAb['orge']['bt_save']					= $__msgAb['perse']['bt_save'];
$__msgAb['orge']['title']					= 'Nová společnost';
$__msgAb['orge']['disp_as']					= 'Zobrazovat jako';
$__msgAb['orge']['name']					= 'Úplný název';
$__msgAb['orge']['ind']						= $__msgAb['perse']['ind'];
$__msgAb['orge']['js']['edit']				= 'Upravit společnost';
$__msgAb['orge']['js']['create']			= $__msgAb['orge']['title'];
$__msgAb['orge']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['orge']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['orge']['js']['address']			= $__msgAb['address'];
$__msgAb['orge']['js']['types']				= $__msgAb['typed']['types'];

/**
 * List of search results.
 */
$__msgAb['list']['noname_pers']				= 'nepojmenovaná osoba!';
$__msgAb['list']['noname_org']				= 'nepojmenovaná společnost!';
$__msgAb['list']['name']					= 'Kontakt';
$__msgAb['list']['empty']					= 'Nemáte žádné kontakty.';
$__msgAb['list']['no_match']				= 'Vyhledávání nevrátilo žádné výsledky.';
$__msgAb['list']['again']					= 'Změnit frázi nebo zopakovat vyhledávaní';
$__msgAb['list']['all']						= 'Zobrazit všechny kontakty';
$__msgAb['list']['add_pers']				= 'Přidat osobu';
$__msgAb['list']['add_org']					= 'organizaci';
$__msgAb['list']['question']				= "Skutečně si přejte odstránit kontakt <b>%s</b>? Tato operace je nevratná.";
$__msgAb['list']['warning']					= "Varování!";
$__msgAb['list']['bt_yes']					= 'Ano';
$__msgAb['list']['bt_no']					= 'Ne';
$__msgAb['list']['alt_remove']				= 'Odstranit kontakt';

$__msgAb['mi_add_person']					= 'Přidat novou osobu';
$__msgAb['mi_add_company']					= 'Přidat novou spoleočnost';

/**
 * Default contexts created on first login.
 */
$__msgAb['1st_login'][0]					= array( 'Rodina',		'des',	'' );
$__msgAb['1st_login'][1]					= array( 'Přátelé',		'blu',	'' );
$__msgAb['1st_login'][2]					= array( 'Kolegové',	'roq',	'' );
$__msgAb['1st_login'][3]					= array( 'Spolužáci',	'flw',	'' );

/*$__msgAb['listAddNewCompanyTop']          = 'Nová společnost';
$__msgAb['listAddNewDepartment']          = 'nové oddělení';
$__msgAb['bubbleOk']                      = 'OK';
$__msgAb['tagPerson']                     = 'Osoba';
$__msgAb['tagCompany']                    = 'Společnost';
$__msgAb['noResultsNoContacts']           = "Nemáte žádné kontakty. Zkuste %s nebo %s.";*/

?>