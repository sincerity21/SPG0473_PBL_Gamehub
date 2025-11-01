<?php
// Ensure session is started if needed for admin authentication (though not explicitly shown here)
// session_start(); 
require '../hub_conn.php';

// Check if ID is provided and numeric
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
            max-width: 500px; 
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
        
        /* Input Styling */
        input[type="text"], 
        input[type="email"],
        input[type="password"] { /* Added password type support */
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 16px;
        }
        
        /* Update Button Styling (Consistent blue accent) */
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
        .error { background-color: #e74c3c; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
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
            <!-- IMPORTANT: Password field must be type="password" and LEFT BLANK for security -->
            <input type="password" id="password" name="password" placeholder="Enter new password to change" value=""> 
        </div>
        
        <input type="submit" value="Update User">
    </form>
</div>
</body>
</html>
