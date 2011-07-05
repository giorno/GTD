<?PHP

/**
 * @file _idx.php
 * @author giorno
 * @package GTD
 * @subpackage AB
 * @license Apache License, Version 2.0, see LICENSE file
 *
 * Main execution of AddressBook application.
 */

require_once dirname( __FILE__ ) . '/_cfg.php';
require_once APP_AB_LIB . '_app.AbMainImpl.php';
AbMainImpl::getInstance();

?>