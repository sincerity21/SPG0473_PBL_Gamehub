<?php
session_start();
session_destroy();

// Redirect to login page after session is destroyed
header("Location: main/hub_home.php");
exit(); 

?>
