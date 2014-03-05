<?php
/*
Plugin name: Coupon Creator
Plugin URI: http://jesseeproductions.com/coupon_creator/
Version: 1.60
Description: This plugin creates a custom post type for coupons with a shortcode to display it on website and a single view template for printing.
Author: Brian Jessee
Author URI: http://jesseeproductions.com
License: GPL2
*/
/**
 *
 * Define the Plugin URL and Lets Go!
 *
 */
if ( ! defined( 'COUPON_PLUGIN_URL' ) )
	define( 'COUPON_PLUGIN_URL', WP_PLUGIN_URL . '/coupon-creator' );

if (!defined('CCTOR_COUPON_VERSION_KEY'))
    define('CCTOR_COUPON_VERSION_KEY', 'cctor_coupon_version');

if (!defined('CCTOR_COUPON_VERSION_NUM'))
    define('CCTOR_COUPON_VERSION_NUM', '1.60');

$cctor_new_version = '1.60';

if (get_option(CCTOR_COUPON_VERSION_KEY) != $cctor_new_version) {
    // Then update the version value
    update_option(CCTOR_COUPON_VERSION_KEY, $cctor_new_version);
}

/**
 * 
 * Load the Coupon Creator Shortcoce
 * 
 */
include_once 'lib/cc_coupon_loop_shortcode.php';

/**
 *
 * Register the Coupon CSS
 *
 */
	function cctor_register_style() {
		if (!is_admin()) {
			$cctor_style = plugin_dir_path( __FILE__ ).'css/cctor_coupon.css';
			wp_register_style('coupon_creator_css', plugins_url('/css/cctor_coupon.css' , __FILE__ ), false, filemtime($cctor_style));
		}
	}
	add_action('wp_print_styles', 'cctor_register_style');
/**
 *
 * Add Custom Image Sizes for Coupon on Site and Print Coupon so no resizing later
 *
 */
add_image_size('single_coupon', 300, 150, TRUE);
add_image_size('print_coupon', 400, 200, TRUE);

