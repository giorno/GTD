<?PHP

/**
 * @file en.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Slovak language localization file for application Address Book.
 */

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgAb['icon']							= 'Adresár';

/**
 * CDES strings.
 */
$__msgAb['cdes']['fold']					= 'Nálepky';
$__msgAb['cdes']['title']					= 'Nálepky pre označovanie kontaktov';

$__msgAb['f_all']							= 'Všetky kontakty';
$__msgAb['t_all']							= 'Ľudia a organizácie';

/**
 * Pretyped contact fields.
 */
$__msgAb['typed']['kind']					= 'Typ';
$__msgAb['typed']['value']					= 'Číslo, Id, atď.';
$__msgAb['typed']['comment']				= 'Poznámka';
$__msgAb['typed']['add_row']				= 'Pridať riadok';
$__msgAb['typed']['del_row']				= 'Odobrať';
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
$__msgAb['address']['add_address']			= 'Pridať adresu';
$__msgAb['address']['del_address']			= 'Odobrať túto adresu';
$__msgAb['address']['field']['address']		= $__msgAb['address']['address'];
$__msgAb['address']['field']['phones']		= 'Telefón(y)';
$__msgAb['address']['field']['faxes']		= 'Fax(y)';
$__msgAb['address']['field']['zip']			= 'PSČ';
$__msgAb['address']['field']['city']		= 'Mesto';
$__msgAb['address']['field']['country']		= 'Krajina';
$__msgAb['address']['field']['comment']		= $__msgAb['typed']['comment'];

/**
 * Person editor form.
 */
$__msgAb['perse']['bt_back']				= 'Späť';
$__msgAb['perse']['bt_save']				= 'Uložiť kontakt';
$__msgAb['perse']['title']					= 'Nová osoba';
$__msgAb['perse']['display']				= 'Zobrazovať ako';
$__msgAb['perse']['predef']					= 'použiť predvolený formát';
$__msgAb['perse']['labels']					= $__msgAb['cdes']['fold'];
$__msgAb['perse']['no_labels']				= 'Nálepky nie sú k dispozícii.';
$__msgAb['perse']['personal']				= 'Osobné informácie';
$__msgAb['perse']['first']					= 'Krstné meno';
$__msgAb['perse']['surname']				= 'Priezvisko';
$__msgAb['perse']['nick']					= 'Prezývka';
$__msgAb['perse']['titles']					= 'Titul(y)';
$__msgAb['perse']['second']					= 'Druhé meno';
$__msgAb['perse']['ssurname']				= 'Druhé priezvisko';
$__msgAb['perse']['anames']					= 'Ďalšie mená';
$__msgAb['perse']['asurnames']				= 'Ďalšie priezviská';
$__msgAb['perse']['birthday']				= 'Narodeniny';
$__msgAb['perse']['comments']				= 'Poznámka';
$__msgAb['perse']['phones']					= 'Telefóny, e-maily, atď.';
$__msgAb['perse']['js']['fmt'][0]			= 'Prezývka [Krstné meno Priezvisko]';
$__msgAb['perse']['js']['fmt'][10]			= 'Krstné meno Priezvisko';
$__msgAb['perse']['js']['fmt'][20]			= 'Priezvisko Krstné meno';
$__msgAb['perse']['js']['fmt'][30]			= 'Krstné meno Druhé meno Priezvisko';
$__msgAb['perse']['js']['fmt'][40]			= 'Priezvisko, Krstné meno Druhé meno';
$__msgAb['perse']['js']['fmt'][50]			= 'Krstné meno Priezvisko-Druhé priezvisko';
$__msgAb['perse']['js']['fmt'][60]			= 'Krstné meno Druhé meno Priezvisko-Druhé priezvisko';
$__msgAb['perse']['js']['fmt'][70]			= 'Priezvisko-Druhé priezvisko, Krstné meno Druhé meno';
$__msgAb['perse']['js']['types']			= $__msgAb['typed']['types'];
$__msgAb['perse']['js']['edit']				= 'Upraviť osobu';
$__msgAb['perse']['js']['create']			= $__msgAb['perse']['title'];
$__msgAb['perse']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['perse']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['perse']['js']['address']			= $__msgAb['address'];
$__msgAb['perse']['ind']['preparing']		= 'Pripravujem...';
$__msgAb['perse']['ind']['prepared']		= 'Pripravené';
$__msgAb['perse']['ind']['saving']			= 'Ukladám...';
$__msgAb['perse']['ind']['saved']			= 'Uložené';
$__msgAb['perse']['ind']['e_unknown']		= 'Chyba: neznáma chyba! Kontaktujte správcov.';
$__msgAb['perse']['ind']['e_bday']			= 'Chyba: nesprávna hodnota v poli Narodeniny! Opravte dátum alebo vypnite pole úplne.';

/**
 * Organization-class contact editor form.
 */
$__msgAb['orge']['bt_back']					= $__msgAb['perse']['bt_back'];
$__msgAb['orge']['bt_save']					= $__msgAb['perse']['bt_save'];
$__msgAb['orge']['title']					= 'Nová spoločnosť';
$__msgAb['orge']['disp_as']					= 'Zobrazovať ako';
$__msgAb['orge']['name']					= 'Úplný názov';
$__msgAb['orge']['ind']						= $__msgAb['perse']['ind'];
$__msgAb['orge']['js']['edit']				= 'Upraviť spoločnosť';
$__msgAb['orge']['js']['create']			= $__msgAb['orge']['title'];
$__msgAb['orge']['js']['add_row']			= $__msgAb['typed']['add_row'];
$__msgAb['orge']['js']['del_row']			= $__msgAb['typed']['del_row'];
$__msgAb['orge']['js']['address']			= $__msgAb['address'];
$__msgAb['orge']['js']['types']				= $__msgAb['typed']['types'];

/**
 * List of search results.
 */
$__msgAb['list']['noname_pers']				= 'nepomenovaná osoba!';
$__msgAb['list']['noname_org']				= 'nepomenovaná spoločnosť!';
$__msgAb['list']['name']					= 'Kontakt';
$__msgAb['list']['empty']					= 'Nemáte žiadne kontakty.';
$__msgAb['list']['no_match']				= 'Vyhľadávanie nevrátilo žiadne výsledky.';
$__msgAb['list']['again']					= 'Zmeniť frázu a zopakovať vyhľadávanie';
$__msgAb['list']['all']						= 'Zobraziť všetky kontakty';
$__msgAb['list']['add_pers']				= 'Pridať osobu';
$__msgAb['list']['add_org']					= 'organizáciu';

$__msgAb['mi_add_person']					= 'Pridať novú osobu';
$__msgAb['mi_add_company']					= 'Pridať novú spoločnosť';
/*$__msgAb['listAddNewCompanyTop']          = 'Nová spoločnosť';
$__msgAb['listAddNewDepartment']          = 'nové oddelenie';

$__msgAb['listQuestionRemove']            = "Skutočne si prajete odstrániť kontakt <b>%s</b>? Táto operácia je nevratná.";
$__msgAb['listCaptionRemove']             = "Varovanie!";

$__msgAb['bubbleYes']                     = 'Áno';
$__msgAb['bubbleNo']                      = 'Nie';
$__msgAb['bubbleOk']                      = 'OK';

$__msgAb['tagPerson']                     = 'Osoba';
$__msgAb['tagCompany']                    = 'Spoločnosť';

$__msgAb['noResultsNoContacts']           = "Nemáte žiadne kontakty. Skúste %s alebo %s.";*/

?>