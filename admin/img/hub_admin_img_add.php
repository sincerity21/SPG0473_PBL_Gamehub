<?php
// Ensure this file is now called hub_admin_gallery_add.php
session_start();
// Assuming hub_conn.php is one level up from the admin folder
require '../../hub_conn.php'; 

// Define ROOT_PATH relative to the PHP file's location
// This helps locate the actual uploads directory on the server's file system
define('ROOT_PATH', __DIR__ . '/'); 

// Check for user login
if (!isset($_SESSION['username'])) {
    header('Location: ../../hub_login.php');
    exit();
}

// --- 1. Validate Game ID from URL ---
if (!isset($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
    header('Location: hub_admin_img.php');
    exit();
}

$game_id = (int)$_GET['game_id'];
$current_game = selectGameByID($game_id);

if (!$current_game) {
    // Game not found
    header('Location: hub_admin_img.php');
    exit();
}

// Initialization for messages
$message = '';
$message_type = ''; 

// --- 2. Handle File Uploads and Database Insertion ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gallery_images'])) {
    
    $upload_dir = 'uploads/gallery/'; 
    $upload_count = 0;
    $failed_count = 0;

    // Create the physical upload directory if it doesn't exist (e.g., /PBL/uploads/gallery/)
    // We navigate UP one directory (../) from the 'admin' folder to reach the 'PBL' root,
    // then define the full server path to the gallery folder.
    $server_upload_path = __DIR__ . '/../../' . $upload_dir; 

    if (!is_dir($server_upload_path)) {
        mkdir($server_upload_path, 0777, true);
    }

    // Loop through all uploaded files from the 'gallery_images' array
    foreach ($_FILES['gallery_images']['name'] as $key => $filename) {
        
        // Check for upload error for the current file
        if ($_FILES['gallery_images']['error'][$key] !== UPLOAD_ERR_OK) {
            if ($_FILES['gallery_images']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                $failed_count++;
            }
            continue;
        }

        $file_tmp_path = $_FILES['gallery_images']['tmp_name'][$key];
        
        // Generate a unique filename
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_file_name = 'game_' . $game_id . '_' . uniqid() . '.' . $ext;
        
        // Define the full server path for moving the file
        $dest_path = $server_upload_path . $new_file_name;

        // The path we save to the database (browser-accessible path)
        $db_path = $upload_dir . $new_file_name; 

        // Move the file from the temporary location to the permanent location
        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            // Success: Insert the path/filename into the game_images table
            // Using the new function we defined in hub_conn.php
            if (addGameGalleryImage($game_id, $db_path, 0)) {
                $upload_count++;
            } else {
                $failed_count++;
                // Clean up file if database insertion fails
                unlink($dest_path); 
                error_log("DB insert failed for file: " . $db_path);
            }
        } else {
            $failed_count++;
            error_log("Failed to move uploaded file to target directory: " . $dest_path);
        }
    }
    
    // 3. Set feedback message
    if ($upload_count > 0) {
        $message_type = 'success';
        $message = "Successfully added **{$upload_count}** new images to the gallery.";
    } 
    
    if ($failed_count > 0) {
        $message_type = $upload_count > 0 ? 'warning' : 'error';
        $message .= " ({$failed_count} file(s) failed to upload or save to database.)";
    }

    if ($upload_count > 0) {
        // Redirect back to the gallery view page to see the new images
        header('Location: hub_admin_img.php?game_id=' . $game_id . '&status=' . $message_type);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Gallery Images - <?php echo htmlspecialchars($current_game['game_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* 2. CSS VARIABLES ADDED */
        :root {
            --bg-color: #f4f7f6;
            --main-text-color: #333;
            --card-bg-color: white;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --border-color: #ccc;
            --header-text-color: #2c3e50;
            --accent-color: #3498db;
            --label-text-color: #555;
        }

        body.dark-mode {
            --bg-color: #121212;
            --main-text-color: #f4f4f4;
            --card-bg-color: #1e1e1e;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --border-color: #555;
            --header-text-color: #ecf0f1;
            --accent-color: #4dc2f9;
            --label-text-color: #bbb;
        }
        /* END CSS VARIABLES */

        /* 3. EXISTING CSS UPDATED TO USE VARIABLES */
        /* Consistent Styling from hub_game_add.php and hub_admin_img.php */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: var(--bg-color); color: var(--main-text-color); transition: background-color 0.3s, color 0.3s; }
        .navbar { background-color: #2c3e50; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 16px 20px; text-decoration: none; transition: background-color 0.3s; }
        .navbar a:hover { background-color: #34495e; }
        .navbar a.active { background-color: #1abc9c; } 
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background-color: var(--card-bg-color); border-radius: 8px; box-shadow: 0 4px 8px var(--shadow-color); }
        h2 { color: var(--header-text-color); text-align: center; margin-bottom: 25px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px; }
        h3 { color: var(--header-text-color); }
        a { color: var(--accent-color); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--label-text-color); }
        input[type="file"] { width: 100%; padding: 10px; border: 1px solid var(--border-color); background-color: var(--card-bg-color); color: var(--main-text-color); border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background-color: var(--accent-color); color: white; border: none; border-radius: 4px; font-size: 18px; cursor: pointer; transition: background-color 0.3s; margin-top: 10px; }
        .btn:hover { background-color: #2980b9; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; text-align: center;}
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        /* 4. DARK MODE SWITCH STYLE ADDED */
        .dark-mode-switch {
            float: right;
            padding: 16px 20px;
            cursor: pointer;
            color: white;
            font-size: 1.1em;
            transition: color 0.3s;
        }
        .dark-mode-switch:hover {
            color: #1abc9c;
        }
    </style>
</head>
<body id="appBody"> <div class="navbar">
        <a href="../user/hub_admin_user.php">Admin Home</a>
        <a href="../games/hub_admin_games.php"class="active">Manage Games</a>
        <a href="../../hub_logout.php">Logout</a>

        <div class="dark-mode-switch" onclick="toggleDarkMode()">
            <i class="fas fa-moon" id="darkModeIcon"></i>
        </div>
    </div>

    <div class="container">
        <h2>Add Gallery Images</h2>
        
        <h3>For Game: <?php echo htmlspecialchars($current_game['game_name']); ?> (ID: <?php echo $game_id; ?>)</h3>

        <p><a href="hub_admin_img.php?game_id=<?php echo $game_id; ?>">← Back to Gallery View</a></p>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data"> 
            <div class="form-group">
                <label for="gallery_images">Select Images (Hold Ctrl/Cmd to select multiple):</label>
                <input 
                    type="file" 
                    id="gallery_images" 
                    name="gallery_images[]" 
                    accept="image/*" 
                    multiple 
                    required
                >
            </div>
            
            <button type="submit" class="btn">Upload and Add to Gallery</button>
        </form>
    </div>

<script>
    const body = document.getElementById('appBody');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const darkModeKey = 'adminGamehubDarkMode';

    function applyDarkMode(isDark) {
        if (isDark) {
            body.classList.add('dark-mode');
            if (darkModeIcon) darkModeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            body.classList.remove('dark-mode');
            if (darkModeIcon) darkModeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    }

    function toggleDarkMode() {
        const isDark = body.classList.contains('dark-mode');
        applyDarkMode(!isDark);
        localStorage.setItem(darkModeKey, !isDark ? 'dark' : 'light');
    }

    (function loadDarkModePreference() {
        const savedMode = localStorage.getItem(darkModeKey);
        const isDark = savedMode === 'dark'; 
        applyDarkMode(isDark);
    })();
</script>
</body>
</html>