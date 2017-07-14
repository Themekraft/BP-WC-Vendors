<?php
add_action( 'template_redirect', 'bp_wcv_store_redirect_to_profile', 10 );
function bp_wcv_store_redirect_to_profile() {
	global $bp, $wp_query, $post;

	$pagename = get_query_var( 'pagename' );

	if( bp_current_component() == $pagename){
		return;
	}

	if( bp_current_action() == $pagename){
		return;
	}

	if( ! class_exists('WCV_Vendors') ){
		return;
	}

	if( is_user_logged_in() && WCV_Vendors::is_vendor( get_current_user_id() ) ) {

		if ( class_exists( 'WCVendors_Pro' ) ) {
			$dashboard_page_id = $pro_dashboard = WCVendors_Pro::get_option( 'dashboard_page_id' );
			$free_dashboard = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
		} else {
			$dashboard_page_id = $free_dashboard = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
		}

		//	if( ! WCV_Vendors::is_vendor_page()){
		//		return;
		//	}

		//	if( ! in_array('shop_settings', $bp->unfiltered_uri ) ) {
		//		return;
		//	}

		//	if ( get_query_var( 'pagename' ) != 'edit' ) {
		//		return;
		//	}

		//	if(!in_array('edit', $bp->action_variables)){
		//		return;
		//	}

		$bp_wcv_options = bp_wcv_get_options();
		if ( isset( $bp_wcv_options['redirect_vendor_store_to_profil'] ) && $bp_wcv_options['redirect_vendor_store_to_profil'] == false) {
			return;
		}

		$vendor_shop = get_query_var( 'vendor_shop' );


//	if ( $form_slug == 'none' ) {
//		$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $vendor_shop . ' /';
//		wp_safe_redirect( $link );
//		exit;
//	}

		if ( empty($vendor_shop) && get_the_ID() == $dashboard_page_id
		     || empty($vendor_shop) && get_the_ID() == $pro_dashboard
		     || empty($vendor_shop) && get_the_ID() == $free_dashboard
		) {
			$link = bp_wcv_get_redirect_link( $dashboard_page_id );
			wp_safe_redirect( $link );
			exit;
		}
	}

	// Not a Vendor Dashboard Page Let us Check if a Vendor Product and get the redirect link if needed.

	if( ! isset($post->post_author) ){
		return;
	}


	if( ! WCV_Vendors::is_vendor( $post->post_author ) ){
		return;
	}

	$link = bp_wcv_get_redirect_link( $post->ID );

	if ( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}
