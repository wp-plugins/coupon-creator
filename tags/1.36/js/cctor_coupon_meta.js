/**
 * Update color picker element
 * Used for static & dynamic added elements (when clone)
 */
function cc_update_color_picker()
{
	var $ = jQuery;
	$( '.cc_color-picker' ).each( function()
	{
		var $this = $( this ),
			$input = $this.siblings( 'input.cc_color' );
		// Make sure the value is displayed
		if ( ! $input.val() )
			$input.val( '#' );
		$this.farbtastic( $input );
	} );
}
jQuery( document ).ready( function($)
{
	$( '.cc_color' ).focus( function()
	{
		$( this ).siblings( '.cc_color-picker' ).show();
		return false;
	} ).blur( function() {
		$( this ).siblings( '.cc_color-picker' ).hide();
		return false;
	} );
	cc_update_color_picker();
} );
		jQuery(function(jQuery) {  
			jQuery('.cctor_coupon_image_button').click(function() {  
				formfield = jQuery(this).siblings('.cctor_coupon_upload_image');  
				preview = jQuery(this).siblings('.cctor_coupon_preview_image');  
				tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=1');  
				window.send_to_editor = function(html) {  
					imgurl = jQuery('img',html).attr('src');  
					classes = jQuery('img', html).attr('class');  
					id = classes.replace(/(.*?)wp-image-/, '');  
					formfield.val(id);  
					preview.attr('src', imgurl);  
					tb_remove();  
				}  
				return false;  
			});  
		  
			jQuery('.cctor_coupon_clear_image_button').click(function(e){
				e.preventDefault();
				var defaultImage = jQuery(this).parent().siblings('.cctor_coupon_default_image').text();  
				jQuery(this).parent().siblings('.cctor_coupon_upload_image').val('');  
				jQuery(this).parent().siblings('.cctor_coupon_preview_image').attr('src', defaultImage);  
				return false;  
			});  
		  
		});