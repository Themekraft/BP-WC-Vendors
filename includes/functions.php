<?php

add_filter('buddyforms_front_js_css_loader', 'bf_wc_vendors_front_js_css_loader', 10, 1 );
function bf_wc_vendors_front_js_css_loader($fount){
    return true;
}

add_filter('wcv_dashboard_quick_links', 'bf_wc_vendors_dashboard_quick_links', 1,1);
function bf_wc_vendors_dashboard_quick_links($quick_links){
  return array();
}

add_action( 'template_redirect', 'bf_wc_vendors_redirect_to_profile' );
function  bf_wc_vendors_redirect_to_profile() {
	global $post;

	if( ! isset( $post->ID ) || ! is_user_logged_in() )
		return false;

	$link =  bf_wc_vendors_get_redirect_link( $post->ID );

	if( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}



function bf_wc_vendors_get_redirect_link( $post_ID ) {
  global $bp, $current_user;

  $dashboard_page_id 		= WCVendors_Pro::get_option( 'dashboard_page_id' );
  $current_user = wp_get_current_user();
  $userdata     = get_userdata($current_user->ID);
  $link = '';

  $type 		= get_query_var( 'object' );
  $action 	= get_query_var( 'action' );
  $id 		  = get_query_var( 'object_id' );

  if ( $dashboard_page_id == $post_ID  ) {

    if($type == 'shop_coupon'){
      $link = get_bloginfo('url') . '/'.$bp->pages->members->slug.'/'. $userdata->user_nicename .'/vendor-dashboard/vendor-dashboard-coupons/' . $action . '/' . $id ;
    } else {
      $link = get_bloginfo('url') . '/'.$bp->pages->members->slug.'/'. $userdata->user_nicename .'/vendor-dashboard/';
    }

  }

  return $link;
}
