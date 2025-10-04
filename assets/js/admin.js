/**
 * SearchAdvanx Admin JavaScript
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var siteIndex = $('#sites-list .site-config').length;
        
        // Tab switching
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').hide();
            $(target).show();
        });
        
        // Add new site
        $('#add-site').on('click', function() {
            var siteHtml = `
                <div class="site-config" data-index="${siteIndex}">
                    <h4>Site ${siteIndex + 1} <button type="button" class="remove-site">Remove</button></h4>
                    <table class="form-table">
                        <tr>
                            <th><label>Site Name</label></th>
                            <td><input type="text" name="searchadvanx_options[external_sites_config][${siteIndex}][name]" value="" class="regular-text" placeholder="My WordPress Site" /></td>
                        </tr>
                        <tr>
                            <th><label>Site URL</label></th>
                            <td><input type="url" name="searchadvanx_options[external_sites_config][${siteIndex}][url]" value="" class="regular-text" placeholder="https://example.com" required /></td>
                        </tr>
                        <tr>
                            <th><label>API Key</label></th>
                            <td><input type="password" name="searchadvanx_options[external_sites_config][${siteIndex}][api_key]" value="" class="regular-text" placeholder="Optional API Key" /></td>
                        </tr>
                        <tr>
                            <th><label>Custom Endpoint</label></th>
                            <td><input type="text" name="searchadvanx_options[external_sites_config][${siteIndex}][endpoint]" value="wp-json/searchadvanx/v1/search" class="regular-text" placeholder="wp-json/searchadvanx/v1/search" /></td>
                        </tr>
                        <tr>
                            <th><label>Default Post Type</label></th>
                            <td>
                                <select name="searchadvanx_options[external_sites_config][${siteIndex}][default_post_type]">
                                    <option value="">Any Post Type</option>
                                    <option value="post">Posts</option>
                                    <option value="page">Pages</option>
                                    <option value="product">Products</option>
                                    <option value="custom">Custom</option>
                                </select>
                                <input type="text" name="searchadvanx_options[external_sites_config][${siteIndex}][custom_post_type]" value="" placeholder="Custom post type" style="margin-left: 10px; display:none;" />
                            </td>
                        </tr>
                        <tr>
                            <th><label>Custom Parameters</label></th>
                            <td>
                                <textarea name="searchadvanx_options[external_sites_config][${siteIndex}][custom_params]" rows="3" cols="50" placeholder='{"meta_key": "value", "orderby": "date"}'></textarea>
                                <p class="description">JSON format for additional API parameters</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Active</label></th>
                            <td><input type="checkbox" name="searchadvanx_options[external_sites_config][${siteIndex}][active]" value="1" checked /> Enable this site for searches</td>
                        </tr>
                    </table>
                    <button type="button" class="test-site-connection" data-index="${siteIndex}">Test Connection</button>
                    <div class="connection-result" id="result-${siteIndex}"></div>
                </div>
            `;
            
            $('#sites-list').append(siteHtml);
            siteIndex++;
        });
        
        // Remove site
        $(document).on('click', '.remove-site', function() {
            if (confirm('Are you sure you want to remove this site?')) {
                $(this).closest('.site-config').remove();
            }
        });
        
        // Toggle custom post type input
        $(document).on('change', 'select[name*="[default_post_type]"]', function() {
            var customInput = $(this).siblings('input[name*="[custom_post_type]"]');
            if ($(this).val() === 'custom') {
                customInput.show();
            } else {
                customInput.hide();
            }
        });
        
        // Test site connection
        $(document).on('click', '.test-site-connection', function() {
            var index = $(this).data('index');
            var siteConfig = $(this).closest('.site-config');
            var url = siteConfig.find('input[name*="[url]"]').val();
            var endpoint = siteConfig.find('input[name*="[endpoint]"]').val();
            var apiKey = siteConfig.find('input[name*="[api_key]"]').val();
            var resultDiv = $('#result-' + index);
            
            if (!url) {
                resultDiv.html('<p style="color: red;">Please enter a site URL first.</p>');
                return;
            }
            
            resultDiv.html('<p>Testing connection...</p>');
            
            var testUrl = url.replace(/\/$/, '') + '/' + endpoint + '?query=test';
            
            $.ajax({
                url: searchadvanx_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'searchadvanx_test_connection',
                    nonce: searchadvanx_admin.nonce,
                    test_url: testUrl,
                    api_key: apiKey
                },
                success: function(response) {
                    if (response.success) {
                        resultDiv.html('<p style="color: green;">✓ Connection successful!</p>');
                    } else {
                        resultDiv.html('<p style="color: red;">✗ Connection failed: ' + response.data + '</p>');
                    }
                },
                error: function() {
                    resultDiv.html('<p style="color: red;">✗ Connection test failed.</p>');
                }
            });
        });
        
        // Validate JSON in custom parameters
        $(document).on('blur', 'textarea[name*="[custom_params]"]', function() {
            var value = $(this).val().trim();
            if (value && value !== '') {
                try {
                    JSON.parse(value);
                    $(this).css('border-color', '');
                } catch (e) {
                    $(this).css('border-color', 'red');
                    alert('Invalid JSON format in custom parameters');
                }
            }
        });
        
    });
    
})(jQuery);