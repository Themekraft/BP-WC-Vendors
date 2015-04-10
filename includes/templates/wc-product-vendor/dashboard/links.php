<h2><?php _e( 'Control Center', 'wcvendors' ); ?></h2>
<p>
	<b><?php _e( 'My shop', 'wcvendors' ); ?></b><br/>
	<a href="<?php echo $shop_page; ?>"><?php echo $shop_page; ?></a>
</p>
<p>
	<b><?php _e( 'My settings', 'wcvendors' ); ?></b><br/>
	<a href="<?php echo $settings_page; ?>"><?php echo $settings_page; ?></a>
</p>

<?php if ( $can_submit ) {

    $create_link =  bp_core_get_userlink( bp_loggedin_user_id(), false, true ).'products/create/';
    ?>
	<p>
		<b><?php _e( 'Submit a product', 'wcvendors' ); ?></b><br/>
		<a target="_TOP" href="<?php echo $create_link ?>"><?php echo $create_link; ?></a>
	</p>
<?php } ?>

<hr>