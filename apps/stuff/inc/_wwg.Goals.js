
/**
 * @file goals.js
 * @author giorno
 *
 * Routines to handle operations with Lifegoals widget.
 */

var _wwgGoalsWeights = new Object( );

function _wwgGoalsStartup ( )
{

	disableSelection( document.getElementById( '_wwg.Goals' ) );
}

/*
 * Increase (or set to 0) weight of given goal.
 */
function _wwgGoalsClick ( SID )
{
	/*
	 * Not just increment, also creating valid value if not exists.
	 */
	if ( ( typeof( _wwgGoalsWeights[SID] ) == 'undefined' ) || ( typeof( _wwgGoalsWeights[SID] ) == 'null' ) )
	{
		var el = document.getElementById( '_wwg.Goals:' + SID );
		if ( el )
			_wwgGoalsWeights[SID] = Number( el.className.substring( el.className.length-1 ) );
		else
			_wwgGoalsWeights[SID] = 0;
	}
	//alert( '_wwg.Goals:' + SID );
	/*_wwgGoalsWeights[SID] = _wwgGoalsWeights[SID] + 1;
	alert( _wwgGoalsWeights[SID] );*/
	_wwgGoalsWeights[SID]++;
	
	if ( _wwgGoalsWeights[SID] > 4 )
		_wwgGoalsWeights[SID] = 0;
//alert(_wwgGoalsWeights[SID]);
	_wwgGoalsSetWeight( SID, _wwgGoalsWeights[SID] );
	document.getElementById( '_wwg.Goals:' + SID ).className = 'wwgGoalsW' + _wwgGoalsWeights[SID];
}

/*
 * Set new weight for goal.
 *
 * @param SID id of Stuff
 * @param weight numerical value of goal weight
 */
function _wwgGoalsSetWeight ( SID, weight )
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
}

/*
 * Update goals cloud.
 */
function _wwgGoalsUpdate ( )
{
	var sender = new Ajax.Updater( '_wwg.Goals:container', './ajax.php',
								{
									method: 'post',
									parameters: 'app=stuff&action=loadGoals',
									onCreate: function ( )
									{
										document.getElementById( 'wwgGoalsLoader' ).style.visibility = 'visible';
										document.getElementById( 'wwgGoalsLoader' ).style.display = 'block';
									},
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
									}
								}
							);
	return sender;
}