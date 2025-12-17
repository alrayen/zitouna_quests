Zitouna Quest - Forum Communautaire
Apprendre, Échanger, Collaborer.

Une plateforme de forum communautaire interactive pour partager ses connaissances et progresser ensemble.

PHP MySQL HTML5 CSS3 JavaScript

À propos
Zitouna Quest est une application web éducative conçue pour encourager l'échange et la collaboration entre étudiants. Les utilisateurs peuvent partager leurs connaissances, poser des questions, et participer à des discussions dans un environnement sécurisé et moderne.

Projet réalisé dans le cadre du cursus universitaire à ESPRIT  (2025-2026).

Fonctionnalités

Gestion des Posts: Création, modification et suppression de posts
Upload d'images pour illustrer les discussions
Recherche dynamique par mots-clés

Système de Commentaires: Ajout et édition de commentaires avec validation (10-1000 caractères)
Modération automatique par IA pour détecter les contenus inappropriés
Compteur de caractères en temps réel
Système de Likes
Like/Unlike avec animation fluide

Protection anti-spam : impossible de liker deux fois (session PHP)
Compteur de likes en temps réel
Statistiques
Tableaux de bord avec statistiques des posts
Analyses de l'activité des commentaires
Suivi de l'engagement utilisateur
Interface
Design moderne avec thème sombre
Responsive (mobile, tablette, desktop)
Animations fluides et notifications
Aperçu
Interface principale du forum

Forum Screenshot 1

Système de commentaires et likes

Forum Screenshot 2

Technologies
Frontend : HTML5, CSS3, JavaScript ES6+
Backend : PHP 7.4+ (Architecture MVC)
Base de données : MySQL 8.0+
Sécurité : PDO (requêtes préparées), protection XSS, validation double
IA : Modération automatique des commentaires
Installation
Prérequis
PHP >= 7.4
MySQL >= 8.0
Apache ou Nginx (XAMPP/WAMP recommandé)
Étapes
Cloner le projet
git clone https://github.com/votre-username/zitouna-quest-forum.git
Créer la base de données
CREATE DATABASE zitouna_quests CHARACTER SET utf8mb4;
Importer le schéma SQL
mysql -u root -p zitouna_quests < database/schema.sql
Configurer la connexion dans config.php

Lancer le serveur et accéder à http://localhost/view/front/forum.php


Sécurité:
Protection SQL Injection (requêtes préparées PDO)
Protection XSS (htmlspecialchars)
Validation côté client et serveur
Sessions sécurisées
Modération IA des contenus

Equipe

Mohamed Ben Hariz
Rayen Gaied
Ahmed El Mokhtar
Nafissatou Souley BouBou
Houssem Laasili


