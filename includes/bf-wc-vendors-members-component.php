<?php

class BuddyForms_WC_Vendors_Component extends BP_Component{

  public $id = 'bf_wc_vendors';

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
			__( 'Vendor Dashboard', 'buddyforms' )
		);

	}

	/**
     * Setup globals
     *
     * @since     Marketplace 0.9
     * @global    object $bp The one true BuddyPress instance
     */
    public function setup_globals($args = Array()) {

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

		if(!bp_is_user())
			return;

		$user = wp_get_current_user();

    if ( ! in_array( 'vendor', (array) $user->roles ) )
      return;

    //$bf_wc_vendors_options = get_option('bf_wc_vendors_options');

		$main_nav = array(
					'name' => 'Vendor Dashboard',
					'slug' => $this->slug,
					'position' => 71,
					'default_subnav_slug' => 'vendor-dashboard',
          'screen_function'     => array( $this, 'bf_wc_vendors_screen_settings' ),
          'show_for_displayed_user'       => false,
          'user_has_access' => bp_is_my_profile()
			);

      $sub_nav[] = array(
  				'name' => 'Dashboard',
  				'slug' => 'vendor-dashboard',
  				'parent_slug' => 'vendor-dashboard',
  				'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
  				'item_css_id' => 'vendor-dashboard',
  				'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
  		);

      $sub_nav[] = array(
  				'name' => 'Products',
  				'slug' => 'vendor-dashboard-products',
  				'parent_slug' => 'vendor-dashboard',
  				'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
  				'item_css_id' => 'vendor-dashboard',
  				'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
  		);

      $sub_nav[] = array(
          'name' => 'Orders',
          'slug' => 'vendor-dashboard-orders',
          'parent_slug' => 'vendor-dashboard',
          'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
          'item_css_id' => 'vendor-dashboard',
          'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
      );

      $sub_nav[] = array(
  				'name' => 'Settings',
  				'slug' => 'vendor-dashboard-settings',
  				'parent_slug' => 'vendor-dashboard',
  				'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
  				'item_css_id' => 'vendor-dashboard',
  				'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
  		);

      $sub_nav[] = array(
          'name' => 'Ratings',
          'slug' => 'vendor-dashboard-ratings',
          'parent_slug' => 'vendor-dashboard',
          'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
          'item_css_id' => 'vendor-dashboard',
          'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
      );

      $sub_nav[] = array(
          'name' => 'Coupongs',
          'slug' => 'vendor-dashboard-coupons',
          'parent_slug' => 'vendor-dashboard',
          'parent_url' => bp_displayed_user_domain() . 'vendor-dashboard/',
          'item_css_id' => 'vendor-dashboard',
          'screen_function' => array($this, 'bf_wc_vendors_screen_settings'),
          'user_has_access' => bp_is_my_profile()
      );


	   parent::setup_nav($main_nav, $sub_nav);

	}

  public function bf_wc_vendors_screen_settings() {

    $bf_wc_wcvendors_pro_public = new BF_WCVendors_Pro_Public('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_wcvendors_pro_public->enqueue_styles();
    $bf_wc_wcvendors_pro_public->enqueue_scripts();

    switch (bp_current_action()) {
      case 'vendor-dashboard-products':
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_products' ) );
        break;
      case 'vendor-dashboard-orders':
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_orders' ) );
        break;
      case 'vendor-dashboard-settings':
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_settings' ) );
        break;
      case 'vendor-dashboard-ratings':
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_ratings' ) );
        break;
      case 'vendor-dashboard-coupons':
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_coupons' ) );
        break;
      default:
        add_action( 'bp_template_content', array( $this, 'bf_wc_vendors_dashboard' ) );
        break;
    }
    bp_core_load_template( 'members/single/plugins' );
  }

  public function bf_wc_vendors_products() {

    echo  do_shortcode('[buddyforms_the_loop form_slug="product"]');

  }
  public function bf_wc_vendors_orders() {

    $bf_wc_vendors_dashboard = new BF_WCVendors_Pro_Dashboard('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_vendors_dashboard->load_order_page();

  }
  public function bf_wc_vendors_settings() {

    $bf_wc_vendors_dashboard = new BF_WCVendors_Pro_Dashboard('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_vendors_dashboard->load_settings_page();

  }
  public function bf_wc_vendors_ratings() {

    $bf_wc_vendors_dashboard = new BF_WCVendors_Pro_Dashboard('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_vendors_dashboard->load_rating_page();

  }
  public function bf_wc_vendors_coupons() {
    global $bp;



    set_query_var('object', 'shop_coupon');

    if(isset($bp->action_variables[0]))
      set_query_var('action', $bp->action_variables[0]);

    if(isset($bp->action_variables[1]))
      set_query_var('object_id', $bp->action_variables[1]);

    $type 		= get_query_var( 'object' );
    $action 	= get_query_var( 'action' );
    $id 		  = get_query_var( 'object_id' );

    $bf_wc_vendors_dashboard = new BF_WCVendors_Pro_Dashboard('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_vendors_dashboard->load_page( $type, $action, $id );


  }
  public function bf_wc_vendors_dashboard() {

    $bf_wc_vendors_dashboard = new BF_WCVendors_Pro_Dashboard('wcvendors-pro',WCV_PRO_VERSION, false);
    $bf_wc_vendors_dashboard->load_page('dashboard');

  }

}

function bf_wc_vendors_register_member_types() {
    bp_register_member_type( 'vendor', array(
        'labels' => array(
            'name'          => 'Vendors',
            'singular_name' => 'Vendor',
        ),
    ) );
}
add_action( 'bp_init', 'bf_wc_vendors_register_member_types' );

function bf_wc_vendors_register_member_types_with_directory() {
    bp_register_member_type( 'vendor', array(
        'labels' => array(
            'name'          => 'Vendors',
            'singular_name' => 'Vendor',
        ),
        'has_directory' => 'vendors'
    ) );
}
add_action( 'bp_register_member_types', 'bf_wc_vendors_register_member_types_with_directory' );

add_action( 'set_user_role', function( $user_id, $role, $old_roles ) {

  if($role == 'vendor'){
      bp_set_member_type($user_id, 'vendor');
  }

  if($role != 'vendor'){
    bp_remove_member_type($user_id, 'vendor');
  }

}, 10, 3 );



class BF_WCVendors_Pro_Dashboard extends WCVendors_Pro_Dashboard{
	public function create_nav( ) {
    echo '';
  }
}
