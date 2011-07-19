
/**
 * @file _uicmp_stuff.js
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Client side logic for Address Book application UICMP components.
 */


function _uicmp_ab_opt_add( select, name, value )
{
	var opt = document.createElement( 'option' );
		opt.text = name;
		opt.value = value;
				
	try				// real browsers (O, FF, S/WK)
	{
		select.add( opt, null );
	}
	catch ( ex )	// bloody MSIE
	{
		select.add( opt );
	}
}

/**
 * Commont parent to all multielement widgets (typed, custom, addresses).
 */
function _uicmp_ab_multiel (  )
{
	/**
	 * Identifier of widget. Must be set in descendant.
	 */
	this.id = null;
	
	/**
	 * Shortcut to build HTML element with id and style.
	 */
	this.element = function ( tag, sub_id, style )
	{
		var el = document.createElement( tag );
		
		if ( sub_id != null )
			el.id = this.id + '.' + sub_id;
		
		if ( style != null )
			el.className = style;
		
		return el;
	};
}

_uicmp_ab_typed.prototype = new _uicmp_ab_multiel;

/**
 * This class is instantiated in form instances. Parameters should be provided
 * but parent instance.
 */
function _uicmp_ab_typed ( id, parent_id, ctrl, types, action )
{
	/**
	 * Copy scope.
	 */
	var me = this;
	
	/**
	 * Identifies group of custom fields.
	 */
	this.id = id;
	
	/**
	 * HTML ID of parent element <TBODY>.
	 */
	this.parent_id = parent_id;
	
	/**
	 * Instance owning and responsible for this object lifecycle.
	 */
	this.ctrl = ctrl;
	
	/**
	 * Associative array of predefined types.
	 */
	this.types = types;
	
	/**
	 * Associative array with data for operation. Three members are required:
	 * 'method', 'style' and 'text'.
	 */
	this.action = action;
	
	this.populate = function ( )
	{
		var select = document.getElementById( me.id + '.type' );
		
		for ( var i = select.length - 1; i>=0; i-- )
			select.remove(i);

		for ( var i in me.types )
			_uicmp_ab_opt_add( select, me.types[i], i );
	};
	
	/*
	 * Create HTML DOM subtree and place it into parent (=container for UI).
	 */
	this.build = function ( )
	{
		var parentEl = document.getElementById( me.parent_id );

		var tr = this.element( 'tr', 'tr', null );
			var tdType = this.element( 'td', 'td_type', '_uicmp_frm_field_1st' );
				var selectType = this.element( 'select', 'type', '_uicmp_frm_input' );
			var tdNumber = this.element( 'td', 'td_number', '_uicmp_frm_field_rest' );
				var inputNumber = this.element( 'input', 'number', '_uicmp_frm_input' );
			var tdComment = this.element( 'td', 'td_comment', '_uicmp_frm_field_rest' );
				var inputComment = this.element( 'input', 'comment', '_uicmp_frm_input' );
			var tdErase = this.element( 'td', 'td_erase', '_uicmp_frm_field_rest _uicmp_fri' );
				var divErase = this.element( 'div', 'div', me.action.style );
					if ( me.action.method == 'del' )
						divErase.onclick = function( ) {me.ctrl.typed_del( me.id );};
					else if ( me.action.method == 'add' )
						divErase.onclick = function( ) {me.ctrl.typed_add( );};
					divErase.innerHTML = me.action.text;
					disableSelection( divErase );

		tr.appendChild( tdType );
			tdType.appendChild( selectType );
		tr.appendChild( tdNumber );
			tdNumber.appendChild( inputNumber );
		tr.appendChild( tdComment );
			tdComment.appendChild( inputComment );
		tr.appendChild( tdErase );
			tdErase.appendChild( divErase );

		parentEl.appendChild( tr );
	};
	
	this.destroy = function ( )
	{
		var parent = document.getElementById( parent_id );
		parent.removeChild( document.getElementById( me.id + '.tr' ) );		
	};
	
	/*
	 * Export instance specific part (its related data from the form) of XML
	 * document. 
	 */
	this.xml = function ( writer )
	{
		writer.writeStartElement( 'field' );			// field
			writer.writeAttributeString( 'type', document.getElementById( me.id + '.type' )[document.getElementById( me.id + '.type' ).selectedIndex].value );			// type
			writer.writeAttributeString( 'name', document.getElementById( me.id + '.type' )[document.getElementById( me.id + '.type' ).selectedIndex].text );			// name
			writer.writeAttributeString( 'number', Base64.encode( document.getElementById( me.id + '.number' ).value ) );		// number
			writer.writeAttributeString( 'comment', Base64.encode( document.getElementById( me.id + '.comment' ).value ) );	// comment
		writer.writeEndElement( );
	};
	
	/**
	 * Populate instance with data.
	 */
	this.set = function ( kind, number, comment )
	{
		var sel = document.getElementById( me.id + '.type' );
		for ( var i = 0; i < sel.length; ++i )
			if ( sel[i].value == kind )
			{
				sel.selectedIndex = i;
			}
			
		/**
		 * New type of information is not present in <SELECT> box. This should
		 * not happen, but we must treat it properly byt adding new option into
		 * the <SELECT> box.
		 */
		if ( sel[sel.selectedIndex].value != kind )
		{
			_uicmp_ab_opt_add( sel, kind, kind );
			sel.selectedIndex = sel.length - 1;
		}
		
		document.getElementById( me.id + '.number' ).value = number;
		document.getElementById( me.id + '.comment' ).value = comment;
	};
	
	this.build( );
	this.populate( );
}

_uicmp_ab_custom.prototype = new _uicmp_ab_multiel;

/**
 * This class is instantiated in form instances. Parameters should be provided
 * but parent instance.
 */
function _uicmp_ab_custom ( id, parent_id, ctrl, action )
{
	/**
	 * Copy scope.
	 */
	var me = this;
	
	/**
	 * Identifies group of custom fields.
	 */
	this.id = id;
	
	/**
	 * HTML ID of parent element <TBODY>.
	 */
	this.parent_id = parent_id;
	
	/**
	 * Instance owning and responsible for this object lifecycle.
	 */
	this.ctrl = ctrl;
	
	/**
	 * Associative array with data for operation. Three members are required:
	 * 'method', 'style' and 'text'.
	 */
	this.action = action;
	
	/*
	 * Create HTML DOM subtree and place it into parent (=container for UI).
	 */
	this.build = function ( )
	{
		var parentEl = document.getElementById( me.parent_id );

		var tr = this.element( 'tr', 'tr', null );
			var tdType = this.element( 'td', 'td_type', '_uicmp_frm_field_1st' );
				var inputName = this.element( 'input', 'name', '_uicmp_frm_input' );
			var tdNumber = this.element( 'td', 'td_number', '_uicmp_frm_field_rest' );
				var inputNumber = this.element( 'input', 'number', '_uicmp_frm_input' );
			var tdComment = this.element( 'td', 'td_comment', '_uicmp_frm_field_rest' );
				var inputComment = this.element( 'input', 'comment', '_uicmp_frm_input' );
			var tdErase = this.element( 'td', 'td_erase', '_uicmp_frm_field_rest _uicmp_fri' );
				var divErase = this.element( 'div', 'div', me.action.style );
					if ( me.action.method == 'del' )
						divErase.onclick = function( ) {me.ctrl.custom_del( me.id );};
					else if ( me.action.method == 'add' )
						divErase.onclick = function( ) {me.ctrl.custom_add( );};
					divErase.innerHTML = me.action.text;
					disableSelection( divErase );

		tr.appendChild( tdType );
			tdType.appendChild( inputName );
		tr.appendChild( tdNumber );
			tdNumber.appendChild( inputNumber );
		tr.appendChild( tdComment );
			tdComment.appendChild( inputComment );
		tr.appendChild( tdErase );
			tdErase.appendChild( divErase );

		parentEl.appendChild( tr );
	};
	
	this.destroy = function ( )
	{
		var parent = document.getElementById( parent_id );
		parent.removeChild( document.getElementById( me.id + '.tr' ) );		
	};
	
	/*
	 * Export instance specific part (its related data from the form) of XML
	 * document.
	 */
	this.xml = function ( writer )
	{
		writer.writeStartElement( 'field' );			// field
			writer.writeAttributeString( 'name', Base64.encode( document.getElementById( me.id + '.name' ).value ) );		// name
			writer.writeAttributeString( 'number', Base64.encode( document.getElementById( me.id + '.number' ).value ) );	// number
			writer.writeAttributeString( 'comment', Base64.encode( document.getElementById( me.id + '.comment' ).value ) );	// comment
		writer.writeEndElement( );
	};
	
	this.build( );
}

