<?php
class SimpleCustomTypes_Client {
	/**
	 * Constructor client, register cpt
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function simplecustomtypes_client() {
		add_action( 'init', array(&$this, 'initCustomTypes'), 0 );
	}
	
	/**
	 * Register custom post type from DB settings
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function initCustomTypes() {
		// Get current options
		$current_options = get_option( SCUST_OPTION );
		foreach( (array) $current_options['customtypes'] as $custom_type ) {
			// Register post_type
			register_post_type( $custom_type['name'], $this->prepareArgs($custom_type) );
		}
	}
	
	/**
	 * Prepare ARGS
	 */
	function prepareArgs( $custom_type ) {
		// Clean string values !
		foreach( (array) $custom_type as $field => $value ) {
			if ( isset($custom_type[$field]) && is_string($custom_type[$field]) ) { // Isset, juste clean values
				$custom_type[$field] = trim(stripslashes($custom_type[$field]));
			} elseif ( isset($custom_type[$field]) && is_array($custom_type[$field]) ) { // Isset, but dispatch array
				foreach( $custom_type[$field] as $k => $_v ) {
					if ( is_string($_v) ) {
						$custom_type[$field][$k] = trim(stripslashes($_v));
					} else {
						$custom_type[$field][$k] = $_v;
					}
				}
			}
		}
		
		// Empty query_var ? use name
		$custom_type['query_var'] = trim($custom_type['query_var']);
		if ( empty($custom_type['query_var']) ) {
			$custom_type['query_var'] = $custom_type['name'];
		}
		
		// Custom capability_type
		if ( $custom_type['capability_type'] == 'custom' ) {
			if ( isset($custom_type['capability_type_custom']) && !empty($custom_type['capability_type_custom']) ) {
				$custom_type['capability_type'] = explode(',', $custom_type['capability_type_custom']);
				if ( count($custom_type['capability_type']) == 1 ) {
					$custom_type['capability_type'] = current($custom_type['capability_type']);
				} else {
					$custom_type['capability_type'] = array_map( 'trim', $custom_type['capability_type'] );
					$custom_type['capability_type'] = array_slice( $custom_type['capability_type'], 0, 2 );
				}
			} else {
				$custom_type['capability_type'] = 'post';
			}
		}
		
		// Capabilities
		if ( in_array($custom_type['capability_type'], array('post', 'page')) ) {
			$custom_type['capabilities'] = array();
		}
		
		// Clean caps, and remove empty
		$custom_type['capabilities'] = array_map( 'trim', $custom_type['capabilities'] );
		$custom_type['capabilities'] = array_filter($custom_type['capabilities']);
		
		// CPT has archive
		$custom_type['has_archive'] = (boolean) $custom_type['has_archive'];
		if ( $custom_type['has_archive'] && !empty($custom_type['archive_slug']) ) {
			$custom_type['has_archive'] = $custom_type['archive_slug'];
		}
		
		// Rewriting
		if ( (boolean) $custom_type['rewrite'] == true ) {
			$custom_type['rewrite'] = array( 'slug' => $custom_type['query_var'], 'with_front' => true, 'pages' => true, 'feeds' => $custom_type['has_archive'] );
		} else {
			$custom_type['rewrite'] = false;
		}
		
		// Taxonomies
		if ( empty($custom_type['taxonomies']) ) {
			$custom_type['taxonomies'] = array();
		} else {
			$custom_type['taxonomies'] = (array) $custom_type['taxonomies'];
			if ( empty($custom_type['taxonomies']) ) {
				$custom_type['taxonomies'] = array();
			}
		}

		// Icons ?
		if ( empty($custom_type['menu_icon']) ) {
			$custom_type['menu_icon'] = false;
		}
		
		// Build args for register function
		$args = array(
			'labels' 				=> (array) $custom_type['labels'],
			'description' 			=> $custom_type['description'],
			'publicly_queryable' 	=> (boolean) $custom_type['publicly_queryable'],
			'exclude_from_search' 	=> (boolean) $custom_type['exclude_from_search'],
			'map_meta_cap'			=> (boolean) $custom_type['map_meta_cap'],
			'capability_type' 		=> $custom_type['capability_type'],
			//capabilities' 			=> (array) $custom_type['capabilities'],
			'public' 				=> true, // not proposed because all flags are specified.
			'hierarchical' 			=> (boolean) $custom_type['hierarchical'],
			'rewrite' 				=> $custom_type['rewrite'],
			'has_archive' 			=> $custom_type['has_archive'],
			'query_var' 			=> $custom_type['query_var'],
			'supports' 				=> (array) $custom_type['supports'],
			// 'register_meta_box_cb', not proposed because it needs a PHP function.
			'taxonomies' 			=> $custom_type['taxonomies'],
			'show_ui' 				=> (boolean) $custom_type['show_ui'],
			'menu_position' 		=> (int) $custom_type['menu_position'],
			'menu_icon' 			=> $custom_type['menu_icon'],
			// 'permalink_epmask', not proposed because it need a PHP constant. (and too advanced settings for this plugin actually !)
			'can_export' 			=> (boolean) $custom_type['can_export'],
			'show_in_nav_menus'		=> (boolean) $custom_type['show_in_nav_menus'],
			'show_in_menu'			=> (boolean) $custom_type['show_in_menu'],
			//'_builtin'			=> false,
			//'_edit_link' 			=> 'post.php?post=%d'
		);
		
		// Capabilities
		if ( !empty($custom_type['capabilities']) ) {
			$args['capabilities'] = $custom_type['capabilities'];
		}
		
		return $args;
	}
}
?>