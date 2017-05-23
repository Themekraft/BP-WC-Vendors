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


// Include all needed files
add_action( 'init', 'bp_wc_vendors_includes', 10 );
function bp_wc_vendors_includes() {

//	if ( ! defined( 'WCV_PRO_VERSION' ) ) {
//		return;
//	}

	if ( ! defined( 'BP_VERSION' ) ) {
		return;
	}

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

	include_once( dirname( __FILE__ ) . '/includes/functions.php' );
	include_once( dirname( __FILE__ ) . '/includes/buddyforms.php' );
	include_once( dirname( __FILE__ ) . '/includes/bp-wc-vendors.php' );

	if ( is_admin() ) {
		include_once( dirname( __FILE__ ) . '/includes/admin/admin.php' );
	}
}

// Load the BuddyPress needed files and create the BP WC Vendors Component
add_action( 'bp_setup_components', 'bp_wc_vendors_bp_init', 10 );
function bp_wc_vendors_bp_init() {
	global $bp;

//	if ( defined( 'WCV_PRO_VERSION' ) ) {
		require( dirname( __FILE__ ) . '/includes/bp-wc-vendors-members-component.php' );
		$bp->bp_wc_vendors = new BuddyForms_WC_Vendors_Component();
//	} else {

//	}

}

add_action( 'admin_enqueue_scripts', 'bp_wc_vendors_wp_admin_style' );
function bp_wc_vendors_wp_admin_style($hook) {

	if($hook != 'toplevel_page_bp_wc_vendors_screen') {
		return;
	}
	wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/assets/admin/css/admin.css', __FILE__) );

}


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
			'capability'   => 'manage_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => false,
			// If false, a user cannot dismiss the nag message.
			'is_automatic' => true,
			// Automatically activate plugins after installation or not.
		);

		// Call the tgmpa function to register the required plugins
		tgmpa( $plugins, $config );

	} );
}, 1, 1 );

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
			'has_premium_version' => false,
			'has_addons'          => false,
			'has_paid_plans'      => true,
			'menu'                => array(
				'slug'           => 'bp_wc_vendors_screen',
				'override_exact' => true,
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