<?php
class SimpleCustomTypes_Admin {
	var $customtype_fields 	= null;
	
	var $admin_url 			= '';
	var $admin_slug			= 'simple-customtypes-settings';
	
	// Error management
	var $message = '';
	var $status  = '';
	
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function SimpleCustomTypes_Admin() {
		$this->customtype_fields = array(
			'name' 					=> '',
			'labels' 				=> array(
										'name' => _x('Entries', 'post type general name', 'simple-customtypes'),
										'singular_name' => _x('Entry', 'post type singular name', 'simple-customtypes'),
										'add_new' => _x('Add New', 'post', 'simple-customtypes'),
										'add_new_item' => __('Add New Entry', 'simple-customtypes'),
										'edit_item' => __('Edit Entry', 'simple-customtypes'),
										'new_item' => __('New Entry', 'simple-customtypes'),
										'view_item' => __('View Entry', 'simple-customtypes'),
										'search_items' => __('Search Entries', 'simple-customtypes'),
										'not_found' => __('No entries found', 'simple-customtypes'),
										'not_found_in_trash' => __('No entries found in Trash', 'simple-customtypes'),
										'parent_item_colon' => __('Parent Entry:', 'simple-customtypes')
									),
			'description' 			=> '',
			'publicly_queryable' 	=> 1,
			'exclude_from_search' 	=> 0,
			'capability_type' 		=> 'post',
			'capability_type_custom'=> 'post,posts',
			'capabilities' 			=> array(
										'edit_post' => '',
										'read_post' => '',
										'delete_post' => '',
										'edit_posts' => '',
										'edit_others_posts' => '',
										'publish_posts' => '',
										'read_private_posts' => '',
										'read' => '',
										'delete_posts' => '',
										'delete_private_posts' => '',
										'delete_published_posts' => '',
										'delete_others_posts' => '',
										'edit_private_posts' => '',
										'edit_published_posts' => '',
									),
			'map_meta_cap' 			=> 1,
			'hierarchical' 			=> 0,
			'public' 				=> true, // not proposed because all flags are specified. Hardcoded !
			'rewrite' 				=> 1, // boolean|array( 'slug', 'with_front' )
			'has_archive'			=> 1,
			'query_var' 			=> '',
			'archive_slug'			=> '',
			'supports' 				=> array('title', 'editor'),
			// 'register_meta_box_cb', not proposed because it needs a PHP function.
			'taxonomies' 			=> array(),
			'show_ui' 				=> 1,
			'menu_position' 		=> 30,
			'menu_icon' 			=> '',
			'custom_role_checkbox'	=> '',
			'custom_role'			=> '',
			// 'permalink_epmask' => EP_PERMALINK, not proposed because it need a PHP constant. (and too advanced settings for this plugin actually !)
			'can_export' 			=> 1,
			'show_in_nav_menus'		=> 1,
			'show_in_menu' 			=> 1
		);

		$this->admin_url = admin_url( 'options-general.php?page='.$this->admin_slug );
		
		// Register hooks
		add_action( 'admin_init', array(&$this, 'initStyleScript') );
		add_action( 'admin_init', array(&$this, 'checkAdminPost') );
		add_action( 'admin_menu', array(&$this, 'addMenu') );
	}
	
