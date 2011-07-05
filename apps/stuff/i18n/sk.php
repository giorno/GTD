<?PHP

/**
 * @file sk.php
 * @author giorno
 *
 * Stuff tab internationalization file for Slovak language.
 *
 */

require_once CHASSIS_LIB . 'i18n/class.I18nCardinalSk.php';

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgStuff['tabName']                  = 'Veci';
$__msgStuff['tabNumbers']				= new I18nCardinalSk( 'Žiadne veci', 'Urobiť 1 vec', "Urobiť %d veci", "Urobiť %d vecí" );

$__msgStuff['boxInbox']                 = 'Schránka';
$__msgStuff['boxNextActions']           = 'Ďalšie akcie';
$__msgStuff['boxNa']					= $__msgStuff['boxNextActions'];
$__msgStuff['boxWaitingFor']            = 'Čakám na';
$__msgStuff['boxWf']					= $__msgStuff['boxWaitingFor'];
$__msgStuff['boxSchedule']              = 'Plán';
$__msgStuff['boxProjects']              = 'Projekty';
$__msgStuff['boxSomeday']               = 'Niekedy/Možno';
$__msgStuff['boxSd']					= $__msgStuff['boxSomeday'];
$__msgStuff['boxArchive']               = 'Archív';
$__msgStuff['boxAr']					= $__msgStuff['boxArchive'];
$__msgStuff['tabAllStuff']              = 'Všetky veci';

/**
 * CPE form strings.
 */
$__msgStuff['cpeMenuItem']				= 'Vložiť novú vec';
$__msgStuff['cpeTitle']					= 'Vložiť';
$__msgStuff['cpeTitleEdit']				= 'Upraviť';
$__msgStuff['cpeTitleProcess']			= 'Spracovať';
$__msgStuff['cpeBtBack']				= 'Späť';
$__msgStuff['cpeBtSave']				= 'Uložiť';
//$__msgStuff['cpeBtEdit']				= $__msgStuff['cpeTitleEdit'];
$__msgStuff['cpeBtProcess']				= $__msgStuff['cpeTitleProcess'];
$__msgStuff['cpeBtCloseOnSave']			= 'zatvoriť formulár';
$__msgStuff['cpeBtClose']				= 'Zatvoriť';
$__msgStuff['cpeBtCopy']				= 'Kopírovať';
$__msgStuff['cpeBtScroll']				= 'Na prvý záznam';
$__msgStuff['cpeStoreIn']				= 'Uložiť do';
$__msgStuff['cpeDetails']				= 'Detaily';
$__msgStuff['cpeCtxs']					= 'Nálepky';
$__msgStuff['cpePriority']				= 'Priorita';
$__msgStuff['cpePlace']					= 'Miesto';
$__msgStuff['cpeDate']					= 'Dátum';
$__msgStuff['cpeTime']					= 'Čas';
$__msgStuff['cpeToday']					= 'Dnes';
$__msgStuff['cpeTomorrow']				= 'Zajtra';
$__msgStuff['cpeCalendar']				= 'Kalendár';
$__msgStuff['cpeProject']				= 'Projekt';
$__msgStuff['cpeNoPrj']					= 'Nepatrí k žiadnemu projektu';
$__msgStuff['cpeAttToPrj']				= 'Pridať k projektu';
$__msgStuff['cpeChangePrj']				= 'Presunúť do iného projektu';
$__msgStuff['cpeDetachPrj']				= 'Odobrať z projektu';
$__msgStuff['cpeAttToPrjPick']			= 'Vybrať projekt';
$__msgStuff['cpeSubTasks']				= 'Podúlohy';
$__msgStuff['cpeHistory']				= 'História';
$__msgStuff['cpePrjPickCaption']		= 'Vyhľadajte alebo nalistujte vec, ktorá má byť projektom a kliknite na ňu';
$__msgStuff['cpe']['box']['Inbox']		= $__msgStuff['boxInbox'];			// Descriptions for box selector
$__msgStuff['cpe']['box']['Na']			= $__msgStuff['boxNextActions'];
$__msgStuff['cpe']['box']['Wf']			= $__msgStuff['boxWaitingFor']  ;
$__msgStuff['cpe']['box']['Sd']			= $__msgStuff['boxSomeday'];
$__msgStuff['cpe']['box']['Ar']			= $__msgStuff['boxArchive'] ;
$__msgStuff['cpe']['pt']['Inbox']		= 'Popis';							// Prompts for task field
$__msgStuff['cpe']['pt']['Wf']			= 'Koho alebo čo';
$__msgStuff['cpe']['pt']['Na']			= 'Akcia';
$__msgStuff['cpe']['pt']['Sd']			= 'Nápad';
$__msgStuff['cpe']['pt']['Ar']			= 'Poznámka';
$__msgStuff['cpe']['pty'][0]			= 'Žiadna';							// Task levels of priority
$__msgStuff['cpe']['pty'][1]			= 'Nízka';
$__msgStuff['cpe']['pty'][2]			= 'Normálna';
$__msgStuff['cpe']['pty'][3]			= 'Vysoká';
$__msgStuff['cpe']['pty'][4]			= 'Kritická';
$__msgStuff['cpe']['saving']			= 'Ukladám...';
$__msgStuff['cpe']['saved']				= 'Uložené';
$__msgStuff['cpe']['loading']			= 'Načítavam...';
$__msgStuff['cpe']['loaded']			= 'Načítané';
$__msgStuff['cpe']['preparing']			= 'Pripravujem...';
$__msgStuff['cpe']['prepared']			= 'Pripravené';
$__msgStuff['cpe']['e_unknown']			= 'Chyba: neznáma chyba! Kontaktujte správcov.';
$__msgStuff['cpe']['e_empty']			= 'Chyba: prázdny reťazec!';
$__msgStuff['cpe']['e_date']			= 'Chyba: nepovolený formát dátumu!';
$__msgStuff['cpe']['e_loop']			= 'Chyba: projekt v podúlohách!';

