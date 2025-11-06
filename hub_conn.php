<?php

$host = '127.0.0.1';
$dbname = 'gamehub';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}


function registerUser($username, $email, $password, $server, $prompt, $answer){
    global $conn;
    
    // HASH the main password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // --- CRITICAL CHANGE: HASH the security answer before storing it ---
    $hashed_answer = password_hash($answer, PASSWORD_DEFAULT);
    
    // 1. Prepare the SQL statement with placeholders (?)
    $sql = "INSERT INTO users (user_username, user_email, user_password, user_server, sec_prompt, sec_answer) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    // Check if preparation was successful
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    // 2. Bind parameters: 'ssssss' means all six values are strings
    // We now bind the HASHED answer: $hashed_answer
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $server, $prompt, $hashed_answer);
    
    // 3. Execute the statement
    $result = $stmt->execute();
    
    // 4. Close the statement
    $stmt->close();

    return $result;
}

function loginUser($username, $password){
    global $conn;

    // 1. Prepare statement to safely fetch all user data by username
    $sql = "SELECT * FROM users WHERE user_username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Prepare failed in loginUser: " . $conn->error);
        return false;
    }

    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        // 2. Verify the submitted password against the HASHED password from the database
        if (password_verify($password, $user['user_password'])) {
            // Success
            return $user; 
        }
    }
    // Failure
    return false;
}

function selectUserByID($id){
    global $conn;
    // Using prepared statement for security
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

function selectAllUsers(){
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateByID($id, $username, $email, $password){
    global $conn;
    
    // CRITICAL FIX: Removed the password_hash() call here.
    // The password parameter must already contain the correct hash (or the old hash) 
    // when passed from hub_admin_user_edit.php
    $hashed_password = $password; 
    
    // 2. Prepare the SQL statement with placeholders (?)
    // Using prepared statements for security against SQL injection.
    $sql = "UPDATE users SET user_username = ?, user_email = ?, user_password = ? WHERE user_id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in updateByID: " . $conn->error);
        return false;
    }
    
    // 3. Bind parameters: 'sssi' for username, email, password (all strings), and id (integer)
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $id);
    
    // 4. Execute the statement
    $result = $stmt->execute();
    
    // 5. Close the statement
    $stmt->close();
    
    return $result;
}

