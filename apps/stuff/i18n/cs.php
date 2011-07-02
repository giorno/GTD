<?PHP
/* 
 * @file cs.php
 *
 * Stuff tab internationalization file for Czech language.
 *
 * @author giorno
 */

require_once CHASSIS_LIB . 'i18n/class.I18nCardinalSk.php';

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgStuff['tabName']                  = 'Věci';
$__msgStuff['tabNumbers']				= new I18nCardinalSk( 'Žádné věci', 'Udělat 1 věc', "Udělat %d věci", "Udělat %d věcí" );

$__msgStuff['boxInbox']                 = 'Schránka';
$__msgStuff['boxNextActions']           = 'Další akce';
$__msgStuff['boxNa']					= $__msgStuff['boxNextActions'];
$__msgStuff['boxWaitingFor']            = 'Čekám na';
$__msgStuff['boxWf']					= $__msgStuff['boxWaitingFor'];
$__msgStuff['boxSchedule']              = 'Plán';
$__msgStuff['boxProjects']              = 'Projekty';
$__msgStuff['boxSomeday']               = 'Někdy/Možná';
$__msgStuff['boxSd']					= $__msgStuff['boxSomeday'];
$__msgStuff['boxArchive']               = 'Archiv';
$__msgStuff['boxAr']					= $__msgStuff['boxArchive'];
$__msgStuff['tabAllStuff']              = 'Všechny věci';

/**
 * CPE form strings.
 */
$__msgStuff['cpeMenuItem']				= 'Vložit novou věc';
$__msgStuff['cpeTitle']					= 'Vložit';
$__msgStuff['cpeTitleEdit']				= 'Upravit';
$__msgStuff['cpeTitleProcess']			= 'Zpracovat';
$__msgStuff['cpeBtBack']				= 'Zpět';
$__msgStuff['cpeBtSave']				= 'Uložit';
//$__msgStuff['cpeBtEdit']				= $__msgStuff['cpeTitleEdit'];
$__msgStuff['cpeBtProcess']				= $__msgStuff['cpeTitleProcess'];
$__msgStuff['cpeBtCloseOnSave']			= 'zatvořit formulář';
$__msgStuff['cpeBtClose']				= 'Zatvořit';
$__msgStuff['cpeBtCopy']				= 'Kopírovat';
$__msgStuff['cpeBtScroll']				= 'Na první záznam';
$__msgStuff['cpeStoreIn']				= 'Uložit do';
$__msgStuff['cpeDetails']				= 'Detaily';
$__msgStuff['cpeCtxs']					= 'Nálepky';
$__msgStuff['cpePriority']				= 'Priorita';
$__msgStuff['cpePlace']					= 'Místo';
$__msgStuff['cpeDate']					= 'Datum';
$__msgStuff['cpeTime']					= 'Čas';
$__msgStuff['cpeToday']					= 'Dnes';
$__msgStuff['cpeTomorrow']				= 'Zítra';
$__msgStuff['cpeCalendar']				= 'Kalendář';
$__msgStuff['cpeProject']				= 'Projekt';
$__msgStuff['cpeNoPrj']					= 'Nepatří do žádného projektu';
$__msgStuff['cpeAttToPrj']				= 'Přidat do projektu';
$__msgStuff['cpeChangePrj']				= 'Přesunout do jiného projektu';
$__msgStuff['cpeDetachPrj']				= 'Odebrat z projektu';
$__msgStuff['cpeAttToPrjPick']			= 'Zvolit projekt';
$__msgStuff['cpeSubTasks']				= 'Podúkoly';
$__msgStuff['cpeHistory']				= 'Historie';
$__msgStuff['cpePrjPickCaption']		= 'Vyhledejte nebo nalistujte věc, která má být projektem a klikněte na ni';
$__msgStuff['cpe']['box']['Inbox']		= $__msgStuff['boxInbox'];
$__msgStuff['cpe']['box']['Na']			= $__msgStuff['boxNextActions'];
$__msgStuff['cpe']['box']['Wf']			= $__msgStuff['boxWaitingFor']  ;
$__msgStuff['cpe']['box']['Sd']			= $__msgStuff['boxSomeday'];
$__msgStuff['cpe']['box']['Ar']			= $__msgStuff['boxArchive'] ;
$__msgStuff['cpe']['pt']['Inbox']		= 'Popis';							// Prompts for task field
$__msgStuff['cpe']['pt']['Wf']			= 'Koho nebo co';
$__msgStuff['cpe']['pt']['Na']			= 'Akce';
$__msgStuff['cpe']['pt']['Sd']			= 'Nápad';
$__msgStuff['cpe']['pt']['Ar']			= 'Poznámka';
$__msgStuff['cpe']['pty'][0]			= 'Žádna';							// Task levels of priority
$__msgStuff['cpe']['pty'][1]			= 'Nízka';
$__msgStuff['cpe']['pty'][2]			= 'Normální';
$__msgStuff['cpe']['pty'][3]			= 'Vysoká';
$__msgStuff['cpe']['pty'][4]			= 'Kritická';
$__msgStuff['cpe']['saving']			= 'Ukládám...';
$__msgStuff['cpe']['saved']				= 'Uloženo';
$__msgStuff['cpe']['loading']			= 'Načítám...';
$__msgStuff['cpe']['loaded']			= 'Načítano';
$__msgStuff['cpe']['preparing']			= 'Připravuji...';
$__msgStuff['cpe']['prepared']			= 'Připraveno';
$__msgStuff['cpe']['e_unknown']			= 'Chyba: neznáma chyba! Kontaktujte správce.';
$__msgStuff['cpe']['e_empty']			= 'Chyba: Prázdný řetězec!';
$__msgStuff['cpe']['e_date']			= 'Chyba: nepovolený formát datumu!';
$__msgStuff['cpe']['e_loop']			= 'Chyba: projekt v podúlohách!';

