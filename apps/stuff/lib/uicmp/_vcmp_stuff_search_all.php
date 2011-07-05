<?php

/**
 * @file _vcmp_stuff_search_all.php
 * @author giorno
 *
 * Virtual component for Stuff application All tab search solution.
 */

require_once CHASSIS_LIB . 'uicmp/_vcmp_search.php';
require_once CHASSIS_LIB . 'uicmp/_uicmp_srch_cnt.php';

require_once APP_STUFF_LIB . 'uicmp/_uicmp_stuff_search_all_form.php';

class _vcmp_stuff_search_all extends _vcmp_search
{

	public function  __construct ( $id, &$tab, $url, $params, $list_cfg, &$settings, &$messages )
	{
		$this->id		= $id;
		$this->tab		= $tab;
		$this->url		= $url;
		$this->params	= $params;
		$this->config	= $list_cfg->get( );
		$this->layout	= $this->tab->getParent( );
		$this->requirer	= $this->layout->getRequirer( );
		$this->size		= n7_globals::settings()->get( 'usr.lst.len' );

		/**
		 * For indicator states messages.
		 */
		$_uimcp_messages = $this->layout->getMessages( );
		
		$this->form = new _uicmp_stuff_search_all_form( $this->tab->getHead( ), $this->id . '.Form', $this->getJsVar( ), ( ( is_array( $this->config ) ) ? $this->config['k'] : '' ), $messages, $this->config );
			$this->ind = new _uicmp_gi_ind( $this->form, $this->form->getId( ) . '.Ind', _uicmp_gi::IT_IND, $_uimcp_messages['srch'] );
			$this->form->add( $this->ind );
		$this->container = new _uicmp_srch_cnt( $this->tab->getBody( ), $this->id . '.Results' );
		$this->resizer = new _uicmp_srch_res( $this->tab->getBody( ), $this->id . '.Resizer', $this->getJsVar( ), $this->size );

		$this->tab->getHead( )->add( $this->form );
		$this->tab->getBody( )->add( $this->container );
		$this->tab->getBody( )->add( $this->resizer );
	}

	/**
	 * Generates Javascript dependencies and code to initialize client side
	 * of the component.
	 */
	public function  generateJs ( )
	{
		$this->requirer->call( _uicmp_layout::RES_JS, Array( 'inc/stuff/_uicmp_stuff.js' , __CLASS__ ) );
		$this->requirer->call( _uicmp_layout::RES_CSS, Array( 'inc/stuff/stuff.css' , __CLASS__ ) );

		/**
		 * Initialize client side.
		 */
		$this->requirer->call( _uicmp_layout::RES_JSPLAIN, 'var ' . $this->getJsVar( ) . ' = new _vcmp_stuff_search_all( \'' . $this->id . '\', \'' . $this->tab->getHtmlId( ) . '\', '. $this->ind->getJsVar( ) . ', \'' . $this->url . '\', ' . $this->generateJsArray( $this->params ) . ', ' . $this->generateJsArray( $this->config ) . ', \'' . $this->form->getHtmlId( ) . '\', \'' . $this->container->getHtmlId( ) . '\', \'' . $this->resizer->getHtmlId( ) . '\' );' );
		$this->requirer->call( _uicmp_layout::RES_JSPLAIN, $this->layout->getJsVar( ) . '.registerTabCb( \'' . $this->tab->getHtmlId( ) . '\', \'onShow\', ' . $this->getJsVar( ) . '.tabShown );' );
		$this->requirer->call( _uicmp_layout::RES_JSPLAIN, $this->layout->getJsVar( ) . '.registerTabCb( \'' . $this->tab->getHtmlId( ) . '\', \'onLoad\', ' . $this->getJsVar( ) . '.startup );' );
		
		$this->setJsSize( );
	}
}

?>