<?php

// Handle the logout functionality here
// For example, you can unset or destroy the session, clear session cookies, etc.

// Start or resume the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear session cookies
setcookie(session_name(), '', time() - 3600);

// Redirect the user to the login page
header('Location: login.dart');
exit;
?>
