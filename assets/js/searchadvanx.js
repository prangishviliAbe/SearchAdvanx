/**
 * SearchAdvanx Frontend JavaScript
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Handle search form submission
        $('.searchadvanx-form').on('submit', function(e) {
            e.preventDefault();
            
            var container = $(this).closest('.searchadvanx-container');
            var query = container.find('[name="searchadvanx_query"]').val();
            var postType = container.data('post-type') || container.find('[name="searchadvanx_post_type"]').val();
            var resultsPerPage = container.data('results-per-page');
            
            // Debug: Log all data attributes
            console.log('Container data attributes:', {
                'post-type': container.data('post-type'),
                'results-per-page': container.data('results-per-page'),
                'include-external': container.data('include-external'),
                'include-external-raw': container.attr('data-include-external')
            });
            
            if (!query.trim()) {
                return;
            }
            
            performSearch(container, query, postType, resultsPerPage);
        });
        
        // Handle filter changes
        $('.searchadvanx-filter').on('change', function() {
            var container = $(this).closest('.searchadvanx-container');
            var query = container.find('[name="searchadvanx_query"]').val();
            
            if (query.trim()) {
                var postType = container.find('[name="searchadvanx_post_type"]').val();
                var resultsPerPage = container.data('results-per-page');
                performSearch(container, query, postType, resultsPerPage);
            }
        });
        
        // Perform search function
        function performSearch(container, query, postType, resultsPerPage) {
            container.find('.searchadvanx-loading').show();
            container.find('.searchadvanx-results').empty();
            
            var includeExternalAttr = container.attr('data-include-external');
            var includeExternal = includeExternalAttr === 'true';
            
            // Debug: Show the comparison
            console.log('Include External Debug:', {
                'attr': includeExternalAttr,
                'boolean': includeExternal,
                'comparison': includeExternalAttr === 'true'
            });
            
            var searchData = {
                action: 'searchadvanx_search',
                nonce: searchadvanx_ajax.nonce,
                query: query,
                post_type: postType || 'post',
                posts_per_page: resultsPerPage || 10,
                include_external: includeExternal
            };
            
            $.ajax({
                url: searchadvanx_ajax.ajax_url,
                type: 'POST',
                data: searchData,
                success: function(response) {
                    container.find('.searchadvanx-loading').hide();
                    
                    // Debug logging
                    console.log('SearchAdvanx AJAX Response:', response);
                    console.log('Include External:', includeExternal);
                    
                    if (response.success && response.results && response.results.length > 0) {
                        displayResults(container, response);
                    } else {
                        console.log('No results - Response success:', response.success, 'Results length:', response.results ? response.results.length : 'undefined');
                        displayNoResults(container);
                    }
                },
                error: function() {
                    container.find('.searchadvanx-loading').hide();
                    displayError(container);
                }
            });
        }
        
        // Display search results
        function displayResults(container, response) {
            var resultsHtml = '<div class="searchadvanx-results-list">';
            
            response.results.forEach(function(result) {
                var featuredImage = result.featured_image ? 
                    '<img src="' + result.featured_image + '" alt="' + result.title + '" style="max-width: 100px; float: left; margin-right: 15px;" />' : '';
                
                var sourceInfo = result.source_name ? 
                    '<span class="source">from ' + result.source_name + '</span>' : '';
                
                resultsHtml += `
                    <div class="searchadvanx-result-item">
                        ${featuredImage}
                        <h3><a href="${result.url}" target="_blank">${result.title}</a></h3>
                        <p>${result.content}</p>
                        <div class="searchadvanx-result-meta">
                            <span class="date">${new Date(result.date).toLocaleDateString()}</span>
                            <span class="author">by ${result.author}</span>
                            <span class="post-type">${result.post_type}</span>
                            ${sourceInfo}
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                `;
            });
            
            resultsHtml += '</div>';
            resultsHtml += `<div class="searchadvanx-results-count">Found ${response.total} results</div>`;
            
            container.find('.searchadvanx-results').html(resultsHtml);
        }
        
        // Display no results message
        function displayNoResults(container) {
            var resultsHtml = '<div class="searchadvanx-no-results">No results found. Try different keywords.</div>';
            container.find('.searchadvanx-results').html(resultsHtml);
        }
        
        // Display error message
        function displayError(container) {
            var resultsHtml = '<div class="searchadvanx-error">Search failed. Please try again.</div>';
            container.find('.searchadvanx-results').html(resultsHtml);
        }
        
        // Auto-complete functionality (optional enhancement)
        $('.searchadvanx-input').on('input', function() {
            var query = $(this).val();
            var container = $(this).closest('.searchadvanx-container');
            
            // Debounce auto-complete
            clearTimeout(container.data('autocomplete-timeout'));
            
            if (query.length > 2) {
                container.data('autocomplete-timeout', setTimeout(function() {
                    // Implement auto-complete if needed
                }, 300));
            }
        });
        
    });
    
})(jQuery);