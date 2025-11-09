<?php
session_start();
require 'hub_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized access.']);
    exit();
}

if (isset($_GET['game_id'])) {
    $game_id = (int)$_GET['game_id'];

    if ($game_id > 0) {
        $images = selectGameGalleryImages($game_id);
        
        //Return the entire data structure,including img_path.
        echo json_encode(['images' => $images]);
    } else {
        echo json_encode(['error' => 'Invalid game ID']);
    }
} else {
    echo json_encode(['error' => 'Game ID not provided']);
}
?>