	/**
	 * Load JS and CSS need for admin features.
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function initStyleScript() {
		if ( isset($_GET['page']) && $_GET['page'] == $this->admin_slug ) {
			wp_enqueue_script( 'simple-custom-types', SCUST_URL.'/inc/js/admin.js', array('jquery'), SCUST_VERSION );
			//wp_enqueue_style ( 'simple-custom-types', SCUST_URL.'/inc/css/admin.css', array(), SCUST_VERSION );
		}
	}
	
	/**
	 * Meta function for load all check functions.
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function checkAdminPost() {
		$this->checkMergeCustomType();
		$this->checkExportCustomType();
		$this->checkDeleteCustomType();
		$this->checkImportExport();
		$this->checkResetRoles();
	}
	
	/**
	 * Add settings menu page
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function addMenu() {
		add_options_page( __('Custom Post Types', 'simple-customtypes'), __('Custom Post Types', 'simple-customtypes'), 'manage_options', $this->admin_slug, array( &$this, 'pageManage' ) );
	}
	
	/**
	 * Allow to display only form.
	 *
	 * @param object $customtype 
	 * @return void
	 * @author Amaury Balmer
	 */
	function pageForm( $customtype ) {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php printf(__('Custom type : %s', 'simple-customtypes'), $customtype['labels']['name']); ?></h2>
			
			<div class="form-wrap">
				<?php $this->formMergeCustomType( $customtype ); ?>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Display options on admin
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function pageManage() {
		// Get current options
		$current_options = get_option( SCUST_OPTION );
		
		// Check get for message
		if ( isset($_GET['message']) ) {
			switch ( $_GET['message'] ) {
				case 'flush-deleted' :
					$this->message = __('Custom post type and this content deleted with success !', 'simple-customtypes');
					break;
				case 'deleted' :
					$this->message = __('Custom post type deleted with success !', 'simple-customtypes');
					break;
				case 'added' :
					$this->message = __('Custom type added with success !', 'simple-customtypes');
					break;
				case 'updated' :
					$this->message = __('Custom type updated with success !', 'simple-customtypes');
					break;
			}
			
			// Add/Update role ?
			if ( ($_GET['message'] == 'added' || $_GET['message'] == 'updated') && isset($_GET['cpt']) ) {
				$this->flushRole( $_GET['cpt'] );
			}
		}
		
		// Display message
		$this->displayMessage();
		
		// Edit custom type
		if ( isset($_GET['action']) && isset($_GET['customtype_name']) && $_GET['action'] == 'edit' && isset($current_options['customtypes'][$_GET['customtype_name']]) ) {
			$this->pageForm( $current_options['customtypes'][$_GET['customtype_name']] );
			return true;
		}
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e("Simple Custom Post Types", 'simple-customtypes'); ?></h2>
			
			<div class="message updated">
				<p><?php _e('<strong>Warning:</strong> Flush & Delete a custom type will delete post type and also all content and all relations related.', 'simple-customtypes'); ?></p>
			</div>
			
			<div id="col-container">
				<table class="widefat tag fixed" cellspacing="0">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-name"><?php _e('Label', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-slug"><?php _e('Name', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-hierarchial"><?php _e('Hierarchical', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-rewrite"><?php _e('Rewrite', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-description"><?php _e('Description', 'simple-customtypes'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th scope="col" class="manage-column column-name"><?php _e('Label', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-slug"><?php _e('Name', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-hierarchial"><?php _e('Hierarchical', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-rewrite"><?php _e('Rewrite', 'simple-customtypes'); ?></th>
							<th scope="col" class="manage-column column-description"><?php _e('Description', 'simple-customtypes'); ?></th>
						</tr>
					</tfoot>
					
					<tbody id="the-list" class="list:customtype">
						<?php
						if ( $current_options == false || empty($current_options['customtypes']) ) :
							echo '<tr><td colspan="3">'.__('No custom type.', 'simple-customtypes').'</td></tr>';
						else :
						$class = 'alternate';
						$i = 0;
						ksort($current_options['customtypes']);
						foreach( (array) $current_options['customtypes'] as $_t_name => $_t ) :
							$i++;
							$class = ( $class == 'alternate' ) ? '' : 'alternate';
							?>
							<tr id="customtype-<?php echo $i; ?>" class="<?php echo $class; ?>">
								<td class="name column-name">
									<strong><a class="row-title" href="<?php echo $this->admin_url; ?>&amp;action=edit&amp;customtype_name=<?php echo $_t_name; ?>" title="<?php esc_attr_e(sprintf(__('Edit the custom type &#8220;%s&#8221;', 'simple-customtypes'), $_t['labels']['name'])); ?>"><?php echo esc_html($_t['labels']['name']); ?></a></strong>
									<br />
									<div class="row-actions">
										<span class="edit"><a href="<?php echo $this->admin_url; ?>&amp;action=edit&amp;customtype_name=<?php echo $_t_name; ?>"><?php _e('Edit', 'simple-customtypes'); ?></a> | </span>
										<span class="export"><a href="<?php echo wp_nonce_url($this->admin_url.'&amp;action=export_php&amp;customtype_name='.$_t_name, 'export_php-customtype-'.$_t_name); ?>"><?php _e('Export PHP', 'simple-customtypes'); ?></a> | </span>
										<span class="delete"><a class="delete-customtype" href="<?php echo wp_nonce_url($this->admin_url.'&amp;action=delete&amp;customtype_name='.$_t_name, 'delete-customtype-'.$_t_name); ?>" onclick="if ( confirm( '<?php echo esc_js( sprintf( __( "You are about to delete this custom post type '%s'\n  'Cancel' to stop, 'OK' to delete.", 'simple-customtypes' ), $_t['labels']['name'] ) ); ?>' ) ) { return true;}return false;"><?php _e('Delete', 'simple-customtypes'); ?></a> | </span>
										<span class="delete"><a class="flush-delete-customtype" href="<?php echo wp_nonce_url($this->admin_url.'&amp;action=flush-delete&amp;customtype_name='.$_t_name, 'flush-delete-customtype-'.$_t_name); ?>" onclick="if ( confirm( '<?php echo esc_js( sprintf( __( "You are about to delete and flush this custom post type '%s' and all this content.\n  'Cancel' to stop, 'OK' to delete.", 'simple-customtypes' ), $_t['labels']['name'] ) ); ?>' ) ) { return true;}return false;"><?php _e('Flush & Delete', 'simple-customtypes'); ?></a></span>
									</div>
								</td>
								<td><?php echo esc_html($_t['name']); ?></td>
								<td><?php echo ($_t['hierarchical'] == true) ? __('Yes', 'simple-customtypes') : __('No', 'simple-customtypes'); ?></td>
								<td><?php echo ($_t['rewrite'] == true) ? __('Yes', 'simple-customtypes') : __('No', 'simple-customtypes'); ?></td>
								<td><?php echo esc_html($_t['description']); ?></td>
							</tr>
						<?php endforeach; endif; ?>
					</tbody>
				</table>
				
				<br class="clear" />
				
				<div class="form-wrap">
					<h2><?php _e('Add a new custom type', 'simple-customtypes'); ?></h2>
					<?php $this->formMergeCustomType( null ); ?>
				</div>
			
			</div><!-- /col-container -->
		</div>
		
		<div class="wrap">
			<h2><?php _e("Simple Custom Post Types : Export/Import", 'simple-customtypes'); ?></h2>
			
			<a class="button" href="<?php echo wp_nonce_url($this->admin_url.'&amp;action=export_config_scpt', 'export-config-scpt'); ?>"><?php _e("Export config file", 'simple-customtypes'); ?></a>
			<a class="button" href="#" id="toggle-import_form"><?php _e("Import config file", 'simple-customtypes'); ?></a>
			<script type="text/javascript">
				jQuery("#toggle-import_form").click(function(event) {
					event.preventDefault();
					jQuery('#import_form').removeClass('hide-if-js');
				});
			</script>
			<div id="import_form" class="hide-if-js">
				<form action="<?php echo $this->admin_url ; ?>" method="post" enctype="multipart/form-data">
					<p>
						<label><?php _e("Config file", 'simple-customtypes'); ?></label>
						<input type="file" name="config_file" />
					</p>
					<p class="submit">
						<?php wp_nonce_field( 'import_config_file_scpt' ); ?>
						<input class="button-primary" type="submit" name="import_config_file_scpt" value="<?php _e('I want import a config from a previous backup, this action will REPLACE current configuration', 'simple-customtypes'); ?>" />
					</p>
				</form>
			</div>
		</div>
		
		<div class="wrap">
			<h2><?php _e("Simple Custom Post Types : Reset all roles", 'simple-customtypes'); ?></h2>
			
			<a class="button" href="<?php echo wp_nonce_url($this->admin_url.'&amp;reset_roles=true', 'reset_roles'); ?>" onclick="if ( confirm( '<?php echo esc_js( sprintf( __( "You are about to delete and flush all roles.\n  'Cancel' to stop, 'OK' to delete.", 'simple-customtypes' ), $_t['labels']['name'] ) ); ?>' ) ) { return true;}return false;"><?php _e("Reset all roles", 'simple-customtypes'); ?></a>
			<p><?php _e("Warning : All roles will be deleted and created again", 'simple-customtypes'); ?></p>
		</div>
		<?php
		return true;
	}
	
	/**
	 * Build HTML for form custom type, add with list on right column
	 *
	 * @param object $customtype 
	 * @return void
	 * @author Amaury Balmer
	 */
	function formMergeCustomType( $customtype = null ) {
		if ( $customtype == null ) {
			$edit 		 = false;
			$_action 	 = 'add-customtype';
			$submit_val	 = __('Add custom type', 'simple-customtypes');
			$nonce_field = 'simplecustomtype-add-type';
			
			foreach( (array) $this->customtype_fields as $field => $default_value ) {  // Use default value
				if ( is_array($default_value) ) {
					$customtype[$field] = array();
					foreach( $default_value as $k => $_v ) {
						if ( is_string($_v) ) {
							$customtype[$field][$k] = trim(stripslashes($_v));
						} else {
							$customtype[$field][$k] = $_v;
						}
					}
				} else {
					$customtype[$field] = $default_value;
				}
			}
		} else {
			$edit 		 = true;
			$_action 	 = 'merge-customtype';
			$submit_val	 = __('Update custom type', 'simple-customtypes');
			$nonce_field = 'simplecustomtype-edit-type';
			
			// clean values
			foreach( (array) $this->customtype_fields as $field => $default_value ) {
				if ( isset($customtype[$field]) && is_string($customtype[$field]) ) { // Isset, juste clean values
					$customtype[$field] = trim(stripslashes($customtype[$field]));
				} elseif ( isset($customtype[$field]) && is_array($customtype[$field]) ) { // Isset, but dispatch array
					foreach( $customtype[$field] as $k => $_v ) {
						if ( is_string($_v) ) {
							$customtype[$field][$k] = trim(stripslashes($_v));
						} else {
							$customtype[$field][$k] = $_v;
						}
					}
				} elseif ( !isset($customtype[$field]) ) { // No set, try to set default values
					if ( is_array($default_value) ) {
						$customtype[$field] = array();
						foreach( $default_value as $k => $_v ) {
							if ( is_string($_v) ) {
								$customtype[$field][$k] = trim(stripslashes($_v));
							} else {
								$customtype[$field][$k] = $_v;
							}
						}
					} else {
						$customtype[$field] = $default_value;
					}
				}
			}
		}
		?>
		<form id="addtag" method="post" action="<?php echo $this->admin_url; ?>">
			<input type="hidden" name="action" value="<?php echo $_action; ?>" />
			<?php wp_nonce_field( $nonce_field ); ?>
			
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<div class="meta-box-sortabless">
						<div class="postbox">
							<h3 class="hndle"><span><?php _e('Rewrite URL', 'simple-customtypes'); ?></span></h3>
							<div class="inside">
								<p>
									<label for="query_var"><?php _e('Query var', 'simple-customtypes'); ?></label>
									<input name="query_var" id="query_var" type="text" value="<?php echo esc_attr($customtype['query_var']); ?>" />
								</p>
								<p class="description"><?php _e("<strong>Query var</strong> is used for build URLs of object. If this value is empty, Simple Custom Post Types will use a slug from label for build URL.", 'simple-customtypes'); ?></p>
								
								<p>
									<label for="has_archive"><?php _e('Has archive view ?', 'simple-customtypes'); ?></label>
									<select name="has_archive" id="has_archive" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['has_archive'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
								</p>
								<p class="description"><?php _e("<strong>Has archive view ?</strong> allow to have a view archive for this custom post type.", 'simple-customtypes'); ?></p>
								
								<p>
									<label for="archive_slug"><?php _e('Query var for archives ', 'simple-customtypes'); ?></label>
									<input name="archive_slug" id="archive_slug" type="text" value="<?php echo esc_attr($customtype['archive_slug']); ?>" />
								</p>
								<p class="description"><?php _e("<strong>Query var for archives</strong> is used for build URLs for lisiting objects of a custom types. Also archives for post...", 'simple-customtypes'); ?></p>

								<p>
									<label for="rewrite"><?php _e('Rewrite ?', 'simple-customtypes'); ?></label>
									<select name="rewrite" id="rewrite" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['rewrite'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
								</p>
								<p class="description"><?php _e("Rewriting allow to build nice URL for custom post type.", 'simple-customtypes'); ?></p>
							</div>
						</div>
					</div>
					
					<div class="meta-box-sortabless">
						<div class="postbox">
							<h3 class="hndle"><span><?php _e('Administration', 'simple-customtypes'); ?></span></h3>
							<div class="inside">
								<p>
									<label for="show_ui"><?php _e('Display on admin ?', 'simple-customtypes'); ?></label>
									<select name="show_ui" id="show_ui" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['show_ui'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("Whether to generate a default UI for managing this post type.", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="description"><?php _e('Admin description', 'simple-customtypes'); ?></label>
									<input name="description" type="text" id="description" value="<?php echo esc_attr($customtype['description']); ?>" class="regular-text" style="width:99%" />
								</p>
								
								<p>
									<label for="show_in_menu"><?php _e('Show in admin menu ?', 'simple-customtypes'); ?></label>
									<select name="show_in_menu" id="show_in_menu" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['show_in_menu'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("Where to show the post type in the admin menu. True for a top level menu, false for no menu, or can be a top level page like 'tools.php' or 'edit.php?post_type=page'. Show UI must be true.", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="show_in_nav_menus"><?php _e('Show in nav menus ?', 'simple-customtypes'); ?></label>
									<select name="show_in_nav_menus" id="show_in_nav_menus" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['show_in_nav_menus'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("Put this setting to true for display this custom type on navigation menu tools.", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="can_export"><?php _e('Can export ?', 'simple-customtypes'); ?></label>
									<select name="can_export" id="can_export" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $type_key => $type_name ) {
											echo '<option '.selected($customtype['can_export'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("Put this setting to true for export this post type items on WordPress eXtended RSS (or WXR)", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="menu_position"><?php _e('Position on menu', 'simple-customtypes'); ?></label>
									<input name="menu_position" id="menu_position" type="text" value="<?php echo esc_attr($customtype['menu_position']); ?>" />
									<p class="description"><?php _e("This field allow to order the position of the custom type menu on admin. By the defaut, the value are upper than 25.", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="menu_icon"><?php _e('Menu Icon', 'simple-customtypes'); ?></label>
									<input name="menu_icon" id="menu_icon" type="text" value="<?php echo esc_attr($customtype['menu_icon']); ?>" />
									<p class="description"><?php _e("You can specifiy a custom icon with this field, just put this URI. (use the media manager for upload image)", 'simple-customtypes'); ?></p>
								</p>
							</div>
						</div>
					</div>
					
					<div class="meta-box-sortabless">
						<div class="postbox">
							<h3 class="hndle"><span><?php _e('Permissions', 'simple-customtypes'); ?></span></h3>
							<div class="inside">
								<p>
									<label for="map_meta_cap"><?php _e('Use the internal default capabilities logic ?', 'simple-customtypes'); ?></label>
									<select name="map_meta_cap" id="map_meta_cap" style="width:50%">
										<?php
										foreach( $this->getTrueFalse() as $key => $value ) {
											echo '<option '.selected($customtype['map_meta_cap'], $key, false).' value="'.esc_attr($key).'">'.esc_html($value).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("Whether to use the internal default meta capability handling. Defaults to true.", 'simple-customtypes'); ?></p>
								</p>
								
								<p>
									<label for="capability_type"><?php _e('Required capability', 'simple-customtypes'); ?></label>
									<select name="capability_type" id="capability_type" style="width:50%">
										<?php
										foreach( $this->getCapabilityType() as $key => $value ) {
											echo '<option '.selected($customtype['capability_type'], $key, false).' value="'.esc_attr($key).'">'.esc_html($value).'</option>' . "\n";
										}
										?>
									</select>
									<p class="description"><?php _e("The string to use to build the read, edit, and delete capabilities. Defaults to 'post'.", 'simple-customtypes'); ?></p>
								</p>
								
								<div id="adv-capabilities" <?php if ( $customtype['capability_type'] != 'custom' ) echo 'style="display:none;"'; ?>>
									<p>
										<label for="capability_type_custom"><?php _e('Custom capability type', 'simple-customtypes'); ?></label>
										<input name="capability_type_custom" id="capability_type_custom" type="text" value="<?php echo esc_attr($customtype['capability_type_custom']); ?>" />
										<p class="description"><?php _e("The string to use to build the read, edit, and delete capabilities. Defaults to 'post'. May be passed as an array to allow for alternative plurals when using this argument as a base to construct the capabilities, separe the two values with a comma, e.g. story,stories.", 'simple-customtypes'); ?></p>
									</p>
									<p>
										<label for="custom_role_checkbox"><?php _e('Add a custom role ?', 'simple-customtypes'); ?></label>
										<input name="custom_role_checkbox" id="custom_role_checkbox" type="checkbox" <?php checked( $customtype['custom_role_checkbox'], 1 ); ?> value="1" />
									</p>
									<p>
										<label for="custom_role"><?php _e('Custom role', 'simple-customtypes'); ?></label>
										<input name="custom_role" id="custom_role" type="text" value="<?php echo esc_attr($customtype['custom_role']); ?>" />
										<p class="description"><?php _e("You can create a custom role for this post type.", 'simple-customtypes'); ?></p>
									</p>
									<p>
										<a href="#" class="display-advanced-caps"><?php _e('Show very advanced capabilities settings'); ?></a>
									</p>
									<div id="adv-capabilities-adv" style="display:none;">
										<p>
											<label for="edit_post"><?php _e('Edit entry', 'simple-customtypes'); ?></label>
											<input name="capabilities[edit_post]" id="edit_post" type="text" value="<?php echo esc_attr($customtype['capabilities']['edit_post']); ?>" />
											<p class="description"><?php _e("The meta capability that controls editing a particular object of this post type. Defaults to 'edit_post'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="read_post"><?php _e('Ready entry', 'simple-customtypes'); ?></label>
											<input name="capabilities[read_post]" id="read_post" type="text" value="<?php echo esc_attr($customtype['capabilities']['read_post']); ?>" />
											<p class="description"><?php _e("The meta capability that controls reading a particular object of this post type. Defaults to 'read_post'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="edit_posts"><?php _e('Edit entries', 'simple-customtypes'); ?></label>
											<input name="capabilities[edit_posts]" id="edit_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['edit_posts']); ?>" />
											<p class="description"><?php _e("The capability that controls editing objects of this post type as a class. Defaults to 'edit_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="delete_post"><?php _e('Delete entry', 'simple-customtypes'); ?></label>
											<input name="capabilities[delete_post]" id="delete_post" type="text" value="<?php echo esc_attr($customtype['capabilities']['delete_post']); ?>" />
											<p class="description"><?php _e("The meta capability that controls deleting a particular object of this post type. Defaults to 'delete_post'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="edit_others_posts"><?php _e('Edit others entries', 'simple-customtypes'); ?></label>
											<input name="capabilities[edit_others_posts]" id="edit_others_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['edit_others_posts']); ?>" />
											<p class="description"><?php _e("The capability that controls editing objects of this post type that are owned by other users. Defaults to 'edit_others_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="publish_posts"><?php _e('Publish entries', 'simple-customtypes'); ?></label>
											<input name="capabilities[publish_posts]" id="publish_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['publish_posts']); ?>" />
											<p class="description"><?php _e("The capability that controls publishing objects of this post type. Defaults to 'publish_posts'", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="read_private_posts"><?php _e('Read private entries', 'simple-customtypes'); ?></label>
											<input name="capabilities[read_private_posts]" id="read_private_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['read_private_posts']); ?>" />
											<p class="description"><?php _e("The capability that controls reading private posts. Defaults to 'read_private_posts'.", 'simple-customtypes'); ?></p>
										</p>
										
										<p style="font-weight:700;">
											<?php _e('The follow capabiilites is required when internal default logic settings is to true', 'simple-customtypes'); ?>
										</p>
										<p>
											<label for="read"><?php _e('Read', 'simple-customtypes'); ?></label>
											<input name="capabilities[read]" id="read" type="text" value="<?php echo esc_attr($customtype['capabilities']['read']); ?>" />
											<p class="description"><?php _e("Controls whether objects of this post type can be read. Defaults to 'read'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="delete_posts"><?php _e('Delete posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[delete_posts]" id="delete_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['delete_posts']); ?>" />
											<p class="description"><?php _e("Controls whether objects of this post type can be deleted. Defaults to 'delete_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="delete_private_posts"><?php _e('Delete private posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[delete_private_posts]" id="delete_private_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['delete_private_posts']); ?>" />
											<p class="description"><?php _e("Controls whether private objects can be deleted. Defaults to 'delete_private_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="delete_published_posts"><?php _e('Delete published posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[delete_published_posts]" id="delete_published_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['delete_published_posts']); ?>" />
											<p class="description"><?php _e("Controls whether published objects can be deleted. Defaults to 'delete_published_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="delete_others_posts"><?php _e('Delete other posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[delete_others_posts]" id="delete_others_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['delete_others_posts']); ?>" />
											<p class="description"><?php _e("Controls whether objects owned by other users can be can be deleted. If the post type does not support an author, then this will behave like delete_posts. Defaults to 'delete_others_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="edit_private_posts"><?php _e('Edit private posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[edit_private_posts]" id="edit_private_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['edit_private_posts']); ?>" />
											<p class="description"><?php _e("Controls whether private objects can be edited. Defaults to 'edit_private_posts'.", 'simple-customtypes'); ?></p>
										</p>
										<p>
											<label for="edit_published_posts"><?php _e('Edit published posts', 'simple-customtypes'); ?></label>
											<input name="capabilities[edit_published_posts]" id="edit_published_posts" type="text" value="<?php echo esc_attr($customtype['capabilities']['edit_published_posts']); ?>" />
											<p class="description"><?php _e("Controls whether published objects can be deleted.. Defaults to 'edit_published_posts'.", 'simple-customtypes'); ?></p>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="has-sidebar sm-padded" >
					<div id="post-body-content" class="has-sidebar-content">
						<div class="meta-box-sortabless">
							<div class="postbox">
								<h3 class="hndle"><span><?php _e('Main information', 'simple-customtypes'); ?></span></h3>
								
								<div class="inside">
									<table class="form-table" style="clear:none;">
										<tr valign="top">
											<th scope="row"><label for="name"><?php _e('Name', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="name" type="text" id="name" value="<?php echo esc_attr($customtype['name']); ?>" class="regular-text" <?php if ( $edit==true ) echo 'readonly="readonly"'; ?> />
												<span class="description"><?php _e("<strong>Name</strong> is used on DB. (All lowercase and no weird characters)", 'simple-customtypes'); ?></span>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="hierarchical"><?php _e('Hierarchical ?', 'simple-customtypes'); ?></label></th>
											<td>
												<select name="hierarchical" id="hierarchical" style="width:20%">
													<?php
													foreach( $this->getTrueFalse() as $type_key => $type_name ) {
														echo '<option '.selected($customtype['hierarchical'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
													}
													?>
												</select>
												<span class="description"><?php _e("Default <strong>hierarchical</strong> in WordPress are Pages. Default posts WP aren't hierarchical.", 'simple-customtypes'); ?></span>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label><?php _e('Supports features ?', 'simple-customtypes'); ?></label></th>
											<td>
												<?php
												foreach( $this->getSupportFeatures() as $k_feature => $feature ) {
													echo '<label class="inline"><input type="checkbox" name="supports[]" '.checked( true, in_array($k_feature, (array) $customtype['supports']), false).' value="'.esc_attr($k_feature).'" /> '.stripslashes($feature).'</label>' . "\n";
												}
												?>
												<span class="description"><?php _e("Default supports <strong>features</strong> from WordPress. You can use custom fields for extend easily your custom post type.", 'simple-customtypes'); ?></span>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label><?php _e('Taxonomies', 'simple-customtypes'); ?></label></th>
											<td>
												<?php
												foreach( get_taxonomies( array( 'show_ui' => true, 'public' => true ), 'object' ) as $taxonomy ) {
													echo '<label class="inline"><input type="checkbox" name="taxonomies[]" '.checked( true, in_array($taxonomy->name, (array) $customtype['taxonomies']), false).' value="'.esc_attr($taxonomy->name).'" /> '.esc_html($taxonomy->label).' ('.esc_html($taxonomy->name).')</label>' . "\n";
												}
												?>
												<span class="description"><?php _e("You can add this object type to an builtin or custom taxonomy. (compatible Simple Taxonomy)", 'simple-customtypes'); ?></span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						
						<div class="meta-box-sortabless">
							<div class="postbox">
								<h3 class="hndle"><span><?php _e('Visibility', 'simple-customtypes'); ?></span></h3>
								
								<div class="inside">
									<table class="form-table" style="clear:none;">
										<tr valign="top">
											<th scope="row"><label for="publicly_queryable"><?php _e('Public queryable ?', 'simple-customtypes'); ?></label></th>
											<td>
												<select name="publicly_queryable" id="publicly_queryable" style="width:20%">
													<?php
													foreach( $this->getTrueFalse() as $type_key => $type_name ) {
														echo '<option '.selected($customtype['publicly_queryable'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
													}
													?>
												</select>
												<span class="description"><?php _e("Whether post_type queries can be performed from the front page.", 'simple-customtypes'); ?></span>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="exclude_from_search"><?php _e('Exclude from search ?', 'simple-customtypes'); ?></label></th>
											<td>
												<select name="exclude_from_search" id="exclude_from_search" style="width:20%">
													<?php
													foreach( $this->getTrueFalse() as $type_key => $type_name ) {
														echo '<option '.selected($customtype['exclude_from_search'], $type_key, false).' value="'.esc_attr($type_key).'">'.esc_html($type_name).'</option>' . "\n";
													}
													?>
												</select>
												<span class="description"><?php _e("Whether to exclude objects with this post type from search results.", 'simple-customtypes'); ?></span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						
						<div class="meta-box-sortabless">
							<div class="postbox">
								<h3 class="hndle"><span><?php _e('Translations Wording', 'simple-customtypes'); ?></span></h3>
								
								<div class="inside">
									<table class="form-table" style="clear:none;">
										<tr valign="top">
											<th scope="row"><label for="labels-name"><?php _e('Entries', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[name]" type="text" id="labels-name" value="<?php echo esc_attr($customtype['labels']['name']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-singular_name"><?php _e('Entry', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[singular_name]" type="text" id="labels-singular_name" value="<?php echo esc_attr($customtype['labels']['singular_name']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-add_new"><?php _e('Add New', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[add_new]" type="text" id="labels-add_new" value="<?php echo esc_attr($customtype['labels']['add_new']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-add_new_item"><?php _e('Add New Entry', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[add_new_item]" type="text" id="labels-add_new_item" value="<?php echo esc_attr($customtype['labels']['add_new_item']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-edit_item"><?php _e('Edit Entry', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[edit_item]" type="text" id="labels-edit_item" value="<?php echo esc_attr($customtype['labels']['edit_item']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-new_item"><?php _e('New Entry', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[new_item]" type="text" id="labels-new_item" value="<?php echo esc_attr($customtype['labels']['new_item']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-view_item"><?php _e('View Entry', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[view_item]" type="text" id="labels-view_item" value="<?php echo esc_attr($customtype['labels']['view_item']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-search_items"><?php _e('Search Entries', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[search_items]" type="text" id="labels-search_items" value="<?php echo esc_attr($customtype['labels']['search_items']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-not_found"><?php _e('No entries found', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[not_found]" type="text" id="labels-not_found" value="<?php echo esc_attr($customtype['labels']['not_found']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-not_found_in_trash"><?php _e('No entries found in Trash', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[not_found_in_trash]" type="text" id="labels-not_found_in_trash" value="<?php echo esc_attr($customtype['labels']['not_found_in_trash']); ?>" class="regular-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="labels-parent_item_colon"><?php _e('Parent Entry:', 'simple-customtypes'); ?></label></th>
											<td>
												<input name="labels[parent_item_colon]" type="text" id="labels-parent_item_colon" value="<?php echo esc_attr($customtype['labels']['parent_item_colon']); ?>" class="regular-text" />
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<p class="submit" style="padding:0 0 1.5em;">
				<input type="submit" class="button-primary" name="submit" id="submit" value="<?php esc_attr_e($submit_val); ?>" />
			</p>
		</form>
		<?php
	}

	/**
	 * Check $_GET/$_POST/$_FILES for Export/Import
	 * 
	 * @return boolean
	 */
	function checkImportExport() {
		if ( isset($_GET['action']) && $_GET['action'] == 'export_config_scpt' ) {
			check_admin_referer('export-config-scpt');
			
			// No cache
			header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); 
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
			header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
			header( 'Cache-Control: post-check=0, pre-check=0', false ); 
			header( 'Pragma: no-cache' ); 
			
			// Force download dialog
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");

			// use the Content-Disposition header to supply a recommended filename and
			// force the browser to display the save dialog.
			header("Content-Disposition: attachment; filename=simple-custom-post-types-config-".date('U').".txt;");
			die('SIMPLECUSTOMTYPES'.base64_encode(serialize(get_option( SCUST_OPTION ))));
		} elseif( isset($_POST['import_config_file_scpt']) && isset($_FILES['config_file']) ) {
			check_admin_referer( 'import_config_file_scpt' );
			
			if ( $_FILES['config_file']['error'] > 0 ) {
				$this->message = __('An error occured during the config file upload. Please fix your server configuration and retry.', 'simple-customtypes');
				$this->status  = 'error';
			} else {
				$config_file = file_get_contents( $_FILES['config_file']['tmp_name'] );
				if ( substr($config_file, 0, strlen('SIMPLECUSTOMTYPES')) !== 'SIMPLECUSTOMTYPES' ) {
					$this->message = __('This is really a config file for Simple Custom Post Types ? Probably corrupt :(', 'simple-customtypes');
					$this->status  = 'error';
				} else {
					$config_file = unserialize(base64_decode(substr($config_file, strlen('SIMPLECUSTOMTYPES'))));
					if ( !is_array($config_file) ) {
						$this->message = __('This is really a config file for Simple Custom Post Types ? Probably corrupt :(', 'simple-customtypes');
						$this->status  = 'error';
					} else {
						update_option(SCUST_OPTION, $config_file);
						$this->message = __('OK. Configuration is restored.', 'simple-customtypes');
						$this->status  = 'updated';
					}
				}
			}
		}
	}

	/**
	 * Check is the admin wants to regenerate all roles
	 *
	 * @return boolean
	 * @author Amaury Balmer
	 */	
	function checkResetRoles() {
		if ( !isset($_GET['reset_roles']) || $_GET['reset_roles'] != "true" )
			return false;
		
		check_admin_referer( 'reset_roles' );
		
		// CPT exist ?
		$current_options = get_option( SCUST_OPTION );
		if ( !isset( $current_options['customtypes'] ) || empty( $current_options['customtypes'] ) || !is_array( $current_options['customtypes'] ) )
			return false;
		
		// Loop on CPT
		foreach ( $current_options['customtypes'] as $cpt_name => $cpt ) {
			// Remove role
			$this->removeRole( $cpt_name );
			
			// flush each role with a custom role
			if ( isset($cpt['custom_role_checkbox']) && (int) $cpt['custom_role_checkbox'] == 1 )
				$this->flushRole( $cpt_name );
		}
		
		$this->message = __('OK. Roles are restored.', 'simple-customtypes');
		$this->status  = 'updated';
	}
	
	/**
	 * Check $_POST datas for add/merge custom type
	 *
	 * @return boolean
	 * @author Amaury Balmer
	 */
	function checkMergeCustomType() {
		if ( isset($_POST['action']) && in_array( $_POST['action'], array('add-customtype', 'merge-customtype') ) ) {
			
			if ( !current_user_can('manage_options') )
				wp_die(__( 'You cannot edit the Simple Custom Post Types options.', 'simple-customtypes' ));
			
			// Clean values from _POST
			$customtype = array();
			foreach( (array) $this->customtype_fields as $field => $default_value ) {
				if ( isset($_POST[$field]) && is_string($_POST[$field]) ) {// String ?
					$customtype[$field] = trim( stripslashes( $_POST[$field] ) );
				} elseif ( isset($_POST[$field]) ) {
					if ( is_array($_POST[$field]) ) {
						$customtype[$field] = array();
						foreach( $_POST[$field] as $k => $_v ) {
							$customtype[$field][$k] = $_v;
						}
					} else {
						$customtype[$field] = $_POST[$field];
					}
				} else {
					$customtype[$field] = '';
				}
			}
			
			if ( $_POST['action'] == 'merge-customtype' && empty($customtype['name']) ) {
				wp_die( __('Tcheater ? You try to edit a custom type without name. Impossible !', 'simple-customtypes') );
			}
			
			if ( !empty($customtype['name']) ) { // Name exist ?
				// Values exist ? or build it from label ?
				$customtype['name'] = ( empty($customtype['name']) ) ? $customtype['labels']['name'] : $customtype['name'];
				
				// Clean sanitize value
				$customtype['name'] = sanitize_title($customtype['name']);
				
				// Allow plugin to filter datas...
				$customtype = apply_filters( 'simple-customtype-check-merge', $customtype );
				
				if ( $_POST['action'] == 'add-customtype' ) {
					check_admin_referer( 'simplecustomtype-add-type' );
					if ( post_type_exists($customtype['name']) ) { // Default Custom type already exist ?
						wp_die( __('Tcheater ? You try to add a custom type with a name already used by an another custom type.', 'simple-customtypes') );
					}
					$this->addCustomType( $customtype );
				} else {
					check_admin_referer( 'simplecustomtype-edit-type' );
					$this->updateCustomType( $customtype );
				}
				
				// Flush rewriting rules !
				global $wp_rewrite;
				$wp_rewrite->flush_rules(false);
			} else {
				$this->message = __('Impossible to add your custom type... You must enter a custom type name.', 'simple-customtypes');
				$this->status  = 'error';
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Allow to export registration CPT with PHP
	 */
	function checkExportCustomType() {
		global $simple_customtypes;
		
		if ( isset($_GET['action']) && isset($_GET['customtype_name']) && $_GET['action'] == 'export_php' ) {
			check_admin_referer( 'export_php-customtype-'.$_GET['customtype_name'] );
			
			// Get proper CPT name
			$cpt_name = stripslashes($_GET['customtype_name']);
			
			// Get CPT data
			$current_options = get_option( SCUST_OPTION );
			if ( !isset($current_options['customtypes'][$cpt_name]) ) { // CPT not exist ?
				wp_die( __('Tcheater ? You try to export a custom type who not exist...', 'simple-customtypes') );
			} else {
				$cpt_data = $current_options['customtypes'][$cpt_name];
			}
			
			// Get proper args
			$args = $simple_customtypes['client']->prepareArgs( $cpt_data );
			
			// Get args to code
			$code = 'register_post_type( "'.$cpt_name.'", '.var_export($args, true).' );';
			
			// Get plugin template
			$output = file_get_contents( SCUST_DIR . '/inc/template/plugin.tpl' );
			
			// Replace marker by variables
			$output = str_replace( '%CPT_LABEL%', $args['labels']['name'], $output );
			$output = str_replace( '%CPT_NAME%', $cpt_name, $output );
			$output = str_replace( '%CPT_CODE%', $code, $output );
			
			// Force download
			header( "Content-Disposition: attachment; filename=" . $cpt_name.'.php' );
			header( "Content-Type: application/force-download" );
			header( "Content-Type: application/octet-stream" );
			header( "Content-Type: application/download" );
			header( "Content-Description: File Transfer" ); 
			flush(); // this doesn't really matter.
			
			die($output);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check $_GET datas for delete a custom type
	 *
	 * @return boolean
	 * @author Amaury Balmer
	 */
	function checkDeleteCustomType() {
		if ( isset($_GET['action']) && isset($_GET['customtype_name']) && $_GET['action'] == 'delete' ) {
			check_admin_referer( 'delete-customtype-'.$_GET['customtype_name'] );
			
			$customtype = array();
			$customtype['name'] = stripslashes($_GET['customtype_name']);
			$this->deleteCustomType( $customtype, false );
			
			// Flush rewriting rules !
			global $wp_rewrite;
			$wp_rewrite->flush_rules(false);
			
			return true;
		} elseif ( isset($_GET['action']) && isset($_GET['customtype_name']) && $_GET['action'] == 'flush-delete' ) {
			check_admin_referer( 'flush-delete-customtype-'.$_GET['customtype_name'] );
			
			$customtype = array();
			$customtype['name'] = stripslashes($_GET['customtype_name']);
			$this->deleteCustomType( $customtype, true );
			
			// Flush rewriting rules !
			global $wp_rewrite;
			$wp_rewrite->flush_rules(false);
			
			return true;
		}
		return false;
	}
	
	/**
	 * Add custom type options
	 *
	 * @param array $customtype 
	 * @return void
	 * @author Amaury Balmer
	 */
	function addCustomType( $customtype ) {
		$current_options = get_option( SCUST_OPTION );
		
		if ( isset($current_options['customtypes'][$customtype['name']]) ) { // User custom type already exist ?
			wp_die( __('Tcheater ? You try to add a custom type with a name already used by an another custom type.', 'simple-customtypes') );
		}
		$current_options['customtypes'][$customtype['name']] = $customtype;
		
		// Save
		update_option( SCUST_OPTION, $current_options );
		
		wp_redirect( $this->admin_url.'&message=added&cpt=' . $customtype['name']);
		exit();
	}
	
	/**
	 * Update custom type options
	 *
	 * @param array $customtype 
	 * @return void
	 * @author Amaury Balmer
	 */
	function updateCustomType( $customtype ) {
		$current_options = get_option( SCUST_OPTION );
		
		if ( !isset($current_options['customtypes'][$customtype['name']]) ) { // Custom type not exist ?
			wp_die( __('Tcheater ? You try to edit a custom type with a name different as original. Simple Custom Fields dont allow update the name. Propose a patch ;)', 'simple-customtypes') );
		}
		$current_options['customtypes'][$customtype['name']] = $customtype;
		
		// Save
		update_option( SCUST_OPTION, $current_options );
		
		wp_redirect( $this->admin_url.'&message=updated&cpt=' . $customtype['name'] );
		exit();
	}
	
	/**
	 * Delete a custom type
	 *
	 * @param array $customtype 
	 * @param boolean $flush_content 
	 * @return void
	 * @author Amaury Balmer
	 */
	function deleteCustomType( $customtype, $flush_content = false ) {
		$current_options = get_option( SCUST_OPTION );
		
		if ( !isset($current_options['customtypes'][$customtype['name']]) ) { // CPT not exist ?
			wp_die( __('Tcheater ? You try to delete a custom type who not exist...', 'simple-customtypes') );
		}
		
		// Delete from options
		unset($current_options['customtypes'][$customtype['name']]);
		
		// Delete object relations/terms
		if ( $flush_content == true )
			$this->deleteObjectsCustomType( $customtype['name'] );
		
		// Save settings
		update_option( SCUST_OPTION, $current_options );
		
		// Remove custom role for this post type
		$this->removeRole( $customtype['name'] );
		
		if ( $flush_content == true )
			wp_redirect( $this->admin_url.'&message=flush-deleted' );
		else
			wp_redirect( $this->admin_url.'&message=deleted' );
		exit();
	}
	
	/**
	 * Delete all objects for a specific custom type
	 *
	 * @param string $custom_type_name 
	 * @return void
	 * @author Amaury Balmer
	 */
	function deleteObjectsCustomType( $custom_type_name = '' ) {
		global $wpdb;
		
		if ( empty($custom_type_name) )
			return false;
		
		// Get all id with this post type
		$p_ids = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = %s", $custom_type_name) );
		if ( $p_ids == false || is_wp_error($p_ids) )
			return false;
		
		// Delete each post
		foreach( (array) $p_ids as $p_id ) {
			wp_delete_post( $p_id, true );
		}
		
		return true;
	}
	
	/**
	 * Helper for build the HTML selector true/false
	 *
	 * @param string $key 
	 * @return string|array
	 * @author Amaury Balmer
	 */
	function getTrueFalse( $key = '' ) {
		$types = array( '1' => __('True', 'simple-customtypes'), '0' => __('False', 'simple-customtypes') );
		if ( isset($types[$key]) ) {
			return $types[$key];
		}
		
		return $types;
	}
	
	/**
	 * Helper for build the HTML selector capability type
	 *
	 * @param string $key 
	 * @return string|array
	 * @author Amaury Balmer
	 */
	function getCapabilityType( $key = '' ) {
		$capability_types = array(
			'post' => 'Post',
			'page' => 'Page',
			'custom' => 'Custom'
		);
		
		$capability_types = apply_filters( 'sctypes-capability-type', $capability_types, $key );
		if ( isset($capability_types[$key]) ) {
			return $capability_types[$key];
		}
		
		return $capability_types;
	}
	
	/**
	 * Reset a custom role
	 *
	 * @param string $post_type
	 * @return bool
	 * @author Benjamin Niess
	 */
	function flushRole( $post_type = '' ) {
		if ( empty($post_type) || !post_type_exists($post_type) )
			return false;
			
		$post_type_obj = get_post_type_object( $post_type );
		if ( empty( $post_type_obj ) )
			return false;
		
		// Create a slug for the role
		$current_options = get_option( SCUST_OPTION );
		if ( !isset( $current_options['customtypes'][$post_type]['custom_role'] ) || empty( $current_options['customtypes'][$post_type]['custom_role'] ) )
			$current_options['customtypes'][$post_type]['custom_role'] = $post_type_obj->name;
		
		// Build a slug for role.
		$role_slug = sanitize_title( $current_options['customtypes'][$post_type]['custom_role'] );
		
		// Get or create the custom role
		$custom_role = get_role( $role_slug );
		if ( $custom_role == false ) {
			// Create role
			add_role( $role_slug, sprintf(__('%s Editor', 'simple-customtypes'), $post_type_obj->labels->name) );
			
			// Get role object
			$custom_role = get_role( $role_slug );
		}
		
		// Get default roles
		$admin_role  = get_role( 'administrator' );
		$editor_role = get_role( 'editor' );
		
		// Add caps for this roles
		
		foreach( (array) $post_type_obj->cap as $capability_value ) {
			$admin_role->add_cap( $capability_value, true );
			$editor_role->add_cap( $capability_value, true );
			
			if ( isset($custom_role) && !empty($custom_role) )
				$custom_role->add_cap( $capability_value, true );
		}
		
		return true;
	}

	/**
	 * Remove a custom role from the role list
	 *
	 * @param string $post_type
	 * @return bool
	 * @author Benjamin Niess
	 */
	function removeRole( $post_type = '' ) {
		$current_options = get_option(SCUST_OPTION);
		if ( empty($post_type) || !post_type_exists($post_type) )
			return false;
		
		// Get role name
		$role = ( isset($current_options['customtypes'][$post_type]['custom_role']) && !empty($current_options['customtypes'][$post_type]['custom_role']) ) ? sanitize_title($current_options['customtypes'][$post_type]['custom_role']) : $post_type; 
		
		// Role exist ?
		$current_role = get_role($role);
		if ( empty($current_role) )
			return false;
		
		return remove_role( $role );
	}
	
	/**
	 * Helper for build the HTML selector features
	 *
	 * @param string $key 
	 * @return string|array
	 * @author Amaury Balmer
	 */
	function getSupportFeatures( $key = '' ) {
		$supports = array(
			'title' 			=> __('Title', 'simple-customtypes'),
			'editor' 			=> __('Visual editor', 'simple-customtypes'),
			'author' 			=> __('Author', 'simple-customtypes'),
			'thumbnail' 		=> __('Thumbnail for content', 'simple-customtypes'),
			'excerpt' 			=> __('Excerpt', 'simple-customtypes'),
			'trackbacks'		=> __('Trackbacks', 'simple-customtypes'),
			'custom-fields'		=> __('Custom fields', 'simple-customtypes'),
			'comments'			=> __('Comments', 'simple-customtypes'),
			'revisions'			=> __('Revisions', 'simple-customtypes'),
			'post-formats'		=> __('Post formats <em>(new in 3.1)</em>', 'simple-customtypes'),
			'page-attributes'	=> __('Attributes pages (useful only for objects with hierarchy)', 'simple-customtypes')
		);
		
		$supports = apply_filters( 'sctypes-supports-features', $supports, $key );
		if ( isset($supports[$key]) ) {
			return $supports[$key];
		}
		
		return $supports;
	}
	
	/**
	 * Display WP alert
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	function displayMessage() {
		if ( $this->message != '') {
			$message = $this->message;
			$status = $this->status;
			$this->message = $this->status = ''; // Reset
		}
		
		if ( isset($message) && !empty($message) ) {
			?>
			<div id="message" class="<?php echo ($status != '') ? $status :'updated'; ?> fade">
				<p><strong><?php echo $message; ?></strong></p>
			</div>
			<?php
		}
	}
}
?>