/**
 * Two-character abbreviations of days of week.
 */
$__msgStuff['dow']['mo']				= 'Po';
$__msgStuff['dow']['tu']				= 'Út';
$__msgStuff['dow']['we']				= 'St';
$__msgStuff['dow']['th']				= 'Čt';
$__msgStuff['dow']['fr']				= 'Pá';
$__msgStuff['dow']['sa']				= 'So';
$__msgStuff['dow']['su']				= 'Ne';

/**
 * CDES solution strings.
 */
$__msgStuff['cdesFold']					= 'Nálepky';
$__msgStuff['cdesTitle']				= 'Nálepky pre označování věcí';

$__msgStuff['capAllStuff']              = 'Rozšířené vyhledávání ve všech věcech';
$__msgStuff['capSchedule']				= 'Věci naplánovány na určité datum';
$__msgStuff['capProjects']				= 'Věci vyžadující více kroků';
$__msgStuff['capNextActions']			= 'Úlohy, ktoré je potřeba udělat';
$__msgStuff['capInbox']					= 'Sesbírané a ještě nezpracované věci';
$__msgStuff['capWaitingFor']			= 'Věci delegované na jiné nebo čekající na jiné akce';
$__msgStuff['capSomeday']				= 'Nápady, budoucí projekty, reference';
$__msgStuff['capArchive']				= 'Dokončené věci čekající na odstranění';

$__msgStuff['miScroll2Top']				= 'Na začátek stránky';

/**
 * Archive list form strings.
 */
$__msgStuff['arBpRemove']				= 'Odstranit označené';
$__msgStuff['arBpQuestion']				= 'Skutečně si přejete odstranit všechny označené záznamy? Tato operace je nevratná.';
$__msgStuff['arWarning']				= 'Varování';
$__msgStuff['arQuestion']				= 'Skutečně si přejete odstranit záznam <b>%s</b>? Tato operace je nevratná.';
$__msgStuff['arBtYes']					= 'Ano';
$__msgStuff['arBtNo']					= 'Ne';
$__msgStuff['arAltRemove']				= 'Odstranit';
$__msgStuff['arMinutes']				= '2 minutová práce';
$__msgStuff['arFinished']				= 'Dokončeno';
$__msgStuff['arGarbage']				= 'Zbytečné';
$__msgStuff['arAltMinutes']				= 'Archivovat jako ' . $__msgStuff['arMinutes'];
$__msgStuff['arAltGarbage']				= 'Archivovat jako ' . $__msgStuff['arGarbage'];
$__msgStuff['arAltFinished']			= 'Archivovat jako ' . $__msgStuff['arFinished'];