function deleteByID($id){
    global $conn;
    // Using prepared statement for security
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function addNewGame($game_category, $game_name, $game_desc, $game_img, $game_trailerLink, $game_Link){
    global $conn;
    
    // The SQL now includes the 'game_Link' column
    $sql = "INSERT INTO games (game_category, game_name, game_desc, game_img, game_trailerLink, game_Link) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind 6 parameters ('s' for each string)
    $stmt->bind_param("ssssss", $game_category, $game_name, $game_desc, $game_img, $game_trailerLink, $game_Link);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function selectAllGames(){
    global $conn;
    $sql = "SELECT * FROM games";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function selectGameByID($id){
    global $conn;
    $sql = "SELECT * FROM games WHERE game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $game = $result->fetch_assoc();
    $stmt->close();
    return $game;
}

function updateGameByID($id, $name, $category, $desc, $img, $trailerLink, $game_Link){
    global $conn;
    
    $sql = "UPDATE games SET 
            game_name = ?, 
            game_category = ?, 
            game_desc = ?, 
            game_img = ?, 
            game_trailerLink = ?,
            game_Link = ? 
            WHERE game_id = ?";
            
    $stmt = $conn->prepare($sql);
    
    // Bind 7 parameters
    $stmt->bind_param("ssssssi", $name, $category, $desc, $img, $trailerLink, $game_Link, $id);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function deleteGameByID($id){
    global $conn;
    $sql = "DELETE FROM games WHERE game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getUserResetData($conn, $username) {
    // We are now fetching the HASHED answer from the database.
    $sql = "SELECT user_id, sec_prompt, sec_answer 
             FROM users 
             WHERE user_username = ?";

    // Check if the connection object is valid before preparing
    if ($conn === null) {
        error_log("MySQLi Connection object is null in getUserResetData.");
        return false;
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in getUserResetData: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param('s', $username); // 's' for string
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    
    // Map column names to match the expected array keys in hub_forgotpassword.php
    if ($user) {
        return [
            'user_id' => $user['user_id'],
            'security_question' => $user['sec_prompt'],
            // sec_answer is now the HASHED value
            'security_answer_hash' => $user['sec_answer'] 
        ];
    }

    return false;
}

function updateUserPassword($conn, $user_id, $hashed_password) {
    // Note: The password MUST already be hashed before calling this function.
    $sql = "UPDATE users SET user_password = ? WHERE user_id = ?";
    
    if ($conn === null) {
        error_log("MySQLi Connection object is null in updateUserPassword.");
        return false;
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in updateUserPassword: " . $conn->error);
        return false;
    }
    
    // 'si' for string (password) and integer (user_id)
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}

function addGameGalleryImage($game_id, $image_path, $sort_order = 0){
    global $conn;
    
    // SQL updated: image_path -> img_path, sort_order -> img_order
    $sql = "INSERT INTO game_images (game_id, img_path, img_order) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind 3 parameters ('isi' for integer, string, integer)
    $stmt->bind_param("isi", $game_id, $image_path, $sort_order);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function selectGameGalleryImages($game_id){
    global $conn;
    
    // SQL updated: sort_order -> img_order
    $sql = "SELECT * FROM game_images WHERE game_id = ? ORDER BY img_order ASC, game_img_id ASC";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in selectGameGalleryImages: " . $conn->error);
        return [];
    }

    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $images;
}

function updateImageSortOrder($image_id, $change_amount) {
    global $conn;
    
    // SQL: Safely update img_order by adding the change_amount to its current value.
    $sql = "UPDATE game_images SET img_order = img_order + ? WHERE game_img_id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in updateImageSortOrder: " . $conn->error);
        return false;
    }
    
    // 'ii' for integer (change_amount) and integer (image_id)
    $stmt->bind_param("ii", $change_amount, $image_id);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function deleteGalleryImageByID($image_id) {
    global $conn;

    // 1. Retrieve the file path and game_id first
    $sql_select = "SELECT img_path, game_id FROM game_images WHERE game_img_id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $image_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $image_data = $result->fetch_assoc();
    $stmt_select->close();

    if (!$image_data) {
        return false; // Record not found
    }
    
    // 2. Delete the record from the database
    $sql_delete = "DELETE FROM game_images WHERE game_img_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $image_id);
    $success = $stmt_delete->execute();
    $stmt_delete->close();

    if ($success) {
        // Return the path data so the calling script can physically delete the file
        return $image_data;
    }
    
    return false;
}

function selectAllGameCategories(){
    global $conn;
    // DISTINCT ensures only unique categories are returned
    $sql = "SELECT DISTINCT game_category FROM games ORDER BY game_category ASC";
    $result = $conn->query($sql);
    
    $categories = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['game_category'];
        }
    }
    return $categories;
}

function selectGameCovers($game_id){
    global $conn;
    
    $sql = "SELECT * FROM game_cover WHERE game_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in selectGameCovers: " . $conn->error);
        return [];
    }

    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $images;
}

function addOrUpdateGameCover($game_id, $cover_path) {
    global $conn;

    // 1. Check if a cover already exists for this game_id
    $sql_check = "SELECT game_cover_id FROM game_cover WHERE game_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check === false) {
        error_log("Prepare failed (check) in addOrUpdateGameCover: " . $conn->error);
        return false;
    }
    
    $stmt_check->bind_param("i", $game_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $existing_cover = $result_check->fetch_assoc();
    $stmt_check->close();

    if ($existing_cover) {
        // 2. UPDATE if it exists
        $sql = "UPDATE game_cover SET cover_path = ? WHERE game_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (update) in addOrUpdateGameCover: " . $conn->error);
            return false;
        }
        $stmt->bind_param("si", $cover_path, $game_id);
    } else {
        // 3. INSERT if it does not exist
        $sql = "INSERT INTO game_cover (game_id, cover_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed (insert) in addOrUpdateGameCover: " . $conn->error);
            return false;
        }
        $stmt->bind_param("is", $game_id, $cover_path);
    }
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

function deleteGameCover($cover_id) {
    global $conn;

    // 1. Retrieve the file path and game_id first
    $sql_select = "SELECT cover_path, game_id FROM game_cover WHERE game_cover_id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $cover_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $image_data = $result->fetch_assoc();
    $stmt_select->close();

    if (!$image_data) {
        return false; // Record not found
    }
    
    // 2. Delete the record from the database
    $sql_delete = "DELETE FROM game_cover WHERE game_cover_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $cover_id);
    $success = $stmt_delete->execute();
    $stmt_delete->close();

    if ($success) {
        // Return the path data so the calling script can physically delete the file
        return $image_data;
    }
    
    return false;
}

