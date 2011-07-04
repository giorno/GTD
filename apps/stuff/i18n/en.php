<?PHP
/* 
 * @file en.php
 *
 * Stuff tab internationalization file for English language.
 *
 * @author giorno
 */

require_once CHASSIS_LIB . 'i18n/class.I18nCardinal.php';

/**
 * Common (e.g. for HTML <header> tag).
 */
$__msgStuff['tabName']                  = 'Things to do';
$__msgStuff['tabNumbers']				= new I18nCardinal( 'No things to do', '1 thing to do', "%d things to do" );

$__msgStuff['boxInbox']                 = 'Inbox';
$__msgStuff['boxNextActions']           = 'Next actions';
$__msgStuff['boxNa']					= $__msgStuff['boxNextActions'];
$__msgStuff['boxWaitingFor']            = 'Waiting for';
$__msgStuff['boxWf']					= $__msgStuff['boxWaitingFor'];
$__msgStuff['boxSchedule']              = 'Schedule';
$__msgStuff['boxProjects']              = 'Projects';
$__msgStuff['boxSomeday']               = 'Someday/Maybe';
$__msgStuff['boxSd']					= $__msgStuff['boxSomeday'];
$__msgStuff['boxArchive']               = 'Archive';
$__msgStuff['boxAr']					= $__msgStuff['boxArchive'];
$__msgStuff['tabAllStuff']              = 'All stuff';

/**
 * CPE form strings.
 */
$__msgStuff['cpeMenuItem']				= 'Collect new thing';
$__msgStuff['cpeTitle']					= 'Collect';
$__msgStuff['cpeTitleEdit']				= 'Edit';
$__msgStuff['cpeTitleProcess']			= 'Process';
$__msgStuff['cpeBtBack']				= 'Back';
$__msgStuff['cpeBtSave']				= 'Save';
//$__msgStuff['cpeBtEdit']				= $__msgStuff['cpeTitleEdit'];
$__msgStuff['cpeBtProcess']				= $__msgStuff['cpeTitleProcess'];
$__msgStuff['cpeBtCloseOnSave']			= 'close the form';
$__msgStuff['cpeBtClose']				= 'Close';
$__msgStuff['cpeBtCopy']				= 'Copy';
$__msgStuff['cpeBtScroll']				= 'Scroll to first record';
$__msgStuff['cpeStoreIn']				= 'Store in';
$__msgStuff['cpeDetails']				= 'Details';
$__msgStuff['cpeCtxs']					= 'Labels';
$__msgStuff['cpePriority']				= 'Priority';
$__msgStuff['cpePlace']					= 'Place';
$__msgStuff['cpeDate']					= 'Date';
$__msgStuff['cpeTime']					= 'Time';
$__msgStuff['cpeToday']					= 'Today';
$__msgStuff['cpeTomorrow']				= 'Tomorrow';
$__msgStuff['cpeCalendar']				= 'Calendar';
$__msgStuff['cpeProject']				= 'Project';
$__msgStuff['cpeNoPrj']					= 'Not assigned to any project';
$__msgStuff['cpeAttToPrj']				= 'Assign to project';
$__msgStuff['cpeChangePrj']				= 'Move into different project';
$__msgStuff['cpeDetachPrj']				= 'Remove from project';
$__msgStuff['cpeAttToPrjPick']			= 'Pick project';
$__msgStuff['cpeSubTasks']				= 'Subtasks';
$__msgStuff['cpeHistory']				= 'History';
$__msgStuff['cpePrjPickCaption']		= 'Search or list thing, which has to become a project a click on it';
$__msgStuff['cpe']['box']['Inbox']		= $__msgStuff['boxInbox'];
$__msgStuff['cpe']['box']['Na']			= $__msgStuff['boxNextActions'];
$__msgStuff['cpe']['box']['Wf']			= $__msgStuff['boxWaitingFor']  ;
$__msgStuff['cpe']['box']['Sd']			= $__msgStuff['boxSomeday'];
$__msgStuff['cpe']['box']['Ar']			= $__msgStuff['boxArchive'] ;
$__msgStuff['cpe']['pt']['Inbox']		= 'Description';						// Prompts for task field
$__msgStuff['cpe']['pt']['Wf']			= 'Who or what';
$__msgStuff['cpe']['pt']['Na']			= 'Action';
$__msgStuff['cpe']['pt']['Sd']			= 'Idea';
$__msgStuff['cpe']['pt']['Ar']			= 'Comment';
$__msgStuff['cpe']['pty'][0]			= 'None';							// Task levels of priority
$__msgStuff['cpe']['pty'][1]			= 'Low';
$__msgStuff['cpe']['pty'][2]			= 'Normal';
$__msgStuff['cpe']['pty'][3]			= 'High';
$__msgStuff['cpe']['pty'][4]			= 'Critical';
$__msgStuff['cpe']['saving']			= 'Saving...';
$__msgStuff['cpe']['saved']				= 'Saved';
$__msgStuff['cpe']['loading']			= 'Loading...';
$__msgStuff['cpe']['loaded']			= 'Loaded';
$__msgStuff['cpe']['preparing']			= 'Preparing...';
$__msgStuff['cpe']['prepared']			= 'Prepared';
$__msgStuff['cpe']['e_unknown']			= 'Error: unknown error! Contact administrators.';
$__msgStuff['cpe']['e_empty']			= 'Error: empty string!';
$__msgStuff['cpe']['e_date']			= 'Error: invalid format of date!';
$__msgStuff['cpe']['e_loop']			= 'Error: project looped in subtasks!';

