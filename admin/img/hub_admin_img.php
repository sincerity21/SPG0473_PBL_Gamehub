<?php
session_start();
require '../../hub_conn.php';

// Check for user login
if (!isset($_SESSION['username'])) {
    header('Location: ../../hub_login.php');
    exit();
}

// 1. Check if a specific game ID is requested for managing the gallery
$game_id = isset($_GET['game_id']) ? (int)$_GET['game_id'] : null;

// 2. Fetch all games for the initial view (or if no specific game is selected)
$games = selectAllGames(); 

// 3. Fetch gallery images if a game_id is provided
$gallery_images = [];
$cover_images = []; // <-- Initialize cover images array
$current_game = null;
$cover_button_text = "➕ Add New Cover"; // <-- NEW: Default button text

if ($game_id) {
    // Get the main game data
    $current_game = selectGameByID($game_id);
    
    if ($current_game) {
        // Get the gallery images for this game
        $gallery_images = selectGameGalleryImages($game_id);
        
        // --- NEW: Fetch Cover Images ---
        if (function_exists('selectGameCovers')) {
            $cover_images = selectGameCovers($game_id);
            
            // --- NEW: Set button text based on whether a cover exists ---
            if (!empty($cover_images)) {
                $cover_button_text = "➕ Replace Game Cover";
            }
            // --- END NEW ---

        } else {
            // Fallback if the function doesn't exist yet
            $cover_images = []; 
        }
        // --- END NEW ---
        
    } else {
        // Handle case where game_id is invalid
        $game_id = null; 
    }
}

