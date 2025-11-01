<?php
session_start();
session_destroy();


// // Redirect to login page after session is destroyed
header("Location: hub_login.php");
exit(); // Terminate script execution after redirect

?>