<?php
/**
 *
 * @link              http://joelstuedle.ch/
 * @since             1.0.1
 * @package           Davon’s Floating Admin Bar
 *
 * @wordpress-plugin
 * Plugin Name: Davon’s Floating Admin Bar
 * Description: The Davon’s Floating Admin Bar floats at the top of your Website with minimal impact on the website’s appearance if you are logged in.
 * Version: 1.0.0
 * Author: joelmelon
 * Author URI: https://joelstuedle.ch/
 * Text Domain: davons-floating-admin-bar
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Use the global $wp_version Variable to make the Compatibility-Check on Plugin-Activation
global $wp_version;

// Get the File-Data from this File to reuse it in the Compatibility-Check on Plugin-Activation
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$davon_floating_admin_bar_heders = (object) get_plugin_data( __FILE__, true, true );

// Compatibility-Check Variables
$min_wp_version    = '4.5';
$min_php_version   = '5.3';
$wp_compatibility  = version_compare( $wp_version, $min_wp_version, '<' );
$php_compatibility = version_compare( PHP_VERSION, $min_php_version, '<' );

// Do the Compatibility-Check
if ( $wp_compatibility || $php_compatibility ) {

	function davon_twpt_compatibility_check() {

		global $davon_floating_admin_bar_heders;
		global $wp_version;
		global $min_wp_version;
		global $min_php_version;

		echo '<div class="error"><p>';
		echo sprintf(
			// translators: Compatibility-Check failed Warning
			_x(
				'%1$s requires PHP %2$s (or newer) and WordPress %3$s (or newer) to function properly. Your Site is using PHP %4$s and WordPress %5$s. Please upgrade. The Plugin has been deactivated automatically. Don’t hesitate to ask for Help @%6$s.',
				'Compatibility-Check failed Warning',
				'davons-floating-admin-bar'
			),
			'<strong>' . $davon_floating_admin_bar_heders->Name . '</strong>',
			$min_php_version,
			$min_wp_version,
			PHP_VERSION,
			$wp_version,
			'<a href="' . $davon_floating_admin_bar_heders->PluginURI . '" target="_blank" title="">' . $davon_floating_admin_bar_heders->Author . ' Support</a>'
		);
		echo '</p></div>';

		// remove the 'Plugin activated message'
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

	}

	add_action( 'admin_notices', 'davon_twpt_compatibility_check' );
	deactivate_plugins( plugin_basename( __FILE__ ) );

	return;
}

/**
 * Load the core plugin class
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'classes/class-plugin.php';

if ( ! function_exists( 'davon_floating_admin_bar' ) ) {
	function davon_floating_admin_bar() {
		return Davon\FloatingAdminBar\Plugin::get_instance( __FILE__ );
	}
}

davon_floating_admin_bar()->run( $davon_floating_admin_bar_heders );
