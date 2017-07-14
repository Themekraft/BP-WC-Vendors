<?php

add_filter( 'buddyforms_front_js_css_loader', 'bp_wcv_front_js_css_loader', 10, 1 );
function bp_wcv_front_js_css_loader( $fount ) {
	return true;
}

add_filter( 'wcv_dashboard_quick_links', 'bp_wcv_dashboard_quick_links', 10, 1 );
function bp_wcv_dashboard_quick_links( $quick_links ) {

	$bp_wcv_options = bp_wcv_get_options();

	$quick_links_new = Array();

	if ( ! isset( $bp_wcv_options['tab_products_disabled'] ) ) {
		$quick_links_new['product'] = $quick_links['product'];
	}

	if ( ! isset( $bp_wcv_options['tab_coupons_disabled'] ) ) {
		$quick_links_new['shop_coupon'] = $quick_links['shop_coupon'];
	}

	return $quick_links_new;
}

add_filter( 'bf_members_get_redirect_link', 'bp_wcv_bf_members_get_redirect_link');
function bp_wcv_bf_members_get_redirect_link( $link ){
	global $bp, $wp_query;

	$pagename = get_query_var( 'pagename' );

	$user_id = get_current_user_id();

	if( $pagename == 'vendor_dashboard' && ! WCV_Vendors::is_vendor( $user_id )){

		if( class_exists('WCVendors_Pro') ){
			$dashboard_page_id = WCVendors_Pro::get_option( 'dashboard_page_id' );
		} else{
			$dashboard_page_id = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
		}

		$link = get_permalink($dashboard_page_id);

	}

	return $link;

}

function bp_wcv_get_redirect_link( $post_ID ) {
	global $bp, $current_user, $wp_query;

	$link = '';

	if( class_exists('WCVendors_Pro') ){
		$dashboard_page_id = WCVendors_Pro::get_option( 'dashboard_page_id' );
	} else{
		$dashboard_page_id = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
	}


	if ( ! $dashboard_page_id ) {
		return $link;
	}

	$current_user = wp_get_current_user();
	$userdata     = get_userdata( $current_user->ID );

	$type   = get_query_var( 'object' );
	$action = get_query_var( 'action' );
	$id     = get_query_var( 'object_id' );

	if ( $dashboard_page_id == $post_ID ) {
		if ( $type == 'shop_coupon' ) {
			$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/vendor-dashboard/vendor-dashboard-coupons/' . $action . '/' . $id;
		} elseif ( $type == 'product' ) {
			$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/vendor-dashboard/vendor-dashboard-products/' . $action . '/' . $id;
		} else {
			$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $userdata->user_nicename . '/vendor-dashboard/';
		}
	}

	return $link;
}

function bp_wcv_no_admin_access() {
	global $current_user, $bp;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	$bp_wcv_options = bp_wcv_get_options();

	if ( isset( $bp_wcv_options['no_admin_access'] ) ) {
		return;
	}

	$user_roles = $current_user->roles;
	$user_role  = array_shift( $user_roles );
	if ( $user_role === 'vendor' ) {
		bp_core_redirect( get_option( 'home' ) . '/' . $bp->pages->members->slug . '/' . bp_core_get_username( bp_loggedin_user_id() ) . '/vendor-dashboard' );
	}
}

add_action( 'admin_init', 'bp_wcv_no_admin_access', 100 );

/**
 * Check if a subscriber have the needed rights to upload images and add this capabilities if needed.
 *
 * @package BuddyForms
 * @since 0.5 beta
 */
add_action( 'init', 'bp_wc_allow_vendor_uploads' );
function bp_wc_allow_vendor_uploads() {
	if ( current_user_can( 'vendor' ) && ! current_user_can( 'upload_files' ) ) {
		$contributor = get_role( 'vendor' );
		$contributor->add_cap( 'upload_files' );
	}
}

function bp_wcv_view() {
	return true;
}

function bp_wcv_get_options(){
	global $bp_wcv_options;

	if(is_array($bp_wcv_options)){
		return $bp_wcv_options;
	}

	$bp_wcv_options = get_option( 'bp_wcv_options' );

	$options =  Array();
	if( is_array( $bp_wcv_options ) ){
		foreach ( $bp_wcv_options as $key => $options_array ){
			if( is_array( $options_array ) ){
				foreach ( $options_array as $slug => $option ) {
					$options[ $slug ] = $option;
				}
			}
		}
	}
	$bp_wcv_options = $options;

	return $options;

}

add_filter( 'wc_get_template', 'bp_wcv_woocommerce_before_template_part', 10, 5 );
function bp_wcv_woocommerce_before_template_part($located, $template_name, $args, $template_path, $default_path ){

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	if( $template_name == 'links.php' || $template_name == 'quick-links.php' ){
		$template_path = BP_WCV_TEMPLATE_PATH . 'dashboard/';
		$located = wc_locate_template( $template_name, $template_path, $template_path );
	}

	return $located;
}


add_filter( 'buddyforms_members_parent_tab', 'bp_wcv_buddyforms_members_parent_tab', 10, 2);

function bp_wcv_buddyforms_members_parent_tab( $parent_tab_slug, $form_slug ){
	global $buddyforms;

//	$options = bp_wcv_get_options();

	if( isset( $buddyforms[$form_slug] ) ){
		if( isset( $buddyforms[$form_slug]['wc_vendor_integration'] ) ){
			$parent_tab_slug = 'vendor-dashboard';
		}
	}

	return $parent_tab_slug;

}



add_filter( 'buddyforms_members_parent_setup_nav', 'bp_wcv_buddyforms_members_parent_setup_nav', 10, 2);

function bp_wcv_buddyforms_members_parent_setup_nav( $parent, $form_slug ){
	global $buddyforms;

//	$options = bp_wcv_get_options();

	if( isset( $buddyforms[$form_slug] ) ){
		if( isset( $buddyforms[$form_slug]['wc_vendor_integration'] ) ){
			$parent = false;
		}
	}

	return $parent;

}