/**
 * Two-character abbreviations of days of week.
 */
$__msgStuff['dow']['mo']				= 'Po';
$__msgStuff['dow']['tu']				= 'Ut';
$__msgStuff['dow']['we']				= 'St';
$__msgStuff['dow']['th']				= 'Št';
$__msgStuff['dow']['fr']				= 'Pi';
$__msgStuff['dow']['sa']				= 'So';
$__msgStuff['dow']['su']				= 'Ne';

/**
 * CDES solution strings.
 */
$__msgStuff['cdesFold']					= 'Nálepky';
$__msgStuff['cdesTitle']				= 'Nálepky pre označovanie vecí';

$__msgStuff['capAllStuff']              = 'Rozšírené vyhľadávanie vo všetkých veciach';
$__msgStuff['capSchedule']				= 'Veci naplánované na určitý dátum';
$__msgStuff['capProjects']				= 'Veci vyžadujúce viac krokov';
$__msgStuff['capNextActions']			= 'Úlohy, ktoré je potrebné vykonať';
$__msgStuff['capInbox']					= 'Zozbierané a ešte nespracované veci';
$__msgStuff['capWaitingFor']			= 'Veci delegované na iných alebo čakajúce na iné akcie';
$__msgStuff['capSomeday']				= 'Nápady, budúce projekty, referencie';
$__msgStuff['capArchive']				= 'Dokončené veci čakajúce na odstránenie';

$__msgStuff['miScroll2Top']				= 'Na začiatok stránky';

/**
 * Archive list and list form strings.
 */
$__msgStuff['arBpRemove']				= 'Odstrániť označené';
$__msgStuff['arBpQuestion']				= 'Skutočne si prajete odstrániť všetky označené záznamy? Táto operácia je nevratná.';
$__msgStuff['arWarning']				= 'Varovanie';
$__msgStuff['arQuestion']				= 'Skutočne si prajete odstrániť záznam <b>%s</b>? Táto operácia je nevratná.';
$__msgStuff['arBtYes']					= 'Áno';
$__msgStuff['arBtNo']					= 'Nie';
$__msgStuff['arAltRemove']				= 'Odstrániť';
$__msgStuff['arMinutes']				= '2 minutová práca';
$__msgStuff['arFinished']				= 'Dokončené';
$__msgStuff['arGarbage']				= 'Zbytočné';
$__msgStuff['arAltMinutes']				= 'Archivovať ako ' . $__msgStuff['arMinutes'];
$__msgStuff['arAltGarbage']				= 'Archivovať ako ' . $__msgStuff['arGarbage'];
$__msgStuff['arAltFinished']			= 'Archivovať ako ' . $__msgStuff['arFinished'];

/**
 * SEM related strings.
 */
