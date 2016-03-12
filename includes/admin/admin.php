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

function bp_wc_vendors_screen() { ?>

  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>BuddyPress WooCommerce Vendors</h2>
      <?php
      if(isset($_POST['bp_wc_vendors_options_submit']))
          update_option('bp_wc_vendors_options',$_POST['bp_wc_vendors_options']);
      $bp_wc_vendors_options = get_option('bp_wc_vendors_options');
      ?>
      <form method="post" action="?page=bp_wc_vendors_screen">
        <div id="post-body-content">

            <h3>Deactivate Vendor Daschboard Tabs</h3>
            <?php

            $tab_products_disabled = 0;
            if(isset( $bp_wc_vendors_options['tab_products_disabled']))
              $tab_products_disabled = $bp_wc_vendors_options['tab_products_disabled'];

            $tab_orders_disabled = 0;
            if(isset( $bp_wc_vendors_options['tab_orders_disabled']))
                $tab_orders_disabled = $bp_wc_vendors_options['tab_orders_disabled'];

            $tab_settings_disabled = 0;
            if(isset( $bp_wc_vendors_options['tab_settings_disabled']))
              $tab_settings_disabled = $bp_wc_vendors_options['tab_settings_disabled'];

            $tab_ratings_disabled = 0;
            if(isset( $bp_wc_vendors_options['tab_ratings_disabled']))
                $tab_ratings_disabled = $bp_wc_vendors_options['tab_ratings_disabled'];

            $tab_coupongs_disabled = 0;
            if(isset( $bp_wc_vendors_options['tab_coupongs_disabled']))
                $tab_coupongs_disabled = $bp_wc_vendors_options['tab_coupongs_disabled'];

            $no_admin_access = 0;
            if(isset( $bp_wc_vendors_options['no_admin_access']))
                $no_admin_access = $bp_wc_vendors_options['no_admin_access'];

            ?>
            <p><input name='bp_wc_vendors_options[tab_products_disabled]' type='checkbox' value='1' <?php checked( $tab_products_disabled, 1  ) ; ?> /> <b>Turn off "Products" tab. </b></p>
            <p><input name='bp_wc_vendors_options[tab_orders_disabled]' type='checkbox' value='1' <?php checked( $tab_orders_disabled, 1  ) ; ?> /> <b>Turn off "Orders" tab. </b></p>
            <p><input name='bp_wc_vendors_options[tab_settings_disabled]' type='checkbox' value='1' <?php checked( $tab_settings_disabled, 1  ) ; ?> /> <b>Turn off "Settings" tab. </b></p>
            <p><input name='bp_wc_vendors_options[tab_ratings_disabled]' type='checkbox' value='1' <?php checked( $tab_ratings_disabled, 1  ) ; ?> /> <b>Turn off "Ratings" tab. </b></p>
            <p><input name='bp_wc_vendors_options[tab_coupongs_disabled]' type='checkbox' value='1' <?php checked( $tab_coupongs_disabled, 1  ) ; ?> /> <b>Turn off "Coupongs" tab. </b></p>
            <br>
            <h3>Deactivate WordPress Dashboard for Vendors</h3>
            <p>By default, only vendors will be redirected to their BuddyPress 'Member Profile Vendors Dashboard' if they try to access the backend ( /wp-admin ). All other roles will be able ti acces the wp admin.</p>
            <p>In the WC Vendor Pro settings you can set WordPress Dashboard to "Only administrators can access the /wp-admin/ dashboard".<p>

            <p><input name='bp_wc_vendors_options[no_admin_access]' type='checkbox' value='1' <?php checked( $no_admin_access, 1  ) ; ?> /> <b>Turn off the redicet and enable admin backend access. </b></p>
            <br>
            <h3>BuddyPress WC Vendors Dependencies</h3>
            <p>You need a couple of plugins installed for the BP WC Vendors Social Marketplace to work. Please make sure you have the following plugins installed and activated.</p>
            <ul>
              <li><b>BuddyPress</b></li>
              <li><b>WooCommerce</b></li>
              <li><b>WC Vendor Pro</b></li>
            </ul>
            <br>
            <h3>We Recomend</h3>
            <ul>
              <li><b>WC4BP - WooCommerce BuddyPress Integration</b>
                <p>Connect your online shop and social network. Create a seamless customer experience and get more engagement on your site. With Thw BP WC Vendors Plugin you integrate all Vendor Views with BuddyPress. Use the WC4BP Plugin to integrate the My Account Pages with BuddyPRess and sync all WooCommerce user data with BuddyPRess</p>
              </li>
              <li><b>WC4BP - Checkout Maneger</b>
                <p>Add BuddyPress Profiel Fields to the WooComerce Checkout</p>
              </li>
              <li><b>BuddyForms</b>
                <p>WC Vendors comes with basic frontend Product Createion</p>
                <p>Use BuddyForms to create easy to use Product creation Forms</p>
                <p>and benefit from many extensions focust on user frontendt sumission like moderation, post meta and custom fields.</p>
                <p>Find out more on the buddyforms site </p>
                <li>
            </ul>
            <br>
            <h3>Frontend Product Management</h3>
            <p>For the product management we need BuddyForms and some extensions.</p>
            <ul>
              <li><b>BuddyForms</b> Form Builder to build the product forms</li>
              <li><b>BuddyForms Members</b> to add the product forms to BuddyPress Members Profile</li>
              <li><b>BuddyForms Moderation</b> to moderate new or edited products</li>
              <li><b>BuddyForms WooCommerce Form Elements</b> adds all WooCommerce form elements to the form builder</li>
            </ul>
            <br>
            <p>After you have created your product forms we need to set the permissions for each form.
            The Vendor Stores plugin deactivates capabilities management for the vendor and pending vendor roles.
            So we need to set the capabilities for each form we want to give vendors access to. The permission section in your form builder will not work for vendors.
            </p>
            <br>
            <input type="submit" value="Save" name="bp_wc_vendors_options_submit" class="button">
        </div>
      </form>
  </div>

<?php
}
?>
