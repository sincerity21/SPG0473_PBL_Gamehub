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
    
    // --- NEW LOGIC: DELETE ASSOCIATED FILES ---

    // 3a. Delete all associated GALLERY image files
    $gallery_images = selectGameGalleryImages($id);
    if ($gallery_images) {
        foreach ($gallery_images as $image) {
            if (!empty($image['img_path']) && file_exists(ROOT_PATH . $image['img_path'])) {
                unlink(ROOT_PATH . $image['img_path']);
            }
        }
    }

    // 3b. Delete all associated COVER image files
    // (We check if function exists just in case)
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
    
    // 3c. (Original Code) Delete the MAIN game image file from the server
    $image_path = $game['game_img'];
    if (!empty($image_path) && file_exists(ROOT_PATH . $image_path)) {
        // Use the absolute path to delete the file
        unlink(ROOT_PATH . $image_path);
    }
    
    // --- END OF NEW LOGIC ---


    // 4. Delete the game record from the database
    // Because you added ON DELETE CASCADE, this single call will now
    // delete the game AND all its associated records from
    // game_images, game_cover, rating, favourites, and feedback_game.
    deleteGameByID($id); 
}

// 5. Redirect back to the game listing page (hub_admin_games.php)
header('Location: hub_admin_games.php');

exit();
?>