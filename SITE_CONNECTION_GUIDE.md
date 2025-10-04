# 🔗 How to Connect WordPress Sites with SearchAdvanx

**Simple step-by-step guide to connect your WordPress sites for cross-site searching**

---

## 📋 What You Need

- ✅ SearchAdvanx plugin installed on **both sites**
- ✅ Admin access to **both sites**
- ✅ Both sites have **active internet connection**
- ✅ **5 minutes** to complete setup

---

## 🎯 Example Scenario

Let's connect **two sites**:

- **Main Site**: `https://mycompany.com` (wants to search the other site)
- **Docs Site**: `https://docs.mycompany.com` (will be searched)

---

## 🚀 Step-by-Step Connection Guide

### Step 1: Setup the Site to be Searched (Docs Site)

**On `https://docs.mycompany.com`:**

1. **Login to WordPress admin**
2. **Go to**: `SearchAdvanx > Settings`
3. **Find the API Key section**
4. **Click**: `Generate New Key` button
5. **Click**: `👁️ Show` to see the key
6. **Click**: `📋 Copy` to copy the key
7. **Click**: `Save Settings`

**✅ Result**: You now have an API key like: `K8mN2pQ7vR5tY9wE4uI1oP6aS3dF0gH2`

---

### Step 2: Setup the Searching Site (Main Site)

**On `https://mycompany.com`:**

1. **Login to WordPress admin**
2. **Go to**: `SearchAdvanx > External Sites`
3. **Click**: `Add New Site` button
4. **Fill in the form**:
   - **Site Name**: `Documentation`
   - **Site URL**: `https://docs.mycompany.com`
   - **API Key**: Paste the key from Step 1
   - **Endpoint**: Leave default (`wp-json/searchadvanx/v1/search`)
5. **Click**: `Save Settings`

**✅ Result**: Main site can now search the docs site!

---

## 🧪 Test the Connection

### Method 1: Quick Test via Browser

**Visit this URL** (replace with your sites):

```
https://mycompany.com/wp-json/searchadvanx/v1/external-search
```

**POST this data**:

```json
{
  "query": "test",
  "sites": [
    {
      "url": "https://docs.mycompany.com",
      "api_key": "K8mN2pQ7vR5tY9wE4uI1oP6aS3dF0gH2"
    }
  ]
}
```

### Method 2: Test with SearchAdvanx Shortcode

**Add to any page/post**:

```
[searchadvanx include_external="true"]
```

**Search something** and you should see results from both sites!

---

## 🔄 Multiple Sites Setup

### 3-Site Network Example

**Sites**:

- **Site A**: `https://blog.com`
- **Site B**: `https://shop.com`
- **Site C**: `https://support.com`

**Setup for each site**:

#### Site A Configuration:

```
Own API Key: blogApiKey123
External Sites:
- Shop (https://shop.com, shopApiKey456)
- Support (https://support.com, supportApiKey789)
```

#### Site B Configuration:

```
Own API Key: shopApiKey456
External Sites:
- Blog (https://blog.com, blogApiKey123)
- Support (https://support.com, supportApiKey789)
```

#### Site C Configuration:

```
Own API Key: supportApiKey789
External Sites:
- Blog (https://blog.com, blogApiKey123)
- Shop (https://shop.com, shopApiKey456)
```

---

## 🛠️ Visual Admin Interface Guide

### Generating API Key

**SearchAdvanx > Settings > API Key section**:

```
[API Key Field: ••••••••••••••••••••••••••••••••]
[Generate New Key] [👁️ Show] [📋 Copy]

📝 Instructions:
• Use this key to allow other sites to search your content
• Share this key with sites that need to search your content
• Keep this key secure - treat it like a password
```

### Adding External Sites

**SearchAdvanx > External Sites**:

```
🔗 How to Connect External Sites
1. On the target site: Install SearchAdvanx and generate API key
2. On this site: Add the target site configuration below
3. Test: Use the external search API to verify connection

[Add New Site]

Site 1:
Site Name: [Documentation        ]
Site URL:  [https://docs.site.com]
API Key:   [••••••••••••••••••••••]
Endpoint:  [wp-json/searchadvanx/v1/search]
[Remove Site]
```

---

## ⚡ Quick Reference

### Essential Information to Exchange

**For each site connection, you need**:

| What         | Where to Find                             | Where to Use                     |
| ------------ | ----------------------------------------- | -------------------------------- |
| **API Key**  | Target site > SearchAdvanx > Settings     | Requesting site > External Sites |
| **Site URL** | Target site domain                        | Requesting site > External Sites |
| **Endpoint** | Usually: `wp-json/searchadvanx/v1/search` | Requesting site > External Sites |

### Connection Flow

```
🏢 Site A (Searcher)          🏢 Site B (Content)
├─ External Sites config      ├─ API Key generated
├─ Site B URL + API key       └─ Search endpoint active
└─ Can search Site B
```

---

## ❓ Common Questions

### Q: Do I need API keys for public sites?

**A**: API keys are optional but **highly recommended** for security. Without them, anyone can search your site.

### Q: Can one site search multiple sites?

**A**: **Yes!** Add multiple external site configurations. Each external site needs its own API key.

### Q: Can multiple sites search one site?

**A**: **Yes!** Each requesting site uses the same API key from the target site.

### Q: What if I change the API key?

**A**: Update the API key on **all requesting sites** that use it, then save settings.

---

## 🚨 Troubleshooting

### "Unauthorized" Error

- ✅ Check API key is correct on target site
- ✅ Verify API key in external site config
- ✅ Remove extra spaces from API key

### "Site Not Found" Error

- ✅ Verify site URL is correct and accessible
- ✅ Check SearchAdvanx plugin is active on target site
- ✅ Test: `https://target-site.com/wp-json/`

### "No Results" Response

- ✅ Target site may have no matching content
- ✅ Check searchable post types in target site settings
- ✅ Try different search terms

### Connection Timeout

- ✅ Increase API timeout in SearchAdvanx settings
- ✅ Check target site server performance
- ✅ Test site accessibility from your server

---

## 🎉 Success! What's Next?

Once connected, you can:

### Use in Templates

```php
// Get external search results
$results = searchadvanx_external_search('wordpress', $sites);
```

### Use Shortcodes

```html
<!-- Search form including external sites -->
[searchadvanx include_external="true"]
```

### Use REST API

```javascript
// Search external sites via AJAX
fetch("/wp-json/searchadvanx/v1/external-search", {
  method: "POST",
  body: JSON.stringify({
    query: "search term",
    sites: external_sites_config,
  }),
});
```

### Elementor Integration

- **Add SearchAdvanx widget** to any page
- **Enable external search** in widget settings
- **Customize appearance** with Elementor controls

---

## 🔐 Security Best Practices

### Keep API Keys Secure

- ✅ **Never expose** API keys in frontend code
- ✅ **Rotate keys** every 3-6 months
- ✅ **Use HTTPS** for all site communications
- ✅ **Monitor usage** in SearchAdvanx analytics

### Site Protection

- ✅ **Always use API keys** for production sites
- ✅ **Keep WordPress updated** on all connected sites
- ✅ **Use strong passwords** for admin accounts
- ✅ **Regular backups** of all sites

---

## 📞 Need Help?

- **Plugin Documentation**: Check SearchAdvanx > API Docs in admin
- **GitHub Issues**: https://github.com/prangishviliAbe/SearchAdvanx/issues
- **WordPress Forums**: Plugin support section
- **Debug Mode**: Enable `WP_DEBUG` to see error details

---

**🎯 That's it! Your sites are now connected and ready for cross-site searching!**
