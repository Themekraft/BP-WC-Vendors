<?php

// if(has_action('bf_wc_vendors_add_submenu_page')) {
//     add_action( 'bf_wc_vendors_add_submenu_page', 'bf_wc_vendors_add_menu' );
// } else {
//     add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP WC Vendors needs WooCommerce BuddyPress Integration to be installed. <a target="_blank" href="%s">--> Get it now</a>!\', " bf_wc_vendors_xprofile" ) . \'</strong></p></div>\', "http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/" );' ) );
// }

if(!WCV_PRO_VERSION)
  add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP WC Vendors needs WC Vendors Pro to be installed. <a target="_blank" href="%s">--> Get it now</a>!\', " bf_wc_vendors_xprofile" ) . \'</strong></p></div>\', "https://www.wcvendors.com/product/wc-vendors-pro/" );' ) );



// Add the option page to the WC4BP Integration menu
add_action( 'admin_menu', 'bf_wc_vendors_add_menu' );
function bf_wc_vendors_add_menu() {
    //add_menu_page( 'WooCommerce for BuddyPress', 'WC4BP Settings', 'manage_options', 'wc4bp-options-page', 'wc4bp_screen' );
    add_submenu_page( 'woocommerce', 'BP WC Vendors' , 'BP WC Vendors' , 'manage_options', 'bf_wc_vendors_screen', 'bf_wc_vendors_screen' );
}

/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function bf_wc_vendors_screen() {
  global $buddyforms ?>

  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>BP WC Vendors</h2>
      <?php
      if(isset($_POST['bf_wc_vendors_options_submit']))
          update_option('bf_wc_vendors_options',$_POST['bf_wc_vendors_options']);
      $bf_wc_vendors_options = get_option('bf_wc_vendors_options');
      ?>
      <form method="post" action="?page=wc4bp-vs-screen">
        <div id="post-body-content">

            <h3>BuddyPress WC Vendors Dependencies</h3>

            You need a couple of plugins installed for the BP WC Vendors Social Marketplace to work. Please make sure you have the following plugins installed and activated.

            <ul>
              <li><b>BuddyPress</b></li>
              <li><b>WooCommerce</b></li>
              <li><b>WC Vendor Pro</b></li>
              <li><b>WC4BP - WooCommerce BuddyPress Integration</b></li>
              <li><b>BuddyForms</b><li>
            </ul>
            <br><br>
            <h3>Vendor Store Settings</h3>
            <p>Deactivate Vendor Daschboard Tabs</p>
            <?php

            $tab_settings_disabled = 0;
            if(isset( $bf_wc_vendors_options['tab_settings_disabled']))
              $tab_settings_disabled = $bf_wc_vendors_options['tab_settings_disabled'];

            $tab_taxes_disabled = 0;
            if(isset( $bf_wc_vendors_options['tab_taxes_disabled']))
                $tab_taxes_disabled = $bf_wc_vendors_options['tab_taxes_disabled'];

            $no_admin_access = 0;
            if(isset( $bf_wc_vendors_options['no_admin_access']))
                $no_admin_access = $bf_wc_vendors_options['no_admin_access'];

            ?>
            <p><input name='bf_wc_vendors_options[tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1  ) ; ?> /> <b>Turn off "Settings" tab. </b></p>
            <p><input name='bf_wc_vendors_options[tab_taxes_disabled]' type='checkbox' value='1' <?php checked( $tab_taxes_disabled, 1  ) ; ?> /> <b>Turn off "Taxes" tab. </b></p>
            <p><input name='bf_wc_vendors_options[tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1  ) ; ?> /> <b>Turn off "Settings" tab. </b></p>
            <p><input name='bf_wc_vendors_options[tab_taxes_disabled]' type='checkbox' value='1' <?php checked( $tab_taxes_disabled, 1  ) ; ?> /> <b>Turn off "Taxes" tab. </b></p>
            <br>
            <br>
            <h3>Deactivate WordPress Backend</h3>
            <p>By default, vendors will be redirected to their BuddyPress 'Member Profile Vendors Dashboard' if they try to access the backend ( /wp-admin ).</p>
            <p><input name='bf_wc_vendors_options[no_admin_access]' type='checkbox' value='1' <?php checked( $no_admin_access, 1  ) ; ?> /> <b>Turn off the redicet and enable admin backend access. </b></p>
            <br>
            <br>
            <h3>Frontend Product Management</h3>
            <p>For the product management we need BuddyForms and some extensions.</p>

          <ul>
            <li><b>BuddyForms</b> Form Builder to build the product forms</li>
            <li><b>BuddyForms Members</b> to add the product forms to BuddyPress Members Profile</li>
            <li><b>BuddyForms Moderation</b> to moderate new or edited products</li>
            <li><b>BuddyForms WooCommerce Form Elements</b> adds all WooCommerce form elements to the form builder</li>
          </ul>

          <p>After you have created your product forms we need to set the permissions for each form.
          The Vendor Stores plugin deactivates capabilities management for the vendor and pending vendor roles.
          So we need to set the capabilities for each form we want to give vendors access to. The permission section in your form builder will not work for vendors.
          </p>

          <br>
          <input type="submit" value="Save" name="bf_wc_vendors_options_submit" class="button">
        </div>
      </form>
  </div>

<?php
}
?>
