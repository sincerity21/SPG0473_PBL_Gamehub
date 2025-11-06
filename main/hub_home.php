<?php
// session_start(); // Removed - No login required

// Ensure the user is logged in, otherwise redirect them to the login page
// Removed authentication check
// if (!isset($_SESSION['username'])) {
//    header('Location: ../hub_login.php');
//    exit();
// }

// $username = htmlspecialchars($_SESSION['username']); // Removed - No user
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- 1. CSS Variables for Theming --- */
        :root {
            /* Light Mode Defaults */
            --bg-color: #f4f7f6;
            --main-text-color: #333;
            --accent-color: #3498db;
            --secondary-text-color: #7f8c8d;
            --card-bg-color: white;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --wave-opacity: 0.15;
            --welcome-title-color: #2c3e50;
        }

        /* Dark Mode Override */
        body.dark-mode {
            --bg-color: #121212;
            --main-text-color: #f4f4f4;
            --accent-color: #4dc2f9; /* Lighter blue for visibility */
            --secondary-text-color: #95a5a6;
            --card-bg-color: #1e1e1e;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --wave-opacity: 0.05;
            --welcome-title-color: #ecf0f1;
        }


        /* Base Setup */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color); /* Uses variable */
            color: var(--main-text-color); /* Uses variable */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }

        /* Header (Top Bar) */
        .header {
            background-color: var(--card-bg-color); /* Uses variable */
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px var(--shadow-color); /* Uses variable */
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent-color); /* Uses variable */
        }

        /* Menu Toggle Button */
        .menu-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--main-text-color); /* Uses variable */
            padding: 5px;
            transition: color 0.2s;
        }
        .menu-toggle:hover {
            color: var(--accent-color);
        }

        /* Side Menu Styles (Matching Sketch) */
        .side-menu {
            position: fixed;
            top: 60px; /* Below the header */
            right: 0;
            width: 220px;
            background-color: var(--card-bg-color); /* Uses variable */
            box-shadow: -4px 4px 8px var(--shadow-color); /* Uses variable */
            border-radius: 8px 0 8px 8px;
            padding: 10px 0;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .side-menu.open {
            transform: translateX(0);
        }
        .side-menu a, .menu-item {
            display: block;
            padding: 12px 20px;
            color: var(--main-text-color); /* Uses variable */
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }
        .side-menu a:hover, .menu-item:hover {
            background-color: var(--bg-color);
            color: var(--accent-color);
        }
        .side-menu a.active { background-color: var(--accent-color); color: white; font-weight: bold; }
        .side-menu a.active:hover { background-color: #2980b9; }
        .menu-divider {
            border-top: 1px solid var(--secondary-text-color);
            margin: 5px 0;
        }
        /* Removed .logout-link styles as it's no longer present */
        .icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }


        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 50px 20px;
            position: relative;
        }
        .welcome-title {
            font-size: 3.5em;
            font-weight: 600;
            color: var(--welcome-title-color); /* Uses variable */
            margin-bottom: 10px;
        }
        .welcome-subtitle {
            font-size: 1.2em;
            color: var(--secondary-text-color); /* Uses variable */
            margin-bottom: 40px;
        }

        /* Login Button (Modified from .start-button) */
        .login-button {
            padding: 15px 40px;
            background: #2ecc71; /* Green for login */
            color: white;
            text-decoration: none;
            border: 2px solid #27ae60;
            border-radius: 6px;
            font-size: 1.2em;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease-in-out;
            margin-top: 50px;
        }
        .login-button:hover {
            background: #27ae60;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.25);
            transform: translateY(-2px);
        }

        /* Wave Separator (Visual effect from sketch) */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px; /* Space for the wave */
            overflow: hidden;
            z-index: 1;
        }
        .wave {
            position: absolute;
            width: 200%;
            height: 200%;
            background: var(--accent-color); /* Uses variable */
            border-radius: 40%;
            bottom: -150%; 
            left: -50%;
            opacity: var(--wave-opacity); /* Uses variable */
            animation: wave-motion 10s linear infinite;
        }
        .wave:nth-child(2) {
            opacity: calc(var(--wave-opacity) / 1.5);
            animation: wave-motion 15s linear infinite reverse;
            bottom: -160%;
            border-radius: 45%;
        }

        @keyframes wave-motion {
            0% { transform: translate(0, 0); }
            50% { transform: translate(-25%, 5%); }
            100% { transform: translate(0, 0); }
        }
        
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
    </style>
</head>
<body id="appBody">

<div class="header">
    <div class="logo">GAMEHUB</div>
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
</div>

<!-- Side Menu (Modified for "before login" state) -->
<div class="side-menu" id="sideMenu">
    
    <!-- === REMOVED Home === -->
    
    <!-- === UPDATED About Link === -->
    <a href="hub_home.php" class="active"><span class="icon"><i class="fas fa-home"></i></span>Home</a>
    <a href="hub_main_about.php"><span class="icon"><i class="fas fa-info-circle"></i></span>About</a>

    <div class="menu-divider"></div>
    
    <!-- Switch Dark Mode Button -->
    <div class="menu-item dark-mode-label" onclick="toggleDarkMode()">
        <span class="icon"><i class="fas fa-moon"></i></span>
        <span id="darkModeText">Switch Dark Mode</span>
    </div>

    <!-- === REMOVED Logout === -->
    
</div>

<div class="main-content">
    <h1 class="welcome-title">WELCOME</h1>
    <p class="welcome-subtitle">
        This is the GameHub, where you can rate your favourite games.
    </p>

    <!-- Changed to LOGIN button -->
    <a href="../hub_login.php" class="login-button">LOGIN</a>

    <!-- Blue Wave Background Effect -->
    <div class="wave-container">
        <div class="wave"></div>
        <div class="wave"></div>
    </div>
</div>

<script>
    document.getElementById('menuToggle').addEventListener('click', function() {
        const menu = document.getElementById('sideMenu');
        menu.classList.toggle('open');
    });

    // --- Dark Mode Logic ---

    const body = document.getElementById('appBody');
    const darkModeText = document.getElementById('darkModeText');
    const localStorageKey = 'gamehubDarkMode';

    function applyDarkMode(isDark) {
        if (isDark) {
            body.classList.add('dark-mode');
            if (darkModeText) darkModeText.textContent = 'Switch Light Mode';
        } else {
            body.classList.remove('dark-mode');
            if (darkModeText) darkModeText.textContent = 'Switch Dark Mode';
        }
    }

    function toggleDarkMode() {
        const isDark = body.classList.contains('dark-mode');
        
        // Toggle the state
        applyDarkMode(!isDark);

        // Save preference to local storage
        localStorage.setItem(localStorageKey, !isDark ? 'dark' : 'light');
    }

    // Load saved preference when the script runs
    (function loadDarkModePreference() {
        const savedMode = localStorage.getItem(localStorageKey);
        
        if (savedMode === 'dark') {
            applyDarkMode(true);
        } else {
            applyDarkMode(false);
        }
    })();
</script>

</body>
</html>

