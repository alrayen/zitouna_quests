-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Dec 11, 2025 at 04:06 AM
-- Server version: 10.6.22-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40496450_projet`
--

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE `challenge` (
  `id_defi` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `difficulty` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`id_defi`, `titre`, `description`, `categorie`, `points`, `time`, `difficulty`, `status`, `place`) VALUES
(1, 'PHP Syntax Fixer', 'Find the missing semicolon and fix the variable name in the provided script.', 'Code', 10, 15, 'Easy', 'Active', 'Lab 101'),
(2, 'Flexbox Froggy', 'Center the div vertically and horizontally using CSS Flexbox.', 'Design', 20, 20, 'Easy', 'Active', 'Design Studio'),
(3, 'SQL Injection Defense', 'Secure the login form against basic SQL injection attacks using prepared statements.', 'Code', 50, 30, 'Hard', 'Active', 'Server Room'),
(4, 'The Fibonacci Sequence', 'Write a function that outputs the first 20 numbers of the Fibonacci sequence.', 'Logic', 30, 25, 'Medium', 'Active', 'Online'),
(5, 'Green Tech Pitch', 'Propose a technological solution to reduce energy consumption in data centers.', 'Innovation', 40, 45, 'Medium', 'Active', 'Conference Hall'),
(6, 'Responsive Navbar', 'Create a navigation bar that turns into a hamburger menu on mobile devices.', 'Design', 35, 40, 'Medium', 'Active', 'Lab 202'),
(7, 'Algorithm Optimization', 'Refactor the sorting algorithm to achieve O(n log n) time complexity.', 'Code', 80, 60, 'Expert', 'Active', 'Lab 105'),
(8, 'Color Theory Quiz', 'Identify the complementary colors for the provided palette.', 'Design', 15, 10, 'Easy', 'Inactive', 'Art Room'),
(9, 'Debug the Loop', 'The while loop is causing an infinite cycle. Find the break condition.', 'Code', 25, 20, 'Medium', 'Active', 'Lab 101'),
(10, 'Cybersecurity Basics', 'Identify the open ports on the target IP using the provided logs.', 'Code', 60, 45, 'Hard', 'Active', 'Security Lab'),
(11, 'Sustainable Packaging', 'Design a prototype for eco-friendly smartphone packaging.', 'Innovation', 45, 60, 'Hard', 'Active', 'Workshop B'),
(12, 'Logical Gates', 'Determine the output of the circuit based on the provided binary inputs.', 'Logic', 20, 15, 'Easy', 'Active', 'Electronics Lab'),
(13, 'Full Stack Hero', 'Build a simple CRUD application for a book library within the time limit.', 'Code', 100, 120, 'Expert', 'Inactive', 'Hackathon Area'),
(14, 'Logo Redesign', 'Modernize the legacy logo while keeping the brand colors.', 'Design', 50, 90, 'Hard', 'Active', 'Remote'),
(15, 'Python Data Parsers', 'Parse the CSV file and calculate the average age of the users.', 'Code', 30, 25, 'Medium', 'Active', 'Lab 103');

-- --------------------------------------------------------

--
-- Table structure for table `online_sessions`
--

CREATE TABLE `online_sessions` (
  `session_id` int(11) NOT NULL,
  `code_invitation` varchar(6) NOT NULL,
  `host_user_id` int(11) NOT NULL,
  `game_state` varchar(20) NOT NULL DEFAULT 'LOBBY',
  `current_question_index` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `current_quiz_id` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `online_sessions`
--

INSERT INTO `online_sessions` (`session_id`, `code_invitation`, `host_user_id`, `game_state`, `current_question_index`, `created_at`, `current_quiz_id`) VALUES
(1, 'AA15B6', 8, 'LOBBY', 0, '2025-11-26 10:21:33', 0),
(2, 'E32B34', 8, 'LOBBY', 0, '2025-11-26 10:51:05', 0),
(3, '1FA093', 8, 'LOBBY', 0, '2025-11-26 10:52:39', 0),
(4, '117541', 8, 'LOBBY', 0, '2025-11-26 11:00:41', 0),
(5, '841A69', 8, 'LOBBY', 0, '2025-11-26 11:17:54', 0),
(6, '523CAA', 8, 'LOBBY', 0, '2025-11-26 11:28:47', 0),
(7, '22C963', 8, 'LOBBY', 0, '2025-11-26 11:50:05', 0),
(8, '86BEDF', 8, 'IN_PROGRESS', 1, '2025-11-26 11:52:40', 0),
(9, '6F0A98', 5, 'IN_PROGRESS', 1, '2025-11-27 09:09:32', 0),
(10, 'AE406D', 5, 'IN_PROGRESS', 1, '2025-11-28 01:53:38', 0),
(11, '6869A2', 5, 'LOBBY', 0, '2025-11-30 09:35:51', 0),
(12, 'B663E1', 5, 'IN_PROGRESS', 1, '2025-11-30 09:46:05', 23),
(13, '6FE09C', 5, 'IN_PROGRESS', 1, '2025-11-30 09:46:38', 14),
(14, 'C0BDD6', 5, 'IN_PROGRESS', 1, '2025-11-30 09:49:40', 11),
(15, '578F32', 5, 'IN_PROGRESS', 1, '2025-11-30 09:49:58', 10),
(16, '280B58', 5, 'IN_PROGRESS', 1, '2025-11-30 09:53:13', 3),
(17, '93AA88', 5, 'IN_PROGRESS', 1, '2025-11-30 09:56:24', 3),
(18, '3BE6EE', 5, 'IN_PROGRESS', 1, '2025-11-30 09:56:45', 5),
(19, '2CD829', 5, 'IN_PROGRESS', 1, '2025-11-30 09:59:08', 5),
(20, 'E37465', 5, 'IN_PROGRESS', 1, '2025-11-30 10:02:13', 5),
(21, '418E5F', 5, 'IN_PROGRESS', 1, '2025-11-30 10:09:04', 11),
(22, '0E07F6', 5, 'IN_PROGRESS', 3, '2025-11-30 10:11:39', 5),
(23, '0AC5E4', 5, 'IN_PROGRESS', 4, '2025-11-30 12:00:49', 5),
(24, '26F147', 5, 'IN_PROGRESS', 2, '2025-11-30 12:08:18', 5),
(25, '240BD7', 5, 'IN_PROGRESS', 3, '2025-11-30 12:13:20', 5),
(26, 'DF9CC5', 5, 'ENDED', 7, '2025-11-30 12:16:59', 5),
(27, '4717BC', 5, 'LOBBY', 0, '2025-11-30 12:20:37', 0),
(28, '934A2D', 5, 'ENDED', 7, '2025-11-30 12:32:00', 5),
(29, '7C30A6', 5, 'IN_PROGRESS', 3, '2025-11-30 12:40:10', 5),
(30, 'A4BF92', 5, 'IN_PROGRESS', 6, '2025-11-30 12:46:20', 5),
(31, '94302D', 5, 'IN_PROGRESS', 1, '2025-11-30 13:47:09', 15),
(32, '031C6B', 5, 'IN_PROGRESS', 7, '2025-11-30 13:47:29', 5),
(33, '2C2767', 5, 'LOBBY', 0, '2025-11-30 13:52:50', 0),
(34, '10C41D', 5, 'ENDED', 7, '2025-12-03 07:59:48', 5),
(35, '576E4B', 5, 'ENDED', 7, '2025-12-03 08:27:52', 5),
(36, 'E46AB8', 5, 'IN_PROGRESS', 1, '2025-12-03 08:31:31', 5),
(37, 'CC96F1', 5, 'ENDED', 1, '2025-12-03 08:32:00', 19),
(38, 'B4D97E', 5, 'IN_PROGRESS', 2, '2025-12-03 08:32:24', 3),
(39, 'DA7218', 5, 'IN_PROGRESS', 1, '2025-12-03 08:36:34', 3),
(40, 'ACD18B', 5, 'IN_PROGRESS', 6, '2025-12-03 08:40:34', 3),
(41, '9FF150', 5, 'ENDED', 7, '2025-12-03 08:42:35', 3),
(42, 'A2CF45', 5, 'ENDED', 7, '2025-12-03 08:53:44', 3),
(43, '65403C', 5, 'IN_PROGRESS', 1, '2025-12-03 09:03:53', 5),
(44, '6D3C44', 5, 'IN_PROGRESS', 1, '2025-12-03 09:05:55', 5),
(45, '9EA4C5', 5, 'IN_PROGRESS', 1, '2025-12-03 09:10:26', 29),
(46, '767037', 5, 'IN_PROGRESS', 1, '2025-12-03 09:15:31', 4),
(47, '795083', 5, 'IN_PROGRESS', 1, '2025-12-03 09:22:52', 30),
(48, '77BBCF', 5, 'IN_PROGRESS', 6, '2025-12-03 09:28:01', 4),
(49, '1766B5', 5, 'IN_PROGRESS', 4, '2025-12-04 09:33:39', 32),
(50, '105931', 9, 'LOBBY', 0, '2025-12-04 10:08:02', 0),
(51, 'BEF370', 5, 'ENDED', 5, '2025-12-05 00:29:09', 33),
(52, '3D7DB2', 5, 'ENDED', 5, '2025-12-05 02:06:49', 34),
(53, '107164', 5, 'LOBBY', 0, '2025-12-10 08:35:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id_question` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `text` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `bonne` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id_question`, `id_quiz`, `text`, `option1`, `option2`, `option3`, `option4`, `bonne`) VALUES
(1, 1, 'Qu\'est-ce que le recyclage ?', 'Jeter des déchets', 'Transformer des déchets en nouveaux produits', 'Brûler des déchets', 'Enterrer des déchets', 'Jeter des déchets'),
(3, 1, 'Quel est un exemple d\'énergie renouvelable ?', 'Le charbon', 'Le pétrole', 'L\'énergie solaire', 'Le gaz naturel', 'Le gaz naturel'),
(4, 2, 'Quelle planète est la plus proche du Soleil ?', 'Vénus', 'Mars', 'Mercure', 'Terre', 'Mercure'),
(5, 2, 'Quel est le symbole chimique de l\\\'eau ?', 'O2', 'H2O', 'CO2', 'NaCl', 'H2O'),
(6, 3, 'En quelle année a eu lieu la chute de l\'Empire Romain d\'Occident ?', '476 ap. J.-C.', '1453 ap. J.-C.', '1789', '1066', '476 ap. J.-C.'),
(7, 3, 'Qui a inventé l\'imprimerie en Europe ?', 'Johannes Gutenberg', 'Léonard de Vinci', 'Isaac Newton', 'Christophe Colomb', 'Johannes Gutenberg'),
(8, 3, 'Quelle est l\'année du début de la Révolution Française ?', '1789', '1812', '1776', '1914', '1789'),
(9, 3, 'Quel pharaon est associé à la construction de la Grande Pyramide de Gizeh ?', 'Ramsès II', 'Toutânkhamon', 'Khéops', 'Cléopâtre', 'Khéops'),
(10, 3, 'En quelle année s\'est terminée la Seconde Guerre Mondiale ?', '1945', '1939', '1918', '1950', '1945'),
(11, 3, 'Qui a dit \"Veni, Vidi, Vici\" ?', 'Jules César', 'Auguste', 'Néron', 'Charlemagne', 'Jules César'),
(12, 3, 'La \"Guerre de Cent Ans\" a principalement opposé :', 'L\'Angleterre et la France', 'L\'Espagne et le Portugal', 'Rome et Carthage', 'Les USA et l\'URSS', 'L\'Angleterre et la France'),
(13, 4, 'Que signifie \"CPU\" ?', 'Central Processing Unit', 'Computer Power Unit', 'Core Processing Unit', 'Central Power Unit', 'Central Processing Unit'),
(14, 4, 'Qui est le co-fondateur d\'Apple Inc. avec Steve Jobs ?', 'Bill Gates', 'Steve Wozniak', 'Paul Allen', 'Larry Page', 'Steve Wozniak'),
(15, 4, 'Qu\'est-ce que l\'HTML ?', 'Un langage de programmation', 'Un langage de balisage', 'Un système d\'exploitation', 'Une base de données', 'Un langage de balisage'),
(16, 4, 'Quel protocole est utilisé pour envoyer des emails ?', 'SMTP', 'HTTP', 'FTP', 'TCP', 'SMTP'),
(17, 4, 'En quelle année le premier iPhone a-t-il été lancé ?', '2005', '2007', '2009', '2001', '2007'),
(18, 4, 'Que signifie \"AI\" ?', 'Artificial Intelligence', 'Automated Interface', 'Algorithmic Input', 'Advanced Internet', 'Artificial Intelligence'),
(19, 4, 'Quel est le \"cerveau\" de l\'ordinateur ?', 'Le disque dur', 'La RAM', 'Le CPU', 'La carte mère', 'Le CPU'),
(20, 5, 'Qui a peint la \"Mona Lisa\" (La Joconde) ?', 'Michel-Ange', 'Raphaël', 'Léonard de Vinci', 'Donatello', 'Léonard de Vinci'),
(21, 5, 'Qui a écrit \"Roméo et Juliette\" ?', 'Charles Dickens', 'William Shakespeare', 'Jane Austen', 'Victor Hugo', 'William Shakespeare'),
(22, 5, 'Quelle ville est surnommée la \"Ville Lumière\" ?', 'Londres', 'Rome', 'New York', 'Paris', 'Paris'),
(23, 5, 'Qui a composé \"La Flûte Enchantée\" ?', 'Beethoven', 'Mozart', 'Bach', 'Chopin', 'Mozart'),
(24, 5, 'Qui a sculpté la statue de \"David\" en marbre ?', 'Michel-Ange', 'Donatello', 'Bernini', 'Rodin', 'Michel-Ange'),
(25, 5, 'Quel mouvement artistique est associé à Salvador Dalí ?', 'Le Cubisme', 'L\'Impressionnisme', 'Le Surréalisme', 'Le Pop Art', 'Le Surréalisme'),
(26, 5, 'Dans quel musée se trouve \"La Joconde\" ?', 'Le MOMA (New York)', 'Le British Museum (Londres)', 'Le Musée du Louvre (Paris)', 'Le Prado (Madrid)', 'Le Musée du Louvre (Paris)'),
(27, 6, 'Que signifie l\'acronyme \"SMART\" pour fixer des objectifs ?', 'Simple, Malin, Actif, Réel, Testé', 'Spécifique, Mesurable, Atteignable, Réaliste, Temporel', 'Supérieur, Magique, Aléatoire, Rapide, Tenu', 'Aucune de ces réponses', 'Spécifique, Mesurable, Atteignable, Réaliste, Temporel'),
(28, 6, 'Laquelle de ces options n\'est PAS une \"soft skill\" (compétence non technique) ?', 'Communication', 'Coder en Python', 'Empathie', 'Gestion du temps', 'Coder en Python'),
(29, 6, 'Qu\'est-ce que la \"pleine conscience\" (mindfulness) ?', 'Dormir profondément', 'Penser au futur', 'Être attentif au moment présent', 'Rêver éveillé', 'Être attentif au moment présent'),
(30, 6, 'Sortir de sa \"zone de confort\" signifie :', 'Rester où l\'on se sent en sécurité', 'Tenter de nouvelles choses qui nous font un peu peur', 'Partir en vacances', 'Acheter un nouveau canapé', 'Tenter de nouvelles choses qui nous font un peu peur'),
(31, 6, 'Lequel est un exemple de \"fixed mindset\" (état d\'esprit fixe) ?', 'Je peux apprendre de mes erreurs', 'L\'échec est une opportunité', 'Je suis nul en maths, c\'est comme ça', 'J\'aime les défis', 'Je suis nul en maths, c\'est comme ça'),
(32, 6, 'L\'intelligence émotionnelle est la capacité de :', 'Résoudre des équations complexes', 'Comprendre et gérer ses émotions et celles des autres', 'Courir très vite', 'Parler plusieurs langues', 'Comprendre et gérer ses émotions et celles des autres'),
(33, 6, 'Qu\'est-ce que la procrastination ?', 'Agir immédiatement', 'Demander de l\'aide', 'Remettre à plus tard ce qu\'on doit faire', 'Finir ses tâches en avance', 'Remettre à plus tard ce qu\'on doit faire'),
(35, 12, 'jhgfc', 'ugj', 'rgg', 'jyfh', 'jyf', 'rgg'),
(36, 14, 'hello', 'rayen', 'amen', 'mohamed', 'ahmed', 'rayen'),
(37, 25, 'What unique property of water is primarily responsible for its high surface tension, high specific heat, and relatively high boiling point compared to other molecules of similar size?', 'Covalent bonding', 'Ionic bonding', 'Hydrogen bonding', 'Metallic bonding', '3'),
(38, 25, 'Water has an unusually high specific heat capacity. What does this property mean for large bodies of water like oceans?', 'They freeze at very high temperatures.', 'They experience rapid and extreme temperature fluctuations.', 'They resist changes in temperature, helping to moderate global climates.', 'They readily absorb gases from the atmosphere.', '3'),
(39, 25, 'Unlike most substances, water becomes less dense as it freezes. At what temperature does liquid water reach its maximum density?', '0Â°C', '4Â°C', '100Â°C', '-4Â°C', '2'),
(40, 25, 'Water is often called the \'universal solvent\' due to its ability to dissolve many different substances. This property is primarily due to water\'s:', 'Nonpolar nature', 'High density', 'Polar nature and ability to form hydrogen bonds', 'Low boiling point', '3'),
(41, 25, 'Pure water has a neutral pH of 7 at 25Â°C. This neutrality is a result of the autoionization of water, where it produces equal concentrations of which two ions?', 'H+ and Cl-', 'Na+ and OH-', 'H3O+ and OH-', 'K+ and SO4^2-', '3'),
(42, 28, 'What is the molecular geometry of a water molecule?', 'Linear', 'Tetrahedral', 'Bent', 'Trigonal planar', '3'),
(43, 28, 'Which property of water allows it to form hydrogen bonds with other water molecules?', 'High specific heat capacity', 'Polarity', 'Low density as a solid', 'Surface tension', '2'),
(44, 28, 'What is the approximate bond angle between hydrogen atoms in a water molecule?', '90 degrees', '109.5 degrees', '120 degrees', '104.5 degrees', '4'),
(45, 28, 'Which of these is NOT a unique property of water due to hydrogen bonding?', 'High boiling point', 'Universal solvent capability', 'Low vapor pressure', 'High surface tension', '2'),
(46, 28, 'What is the chemical formula for heavy water?', 'Hâ‚‚Oâ‚‚', 'Dâ‚‚O', 'Tâ‚‚O', 'HDO', '2'),
(47, 29, 'What is the official name of the game commonly referred to as \'CS:GO 2\'?', 'Counter-Strike: Global Offensive Source 2', 'Counter-Strike 2', 'Counter-Strike: Global Offensive 2', 'Counter-Strike: Source 2', '2'),
(48, 29, 'Which of these features was NOT introduced with the Source 2 engine update for Counter-Strike?', 'Sub-tick update system', 'Smoke grenade volumetric effects', '128-tick matchmaking servers', 'Unified inventory system', '3'),
(49, 29, 'What was the primary technical limitation that prevented smoke grenades from interacting with bullets and other game elements in the original CS:GO?', 'CPU processing limitations', 'Network bandwidth constraints', 'Source 1 engine architecture', 'GPU rendering capabilities', '3'),
(50, 29, 'Which map received the most significant visual and gameplay overhaul in the initial Counter-Strike 2 release?', 'Dust II', 'Overpass', 'Nuke', 'Mirage', '2'),
(51, 30, 'What is the name of the most popular defuse map in CS:GO featuring two bomb sites connected by a mid area?', 'Dust II', 'Mirage', 'Inferno', 'Overpass', '1'),
(52, 30, 'Which of these is NOT a common grenade type used in CS:GO?', 'Flashbang', 'Smoke Grenade', 'Molotov Cocktail', 'Poison Grenade', '4'),
(53, 30, 'What is the main objective for Terrorists in CS:GO?', 'Defuse the bomb', 'Eliminate all Counter-Terrorists', 'Plant the bomb at a site', 'Rescue hostages', '3'),
(54, 30, 'Which map is known for its iconic banana-shaped lane?', 'Nuke', 'Vertigo', 'Cache', 'Inferno', '4'),
(55, 31, 'Which VR headset first introduced the concept of room-scale tracking using external base stations?', 'Oculus Rift CV1', 'HTC Vive', 'PlayStation VR', 'Samsung Gear VR', '2'),
(56, 31, 'What is the minimum recommended refresh rate for VR headsets to avoid motion sickness according to most industry standards?', '60 Hz', '75 Hz', '90 Hz', '120 Hz', '3'),
(57, 31, 'Which technology uses photorealistic 3D captures of real environments to create immersive VR experiences?', 'Volumetric capture', 'Photogrammetry', 'Light field rendering', 'Foveated rendering', '2'),
(58, 31, 'What does \'IPD\' stand for in VR headset specifications, and why is it important?', 'Image Processing Delay - affects motion-to-photon latency', 'Interpupillary Distance - ensures proper lens alignment with user\'s eyes', 'Internal Pixel Density - determines display sharpness', 'Immersion Perception Degree - measures field of view effectiveness', '2'),
(59, 31, 'Which VR tracking technology uses constellation patterns of infrared LEDs for positional tracking?', 'Inside-out tracking', 'Lighthouse tracking', 'Magnetic tracking', 'Constellation tracking (Oculus)', '4'),
(60, 32, 'What is the output of the following code snippet? \n\nlet x = 10;\nlet y = (x++, x + 1);\nconsole.log(y);', '10', '11', '12', 'undefined', '12'),
(61, 32, 'Which of the following methods will NOT create a copy of an array in JavaScript?', 'array.slice()', 'array.concat()', 'array.filter(() => true)', 'array.forEach(() => {})', 'array.forEach(() => {})'),
(62, 32, 'What does the \'use strict\' directive do in JavaScript?', 'Enforces stricter type checking', 'Prevents the use of global variables', 'Enables ES6 features', 'Optimizes the performance of the code', 'Prevents the use of global variables'),
(63, 32, 'Which of the following is NOT a valid way to create an object in JavaScript?', 'let obj = new Object();', 'let obj = Object.create(null);', 'let obj = { key: \'value\' };', 'let obj = new class Obj {};', 'let obj = new class Obj {};'),
(64, 32, 'What will be the value of \'result\' after executing the following code? \n\nconst result = NaN === NaN;', 'true', 'false', 'undefined', 'NaN', 'false'),
(65, 33, 'What is the primary role of an astronaut?', 'Flying airplanes', 'Conducting scientific experiments in space', 'Driving submarines', 'Studying marine life', 'Conducting scientific experiments in space'),
(66, 33, 'Which organization is most associated with astronauts in the United States?', 'FBI', 'NASA', 'CIA', 'NOAA', 'NASA'),
(67, 33, 'What is the name of the suit astronauts wear during spacewalks?', 'Swimsuit', 'EMU (Extravehicular Mobility Unit)', 'Raincoat', 'Tuxedo', 'EMU (Extravehicular Mobility Unit)'),
(68, 33, 'Who was the first human to travel into space?', 'Neil Armstrong', 'Yuri Gagarin', 'Buzz Aldrin', 'John Glenn', 'Yuri Gagarin'),
(69, 33, 'What is the term for the condition where astronauts experience weightlessness in space?', 'Gravity', 'Microgravity', 'Hypergravity', 'Zero speed', 'Microgravity'),
(70, 34, 'What is the primary role of an aestraunaut?', 'Plane Pilot', 'Space Explorer', 'Ship Captain', 'Submarine Operator', 'Space Explorer'),
(71, 34, 'Which planet is an aestraunaut most likely to explore?', 'Earth', 'Mars', 'Venus', 'Mercury', 'Mars'),
(72, 34, 'What essential equipment does an aestraunaut use?', 'Spacesuit', 'Scuba Gear', 'Parachute', 'Ski Goggles', 'Spacesuit'),
(73, 34, 'Which agency trains aestraunauts in the USA?', 'NASA', 'FBI', 'CIA', 'USSR', 'NASA'),
(74, 34, 'What is the main challenge for an aestraunaut?', 'Underwater Pressure', 'Zero Gravity', 'Extreme Heat', 'Dense Forest Navigation', 'Zero Gravity');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id_quiz` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `niveau` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `reward_image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id_quiz`, `titre`, `categorie`, `niveau`, `points`, `reward_image_url`) VALUES
(1, 'kljkhjhv', 'History', 'Easy', 5, NULL),
(2, 'Quiz de Science', 'Science', 'Moyen', 75, NULL),
(3, 'voyage in the history', 'Histoire', 'Moyen', 75, NULL),
(4, 'Innovations Tech', 'Technologie', 'Difficile', 100, NULL),
(5, 'Maîtres de la Renaissance', 'Arts & Culture', 'Moyen', 50, NULL),
(9, 'wert', 'Personal Growth', 'Hard', 558, NULL),
(10, 'jhv', 'Environment', 'Hard', 10, NULL),
(11, '585', 'Personal Growth', 'Extreme', 25, NULL),
(14, 'dfxgcvb', 'Environment', 'Easy', 5, NULL),
(15, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(16, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(17, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(18, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(19, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(20, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(21, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(22, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(23, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(24, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(25, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(26, 'i want a quiz about cs-go 2', 'Technologie', 'Hard', 10, NULL),
(27, 'the earth', 'Science', 'Medium', 10, NULL),
(28, 'The Chemistry of Water', 'Science', 'Medium', 10, NULL),
(29, 'cs-go 2 ', 'Technologie', 'Hard', 10, NULL),
(30, 'cs-go 2 maps and techniques', 'Technologie', 'Easy', 10, NULL),
(31, 'vr technologie', 'Technologie', 'Hard', 10, NULL),
(32, 'AI: javascript general questions', 'Generated', 'Hard', 0, NULL),
(33, 'AI: aestronauts', 'Generated', 'Easy', 0, NULL),
(34, 'AI: aestraunaut', 'Generated', 'Easy', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `session_answers`
--

CREATE TABLE `session_answers` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_index` int(11) NOT NULL,
  `submitted_answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `answer_time` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `session_answers`
--

INSERT INTO `session_answers` (`id`, `session_id`, `user_id`, `question_index`, `submitted_answer`, `is_correct`, `points_earned`, `answer_time`) VALUES
(1, 23, 5, 2, 'Victor Hugo', 0, 0, '2025-11-30 12:05:33'),
(2, 23, 5, 3, 'Paris', 1, 10, '2025-11-30 12:05:42'),
(3, 23, 5, 4, 'Mozart', 1, 10, '2025-11-30 12:07:35'),
(4, 24, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:08:34'),
(5, 24, 5, 2, 'Victor Hugo', 0, 0, '2025-11-30 12:08:45'),
(6, 25, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:13:33'),
(7, 25, 5, 2, 'William Shakespeare', 1, 10, '2025-11-30 12:13:42'),
(8, 26, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:17:08'),
(9, 26, 5, 2, 'William Shakespeare', 1, 10, '2025-11-30 12:17:15'),
(10, 26, 5, 3, 'Paris', 1, 10, '2025-11-30 12:17:21'),
(11, 26, 5, 4, 'Beethoven', 0, 0, '2025-11-30 12:17:25'),
(12, 26, 5, 5, 'Rodin', 0, 0, '2025-11-30 12:17:29'),
(13, 26, 5, 6, 'Le Cubisme', 0, 0, '2025-11-30 12:17:39'),
(14, 26, 5, 7, 'Le MusÃ©e du Louvre (Paris)', 1, 10, '2025-11-30 12:17:53'),
(15, 28, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:33:08'),
(16, 28, 9, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:33:12'),
(17, 28, 5, 2, 'Charles Dickens', 0, 0, '2025-11-30 12:33:19'),
(18, 28, 9, 2, 'William Shakespeare', 1, 10, '2025-11-30 12:33:21'),
(19, 28, 5, 3, 'Paris', 1, 10, '2025-11-30 12:33:29'),
(20, 28, 9, 3, 'Paris', 1, 10, '2025-11-30 12:33:31'),
(21, 28, 5, 4, 'Mozart', 1, 10, '2025-11-30 12:33:38'),
(22, 28, 9, 4, 'Mozart', 1, 10, '2025-11-30 12:33:39'),
(23, 28, 5, 5, 'Bernini', 0, 0, '2025-11-30 12:33:44'),
(24, 28, 9, 5, 'Donatello', 0, 0, '2025-11-30 12:33:47'),
(25, 28, 5, 6, 'L\'Impressionnisme', 0, 0, '2025-11-30 12:33:53'),
(26, 28, 9, 6, 'Le SurrÃ©alisme', 1, 10, '2025-11-30 12:33:56'),
(27, 28, 9, 7, 'Le MusÃ©e du Louvre (Paris)', 1, 10, '2025-11-30 12:34:05'),
(28, 28, 5, 7, 'Le British Museum (Londres)', 0, 0, '2025-11-30 12:34:06'),
(29, 29, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 12:40:56'),
(30, 29, 5, 2, 'William Shakespeare', 1, 10, '2025-11-30 12:41:22'),
(31, 30, 5, 1, 'RaphaÃ«l', 0, 0, '2025-11-30 12:48:35'),
(32, 30, 5, 2, 'William Shakespeare', 1, 10, '2025-11-30 12:52:06'),
(33, 30, 5, 3, 'Paris', 1, 10, '2025-11-30 12:54:30'),
(34, 30, 5, 4, 'Mozart', 1, 10, '2025-11-30 12:54:55'),
(35, 30, 5, 5, 'Rodin', 0, 0, '2025-11-30 12:55:21'),
(36, 32, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-11-30 13:47:51'),
(37, 32, 5, 2, 'William Shakespeare', 1, 10, '2025-11-30 13:47:59'),
(38, 32, 5, 3, 'Paris', 1, 10, '2025-11-30 13:48:38'),
(39, 32, 999, 3, 'Paris', 1, 10, '2025-11-30 13:51:33'),
(40, 32, 5, 4, 'Mozart', 1, 10, '2025-11-30 13:51:41'),
(41, 32, 999, 4, 'Mozart', 1, 10, '2025-11-30 13:51:41'),
(42, 32, 999, 5, 'Michel-Ange', 1, 10, '2025-11-30 13:51:52'),
(43, 32, 5, 5, 'Michel-Ange', 1, 10, '2025-11-30 13:51:52'),
(44, 32, 999, 6, 'Le SurrÃ©alisme', 1, 10, '2025-11-30 13:52:02'),
(45, 32, 5, 6, 'L\'Impressionnisme', 0, 0, '2025-11-30 13:52:05'),
(46, 34, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-12-03 08:00:05'),
(47, 34, 5, 2, 'William Shakespeare', 1, 10, '2025-12-03 08:00:21'),
(48, 34, 5, 3, 'Paris', 1, 10, '2025-12-03 08:00:31'),
(49, 34, 5, 4, 'Mozart', 1, 10, '2025-12-03 08:00:47'),
(50, 34, 5, 5, 'Michel-Ange', 1, 10, '2025-12-03 08:00:56'),
(51, 34, 5, 6, 'Le Pop Art', 0, 0, '2025-12-03 08:01:06'),
(52, 34, 5, 7, 'Le MusÃ©e du Louvre (Paris)', 1, 10, '2025-12-03 08:01:13'),
(53, 35, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-12-03 08:28:04'),
(54, 35, 5, 2, 'William Shakespeare', 1, 10, '2025-12-03 08:28:12'),
(55, 35, 5, 3, 'Paris', 1, 10, '2025-12-03 08:28:18'),
(56, 35, 5, 4, 'Mozart', 1, 10, '2025-12-03 08:28:30'),
(57, 35, 5, 5, 'Michel-Ange', 1, 10, '2025-12-03 08:28:43'),
(58, 35, 5, 6, 'Le Pop Art', 0, 0, '2025-12-03 08:28:51'),
(59, 35, 5, 7, 'Le MusÃ©e du Louvre (Paris)', 1, 10, '2025-12-03 08:28:58'),
(60, 36, 5, 1, 'LÃ©onard de Vinci', 1, 10, '2025-12-03 08:31:41'),
(61, 38, 5, 1, '476 ap. J.-C.', 1, 10, '2025-12-03 08:33:18'),
(62, 39, 5, 1, '476 ap. J.-C.', 1, 10, '2025-12-03 08:36:45'),
(63, 40, 5, 1, '476 ap. J.-C.', 1, 10, '2025-12-03 08:40:45'),
(64, 40, 5, 2, 'Johannes Gutenberg', 1, 10, '2025-12-03 08:41:00'),
(65, 40, 5, 3, '1789', 1, 10, '2025-12-03 08:41:20'),
(66, 40, 5, 4, 'KhÃ©ops', 1, 10, '2025-12-03 08:41:37'),
(67, 40, 5, 5, '1945', 1, 10, '2025-12-03 08:42:27'),
(68, 41, 5, 1, '476 ap. J.-C.', 1, 10, '2025-12-03 08:42:48'),
(69, 41, 5, 2, 'Johannes Gutenberg', 1, 10, '2025-12-03 08:42:54'),
(70, 41, 5, 3, '1789', 1, 10, '2025-12-03 08:43:06'),
(71, 41, 5, 4, 'KhÃ©ops', 1, 10, '2025-12-03 08:43:14'),
(72, 41, 5, 5, '1945', 1, 10, '2025-12-03 08:43:28'),
(73, 41, 5, 6, 'Jules CÃ©sar', 1, 10, '2025-12-03 08:43:40'),
(74, 41, 5, 7, 'L\'Angleterre et la France', 1, 10, '2025-12-03 08:43:57'),
(75, 42, 5, 1, '476 ap. J.-C.', 1, 10, '2025-12-03 08:54:01'),
(76, 42, 5, 2, 'Johannes Gutenberg', 1, 10, '2025-12-03 08:54:07'),
(77, 42, 5, 3, '1789', 1, 10, '2025-12-03 08:54:12'),
(78, 42, 5, 4, 'KhÃ©ops', 1, 10, '2025-12-03 08:54:18'),
(79, 42, 5, 5, '1945', 1, 10, '2025-12-03 08:54:26'),
(80, 42, 5, 6, 'Jules CÃ©sar', 1, 10, '2025-12-03 08:54:33'),
(81, 42, 5, 7, 'L\'Angleterre et la France', 1, 10, '2025-12-03 08:54:44'),
(82, 48, 5, 1, 'Central Processing Unit', 1, 10, '2025-12-03 09:38:04'),
(83, 48, 5, 2, 'Steve Wozniak', 1, 10, '2025-12-03 09:41:16'),
(84, 48, 5, 3, 'Un langage de balisage', 1, 10, '2025-12-03 09:45:05'),
(85, 48, 5, 4, 'SMTP', 1, 10, '2025-12-03 09:45:16'),
(86, 48, 5, 5, '2007', 1, 10, '2025-12-03 09:45:24'),
(87, 48, 5, 6, 'Artificial Intelligence', 1, 10, '2025-12-03 09:45:34'),
(88, 49, 999, 1, '11', 0, 0, '2025-12-04 09:35:36'),
(89, 49, 5, 1, '12', 1, 10, '2025-12-04 09:36:01'),
(90, 49, 999, 2, 'array.slice()', 0, 0, '2025-12-04 09:36:14'),
(91, 49, 5, 2, 'array.slice()', 0, 0, '2025-12-04 09:36:41'),
(92, 49, 999, 3, 'Prevents the use of global variables', 1, 10, '2025-12-04 09:36:55'),
(93, 49, 5, 3, 'Enforces stricter type checking', 0, 0, '2025-12-04 09:37:17'),
(94, 49, 999, 4, 'let obj = Object.create(null);', 0, 0, '2025-12-04 09:37:24'),
(95, 51, 5, 1, 'Conducting scientific experiments in space', 1, 10, '2025-12-05 00:32:08'),
(96, 51, 5, 2, 'NASA', 1, 10, '2025-12-05 00:32:19'),
(97, 51, 5, 3, 'EMU (Extravehicular Mobility Unit)', 1, 10, '2025-12-05 00:32:32'),
(98, 51, 5, 4, 'Yuri Gagarin', 1, 10, '2025-12-05 00:32:54'),
(99, 51, 5, 5, 'Gravity', 0, 0, '2025-12-05 00:33:10'),
(100, 52, 999, 1, 'Submarine Operator', 0, 0, '2025-12-05 02:08:56'),
(101, 52, 5, 1, 'Space Explorer', 1, 10, '2025-12-05 02:10:26'),
(102, 52, 999, 2, 'Venus', 0, 0, '2025-12-05 02:10:55'),
(103, 52, 5, 2, 'Mars', 1, 10, '2025-12-05 02:11:08'),
(104, 52, 5, 3, 'Spacesuit', 1, 10, '2025-12-05 02:11:25'),
(105, 52, 999, 3, 'Parachute', 0, 0, '2025-12-05 02:11:57'),
(106, 52, 999, 4, 'NASA', 1, 10, '2025-12-05 02:16:55'),
(107, 52, 5, 4, 'NASA', 1, 10, '2025-12-05 02:21:17'),
(108, 52, 999, 5, 'Zero Gravity', 1, 10, '2025-12-05 02:21:27'),
(109, 52, 5, 5, 'Zero Gravity', 1, 10, '2025-12-05 02:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `session_players`
--

CREATE TABLE `session_players` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score_total` int(11) NOT NULL DEFAULT 0,
  `is_host` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `session_players`
--

INSERT INTO `session_players` (`id`, `session_id`, `user_id`, `score_total`, `is_host`) VALUES
(1, 1, 8, 0, 1),
(2, 2, 8, 0, 1),
(3, 3, 8, 0, 1),
(4, 3, 9, 0, 0),
(5, 4, 8, 0, 1),
(6, 4, 9, 0, 0),
(7, 5, 8, 0, 1),
(8, 5, 9, 0, 0),
(9, 6, 8, 0, 1),
(10, 6, 9, 0, 0),
(11, 7, 8, 0, 1),
(12, 7, 9, 0, 0),
(13, 8, 8, 0, 1),
(14, 8, 9, 0, 0),
(15, 9, 5, 0, 1),
(16, 9, 9, 0, 0),
(17, 10, 5, 0, 1),
(18, 10, 9, 0, 0),
(19, 11, 5, 0, 1),
(20, 12, 5, 0, 1),
(21, 13, 5, 0, 1),
(22, 14, 5, 0, 1),
(23, 15, 5, 0, 1),
(24, 15, 9, 0, 0),
(25, 16, 5, 0, 1),
(26, 16, 9, 0, 0),
(27, 17, 5, 0, 1),
(28, 18, 5, 0, 1),
(29, 18, 9, 0, 0),
(30, 19, 5, 0, 1),
(31, 19, 9, 0, 0),
(32, 20, 5, 0, 1),
(33, 20, 9, 0, 0),
(34, 21, 5, 0, 1),
(35, 22, 5, 0, 1),
(36, 22, 9, 0, 0),
(37, 23, 5, 20, 1),
(38, 24, 5, 10, 1),
(39, 25, 5, 20, 1),
(40, 26, 5, 40, 1),
(41, 27, 5, 0, 1),
(42, 28, 5, 30, 1),
(43, 28, 9, 60, 0),
(44, 29, 5, 20, 1),
(45, 30, 5, 30, 1),
(46, 31, 5, 0, 1),
(47, 31, 999, 0, 0),
(48, 32, 5, 50, 1),
(49, 32, 999, 40, 0),
(50, 33, 5, 0, 1),
(51, 34, 5, 60, 1),
(52, 35, 5, 60, 1),
(53, 36, 5, 10, 1),
(54, 37, 5, 0, 1),
(55, 38, 5, 10, 1),
(56, 39, 5, 10, 1),
(57, 40, 5, 50, 1),
(58, 41, 5, 70, 1),
(59, 42, 5, 70, 1),
(60, 43, 5, 0, 1),
(61, 44, 5, 0, 1),
(62, 45, 5, 0, 1),
(63, 46, 5, 0, 1),
(64, 47, 5, 0, 1),
(65, 48, 5, 60, 1),
(66, 49, 5, 10, 1),
(67, 49, 999, 10, 0),
(68, 50, 9, 0, 1),
(69, 51, 5, 40, 1),
(70, 52, 5, 50, 1),
(71, 52, 999, 20, 0),
(72, 53, 5, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `secteur` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `contribution` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`id`, `nom`, `secteur`, `contact`, `contribution`) VALUES
(2, 'Ooredoo Tunisie', 'Telecommunications', 'business@ooredoo.tn', 7500.5),
(3, 'Vermeg', 'IT & Software', 'contact@vermeg.com', 3000),
(4, 'BIAT', 'Finance', 'info@biat.com.tn', 10000),
(5, 'Délice Danone', 'Agri-food', 'marketing@delice.tn', 2500),
(6, 'InstaDeep', 'AI & Technology', 'hello@instadeep.com', 12000),
(7, 'p[oiuyfd', 'IT', 'l;kuiytgdf', 8745);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `nom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `niveau` int(11) NOT NULL DEFAULT 1,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `badges` varchar(255) DEFAULT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `points` int(11) NOT NULL DEFAULT 0,
  `Prenom` varchar(255) DEFAULT NULL,
  `birthdate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `role`, `nom`, `email`, `password`, `niveau`, `photo`, `bio`, `badges`, `rank`, `points`, `Prenom`, `birthdate`) VALUES
(5, 0, 'gaied', 'rayen@gmail.com', '$2y$10$CWPxqLQWoaDA1EIq0z2TguySFWJ/m2augTLMqWVXfFm3kONsirk3W', 5, 'profile_5_1763550497.png', 'null', 'null', 0, 120, 'rayen', '2002-06-03'),
(6, 0, 'hgfds', 'admin1@gmail.com', '$2y$10$KefkUdKiw4baJA..3obkqO02eF7P/.g25RZamWQhuNNPhENWY6aE2', 1, 'profile_6_1763396128.png', 'null', 'null', 0, 0, 'asdfgh', '2006-07-20'),
(7, 1, 'gaied', 'admin@gmail.com', '$2y$10$gPWNqsiLpE/yzq1pjBz.L.zeKhtxFLRJdvavsZKKLxpe1H14bYzqW', 1, 'null', 'null', 'null', 0, 0, 'rayen', '2007-04-20'),
(8, 0, 'gaied', 'rayen1@gmail.com', '$2y$10$S/Bvb5LpvrVyPjurJsUrX.sP102RtrKXKHABR6usNXLuwQUzL/9Mi', 1, 'profile_8_1764172626.png', 'null', 'null', 0, 0, 'rayen', '2011-02-11'),
(9, 0, 'vhbjnkm', 'rayenn@gmail.com', '$2y$10$w3MJYw1SYZVZJWOQwtbzr.1gTI84976f3KDgr8sAGELKoJpeNso62', 1, 'null', 'null', 'null', 0, 0, 'cgvhbjnm', '2002-02-14'),
(999, 0, 'ZitounaBot 🤖', 'bot@zitouna.com', 'botpass', 100, NULL, NULL, NULL, 0, 0, NULL, ''),
(1000, 0, 'Med', 'mohamedbenhariz8@gmail.com', '$2y$10$gobBXNygZANF7oMj..NPIu0FVC.Nx4GQ.hKfaKggVonKZ6/RVA.LK', 1, 'null', 'null', 'null', 0, 0, 'Med', '2005-12-03'),
(1001, 0, 'You (Guest)', 'guest_6939a1656f99e@temp.com', NULL, 1, NULL, NULL, NULL, 0, 0, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_collections`
--

CREATE TABLE `user_collections` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `collected_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_collections`
--

INSERT INTO `user_collections` (`id`, `user_id`, `quiz_id`, `image_url`, `collected_at`) VALUES
(1, 5, 3, 'https://image.pollinations.ai/prompt/voyage+in+the+history+epic+futuristic+video+game+poster+style+high+resolution+detailed?width=300&height=450&nologo=true', '2025-12-03 08:54:44'),
(2, 5, 33, 'https://image.pollinations.ai/prompt/AI%3A+aestronauts+epic+futuristic+video+game+poster+style+high+resolution+detailed?width=300&height=450&nologo=true', '2025-12-05 00:33:10'),
(3, 5, 34, 'https://image.pollinations.ai/prompt/AI%3A+aestraunaut+epic+futuristic+video+game+poster+style+high+resolution+detailed?width=300&height=450&nologo=true', '2025-12-05 02:21:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id_defi`);

--
-- Indexes for table `online_sessions`
--
ALTER TABLE `online_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `code_invitation` (`code_invitation`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id_question`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id_quiz`);

--
-- Indexes for table `session_answers`
--
ALTER TABLE `session_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_q_index` (`session_id`,`user_id`,`question_index`);

--
-- Indexes for table `session_players`
--
ALTER TABLE `session_players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_collections`
--
ALTER TABLE `user_collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `challenge`
--
ALTER TABLE `challenge`
  MODIFY `id_defi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `online_sessions`
--
ALTER TABLE `online_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `session_answers`
--
ALTER TABLE `session_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `session_players`
--
ALTER TABLE `session_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `user_collections`
--
ALTER TABLE `user_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
