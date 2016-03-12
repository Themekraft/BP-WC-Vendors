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

function bf_wc_vendors_no_admin_access() {
  global $current_user, $bp;

  $bf_wc_vendors_options = get_option('bf_wc_vendors_options');

  if(isset($bf_wc_vendors_options['no_admin_access']))
    return;

   $user_roles = $current_user->roles;
   $user_role = array_shift($user_roles);
   if($user_role === 'vendor'){
     bp_core_redirect( get_option('home') . '/' . $bp->pages->members->slug . '/' . bp_core_get_username( bp_loggedin_user_id() ) . '/vendor-dashboard' );
   }
}
add_action( 'admin_init', 'bf_wc_vendors_no_admin_access', 100 );
