<?php
session_start();
require '../hub_conn.php'; 
include '../modal_login.php';
include '../modal_register.php';

$login_error = '';
$register_error = '';

if ($_POST) {
    // Check which action is being performed
    $action = $_POST['action'] ?? '';

    // --- LOGIN LOGIC ---
    if ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = loginUser($username, $password);

        if($result){
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['user_username'];
            $_SESSION['email'] = $result['user_email'];

            if (isset($result['is_admin']) && $result['is_admin'] == 1) {
                $_SESSION['is_admin'] = true;
                header("Location: ../admin/user/hub_admin_user.php"); 
            } else {
                $_SESSION['is_admin'] = false;
                header("Location: logged_in/hub_home_logged_in.php");
            }
            exit(); 
        } else {
            $login_error = "Login Unsuccessful. Check your username and password.";
        }
    }

    // --- REGISTER LOGIC ---
    if ($action === 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $server = $_POST['server'];
        $prompt = $_POST['prompt'];
        $answer = $_POST['answer'];

        if (empty($username) || empty($email) || empty($password) || empty($answer)) {
            $register_error = "You must fill in all fields.";
        } else {
            $success = registerUser($username, $email, $password, $server, $prompt, $answer);
            
            if ($success) {
                // On success, redirect to the login page (or show the login modal)
                header('Location: ../hub_login.php?status=registered'); // Simple redirect
                exit();
            } else {
                $register_error = "Registration failed. Username or email may already be in use.";
            }
        }
    }
}