// Determine the page title and instructions
$page_title = $current_game ? "Image Management for: " . htmlspecialchars($current_game['game_name']) : "Games Gallery (Select a Game)";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        /* INHERIT STYLES from hub_games.php */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .navbar { background-color: #2c3e50; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 16px 20px; text-decoration: none; transition: background-color 0.3s; }
        .navbar a:hover { background-color: #34495e; }
        .navbar a.active { background-color: #1abc9c; } /* Highlight the current tab */
        .content { padding: 30px; max-width: 1200px; margin: 0 auto; }
        h1 { color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
        h2 { color: #2c3e50; margin-top: 30px; } /* Added margin-top for spacing */
        table { width: 100%; border-collapse: collapse; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: white; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; } 
        th { background-color: #3498db; color: white; font-weight: 600; text-transform: uppercase; }
        tr:hover { background-color: #f5f5f5; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        td a { color: #2980b9; text-decoration: none; margin-right: 5px; }
        td a:hover { text-decoration: underline; }
        .add-link { display: inline-block; padding: 10px 15px; margin-bottom: 20px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background-color 0.3s; }
        .add-link:hover { background-color: #27ae60; }
        .game-image { max-width: 80px; height: auto; display: block; }

        /* --- Specific Gallery Styles --- */
        .gallery-image-container { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .gallery-image-card { border: 1px solid #ccc; padding: 10px; background-color: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); width: calc(33.333% - 20px); box-sizing: border-box; }
        .gallery-image-card img { max-width: 100%; height: auto; display: block; margin-bottom: 10px; }
        .gallery-image-card p { margin: 5px 0; font-size: 0.9em; }
        .current-game-info { background-color: #ecf0f1; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        
        /* --- NEW: Style for the separator --- */
        .section-divider {
            margin-top: 30px;
            margin-bottom: 30px;
            border: 0;
            border-top: 2px solid #ddd;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="../user/hub_admin_user.php">Admin Home</a>
    <a href="../games/hub_admin_games.php"class="active">Manage Games</a>
    <a href="../../hub_logout.php">Logout</a>
</div>

<div class="content">
    <h1><?php echo $page_title; ?></h1>

    <?php if ($game_id && $current_game): ?>
    
        <div class="current-game-info">
            Managing Images for: <strong><?php echo htmlspecialchars($current_game['game_name']); ?></strong> (ID: <?php echo $game_id; ?>) | 
            <a href="../games/hub_admin_games.php">← Back to Game List</a>
        </div>
        
        
        <!-- --- NEW: GAME COVER SECTION --- -->
        <h2>Game Cover</h2>
        
        <!-- UPDATED: Button text is now dynamic -->
        <a href="hub_admin_cover_add.php?game_id=<?php echo $game_id; ?>" class="add-link" style="background-color: #9b59b6;"><?php echo $cover_button_text; ?></a>

        <?php if (function_exists('selectGameCovers')): ?>
            <?php if (!empty($cover_images)): ?>
                <div class="gallery-image-container">
                <?php foreach ($cover_images as $image): ?>
                    <div class="gallery-image-card">
                        <img 
                            src="../../<?php echo htmlspecialchars($image['cover_path']); ?>" 
                            alt="Game Cover ID <?php echo $image['game_cover_id']; ?>"
                        >
                        <p>
                            <!-- Link to a new file you will need to create -->
                            <a href="hub_admin_cover_delete.php?id=<?php echo $image['game_cover_id']; ?>&game_id=<?php echo $game_id; ?>" onclick="return confirm('Are you sure you want to delete this cover image?');">Delete</a>
                        </p>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- This message is now shown when no covers exist -->
                <p>No cover images found for this game. Click 'Add New Cover' to upload one.</p>
            <?php endif; ?>
        <?php else: ?>
            <p style="color: red; font-weight: bold; background-color: #fdd; padding: 10px; border-radius: 4px;">
                Error: The function 'selectGameCovers' is not defined in hub_conn.php. Please add it to manage covers.
            </p>
        <?php endif; ?>
        
        <hr class="section-divider">
        <!-- --- END: GAME COVER SECTION --- -->


        <!-- --- EXISTING GALLERY IMAGES SECTION --- -->
        <h2>Gallery Images</h2>
        
        <a href="hub_admin_img_add.php?game_id=<?php echo $game_id; ?>" class="add-link">➕ Add New Pictures to Gallery</a>

        <?php if (!empty($gallery_images)): ?>
            <div class="gallery-image-container">
            <?php foreach ($gallery_images as $image): ?>
                <div class="gallery-image-card">
                    <img 
                        src="../../<?php echo htmlspecialchars($image['img_path']); ?>" 
                        alt="Gallery Image ID <?php echo $image['game_img_id']; ?>"
                    >

                    <p>
                    <strong>Order:</strong> <?php echo htmlspecialchars($image['img_order']); ?>
                        <a 
                            href="hub_admin_img_sortplus.php?id=<?php echo $image['game_img_id']; ?>&game_id=<?php echo $game_id; ?>"
                            title="Increase Order" style="text-decoration: none; font-weight: bold; margin-left: 10px;"
                        >
                    ➕
                    </a>
                        <a 
                            href="hub_admin_img_sortminus.php?id=<?php echo $image['game_img_id']; ?>&game_id=<?php echo $game_id; ?>"
                            title="Decrease Order" style="text-decoration: none; font-weight: bold; margin-left: 5px;"
                        >
                    ➖
                    </a>
                </p>
                <p>
                    <a href="hub_admin_img_delete.php?id=<?php echo $image['game_img_id']; ?>&game_id=<?php echo $game_id; ?>" onclick="return confirm('Are you sure you want to delete this gallery image?');">Delete</a>
                </p>
            </div>
        <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No gallery images found for this game. Click 'Add New Pictures' to upload some!</p>
        <?php endif; ?>
        <!-- --- END: EXISTING GALLERY IMAGES SECTION --- -->

    <?php else: ?>
    
        <h2>Select a Game to Manage its Images</h2>
        
        <?php if (!empty($games)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Main Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($games as $game): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($game['game_id']); ?></td>
                        <td><?php echo htmlspecialchars($game['game_name']); ?></td>
                        <td>
                            <?php if ($game['game_img']): ?>
                                <img src="../../<?php echo htmlspecialchars($game['game_img']); ?>" class="game-image">
                            <?php else: ?>
                                No Cover
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="hub_admin_img.php?game_id=<?php echo htmlspecialchars($game['game_id']); ?>">Manage Images (Cover & Gallery)</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No games found in the database. Please add a game first via the "Manage Games" tab.</p>
        <?php endif; ?>

    <?php endif; ?>

</div>
</body>
</html>

