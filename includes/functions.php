<?php

add_filter( 'buddyforms_front_js_css_loader', 'bp_wc_vendors_front_js_css_loader', 10, 1 );
function bp_wc_vendors_front_js_css_loader( $fount ) {
	return true;
}

add_filter( 'wcv_dashboard_quick_links', 'bp_wc_vendors_dashboard_quick_links', 10, 1 );
function bp_wc_vendors_dashboard_quick_links( $quick_links ) {

	$bp_wc_vendors_options = bp_wc_vendors_get_options();

	$quick_links_new = Array();

	if ( ! isset( $bp_wc_vendors_options['tab_products_disabled'] ) ) {
		$quick_links_new['product'] = $quick_links['product'];
	}

	if ( ! isset( $bp_wc_vendors_options['tab_coupons_disabled'] ) ) {
		$quick_links_new['shop_coupon'] = $quick_links['shop_coupon'];
	}

	return $quick_links_new;
}

add_action( 'template_redirect', 'bp_wc_vendors_store_redirect_to_profile' );
function bp_wc_vendors_store_redirect_to_profile() {
	global $bp, $wp_query;

	$pagename = get_query_var( 'pagename' );

//	if( ! WCV_Vendors::is_vendor_page()){
//		return;
//	}

	if( ! in_array('shop_settings', $bp->unfiltered_uri ) ) {
		return;
	}

//	if ( get_query_var( 'pagename' ) != 'edit' ) {
//		return;
//	}

//	if(!in_array('edit', $bp->action_variables)){
//		return;
//	}

	$bp_wc_vendors_options = bp_wc_vendors_get_options();
	if ( ! isset( $bp_wc_vendors_options['redirect_vendor_store_to_profil'] ) && $bp_wc_vendors_options['redirect_vendor_store_to_profil'] == 'none' ) {
		return;
	}

	$vendor_shop = get_query_var( 'vendor_shop' );

	if ( $form_slug == 'none' ) {
		$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $vendor_shop . ' /';
		wp_safe_redirect( $link );
		exit;
	}

	$link = get_bloginfo( 'url' ) . '/' . $bp->pages->members->slug . '/' . $vendor_shop . ' /' . $form_slug . '/';
	wp_safe_redirect( $link );
	exit;
}


add_action( 'template_redirect', 'bp_wc_vendors_dashboard_redirect_to_profile' );
function bp_wc_vendors_dashboard_redirect_to_profile() {
	global $wp_query, $post;

	if ( ! isset( $post->ID ) || ! is_user_logged_in() ) {
		return false;
	}

	$user = wp_get_current_user();
	if ( ! in_array( 'vendor', (array) $user->roles ) ) {
		return false;
	}

	$link = bp_wc_vendors_get_redirect_link( $post->ID );

	if ( ! empty( $link ) ) :
		wp_safe_redirect( $link );
		exit;
	endif;
}

function bp_wc_vendors_get_redirect_link( $post_ID ) {
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

function bp_wc_vendors_no_admin_access() {
	global $current_user, $bp;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	$bp_wc_vendors_options = bp_wc_vendors_get_options();

	if ( isset( $bp_wc_vendors_options['no_admin_access'] ) ) {
		return;
	}

	$user_roles = $current_user->roles;
	$user_role  = array_shift( $user_roles );
	if ( $user_role === 'vendor' ) {
		bp_core_redirect( get_option( 'home' ) . '/' . $bp->pages->members->slug . '/' . bp_core_get_username( bp_loggedin_user_id() ) . '/vendor-dashboard' );
	}
}

add_action( 'admin_init', 'bp_wc_vendors_no_admin_access', 100 );

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

function bp_wc_vendors_view() {
	return true;
}

function bp_wc_vendors_get_options(){
	global $bp_wc_vendors_options;

	if(is_array($bp_wc_vendors_options)){
		return $bp_wc_vendors_options;
	}

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );

	$options =  Array();
	if( is_array( $bp_wc_vendors_options ) ){
		foreach ( $bp_wc_vendors_options as $key => $options_array ){
			if( is_array( $options_array ) ){
				foreach ( $options_array as $slug => $option ) {
					$options[ $slug ] = $option;
				}
			}
		}
	}
	$bp_wc_vendors_options = $options;

	return $options;

}

add_filter( 'wc_get_template', 'bp_wc_vendors_woocommerce_before_template_part', 10, 5 );
function bp_wc_vendors_woocommerce_before_template_part($located, $template_name, $args, $template_path, $default_path ){

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	if( $template_name == 'links.php' || $template_name == 'quick-links.php' ){
		$template_path = BP_WCV_TEMPLATE_PATH . 'dashboard/';
		$located = wc_locate_template( $template_name, $template_path, $template_path );
	}

	return $located;
}


add_filter( 'buddyforms_members_parent_tab', 'bp_wc_vendors_buddyforms_members_parent_tab', 10, 2);