// --- 1. Get and Validate Game ID ---
if (!isset($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
    header('Location: hub_home_category.php'); // Redirect if no valid game ID
    exit();
}

$game_id = (int)$_GET['game_id'];

// --- 2. Fetch All Data ---
$game = selectGameByID($game_id);

if (!$game) {
    header('Location: hub_home_category.php');
    exit();
}

$gallery_images = selectGameGalleryImages($game_id);

// --- 3. Set Defaults for Logged-out state ---
$current_rating = 0;
$is_favorite = 0;

// Fallback image if gallery is empty
$fallback_path = 'uploads/placeholder.png';
if (empty($gallery_images)) {
    $gallery_images[] = ['img_path' => $fallback_path];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['game_name']); ?> - GameHub</title>
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
            --star-color: #f39c12;
            --heart-color: #e74c3c;
            --login-color: #2ecc71; /* Green for login */
        }
        body.dark-mode {
            --bg-color: #121212; --main-text-color: #f4f4f4; --accent-color: #4dc2f9;
            --secondary-text-color: #95a5a6; --card-bg-color: #1e1e1e; --shadow-color: rgba(0, 0, 0, 0.4);
            --border-color: #444; --welcome-title-color: #ecf0f1;
            --login-color: #27ae60; /* Darker green */
        }

        /* --- 2. Base & Menu Styles --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: var(--bg-color); color: var(--main-text-color); min-height: 100vh; transition: background-color 0.3s, color 0.3s; }
        .header { background-color: var(--card-bg-color); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px var(--shadow-color); position: sticky; top: 0; z-index: 1001; }
        .logo { font-size: 24px; font-weight: 700; color: var(--accent-color); text-decoration: none; }
        .menu-toggle { background: none; border: none; cursor: pointer; font-size: 24px; color: var(--main-text-color); padding: 5px; }
        .side-menu { position: fixed; top: 60px; right: 0; width: 220px; background-color: var(--card-bg-color); box-shadow: -4px 4px 8px var(--shadow-color); border-radius: 8px 0 8px 8px; padding: 10px 0; z-index: 1000; transform: translateX(100%); transition: transform 0.3s ease-in-out; }
        .side-menu.open { transform: translateX(0); }
        .side-menu a, .menu-item { display: block; padding: 12px 20px; color: var(--main-text-color); text-decoration: none; transition: background-color 0.2s; cursor: pointer; }
        .side-menu a:hover, .menu-item:hover { background-color: var(--bg-color); color: var(--accent-color); }
        .side-menu a.active { background-color: var(--accent-color); color: white; font-weight: bold; }
        .side-menu a.active:hover { background-color: #2980b9; }
        /* --- NEW: Login Link Style --- */
        .side-menu a.login-link {
            color: var(--login-color) !important;
            font-weight: bold;
        }
        .side-menu a.login-link:hover {
            background-color: var(--bg-color);
            color: #2ecc71 !important;
        }
        /* --- END NEW --- */
        .menu-divider { border-top: 1px solid var(--secondary-text-color); margin: 5px 0; }
        .icon { margin-right: 10px; width: 20px; text-align: center; }
        .dark-mode-label { display: flex; justify-content: space-between; align-items: center; user-select: none; }
        
        /* --- 3. Page Layout (Sketch) --- */
        .content-container {
            max-width: 1000px; 
            margin: 0 auto;
            padding: 30px;
        }

        .game-detail-layout {
            display: grid;
            grid-template-columns: 1fr 1fr; 
            gap: 40px;
            align-items: flex-start;
        }
        
        /* --- 4. Left Column: Slideshow --- */
        .image-slideshow {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            overflow: hidden;
            border-radius: 8px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
        }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out; }
        .slide.active { opacity: 1; }
        .slide img { width: 100%; height: 100%; object-fit: cover; }
        .slider-control { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0, 0, 0, 0.4); color: white; border: none; padding: 10px; cursor: pointer; z-index: 10; font-size: 1.5em; }
        .slider-control:hover { background: rgba(0, 0, 0, 0.6); }
        .prev { left: 0; border-radius: 0 5px 5px 0; }
        .next { right: 0; border-radius: 5px 0 0 5px; }
        .slide-indicators { position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 5px; }
        .dot { display: inline-block; width: 10px; height: 10px; background: rgba(255, 255, 255, 0.5); border-radius: 50%; cursor: pointer; transition: background 0.3s; }
        .dot.active { background: white; }

        /* --- 5. Right Column: Game Info --- */
        .game-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .game-title-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
        }
        .game-title {
            font-size: 2.5em;
            font-weight: 600;
            color: var(--welcome-title-color);
            margin: 0;
            line-height: 1.1;
        }
        .game-desc {
            font-size: 1.1em;
            color: var(--secondary-text-color);
            line-height: 1.6;
        }

        /* Heart Icon */
        .favorite-icon {
            font-size: 2.5em;
            color: var(--border-color);
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
        }
        .favorite-icon.active { /* This class will just be on the 'far' icon */
            color: var(--heart-color);
        }

        /* Star Rating */
        .star-rating {
            font-size: 2em;
            color: var(--star-color);
        }
        .star-rating .star {
            cursor: pointer;
            transition: transform 0.1s;
        }
        .star-rating .star:hover {
            transform: scale(1.2);
        }

        /* Trailer & Next Buttons */
        .trailer-link, .next-link {
            display: inline-block;
            padding: 12px 20px;
            font-size: 1.1em;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .trailer-link {
            background-color: var(--card-bg-color);
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
        }
        .trailer-link:hover {
            background-color: var(--accent-color);
            color: white;
        }
        .next-link {
            background-color: #8e44ad; 
            color: white;
            border: 2px solid #8e44ad;
            margin-top: 20px;
        }
        .next-link:hover {
            background-color: #9b59b6;
        }
        
        /* Back link */
        .back-link {
            display: inline-block;
            margin-top: 20px; /* Added margin */
            margin-bottom: 20px;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover { text-decoration: underline; }
        /* Dark Mode Styling (Toggle placeholder styling updated) */
        .dark-mode-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }
        .dark-mode-label .icon {
            font-size: 1.2em;
        }
        /* --- Modal Styles --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            display: none; /* Hidden by default */
            align-items: center;
            justify-content: center;
        }
        .modal-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            width: 100%;
            max-width: 500px; /* Width of register modal */
            
            /* Dark Mode styles for modal content */
            color: #333; 
        }
        body.dark-mode .modal-container {
            background-color: var(--card-bg-color);
            color: var(--main-text-color);
        }
        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
        }
        body.dark-mode .modal-close {
            color: var(--secondary-text-color);
        }
        /* --- Form Styles (from login/register) --- */
        .modal-container h2 {
            color: #2c3e50;
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        body.dark-mode .modal-container h2 {
            color: var(--welcome-title-color);
            border-color: var(--accent-color);
        }
        .modal-container .form-group { 
            margin-bottom: 20px; 
        }
        .modal-container label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold;
            color: #555;
        }
        body.dark-mode .modal-container label {
            color: var(--secondary-text-color);
        }
        .modal-container input[type="text"],
        .modal-container input[type="email"],
        .modal-container input[type="password"],
        .modal-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px; 
            box-sizing: border-box; 
            font-size: 16px;
            
            /* Dark mode form inputs */
            background-color: white;
            color: #333;
        }
        body.dark-mode .modal-container input[type="text"],
        body.dark-mode .modal-container input[type="email"],
        body.dark-mode .modal-container input[type="password"],
        body.dark-mode .modal-container select {
            background-color: var(--bg-color);
            color: var(--main-text-color);
            border-color: var(--border-color);
        }
        .modal-container .btn {
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
        .modal-container .btn:hover {
            background-color: #2980b9;
        }
        .modal-container .error {
            background-color: #fdd; 
            color: #c00; 
            padding: 10px; 
            border: 1px solid #f99;
            border-radius: 4px;
            margin-bottom: 15px; 
            text-align: center;
            font-weight: bold;
        }
        .modal-container .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .modal-container .register-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .modal-container .register-link a:hover {
            text-decoration: underline;
        }
        .modal-container .forgot-link {
            text-align: right;
            margin-top: -15px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .modal-container .forgot-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        body.dark-mode .modal-container .register-link a,
        body.dark-mode .modal-container .forgot-link a {
            color: var(--accent-color);
        }
    </style>
</head>
<body id="appBody">

<div class="header">
    <div class="logo">GAMEHUB</div>
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="side-menu" id="sideMenu">
    <a href="hub_home.php"><span class="icon"><i class="fas fa-home"></i></span>Home</a>
    <a href="hub_home_category.php" class="active"><span class="icon"><i class="fas fa-book-open"></i></span>Library</a> 
    <a href="hub_main_about.php"><span class="icon"><i class="fas fa-info-circle"></i></span>About</a>
    
    <div class="menu-divider"></div>

    <a href="#" class="login-link" onclick="openModal('loginModal')"><span class="icon"><i class="fas fa-sign-in-alt"></i></span>Login</a>
    
    <div class="menu-divider"></div>
    <div class="menu-item dark-mode-label" onclick="toggleDarkMode()">
        <span class="icon"><i class="fas fa-moon"></i></span>
        <span id="darkModeText">Switch Dark Mode</span>
    </div>
</div>

<div class="content-container">
    
    <a href="hub_home_category.php" class="back-link">
        <i class="fas fa-chevron-left"></i> Back to Library
    </a>

    <div class="game-detail-layout">
        
        <div class="image-slideshow">
            <div id="slideshow-content">
                <?php foreach ($gallery_images as $index => $image): ?>
                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="../<?php echo htmlspecialchars($image['img_path']); ?>" alt="Game Screenshot">
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($gallery_images) > 1): ?>
                <button type="button" class="slider-control prev" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="slider-control next" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="slide-indicators" id="slide-indicators">
                    <?php for ($i = 0; $i < count($gallery_images); $i++): ?>
                        <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></span>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="game-info">
            <div class="game-title-header">
                <h2 class="game-title"><?php echo htmlspecialchars($game['game_name']); ?></h2>
                <i class="far fa-heart favorite-icon <?php echo $is_favorite ? 'active' : ''; ?>" 
                   id="favoriteIcon"></i>
            </div>
            
            <p class="game-desc"><?php echo nl2br(htmlspecialchars($game['game_desc'])); ?></p>
            
            <div class="star-rating" id="starRating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="star far fa-star" data-value="<?php echo $i; ?>"></i>
                <?php endfor; ?>
            </div>

            <a href="<?php echo htmlspecialchars($game['game_trailerLink']); ?>" class="trailer-link" target="_blank">
                <i class="fab fa-youtube"></i> Watch the Trailer (on YouTube)
            </a>

            <a href="#" class="next-link" onclick="openModal('loginModal')">
                LOGIN TO CONTINUE
            </a>
        </div>
    </div>
</div>

<script>
    // --- 1. Side Menu & Dark Mode (Standard) ---
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('sideMenu').classList.toggle('open');
    });

    const body = document.getElementById('appBody');
    const darkModeText = document.getElementById('darkModeText');
    const localStorageKey = 'gamehubDarkMode';

    function applyDarkMode(isDark) {
        if (isDark) {
            body.classList.add('dark-mode');
            if(darkModeText) darkModeText.textContent = 'Switch Light Mode';
        } else {
            body.classList.remove('dark-mode');
            if(darkModeText) darkModeText.textContent = 'Switch Dark Mode';
        }
    }
    function toggleDarkMode() {
        const isDark = body.classList.contains('dark-mode');
        applyDarkMode(!isDark);
        localStorage.setItem(localStorageKey, !isDark ? 'dark' : 'light');
    }
    (function loadDarkModePreference() {
        applyDarkMode(localStorage.getItem(localStorageKey) === 'dark');
    })();

    // --- 2. Slideshow Logic ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('#slideshow-content .slide');
    const dots = document.querySelectorAll('#slide-indicators .dot');
    const totalSlides = slides.length;
    let slideTimer = null;

    function showSlide(n) {
        if (totalSlides === 0) return;
        currentSlide = (n + totalSlides) % totalSlides; // Wraps around
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        if (slides[currentSlide]) slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }
    function changeSlide(n) {
        stopAutoSlide();
        showSlide(currentSlide + n);
        startAutoSlide();
    }
    function startAutoSlide() {
        if (totalSlides > 1 && !slideTimer) {
            slideTimer = setInterval(() => showSlide(currentSlide + 1), 5000);
        }
    }
    function stopAutoSlide() {
        clearInterval(slideTimer);
        slideTimer = null;
    }
    document.addEventListener('DOMContentLoaded', () => {
        if(totalSlides > 0) showSlide(0);
        startAutoSlide();
    });


    // --- 3. NEW: Logged-out Feedback Redirect ---
    const loginRedirect = () => {
        window.location.href = '../hub_login.php';
    };

    const favoriteIcon = document.getElementById('favoriteIcon');
    if (favoriteIcon) {
        favoriteIcon.addEventListener('click', loginRedirect);
    }

    const starRatingContainer = document.getElementById('starRating');
    if (starRatingContainer) {
        const stars = starRatingContainer.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', loginRedirect);
        });
    }

    // --- NEW Modal JavaScript ---
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    function switchToModal(fromModalId, toModalId) {
        closeModal(fromModalId);
        openModal(toModalId);
    }
    
    // Auto-open modal if there was a PHP error
    <?php if (!empty($login_error)): ?>
        openModal('loginModal');
    <?php endif; ?>
    
    <?php if (!empty($register_error)): ?>
        openModal('registerModal');
    <?php endif; ?>
</script>

</body>
</html>