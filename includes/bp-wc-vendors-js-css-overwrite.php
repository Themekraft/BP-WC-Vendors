<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class BP_WCVendors_Pro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wcvendors_pro    The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Is the plugin in debug mode
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool    $debug    plugin is in debug mode
	 */
	private $debug;

	/**
	 * Script suffix for debugging
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $suffix    script suffix for including minified file versions
	 */
	private $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wcvendors_pro       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      bool      $debug    Plugin is in debug mode
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug;
		$this->base_dir			= plugin_dir_url( __FILE__ );
		$this->suffix		 	= $this->debug ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @todo 	 check if any of the styles are already loaded before enqueing them
	 */
	public function enqueue_styles() {

		// wp_enqueue_style( $this->wcvendors_pro, $this->base_dir . 'assets/css/wcvendors-pro-public'.$this->suffix.'.css', array(), $this->version, 'all' );

		$current_page_id = get_the_ID();

		$dashboard_page_id 	= WCVendors_Pro::get_option( 'dashboard_page_id' );
		$feedback_page_id 	= WCVendors_Pro::get_option( 'feedback_page_id' );

		//if ( $current_page_id == $dashboard_page_id ) {

			if ( is_user_logged_in() ) {

				// Dashboard Style
				wp_enqueue_style( 'wcv-pro-dashboard', apply_filters( 'wcv_pro_dashboard_style' , $this->base_dir . 'assets/css/dashboard' . $this->suffix . '.css' ), false, '1.0.0' );

			}

		//}

		// Store Style
		if ( is_woocommerce() || is_product() ) {
			wp_enqueue_style( 'wcv-pro-store-style', apply_filters( 'wcv_pro_store_style', $this->base_dir . 'assets/css/store' . $this->suffix . '.css' ), false, '1.0.0' );
		}

		//if ( ( $current_page_id == $dashboard_page_id ) || ( $current_page_id == $feedback_page_id ) )  {

			// Ink system
			wp_enqueue_style( 'wcv-ink', 	apply_filters( 'wcv_pro_ink_style', $this->base_dir . 'assets/lib/ink-3.1.10/dist/css/ink.min.css' ), array(), '3.1.10', 'all' );

			//Select2 3.5.4
			wp_enqueue_style( 'select2-css', 	$this->base_dir . '../includes/assets/css/select2' . $this->suffix . '.css', array(), '4.3.0', 'all' );

		//}

		//font awesome
		wp_enqueue_style( 'font-awesome', 	$this->base_dir . '../includes/assets/lib/font-awesome-4.3.0/css/font-awesome.min.css', array(), '4.3.0', 'all' );


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->wcvendors_pro, $this->base_dir . 'assets/js/wcvendors-pro-public'.$this->suffix.'.js', array( 'jquery' ), $this->version, true );

		$current_page_id = get_the_ID();

		$dashboard_page_id = WCVendors_Pro::get_option( 'dashboard_page_id' );

		//if ( $current_page_id == $dashboard_page_id ) {

			if ( is_user_logged_in() ) {

				wp_enqueue_media();

				$localize_search_args = array(
					'i18n_matches_1'            => __( 'One result is available, press enter to select it.', 'enhanced select', 'wcvendors-pro' ),
					'i18n_matches_n'            => __( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'wcvendors-pro' ),
					'i18n_no_matches'           => __( 'No matches found', 'enhanced select', 'wcvendors-pro' ),
					'i18n_ajax_error'           => __( 'Loading failed', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_short_1'    => __( 'Please enter 1 or more characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_short_n'    => __( 'Please enter %qty% or more characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_long_1'     => __( 'Please delete 1 character', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_long_n'     => __( 'Please delete %qty% characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_selection_too_long_1' => __( 'You can only select 1 item', 'enhanced select', 'wcvendors-pro' ),
					'i18n_selection_too_long_n' => __( 'You can only select %qty% items', 'enhanced select', 'wcvendors-pro' ),
					'i18n_load_more'            => __( 'Loading more results&hellip;', 'enhanced select', 'wcvendors-pro' ),
					'i18n_searching'            => __( 'Searching&hellip;', 'enhanced select', 'wcvendors-pro' ),
					'ajax_url'                  => admin_url( 'admin-ajax.php' ),
					'nonce'						=> wp_create_nonce('wcv-search'),
				);

				// ChartJS 1.0.2
				wp_register_script( 'chartjs', 				$this->base_dir . 'assets/lib/chartjs/Chart' . $this->suffix	 . '.js', array( 'jquery' ), '1.0.2', true );
				wp_enqueue_script( 'chartjs');

				// WCV chart init
				wp_register_script( 'wcvendors-pro-charts', $this->base_dir . 'assets/js/wcvendors-pro-charts'.$this->suffix.'.js', array( 'chartjs' ), $this->version, true );
				wp_enqueue_script( 'wcvendors-pro-charts');

				// Select 2 (3.5.2 branch)
				wp_register_script( 'select2', 				$this->base_dir . '../includes/assets/lib/select2/select2' . $this->suffix	 . '.js', array( 'jquery' ), '3.5.2', true );
				wp_enqueue_script( 'select2');

				wp_register_script( 'ink-js', 				$this->base_dir . 'assets/lib/ink-3.1.10/dist/js/ink-all' . $this->suffix	 . '.js', array(), '1.11.4', true );
				wp_enqueue_script( 'ink-js');

				wp_register_script( 'ink-autoloader-js', 	$this->base_dir . 'assets/lib/ink-3.1.10/dist/js/autoload' . $this->suffix	 . '.js', array( 'jquery' ), '1.11.4', true );
				wp_enqueue_script( 'ink-autoloader-js');

				// Product search
				wp_register_script( 'wcv-product-search', 	$this->base_dir . 'assets/js/select' . $this->suffix . '.js', array( 'jquery', 'select2' ), '3.5.2', true );
				$localize_search_args['nonce'] = wp_create_nonce( 'wcv-search-products' );
				wp_localize_script( 'wcv-product-search', 'wcv_product_select_params', $localize_search_args );
				wp_enqueue_script( 'wcv-product-search' );

				// Tag search
				wp_register_script( 'wcv-tag-search', 	$this->base_dir . 'assets/js/tags' . $this->suffix . '.js', array( 'jquery', 'select2' ), '1.0.0', true );
				$localize_search_args['nonce'] = wp_create_nonce( 'wcv-search-product-tags' );
				wp_localize_script( 'wcv-tag-search', 'wcv_tag_search_params', $localize_search_args );
				wp_enqueue_script( 'wcv-tag-search' );

				// Product edit
				wp_register_script( 'wcv-frontend-product', $this->base_dir . 'assets/js/product' . $this->suffix	 . '.js', array('jquery-ui-core' ), '1.0.0', true );
				wp_localize_script( 'wcv-frontend-product', 'wcv_frontend_product', array( 'product_types' => array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ) ) );
				wp_enqueue_script( 'wcv-frontend-product' );

				// Order
				wp_register_script( 'wcv-frontend-order', $this->base_dir . 'assets/js/order' . $this->suffix	 . '.js', array( 'jquery' ), '1.0.0', true );
				wp_enqueue_script( 'wcv-frontend-order' );

				// General settings
				wp_register_script( 'wcv-frontend-general', $this->base_dir . 'assets/js/general' . $this->suffix	 . '.js', array( 'jquery', 'select2' ), '1.0.0', true );
				wp_enqueue_script( 'wcv-frontend-general' );

			}  // user logged in check

		//} // on dashboard page

	}

}
