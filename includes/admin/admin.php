<?php


// Add the option page to the BP WC Vendors menu
add_action( 'admin_menu', 'bp_wc_vendors_add_menu');
function bp_wc_vendors_add_menu() {
    add_submenu_page( 'woocommerce', 'BP WC Vendors' , 'BP WC Vendors' , 'manage_options', 'bp_wc_vendors_screen', 'bp_wc_vendors_screen' );
}

/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package BP WC Vendors
 * @since 1.3
 */

function bp_wc_vendors_screen() {

  if(isset($_POST['bp_wc_vendors_options_submit']))
      update_option('bp_wc_vendors_options',$_POST['bp_wc_vendors_options']);

  $bp_wc_vendors_options = get_option('bp_wc_vendors_options'); ?>

  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>BuddyPress WooCommerce Vendors</h2>
      <form method="post" action="?page=bp_wc_vendors_screen">
        <div id="post-body-content">

            <h3>Deactivate Vendor Daschboard Tabs</h3>
            <?php isset( $bp_wc_vendors_options['tab_products_disabled'] ) ? $tab_products_disabled = $bp_wc_vendors_options['tab_products_disabled'] : $tab_products_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[tab_products_disabled]' type='checkbox' value='1' <?php checked( $tab_products_disabled, 1  ) ; ?> /> <b>Turn off "Products" tab. </b></p>

            <?php isset( $bp_wc_vendors_options['tab_orders_disabled'] ) ? $tab_orders_disabled = $bp_wc_vendors_options['tab_orders_disabled'] : $tab_orders_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[tab_orders_disabled]' type='checkbox' value='1' <?php checked( $tab_orders_disabled, 1  ) ; ?> /> <b>Turn off "Orders" tab. </b></p>

            <?php isset( $bp_wc_vendors_options['tab_settings_disabled'] ) ? $tab_settings_disabled = $bp_wc_vendors_options['tab_settings_disabled'] : $tab_settings_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1  ) ; ?> /> <b>Turn off "Settings" tab. </b></p>

            <?php isset( $bp_wc_vendors_options['tab_ratings_disabled'] ) ? $tab_ratings_disabled = $bp_wc_vendors_options['tab_ratings_disabled'] : $tab_ratings_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[tab_ratings_disabled]' type='checkbox' value='1' <?php checked( $tab_ratings_disabled, 1  ) ; ?> /> <b>Turn off "Ratings" tab. </b></p>

            <?php isset( $bp_wc_vendors_options['tab_coupons_disabled'] ) ? $tab_coupons_disabled = $bp_wc_vendors_options['tab_coupons_disabled'] : $tab_coupons_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[tab_coupons_disabled]' type='checkbox' value='1' <?php checked( $tab_coupons_disabled, 1  ) ; ?> /> <b>Turn off "Coupons" tab. </b></p>
            <br>
            <h3>Deactivate WordPress Dashboard for Vendors</h3>
            <p>By default, only vendors will be redirected to their BuddyPress 'Member Profile Vendors Dashboard' if they try to access the back-end ( /wp-admin ). All other roles will be able to access the wp admin.</p>
            <p>In the WC Vendor Pro settings you can set WordPress dashboard to "only administrators can access the /wp-admin/ dashboard".<p>

            <?php isset( $bp_wc_vendors_options['no_admin_access'] ) ? $no_admin_access = $bp_wc_vendors_options['no_admin_access'] : $no_admin_access = 0; ?>
            <p><input name='bp_wc_vendors_options[no_admin_access]' type='checkbox' value='1' <?php checked( $no_admin_access, 1  ) ; ?> /> <b>Turn off the redirect and enable admin back-end access. </b></p>
            <br>

            <h3>Deactivate Links</h3>
            <p>Deactivate the Visit Store link in the BuddyPress Members Profile Headder</p>
            <?php isset( $bp_wc_vendors_options['visit_store_disabled'] ) ? $visit_store_disabled = $bp_wc_vendors_options['visit_store_disabled'] : $visit_store_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[visit_store_disabled]' type='checkbox' value='1' <?php checked( $visit_store_disabled, 1  ) ; ?> /> <b>visit_store_disabled</b></p>

            <p>Deactivate the View Profile Links in the Vendor Shop Product listings and Products Single Views</p>
            <?php isset( $bp_wc_vendors_options['view_profile_disabled'] ) ? $view_profile_disabled = $bp_wc_vendors_options['view_profile_disabled'] : $view_profile_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[view_profile_disabled]' type='checkbox' value='1' <?php checked( $view_profile_disabled, 1  ) ; ?> /> <b>view_profile_disabled</b></p>

            <p>Deactivate the View Sold by links in the Product Listings and Single View</p>
            <?php isset( $bp_wc_vendors_options['sold_by_disabled'] ) ? $sold_by_disabled = $bp_wc_vendors_options['sold_by_disabled'] : $sold_by_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[sold_by_disabled]' type='checkbox' value='1' <?php checked( $sold_by_disabled, 1  ) ; ?> /> <b>sold_by_disabled</b></p>

            <p>Deactivate the Contact Vendor links in the Product Single View</p>
            <?php isset( $bp_wc_vendors_options['contact_vendor_disabled'] ) ? $contact_vendor_disabled = $bp_wc_vendors_options['contact_vendor_disabled'] : $contact_vendor_disabled = 0; ?>
            <p><input name='bp_wc_vendors_options[contact_vendor_disabled]' type='checkbox' value='1' <?php checked( $contact_vendor_disabled, 1  ) ; ?> /> <b>Disable Contact Vendor Link</b></p>

            <br>

            <h3>Vendor Store Settings</h3>
            <p>Redirect the Vendor Store to the BuddyPress Vendor Profile</p>
            <?php isset( $bp_wc_vendors_options['redirect_vendor_store_to_profil'] ) ? $redirect_vendor_store_to_profil = $bp_wc_vendors_options['redirect_vendor_store_to_profil'] : $redirect_vendor_store_to_profil = 0; ?>
            <p><input name='bp_wc_vendors_options[redirect_vendor_store_to_profil]' type='checkbox' value='1' <?php checked( $redirect_vendor_store_to_profil, 1  ) ; ?> /> <b>redirect_vendor_store_to_profil</b></p>

            <p>Integrate into Profiel</p>
            <?php isset( $bp_wc_vendors_options['integrate_vendor_store_as_profil_tab'] ) ? $integrate_vendor_store_as_profil_tab = $bp_wc_vendors_options['integrate_vendor_store_as_profil_tab'] : $integrate_vendor_store_as_profil_tab = 0; ?>
            <p><input name='bp_wc_vendors_options[integrate_vendor_store_as_profil_tab]' type='checkbox' value='1' <?php checked( $integrate_vendor_store_as_profil_tab, 1  ) ; ?> /> <b>integrate_vendor_store_as_profil_tab</b></p>

            <p>Select the BuddyForm</p>
            <?php isset( $bp_wc_vendors_options['integrate_vendor_store_form'] ) ? $integrate_vendor_store_form = $bp_wc_vendors_options['integrate_vendor_store_form'] : $integrate_vendor_store_form = 'none'; ?>
            <p><select name='bp_wc_vendors_options[integrate_vendor_store_form]'>

                <option>Forms</option>
            </select></p>
            <br>

            <?php if( defined( 'BUDDYFORMS_VERSION' )){ ?>
              <h3>BuddyForms Settings</h3>

              <ul>
                <li>
                  <b>1. Deactivate Product Tab in Vendor Dashooard</b>
                  <p>
                    You can deactivate the product tab and use the BuddyForms Members extension to seperate the product tab from the vendors dashboard.
                    If you make the product tab a main nav item it is great for showing all vendor products in the profile. Normal profile visiters can see the vendor products. The vendor can create and edit.
                  </p>
                </li>
                <!-- <li>
                  <b>2. Use BuddyForms in Vendor Dashooard</b>
                  <p>
                    Products tab will remine in the vendors dashboard but the product list and form will be generated from BuddyForms.
                    BP WC Vendors will use the BuddyForms post types default form.
                    You can select a default form for every post type in the BuddyForms settings. Please make sure you have a product form selected for the custom post type products
                  </p>
                  <p><input name='bp_wc_vendors_options[buddyforms_form]' type='checkbox' value='1' <?php checked( $buddyforms_form, 1  ) ; ?> /> <b>Use BuddyForms for product creation/management</b></p>
                </li> -->
              </ul>

            <?php } ?>

        <input type="submit" value="Save" name="bp_wc_vendors_options_submit" class="button">
        </div>
      </form>
  </div>

<?php
}
?>
