<?php

/**
 * @file _uicmp_stuff_search_all_form.php
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 */

require_once CHASSIS_LIB . 'uicmp/vsearch.php';

require_once APP_STUFF_LIB . 'class.StuffSearchBoxes.php';

/**
 * Search form UICMP component for All tab search solution.
 */
class _uicmp_stuff_search_all_form extends \io\creat\chassis\uicmp\srchfrm
{
	/**
	 * Reference to application localization messages.
	 *
	 * @var array
	 */
	protected $messages = NULL;

	/**
	 * Export of configuration data from list config instance.
	 *
	 * @var array
	 */
	protected $cfg = NULL;

	/**
	 * Constructor.
	 * 
	 * @param \io\creat\chassis\uicmp\head $parent parent element
	 * @param string $id component ID
	 * @param string $jsVar search control variable
	 * @param string $keywords keywords to prefill in the form
	 * @param array $messages localization messages
	 * @param _list_cfg $cfg previously saved list display configuration
	 */
	public function  __construct ( &$parent, $id, $jsVar, $keywords, &$messages, &$cfg )
	{
		parent::__construct( $parent, $id, $jsVar, $keywords );

		$this->type		= __CLASS__;
		$this->renderer	= APP_STUFF_UI . 'uicmp/search_all_form.html';
		$this->messages	= $messages;
		$this->cfg		= $cfg;
	}
	
	/**
	 * Adds contexts into existing array of data used for UI <SELECT> box.
	 * 
	 * @param array $result reference to array to be populated with contexts
	 */
	public static function getCtxs ( &$result )
	{
		$ctxs = _cdes::allCtxs( \io\creat\chassis\session::getInstance( )->getUid( ), StuffConfig::T_STUFFCTX );
		if ( is_array( $ctxs ) )
		{
			foreach ( $ctxs as $CID => $ctx )
				$result['ctx']["{$CID}"]  = $ctx->disp;
		}
	}

	/**
	 * Returns array populated with form data. This method should be accessed
	 * from component's Smarty template.
	 *
	 * @return array data for advanced search form
	 *
	 * @todo english localization confuses Label as title for task and Label as
	 * context
	 */
	public function getData ( )
	{
		$data['showCtxs'] = $this->cfg['s'];
		$data['selDisplay'] = $this->cfg['y'];
		$data['display'][StuffSearchBoxes::ADVSRCHDISP_LIST] = $this->messages['advSrchDispList'];
		$data['display'][StuffSearchBoxes::ADVSRCHDISP_TREE] = $this->messages['advSrchDispTree'];

		$data['selBox']			= $this->cfg['b'];
		$data['box']['All']		= $this->messages['advSearchAllBoxes'];
		$data['box']['Inbox']	= $this->messages['boxInbox'];
		$data['box']['Na']		= $this->messages['boxNextActions'];
		$data['box']['Wf']		= $this->messages['boxWaitingFor'];
		$data['box']['Sd']		= $this->messages['boxSomeday'];
		$data['box']['Ar']		= $this->messages['boxArchive'];

		$data['selField'] = $this->cfg['f'];
		$data['field'][StuffSearchBoxes::ADVSRCHFIELD_ALL]  = $this->messages['advSrchAllFields'];
		$data['field'][StuffSearchBoxes::ADVSRCHFIELD_NAME] = $this->messages['cpe']['pt']['Inbox'];
		$data['field'][StuffSearchBoxes::ADVSRCHFIELD_DESC] = $this->messages['cpeDetails'];
		$data['field'][StuffSearchBoxes::ADVSRCHFIELD_CTX]  = $this->messages['cpeCtxs'];

		$data['selCtx']		= $this->cfg['c'];
		$data['ctx']['0']	= $this->messages['advSrchAllCtxs'];

		self::getCtxs( $data );
		
		return $data;
	}
}

?>