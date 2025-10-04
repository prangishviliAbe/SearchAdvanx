<?php
/**
 * Pro Features Notice Template
 *
 * @package SearchAdvanx
 * @since 1.3.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="searchadvanx-pro-notice" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 20px; margin: 20px 0; color: white; position: relative; overflow: hidden;">
    
    <!-- Background Pattern -->
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grain\" width=\"100\" height=\"100\" patternUnits=\"userSpaceOnUse\"><circle cx=\"25\" cy=\"25\" r=\"1\" fill=\"%23ffffff\" opacity=\"0.1\"/><circle cx=\"75\" cy=\"25\" r=\"1\" fill=\"%23ffffff\" opacity=\"0.05\"/><circle cx=\"25\" cy=\"75\" r=\"1\" fill=\"%23ffffff\" opacity=\"0.08\"/><circle cx=\"75\" cy=\"75\" r=\"1\" fill=\"%23ffffff\" opacity=\"0.03\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grain)\"/></svg>'); opacity: 0.3; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 1;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            
            <!-- Left Content -->
            <div style="flex: 1; min-width: 300px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; margin-right: 12px;">
                        ✨ COMING SOON
                    </span>
                    <h3 style="margin: 0; font-size: 1.4em; font-weight: 700;">
                        SearchAdvanx Pro
                    </h3>
                </div>
                
                <p style="margin: 8px 0 15px 0; opacity: 0.95; line-height: 1.5;">
                    Unlock advanced features and take your search experience to the next level
                </p>
                
                <!-- Pro Features List -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin: 15px 0;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">🚀</span>
                        <span style="font-size: 0.9em;">Advanced Search Filters</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">📊</span>
                        <span style="font-size: 0.9em;">Detailed Analytics</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">🎨</span>
                        <span style="font-size: 0.9em;">Premium Themes</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">⚡</span>
                        <span style="font-size: 0.9em;">Performance Boost</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">🔧</span>
                        <span style="font-size: 0.9em;">Custom Integrations</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 8px;">🎯</span>
                        <span style="font-size: 0.9em;">Priority Support</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Action -->
            <div style="text-align: center;">
                <button onclick="searchadvanxShowProModal()" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; padding: 12px 24px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.borderColor='rgba(255,255,255,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.borderColor='rgba(255,255,255,0.3)'">
                    Get Notified 🔔
                </button>
                <div style="margin-top: 8px; font-size: 0.8em; opacity: 0.8;">
                    Be the first to know!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pro Modal -->
<div id="searchadvanx-pro-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 999999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 16px; padding: 40px; max-width: 500px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
        
        <!-- Close Button -->
        <button onclick="searchadvanxCloseProModal()" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #999;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='#999'">×</button>
        
        <!-- Modal Content -->
        <div style="text-align: center;">
            <div style="font-size: 48px; margin-bottom: 20px;">🚀</div>
            <h2 style="margin: 0 0 15px 0; color: #333; font-size: 1.8em;">SearchAdvanx Pro Coming Soon!</h2>
            <p style="color: #666; line-height: 1.6; margin-bottom: 25px;">
                We're working hard to bring you advanced search features, premium themes, detailed analytics, and much more. 
                <strong>SearchAdvanx Pro will be released very soon!</strong>
            </p>
            
            <!-- Features Preview -->
            <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: left;">
                <h4 style="margin: 0 0 15px 0; color: #333;">What's Coming in Pro:</h4>
                <ul style="list-style-position: inside; color: #666; line-height: 1.8; margin: 0; padding: 0;">
                    <li>🔍 Advanced search filters and facets</li>
                    <li>📊 Comprehensive search analytics dashboard</li>
                    <li>🎨 10+ premium search result themes</li>
                    <li>⚡ Enhanced performance and caching</li>
                    <li>🔧 WooCommerce and custom post type integrations</li>
                    <li>🎯 Priority email support</li>
                    <li>📱 Mobile-optimized search experience</li>
                    <li>🌍 Multi-language search support</li>
                </ul>
            </div>
            
            <!-- Call to Action -->
            <div style="margin-top: 30px;">
                <p style="color: #666; font-size: 0.9em; margin-bottom: 15px;">
                    Want to be notified when SearchAdvanx Pro launches?
                </p>
                <a href="https://github.com/prangishviliAbe/SearchAdvanx" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    ⭐ Star on GitHub
                </a>
                <div style="margin-top: 10px; font-size: 0.8em; color: #999;">
                    Follow our GitHub for updates and early access!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function searchadvanxShowProModal() {
    document.getElementById('searchadvanx-pro-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function searchadvanxCloseProModal() {
    document.getElementById('searchadvanx-pro-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('searchadvanx-pro-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        searchadvanxCloseProModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        searchadvanxCloseProModal();
    }
});
</script>