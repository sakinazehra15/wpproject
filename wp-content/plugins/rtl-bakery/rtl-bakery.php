<?php
/**
 * Plugin Name: WPBakery RTL
 * Plugin URI: https://www.combar.co.il
 * Description: RTL Bakery sets the direction of reading and writing of the WPBakery page builder for proper working in RTL languages.
 * Version: 1.0
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Author: Combar
 * Author URI: https://www.combar.co.il/contact-us/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rtl-bakery
 * Domain Path: /languages
 */
 
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// define plugin version
if (!defined('vcrtl_pp_version')) {
	define('RTL_BAKERY_VERSION', '1.0');
}

/**
  * enqueue plugin stylesheets
  */
add_action('wp_enqueue_scripts', 'rtl_bakery_styles', 100);
add_action('admin_enqueue_scripts', 'rtl_bakery_styles', 100);
function rtl_bakery_styles() {
    global $post;
	$rtl_bakery_plugin = plugin_dir_url(__FILE__ );
	if (is_rtl() && defined( 'WPB_VC_VERSION' ) ) {
		if ( is_admin() ) {
			wp_enqueue_style( 'rtl-bakery-backend', $rtl_bakery_plugin . 'css/rtl-bakery-backend.css', false, RTL_BAKERY_VERSION, 'all');
		} else {
			wp_enqueue_style( 'rtl-bakery', $rtl_bakery_plugin . 'css/rtl-bakery.css', false, RTL_BAKERY_VERSION, 'all');
		}
		if ($_GET['vc_editable'] == 'true') {
			wp_enqueue_style( 'rtl-bakery-backend', $rtl_bakery_plugin . 'css/rtl-bakery-backend.css', false, RTL_BAKERY_VERSION, 'all');
		}
	}
}

/**
 * Add admin warning if WPBakery is not detected
 */
add_action('admin_notices', 'rtl_bakery_not_found_notice');
function rtl_bakery_not_found_notice(){
	
    if ( !defined( 'WPB_VC_VERSION' ) ) {
		$current_user = get_current_user_id();
		if (empty($current_user)) {
			return false;
		}
		$watched_meta = '_rtl_bakery_admin_notice';
		$notice_watched = get_user_meta($current_user, $watched_meta);
		if (!$notice_watched ) {
			 echo '<div class="notice notice-error is-dismissible">
				 <p>' . __('WPBakery RTL detected that WPBakery is not active. so we turned off plugin activity for now.', 'rtl-bakery') . ' <a href="?' . $watched_meta . '=true">' . __('Dismiss', 'rtl-bakery') . '</a></p>
			 </div>';
		}
    }
}

/**
 * Add '_rtl_bakery_admin_notice' field if the user clicks the 'Dismiss' button.
 */
 add_filter('init', 'rtl_bakery_update_user_admin_notice_meta');
function rtl_bakery_update_user_admin_notice_meta() {

	$watched_meta = '_rtl_bakery_admin_notice';

	if ($_GET[$watched_meta] == true) {
	
		$current_user = get_current_user_id();
		if (empty($current_user)) {
			return false;
		}
		add_user_meta( $current_user, $watched_meta, true );
		
	}
	
}