/**
 * SEM related strings.
 */
$__msgStuff['sem']['title']				= 'Nastavení Věcí';
$__msgStuff['sem']['aLg']				= 'Životní cíle';
$__msgStuff['sem']['dLg']				= 'Věci označené Nálepkou, kterou zvolíte, se vám budou zobrazovat jako oblak v spodní části obrazovky.';
$__msgStuff['sem']['oNoLg']				= 'Vypnout životní cíle';
$__msgStuff['sem']['oLg'][0]			= 'Jen ze schránky Někdy/Možná';
$__msgStuff['sem']['oLg'][1]			= 'Ze všech schránek kromě Archivu';
$__msgStuff['sem']['oLg'][2]			= 'Ze všech schránek';
$__msgStuff['sem']['aAlg']				= 'Barva počtu Věcí';
$__msgStuff['sem']['dAlg']				= 'Určení barevné schémy počtu věcí ve schránkách (barevný obdélník s číslem na záložce schránky).';
$__msgStuff['sem']['oAlg']['hofstadter']= 'Hofstadterův alogoritmus';
$__msgStuff['sem']['oAlg']['simpleMath']= 'Jednoduchý průměr priorit';
$__msgStuff['sem']['oAlg']['static']	= 'Vypnout barvy';
$__msgStuff['sem']['aPresets']			= 'Předvolby času';
$__msgStuff['sem']['dPresets']			= 'Počet a způsob výběru nejpoužívanějších časů ve formuláři pro zběr a zpracování věcí.';
$__msgStuff['sem']['oPresetsNo'][0]		= 'Vypnout předvolby času';
$__msgStuff['sem']['oPresetsNo'][3]		= '3 předvolby';
$__msgStuff['sem']['oPresetsNo'][4]		= '4 předvolby';
$__msgStuff['sem']['oPresetsNo'][5]		= '5 předvoleb';
$__msgStuff['sem']['oPresetsNo'][6]		= '6 předvoleb';
$__msgStuff['sem']['oPresetsNo'][7]		= '7 předvoleb';
$__msgStuff['sem']['oPresetsBy'][-1]	= 'Systémové';
$__msgStuff['sem']['oPresetsBy'][7]		= 'Za poslední týden';
$__msgStuff['sem']['oPresetsBy'][30]	= 'Za posledních 30 dní';
$__msgStuff['sem']['oPresetsBy'][60]	= 'Za posledních 30 dní';
$__msgStuff['sem']['oPresetsBy'][90]	= 'Za posledních 90 dní';
$__msgStuff['sem']['oPresetsBy'][0]		= 'Od prvního přihlášení';


$__msgStuff['editorHistoryBoxName']     = 'Uloženo v ';
$__msgStuff['editorHistoryBoxInbox']    = 'Schránce';
$__msgStuff['editorHistoryBoxNa']       = 'Ďalších akcích';
$__msgStuff['editorHistoryBoxWf']       = $__msgStuff['boxWaitingFor'];
$__msgStuff['editorHistoryBoxSd']       = $__msgStuff['boxSomeday'];
$__msgStuff['editorHistoryBoxAr']       = 'Archivu';

/**
 * Empty list content messages.
 */