_uicmp_ab_address.prototype = new _uicmp_ab_multiel;

function _uicmp_ab_address ( id, parent_id, ctrl, display, strings, action )
{
	/**
	 * Copy scope.
	 */
	var me = this;
	
	/**
	 * Identifies group of custom fields.
	 */
	this.id = id;
	
	/**
	 * HTML ID of parent element <TBODY>.
	 */
	this.parent_id = parent_id;
	
	/**
	 * Instance owning and responsible for this object lifecycle.
	 */
	this.ctrl = ctrl;
	
	/**
	 * Caption text to display.
	 */
	this.display = display;
	
	/**
	 * Localization for field prompts.
	 */
	this.strings = strings;
	
	/**
	 * Associative array with data for operation. Three members are required:
	 * 'method', 'style' and 'text'.
	 */
	this.action = action;
	
	this.build = function ( )
	{
		var parentEl = document.getElementById( me.parent_id );

		var tr = this.element( 'tr', 'tr', null );
			var td = this.element( 'td', 'td', null );
				td.setAttribute( 'colSpan' , 2 );
				var table = this.element( 'table', 'table', null );
					table.setAttribute( 'cellspacing' , '0' );
					table.setAttribute( 'cellpadding' , '0' );
					table.setAttribute( 'width' , '100%' );
					var tbody = this.element( 'tbody', 'tbody', null );
					var trCap = this.element( 'tr', 'trCap', null);
						var tdCap = this.element( 'td', 'tdCap', '_uicmp_frm_hdr' );
							tdCap.setAttribute( 'colSpan' , 2 );
							var table_cap = this.element( 'table', 'table_cap', null );
								table_cap.setAttribute( 'cellspacing' , '0' );
								table_cap.setAttribute( 'cellpadding' , '0' );
								var tbody_cap = this.element( 'tbody', 'tbody_cap', null );
									var tr_cap = this.element( 'tr', 'tr_cap', null );
										var td_cap = this.element( 'td', 'td_cap', '_uicmp_frm_hdr_item' );
											var div_cap = this.element( 'div', 'div_cap', '_uicmp_frm_hdr_cap' );
												div_cap.innerHTML = this.display;
										var td_erase = this.element( 'td', 'td_erase', '_uicmp_frm_hdr_item' );
											var div_erase = this.element( 'div', '.div_erase', action.style );
												div_erase.innerHTML = action.text;
												if ( action.method == 'add' )
													div_erase.onclick = function ( ) {me.ctrl.address_add( );};
												else if ( action.method == 'del' )
													div_erase.onclick = function ( ) {me.ctrl.address_del( me.id );};
												disableSelection( div_erase );

		tr.appendChild( td );
			td.appendChild( table );
				table.appendChild( tbody );
					tbody.appendChild( trCap );
						trCap.appendChild( tdCap );
							tdCap.appendChild( table_cap );
								table_cap.appendChild( tbody_cap );
									tbody_cap.appendChild( tr_cap );
										tr_cap.appendChild( td_cap );
											td_cap.appendChild( div_cap );
										tr_cap.appendChild( td_erase );
											td_erase.appendChild( div_erase );

					var trContainer = this.element( 'tr', 'trContainer', null );
						var tdContainer = this.element( 'td', 'tdContainer', '_uicmp_body_frm' );
							tdContainer.setAttribute( 'colSpan' , 2 );
							var tableContainer = this.element( 'table', 'tableContainer', null );
								tableContainer.setAttribute( 'cellspacing' , '0' );
								tableContainer.setAttribute( 'cellpadding' , '0' );
								tableContainer.setAttribute( 'width' , '100%' );
								var tbodyContainer = this.element( 'tbody', 'tbodyContainer', null );
								
					tbody.appendChild( trContainer );
						trContainer.appendChild( tdContainer );
							tdContainer.appendChild( tableContainer );
								tableContainer.appendChild( tbodyContainer );

									/* Description field */
									var trDesc = this.element( 'tr', 'trDesc', null );
										var tdDescPrompt = this.element( 'td', 'tdDescP', '_uicmp_frm_prompt' );
											tdDescPrompt.innerHTML = strings['comment'];
										var tdDesc = this.element( 'td', 'tdDesc', '_uicmp_frm_field' );
											tdDesc.setAttribute( 'colSpan', 3 );
											var inputDesc = this.element( 'input', 'desc', '_uicmp_frm_input' );

									tbodyContainer.appendChild( trDesc );
										trDesc.appendChild( tdDescPrompt );
										trDesc.appendChild( tdDesc );
											tdDesc.appendChild( inputDesc );

									/* Address lines */
									var trAddr1 = this.element( 'tr', 'trAddr1', null );
										var tdAddr1Prompt = this.element( 'td', 'tdAddr1P', '_uicmp_frm_prompt' );
											tdAddr1Prompt.innerHTML = strings['address'];
										var tdAddr1 = this.element( 'td', '.tdAddr1', '_uicmp_frm_field' );
											tdAddr1.setAttribute( 'colSpan', 3 );
											var inputAddr1 = this.element( 'input', 'addr1', '_uicmp_frm_input' );
									var trAddr2 = this.element( 'tr', 'trAddr2', null );
										var tdAddr2Prompt = this.element( 'td', 'tdAddr2P', null );
										var tdAddr2 = this.element( 'td', 'tdAddr2', '_uicmp_frm_field' );
											tdAddr2.setAttribute( 'colSpan', 3 );
											var inputAddr2 = this.element( 'input', 'addr2', '_uicmp_frm_input' );
											
									tbodyContainer.appendChild( trAddr1 );
										trAddr1.appendChild( tdAddr1Prompt );
										trAddr1.appendChild( tdAddr1 );
											tdAddr1.appendChild( inputAddr1 );

									tbodyContainer.appendChild( trAddr2 );
										trAddr2.appendChild( tdAddr2Prompt );
										trAddr2.appendChild( tdAddr2 );
											tdAddr2.appendChild( inputAddr2 );

									/* Zip code and City */
									var trZipCity = this.element( 'tr', 'trZip', null );
										var tdZipPrompt = this.element( 'td', 'tdZipP', '_uicmp_frm_prompt' );
											tdZipPrompt.innerHTML = strings['zip'];
										var tdZip = this.element( 'td', 'tdZip', '_uicmp_frm_field_1st' );
											tdZip.setAttribute( 'width', '10%' );
											var inputZip = this.element( 'input', 'zip', '_uicmp_frm_input' );
										var tdCityPrompt = this.element( 'td', 'tdCityP', '_uicmp_frm_prompt' );
											tdCityPrompt.innerHTML = strings['city'];
											tdCityPrompt.setAttribute( 'width', '1%' );
										var tdCity = this.element( 'td', 'tdCity', '_uicmp_frm_field_rest' );
											tdCity.setAttribute( 'width', '100%' );
											var inputCity = this.element( 'input', 'city', '_uicmp_frm_input' );

									tbodyContainer.appendChild( trZipCity );
										trZipCity.appendChild( tdZipPrompt );
										trZipCity.appendChild( tdZip );
											tdZip.appendChild( inputZip );
										trZipCity.appendChild( tdCityPrompt );
										trZipCity.appendChild( tdCity );
											tdCity.appendChild( inputCity );

									/* Country field */
									var trCountry = this.element( 'tr', 'trCountry', null );
										var tdCountryPrompt = this.element( 'td', 'tdCountryP', '_uicmp_frm_prompt' );
											tdCountryPrompt.innerHTML = strings['country'];
										var tdCountry = this.element( 'td', 'tdCountry', '_uicmp_frm_field' );
											tdCountry.setAttribute( 'colSpan', 3 );
											var inputCountry = this.element( 'input', 'country', '_uicmp_frm_input' );
											
									tbodyContainer.appendChild( trCountry );
										trCountry.appendChild( tdCountryPrompt );
										trCountry.appendChild( tdCountry );
											tdCountry.appendChild( inputCountry );

									/* Phone(s) field */
									var trPhones = this.element( 'tr', 'trPhones', null );
										var tdPhonesPrompt = this.element( 'td', 'tdPhonesP', '_uicmp_frm_prompt' );
											tdPhonesPrompt.innerHTML = strings['phones'];
										var tdPhones = this.element( 'td', 'tdPhones', '_uicmp_frm_field' );
											tdPhones.setAttribute( 'colSpan', 3 );
											var inputPhones = this.element( 'input', 'phones', '_uicmp_frm_input' );
											
									tbodyContainer.appendChild( trPhones );
										trPhones.appendChild( tdPhonesPrompt );
										trPhones.appendChild( tdPhones );
											tdPhones.appendChild( inputPhones );

									/* Faxe(s) field */
									var trFaxes = this.element( 'tr', 'trFaxes', null );	
										var tdFaxesPrompt = this.element( 'td', 'tdFaxesP', '_uicmp_frm_prompt' );
											tdFaxesPrompt.innerHTML = strings['faxes'];
										var tdFaxes = this.element( 'td', 'tdFaxes', '_uicmp_frm_field' );
											tdFaxes.setAttribute( 'colSpan', 3 );
											var inputFaxes = this.element( 'input', 'faxes', '_uicmp_frm_input' );


									tbodyContainer.appendChild( trFaxes );
										trFaxes.appendChild( tdFaxesPrompt );
										trFaxes.appendChild( tdFaxes );
											tdFaxes.appendChild( inputFaxes );

		parentEl.appendChild( tr );
	};
	
	this.destroy = function ( )
	{
		var parent = document.getElementById( parent_id );
		parent.removeChild( document.getElementById( me.id + '.tr' ) );		
	};
	
	/*
	 * Export instance specific part (its related data from the form) of XML
	 * document.
	 */
	this.xml = function ( writer )
	{
		writer.writeStartElement( 'address' );			// address
			writer.writeAttributeString( 'desc', Base64.encode( document.getElementById( me.id + '.desc' ).value ) );		// description
			writer.writeAttributeString( 'addr1', Base64.encode( document.getElementById( me.id + '.addr1' ).value ) );		// address line 1
			writer.writeAttributeString( 'addr2', Base64.encode( document.getElementById( me.id + '.addr2' ).value ) );		// address line 2
			writer.writeAttributeString( 'zip', Base64.encode( document.getElementById( me.id + '.zip' ).value ) );			// Zip code
			writer.writeAttributeString( 'city', Base64.encode( document.getElementById( me.id + '.city' ).value ) );		// city
			writer.writeAttributeString( 'country', Base64.encode( document.getElementById( me.id + '.country' ).value ) );	// country
			writer.writeAttributeString( 'phones', Base64.encode( document.getElementById( me.id + '.phones' ).value ) );	// phones
			writer.writeAttributeString( 'faxes', Base64.encode( document.getElementById( me.id + '.faxes' ).value ) );		// faxes
		writer.writeEndElement( );
	};
	
	/**
	 * Populates instance with data.
	 */
	this.set = function ( desc, addr1, addr2, zip, city, country, phones, faxes )
	{
		document.getElementById( me.id + '.desc' ).value	= desc;
		document.getElementById( me.id + '.addr1' ).value	= addr1;
		document.getElementById( me.id + '.addr2' ).value	= addr2;
		document.getElementById( me.id + '.zip' ).value		= zip;
		document.getElementById( me.id + '.city' ).value	= city;
		document.getElementById( me.id + '.country' ).value	= country;
		document.getElementById( me.id + '.phones' ).value	= phones;
		document.getElementById( me.id + '.faxes' ).value	= faxes;
	};
	
	this.build( );
}

