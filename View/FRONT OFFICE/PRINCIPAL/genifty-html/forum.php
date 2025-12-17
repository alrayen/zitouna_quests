<?php 
include "../../../../Controller/crudSujet.php";
include "../../../../Controller/crudCommentaire.php";

$sujets_result = afficherSujet();
$sujets = [];
if($sujets_result) {
    $sujets = $sujets_result->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir l'URL de l'image
function getImageUrl($image_name, $post_id) {
    // Si l'image existe dans la base de données
    if (!empty($image_name)) {
        // Vérifier si c'est déjà une URL complète
        if (filter_var($image_name, FILTER_VALIDATE_URL)) {
            return $image_name;
        }
        
        // Chemin relatif vers le dossier d'images
        $local_path = "../../../../Controller/images/";
        
        // Vérifier si l'image existe localement
        if (file_exists($local_path . $image_name)) {
            return "../../../../Controller/images/" . $image_name;
        }
    }
    
    // Images par défaut si aucune image n'est spécifiée ou si elle n'existe pas
    $default_images = [
        'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=400&fit=crop',
        'https://images.unsplash.com/photo-1466611653911-95081537e5b7?w=800&h=400&fit=crop',
        'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=400&fit=crop',
        'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=400&fit=crop',
        'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800&h=400&fit=crop',
    ];
    
    $image_index = $post_id % count($default_images);
    return $default_images[$image_index];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests || Forum Communautaire</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Styles généraux */
        .forum-header {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.1), rgba(255, 187, 40, 0.1));
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .forum-search {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px 20px;
            color: white;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .forum-search:focus {
            outline: none;
            border-color: #00C49F;
            box-shadow: 0 0 0 2px rgba(0, 196, 159, 0.2);
        }

        /* Post Card Styles */
        .post-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }
        
        .post-image-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.1), rgba(0, 136, 254, 0.1));
        }
        
        .post-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.05) 25%, 
                rgba(255, 255, 255, 0.1) 50%, 
                rgba(255, 255, 255, 0.05) 75%
            );
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        .post-image.loaded {
            animation: none;
        }
        
        .post-card:hover .post-image {
            transform: scale(1.05);
        }
        
        .post-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
            pointer-events: none;
        }
        
        .post-category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.95), rgba(0, 168, 133, 0.95));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 196, 159, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 2;
        }

        .post-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.1), rgba(255, 187, 40, 0.1));
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .post-title {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            margin: 0;
            flex: 1;
            min-width: 200px;
        }

        .post-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .post-action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .post-action-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .post-action-btn.edit {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.2), rgba(0, 196, 159, 0.1));
            border-color: rgba(0, 196, 159, 0.4);
        }

        .post-action-btn.edit:hover {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.3), rgba(0, 196, 159, 0.2));
            border-color: #00C49F;
            color: #00C49F;
            box-shadow: 0 4px 15px rgba(0, 196, 159, 0.3);
        }

        .post-action-btn.delete {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.2), rgba(255, 107, 107, 0.1));
            border-color: rgba(255, 107, 107, 0.4);
        }

        .post-action-btn.delete:hover {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.3), rgba(255, 107, 107, 0.2));
            border-color: #ff6b6b;
            color: #ff6b6b;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .post-action-btn.save {
            background: linear-gradient(135deg, #00C49F, #00a885);
            border-color: #00C49F;
            color: white;
        }
        
        .post-action-btn.save:hover {
            background: linear-gradient(135deg, #00a885, #008f73);
            box-shadow: 0 4px 15px rgba(0, 196, 159, 0.4);
        }
        
        .post-action-btn.cancel {
            background: rgba(150, 150, 150, 0.2);
            border-color: rgba(150, 150, 150, 0.4);
        }
        
        .post-action-btn.cancel:hover {
            background: rgba(150, 150, 150, 0.3);
            border-color: #999;
        }

        .post-meta {
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .post-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .post-stats {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .post-stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #bbb;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .post-stat-item:hover {
            color: #00C49F;
        }
        
        .post-stat-item i {
            font-size: 16px;
        }
        
        .post-stat-item .stat-number {
            font-weight: 600;
            color: white;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00C49F, #0088FE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 16px;
        }

        .post-date {
            color: #bbb;
            font-size: 14px;
        }

        .btn-show-comments {
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border: none;
            color: white;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 196, 159, 0.25);
        }

        .btn-show-comments:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 196, 159, 0.4);
            background: linear-gradient(135deg, #00a885, #0077dd);
        }
        
        .btn-show-comments:active {
            transform: translateY(-1px);
        }

        /* Comments Section */
        .comments-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .comments-container.active {
            max-height: 2000px;
            padding: 30px;
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
            gap: 15px;
        }

        .comments-count {
            color: #00C49F;
            font-weight: 600;
            font-size: 18px;
        }

        .btn-add-comment {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.25), rgba(0, 136, 254, 0.25));
            border: 1px solid rgba(0, 196, 159, 0.5);
            color: #00C49F;
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 196, 159, 0.15);
        }

        .btn-add-comment:hover {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.35), rgba(0, 136, 254, 0.35));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 196, 159, 0.25);
            border-color: #00C49F;
        }

        .comment-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }

        .comment-header-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .comment-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comment-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFBB28, #FF8042);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 14px;
        }

        .comment-username {
            font-weight: 600;
            color: #fff;
        }

        .comment-date-small {
            color: #999;
            font-size: 13px;
        }

        .comment-actions-small {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .comment-action-small {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #bbb;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .comment-action-small:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .comment-action-small.edit {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.15), rgba(0, 196, 159, 0.08));
            border-color: rgba(0, 196, 159, 0.3);
        }

        .comment-action-small.edit:hover {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.25), rgba(0, 196, 159, 0.15));
            color: #00C49F;
            border-color: #00C49F;
            box-shadow: 0 3px 12px rgba(0, 196, 159, 0.2);
        }

        .comment-action-small.delete {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.08));
            border-color: rgba(255, 107, 107, 0.3);
        }

        .comment-action-small.delete:hover {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.25), rgba(255, 107, 107, 0.15));
            color: #ff6b6b;
            border-color: #ff6b6b;
            box-shadow: 0 3px 12px rgba(255, 107, 107, 0.2);
        }

        .comment-content {
            color: #ddd;
            line-height: 1.6;
            padding-left: 45px;
        }
        
        .comment-edit-form {
            padding-left: 0;
        }

        .no-comments {
            text-align: center;
            padding: 30px;
            color: #888;
        }
        
        /* Validation Styles */
        .error-message {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.2), rgba(255, 71, 87, 0.15));
            border: 1px solid rgba(255, 107, 107, 0.5);
            border-left: 4px solid #ff6b6b;
            color: #ffb3b3;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
        }
        
        .error-message.show {
            display: flex;
        }
        
        .error-message i {
            font-size: 18px;
            color: #ff6b6b;
        }
        
        .success-message {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.2), rgba(0, 168, 133, 0.15));
            border: 1px solid rgba(0, 196, 159, 0.5);
            border-left: 4px solid #00C49F;
            color: #66f5d6;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 196, 159, 0.2);
        }
        
        .success-message.show {
            display: flex;
        }
        
        .success-message i {
            font-size: 18px;
            color: #00C49F;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-textarea.error {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2);
        }
        
        .form-textarea.error:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.3);
        }
        
        input[type="text"].error {
            border-color: #ff6b6b !important;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2) !important;
        }
        
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        .char-counter.warning {
            color: #FFBB28;
        }
        
        .char-counter.error {
            color: #ff6b6b;
        }
        
        /* Notification System */
        .notification-container {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
        }
        
        .notification {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.95), rgba(0, 168, 133, 0.95));
            border: 1px solid rgba(0, 196, 159, 0.5);
            border-left: 4px solid #00C49F;
            color: white;
            padding: 18px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 196, 159, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 320px;
            animation: slideInRight 0.4s ease, fadeOut 0.4s ease 4.6s;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        
        .notification::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            animation: progressBar 5s linear;
        }
        
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        .notification.success {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.95), rgba(0, 168, 133, 0.95));
            border-left-color: #00C49F;
        }
        
        .notification.error {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.95), rgba(255, 71, 87, 0.95));
            border: 1px solid rgba(255, 107, 107, 0.5);
            border-left-color: #ff6b6b;
        }
        
        .notification.warning {
            background: linear-gradient(135deg, rgba(255, 187, 40, 0.95), rgba(255, 167, 38, 0.95));
            border: 1px solid rgba(255, 187, 40, 0.5);
            border-left-color: #FFBB28;
        }
        
        .notification.info {
            background: linear-gradient(135deg, rgba(0, 136, 254, 0.95), rgba(0, 119, 221, 0.95));
            border: 1px solid rgba(0, 136, 254, 0.5);
            border-left-color: #0088FE;
        }
        
        .notification-icon {
            font-size: 24px;
            min-width: 24px;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 4px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .notification-message {
            font-size: 13px;
            opacity: 0.95;
            line-height: 1.4;
        }
        
        .notification-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .notification-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(400px);
            }
        }
        
        @media (max-width: 768px) {
            .notification-container {
                right: 15px;
                left: 15px;
                top: 80px;
                max-width: none;
            }
            
            .notification {
                min-width: auto;
                width: 100%;
            }
        }

        /* Add Comment Form */
        .add-comment-form {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            display: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .add-comment-form.active {
            display: block;
        }

        .form-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 15px;
            color: white;
            resize: vertical;
            min-height: 100px;
            margin-bottom: 15px;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #00C49F;
            box-shadow: 0 0 0 2px rgba(0, 196, 159, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-submit-comment {
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit-comment:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 196, 159, 0.3);
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-new-topic {
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-new-topic:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 196, 159, 0.3);
        }

        /* Delete Modal */
        .delete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .delete-modal.active {
            display: flex !important;
        }
        
        .delete-modal-content {
            background: rgba(45, 45, 45, 0.95);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .delete-modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }

        .btn-confirm-delete {
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-confirm-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }

        /* Sidebar */
        .active-users {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .comment-content {
                padding-left: 0;
                margin-top: 10px;
            }
            
            .post-image-container {
                height: 200px;
            }
            
            .post-stats {
                flex-wrap: wrap;
                justify-content: center;
                padding: 12px 20px;
            }
            
            .post-stat-item {
                font-size: 13px;
            }
            
            .post-category-badge {
                top: 10px;
                left: 10px;
                padding: 6px 12px;
                font-size: 12px;
            }

        }
        .coeur-rouge
        {
            color:red;
        }
    </style>
</head>

<body class="rt_bg-secondary">

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <!-- Delete Modal for Posts -->
    <div class="delete-modal" id="deletePostModal">
        <div class="delete-modal-content">
            <i class="far fa-exclamation-triangle" style="font-size: 48px; color: #ff6b6b; margin-bottom: 15px;"></i>
            <h4 style="color: white; margin-bottom: 10px;">Confirmer la suppression</h4>
            <p style="color: #bbb; margin-bottom: 20px;">Êtes-vous sûr de vouloir supprimer ce post ?</p>
            <div class="delete-modal-buttons">
                <button class="btn-cancel" onclick="closeDeletePostModal()">Annuler</button>
                <button class="btn-confirm-delete" onclick="confirmDeletePost()">Supprimer</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal for Comments -->
    <div class="delete-modal" id="deleteCommentModal">
        <div class="delete-modal-content">
            <i class="far fa-exclamation-triangle" style="font-size: 48px; color: #ff6b6b; margin-bottom: 15px;"></i>
            <h4 style="color: white; margin-bottom: 10px;">Confirmer la suppression</h4>
            <p style="color: #bbb; margin-bottom: 20px;">Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
            <div class="delete-modal-buttons">
                <button class="btn-cancel" onclick="closeDeleteCommentModal()">Annuler</button>
                <button class="btn-confirm-delete" onclick="confirmDeleteComment()">Supprimer</button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="rts-header-area header-inner-one header--sticky">
        <div class="container-header">
            <div class="row align-items-center ptb_sm--20 padding-controler-header">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
                    <div class="header-left">
                        <a href="index.html" class="logo">
                            <img src="assets/images/logo/logo3.png" alt="NFT_image">
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 d-xl-block d-none">
                    <div class="main-menu-wrapepr">
                        <nav class="mainmenu-nav d-none d-xl-block">
                            <ul class="main-menu">
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="index.html">Home</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="quiz.php">Quests</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="forum.php">Forum</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12">
                    <div class="header-right">
                        <ul class="icons">
                            <li class="icon user"> <a href="author.html"><i class="far fa-user"></i></a></li>
                            <li class="icon notification"> <a href="#"><i class="far fa-bell"></i></a></li>
                        </ul>
                        <a href="login.html" class="rts-btn btn-primary">login / sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Forum Content -->
    <div class="rts-banner-area banner-one rt_bg-secondary" style="padding: 100px 0 50px;">
        <div class="container">
            <div class="forum-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="title">Forum Communautaire</h1>
                        <p class="disc">
                            Échangez, apprenez et collaborez avec notre communauté d'apprenants passionnés.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <button onclick="window.location.href='post.php';" class="btn-new-topic">
                            <i class="far fa-plus"></i> Nouveau Post
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <input type="text" class="forum-search" placeholder="Rechercher dans le forum..." id="searchInput">
                    
                    <!-- Posts List -->
                    <div id="postsList">
                        <?php 
                        if($sujets && count($sujets) > 0):
                            foreach($sujets as $sujet): 
                                $post_id = $sujet['id'];
                                $commentaires_result = afficherCommentaireParPost($post_id);
                                $comment_count = 0;
                                $commentaires_array = [];
                                
                                if($commentaires_result) {
                                    $commentaires_array = $commentaires_result->fetchAll(PDO::FETCH_ASSOC);
                                    $comment_count = count($commentaires_array);
                                }
                                
                                // Récupérer le nom de l'image depuis la base de données
                                $image_name = isset($sujet['imagee']) ? $sujet['imagee'] : '';
                                
                                // Obtenir l'URL de l'image
                                $image_url = getImageUrl($image_name, $post_id);
                                
                                
                                
                                
                               
                                
                                
                        ?>
                        <div class="post-card" data-post-id="<?= $post_id ?>">
                            <!-- Image du post -->
                            <div class="post-image-container">
                                <img src="<?= $image_url ?>" 
                                     alt="<?= htmlspecialchars($sujet['nom']) ?>" 
                                     class="post-image"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=400&fit=crop';">
                                <div class="post-image-overlay"></div>
                               
                            </div>
                            
                            <div class="post-header">
                                <!-- Vue normale du titre -->
                                <h3 class="post-title" id="post-title-view-<?= $post_id ?>">
                                    <?= htmlspecialchars($sujet['nom']) ?>
                                </h3>
                                
                                <!-- Formulaire d'édition du titre (caché par défaut) -->
                                <div id="post-title-edit-<?= $post_id ?>" style="display: none; flex: 1;">
                                    <form method="POST" action="../../../../Controller/modifierSujetController.php?position=front&id=<?= $post_id ?>" style="display: flex; gap: 10px; align-items: flex-start; flex-wrap: wrap; width: 100%;" onsubmit="return validatePostForm(<?= $post_id ?>)">
                                        <div style="flex: 1; min-width: 250px;">
                                            <div class="error-message" id="post-error-<?= $post_id ?>">
                                                <i class="far fa-exclamation-circle"></i>
                                                <span id="post-error-text-<?= $post_id ?>"></span>
                                            </div>
                                            <input type="hidden" name="id" value="<?= $post_id ?>">
                                            <input 
                                                type="text" 
                                                name="nom" 
                                                id="post-input-<?= $post_id ?>"
                                                value="<?= htmlspecialchars($sujet['nom']) ?>" 
                                                
                                                

                                                style="width: 100%; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white; padding: 12px 15px; border-radius: 10px; font-size: 18px; font-weight: 600; transition: all 0.3s ease;"
                                                oninput="validatePostInput(<?= $post_id ?>)"
                                            >
                                            <div class="char-counter" id="post-counter-<?= $post_id ?>">
                                                <span id="post-char-count-<?= $post_id ?>"><?= strlen($sujet['nom']) ?></span>/200
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <button type="submit" name="modifier_post" class="post-action-btn save">
                                                <i class="far fa-save"></i> Sauvegarder
                                            </button>
                                            <button type="button" class="post-action-btn cancel" onclick="toggleEditPost(<?= $post_id ?>)">
                                                <i class="far fa-times"></i> Annuler
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="post-actions" id="post-actions-<?= $post_id ?>">
                                    <button class="post-action-btn edit" onclick="toggleEditPost(<?= $post_id ?>)">
                                        <i class="far fa-edit"></i> Modifier
                                    </button>
                                    <button class="post-action-btn delete" onclick="openDeletePostModal(<?= $post_id ?>)">
                                        <i class="far fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Statistiques du post -->
                            <div class="post-stats">
                               
                                <?php if($sujet['likes']>0):?>
                                <div onclick="window.location.href='../../../../Controller/likeController.php?id=<?=$sujet['id']?>&choix=decrement';" class="post-stat-item">
                                   <span style="color:red; font-size:24px;">❤️ </span>
                                    <span class="stat-number"><?=$sujet['likes']?></span> likes
                                </div>
                                <?php else:?>
                                <div onclick="window.location.href='../../../../Controller/likeController.php?id=<?=$sujet['id']?>&choix=increment';" class="post-stat-item">
                                    <span style="font-size:24px;">🤍</span>

                                    <span class="stat-number"><?=$sujet['likes']?></span> likes
                                </div>
                                <?php endif;?>
                              <!--  <div class="post-stat-item">
                                    <i class="far fa-share"></i>
                                    <span class="stat-number">15</span> partages
                                </div>-->
                            </div>
                            
                            <div class="post-meta">
                                <div class="post-info">
                                    <div class="user-avatar">E</div>
                                    <div>
                                        <div style="color: #fff; font-weight: 600;">@EcoWarrior</div>
                                        <div class="post-date">
                                            <i class="far fa-clock"></i>
                                            <?= $sujet['date_sujets'] ?>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn-show-comments" onclick="toggleComments(<?= $post_id ?>)">
                                    <i class="far fa-comments"></i>
                                    <span id="comment-count-<?= $post_id ?>"><?= $comment_count ?></span> Commentaire<?= $comment_count > 1 ? 's' : '' ?>
                                </button>
                            </div>

                            <!-- Comments Container -->
                            <div class="comments-container" id="comments-<?= $post_id ?>">
                                <div class="comments-header">
                                    <h4 class="comments-count">
                                        <i class="far fa-comments"></i>
                                        <?= $comment_count ?> Commentaire<?= $comment_count > 1 ? 's' : '' ?>
                                    </h4>
                                    <button class="btn-add-comment" onclick="toggleAddCommentForm(<?= $post_id ?>)">
                                        <i class="far fa-plus"></i> Ajouter un commentaire
                                    </button>
                                </div>

                                <!-- Add Comment Form -->
                                <div class="add-comment-form" id="add-comment-form-<?= $post_id ?>">
                                    <form method="POST" action="../../../../Controller/ajouterCommentaireController.php?post=<?= $post_id ?>&position=front" onsubmit="return validateCommentForm('add', <?= $post_id ?>)">
                                        <div class="error-message" id="add-comment-error-<?= $post_id ?>">
                                            <i class="far fa-exclamation-circle"></i>
                                            <span id="add-comment-error-text-<?= $post_id ?>"></span>
                                        </div>
                                        <textarea 
                                            name="contenu" 
                                            id="add-comment-textarea-<?= $post_id ?>"
                                            class="form-textarea" 
                                            placeholder="Partagez votre avis, posez une question..."
                                            
                                            
                                            oninput="validateCommentInput('add', <?= $post_id ?>)"
                                        ></textarea>
                                        <div class="char-counter" id="add-comment-counter-<?= $post_id ?>">
                                            <span id="add-comment-char-count-<?= $post_id ?>">0</span>/1000 caractères (minimum 10)
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="btn-cancel" onclick="toggleAddCommentForm(<?= $post_id ?>)">
                                                <i class="far fa-times"></i> Annuler
                                            </button>
                                            <button type="submit" name="ajouter_commentaire" class="btn-submit-comment">
                                                <i class="far fa-paper-plane"></i> Publier
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Comments List -->
                                <div class="comments-list">
                                    <?php 
                                    if($comment_count > 0):
                                        foreach($commentaires_array as $commentaire):
                                    ?>
                                    <div class="comment-item">
                                        <div class="comment-header-item">
                                            <div class="comment-author">
                                                <div class="comment-avatar">N</div>
                                                <div>
                                                    <div class="comment-username">Utilisateur</div>
                                                    <div class="comment-date-small">
                                                        <i class="far fa-clock"></i>
                                                        <?= $commentaire['date_commentaires'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="comment-actions-small">
                                                <button class="comment-action-small edit" onclick="toggleEditComment(<?= $commentaire['id'] ?>)">
                                                    <i class="far fa-edit"></i> Modifier
                                                </button>
                                                <button class="comment-action-small delete" onclick="openDeleteCommentModal(<?= $commentaire['id'] ?>)">
                                                    <i class="far fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Vue normale du commentaire -->
                                        <div class="comment-content" id="comment-view-<?= $commentaire['id'] ?>">
                                            <?= nl2br(htmlspecialchars($commentaire['contenu'])) ?>
                                        </div>
                                        
                                        <!-- Formulaire d'édition du commentaire (caché par défaut) -->
                                        <div class="comment-edit-form" id="comment-edit-<?= $commentaire['id'] ?>" style="display: none;">
                                            <form method="POST" action="../../../../Controller/modifierCommentaireController.php?position=front&id=<?= $commentaire['id'] ?>" onsubmit="return validateCommentForm('edit', <?= $commentaire['id'] ?>)">
                                                <div class="error-message" id="edit-comment-error-<?= $commentaire['id'] ?>">
                                                    <i class="far fa-exclamation-circle"></i>
                                                    <span id="edit-comment-error-text-<?= $commentaire['id'] ?>"></span>
                                                </div>
                                                <input type="hidden" name="id" value="<?= $commentaire['id'] ?>">
                                                <textarea 
                                                    name="contenu" 
                                                    id="edit-comment-textarea-<?= $commentaire['id'] ?>"
                                                    class="form-textarea" 
                                                    
                                                   
                                                    style="margin-left: 45px; min-height: 80px;"
                                                    oninput="validateCommentInput('edit', <?= $commentaire['id'] ?>)"
                                                ><?= htmlspecialchars($commentaire['contenu']) ?></textarea>
                                                <div class="char-counter" id="edit-comment-counter-<?= $commentaire['id'] ?>" style="margin-left: 45px;">
                                                    <span id="edit-comment-char-count-<?= $commentaire['id'] ?>"><?= strlen($commentaire['contenu']) ?></span>/1000 caractères (minimum 10)
                                                </div>
                                                <div class="form-actions" style="margin-left: 45px; margin-top: 10px;">
                                                    <button type="button" class="btn-cancel" onclick="toggleEditComment(<?= $commentaire['id'] ?>)">
                                                        <i class="far fa-times"></i> Annuler
                                                    </button>
                                                    <button type="submit" name="modifier_commentaire" class="btn-submit-comment">
                                                        <i class="far fa-save"></i> Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="no-comments">
                                        <i class="far fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                                        <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <div style="text-align: center; padding: 60px 20px; color: #888;">
                            <i class="far fa-inbox" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
                            <h3>Aucun post pour le moment</h3>
                            <p>Soyez le premier à créer un post !</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="active-users">
                        <h4 class="title">Utilisateurs en ligne</h4>
                        <div class="users-list mt-3">
                            <img src="https://placehold.co/40x40/00C49F/ffffff?text=E" alt="User" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <img src="https://placehold.co/40x40/0088FE/ffffff?text=S" alt="User" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <img src="https://placehold.co/40x40/FFBB28/000000?text=H" alt="User" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <span class="text-muted">+12 autres</span>
                        </div>
                    </div>
                    
                    <div class="active-users">
                        <h4 class="title">Statistiques du Forum</h4>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2" style="color: #ddd;">
                                <span>Sujets totaux:</span>
                                <strong><?= count($sujets) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2" style="color: #ddd;">
                                <span>Membres:</span>
                                <strong>1,245</strong>
                            </div>
                            <div class="d-flex justify-content-between" style="color: #ddd;">
                                <span>En ligne:</span>
                                <strong>17</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="rts-footer-area bg-shape-footer pt--120 rt_bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copy-right-area ptb--50">
                        <div class="copy-right-text text-center">
                            <p class="rts-cp" style="color: #ddd;">Tous droits réservés <span>©2025 Zitouna Quests</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    
    <script>
        // Variables globales pour stocker les IDs
        let currentPostIdToDelete = null;
        let currentCommentIdToDelete = null;

        // ========== NOTIFICATION SYSTEM ==========
        
        function showNotification(type, title, message) {
            const container = document.getElementById('notificationContainer');
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="far ${icons[type]}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="closeNotification(this)">
                    <i class="far fa-times"></i>
                </button>
            `;
            
            container.appendChild(notification);
            
            // Auto-remove après 5 secondes
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'fadeOut 0.4s ease';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 400);
                }
            }, 5000);
        }
        
        function closeNotification(button) {
            const notification = button.closest('.notification');
            notification.style.animation = 'fadeOut 0.4s ease';
            setTimeout(() => {
                notification.remove();
            }, 400);
        }
        
        // Vérifier les paramètres URL pour afficher les notifications
        // Vérifier les paramètres URL pour afficher les notifications
function checkUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('success')) {
        const action = urlParams.get('success');
        
        switch(action) {
            case 'post_added':
                showNotification('success', 'Post créé !', 'Votre post a été publié avec succès.');
                break;
            case 'post_updated':
                showNotification('success', 'Post modifié !', 'Votre post a été mis à jour avec succès.');
                break;
            case 'post_deleted':
                showNotification('success', 'Post supprimé !', 'Le post a été supprimé avec succès.');
                break;
            case 'comment_added':
                showNotification('success', 'Commentaire ajouté !', 'Votre commentaire a été publié avec succès.');
                break;
            case 'comment_updated':
                showNotification('success', 'Commentaire modifié !', 'Votre commentaire a été mis à jour avec succès.');
                break;
            case 'comment_deleted':
                showNotification('success', 'Commentaire supprimé !', 'Le commentaire a été supprimé avec succès.');
                break;
        }
        
        // Nettoyer l'URL sans recharger la page
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    
    if (urlParams.has('error')) {
        const error = urlParams.get('error');
        
        // Gérer les erreurs spécifiques
        if (error === 'inappropriate_content') {
            showNotification('error', 'Contenu inapproprié', 'Votre commentaire contient du contenu inapproprié et ne peut pas être publié. Veuillez modifier votre message.');
            
            // Si un post_id est présent, ouvrir la section commentaires
            const postId = urlParams.get('post');
            if (postId) {
                setTimeout(() => {
                    const commentsSection = document.getElementById('comments-' + postId);
                    const addCommentForm = document.getElementById('add-comment-form-' + postId);
                    
                    if (commentsSection) {
                        commentsSection.classList.add('active');
                        commentsSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    if (addCommentForm) {
                        addCommentForm.classList.add('active');
                    }
                }, 500);
            }
        } else {
            showNotification('error', 'Erreur', error || 'Une erreur est survenue.');
        }
        
        // Nettoyer l'URL
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
}
        
        // Appeler au chargement de la page
        document.addEventListener('DOMContentLoaded', checkUrlParams);

        // ========== VALIDATION FUNCTIONS ==========
        
        // Validation du formulaire de post
        function validatePostForm(postId) {
            const input = document.getElementById('post-input-' + postId);
            const errorDiv = document.getElementById('post-error-' + postId);
            const errorText = document.getElementById('post-error-text-' + postId);
            
            // Réinitialiser les erreurs
            errorDiv.classList.remove('show');
            input.classList.remove('error');
            
            const value = input.value.trim();
            
            // Vérifier si vide
            if (value.length === 0) {
                showError(errorDiv, errorText, input, 'Le titre du post ne peut pas être vide');
                return false;
            }
            
            // Vérifier longueur minimale
            if (value.length < 5) {
                showError(errorDiv, errorText, input, 'Le titre doit contenir au moins 5 caractères');
                return false;
            }
            
            // Vérifier longueur maximale
            if (value.length > 200) {
                showError(errorDiv, errorText, input, 'Le titre ne peut pas dépasser 200 caractères');
                return false;
            }
            
            // Vérifier caractères spéciaux excessifs
            const specialCharsCount = (value.match(/[^a-zA-Z0-9\sàâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ'-]/g) || []).length;
            if (specialCharsCount > value.length * 0.3) {
                showError(errorDiv, errorText, input, 'Le titre contient trop de caractères spéciaux');
                return false;
            }
            
            return true;
        }
        
        // Validation en temps réel du post
        function validatePostInput(postId) {
            const input = document.getElementById('post-input-' + postId);
            const counter = document.getElementById('post-counter-' + postId);
            const charCount = document.getElementById('post-char-count-' + postId);
            
            const length = input.value.length;
            charCount.textContent = length;
            
            // Changer la couleur du compteur
            counter.classList.remove('warning', 'error');
            if (length > 180) {
                counter.classList.add('warning');
            }
            if (length >= 200) {
                counter.classList.add('error');
            }
        }
        
        // Validation du formulaire de commentaire
        function validateCommentForm(type, id) {
            const textarea = document.getElementById(type + '-comment-textarea-' + id);
            const errorDiv = document.getElementById(type + '-comment-error-' + id);
            const errorText = document.getElementById(type + '-comment-error-text-' + id);
            
            // Réinitialiser les erreurs
            errorDiv.classList.remove('show');
            textarea.classList.remove('error');
            
            const value = textarea.value.trim();
            
            // Vérifier si vide
            if (value.length === 0) {
                showError(errorDiv, errorText, textarea, 'Le commentaire ne peut pas être vide');
                return false;
            }
            
            // Vérifier longueur minimale
            if (value.length < 10) {
                showError(errorDiv, errorText, textarea, 'Le commentaire doit contenir au moins 10 caractères');
                return false;
            }
            
            // Vérifier longueur maximale
            if (value.length > 1000) {
                showError(errorDiv, errorText, textarea, 'Le commentaire ne peut pas dépasser 1000 caractères');
                return false;
            }
            
            // Vérifier si c'est seulement des espaces
            if (value.replace(/\s/g, '').length === 0) {
                showError(errorDiv, errorText, textarea, 'Le commentaire ne peut contenir que des espaces');
                return false;
            }
            
            // Vérifier spam (caractères répétés)
            const repeatedChars = value.match(/(.)\1{9,}/g);
            if (repeatedChars) {
                showError(errorDiv, errorText, textarea, 'Le commentaire contient trop de caractères répétés');
                return false;
            }
            
            return true;
        }
        
        // Validation en temps réel du commentaire
        function validateCommentInput(type, id) {
            const textarea = document.getElementById(type + '-comment-textarea-' + id);
            const counter = document.getElementById(type + '-comment-counter-' + id);
            const charCount = document.getElementById(type + '-comment-char-count-' + id);
            
            const length = textarea.value.length;
            charCount.textContent = length;
            
            // Changer la couleur du compteur
            counter.classList.remove('warning', 'error');
            if (length < 10) {
                counter.classList.add('error');
            } else if (length > 900) {
                counter.classList.add('warning');
            }
            if (length >= 1000) {
                counter.classList.add('error');
            }
        }
        
        // Fonction utilitaire pour afficher les erreurs
        function showError(errorDiv, errorText, inputElement, message) {
            errorText.textContent = message;
            errorDiv.classList.add('show');
            inputElement.classList.add('error');
            inputElement.focus();
            
            // Scroll vers l'erreur
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-hide après 5 secondes
            setTimeout(() => {
                errorDiv.classList.remove('show');
            }, 5000);
        }

        // ========== TOGGLE FUNCTIONS ==========

        // Toggle Comments Section
        function toggleComments(postId) {
            console.log('Toggle comments for post:', postId);
            const commentsContainer = document.getElementById('comments-' + postId);
            
            if(commentsContainer) {
                commentsContainer.classList.toggle('active');
                console.log('Toggled. Active:', commentsContainer.classList.contains('active'));
            } else {
                console.error('Comments container not found for post:', postId);
            }
        }

        // Toggle Add Comment Form
        function toggleAddCommentForm(postId) {
            const form = document.getElementById('add-comment-form-' + postId);
            if(form) {
                form.classList.toggle('active');
                
                // Si on ouvre le formulaire, reset les erreurs
                if(form.classList.contains('active')) {
                    const errorDiv = document.getElementById('add-comment-error-' + postId);
                    const textarea = document.getElementById('add-comment-textarea-' + postId);
                    if(errorDiv) errorDiv.classList.remove('show');
                    if(textarea) textarea.classList.remove('error');
                }
            }
        }
        
        // Toggle Edit Comment Form
        function toggleEditComment(commentId) {
            const viewDiv = document.getElementById('comment-view-' + commentId);
            const editDiv = document.getElementById('comment-edit-' + commentId);
            
            if(viewDiv && editDiv) {
                if(viewDiv.style.display === 'none') {
                    // Annuler l'édition
                    viewDiv.style.display = 'block';
                    editDiv.style.display = 'none';
                } else {
                    // Activer l'édition
                    viewDiv.style.display = 'none';
                    editDiv.style.display = 'block';
                    
                    // Reset les erreurs
                    const errorDiv = document.getElementById('edit-comment-error-' + commentId);
                    const textarea = document.getElementById('edit-comment-textarea-' + commentId);
                    if(errorDiv) errorDiv.classList.remove('show');
                    if(textarea) textarea.classList.remove('error');
                }
            }
        }
        
        // Toggle Edit Post Form
        function toggleEditPost(postId) {
            const titleView = document.getElementById('post-title-view-' + postId);
            const titleEdit = document.getElementById('post-title-edit-' + postId);
            const actions = document.getElementById('post-actions-' + postId);
            
            if(titleView && titleEdit && actions) {
                if(titleView.style.display === 'none') {
                    // Annuler l'édition
                    titleView.style.display = 'block';
                    titleEdit.style.display = 'none';
                    actions.style.display = 'flex';
                } else {
                    // Activer l'édition
                    titleView.style.display = 'none';
                    titleEdit.style.display = 'flex';
                    actions.style.display = 'none';
                    
                    // Reset les erreurs
                    const errorDiv = document.getElementById('post-error-' + postId);
                    const input = document.getElementById('post-input-' + postId);
                    if(errorDiv) errorDiv.classList.remove('show');
                    if(input) input.classList.remove('error');
                }
            }
        }

        // ========== DELETE MODAL FUNCTIONS ==========

        // Delete Post Modal
        function openDeletePostModal(postId) {
            console.log('Opening delete modal for post:', postId);
            currentPostIdToDelete = postId;
            document.getElementById('deletePostModal').classList.add('active');
        }

        function closeDeletePostModal() {
            document.getElementById('deletePostModal').classList.remove('active');
            currentPostIdToDelete = null;
        }

        function confirmDeletePost() {
            if(currentPostIdToDelete) {
                window.location.href = '../../../../Controller/supprimerSujetController.php?id=' + currentPostIdToDelete + '&position=front';
            }
        }

        // Delete Comment Modal
        function openDeleteCommentModal(commentId) {
            console.log('Opening delete modal for comment:', commentId);
            currentCommentIdToDelete = commentId;
            document.getElementById('deleteCommentModal').classList.add('active');
        }

        function closeDeleteCommentModal() {
            document.getElementById('deleteCommentModal').classList.remove('active');
            currentCommentIdToDelete = null;
        }

        function confirmDeleteComment() {
            if(currentCommentIdToDelete) {
                window.location.href = '../../../../Controller/supprimerCommentaireController.php?id=' + currentCommentIdToDelete + '&position=front';
            }
        }

        // ========== EVENT LISTENERS ==========

        // Close modals on outside click
        window.onclick = function(event) {
            const postModal = document.getElementById('deletePostModal');
            const commentModal = document.getElementById('deleteCommentModal');
            
            if (event.target == postModal) {
                closeDeletePostModal();
            }
            if (event.target == commentModal) {
                closeDeleteCommentModal();
            }
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeletePostModal();
                closeDeleteCommentModal();
            }
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const posts = document.querySelectorAll('.post-card');
                
                posts.forEach(post => {
                    const title = post.querySelector('.post-title').textContent.toLowerCase();
                    if (title.includes(searchTerm)) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                });
            });
        }
        
        // Image loading and error handling
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.post-image');
            const fallbackImages = [
                'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=400&fit=crop',
                'https://images.unsplash.com/photo-1466611653911-95081537e5b7?w=800&h=400&fit=crop',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=400&fit=crop',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=400&fit=crop',
                'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800&h=400&fit=crop',
            ];
            
            images.forEach(img => {
                // Pré-charger les images
                const imageUrl = img.src;
                
                // Gestion des erreurs de chargement
                img.addEventListener('error', function() {
                    console.warn('Image failed to load:', this.src);
                    
                    // Utiliser une image de secours différente
                    const randomIndex = Math.floor(Math.random() * fallbackImages.length);
                    this.src = fallbackImages[randomIndex];
                    this.classList.add('loaded');
                });
                
                // Marquer l'image comme chargée quand elle se charge
                if (img.complete && img.naturalHeight !== 0) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });
        });
    </script>
</body>
</html>