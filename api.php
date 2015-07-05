<?php
session_start();
require_once 'instagram.php';

$config = array(
    'client_id' => 'a8bca2257b204c4d865fe0337853caa3',
    'client_secret' => '10c22646b9b143f1bb11746fd9dbb6a5',
    'grant_type' => 'authorization_code',
    'redirect_uri' => 'http://digiworldcom.com/instagram/api.php',
);

$instagram = new Instagram($config);
$accessToken = $instagram->getAccessToken();
$_SESSION['InstagramAccessToken'] = $accessToken;
$instagram->setAccessToken($_SESSION['InstagramAccessToken']);
$userinfo = $instagram->getUser($_SESSION['InstagramAccessToken']);
$ures = json_decode($userinfo, true);
?>
<html>
    <head>
        <title>Instagram API</title>
    </head>

    <body>
        <h1 align="center"><?= $ures['data']['username'] ?>(<?= $ures['data']['full_name'] ?>)</h1>
        <div align="center"><img src="<?= $ures['data']['profile_picture'] ?>" /></div>
        <div align="center">User ID: <?= $ures['data']['id'] ?></div>
        <div align="center">Total Photos: <?= $ures['data']['counts']['media'] ?></div>
        <div align="center">Total Following: <?= $ures['data']['counts']['follows'] ?></div>
        <div align="center">Total Followers: <?= $ures['data']['counts']['followed_by'] ?></div>
    </body>
</html>
