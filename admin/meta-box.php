<?php
/*
* Coupon Creator Meta Boxes
* @version 1.7
*/
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );
	
	
wp_nonce_field( 'coupon_creator_save_post', 'coupon_creator_nonce' );

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
		'value' => "#000000",
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

global $post;
// Use nonce for verification
//echo '<input type="hidden" name="coupon_creator_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

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
					// image using Media Manager from WP 3.5 and greater
					case 'image':
						$image = plugins_url('/images/optional_coupon.png' , __FILE__ );
						echo '<img id="'.$field['id'].'" class="cctor_coupon_default_image" style="display:none" src="'.$image.'">';
						//Check existing field and if numeric
						if (is_numeric($meta)) { 
							$image = wp_get_attachment_image_src($meta, 'medium'); 
							$image = $image[0];
						} 
						echo    '<img src="'.$image.'" id="'.$field['id'].'" class="cctor_coupon_image" /><br />
								<input name="'.$field['id'].'" id="'.$field['id'].'" type="hidden" class="upload_coupon_image" type="text" size="36" name="ad_image" value="'.$meta.'" /> 
								<input id="'.$field['id'].'" class="coupon_image_button" type="button" value="Upload Image" />
								<small> <a href="#" id="'.$field['id'].'" class="cctor_coupon_clear_image_button">Remove Image</a></small>
								<br /><span class="description">'.$field['desc'].'</span>';
					break;					
					// color
					case 'color':
						echo '<input class="color-picker" type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" data-default-color="'.$field['value'].'"/>
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
		