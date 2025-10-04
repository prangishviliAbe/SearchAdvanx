<?php
/**
 * Debug test file for SearchAdvanx
 * Temporary file to test class loading
 */

// Define WordPress constants for testing (if not already defined)
if (!defined('ABSPATH')) {
    define('ABSPATH', '/');
}

// Define plugin constants
define('SEARCHADVANX_VERSION', '1.0.0');
define('SEARCHADVANX_PLUGIN_FILE', __FILE__);
define('SEARCHADVANX_PLUGIN_DIR', dirname(__FILE__) . '/');
define('SEARCHADVANX_PLUGIN_URL', '');
define('SEARCHADVANX_PLUGIN_BASENAME', plugin_basename(__FILE__));

echo "Testing SearchAdvanx plugin loading...\n";

// Test 1: Check if files exist
$required_files = [
    'includes/class-database.php',
    'includes/class-api.php', 
    'includes/admin/class-admin.php',
    'includes/class-searchadvanx.php'
];

foreach ($required_files as $file) {
    $path = SEARCHADVANX_PLUGIN_DIR . $file;
    if (file_exists($path)) {
        echo "✓ File exists: $file\n";
    } else {
        echo "✗ File missing: $file\n";
    }
}

// Test 2: Try to load classes manually
echo "\nTesting manual class loading...\n";

try {
    require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-database.php';
    echo "✓ Database class loaded\n";
} catch (Exception $e) {
    echo "✗ Database class error: " . $e->getMessage() . "\n";
}

try {
    require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-api.php';
    echo "✓ API class loaded\n";
} catch (Exception $e) {
    echo "✗ API class error: " . $e->getMessage() . "\n";
}

try {
    require_once SEARCHADVANX_PLUGIN_DIR . 'includes/admin/class-admin.php';
    echo "✓ Admin class loaded\n";
} catch (Exception $e) {
    echo "✗ Admin class error: " . $e->getMessage() . "\n";
}

try {
    require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-searchadvanx.php';
    echo "✓ Main class loaded\n";
} catch (Exception $e) {
    echo "✗ Main class error: " . $e->getMessage() . "\n";
}

// Test 3: Check class existence
echo "\nTesting class existence...\n";

$classes = ['SearchAdvanx_Database', 'SearchAdvanx_API', 'SearchAdvanx_Admin', 'SearchAdvanx'];
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✓ Class exists: $class\n";
    } else {
        echo "✗ Class missing: $class\n";
    }
}

echo "\nDebug test completed.\n";