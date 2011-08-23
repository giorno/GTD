<?php

/**
 * @file _vcmp_cpe.php
 * @author giorno
 * @subpackage Stuff
 *
 * Virtual component creating tab layout for CPE (Collect/Process/Edit) form.
 * This solution comprises creation of control panel in tab head section and
 * body content, the form itself.
 */

require_once CHASSIS_LIB . 'uicmp/vcmp.php';
require_once CHASSIS_LIB . 'uicmp/buttons.php';
require_once CHASSIS_LIB . 'uicmp/grpitem.php';
require_once CHASSIS_LIB . 'uicmp/indicator.php';

require_once APP_STUFF_LIB . 'uicmp/_uicmp_cpe_frm.php';
require_once APP_STUFF_LIB . 'uicmp/_uicmp_cpe_cal.php';
require_once APP_STUFF_LIB . 'uicmp/_uicmp_cpe_prjpick.php';

class _vcmp_cpe extends \io\creat\chassis\uicmp\vcmp
{
	/**
	 * CPE form instance.
	 * 
	 * @var <_uicmp_cpe_frm> 
	 */
	protected $form = NULL;

	/**
	 * Calendar dialog instance.
	 *
	 * @var <_uicmp_cpe_cal>
	 */
	protected $cal = NULL;

	/**
	 * Project picker dialog.
	 *
	 * @var <_uicmp_cpe_prjpick>
	 */
	protected $picker = NULL;

	/**
	 * Javascript instance of folds information container.
	 * 
	 * @var <string>
	 */
	protected $foldsJsVar = NULL;

	/**
	 * Search solution for picker dialog.
	 * 
	 * @var <_vcmp_search>
	 */
	protected $search = NULL;

	/**
	 * Indicator instance.
	 * 
	 * @var <_uicmp_gi_ind>
	 */
	protected $ind = NULL;

	/**
	 * Save button instance.
	 *
	 * @var <_uicmp_gi>
	 */
	protected $bt = NULL;

	/**
	 * Save and close checkbox instance.
	 *
	 * @var <_uicmp_gi>
	 */
	protected $chk = NULL;

	/**
	 * Id used for HTML Id's.
	 *
	 * @var <string>
	 */
	protected $id = NULL;

	/**
	 * URL of Ajax server implementation for Ajax requests.
	 *
	 * @var <string>
	 */
	protected $url = NULL;

	/**
	 * Associative array of additional parameters for Ajax request.
	 *
	 * @var <array>
	 */
	protected $params = NULL;

	/**
	 * Localization messages.
	 * 
	 * @var <array>
	 */
	protected $messages = NULL;

	public function __construct ( &$parent, $id, $folds_js_name, $search_id, $dlgs, $url, $params, $list_cfg, &$messages, $tah, $tz, $presets )
	{
		parent::__construct( $parent );
		$this->id			= $id;
		$this->url			= $url;
		$this->foldsJsVar	= $folds_js_name;
		$this->params		= $params;
		$this->messages		= $messages;
		$this->form			= new _uicmp_cpe_frm( $this->parent->getBody( ), $id . '.Form', $tah, $presets );
		$this->cal			= new _uicmp_cpe_cal( $dlgs, $this->id . '.Cal', $tz );
		$this->picker		= new _uicmp_cpe_prjpick( $dlgs, $this->id . '.PrjPick' );

		$this->picker->getHead( )->add( new \io\creat\chassis\uicmp\headline( $this->picker, $this->picker->getId( ) . '.Title', $this->messages['cpePrjPickCaption'] ) );

		$this->params['cpe_js_var']	= $this->getJsVar( );	// temporarily push form Javascript variable for project picker
		$this->search = new \io\creat\chassis\uicmp\vsearch( $search_id, $this->picker, \io\creat\chassis\uicmp\vsearch::FLAG_NORESIZER, $this->url, $this->params, $list_cfg, 10 );
		$this->picker->addVcmp( $this->search );
		unset( $this->params['cpe_js_var'] );				// restore parameters structure

		$this->parent->getBody( )->add( $this->form );
		$dlgs->addUicmp( $this->cal );
		$dlgs->addUicmp( $this->picker );

		$buttons = new \io\creat\chassis\uicmp\buttons( $this->parent->getHead( ), $this->parent->getHead( )->getId( ) . '.Buttons' );
				$buttons->add( new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.Back', \io\creat\chassis\uicmp\grpitem::IT_A, $this->messages['cpeBtBack'], $this->parent->getLayoutJsVar( ) . '.back( );', '_uicmp_gi_back' ) );
				$buttons->add( new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.S1', \io\creat\chassis\uicmp\grpitem::IT_TXT, '|' ) );
				$buttons->add( $this->bt = new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.Save', \io\creat\chassis\uicmp\grpitem::IT_BT, $this->messages['cpeBtSave'], $this->form->getJsVar() . '.save( );' ) );
				$buttons->add( $this->chk = new \io\creat\chassis\uicmp\grpitem( $buttons, $buttons->getId( ) . '.Chk', \io\creat\chassis\uicmp\grpitem::IT_CHK, $this->messages['cpeBtCloseOnSave'] ) );
				$this->ind = new \io\creat\chassis\uicmp\indicator( $buttons, $buttons->getId( ) . '.Ind', \io\creat\chassis\uicmp\grpitem::IT_IND, $this->messages['cpe'] );
					$buttons->add( $this->ind );
				$this->parent->getHead( )->add( $buttons );
	}
	
	/**
	 * Read access to form Javascript variable name.
	 * 
	 * @return <string>
	 */
	public function getJsVar() { return $this->form->getJsVar( ); }

	public function generateReqs ( )
	{
		$requirer = $this->form->getRequirer( );
		if ( !is_null( $requirer ) )
		{
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( $requirer->getRelative( ) . '3rd/XMLWriter-1.0.0-min.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( $requirer->getRelative( ) . '3rd/base64.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JS, array( 'inc/stuff/_uicmp_stuff.js' , __CLASS__ ) );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . $this->cal->getJsVar( ) . ' = new _uicmp_cpe_cal( \'' . $this->cal->getHtmlId( ) . '\' );' );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . $this->picker->getJsVar( ) . ' = new _uicmp_cpe_prjpick( \'' . $this->picker->getHtmlId( ) . '\', ' . $this->search->getJsVar( ) . ' );' );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . $this->form->getJsVar( ) . ' = new _vcmp_cpe( \'' . $this->form->getJsVar( ) . '\', ' . $this->parent->getLayoutJsVar( ) . ', ' . $this->cal->getJsVar( ) . ', ' . $this->picker->getJsVar( ) . ', ' . $this->foldsJsVar . ', \'' . $this->parent->getHtmlId( ) . '\', \'' . $this->parent->getHead( )->getFirst( )->getHtmlId( ) . '\', \'' . $this->bt->getHtmlId( ) . '\', \'' . $this->chk->getHtmlId( ) . '\', \'' . $this->form->getHtmlId( ) . '\', ' . $this->ind->getJsVar( ) . ', \''. $this->url . '\', ' . \io\creat\chassis\uicmp\uicmp::toJsArray( $this->params ) . ' );' );
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, $this->parent->getLayoutJsVar( ) . '.registerTabCb( \'' . $this->parent->getHtmlId( ) . '\', \'onLoad\', ' . $this->form->getJsVar( ) . '.startup );' );
			
		}
	}
}

?>