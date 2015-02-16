<?php
/*
 Plugin Name: BuddyForms WC-Vendors
 Plugin URI: http://themekraft.com/store/wordpress-front-end-editor-and-form-builder-buddyforms/
 Description: Integrates the WC-Vendors Plugin With BuddyForms
 Version: 0.0.1
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


add_action('init', 'bf_wc_vendors_includes', 10);
function bf_wc_vendors_includes(){
    include_once(dirname(__FILE__) . '/includes/bf-wc-vendors.php');
}

add_action('plugins_loaded', 'bf_wc_vendors_requirements');
function bf_wc_vendors_requirements(){
    if( ! defined( 'buddyforms' )){
        add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BuddyForms Review needs BuddyForms to be installed. <a target="_blank" href="%s">--> Get it now</a>!\', " wc4bp_xprofile" ) . \'</strong></p></div>\', "http://themekraft.com/store/wordpress-front-end-editor-and-form-builder-buddyforms/" );' ) );
        return;
    }
}