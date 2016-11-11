<?php
/*
 Plugin Name: BP WC Vendors
 Plugin URI: http://themekraft.com
 Description: Integrates the WC Vendors Pro Plugin With BuddyPress
 Version: 1.0.9
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
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

	if ( ! defined( 'WCV_PRO_VERSION' ) ) {
		return;
	}

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
		define( 'BP_WCV_TEMPLATE_PATH', BP_WCV_INSTALL_PATH . 'includes/templates/' );
	}

	include_once( dirname( __FILE__ ) . '/includes/functions.php' );
	include_once( dirname( __FILE__ ) . '/includes/bp-wc-vendors.php' );

	if ( is_admin() ) {
		include_once( dirname( __FILE__ ) . '/includes/admin/admin.php' );
	}
}

// Check all dependencies
add_action( 'plugins_loaded', 'bp_wc_vendors_requirements' );
function bp_wc_vendors_requirements() {
	if ( ! defined( 'WCV_PRO_VERSION' ) ) {
		add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP WC Vendors needs WC Vendors Pro to be installed. <a target="_blank" href="%s">--> Get it now</a>!\', "bp-wcv" ) . \'</strong></p></div>\', "https://www.wcvendors.com/product/wc-vendors-pro/" );' ) );
	}

	if ( ! defined( 'BP_VERSION' ) ) {
		add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP WC Vendors needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', " buddypress" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
	}
}

// Load the BuddyPress needed files and create the BP WC Vendors Component
add_action( 'bp_setup_components', 'bp_wc_vendors_bp_init', 10 );
function bp_wc_vendors_bp_init() {
	global $bp;

	if ( ! defined( 'WCV_PRO_VERSION' ) ) {
		return;
	}

	require( dirname( __FILE__ ) . '/includes/bp-wc-vendors-members-component.php' );
	$bp->bp_wc_vendors = new BuddyForms_WC_Vendors_Component();
}
