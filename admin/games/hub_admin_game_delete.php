<?php
require '../../hub_conn.php';

// Define the root path for file deletion
define('ROOT_PATH', __DIR__ . '/../../'); 

// Check for ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Game ID not specified.");
}

$id = $_GET['id'];


// Fetch game data, including gallery and cover
$game = selectGameByID($id);
$gallery_images = selectGameGalleryImages($id);
$cover_images = selectGameCovers($id);

if ($game) {
    
    // Delete physical gallery images
    if (is_array($gallery_images)) {
        foreach ($gallery_images as $image) {
            $file_path = ROOT_PATH . $image['img_path'];
            if (!empty($image['img_path']) && file_exists($file_path)) {
                @unlink($file_path); // Use @ to suppress errors if file is already gone
            }
        }
    }

    // Delete physical cover image(s)
    if (is_array($cover_images)) {
        foreach ($cover_images as $cover) {
            $file_path = ROOT_PATH . $cover['cover_path'];
            if (!empty($cover['cover_path']) && file_exists($file_path)) {
                @unlink($file_path);
            }
        }
    }
    
    // Manually delete gallery records since ON DELETE CASCADE is missing
    $sql_delete_gallery = "DELETE FROM game_images WHERE game_id = ?";
    $stmt_delete_gallery = $conn->prepare($sql_delete_gallery);
    $stmt_delete_gallery->bind_param("i", $id);
    $stmt_delete_gallery->execute();
    $stmt_delete_gallery->close();

    // Delete the game record from the database (this will cascade)
    deleteGameByID($id);
}

// Redirect back to the game listing page (hub_admin_games.php)
header('Location: hub_admin_games.php');

exit();
?>