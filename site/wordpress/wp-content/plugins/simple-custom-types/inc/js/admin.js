jQuery(document).ready(function() {
	// Visibility of advanced capabilities
	jQuery('#capability_type').change(function(){
		if( jQuery(this).children('option:selected').val() == 'custom' ) {
			jQuery('#adv-capabilities').show();
		} else {
			jQuery('#adv-capabilities').hide();
		}
	});
	
	// Visibility of very advanced capabilities
	jQuery('a.display-advanced-caps').bind('click', function(event) {
		event.preventDefault();
		
		jQuery('#adv-capabilities-adv').toggle();
	});
});