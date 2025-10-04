<?php
/**
 * API Documentation Template
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h2>API Documentation</h2>

<h3>Search Endpoint</h3>
<p><strong>URL:</strong> <code><?php echo rest_url('searchadvanx/v1/search'); ?></code></p>
<p><strong>Method:</strong> GET</p>
<p><strong>Parameters:</strong></p>
<ul>
    <li><code>query</code> (required) - Search query string</li>
    <li><code>post_type</code> (optional) - Post type to search (default: post)</li>
    <li><code>posts_per_page</code> (optional) - Number of results per page (default: 10)</li>
    <li><code>meta_query</code> (optional) - Custom meta query array</li>
    <li><code>site_url</code> (optional) - External site URL to search</li>
</ul>

<h4>Example Request:</h4>
<pre><code>GET <?php echo rest_url('searchadvanx/v1/search'); ?>?query=wordpress&post_type=post&posts_per_page=5</code></pre>

<h4>Example Response:</h4>
<pre><code>{
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
}</code></pre>

<h3>External Search Endpoint</h3>
<p><strong>URL:</strong> <code><?php echo rest_url('searchadvanx/v1/external-search'); ?></code></p>
<p><strong>Method:</strong> POST</p>
<p><strong>Parameters:</strong></p>
<ul>
    <li><code>sites</code> (optional) - Array of site objects with url, name, and api_key (uses configured sites if empty)</li>
    <li><code>query</code> (required) - Search query string</li>
    <li><code>filters</code> (optional) - Additional filters</li>
</ul>

<h4>Example Request Body:</h4>
<pre><code>{
  "sites": [
    {
      "url": "https://site1.com",
      "name": "Site 1",
      "api_key": "your-api-key"
    },
    {
      "url": "https://site2.com",
      "name": "Site 2"
    }
  ],
  "query": "wordpress",
  "filters": {
    "post_type": "post",
    "posts_per_page": 10
  }
}</code></pre>

<h3>Usage Examples</h3>

<h4>JavaScript/jQuery:</h4>
<pre><code>// Basic search
$.get('<?php echo rest_url('searchadvanx/v1/search'); ?>', {
    query: 'wordpress',
    post_type: 'post'
}, function(data) {
    console.log(data.results);
});

// External search using configured sites
$.ajax({
    url: '<?php echo rest_url('searchadvanx/v1/external-search'); ?>',
    method: 'POST',
    data: JSON.stringify({
        query: 'wordpress'
    }),
    contentType: 'application/json',
    success: function(data) {
        console.log(data.results);
    }
});</code></pre>

<h4>PHP:</h4>
<pre><code>// Basic search
$response = wp_remote_get('<?php echo rest_url('searchadvanx/v1/search'); ?>?query=wordpress&post_type=post');
$data = json_decode(wp_remote_retrieve_body($response), true);

// External search using configured sites
$body = json_encode([
    'query' => 'wordpress'
]);

$response = wp_remote_post('<?php echo rest_url('searchadvanx/v1/external-search'); ?>', [
    'headers' => ['Content-Type' => 'application/json'],
    'body' => $body
]);
$data = json_decode(wp_remote_retrieve_body($response), true);</code></pre>

<h3>Shortcode Usage</h3>
<p>Use the <code>[searchadvanx]</code> shortcode to embed search functionality:</p>
<pre><code>[searchadvanx post_type="post" placeholder="Search posts..." button_text="Search" show_filters="true" results_per_page="10"]</code></pre>

<h4>Shortcode Parameters:</h4>
<ul>
    <li><code>post_type</code> - Post type to search (default: post)</li>
    <li><code>placeholder</code> - Input placeholder text (default: Search...)</li>
    <li><code>button_text</code> - Search button text (default: Search)</li>
    <li><code>show_filters</code> - Show filter options (default: false)</li>
    <li><code>results_per_page</code> - Results per page (default: 10)</li>
    <li><code>template</code> - Result template (default: default)</li>
</ul>

<h3>JetEngine Integration</h3>
<p>SearchAdvanx integrates seamlessly with JetEngine:</p>
<ul>
    <li>Custom query type: "SearchAdvanx Query"</li>
    <li>Automatic meta field searching</li>
    <li>Custom field filtering support</li>
    <li>Dynamic content compatibility</li>
</ul>

<h3>Elementor Integration</h3>
<p>Use the SearchAdvanx widget in Elementor:</p>
<ul>
    <li>Drag and drop search widget</li>
    <li>Visual style controls</li>
    <li>Live preview in editor</li>
    <li>Responsive design options</li>
</ul>