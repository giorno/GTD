
/**
 * @file goals.js
 * @author giorno
 * @package GTD
 * @subpackage Stuff
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Routines to handle operations with Lifegoals widget.
 */

function _wwgGoals ( url, params, ind )
{
	/**
	 * Copy scope.
	 */
	var me = this;
	
	/**
	 * URL for Ajax requests.
	 */
	this.url = url;
	
	/**
	 * Parameters for Ajax requests.
	 */
	this.params = params;
	
	/**
	 * Instance of UICMP indicator.
	 */
	this.ind = ind;
	
	/**
	 * Array of internal weight counters.
	 */
	this.weights = new Object( );
	
	this.startup = function ( )
	{
		me.refresh( );
		disableSelection( document.getElementById( '_wwg.Goals' ) );
	};
	
	this.click = function ( sid )
	{
		/*
		 * Not just increment, also creating valid value if not exists.
		 */
		if ( ( typeof( this.weights[sid] ) == 'undefined' ) || ( typeof( this.weights[sid] ) == 'null' ) )
		{
			var el = document.getElementById( '_wwg.Goals:' + sid );
			
			var old_weight = Number( el.className.substring( el.className.length-1 ) );
			
			if ( el )
				this.weights[sid] = old_weight;
			else
				this.weights[sid] = 0;
		}
		
		this.weights[sid]++;

		if ( this.weights[sid] > 4 )
			this.weights[sid] = 0;
		
		this.set_weight( sid, this.weights[sid] );
		document.getElementById( '_wwg.Goals:' + sid ).className = '_wwg_goals_w' + this.weights[sid];
	};
	
	this.set_weight = function ( sid, weight )
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

		reqParams += '&method=set_weight' +
					 '&sid=' + sid +
					 '&weight=' + weight;
		
		var sender = new Ajax.Request( scope.url,
									{
										method: 'post',
										parameters: reqParams,
										onCreate: function ( )
										{
										//	scope.ind.show( 'loading', '_uicmp_ind_gray' );
										},
										onComplete: function ( )
										{
										//	scope.ind.fade( 'loaded', '_uicmp_ind_green' );
										},
										onSuccess: function ( )
										{
											//scope.ind.fade( 'loaded', '_uicmp_ind_green' );
										}
									}
								);
		return sender;
	};
	
	this.refresh = function ( )
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

		reqParams += '&method=refresh';
		
		var sender = new Ajax.Updater( '_wwg.Goals:container', scope.url,
									{
										method: 'post',
										parameters: reqParams,
										onCreate: function ( )
										{
											scope.ind.show( 'loading', '_uicmp_ind_gray' );
										},
										onComplete: function ( )
										{
											scope.ind.fade( 'loaded', '_uicmp_ind_green' );
										},
										onSuccess: function ( )
										{
											scope.ind.fade( 'loaded', '_uicmp_ind_green' );
										}
									}
								);
		return sender;
	};
}

/**
 * Callback for removal of context in CDES.
 */
function _wwg_goals_refresh ( )
{
	if ( typeof _wwgGoals_i !== 'undefined' )
		_wwgGoals_i.refresh( );
}
