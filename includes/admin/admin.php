<?php
// Add the option page to the BP WC Vendors menu
add_action( 'admin_menu', 'bp_wcv_add_menu' );
function bp_wcv_add_menu() {
	$settings_page = add_menu_page( 'BP WC Vendors', 'BP WC Vendors', 'manage_options', 'bp_wcv_screen', 'bp_wcv_screen_function' );
	add_action( "load-{$settings_page}", 'bp_wcv_tabs_submit' );
}

function bp_wcv_tabs_submit( $get ) {
    global $current;

	$current = '';
	$current = isset( $_POST['bp_wcv_options_general_submit'] ) ? 'general' : $current;
	$current = isset( $_POST['bp_wcv_options_products_submit'] ) ? 'products' : $current;
	$current = isset( $_POST['bp_wcv_options_links_submit'] ) ? 'links' : $current;
	$current = isset( $_POST['bp_wcv_options_redirects_submit'] ) ? 'redirects' : $current;
	$current = isset( $_POST['bp_wcv_options_signup_submit'] ) ? 'signup' : $current;
	$current = isset( $_POST['bp_wcv_options_roles_submit'] ) ? 'roles' : $current;

	if( empty( $current ) ){
		return;
	}

    check_admin_referer( "bp-wc-vendors-settings-page" );

    $bp_wcv_options = get_option( 'bp_wcv_options' );

    if ( isset( $_POST['bp_wcv_options'] ) && is_array( $_POST['bp_wcv_options'] ) ) {
        foreach ( $_POST['bp_wcv_options'] as $key => $bp_wcv_option ) {
            $bp_wcv_options[ $key ] = $bp_wcv_option;
        }
    } else {
        unset($bp_wcv_options[$current]);
    }

    update_option( 'bp_wcv_options', $bp_wcv_options );



    wp_redirect( admin_url( 'admin.php?page=bp_wcv_screen&updated=true&tab=' . $current ) );
    exit;
}

/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package BP WC Vendors
 * @since 1.3
 */

