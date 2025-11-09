<?php
session_start();
require '../../hub_conn.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../hub_login.php');
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['game_id']) || !is_numeric($_GET['id']) || !is_numeric($_GET['game_id'])) {
    header('Location: hub_admin_img.php');
    exit();
}

$image_id = (int)$_GET['id'];
$game_id = (int)$_GET['game_id'];
$change_amount = 1; // Increase value of sort order

updateImageSortOrder($image_id, $change_amount);

header('Location: hub_admin_img.php?game_id=' . $game_id);
exit();
?>