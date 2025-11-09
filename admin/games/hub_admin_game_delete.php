<?php
require '../../hub_conn.php';

// Define the root path for file deletion
define('ROOT_PATH', __DIR__ . '/../../'); 

// 1. Check for ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Game ID not specified.");
}

$id = $_GET['id'];

// 2. Fetch game data to get the image path before deleting the database record
$game = selectGameByID($id); 

if ($game) {
    // 3a. (NEW) Delete all gallery images AND their files
    $gallery_images = selectGameGalleryImages($id);
    if ($gallery_images) {
        foreach ($gallery_images as $image) {
            // This function from hub_conn.php already deletes the file
            deleteGalleryImageByID($image['game_img_id']);
        }
    }
    
    // 3b. (NEW) Delete all cover images AND their files
    $covers = selectGameCovers($id);
    if ($covers) {
        foreach ($covers as $cover) {
            deleteGameCover($cover['game_cover_id']);
        }
    }

    // 3c. (Original code) Delete the main game image file
    $image_path = $game['game_img'];
    if (!empty($image_path) && file_exists(ROOT_PATH . $image_path)) {
        unlink(ROOT_PATH . $image_path);
    }
    
    // 4. Delete the game record from the database
    deleteGameByID($id); // This will now succeed
}

// 5. Redirect back to the game listing page (hub_admin_games.php)
header('Location: hub_admin_games.php');

exit();
?>