function selectAllGamesWithCovers(){
    global $conn;
    
    // Selects game details and the cover path
    $sql = "SELECT 
                g.game_id, 
                g.game_name, 
                g.game_category,
                gc.cover_path 
            FROM 
                games g
            LEFT JOIN 
                game_cover gc ON g.game_id = gc.game_id
            ORDER BY 
                g.game_name ASC";
                
    $result = $conn->query($sql);
    
    if ($result === false) {
        error_log("Query failed in selectAllGamesWithCovers: " . $conn->error);
        return [];
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function selectUserGameFeedback($user_id, $game_id){
    global $conn;
    $feedback = ['game_rating' => 0, 'favorite_game' => 0]; // Default values

    // 1. Get rating from 'rating' table
    $sql_rating = "SELECT rating_game FROM rating WHERE user_id = ? AND game_id = ?";
    $stmt_rating = $conn->prepare($sql_rating);
    $stmt_rating->bind_param("ii", $user_id, $game_id);
    $stmt_rating->execute();
    $result_rating = $stmt_rating->get_result();
    if ($row_rating = $result_rating->fetch_assoc()) {
        $feedback['game_rating'] = (int)$row_rating['rating_game'];
    }
    $stmt_rating->close();

    // 2. Get favorite from 'favourites' table
    $sql_fav = "SELECT favourite_game FROM favourites WHERE user_id = ? AND game_id = ?";
    $stmt_fav = $conn->prepare($sql_fav);
    $stmt_fav->bind_param("ii", $user_id, $game_id);
    $stmt_fav->execute();
    $result_fav = $stmt_fav->get_result();
    if ($row_fav = $result_fav->fetch_assoc()) {
        $feedback['favorite_game'] = (int)$row_fav['favourite_game'];
    }
    $stmt_fav->close();

    return $feedback; // Return the combined array
}

function upsertGameRating($user_id, $game_id, $rating) {
    global $conn;
    $sql = "INSERT INTO rating (user_id, game_id, rating_game)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE rating_game = VALUES(rating_game)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed in upsertGameRating: " . $conn->error); 
        return false;
    }
    $stmt->bind_param("iii", $user_id, $game_id, $rating);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function upsertGameFavourite($user_id, $game_id, $favorite) {
    global $conn;
    $sql = "INSERT INTO favourites (user_id, game_id, favourite_game)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE favourite_game = VALUES(favourite_game)";
    $stmt = $conn->prepare($sql);
     if ($stmt === false) {
        error_log("Prepare failed in upsertGameFavourite: " . $conn->error); 
        return false;
    }
    $stmt->bind_param("iii", $user_id, $game_id, $favorite);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function upsertGameFeedback($user_id, $game_id, $frequency, $open_feedback) {
    global $conn;
    $sql = "INSERT INTO feedback_game (user_id, game_id, feedback_game_frequency, feedback_game_open)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                feedback_game_frequency = VALUES(feedback_game_frequency), 
                feedback_game_open = VALUES(feedback_game_open)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed in upsertGameFeedback: " . $conn->error); 
        return false;
    }
    $stmt->bind_param("iiss", $user_id, $game_id, $frequency, $open_feedback);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function selectUserSurveyFeedback($user_id, $game_id){
    global $conn;
    
    $sql = "SELECT feedback_game_frequency, feedback_game_open 
            FROM feedback_game 
            WHERE user_id = ? AND game_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback = $result->fetch_assoc();
    $stmt->close();
    
    return $feedback; // Returns the row, or null if no feedback exists
}

function upsertSiteFeedback($user_id, $satisfaction, $open_feedback) {
    global $conn;
    $sql = "INSERT INTO feedback_site (user_id, feedback_site_satisfaction, feedback_site_open)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                feedback_site_satisfaction = VALUES(feedback_site_satisfaction), 
                feedback_site_open = VALUES(feedback_site_open)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed in upsertSiteFeedback: " . $conn->error); 
        return false;
    }
    $stmt->bind_param("iss", $user_id, $satisfaction, $open_feedback);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function selectUserSiteFeedback($user_id){
    global $conn;
    
    $sql = "SELECT feedback_site_satisfaction, feedback_site_open 
            FROM feedback_site 
            WHERE user_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback = $result->fetch_assoc();
    $stmt->close();
    
    return $feedback; // Returns the row, or null if no feedback exists
}

function selectUserInteractedGames($user_id){
    global $conn;
    
    // Selects game info, cover path, and user's rating/favourite/survey status
    //
    // MODIFIED QUERY:
    // - Added LEFT JOIN for feedback_game (fg)
    // - Added a WHERE clause to only include games where an interaction exists
    // - Added GROUP BY to prevent duplicate games if a user has multiple interactions
    // - Added a user_surveyed flag
    $sql = "SELECT 
                g.game_id, 
                g.game_name, 
                g.game_category,
                g.game_Link,
                gc.cover_path,
                COALESCE(r.rating_game, 0) AS user_rating,
                COALESCE(f.favourite_game, 0) AS user_favourite,
                (fg.feedback_game_id IS NOT NULL) AS user_surveyed
            FROM 
                games g
            LEFT JOIN 
                game_cover gc ON g.game_id = gc.game_id
            LEFT JOIN 
                rating r ON g.game_id = r.game_id AND r.user_id = ?
            LEFT JOIN 
                favourites f ON g.game_id = f.game_id AND f.user_id = ?
            LEFT JOIN
                feedback_game fg ON g.game_id = fg.game_id AND fg.user_id = ?
            WHERE
                r.user_id IS NOT NULL 
                OR f.user_id IS NOT NULL 
                OR fg.user_id IS NOT NULL
            GROUP BY
                g.game_id
            ORDER BY
                g.game_name ASC";
                
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed in selectUserInteractedGames: " . $conn->error);
        return [];
    }
    
    // Bind the user_id to all three JOIN conditions
    $stmt->bind_param("iii", $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $games = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $games;
}

function updateUsername($user_id, $new_username, $current_password) {
    global $conn;

    // 1. Check if new username is already taken
    $sql_check = "SELECT user_id FROM users WHERE user_username = ? AND user_id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $new_username, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $stmt_check->close();
        return "Username already taken. Please choose another.";
    }
    $stmt_check->close();

    // 2. Verify current password
    $sql_user = "SELECT user_password FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();
    $stmt_user->close();

    if ($user && password_verify($current_password, $user['user_password'])) {
        // 3. Password is correct, update username
        $sql_update = "UPDATE users SET user_username = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_username, $user_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['username'] = $new_username; // Update session variable
            $stmt_update->close();
            return "success";
        } else {
            $stmt_update->close();
            return "Database error. Could not update username.";
        }
    } else {
        return "Incorrect current password.";
    }
}

