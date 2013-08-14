<?php
/*
* 	Coupon Creator Loop Shortcode 
* 	@since 1.40
*/

// Create the Shortcode
add_shortcode('coupon', 'cctor_allcoupons_shortcode');
//  Start Function for Coupon Creator Shortcode 
function cctor_allcoupons_shortcode($atts) {
	   //Load Stylesheet for Coupon Creator when Shortcode Called
		 wp_enqueue_style('coupon_creator_css'); 
	   //Coupon ID is the Custom Post ID
	   extract(shortcode_atts(array(
		"totalcoupons" => '-1',
		"couponid" => '',
		"coupon_align" => 'cctor_alignnone',
		"category" => ''
		), $atts ) );
		
		// Setup Query for Either Single Coupon or a Loop
			$args = array(
			'p' => $couponid,
			'posts_per_page' => $totalcoupons,
			'cctor_coupon_category' => $category,
			'post_type' => 'cctor_coupon',
			'post_status' => 'publish'
		);
			$alloutput = '';

			$allcouponpost = new WP_Query($args);
			
		// The Coupon Loop						  
		while ($allcouponpost->have_posts()) {
		
		$allcouponpost->the_post();
		$couponid = $allcouponpost->post->ID;
				
		// Custom Fields from Post Type
		$couponborder = get_post_meta($couponid, 'cctor_couponborder', true);
		$amountco = get_post_meta($couponid, 'cctor_amount', true);
		$colordiscount = get_post_meta($couponid, 'cctor_colordiscount', true);
		$colorheader = get_post_meta($couponid, 'cctor_colorheader', true);
		$expirationco = get_post_meta($couponid, 'cctor_expiration', true);
		$bordercolor = get_post_meta($couponid, 'cctor_bordercolor', true);
		$couponimage_id = get_post_meta($couponid, 'cctor_image', true);
		$couponimage = wp_get_attachment_image_src($couponimage_id, 'single_coupon');
		$couponimage = $couponimage[0];
		$permalink = get_permalink( $couponid ); 
		$descriptionco = get_post_meta($couponid, 'cctor_description', true);
		$daymonth_date_format = get_post_meta($couponid, 'cctor_date_format', true); //get the ignore expiration checkbox value
		//Check Expiration if past date then exit
		$cc_blogtime = current_time('mysql'); 
		list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $cc_blogtime );
		$cc_today = strtotime($today_month."/".$today_day."/". $today_year);
		$cc_expiration_date = strtotime($expirationco);
		$ignore_expiration = get_post_meta($couponid, 'cctor_ignore_expiration', true); //get the ignore expiration checkbox value
		
		if ($cc_expiration_date >= $cc_today || $ignore_expiration == "on" ) { // Display coupon if expiration date is in future or if ignore box checked
			//Start Single View 
			$alloutput .=  "<div class='cctor_coupon_container ". $coupon_align ."'>";
				// If Image Use as Coupon
				if ($couponimage) {
				$alloutput .=  "<a target='_blank' href='".$permalink."' title='Click to Open in Print View'><img class='cctor_coupon_image' src='".$couponimage."' alt=''title=''></a>";
				
				//No Image Create Coupon		
				} else {
				$alloutput .=  "<div class='cctor_coupon'>";
				$alloutput .=  "<div class='cctor_coupon_content' style='border-color:".$bordercolor."!important;'>";	
				$alloutput .=  "<h3 style='background-color:".$colordiscount."!important; color:".$colorheader."!important;'>" . $amountco . "</h3>";
				$alloutput .=	"<div class='cctor_deal'>".$descriptionco."</div>";
				if ($expirationco) {  // Only Display Expiration if Date
					if ($daymonth_date_format == "on" ) { //Change to Day - Month Style
						$expirationco = date("d-m-Y", $cc_expiration_date); 
					}
				$alloutput .=	"<div class='cctor_expiration'>Expires on:&nbsp;".$expirationco."</div>";
					} //end If Expiration
				$alloutput .=	"</div> <!--end .coupon --></div> <!--end .cctor_coupon -->";
				}
			//Add Link to Open in Print View	
			$alloutput .=	"<div class='cctor_opencoupon'><a rel='coupon' href='".$permalink." 'onclick='window.open(this.href);return false;'>Click to Open in Print View</a></div><!--end .opencoupon -->";
			$alloutput .= 	"</div><!--end .cctor_coupon_container -->";	
		} //End Coupon Display
					
	} //End While

		// Return Variables
		return $alloutput; 
}
?>