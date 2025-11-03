<?php
session_start();
session_destroy();

// Testing Public Repository
// // Redirect to login page after session is destroyed
header("Location: hub_login.php");
exit(); // Terminate script execution after redirect

?>
