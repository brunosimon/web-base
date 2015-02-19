<?php
class SimpleCustomTypes_Admin_Conversion {
	/**
	 * Constructor
	 *
	 */
	function __construct() {
		global $messages;
		
		// Add message for conversion
		$messages[991] = __('Item(s) converted on another post type with success.', 'simple-customtypes');
		
		// Add action on edit posts page
		add_filter( 'admin_footer', array(&$this, 'addActions') );
		add_action( 'admin_init', array(&$this, 'listenConversion' ) );
	}
	
	/**
	 * Listen POST datas for make bulk posts conversion to new post type
	 */
	function listenConversion() {
		global $pagenow, $wpdb;
		
		if ( $pagenow != 'edit.php' ) 
			return false;
		
		// Default values for CPT
		$typenow = ( isset($_REQUEST['post_type']) ) ? $_REQUEST['post_type'] : 'post';
		
		if ( isset($_REQUEST['action']) && substr($_REQUEST['action'], 0, strlen('convert_cpt')) == 'convert_cpt' ) {
			check_admin_referer( 'bulk-posts' );
			
			// Source CPT
			$source_cpt = get_post_type_object( $typenow );
			if ( !current_user_can( $source_cpt->cap->edit_posts ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );
			
			// Destination CPT
			$destination_cpt = get_post_type_object( substr($_REQUEST['action'], strlen('convert_cpt')+1) );
			if ( !current_user_can( $destination_cpt->cap->edit_posts ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );
			
			// Loop on posts
			foreach( (array) $_REQUEST['post'] as $post_id ) {
				// Change the post type
				$object = get_post_to_edit( $post_id );
				$object->post_type = $destination_cpt->name;
				wp_update_post( (array) $object );
				
				// Clean object cache
				clean_post_cache($post_id);
			}
			
			$location = 'edit.php?post_type=' . $typenow;
			if ( $referer = wp_get_referer() ) {
				if ( false !== strpos( $referer, 'edit.php' ) )
					$location = $referer;
			}
			
			$location = add_query_arg( 'message', 991, $location );
			wp_redirect( $location );
			exit;
		}
	}
	
	/**
	 * Add JS on footer WP Admin for add option in select bulk action list
	 */
	function addActions() {
		global $pagenow;
		
		// Default values for CPT
		$current_cpt = ( isset($_REQUEST['post_type']) ) ? $_REQUEST['post_type'] : 'post';
		
		if ( $pagenow == 'edit.php' ) {
			?>
			<script type="text/javascript">
				<?php foreach( get_post_types( array('public' => true, 'show_ui' => true), 'objects' ) as $post_type ) :
					if ( $post_type->name == $current_cpt ) continue; // Not itself...
					if ( !current_user_can( $post_type->cap->edit_posts ) ) continue; // User can ?
					?>
					jQuery('div.actions select').append('<option value="convert_cpt-<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html(sprintf(__('Convert to %s', 'simple-taxonomy'), $post_type->labels->name)); ?></option>');
				<?php endforeach; ?>
			</script>
			<?php
		}
	}
}
?>