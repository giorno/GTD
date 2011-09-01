
/**
 * @file _uicmp_stuff.js
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Client side logic for Stuff application specific UICMP components: CPE,
 * folds and search in All.
 *
 * @todo XHTML with data for Process mode should have ID's CPE instance-specific
 */

function _vcmp_cpe ( my_name, layout, cal, picker, folds, tab_id, title_id, bt_id, chk_id, form_id, ind, url, params )
{
	var me = this;
	
	this.my_name = my_name;
	this.layout = layout;
	this.tab_id = tab_id;
	this.title_id = title_id;
	this.form_id = form_id;
	this.bt_id = bt_id;
	this.chk_id = chk_id;
	this.ind = ind;
	this.url = url;
	this.params = params;
	this.strings = new Object( );
	this.cal = cal;
	this.cal.cpe = me;
	this.picker = picker;
	this.picker.cpe = me;

	/**
	 * Instance of _uicmp_stuff_folds.
	 */
	this.folds = folds;

	/**
	 * Storage for Inbox option in box select.
	 */
	this.option_inbox = null;

	/**
	 * Project ID.
	 */
	this.prj_id = 0;

	/**
	 * Stuff record ID. Zero for 'C' mode.
	 */
	this.stuff_id = 0;
	
	/**
	 * Sequence No. Valid only for 'E' mode.
	 */
	this.seq = 0;

	this.cloud = new _uicmp_cdes_cloud( this.my_name + '.cloud', this.form_id + '.ctxs', this.url, this.params );

	/**
	 * Mode of the form: [C]ollect, [P]rocess, [E]dit.
	 */
	this.mode = 'C';

	/**
	 * Callback called from tab 'onLoad' event handler. Prepares resources.
	 */
	this.startup = function ( )
	{
		me.cal.startup( );
		me.picker.startup( );
		var res_opts = new Object();
		res_opts.afterDrag = me.tah;

		new TextAreaResizer( document.getElementById( me.form_id + '.details' ), res_opts );

		/**
		 * Load strings embedded in data elements of CPE form.
		 */
		me.strings['collect'] = me.extract_string( me.form_id + '.msg.collect' );
		me.strings['edit'] = me.extract_string( me.form_id + '.msg.edit' );
		me.strings['process'] = me.extract_string( me.form_id + '.msg.process' );
		me.strings['bt.save'] = me.extract_string( me.form_id + '.msg.bt.save' );
		//me.strings['bt.edit'] = me.extract_string( me.form_id + '.msg.bt.edit' );
		me.strings['bt.process'] = me.extract_string( me.form_id + '.msg.bt.process' );
		me.strings['bt.chprj'] = me.extract_string( me.form_id + '.msg.bt.chprj' );
		me.strings['bt.attprj'] = me.extract_string( me.form_id + '.msg.bt.attprj' );
		me.strings['pt.Inbox'] = me.extract_string( me.form_id + '.msg.pt.Inbox' );
		me.strings['pt.Na'] = me.extract_string( me.form_id + '.msg.pt.Na' );
		me.strings['pt.Wf'] = me.extract_string( me.form_id + '.msg.pt.Wf' );
		me.strings['pt.Sd'] = me.extract_string( me.form_id + '.msg.pt.Sd' );
		me.strings['pt.Ar'] = me.extract_string( me.form_id + '.msg.pt.Ar' );

		disableSelection( document.getElementById( me.chk_id ) );
		disableSelection( document.getElementById( me.form_id + '.btCpy' ) );
		disableSelection( document.getElementById( me.form_id + '.btCpyD' ) );
		disableSelection( document.getElementById( me.form_id + '.btToday' ) );
		disableSelection( document.getElementById( me.form_id + '.btTomorrow' ) );
		disableSelection( document.getElementById( me.form_id + '.btCal' ) );
		disableSelection( document.getElementById( me.form_id + '.btChPrj' ) );
		disableSelection( document.getElementById( me.form_id + '.btRmPrj' ) );

		/**
		 * Disable selection on double click for time presets.
		 */
		var i = 1;
		var preset_el = document.getElementById( me.form_id + '.btPreset_' + i++ );
		while ( preset_el && ( i < 10 ) )
		{
			disableSelection( preset_el );
			preset_el = document.getElementById( me.form_id + '.btPreset_' + i++ );
		}
		
		disableSelection( document.getElementById( me.form_id + '.pDate' ) );
		disableSelection( document.getElementById( me.form_id + '.pTime' ) );
		disableSelection( document.getElementById( me.form_id + '.prjEmpty' ) );
		disableSelection( document.getElementById( me.form_id + '.prjDetails' ) );
		
	};

	/**
	 * Makes form features unavailable to user intervention. Used for the
	 * duration of sending data to the server.
	 */
	this.disable = function ( )
	{
		if ( BrowserDetect.browser == 'Explorer' )
			return;

		document.getElementById( this.form_id + '.box' ).disabled = true;
		document.getElementById( this.form_id + '.pty' ).disabled = true;
		document.getElementById( this.form_id + '.task' ).disabled = true;
		document.getElementById( this.form_id + '.loc' ).disabled = true;
		document.getElementById( this.form_id + '.details' ).disabled = true;
		
		document.getElementById( this.form_id + '.date' ).disabled = true;
		document.getElementById( this.form_id + '.year' ).disabled = true;
		document.getElementById( this.form_id + '.month' ).disabled = true;
		document.getElementById( this.form_id + '.day' ).disabled = true;

		document.getElementById( this.form_id + '.time' ).disabled = true;
		document.getElementById( this.form_id + '.hour' ).disabled = true;
		document.getElementById( this.form_id + '.minute' ).disabled = true;

		document.getElementById( this.bt_id ).disabled = true;

		this.cloud.disable( );
	};

	/**
	 * Makes form features available again after .disable() method.
	 */
	this.enable = function ( )
	{
		if ( BrowserDetect.browser == 'Explorer' )
			return;

		document.getElementById( this.form_id + '.box' ).disabled = false;
		document.getElementById( this.form_id + '.pty' ).disabled = false;
		document.getElementById( this.form_id + '.task' ).disabled = false;
		document.getElementById( this.form_id + '.loc' ).disabled = false;
		document.getElementById( this.form_id + '.details' ).disabled = false;

		document.getElementById( this.form_id + '.date' ).disabled = false;
		document.getElementById( this.form_id + '.year' ).disabled = false;
		document.getElementById( this.form_id + '.month' ).disabled = false;
		document.getElementById( this.form_id + '.day' ).disabled = false;

		document.getElementById( this.form_id + '.time' ).disabled = false;
		document.getElementById( this.form_id + '.hour' ).disabled = false;
		document.getElementById( this.form_id + '.minute' ).disabled = false;

		document.getElementById( this.bt_id ).disabled = false;

		this.cloud.enable( );
	};

	/**
	 * Renders tab caption. Also returns value of task field.
	 *
	 * @todo implement styled element for variable part of title (e.g. <i> for <span>)
	 */
	this.preview = function ( )
	{
		var caption;

		if ( this.mode == 'C' ) caption = this.strings['collect'];
		else if ( this.mode == 'P' ) caption = this.strings['process'];
		else caption = this.strings['edit'];

		var task = document.getElementById( this.form_id + '.task' ).value;

		document.getElementById( this.title_id ).innerHTML = caption + ' <i>' + task + '</i>';

		return task;
	};

	/**
	 * Callback for onChange event of box selector. Renders prompt for task
	 * field. Also returns id of selected box.
	 */
	this.box_switched = function ( )
	{
		/**
		 * Extract box Id.
		 */
		var el = document.getElementById( this.form_id + '.box' );
		if ( !el )
			return null;

		var box = el.options[el.selectedIndex].value;

		/**
		 * Render prompt.
		 */
		el = document.getElementById( this.form_id + '.pTask' );
		if ( el )
			el.innerHTML = this.strings['pt.' + box];

		return box;
	};

	/**
	 * Applies changes to control elements of the form according to form's
	 * current mode.
	 */
	this.ctrls_render = function ( )
	{
		var bt_id = document.getElementById( this.bt_id );
		var box_id = document.getElementById( this.chk_id + '.box' );
		var txt_id = document.getElementById( this.chk_id + '.txt' );

		bt_id.value = this.strings['bt.save'];
		box_id.checked = true;

		document.getElementById( this.form_id + '.btCpy' ).style.visibility = 'hidden';
		document.getElementById( this.form_id + '.btCpyD' ).style.visibility = 'hidden';

		var sel = document.getElementById( this.form_id + '.box' );

		/**
		 * For editing first record in history or collecting we need Inbox option.
		 */
		if ( ( ( this.mode == 'E' ) && ( this.seq == 0 ) ) || ( this.mode == 'C' ) )
		{
			/**
			 * Restore Inbox option if not present.
			 */
			if ( sel.options[0].value != 'Inbox' )
			{//alert(this.option_inbox);
				try
				{
					sel.add( this.option_inbox, sel.options[0] );
				}
				catch ( ex )
				{
					sel.add( this.option_inbox, 0 );	// IE was always 'special' kid
				}
			}
		}
		else // For rest of cases we must not use Inbox.
		{
			/**
			 * Store Inbox option into cache.
			 */
			if ( sel.options[0].value == 'Inbox' )
			{
				this.option_inbox = sel.options[0];//.cloneNode( true );
				//alert(this.option_inbox);
				sel.remove( 0 );
			}
		}
		
		if ( this.mode == 'C' )
		{
			box_id.disabled = false;
			txt_id.className = '_uicmp_blue';

			/**
			 * Insert stored option into record box <select>.
			 */
			if ( ( sel ) && ( sel.options[0].value != 'Inbox' ) )
				sel.add( this.option_inbox, sel.options[0] );
		}
		else
		{
			if ( this.mode == 'P' )
			{
				bt_id.value = this.strings['bt.process'];
				document.getElementById( this.form_id + '.btCpy' ).style.visibility = 'visible';
				document.getElementById( this.form_id + '.btCpyD' ).style.visibility = 'visible';
			}

			box_id.disabled = true;
			txt_id.className = '';
		}
	};

	/**
	 * Renders priority indicator and returns chosen value.
	 */
	this.pty_switched = function ( )
	{
		var pty_el = document.getElementById( this.form_id + '.pty' );
		var val = pty_el[pty_el.selectedIndex].value;

		document.getElementById( me.form_id + '.pty.preview' ).className = '_stuff_pty_ind _stuff_pty_ind_' + val;
		document.getElementById( me.form_id + '.pty.rule' ).style.width = ( val * 25 ) + '%';

		return val;
	};
	
	this.date_check = function ( )
	{
		var day = document.getElementById( me.form_id + '.day' )[document.getElementById( me.form_id + '.day' ).selectedIndex].value;
		var month = document.getElementById( me.form_id + '.month' )[document.getElementById( me.form_id + '.month' ).selectedIndex].value;
		var year = document.getElementById( me.form_id + '.year' )[document.getElementById( me.form_id + '.year' ).selectedIndex].value;

		year = Number( year );
		month = Number( month ) - 1;
		day = Number( day );

		/*
		 * Months are in Javascript indexed from 0 (january).
		 */
		var input = new Date( year, month, day );

		return ( ( input.getFullYear() == year ) && ( input.getMonth() == month ) && ( input.getDate() == day ) );
	};

	this.date_render = function ( y, m, d )
	{
		var day_el = document.getElementById( me.form_id + '.day' );
		var month_el = document.getElementById( me.form_id + '.month' );
		var year_el = document.getElementById( me.form_id + '.year' );

		for ( i = 0; i < day_el.options.length; i++ )
			if ( day_el.options[i].value == d ) {day_el.selectedIndex = i;break;}

		/*
		 * date.getMonth() result is indexed from 0 (=January).
		 */
		for ( i = 0; i < month_el.options.length; i++ )
			if ( Number( month_el.options[i].value ) == m ) {month_el.selectedIndex = i;break;}

		for ( i = 0; i < year_el.options.length; i++ )
			if ( year_el.options[i].value == y ) {year_el.selectedIndex = i;break;}
	};

	this.date_set = function ( day )
	{
		//frmEdtHideCalendar( );
		document.getElementById( me.form_id + '.date' ).checked = true;
		this.date_toggle( );
		var date = new Date( day );
		me.date_render( date.getFullYear(), date.getMonth() + 1, date.getDate( ) );
	};

	this.date_set_today = function ( ) {me.date_set( me.cal.today )};
	this.date_set_tomorrow = function ( ) {me.date_set( me.cal.tomorrow )};

	this.date_toggle = function ( )
	{
		var checked = false;

		if ( document.getElementById( me.form_id + '.date' ).checked == true )
			checked = true;
		else
			checked = false;

		document.getElementById( me.form_id + '.year' ).disabled = ( checked == false );
		document.getElementById( me.form_id + '.month' ).disabled = ( checked == false );
		document.getElementById( me.form_id + '.day' ).disabled = ( checked == false );

		document.getElementById( me.form_id + '.time' ).disabled = ( checked == false );
		this.time_toggle( );
		return checked;
	};

	this.time_render = function ( h, m )
	{
		var hour_el = document.getElementById( me.form_id + '.hour' );
		var minute_el = document.getElementById( me.form_id + '.minute' );

		for ( i = 0; i < hour_el.options.length; i++ )
			if ( hour_el.options[i].value == h ) {hour_el.selectedIndex = i;break;}
		for ( i = 0; i < minute_el.options.length; i++ )
			if ( minute_el.options[i].value == m ) {minute_el.selectedIndex = i;break;}

	}

	this.time_set = function ( time )
	{
		//frmEdtHideCalendar( );
		document.getElementById( me.form_id + '.date' ).checked = true;
		me.date_toggle( );
		document.getElementById( me.form_id + '.time' ).checked = true;
		me.time_toggle( );
		var el = time.split( ':' );
		me.time_render( el[0], el[1] );
	};

	this.time_toggle = function ( )
	{
		var checked = false;
		var el = document.getElementById( me.form_id + '.time' );

		if ( el.disabled == true )
			el.checked = false;

		if ( el.checked == true )
			checked = true;
		else
			checked = false;

		document.getElementById( me.form_id + '.hour' ).disabled = ( checked == false );
		document.getElementById( me.form_id + '.minute' ).disabled = ( checked == false );

		return checked;
	};

	this.cal_show = function ( ) {me.cal.show( );};

	this.prj_load = function ( id  )
	{
		if ( id == 0 )
		return true;

		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=load_prj' +
					 '&id=' + id +
					 '&cpe_js_var=' + scope.my_name;// +
					 //'&form_id=' + scope.form_id;

		var result = false;
		var sender = new Ajax.Updater( scope.form_id + '.prjDetails', scope.url,
									{
										asynchronous: false,
										method: 'post',
										parameters: reqParams,
										onCreate: function ( )
										{
											scope.ind.show( 'loading', '_uicmp_ind_gray' );
										},
										onComplete: function ( ) { },
										onFailure: function ( )
										{
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data ) {
											scope.ind.fade( 'loaded', '_uicmp_ind_green' );
											var el = document.getElementById( scope.form_id + '.prjEmpty' );
											if ( el )
											{
												el.style.visibility = 'hidden';
												el.style.display = 'none';
											}

											el = document.getElementById( scope.form_id + '.prjDetails' );
											if ( el )
											{
												el.style.visibility = 'visible';
												el.style.display = 'block';
											}

											document.getElementById( me.form_id + '.btChPrj' ).innerHTML = me.strings['bt.chprj'];
											el = document.getElementById( scope.form_id + '.btRmPrj' );
											if ( el )
											{
												el.style.visibility = 'visible';
											}

											result = true;
										}
									}
								);

		return result;
	};

	this.prj_pick = function ( id )
	{
		me.picker.close( );
		if ( me.prj_load( id ) )
			me.prj_id = id;
	};

	this.prj_erase = function ( )
	{
		var el = document.getElementById( me.form_id + '.prjEmpty' );
		if ( el )
		{
			el.style.visibility = 'visible';
			el.style.display = 'block';
		}

		el = document.getElementById( me.form_id + '.prjDetails' );
		if ( el )
		{
			el.style.visibility = 'hidden';
			el.style.display = 'none';
		}

		document.getElementById( me.form_id + '.btChPrj' ).innerHTML = me.strings['bt.attprj'];
		el = document.getElementById( me.form_id + '.btRmPrj' );
		if ( el )
		{
			el.style.visibility = 'hidden';
		}

		me.prj_id = 0;
	};
	
	this.picker_show = function ( ) {me.picker.show( );};

	/**
	 * Extracts single localization string from <div> element embedded in the
	 * form template.
	 */
	this.extract_string = function ( html_id )
	{
		var el = document.getElementById( html_id );
		if ( el )
			return el.innerHTML;
	};

	this.reset = function ( )
	{
		window.scroll( 0, 0 );
		this.prj_erase( );
		this.ctrls_render( );
		this.stuff_id = 0;
		this.seq = 0;
		document.getElementById( this.form_id + '.box' ).selectedIndex = 0;
		this.box_switched( );
		document.getElementById( this.form_id + '.pty' ).selectedIndex = 2;
		this.pty_switched( );
		document.getElementById( this.form_id + '.loc' ).value = '';
		document.getElementById( this.form_id + '.task' ).value = '';
		//document.getElementById( this.form_id + '.task' ).focus( );
		if ( BrowserDetect.browser != 'Explorer' )
			document.getElementById( this.form_id + '.task' ).focus();
		document.getElementById( this.form_id + '.details' ).value = '';
		me.time_set( '09:00' );
		document.getElementById( this.form_id + '.time' ).checked = false;
		me.time_toggle( );
		me.date_set_today( );
		document.getElementById( this.form_id + '.date' ).checked = false;
		me.date_toggle( );

		document.getElementById( this.form_id + '.history' ).innerHTML = '';
		
		this.preview( );
		this.cloud.get( );
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
	};
	
	this.collect = function ( )
	{
		this.layout.show( this.tab_id );
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.mode = 'C';
		this.reset( );
	};

	this.process = function ( id )
	{
		//scroll( 0, 0 );
		this.layout.show( this.tab_id );
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.mode = 'P';
		this.reset( );
		this.history_load( id );
	};

	this.edit = function ( id, seq )
	{
		this.layout.show( this.tab_id );
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.mode = 'E';
		this.seq = seq;	// for ctrls_render()
		this.reset( );
		this.load( id, seq );
	};

	/*
	 * Put latest record caption from XHTML stub into form field.
	 */
	this.cp_task = function ( )
	{
		document.getElementById( this.form_id + '.task' ).value = document.getElementById( 'hdataName' ).innerHTML;
		//document.getElementById( this.form_id + '.task' ).focus( );
		if ( BrowserDetect.browser != 'Explorer' )
			document.getElementById( this.form_id + '.task' ).focus();

	};

	/*
	 * Put latest record details from XHTML stub into form field.
	 */
	this.cp_details = function ( )
	{
		document.getElementById( this.form_id + '.details' ).value = document.getElementById( 'hdataDesc' ).innerHTML;
	}

	/**
	 * Extract data from history XHTML container.
	 */
	this.history_extract = function ( )
	{
		document.getElementById( this.form_id + '.loc' ).value = document.getElementById( 'hdataPlace' ).innerHTML;
		document.getElementById( this.form_id + '.pty' ).selectedIndex = Number( document.getElementById( 'hdataPriority' ).innerHTML );
		this.pty_switched( );
		document.getElementById( this.title_id ).innerHTML = document.getElementById( 'hdataName' ).innerHTML;

		var date_checked = ( document.getElementById( 'hdataDateSet' ).innerHTML == '1' );
		var time_checked = ( date_checked && ( document.getElementById( 'hdataTimeSet' ).innerHTML == '1' ) );

		document.getElementById( this.form_id + '.date' ).checked = date_checked;
		document.getElementById( this.form_id + '.time' ).checked = time_checked;
		this.date_toggle( );
		this.time_toggle( );

		if ( date_checked )
		{
			this.date_render( Number( document.getElementById( 'hdataDateYear' ).innerHTML ), Number( document.getElementById( 'hdataDateMonth' ).innerHTML ), Number( document.getElementById( 'hdataDateDay' ).innerHTML ) );

			if ( time_checked )
			{
				this.time_render( Number( document.getElementById( 'hdataTimeHour' ).innerHTML ), Number( document.getElementById( 'hdataTimeMinute' ).innerHTML ) );
			}

		}

		this.cloud.set_batch( document.getElementById( 'hdataCtxs' ).innerHTML );
		//frmEdtBackBox         = document.getElementById( 'hdataBox' ).innerHTML;
		this.stuff_id	= Number( document.getElementById( 'hdataSid' ).innerHTML );
		this.prj_id		= Number( document.getElementById( 'hdataPid' ).innerHTML );

	//	stuffRenderBack( frmEdtBackBox, 'txtFrmEdtBack' );
	};

	this.history_load = function ( id )
	{
		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=history_load' +
					 '&cpe_js_var=' + scope.my_name +
					 '&id=' + id;

		var sender = new Ajax.Updater( scope.form_id + '.history', scope.url,
								{
									asynchronous: false,
									method: 'post',
									parameters: reqParams,
									onCreate: function ( ) {scope.ind.show( 'loading', '_uicmp_ind_gray' );},
									onComplete: function ( ) { },
									onFailure: function ( ) {scope.ind.show( 'e_unknown', '_uicmp_ind_red' );},
									onSuccess: function ( data )
									{
										scope.ind.fade( 'loaded', '_uicmp_ind_green' );
									}
								}
							);
		me.history_extract( );
		me.prj_load( this.prj_id );
		return sender;
	};

	this.xml_parse = function ( xml )
	{
		var parser = new DOMImplementation( );
		var domDoc = parser.loadXML( xml );

		// <edt>
		var elEdt = domDoc.getDocumentElement( );

			// priority
			document.getElementById( this.form_id + '.pty' ).selectedIndex = Number( elEdt.getAttribute( 'pr' ) );
			this.pty_switched( );

			// box
			var box = elEdt.getAttribute( 'b' ).toString( );
			var selEl = document.getElementById( this.form_id + '.box' );
			for ( i = 0; i < selEl.length; i++ )
			{
				if ( selEl[i].value == box )
				{
					selEl.selectedIndex = i;
					break;
				}
			}

			//frmEdtXmlWriter.writeAttributeString( 'b', [document.getElementById( 'frmEdtBox' ).selectedIndex].value );

			// seq
			this.seq = Number( elEdt.getAttribute( 'seq' ) );
			this.prj_id = Number( elEdt.getAttribute( 'pid' ) );

		// <edt/n> - name/task
		var elName = elEdt.getElementsByTagName( 'n' ).item( 0 );
			if ( elName.getFirstChild( ) != null )
				document.getElementById( this.form_id + '.task' ).value = Base64.decode( elName.getFirstChild( ).getNodeValue( ) );
				this.preview( );

		// <edt/pl> - place
		var elPlace = elEdt.getElementsByTagName( 'pl' ).item( 0 );
			if ( elPlace.getFirstChild( ) != null )
				document.getElementById( this.form_id + '.loc' ).value = Base64.decode( elPlace.getFirstChild( ).getNodeValue( ) );

		// <edt/ds> - details
		var elDesc = elEdt.getElementsByTagName( 'ds' ).item( 0 );
			if ( elDesc.getFirstChild( ) != null )
				document.getElementById( this.form_id + '.details' ).value = Base64.decode( elDesc.getFirstChild( ).getNodeValue( ) );

		// <edt/d> - date
		var elDate = elEdt.getElementsByTagName( 'd' ).item( 0 );
			var date_checked = ( elDate.getAttribute( 's' ).toString( ) == 'true' );
			document.getElementById( this.form_id + '.date' ).checked = date_checked;
			this.date_toggle( );

		if ( date_checked )
		{
			this.date_render( Number( elDate.getAttribute( 'y' ) ), Number( elDate.getAttribute( 'm' ) ), Number( elDate.getAttribute( 'd' ) ) );

			// <edt/t> - time (not relevent if date is not set)
			var elTime = elEdt.getElementsByTagName( 't' ).item( 0 );
				var time_checked = ( elTime.getAttribute( 's' ).toString( ) == 'true' );
				document.getElementById( this.form_id + '.time' ).checked = time_checked;
				this.time_toggle( );

			if ( time_checked )
				this.time_render( Number( elTime.getAttribute( 'h' ) ), Number( elTime.getAttribute( 'm' ) ) );
		}

		// <edt/ctxs> - contexts
		var elCtxs = elEdt.getElementsByTagName( 'ctxs' ).item( 0 );
		var ctxId = 0;
			//frmEdtCtxCloud.on = new Object( );
			for ( i = 0; i < elCtxs.getElementsByTagName( 'ctx' ).length; i++ )
			{
				ctxId = Number( elCtxs.getElementsByTagName( 'ctx' ).item( i ).getFirstChild( ).getNodeValue( ) );
				this.cloud.set( ctxId );
				/*frmEdtCtxCloud.on[ctxId] = true;
				frmEdtCtxCloud.colorize ( ctxId );*/
			}
	};

	/**
	 * Loads sequence for editing.
	 */
	this.load = function ( id, seq )
	{
		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=load' +
					 '&seq=' + seq+
					 '&id=' + id;

		var sender = new Ajax.Request( scope.url,
								{
									asynchronous: false,
									method: 'post',
									parameters: reqParams,
									onCreate: function ( ) {scope.ind.show( 'loading', '_uicmp_ind_gray' );},
									onComplete: function ( ) { },
									onFailure: function ( ) {scope.ind.show( 'e_unknown', '_uicmp_ind_red' );},
									onSuccess: function ( data )
									{
										scope.xml_parse( data.responseText );
										scope.ind.fade( 'loaded', '_uicmp_ind_green' );
									}
								}
							);
								
		me.prj_load( this.prj_id );
		me.stuff_id = id;

		return sender;
	};

	this.prj_check_loop = function ( )
	{
		if ( ( Number( me.stuff_id ) == 0 ) || ( Number( me.prj_id ) == 0 ) )
		return false;

		if ( Number( me.stuff_id ) == Number( me.prj_id ) )
			return true;

		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=prj_check_loop' +
					 '&sid=' + this.stuff_id +
					 '&pid=' + this.prj_id;

		var result = true;
		var sender = new Ajax.Request( scope.url,
						{
							asynchronous: false,
							method: 'post',
							parameters: reqParams,
							onCreate: function ( )
							{
							},
							onComplete: function ( )
							{
							},
							onSuccess: function ( data )
							{
								//alert(data.responseText);
								if ( data.responseText == '0' )
									result = false;
							}
						}
					);

		return result;
	};

	this.check_strings = function ( )
	{
		return ( document.getElementById( me.form_id + '.task' ).value.trim() != '' );
	};

	this.save = function ( )
	{
		me.ind.show( 'saving', '_uicmp_ind_gray' );

		if ( !me.check_strings( ) )
		{
			me.ind.show( 'e_empty', '_uicmp_ind_red' );
			return;
		}

		if ( !me.date_check( ) )
		{
			me.ind.show( 'e_date', '_uicmp_ind_red' );
			return;
		}

		if ( me.prj_check_loop( ) )
		{
			me.ind.show( 'e_loop', '_uicmp_ind_red' );
			return;
		}

		me.disable( );

		writer = new XMLWriter( 'UTF-8', '1.0' );

		writer.writeStartDocument( false );

			writer.writeStartElement( 'cpe' )

				switch ( me.mode )
				{
					case 'E':
						writer.writeAttributeString( 'seq', me.seq );
					case 'P':
						writer.writeAttributeString( 'id', me.stuff_id );
					break;
				}

				writer.writeAttributeString( 'pid', me.prj_id );

				writer.writeAttributeString( 'b', me.box_switched( ) );
				writer.writeAttributeString( 'pr', me.pty_switched( ) );

				writer.writeElementString( 'n', Base64.encode( document.getElementById( me.form_id + '.task' ).value ) );
				writer.writeElementString( 'pl', Base64.encode( document.getElementById( me.form_id + '.loc' ).value ) );
				writer.writeElementString( 'ds', Base64.encode( document.getElementById( me.form_id + '.details' ).value ) );

				writer.writeStartElement( 'd' );
						writer.writeAttributeString( 's', ( ( me.date_toggle( ) === true ) ? 'true' : 'false' ) );
						writer.writeAttributeString( 'd', document.getElementById( me.form_id + '.day' )[document.getElementById( me.form_id + '.day' ).selectedIndex].value );
						writer.writeAttributeString( 'm', document.getElementById( me.form_id + '.month' )[document.getElementById( me.form_id + '.month' ).selectedIndex].value );
						writer.writeAttributeString( 'y', document.getElementById( me.form_id + '.year' )[document.getElementById( me.form_id + '.year' ).selectedIndex].value );
				writer.writeEndElement( );

				writer.writeStartElement( 't' );
						writer.writeAttributeString( 's', ( ( me.time_toggle( ) === true ) ? 'true' : 'false' ) );
						writer.writeAttributeString( 'h', document.getElementById( me.form_id + '.hour' )[document.getElementById( me.form_id + '.hour' ).selectedIndex].value );
						writer.writeAttributeString( 'm', document.getElementById( me.form_id + '.minute' )[document.getElementById( me.form_id + '.minute' ).selectedIndex].value );
				writer.writeEndElement( );

				me.cloud.write( writer );

			writer.writeEndElement( );
		writer.writeEndDocument( );

		var data = waPlusSignWaEncode( writer.flush() );

		writer.close( );

		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Method value.
		 */
		var method = '';
		switch ( me.mode )
		{
			case 'C':method = 'collect';break;
			case 'P':method = 'process';break;
			case 'E':method = 'edit';break;
		}

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=' + method +
					 '&sid=' + scope.stuff_id;

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										postBody: reqParams + '&data=' + data,
										onCreate: function ( ) {scope.ind.show( 'saving', '_uicmp_ind_gray' );},
										onFailure: function ( )
										{
											me.enable( );
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data )
										{
											scope.folds.update( );
											me.enable( );
											scope.ind.fade( 'saved', '_uicmp_ind_green' );
											if ( ( document.getElementById( scope.chk_id + '.box' ).checked ) || ( scope.mode != 'C' ) )
												scope.layout.back( );

											scope.reset( );
											
											if ( typeof _wwgGoals_i !== 'undefined' )
												_wwgGoals_i.refresh( );
										}
									}
								);
		return sender;
	};

	/**
	 * Sends actual height to Ajax server.
	 */
	this.tah = function ( )
	{
		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;
		
		/**
		 * Get actuall textarea height.
		 */
		var el = document.getElementById( scope.form_id + '.details' );
		var height = el.getHeight( );

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=tah' +
					 '&val=' + height;

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams
									}
								);
		return sender;
	};
}

