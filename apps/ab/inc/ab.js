
/**
 * @file ab.js
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Address Book client side utils.
 */

function _ab_rm_single ( data )
{
	data['checked'] = new Array( );
	data['checked'][0] = data['id'];
	_ab_rm_batch( data );
}

function _ab_rm_batch ( data )
{
	var ids		= data['checked'];
	var srch	= data['client_var'];
	var kind	= data['class'];

	/**
	 * Visit UICMP search instance and steal Ajax configuration.
	 */
	var url		= srch.url;
	var params	= srch.params;
	var ind		= srch.ind;

	/**
	 * Compose request parameters.
	 */
	var reqParams = '';
	for ( var key in params )
		reqParams += '&' + key + '=' + params[key];

	reqParams += '&method=_ab_rm_batch' +
				 '&class=' + kind +
				 '&ids=' + ids.join( ',' );

	var sender = new Ajax.Request( url,
					{
						asynchronous: false,
						method: 'post',
						parameters: reqParams,
						onCreate: function ( ) {ind.show( 'executing', '_uicmp_ind_gray' );},
						onFailure: function ( ) {ind.show( 'e_unknown', '_uicmp_ind_red' );},
						onSuccess: function ( )
						{
							ind.fade( 'executed', '_uicmp_ind_green' );
							srch.refresh( );
						}
					}
				);

	return sender;
}
