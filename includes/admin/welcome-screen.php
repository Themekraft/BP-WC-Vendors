<?php
//
// Add the Settings Page to the Menu
//
function bp_wcv_welcome_screen_menu() {
	add_submenu_page( 'bp_wcv_screen', __( 'Info', 'bpwcv' ), __( 'Info', 'bpwcv' ), 'manage_options', 'bp_wcv_welcome_screen', 'bp_wcv_welcome_screen_content' );
}
add_action( 'admin_menu', 'bp_wcv_welcome_screen_menu', 999 );

function bp_wcv_welcome_screen_content() {
	?>
	<div id="bf_admin_wrap" class="wrap welcome-wrap">

		<div class="wrap about-wrap bp-wcv-welcome">

			<h1>Welcome to BP WC Vendors Version <?php echo BP_WCV_VERSION ?></h1>

			<!-- <p class="about-text">The BuddyPress Marketplace - Enjoy Groundbreaking New Features!</p> -->

      <h2 class="nav-tab-wrapper wp-clearfix">
				<a href="about.php" class="nav-tab nav-tab-active">What’s New</a>
				<a href="admin.php?page=bp_wcv_screen-addons" target="_new" title="Browse Add-ons" class="nav-tab">Browse Add-ons</a>
			</h2>


			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<!-- <div class="xcol col-big">
					<h2>A Revolutionary Customer Experience</h2>
					<p class="lead">
						<b>Never feel lost again.</b> Let your USERS find all in one place, for a truly seamless Marketplace experience.
					</p>
				</div> -->

        <div class="xcol col-big">
					<!-- With BuddyPress, WooCommerce + WC4BP, WC Vendor + BP WC Vendors and BuddyForms to create beautiful and intuitive Product Forms you have all tools to your hands you need to build the next outstanding Marketplace. -->
					<h1>Your Premium Marketplace.</h1>
					<h3>Bundled and supported in one plugin.</h3>
          <p class="lead">This plugin will guide you through the Marketplace set up you need to get it all working smoothly with BuddyPress, WooCommerce and WC Vendors. <br>
							And to top it off, we will show you how you make better product submission forms with BuddyForms.
					</p>
        </div>

			</div>

            <hr style="margin: 30px 0;">

            <div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

                <div class="xcol col-big">
                    <h2>First Step - install all dependencies</h2>
                    <p class="lead">
                        We recommend you to install and activate all plugins from the list below. Just check all and select install/activate in the Bulk Actions menu.
                    </p>
                    <?php $jhg = new TGM_Plugin_Activation; $jhg->install_plugins_page(); ?>
                </div>

            </div>

            <div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

                <div class="xcol col-big">
                    <h2>Next Step: Set it all up</h2>
                    <p class="lead">
                        Go to the <a href="<?php echo admin_url('admin.php?page=bp_wcv_screen') ?>" >Plugin Settings</a> and Set up all to your needs. You can find Documentation and Help for the different plugins from the links below.
                    </p>

                </div>

                <div class="bfw-section bfw-getting-started" style="overflow: auto;">
                    <h3>Documentation</h3>
                    <div class="bfw-col bfw-col-50">
                        <div class="well">
                            <h3 class="bfw-title">External</h3>
                            <ul>
                                <li><a href="https://docs.woocommerce.com/documentation/plugins/woocommerce/" target="_blank"><b>WooCommerce</b></a></li>
                                <li><a href="https://www.wcvendors.com/kb/" target="_blank"><b>WC Vendors</b></a></li>
                                <li><a href="https://codex.buddypress.org/" target="_blank"><b>BuddyPress</b></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="bfw-col bfw-col-50">
                        <div class="well">
                            <h3 class="bfw-title">ThemeKraft</h3>
                            <ul>
                                <li><a href="https://docs.themekraft.com/collection/208-wc4bp-integration" target="_blank"><b>WooCommerce BuddyPress Integration</b></a></li>
                                <li><a href="https://docs.themekraft.com/collection/474-bp-wc-vendors" target="_blank"><b>BP WC Vendors</b></a></li>
                                <li><a href="https://docs.buddyforms.com" target="_blank"><b>BuddyForms</b></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

								<div style="padding: 20px 20px; background: #fff; border: 1px solid rgba(0,0,0,0.1); margin: 50px 0 20px;">
									<p class="lead"><b>Need Help?</b></p>
									<p class="lead"><a href="https://themekraft.com/wordpress-custom-development-design/" title="Get the whole setup done, by pros." target="_blank" class="button button-primary">Done-For-You Service</a></p>
									<p class="lead">Save time and do it properly.</p>
								</div>

            </div>

            <!-- <hr style="margin: 30px 0;"> -->

						<!-- Blogpost & Changelog -->
						<div class="bfw-section bfw-news" style="margin-top: 30px;">
							<div class="bfw-col bfw-col-50">
								<h2 class="bfw-title">Latest Blogpost</h2>
								<p class="lead">Read all about Marketplaces with BuddyPress. Find Tips and Tricks in our Blog:</p>
								<a href="https://themekraft.com/news/" target="_new" class="button button-primary">Read Blogpost</a>
							</div>
							<div class="bfw-col bfw-col-50">
								<h2 class="bfw-title">Changelog</h2>
								<p class="lead">Check out the changelog for version <?php echo BP_WCV_VERSION ?></p>
								<a href="https://wordpress.org/plugins/bp-wc-vendors/#developers" target="_new" class="button button-primary">View Changelog</a></p>
							</div>
						</div>


						<hr style="margin: 140px 0;">


            <div style="padding: 20px 20px; background: #fff; border: 1px solid rgba(0,0,0,0.1); margin: 50px 0 20px;">
							<h2 style="margin: 20px 0; padding: 0;">Taking it seriously?</h2>
							<p style="font-size: 17px;">Get the PRO versions of those plugins:</p>
						</div>

            <div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

                <div class="xcol col-big">
                    <h2>The "Vendor Pro Dashboard" in the BuddyPress Profile</h2>
                    <p class="lead">
                        Integrate the Vendor Dashboard with BuddyPress Member Profiles and let your Vendors manage there Shop from there Profile
                    </p>
                </div>

                <div class="xcol col-small">
                    <div class="imgframe">
                        <img class="nopad"
                             style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd; max-width: 98%;"
                             src="<?php echo BP_WCV_PLUGIN_URL . 'assets/admin/images/bp-wcv-banner-1544x500.jpg'?>"
                             alt="Frontend Product Forms">
                    </div>
										<a href="https://themekraft.com/products/bp-wc-vendors/" target="_blank" class="button button-primary">Get BP WC Vendors PRO Here</a>
                    <a href="https://www.wcvendors.com/product/wc-vendors-pro/" target="_blank"  class="button button-primary">Get WC Vendors PRO Here</a>
                </div>

            </div>

            <hr>

            <div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

                <div class="xcol col-big">
                    <h2>All the "My Account" Pages in the BuddyPress Profile</h2>
                    <p class="lead">
                        Integrate the My Account with BuddyPress Member Profiles and let your Customers find all in one place, with the WooCommerce BuddyPress Integration Plugin.
                    </p>
                </div>

                <div class="xcol col-small">
                    <div class="imgframe">
                        <img class="nopad"
                             style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd; max-width: 98%;"
                             src="<?php echo BP_WCV_PLUGIN_URL . 'assets/admin/images/wc4bp-banner-1544x500.jpg'?>"
                             alt="Frontend Product Forms">
                    </div>
                    <a href="https://themekraft.com/products/woocommerce-buddypress-integration/" target="_blank" class="button button-primary">Get WooCommerce BuddyPress Integration PRO Here</a>
                </div>

            </div>

            <hr>

            <div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

                <div class="xcol col-big">
                    <h2>Product Creation at its finest</h2>
                    <p class="lead">
                        Build outstanding Product Forms for your Vendors.
                        Integrate Vendor Product Forms and Vendor Product lists into the BuddyPress Profile
                        <b>For the Customer:</b> Let your Customers find the Vendor Products in the Vendor profile
                        <b>For the Vendor:</b> Let your vendors manage there products form there Profile
                    </p>
                </div>

                <div class="xcol col-small">
                    <div class="imgframe">
                        <img class="nopad"
                             style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd; max-width: 98%;"
                             src="<?php echo BP_WCV_PLUGIN_URL . 'assets/admin/images/buddyforms-banner-1544x500.jpg'?>"
                             alt="Frontend Product Forms">
                    </div>

                    <a href="https://themekraft.com/buddyforms/" target="_blank" class="button button-primary">Get BuddyForms PRO Here</a>
                </div>

            </div>

		</div>




	</div>
	<?php
}
