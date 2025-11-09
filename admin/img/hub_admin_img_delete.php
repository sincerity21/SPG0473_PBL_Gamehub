<?php
session_start();
require '../../hub_conn.php';

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
$game_id = null; //Get game_id after the delete

//Delete from DB and get the file's data
$deleted_data = deleteGalleryImageByID($image_id);

if ($deleted_data) {
    $file_path = $deleted_data['img_path'];
    $game_id = $deleted_data['game_id'];

    //Delete the actual file
    $server_file_path = __DIR__ . '/../../' . $file_path; 

    //If the file exists,delete it
    if (file_exists($server_file_path)) {
        if (unlink($server_file_path)) {
        }
         else {
            error_log("Failed to delete physical file: " . $server_file_path);
        }
    } else {
        error_log("Physical file not found (but DB record deleted): " . $server_file_path);
    }
    
} else 
{
    error_log("Failed to delete gallery image ID: " . $image_id);
}

//Decide where to send the user
$redirect_url = ($game_id) ? 'hub_admin_img.php?game_id=' . $game_id : 'hub_admin_img.php';

//All done, send the user back
header('Location: ' . $redirect_url);
exit();
?>
