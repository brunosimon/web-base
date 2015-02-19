jQuery(document).ready(function($) {

	var $Form = $(".wauc_form");

	$('.handlediv' , $Form).live( 'click', function() {
		$(this).parent().toggleClass('closed');
	});

	$('.handlediv' , 'body.wp-admin-ui-customize_page_wp_admin_ui_customize_admin_bar #can_menus').live( 'click', function() {
		$(this).parent().toggleClass('closed');
	});

});
