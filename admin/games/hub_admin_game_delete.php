<?php
require '../../hub_conn.php';

// Define the root path for file deletion
define('ROOT_PATH', __DIR__ . '/../../'); 

// Check for ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Game ID not specified.");
}

$id = $_GET['id'];

// Fetch game data to get the image path before deleting the database record
$game = selectGameByID($id); 

if ($game) {
    // Delete the associated image file from the server
    $image_path = $game['game_img'];
    if (!empty($image_path) && file_exists(ROOT_PATH . $image_path)) {
        // Use the absolute path to delete the file
        unlink(ROOT_PATH . $image_path);
    }
    
    // Delete the game record from the database
    deleteGameByID($id); 
}

// Redirect back to the game listing page (hub_admin_games.php)
header('Location: hub_admin_games.php');

exit();
?>