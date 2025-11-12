-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 03:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamehub`
--

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `favourite_id` int(11) NOT NULL,
  `favourite_game` tinyint(1) NOT NULL DEFAULT 0,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`favourite_id`, `favourite_game`, `game_id`, `user_id`) VALUES
(1, 1, 7, 3),
(4, 1, 38, 3),
(5, 1, 14, 3),
(6, 1, 29, 3),
(7, 1, 39, 1),
(8, 1, 39, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_game`
--

CREATE TABLE `feedback_game` (
  `feedback_game_id` int(11) NOT NULL,
  `feedback_game_frequency` varchar(255) DEFAULT NULL,
  `feedback_game_open` text NOT NULL,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_game`
--

INSERT INTO `feedback_game` (`feedback_game_id`, `feedback_game_frequency`, `feedback_game_open`, `game_id`, `user_id`) VALUES
(4, 'frequency_0', 'Incredibly fun. A breath of fresh air (ironically) compared to 2042. The game, in a way, feels similar to older Battlefield titles like BF4 and BF3, in a good way. It has some QoL here and there, and unfortunately some bugs, but I believe they\'ll be fixed soon. \r\n\r\nThe best part about this game, is that I can feel the passion the devs put into this title. After 2042\'s...disastrous launch, this game had an incredibly smooth launch, and is possibly the most optimized triple-A title of 2025, which is impressive, even more so for EA.\r\n\r\n\r\nNonetheless, if you like Battlefield, you need to try this.', 7, 3),
(6, 'frequency_0', 'Quite a bad launch for the game, and yet, as years gone by, the game kept receiving updates, that optimized it a bit more, fixed more bugs, and people realized under all the mess, is such a beautiful, highly-detailed world, with a great RPG story and captivating characters. \r\n\r\nI honestly prefer this over GTAV. It just feels...more immersive. Play this game, you need to.', 14, 3),
(7, 'frequency_0', 'I\'ve never thought driving trucks can be fun, yet here we are. A surprisingly fun game about driving, well, trucks, in a miniaturized, scaled-down Europe, that still feels massive on its own, excluding various map mods one can download for the game. \r\n\r\nLive out your trucker dreams.', 29, 3),
(10, 'frequency_0', 'An incredibly immersive game. A rich world with well-written characters, and an open-world that generally lets the player do whatever they want, without locking them to a mission or a certain path. \r\nThe perfect \"isekai\" game.', 12, 3),
(13, 'frequency_0', 'Fun car game.', 39, 1),
(19, 'frequency_1', 'Good good good', 38, 3),
(23, 'frequency_0', 'Hard game af. ', 42, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_site`
--

CREATE TABLE `feedback_site` (
  `feedback_site_id` int(11) NOT NULL,
  `feedback_site_satisfaction` varchar(255) DEFAULT NULL,
  `feedback_site_open` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_site`
--

INSERT INTO `feedback_site` (`feedback_site_id`, `feedback_site_satisfaction`, `feedback_site_open`, `user_id`) VALUES
(1, 'satisfaction_3', 'Maybe decorate it a bit more? Functionality-wise, it\'s impressive, but the background is quite bland.  Test\r\n12345', 3),
(8, 'satisfaction_3', 'Good.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_category` varchar(50) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  `game_desc` varchar(10000) DEFAULT NULL,
  `game_trailerLink` varchar(512) DEFAULT NULL,
  `game_Link` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `game_category`, `game_name`, `game_desc`, `game_trailerLink`, `game_Link`) VALUES
(7, 'fps', 'Battlefield 6', 'A return to form. After EA\'s worst Battlefield title in BF2042 released a few years ago during COVID, they\'ve made the latest Battlefield to be one of the safest Battlefields, barely any innovation in-terms of gameplay. And players love it.', 'https://www.youtube.com/watch?v=wFGEMfyAQtI', 'https://store.steampowered.com/app/2807960/Battlefield_6/'),
(8, 'fps', 'Counter-Strike 2', 'The legendary Counter-Strike: Global Offensive, now upgraded into a new engine. With better visuals that not only makes for a more detailed game, but also changed some gameplay fundamentals for the better. One of the biggest e-Sports in the world.', 'https://www.youtube.com/watch?v=c80dVYcL69E', 'https://store.steampowered.com/app/730/CounterStrike_2/'),
(9, 'fps', 'Valorant', 'Riot\'s FPS answer for Valve\'s CS2. Combining gunplay elements from Counter-Strike, hero / character elements present in hero shooters, and map knowledge, allowing for a more strategic gunplay. A surprise hit for the people, and it\'s still going strong.', 'https://www.youtube.com/watch?v=lWr6dhTcu-E', 'https://playvalorant.com/en-us/'),
(12, 'rpg', 'The Elder Scrolls V: Skyrim', 'Skyrim, the fifth installment in the legendary Elder Scrolls series. The biggest game Bethesda has ever made, they re-released it two to three times. Also features an incredibly active modding community, possibly only second to Minecraft\'s modding community.\r\nIf you can only choose one RPG to play, it\'s this one.', 'https://www.youtube.com/watch?v=JSRtYpNRoN0', 'https://store.steampowered.com/app/489830/The_Elder_Scrolls_V_Skyrim_Special_Edition/'),
(13, 'rpg', 'ELDEN RING', 'Another entry in the \"Souls\" genre, by FromSoftware themselves. The most beginner-friendly \"Souls\" game, though that doesn\'t mean the game is any easier than other \"Souls\" titles. Features world-building written by George R. R. Martin. If you\'re new into the \"Souls\" genre, this is the game for you.', 'https://www.youtube.com/watch?v=E3Huy2cdih0', 'https://store.steampowered.com/app/1245620/ELDEN_RING/'),
(14, 'rpg', 'Cyberpunk 2077', 'The most anticipated games of the 2020s, only for it to release in a buggy state that renders the game unplayable. Fast-forward to now, through constant updates and fixes, the game has more than redeemed itself. With an incredibly detailed world and charming characters, one of which played by a certain Keanu Reeves. Welcome To Night City.', 'https://www.youtube.com/watch?v=lJiCOFwoyMA', 'https://store.steampowered.com/app/1091500/Cyberpunk_2077/'),
(17, 'moba', 'Dota 2', 'One of the biggest e-Sports alongside Counter-Strike 2 and League of Legends, Dota 2 is complex, having a steep learning curve that may turn off new players. And yet, it\'s one of the most rewarding game there is. ', 'https://www.youtube.com/watch?v=-cSFPIwMEq4', 'https://store.steampowered.com/app/570/Dota_2/'),
(22, 'puzzle', 'Portal', 'You play as Chell, a test subject in the Aperture Science Lab, where you\'re tasked with solving puzzles, involving escaping rooms, using a Portal gun. A legendary game that redefined the concept of portals in general.', 'https://www.youtube.com/watch?v=TluRVBhmf8w', 'https://store.steampowered.com/app/400/Portal/'),
(23, 'puzzle', 'The Talos Principle', 'You play as an unnamed robot as you traverse a strange, yet beautiful world filled with ancient ruins and advanced technology. Guided by a voice known as Elohim, you are tasked with solving complex environmental puzzles.\r\n', 'https://www.youtube.com/watch?v=Vu9QFBWb7WQ', 'https://store.steampowered.com/app/257510/The_Talos_Principle/'),
(24, 'puzzle', 'The Witness', 'An open-world puzzle game, set in a dazzling, bright, densely packed island. Unlike other puzzle games, the game does not provide instructions, instead relying on environmental cues and keen observation by the player to solve the ever-evolving puzzles.', 'https://www.youtube.com/watch?v=ul7kNFD6noU', 'https://store.steampowered.com/app/210970/The_Witness/'),
(25, 'sport', 'eFootball', 'A free-to-play football title, a rebrand of Konami\'s long-standing Pro Evolution Soccer (PES) series. It features two primary modes: Authentic Teams, where you compete against other players using real teams, and Dream Team, where players can build their Dream Team and compete against other players\' Dream Teams.', 'https://www.youtube.com/watch?v=BdyXsZMPjWo', 'https://store.steampowered.com/app/1665460/eFootball/'),
(26, 'sport', 'EA Sports FC', 'A rebrand of EA Sports\' long-standing FiFA series. A football game, similar in concept to older titles, each year bringing visual improvements. ', 'https://www.youtube.com/watch?v=TSi0iJYSQ24&vl=en', 'https://store.steampowered.com/app/3405690/EA_SPORTS_FC_26/'),
(27, 'sport', 'Football Manager 26', 'Another entry in the long-standing Football Manager series, you play as the coach as you manage your football team. Developed in Unity, the first in the series, the title features brand new user interface overhaul and improved details. It also introduces Women\'s Football, the first in its series.', 'https://www.youtube.com/watch?v=_cDQi5kwuHQ', 'https://store.steampowered.com/app/3551340/Football_Manager_26/'),
(28, 'sim', 'Cities: Skylines', 'Critically-acclaimed title in the city-building genre, where you can build your own city, let it grow, develop it however you like, go through challenges and hurdles that plague a city, or blow it all up, because you can.', 'https://www.youtube.com/watch?v=0gI2N10QyRA', 'https://store.steampowered.com/app/255710/Cities_Skylines/'),
(29, 'sim', 'Euro Truck Simulator 2', 'Have you ever wanted to drive trucks? Yes? Then this is for you! Drive highly-detailed and fully-simulated trucks in a scaled-down Europe, featuring various European countries, trucks from various truck manufacturers, and take in the sights of what Europe has to offer.', 'https://www.youtube.com/watch?v=d3GuiADdiEg', 'https://store.steampowered.com/app/227300/Euro_Truck_Simulator_2/'),
(30, 'sim', 'The Sims 4', 'The fourth full entry in the Sims series, where you can make, customize, control your own sim in the world. Meet people, form relationships, get a job, get a hobby, redecorate a house, get married, let your sim drown in an electrocuted pool, the possibilities are endless!', 'https://www.youtube.com/watch?v=GJENRAB4ykA', 'https://store.steampowered.com/app/1222670/The_Sims_4/'),
(31, 'survival', 'Sons of The Forest', 'Serving as a sequel to 2018\'s The Forest, you play as a private military contractor, dispatched into an island to find a billionaire and his family. You must utilize various survival, crafting, gathering skills and defend yourself from mutants and cannibal tribes.', 'https://www.youtube.com/watch?v=A_E4eCwUEqg', 'https://store.steampowered.com/app/1326470/Sons_Of_The_Forest/'),
(32, 'survival', 'Project Zomboid', 'A zombie survival RPG, stylized like a pixel art game, played from the top-down. You play as a human, trying to survive in a zombified world. Build bases, gather resources, craft weapons, find other humans to make allies, survive however long you can.', 'https://www.youtube.com/watch?v=YhSd39QqQUg', 'https://store.steampowered.com/app/108600/Project_Zomboid/'),
(33, 'survival', 'The Long Dark', 'A first-person, exploration survival game, you are challenged to survive in a freezing, hostile Canadian wilderness in the aftermath of a mysterious global geomagnetic disaster that has knocked out all modern technology. Your threats? Cold, hunger, thirst, and Mother Nature. Good luck. ', 'https://www.youtube.com/watch?v=V5ytwWofaqY', 'https://store.steampowered.com/app/305620/The_Long_Dark/'),
(34, 'fight', 'TEKKEN 8', 'The 8th full title in the long-standing TEKKEN series, the game continues the story surrounding the TEKKEN world, from recurring characters such as Kazuya Mishima and his family, Jun Kazama and Jin Kazama, but also new additions to the series such as Reina and Victor Chevalier. Now updated with brand new animations and visuals.', 'https://www.youtube.com/watch?v=_MM4clV2qjE', 'https://store.steampowered.com/app/1778820/TEKKEN_8/'),
(35, 'fight', 'Mortal Kombat 1', 'Serving as the reboot in the long-standing Mortal Kombat series, the game is violent, with stunning visuals, but still maintained the fast-paced fighting gameplay everyone expects, and of course, fatalities.', 'https://www.youtube.com/watch?v=PL6ZdOXlj6g', 'https://store.steampowered.com/app/1971870/Mortal_Kombat_1/'),
(36, 'fight', 'Street Fighter 6', 'The latest entry in the long-standing Street Fighter series, the game features new fighting mechanics that revolutionize the gameplay, alongside brand new modes to appeal to newcomers.\r\n', 'https://www.youtube.com/watch?v=4EnsDg6DCTE', 'https://store.steampowered.com/app/1364780/Street_Fighter_6/'),
(37, 'racing', 'iRacing', 'iRacing, the most realistic racing simulator there is. Compete with other racers in highly-detailed race-tracks around the world, in fully-simulated racing machines from all kinds of motorsports, under proper regulations employed by real motorsports. It can\'t get any more realistic than this.', 'https://www.youtube.com/watch?v=ecfJGNauAwY', 'https://store.steampowered.com/app/266410/iRacing/'),
(38, 'racing', 'Assetto Corsa', 'A racing simulator, with heavy emphasis on the driving physics. Features a single-player mode, multiplayer, and various other race modes, with the main appeal being the modding scene. Mod the game to race Japanese Drift Machines in Tokyo\'s busy highways, and many more!', 'https://www.youtube.com/watch?v=TDFN-E30jhU', 'https://store.steampowered.com/app/244210/Assetto_Corsa/'),
(39, 'racing', 'Gran Turismo 7', 'A PlayStation-exclusive, and the latest entry in the long-standing Gran Turismo series. A celebration of car culture, where you can drive, race, modify, and simply appreciate highly detailed cars from all spectrums, from the fastest machines ever to the historical machines that paved the way for the industry. This is Gran Turismo, the Real Driving Simulator.', 'https://www.youtube.com/watch?v=oz-O74SmTSQ', 'https://www.gran-turismo.com/us/gt7/top/'),
(42, 'rpg', 'DARK SOULS: REMASTERED', 'The iconic first entry into the Dark Souls franchise, now remastered! Set in the bleak, decaying fantasy kingdom of Lordran, you play as an Undead cursed to a cycle of rebirth, tasked with a monumental quest. Suffering is a main feature.', 'https://www.youtube.com/watch?v=KfjG9ZLGBHE', 'https://store.steampowered.com/app/570940/DARK_SOULS_REMASTERED/'),
(44, 'action', 'Prototype', 'You play as the Prototype, Alex Mercer, a man with no memory, whom suddenly gained inhuman abilities that allows you to shape-shift. Discover who you are, adapt to situations, and uncover the truth behind the making of you, the Prototype.', 'https://www.youtube.com/watch?v=Nc3XptLacMM', 'https://store.steampowered.com/app/10150/Prototype/'),
(46, 'fight', 'Brawlhalla', 'A free-to-play platform 2D fighter, where you only need to push your opponents off the platform. Sounds simple enough, but combined with various playstyle combinations possible with each character, you\'ll learn how hard it actually is.', 'https://www.youtube.com/watch?v=Odj7pIsHRt0', 'https://store.steampowered.com/app/291550/Brawlhalla/'),
(47, 'fight', 'DRAGON BALL XENOVERSE 2', 'Immerse yourself in the most detailed rendition of Dragon Ball world yet with Xenoverse 2! Create your own Time Patroller and travel through Dragon Ball\'s timeline to correct dangerous changes made by new villains. ', 'https://www.youtube.com/watch?v=VFDugj5yjNw', 'https://store.steampowered.com/app/454650/DRAGON_BALL_XENOVERSE_2/'),
(48, 'fight', 'GUILTY GEAR -STRIVE-', 'The critically-acclaimed fighting game, famous for its blend of cell-shaded and 3D visual style, Guilty Gear Strive is a reconstruction of the series, made to be more accessible for newcomers with simplified combo routes while retaining the high-octane, aggressive gameplay veterans love.', 'https://www.youtube.com/watch?v=-rbffP5aQoA', 'https://store.steampowered.com/app/1384160/GUILTY_GEAR_STRIVE/'),
(49, 'fight', 'FATAL FURY: City of the Wolves', 'The first new mainline entry into the FATAL FURY franchise in 26 years, City of the Wolves features a striking, modern visual style while building on the series\' core mechanics. Its central innovation is the \"REV System,\" an offensive meter that allows players to use powerful moves like \"REV Arts\" and \"REV Blows\" right from the start of the match, encouraging aggressive play until the meter overheats.', 'https://www.youtube.com/watch?v=lHjjlpCoBOQ', 'https://store.steampowered.com/app/2492040/FATAL_FURY_City_of_the_Wolves/'),
(50, 'fight', 'Granblue Fantasy Versus: Rising', 'A spin-off entry into the world-famous Granblue Fantasy franchise, Versus: Rising serves as an expanded and revamped to Granblue Fantasy Versus. It\'s renowned for its gorgeous anime art style and a design philosophy that balances accessibility for newcomers with significant depth for veterans.', 'https://www.youtube.com/watch?v=SbI26Ehde3g', 'https://store.steampowered.com/app/2157560/Granblue_Fantasy_Versus_Rising/'),
(51, 'moba', 'League of Legends', 'Another titan in the eSports industry, League of Legends features a faster MOBA gameplay loop than, say, Dota 2, which allows for more casual gameplay, but, well the community is anything but casual. ', 'https://www.youtube.com/watch?v=JGKqTuPVIS4', 'https://www.leagueoflegends.com/'),
(52, 'racing', 'BeamNG.drive', 'A unique, one-of-a-kind game in its category, celebrated for its ultra-realistic vehicle depiction down to the internals, and a soft body physics engine where everything is simulated real-time, allowing for...well, crashes, to be incredibly detailed. But this game, is more than that. ', 'https://www.youtube.com/watch?v=4ms-LHkN6YQ', 'https://store.steampowered.com/app/284160/BeamNGdrive/'),
(53, 'racing', 'Wreckfest', 'Buckle up people, because this isn\'t your usual racing game! You race in some junkyard scrap-looking cars, against other cars, and there\'s no such thing as rules! Take them out, do everything to win the race! This is Wreckfest!', 'https://www.youtube.com/watch?v=cbsDiIuI7KQ', 'https://store.steampowered.com/app/228380/Wreckfest/'),
(54, 'racing', 'DiRT Rally 2.0', 'Drive incredibly light, but powerful machines, and race against time on tight, wet, bumpy road courses that will throw you off. A single mistake can make or break your run.', 'https://www.youtube.com/watch?v=RQ7JvIncd4Y', 'https://store.steampowered.com/app/690790/DiRT_Rally_20/'),
(55, 'racing', 'Forza Horizon 5', 'The fifth entry into the ever-so-popular Forza Horizon franchise brings you to Mexico! Drive your favourite machines, ranging from all spectrum, worldwide car manufacturers, different time periods, in a vast open-world of Mexico.', 'https://www.youtube.com/watch?v=7pEjhaBjlj0', 'https://store.steampowered.com/app/1551360/Forza_Horizon_5/'),
(56, 'racing', 'Tokyo Xtreme Racer', 'The first entry in the beloved Tokyo Xtreme Racer franchise in a decade, you\'re set loose in a meticulously recreated, futuristic version of Tokyo\'s Shuto Expressway. Race against your rivals and stay in the lead to gain Spirit Points, whilst depleting theirs.\r\nWe\'re so back.', 'https://www.youtube.com/watch?v=8jlDxL6ZXPk', 'https://store.steampowered.com/app/2634950/Tokyo_Xtreme_Racer/'),
(57, 'rpg', 'Dragon\'s Dogma: Dark Arisen', 'The definitive, expanded version of Dragon\'s Dogma, you\'re awaken as the \"Arisen\", a cursed hero bound by fate to hunt down that Dragon and reclaim your heart. Explore the land of Gransys, alongside your companions, or \"pawns\".', 'https://www.youtube.com/watch?v=PiJ_L8It7nc', 'https://store.steampowered.com/app/367500/Dragons_Dogma_Dark_Arisen/'),
(58, 'rpg', 'Kingdom Come: Deliverance', 'An open-world RPG set in the war-torn Kingdom of Bohemia in 1403, you play as Henry, who survived a brutal mercenary raid on his village that kills his family. Seeking revenge, you enter the service of a lord and fight for the rightful king.', 'https://www.youtube.com/watch?v=tpnuBdG9txM', 'https://store.steampowered.com/app/379430/Kingdom_Come_Deliverance/'),
(59, 'rpg', 'FINAL FANTASY VII REMAKE INTERGRADE', 'The first entry in the FF7 Remake trilogy, you play as Cloud Strife, an ex-SOLDIER operative, where you\'ll descend on the mako-powered city of Midgar.', 'https://www.youtube.com/watch?v=Ge73iBqc7o8', 'https://store.steampowered.com/app/1462040/FINAL_FANTASY_VII_REMAKE_INTERGRADE/'),
(60, 'rpg', 'Clair Obscur: Expedition 33', 'A turn-based, French-inspired JRPG set in a grimly beautiful \"Belle Époque\" fantasy world, you follow Gustave and the members of Expedition 33, a group of people who have only one year left to live. They\'re on a desperate mission to kill the Paintress, the one whom responsible for the fact they only have a year left to live.', 'https://www.youtube.com/watch?v=wWGIakhqr5g', 'https://store.steampowered.com/app/1903340/Clair_Obscur_Expedition_33/'),
(61, 'survival', 'ARK: Survival Evolved', 'A brutal open-world, survival game, where you\'re left stranded, naked and unarmed, on the shores of a mysterious island populated by dinosaurs and other prehistoric creatures. You will struggle for survival, hunt and harvest resources, and build shelters to protect yourself from the elements, the wildlife-and other players.', 'https://www.youtube.com/watch?v=5fIAPcVdZO8', 'https://store.steampowered.com/app/346110/ARK_Survival_Evolved/'),
(62, 'puzzle', 'It Takes Two', 'An action-adventure platformer, where it\'s an exclusively two-player game, you play as Cody and May, a clashing couple on the verge of divorce who are magically trapped inside two dolls created by their daughter. Embark on a fantastical journey, where you\'ll be working to solve puzzles together, among other things.', 'https://www.youtube.com/watch?v=GAWHzGNcTEw', 'https://store.steampowered.com/app/1426210/It_Takes_Two/'),
(63, 'sim', 'Kerbal Space Program', 'A space flight simulation, a one-of-its-kind in the genre, becoming a bar for games like it to live up to or even surpass. You\'re in charge of an alien species known as Kerbals. Design rockets, planes, space stations, plan for planetary navigation and launch your rockets to other planets. ', 'https://www.youtube.com/watch?v=aAa9Ao26gtM', 'https://store.steampowered.com/app/220200/Kerbal_Space_Program/'),
(64, 'sim', 'DCS World Steam Edition', 'A realistic fighter jet simulator, simulating almost every mechanics of a fighter jet (at least ones publicly known). Fly your favourite fighter jets against NPCs or other players in various war theaters worldwide, across various time periods, from World War 2 to Iraq War. ', 'https://www.youtube.com/watch?v=4Q37cbS0XKY', 'https://store.steampowered.com/app/223750/DCS_World_Steam_Edition/'),
(65, 'sim', 'X-Plane 12', 'Live your aviation dreams and pilot highly-detailed, realistic renditions of planes across different spectrum. From helicopters, jumbo jets, cargo planes, commercial planes, private jets, training aircraft, you name it.', 'https://www.youtube.com/watch?v=N05KBMKuXXU', 'https://store.steampowered.com/app/2014780/XPlane_12/'),
(66, 'sim', 'TCG Card Shop Simulator', 'You take on the role of a local game store owner, where you\'ll be managing your business from the ground up. Order booster packs of various trading cards, set your own prices, and manage your inventory to meet customer demand. Of course, you can also open them yourself on the chance of getting rare cards, which you can either save for your own collection or sell.', 'https://www.youtube.com/watch?v=hYyZVRpRnKc', 'https://store.steampowered.com/app/3070070/TCG_Card_Shop_Simulator/'),
(67, 'fps', 'Arena Breakout: Infinite', 'A tactical first-person extraction shooter, your goal is to infiltrate a stage, loot items, exfiltrate. You play as a Mercenary; everyone else is an enemy. Customize your guns however you want to, and engage in intense gunfights with other people. Die, and you lose all your loot, including the equipment you brought in. Good luck.', 'https://www.youtube.com/watch?v=WRoHUXsElAg', 'https://store.steampowered.com/app/2073620/Arena_Breakout_Infinite/'),
(68, 'action', 'METAL GEAR SOLID Δ: SNAKE EATER', 'A remake of the 2004 game of the same name, you play as Naked Snake, set in the 1960s Cold War, on a mission to survive in the Soviet jungle and stop a superweapon. An incredibly faithful remake, it maintains a lot of the charm of the old game, while improving on a lot of gameplay aspects.', 'https://www.youtube.com/watch?v=ajh3YHJ6baI', 'https://store.steampowered.com/app/2417610/METAL_GEAR_SOLID_D_SNAKE_EATER/'),
(69, 'action', 'God of War', 'The game follows Kratos, years after his brutal vengeance against the gods of Olympus. Now living a new life as a man in the realm of Norse gods, now a widower and father to a young son, Atreus. The story revolves around fulfilling his wife\'s final wish—to scatter her ashes from the highest peak in the nine realms.', 'https://www.youtube.com/watch?v=HqQMh_tij0c', 'https://store.steampowered.com/app/1593500/God_of_War/'),
(70, 'action', 'Ghost of Tsushima DIRECTOR\'S CUT', 'An open-world action-adventure game set on the real-world Tsushima Island during the first Mongol invasion of Japan in 1274, you play as Jin Sakai, a noble samurai who survives the initial devastating attack and must grapple with a profound internal conflict: adhere to his strict, honorable samurai code, or adopt the \"dishonorable\" but effective tactics of a stealthy assassin—the \"Ghost\"—to save his people.', 'https://www.youtube.com/watch?v=GJqUwr41KLc', 'https://store.steampowered.com/app/2215430/Ghost_of_Tsushima_DIRECTORS_CUT/'),
(71, 'fps', 'Ready or Not', 'You play as Judge, a SWAT operative, part of the Los Sueños Police Department. You\'re deployed to various parts of Los Sueños where you must defuse the situations, ranging from a robbery at a gas station, to a SWAT raid on a suspected pedophile, or a school shooting situation, to name a few.\r\n\r\nIt \'s as real as it gets.', 'https://www.youtube.com/watch?v=ItUnHOY_kfw', 'https://store.steampowered.com/app/1144200/Ready_or_Not/'),
(72, 'fps', 'Trepang2', 'You play as Subject 106, an amnesiac super-soldier with superhuman abilities who is broken out of a corporate black site. Discover who you are, kill your enemies, find secrets about the corporate that kept you there, kill your enemies, kill your enemies, kill your enemies.', 'https://www.youtube.com/watch?v=pIhx-Lgqi_E', 'https://store.steampowered.com/app/1164940/Trepang2/'),
(73, 'horror', 'Alien: Isolation', 'The game is first-person survival horror game that masterfully recreates the terrifying, lo-fi sci-fi aesthetic of the original 1979 film. You play as Amanda Ripley, Ellen Ripley\'s daughter, who travels to the decaying Sevastopol space station 15 years after her mother\'s disappearance, only to find it terrorized by a single, terrifying Xenomorph. Hide.', 'https://www.youtube.com/watch?v=7h0cgmvIrZw', 'https://store.steampowered.com/app/214490/Alien_Isolation/'),
(74, 'horror', 'Doki Doki Literature Club!', 'Welcome to the Literature Club, filled with cute girls. Write poems to get into their hearts! \r\n\r\nWhat, what do you mean it doesn\'t look like a horror game?', 'https://www.youtube.com/watch?v=kB1663FTpzU', 'https://store.steampowered.com/app/698780/Doki_Doki_Literature_Club/'),
(75, 'horror', 'SILENT HILL f', 'You play as Hinako Shimizu, a high school student who must navigate her town after it\'s consumed by a mysterious, fog-like menace and grotesque, flower-like monsters. Written by Ryukishi07 (Higurashi When They Cry), the game is a psychological horror story rooted in Japanese folklore, exploring themes of trauma and the oppressive social expectations of its era.', 'https://www.youtube.com/watch?v=0NMoPvqaz10', 'https://store.steampowered.com/app/2947440/SILENT_HILL_f/'),
(76, 'horror', 'OMORI', 'You\'re Sunny, a reclusive boy who escapes his real-world trauma by retreating into a colorful, dream-like world as his alter-ego, Omori.\r\n\r\nDiscover who you are.', 'https://www.youtube.com/watch?v=erzgjfU271g', 'https://store.steampowered.com/app/1150690/OMORI/'),
(77, 'fps', 'Squad', 'A tactical FPS that provides authentic combat experiences through teamwork, communication, and realistic combat. \r\nThis is war.', 'https://www.youtube.com/watch?v=UDnUD73gRXk', 'https://store.steampowered.com/app/393380/Squad/'),
(78, 'sim', 'Stardew Valley', 'You\'re sick of your nine-to-five daily life, purposely grinding for money. And then you decided to read that letter your grandpa gave you years ago. \r\nCongrats, you\'re now the proud owner of a farm. Tend to it, grow crops, live a chill life as a farmer.', 'https://www.youtube.com/watch?v=ot7uXNQskhs', 'https://store.steampowered.com/app/413150/Stardew_Valley/');

-- --------------------------------------------------------

--
-- Table structure for table `game_cover`
--

CREATE TABLE `game_cover` (
  `game_cover_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `cover_path` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_cover`
--

INSERT INTO `game_cover` (`game_cover_id`, `game_id`, `cover_path`) VALUES
(2, 7, 'uploads/covers/cover_7_690a0ff7754bf.jpg'),
(3, 8, 'uploads/covers/cover_8_690a2006e0117.jpg'),
(4, 12, 'uploads/covers/cover_12_690a204f3eacc.jpg'),
(5, 13, 'uploads/covers/cover_13_690a2081171ae.jpg'),
(6, 14, 'uploads/covers/cover_14_690a20ec59669.jpg'),
(7, 17, 'uploads/covers/cover_17_690a234240e7f.jpg'),
(8, 22, 'uploads/covers/cover_22_690a239abc7f8.jpg'),
(9, 23, 'uploads/covers/cover_23_690a23b3c316d.jpg'),
(10, 24, 'uploads/covers/cover_24_690a23cb37962.jpg'),
(11, 25, 'uploads/covers/cover_25_690a23e945fc5.jpg'),
(12, 26, 'uploads/covers/cover_26_690a240ba4928.jpg'),
(13, 27, 'uploads/covers/cover_27_690a2469688b2.jpg'),
(14, 28, 'uploads/covers/cover_28_690a2482e5653.jpg'),
(15, 29, 'uploads/covers/cover_29_690a2504403aa.jpg'),
(16, 30, 'uploads/covers/cover_30_690a2523720d3.jpg'),
(17, 31, 'uploads/covers/cover_31_690a25d6d4a5b.jpg'),
(18, 32, 'uploads/covers/cover_32_690a25fade09b.jpg'),
(19, 33, 'uploads/covers/cover_33_690a2616a4d96.jpg'),
(20, 34, 'uploads/covers/cover_34_690a2631d5566.jpg'),
(21, 35, 'uploads/covers/cover_35_690a264b08399.jpg'),
(22, 36, 'uploads/covers/cover_36_690a266eec4b1.jpg'),
(23, 37, 'uploads/covers/cover_37_690a268a07dc1.jpg'),
(24, 38, 'uploads/covers/cover_38_690a26b1d8ed0.jpg'),
(25, 9, 'uploads/covers/cover_9_690a2cf3570e9.jpg'),
(27, 39, 'uploads/covers/cover_39_690a332508cd6.jpg'),
(31, 42, 'uploads/covers/cover_42_6912ecf593995.jpg'),
(33, 44, 'uploads/covers/cover_44_6912ef11d3a85.jpg'),
(35, 46, 'uploads/covers/cover_46_6912f4941a865.jpg'),
(36, 47, 'uploads/covers/cover_47_6912f7795a2e4.jpg'),
(37, 48, 'uploads/covers/cover_48_6912f9df7af0d.jpg'),
(38, 49, 'uploads/covers/cover_49_6912fd6de77fd.jpg'),
(39, 50, 'uploads/covers/cover_50_69130682895bc.jpg'),
(40, 51, 'uploads/covers/cover_51_691349442d2a4.webp'),
(41, 52, 'uploads/covers/cover_52_69134d9b8e90a.jpg'),
(42, 53, 'uploads/covers/cover_53_69134ed0ca235.jpg'),
(43, 54, 'uploads/covers/cover_54_69135036c3f92.jpg'),
(44, 55, 'uploads/covers/cover_55_6913517c4dfea.jpg'),
(45, 56, 'uploads/covers/cover_56_6913531d28cba.jpg'),
(46, 57, 'uploads/covers/cover_57_6913555cdc7e7.jpg'),
(47, 58, 'uploads/covers/cover_58_691357f31fb2f.jpg'),
(48, 59, 'uploads/covers/cover_59_69135844e1a1a.jpg'),
(49, 60, 'uploads/covers/cover_60_69135a14d12e4.jpg'),
(50, 61, 'uploads/covers/cover_61_691363d7ade41.jpg'),
(51, 62, 'uploads/covers/cover_62_691366116186a.jpg'),
(52, 63, 'uploads/covers/cover_63_69136854834b4.jpg'),
(53, 64, 'uploads/covers/cover_64_6913694f8821a.jpg'),
(54, 65, 'uploads/covers/cover_65_69136a67c1dd8.jpg'),
(55, 66, 'uploads/covers/cover_66_69136bc38d36e.jpg'),
(56, 67, 'uploads/covers/cover_67_69136e1e5a6d9.jpg'),
(57, 68, 'uploads/covers/cover_68_69136f09d7275.jpg'),
(58, 69, 'uploads/covers/cover_69_69137036629c4.jpg'),
(59, 70, 'uploads/covers/cover_70_691371010bf6d.jpg'),
(60, 71, 'uploads/covers/cover_71_69137276796f3.jpg'),
(61, 72, 'uploads/covers/cover_72_69137353c885c.jpg'),
(62, 73, 'uploads/covers/cover_73_69137476d6197.jpg'),
(63, 74, 'uploads/covers/cover_74_6913758f28022.jpg'),
(64, 75, 'uploads/covers/cover_75_691377649b2f5.jpg'),
(65, 76, 'uploads/covers/cover_76_6913780729ebe.jpg'),
(66, 77, 'uploads/covers/cover_77_69137c321e5be.jpg'),
(67, 78, 'uploads/covers/cover_78_69137d3d0e316.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `game_images`
--

CREATE TABLE `game_images` (
  `game_img_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `img_path` varchar(1024) NOT NULL,
  `img_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_images`
--

INSERT INTO `game_images` (`game_img_id`, `game_id`, `img_path`, `img_order`) VALUES
(11, 8, 'uploads/gallery/game_8_6905e1ff56f5c.jpg', 0),
(12, 8, 'uploads/gallery/game_8_6905e1ff5792f.jpg', 4),
(13, 8, 'uploads/gallery/game_8_6905e1ff5812d.jpg', 1),
(14, 8, 'uploads/gallery/game_8_6905e1ff58b67.jpg', 3),
(15, 8, 'uploads/gallery/game_8_6905e1ff597e8.jpg', 2),
(16, 8, 'uploads/gallery/game_8_6905e40108c34.jpg', 5),
(17, 7, 'uploads/gallery/game_7_6905e56124a13.jpg', 3),
(18, 7, 'uploads/gallery/game_7_6905e5612526e.jpg', 2),
(19, 7, 'uploads/gallery/game_7_6905e56125ec1.jpg', 1),
(20, 7, 'uploads/gallery/game_7_6905e561267af.jpg', 4),
(21, 7, 'uploads/gallery/game_7_6905e56126daf.jpg', 5),
(22, 7, 'uploads/gallery/game_7_6905e5612741c.jpg', 0),
(23, 12, 'uploads/gallery/game_12_6905e89220280.jpg', 5),
(24, 12, 'uploads/gallery/game_12_6905e892211e8.jpg', 0),
(25, 12, 'uploads/gallery/game_12_6905e89221e6a.jpg', 1),
(26, 12, 'uploads/gallery/game_12_6905e892226aa.jpg', 2),
(27, 12, 'uploads/gallery/game_12_6905e89222da0.jpg', 3),
(28, 12, 'uploads/gallery/game_12_6905e892233e6.jpg', 4),
(29, 13, 'uploads/gallery/game_13_6905e95459c87.jpg', 0),
(30, 13, 'uploads/gallery/game_13_6905e9545a5b5.jpg', 3),
(31, 13, 'uploads/gallery/game_13_6905e9545afd5.jpg', 1),
(32, 13, 'uploads/gallery/game_13_6905e9545bb79.jpg', 2),
(33, 13, 'uploads/gallery/game_13_6905e9545c3cc.jpg', 4),
(34, 13, 'uploads/gallery/game_13_6905e9545ca7e.jpg', 5),
(35, 14, 'uploads/gallery/game_14_6905ea201f6cd.jpg', 3),
(36, 14, 'uploads/gallery/game_14_6905ea20205c6.jpg', 4),
(37, 14, 'uploads/gallery/game_14_6905ea20210c4.jpg', 5),
(38, 14, 'uploads/gallery/game_14_6905ea2021843.jpg', 1),
(39, 14, 'uploads/gallery/game_14_6905ea2021e8d.jpg', 2),
(40, 14, 'uploads/gallery/game_14_6905ea20224f2.jpg', 0),
(41, 17, 'uploads/gallery/game_17_6905eb7e1ef24.jpg', 3),
(42, 17, 'uploads/gallery/game_17_6905eb7e1f937.jpg', 0),
(43, 17, 'uploads/gallery/game_17_6905eb7e20632.jpg', 4),
(44, 17, 'uploads/gallery/game_17_6905eb7e21291.jpg', 5),
(45, 17, 'uploads/gallery/game_17_6905eb7e21cc2.jpg', 2),
(46, 17, 'uploads/gallery/game_17_6905eb7e22543.jpg', 1),
(47, 22, 'uploads/gallery/game_22_6905ec1318338.jpg', 0),
(48, 22, 'uploads/gallery/game_22_6905ec131928d.jpg', 1),
(49, 22, 'uploads/gallery/game_22_6905ec1319c97.jpg', 2),
(50, 22, 'uploads/gallery/game_22_6905ec131a67d.jpg', 3),
(51, 22, 'uploads/gallery/game_22_6905ec131af7e.jpg', 4),
(52, 22, 'uploads/gallery/game_22_6905ec131b6af.jpg', 5),
(53, 23, 'uploads/gallery/game_23_6905ec7ed4c7d.jpg', 1),
(54, 23, 'uploads/gallery/game_23_6905ec7ed57f5.jpg', 0),
(55, 23, 'uploads/gallery/game_23_6905ec7ed6199.jpg', 3),
(56, 23, 'uploads/gallery/game_23_6905ec7ed6a94.jpg', 2),
(57, 23, 'uploads/gallery/game_23_6905ec7ed7090.jpg', 5),
(58, 23, 'uploads/gallery/game_23_6905ec7ed77b1.jpg', 4),
(59, 24, 'uploads/gallery/game_24_6905ed0463b63.jpg', 5),
(60, 24, 'uploads/gallery/game_24_6905ed04648a0.jpg', 4),
(61, 24, 'uploads/gallery/game_24_6905ed046548b.jpg', 1),
(62, 24, 'uploads/gallery/game_24_6905ed0465fb4.jpg', 3),
(63, 24, 'uploads/gallery/game_24_6905ed046679b.jpg', 2),
(64, 24, 'uploads/gallery/game_24_6905ed0466e05.jpg', 0),
(65, 25, 'uploads/gallery/game_25_6905ed9108b8b.jpg', 2),
(66, 25, 'uploads/gallery/game_25_6905ed91094bf.jpg', 3),
(67, 25, 'uploads/gallery/game_25_6905ed9109ee5.jpg', 4),
(68, 25, 'uploads/gallery/game_25_6905ed910a8ce.jpg', 5),
(69, 25, 'uploads/gallery/game_25_6905ed910af18.jpg', 1),
(70, 25, 'uploads/gallery/game_25_6905ed910b4af.jpg', 0),
(71, 26, 'uploads/gallery/game_26_6905eefc99a40.jpg', 0),
(72, 26, 'uploads/gallery/game_26_6905eefc9a69b.jpg', 1),
(73, 26, 'uploads/gallery/game_26_6905eefc9afec.jpg', 2),
(74, 26, 'uploads/gallery/game_26_6905eefc9b660.jpg', 3),
(75, 26, 'uploads/gallery/game_26_6905eefc9baf6.jpg', 4),
(76, 26, 'uploads/gallery/game_26_6905eefc9bee9.jpg', 5),
(77, 27, 'uploads/gallery/game_27_6905ef93b2c98.jpg', 5),
(78, 27, 'uploads/gallery/game_27_6905ef93b3a25.jpg', 4),
(79, 27, 'uploads/gallery/game_27_6905ef93b4525.jpg', 3),
(80, 27, 'uploads/gallery/game_27_6905ef93b4da9.jpg', 0),
(81, 27, 'uploads/gallery/game_27_6905ef93b55cc.jpg', 1),
(82, 27, 'uploads/gallery/game_27_6905ef93b5bea.jpg', 2),
(83, 28, 'uploads/gallery/game_28_6905f0340a282.jpg', 5),
(84, 28, 'uploads/gallery/game_28_6905f0340b230.jpg', 3),
(85, 28, 'uploads/gallery/game_28_6905f0340bbc1.jpg', 2),
(86, 28, 'uploads/gallery/game_28_6905f0340c3f5.jpg', 4),
(87, 28, 'uploads/gallery/game_28_6905f0340cae7.jpg', 1),
(88, 28, 'uploads/gallery/game_28_6905f0340d549.jpg', 0),
(89, 29, 'uploads/gallery/game_29_6905f0ab629ba.jpg', 3),
(90, 29, 'uploads/gallery/game_29_6905f0ab631b0.jpg', 2),
(91, 29, 'uploads/gallery/game_29_6905f0ab63a0f.jpg', 5),
(92, 29, 'uploads/gallery/game_29_6905f0ab63f72.jpg', 4),
(93, 29, 'uploads/gallery/game_29_6905f0ab645b6.jpg', 1),
(94, 29, 'uploads/gallery/game_29_6905f0ab64caa.jpg', 0),
(95, 30, 'uploads/gallery/game_30_6905f11ea9da6.jpg', 3),
(96, 30, 'uploads/gallery/game_30_6905f11eaa395.jpg', 1),
(97, 30, 'uploads/gallery/game_30_6905f11eaacd9.jpg', 2),
(98, 30, 'uploads/gallery/game_30_6905f11eab733.jpg', 0),
(99, 31, 'uploads/gallery/game_31_6905f182ae727.jpg', 2),
(100, 31, 'uploads/gallery/game_31_6905f182af192.jpg', 3),
(101, 31, 'uploads/gallery/game_31_6905f182afeff.jpg', 0),
(102, 31, 'uploads/gallery/game_31_6905f182b0a4e.jpg', 1),
(103, 31, 'uploads/gallery/game_31_6905f182b1031.jpg', 4),
(104, 31, 'uploads/gallery/game_31_6905f182b1583.jpg', 6),
(105, 32, 'uploads/gallery/game_32_6905f2a1693ed.jpg', 1),
(106, 32, 'uploads/gallery/game_32_6905f2a169dde.jpg', 3),
(107, 32, 'uploads/gallery/game_32_6905f2a16ab01.jpg', 2),
(108, 32, 'uploads/gallery/game_32_6905f2a16b3eb.jpg', 4),
(109, 32, 'uploads/gallery/game_32_6905f2a16b904.jpg', 5),
(110, 32, 'uploads/gallery/game_32_6905f2a16bfc2.jpg', 0),
(111, 33, 'uploads/gallery/game_33_6905f3255d83c.jpg', 0),
(112, 33, 'uploads/gallery/game_33_6905f3255de4d.jpg', 2),
(113, 33, 'uploads/gallery/game_33_6905f3255e90f.jpg', 3),
(114, 33, 'uploads/gallery/game_33_6905f3255f2ce.jpg', 1),
(115, 33, 'uploads/gallery/game_33_6905f3255fb85.jpg', 5),
(116, 33, 'uploads/gallery/game_33_6905f32560280.jpg', 4),
(117, 34, 'uploads/gallery/game_34_6905f3c74ced9.jpg', 2),
(118, 34, 'uploads/gallery/game_34_6905f3c74d667.jpg', 5),
(119, 34, 'uploads/gallery/game_34_6905f3c74de99.jpg', 3),
(120, 34, 'uploads/gallery/game_34_6905f3c74e358.jpg', 4),
(121, 34, 'uploads/gallery/game_34_6905f3c74e7ad.jpg', 1),
(122, 34, 'uploads/gallery/game_34_6905f3c74ef77.jpg', 0),
(123, 35, 'uploads/gallery/game_35_6906146355dac.jpg', 4),
(124, 35, 'uploads/gallery/game_35_6906146356c27.jpg', 5),
(125, 35, 'uploads/gallery/game_35_6906146357773.jpg', 3),
(126, 35, 'uploads/gallery/game_35_6906146357dad.jpg', 0),
(127, 35, 'uploads/gallery/game_35_69061463583ba.jpg', 1),
(128, 35, 'uploads/gallery/game_35_69061463589c5.jpg', 2),
(129, 36, 'uploads/gallery/game_36_690615130a4cc.jpg', 0),
(130, 36, 'uploads/gallery/game_36_690615130aee9.jpg', 0),
(131, 36, 'uploads/gallery/game_36_690615130b90f.jpg', 0),
(132, 36, 'uploads/gallery/game_36_690615130be8e.jpg', 0),
(133, 36, 'uploads/gallery/game_36_690615130c405.jpg', 0),
(134, 36, 'uploads/gallery/game_36_690615130ccf6.jpg', 0),
(135, 37, 'uploads/gallery/game_37_690615b695b1b.jpg', 1),
(136, 37, 'uploads/gallery/game_37_690615b6960e7.jpg', 5),
(137, 37, 'uploads/gallery/game_37_690615b6967a7.jpg', 2),
(138, 37, 'uploads/gallery/game_37_690615b697281.jpg', 3),
(139, 37, 'uploads/gallery/game_37_690615b697cf4.jpg', 4),
(140, 37, 'uploads/gallery/game_37_690615b6983b8.jpg', 0),
(141, 38, 'uploads/gallery/game_38_690616246c3cd.jpg', 1),
(142, 38, 'uploads/gallery/game_38_690616246d3b6.jpg', 2),
(143, 38, 'uploads/gallery/game_38_690616246db8c.jpg', 3),
(144, 38, 'uploads/gallery/game_38_690616246e24a.jpg', 4),
(145, 38, 'uploads/gallery/game_38_690616246e7fc.jpg', 0),
(146, 38, 'uploads/gallery/game_38_690616246ed68.jpg', 5),
(149, 9, 'uploads/gallery/game_9_690a2de5d111c.jpg', 2),
(150, 9, 'uploads/gallery/game_9_690a2dee11e15.jpg', 1),
(151, 9, 'uploads/gallery/game_9_690a2e4d425a5.jpg', 4),
(152, 9, 'uploads/gallery/game_9_690a2e4d43295.jpg', 3),
(153, 9, 'uploads/gallery/game_9_690a2e4d4407f.webp', 5),
(154, 9, 'uploads/gallery/game_9_690a2e4d44ab7.jpg', 0),
(161, 39, 'uploads/gallery/game_39_690a35b60f907.jpeg', 0),
(162, 39, 'uploads/gallery/game_39_690a35b6101b2.webp', 1),
(163, 39, 'uploads/gallery/game_39_690a35b610bf6.jpg', 4),
(164, 39, 'uploads/gallery/game_39_690a35b611293.png', 2),
(165, 39, 'uploads/gallery/game_39_690a35b61192a.jpg', 5),
(166, 39, 'uploads/gallery/game_39_690a35b611ec8.jpg', 3),
(174, 42, 'uploads/gallery/game_42_6912ecfbdc91e.jpg', 4),
(175, 42, 'uploads/gallery/game_42_6912ecfbdda76.jpg', 3),
(176, 42, 'uploads/gallery/game_42_6912ecfbde042.jpg', 2),
(177, 42, 'uploads/gallery/game_42_6912ecfbde56a.jpg', 1),
(178, 42, 'uploads/gallery/game_42_6912ecfbdead8.jpg', 0),
(180, 44, 'uploads/gallery/game_44_6912ef2e3ec61.jpg', 2),
(181, 44, 'uploads/gallery/game_44_6912ef2e3f2de.jpg', 1),
(182, 44, 'uploads/gallery/game_44_6912ef2e3f89f.jpg', 0),
(183, 44, 'uploads/gallery/game_44_6912ef2e3ff22.jpg', 5),
(184, 44, 'uploads/gallery/game_44_6912ef2e4049f.jpg', 3),
(185, 44, 'uploads/gallery/game_44_6912ef2e40a88.jpg', 4),
(187, 46, 'uploads/gallery/game_46_6912f4994e164.jpg', 3),
(188, 46, 'uploads/gallery/game_46_6912f4994e89e.jpg', 5),
(189, 46, 'uploads/gallery/game_46_6912f4994ef13.jpg', 4),
(190, 46, 'uploads/gallery/game_46_6912f4994f4e5.jpg', 1),
(191, 46, 'uploads/gallery/game_46_6912f4994fa61.jpg', 2),
(192, 46, 'uploads/gallery/game_46_6912f49950035.jpg', 0),
(193, 47, 'uploads/gallery/game_47_6912f77e1ebda.jpg', 5),
(194, 47, 'uploads/gallery/game_47_6912f77e1f2bb.jpg', 4),
(195, 47, 'uploads/gallery/game_47_6912f77e1fbee.jpg', 3),
(196, 47, 'uploads/gallery/game_47_6912f77e201ae.jpg', 2),
(197, 47, 'uploads/gallery/game_47_6912f77e20738.jpg', 1),
(198, 47, 'uploads/gallery/game_47_6912f77e20be1.jpg', 0),
(199, 48, 'uploads/gallery/game_48_6912f9e517a4e.jpg', 5),
(200, 48, 'uploads/gallery/game_48_6912f9e5185c0.jpg', 4),
(201, 48, 'uploads/gallery/game_48_6912f9e518e75.jpg', 3),
(202, 48, 'uploads/gallery/game_48_6912f9e5195a4.jpg', 2),
(203, 48, 'uploads/gallery/game_48_6912f9e519bb0.jpg', 1),
(204, 48, 'uploads/gallery/game_48_6912f9e51a1a7.jpg', 0),
(205, 49, 'uploads/gallery/game_49_6912fd9756c61.jpg', 5),
(206, 49, 'uploads/gallery/game_49_6912fd9757446.jpg', 4),
(207, 49, 'uploads/gallery/game_49_6912fd97580a2.jpg', 3),
(208, 49, 'uploads/gallery/game_49_6912fd97586f8.jpg', 2),
(209, 49, 'uploads/gallery/game_49_6912fd975902d.jpg', 1),
(210, 49, 'uploads/gallery/game_49_6912fd9759662.jpg', 0),
(211, 50, 'uploads/gallery/game_50_6913069bc993a.jpg', 5),
(212, 50, 'uploads/gallery/game_50_6913069bc9eae.jpg', 4),
(213, 50, 'uploads/gallery/game_50_6913069bca707.jpg', 3),
(214, 50, 'uploads/gallery/game_50_6913069bcacb9.jpg', 2),
(215, 50, 'uploads/gallery/game_50_6913069bcb103.jpg', 1),
(216, 50, 'uploads/gallery/game_50_6913069bcb52c.jpg', 0),
(217, 51, 'uploads/gallery/game_51_69134ac94a2e4.jpg', 1),
(218, 51, 'uploads/gallery/game_51_69134ac94aa1e.jpg', 0),
(219, 52, 'uploads/gallery/game_52_69134da0c036e.jpg', 5),
(220, 52, 'uploads/gallery/game_52_69134da0c09ab.jpg', 4),
(221, 52, 'uploads/gallery/game_52_69134da0c0eb2.jpg', 3),
(222, 52, 'uploads/gallery/game_52_69134da0c139a.jpg', 2),
(223, 52, 'uploads/gallery/game_52_69134da0c17fe.jpg', 1),
(224, 52, 'uploads/gallery/game_52_69134da0c1bc3.jpg', 0),
(225, 53, 'uploads/gallery/game_53_69134ed80c5a9.jpg', 5),
(226, 53, 'uploads/gallery/game_53_69134ed80d0af.jpg', 4),
(227, 53, 'uploads/gallery/game_53_69134ed80da50.jpg', 3),
(228, 53, 'uploads/gallery/game_53_69134ed80e13b.jpg', 2),
(229, 53, 'uploads/gallery/game_53_69134ed80e5a9.jpg', 1),
(230, 53, 'uploads/gallery/game_53_69134ed80ea46.jpg', 0),
(231, 54, 'uploads/gallery/game_54_6913506ac57a9.jpg', 5),
(232, 54, 'uploads/gallery/game_54_6913506ac634e.jpg', 4),
(233, 54, 'uploads/gallery/game_54_6913506ac6a24.jpg', 3),
(234, 54, 'uploads/gallery/game_54_6913506ac7583.jpg', 2),
(235, 54, 'uploads/gallery/game_54_6913506ac7bc1.jpg', 1),
(236, 54, 'uploads/gallery/game_54_6913506ac85fc.jpg', 0),
(237, 55, 'uploads/gallery/game_55_691351819507d.jpg', 5),
(238, 55, 'uploads/gallery/game_55_6913518195881.jpg', 4),
(239, 55, 'uploads/gallery/game_55_6913518195f0f.jpg', 3),
(240, 55, 'uploads/gallery/game_55_6913518196513.jpg', 2),
(241, 55, 'uploads/gallery/game_55_6913518196cb9.jpg', 1),
(242, 55, 'uploads/gallery/game_55_69135181975bd.jpg', 0),
(243, 56, 'uploads/gallery/game_56_69135327e9e5f.jpg', 4),
(244, 56, 'uploads/gallery/game_56_69135327eaa7d.jpg', 3),
(245, 56, 'uploads/gallery/game_56_69135327eb1e5.jpg', 2),
(246, 56, 'uploads/gallery/game_56_69135327eb8b1.jpg', 1),
(247, 56, 'uploads/gallery/game_56_69135327ebff2.jpg', 0),
(248, 57, 'uploads/gallery/game_57_6913556215dd7.jpg', 5),
(249, 57, 'uploads/gallery/game_57_69135562164ca.jpg', 4),
(250, 57, 'uploads/gallery/game_57_6913556216c7b.jpg', 3),
(251, 57, 'uploads/gallery/game_57_6913556217255.jpg', 2),
(252, 57, 'uploads/gallery/game_57_69135562178a8.jpg', 1),
(253, 57, 'uploads/gallery/game_57_6913556217d43.jpg', 0),
(254, 58, 'uploads/gallery/game_58_691357fa17106.jpg', 5),
(255, 58, 'uploads/gallery/game_58_691357fa17f96.jpg', 4),
(256, 58, 'uploads/gallery/game_58_691357fa18599.jpg', 3),
(257, 58, 'uploads/gallery/game_58_691357fa18c73.jpg', 2),
(258, 58, 'uploads/gallery/game_58_691357fa191d0.jpg', 1),
(259, 58, 'uploads/gallery/game_58_691357fa196fe.jpg', 0),
(260, 59, 'uploads/gallery/game_59_6913584f83ea9.jpg', 4),
(261, 59, 'uploads/gallery/game_59_6913584f8465f.jpg', 3),
(262, 59, 'uploads/gallery/game_59_6913584f84cc7.jpg', 2),
(263, 59, 'uploads/gallery/game_59_6913584f8522a.jpg', 1),
(264, 59, 'uploads/gallery/game_59_6913584f859b9.jpg', 0),
(265, 60, 'uploads/gallery/game_60_69135a569dbce.jpg', 5),
(266, 60, 'uploads/gallery/game_60_69135a569e180.jpg', 4),
(267, 60, 'uploads/gallery/game_60_69135a569e962.jpg', 3),
(268, 60, 'uploads/gallery/game_60_69135a569ef06.jpg', 2),
(269, 60, 'uploads/gallery/game_60_69135a569f45d.jpg', 1),
(270, 60, 'uploads/gallery/game_60_69135a569f9f8.jpg', 0),
(271, 61, 'uploads/gallery/game_61_691363dca6ef3.jpg', 5),
(272, 61, 'uploads/gallery/game_61_691363dca7b95.jpg', 4),
(273, 61, 'uploads/gallery/game_61_691363dca8031.jpg', 3),
(274, 61, 'uploads/gallery/game_61_691363dca888f.jpg', 2),
(275, 61, 'uploads/gallery/game_61_691363dca8ede.jpg', 1),
(276, 61, 'uploads/gallery/game_61_691363dca93c5.jpg', 0),
(277, 62, 'uploads/gallery/game_62_6913662d66cce.jpg', 5),
(278, 62, 'uploads/gallery/game_62_6913662d6771d.jpg', 4),
(279, 62, 'uploads/gallery/game_62_6913662d67c75.jpg', 3),
(280, 62, 'uploads/gallery/game_62_6913662d6812a.jpg', 2),
(281, 62, 'uploads/gallery/game_62_6913662d68546.jpg', 1),
(282, 62, 'uploads/gallery/game_62_6913662d68949.jpg', 0),
(283, 63, 'uploads/gallery/game_63_6913685a2786f.jpg', 5),
(284, 63, 'uploads/gallery/game_63_6913685a2829d.jpg', 4),
(285, 63, 'uploads/gallery/game_63_6913685a28d36.jpg', 3),
(286, 63, 'uploads/gallery/game_63_6913685a291f0.jpg', 2),
(287, 63, 'uploads/gallery/game_63_6913685a296a6.jpg', 1),
(288, 63, 'uploads/gallery/game_63_6913685a29c44.jpg', 0),
(289, 64, 'uploads/gallery/game_64_69136954df53e.jpg', 4),
(290, 64, 'uploads/gallery/game_64_69136954dfd4c.jpg', 3),
(291, 64, 'uploads/gallery/game_64_69136954e0338.jpg', 2),
(292, 64, 'uploads/gallery/game_64_69136954e08ba.jpg', 1),
(293, 64, 'uploads/gallery/game_64_69136954e0ed7.jpg', 0),
(294, 64, 'uploads/gallery/game_64_69136954e148e.jpg', 5),
(295, 65, 'uploads/gallery/game_65_69136a6ca7e2f.jpg', 5),
(296, 65, 'uploads/gallery/game_65_69136a6ca8509.jpg', 4),
(297, 65, 'uploads/gallery/game_65_69136a6ca8e1f.jpg', 3),
(298, 65, 'uploads/gallery/game_65_69136a6ca9368.jpg', 2),
(299, 65, 'uploads/gallery/game_65_69136a6ca984b.jpg', 1),
(300, 65, 'uploads/gallery/game_65_69136a6ca9e08.jpg', 0),
(301, 66, 'uploads/gallery/game_66_69136bc8b9968.jpg', 5),
(302, 66, 'uploads/gallery/game_66_69136bc8ba131.jpg', 4),
(303, 66, 'uploads/gallery/game_66_69136bc8ba732.jpg', 3),
(304, 66, 'uploads/gallery/game_66_69136bc8bad77.jpg', 2),
(305, 66, 'uploads/gallery/game_66_69136bc8bb320.jpg', 1),
(306, 66, 'uploads/gallery/game_66_69136bc8bb908.jpg', 0),
(307, 67, 'uploads/gallery/game_67_69136e23c2059.jpg', 5),
(308, 67, 'uploads/gallery/game_67_69136e23c273b.jpg', 4),
(309, 67, 'uploads/gallery/game_67_69136e23c3068.jpg', 3),
(310, 67, 'uploads/gallery/game_67_69136e23c3737.jpg', 2),
(311, 67, 'uploads/gallery/game_67_69136e23c3d05.jpg', 1),
(312, 67, 'uploads/gallery/game_67_69136e23c40f3.jpg', 0),
(313, 68, 'uploads/gallery/game_68_69136f0ef027b.jpg', 5),
(314, 68, 'uploads/gallery/game_68_69136f0ef0dd8.jpg', 4),
(315, 68, 'uploads/gallery/game_68_69136f0ef12b9.jpg', 3),
(316, 68, 'uploads/gallery/game_68_69136f0ef16b8.jpg', 2),
(317, 68, 'uploads/gallery/game_68_69136f0ef1b9d.jpg', 1),
(318, 68, 'uploads/gallery/game_68_69136f0ef2051.jpg', 0),
(319, 69, 'uploads/gallery/game_69_6913703b958c7.jpg', 5),
(320, 69, 'uploads/gallery/game_69_6913703b95f5e.jpg', 4),
(321, 69, 'uploads/gallery/game_69_6913703b9692d.jpg', 3),
(322, 69, 'uploads/gallery/game_69_6913703b96f85.jpg', 2),
(323, 69, 'uploads/gallery/game_69_6913703b97567.jpg', 1),
(324, 69, 'uploads/gallery/game_69_6913703b97bce.jpg', 0),
(325, 70, 'uploads/gallery/game_70_69137105ee251.jpg', 5),
(326, 70, 'uploads/gallery/game_70_69137105ee941.jpg', 4),
(327, 70, 'uploads/gallery/game_70_69137105eee34.jpg', 3),
(328, 70, 'uploads/gallery/game_70_69137105ef342.jpg', 2),
(329, 70, 'uploads/gallery/game_70_69137105ef905.jpg', 1),
(330, 70, 'uploads/gallery/game_70_69137105eff56.jpg', 0),
(331, 71, 'uploads/gallery/game_71_6913727ce8a22.jpg', 5),
(332, 71, 'uploads/gallery/game_71_6913727ce9617.jpg', 4),
(333, 71, 'uploads/gallery/game_71_6913727ce9fad.jpg', 3),
(334, 71, 'uploads/gallery/game_71_6913727cea5e1.jpg', 2),
(335, 71, 'uploads/gallery/game_71_6913727ceabc3.jpg', 1),
(336, 71, 'uploads/gallery/game_71_6913727ceb227.jpg', 0),
(337, 72, 'uploads/gallery/game_72_6913735966143.jpg', 5),
(338, 72, 'uploads/gallery/game_72_6913735966c81.jpg', 4),
(339, 72, 'uploads/gallery/game_72_6913735967612.jpg', 3),
(340, 72, 'uploads/gallery/game_72_6913735967be8.jpg', 2),
(341, 72, 'uploads/gallery/game_72_691373596818f.jpg', 1),
(342, 72, 'uploads/gallery/game_72_69137359686cd.jpg', 0),
(343, 73, 'uploads/gallery/game_73_6913747be8bf9.jpg', 5),
(344, 73, 'uploads/gallery/game_73_6913747be925f.jpg', 4),
(345, 73, 'uploads/gallery/game_73_6913747be9f48.jpg', 3),
(346, 73, 'uploads/gallery/game_73_6913747bea49e.jpg', 2),
(347, 73, 'uploads/gallery/game_73_6913747beaa11.jpg', 1),
(348, 73, 'uploads/gallery/game_73_6913747beafe7.jpg', 0),
(349, 74, 'uploads/gallery/game_74_69137593d1297.jpg', 4),
(350, 74, 'uploads/gallery/game_74_69137593d2095.jpg', 3),
(351, 74, 'uploads/gallery/game_74_69137593d28f3.jpg', 2),
(352, 74, 'uploads/gallery/game_74_69137593d3097.jpg', 1),
(353, 74, 'uploads/gallery/game_74_69137593d3767.jpg', 0),
(354, 75, 'uploads/gallery/game_75_69137769e291c.jpg', 5),
(355, 75, 'uploads/gallery/game_75_69137769e2fae.jpg', 4),
(356, 75, 'uploads/gallery/game_75_69137769e39bf.jpg', 3),
(357, 75, 'uploads/gallery/game_75_69137769e3f5b.jpg', 2),
(358, 75, 'uploads/gallery/game_75_69137769e44ac.jpg', 1),
(359, 75, 'uploads/gallery/game_75_69137769e49e8.jpg', 0),
(360, 76, 'uploads/gallery/game_76_6913781fdef06.jpg', 5),
(361, 76, 'uploads/gallery/game_76_6913781fdf6f7.jpg', 4),
(362, 76, 'uploads/gallery/game_76_6913781fdfe0f.jpg', 3),
(363, 76, 'uploads/gallery/game_76_6913781fe057a.jpg', 2),
(364, 76, 'uploads/gallery/game_76_6913781fe0ce8.jpg', 1),
(365, 76, 'uploads/gallery/game_76_6913781fe1352.jpg', 0),
(366, 77, 'uploads/gallery/game_77_69137c38da382.jpg', 5),
(367, 77, 'uploads/gallery/game_77_69137c38daeca.jpg', 4),
(368, 77, 'uploads/gallery/game_77_69137c38db830.jpg', 3),
(369, 77, 'uploads/gallery/game_77_69137c38dc056.jpg', 2),
(370, 77, 'uploads/gallery/game_77_69137c38dc75e.jpg', 1),
(371, 77, 'uploads/gallery/game_77_69137c38dcde7.jpg', 0),
(372, 78, 'uploads/gallery/game_78_69137d42263ce.jpg', 2),
(373, 78, 'uploads/gallery/game_78_69137d4226b01.jpg', 5),
(374, 78, 'uploads/gallery/game_78_69137d4227005.jpg', 4),
(375, 78, 'uploads/gallery/game_78_69137d42275ef.jpg', 3),
(376, 78, 'uploads/gallery/game_78_69137d4227b3d.jpg', 1),
(377, 78, 'uploads/gallery/game_78_69137d42280b7.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `rating_game` tinyint(1) NOT NULL,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `rating_game`, `game_id`, `user_id`) VALUES
(1, 4, 7, 3),
(5, 5, 38, 3),
(8, 5, 14, 3),
(10, 4, 29, 3),
(11, 5, 12, 3),
(13, 5, 39, 1),
(14, 3, 39, 3),
(22, 4, 42, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `sec_prompt` varchar(255) DEFAULT NULL,
  `sec_answer` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_email`, `user_password`, `sec_prompt`, `sec_answer`, `is_admin`) VALUES
(1, 'admin', 'sincerity2103@gmail.com', '$2y$10$PagVgbv92r5BqIe/3hm6GuAlP0/9.iKhCKx3nnjh6BzibtN0kM2Fy', 'prompt_1', '$2y$10$rgN6yG/mXBVk/zFdo6BCW.NvHBrcwlaueYZ5SMVKfgfhn3OtvaV/K', 1),
(3, 'max_beingstepen', 'anwar@gmail.com', '$2y$10$0/2LUq1VSYaoMRxU.AbxVONSO8/wzloB77.NlDYh3ljGK3PcNoA/u', 'prompt_2', '$2y$10$HLXht7TkyDVQiVbcvUBwFOhs3os9cpgJbbto9cuD6ckpxV.lNabHq', 0),
(6, 'irelandboi69', 'irelandboi@gmail.com', '$2y$10$n8Og4Z.8JJLohNqlpFdsS.vPUY0SmELlS7QQompVxRebv0uF4PK1a', 'prompt_4', '$2y$10$gWjf8y5rOBu.NCnWvF0mJOT1.nl3kXQw/vS8TQZvBg2lqpbxzQ3QS', 0),
(23, 'admin21', 'admin21@gmail.com', '$2y$10$ydz26uAkJ1JWqdTLARcpie0tDL8WRiGWN/VZqptMH0tFuDaCHkt2O', 'prompt_1', '$2y$10$RV8.L6xomgj.RV/rMGrQEOR6OV3NtvodISpb7ZgGasd2eRXjmdK9S', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`favourite_id`),
  ADD UNIQUE KEY `user_game_favourite` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `feedback_game`
--
ALTER TABLE `feedback_game`
  ADD PRIMARY KEY (`feedback_game_id`),
  ADD UNIQUE KEY `user_game_feedback` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `feedback_site`
--
ALTER TABLE `feedback_site`
  ADD PRIMARY KEY (`feedback_site_id`),
  ADD UNIQUE KEY `user_feedback` (`user_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD UNIQUE KEY `game_name` (`game_name`);

--
-- Indexes for table `game_cover`
--
ALTER TABLE `game_cover`
  ADD PRIMARY KEY (`game_cover_id`),
  ADD KEY `fk_game_cover_to_game` (`game_id`);

--
-- Indexes for table `game_images`
--
ALTER TABLE `game_images`
  ADD PRIMARY KEY (`game_img_id`),
  ADD KEY `game_images_ibfk_1` (`game_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `user_game_rating` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_username` (`user_username`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `favourite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback_game`
--
ALTER TABLE `feedback_game`
  MODIFY `feedback_game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `feedback_site`
--
ALTER TABLE `feedback_site`
  MODIFY `feedback_site_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `game_cover`
--
ALTER TABLE `game_cover`
  MODIFY `game_cover_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `game_images`
--
ALTER TABLE `game_images`
  MODIFY `game_img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favourites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_game`
--
ALTER TABLE `feedback_game`
  ADD CONSTRAINT `feedback_game_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_game_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_game_ibfk_3` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_game_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_site`
--
ALTER TABLE `feedback_site`
  ADD CONSTRAINT `feedback_site_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `game_cover`
--
ALTER TABLE `game_cover`
  ADD CONSTRAINT `fk_game_cover_to_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE;

--
-- Constraints for table `game_images`
--
ALTER TABLE `game_images`
  ADD CONSTRAINT `game_images_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
