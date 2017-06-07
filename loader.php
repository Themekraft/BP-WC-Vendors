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
		add_action( 'admin_enqueue_scripts', array( $this, 'bp_wcv_admin_js' ), 1, 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'bp_wcv_admin_css' ), 1, 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'bp_wcv_front_js_css' ), 1, 10 );

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
		include_once( dirname( __FILE__ ) . '/includes/redirect.php' );
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
	function bp_wcv_admin_css( $hook_suffix ) {

		if($hook_suffix == 'toplevel_page_bp_wc_vendors_screen') {
			wp_enqueue_style( 'bp_wcv_wp_admin_css', plugins_url('/assets/admin/css/admin.css', __FILE__) );
		}
		if($hook_suffix == 'bp-wc-vendors_page_bp_wcv_welcome_screen') {
			wp_enqueue_style( 'bp_wcv_wp_welcome_admin_css', plugins_url('/assets/admin/css/welcome.css', __FILE__) );
		}

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
		wp_enqueue_script( 'bp_wcv_wp_admin_js', plugins_url('/assets/admin/js/admin.js', __FILE__) );

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
				'required'  => true,
			);
		}

		$plugins['buddyforms-members'] = array(
			'name'     => 'BuddyForms Members',
			'slug'     => 'buddyforms-members',
			'required' => false,
		);

		$plugins['buddyforms-woocommerce-form-elements'] = array(
			'name'     => 'BuddyForms WooCommerce Form Elements',
			'slug'     => 'buddyforms-woocommerce-form-elements',
			'required' => false,
		);

		$plugins['woocommerce'] = array(
			'name'     => 'A WooCommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		);

		$plugins['wc4bp'] = array(
			'name'     => 'WooCommerce BuddyPress Integration',
			'slug'     => 'wc4bp',
			'required' => false,
		);

		$plugins['wc-vendors'] = array(
			'name'     => 'WC Vendors',
			'slug'     => 'wc-vendors',
			'required' => true,
		);

		$config = array(
			'id'           => 'tgmpa',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => true,
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

function bp_wc_vendors_special_admin_notice() {
	?>
	<div class="notice notice-success is-dismissible">
		<p>GO PRO NOW – AND SAVE BIG<br>
            THIS WEEK – 60% OFF<br>
            Get 60% discount if you order within the beta week – only until 06 June 2017.<br>

            BPWCVENDORSBETA</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'bp_wc_vendors_special_admin_notice' );