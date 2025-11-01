<?php
session_start();
require '../hub_conn.php';

// --- 1. Authentication Check (Must be logged in) ---
if (!isset($_SESSION['username'])) {
    header('Location: ../hub_login.php');
    exit();
}

// --- 2. Authorization Check (Must be an Admin) ---
// We check if the 'is_admin' session variable is NOT set OR if it is set but NOT true (i.e., not an admin).
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect non-admin users to the regular user home page
    header('Location: ../hub_home.php'); 
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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }
        .navbar {
            background-color: #2c3e50; /* Darker blue/grey */
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
            background-color: #34495e; /* Slightly lighter hover */
        }
        .content {
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db; /* Blue header */
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f5f5f5; /* Light grey on hover */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping */
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
    </style>
</head>
<body>
<div class="navbar">
    <a href="hub_admin_user.php" class="active">Admin Home</a>
    <a href="hub_admin_games.php">Manage Games</a>
    <a href="../hub_logout.php">Logout</a>
</div>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?> (Admin)!</h1>

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
</body>
</html>