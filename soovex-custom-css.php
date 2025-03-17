<?php
/**
* Plugin Name: Soovex Custom CSS for Elementor
* Description: Allows adding custom CSS for specific pages or elements in Elementor.
* Version: 1.1
* Plugin URI: https://www.soovex.com/
* Author: Mustafijur Rahman
* Author URI: https://www.mustafijur.org/
* Text Domain: soovex-custom-css-for-elementor
* Requires at least: 6.0
* Tested up to: 6.7.2
* 
* Requires Plugins: elementor
*
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/






if (!defined('ABSPATH')) exit;

require_once( plugin_dir_path( __FILE__ ) . 'assets/int.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/settings.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'settings/menus.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings/doc.php' );

// Initialize Plugin
SOOVEX_Settings::instance();

// SOOVEX_Settings::sanitize_settings;


// Add "Settings" link under plugin on the Plugins page
function soovex_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=elementor-settings#tab-_soovex_css_settings') . '">' . __('Settings', 'soovex-custom-css-for-elementor') . '</a>';
    array_unshift($links, $settings_link);
    
    
    
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'soovex_add_settings_link');
