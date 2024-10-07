<?php
session_start();
include 'config.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange authorization code for access token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, SPOTIFY_TOKEN_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => REDIRECT_URI,
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);

    if (isset($json['access_token'])) {
        $_SESSION['access_token'] = $json['access_token'];
        header('Location: delete_playlists.php');
        exit();
    } else {
        echo "Failed to obtain access token.";
    }
} else {
    echo "Authorization code not found.";
}
