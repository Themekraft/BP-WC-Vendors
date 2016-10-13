<?php
// Add the option page to the BP WC Vendors menu
add_action( 'admin_menu', 'bp_wc_vendors_add_menu');
function bp_wc_vendors_add_menu() {
  $settings_page =  add_submenu_page( 'woocommerce', 'BP WC Vendors' , 'BP WC Vendors' , 'manage_options', 'bp_wc_vendors_screen', 'bp_wc_vendors_screen' );
	add_action( "load-{$settings_page}", 'bp_wc_vendors_tabs_submit');
}

function bp_wc_vendors_tabs_submit( $current = 'vendor_dashboard' ) {
  if(isset($_POST['bp_wc_vendors_options_submit'])){
    check_admin_referer( "bp-wc-vendors-settings-page" );
    update_option('bp_wc_vendors_options',$_POST['bp_wc_vendors_options']);

    $url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
    wp_redirect(admin_url('admin.php?page=bp_wc_vendors_screen&'.$url_parameters));
    exit;
  }
}

function bp_wc_vendors_tabs( $current = 'vendor_dashboard' ) {
    $tabs = array( 'vendor_dashboard' => 'Dashboard', 'vendor_store' => 'Store', 'vendor_links' => ' Links' );
    $links = array();
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=bp_wc_vendors_screen&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package BP WC Vendors
 * @since 1.3
 */

function bp_wc_vendors_screen() {
  global $buddyforms;

  $bp_wc_vendors_options = get_option('bp_wc_vendors_options'); ?>

  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>BuddyPress WooCommerce Vendors</h2>
      <form method="post" action="?page=bp_wc_vendors_screen">
        <div id="post-body-content">
            <?php
            wp_nonce_field( "bp-wc-vendors-settings-page" );

             if ( isset($_GET['updated']) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Settings updated.</p></div>';
            // if ( isset ( $_GET['tab'] ) ) bp_wc_vendors_tabs($_GET['tab']); else bp_wc_vendors_tabs('vendor_dashboard');
            //
            // if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; else $tab = 'vendor_dashboard'; ?>
  					<table class="form-table">
  							<tr>
  								<th><label for="">Deactivate Vendor Daschboard Tabs</label></th>
  								<td>
                    <?php isset( $bp_wc_vendors_options['tab_products_disabled'] ) ? $tab_products_disabled = $bp_wc_vendors_options['tab_products_disabled'] : $tab_products_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[tab_products_disabled]' type='checkbox' value='1' <?php checked( $tab_products_disabled, 1  ) ; ?> /> <b>Turn off "Products" tab </b></p>

                    <?php isset( $bp_wc_vendors_options['tab_orders_disabled'] ) ? $tab_orders_disabled = $bp_wc_vendors_options['tab_orders_disabled'] : $tab_orders_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[tab_orders_disabled]' type='checkbox' value='1' <?php checked( $tab_orders_disabled, 1  ) ; ?> /> <b>Turn off "Orders" tab </b></p>

                    <?php isset( $bp_wc_vendors_options['tab_settings_disabled'] ) ? $tab_settings_disabled = $bp_wc_vendors_options['tab_settings_disabled'] : $tab_settings_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1  ) ; ?> /> <b>Turn off "Settings" tab </b></p>

                    <?php isset( $bp_wc_vendors_options['tab_ratings_disabled'] ) ? $tab_ratings_disabled = $bp_wc_vendors_options['tab_ratings_disabled'] : $tab_ratings_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[tab_ratings_disabled]' type='checkbox' value='1' <?php checked( $tab_ratings_disabled, 1  ) ; ?> /> <b>Turn off "Ratings" tab </b></p>

                    <?php isset( $bp_wc_vendors_options['tab_coupons_disabled'] ) ? $tab_coupons_disabled = $bp_wc_vendors_options['tab_coupons_disabled'] : $tab_coupons_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[tab_coupons_disabled]' type='checkbox' value='1' <?php checked( $tab_coupons_disabled, 1  ) ; ?> /> <b>Turn off "Coupons" tab </b></p>

                  </td>
  							</tr>
                <tr>
                  <th><label for="">Deactivate WordPress Dashboard for Vendors</label></th>
                  <td>
                    <p>By default only vendors will be redirected to their BuddyPress 'Member Profile Vendors Dashboard' if they try to access the back-end ( /wp-admin ). All other roles will be able to access the wp admin. In the WC Vendor Pro settings you can set WordPress dashboard to "only administrators can access the /wp-admin/ dashboard".<p>
                    <br>
                    <?php isset( $bp_wc_vendors_options['no_admin_access'] ) ? $no_admin_access = $bp_wc_vendors_options['no_admin_access'] : $no_admin_access = 0; ?>
                    <p><input name='bp_wc_vendors_options[no_admin_access]' type='checkbox' value='1' <?php checked( $no_admin_access, 1  ) ; ?> /> <b>Turn off the redirect and enable admin back-end access. </b></p>
                    <br>
                  </td>
                </tr>

  							<tr>
  								<th><label for="">Vendor Store Settings</label></th>
  								<td>
                  <p>Redirect the Vendor Store to the BuddyPress Vendor Profile</p>
                  <?php isset( $bp_wc_vendors_options['redirect_vendor_store_to_profil'] ) ? $redirect_vendor_store_to_profil = $bp_wc_vendors_options['redirect_vendor_store_to_profil'] : $redirect_vendor_store_to_profil = 0; ?>
                  <p><input name='bp_wc_vendors_options[redirect_vendor_store_to_profil]' type='checkbox' value='1' <?php checked( $redirect_vendor_store_to_profil, 1  ) ; ?> /> <b>Redirect Vendor Store to Profile</b></p>

                  </td>
                </tr>
                <tr>
    							<th><label for="">Integrate into Members Profile as new Tab <br><br>BuddyForms Required</label>
                    <?php if(!is_array($buddyforms)) { ?>
                      <br><br>
                      <a href="https://buddyforms.com" target="_blank">Get BuddyForms Now</a>
                    <?php } ?>
                  </th>
    							<td>
                  <p>Select the Product Form</p>
                  <?php isset( $bp_wc_vendors_options['integrate_vendor_store_form'] ) ? $integrate_vendor_store_form = $bp_wc_vendors_options['integrate_vendor_store_form'] : $integrate_vendor_store_form = 'none'; ?>
                  <p>
                    <select name='bp_wc_vendors_options[integrate_vendor_store_form]'>
                      <option value="none">Select a Form</option>
                      <?php if(is_array($buddyforms)){ foreach ($buddyforms as $form_slug => $form) {
                        if($form['post_type'] == 'product') { ?>
                          <option <?php selected( $integrate_vendor_store_form, $form_slug ) ?> value="<?php echo $form_slug ?>"><?php echo $form['name'] ?></option>
                      <?php }}} ?>
                    </select>
                  </p>
                  <br>
                  <b>Tip: Deactivate Product Tab in Vendor Dashooard</b>
                  <p>
                    You can deactivate the product tab and use the BuddyForms Members extension to seperate the product tab from the vendors dashboard.
                    If you make the product tab a main nav item it is great for showing all vendor products in the profile. Normal profile visiters can see the vendor products. The vendor can create and edit.
                  </p>
                  </td>
  							</tr>
                <tr>
                  <th><label for="">Deactivate Links</th>
                  <td>
                    <p>Deactivate the "Visit Store" link in the BuddyPress Members Profile Headder</p>
                    <?php isset( $bp_wc_vendors_options['visit_store_disabled'] ) ? $visit_store_disabled = $bp_wc_vendors_options['visit_store_disabled'] : $visit_store_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[visit_store_disabled]' type='checkbox' value='1' <?php checked( $visit_store_disabled, 1  ) ; ?> /> <b>Disable "Visit Store" Link</b></p>
                    <br>
                    <p>Deactivate the "Profile Links" in the Vendor Shop Product listings and Products Single Views</p>
                    <?php isset( $bp_wc_vendors_options['view_profile_disabled'] ) ? $view_profile_disabled = $bp_wc_vendors_options['view_profile_disabled'] : $view_profile_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[view_profile_disabled]' type='checkbox' value='1' <?php checked( $view_profile_disabled, 1  ) ; ?> /> <b>Disable "View Profile" Link</b></p>
                    <br>
                    <p>Deactivate the "Sold by" links in the Product Listings and Single View</p>
                    <?php isset( $bp_wc_vendors_options['sold_by_disabled'] ) ? $sold_by_disabled = $bp_wc_vendors_options['sold_by_disabled'] : $sold_by_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[sold_by_disabled]' type='checkbox' value='1' <?php checked( $sold_by_disabled, 1  ) ; ?> /> <b>Disable "Sold by" Link</b></p>
                    <br>
                    <p>Deactivate the "Contact Vendor" links in the Product Single View</p>
                    <?php isset( $bp_wc_vendors_options['contact_vendor_disabled'] ) ? $contact_vendor_disabled = $bp_wc_vendors_options['contact_vendor_disabled'] : $contact_vendor_disabled = 0; ?>
                    <p><input name='bp_wc_vendors_options[contact_vendor_disabled]' type='checkbox' value='1' <?php checked( $contact_vendor_disabled, 1  ) ; ?> /> <b>Disable "Contact Vendor" Link</b></p>
                    <br>
                  </td>
                </tr>
  					</table>'


        <input type="submit" value="Save" name="bp_wc_vendors_options_submit" class="button">
        </div>
      </form>
  </div>

<?php
}
