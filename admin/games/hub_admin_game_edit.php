<?php
session_start(); // Added session_start for admin auth
require '../../hub_conn.php';

// --- Admin Auth Check (Added) ---
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../../hub_login.php'); 
    exit();
}
// --- End Auth Check ---

// Define the root path and upload directory for file handling
define('ROOT_PATH', __DIR__ . '/../../'); 
$upload_dir = 'uploads/images/';

// 1. Get initial data
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Game ID not specified.");
}
$id = $_GET['id'];
$game = selectGameByID($id);

if (!$game) {
    die("Error: Game not found.");
}

$error = ''; // Initialize error variable

if ($_POST) {
    // Collect non-file data from form
    $game_name = $_POST['game_name'];
    $game_category = $_POST['game_category'];
    $game_desc = $_POST['game_desc'];
    $game_trailerLink = $_POST['game_trailerLink'];
    
    // Start with the existing image path (in case no new file is uploaded)
    $game_img_filename = $game['game_img']; 

    // 2. Handle File Upload (only if a new file is selected)
    if (isset($_FILES['game_img']) && $_FILES['game_img']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp_path = $_FILES['game_img']['tmp_name'];
        $file_name = $_FILES['game_img']['name'];
        
        // Sanitize filename and create unique name
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid('game_img_', true) . '.' . $ext;
        
        // Use the absolute path for moving the file
        $dest_path = ROOT_PATH . $upload_dir . $new_file_name;

        // Move the file
        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            // Success: Set the new relative path for the database
            $game_img_filename = $upload_dir . $new_file_name;
            
            // Optional: Delete the old file from the server if it exists
            if (!empty($game['game_img']) && file_exists(ROOT_PATH . $game['game_img'])) {
                 unlink(ROOT_PATH . $game['game_img']);
            }
            
        } else {
            // Failure: Set an error message
            $error = "Error uploading new file. Check directory permissions.";
        }
    }
    
    // 3. Update data if no critical error occurred
    if (!$error) {
        $result = updateGameByID(
            $id, 
            $game_name, 
            $game_category, 
            $game_desc, 
            $game_img_filename, // Use the new or existing filename
            $game_trailerLink
        );
        
        if ($result) {
            // 4. Go back to hub_admin_games.php
            header('Location: hub_admin_games.php'); 
            exit();
        } else {
            $error = "Database update failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Game: <?php echo htmlspecialchars($game['game_name']); ?></title>
    <style>
        /* Consistent Body and Font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }

        /* --- NEW: Navbar Styles --- */
        .navbar {
            background-color: #2c3e50; /* Consistent Navbar Background */
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 16px 20px; /* Consistent Padding */
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #34495e;
        }
        .navbar a.active { 
            background-color: #1abc9c; 
        }
        /* --- END: Navbar Styles --- */
        
        /* Consistent Container for Form */
        .container { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }
        
        /* Consistent Heading */
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        /* Form Grouping and Labels */
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold;
            color: #555;
        }
        
        /* Input and Textarea Styling */
        input[type="text"], 
        textarea, 
        select,
        input[type="file"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 16px;
        }
        textarea {
            resize: vertical;
        }
        
        /* Current Image Display */
        .current-img { 
            max-width: 150px; 
            height: auto; 
            display: block; 
            margin: 10px 0; 
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Update Button Styling (Consistent with other blue accents) */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db; 
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        
        /* Error Styling */
        .error { 
            background-color: #fdd; 
            color: #c00; 
            padding: 10px; 
            border: 1px solid #f99;
            border-radius: 4px;
            margin-bottom: 15px; 
            text-align: center;
        }

        /* --- NEW: Back Link Style --- */
        .back-link {
            text-decoration: none; 
            color: #3498db; 
            font-weight: bold; 
            display: inline-block; 
            margin-bottom: 15px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="../user/hub_admin_user.php">Admin Home</a>
    <a href="hub_admin_games.php" class="active">Manage Games</a>
    <a href="../../hub_logout.php">Logout</a>
</div>
<div class="container">
    
    <a href="hub_admin_games.php" class="back-link">&larr; Back to Game List</a>
    <h2>Edit Game: <?php echo htmlspecialchars($game['game_name']); ?></h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"> 
        
        <div class="form-group">
            <label for="game_category">Category:</label>
            <select name="game_category" id="game_category" required>
                <option value="fps" <?php echo ($game['game_category'] == 'fps') ? 'selected' : ''; ?>>First-Person Shooter</option>
                <option value="rpg" <?php echo ($game['game_category'] == 'rpg') ? 'selected' : ''; ?>>Role-Playing Games</option>
                <option value="moba" <?php echo ($game['game_category'] == 'moba') ? 'selected' : ''; ?>>Multiplayer Online Battle Arena (MOBA)</option>
                <option value="puzzle" <?php echo ($game['game_category'] == 'puzzle') ? 'selected' : ''; ?>>Puzzle</option>
                <option value="sport" <?php echo ($game['game_category'] == 'sport') ? 'selected' : ''; ?>>Sports</option>
                <option value="sim" <?php echo ($game['game_category'] == 'sim') ? 'selected' : ''; ?>>Simulator</option>
                <option value="survival" <?php echo ($game['game_category'] == 'survival') ? 'selected' : ''; ?>>Survival</option>
                <option value="fight" <?php echo ($game['game_category'] == 'fight') ? 'selected' : ''; ?>>Fighting</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="game_name">Name:</label>
            <input type="text" id="game_name" name="game_name" value="<?php echo htmlspecialchars($game['game_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="game_desc">Description:</label>
            <textarea id="game_desc" name="game_desc" rows="5" required><?php echo htmlspecialchars($game['game_desc']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Current Image:</label>
            <?php if (!empty($game['game_img'])): ?>
                <img src="../../<?php echo htmlspecialchars($game['game_img']); ?>" class="current-img" alt="Current Game Image">
                <p><small>File: <?php echo htmlspecialchars($game['game_img']); ?></small></p>
            <?php else: ?>
                <p>No current image.</p>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="game_img">Browse/Upload New Image (Leave blank to keep current):</label>
            <input type="file" id="game_img" name="game_img" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="game_trailerLink">Trailer Link:</label>
            <input type="text" id="game_trailerLink" name="game_trailerLink" value="<?php echo htmlspecialchars($game['game_trailerLink']); ?>" required>
        </div>

        <input type="submit" value="Update Game">
    </form>
</div>
</body>
</html>