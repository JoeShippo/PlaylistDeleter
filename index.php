<?php
session_start();
include 'config.php';

// Spotify Authorization URL
$authUrl = SPOTIFY_AUTH_URL;

if (!isset($_SESSION['access_token'])) {
    // Step 1: Redirect user to Spotify Authorization URL
    $scopes = 'playlist-modify-private playlist-modify-public';
    $authorizeUrl = $authUrl . '?response_type=code&client_id=' . CLIENT_ID . '&redirect_uri=' . urlencode(REDIRECT_URI) . '&scope=' . urlencode($scopes);
    
    // Output HTML with Chota styles
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spotify Playlist Deleter</title>
        <link rel="stylesheet" href="https://unpkg.com/chota@latest">
    </head>
    <body>
        <div class="container">
            <div class="hero center">
                <h1>Spotify Playlist Deleter</h1>
                <p>Authenticate with Spotify to manage your playlists.</p>
                <a class="button primary" href="' . $authorizeUrl . '">Login with Spotify</a>
            </div>
        </div>
    </body>
    </html>';
    exit();
}

// If the user is already authenticated, display playlists
header('Location: delete_playlists.php');
exit();