function updateUserPasswordSecurely($user_id, $current_password, $new_password) {
    global $conn;

    // 1. Verify current password
    $sql_user = "SELECT user_password FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();
    $stmt_user->close();

    if ($user && password_verify($current_password, $user['user_password'])) {
        // 2. Password is correct, hash and update new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql_update = "UPDATE users SET user_password = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            $stmt_update->close();
            return "success";
        } else {
            $stmt_update->close();
            return "Database error. Could not update password.";
        }
    } else {
        return "Incorrect current password.";
    }
}


/**Get user's security question, used temporarily to update old accounts with non-hashed security answers (hub_hashsecanswer.php). 
function getSecurityAnswerForHashing($conn, $username) {
    $sql = "SELECT user_id, sec_answer FROM users WHERE user_username = ?";
    
    if ($conn === null) {
        return false;
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in getSecurityAnswerForHashing: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    
    return $user;
} */

/**Updates security answer (either same one or new inputted one) into a hashed format (hub_hashsecanswer.php). 
function updateHashedSecurityAnswer($conn, $user_id, $hashed_answer) {
    $sql = "UPDATE users SET sec_answer = ? WHERE user_id = ?";
    
    if ($conn === null) {
        return false;
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in updateHashedSecurityAnswer: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("si", $hashed_answer, $user_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
} */

/**function selectRandomGalleryImages($limit = 9){
    global $conn;
    
    $sql = "
        SELECT gi.img_path
        FROM game_images gi
        INNER JOIN games g ON gi.game_id = g.game_id
        INNER JOIN (
            -- Subquery to force a random game_img_id for each category
            SELECT
                g3.game_category,
                (SELECT gi3.game_img_id
                 FROM game_images gi3
                 INNER JOIN games g4 ON gi3.game_id = g4.game_id
                 WHERE g4.game_category = g3.game_category
                 ORDER BY RAND()
                 LIMIT 1
                ) AS random_img_id
            FROM games g3
            GROUP BY g3.game_category
            ORDER BY RAND()
            LIMIT ?
        ) AS RandomCategories ON gi.game_img_id = RandomCategories.random_img_id
    ";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in selectRandomGalleryImages: " . $conn->error);
        return [];
    }

    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $paths = array_column($images, 'img_path');
    return $paths;
} */


/**function selectGamesByCategory($category_value){
    global $conn;
    
    $sql = "SELECT * FROM games WHERE game_category = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed in selectGamesByCategory: " . $conn->error);
        return [];
    }

    $stmt->bind_param("s", $category_value);
    $stmt->execute();
    $result = $stmt->get_result();
    $games = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $games;
} */

?>
