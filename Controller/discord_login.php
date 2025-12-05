<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php';

$provider = new \TeamNifty\OAuth2\Client\Provider\Discord([
    'clientId'     => 'VOTRE_ID_CLIENT_DISCORD', 
    'clientSecret' => 'VOTRE_SECRET_CLIENT_DISCORD',
    'redirectUri'  => 'http://localhost/Projet2/Controller/discord_callback.php'
]);

$options = [
    'scope' => ['identify', 'email'] 
];

$authUrl = $provider->getAuthorizationUrl($options);
$_SESSION['oauth2state'] = $provider->getState();

header('Location: ' . $authUrl);
exit;
?>