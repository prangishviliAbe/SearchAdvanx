# SearchAdvanx Automatic Updates

This plugin uses the Plugin Update Checker library to provide automatic updates directly from GitHub.

## How It Works

- The plugin automatically checks for new releases on GitHub
- Users can enable/disable automatic updates in the admin panel
- Updates are downloaded directly from GitHub releases
- No need to manually upload plugin files

## Admin Configuration

Go to **SearchAdvanx > Settings** and scroll to the **Plugin Updates** section:

### Settings Available:
- **Automatic Updates**: Enable/disable automatic update checks
- **Update Branch**: Choose between `main` (stable) or `develop` (beta) releases
- **Current Version**: View current version and check for updates manually

## Manual Update Check

You can manually check for updates by:
1. Going to SearchAdvanx admin settings
2. Click "Check for Updates" in the Plugin Updates section
3. Or use the WordPress Plugins page and click "Check for updates"

## Update Process

1. Plugin checks GitHub for new releases
2. If a new version is available, WordPress shows an update notification
3. Click "Update" to automatically download and install from GitHub
4. Plugin updates seamlessly without losing settings

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Internet connection for GitHub API access

## GitHub Integration

- Repository: https://github.com/prangishviliAbe/SearchAdvanx
- Releases: https://github.com/prangishviliAbe/SearchAdvanx/releases
- Update checks use GitHub's public API (no authentication required)

## Troubleshooting

If updates aren't working:
1. Check your internet connection
2. Verify the GitHub repository is accessible
3. Check WordPress error logs for any issues
4. Try a manual update check from the admin panel

## Privacy

The plugin only connects to:
- GitHub API for checking releases
- Your own configured external WordPress sites (if any)

No personal data is transmitted during update checks.