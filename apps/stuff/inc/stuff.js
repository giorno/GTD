
/**
 * @file stuff.js
 * @author giorno
 * @subpackage Stuff
 *
 * General non-UICMP routines specific for application Stuff.
 */

/*
 * Move stuff to Archive box. After success actual box list is updated.
 *
 * @param <array> data associative array containing keys: 'id' for Stuff ID,
 *                  'label' for proper archivation label (2 - 2 minutes job,
 *                  F - Finished, G - Garbage), and 'client_var' for instance of
 *                  _uicmp_search used for extraction of Ajax parameters
 */
function _stuff_archive ( data )
{
	var srch	= data['client_var'];

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

	reqParams += '&method=_stuff_archive' +
				 '&id=' + data['id'] +
				 '&label=' + data['label'];

	var sender = new Ajax.Request( url,
					{
						asynchronous: false,
						method: 'post',
						parameters: reqParams,
						onCreate: function ( ) { ind.show( 'executing', '_uicmp_ind_gray' ); },
						onFailure: function ( ) { ind.show( 'e_unknown', '_uicmp_ind_red' ); },
						onSuccess: function ( data )
						{
							ind.fade( 'executed', '_uicmp_ind_green' );

							srch.refresh( );
							_uicmp_stuff_folds_i.update( );

							/** @todo update goals */
						}
					}
				);

	return sender;
}

/**
 * Wrapper for _stuff_purge_batch() specialized for single entry removal.
 *
 * @param <array> data associative array containing keys: 'id' for Stuff ID,
 *                  and 'client_var' for instance of _uicmp_search used for
 *                  extraction of Ajax parameters
 */
function _stuff_purge ( data )
{
	data['checked'] = new Array( );
	data['checked'][0] = data['id'];
	_stuff_purge_batch( data );
}

/**
 * Permanently removes batch of entries from Archive.
 * 
 * Parasites on _uicmp_stuff_folds and search instances.
 *
 * @param <array> data associative array containing keys: 'ids' for array of
 *                  Stuff ID's, and 'client_var' for instance of _uicmp_search
 *                  used for extraction of Ajax parameters
 */
function _stuff_purge_batch ( data )
{
	var ids		= data['checked'];
	var srch	= data['client_var'];

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

	reqParams += '&method=_stuff_purge_batch' +
				 '&ids=' + ids.join( ',' );

	var sender = new Ajax.Request( url,
					{
						asynchronous: false,
						method: 'post',
						parameters: reqParams,
						onCreate: function ( ) { ind.show( 'executing', '_uicmp_ind_gray' ); },
						onFailure: function ( ) { ind.show( 'e_unknown', '_uicmp_ind_red' ); },
						onSuccess: function ( data )
						{
							ind.fade( 'executed', '_uicmp_ind_green' );

							srch.refresh( );
							_uicmp_stuff_folds_i.update( );

							/** @todo update goals */
						}
					}
				);

	return sender;
}
