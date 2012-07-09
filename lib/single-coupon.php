<?php
/*
Single Template For Custom Post Type Coupon - Permalink displays the Coupon with Print Button
Coustom Template with Basic WordPress loaded no header footer, etc
*/ 
?>
<!DOCTYPE html>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php echo get_the_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	
	<?php $coupon_url_dir = plugins_url(); // Declare Plugin Directory ?>
	
	<!--Make Background White to Print Coupon -->
	<style>
		body {
			background-color: #fff;
			background-image: none;
		}
	</style>
	<!--Load StyleSheet for Coupons -->
	<link rel='stylesheet' id='coupon-style-css'  href='<?php echo $coupon_url_dir; ?>/coupon-creator/css/cctor_coupon.css' type='text/css' media='all' />  
</head>	
	
	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<?php //Check Expiration if past date then exit
		$expirationco = get_post_meta($post->ID, 'cctor_expiration', true); //Expiration Date
		$cc_blogtime = current_time('mysql'); //Blog Time According to WordPress
		list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $cc_blogtime ); //List out the Different Fields to Use
		$cc_today = strtotime($today_month."/".$today_day."/". $today_year); //Combine the data we need to compare
		$cc_expiration_date = strtotime($expirationco); //php fun and we are ready to compare

		if ($cc_expiration_date >= $cc_today) { //Check Expiration Date if Past that Date do not display coupon
			$couponimage_id = get_post_meta($post->ID, 'cctor_image', true); // Get Image Meta 
			$couponimage = wp_get_attachment_image_src($couponimage_id, 'print_coupon'); //Get Right Size Image for Print Template
			$couponimage = $couponimage[0]; //Make sure we only have first attached image not that there should be any others
	?>
		
		<div class="cctor_coupon_container print_coupon"> <!--start coupon container -->
			
			<?php if ($couponimage) { //if there is an image only display that and forget the rest ?>
			
				<img class='cctor_coupon_image' src='<?php echo $couponimage; ?>' alt='' title=''> 
			
			<?php } else { //No Image so lets create a coupon ?> 
				<div class="cctor_coupon">	
					<div class="cctor_coupon_content" style="border-color:<?php echo get_post_meta($post->ID, 'cctor_bordercolor', true); ?>!important;"> <!--style border -->
				
						<h3 style="background-color:<?php echo get_post_meta($post->ID, 'cctor_colordiscount', true);  ?>!important; color:<?php echo get_post_meta($post->ID, 'cctor_colorheader', true); ?>!important;"> <!--style bg of discount -->
						<?php echo get_post_meta($post->ID, 'cctor_amount', true);  ?></h3>
						
						<div class="cctor_deal"><?php echo get_post_meta($post->ID, 'cctor_description', true);  ?></div>
						<div class="cctor_expiration">Expires on:&nbsp;<?php echo get_post_meta($post->ID, 'cctor_expiration', true);  ?></div>
					</div> <!--end .cctor_coupon_content -->
				</div> <!--end .cctor_coupon -->
				
			<?php } // End the Else ?>
			
			<div class="cctor_opencoupon"> <!-- We Need a Click to Print Button -->
				<a href="javascript:window.print();">Click to Print</a>
				
			</div> <!--end .opencoupon -->

		</div> <!--end #cctor_coupon_container -->
	
	<?php } // End the If Expiration Date?>
	
<?php endwhile; // end the coupon creator loop ?>	