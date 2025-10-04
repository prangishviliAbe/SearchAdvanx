# Changelog

All notable changes to SearchAdvanx will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.3] - 2025-10-04

### Fixed

- **Width Expansion for Elementor Containers** - Removed 800px max-width constraint from `.searchadvanx-container`
- **Full-Width Responsive Design** - Enhanced all search components to expand to full container width
- **Grid Layout Flexibility** - Improved grid cards and modern grid styles for wide containers
- **CSS Standards Compliance** - Added standard `line-clamp` properties alongside `-webkit-line-clamp` for better browser compatibility
- **Search Form Expansion** - Updated input groups and form elements for complete width utilization

### Changed

- Container margin from `20px auto` to `20px 0` for better Elementor integration
- Added comprehensive full-width styles for all display modes
- Enhanced responsive breakpoints for optimal wide container performance

## [1.2.0] - 2025-10-04

### Added

- **Enhanced API Key Management System** - Built-in secure API key generator with 32-character cryptographically secure keys
- **Professional Admin Interface** - Show/hide toggle, one-click copy to clipboard, and visual usage instructions
- **Comprehensive Setup Documentation** - Complete API_SETUP_GUIDE.md with real-world examples and security best practices
- **Improved External Sites Configuration** - Step-by-step connection instructions and visual help sections
- **Advanced Connection Examples** - Corporate networks, blog networks, and multi-site scenarios
- **Security Best Practices** - Key rotation, HTTPS enforcement, and access control guidelines
- **Troubleshooting Guide** - Common issues, debug mode, and maintenance checklist

### Improved

- **Sites Manager Interface** - Enhanced with clear setup instructions and professional guidance
- **API Key Field** - Interactive generator with secure viewing and clipboard functionality
- **User Experience** - No technical knowledge required for cross-site search setup
- **Documentation** - Code examples in PHP, JavaScript, cURL, and WordPress integration

## [1.1.0] - 2025-10-04

### Added

- Automatic plugin updates from GitHub using Plugin Update Checker library
- Comprehensive updates settings section in admin panel
- Configurable update branches (main for stable, develop for beta)
- Manual update check functionality with immediate feedback
- GitHub API integration for seamless automatic updates
- WordPress-native update notifications and installation process
- Version status display with available update information
- Direct links to GitHub repository and releases
- Complete documentation for update system setup and usage

### Enhanced

- Admin interface with new "Plugin Updates" section
- Settings preservation during automatic updates
- Professional update management workflow
- Integration with WordPress plugin update system

## [1.0.1] - 2025-10-04

### Fixed

- Critical error on External Sites admin page
- Class loading and autoloader issues
- Plugin initialization error handling

### Added

- Professional cover image for repository branding
- Complete admin sidebar menu with dedicated sections
- Modern dashboard with statistics and quick actions
- Enhanced admin interface with responsive design
- Improved error handling and debugging capabilities

### Changed

- Moved plugin from Settings submenu to main admin sidebar menu
- Upgraded admin interface with modern cards and navigation
- Enhanced class loading with better error detection

## [1.0.0] - 2025-10-04

### Added

- Initial release of SearchAdvanx
- Core search functionality with custom field support
- REST API endpoints for local and external search
- Elementor widget integration with visual controls
- JetEngine query type integration
- Admin interface with tabbed settings
- External sites management system
- Connection testing for external sites
- Search analytics and logging
- Comprehensive API documentation
- Shortcode support with multiple parameters
- Response caching system for performance
- Multi-site search capabilities
- Real-time AJAX search functionality
- Custom post type search support
- Search result templates
- Admin and frontend CSS/JS assets
- Translation ready (i18n)
- Security features (nonces, sanitization, permissions)

### Features

- **Search Engine**: Advanced WordPress search with meta field support
- **REST API**: Full REST API with external site search capabilities
- **Integrations**: Native Elementor and JetEngine support
- **Analytics**: Built-in search analytics and reporting
- **Multi-site**: Cross-site search functionality
- **Performance**: Caching system and optimized queries
- **Security**: Secure API with key authentication
- **Admin UI**: User-friendly administration interface

### Technical Details

- Minimum WordPress version: 5.0
- Minimum PHP version: 7.4
- Database table for search logs
- Autoloader for class files
- Modular architecture with separate classes
- Template system for customization
- Translation support
- Responsive design