function _uicmp_cpe_cal ( my_id/*, form_id */)
{
	var me = this;
	this.my_id = my_id;
	//this.form_id = form_id;

	/**
	 * CPE form instance. Set therein.
	 */
	this.cpe = null;

	/**
	 * SkyDome instance for the calendar.
	 */
	this.sd = null;

	/**
	 * Dialog instance.
	 */
	this.dlg = null;

	this.today = null;
	this.tomorrow = null;

	this.startup = function ( )
	{
		/**
		 * SkyDome instance.
		 */
		me.sd = new _sd_dome( me.my_id + '.sd' );

		/**
		 * Dialog rendered with SkyDome.
		 */
		me.dlg = new _sd_simple_ctrl( me.sd, me.my_id );

		/**
		 * Extract today and tomorrow.
		 */
		this.today = document.getElementById( me.my_id + '.today' ).innerHTML;
		this.tomorrow = document.getElementById( me.my_id + '.tomorrow' ).innerHTML;
	};

	this.show = function ( ) {me.dlg.show( );};

	this.close = function ( ) {me.dlg.hide( );};

	this.set = function ( day )
	{
		me.cpe.date_set( day );
		me.close( );
	};
}

function _uicmp_cpe_prjpick ( my_id, srch )
{
	var me = this;
	this.my_id = my_id;
	
	/**
	 * Search solution instance.
	 */
	this.srch = srch;

	/**
	 * CPE form instance. Set therein.
	 */
	this.cpe = null;

	/**
	 * SkyDome instance for the calendar.
	 */
	this.sd = null;

	/**
	 * Dialog instance.
	 */
	this.dlg = null;

	this.startup = function ( )
	{
		/**
		 * SkyDome instance.
		 */
		me.sd = new _sd_dome( me.my_id + '.sd' );

		/**
		 * Dialog rendered with SkyDome.
		 */
		me.dlg = new _sd_fullscr_ctrl( me.sd, me.my_id );
	};

	this.show = function ( )
	{
		me.srch.showAll( );
		me.dlg.show( );
		me.srch.focus( );
	};

	this.close = function ( ) {me.dlg.hide( );};
}

