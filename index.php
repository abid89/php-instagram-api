<?php

require_once 'instagram.php';

$config = array(
    'client_id' => 'a8bca2257b204c4d865fe0337853caa3',
    'client_secret' => '10c22646b9b143f1bb11746fd9dbb6a5',
    'grant_type' => 'authorization_code',
    'redirect_uri' => 'http://digiworldcom.com/instagram/api.php',
);

session_start();
if (isset($_SESSION['InstagramAccessToken']) && !empty($_SESSION['InstagramAccessToken'])) {
    header('Location: api.php');
    die();
}

$instagram = new Instagram($config);
$instagram->openAuthorizationUrl();
