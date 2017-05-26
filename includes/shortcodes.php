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