$__msgStuff['empty']['box']				= 'Tato schránka je prázdná.';
$__msgStuff['empty']['Schedule']		= 'Nemáte žádné naplánované aktivity.';
$__msgStuff['empty']['Projects']		= 'nemáte žádné projekty.';
$__msgStuff['empty']['All']				= 'Nemáte žádné záznamy.';
$__msgStuff['nomatch']['box']			= 'Vyhledávání nevrátilo žádné výsledky.';
$__msgStuff['nomatch']['Schedule']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['Projects']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['All']			= $__msgStuff['nomatch']['box'];
$__msgStuff['eo']['collect']			= 'Vložit novou věc';
$__msgStuff['eo']['again']				= 'Změnit frázi a hledat znovu';
$__msgStuff['eo']['all']				= 'Zobrazit celý obsah';

//$__msgStuff['editorUseOriginal']        = 'Poslední';
$__msgStuff['editorStatusPreparing']    = 'Připravuji formulář...';
$__msgStuff['editorStatusLoading']      = 'Načítám záznam...';
$__msgStuff['editorStatusLoaded']       = 'Načteno';
$__msgStuff['editorStatusGoOn']         = 'Připraveno';




$__msgStuff['prjParent']				= 'Patří do';

//$__msgStuff['project']					= 'Projekt';

$__msgStuff['inboxListTask']            = 'Úloha';
$__msgStuff['inboxDateYesterday']       = 'Včera';
$__msgStuff['inboxDateTomorrow']        = 'Zítra';
$__msgStuff['inboxDateToday']           = 'Dnes';
$__msgStuff['inboxListRecorded']        = 'Zaznamenáno';
$__msgStuff['inboxListMoved']           = 'Přesunuto';
$__msgStuff['inboxListPriority']        = 'Priorita';
$__msgStuff['naTask']                   = 'První záznam';
$__msgStuff['naListThing']              = 'Věc';
$__msgStuff['naListAction']             = 'Ďalší akce';
$__msgStuff['naListWf']                 = 'Čekám na';
$__msgStuff['naListSd']                 = 'Nápad';
$__msgStuff['naListComment']            = 'Poznámka';
$__msgStuff['schTimeFrame']             = 'Termín';
$__msgStuff['schInBox']                 = 'V schránce';
$__msgStuff['schAppointment']           = 'Vybavit';

$__msgStuff['advSearchKeywords']        = 'Hledat klíčová slova';
$__msgStuff['advSearchField']           = 'V poli';

$__msgStuff['advSearchBox']             = 'V schránce';
$__msgStuff['advSearchAllBoxes']        = 'Ve všech schránkách';
$__msgStuff['advSearchLabeled']         = 'Označeno nálepkou';
$__msgStuff['advSrchAllCtxs']     = 'Nálepka nerozhoduje';
$__msgStuff['advSearchDisplay']         = 'Zobrazení';
$__msgStuff['advSrchDispList']			= 'Seznam';
$__msgStuff['advSrchDispTree']			= 'Zeskupit dle nálepek';
$__msgStuff['advSrchAllFields']			= 'Ve všech polích';
$__msgStuff['advSearchShowBadges']      = 'zobrazovat nálepky';
$__msgStuff['advSearchTreeCount']		= new I18nCardinalSk( 'Žádné věci označené nálepkou', '1 věc označená nálepkou', "%d věci označené nálepkou", "%d věcí označených nálepkou" );
$__msgStuff['advSearchTreeCountWout']	= new I18nCardinalSk( 'Žádné věci bez nálepky', '1 věc bez nálepky', "%d věci bez nálepky", "%d věcí bez nálepky" );

$__msgStuff['ico2m']                    = '2 min';

$__msgStuff['noResultsEmptyBox']        = "Schránka <b>%s</b> je prázdná.";
$__msgStuff['noResultsEmptySchedule']   = "Nejsou naplánovány žádné věci.";
$__msgStuff['noResultsEmptyProjects']	= "Nemáte žádné projekty.";


//$__msgStuff['altCtxRem']                = 'Odstránit';



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

/*
 * Append common strings.
 */
/*include N7_SOLUTION_ROOT . 'i18n/common/cs.php';
$__msgStuff = array_merge( $__msgStuff, $__msgCommon );*/

?>