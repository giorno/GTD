<?PHP

/**
 * @file _idx.php
 * @author giorno
 * @subpackage Stuff
 *
 * Initialization of Stuff app.
 */

require_once dirname( __FILE__ ) . '/_cfg.php';
require_once APP_STUFF_LIB . '_app.StuffMainImpl.php';
StuffMainImpl::getInstance();

?>