/**
 * Common part of editors.
 */
function _uicmp_ab_frm ( )
{
	/**
	 * Copy scope.
	 */
	var me = this;
	
	/**
	 * HTML ID of my form.
	 */
	this.my_id = null;
	
	/**
	 * Indicates whether form is in edit mode. false value represents
	 * 'Add new contact' mode.
	 */
	this.editing = false;
	
	/**
	 * Array holding widgets for pre-typed information (phones, IM's, etc.).
	 */
	this.typed = new Array( );
	
	/**
	 * Custom data fields.
	 */
	this.custom = new Array( );
	
	/**
	 * Custom addresses.
	 */
	this.addresses = new Array( );
	
	/**
	 * Multidimensional associative array of strings.
	 */
	this.strings = null;
	
	/**
	 * Creates new pretyped contact detail field.
	 */
	this.typed_add = function ( )
	{
		this.typed[this.typed.length] = new _uicmp_ab_typed( this.my_id + '.typed_' + this.typed.length, this.my_id + '.typed', this, this.strings['types'], {text: this.strings['del_row'], method: 'del', style: '_uicmp_blue _uicmp_gi_close'} );
	};
	
	/**
	 * Deletes pretyped contact field given by its ID. This method is called
	 * from onClick() event of typed field instance itself.
	 */
	this.typed_del = function ( id )
	{
		for ( var i = 0; i < this.typed.length ; ++i )
		{
			if ( ( this.typed[i] != null ) && ( this.typed[i].id == id ) )
			{
				this.typed[i].destroy( );
				this.typed[i] = null;
			}
		}
	};
	
	/**
	 * Creates new custom added contact detail field.
	 */
	this.custom_add = function ( )
	{
		this.custom[this.custom.length] = new _uicmp_ab_custom( this.my_id + '.custom_' + this.custom.length, this.my_id + '.custom', this, {text: this.strings['del_row'], method: 'del', style: '_uicmp_blue _uicmp_gi_close'} );
	};
	
	/**
	 * Deletes custom contact field given by its ID. This method is called
	 * from onClick() event of custom field instance itself.
	 */
	this.custom_del = function ( id )
	{
		for ( var i = 0; i < this.custom.length ; ++i )
		{
			if ( ( this.custom[i] != null ) && ( this.custom[i].id == id ) )
			{
				this.custom[i].destroy( );
				this.custom[i] = null;
			}
		}
	};
	
	/**
	 * Creates new address field in the form.
	 */
	this.address_add = function ( )
	{
		this.addresses[this.addresses.length] = new _uicmp_ab_address( this.my_id + '.address_' + this.addresses.length, this.my_id + '.addresses', this, this.strings['address']['address'] + this.strings['address']['no'] + ( this.addresses.length + 1 ), this.strings['address']['field'], {text: this.strings['address']['del_address'], method: 'del', style: '_uicmp_blue _uicmp_gi_close'} );
	};
	
	/**
	 * Deletes address field given by its ID. This method is called
	 * from onClick() event of address field instance itself.
	 */
	this.address_del = function ( id )
	{
		for ( var i = 0; i < this.addresses.length ; ++i )
		{
			if ( ( this.addresses[i] != null ) && ( this.addresses[i].id == id ) )
			{
				this.addresses[i].destroy( );
				this.addresses[i] = null;
			}
		}
	};
	
	/**
	 * Performs reset on dynamically created form widgets.
	 */
	this.dyn_reset = function ( )
	{
		/**
		 * Erase dynamic widgets and create initial instances to control content.
		 */
		for ( var i = 0; i < this.typed.length ; ++i )
			if ( this.typed[i] != null )
			{
				this.typed[i].destroy( );
				this.typed[i] = null;
			}
		for ( var i = 0; i < this.custom.length ; ++i )
			if ( this.custom[i] != null )
			{
				this.custom[i].destroy( );
				this.custom[i] = null;
			}
		for ( var i = 0; i < this.addresses.length ; ++i )
			if ( this.addresses[i] != null )
			{
				this.addresses[i].destroy( );
				this.addresses[i] = null;
			}
		this.typed = new Array( );
		this.custom = new Array( );
		this.addresses = new Array( );
		
		this.typed[this.typed.length] = new _uicmp_ab_typed( this.my_id + '.typed_' + this.typed.length, this.my_id + '.typed', this, this.strings['types'], {text: this.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );
		this.custom[this.custom.length] = new _uicmp_ab_custom( this.my_id + '.custom_' + this.custom.length, this.my_id + '.custom', this, {text: this.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );
		this.addresses[this.addresses.length] = new _uicmp_ab_address( this.my_id + '.address_' + this.addresses.length, this.my_id + '.addresses', this, this.strings['address']['address'], this.strings['address']['field'], {text: this.strings['address']['add_address'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );
	};
	
	/**
	 * Sends actual height to Ajax server.
	 */
	this.tah = function ( height )
	{
		/**
		 * Copy me into this scope. Awkward, but works.
		 */
		var scope = this;

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
	
	/**
	 * Encodes dynamic widgets data into XML using XML writer.
	 */
	this.dyn_xml = function ( writer )
	{
		writer.writeStartElement( 'tfields' );			// typed fields
			for ( var i = 0; i < this.typed.length; ++i )
				if ( this.typed[i] != null )
					this.typed[i].xml( writer );
		writer.writeEndElement( );
		
		writer.writeStartElement( 'cfields' );			// custom fields
			for ( var i = 0; i < this.custom.length; ++i )
				if ( this.custom[i] != null )
					this.custom[i].xml( writer );
		writer.writeEndElement( );
					
		writer.writeStartElement( 'addresses' );		// addresses
			for ( var i = 0; i < this.addresses.length; ++i )
				if ( this.addresses[i] != null )
					this.addresses[i].xml( writer );
		writer.writeEndElement( );
	};
	
	this.dyn_parse = function ( global )
	{
		var i = 0;

		/*
		 * Pretyped fields. This is optional information.
		 */
		if ( global.getElementsByTagName( 'tfields' ).length > 0 )
		{
			var typed = global.getElementsByTagName( 'tfields' ).item( 0 );
		//	var tId = 1;				// partial id of first-non erasable widget
			var tField = null;
			for ( i = 0; i < typed.getElementsByTagName( 'field' ).length; i++ )
			{
				tField = typed.getElementsByTagName( 'field' ).item( i );
				
//				for ( j = ; j >= 0; --j )
//alert(Field.getAttribute( 'type' ).toString( ));
				if ( i != 0 )
					this.typed_add( );
				
				this.typed[this.typed.length - 1].set( tField.getAttribute( 'type' ).toString( ), tField.getAttribute( 'number' ).toString( ), tField.getAttribute( 'comment' ).toString( ) );
				
				//if ( i != 0 )
					//tId = frmAcPersonTypedFields.addTypedEmptyRow( true );

				//frmAcPersonTypedFields.setTypedData( tId, tField.getAttribute( 'type' ).toString( ), tField.getAttribute( 'number' ).toString( ), tField.getAttribute( 'comment' ).toString( ) );
			}
		}
//alert('a');
		/*
		 * Addresses. This is optional information.
		 */
		if ( global.getElementsByTagName( 'addresses' ).length > 0 )
		{
			var addresses = global.getElementsByTagName( 'addresses' ).item( 0 );
			//var aId = 1;				// partial id of first-non erasable widget
			var aField = null;
			for ( i = 0; i < addresses.getElementsByTagName( 'address' ).length; i++ )
			{
				aField = addresses.getElementsByTagName( 'address' ).item( i );
				
				if ( i != 0 )
					this.address_add( );
				
				this.addresses[this.addresses.length - 1].set( aField.getAttribute( 'desc' ).toString( ),
															aField.getAttribute( 'addr1' ).toString( ),
															aField.getAttribute( 'addr2' ).toString( ),
															aField.getAttribute( 'zip' ).toString( ),
															aField.getAttribute( 'city' ).toString( ),
															aField.getAttribute( 'country' ).toString( ),
															aField.getAttribute( 'phones' ).toString( ),
															aField.getAttribute( 'faxes' ).toString( ) );
				/*if ( i != 0 )
					aId = frmAcPersonAddresses.addAddress( true );

				frmAcPersonAddresses.setAddressData( aId, aField.getAttribute( 'desc' ).toString( ),
															aField.getAttribute( 'addr1' ).toString( ),
															aField.getAttribute( 'addr2' ).toString( ),
															aField.getAttribute( 'zip' ).toString( ),
															aField.getAttribute( 'city' ).toString( ),
															aField.getAttribute( 'country' ).toString( ),
															aField.getAttribute( 'phones' ).toString( ),
															aField.getAttribute( 'faxes' ).toString( ) );*/
			}
		}

		/*var elCtxs = display.getElementsByTagName( 'ctxs' ).item( 0 );
		var ctxId = 0;
			frmAcCtxCloud.on = new Object( );
			for ( i = 0; i < elCtxs.getElementsByTagName( 'ctx' ).length; i++ )
			{
				ctxId = Number( elCtxs.getElementsByTagName( 'ctx' ).item( i ).getFirstChild( ).getNodeValue( ) );
				frmAcCtxCloud.on[ctxId] = true;
				frmAcCtxCloud.colorize ( ctxId );
			}*/
	};
}

_uicmp_ab_perse.prototype = new _uicmp_ab_frm;

function _uicmp_ab_perse ( layout, tab_id, my_name, my_id, title_id, url, params, strings_id, ind )
{
	/**
	 * Copy scope;
	 */
	var me = this;
	
	/**
	 * Reference to layout instance.
	 */
	this.layout = layout;
	
	/**
	 * ID of UICMP tab component holding this form.
	 */
	this.tab_id = tab_id;
	
	/**
	 * Name of Javascript variable holding reference to this instance.
	 */
	this.my_name = my_name;
	
	/**
	 * HTML ID of my form.
	 */
	this.my_id = my_id;
	
	/**
	 * ID of tab title component.
	 */
	this.title_id = title_id;
	
	/**
	 * Ajax requests URL.
	 */
	this.url = url;
	
	/**
	 * Base set of Ajax request parameters.
	 */
	this.params = params;
	
	/**
	 * Array of format display names. Localized strings for no or empty data to
	 * be formatted and displayed.
	 */
	this.formats = null;
	
	/**
	 * HTML ID of UICMP strings data container.
	 */
	this.strings_id = strings_id;
	
	/**
	 * Indicator instance.
	 */
	this.ind = ind;
	
	/**
	 * Contact ID. should be 0 for new contact.
	 */
	this.person_id = 0;
	
	/**
	 * CDES cloud client side logic instance.
	 */
	this.cloud = new _uicmp_cdes_cloud( this.my_name + '.cloud', this.my_id + '.ctxs', this.url, this.params );
	
	this.startup = function ( )
	{
		var strings_prov = new _uicmp_strings( me.strings_id );
		me.strings = strings_prov.data;
		me.formats = me.strings['fmt'];
		
		var res_opts = new Object();
		res_opts.afterDrag = me.tah_save;

		new TextAreaResizer( document.getElementById( me.my_id + '.comments' ), res_opts );
		
		disableSelection( document.getElementById( me.my_id + '.pPredef' ) );
		disableSelection( document.getElementById( me.my_id + '.pBDay' ) );
	};
	
	/**
	 * Put form into initial state.
	 */
	this.reset = function ( )
	{
		this.person_id = 0;
		
		this.cloud.get( );

		document.getElementById( me.my_id + '.predef' ).checked = false;
		document.getElementById( me.my_id + '.format' ).selectedIndex = 0;
		document.getElementById( me.my_id + '.display' ).value = '';

		document.getElementById( me.my_id + '.nick' ).value = '';
		document.getElementById( me.my_id + '.titles' ).value = '';
		document.getElementById( me.my_id + '.first' ).value = '';
		document.getElementById( me.my_id + '.second' ).value = '';
		document.getElementById( me.my_id + '.anames' ).value = '';
		document.getElementById( me.my_id + '.surname' ).value = '';
		document.getElementById( me.my_id + '.ssurname' ).value = '';
		document.getElementById( me.my_id + '.asurnames' ).value = '';
		document.getElementById( me.my_id + '.comments' ).value = '';

		document.getElementById( me.my_id + '.bday' ).checked = false;
		
		this.bday_toggle( );
		this.predef_toggle( );
		
		this.dyn_reset( );
		/*this.typed[this.typed.length] = new _uicmp_ab_typed( me.my_id + '.typed_' + this.typed.length, me.my_id + '.typed', me, me.strings['types'], {text: me.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );
		this.custom[this.custom.length] = new _uicmp_ab_custom( me.my_id + '.custom_' + this.custom.length, me.my_id + '.custom', me, {text: me.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );
		this.addresses[this.addresses.length] = new _uicmp_ab_address( me.my_id + '.address_' + this.addresses.length, me.my_id + '.addresses', me, me.strings['address']['address'], me.strings['address']['field'], {text: me.strings['address']['add_address'], method: 'add', style: '_uicmp_blue _uicmp_gi_add'} );*/
	};
	
	/**
	 * Provides preformatted strings for display value of contact.
	 */
	this.format = function ( )
	{
		function load_atom( atoms, id ) {atoms[id]	= document.getElementById( me.my_id + '.' + id ).value;}
		
		/**
		 * Extract data.
		 */
		var atoms = new Object( );
			load_atom( atoms, 'nick' );
			load_atom( atoms, 'first' );
			load_atom( atoms, 'second' );
			load_atom( atoms, 'surname' );
			load_atom( atoms, 'ssurname' );
			
		/**
		 * Copy default values.
		 */
		var formatted = new Object( );
			for ( var i in this.formats )
				formatted[i] = this.formats[i];
				
		/*
		 * 0: nick [FirstName Surname]
		 */
		if ( atoms['nick'] != '' || atoms['first'] != '' || atoms['surname'] != '' )
			formatted[0] = atoms['nick'] + ( ( atoms['nick'] != '' ) ? ' ' : '' ) + ( ( atoms['first'] != '' || atoms['surname'] != '' ) ? '[' + atoms['first'] + ( ( atoms['first'] !='' && atoms['surname'] != '' ) ? ' ' : '' ) + atoms['surname'] + ']' : '' );

		/*
		 * 10: FirstName Surname
		 */
		if ( atoms['first'] != '' || atoms['surname'] != '' )
			formatted[10] = atoms['first'] + ( ( atoms['surname'] != '' ) ? ' ' : '' ) + atoms['surname'];

		/*
		 * 20: Surname FirstName
		 */
		if ( atoms['first'] != '' || atoms['surname'] != '' )
			formatted[20] = atoms['surname'] + ( ( atoms['first'] != '' ) ? ' ' : '' ) + atoms['first'];

		/*
		 * 30: FirstName SecondName Surname
		 */
		if ( atoms['first'] != '' || atoms['second'] != '' || atoms['surname'] != '' )
			formatted[30] = atoms['first'] + ( ( atoms['second'] != '' ) ? ' ' : '' ) + atoms['second']
										+ ( ( atoms['surname'] != '' ) ? ' ' : '' ) + atoms['surname'];

		/*
		 * 40: Surname, FirstName SecondName
		 */
		if ( atoms['first'] != '' || atoms['second'] != '' || atoms['surname'] != '' )
			formatted[40] = ( ( atoms['surname'] != '' ) ? atoms['surname'] + ', ' : '' )
								+ ( ( atoms['first'] != '' ) ? ' ' : '' ) + atoms['first']
								+ ( ( atoms['second'] != '' ) ? ' ' : '' ) + atoms['second'];

		/*
		 * 50: FirstName Surname-SecondSurname
		 */
		if ( atoms['first'] != '' || atoms['ssurname'] != '' || atoms['surname'] != '' )
			formatted[50] = atoms['first'] + ( ( atoms['surname'] != '' ) ? ' ' : '' ) + atoms['surname']
										+ ( ( atoms['ssurname'] != '' && atoms['surname'] != '' ) ? '-' : ' ' ) + atoms['ssurname'];

		/*
		 * 60: FirstName SecondName Surname-SecondSurname
		 */
		if ( atoms['first'] != '' || atoms['second'] != '' || atoms['ssurname'] != '' || atoms['surname'] != '' )
			formatted[60] = atoms['first'] + ( ( atoms['second'] != '' ) ? ' ' : '' ) + atoms['second']
										+ ( ( atoms['surname'] != '' ) ? ' ' : '' ) + atoms['surname']
										+ ( ( atoms['ssurname'] != '' && atoms['surname'] != '' ) ? '-' : ' ' ) + atoms['ssurname'];

		/*
		 * 70: Surname-SecondSurname, FirstName SecondName
		 */
		if ( atoms['first'] != '' || atoms['second'] != '' || atoms['ssurname'] != '' || atoms['surname'] != '' )
			formatted[70] = atoms['surname'] + ( ( atoms['ssurname'] != '' && atoms['surname'] != '' ) ? '-' : '' ) + atoms['ssurname']
								+ ( ( ( atoms['surname'] != '' || atoms['ssurname'] != '' ) && ( atoms['first'] != '' || atoms['second'] != '' ) ) ? ', ' : '' )
								+ atoms['first'] + ( ( atoms['second'] != '' ) ? ' ' : '' ) + atoms['second'];
										
		return formatted;
	};
	
	/**
	 * Previews display name according to data set in the form and return its
	 * value.
	 */
	this.preview = function ( )
	{		
		var display = '';
		
		if ( document.getElementById( me.my_id + '.predef' ).checked == true )
		{
			/**
			 * Extract pre-formatted values.
			 */
			var formatted = this.format( );
			
			/**
			 * Wipe and repopulate select box.
			 */
			var select = document.getElementById( me.my_id + '.format' );
			var index = select.selectedIndex;
			
			for ( i = select.length - 1; i>=0; i-- )
				select.remove(i);
			
			for ( var value in formatted )
				_uicmp_ab_opt_add( select, formatted[value], value );
			
			select.selectedIndex = index;
			display = select.options[index].innerHTML;
		}
		else
		{
			display = document.getElementById( me.my_id + '.display' ).value;
		}
		
		var caption = ( this.editing ) ? this.strings['edit'] : this.strings['create'];
		caption += ' <i>' + display + '</i>';
		document.getElementById( me.title_id ).innerHTML = caption;
		
		return display;
	};
	
	/**
	 * Put form into Adding new person mode.
	 */
	this.add = function ( )
	{
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.layout.show( this.tab_id );
		this.editing = false;
		this.reset( );
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
	};
	
	/**
	 * Put form into Editing new person mode.
	 */
	this.edit = function ( id )
	{
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.layout.show( this.tab_id );
		this.editing = true;
		this.reset( );
		this.open( id );
		this.person_id = id;
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
	};
	
	this.get_types = function ( )
	{
		
	};
	
	/**
	 * Renders widgets for predefined or typed display name.
	 */
	this.predef_toggle = function ( )
	{
		var predef = false;
		if ( document.getElementById( me.my_id + '.predef' ).checked == true )
		{
			document.getElementById( me.my_id + '.display' ).className = '_uicmp_ab_hidden';
			document.getElementById( me.my_id + '.format' ).className = '_uicmp_frm_input';
			predef = true;
		}
		else
		{
			document.getElementById( me.my_id + '.display' ).className = '_uicmp_frm_input';
			document.getElementById( me.my_id + '.format' ).className = '_uicmp_ab_hidden';
		}
		
		this.preview( );
		return predef;
	};
	
	/**
	 * Updates disability status on birthday SELECTs and provides value of
	 * checkbox checked state.
	 */
	this.bday_toggle = function ( )
	{
		var checked = false;

		if ( document.getElementById( me.my_id + '.bday' ).checked == true )
			checked = true;
		else
			checked = false;

		document.getElementById( me.my_id + '.year' ).disabled = ( checked == false );
		document.getElementById( me.my_id + '.month' ).disabled = ( checked == false );
		document.getElementById( me.my_id + '.day' ).disabled = ( checked == false );
		
		return checked;
	};
	
	this.bday_check = function ( )
	{
		var day = document.getElementById( me.my_id + '.day' )[document.getElementById( me.my_id + '.day' ).selectedIndex].value;
		var month = document.getElementById( me.my_id + '.month' )[document.getElementById( me.my_id + '.month' ).selectedIndex].value;
		var year = document.getElementById( me.my_id + '.year' )[document.getElementById( me.my_id + '.year' ).selectedIndex].value;

		year = Number( year );
		month = Number( month ) - 1;
		day = Number( day );

		/*
		 * Months are in Javascript indexed from 0 (january).
		 */
		var input = new Date( year, month, day );

		return ( ( input.getFullYear() == year ) && ( input.getMonth() == month ) && ( input.getDate() == day ) );
	};
	
	this.bday_set = function ( day, month, year )
	{
		var dayEl = document.getElementById( me.my_id + '.day' );
		for ( i = 0; i < dayEl.options.length; i++ )
			if ( dayEl.options[i].value == day ) { dayEl.selectedIndex = i;	break; }

		/*
		 * date.getMonth() result is indexed from 0 (=January).
		 */
		var monthEl = document.getElementById( me.my_id + '.month' );
		for ( i = 0; i < monthEl.options.length; i++ )
			if ( Number( monthEl.options[i].value ) == month ) { monthEl.selectedIndex = i;	break; }

		var yearEl = document.getElementById( me.my_id + '.year' );
		for ( i = 0; i < yearEl.options.length; i++ )
			if ( yearEl.options[i].value == year ) { yearEl.selectedIndex = i;	break; }
	};
	
	/**
	 * Calls parent method to save height of textarea.
	 */
	this.tah_save = function( )
	{
		/**
		 * Get actuall textarea height.
		 */
		var el = document.getElementById( me.my_id + '.comments' );
		var height = el.getHeight( );
		me.tah( height );
	};
	
	this.save = function ( )
	{
		var bday = this.bday_toggle( );
		
		if ( ( bday ) && ( this.bday_check( ) === false ) )
		{
			this.ind.show( 'e_bday', '_uicmp_ind_red');
			return;
		}

		this.ind.show( 'saving', '_uicmp_ind_gray');

		writer = new XMLWriter( 'UTF-8', '1.0' );

		writer.writeStartDocument( false );
			writer.writeStartElement( 'person' )

				writer.writeStartElement( 'global' );			// global
					writer.writeAttributeString( 'personId', ( this.person_id != null ) ? this.person_id : '' );

					writer.writeStartElement( 'display' );			// display name
						writer.writeAttributeString( 'predefined', ( ( this.predef_toggle( ) === true ) ? 'true' : 'false' ) );		// usepredefined
						writer.writeAttributeString( 'format',document.getElementById( me.my_id + '.format' )[document.getElementById( me.my_id + '.format' ).selectedIndex].value );		// format of predefined display name
						writer.writeAttributeString( 'custom', Base64.encode( document.getElementById( me.my_id + '.display' ).value ) );		// custom display name
						me.cloud.write( writer );
					writer.writeEndElement( );

					writer.writeStartElement( 'personal' );			// personal data
						writer.writeAttributeString( 'nick', Base64.encode( document.getElementById( me.my_id + '.nick' ).value ) );						// nickname
						writer.writeAttributeString( 'titles', Base64.encode( document.getElementById( me.my_id + '.titles' ).value ) );						// titles
						writer.writeAttributeString( 'firstname', Base64.encode( document.getElementById( me.my_id + '.first' ).value ) );					// first name
						writer.writeAttributeString( 'secondname', Base64.encode( document.getElementById( me.my_id + '.second' ).value ) );				// second name
						writer.writeAttributeString( 'anothernames', Base64.encode( document.getElementById( me.my_id + '.anames' ).value ) );			// another names
						writer.writeAttributeString( 'surname', Base64.encode( document.getElementById( me.my_id + '.surname' ).value ) );						// surname
						writer.writeAttributeString( 'secondsurname', Base64.encode( document.getElementById( me.my_id + '.ssurname' ).value ) );			// second surname
						writer.writeAttributeString( 'anothersurnames', Base64.encode( document.getElementById( me.my_id + '.asurnames' ).value ) );		// another surnames

						writer.writeElementString( 'comments', Base64.encode( document.getElementById( me.my_id + '.comments' ).value ) );

						writer.writeStartElement( 'birthday' );			// birthday
							writer.writeAttributeString( 'known', ( ( bday === true ) ? 'true' : 'false' ) );				// is birthday known
							writer.writeAttributeString( 'day', document.getElementById( me.my_id + '.day' )[document.getElementById( me.my_id + '.day' ).selectedIndex].value );				// birthday day
							writer.writeAttributeString( 'month', document.getElementById( me.my_id + '.month' )[document.getElementById( me.my_id + '.month' ).selectedIndex].value );		// birthday month
							writer.writeAttributeString( 'year', document.getElementById( me.my_id + '.year' )[document.getElementById( me.my_id + '.year' ).selectedIndex].value );			// birthday year
						writer.writeEndElement( );

					writer.writeEndElement( );

					this.dyn_xml( writer );

				writer.writeEndElement( );

			writer.writeEndElement( );
		writer.writeEndDocument( );

		var data = waPlusSignWaEncode( writer.flush() );

		writer.close( );
		
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

		reqParams += '&method=save';

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										postBody: reqParams + '&data=' + data,
										onCreate: function ( ) {scope.ind.show( 'saving', '_uicmp_ind_gray' );},
										onFailure: function ( )
										{
										//	me.enable( );
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data )
										{
											//alert(data.responseText);
											//scope.folds.update( );
											//me.enable( );
											scope.ind.fade( 'saved', '_uicmp_ind_green' );
											//if ( ( document.getElementById( scope.chk_id + '.box' ).checked ) || ( scope.mode != 'C' ) )
												scope.layout.back( );
										}
									}
								);
		return sender;
	};
	
	this.open = function ( id )
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
					 '&id=' + id;

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										onCreate: function ( ) {scope.ind.show( 'loading', '_uicmp_ind_gray' );},
										onFailure: function ( )
										{
										//	me.enable( );
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data )
										{
											//alert(data.responseText);
											//scope.folds.update( );
											//me.enable( );
											scope.parse( data.responseText );
											scope.ind.fade( 'loaded', '_uicmp_ind_green' );
											//if ( ( document.getElementById( scope.chk_id + '.box' ).checked ) || ( scope.mode != 'C' ) )
												//scope.layout.back( );
										}
									}
								);
		return sender;
	};
	
	this.parse = function ( xml )
	{
		var parser = new DOMImplementation( );
		var domDoc = parser.loadXML( xml );
			var docRoot = domDoc.getDocumentElement( );
				var global = docRoot.getElementsByTagName( 'global' ).item( 0 );
				frmAcPersonId = Number( global.getAttribute( 'personId' ) );

					var display = global.getElementsByTagName( 'display' ).item( 0 );

					if ( ( display.getAttribute( 'predefined' ).toString( ) ) == 'true' )
						document.getElementById( me.my_id + '.predef' ).click();

					document.getElementById( me.my_id + '.display' ).value = display.getAttribute( 'custom' ).toString( );

					var selEl = document.getElementById( me.my_id + '.format' );
					selEl.selectedIndex = 0;
					for ( i = 0; i < selEl.length; i++ )
					{
						if ( selEl[i].value == Number( display.getAttribute( 'format' ) ) )
						{
							selEl.selectedIndex = i;
							break;
						}
					}

					var personal = global.getElementsByTagName( 'personal' ).item( 0 );
					document.getElementById( me.my_id + '.nick' ).value = personal.getAttribute( 'nick' ).toString( );
					document.getElementById( me.my_id + '.titles' ).value = personal.getAttribute( 'titles' ).toString( );
					document.getElementById( me.my_id + '.first' ).value = personal.getAttribute( 'firstname' ).toString( );
					document.getElementById( me.my_id + '.second' ).value = personal.getAttribute( 'secondname' ).toString( );
					document.getElementById( me.my_id + '.anames' ).value = personal.getAttribute( 'anothernames' ).toString( );
					document.getElementById( me.my_id + '.surname' ).value = personal.getAttribute( 'surname' ).toString( );
					document.getElementById( me.my_id + '.ssurname' ).value = personal.getAttribute( 'secondsurname' ).toString( );
					document.getElementById( me.my_id + '.asurnames' ).value = personal.getAttribute( 'anothersurnames' ).toString( );

						var comments = personal.getElementsByTagName( 'comments' ).item( 0 );
						if ( comments.getFirstChild( ) != null )
							document.getElementById( me.my_id + '.comments' ).value = comments.getFirstChild( ).getNodeValue( );

					me.preview( );

					var birthday = global.getElementsByTagName( 'birthday' ).item( 0 );
					if ( ( birthday.getAttribute( 'known' ).toString( ) ) == 'true' )
						document.getElementById( me.my_id + '.bday' ).click();
					me.bday_set( Number( birthday.getAttribute( 'day' ) ), Number( birthday.getAttribute( 'month' ) ), Number( birthday.getAttribute( 'year' ) ) );
					
		this.dyn_parse( global );
		
		var elCtxs = display.getElementsByTagName( 'ctxs' ).item( 0 );
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
}

_uicmp_ab_orge.prototype = new _uicmp_ab_frm;

function _uicmp_ab_orge ( layout, tab_id, my_name, my_id, title_id, url, params, strings_id, ind )
{
	/**
	 * Copy scope;
	 */
	var me = this;
	
	/**
	 * Reference to layout instance.
	 */
	this.layout = layout;
	
	/**
	 * ID of UICMP tab component holding this form.
	 */
	this.tab_id = tab_id;
	
	/**
	 * Name of Javascript variable holding reference to this instance.
	 */
	this.my_name = my_name;
	
	/**
	 * HTML ID of my form.
	 */
	this.my_id = my_id;
	
	/**
	 * ID of tab title component.
	 */
	this.title_id = title_id;
	
	/**
	 * Ajax requests URL.
	 */
	this.url = url;
	
	/**
	 * Base set of Ajax request parameters.
	 */
	this.params = params;
	
	/**
	 * HTML ID of UICMP strings data container.
	 */
	this.strings_id = strings_id;
	
	/**
	 * Indicator instance.
	 */
	this.ind = ind;
	
	/**
	 * Contact ID. should be 0 for new contact.
	 */
	this.org_id = 0;
	
	/**
	 * CDES cloud client side logic instance.
	 */
	this.cloud = new _uicmp_cdes_cloud( this.my_name + '.cloud', this.my_id + '.ctxs', this.url, this.params );
	
	this.startup = function ( )
	{
		var strings_prov = new _uicmp_strings( me.strings_id );
		me.strings = strings_prov.data;
		
		var res_opts = new Object();
		res_opts.afterDrag = me.tah_save;

		new TextAreaResizer( document.getElementById( me.my_id + '.comments' ), res_opts );
		
		/*disableSelection( document.getElementById( me.my_id + '.pPredef' ) );
		disableSelection( document.getElementById( me.my_id + '.pBDay' ) );*/
	};
	
	this.reset = function ( )
	{
		this.org_id = 0;
		
		this.cloud.get( );

		document.getElementById( me.my_id + '.dispName' ).value = '';
		document.getElementById( me.my_id + '.name' ).value = '';
		document.getElementById( me.my_id + '.comments' ).value = '';

		document.getElementById( me.my_id + '.disp' ).checked = false;
		this.disp_toggle( );
		
		this.dyn_reset( );
	};
	
	/**
	 * Put form into Adding new person mode.
	 */
	this.add = function ( )
	{
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.layout.show( this.tab_id );
		this.editing = false;
		this.reset( );
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
	};
	
	/**
	 * Put form into Editing new person mode.
	 */
	this.edit = function ( id )
	{
		this.ind.show( 'preparing', '_uicmp_ind_gray' );
		this.layout.show( this.tab_id );
		this.editing = true;
		this.reset( );
		this.open( id );
		this.org_id = id;
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
	};
	
	this.preview = function ( )
	{
		var display = '';
		
		if ( document.getElementById( me.my_id + '.disp' ).checked == true )
			display = document.getElementById( me.my_id + '.dispName' ).value;
		else
			display = document.getElementById( me.my_id + '.name' ).value;
		
		var caption = ( this.editing ) ? this.strings['edit'] : this.strings['create'];
		caption += ' <i>' + display + '</i>';
		document.getElementById( me.title_id ).innerHTML = caption;
		
		return display;
	};
	
	this.disp_toggle = function ( )
	{
		var checked = document.getElementById( me.my_id + '.disp' ).checked;
/*		if ( checked )
		{*/
			document.getElementById( me.my_id + '.dispName' ).disabled = !checked;
		/*}
		else
		{	
			document.getElementById( me.my_id + '.disp' ).disabled = true;
		}*/
		me.preview( );
		
		return checked;
	};
	
	this.tah_save = function ( )
	{
		/**
		 * Get actuall textarea height.
		 */
		var el = document.getElementById( me.my_id + '.comments' );
		var height = el.getHeight( );
		me.tah( height );
	};
	
	this.save = function ( )
	{
		this/ind.show( 'saving', '_uicmp_ind_gray' );
		var disp = this.disp_toggle( );
		//frmAcompStatusIndicatorShow( 'gtdEditorStatus', __Msg['editorStatusSaving'] );
	
		writer = new XMLWriter( 'UTF-8', '1.0' );

		writer.writeStartDocument( false );
			writer.writeStartElement( 'company' )

				writer.writeStartElement( 'global' );			// global
					writer.writeAttributeString( 'companyId', ( me.org_id != null ) ? me.org_id : '' );

					writer.writeStartElement( 'general' );			// display names, comments, etc.
						writer.writeAttributeString( 'display', ( ( disp === true ) ? 'true' : 'false' ) );			// use predefined
						writer.writeAttributeString( 'displayName',Base64.encode( document.getElementById( me.my_id + '.dispName' ).value ) );	// predefined name string
						writer.writeAttributeString( 'name', Base64.encode( document.getElementById( me.my_id + '.name' ).value ) );					// full name
						writer.writeElementString( 'comments', Base64.encode( document.getElementById( me.my_id + '.comments' ).value ) );
						me.cloud.write( writer );
					writer.writeEndElement( );

					this.dyn_xml( writer );

				writer.writeEndElement( );

			writer.writeEndElement( );
		writer.writeEndDocument( );

		var data = waPlusSignWaEncode( writer.flush() );

		writer.close( );

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

		reqParams += '&method=save';

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										postBody: reqParams + '&data=' + data,
										onCreate: function ( ) {scope.ind.show( 'saving', '_uicmp_ind_gray' );},
										onFailure: function ( )
										{
										//	me.enable( );
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data )
										{
										//	alert(data.responseText);
											//scope.folds.update( );
											//me.enable( );
											scope.ind.fade( 'saved', '_uicmp_ind_green' );
											//if ( ( document.getElementById( scope.chk_id + '.box' ).checked ) || ( scope.mode != 'C' ) )
												scope.layout.back( );
										}
									}
								);
		return sender;
	};
	
	this.open = function ( id )
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
					 '&id=' + id;

		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										onCreate: function ( ) {scope.ind.show( 'loading', '_uicmp_ind_gray' );},
										onFailure: function ( )
										{
										//	me.enable( );
											scope.ind.show( 'e_unknown', '_uicmp_ind_red' );
										},
										onSuccess: function ( data )
										{
											//alert(data.responseText);
											//scope.folds.update( );
											//me.enable( );
											scope.parse( data.responseText );
											scope.ind.fade( 'loaded', '_uicmp_ind_green' );
											//if ( ( document.getElementById( scope.chk_id + '.box' ).checked ) || ( scope.mode != 'C' ) )
												//scope.layout.back( );
										}
									}
								);
		return sender;
	};
	
	this.parse = function ( xml )
	{
		var parser = new DOMImplementation( );
		var domDoc = parser.loadXML( xml );
			var docRoot = domDoc.getDocumentElement( );
				var global = docRoot.getElementsByTagName( 'global' ).item( 0 );
				frmAcompCompanyId = Number( global.getAttribute( 'companyId' ) );

					var display = global.getElementsByTagName( 'general' ).item( 0 );

					if ( ( display.getAttribute( 'display' ).toString( ) ) == 'true' )
						document.getElementById( me.my_id + '.disp' ).click();

					document.getElementById( me.my_id + '.dispName' ).value = display.getAttribute( 'displayName' ).toString( );
					document.getElementById( me.my_id + '.name' ).value = display.getAttribute( 'name' ).toString( );

					var comments = display.getElementsByTagName( 'comments' ).item( 0 );

					if ( comments.getFirstChild( ) != null )
							document.getElementById( me.my_id + '.comments' ).value = comments.getFirstChild( ).getNodeValue( );

					me.preview( );
					
		this.dyn_parse( global );
		
		var elCtxs = display.getElementsByTagName( 'ctxs' ).item( 0 );
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
	
}
