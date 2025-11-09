<?php
require '../../hub_conn.php';

//  Define the root path for file deletion
define('ROOT_PATH', __DIR__ . '/../../'); 

// Check for ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Game ID not specified.");
}

$id = $_GET['id'];

// Fetch game data to get the image path before deleting the database record
$game = selectGameByID($id); 

if ($game) {
    
    // Find all gallery images for this game and remove them from the server
    $gallery_images = selectGameGalleryImages($id);
    if ($gallery_images) {
        foreach ($gallery_images as $image) {
            if (!empty($image['img_path']) && file_exists(ROOT_PATH . $image['img_path'])) {
                unlink(ROOT_PATH . $image['img_path']);
            }
        }
    }

    // Delete all related cover image files
    // check if function exists
    if (function_exists('selectGameCovers')) {
        $cover_images = selectGameCovers($id);
        if ($cover_images) {
            foreach ($cover_images as $cover) {
                if (!empty($cover['cover_path']) && file_exists(ROOT_PATH . $cover['cover_path'])) {
                    unlink(ROOT_PATH . $cover['cover_path']);
                }
            }
        }
    }
    
    // Delete the main game image file from the server
    $image_path = $game['game_img'];
    if (!empty($image_path) && file_exists(ROOT_PATH . $image_path)) {
        //  Use the absolute path to delete the file
        unlink(ROOT_PATH . $image_path);
    }
    
    deleteGameByID($id); 
}

// Go back to the game listing page
header('Location: hub_admin_games.php');

exit();
?>