/**
 * Two-character abbreviations of days of week.
 */
$__msgStuff['dow']['mo']				= 'Mo';
$__msgStuff['dow']['tu']				= 'Tu';
$__msgStuff['dow']['we']				= 'We';
$__msgStuff['dow']['th']				= 'Th';
$__msgStuff['dow']['fr']				= 'Fr';
$__msgStuff['dow']['sa']				= 'Sa';
$__msgStuff['dow']['su']				= 'Su';

/**
 * CDES solution strings.
 */
$__msgStuff['cdesFold']					= 'Labels';
$__msgStuff['cdesTitle']				= 'Labels to mark things';

$__msgStuff['capAllStuff']              = 'Advanced search in all stuff';
$__msgStuff['capSchedule']				= 'Things planned on certain date';
$__msgStuff['capProjects']				= 'Things requiring more steps';
$__msgStuff['capNextActions']			= 'Tasks to do';
$__msgStuff['capInbox']					= 'Collected but not processed things';
$__msgStuff['capWaitingFor']			= 'Things waiting for other people or actions';
$__msgStuff['capSomeday']				= 'Ideas, future projects, references';
$__msgStuff['capArchive']				= 'Finished things waiting for removal';

$__msgStuff['miScroll2Top']			= 'To the top of the page';

/**
 * Archive list form strings.
 */
$__msgStuff['arBpRemove']				= 'Remove selected';
$__msgStuff['arBpQuestion']				= 'Do you really want to remove all selected entries? This operation cannot be undone.';
$__msgStuff['arWarning']				= 'Warning';
$__msgStuff['arQuestion']				= 'Do you really want to remove entry <b>%s</b>? This operation cannot be undone.';
$__msgStuff['arBtYes']					= 'Yes';
$__msgStuff['arBtNo']					= 'No';
$__msgStuff['arAltRemove']				= 'Remove';
$__msgStuff['arMinutes']				= '2 minutes job';
$__msgStuff['arFinished']				= 'Finished';
$__msgStuff['arGarbage']				= 'Garbage';
$__msgStuff['arAltMinutes']				= 'Archive as ' . $__msgStuff['arMinutes'];
$__msgStuff['arAltGarbage']				= 'Archive as ' . $__msgStuff['arGarbage'];
$__msgStuff['arAltFinished']			= 'Archive as ' . $__msgStuff['arFinished'];

/**
 * SEM related strings.
 */
$__msgStuff['sem']['title']				= 'Stuff application settings';
$__msgStuff['sem']['aLg']				= 'Lifegoals';
$__msgStuff['sem']['dLg']				= 'Stuff marked with chosen label will show as cloud in bottom area of page.';
$__msgStuff['sem']['oNoLg']				= 'Turn off Lifegoals';
$__msgStuff['sem']['oLg'][0]			= 'Only from box Someday/Maybe';
$__msgStuff['sem']['oLg'][1]			= 'From all boxes except Archive';
$__msgStuff['sem']['oLg'][2]			= 'From all boxes';
$__msgStuff['sem']['aAlg']				= 'Color for number of Things';
$__msgStuff['sem']['dAlg']				= 'Determination of color scheme for count of things in boxes (colored rectangle with number on box tab).';
$__msgStuff['sem']['oAlg']['hofstadter']= 'Hofstadter algorithm';
$__msgStuff['sem']['oAlg']['simpleMath']= 'Average of priorities';
$__msgStuff['sem']['oAlg']['static']	= 'Disable colors';
$__msgStuff['sem']['aPresets']			= 'Time presets';
$__msgStuff['sem']['dPresets']			= 'Number and algorithm to select most used time presets in the form for collecting and processing.';
$__msgStuff['sem']['oPresetsNo'][0]		= 'Disable time presets';
$__msgStuff['sem']['oPresetsNo'][3]		= '3 options';
$__msgStuff['sem']['oPresetsNo'][4]		= '4 options';
$__msgStuff['sem']['oPresetsNo'][5]		= '5 options';
$__msgStuff['sem']['oPresetsNo'][6]		= '6 options';
$__msgStuff['sem']['oPresetsNo'][7]		= '7 options';
$__msgStuff['sem']['oPresetsBy'][-1]	= 'System';
$__msgStuff['sem']['oPresetsBy'][7]		= 'In last week';
$__msgStuff['sem']['oPresetsBy'][30]	= 'In last 30 days';
$__msgStuff['sem']['oPresetsBy'][60]	= 'In last 60 days';
$__msgStuff['sem']['oPresetsBy'][90]	= 'In last 90 days';
$__msgStuff['sem']['oPresetsBy'][0]		= 'Since ever';