function bp_wcv_screen_function() {
	global $pagenow, $buddyforms, $current;

	$bp_wcv_options = get_option( 'bp_wcv_options' );

	if( isset( $_GET['tab'] ) ){
	    $current = $_GET['tab'];
    }

    if( empty( $current ) ){
	    $current = 'general';
    }
	?>

    <div class="wrap">
        <div id="icon-options-general" class="icon32"><br></div>
        <h2>BuddyPress WooCommerce Vendors</h2>

		<?php

		$tabs = array( 'general' => 'Dashboard Tabs', 'roles' => 'Roles', 'products' => 'Product Creation', 'links' => 'Deactivate Links', 'redirects' => 'Redirects', 'signup' => 'Sign up Forms', 'go_pro' => '<font color="#b22222">Go Professional!!!</font>');

        if ( bp_wcv_fs()->is_plan__premium_only('professional') ) {
	        unset($tabs['go_pro']);
        }

		$tabs = apply_filters( 'buddyforms_admin_tabs', $tabs );

		echo '<h2 class="nav-tab-wrapper" style="padding-bottom: 0;">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='admin.php?page=bp_wcv_screen&tab=$tab'>$name</a>";

		}
		echo '</h2>';

		?>
        <form method="post" action="?page=bp_wcv_screen">
            <div id="post-body-content">
				<?php

				wp_nonce_field( "bp-wc-vendors-settings-page" );
				if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) {
					echo '<div class="updated" ><p>Settings updated.</p></div>';
				}

				if ( $pagenow == 'admin.php' && $_GET['page'] == 'bp_wcv_screen' ) {

				if ( isset ( $_GET['tab'] ) ) {
					$tab = $_GET['tab'];
				} else {
					$tab = 'general';
				}

				switch ( $tab ) {
					case 'general' :
						?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Default Tabs</label></th>
                                <td>
                                    <?php bp_wcv_disabled_message(); ?>

	                                <?php isset( $bp_wcv_options['general']['tab_settings_disabled'] ) ? $tab_settings_disabled = $bp_wcv_options['general']['tab_settings_disabled'] : $tab_settings_disabled = 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input <?php bp_wcv_disabled() ?> name='bp_wcv_options[general][tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1 ); ?> /> <b>Turn off "Settings" tab </b>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="">Pro Dashboard Tabs</label></th>
                                <td>

	                                <?php $tab_orders_disabled = isset( $bp_wcv_options['general']['tab_orders_disabled'] ) ? $bp_wcv_options['general']['tab_orders_disabled'] : 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[general][tab_orders_disabled]' type='checkbox' <?php bp_wcv_disabled() ?> value='1' <?php checked( $tab_orders_disabled, 1 ); ?> />
                                        <b>Turn off "Orders" tab </b>
                                    </p>

									<?php $tab_products_disabled =  isset( $bp_wcv_options['general']['tab_products_disabled'] ) ? $bp_wcv_options['general']['tab_products_disabled'] : 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[general][tab_products_disabled]' type='checkbox' <?php bp_wcv_disabled() ?> value='1' <?php checked( $tab_products_disabled, 1 ); ?> />
                                        <b>Turn off "Products" tab </b>
                                    </p>

									<?php $tab_ratings_disabled = isset( $bp_wcv_options['general']['tab_ratings_disabled'] ) ? $bp_wcv_options['general']['tab_ratings_disabled'] : 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[general][tab_ratings_disabled]' type='checkbox' <?php bp_wcv_disabled() ?> value='1' <?php checked( $tab_ratings_disabled, 1 ); ?> />
                                        <b>Turn off "Ratings" tab </b>
                                    </p>

									<?php $tab_coupons_disabled = isset( $bp_wcv_options['general']['tab_coupons_disabled'] ) ? $bp_wcv_options['general']['tab_coupons_disabled'] : 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[general][tab_coupons_disabled]' type='checkbox' <?php bp_wcv_disabled() ?> value='1' <?php checked( $tab_coupons_disabled, 1 ); ?> />
                                        <b>Turn off "Coupons" tab </b>
                                    </p>

                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_general_submit" class="button">
						<?php
						break;
					case 'roles' : ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Add Vendor as Member Type</label>
                                </th>
                                <td>

	                                   <?php $vendor_role = isset( $bp_wcv_options['roles']['vendor'] ) ? $bp_wcv_options['roles']['vendor'] : 0; ?>
                                    <p><input name='bp_wcv_options[roles][vendor]' type='checkbox'
                                              value='1' <?php checked( $vendor_role, 1 ); ?> /> Create new Member Type "Vendor" for all Vendors </p>

	                                <?php $vendor_directory = isset( $bp_wcv_options['roles']['vendor_directory'] ) ? $bp_wcv_options['roles']['vendor_directory'] : 0; ?>
                                    <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[roles][vendor_directory]' type='checkbox' <?php bp_wcv_disabled() ?>
                                              value='1' <?php checked( $vendor_directory, 1 ); ?> /> Create a "Vendor Directory" </p>

	                                <?php $vendor_directory_name = isset( $bp_wcv_options['roles']['vendor_name'] ) ? $bp_wcv_options['roles']['vendor_directory_name'] : __( 'Vendors', 'bpwcv' ); ?>
                                    <p <?php bp_wcv_pro() ?>><label>Directory Name: </label><input name='bp_wcv_options[roles][vendor_directory_name]' type='text' <?php bp_wcv_disabled() ?>
                                              value='<?php echo $vendor_directory_name ?>' /></p>

	                                <?php $vendor_directory_name_singular = isset( $bp_wcv_options['roles']['vendor_name_singular'] ) ? $bp_wcv_options['roles']['vendor_directory_name_singular'] : __( 'Vendor', 'bpwcv' ); ?>
                                    <p <?php bp_wcv_pro() ?>><label>Directory Name Singular: </label><input name='bp_wcv_options[roles][vendor_directory_name_singular]' type='text' <?php bp_wcv_disabled() ?>
                                              value='<?php echo $vendor_directory_name_singular ?>' /></p>


                                </td>
                            </tr>
                        </table>
                                   <hr>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Member Types Vendors</label></th>
                                <td>
                                    <?php
                                    $member_types = bp_get_member_types();
                                    foreach($member_types as $member_type ) {

                                        if( $member_type == 'vendor' ){
                                            continue;
                                        }

                                        $vendor_role = isset( $bp_wcv_options['roles']['member_types'][$member_type] ) ? $bp_wcv_options['roles']['member_types'][$member_type] : 0; ?>
                                        <p <?php bp_wcv_pro() ?>><input name='bp_wcv_options[roles][member_types][<?php echo $member_type ?>]' type='checkbox' <?php bp_wcv_disabled() ?>
                                              value='1' <?php checked( $vendor_role, 1 ); ?> /> <b><?php echo $member_type ?></b></p>
                                        <?php
                                    }
                                    ?>
                                    <p>Selected Member Types will become Vendors </p>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_roles_submit" class="button">
						<?php
						break;
					case 'products': ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">WC Vendors Free</label></th>
                                <td>
                                    <p>You can use BuddyForms to create forms for any product type. You can integrate this forms into the BuddyPress Member Profile or the Vendors Dashboard.</p>
                                    <p>Just create a Product Form and select the integration in the Form Settings</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="">WC Vendors Pro</label></th>
                                <td>
                                    <b>Tip: Deactivate Product Tab in Vendor Dashooard</b>
                                    <p>
                                        You can deactivate the product tab and use the BuddyForms Members extension to
                                        seperate
                                        the product tab from the vendors dashboard.
                                        If you make the product tab a main nav item it is great for showing all vendor
                                        products
                                        in the profile. Normal profile visiters can see the vendor products. The vendor
                                        can
                                        create and edit.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_products_submit" class="button">
						<?php
						break;
					case 'links' : ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Deactivate Links</th>
                                <td>
                                    <p>Deactivate the "Visit Store" link in the BuddyPress Members Profile Headder</p>
									<?php $visit_store_disabled = isset( $bp_wcv_options['links']['visit_store_disabled'] ) ? $bp_wcv_options['links']['visit_store_disabled'] : 0; ?>
                                    <p><input name='bp_wcv_options[links][visit_store_disabled]' type='checkbox'
                                              value='1' <?php checked( $visit_store_disabled, 1 ); ?> /> <b>Disable
                                            "Visit
                                            Store" Link</b></p>
                                    <br>
                                    <p>Deactivate the "Profile Links" in the Vendor Shop Product listings and Products
                                        Single
                                        Views</p>
									<?php $view_profile_disabled = isset( $bp_wcv_options['links']['view_profile_disabled'] ) ? $bp_wcv_options['links']['view_profile_disabled'] : 0; ?>
                                    <p><input name='bp_wcv_options[links][view_profile_disabled]' type='checkbox'
                                              value='1' <?php checked( $view_profile_disabled, 1 ); ?> /> <b>Disable
                                            "View
                                            Profile" Link</b></p>
                                    <br>
                                    <p>Deactivate the "Sold by" links in the Product Listings and Single View</p>
									<?php $sold_by_disabled = isset( $bp_wcv_options['links']['sold_by_disabled'] ) ? $bp_wcv_options['links']['sold_by_disabled'] :  0; ?>
                                    <p><input name='bp_wcv_options[links][sold_by_disabled]' type='checkbox'
                                              value='1' <?php checked( $sold_by_disabled, 1 ); ?> /> <b>Disable "Sold
                                            by"
                                            Link</b></p>
                                    <br>
                                    <p>Deactivate the "Contact Vendor" links in the Product Single View</p>
									<?php $contact_vendor_disabled = isset( $bp_wcv_options['links']['contact_vendor_disabled'] ) ? $bp_wcv_options['links']['contact_vendor_disabled'] : 0; ?>
                                    <p><input name='bp_wcv_options[links][contact_vendor_disabled]' type='checkbox'
                                              value='1' <?php checked( $contact_vendor_disabled, 1 ); ?> /> <b>Disable
                                            "Contact
                                            Vendor" Link</b></p>
                                    <br>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_links_submit" class="button">
						<?php
						break;
					case 'redirects': ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Deactivate WordPress Dashboard for Vendors</label></th>
                                <td>
                                    <p>By default vendors will be redirected to their BuddyPress 'Member Profile
                                        Vendor Dashboard' if they try to access the back-end ( /wp-admin ). All other roles will be
                                        able to access the wp admin. In the WC Vendor Pro settings you can set WordPress
                                        dashboard to "only administrators can access the /wp-admin/ dashboard".
                                    <p>
                                        <br>
										<?php $no_admin_access = isset( $bp_wcv_options['redirects']['no_admin_access'] ) ? $bp_wcv_options['redirects']['no_admin_access'] : 0; ?>
                                    <p><input name='bp_wcv_options[redirects][no_admin_access]' type='checkbox'
                                              value='1' <?php checked( $no_admin_access, 1 ); ?> /> <b>Turn off the
                                            redirect and
                                            enable admin back-end access. </b></p>
                                    <br>
                                </td>
                            </tr>

                            <tr>
                                <th><label for="">Vendor Store Settings</label></th>
                                <td>
                                    <p>Redirect the Vendor Store to the BuddyPress Vendor Profile</p>
									<?php $redirect_vendor_store_to_profil = isset( $bp_wcv_options['redirects']['redirect_vendor_store_to_profil'] ) ? $bp_wcv_options['redirects']['redirect_vendor_store_to_profil'] : 0; ?>
                                    <p><input name='bp_wcv_options[redirects][redirect_vendor_store_to_profil]'
                                              type='checkbox'
                                              value='1' <?php checked( $redirect_vendor_store_to_profil, 1 ); ?> /> <b>Redirect
                                            Vendor Store to Profile</b></p>

                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_redirects_submit" class="button">
						<?php
						break;
					case 'signup': ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Sign Up Forms</label></th>
                                <td>
                                    <p>Use BuddyForms to create Sign up forms and ask during sign up for all relevant data. Automatically assign new users vendor rights and sync all data with WordPress and BuddyPress</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="">Become a Vendor</label></th>
                                <td>
                                    <p><b>Use Shortcodes</b></p>
                                    <p>You can use the [bp_wcv_bav] Shortcode to display the default "Become a Vendor" Form in any Page.</p>

                                    <p>To display a Registration/Login form for logged off user you need to create a new Registration form with BuddyForms.

                                    Use the Shortcode generator to generate the correct shortcode.
                                    The Shortcode will display the registration form for logged off users and the "Become a vendor" Form if logged in but not a Vendor.
                                    </p>
                                    <hr>

                                    <p><b>Shortcode Generator</b></p>

                                    <?php
                                    if( isset( $buddyforms ) && is_array( $buddyforms ) ){
	                                    echo '<select id="bp-wcv-form-select">';
	                                    echo '<option value="none">Select a Registration Form to generate the Shortcode</option>';
	                                    foreach ( $buddyforms as $form_slug => $form ){
		                                    if( $form['form_type'] == 'registration' ){
			                                    echo '<option value="' . $form['slug'] . '">' . $form['name'] . '</option>';
		                                    }
	                                    }
	                                    echo '</select>';
                                    }
                                    ?>
                                    <div style="display: none" id="bp-wc-vendors-shortcode-result"></div>

                                </td>
                            </tr>


                            <tr>
                                <th><label for="">Add to My Account for non Vendors</label></th>
                                <td>
                                    <p>If you integrate the My Account into BuddyPress It make sense to Integrate the "Become a vendor" Form into the My Account (Shop Tab).</p>
                                    <p>To do so create a page and add the shortcode [bp_wcv_bav] as content. In the next step add this Page in the WC4BP Integrate Pages Settings as new Page.

                                        This will add the "Become a Vendor Form to the Shop Tab as Sub Tab." </p>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wcv_options_signup_submit" class="button">
						<?php
						break;
                    case 'go_pro':
	                    wp_redirect( admin_url( 'admin.php?page=bp_wcv_screen-pricing' ) );
                        break;
				}
				?>
            </div>
        </form>
	<?php } ?>
    </div>
	<?php
}

