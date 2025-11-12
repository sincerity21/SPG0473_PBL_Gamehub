<?php
session_start();
require '../../hub_conn.php';

// Authentication Check (Must be logged in)
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ../modals/hub_login.php');
    exit();
} 

// Authorization Check (Must be an Admin to delete users)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../../main/logged_in/hub_home_logged_in.php'); 
    exit();
}

// Get the ID of the currently logged-in admin
$logged_in_user_id = $_SESSION['user_id']; 


if (isset($_GET['id'])) {
    // Sanitize and cast the ID from the URL to ensure it's an integer
    $id_to_delete = (int)$_GET['id'];
    
    // Check if the user ID is valid
    if ($id_to_delete <= 0) {
        header('Location: hub_admin_user.php?error=invalid_id');
        exit();
    }
    
    // Execute Deletion
    $deletion_successful = deleteByID($id_to_delete);
    
    if ($deletion_successful) {
        
        // Self-Deletion Logic
        if ($id_to_delete == $logged_in_user_id) { 
            
            // LOGOUT: If admin deleted their own account
            session_unset();
            session_destroy();
            
            // KICK THE USER OUT to the login page
            header('Location: ../modals/hub_login.php?status=self_deleted'); 
            exit();
        }
        
        // Go back to the user list to show the change
        header('Location: hub_admin_user.php?status=deleted');
        exit();
    } else {
        // Deletion failed (e.g., database error)
        header('Location: hub_admin_user.php?error=deletion_failed');
        exit();
    }
}

// Fallback redirect if ID is missing or not processed
header('Location: hub_admin_user.php');
exit();
?>