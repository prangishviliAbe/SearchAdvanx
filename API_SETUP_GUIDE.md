# SearchAdvanx API Key Setup & External Site Connection Guide

This guide explains how to generate API keys and connect external WordPress sites for cross-site searching with SearchAdvanx.

## 🔑 Understanding API Keys

API keys provide secure authentication between SearchAdvanx installations on different WordPress sites. They ensure that only authorized sites can perform searches on your content.

### How It Works

1. **Site A** (requesting site) wants to search **Site B** (target site)
2. **Site A** sends search request with API key in Authorization header
3. **Site B** validates the API key and returns search results
4. Both sites need SearchAdvanx plugin installed and configured

---

## 🎯 Quick Setup (Step-by-Step)

### Step 1: Generate API Key on Target Site (Site B)

**Option A: Manual Generation**

```php
// Generate a secure random API key (32 characters)
$api_key = bin2hex(random_bytes(16));
// Example: a1b2c3d4e5f6789012345678901234ab
```

**Option B: Use Online Generator**

- Visit: https://randomkeygen.com/
- Copy a "CodeIgniter Encryption Key" (32 chars)
- Or use WordPress secret key generator: https://api.wordpress.org/secret-key/1.1/salt/

**Option C: Use SearchAdvanx Generator (see below)**

### Step 2: Configure Target Site (Site B)

1. Go to **SearchAdvanx > Settings**
2. In the **Main Settings** section:
   - **API Key**: Enter your generated API key
   - Save settings

### Step 3: Configure Requesting Site (Site A)

1. Go to **SearchAdvanx > External Sites**
2. Add a new site configuration:
   - **Site Name**: "My Other Site"
   - **Site URL**: "https://siteb.com"
   - **API Key**: Same key you set on Site B
   - Save settings

### Step 4: Test Connection

1. Go to **SearchAdvanx > Settings**
2. Use the API test feature (or add one)
3. Or test via REST API endpoint

---

## 🛠️ Enhanced API Key Management

---

## 🔧 Detailed Configuration Examples

### Example 1: Company with Multiple Sites

**Scenario:** Main company site wants to search product documentation and support sites.

**Sites:**

- Main Site: `https://company.com` (Site A)
- Docs Site: `https://docs.company.com` (Site B)
- Support Site: `https://support.company.com` (Site C)

**Setup:**

1. **On Docs Site (Site B):**

   ```
   SearchAdvanx > Settings > API Key: abc123def456
   ```

2. **On Support Site (Site C):**

   ```
   SearchAdvanx > Settings > API Key: xyz789uvw012
   ```

3. **On Main Site (Site A):**

   ```
   SearchAdvanx > External Sites:

   Site 1:
   - Name: Documentation
   - URL: https://docs.company.com
   - API Key: abc123def456

   Site 2:
   - Name: Support Center
   - URL: https://support.company.com
   - API Key: xyz789uvw012
   ```

### Example 2: Network of Related Sites

**Scenario:** Multiple sites want to search each other's content.

**Sites:**

- Blog A: `https://blog-a.com`
- Blog B: `https://blog-b.com`
- Blog C: `https://blog-c.com`

**Setup (each site needs the others configured):**

**On Blog A:**

```
Own API Key: key-blog-a-123
External Sites:
- Blog B (URL: https://blog-b.com, API Key: key-blog-b-456)
- Blog C (URL: https://blog-c.com, API Key: key-blog-c-789)
```

**On Blog B:**

```
Own API Key: key-blog-b-456
External Sites:
- Blog A (URL: https://blog-a.com, API Key: key-blog-a-123)
- Blog C (URL: https://blog-c.com, API Key: key-blog-c-789)
```

**On Blog C:**

```
Own API Key: key-blog-c-789
External Sites:
- Blog A (URL: https://blog-a.com, API Key: key-blog-a-123)
- Blog B (URL: https://blog-b.com, API Key: key-blog-b-456)
```

---

## 🛠️ API Key Generator (Built-in)

SearchAdvanx now includes a built-in API key generator in the admin interface:

### Using the Generator

1. Go to **SearchAdvanx > Settings**
2. In the **API Key** field, click **"Generate New Key"**
3. A secure 32-character key is automatically created
4. Click **"👁️ Show"** to view the key
5. Click **"📋 Copy"** to copy to clipboard
6. **Save Settings** to activate the key

### Features

- **Secure Generation**: Uses cryptographically secure random generation
- **32-Character Keys**: Optimal length for security and usability
- **Copy to Clipboard**: Easy sharing with other sites
- **Show/Hide Toggle**: Secure viewing of keys
- **Usage Instructions**: Built-in help text

---

## 🔌 API Endpoints

### Local Search Endpoint

```
GET /wp-json/searchadvanx/v1/search
```

**Parameters:**

- `query` (required): Search term
- `post_types`: Array of post types to search
- `meta_query`: Custom field queries

**Headers (for protected endpoints):**

```
Authorization: Bearer YOUR_API_KEY
```

### External Search Endpoint

```
POST /wp-json/searchadvanx/v1/external-search
```

**Parameters:**

- `query` (required): Search term
- `sites`: Array of site configurations
- `limit`: Number of results per site

**Example Request:**

