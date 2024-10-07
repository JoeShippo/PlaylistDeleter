
# Spotify Playlist Deleter

A PHP web app that allows users to authenticate with their Spotify account and delete their playlists through a user-friendly interface.

## Features

- Authenticate with your Spotify account.
- View your playlists with details (name, track count, and followers).
- Select and delete multiple playlists at once.
- Simple and clean UI.

## Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/JoeShippo/PlaylistDeleter.git
   cd PlaylistDeleter
   ```

2. **Configure Your Spotify API Credentials**
   - Copy `config.example.php` to `config.php`.
   - Replace `your-client-id-here`, `your-client-secret-here`, and `http://localhost:3000/callback.php` with your actual Spotify credentials and redirect URI.
   
3. **Install Dependencies**
   - Ensure you have a working PHP environment (e.g., MAMP, XAMPP).
   - Place the files in your server's document root.

4. **Run the Application**
   - Start your local server.
   - Open the app in your browser (e.g., `http://localhost:3000/index.php`).

5. **Authenticate with Spotify**
   - Click "Login with Spotify" to authorize the app.
   - After authentication, you can view and delete your Spotify playlists.

## Security Notes

- Make sure to add `config.php` to `.gitignore` to prevent accidental exposure of sensitive credentials.
- Use HTTPS for secure communication between your app and Spotify's API.

## License

This project is open-source and available under the MIT License.