function bp_wcv_pro() {

	$class = 'class="bp-wc-vendors-disabled"';

	if ( bp_wcv_fs()->is_plan( 'professional' ) && defined( 'WCV_PRO_VERSION' ) ) {
		$class = "";
	}

	echo $class;
}

function bp_wcv_disabled() {
	$disabled = 'disabled';

	if ( bp_wcv_fs()->is_plan( 'professional' ) && defined( 'WCV_PRO_VERSION' ) ) {
		$disabled = "";
	}

	echo $disabled;
}

function bp_wcv_disabled_message() {
	$message = __('You are using the free Version. Please make sure to Update to the Pro Versions to use the Pro Features ', 'bpwcv') . '<br><br>';

	if ( !defined( 'WCV_PRO_VERSION' ) ) {
		$message .= '<p><b>WC Vendors Pro</b> You need the WC Vendors Pro Version to change this settings: <a href="https://www.wcvendors.com/product/wc-vendors-pro/" target="_blank">Get it here</a></p><br>';
	}

	if ( !bp_wcv_fs()->is_plan( 'professional' ) ) {
		$message .= '<p><b>BP WC Vendors Pro</b>  You need the BP WC Vendors Pro Version to change this settings: <a href="https://themekraft.com/products/buddypress-woocommerce-vendors" target="_blank">Get it here</a></p><br>';
	}

	if ( bp_wcv_fs()->is_plan( 'professional' ) && defined( 'WCV_PRO_VERSION' ) ) {
		$message = '';
	}

	echo $message;
}