$__msgStuff['sem']['title']				= 'Nastavenia Vecí';
$__msgStuff['sem']['aLg']				= 'Životné ciele';
$__msgStuff['sem']['dLg']				= 'Veci označené Nálepkou, ktorú vyberiete, sa vám budú zobrazovať ako oblak v spodnej časti obrazovky.';
$__msgStuff['sem']['oNoLg']				= 'Vypnúť životné ciele';
$__msgStuff['sem']['oLg'][0]			= 'Len zo schránky Niekedy/Možno';
$__msgStuff['sem']['oLg'][1]			= 'Zo všetkých schránok okrem Archívu';
$__msgStuff['sem']['oLg'][2]			= 'Zo všetkých schránok';
$__msgStuff['sem']['aAlg']				= 'Farba počtu Vecí';
$__msgStuff['sem']['dAlg']				= 'Určenie farebnej schémy počtu vecí v schránkach (farebný obdĺžnik s číslom na záložke schránky).';
$__msgStuff['sem']['oAlg']['hofstadter']= 'Hofstadterov algoritmus';
$__msgStuff['sem']['oAlg']['simpleMath']= 'Jednoduchý priemer priorít';
$__msgStuff['sem']['oAlg']['static']	= 'Vypnúť farby';
$__msgStuff['sem']['aPresets']			= 'Predvoľby času';
$__msgStuff['sem']['dPresets']			= 'Počet a spôsob výberu najpoužívanejších časov vo formulári pre zbieranie a spracovanie vecí.';
$__msgStuff['sem']['oPresetsNo'][0]		= 'Vypnúť predvoľby času';
$__msgStuff['sem']['oPresetsNo'][3]		= '3 predvoľby';
$__msgStuff['sem']['oPresetsNo'][4]		= '4 predvoľby';
$__msgStuff['sem']['oPresetsNo'][5]		= '5 predvolieb';
$__msgStuff['sem']['oPresetsNo'][6]		= '6 predvolieb';
$__msgStuff['sem']['oPresetsNo'][7]		= '7 predvolieb';
$__msgStuff['sem']['oPresetsBy'][-1]	= 'Systémové';
$__msgStuff['sem']['oPresetsBy'][7]		= 'Za posledný týždeň';
$__msgStuff['sem']['oPresetsBy'][30]	= 'Za posledných 30 dní';
$__msgStuff['sem']['oPresetsBy'][60]	= 'Za posledných 60 dní';
$__msgStuff['sem']['oPresetsBy'][90]	= 'Za posledných 90 dní';
$__msgStuff['sem']['oPresetsBy'][0]		= 'Od prvého prihlásenia';

$__msgStuff['editorHistoryBoxName']     = 'Uložené v ';
$__msgStuff['editorHistoryBoxInbox']    = 'Schránke';
$__msgStuff['editorHistoryBoxNa']       = 'Ďalších akciách';
$__msgStuff['editorHistoryBoxWf']       = $__msgStuff['boxWaitingFor'];
$__msgStuff['editorHistoryBoxSd']       = $__msgStuff['boxSomeday'];
$__msgStuff['editorHistoryBoxAr']       = 'Archíve';

/**
 * Empty list content messages.
 */
$__msgStuff['empty']['box']				= 'Táto schránka je prázdna.';
$__msgStuff['empty']['Schedule']		= 'Nemáte žiadne naplánované aktivity.';
$__msgStuff['empty']['Projects']		= 'Nemáte žiadne projekty.';
$__msgStuff['empty']['All']				= 'Nemáte žiadne záznamy.';
$__msgStuff['nomatch']['box']			= 'Vyhľadávanie nevrátilo žiadne výsledky.';
$__msgStuff['nomatch']['Schedule']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['Projects']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['All']			= $__msgStuff['nomatch']['box'];
$__msgStuff['eo']['collect']			= 'Vložiť novú vec';
$__msgStuff['eo']['again']				= 'Zmeniť frázu a zopakovať vyhľadávanie';
$__msgStuff['eo']['all']				= 'Zobrazit celý obsah';

/**
 * Lifegoals widget.
 */
$__msgStuff['goals']['caption']			= 'Životné ciele';
$__msgStuff['goals']['i']['loading']	= 'Načítavam...';
$__msgStuff['goals']['i']['loaded']		= 'Načítané';
$__msgStuff['goals']['i']['e_unknown']	= $__msgStuff['cpe']['e_unknown'];
$__msgStuff['goals']['empty']			= 'Nemáte žiadne ciele. Vytvorte si nejaký alebo zmeňte nastavenia.';

$__msgStuff['editorStatusPreparing']    = 'Pripravujem formulár...';
$__msgStuff['editorStatusLoading']      = 'Načítavam záznam...';
$__msgStuff['editorStatusLoaded']       = 'Načítané';
$__msgStuff['editorStatusGoOn']         = 'Pripravené';


