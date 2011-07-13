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

		var tr = document.createElement( 'tr' );
			tr.id = me.id + '.tr';

			var tdType = document.createElement( 'td' );
				tdType.id = me.id + '.td_type';
				tdType.className = '_uicmp_frm_field_1st';

				var selectType = document.createElement( 'select' );
					selectType.id = me.id + '.type';
					selectType.className = '_uicmp_frm_input';

			var tdNumber = document.createElement( 'td' );
				tdNumber.id = 'frmAcTfTdNumber' + this.commonId;
				tdNumber.className = '_uicmp_frm_field_rest';

				var inputNumber = document.createElement( 'input' );
					inputNumber.id = me.id + '.number';
					inputNumber.className = '_uicmp_frm_input';

			var tdComment = document.createElement( 'td' );
				tdComment.id = 'frmAcTfTdComment' + this.commonId;
				tdComment.className = '_uicmp_frm_field_rest';

				var inputComment = document.createElement( 'input' );
					inputComment.id = me.id + '.comment';
					inputComment.className = '_uicmp_frm_input';

			var tdErase = document.createElement( 'td' );
				tdErase.id = 'frmAcTfTdErase' + this.commonId;
				tdErase.className = '_uicmp_frm_field_rest _uicmp_fri';

				var divErase = document.createElement( 'div' );
					divErase.id = me.id + '.div';
					divErase.className = me.action.style;
					if ( me.action.method == 'del' )
						divErase.onclick = function( ) { me.ctrl.typed_del( me.id ); };
					else if ( me.action.method == 'add' )
						divErase.onclick = function( ) { me.ctrl.typed_add( ); };
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
	
	this.build( );
	this.populate( );
}

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

		var tr = document.createElement( 'tr' );
			tr.id = me.id + '.tr';

			var tdType = document.createElement( 'td' );
				tdType.id = me.id + '.td_type';
				tdType.className = '_uicmp_frm_field_1st';

				var inputName = document.createElement( 'input' );
					inputName.id = me.id + '.name';
					inputName.className = '_uicmp_frm_input';

			var tdNumber = document.createElement( 'td' );
				tdNumber.id = 'frmAcTfTdNumber' + this.commonId;
				tdNumber.className = '_uicmp_frm_field_rest';

				var inputNumber = document.createElement( 'input' );
					inputNumber.id = me.id + '.number';
					inputNumber.className = '_uicmp_frm_input';

			var tdComment = document.createElement( 'td' );
				tdComment.id = 'frmAcTfTdComment' + this.commonId;
				tdComment.className = '_uicmp_frm_field_rest';

				var inputComment = document.createElement( 'input' );
					inputComment.id = me.id + '.comment';
					inputComment.className = '_uicmp_frm_input';

			var tdErase = document.createElement( 'td' );
				tdErase.id = 'frmAcTfTdErase' + this.commonId;
				tdErase.className = '_uicmp_frm_field_rest _uicmp_fri';

				var divErase = document.createElement( 'div' );
					divErase.id = me.id + '.div';
					divErase.className = me.action.style;
					if ( me.action.method == 'del' )
						divErase.onclick = function( ) { me.ctrl.custom_del( me.id ); };
					else if ( me.action.method == 'add' )
						divErase.onclick = function( ) { me.ctrl.custom_add( ); };
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

		var tr = document.createElement( 'tr' );
			tr.id = me.id + '.tr';

			var td = document.createElement( 'td' );
				td.id = me.id + '.td';
				td.setAttribute( 'colSpan' , 2 );

				var table = document.createElement( 'table' );
					table.id = me.id + '.table';
					table.setAttribute( 'cellspacing' , '0' );
					table.setAttribute( 'cellpadding' , '0' );
					table.setAttribute( 'width' , '100%' );

					var tbody = document.createElement( 'tbody' );
						tbody.id = me.id + '.tbody';

					var trCap = document.createElement( 'tr' );
						trCap.id = me.id + '.trCap';

						var tdCap = document.createElement( 'td' );
							tdCap.id = me.id + '.tdCap';
							tdCap.setAttribute( 'colSpan' , 2 );
							tdCap.className = '_uicmp_frm_hdr';
							
							var table_cap = document.createElement( 'table' );
								table_cap.id = me.id + '.table_cap';
								table_cap.setAttribute( 'cellspacing' , '0' );
								table_cap.setAttribute( 'cellpadding' , '0' );
								
								var tbody_cap = document.createElement( 'tbody' );
									tbody_cap.id = me.id + '.tbody_cap';
								
									var tr_cap = document.createElement( 'tr' );
										tr_cap.id = me.id + '.tr_cap';
										
										var td_cap = document.createElement( 'td' );
											td_cap.id = me.id + '.td_cap';
											td_cap.className = '_uicmp_frm_hdr_item';
											
											var div_cap = document.createElement( 'div' );
												div_cap.id = me.id + '.div_cap';
												div_cap.className = '_uicmp_frm_hdr_cap';
												div_cap.innerHTML = this.display;
												
										var td_erase = document.createElement( 'td' );
											td_erase.id = me.id + '.td_erase';
											td_erase.className = '_uicmp_frm_hdr_item';
											
											var div_erase = document.createElement( 'div' );
												div_erase.id = me.id + '.div_erase';
												div_erase.className = action.style;
												div_erase.innerHTML = action.text;
												if ( action.method == 'add' )
													div_erase.onclick = function ( ) { me.ctrl.address_add( ); };
												else if ( action.method == 'del' )
													div_erase.onclick = function ( ) { me.ctrl.address_del( me.id ); };

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

					var trContainer = document.createElement( 'tr' );
						trContainer.id = me.id + '.trContainer';

						var tdContainer = document.createElement( 'td' );
							tdContainer.id = me.id + '.tdContainer';
							tdContainer.setAttribute( 'colSpan' , 2 );
							tdContainer.className = '_uicmp_body_frm';

							var tableContainer = document.createElement( 'table' );
								tableContainer.id = me.id + '.tableContainer';
								tableContainer.setAttribute( 'cellspacing' , '0' );
								tableContainer.setAttribute( 'cellpadding' , '0' );
								tableContainer.setAttribute( 'width' , '100%' );

								var tbodyContainer = document.createElement( 'tbody' );
									tbodyContainer.id = me.id + '.tbodyContainer';

					tbody.appendChild( trContainer );
						trContainer.appendChild( tdContainer );
							tdContainer.appendChild( tableContainer );
								tableContainer.appendChild( tbodyContainer );

									/* Description field */
									var trDesc = document.createElement( 'tr' );
										trDesc.id = me.id + '.trDesc';

										var tdDescPrompt = document.createElement( 'td' );
											tdDescPrompt.id = me.id + '.tdDescP';
											tdDescPrompt.className = '_uicmp_frm_prompt';
											tdDescPrompt.innerHTML = strings['comment'];

										var tdDesc = document.createElement( 'td' );
											tdDesc.id = me.id + '.tdDesc';
											tdDesc.className = '_uicmp_frm_field';
											tdDesc.setAttribute( 'colSpan', 3 );

											var inputDesc = document.createElement( 'input' );
												inputDesc.id = me.id + '.desc';
												inputDesc.className = '_uicmp_frm_input';


									tbodyContainer.appendChild( trDesc );
										trDesc.appendChild( tdDescPrompt );
										trDesc.appendChild( tdDesc );
											tdDesc.appendChild( inputDesc );

									/* Address lines */
									var trAddr1 = document.createElement( 'tr' );
										trAddr1.id = me.id + '.trAddr1';

										var tdAddr1Prompt = document.createElement( 'td' );
											tdAddr1Prompt.id = me.id + '.tdAddr1P';
											tdAddr1Prompt.className = '_uicmp_frm_prompt';
											tdAddr1Prompt.innerHTML = strings['address'];

										var tdAddr1 = document.createElement( 'td' );
											tdAddr1.id = me.id + '.tdAddr1';
											tdAddr1.className = '_uicmp_frm_field';
											tdAddr1.setAttribute( 'colSpan', 3 );

											var inputAddr1 = document.createElement( 'input' );
												inputAddr1.id = me.id + '.addr1';
												inputAddr1.className = '_uicmp_frm_input';

									var trAddr2 = document.createElement( 'tr' );
										trAddr2.id = me.id + '.trAddr2';

										var tdAddr2Prompt = document.createElement( 'td' );
											tdAddr2Prompt.id = me.id + '.tdAddr2P';

										var tdAddr2 = document.createElement( 'td' );
											tdAddr2.id = me.id + '.tdAddr2';
											tdAddr2.className = '_uicmp_frm_field';
											tdAddr2.setAttribute( 'colSpan', 3 );

											var inputAddr2 = document.createElement( 'input' );
												inputAddr2.id = me.id + '.addr2';
												inputAddr2.className = '_uicmp_frm_input';


									tbodyContainer.appendChild( trAddr1 );
										trAddr1.appendChild( tdAddr1Prompt );
										trAddr1.appendChild( tdAddr1 );
											tdAddr1.appendChild( inputAddr1 );

									tbodyContainer.appendChild( trAddr2 );
										trAddr2.appendChild( tdAddr2Prompt );
										trAddr2.appendChild( tdAddr2 );
											tdAddr2.appendChild( inputAddr2 );

									/* Zip code and City */
									var trZipCity = document.createElement( 'tr' );
										trZipCity.id = me.id + '.trZip';

										var tdZipPrompt = document.createElement( 'td' );
											tdZipPrompt.id = me.id + '.tdZipP';
											tdZipPrompt.className = '_uicmp_frm_prompt';
											tdZipPrompt.innerHTML = strings['zip'];

										var tdZip = document.createElement( 'td' );
											tdZip.id = me.id + '.tdZip';
											tdZip.className = '_uicmp_frm_field_1st';
											tdZip.setAttribute( 'width', '10%' );

											var inputZip = document.createElement( 'input' );
												inputZip.id = me.id + '.zip';
												inputZip.className = '_uicmp_frm_input';
												//inputZip.setAttribute( 'size', 20 );

										var tdCityPrompt = document.createElement( 'td' );
											tdCityPrompt.id = me.id + '.tdCityP';
											tdCityPrompt.className = '_uicmp_frm_prompt';
											tdCityPrompt.innerHTML = strings['city'];
											tdCityPrompt.setAttribute( 'width', '1%' );

										var tdCity = document.createElement( 'td' );
											tdCity.id = me.id + '.tdCity';
											tdCity.className = '_uicmp_frm_field_rest';
											tdCity.setAttribute( 'width', '100%' );

											var inputCity = document.createElement( 'input' );
												inputCity.id = me.id + '.city';
												inputCity.className = '_uicmp_frm_input';


									tbodyContainer.appendChild( trZipCity );
										trZipCity.appendChild( tdZipPrompt );
										trZipCity.appendChild( tdZip );
											tdZip.appendChild( inputZip );
										trZipCity.appendChild( tdCityPrompt );
										trZipCity.appendChild( tdCity );
											tdCity.appendChild( inputCity );

									/* Country field */
									var trCountry = document.createElement( 'tr' );
										trCountry.id = me.id + '.trCountry';

										var tdCountryPrompt = document.createElement( 'td' );
											tdCountryPrompt.id = me.id + '.tdCountryP';
											tdCountryPrompt.className = '_uicmp_frm_prompt';
											tdCountryPrompt.innerHTML = strings['country'];

										var tdCountry = document.createElement( 'td' );
											tdCountry.id = me.id + '.tdCountry';
											tdCountry.className = '_uicmp_frm_field';
											tdCountry.setAttribute( 'colSpan', 3 );

											var inputCountry = document.createElement( 'input' );
												inputCountry.id = me.id + '.country';
												inputCountry.className = '_uicmp_frm_input';


									tbodyContainer.appendChild( trCountry );
										trCountry.appendChild( tdCountryPrompt );
										trCountry.appendChild( tdCountry );
											tdCountry.appendChild( inputCountry );

									/* Phone(s) field */
									var trPhones = document.createElement( 'tr' );
										trPhones.id = me.id + '.trPhones';

										var tdPhonesPrompt = document.createElement( 'td' );
											tdPhonesPrompt.id = me.id + '.tdPhonesP';
											tdPhonesPrompt.className = '_uicmp_frm_prompt';
											tdPhonesPrompt.innerHTML = strings['phones'];

										var tdPhones = document.createElement( 'td' );
											tdPhones.id = me.id + '.tdPhones';
											tdPhones.className = '_uicmp_frm_field';
											tdPhones.setAttribute( 'colSpan', 3 );

											var inputPhones = document.createElement( 'input' );
												inputPhones.id = me.id + '.phones';
												inputPhones.className = '_uicmp_frm_input';


									tbodyContainer.appendChild( trPhones );
										trPhones.appendChild( tdPhonesPrompt );
										trPhones.appendChild( tdPhones );
											tdPhones.appendChild( inputPhones );

									/* Faxe(s) field */
									var trFaxes = document.createElement( 'tr' );
										trFaxes.id = me.id + '.trFaxes';

										var tdFaxesPrompt = document.createElement( 'td' );
											tdFaxesPrompt.id = me.id + '.tdFaxesP';
											tdFaxesPrompt.className = '_uicmp_frm_prompt';
											tdFaxesPrompt.innerHTML = strings['faxes'];

										var tdFaxes = document.createElement( 'td' );
											tdFaxes.id = me.id + '.tdFaxes';
											tdFaxes.className = '_uicmp_frm_field';
											tdFaxes.setAttribute( 'colSpan', 3 );

											var inputFaxes = document.createElement( 'input' );
												inputFaxes.id = me.id + '.faxes';
												inputFaxes.className = '_uicmp_frm_input';


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
	
	this.build( );
}

