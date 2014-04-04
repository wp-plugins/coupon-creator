<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );


	/*
	* Coupon Creator Class
	* @version 1.70
	*/
	class Coupon_Creator_Plugin {
		/*
		* Plugin file
		* @var string
		* @version 1.70
		*/
		public static $file;
		/*
		* Plugin dirname
		* @var string
		* @version 1.70
		*/
		public static $dirname;

	/***************************************************************************/

		/*
		* Bootstrap
		* @version 1.70
		*/
		public static function bootstrap( $file ) {
			self::$file    = $file;
			self::$dirname = dirname( $file );
		
			register_activation_hook( $file, array( __CLASS__, 'activate' ) );
			register_deactivation_hook( $file, array( __CLASS__, 'deactivate' ) );
			
			add_action( 'init',   array( __CLASS__, 'init' ) );
			
			Coupon_Creator_Plugin_Admin::bootstrap();
			//Localization
			add_action('plugins_loaded', array( __CLASS__, 'i18n' ));
		}

	/***************************************************************************/
		
		/*
		* Initialize Coupon Creator
		* @version 1.70
		*/
		public static function init() {
			
			//Load Files
			require_once CCTOR_PATH. 'inc/taxonomy.php';

			// if no custom slug use this base slug
			$slug = get_option( 'cctor_coupon_base' );
			$slug = empty( $slug ) ? _x( 'cctor_coupon', 'slug', 'coupon_creator' ) : $slug;
			
			//Coupon Creator Custom Post Type
			register_post_type( 'cctor_coupon', array(
				'labels'             => array(
					'name'               => _x( 'Coupons', 'coupon_creator' ), 
					'singular_name'      => _x( 'Coupon', 'coupon_creator' ), 
					'add_new'            => _x( 'Add New', 'coupon_creator' ), 
					'add_new_item'       => __( 'Add New Coupon', 'coupon_creator' ), 
					'edit_item'          => __( 'Edit Coupon', 'coupon_creator' ), 
					'new_item'           => __( 'New Coupon', 'coupon_creator' ), 
					'view_item'          => __( 'View Coupon', 'coupon_creator' ), 
					'search_items'       => __( 'Search Coupons', 'coupon_creator' ), 
					'not_found'          => __( 'No coupons found', 'coupon_creator' ), 
					'not_found_in_trash' => __( 'No coupons found in Trash', 'coupon_creator' ),  
					'parent_item_colon'  => __( 'Parent Coupon:', 'coupon_creator' ), 
					'menu_name'          => __( 'Coupons', 'coupon_creator' ),
				),
				'hierarchical'		 => false,
				'description' 		 => 'Creates a Coupon as a Custom Post Type',
				'public'             => true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => false,
				'show_in_menu'       => true,
				'query_var'          => true,
				'can_export'		 => true,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'rewrite'            => array( 'slug' => $slug ), 
				'menu_icon'          => CCTOR_URL . 'admin/images/coupon_creator.png',
				//Supported Meta Boxes
				'supports'           => array( 'title', 'coupon_creator_meta_box','custom-fields' ),
			) );
			
			//Load Coupon Creator Custom Taxonomy
			coupon_creator_create_taxonomies();
			
			//Register Coupon Style
			add_action('wp_print_styles',  array( __CLASS__, 'cctor_register_style' ));
			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );	
			//Create the Shortcode
			add_shortcode( 'coupon', array(  __CLASS__, 'cctor_allcoupons_shortcode' ) );
			//Load Single Coupon Template
			add_filter( 'template_include', array(  __CLASS__, 'get_coupon_post_type_template') );
		}
		
	/***************************************************************************/
	
	public static function i18n() {

	   $cctor_local_path = dirname( plugin_basename( self::$file ) ) . '/languages/';
       load_plugin_textdomain('coupon_creator', false, $cctor_local_path ); 
	 
	}
	
	/***************************************************************************/
		/**
		 * Activate
		 */
		public static function activate() {
			// Flush rewrite rules on activation
			// @see https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
			add_action( 'init', 'flush_rewrite_rules', 20 );
		}
		
		/**
		 * Deactivate
		 */
		public static function deactivate() {
			// Flush rewrite rules on deactivation
			// @see https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
			add_action( 'init', 'flush_rewrite_rules', 20 );
		}	
		
	/***************************************************************************/	

		/*
		* Register Coupon Creator CSS
		* @version 1.00
		*/
		public static function cctor_register_style() {
			if (!is_admin()) {
				$cctor_style = CCTOR_PATH.'css/cctor_coupon.css';
				wp_register_style('coupon_creator_css',  CCTOR_URL . '/css/cctor_coupon.css', false, filemtime($cctor_style));
			}
		}
		
		/*
		* Register Coupon Creator Image Sizes
		* @version 1.00
		*/		
		public static function cctor_add_image_sizes() {
			add_image_size('single_coupon', 300, 150, TRUE);
			add_image_size('print_coupon', 400, 200, TRUE);
		}
		
	/***************************************************************************/		
		/*
		* Register Coupon Creator Shortcode
		* @version 1.00
		*/
		public static function cctor_allcoupons_shortcode($atts) {
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
				list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );
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
								$expirationco = date("d/m/Y", $cc_expiration_date);
							}
						$alloutput .=	"<div class='cctor_expiration'>".__('Expires on:','coupon_creator')."&nbsp;".$expirationco."</div>";
							} //end If Expiration
						$alloutput .=	"</div> <!--end .coupon --></div> <!--end .cctor_coupon -->";
						}
					//Add Link to Open in Print View
					$alloutput .=	"<div class='cctor_opencoupon'><a rel='coupon' href='".$permalink." 'onclick='window.open(this.href);return false;'>".__('Click to Open in Print View','coupon_creator')."</a></div><!--end .opencoupon -->";
					$alloutput .= 	"</div><!--end .cctor_coupon_container -->";
				} else {
					$alloutput .=  "<!-- ".get_the_title()." has expired on ".$expirationco." -->";
				}//End Coupon Display

			} //End While

			/* Restore original Post Data */
			wp_reset_postdata();

			// Return Variables
			return $alloutput;
		} //end cctor_allcoupons_shortcode

	/***************************************************************************/
		/*
		* Use Single Coupon Template from Plugin when creating the print version
		* @version 1.00
		*/
		function get_coupon_post_type_template($print_template) {
			 global $post;
			 if ($post->post_type == 'cctor_coupon') {
				  $print_template = CCTOR_PATH. 'templates/single-coupon.php';
			 }
			 return $print_template;
		}

	/***************************************************************************/

	} //end Coupon_Creator_Plugin Class

