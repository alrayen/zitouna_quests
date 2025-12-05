<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php';

$provider = new \League\OAuth2\Client\Provider\Github([
    'clientId'     => 'Ov23liLEUp3QFpFzV3q3', 
    'clientSecret' => '36b998034e8c420fffbc8b41f29670725c6003c4',
    'redirectUri'  => 'http://localhost/Projet2/Controller/github_callback.php',
]);

$options = [
    'scope' => ['user:email'] 
];

$authUrl = $provider->getAuthorizationUrl($options);
$_SESSION['oauth2state'] = $provider->getState();

header('Location: ' . $authUrl);
exit;