$__msgStuff['prjParent']				= 'Patrí do';

$__msgStuff['inboxListTask']            = 'Úloha';
$__msgStuff['inboxDateYesterday']       = 'Včera';
$__msgStuff['inboxDateTomorrow']        = 'Zajtra';
$__msgStuff['inboxDateToday']           = 'Dnes';
$__msgStuff['inboxListRecorded']        = 'Zaznamenané';
$__msgStuff['inboxListMoved']           = 'Presunuté';
$__msgStuff['inboxListPriority']        = 'Priorita';
$__msgStuff['naTask']                   = 'Prvý záznam';
$__msgStuff['naListThing']              = 'Vec';
$__msgStuff['naListAction']             = 'Ďalšia akcia';
$__msgStuff['naListWf']                 = 'Čakám na';
$__msgStuff['naListSd']                 = 'Nápad';
$__msgStuff['naListComment']            = 'Poznámka';
$__msgStuff['schTimeFrame']             = 'Termín';
$__msgStuff['schInBox']                 = 'V schránke';
$__msgStuff['schAppointment']           = 'Vybaviť';

$__msgStuff['advSearchKeywords']        = 'Hľadať kľúčové slová';
$__msgStuff['advSearchField']           = 'V poli';
$__msgStuff['advSearchBox']             = 'V schránke';
$__msgStuff['advSearchAllBoxes']        = 'Vo všetkých schránkach';
$__msgStuff['advSearchLabeled']         = 'Označené nálepkou';
$__msgStuff['advSrchAllCtxs']     = 'Nálepka nerozhoduje';
$__msgStuff['advSearchDisplay']         = 'Zobrazenie';
$__msgStuff['advSrchDispList']			= 'Zoznam';
$__msgStuff['advSrchDispTree']			= 'Zoskupiť podľa nálepiek';
$__msgStuff['advSrchAllFields']			= 'Vo všetkých poliach';
$__msgStuff['advSearchShowBadges']      = 'zobrazovať nálepky';
$__msgStuff['advSearchTreeCount']		= new I18nCardinalSk( 'Žiadne veci označené nálepkou', '1 vec označená nálepkou', "%d veci označené nálepkou", "%d vecí označených nálepkou" );
$__msgStuff['advSearchTreeCountWout']	= new I18nCardinalSk( 'Žiadne veci bez nálepky', '1 vec bez nálepky', "%d veci bez nálepky", "%d vecí bez nálepky" );

$__msgStuff['ico2m']                    = '2 min';

$__msgStuff['noResultsEmptyBox']        = "Schránka <b>%s</b> je prázdna.";
$__msgStuff['noResultsEmptySchedule']   = "Nie sú naplánované žiadne veci.";
$__msgStuff['noResultsEmptyProjects']	= "Nemáte žiadne projekty.";


//$__msgStuff['altCtxRem']                = 'Odstrániť';

/*
 * Stub containing formatting strings for strformat.
 */
$__msgStuff['dtFormat']['RECDATE']		= '%e. %b';
$__msgStuff['dtFormat']['RECDATEwY']	= '%d.%m.%y';
$__msgStuff['dtFormat']['RECTIME']		= '%H:%M';
$__msgStuff['dtFormat']['PRESET']		= $__msgStuff['dtFormat']['RECTIME'];
$__msgStuff['dtFormat']['RECDATETIME']	= '%e. %B %Y, %H:%M';
$__msgStuff['dtFormat']['RECDATESHORT']	= '%e. %b %Y';
$__msgStuff['dtFormat']['HISTTIME']		= $__msgStuff['dtFormat']['RECTIME'];
$__msgStuff['dtFormat']['HISTDATE']		= '%e. %B %Y';

/*$__STUFFINBOXRECORDEDDATE				= '%e. %b';
$__STUFFINBOXRECORDEDDATEwY				= '%d.%m.%y';
$__STUFFINBOXRECORDEDTIME				= '%H:%M';
$__STUFFINBOXRECORDEDDATETIME			= '%e. %B %Y, %H:%M';
$__STUFFINBOXRECORDEDDATESHORT			= '%e. %b %Y';
$__STUFFINBOXHISTORYTIME				= $__STUFFINBOXRECORDEDTIME;
$__STUFFINBOXHISTORYDATE				= "%e. %B %Y";*/

/*
 * Append common strings.
 */
/*include N7_SOLUTION_ROOT . 'i18n/common/sk.php';
$__msgStuff = array_merge( $__msgStuff, $__msgCommon );*/

?>