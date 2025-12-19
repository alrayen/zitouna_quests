<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/Model/user.php';

$provider = new \League\OAuth2\Client\Provider\Github([
    'clientId'     => 'Ov23liLEUp3QFpFzV3q3', 
    'clientSecret' => '36b998034e8c420fffbc8b41f29670725c6003c4', 
    'redirectUri'  => 'http://localhost/Projet2/Controller/github_callback.php',
]);

if (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
}

try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $userGithub = $provider->getResourceOwner($token);
    $userGithubData = $userGithub->toArray();

    $email = $userGithub->getEmail(); 
    if (empty($email)) {
        $emails = $provider->get('user/emails', ['headers' => ['Authorization' => 'token ' . $token->getToken()]]);
        foreach ($emails as $e) {
            if ($e['primary'] && $e['verified']) {
                $email = $e['email'];
                break;
            }
        }
    }

    if (empty($email)) {
        die("Impossible de récupérer une adresse e-mail vérifiée depuis GitHub. Veuillez rendre votre e-mail public sur votre profil GitHub.");
    }

    $githubUsername = $userGithub->getNickname();
    $photo = $userGithubData['avatar_url'] ?? null;
    $nameParts = explode(' ', $userGithubData['name'] ?? $githubUsername);
    $prenom = $nameParts[0];
    $nom = $nameParts[1] ?? 'Utilisateur';

    $user_data = User::getUserByEmail($email);

    if ($user_data) {
        if (isset($user_data['etat']) && $user_data['etat'] == 1) {
            $_SESSION['error_login'] = 'Votre compte est banni.';
            header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
            exit;
        }
        $_SESSION['user_id'] = $user_data['id_user'];
        $_SESSION['user_email'] = $user_data['email'];
        $_SESSION['user_nom'] = $user_data['nom'];
        $_SESSION['user_prenom'] = $user_data['Prenom'];
        $_SESSION['user_role'] = $user_data['role'];
        $_SESSION['user_image'] = $user_data['photo'];
    } else {
        $random_password = bin2hex(random_bytes(16));
        $birthdate = date('Y-m-d');

        $newUser = new User($nom, $prenom, $birthdate, $email, $random_password);
        if ($photo) {
            $newUser->setPhoto($photo);
        }
        
        $newUserId = $newUser->register();

        if ($newUserId) {
            $user_data = User::getUserById($newUserId);
            $_SESSION['user_id'] = $user_data['id_user'];
            $_SESSION['user_email'] = $user_data['email'];
            $_SESSION['user_nom'] = $user_data['nom'];
            $_SESSION['user_prenom'] = $user_data['Prenom'];
            $_SESSION['user_role'] = $user_data['role'];
            $_SESSION['user_image'] = $user_data['photo'];
        } else {
            throw new Exception("Erreur lors de la création du compte via GitHub.");
        }
    }

    $base_path = '/Projet2';
    $redirect_url = ($_SESSION['user_role'] == 1) ? $base_path . "/View/BACK%20OFFICE/VIEW/build/pages/users_table.php" : $base_path . "/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.php";
    header('Location: ' . $redirect_url);
    exit;

} catch (Exception $e) {
    die('Une erreur est survenue : ' . $e->getMessage());
}