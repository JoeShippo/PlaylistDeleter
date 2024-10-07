<?php
session_start();
include 'config.php';

// Ensure the user is authenticated
if (!isset($_SESSION['access_token'])) {
    die('User is not authenticated.');
}

// Fetch user playlists
$accessToken = $_SESSION['access_token'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/playlists');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if the token has expired or is invalid
if ($httpCode == 401) {
    // Redirect to index.php to re-authenticate
    header('Location: index.php');
    exit();
}

$playlists = json_decode($response, true);

// Output the HTML with Chota styles
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Spotify Playlists</title>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
    <link rel="stylesheet" href="styles.css">
    <script>
        // Select All/Deselect All functionality
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll("input[type=\'checkbox\'][name=\'playlists[]\']");
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        // Refresh the page after successful deletion
        function refreshPage() {
            setTimeout(function() {
                window.location.reload();
            }, 1000); // 1 second delay before refreshing
        }
    </script>
</head>
<body id="delete">

    <div class="container">';

if (isset($playlists['items'])) {
    echo '
    <form method="POST" action="delete_playlists.php" onsubmit="refreshPage()">
        <table class="striped">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAll(this)"> Select All</th>
                    <th>Playlist Name</th>
                    <th># of Tracks</th>
                    <th># of Followers</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($playlists['items'] as $playlist) {
        $playlistLink = $playlist['external_urls']['spotify'];
        $numTracks = $playlist['tracks']['total'];
        $numFollowers = isset($playlist['followers']['total']) ? $playlist['followers']['total'] : 0; // Check if followers exist

        echo '
        <tr>
            <td><input type="checkbox" name="playlists[]" value="' . $playlist['id'] . '"></td>
            <td><a href="' . $playlistLink . '" target="_blank">' . htmlspecialchars($playlist['name']) . '</a></td>
            <td>' . $numTracks . '</td>
            <td>' . $numFollowers . '</td>
        </tr>';
    }

    echo '
            </tbody>
        </table>
        <div class="row center">
            <input class="button primary" type="submit" value="Delete Selected Playlists">
        </div>
    </form>';
} else {
    echo '<p>No playlists found.</p>';
}

// Handle the playlist deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlists'])) {
    foreach ($_POST['playlists'] as $playlistId) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/playlists/' . $playlistId . '/followers');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
    echo '
        <div class="alert success">
            <p>Selected playlists have been deleted.</p>
        </div>';
}

echo '
        </div>

    <!-- Logo at bottom right -->
    <div class="logo-container">
        <img src="https://holidayify.aight.fun/aight.svg" alt="Logo" />
    </div>

</body>
</html>';