_vcmp_stuff_search_all.prototype = new _uicmp_search;

function _vcmp_stuff_search_all ( id, tabId, ind, url, params, config, formId, container_id, resizer_id )
{
	/**
	 * Hack to cope with event controlled methods where instance scope may be
	 * unavailable.
	 */
	var me = this;

	/**
	 * Instance of _uicmp_ind providing messaging UI for the solution.
	 */
	this.ind = ind;

	/**
	 * Registers instance into lookup table.
	 */
	_uicmp_lookup.register( id, me );

	/**
	 * Identification of search instance. This is sent to Ajax server
	 * implementation to identify requester of the content.
	 */
	this.id = id;

	/**
	 * HTML id of corresponding tab.
	 */
	this.tabId = tabId;
	this.url = url;
	this.params = params;
	this.formId = formId;
	this.container_id = container_id;
	this.resizer_id = resizer_id;

	this.keywords = ( config != null ) ? config.k : '';
	this.page = ( config != null ) ? Number( config.p ) : 1;
	this.order = ( config != null ) ? config.o : null;
	this.dir = ( config != null ) ? config.d : 'ASC';

	/**
	 * Perhaps not necessary as it is populated from the form, but can be handy.
	 */
	this.box = ( config != null ) ? config.b : 'All';
	this.field = ( config != null ) ? config.f : 'All';
	this.context = ( config != null ) ? config.c : 0;
	this.display = ( config != null ) ? config.y : 'list';
	this.showCtxs = ( config != null ) ? config.s : 0;

	this.startup = function ( )
	{
		disableSelection( document.getElementById( me.resizer_id ) );
		disableSelection( document.getElementById( me.formId + '.showCtxs' ) );
	};

	/**
	 * Callback for tab being shown event. It is same as parent's method, but
	 * as it is callback, scope of this context islost, so it has to be re-
	 * implemented.
	 */
	this.tabShown = function ( )
	{
		me.ctxs_update( );
		me.focus( );
		me.render_resizer( );
		me.refresh( );
	};

	this.search = function ( )
	{
		this.keywords = document.getElementById( this.formId + ':input' ).value;
		this.field = document.getElementById( this.formId + ':field' )[document.getElementById( this.formId + ':field' ).selectedIndex].value;
		this.box = document.getElementById( this.formId + ':box' )[document.getElementById( this.formId + ':box' ).selectedIndex].value;
		this.context = document.getElementById( this.formId + ':context' )[document.getElementById( this.formId + ':context' ).selectedIndex].value;
		this.showCtxs = ( document.getElementById( this.formId + '.ChkBox' ).checked == true ) ? '1' : '0';
		this.display = document.getElementById( this.formId + ':display' )[document.getElementById( this.formId + ':display' ).selectedIndex].value;
		this.page = 1;
		this.focus( );
		this.refresh( );
	};

	this.showAll = function ( )
	{
		document.getElementById( this.formId + ':input' ).value = '';
		document.getElementById( this.formId + ':field' ).selectedIndex = 0;
		document.getElementById( this.formId + ':box' ).selectedIndex = 0;
		document.getElementById( this.formId + ':context' ).selectedIndex = 0;
		this.search( );
	};
	
	/**
	 * Updates contexts <SELECT> box with most accurate values.
	 */
	this.ctxs_update = function ( )
	{
		var ctx = document.getElementById( me.formId + ':context' )[document.getElementById( me.formId + ':context' ).selectedIndex].value;
		
		function onSuccess( data )
		{
			/**
			 * Presence of OK comment guarantees that received HTML code is
			 * correct.
			 */
			if ( data.responseText.substr( 0, 6 ) == '<!--OK' )
				document.getElementById( this.formId + '.ctxs_container' ).innerHTML = data.responseText;
		};
		
		var ajax_ad = new _ajax_req_ad( false, me.url, me.params );
			ajax_ad.send( { method : 'ctxs_update', id : me.formId, ctx : ctx }, { onSuccess : onSuccess } );
	};

	this.refresh = function ( )
	{
		/**
		 * Copy scope.
		 */
		
		var scope = this;
		
		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in this.params )
			reqParams += '&' + key + '=' + this.params[key];
		reqParams += '&method=refresh' +
					 '&id=' + this.id +
					 '&keywords=' + this.keywords +
					 '&page=' + this.page +
					 '&dir=' + this.dir +
					 '&order=' + this.order +
					 '&box=' + this.box +
					 '&field=' + this.field +
					 '&context=' + this.context +
					 '&showCtxs=' + this.showCtxs +
					 '&display=' + this.display;

		/**
		 * Copy Ajax indicator into this scope.
		 */
		var indId = this.formId + ':indicator';

		var sender = new Ajax.Updater( this.container_id, this.url,
										{
											asynchronous: true,
											method: 'post',
											parameters: reqParams,
											onCreate: function ( )
											{
												scope.effect_show( );
												ind.show( 'loading', '_uicmp_ind_gray' );
											},
											onFailure: function ( )
											{
												scope.effect_hide( );
												ind.show( 'e_unknown', '_uicmp_ind_red' );
											},
											onComplete: function ( )
											{
												scope.effect_hide( );
											},
											onSuccess: function ( data )
											{
												scope.effect_hide( );
												ind.fade( 'loaded', '_uicmp_ind_green' );
											}
										}
							);
		return sender;
	};
}


