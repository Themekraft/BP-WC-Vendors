<?php
// Add the option page to the BP WC Vendors menu
add_action( 'admin_menu', 'bp_wc_vendors_add_menu' );
function bp_wc_vendors_add_menu() {
	$settings_page = add_menu_page( 'BP WC Vendors', 'BP WC Vendors', 'manage_options', 'bp_wc_vendors_screen', 'bp_wc_vendors_screen' );
	add_action( "load-{$settings_page}", 'bp_wc_vendors_tabs_submit' );
}

function bp_wc_vendors_tabs_submit( $get ) {
	if ( isset( $_POST['bp_wc_vendors_options'] ) ) {
		check_admin_referer( "bp-wc-vendors-settings-page" );

		global $current;

		$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );

		if ( isset( $_POST['bp_wc_vendors_options'] ) && is_array( $_POST['bp_wc_vendors_options'] ) ) {
			foreach ( $_POST['bp_wc_vendors_options'] as $key => $bp_wc_vendors_option ) {
				$bp_wc_vendors_options[ $key ] = $bp_wc_vendors_option;
			}
		}
		update_option( 'bp_wc_vendors_options', $bp_wc_vendors_options );

		$current = 'general';
		$current = isset( $_POST['bp_wc_vendors_options_general_submit'] ) ? 'general' : $current;
		$current = isset( $_POST['bp_wc_vendors_options_products_submit'] ) ? 'products' : $current;
		$current = isset( $_POST['bp_wc_vendors_options_links_submit'] ) ? 'links' : $current;

		wp_redirect( admin_url( 'admin.php?page=bp_wc_vendors_screen&updated=true&tab=' . $current ) );
		exit;
	}
}

/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package BP WC Vendors
 * @since 1.3
 */

