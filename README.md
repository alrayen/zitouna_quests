<div align="center"> <img src="assets/images/logo/logo3.png" alt="Zitouna Quest Logo" width="300"/> <p> <b>Apprendre, Échanger, Collaborer.</b><br> Une plateforme de forum communautaire interactive pour partager ses connaissances et progresser ensemble. </p> <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" /> <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" /> <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" /> <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" /> <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" /> </div> <br>
📖 À propos du projet
Zitouna Quest - Forum Communautaire est une application web éducative conçue pour encourager l'échange et la collaboration entre étudiants. L'objectif est de permettre aux utilisateurs de partager leurs connaissances, poser des questions, et participer à des discussions enrichissantes dans un environnement sécurisé et moderne.

Ce projet a été réalisé dans le cadre de notre cursus universitaire à ESPRIT et Université Laval (2024-2025).

✨ Fonctionnalités Principales
🗣️ Gestion des Posts
Création de posts : Publiez des discussions avec titre et image personnalisée
Modification en temps réel : Éditez vos posts avec validation instantanée
Suppression sécurisée : Supprimez vos posts avec confirmation
Recherche dynamique : Filtrez les discussions par mots-clés
💬 Système de Commentaires
Ajout de commentaires : Participez aux discussions avec validation de contenu
Édition de commentaires : Modifiez vos commentaires en ligne
Suppression de commentaires : Gérez vos contributions
Modération IA : Filtrage automatique des contenus inappropriés
Compteur intelligent : Validation en temps réel (10-1000 caractères)
❤️ Système de Likes
Like/Unlike : Réagissez aux posts avec animation fluide
Protection anti-spam : Impossible de liker deux fois (session PHP)
Compteur en temps réel : Visualisez instantanément les réactions
Persistance : Vos likes sont sauvegardés entre les sessions
🤖 Intelligence Artificielle
Modération automatique : Détection des commentaires inappropriés via IA
Protection de la communauté : Filtrage en temps réel des contenus offensants
Notifications explicites : Messages clairs en cas de contenu refusé
📊 Statistiques & Analytics
Statistiques des posts : Nombre total de publications, posts les plus likés
Statistiques des commentaires : Activité des discussions, engagement utilisateur
Tableaux de bord : Visualisation des données en temps réel
Analyses détaillées : Suivi de l'activité de la plateforme
🎨 Interface Moderne
Design glassmorphism : Interface élégante avec effets de transparence
Thème sombre : Confortable pour les yeux
Responsive : Adapté à tous les appareils (mobile, tablette, desktop)
Animations fluides : Transitions CSS et JavaScript
Notifications toast : Feedback utilisateur instantané
🛠️ Architecture Technique
Front-end : HTML5, CSS3 (Design moderne et responsive), JavaScript ES6+ (AJAX, DOM manipulation)
Back-end : PHP 7.4+ (Architecture MVC, Sessions, PDO)
Base de Données : MySQL 8.0+ (Relations, Cascade, Index)
Sécurité : Requêtes préparées PDO, Validation double (client/serveur), Protection XSS
IA : Modération automatique des commentaires
📸 Aperçu de l'interface
Page Forum - Liste des Posts
<img width="1440" alt="forum-posts" src="https://via.placeholder.com/1440x900/1a1a2e/00C49F?text=Liste+des+Posts" />
<br>
Système de Commentaires & Likes
<img width="1440" alt="comments-likes" src="https://via.placeholder.com/1440x900/1a1a2e/00C49F?text=Commentaires+%26+Likes" />
<br>
Statistiques & Analytics
<img width="1440" alt="statistics" src="https://via.placeholder.com/1440x900/1a1a2e/00C49F?text=Statistiques+du+Forum" />
🚀 Installation
Prérequis
PHP >= 7.4
MySQL >= 8.0
Apache/Nginx (XAMPP/WAMP recommandé)
Étapes
Cloner le repository
git clone https://github.com/votre-username/zitouna-quest-forum.git
cd zitouna-quest-forum
Configurer la base de données
CREATE DATABASE zitouna_quests CHARACTER SET utf8mb4;
USE zitouna_quests;

-- Importer le schéma
SOURCE database/schema.sql;
Configurer la connexion Éditer config.php avec vos identifiants MySQL

Lancer le serveur

# XAMPP/WAMP : Placer dans htdocs/ et démarrer Apache
# OU serveur PHP intégré :
php -S localhost:8000
Accéder à l'application Ouvrir http://localhost:8000/view/front/forum.php
📁 Structure du Projet
zitouna-quest-forum/
├── config/              # Configuration BDD
├── controller/          # Logique métier (CRUD)
├── model/              # Classes PHP (Sujets, Commentaires)
├── view/
│   ├── front/          # Interface utilisateur
│   └── back/           # Administration (stats)
├── assets/
│   ├── css/            # Styles
│   ├── js/             # Scripts
│   └── images/         # Médias
└── database/           # Schéma SQL
🔐 Sécurité
✅ Protection SQL Injection : Requêtes préparées PDO avec bindParam()
✅ Protection XSS : Échappement via htmlspecialchars(ENT_QUOTES)
✅ Validation double : Côté client (JavaScript) et serveur (PHP)
✅ Sessions sécurisées : Protection anti-double-like
✅ Modération IA : Filtrage automatique de contenu inapproprié
👥 L'Équipe



<div align="center"> <i>Fait avec ❤️ par l'équipe Zitouna Quest</i> </div>  
