<?php
session_start();
require '../../hub_conn.php';

//Check for admin login
if (!isset($_SESSION['username'])) {
    header('Location: ../hub_login.php');
    exit();
}

//Make sure the image ID is given
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: hub_admin_img.php');
    exit();
}

$image_id = (int)$_GET['id'];
$game_id = (int)$_GET['game_id']; // Get game_id from URL to redirect back

//Delete the image from the db and get its path for file deletion
$deleted_data = deleteGameCover($image_id);

if ($deleted_data) {
    $file_path = $deleted_data['cover_path'];

    //Delete the actual image file from the server
    $server_file_path = __DIR__ . '/../../' . $file_path; 

    if (file_exists($server_file_path)) {
        if (unlink($server_file_path)) {
        } 
        else {
            error_log("Failed to delete physical cover file: " . $server_file_path);
        }
    } else {
        error_log("Physical cover file not found (but DB record deleted): " . $server_file_path);
    }
    
} else 
{
    error_log("Failed to delete cover image ID: " . $image_id);
}

//Go back to the game's image list
if ($game_id) {
    header('Location: hub_admin_img.php?game_id=' . $game_id);
} else {
    header('Location: hub_admin_img.php');
}
exit();
?>
