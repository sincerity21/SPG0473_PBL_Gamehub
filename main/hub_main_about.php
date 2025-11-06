<?php
// No session required for this public page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - GameHub</title>
    <!-- Using Font Awesome 6 for social icons -->
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
            --card-info-bg: #f39c12; /* Orange from sketch */
            --card-info-text: #333;
        }
        body.dark-mode {
            --bg-color: #121212; 
            --main-text-color: #f4f4f4; 
            --accent-color: #4dc2f9;
            --secondary-text-color: #95a5a6; 
            --card-bg-color: #1e1e1e; 
            --shadow-color: rgba(0, 0, 0, 0.4);
            --border-color: #444; 
            --welcome-title-color: #ecf0f1;
            --card-info-bg: #e67e22; /* Slightly darker orange for dark mode */
            --card-info-text: #fff;
        }

        /* --- 2. Base & Menu Styles (from hub_home.php) --- */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: var(--bg-color); color: var(--main-text-color); min-height: 100vh; transition: background-color 0.3s, color 0.3s; }
        .header { background-color: var(--card-bg-color); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px var(--shadow-color); position: sticky; top: 0; z-index: 1001; }
        .logo { font-size: 24px; font-weight: 700; color: var(--accent-color); text-decoration: none; }
        .menu-toggle { background: none; border: none; cursor: pointer; font-size: 24px; color: var(--main-text-color); padding: 5px; }
        
        /* --- 3. Side Menu (Reduced Logged-out Version) --- */
        .side-menu { position: fixed; top: 60px; right: 0; width: 220px; background-color: var(--card-bg-color); box-shadow: -4px 4px 8px var(--shadow-color); border-radius: 8px 0 8px 8px; padding: 10px 0; z-index: 1000; transform: translateX(100%); transition: transform 0.3s ease-in-out; }
        .side-menu.open { transform: translateX(0); }
        .side-menu a, .menu-item { display: block; padding: 12px 20px; color: var(--main-text-color); text-decoration: none; transition: background-color 0.2s; cursor: pointer; }
        .side-menu a:hover, .menu-item:hover { background-color: var(--bg-color); color: var(--accent-color); }
        .side-menu a.active { background-color: var(--accent-color); color: white; font-weight: bold; }
        .side-menu a.active:hover { background-color: #2980b9; }
        .menu-divider { border-top: 1px solid var(--secondary-text-color); margin: 5px 0; }
        .icon { margin-right: 10px; width: 20px; text-align: center; }
        .dark-mode-label { display: flex; justify-content: space-between; align-items: center; user-select: none; }
        
        /* --- 4. Content & Team Card Styles (Sketch) --- */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }
        .greeting {
            font-size: 2.5em;
            font-weight: 600;
            color: var(--welcome-title-color);
            margin: 0;
        }
        .wave-divider {
            width: 100%;
            height: 30px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .wave-divider svg {
            width: 100%;
            height: 100%;
            stroke: var(--accent-color);
            stroke-width: 2;
            fill: none;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
        .team-card {
            background-color: var(--card-bg-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px var(--shadow-color);
            overflow: hidden;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px var(--shadow-color);
        }
        .card-image {
            width: 100%;
            height: 350px;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-info {
            background-color: var(--card-info-bg);
            padding: 20px;
            color: var(--card-info-text);
        }
        .card-info h3 {
            margin: 0 0 5px 0;
            color: var(--card-info-text);
            font-size: 1.4em;
        }
        .card-info p {
            margin: 0 0 15px 0;
            font-size: 0.9em;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            min-height: 32px; /* Ensures all cards have same height */
        }
        .social-links a {
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            font-size: 1.1em;
            color: var(--card-info-text);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 4px;
            transition: all 0.2s;
        }
        .social-links a:hover {
            background-color: white;
            transform: scale(1.1);
        }
        body.dark-mode .social-links a {
             background-color: rgba(0, 0, 0, 0.2);
        }
        body.dark-mode .social-links a:hover {
             background-color: rgba(0, 0, 0, 0.4);
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


<!-- Side Menu (Reduced Logged-out Version) -->
<div class="side-menu" id="sideMenu">
    <a href="hub_home.php"><span class="icon"><i class="fas fa-home"></i></span>Home</a>
    <a href="hub_main_about.php" class="active"><span class="icon"><i class="fas fa-info-circle"></i></span>About</a>
    <div class="menu-divider"></div>
    <div class="menu-item dark-mode-label" onclick="toggleDarkMode()">
        <span class="icon"><i class="fas fa-moon"></i></span>
        <span id="darkModeText">Switch Dark Mode</span>
    </div>
</div>

<div class="content-container">

    <h1 class="greeting">Let's meets our AMAZING TEAMS</h1>
    <div class="wave-divider">
        <svg viewBox="0 0 100 10" preserveAspectRatio="none">
            <path d="M 0 5 C 25 10, 75 0, 100 5" />
        </svg>
    </div>

    <div class="team-grid">
        
        <!-- Team Member 1 -->
        <div class="team-card">
            <div class="card-image">
                <img src="../uploads/members/iman.jpg" alt="Team Member 1 Photo">
            </div>
            <div class="card-info">
                <h3>IRELAND BOI</h3>
                <p>FRONT-END,
                HTML</p>
                <div class="social-links">
                    <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Team Member 2 -->
        <div class="team-card">
            <div class="card-image">
                <img src="../uploads/members/anwar.jpg" alt="Team Member 2 Photo">
            </div>
            <div class="card-info">
                <h3>ANUAT</h3>
                <p>DESIGN,
                GUI</p>
                <div class="social-links">
                    <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Team Member 3 (No socials) -->
        <div class="team-card">
            <div class="card-image">
                <img src="../uploads/members/fawwaz.jpg" alt="Team Member 3 Photo">
            </div>
            <div class="card-info">
                <h3>FAWWAZ</h3>
                <p>BACK-END,
                DATABASE</p>
                <div class="social-links">
                    <!-- No links as requested -->
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // --- Standard Menu & Dark Mode JS ---
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
</script>

</body>
</html>

