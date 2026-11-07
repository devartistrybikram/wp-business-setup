<?php
/**
 * Plugin Name:       WP Business Setup
 * Plugin URI:        https://example.com/wp-business-setup
 * Description:       Centralized WordPress business identity and setup management. Stores structured business data, branding assets, multi-branch contacts, hours, social links, headers/footers, and rich Schema JSON-LD in the WordPress options table.
 * Version:           1.0.0
 * Author:            Antigravity
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-business-setup
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_BUSINESS_SETUP_VERSION', '1.0.0' );
define( 'WP_BUSINESS_SETUP_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_BUSINESS_SETUP_URL', plugin_dir_url( __FILE__ ) );

// Load Default Schema Definition
require_once WP_BUSINESS_SETUP_DIR . 'includes/default-schema.php';

// Load Helper & Frontend Template Functions
require_once WP_BUSINESS_SETUP_DIR . 'includes/helper-functions.php';

// Load Admin Controller
require_once WP_BUSINESS_SETUP_DIR . 'includes/class-business-setup.php';

/**
 * Initialize default options on plugin activation
 */
function wp_business_setup_activate() {
	$existing = get_option( 'business_setup_options', null );
	if ( null === $existing ) {
		$defaults = wp_business_setup_get_default_schema();
		add_option( 'business_setup_options', $defaults, '', 'yes' );
	}
}
register_activation_hook( __FILE__, 'wp_business_setup_activate' );

/**
 * Instantiate Admin Page
 */
function wp_business_setup_init() {
	if ( is_admin() ) {
		new WP_Business_Setup_Admin();
	}
}
add_action( 'plugins_loaded', 'wp_business_setup_init' );
