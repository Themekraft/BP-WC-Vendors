<?php
add_shortcode( 'bp_wcv_bav', 'bp_wcv_bav' );
function bp_wcv_bav(){
	return wc_get_template( 'denied.php', array(), 'wc-vendors/dashboard/', wcv_plugin_dir . 'templates/dashboard/' );
}

add_shortcode( 'bp_wcv_bav_or_register', 'bp_wcv_bav_if_logged_in_or_reg' );
function bp_wcv_bav_if_logged_in_or_reg( $args ){

	extract( shortcode_atts( array(
		'form_slug'   => 'fom_slug',
	), $args ) );

	if( is_user_logged_in() ){
		bp_wcv_bav();
	} else {
		return do_shortcode('[bf form_slug="' . $form_slug . '"]');
	}

}

add_action('template_redirect','bp_wcv_bav_if_logged_in_or_reg_process_shortcode',1);
function bp_wcv_bav_if_logged_in_or_reg_process_shortcode() {
  if (!is_singular()) return;
  global $post;
  if (!empty($post->post_content)) {
	  $regex = get_shortcode_regex();
	  preg_match_all('/'.$regex.'/',$post->post_content,$matches);
	  if (!empty($matches[2]) && in_array('bp_wcv_bav_or_register',$matches[2]) && is_user_logged_in()) {
		  if( WCV_Vendors::is_vendor( get_current_user_id() ) ){

			  if( class_exists('WCVendors_Pro') ){
				  $dashboard_page_id = WCVendors_Pro::get_option( 'dashboard_page_id' );
			  } else{
				  $dashboard_page_id = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
			  }

			  wp_redirect( get_permalink($dashboard_page_id) );
			  return;
		  }
	  }
  }
}