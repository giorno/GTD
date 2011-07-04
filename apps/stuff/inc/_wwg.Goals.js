
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



/*function _wwgGoalsStartup ( )
{
	_wwgGoalsUpdate();
	disableSelection( document.getElementById( '_wwg.Goals' ) );
}*/

/*
 * Increase (or set to 0) weight of given goal.
 */
/*function _wwgGoalsClick ( SID )
{*/
	/*
	 * Not just increment, also creating valid value if not exists.
	 */
	/*if ( ( typeof( _wwgGoalsWeights[SID] ) == 'undefined' ) || ( typeof( _wwgGoalsWeights[SID] ) == 'null' ) )
	{
		var el = document.getElementById( '_wwg.Goals:' + SID );
		if ( el )
			_wwgGoalsWeights[SID] = Number( el.className.substring( el.className.length-1 ) );
		else
			_wwgGoalsWeights[SID] = 0;
	}*/
	//alert( '_wwg.Goals:' + SID );
	/*_wwgGoalsWeights[SID] = _wwgGoalsWeights[SID] + 1;
	alert( _wwgGoalsWeights[SID] );*/
	/*_wwgGoalsWeights[SID]++;
	
	if ( _wwgGoalsWeights[SID] > 4 )
		_wwgGoalsWeights[SID] = 0;*/
//alert(_wwgGoalsWeights[SID]);
/*	_wwgGoalsSetWeight( SID, _wwgGoalsWeights[SID] );
	document.getElementById( '_wwg.Goals:' + SID ).className = 'wwgGoalsW' + _wwgGoalsWeights[SID];
}*/

/*
 * Set new weight for goal.
 *
 * @param SID id of Stuff
 * @param weight numerical value of goal weight
 */
/*function _wwgGoalsSetWeight ( SID, weight )
{
	var sender = new Ajax.Request( './ajax.php',
					{
						method: 'post',
						parameters: 'action=setGoalWeight&app=stuff&SID=' + SID + '&weight=' + weight,
						onSuccess: function ( )
						{
							alert(data.responseText);
						}
					}
				);

	return sender;
}*/

/*
 * Update goals cloud.
 */
/*function _wwgGoalsUpdate ( )
{
	var sender = new Ajax.Updater( '_wwg.Goals:container', './ajax.php',
								{
									method: 'post',
									parameters: 'app=stuff&action=loadGoals',
									onCreate: function ( )
									{
										document.getElementById( '_wwg.Goals.Ind' ).innerHTML = 'aa';
										document.getElementById( '_wwg.Goals.Ind' ).className = '_uicmp_ind_gray';
										document.getElementById( '_wwg.Goals.Ind' ).style.visibility = 'visible';*/
										/*document.getElementById( 'wwg.Goals.Ind' ).style.display = 'block';*/
									/*},
									onComplete: function ( )
									{
										document.getElementById( 'wwgGoalsLoader' ).style.visibility = 'hidden';
										document.getElementById( 'wwgGoalsLoader' ).style.display = 'none';
									},
									onSuccess: function ( )
									{
										//alert(data.responseText);
										document.getElementById( 'wwgGoalsLoader' ).style.visibility = 'hidden';
										document.getElementById( 'wwgGoalsLoader' ).style.display = 'none';

										/**
										 * Disable <DIV> container for lifegoals if there
										 * are accidentaly no lifegoals (=no context for lifegoal) .
										 */
										/*if ( data.responseText.substr( 0, 4 ) == 'NOLG' )
										{
											var el = document.getElementById( 'gtdGoals' );
											if ( el != null )
											{
												el.style.visibility = 'hidden';
												el.style.display = 'none';
											}
										}*/
									/*}
								}
							);
	return sender;
}*/
				