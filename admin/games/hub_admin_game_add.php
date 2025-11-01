<?php
define('ROOT_PATH', __DIR__ . '/');
require '../../hub_conn.php';

$error = '';

if($_POST){
    // 1. Collect POST data
    $game_category = $_POST['game_category'];
    $game_name = $_POST['game_name'];
    $game_desc = $_POST['game_desc'];
    $game_trailerLink = $_POST['game_trailerLink'];
    
    // Initialize image filename variable
    $game_img_filename = null;
    
    // 2. Handle File Upload (using $_FILES)
    $upload_dir = 'uploads/images/'; 
    if (isset($_FILES['game_img']) && $_FILES['game_img']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp_path = $_FILES['game_img']['tmp_name'];
        $file_name = $_FILES['game_img']['name'];
        
        // Sanitize filename and create unique name to prevent overwrites
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid('game_img_', true) . '.' . $ext;
        
        // Define upload directory and target path
        $dest_path = ROOT_PATH . $upload_dir . $new_file_name;

        // Move the file from the temporary location to the permanent location
        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            // Success: Store the path/filename to save in the DB
            $game_img_filename = $upload_dir . $new_file_name; 
        } else {
            // Failure: Set an error message if the file move failed
            $error = "Error uploading file. Check directory permissions.";
        }
    } else {
        // Handle case where no file was uploaded
        $game_img_filename = ''; 
    }
    
    // 3. Insert data only if no critical error occurred
    if (!$error) {
        $result = addNewGame(
            $game_category, 
            $game_name, 
            $game_desc, 
            $game_img_filename, // Pass the filename/path
            $game_trailerLink
        );

        if ($result) {
            header('Location: hub_admin_games.php');
            exit();
        } else {
            $error = "Database insertion failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Game</title>
    <style>
        /* Consistent Body and Font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }
        
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
        
        /* Input, Textarea, and Select Styling */
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
            resize: vertical; /* Allows vertical resizing */
        }
        
        /* Submit Button Styling (Consistent with other blue accents) */
        .btn {
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
        .btn:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Game</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data"> 
            <div class="form-group">
                <label for="game_category">Category:</label>
                <select name="game_category" id="game_category" required>
                    <option value="fps">First-Person Shooter</option>
                    <option value="rpg">Role-Playing Games</option>
                    <option value="moba">Multiplayer Online Battle Area (MOBA)</option>
                    <option value="puzzle">Puzzle</option>
                    <option value="sport">Sports</option>
                    <option value="racing">Racing</option>
                    <option value="sim">Simulator</option>
                    <option value="survival">Survival</option>
                    <option value="fight">Fighting</option>
                </select>
            </div>

            <div class="form-group">
                <label for="game_name">Name:</label>
                <input type="text" id="game_name" name="game_name" required>
            </div>
            
            <div class="form-group">
                <label for="game_desc">Description:</label>
                <textarea id="game_desc" name="game_desc" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="game_img">Game Image (File):</label>
                <input type="file" id="game_img" name="game_img" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="game_trailerLink">Trailer (Link):</label>
                <input type="text" id="game_trailerLink" name="game_trailerLink" required>
            </div>
            
            <button type="submit" class="btn">Add Game</button>
        </form>

        
    </div>
</body>
</html>