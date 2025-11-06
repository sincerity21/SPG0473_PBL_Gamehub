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

/**
 * Finds a user by username and retrieves their ID, security question, and HASHED answer.
 * @param mysqli $conn The MySQLi database connection object.
 * @param string $username The username to search for.
 * @return array|false Returns user data (ID, question, answer HASH) or false if not found.
 */
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

/**
 * Updates a user's password hash in the database securely using their ID.
 * @param mysqli $conn The MySQLi database connection object.
 * @param int $user_id The ID of the user whose password is to be reset.
 * @param string $hashed_password The new, securely hashed password.
 * @return bool True on success, false on failure.
 */
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

/**
 * Adds a new image path to the 'game_images' gallery table.
 * (Updated to use img_path and img_order)
 * * @param int $game_id The ID of the game this image belongs to.
 * @param string $image_path The file path of the image.
 * @param int $sort_order The order in which the image should appear.
 * @return bool True on success, false on failure.
 */
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

/**
 * Retrieves all gallery images for a specific game ID, ordered by img_order.
 * (Updated to use img_path, game_img_id, and img_order)
 * * @param int $game_id The ID of the game to retrieve images for.
 * @return array An array of image records, or an empty array on failure/no images.
 */
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

/**
 * Changes the img_order value for a specific gallery image.
 * @param int $image_id The primary key (game_img_id) of the image record.
 * @param int $change_amount The amount to add to the current img_order (e.g., 1 or -1).
 * @return bool True on success, false on failure.
 */
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

/**
 * Deletes a single gallery image record and returns the file path for physical deletion.
 * @param int $image_id The primary key (game_img_id) of the image record.
 * @return string|false The file path string (e.g., 'uploads/gallery/...') on success, or false on failure.
 */
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

/**
 * Retrieves all unique game categories from the games table.
 * @return array A list of unique category strings, or an empty array.
 */
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

/**
 * Retrieves a list of random image paths from the game_images table, 
 * ensuring only ONE image is selected per category.
 * @param int $limit The maximum number of images/categories to return.
 * @return array An array of image path strings.
 */
function selectRandomGalleryImages($limit = 9){
    global $conn;
    
    // FIX: MariaDB-compatible query to get ONE random image for each category.
    // This uses the INNER JOIN technique to select a random image ID for each game,
    // then groups by category, and finally randomizes the categories chosen.
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
}

/**
 * Retrieves all games belonging to a specific category.
 * @param string $category_value The unique category string (e.g., 'rpg').
 * @return array An array of game records, or an empty array on failure/no games.
 */
function selectGamesByCategory($category_value){
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
}

// --- NEW FUNCTIONS FOR GAME COVER ---

/**
 * Selects all cover images for a given game ID.
 * (Even if logic is 1-to-1, returning an array is safer for foreach loops)
 *
 * @param int $game_id The ID of the game.
 * @return array An array of cover image records.
 */
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

/**
 * Adds a new game cover or updates the existing one.
 * Enforces a one-to-one relationship by checking for an existing game_id.
 *
 * @param int $game_id The ID of the game.
 * @param string $cover_path The new file path for the cover.
 * @return bool True on success, false on failure.
 */
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

/**
 * Deletes a single game cover record and returns its data for file deletion.
 *
 * @param int $cover_id The primary key (game_cover_id) of the cover record.
 * @return array|false The cover data (cover_path, game_id) on success, or false on failure.
 */
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

/**
 * Selects all games, joining with their respective cover image path.
 * Uses a LEFT JOIN to include games that may not have a cover.
 * @return array An array of all game records with cover_path.
 */
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

/**
 * Selects the existing feedback (rating and favorite) for a specific user and game
 * by querying the new 'rating' and 'favourites' tables.
 * @param int $user_id The ID of the user.
 * @param int $game_id The ID of the game.
 * @return array A combined array: ['game_rating' => 0, 'favorite_game' => 0]
 */
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

/**
 * Inserts or updates a user's star rating for a game in the 'rating' table.
 *
 * @param int $user_id The user's ID.
 * @param int $game_id The game's ID.
 * @param int $rating The star rating (1-5).
 * @return bool True on success, false on failure.
 */
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

/**
 * Inserts or updates a user's favorite status for a game in the 'favourites' table.
 *
 * @param int $user_id The user's ID.
 * @param int $game_id The game's ID.
 * @param int $favorite The favorite status (0 or 1).
 * @return bool True on success, false on failure.
 */
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

/**
 * Retrieves the user's ID and current security answer (sec_answer) by username.
 * This function is used by the admin tool to check/update old plain text answers.
 * @param mysqli $conn The MySQLi database connection object.
 * @param string $username The username to search for.
 * @return array|false Returns user data (ID and sec_answer) or false if not found.
 */
/**function getSecurityAnswerForHashing($conn, $username) {
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

/**
 * Updates the user's security answer with a new hash.
 * @param mysqli $conn The MySQLi database connection object.
 * @param int $user_id The ID of the user to update.
 * @param string $hashed_answer The newly generated hashed security answer.
 * @return bool True on success, false on failure.
 */
/**function updateHashedSecurityAnswer($conn, $user_id, $hashed_answer) {
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

?>
