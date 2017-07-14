<?php

class BuddyForms_WC_Vendors_Component extends BP_Component {

	public $id = 'bp_wcv';

	/**
	 * Initiate the class
	 *
	 * @package wc4bp
	 * @since 0.1 beta
	 */
	public function __construct() {
		global $bp;

		parent::start(
		// Unique component ID
			$this->id,
			// Used by BP when listing components (eg in the Dashboard)
			__( 'Vendor Dashboard', 'bpwcv' )
		);

	}

	/**
	 * Setup globals
	 *
	 * @since     Marketplace 0.9
	 * @global    object $bp The one true BuddyPress instance
	 */
	public function setup_globals( $args = Array() ) {

		$globals = array(
			'slug'          => 'vendor-dashboard',
			'has_directory' => false
		);

		parent::setup_globals( $globals );
	}

	/**
	 * Setup profile navigation
	 *
	 * @package wc4bp
	 * @since 0.1 beta
	 */
	public function setup_nav( $main_nav = Array(), $sub_nav = Array() ) {
		global $bp, $wp_admin_bar, $current_user;

		if ( ! bp_is_user() ) {
			return;
		}

		$user = wp_get_current_user();

		if ( ! in_array( 'vendor', (array) $user->roles ) ) {
			return;
		}

		$bp_wcv_options = bp_wcv_get_options();

		if( class_exists('WCVendors_Pro') ){
			$dashboard_page_id = WCVendors_Pro::get_option( 'dashboard_page_id' );
		} else{
			$dashboard_page_id = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
		}

		$dashboard_page_title = get_the_title( $dashboard_page_id );

		$main_nav = array(
			'name'                    => $dashboard_page_title,
			'slug'                    => $this->slug,
			'position'                => 71,
			'default_subnav_slug'     => 'vendor-dashboard',
			'screen_function'         => array( $this, 'bp_wcv_screen_settings' ),
			'show_for_displayed_user' => false,
			'user_has_access'         => bp_is_my_profile()
		);

		$parent_slug = 'vendor-dashboard';

		$sub_nav[] = array(
			'name'            => $dashboard_page_title,
			'slug'            => 'vendor-dashboard',
			'class'           => 'test',
			'parent_slug'     => $parent_slug,
			'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
			'item_css_id'     => 'vendor-dashboard',
			'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
			'user_has_access' => bp_is_my_profile()
		);

		if ( bp_wcv_fs()->is_plan( 'professional' ) ) {
			if ( ! isset( $bp_wcv_options['tab_products_disabled'] ) && defined( 'WCV_PRO_VERSION' ) ) {

				$sub_nav[] = array(
					'name'            => __( 'Products', 'bpwcv' ),
					'slug'            => 'vendor-dashboard-products',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}
		if ( bp_wcv_fs()->is_plan( 'professional' ) ) {
			if ( ! isset( $bp_wcv_options['tab_orders_disabled'] ) && defined( 'WCV_PRO_VERSION') ) {
				$sub_nav[] = array(
					'name'            => __( 'Orders', 'bpwcv' ),
					'slug'            => 'vendor-dashboard-orders',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}

		if ( ! isset( $bp_wcv_options['tab_settings_disabled'] ) ) {
			$sub_nav[] = array(
				'name'            => __( 'Settings', 'bpwcv' ),
				'slug'            => 'vendor-dashboard-settings',
				'parent_slug'     => $parent_slug,
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
				'item_css_id'     => 'vendor-dashboard',
				'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
				'user_has_access' => bp_is_my_profile()
			);
		}

		if ( bp_wcv_fs()->is_plan( 'professional' ) ) {
			if ( ! isset( $bp_wcv_options['tab_ratings_disabled'] ) && defined( 'WCV_PRO_VERSION') ) {
				$sub_nav[] = array(
					'name'            => __( 'Ratings', 'bpwcv' ),
					'slug'            => 'vendor-dashboard-ratings',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}
		if ( bp_wcv_fs()->is_plan( 'professional' ) && defined( 'WCV_PRO_VERSION' ) ) {
			if ( ! isset( $bp_wcv_options['tab_coupons_disabled'] ) ) {
				$sub_nav[] = array(
					'name'            => __( 'Coupons', 'bpwcv' ),
					'slug'            => 'vendor-dashboard-coupons',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wcv_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}
		parent::setup_nav( $main_nav, $sub_nav );

	}

	public function bp_wcv_screen_settings() {

		add_filter( 'wcv_view_dashboard', 'bp_wcv_view' );
		add_filter( 'wcv_view_feedback', 'bp_wcv_view' );

		switch ( bp_current_action() ) {
			case 'vendor-dashboard-products':
				add_action( 'bp_template_content', array( $this, 'bp_wcv_products' ) );
				break;
			case 'vendor-dashboard-orders':
				add_action( 'bp_template_content', array( $this, 'bp_wcv_orders' ) );
				break;
			case 'vendor-dashboard-settings':
				add_action( 'bp_template_content', array( $this, 'bp_wcv_settings' ) );
				break;
			case 'vendor-dashboard-ratings':
				add_action( 'bp_template_content', array( $this, 'bp_wcv_ratings' ) );
				break;
			case 'vendor-dashboard-coupons':
				add_action( 'bp_template_content', array( $this, 'bp_wcv_coupons' ) );
				break;
			default:
				add_action( 'bp_template_content', array( $this, 'bp_wcv_dashboard' ) );
				break;
		}
		bp_core_load_template( 'members/single/plugins' );
	}

	public function bp_wcv_products() {
		global $bp;

		set_query_var( 'object', 'product', 'action', 'object_id');

		if ( isset( $bp->action_variables[0] ) && $bp->action_variables[0] != 'page' ) {
			set_query_var( 'action', $bp->action_variables[0] );
		}

		if ( isset( $bp->action_variables[1] ) && $bp->action_variables[0] != 'page' ) {
			set_query_var( 'object_id', $bp->action_variables[1] );
		}

		if ( isset( $bp->action_variables[1] ) && $bp->action_variables[0] == 'page' ) {
			set_query_var( 'paged', $bp->action_variables[1] );
		}

		$type   = get_query_var( 'object' );
		$action = get_query_var( 'action' );
		$id     = get_query_var( 'object_id' );




		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wcv_dashboard->load_page( $type, $action, $id );
		} else {

		}


		// bp_wcv_locate_template('buddyforms/members/members-post-display.php');
		// bp_wcv_locate_template('buddyforms/members/members-post-create.php');
		// echo do_shortcode('[buddyforms_the_loop form_slug="product"]');
	}

	public function bp_wcv_orders() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wcv_dashboard->load_order_page();
		} else {
			echo do_shortcode( '[wcv_orders]' );
		}



	}

	public function bp_wcv_settings() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wcv_dashboard->load_settings_page();
		} else {
			echo do_shortcode( '[wcv_shop_settings]' );
		}

	}

	public function bp_wcv_ratings() {

		$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
		$bp_wcv_dashboard->load_rating_page();

	}

	public function bp_wcv_coupons() {
		global $bp;

		set_query_var( 'object', 'shop_coupon' );

		if ( isset( $bp->action_variables[0] ) ) {
			set_query_var( 'action', $bp->action_variables[0] );
		}

		if ( isset( $bp->action_variables[1] ) ) {
			set_query_var( 'object_id', $bp->action_variables[1] );
		}

		$type   = get_query_var( 'object' );
		$action = get_query_var( 'action' );
		$id     = get_query_var( 'object_id' );

		$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
		$bp_wcv_dashboard->load_page( $type, $action, $id );


	}

	public function bp_wcv_dashboard() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wcv_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wcv_dashboard->load_page( 'dashboard' );
		} else {
			echo do_shortcode( '[wcv_vendor_dashboard]' );
		}

	}

}

function bp_wcv_register_member_type_vendor() {

	$bp_wcv_options = get_option( 'bp_wcv_options' );

	if(  isset( $bp_wcv_options['roles']['vendor'] ) ){

		$args = array(
			'labels'        => array(
				'name'          => isset( $bp_wcv_options['roles']['vendor_member_type_name'] ) ? $bp_wcv_options['roles']['vendor_name'] : __( 'Vendors', 'bpwcv' ),
				'singular_name' => isset( $bp_wcv_options['roles']['vendor_name_singular'] ) ? $bp_wcv_options['roles']['vendor_name_singular'] : __( 'Vendor', 'bpwcv' ),
			)
		);

		if(  isset( $bp_wcv_options['roles']['vendor_'] ) ){
			$args['has_directory'] = apply_filters('bp_wcv_member_types_directory_slug', 'vendors');
		}

		bp_register_member_type( 'vendor', $args );
	}

}

add_action( 'bp_register_member_types', 'bp_wcv_register_member_type_vendor' );

add_action( 'set_user_role', function ( $user_id, $role, $old_roles ) {

	$bp_wcv_options = get_option( 'bp_wcv_options' );

	if( ! isset( $bp_wcv_options['roles']['vendor'] ) ){
		return;
	}

	if ( $role == 'vendor' ) {
		bp_set_member_type( $user_id, 'vendor' );
	}

	if ( $role != 'vendor' ) {
		bp_remove_member_type( $user_id, 'vendor' );
	}

}, 10, 3 );

add_action( 'bp_set_member_type', 'bp_wcv_bp_set_member_type', 10, 3 );
function bp_wcv_bp_set_member_type( $user_id, $member_type, $append ){

	$bp_wcv_options = get_option( 'bp_wcv_options' );

	if( isset( $bp_wcv_options['roles']['member_types'][$member_type] ) || $member_type == 'vendor' ){
		$u = new WP_User( $user_id );
		$u->add_role( 'vendor' );
	} else {
		$u = new WP_User( $user_id );
		$u->remove_role( 'vendor' );
	}

}

if ( class_exists( 'WCVendors_Pro_Dashboard' ) ) {
	class BP_WCVendors_Pro_Dashboard extends WCVendors_Pro_Dashboard {
		public function create_nav() {
			echo '';
		}
	}
}

/**
 * Locate a template
 *
 * @package BuddyForms
 * @since 0.1 beta
 */
function bp_wcv_locate_template( $file ) {
	if ( locate_template( array( $file ), false ) ) {
		locate_template( array( $file ), true );
	} else {
		include( BP_WCV_TEMPLATE_PATH . $file );
	}
}
