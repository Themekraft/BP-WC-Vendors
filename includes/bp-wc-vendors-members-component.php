<?php

class BuddyForms_WC_Vendors_Component extends BP_Component {

	public $id = 'bp_wc_vendors';

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
			__( 'Vendor Dashboard', 'wcvendors' )
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

		$bp_wc_vendors_options = bp_wc_vendors_get_options();

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
			'screen_function'         => array( $this, 'bp_wc_vendors_screen_settings' ),
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
			'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
			'user_has_access' => bp_is_my_profile()
		);

		if ( bp_wc_vendors_fs()->is_plan('professional', true) ) {
			if ( ! isset( $bp_wc_vendors_options['tab_products_disabled'] ) && defined( 'WCV_PRO_VERSION' ) ) {

				$sub_nav[] = array(
					'name'            => __( 'Products', 'wcvendors' ),
					'slug'            => 'vendor-dashboard-products',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}

		if ( ! isset( $bp_wc_vendors_options['tab_orders_disabled'] ) ) {
			$sub_nav[] = array(
				'name'            => __( 'Orders', 'wcvendors' ),
				'slug'            => 'vendor-dashboard-orders',
				'parent_slug'     => $parent_slug,
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
				'item_css_id'     => 'vendor-dashboard',
				'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
				'user_has_access' => bp_is_my_profile()
			);
		}

		if ( ! isset( $bp_wc_vendors_options['tab_settings_disabled'] ) ) {
			$sub_nav[] = array(
				'name'            => __( 'Settings', 'wcvendors' ),
				'slug'            => 'vendor-dashboard-settings',
				'parent_slug'     => $parent_slug,
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
				'item_css_id'     => 'vendor-dashboard',
				'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
				'user_has_access' => bp_is_my_profile()
			);
		}

		if ( bp_wc_vendors_fs()->is_plan('professional', true) ) {
			if ( ! isset( $bp_wc_vendors_options['tab_ratings_disabled'] ) && defined( 'WCV_PRO_VERSION') ) {
				$sub_nav[] = array(
					'name'            => __( 'Ratings', 'wcvendors' ),
					'slug'            => 'vendor-dashboard-ratings',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}
		if ( bp_wc_vendors_fs()->is_plan('professional', true) && defined( 'WCV_PRO_VERSION' ) ) {
			if ( ! isset( $bp_wc_vendors_options['tab_coupons_disabled'] ) ) {
				$sub_nav[] = array(
					'name'            => __( 'Coupons', 'wcvendors' ),
					'slug'            => 'vendor-dashboard-coupons',
					'parent_slug'     => $parent_slug,
					'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $parent_slug ),
					'item_css_id'     => 'vendor-dashboard',
					'screen_function' => array( $this, 'bp_wc_vendors_screen_settings' ),
					'user_has_access' => bp_is_my_profile()
				);
			}
		}
		parent::setup_nav( $main_nav, $sub_nav );

//		foreach ( $sub_nav as $nav ){
//			bp_core_new_subnav_item($nav);
//		}

	}

	public function bp_wc_vendors_screen_settings() {

		add_filter( 'wcv_view_dashboard', 'bp_wc_vendors_view' );
		add_filter( 'wcv_view_feedback', 'bp_wc_vendors_view' );

		switch ( bp_current_action() ) {
			case 'vendor-dashboard-products':
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_products' ) );
				break;
			case 'vendor-dashboard-orders':
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_orders' ) );
				break;
			case 'vendor-dashboard-settings':
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_settings' ) );
				break;
			case 'vendor-dashboard-ratings':
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_ratings' ) );
				break;
			case 'vendor-dashboard-coupons':
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_coupons' ) );
				break;
			default:
				add_action( 'bp_template_content', array( $this, 'bp_wc_vendors_dashboard' ) );
				break;
		}
		bp_core_load_template( 'members/single/plugins' );
	}

	public function bp_wc_vendors_products() {
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
			$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wc_vendors_dashboard->load_page( $type, $action, $id );
		} else {

		}


		// bp_wc_vendors_locate_template('buddyforms/members/members-post-display.php');
		// bp_wc_vendors_locate_template('buddyforms/members/members-post-create.php');
		// echo do_shortcode('[buddyforms_the_loop form_slug="product"]');
	}

	public function bp_wc_vendors_orders() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wc_vendors_dashboard->load_order_page();
		} else {
			echo do_shortcode( '[wcv_orders]' );
		}



	}

	public function bp_wc_vendors_settings() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wc_vendors_dashboard->load_settings_page();
		} else {
			echo do_shortcode( '[wcv_shop_settings]' );
		}

	}

	public function bp_wc_vendors_ratings() {

		$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
		$bp_wc_vendors_dashboard->load_rating_page();

	}

	public function bp_wc_vendors_coupons() {
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

		$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
		$bp_wc_vendors_dashboard->load_page( $type, $action, $id );


	}

	public function bp_wc_vendors_dashboard() {

		if( class_exists('BP_WCVendors_Pro_Dashboard' ) ){
			$bp_wc_vendors_dashboard = new BP_WCVendors_Pro_Dashboard( 'wcvendors-pro', WCV_PRO_VERSION, false );
			$bp_wc_vendors_dashboard->load_page( 'dashboard' );
		} else {
			echo do_shortcode( '[wcv_vendor_dashboard]' );
		}

	}

}

function bp_wc_vendors_register_member_types() {
	bp_register_member_type( 'vendor', array(
		'labels' => array(
			'name'          => 'Vendors',
			'singular_name' => 'Vendor',
		),
	) );
}

add_action( 'bp_init', 'bp_wc_vendors_register_member_types' );

function bp_wc_vendors_register_member_types_with_directory() {
	bp_register_member_type( 'vendor', array(
		'labels'        => array(
			'name'          => 'Vendors',
			'singular_name' => 'Vendor',
		),
		'has_directory' => 'vendors'
	) );
}

add_action( 'bp_register_member_types', 'bp_wc_vendors_register_member_types_with_directory' );

add_action( 'set_user_role', function ( $user_id, $role, $old_roles ) {

	if ( $role == 'vendor' ) {
		bp_set_member_type( $user_id, 'vendor' );
	}

	if ( $role != 'vendor' ) {
		bp_remove_member_type( $user_id, 'vendor' );
	}

}, 10, 3 );

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
function bp_wc_vendors_locate_template( $file ) {
	if ( locate_template( array( $file ), false ) ) {
		locate_template( array( $file ), true );
	} else {
		include( BP_WCV_TEMPLATE_PATH . $file );
	}
}
