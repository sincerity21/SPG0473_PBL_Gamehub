<?php
session_start();
require '../../hub_conn.php';

// Assuming you have added the selectAllGames function to ../hub_conn.php:
// function selectAllGames(){
//     global $conn;
//     $sql = "SELECT * FROM games";
//     $result = $conn->query($sql);
//     return $result->fetch_all(MYSQLI_ASSOC);
// }

if (!isset($_SESSION['username'])) {
    header('Location: ../../hub_login.php');
    exit();
}   

$games = selectAllGames();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Listing - Game Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* 2. CSS VARIABLES ADDED */
        :root {
            --bg-color: #f4f7f6;
            --main-text-color: #333;
            --card-bg-color: white;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --border-color: #ddd;
            --header-text-color: #2c3e50;
            --accent-color: #3498db;
            --accent-text-color: white;
            --hover-bg-color: #f5f5f5;
            --zebra-bg-color: #f9f9f9;
        }

        body.dark-mode {
            --bg-color: #121212;
            --main-text-color: #f4f4f4;
            --card-bg-color: #1e1e1e;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --border-color: #444;
            --header-text-color: #ecf0f1;
            --accent-color: #4dc2f9;
            --accent-text-color: #1e1e1e;
            --hover-bg-color: #2c2c2c;
            --zebra-bg-color: #222;
        }
        /* END CSS VARIABLES */

        /* 3. EXISTING CSS UPDATED TO USE VARIABLES */
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--main-text-color);
            transition: background-color 0.3s, color 0.3s;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: #2c3e50; /* Static Dark */
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
        
        /* Content Area */
        .content {
            padding: 30px; /* Consistent Padding */
            max-width: 1200px; /* Increased width to accommodate the image and description */
            margin: 0 auto;
        }
        h1 {
            color: var(--header-text-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 5px;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px var(--shadow-color);
            background-color: var(--card-bg-color);
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px; /* Consistent Padding */
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top; 
        }
        th {
            background-color: var(--accent-color);
            color: var(--accent-text-color);
            font-weight: 600;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: var(--hover-bg-color);
        }
        tr:nth-child(even) {
            background-color: var(--zebra-bg-color);
        }
        
        /* Action Links and Image */
        td a {
            color: #2980b9;
            text-decoration: none;
            margin-right: 5px;
        }
        td a:hover {
            text-decoration: underline;
        }
        .add-link {
            display: inline-block; /* Changed to inline-block for better button feel */
            padding: 10px 15px;
            margin-bottom: 20px;
            background-color: #2ecc71; /* Green for 'Add' action */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .add-link:hover {
            background-color: #27ae60;
        }
        .game-image {
            max-width: 80px; /* Adjusted size for table */
            height: auto;
            display: block;
        }
        .navbar a.active { 
            background-color: #1abc9c; 
        }

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
    <a href="hub_admin_games.php" class="active">Manage Games</a>
    <a href="../../hub_logout.php">Logout</a>

    <div class="dark-mode-switch" onclick="toggleDarkMode()">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </div>
</div>

<div class="content">
    <h1>Game Listing</h1>

    <a href="hub_admin_game_add.php" class="add-link">➕ Add New Game</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Trailer Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($games as $game): ?>
            <tr>
                <td><?php echo htmlspecialchars($game['game_id']); ?></td>
                <td><?php echo htmlspecialchars($game['game_category']); ?></td>
                <td><?php echo htmlspecialchars($game['game_name']); ?></td>
                <td style="max-width: 300px;"><?php echo htmlspecialchars($game['game_desc']); ?></td> 
                <td>
                    <?php if ($game['game_img']): ?>
                        <img 
                            src="../../<?php echo htmlspecialchars($game['game_img']); ?>" 
                            alt="<?php echo htmlspecialchars($game['game_name']); ?> Cover" 
                            class="game-image"
                        >
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo htmlspecialchars($game['game_trailerLink']); ?>" target="_blank">Watch Trailer</a></td>
                <td>
                    <a href="../img/hub_admin_img.php?game_id=<?php echo htmlspecialchars($game['game_id']); ?>">🖼️ Gallery</a> |
                    <a href="hub_admin_game_edit.php?id=<?php echo htmlspecialchars($game['game_id']); ?>">Edit</a> |
                    <a href="hub_admin_game_delete.php?id=<?php echo htmlspecialchars($game['game_id']); ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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