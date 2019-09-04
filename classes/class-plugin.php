<?php

namespace Davon\FloatingAdminBar;

class Plugin {

	private static $instance;

	public $plugin_headers;
	public $domain_path;

	/**
	 * Default plugin function called in plugin root file
	 *
	 * @since    1.0.0
	 */
	public function run( $plugin_headers ) {

		$this->plugin_headers = $plugin_headers;
		$this->domain_path    = basename( dirname( __DIR__ ) ) . $this->plugin_headers->DomainPath;

		// load plugin classes
		$this->load_plugin_classes();

		// load the textdomain
		add_action( 'init', [ $this, 'load_textdomain' ] );

	}


	/**
	 * Creates an instance if one isn't already available,
	 * then return the current instance.
	 * @return object The class instance.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Plugin ) ) {

			self::$instance = new Plugin;

		}

		return self::$instance;
	}


	/**
	 * loads and initializes the plugin classes
	 *
	 * @since    1.0.0
	 */
	private function load_plugin_classes() {

		// load example class
		include_once( plugin_dir_path( __FILE__ ) . 'package/class-stylechanges.php' );
		davon_floating_admin_bar()->StyleChanges = new \Davon\FloatingAdminBar\Package\StyleChanges();
		if ( method_exists( davon_floating_admin_bar()->StyleChanges, 'run' ) ) {
			davon_floating_admin_bar()->StyleChanges->run();
		}

	}


	/**
	 * Load the plugins textdomain
	 *
	 * @since    1.0.0
	 */
	static function load_textdomain() {

		load_plugin_textdomain( 'davon-floating-admin-bar', false, $this->domain_path );

	}
}
