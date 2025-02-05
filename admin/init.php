<?php
// Define SESSION_STARTED before including connection.inc.php
if (!defined('SESSION_STARTED')) {
    define('SESSION_STARTED', true);
    session_start();
}

// Include necessary files
require_once('connection.inc.php');
require_once('functions.inc.php');

// Any other initialization code can go here
?>