/**
 *
 * Localization
 * @ Version 1.50
 */
 function cctor_action_init() {
	load_plugin_textdomain('couponcreator', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('init', 'cctor_action_init');

/**
 *
 * Create the Coupon Creator Custom Post Type with Coupons
 *
 */
	add_action( 'init', 'register_cctor_coupon' );
		function register_cctor_coupon() {
			$labels = array(
				'name' => _x( 'Coupons', 'cctor_coupon' ),
				'singular_name' => _x( 'Coupon', 'cctor_coupon' ),
				'add_new' => _x( 'Add New', 'cctor_coupon' ),
				'add_new_item' => _x( 'Add New Coupon', 'cctor_coupon' ),
				'edit_item' => _x( 'Edit Coupon', 'cctor_coupon' ),
				'new_item' => _x( 'New Coupon', 'cctor_coupon' ),
				'view_item' => _x( 'View Coupon', 'cctor_coupon' ),
				'search_items' => _x( 'Search Coupons', 'cctor_coupon' ),
				'not_found' => _x( 'No coupons found', 'cctor_coupon' ),
				'not_found_in_trash' => _x( 'No coupons found in Trash', 'cctor_coupon' ),
				'parent_item_colon' => _x( 'Parent Coupon:', 'cctor_coupon' ),
				'menu_name' => _x( 'Coupons', 'cctor_coupon' ),
			);
			$args = array(
				'labels' => $labels,
				'hierarchical' => false,
				'description' => 'Creates a Coupon Post',
				//Add Support for Custom Meta Boxes and Show Custom Fields Just in Case
				'supports' => array( 'title','coupon_creator_meta_box','custom-fields' ),
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_icon' => COUPON_PLUGIN_URL . '/images/coupon_creator.png',
				'show_in_nav_menus' => false,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'has_archive' => true,
				'query_var' => true,
				'can_export' => true,
				'rewrite' => array('slug'=>'','with_front'=>false),
				'capability_type' => 'post'
			);
			//Register the Post Type
			register_post_type( 'cctor_coupon', $args );
		}

	// Add Custom Taxonomy Coupon Sites for Music Videos
	add_action( 'init', 'register_taxonomy_coupon_sites' );

	function register_taxonomy_coupon_sites() {

		$labels = array(
			'name' => _x( 'Coupon Category', 'cctor_coupon_category' ),
			'singular_name' => _x( 'Coupon Categories', 'cctor_coupon_category' ),
			'search_items' => _x( 'Search Coupon Category', 'cctor_coupon_category' ),
			'popular_items' => _x( 'Popular Coupon Category', 'cctor_coupon_category' ),
			'all_items' => _x( 'All Coupon Categories', 'cctor_coupon_category' ),
			'parent_item' => _x( 'Parent Coupon Categories', 'cctor_coupon_category' ),
			'parent_item_colon' => _x( 'Parent Coupon Categories:', 'cctor_coupon_category' ),
			'edit_item' => _x( 'Edit Coupon Categories', 'cctor_coupon_category' ),
			'update_item' => _x( 'Update Coupon Categories', 'cctor_coupon_category' ),
			'add_new_item' => _x( 'Add New Coupon Category', 'cctor_coupon_category' ),
			'new_item_name' => _x( 'New Coupon Categories Name', 'cctor_coupon_category' ),
			'separate_items_with_commas' => _x( 'Separate artist with commas', 'cctor_coupon_category' ),
			'add_or_remove_items' => _x( 'Add or remove artist', 'cctor_coupon_category' ),
			'choose_from_most_used' => _x( 'Choose from the most used artist', 'cctor_coupon_category' ),
			'menu_name' => _x( 'Coupon Category', 'cctor_coupon_category' ),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,

			'rewrite' => false,
			'query_var' => true
		);

		register_taxonomy( 'cctor_coupon_category', array('cctor_coupon'), $args );
	}

/**
 *
 * Plugin Activation Only Flush Rewrite for Correct Permalinks to Work
 * Coding from WordPress Codex on http://codex.wordpress.org/Function_Reference/register_post_type
 *
 */
function cctor_activate_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    register_cctor_coupon();
    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cctor_activate_rewrite_flush' );

/*
 *
 * Use Single Coupon Template from Plugin when creating the print version
 *
*/
function get_coupon_post_type_template($print_template) {
	 global $post;
	 if ($post->post_type == 'cctor_coupon') {
		  $print_template = dirname( __FILE__ ) . '/lib/single-coupon.php';
	 }
	 return $print_template;
}
//add_filter( "single_template", "get_coupon_post_type_template" ) ; removed @1.6 instead use template include
add_filter( 'template_include', 'get_coupon_post_type_template' );

/**
 *
 * Add Coupon Pop Up to Editor
 * Modified coding from post on themergency! http://themergency.com/adding-custom-buttons-to-the-wordpress-content-editor-part-1/
 *
 */
	if (is_admin()) {
		//Add Button for Coupons in Editor
		//add a button to the content editor, next to the media button
		//this button will show a popup that contains inline content
		add_action('media_buttons_context', 'add_cc_coupon_button');
		//add some content to the bottom of the page
		//This will be shown in the inline modal
		add_action('admin_footer', 'add_coupon_inline_popup');
		//action to add a custom button to the content editor
		function add_cc_coupon_button($context) {
		  //path to my icon
		  $img = plugins_url( '/images/coupon_creator.png' , __FILE__ );
		  //the id of the container I want to show in the popup
		  $container_id = 'coupon_container';
		  //our popup's title
		  $title = 'Insert Coupon';
  // do a version check for the new 3.5 UI
	$version    = get_bloginfo('version');

	if ($version < 3.5) {
		// show button for v 3.4 and below
		$context .= "<a class='thickbox' title='{$title}' href='#TB_inline?width=640&inlineId={$container_id}'><img src='{$img}' /></a>";
	} else {
		// display new ui button for 3.5
		$context .="<style>.cctor_insert_icon{
				background:url('{$img}') no-repeat top left;
				display: inline-block;
				height: 16px;
				margin: 0 2px 0 0;
				vertical-align: text-top;
				width: 16px;
				}
				.wp-core-ui a.cctor_insert_link{
				 padding-left: 0.4em;
				}
			 </style>
				<a class='thickbox button cctor_insert_link' id='add_jp_gallery'  title='{$title}' href='#TB_inline?width=640&inlineId={$container_id}'><span class='cctor_insert_icon'></span>Add Coupon</a>";
			}
		  return $context;
	} //End Insert Icon Creation

		function add_coupon_inline_popup() {
		?>
				<!--Script to insert Coupon ShortCode Into Editor -->
				<script>

					//Insert Shortcode into Editor
					function InsertCoupon(){
						var coupon_id = jQuery("#coupon_select").val();
							if (coupon_id == "loop") {
								var coupon_shortcode = "coupon";
								var coupon_category = jQuery("#coupon_category_select").val();
								var coupon_category = " category=\""+ coupon_category + "\" ";
							} else {
								var coupon_shortcode = "coupon";
								var coupon_category = "";
							}
						var coupon_name = jQuery("#coupon_select option[value='" + coupon_id + "']").text().replace(/[\[\]]/g, '');
						var cctor_align = jQuery("#coupon_align").val();
						var coupon_align = jQuery("#coupon_align option[value='" + cctor_align + "']").text().replace(/[\[\]]/g, '');
						window.send_to_editor("[" + coupon_shortcode + " couponid=\"" + coupon_id + "\"" + coupon_category + " coupon_align=\"" + cctor_align + "\" name=\"" + coupon_name + "\"]");
					}

					//Toggle Category Input when Loop Selected
					function show_category() {
						var coupon_select = document.getElementById("coupon_select");
						var coupon_selection = coupon_select.options[coupon_select.selectedIndex].value;

						var category_select = document.getElementById("coupon_category_select_container");

						if (coupon_selection == "loop") {
							category_select.style.visibility = "visible";
						}
						else {
							category_select.style.visibility = "hidden";
						}
					}
				</script>

				<style>
					#coupon_category_select_container {
						visibility: hidden;
					}
				</style>

			<!--Start Thickbox Popup -->
			<div id="coupon_container" style="display:none;">
			  <h2>Coupon Creator Shortcode:</h2>
				<?php
					 $querycoupon = new WP_Query( 'post_status=publish&post_type=cctor_coupon&posts_per_page=-1' );
					// The Coupon Loop
					if ($querycoupon->have_posts()) {
				?>
				<div style="padding:15px;">
					<!--Create a Select Box with Coupon Titles -->
					<label for="coupon_select">Select Loop or an Individual Coupon</label>
						<select name="coupon_select_box" id="coupon_select" onchange="show_category()">
							<option value="#" ></option>
							<option value="loop" >Coupon Loop</option>
							<?php
							while ($querycoupon->have_posts()) {
							$querycoupon->the_post(); ?>
								<!--Adding the Value as ID for the Shortcode and the Title for Humans-->
								<option value="<?php the_ID(); ?>" ><?php the_title(); ?></option>

							<?php } ?>
						</select><br> <!--End Select Box Coupons-->

					<!--Create a Select Box for Categories -->
					<div id="coupon_category_select_container"><br>
						<label for="coupon-categories">Select a Coupon Category to use in the Loop</label>
							<select id="coupon_category_select" name="coupon_category_select">
							<option value="#" ></option>
							 <option value="">All Categories</option>
							 <?php
								$values = array(
								  'orderby' => 'name',
								  'order' => 'ASC',
								  'echo' => 1,
								  'selected' => $kat = get_query_var( 'cat' ),
								  'name' => 'cat',
								  'id' => '',
								  'taxonomy' => 'cctor_coupon_category'
								 );
							  $categories = get_categories($values);
							  foreach ($categories as $category) {
								$option = '<option value="'.$category->name.'">';
								$option .= $category->cat_name;
								$option .= '</option>';
								echo $option;
							  }
							 ?>
							</select> <!--End Select Box Categories-->
					</div><br>
					<!--Create a Select Box for Align -->
					<label for="coupon_align">Select How to Align the Coupon(s)</label>
						<select name="coupon_align_select_box" id="coupon_align">
							 <option value="cctor_alignnone">None</option>
							 <option value="cctor_alignleft">Align Left</option>
							 <option value="cctor_alignright">Align Right</option>
							 <option value="cctor_aligncenter">Align Center</option>
						</select><br> <!--End Select Box Align -->
				</div> <!--End Div -->
				<br/>

				<div style="padding:15px;">
					<!--Insert into Editor Button that Calls Script-->
					<input type="button" id="coupon-submit" onclick="InsertCoupon();" class="button-primary" value="Insert Coupon" name="submit" />
				</div>

				<?php }  ?>
			</div> <!--End #coupon_container -->
		<?php }

	} // End Insert into Editor Coding
