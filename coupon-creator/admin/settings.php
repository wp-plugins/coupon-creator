<?php
/*
* Coupon Creator Settings
* @version 1.70
*/
	
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

// Flush rewrite rules, per WordPress permalinks options page setup:
flush_rewrite_rules();

?>
<div class="wrap">
	<h2>
		<?php _e( 'Coupon Creator Settings', 'coupon_creator' ); ?>
	</h2>
	<h4>Coupon Creator Version: <?php echo get_option(CCTOR_COUPON_VERSION_KEY); ?></h4>
	<form name="form" action="options.php" method="post">
		<?php settings_fields( 'coupon_creator' ); ?>

		<?php do_settings_sections( 'coupon_creator' ); ?>

		<?php submit_button(); ?>
	</form>

	<div style="margin-bottom: 20px;">
		<?php Coupon_Creator_Plugin_Admin::include_file( 'jesseeprod.php' ); ?>
	</div>
</div>