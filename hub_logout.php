<?php
session_start();
session_destroy();

// test
// // Redirect to login page after session is destroyed
header("Location: hub_login.php");
exit(); // Terminate script execution after redirect

?>
