<?php
session_start();
require '../../hub_conn.php';

// --- 1. Authentication Check (Must be logged in) ---
if (!isset($_SESSION['username'])) {
    header('Location: ../../hub_login.php');
    exit();
}

// --- 2. Authorization Check (Must be an Admin) ---
// We check if the 'is_admin' session variable is NOT set OR if it is set but NOT true (i.e., not an admin).
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect non-admin users to the regular user home page
    header('Location: ../../main/hub_home_logged_in.php'); 
    exit();
}

// Only admins can reach this point
$username = $_SESSION['username'];
$email = $_SESSION['email'];

$users = selectAllUsers(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Hub</title>
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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--main-text-color);
            transition: background-color 0.3s, color 0.3s;
        }
        .navbar {
            background-color: #2c3e50; /* Navbar color kept static (dark) */
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 16px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #34495e; /* Static hover */
        }
        .content {
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: var(--header-text-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 5px;
        }
        .user-info {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #ecf0f1;
            border-left: 5px solid #3498db;
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
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
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
        td a {
            color: #2980b9;
            text-decoration: none;
            margin-right: 5px;
        }
        td a:hover {
            text-decoration: underline;
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
    <a href="hub_admin_user.php" class="active">Admin Home</a>
    <a href="../games/hub_admin_games.php">Manage Games</a>
    <a href="../../hub_logout.php">Logout</a>

    <div class="dark-mode-switch" onclick="toggleDarkMode()">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </div>
</div>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <h2>User Listing (Administrative View)</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Server</th>
                <th>Admin Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['user_username']); ?></td>
                <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                <td><?php echo htmlspecialchars($user['user_server']); ?></td>
                <td><?php echo htmlspecialchars($user['is_admin']); ?></td>
                <td>
                    <a href="hub_admin_user_edit.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">Edit</a> |
                    <a href="hub_admin_user_delete.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">Delete</a>
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