<?php
//
// Add the Settings Page to the bp_wcv_ Menu
//
function bp_wcv_welcome_screen_menu() {
	add_submenu_page( 'bp_wc_vendors_screen', __( 'Info', 'bp_wcv_' ), __( 'Info', 'bp_wcv_' ), 'manage_options', 'bp_wcv_welcome_screen', 'bp_wcv_welcome_screen_content' );
}
add_action( 'admin_menu', 'bp_wcv_welcome_screen_menu', 9999 );

function bp_wcv_welcome_screen_content() {
	?>
	<div id="bf_admin_wrap" class="wrap">


		<style>
			/* Welcome Page CSS */

			.about-wrap.bp-wcv-welcome {
				margin-top: 40px;
			}

			.about-wrap.bp-wcv-welcome .lead {
				max-width: none;
				margin: 20px 0;
			}

			.about-wrap.bp-wcv-welcome .feature-section h1 {
				max-width: none;
				margin: 40px 0 20px;
				font-weight: 300;
			}

			.about-wrap.bp-wcv-welcome h2 {
				max-width: none;
				margin: 40px 0 20px;
				text-align: left;
			}

			.about-wrap.bp-wcv-welcome .about-text {
				min-height: 40px;
				margin-top: 20px;
				font-size: 23px;
				color: #32373c;
				margin-bottom: 30px;
				font-weight: 300;
			}

			.bfw-section {
				margin: 70px 0;
				overflow: auto;
			}

			.bfw-row {
				overflow: auto;
				clear: both;
			}

			.bfwell {
				margin: 40px 0 0 0;
				background: #e5e5e5;
				overflow: auto;
				border: 1px solid #ccc;
				padding: 20px 10px;
			}

			.bfw-col {
				display: block;
				float: left;
				width: 100%;
				overflow: auto;
				padding: 10px;
				box-sizing: border-box;
			}

			.bfw-col-40 {
				width: 40%;
			}

			.bfw-col-50 {
				width: 50%;
			}

			.bfw-col-60 {
				width: 60%;
			}

			.bfw-well {
				padding: 20px;
				background: #fafafa;
				border: 1px solid rgba(0, 0, 0, 0.1);
			}

			.about-wrap.bp-wcv-welcome .bfw-title {
				margin-top: 0;
				font-weight: 300;
			}
		</style>


		<div class="wrap about-wrap bp-wcv-welcome">

			<h1>Welcome to BP WC Vendors Version <?php echo BP_WCV_VERSION ?></h1>

			<p class="about-text">The BuddyPress Marketplace - Enjoy Groundbreaking New Features!</p>

			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="about.php" class="nav-tab nav-tab-active">What’s New</a>
				<a href="admin.php?page=bp_wc_vendors_screen-addons" target="_new" title="Browse Add-ons" class="nav-tab">Browse Add-ons</a>
			</h2>


			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<div class="xcol col-big">
					<h2>A Revolutionary Customer Experience</h2>
					<p class="lead">
						<b>Never feel lost again.</b> Let your USERS find all in one Place and Make the BuddyPress Profile a Home for your Vendors and Customers
					</p>
				</div>

                <div class="xcol col-big">
                    <h2>The final collection of Plugins for your Premium Marketplace Bundled and Supported in one Plugin</h2>
                    <p class="lead">
                        With BuddyPress, WooCommerce + WC4BP, WC Vendor + BP WC Vendors and BuddyForms to create beautiful and intuitive Product Forms you have all tools to your hand you need to build the next outstanding Marketplace.
                    </p>

                    <p class="lead">
                        Keep the admin for admins and make your BuddyPress Profile the Home for all the Vendor and Customer needs
                    </p>
                </div>

			</div>

			<hr>


			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<div class="xcol col-big">
					<h2>All the "My Account" Pages in the BuddyPress Profile</h2>
					<p class="lead">
						Integrate the My Account with BuddyPress Member Profiles and let your Customers find all in one place
					</p>
				</div>

                <div class="xcol col-small">
                    <div class="imgframe">
                        <img class="nopad"
                             style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd;"
                             src="<?php echo BP_WCV_PLUGIN_URL . 'assets/admin/images/wc4bp-banner-1544x500.jpg'?>"
                             alt="Frontend Product Forms">
                    </div>
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
                             style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd;"
                             src="<?php echo BP_WCV_PLUGIN_URL . 'assets/admin/images/buddyforms-banner-1544x500.jpg'?>"
                             alt="Frontend Product Forms">
                    </div>
                </div>

			</div>


			<!-- Blogpost & Changelog -->
			<div class="bfw-section bfw-news" style="margin-top: 30px;">
				<div class="bfw-col bfw-col-50">
					<h2 class="bfw-title">Latest Blogpost</h2>
					<p class="lead">Read all about Marketplaces with BuddyPress. Find Tips and Tricks in our Blog:</p>
					<a href="https://themekraft.com/bp_wcv_-news/" target="_new" class="button button-primary">Read Blogpost</a>
				</div>
				<div class="bfw-col bfw-col-50">
					<h2 class="bfw-title">Changelog</h2>
					<p class="lead">Check out the changelog for version <?php echo BP_WCV_VERSION ?></p>
					<a href="https://wordpress.org/plugins/bp-wc-vendors/#developers" target="_new" class="button button-primary">View Changelog</a></p>
				</div>
			</div>


			<hr style="margin: 70px 0;">


			<!-- Getting Started -->
			<div class="bfw-section bfw-getting-started">
				<div class="bfw-col bfw-col-50">
					<div class="well">
						<h3 class="bfw-title">First Time Here?</h3>
							<a class="button xbutton-primary" href="http://docs.themekraft.com/" title="" target="new">Getting Started</a>
					</div>
				</div>
				<div class="bfw-col bfw-col-50">
					<div class="well">
						<h3 class="bfw-title">How To Create New Forms</h3>
							<a class="button xbutton-primary" href="" title="" target="new">Integrate Vendor Dashboard</a><br>
							<a class="button xbutton-primary" href="" title="" target="new">Integrate My Account Pages</a><br>
							<a class="button xbutton-primary" href="" title="" target="new">Create Product Forms</a><br>
                            <a class="button xbutton-primary" href="http://docs.buddyforms.com/article/151-create-a-social-marketplace-with-woocommerce-and-buddypress" title="" target="new">Marketplace with BuddyPress</a><br>
					</div>
				</div>
			</div>


		</div>


	</div>
	<?php
}