$__msgStuff['editorHistoryBoxInbox']    = $__msgStuff['boxInbox'];
$__msgStuff['editorHistoryBoxNa']       = $__msgStuff['boxNextActions'];
$__msgStuff['editorHistoryBoxWf']       = $__msgStuff['boxWaitingFor'];
$__msgStuff['editorHistoryBoxSd']       = $__msgStuff['boxSomeday'];
$__msgStuff['editorHistoryBoxAr']       = $__msgStuff['boxArchive'];

/**
 * Empty list content messages.
 */
$__msgStuff['empty']['box']				= 'This box is empty.';
$__msgStuff['empty']['Schedule']		= 'You do not have any scheduled tasks.';
$__msgStuff['empty']['Projects']		= 'You do not have any projects created yet.';
$__msgStuff['empty']['All']				= 'You do not have any records.';
$__msgStuff['nomatch']['box']			= 'Not match found for search request.';
$__msgStuff['nomatch']['Schedule']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['Projects']		= $__msgStuff['nomatch']['box'];
$__msgStuff['nomatch']['All']			= $__msgStuff['nomatch']['box'];
$__msgStuff['eo']['collect']			= 'Collect new thing';
$__msgStuff['eo']['again']				= 'Change keywords and search again';
$__msgStuff['eo']['all']				= 'Show all content';

/**
 * Lifegoals widget.
 */
$__msgStuff['goals']['caption']			= 'Lifegoals';
$__msgStuff['goals']['i']['loading']	= 'Loading...';
$__msgStuff['goals']['i']['loaded']		= 'Loaded';
$__msgStuff['goals']['i']['e_unknown']	= $__msgStuff['cpe']['e_unknown'];
$__msgStuff['goals']['empty']			= 'You do not have any goals. Please make yourself one or change settings.';

$__msgStuff['prjParent']				= 'Parent';

$__msgStuff['inboxListTask']            = 'Task';
$__msgStuff['inboxDateYesterday']       = 'Yesterday';
$__msgStuff['inboxDateTomorrow']        = 'Tomorrow';
$__msgStuff['inboxDateToday']           = 'Today';
$__msgStuff['inboxListRecorded']        = 'Recorded';
$__msgStuff['inboxListMoved']           = 'Moved';
$__msgStuff['inboxListPriority']        = 'Priority';
$__msgStuff['naTask']                   = 'First record';
$__msgStuff['naListThing']              = 'Thing';
$__msgStuff['naListAction']             = 'Next action';
$__msgStuff['naListWf']                 = 'Waiting for';
$__msgStuff['naListSd']                 = 'Idea';
$__msgStuff['naListComment']            = 'Comment';
$__msgStuff['schTimeFrame']             = 'Time frame';
$__msgStuff['schInBox']                 = 'In box';
$__msgStuff['schAppointment']           = 'Appointment';

$__msgStuff['advSearchKeywords']        = 'Search keywords';
$__msgStuff['advSearchField']           = 'In field';

$__msgStuff['advSearchBox']             = 'In box';
$__msgStuff['advSearchAllBoxes']        = 'In all boxes';
$__msgStuff['advSearchLabeled']         = 'Labeled';
$__msgStuff['advSrchAllCtxs']     = 'Any (or none) label';
$__msgStuff['advSearchDisplay']         = 'Display';
$__msgStuff['advSrchDispList']			= 'List';
$__msgStuff['advSrchDispTree']			= 'Group by labels';
$__msgStuff['advSrchAllFields']			= 'In all fields';
$__msgStuff['advSearchShowBadges']      = 'show labels';
$__msgStuff['advSearchTreeCount']		= new I18nCardinal( 'No things in label', '1 thing in label', "%d things in label" );
$__msgStuff['advSearchTreeCountWout']	= new I18nCardinal( 'No things without label', '1 thing without label', "%d things without label" );

$__msgStuff['noResultsEmptyBox']        = "Box <b>%s</b> is empty.";
$__msgStuff['noResultsEmptySchedule']   = "There are no scheduled things.";
$__msgStuff['noResultsEmptyProjects']	= "You do not have any projects.";

/*
 * Stub containing formatting strings for strformat.
 */
$__msgStuff['dtFormat']['RECDATE']		= '%b %e';
$__msgStuff['dtFormat']['RECDATEwY']	= '%m/%d/%y';
$__msgStuff['dtFormat']['RECTIME']		= '%l:%M %P';
$__msgStuff['dtFormat']['PRESET']		= $__msgStuff['dtFormat']['RECTIME'];
$__msgStuff['dtFormat']['RECDATETIME']	= '%b %e, %Y %l:%M%P';
$__msgStuff['dtFormat']['RECDATESHORT']	= '%e. %b, %Y';
$__msgStuff['dtFormat']['HISTTIME']		= $__msgStuff['dtFormat']['RECTIME'];
$__msgStuff['dtFormat']['HISTDATE']		= '%b %e, %Y';


?>