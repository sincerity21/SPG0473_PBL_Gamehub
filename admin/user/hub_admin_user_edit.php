<?php
session_start(); // Added for consistency
require '../../hub_conn.php';

// Check if ID is provided and numeric
// ... (rest of your existing PHP code) ...
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: hub_admin_user.php');
    exit();
}

$id = (int)$_GET['id'];
$user = selectUserByID($id);    

// Redirect if user not found
if (!$user) {
    header('Location: hub_admin_user.php');
    exit();
}

// Initialization for optional error messages
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'] ?? ''; 
    
    // --- SECURITY LOGIC MODIFICATION ---
    
    // 1. Determine which password hash to use
    if (!empty($new_password)) {
        // HASH the new password if provided
        $final_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        // Keep the existing hash if the field was left blank
        // This is necessary because the form doesn't send the old hash
        $final_password_hash = $user['user_password'];
    }

    // 2. Update the data using the determined hash
    $result = updateByID($id, $username, $email, $final_password_hash);  

    if ($result) {
        // (3) Go back to hub_admin_user.php on success
        header('Location: hub_admin_user.php');     
        exit();
    } else {
        $error = "Error updating user data in the database.";
    }
}

// Re-fetch user data if post failed to ensure form is current
$user = selectUserByID($id); 

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User: <?php echo htmlspecialchars($user['user_username']); ?></title>
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
        /* Consistent Body and Font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--main-text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        /* --- NEW: Navbar Styles --- */
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
            padding: 16px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #34495e;
        }
        .navbar a.active { 
            background-color: #1abc9c; 
        }
        /* --- END NEW --- */

        /* --- NEW: Content Wrapper --- */
        .content {
            padding: 30px;
            max-width: 500px; /* Adjusted to match container */
            margin: 0 auto;
        }
        /* --- END NEW --- */
        
        /* Form Container */
        .container { 
            width: 100%; /* Fill the content area */
            padding: 30px; 
            background-color: var(--card-bg-color);
            border-radius: 8px;
            box-shadow: 0 4px 8px var(--shadow-color); 
            box-sizing: border-box; /* Added */
        }
        
        /* Consistent Heading */
        h2 {
            color: var(--header-text-color);
            text-align: center;
            margin-top: 0; /* Adjusted */
            margin-bottom: 25px;
            border-bottom: 2px solid var(--accent-color);
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
            color: var(--label-text-color);
        }
        
        /* Input Styling */
        input[type="text"], 
        input[type="email"],
        input[type="password"] { /* Added password type support */
            width: 100%; 
            padding: 10px; 
            border: 1px solid var(--border-color);
            background-color: var(--card-bg-color);
            color: var(--main-text-color);
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 16px;
        }
        
        /* Update Button Styling */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--accent-color); 
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #2980b9; /* Static hover */
        }
        .error { 
            background-color: #e74c3c; 
            color: white; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
            text-align: center; 
            font-weight: bold; 
        }

        /* --- NEW: Back Link --- */
        .back-link {
            display: block;
            margin-bottom: 20px;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        /* --- END NEW --- */

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
    <div class="container">

        <a href="hub_admin_user.php" class="back-link">← Back to User List</a>

        <h2>Edit User: <?php echo htmlspecialchars($user['user_username']); ?></h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['user_username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password (Leave blank to keep current password):</label>
                <input type="password" id="password" name="password" placeholder="Enter new password to change" value=""> 
            </div>
            
            <input type="submit" value="Update User">
        </form>
    </div>
</div> <script>
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