<?php
/*
 Plugin Name: BP WC Vendors
 Plugin URI: https://themekraft.com/products/bp-wc-vendors/
 Description: Integrates the WC Vendors Pro Plugin With BuddyPress
 Version: 1.1
 Author: ThemeKraft
 Author URI: http://themekraft.com/
 License: GPLv3 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */


class BP_WC_Vendors {

	/**
	 * @var string
	 */
	public $version = '1.1';

	/**
	 * Initiate the class
	 *
	 * @package
	 * @since 0.1
	 */
	public function __construct() {

		$this->load_constants();

		add_action( 'init', array( $this, 'includes' ), 4, 1 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'bp_wcv_admin_js' ), 2, 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'bp_wcv_front_js_css' ), 2, 1 );

		// Load the BuddyPress needed files and create the BP WC Vendors Component
		add_action( 'bp_setup_components', array( $this, 'bp_wc_vendors_bp_init' ), 10 );

	}

	/**
	 * Defines constants needed throughout the plugin.
	 *
	 * These constants can be overridden in bp-custom.php or wp-config.php.
	 *
	 * @package bp_wcv
	 * @since 0.1
	 */
	public function load_constants() {

		/**
		 * Define the plugin version
		 */
		define( 'BP_WCV_VERSION', $this->version );

		if ( ! defined( 'BP_WCV_PLUGIN_URL' ) ) {
			define( 'BP_WCV_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
		}

		if ( ! defined( 'BP_WCV_INSTALL_PATH' ) ) {
			define( 'BP_WCV_INSTALL_PATH', dirname( __FILE__ ) . '/' );
		}

		if ( ! defined( 'BP_WCV_INCLUDES_PATH' ) ) {
			define( 'BP_WCV_INCLUDES_PATH', BP_WCV_INSTALL_PATH . 'includes/' );
		}

		if ( ! defined( 'BP_WCV_TEMPLATE_PATH' ) ) {
			define( 'BP_WCV_TEMPLATE_PATH', BP_WCV_INSTALL_PATH . 'templates/' );
		}

	}

	/**
	 * Include files needed by BuddyForms
	 *
	 * @package bp_wcv
	 * @since 0.1
	 */
	public function includes() {

		include_once( dirname( __FILE__ ) . '/includes/functions.php' );
		include_once( dirname( __FILE__ ) . '/includes/shortcodes.php' );
		include_once( dirname( __FILE__ ) . '/includes/buddyforms.php' );
		include_once( dirname( __FILE__ ) . '/includes/bp-wc-vendors.php' );

		if ( is_admin() ) {
			include_once( dirname( __FILE__ ) . '/includes/admin/admin.php' );
			include_once( dirname( __FILE__ ) . '/includes/admin/welcome-screen.php' );
		}

	}

	/**
	 * Load the textdomain for the plugin
	 *
	 * @package bp_wcv
	 * @since 0.1
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'bp-wcv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue the needed CSS for the admin screen
	 *
	 * @package bp_wcv
	 * @since 0.1
	 */
	function bp_wcv_admin_style( $hook_suffix ) {
	}

	/**
	 * Enqueue the needed JS for the admin screen
	 *
	 * @since 1.1
	 */
	function bp_wcv_admin_js( $hook_suffix ) {

		if($hook_suffix != 'toplevel_page_bp_wc_vendors_screen') {
			return;
		}
		wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/assets/admin/css/admin.css', __FILE__) );

	}

	/**
	 * Enqueue the needed JS for the frontend
	 *
	 * @since 0.1
	 */
	function bp_wcv_front_js_css() {
	}

	function bp_wc_vendors_bp_init() {
		global $bp;

		require( dirname( __FILE__ ) . '/includes/bp-wc-vendors-members-component.php' );
		$bp->bp_wc_vendors = new BuddyForms_WC_Vendors_Component();

	}

}

$GLOBALS['BP_WC_Vendors'] = new BP_WC_Vendors();


//
// Check the plugin dependencies
//
add_action( 'init', function () {

	// Only Check for requirements in the admin
	if ( ! is_admin() ) {
		return;
	}

	// Require TGM
	require( dirname( __FILE__ ) . '/includes/resources/tgm/class-tgm-plugin-activation.php' );

	// Hook required plugins function to the tgmpa_register action
	add_action( 'tgmpa_register', function () {

		// Create the required plugins array
		$plugins['buddypress'] = array(
			'name'     => 'BuddyPress',
			'slug'     => 'buddypress',
			'required' => true,
		);

		if ( ! defined( 'BUDDYFORMS_PRO_VERSION' ) ) {
			$plugins['buddyforms'] = array(
				'name'      => 'BuddyForms',
				'slug'      => 'buddyforms',
				'required'  => false,
			);
		}

		$plugins['buddyforms-members'] = array(
			'name'     => 'BuddyForms Members',
			'slug'     => 'buddyforms-members',
			'required' => false,
		);

		$plugins['woocommerce'] = array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		);

		$plugins['wc-vendors'] = array(
			'name'     => 'WC Vendors',
			'slug'     => 'wc-vendors',
			'required' => true,
		);

		$config = array(
			'id'           => 'tgmpa',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'parent_slug'  => 'plugins.php',
			// Parent menu slug.
			'capability'   => '^',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'is_automatic' => true,
			// Automatically activate plugins after installation or not.
		);

		// Call the tgmpa function to register the required plugins
		tgmpa( $plugins, $config );

	}  );
}, 0, 1 );

// Create a helper function for easy SDK access.
function bp_wc_vendors_fs() {
	global $bp_wc_vendors_fs;

	if ( ! isset( $bp_wc_vendors_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/includes/resources/freemius/start.php';

		$bp_wc_vendors_fs = fs_dynamic_init( array(
			'id'                  => '416',
			'slug'                => 'bp-wc-vendors',
			'type'                => 'plugin',
			'public_key'          => 'pk_0b28a902c5241cbcb765a554c92cf',
			'is_premium'          => true,
			// If your plugin is a serviceware, set this option to false.
			'has_premium_version' => true,
			'has_addons'          => true,
			'has_paid_plans'      => true,
			'menu'                => array(
				'slug'           => 'bp_wc_vendors_screen',
				'override_exact' => true,
				'first-path'     => 'admin.php?page=bp_wcv_welcome_screen',
				'support'        => false,
			),
			// Set the SDK to work in a sandbox mode (for development & testing).
			// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
			'secret_key'          => 'sk_~_UJ99}ShjHEJZ.:aDP{N(->Thf{X',
		) );
	}

	return $bp_wc_vendors_fs;
}

// Init Freemius.
bp_wc_vendors_fs();
// Signal that SDK was initiated.
do_action( 'bp_wc_vendors_fs_loaded' );

function bp_wc_vendors_fs_settings_url() {
	return admin_url( 'admin.php?page=bp_wc_vendors_screen' );
}

bp_wc_vendors_fs()->add_filter( 'connect_url', 'bp_wc_vendors_fs_settings_url' );
bp_wc_vendors_fs()->add_filter( 'after_skip_url', 'bp_wc_vendors_fs_settings_url' );
bp_wc_vendors_fs()->add_filter( 'after_connect_url', 'bp_wc_vendors_fs_settings_url' );