function _uicmp_ab_perse ( layout, tab_id, my_name, my_id, title_id, url, params, strings_id, ind )
{
	/**
	 * Copy scope;
	 */
	var me = this;
	
	/**
	 * Indicates whether form is in edit mode. false value represents
	 * 'Add new person' mode.
	 */
	this.edit = false;
	
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
	 * Multidimensional associative array of strings.
	 */
	this.strings = null;
	
	/**
	 * Indicator instance.
	 */
	this.ind = ind;
	
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
		res_opts.afterDrag = me.tah;

		new TextAreaResizer( document.getElementById( me.my_id + '.comments' ), res_opts );
		
		disableSelection( document.getElementById( me.my_id + '.pPredef' ) );
		disableSelection( document.getElementById( me.my_id + '.pBDay' ) );
	};
	
	this.typed_add = function ( )
	{
		this.typed[this.typed.length] = new _uicmp_ab_typed( me.my_id + '.typed_' + this.typed.length, me.my_id + '.typed', me, me.strings['types'], { text: me.strings['del_row'], method: 'del', style: '_uicmp_blue _uicmp_gi_close' } );
	};
	
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
	
	this.custom_add = function ( )
	{
		this.custom[this.custom.length] = new _uicmp_ab_custom( me.my_id + '.custom_' + this.custom.length, me.my_id + '.custom', me, { text: me.strings['del_row'], method: 'del', style: '_uicmp_blue _uicmp_gi_close' } );
	};
	
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
	
	this.address_add = function ( )
	{
		this.addresses[this.addresses.length] = new _uicmp_ab_address( me.my_id + '.address_' + this.addresses.length, me.my_id + '.addresses', me, me.strings['address']['address'] + me.strings['address']['no'] + ( this.addresses.length + 1 ), me.strings['address']['field'], { text: me.strings['address']['del_address'], method: 'del', style: '_uicmp_blue _uicmp_gi_close' } );
	};
	
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
	 * Put form into initial state.
	 */
	this.reset = function ( )
	{
		this.person_id = 0;
		
		this.cloud.get( );
		
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
		this.typed[this.typed.length] = new _uicmp_ab_typed( me.my_id + '.typed_' + this.typed.length, me.my_id + '.typed', me, me.strings['types'], { text: me.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add' } );
		this.custom[this.custom.length] = new _uicmp_ab_custom( me.my_id + '.custom_' + this.custom.length, me.my_id + '.custom', me, { text: me.strings['add_row'], method: 'add', style: '_uicmp_blue _uicmp_gi_add' } );
		this.addresses[this.addresses.length] = new _uicmp_ab_address( me.my_id + '.address_' + this.addresses.length, me.my_id + '.addresses', me, me.strings['address']['address'], me.strings['address']['field'], { text: me.strings['address']['add_address'], method: 'add', style: '_uicmp_blue _uicmp_gi_add' } );
	};
	
	/**
	 * Provides preformatted strings for display value of contact.
	 */
	this.format = function ( )
	{
		function load_atom( atoms, id ) { atoms[id]	= document.getElementById( me.my_id + '.' + id ).value; }
		
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
		
		var caption = ( this.edit ) ? this.strings['edit'] : this.strings['create'];
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
		this.edit = false;
		this.reset( );
		this.ind.fade( 'prepared', '_uicmp_ind_green' );
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
		var el = document.getElementById( scope.my_id + '.comments' );
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
											alert(data.responseText);
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
}