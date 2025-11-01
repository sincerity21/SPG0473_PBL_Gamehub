<?php
session_start();
require '../hub_conn.php';

// Assuming you have added the selectAllGames function to ../hub_conn.php:
// function selectAllGames(){
//     global $conn;
//     $sql = "SELECT * FROM games";
//     $result = $conn->query($sql);
//     return $result->fetch_all(MYSQLI_ASSOC);
// }

if (!isset($_SESSION['username'])) {
    header('Location: ../hub_login.php');
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
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6; /* Consistent Background */
            color: #333;
        }
        
        /* Navbar Styles */
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
        
        /* Content Area */
        .content {
            padding: 30px; /* Consistent Padding */
            max-width: 1200px; /* Increased width to accommodate the image and description */
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db; /* Consistent Accent Line */
            padding-bottom: 5px;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Consistent Shadow */
            background-color: white;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px; /* Consistent Padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top; 
        }
        th {
            background-color: #3498db; /* Blue Header */
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f5f5f5; /* Consistent Hover */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Consistent Zebra Striping */
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
    </style>
</head>
<body>
<div class="navbar">
    <a href="hub_admin_user.php">Admin Home</a>
    <a href="hub_admin_games.php" class="active">Manage Games</a>
    <a href="../hub_logout.php">Logout</a>
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
                            src="../<?php echo htmlspecialchars($game['game_img']); ?>" 
                            alt="<?php echo htmlspecialchars($game['game_name']); ?> Cover" 
                            class="game-image"
                        >
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo htmlspecialchars($game['game_trailerLink']); ?>" target="_blank">Watch Trailer</a></td>
                <td>
                    <a href="hub_admin_img.php?game_id=<?php echo htmlspecialchars($game['game_id']); ?>">🖼️ Gallery</a> |
                    <a href="hub_admin_game_edit.php?id=<?php echo htmlspecialchars($game['game_id']); ?>">Edit</a> |
                    <a href="hub_admin_game_delete.php?id=<?php echo htmlspecialchars($game['game_id']); ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>