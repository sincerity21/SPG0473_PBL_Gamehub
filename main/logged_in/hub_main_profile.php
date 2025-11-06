<?php
session_start();
require '../../hub_conn.php'; 

// --- 1. Authentication & Authorization ---
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ../../hub_login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);
$email = htmlspecialchars($_SESSION['email']); // Get email from session

// --- 2. Modal Error/Success Variables ---
$username_error = '';
$username_success = '';
$password_error = '';
$password_success = '';

// --- 3. Handle Form Submissions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // --- Handle Change Username ---
    if ($_POST['action'] === 'change_username') {
        $new_username = $_POST['new_username'] ?? '';
        $current_password = $_POST['current_password'] ?? '';

        if (empty($new_username) || empty($current_password)) {
            $username_error = "All fields are required.";
        } else {
            $result = updateUsername($user_id, $new_username, $current_password);
            if ($result === "success") {
                $username_success = "Username updated successfully!";
                $username = htmlspecialchars($new_username); // Update username on the page
            } else {
                $username_error = $result; // Show the error from the function
            }
        }
    }

    // --- Handle Change Password ---
    if ($_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
            $password_error = "All fields are required.";
        } elseif ($new_password !== $confirm_new_password) {
            $password_error = "New passwords do not match.";
        } elseif (strlen($new_password) < 8) {
            $password_error = "Password must be at least 8 characters long.";
        } else {
            $result = updateUserPasswordSecurely($user_id, $current_password, $new_password);
            if ($result === "success") {
                $password_success = "Password updated successfully!";
            } else {
                $password_error = $result; // Show the error
            }
        }
    }
}

