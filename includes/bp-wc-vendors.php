<?php

/* WC Vendors Pro Only - Adds View Store button to BuddyPress profiles */
add_action( 'bp_member_header_actions', 'bp_wc_vendors_bp_member_header_actions' );
function bp_wc_vendors_bp_member_header_actions() {

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );
	if ( isset( $bp_wc_vendors_options['visit_store_disabled'] ) ) {
		return;
	}

	$wcv_profile_id   = bp_displayed_user_id();
	$wcv_profile_info = get_userdata( bp_displayed_user_id() );
	$wcv_profile_role = implode( $wcv_profile_info->roles );
	$store_url        = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $wcv_profile_id );
	$sold_by          = '<div class="generic-button" id="post-mention"><a href="' . $store_url . '" class="send-message">Visit Store</a></div>';

	if ( isset($wcv_profile_info->roles[0]) && $wcv_profile_info->roles[0] == "vendor" ) {
		$vendor_name_message = get_the_author_meta( 'user_login' );
		$current_user        = wp_get_current_user();
		echo $sold_by;
	}
}

/* WC Vendors Pro - Adds a View Profile link on the vendors store header */
add_action( 'wcv_after_main_header', 'bp_wc_vendors_after_vendor_store_title' );
function bp_wc_vendors_after_vendor_store_title() {

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );
	if ( isset( $bp_wc_vendors_options['view_profile_disabled'] ) ) {
		return;
	}

	$vendor_shop    = urldecode( get_query_var( 'vendor_shop' ) );
	$wcv_profile_id = WCV_Vendors::get_vendor_id( $vendor_shop );
	$profile_url    = bp_core_get_user_domain( $wcv_profile_id );
	echo '<center><a href="' . $profile_url . '/profile/" class="button">View Profile</a></center>';
}

/* WC Vendors Pro - Adds a link to Profile on Single Product Pages */
add_action( 'woocommerce_product_meta_start', 'bp_wc_vendors_link_woocommerce_product_meta_start' );
function bp_wc_vendors_link_woocommerce_product_meta_start() {
	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );
	if ( isset( $bp_wc_vendors_options['view_profile_disabled'] ) ) {
		return;
	}

	$wcv_profile_id = get_the_author_meta( 'ID' );
	$profile_url    = bp_core_get_user_domain( $wcv_profile_id );
	echo 'Vendor Profile: <a href="' . $profile_url . '">View My Profile</a>';
}

/* WC Vendors Pro - Adds a "Contact Vendor" link on Single Product Pages which uses BuddyPress Private Messages */
add_action( 'woocommerce_product_meta_start', 'bp_wc_vendors_bpmail_woocommerce_product_meta_start' );
function bp_wc_vendors_bpmail_woocommerce_product_meta_start() {
	if ( ! function_exists( 'bp_get_messages_slug' ) ) {
		return;
	}

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );
	if ( isset( $bp_wc_vendors_options['contact_vendor_disabled'] ) ) {
		return;
	}

	if ( is_user_logged_in() ) {
		$wcv_store_id   = get_the_author_meta( 'ID' );
		$wcv_store_name = get_user_meta( $wcv_store_id, 'pv_shop_name', true );
		echo '<br>Contact Vendor: <a href="' . bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . get_the_author_meta( 'user_login' ) . '">Contact ' . $wcv_store_name . '</a>';
	} else {
		$wcv_my_account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		echo '<br>Contact Vendor: <a href="' . $wcv_my_account_url . '">Login or Register to Contact Vendor</a>';
	}
}

add_action( 'init', 'bp_wc_vendors_disable_sold_by', 9999 );
function bp_wc_vendors_disable_sold_by() {

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );
	if ( ! isset( $bp_wc_vendors_options['sold_by_disabled'] ) ) {
		return;
	}

	remove_action( 'woocommerce_product_meta_start', array( 'WCV_Vendor_Cart', 'sold_by_meta' ), 10, 2 );
	remove_action( 'woocommerce_after_shop_loop_item', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9, 2 );
}

add_action( 'buddyforms_the_loop_item_last', 'bp_wc_vendors_buddyforms_the_loop_actions', 10, 1 );
function bp_wc_vendors_buddyforms_the_loop_actions( $post_id ) {
	$product = new WC_Product( $post_id );

	if ( $product->price ) {
		echo $product->get_price_html() . ' ';
		woocommerce_template_loop_add_to_cart();
	}

}
