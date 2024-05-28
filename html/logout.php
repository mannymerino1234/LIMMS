<?php
session_start(); // Start the session

// Destroy the session data regardless
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit(); // Stop script execution
?>
