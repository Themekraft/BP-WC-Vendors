<?php

function bp_wcv_buddyforms_members_admin_settings_sidebar_metabox() {
	add_meta_box( 'bp_wcv_buddyforms_buddyforms', __( "BP Member Profiles", 'bpwcv' ), 'bp_wcv_buddyforms_members_admin_settings_sidebar_metabox_html', 'buddyforms', 'normal', 'low' );
	add_filter( 'postbox_classes_buddyforms_bp_wcv_buddyforms_buddyforms', 'buddyforms_metabox_class' );
	add_filter( 'postbox_classes_buddyforms_bp_wcv_buddyforms_buddyforms', 'buddyforms_metabox_hide_if_form_type_register' );
	add_filter( 'postbox_classes_buddyforms_bp_wcv_buddyforms_buddyforms', 'buddyforms_metabox_show_if_attached_page' );
}


function bp_wcv_buddyforms_members_admin_settings_sidebar_metabox_html() {
	global $post;

	if ( $post->post_type != 'buddyforms' ) {
		return;
	}

	$buddyform = get_post_meta( get_the_ID(), '_buddyforms_options', true );

	$form_setup = array();

	$wc_vendor_integration = '';
	if ( isset( $buddyform['wc_vendor_integration'] ) ) {
		$wc_vendor_integration = $buddyform['wc_vendor_integration'];
	}

	$form_setup[] = new Element_Checkbox( "<b>" . __( 'Add this form as Vendor Dashboard Tab', 'bpwcv' ) . "</b>", "buddyforms_options[wc_vendor_integration]", array( "integrate" => "Integrate this Form" ), array( 'value' => $wc_vendor_integration, 'shortDesc' => __( 'Add this form to the Vendors Dashboard', 'buddyforms' )
	) );
	buddyforms_display_field_group_table( $form_setup );

}

add_filter( 'add_meta_boxes', 'bp_wcv_buddyforms_members_admin_settings_sidebar_metabox' );
