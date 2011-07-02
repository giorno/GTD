<?php

/**
 * @file install.php
 * @author giorno
 * @package N7
 * @subpackage AI
 * @license Apache License, Version 2.0, see LICENSE file
 * 
 * Install script for Stuff app.
 */

define ( "APP_STUFF_INST", dirname( __FILE__ ) );

/**
 * Prepare database objects installation.
 */
$tables = file_get_contents( APP_STUFF_INST . '/tables.sql' );
$settings = file_get_contents( APP_STUFF_INST . '/settings.sql' );

$body = $tables . "\n\r" . $settings;
$table = Config::T_SETTINGS;
$ns = N7_SOLUTION_ID . '.Stuff';	// must be consistent with value used in StuffSettings class
		
/**
 * Remove comments and bind namespace.
 */
$comments = array( '/\s*--.*\n/' );
$script = preg_replace( $comments, "\n", $body );
$script = str_replace( '{$__1}', $table, $script );
$script = str_replace( '{$__2}', $ns, $script );
$statements = explode( ";\n", $script );

_db_query( "BEGIN" );

/**
 * Execute scripts.
 */
if ( is_array( $statements ) )
	foreach( $statements as $statement )
		_db_query( $statement );

_db_query( "COMMIT" );
			
?>