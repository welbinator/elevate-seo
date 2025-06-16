<?php
/**
 * Plugin Name: Elevate SEO
 * Description: Lightweight, AI-powered SEO plugin with all the essentials—no bloat, no nags.
 * Version: 1.0.0
 * Author: Elevate Plugins
 * Text Domain: elevate-seo
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'ELEVATE_SEO_VERSION', '1.0.0' );
define( 'ELEVATE_SEO_PLUGIN_FILE', __FILE__ );
define( 'ELEVATE_SEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELEVATE_SEO_URL', plugin_dir_url( __FILE__ ) );


// Autoload functions, classes, etc.
require_once ELEVATE_SEO_PATH . 'includes/admin-menu.php';
require_once ELEVATE_SEO_PATH . 'includes/meta-output.php';
require_once ELEVATE_SEO_PATH . 'includes/admin-assets.php';
require_once ELEVATE_SEO_PATH . 'includes/admin-cpt-settings.php';
require_once ELEVATE_SEO_PATH . 'includes/Admin/Fields.php';
require_once ELEVATE_SEO_PATH . 'includes/admin-meta-boxes.php';
require_once ELEVATE_SEO_PATH . 'includes/admin-robots.php';
require_once ELEVATE_SEO_PATH . 'includes/frontend-robots.php';




// Hook to load plugin text domain for translations
function elevate_seo_load_textdomain() {
    load_plugin_textdomain( 'elevate-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'elevate_seo_load_textdomain' );