function bp_wc_vendors_buddyforms_members_parent_tab( $parent_tab_slug, $form_slug ){
	global $buddyforms;

//	$options = bp_wc_vendors_get_options();

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

//	$options = bp_wc_vendors_get_options();

	if( isset( $buddyforms[$form_slug] ) ){
		if( isset( $buddyforms[$form_slug]['wc_vendor_integration'] ) ){
			$parent = false;
		}
	}

	return $parent;

}

add_filter( 'buddyforms_templates', 'bp_wcv_buddyforms_templates' );
add_filter( 'buddyforms_wizard_types', 'bp_wcv_buddyforms_templates' );

function bp_wcv_buddyforms_templates( $buddyforms_templates ){

	$buddyforms_templates['vendor']['title'] = 'Become a WC Vendor';
	$buddyforms_templates['vendor']['desc']  = 'Setup a "Become a Vendor" Registration Form.';

	return $buddyforms_templates;

}

add_filter( 'buddyforms_templates_json', 'bp_wcv_buddyforms_templates_json' );

function bp_wcv_buddyforms_templates_json($buddyform){

	if( isset( $_POST['template'] ) && $_POST['template'] == 'vendor' ){
		$buddyform = '{"form_fields":{"a40912e1a5":{"type":"user_login","slug":"user_login","name":"Username","description":"","required":["required"],"validation_error_message":"This field is required.","custom_class":""},"82abe39ed2":{"type":"user_email","slug":"user_email","name":"eMail","description":"","required":["required"],"validation_error_message":"This field is required.","custom_class":""},"611dc33cb2":{"type":"user_pass","slug":"user_pass","name":"Password","description":"","required":["required"],"validation_error_message":"This field is required.","custom_class":""},"636c12a746":{"type":"text","name":"Schop Name","description":"","validation_error_message":"This field is required.","validation_minlength":"0","validation_maxlength":"0","slug":"pv_shop_name","custom_class":""},"dfc114e960":{"type":"text","name":"PayPal E-mail (required)","description":"","required":["required"],"validation_error_message":"This field is required.","validation_minlength":"0","validation_maxlength":"0","slug":"pv_paypal","custom_class":""},"df44e14ace":{"type":"textarea","name":"Seller Info","description":"","validation_error_message":"This field is required.","validation_minlength":"0","validation_maxlength":"0","slug":"pv_seller_info","custom_class":""},"fce05b6cd3":{"type":"textarea","name":"Shop description","description":"","validation_error_message":"This field is required.","validation_minlength":"0","validation_maxlength":"0","slug":"pv_shop_description","custom_class":""}},"layout":{"cords":{"a40912e1a5":"1","82abe39ed2":"1","611dc33cb2":"1","636c12a746":"1","dfc114e960":"1","df44e14ace":"1","fce05b6cd3":"1"},"labels_layout":"inline","label_font_size":"","label_font_color":{"style":"auto","color":""},"label_font_style":"bold","desc_font_size":"","desc_font_color":{"color":""},"field_padding":"15","field_background_color":{"style":"auto","color":""},"field_border_color":{"style":"auto","color":""},"field_border_width":"","field_border_radius":"","field_font_size":"15","field_font_color":{"style":"auto","color":""},"field_placeholder_font_color":{"style":"auto","color":""},"field_active_background_color":{"style":"auto","color":""},"field_active_border_color":{"style":"auto","color":""},"field_active_font_color":{"style":"auto","color":""},"submit_text":"Submit","button_width":"blockmobile","button_alignment":"left","button_size":"large","button_class":"","button_border_radius":"","button_border_width":"","button_background_color":{"style":"auto","color":""},"button_font_color":{"style":"auto","color":""},"button_border_color":{"style":"auto","color":""},"button_background_color_hover":{"style":"auto","color":""},"button_font_color_hover":{"style":"auto","color":""},"button_border_color_hover":{"style":"auto","color":""},"custom_css":""},"form_type":"registration","after_submit":"display_page","after_submission_page":"33","after_submission_url":"","after_submit_message_text":"User Registration Successful! Please check your eMail Inbox and click the activation link to activate your account.","post_type":"bf_submissions","status":"publish","comment_status":"open","singular_name":"","attached_page":"none","edit_link":"all","list_posts_option":"list_all_form","list_posts_style":"list","public_submit":["public_submit"],"public_submit_login":"above","registration":{"activation_page":"33","activation_message_from_subject":"Vendor Account Activation Mail","activation_message_text":"Hi [user_login],\r\n\r\nGreat to see you come on board! Just one small step left to make your registration complete.\r\n<br>\r\n<b>Click the link below to activate your account.<\/b>\r\n<br>\r\n[activation_link]\r\n<br><br>\r\n[blog_title]","activation_message_from_name":"[blog_title]","activation_message_from_email":"dfg@dfg.fr","new_user_role":"vendor"},"profile_visibility":"any","name":"Become a Vendor","slug":"become-a-vendor"}';
	}

	return $buddyform;

}