```javascript
fetch("/wp-json/searchadvanx/v1/external-search", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify({
    query: "wordpress tutorials",
    sites: [
      {
        url: "https://docs.example.com",
        name: "Documentation",
        api_key: "abc123def456",
      },
    ],
    limit: 5,
  }),
});
```

---

## 🧪 Testing Connections

### Method 1: WordPress Admin Test

1. Go to **SearchAdvanx > Settings**
2. Scroll to **API Key** section
3. Your site's endpoint: `https://yoursite.com/wp-json/searchadvanx/v1/search`
4. Test with external sites using their API keys

### Method 2: cURL Testing

**Test external site connection:**

```bash
curl -X GET \
  "https://target-site.com/wp-json/searchadvanx/v1/search?query=test" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

**Expected Response:**

```json
{
    "success": true,
    "data": {
        "results": [...],
        "total": 10,
        "query": "test"
    }
}
```

### Method 3: Browser Testing

**Public endpoint (no API key required):**

```
https://yoursite.com/wp-json/searchadvanx/v1/search?query=test
```

**Protected endpoint (API key required):**
Add `Authorization: Bearer YOUR_API_KEY` header

---

## 🔒 Security Best Practices

### API Key Management

- **Use Unique Keys**: Different key for each external site connection
- **Regular Rotation**: Change keys periodically (quarterly recommended)
- **Secure Storage**: Never expose keys in frontend code or public repositories
- **Access Logging**: Monitor API usage in SearchAdvanx > Analytics

### Site Protection

- **Enable Authentication**: Always set an API key for production sites
- **Rate Limiting**: Monitor for unusual search activity
- **HTTPS Only**: Use SSL certificates for all API communications
- **WordPress Security**: Keep WordPress and plugins updated

### Network Security

- **Firewall Rules**: Restrict API access to known IP ranges if possible
- **VPN Access**: Use VPN for sensitive internal site connections
- **Regular Audits**: Review external site configurations monthly

---

## 🚨 Troubleshooting

### Common Issues

**"Unauthorized" Error (401):**

- Check API key is correctly set on target site
- Verify API key in external site configuration
- Ensure key has no extra spaces or characters

**"Site Not Found" Error:**

- Verify target site URL is correct and accessible
- Check SearchAdvanx plugin is installed and activated on target site
- Test site accessibility: `https://target-site.com/wp-json/`

**"No Results" Response:**

- Target site may have no matching content
- Check post types are configured for search
- Verify search query syntax

**Connection Timeout:**

- Increase API timeout in SearchAdvanx settings
- Check target site server performance
- Verify network connectivity between sites

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check `/wp-content/debug.log` for SearchAdvanx API errors.

---

## 📊 Usage Examples

### PHP Example

```php
// Get SearchAdvanx API instance
$api = new SearchAdvanx_API();

// Search external sites
$results = $api->search_external_sites([
    'query' => 'wordpress tutorial',
    'sites' => [
        [
            'url' => 'https://docs.example.com',
            'api_key' => 'abc123def456'
        ]
    ]
]);
```

### JavaScript Example

```javascript
// External search via AJAX
jQuery
  .post("/wp-json/searchadvanx/v1/external-search", {
    query: "wordpress",
    sites: [
      {
        url: "https://example.com",
        name: "Example Site",
        api_key: "your-api-key",
      },
    ],
  })
  .done(function (response) {
    console.log("Search results:", response.data);
  });
```

### Shortcode Example

```html
<!-- Basic search form -->
[searchadvanx]

<!-- Search with external sites -->
[searchadvanx include_external="true"]
```

---

## 📚 Advanced Configuration

### Custom Endpoints

You can customize API endpoints for each external site:

```
Default: wp-json/searchadvanx/v1/search
Custom: api/v2/custom-search
```

### Multiple API Keys

For high-security setups, use different API keys for different purposes:

- **Read-Only Key**: Basic search access
- **Full-Access Key**: Search + analytics access
- **Admin Key**: Full plugin configuration access

### Load Balancing

For high-traffic scenarios, configure multiple endpoints for the same site:

```php
'sites' => [
    [
        'url' => 'https://site1.example.com',
        'api_key' => 'key1'
    ],
    [
        'url' => 'https://site2.example.com',  // Load balanced
        'api_key' => 'key1'                    // Same content, same key
    ]
]
```

---

## ✅ Quick Checklist

### Before Going Live

- [ ] API keys generated and secured
- [ ] External sites configured and tested
- [ ] HTTPS enabled on all sites
- [ ] WordPress and plugins updated
- [ ] Search functionality tested
- [ ] Performance monitoring enabled
- [ ] Backup system in place
- [ ] Documentation updated for team

### Regular Maintenance

- [ ] Review API usage monthly
- [ ] Rotate API keys quarterly
- [ ] Update external site configurations
- [ ] Monitor search performance
- [ ] Check for plugin updates
- [ ] Audit external site access

---

## 🆘 Support

Need help with API setup or external site connections?

- **Documentation**: Check SearchAdvanx > API Docs in WordPress admin
- **GitHub Issues**: https://github.com/prangishviliAbe/SearchAdvanx/issues
- **Plugin Forums**: WordPress.org plugin support forums
- **Debug Guide**: Enable WP_DEBUG and check error logs

---

This guide covers everything you need to set up secure, reliable cross-site search with SearchAdvanx. The built-in admin tools make it easy to generate keys and configure external sites without technical complexity.
