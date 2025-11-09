<?php
session_start();
require '../../hub_conn.php';
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ../hub_login.php');
    exit();
} 
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../hub_home_logged_in.php'); 
    exit();
}

$logged_in_user_id = $_SESSION['user_id']; 


if (isset($_GET['id'])) {
    //Get the ID of the user to delete
    $id_to_delete = (int)$_GET['id'];
    
    //Make sure ID is a valid number
    if ($id_to_delete <= 0) {
        header('Location: hub_admin_user.php?error=invalid_id');
        exit();
    }
    
    //Delete the user from the database
    $deletion_successful = deleteByID($id_to_delete);
    
    if ($deletion_successful) {
        
        //Check if admin deleted their own account
        if ($id_to_delete == $logged_in_user_id) { 
            
            //if yes,Logout
            session_unset();
            session_destroy();
            
            //Send them to the login page
            header('Location: ../hub_login.php?status=self_deleted'); 
            exit();
        }
        
        //If admin deleted someone else
        // Go back to the user list to show the change
        header('Location: hub_admin_user.php?status=deleted');
        exit();
    } else {
        // Deletion failed (e.g., database error)
        header('Location: hub_admin_user.php?error=deletion_failed');
        exit();
    }
}

//Go back to user list if no ID was provided
header('Location: hub_admin_user.php');
exit();
?>