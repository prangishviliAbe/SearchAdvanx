# SearchAdvanx

Advanced WordPress search engine plugin with Elementor and JetEngine support, including REST API functionality for cross-site searching.

![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/License-GPL%20v2%2B-blue)
![Version](https://img.shields.io/badge/Version-1.0.0-green)

## 🚀 Features

### Core Search Functionality

- **Advanced Search Algorithm**: Enhanced search with custom field support
- **Real-time AJAX Search**: Instant search results without page reload
- **Search Analytics**: Track search queries, popular terms, and user behavior
- **Shortcode Support**: Easy integration with `[searchadvanx]` shortcode
- **Custom Post Type Support**: Search across any post type

### 🎨 Visual Page Builders

- **Elementor Integration**: Drag-and-drop search widget with style controls
- **JetEngine Support**: Custom query integration for dynamic listings
- **Filter Loop Posts**: Advanced filtering capabilities for JetEngine
- **Live Preview**: Real-time preview in page builders

### 🌐 REST API & Cross-Site Search

- **REST API Endpoints**: Full API for programmatic access
- **External Site Search**: Search across multiple WordPress sites
- **API Management Interface**: User-friendly external sites configuration
- **Connection Testing**: Verify external site connectivity
- **Response Caching**: Optimize performance with intelligent caching
- **API Authentication**: Secure API access with key-based authentication

### 📊 Admin Dashboard

- **Settings Management**: Comprehensive admin interface
- **Search Analytics**: Visual reports and statistics
- **API Documentation**: Built-in documentation and examples
- **Site Management**: Easy external site configuration

## 📦 Installation

### Method 1: Download and Install

1. Download the plugin files
2. Upload to `/wp-content/plugins/searchadvanx/`
3. Activate through WordPress admin
4. Configure settings under **Settings > SearchAdvanx**

### Method 2: WordPress Admin

1. Go to **Plugins > Add New**
2. Upload the plugin zip file
3. Activate the plugin
4. Configure settings

## ⚙️ Configuration

### Basic Setup

1. Navigate to **Settings > SearchAdvanx**
2. Configure searchable post types
3. Set up API key for secure access
4. Save settings

### External Sites Setup

1. Go to **REST API Management** tab
2. Click **Add New Site**
3. Configure site details:
   - Site Name: "My Other WordPress Site"
   - Site URL: "https://example.com"
   - API Key: (optional) for secure access
   - Custom Endpoint: API endpoint path
   - Default Post Type: What to search by default
   - Custom Parameters: Additional API parameters in JSON
4. Test connection and save

## 📖 Usage Examples

### Shortcode Usage

```php
// Basic search form
[searchadvanx]

// Advanced search with filters
[searchadvanx post_type="post" placeholder="Search posts..." show_filters="true" results_per_page="10"]

// Product search for WooCommerce
[searchadvanx post_type="product" button_text="Find Products" results_per_page="12"]
```

### Shortcode Parameters

- `post_type` - Post type to search (default: post)
- `placeholder` - Input placeholder text (default: Search...)
- `button_text` - Search button text (default: Search)
- `show_filters` - Show filter options (default: false)
- `results_per_page` - Results per page (default: 10)
- `template` - Result template (default: default)

### REST API Usage

#### Basic Search

```javascript
// GET request
fetch(
  "/wp-json/searchadvanx/v1/search?query=wordpress&post_type=post&posts_per_page=5"
)
  .then((response) => response.json())
  .then((data) => console.log(data.results));
```

#### External Site Search

```javascript
// POST request for cross-site search
fetch("/wp-json/searchadvanx/v1/external-search", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    query: "wordpress tutorial",
    filters: {
      post_type: "post",
      posts_per_page: 10,
    },
  }),
});
```

#### PHP Example

```php
// Basic search
$response = wp_remote_get(home_url('/wp-json/searchadvanx/v1/search?query=wordpress&post_type=post'));
$data = json_decode(wp_remote_retrieve_body($response), true);

// External search using configured sites
$body = json_encode([
    'query' => 'wordpress',
    'filters' => ['post_type' => 'post']
]);

$response = wp_remote_post(home_url('/wp-json/searchadvanx/v1/external-search'), [
    'headers' => ['Content-Type' => 'application/json'],
    'body' => $body
]);
```

### Elementor Integration

1. Edit page with Elementor
2. Search for "SearchAdvanx" widget
3. Drag and drop to desired location
4. Configure settings in widget panel
5. Style with visual controls

### JetEngine Integration

1. Create new JetEngine listing
2. Set query type to "SearchAdvanx Query"
3. Configure search parameters
4. Add meta field filtering as needed

## 🔧 API Reference

### Search Endpoint

**URL:** `/wp-json/searchadvanx/v1/search`  
**Method:** GET

#### Parameters

- `query` (required) - Search query string
- `post_type` (optional) - Post type to search (default: post)
- `posts_per_page` (optional) - Results per page (default: 10)
- `meta_query` (optional) - Custom meta query array
- `site_url` (optional) - External site URL to search

#### Response Format

```json
{
  "success": true,
  "results": [
    {
      "id": 123,
      "title": "WordPress Tutorial",
      "content": "Learn WordPress development...",
      "url": "https://example.com/wordpress-tutorial",
      "date": "2025-10-04T12:00:00",
      "author": "John Doe",
      "featured_image": "https://example.com/image.jpg",
      "post_type": "post",
      "meta": {
        "custom_field": "value"
      }
    }
  ],
  "total": 1,
  "query": "wordpress"
}
```

### External Search Endpoint

**URL:** `/wp-json/searchadvanx/v1/external-search`  
**Method:** POST

#### Request Body

```json
{
  "sites": [
    {
      "url": "https://site1.com",
      "name": "Site 1",
      "api_key": "your-api-key"
    }
  ],
  "query": "wordpress",
  "filters": {
    "post_type": "post",
    "posts_per_page": 10
  }
}
```

## 🎯 Advanced Features

### Custom Parameters for External Sites

Configure additional API parameters in JSON format:

```json
{
  "meta_key": "featured_post",
  "meta_value": "yes",
  "orderby": "date",
  "order": "DESC"
}
```

### Search Analytics

Track and analyze search behavior:

- Total searches performed
- Most popular search terms
- Recent search activity
- Results found per query
- User IP tracking

### Caching System

- Configurable cache duration (0-1440 minutes)
- Automatic cache invalidation
- Performance optimization for external searches
- Transient-based caching

## 🏗️ File Structure

```
searchadvanx/
├── searchadvanx.php              # Main plugin file
├── includes/
│   ├── class-searchadvanx.php    # Core plugin class
│   ├── class-api.php             # REST API functionality
│   ├── class-database.php        # Database operations
│   ├── admin/
│   │   ├── class-admin.php       # Admin interface
│   │   ├── sites-manager.php     # External sites manager
│   │   ├── analytics.php         # Analytics display
│   │   └── api-docs.php          # API documentation
│   └── integrations/
│       └── class-elementor-widget.php  # Elementor widget
├── assets/
│   ├── css/
│   │   ├── searchadvanx.css      # Frontend styles
│   │   └── admin.css             # Admin styles
│   └── js/
│       ├── searchadvanx.js       # Frontend JavaScript
│       └── admin.js              # Admin JavaScript
├── templates/
│   └── search-form.php           # Search form template
├── languages/                    # Translation files
└── README.md                     # This file
```

## 🔒 Security Features

- **Nonce Verification**: All AJAX requests protected
- **Input Sanitization**: All user inputs sanitized
- **Permission Checks**: Admin functions require proper capabilities
- **API Key Authentication**: Optional secure API access
- **SQL Injection Prevention**: Prepared statements used

## 🌐 Internationalization

The plugin is translation-ready with:

- Text domain: `searchadvanx`
- Translation files in `/languages/` directory
- All strings properly escaped and localized

## 🚀 Performance Optimization

- **Efficient Database Queries**: Optimized search queries
- **Response Caching**: Configurable caching system
- **Lazy Loading**: Results loaded on demand
- **Minimal HTTP Requests**: Optimized external API calls
- **Database Indexing**: Proper database indexes for search logs

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Changelog

### Version 1.0.0

- Initial release
- Core search functionality
- REST API endpoints
- Elementor integration
- JetEngine support
- External site search
- Admin interface
- Search analytics

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Abe Prangishvili**

- GitHub: [@abeprangishvili](https://github.com/abeprangishvili)
- Website: [abeprangishvili.com](https://abeprangishvili.com)

## 💬 Support

For support and questions:

- Create an issue on GitHub
- Contact the author
- Check the plugin documentation in WordPress admin

## 🙏 Acknowledgments

- WordPress community for inspiration
- Elementor team for excellent documentation
- JetEngine for powerful dynamic content capabilities
- All contributors and users

---

**Made with ❤️ for the WordPress community**
