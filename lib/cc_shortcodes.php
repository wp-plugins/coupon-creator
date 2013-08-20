<?php
/*
* 	Depreciated See cc_coupon_loop_shortcode.php
*	@since 1.40
*
* 	Coupon Creator Shortcode
*/
// Create the Shortcode
add_shortcode('coupon', 'cctor_coupon_shortcode');
//  Start Function for Coupon Creator Shortcode 
function cctor_coupon_shortcode($atts) {

	   //Load Stylesheet for Coupon Creator when Shortcode Called
		 wp_enqueue_style('coupon_creator_css'); 
		 
	   //Coupon ID is the Custom Post ID
	   extract(shortcode_atts(array(
		"couponid" => '0',
		"coupon_align" => 'cctor_alignnone'
		), $atts ) );
		// Call One post using ID from shortcode and is published and post type coupon
			$args = array(
			'p' => $couponid,
			'numberposts' => 1,
			'post_type' => 'cctor_coupon',
			'post_status' => 'publish'
		);
		$couponpost = get_posts( $args );
			
		foreach( $couponpost as $post ) :
		
		
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
		$daymonth_date_format = get_post_meta($post->ID, 'cctor_date_format', true); //get the ignore expiration checkbox value

		//Check Expiration if past date then exit
		$cc_blogtime = current_time('mysql'); 
		list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $cc_blogtime );
		$cc_today = strtotime($today_month."/".$today_day."/". $today_year);
		$cc_expiration_date = strtotime($expirationco);
		$ignore_expiration = get_post_meta($post->ID, 'cctor_ignore_expiration', true); //get the ignore expiration checkbox value
		
		if ($cc_expiration_date >= $cc_today || $ignore_expiration == "on" ) { // Display coupon if expiration date is in future or if ignore box checked
			$output = '';	
			//Start Single View 
			$output .=  "<div class='cctor_coupon_container ". $coupon_align ."'>";
				// If Image Use as Coupon
				if ($couponimage) {
				$output .=  "<a href='".$permalink."' target='_blank' title='Click to Open in Print View'><img class='cctor_coupon_image' src='".$couponimage."' alt=''title=''></a>";
				
				//No Image Create Coupon		
				} else {
				$output .=  "<div class='cctor_coupon'>";
				$output .=  "<div class='cctor_coupon_content' style='border-color:".$bordercolor."!important;'>";	
				$output .=  "<h3 style='background-color:".$colordiscount."!important; color:".$colorheader."!important;'>" . $amountco . "</h3>";
				$output .=	"<div class='cctor_deal'>".$descriptionco."</div>";
				if ($expirationco) {  // Only Display Expiration if Date
					if ($daymonth_date_format == "on" ) { //Change to Day - Month Style
						$expirationco = date("d-m-Y", $cc_expiration_date); 
					}
				$output .=	"<div class='cctor_expiration'>Expires on:&nbsp;".$expirationco."</div>";
					} //end If Expiration
				$output .=	"</div> <!--end .coupon --></div> <!--end .cctor_coupon -->";
				}
			//Add Link to Open in Print View	
			$output .=	"<div class='cctor_opencoupon'><a href='".$permalink."' target='_blank'>Click to Open in Print View</a></div><!--end .opencoupon -->";
			$output .= 	"</div><!--end .cctor_coupon_container -->";	
		} 
       
	endforeach; 
		// Return Variables
		return $output; 
}
?>