<?php
session_start();
session_destroy();

header("Location: main/hub_home.php");
exit(); // Terminate script execution after redirection

?>