function bp_wc_vendors_screen() {
	global $pagenow, $buddyforms, $current;

	$bp_wc_vendors_options = get_option( 'bp_wc_vendors_options' );

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

		$tabs = array( 'general' => 'Dashboard', 'products' => 'Product Creation', 'links' => 'Deactivate Links' );

		$tabs = apply_filters( 'buddyforms_admin_tabs', $tabs );

		echo '<h2 class="nav-tab-wrapper" style="padding-bottom: 0;">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='admin.php?page=bp_wc_vendors_screen&tab=$tab'>$name</a>";

		}
		echo '</h2>';

		?>
        <form method="post" action="?page=bp_wc_vendors_screen">
            <div id="post-body-content">
				<?php

				wp_nonce_field( "bp-wc-vendors-settings-page" );
				if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) {
					echo '<div class="updated" ><p>Settings updated.</p></div>';
				}

				if ( $pagenow == 'admin.php' && $_GET['page'] == 'bp_wc_vendors_screen' ) {

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
                                <th><label for="">Free Version</label></th>
                                <td><p>You are using the free version. There are no options for the Free Dashboard. All Tabs get included</p></td>
                            </tr>
                            <tr>
                                <th><label for="">Deactivate Pro Dashboard Tabs</label></th>
                                <td>
									<?php isset( $bp_wc_vendors_options['general']['tab_products_disabled'] ) ? $tab_products_disabled = $bp_wc_vendors_options['general']['tab_products_disabled'] : $tab_products_disabled = 0; ?>
                                    <p <?php bp_wc_vendors() ?>><input
                                                name='bp_wc_vendors_options[general][tab_products_disabled]'
                                                type='checkbox'
                                                value='1' <?php checked( $tab_products_disabled, 1 ); ?> />
                                        <b>Turn off "Products" tab </b></p>

									<?php isset( $bp_wc_vendors_options['general']['tab_orders_disabled'] ) ? $tab_orders_disabled = $bp_wc_vendors_options['general']['tab_orders_disabled'] : $tab_orders_disabled = 0; ?>
                                    <p <?php bp_wc_vendors() ?>><input name='bp_wc_vendors_options[general][tab_orders_disabled]'
                                                                       type='checkbox'
                                                                       value='1' <?php checked( $tab_orders_disabled, 1 ); ?> />
                                        <b>Turn off "Orders"
                                            tab </b></p>

									<?php isset( $bp_wc_vendors_options['general']['tab_settings_disabled'] ) ? $tab_settings_disabled = $bp_wc_vendors_options['general']['tab_settings_disabled'] : $tab_settings_disabled = 0; ?>
                                    <p <?php bp_wc_vendors() ?>><input
                                                name='bp_wc_vendors_options[general][tab_settings_disabled]' type='checkbox'
                                                value='1' <?php checked( $tab_settings_disabled, 1 ); ?> /> <b>Turn off
                                            "Settings"
                                            tab </b></p>

									<?php isset( $bp_wc_vendors_options['general']['tab_ratings_disabled'] ) ? $tab_ratings_disabled = $bp_wc_vendors_options['general']['tab_ratings_disabled'] : $tab_ratings_disabled = 0; ?>
                                    <p <?php bp_wc_vendors() ?>><input
                                                name='bp_wc_vendors_options[general][tab_ratings_disabled]' type='checkbox'
                                                value='1' <?php checked( $tab_ratings_disabled, 1 ); ?> /> <b>Turn off
                                            "Ratings"
                                            tab </b></p>

									<?php isset( $bp_wc_vendors_options['general']['tab_coupons_disabled'] ) ? $tab_coupons_disabled = $bp_wc_vendors_options['general']['tab_coupons_disabled'] : $tab_coupons_disabled = 0; ?>
                                    <p <?php bp_wc_vendors() ?>><input
                                                name='bp_wc_vendors_options[general][tab_coupons_disabled]' type='checkbox'
                                                value='1' <?php checked( $tab_coupons_disabled, 1 ); ?> /> <b>Turn off
                                            "Coupons"
                                            tab </b></p>

                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wc_vendors_options_general_submit" class="button">
						<?php
						break;
					case 'products': ?>
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
										<?php isset( $bp_wc_vendors_options['products']['no_admin_access'] ) ? $no_admin_access = $bp_wc_vendors_options['products']['no_admin_access'] : $no_admin_access = 0; ?>
                                    <p><input name='bp_wc_vendors_options[products][no_admin_access]' type='checkbox'
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
									<?php isset( $bp_wc_vendors_options['products']['redirect_vendor_store_to_profil'] ) ? $redirect_vendor_store_to_profil = $bp_wc_vendors_options['products']['redirect_vendor_store_to_profil'] : $redirect_vendor_store_to_profil = 0; ?>
                                    <p><input name='bp_wc_vendors_options[products][redirect_vendor_store_to_profil]'
                                              type='checkbox'
                                              value='1' <?php checked( $redirect_vendor_store_to_profil, 1 ); ?> /> <b>Redirect
                                            Vendor Store to Profile</b></p>

                                </td>
                            </tr>
                            <tr>
                                <th><label for="">Integrate into Members Profile as new Tab <br><br>BuddyForms Required</label>
									<?php if ( ! is_array( $buddyforms ) ) { ?>
                                        <br><br>
                                        <a href="https://buddyforms.com" target="_blank">Get BuddyForms Now</a>
									<?php } ?>
                                </th>
                                <td>
                                    <p>Select the Product Form</p>
									<?php isset( $bp_wc_vendors_options['products']['integrate_vendor_store_form'] ) ? $integrate_vendor_store_form = $bp_wc_vendors_options['products']['integrate_vendor_store_form'] : $integrate_vendor_store_form = 'none'; ?>
                                    <p>
                                        <select name='bp_wc_vendors_options[products][integrate_vendor_store_form]'>
                                            <option value="none">Select a Form</option>
											<?php if ( is_array( $buddyforms ) ) {
												foreach ( $buddyforms as $form_slug => $form ) {
													if ( $form['post_type'] == 'product' ) { ?>
                                                        <option <?php selected( $integrate_vendor_store_form, $form_slug ) ?>
                                                                value="<?php echo $form_slug ?>"><?php echo $form['name'] ?></option>
													<?php }
												}
											} ?>
                                        </select>
                                    </p>
                                    <br>
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
                        <input type="submit" value="Save" name="bp_wc_vendors_options_products_submit" class="button">
						<?php
						break;
					case 'links' : ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="">Deactivate Links</th>
                                <td>
                                    <p>Deactivate the "Visit Store" link in the BuddyPress Members Profile Headder</p>
									<?php isset( $bp_wc_vendors_options['links']['visit_store_disabled'] ) ? $visit_store_disabled = $bp_wc_vendors_options['links']['visit_store_disabled'] : $visit_store_disabled = 0; ?>
                                    <p><input name='bp_wc_vendors_options[links][visit_store_disabled]' type='checkbox'
                                              value='1' <?php checked( $visit_store_disabled, 1 ); ?> /> <b>Disable
                                            "Visit
                                            Store" Link</b></p>
                                    <br>
                                    <p>Deactivate the "Profile Links" in the Vendor Shop Product listings and Products
                                        Single
                                        Views</p>
									<?php isset( $bp_wc_vendors_options['links']['view_profile_disabled'] ) ? $view_profile_disabled = $bp_wc_vendors_options['links']['view_profile_disabled'] : $view_profile_disabled = 0; ?>
                                    <p><input name='bp_wc_vendors_options[links][view_profile_disabled]' type='checkbox'
                                              value='1' <?php checked( $view_profile_disabled, 1 ); ?> /> <b>Disable
                                            "View
                                            Profile" Link</b></p>
                                    <br>
                                    <p>Deactivate the "Sold by" links in the Product Listings and Single View</p>
									<?php isset( $bp_wc_vendors_options['links']['sold_by_disabled'] ) ? $sold_by_disabled = $bp_wc_vendors_options['links']['sold_by_disabled'] : $sold_by_disabled = 0; ?>
                                    <p><input name='bp_wc_vendors_options[links][sold_by_disabled]' type='checkbox'
                                              value='1' <?php checked( $sold_by_disabled, 1 ); ?> /> <b>Disable "Sold
                                            by"
                                            Link</b></p>
                                    <br>
                                    <p>Deactivate the "Contact Vendor" links in the Product Single View</p>
									<?php isset( $bp_wc_vendors_options['links']['contact_vendor_disabled'] ) ? $contact_vendor_disabled = $bp_wc_vendors_options['links']['contact_vendor_disabled'] : $contact_vendor_disabled = 0; ?>
                                    <p><input name='bp_wc_vendors_options[links][contact_vendor_disabled]' type='checkbox'
                                              value='1' <?php checked( $contact_vendor_disabled, 1 ); ?> /> <b>Disable
                                            "Contact
                                            Vendor" Link</b></p>
                                    <br>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" value="Save" name="bp_wc_vendors_options_links_submit" class="button">
						<?php
						break;
				}
				?>
            </div>
        </form>
	<?php } ?>
    </div>
	<?php
}

function bp_wc_vendors() {
	$class = 'class="bp-wc-vendors-disabled"';

	if ( bp_wc_vendors_fs()->is_plan( 'professional' ) ) {
		$class = "";
	}

	echo $class;
}