// --- 4. Fetch Game Data ---
$games_list = selectUserInteractedGames($user_id);
$fallback_cover = 'uploads/placeholder.png'; // Fallback for games without covers

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - GameHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- 1. CSS Variables for Theming --- */
        :root {
            --bg-color: #f4f7f6;
            --main-text-color: #333;
            --accent-color: #3498db;
            --secondary-text-color: #7f8c8d;
            --card-bg-color: white;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --border-color: #ddd;
            --welcome-title-color: #2c3e50;
            --label-text-color: #555;
            --success-bg: #d4edda;
            --success-text: #155724;
            --success-border: #c3e6cb;
            --error-bg: #f8d7da;
            --error-text: #721c24;
            --error-border: #f5c6cb;
        }
        
        /* --- 2. Dark Mode Fix --- */
        html.dark-mode body {
            --bg-color: #121212; 
            --main-text-color: #f4f4f4; 
            --accent-color: #4dc2f9;
            --secondary-text-color: #95a5a6; 
            --card-bg-color: #1e1e1e; 
            --shadow-color: rgba(0, 0, 0, 0.4);
            --border-color: #444; 
            --welcome-title-color: #ecf0f1;
            --label-text-color: #bbb;
            --success-bg: #1a3a24;
            --success-text: #d4edda;
            --success-border: #2a5c3a;
            --error-bg: #3a1a1f;
            --error-text: #f8d7da;
            --error-border: #5c2a30;
        }

        /* --- 3. Base & Menu Styles --- */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: var(--bg-color); 
            color: var(--main-text-color); 
            min-height: 100vh; 
            transition: background-color 0.3s, color 0.3s; 
        }
        .header { 
            background-color: var(--card-bg-color); 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 4px var(--shadow-color); 
            position: sticky; top: 0; z-index: 1001; 
        }
        .logo { font-size: 24px; font-weight: 700; color: var(--accent-color); text-decoration: none; }
        .menu-toggle { background: none; border: none; cursor: pointer; font-size: 24px; color: var(--main-text-color); padding: 5px; }
        .side-menu { 
            position: fixed; top: 60px; right: 0; width: 220px; 
            background-color: var(--card-bg-color); 
            box-shadow: -4px 4px 8px var(--shadow-color); 
            border-radius: 8px 0 8px 8px; 
            padding: 10px 0; z-index: 1000; 
            transform: translateX(100%); 
            transition: transform 0.3s ease-in-out; 
        }
        .side-menu.open { transform: translateX(0); }
        .side-menu a, .menu-item { 
            display: block; padding: 12px 20px; 
            color: var(--main-text-color); 
            text-decoration: none; 
            transition: background-color 0.2s; 
            cursor: pointer; 
        }
        .side-menu a:hover, .menu-item:hover { background-color: var(--bg-color); color: var(--accent-color); }
        .side-menu a.active { background-color: var(--accent-color); color: white; font-weight: bold; }
        .side-menu a.active:hover { background-color: #2980b9; }
        .menu-divider { border-top: 1px solid var(--secondary-text-color); margin: 5px 0; }
        .logout-link { color: #e74c3c !important; font-weight: bold; }
        .icon { margin-right: 10px; width: 20px; text-align: center; }
        .dark-mode-label { display: flex; justify-content: space-between; align-items: center; user-select: none; }
        
        /* --- 4. Profile Page Layout --- */
        .profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 30px;
        }

        .account-panel, .games-panel {
            background-color: var(--card-bg-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px var(--shadow-color);
            padding: 25px;
        }

        .account-panel h2 {
            margin-top: 0;
            color: var(--welcome-title-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }
        .user-details p {
            font-size: 1.1em;
            color: var(--secondary-text-color);
            word-wrap: break-word;
        }
        .user-details p strong {
            color: var(--main-text-color);
        }
        .account-btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 1em;
            font-weight: bold;
            color: var(--accent-color);
            background-color: var(--bg-color);
            border: 2px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.2s;
        }
        .account-btn:hover {
            border-color: var(--accent-color);
            background-color: var(--card-bg-color);
        }
        
        .games-panel h2 {
            margin-top: 0;
            color: var(--welcome-title-color);
        }
        
        .sort-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 15px;
            background-color: var(--bg-color);
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .sort-controls label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
        }
        .sort-controls input[type="radio"] {
            margin-right: 5px;
            accent-color: var(--accent-color);
        }

        .game-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .game-card {
            background-color: var(--bg-color);
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: var(--main-text-color);
            box-shadow: 0 2px 5px var(--shadow-color);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .game-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px var(--shadow-color);
        }
        .game-card img {
            width: 100%;
            height: auto;
            aspect-ratio: 460 / 215;
            object-fit: cover;
            display: block;
            border-bottom: 3px solid var(--accent-color);
        }
        .game-card-info {
            padding: 15px;
        }
        .game-card-title {
            font-weight: bold;
            font-size: 1.1em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0 0 10px 0;
        }
        .game-card-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            color: var(--secondary-text-color);
        }
        .game-card-stats .stat-rating {
            color: #f39c12;
            font-weight: bold;
        }
        .game-card-stats .stat-fav {
            color: #e74c3c;
        }
        .game-card-stats .icon {
            margin-right: 5px;
        }
        
        /* --- 5. Modal Styles --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7); z-index: 2000; display: none;
            align-items: center; justify-content: center; overflow-y: auto;
        }
        .modal-container {
            background-color: var(--card-bg-color); padding: 30px; border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); position: relative;
            width: 100%; max-width: 500px; color: var(--main-text-color); margin: 20px;
        }
        .modal-close {
            position: absolute; top: 10px; right: 15px; font-size: 28px;
            font-weight: bold; color: var(--secondary-text-color);
            background: none; border: none; cursor: pointer;
        }
        .modal-container h2 {
            color: var(--welcome-title-color); text-align: center; margin-top: 0;
            margin-bottom: 25px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px;
        }
        .modal-container .form-group { margin-bottom: 20px; }
        .modal-container label {
            display: block; margin-bottom: 8px; font-weight: bold;
            color: var(--label-text-color);
        }
        .modal-container input[type="text"],
        .modal-container input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid var(--border-color);
            border-radius: 4px; box-sizing: border-box; font-size: 16px;
            background-color: var(--bg-color); color: var(--main-text-color);
        }
        .modal-container .btn {
            width: 100%; padding: 12px; background-color: var(--accent-color); 
            color: white; border: none; border-radius: 4px; font-size: 18px;
            cursor: pointer; transition: background-color 0.3s; margin-top: 10px;
        }
        .modal-container .btn:hover { background-color: #2980b9; }
        .modal-container .message { 
            padding: 10px; border-radius: 4px; margin-bottom: 15px; 
            text-align: center; font-weight: bold;
        }
        .modal-container .error { 
            background-color: var(--error-bg); color: var(--error-text); 
            border: 1px solid var(--error-border); 
        }
        .modal-container .success { 
            background-color: var(--success-bg); color: var(--success-text); 
            border: 1px solid var(--success-border); 
        }

    </style>

    <!-- === DARK MODE FIX SCRIPT === -->
    <script>
        (function() {
            const localStorageKey = 'gamehubDarkMode'; 
            if (localStorage.getItem(localStorageKey) === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

</head>
<body>

<div class="header">
    <div class="logo">GAMEHUB</div>
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="side-menu" id="sideMenu">
    <a href="hub_home_logged_in.php"><span class="icon"><i class="fas fa-home"></i></span>Home</a>
    <a href="hub_home_category_logged_in.php"><span class="icon"><i class="fas fa-book-open"></i></span>Library</a> 
    <a href="hub_main_profile.php" class="active"><span class="icon"><i class="fas fa-user-circle"></i></span>Profile</a>
    <a href="hub_main_about_logged_in.php"><span class="icon"><i class="fas fa-info-circle"></i></span>About</a>
    <div class="menu-divider"></div>
    <div class="menu-item dark-mode-label" onclick="toggleDarkMode()">
        <span class="icon"><i class="fas fa-moon"></i></span>
        <span id="darkModeText">Switch Dark Mode</span>
    </div>
    <div class="menu-divider"></div>
    <a href="../../hub_logout.php" class="logout-link">
        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
        Logout
    </a>
</div>

<div class="profile-container">

    <aside class="account-panel">
        <h2>ACCOUNT</h2>
        <div class="user-details">
            <p><strong>Username:</strong> <span id="currentUsername"><?php echo $username; ?></span></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
        </div>
        <button class="account-btn" onclick="openModal('changeUsernameModal')">Change Username</button>
        <button class="account-btn" onclick="openModal('changePasswordModal')">Change Password</button>
    </aside>

    <main class="games-panel">
        <h2>YOUR GAMES</h2>
        <div class="sort-controls" id="sortControls">
            <label>
                <input type="radio" name="sort" value="alpha" checked>
                Alphabetical
            </label>
            <label>
                <input type="radio" name="sort" value="rating">
                Your Rating
            </label>
            <label>
                <input type="radio" name="sort" value="category">
                Category
            </label>
            <label>
                <input type="checkbox" id="showFavourites">
                Show Favourites Only
            </label>
        </div>

        <div class="game-grid" id="gameGrid">
            <?php if (!empty($games_list)): ?>
                <?php foreach ($games_list as $game): ?>
                    <?php
                    $cover_path = !empty($game['cover_path']) ? $game['cover_path'] : $fallback_cover;
                    ?>
                    <a href="hub_main_profile_game_details.php?game_id=<?php echo $game['game_id']; ?>" 
                       class="game-card"
                       data-name="<?php echo htmlspecialchars($game['game_name']); ?>"
                       data-category="<?php echo htmlspecialchars($game['game_category']); ?>"
                       data-rating="<?php echo htmlspecialchars($game['user_rating']); ?>"
                       data-favourite="<?php echo htmlspecialchars($game['user_favourite']); ?>">
                        
                        <img src="../../<?php echo htmlspecialchars($cover_path); ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?> Cover">
                        
                        <div class="game-card-info">
                            <h3 class="game-card-title"><?php echo htmlspecialchars($game['game_name']); ?></h3>
                            <div class="game-card-stats">
                                <span class="stat-rating">
                                    <i class="icon <?php echo ($game['user_rating'] > 0) ? 'fas' : 'far'; ?> fa-star"></i>
                                    <?php echo $game['user_rating'] > 0 ? $game['user_rating'] : '-'; ?>/5
                                </span>
                                <?php if ($game['user_favourite'] == 1): ?>
                                    <span class="stat-fav">
                                        <i class="icon fas fa-heart"></i>
                                        Favourite
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have not rated or favourited any games yet. Go to the Library to get started!</p>
            <?php endif; ?>
        </div>
    </main>

</div>

<?php
    // --- 6. Include Modals ---
    include 'modal_change_username.php';
    include 'modal_change_password.php';
?>

<script>
    // --- 1. Side Menu Toggle Logic ---
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('sideMenu').classList.toggle('open');
    });

    // --- 2. Updated Dark Mode Logic (Fixes Flicker) ---
    const darkModeText = document.getElementById('darkModeText');
    const localStorageKey = 'gamehubDarkMode';
    const htmlElement = document.documentElement; 

    function applyDarkMode(isDark) {
        if (isDark) {
            htmlElement.classList.add('dark-mode');
            if (darkModeText) darkModeText.textContent = 'Switch Light Mode';
        } else {
            htmlElement.classList.remove('dark-mode');
            if (darkModeText) darkModeText.textContent = 'Switch Dark Mode';
        }
    }

    function toggleDarkMode() {
        const isDark = htmlElement.classList.contains('dark-mode');
        applyDarkMode(!isDark);
        localStorage.setItem(localStorageKey, !isDark ? 'dark' : 'light');
    }

    (function loadButtonText() {
        const isDark = htmlElement.classList.contains('dark-mode');
        applyDarkMode(isDark);
    })();

    // --- 3. Modal Control Logic ---
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
        
        // Clear success/error messages when closing
        const successMsg = modal.querySelector('.success');
        const errorMsg = modal.querySelector('.error');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    }
    
    // Auto-open modal if PHP has set an error/success
    <?php if (!empty($username_error) || !empty($username_success)): ?>
        openModal('changeUsernameModal');
    <?php elseif (!empty($password_error) || !empty($password_success)): ?>
        openModal('changePasswordModal');
    <?php endif; ?>

    // --- 4. Sorting and Filtering Logic ---
    document.addEventListener('DOMContentLoaded', function() {
        const controls = document.getElementById('sortControls');
        const grid = document.getElementById('gameGrid');
        // Convert NodeList to Array to make it sortable
        const allGames = Array.from(grid.getElementsByClassName('game-card'));

        function applySortAndFilter() {
            const sortType = controls.querySelector('input[name="sort"]:checked').value;
            const showFavourites = controls.querySelector('#showFavourites').checked;

            // 1. Filter
            let filteredGames = allGames.filter(game => {
                if (showFavourites) {
                    return game.dataset.favourite == '1';
                }
                return true; // Show all
            });

            // 2. Sort
            if (sortType === 'alpha') {
                filteredGames.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
            } else if (sortType === 'rating') {
                filteredGames.sort((a, b) => b.dataset.rating - a.dataset.rating);
            } else if (sortType === 'category') {
                filteredGames.sort((a, b) => {
                    if (a.dataset.category === b.dataset.category) {
                        // If categories are same, sort by name
                        return a.dataset.name.localeCompare(b.dataset.name);
                    }
                    return a.dataset.category.localeCompare(b.dataset.category);
                });
            }

            // 3. Re-append to grid
            // Clear grid
            grid.innerHTML = '';
            // Append sorted/filtered items
            filteredGames.forEach(game => grid.appendChild(game));
            
            if (filteredGames.length === 0) {
                grid.innerHTML = '<p>No games match your criteria.</p>';
            }
        }

        // Add event listeners to all controls
        controls.addEventListener('change', applySortAndFilter);

        // Initial sort (default is alphabetical, which is already done by PHP)
    });

</script>

</body>
</html>