/**
 * For stuff.js usage.
 */
var _uicmp_stuff_folds_i = null;

/**
 * There should only one instance of this class. It holds information about
 * all Stuff application specialized fold components and performs operations
 * for them (Ajax update).
 */
function _uicmp_stuff_folds ( url, params, ico_id )
{
	var me = this;
	this.url = url;
	this.params = params;
	this.ico_id = ico_id;
	if ( _uicmp_stuff_folds_i == null )
		_uicmp_stuff_folds_i = me;
	
	/**
	 * Associative array of box ID's and HTML ID's of folds.
	 */
	this.folds = new Object( );

	/**
	 * Registers new fold instance.
	 */
	this.register = function ( box, id ) { this.folds[box] = id; };

	this.update = function ( )
	{
		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = me;

		/**
		 * Compose request parameters.
		 */
		var reqParams = '';
		for ( var key in scope.params )
			reqParams += '&' + key + '=' + scope.params[key];

		reqParams += '&method=update';

		var sender = new Ajax.Request( scope.url,
								{
									asynchronous: false,
									method: 'post',
									parameters: reqParams,
									onCreate: function ( ) { },
									onComplete: function ( ) { },
									onFailure: function ( ) {},
									onSuccess: function ( data )
									{
										var parser = new DOMImplementation( );
										var domDoc = parser.loadXML( data.responseText );
											var docRoot = domDoc.getDocumentElement();
												var global = docRoot.getElementsByTagName( 'size' ).item( 0 );
												var avg = docRoot.getElementsByTagName( 'avg' ).item( 0 );
												var el = null;

												for ( box in scope.folds )
												{
													
													el = document.getElementById( scope.folds[box] + '.ind' );
													if ( el )
													{
														el.innerHTML = Number( global.getAttribute( box ) );
														el.className = '_uicmp_stuff_fold_c' + avg.getAttribute( box );
													}
												}

												var things = global.getAttribute( 'i18n' ).toString( );
												if ( things != '' )
												{
													document.getElementById( scope.ico_id ).innerHTML = things;
												}
									}
								}
							);
		return sender;
	};
}