/**
 *
 * Custom Coupon Meta Box
 * Modifed coding from the Tutorials of WP Tuts Plus - http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
 * Color Picker coding modifed from Deluxe Tips - http://www.deluxeblogtips.com/meta-box/
 *
 */
// Need some Scripts and Styling for Editing
add_action('admin_enqueue_scripts','load_admin_coupon_script_style');
// Only Load Scripts and CSS on Coupon Edit Page
function load_admin_coupon_script_style() {
	global $pagenow, $typenow;
	if (empty($typenow) && !empty($_GET['post'])) {
		$post = get_post($_GET['post']);
		$typenow = $post->post_type;
	}
	if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='cctor_coupon') {
	
	//Styles
	//Date Picker CSS
	$cctor_datapicker_css = plugin_dir_path( __FILE__ ).'css/cctor.ui.datepicker.css';
	wp_register_style('cctor_meta_css', plugins_url('/css/cctor.ui.datepicker.css' , __FILE__ ), false, filemtime($cctor_datapicker_css));
	
	//Color Picker CSS
	$cctor_colorpicker_css = plugin_dir_path( __FILE__ ).'css/cctor_color.css';
	wp_register_style('cc_color_css', plugins_url('/css/cctor_color.css' , __FILE__ ), array( 'farbtastic' ), filemtime($cctor_colorpicker_css));

	wp_enqueue_style('cctor_meta_css');
	wp_enqueue_style('cc_color_css');
	wp_enqueue_style('thickbox');  //Image Upload CSS
	
	//Scripts
	//Load Color Picker and Image Upload JS
	$cctor_coupon_meta_js = plugin_dir_path( __FILE__ ).'js/cctor_coupon_meta.js';
	wp_register_script('cctor_coupon_meta_js',  plugins_url('/js/cctor_coupon_meta.js', __FILE__ ) ,array('jquery', 'media-upload','thickbox','farbtastic'), filemtime($cctor_coupon_meta_js), true);		
	wp_enqueue_script('cctor_coupon_meta_js');
	wp_enqueue_script('jquery-ui-datepicker');
	
	//Color Box For How to Videos
	$cctor_colorbox_css = plugin_dir_path( __FILE__ ).'colorbox/colorbox.css';
	wp_register_style('cctor_colorbox_css', plugins_url('/colorbox/colorbox.css' , __FILE__ ), false, filemtime($cctor_colorbox_css));	
	
	$cctor_colorbox_js = plugin_dir_path( __FILE__ ).'colorbox/jquery.colorbox-min.js';
	wp_register_script('cctor_colorbox_js',  plugins_url('/colorbox/jquery.colorbox-min.js', __FILE__ ) ,array('jquery'), filemtime($cctor_colorbox_js), true);				
	
	wp_enqueue_style('cctor_colorbox_css');
	wp_enqueue_script('cctor_colorbox_js');

			//Load Script if Date Picker Meta Input
		add_action('admin_head','cc_datepicker_script');
		function cc_datepicker_script() {
			$output = '<script type="text/javascript">
						jQuery(function() {jQuery(".datepicker").datepicker();});
						jQuery(document).ready(function(){
						jQuery(".youtube_colorbox").colorbox({rel: "how_to_videos", current: "video {current} of {total}", iframe:true, width:"90%", height:"90%"});
						});';
			$output .=	'</script>';

			echo $output;
		}

	}
}

		// Add the Meta Box
		function add_coupon_creator_meta_box() {
			add_meta_box(
				'coupon_creator_meta_box', // $id
				'Coupon Settings', // $title
				'show_coupon_creator_meta_box', // $callback
				'cctor_coupon', // $page
				'normal', // $context
				'high'); // $priority
		}
		add_action('add_meta_boxes', 'add_coupon_creator_meta_box');

		// Field Array  cctor_
		$prefix = 'cctor_';
		$coupon_creator_meta_fields = array(
			array(
				'label' => 'Discount',
				'desc' => 'Enter the discount amount  - 30%, Buy One Get One Free, etc...',
				'id' => $prefix . 'amount',
				'type'  => 'text'
			),
			array(
				'label' => 'Discount Background Color',
				'desc'  => 'Choose background color',
				'id' => $prefix . 'colordiscount',
				'type' => 'color', // color
				'value' => "#4377df",
			),
			array(
				'label' => 'Discount Text Color',
				'desc'  => 'Choose color for discount text',
				'id' => $prefix . 'colorheader',
				'type' => 'color', // color
				'value' => "#4377df",
			),
			array(
				'label' => 'Border Color',
				'desc'  => 'Choose inside solid border color',
				'id' => $prefix . 'bordercolor',
				'type' => 'color', // color
				'value' => "#4377df",
			),
			array(
				'label' => 'Terms:',
				'desc' => 'Enter the terms of the discount',
				'id' => $prefix . 'description',
				'type'  => 'textarea'  //textarea
			),
			array(
				'label' => 'Expiration Date:',
				'id' => $prefix . 'expiration',
				'desc' => 'The coupon will not display without the date and will not display on your site after the date.',
				'type'  => 'date'  //datepicker
			),
			array(
				'label'=> 'Date Format',
				'desc'  => 'Check this to change date format to Day / Month / Year (default is Month / Day / Year).',
				'id'    => $prefix.'date_format',
				'type'  => 'checkbox'
			),
			 array(
				'label'=> 'Ignore Expiration Date',
				'desc'  => 'Check this to ignore the expiration date.',
				'id'    => $prefix.'ignore_expiration',
				'type'  => 'checkbox'
			),
			array(
				'label'  => 'Image',
				'desc'  => 'Upload and insert an image as a coupon - Image Size 400 pixels by 200 pixels',
				'id'    => $prefix.'image',
				'type'  => 'image'  //image uploader
			),
			array(
				'label'  => 'Coupon Creator How to Videos:',
				'id'    => $prefix.'cctor_videos',
				'type'  => 'cctor_videos'  //image uploader
			)
		);

		// The Callback  to Show the Meta Boxes
		function show_coupon_creator_meta_box() {
		global $coupon_creator_meta_fields, $post;
		// Use nonce for verification
		echo '<input type="hidden" name="coupon_creator_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ($coupon_creator_meta_fields as $field) {
				// get value of this field if it exists for this post
				$meta = get_post_meta($post->ID, $field['id'], true);
				// begin a table row with
				echo '<tr>
						<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
						<td>';
						switch($field['type']) {
							// text
							case 'text':
								echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
							break;
							// textarea
							case 'textarea':
								echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
									<br /><span class="description">'.$field['desc'].'</span>';
							break;
							// checkbox
							case 'checkbox':
								echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
									<label for="'.$field['id'].'">'.$field['desc'].'</label>';
							break;
							// image
							case 'image':
								$image = COUPON_PLUGIN_URL .'/images/optional_coupon.png';
								echo '<span class="cctor_coupon_default_image" style="display:none">'.$image.'</span>';
								if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }
								echo    '<input name="'.$field['id'].'" type="hidden" class="cctor_coupon_upload_image" value="'.$meta.'" />
											<img src="'.$image.'" class="cctor_coupon_preview_image" alt="" /><br />
												<input class="cctor_coupon_image_button button" type="button" value="Choose Image" />
												<small> <a href="#" class="cctor_coupon_clear_image_button">Remove Image</a></small>
												<br clear="all" /><span class="description">'.$field['desc'].'';
							break;
							// color
							case 'color':
								echo '<input class="cc_color" type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
									<div class="cc_color-picker"></div>
									<br /><span class="description">'.$field['desc'].'</span>';
							break;
							// date
							case 'date':
								echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
										<br /><span class="description">'.$field['desc'].'</span>';
							break;
								// date
							case 'cctor_videos':
								echo "<p>
<a href='http://www.youtube.com/embed/9Uui5fNqg_I?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to create a coupon</a><br/>
<a href='http://www.youtube.com/embed/pS37ahKChqc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to create an image coupon</a><br/>
<a href='http://www.youtube.com/embed/mMAJq47AjzE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to insert and align coupons</a>
<br/>(click to open)</p>";
							break;
							 //wysiwyg
							case 'wysiwyg':
								wp_editor( $meta ? $meta : $field['std'], $field['id'], isset( $field['options'] ) ? $field['options'] : array() );
							echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
							break;
						} //end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table>'; // end table
		}

		// Save the Coupon Data
		function save_coupon_creator_meta($post_id) {
			global $coupon_creator_meta_fields;

			// verify nonce
			if ( !isset($_POST['coupon_creator_meta_box_nonce']) || !wp_verify_nonce( $_POST['coupon_creator_meta_box_nonce'], basename(__FILE__) ))
				return $post_id;
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return $post_id;
			// check permissions
			if ('page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id))
					return $post_id;
				} elseif (!current_user_can('edit_post', $post_id)) {
					return $post_id;
			}

			// loop through fields and save the data
			foreach ($coupon_creator_meta_fields as $field) {
					$old = get_post_meta($post_id, $field['id'], true);
					$new = $_POST[$field['id']];
					if ($new && $new != $old) {
						update_post_meta($post_id, $field['id'], $new);
					} elseif ('' == $new && $old) {
						delete_post_meta($post_id, $field['id'], $old);
					}
			} // end foreach

		}
		// Save Meta Data and were done here
		add_action('save_post', 'save_coupon_creator_meta');
?>