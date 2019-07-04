<?php
/*
 * Plugin Name: BP WC Vendors
 *Plugin URI: https://themekraft.com/products/bp-wc-vendors/
 * Description: Integrates the WC Vendors Pro Plugin With BuddyPress
 * Version: 1.1.5
 * Author: ThemeKraft
 * Author URI: http://themekraft.com/
 * License: GPLv3 or later
 * Network: false
 * Svn: bp-wc-vendors
 *
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
	public $version = '1.1.5';

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
		add_action( 'bp_setup_components', array( $this, 'bp_wcv_bp_init' ), 10 );

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
		load_plugin_textdomain( 'bpwcv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue the needed CSS for the admin screen
	 *
	 * @package bp_wcv
	 * @since 0.1
	 */
	function bp_wcv_admin_css( $hook_suffix ) {

		if($hook_suffix == 'toplevel_page_bp_wcv_screen') {
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

		if($hook_suffix != 'toplevel_page_bp_wcv_screen') {
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

	function bp_wcv_bp_init() {
		global $bp;

		require( dirname( __FILE__ ) . '/includes/bp-wc-vendors-members-component.php' );
		$bp->bp_wcv = new BuddyForms_WC_Vendors_Component();

	}

}

$GLOBALS['bp_wcv'] = new BP_WC_Vendors();


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
function bp_wcv_fs() {
	global $bp_wcv_fs;

	if ( ! isset( $bp_wcv_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/includes/resources/freemius/start.php';

		$bp_wcv_fs = fs_dynamic_init( array(
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
				'slug'           => 'bp_wcv_screen',
				'override_exact' => true,
				'first-path'     => 'admin.php?page=bp_wcv_welcome_screen',
				'support'        => false,
			),
		) );
	}

	return $bp_wcv_fs;
}

// Init Freemius.
bp_wcv_fs();
// Signal that SDK was initiated.
do_action( 'bp_wcv_fs_loaded' );

function bp_wcv_fs_settings_url() {
	return admin_url( 'admin.php?page=bp_wcv_screen' );
}

bp_wcv_fs()->add_filter( 'connect_url', 'bp_wcv_fs_settings_url' );
bp_wcv_fs()->add_filter( 'after_skip_url', 'bp_wcv_fs_settings_url' );
bp_wcv_fs()->add_filter( 'after_connect_url', 'bp_wcv_fs_settings_url' );

function bp_wcv_special_admin_notice() {
	$user_id = get_current_user_id();
	if ( ! get_user_meta( $user_id, 'bp_wcv_special_admin_notice_dismissed' ) ) {
		?>
        <div class="notice notice-success is-dismissible">
            <h4 style="margin-top: 20px;">BUILD THE ULTIMATE BUDDYPRESS MARKETPLACE</h4>
            <p style="line-height: 2.2; font-size: 13px;"><b>GO PRO NOW – AND SAVE BIG – 50% OFF - THIS MONTH ONLY</b><br>
                Get 50% discount if you order within the next month – only until 06 Jul 2017.
                <br>
                Coupon Code: <span
                        style="line-height: 1; margin: 0 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; background: #fff; border: 1px solid rgba(0,0,0,0.1);">BPWCVENDORS50</span>
            </p>
            <p style="margin: 20px 0;">
                <a class="button xbutton-primary"
                   style="font-size: 15px; padding: 8px 20px; height: auto; line-height: 1;"
                   href="https://themekraft.com/final-beta-buddypress-wc-vendors/" target="_blank">READ MORE</a>
                <a class="button button-primary"
                   style="font-size: 15px; padding: 8px 20px; height: auto; line-height: 1; box-shadow: none; text-shadow: none; background: #46b450; color: #fff; border: 1px solid rgba(0,0,0,0.1);"
                   href="https://themekraft.com/lifetime-deal-99-instead-299-06-july/"
                   target="_blank"><s>&dollar;299</s> &dollar;99 LIFETIME DEAL</a>
                <a class="button xbutton-primary"
                   style="font-size: 15px; padding: 8px 20px; height: auto; line-height: 1;"
                   href="?bp_wcv_special_admin_notice_dismissed">Dismiss</a>
            </p>
        </div>
		<?php
	}
}
//add_action( 'admin_notices', 'bp_wcv_special_admin_notice' );

function bp_wcv_special_admin_notice_dismissed() {
	$user_id = get_current_user_id();
	if ( isset( $_GET['bp_wcv_special_admin_notice_dismissed'] ) ){
		add_user_meta( $user_id, 'bp_wcv_special_admin_notice_dismissed', 'true', true );
	}
}
//add_action( 'admin_init', 'bp_wcv_special_admin_notice_dismissed' );