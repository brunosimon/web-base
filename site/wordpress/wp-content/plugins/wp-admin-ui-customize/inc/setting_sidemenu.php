<?php

$this->get_user_role();
$Data = $this->get_data( 'sidemenu' );

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-draggable' , 'jquery-ui-droppable' , 'jquery-ui-sortable' , 'thickbox' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style('thickbox');
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

?>

<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php _e( 'Side Menu' , $this->ltd ); ?></h2>
	<p><?php _e( 'Please change the menu by drag and drop.' , $this->ltd ); ?></p>
	<p class="description"><?php echo sprintf( __( 'New plugin menus will be added to the <strong>%s</strong>.' , $this->ltd ) , __( 'Menu items that can be added' , $this->ltd ) ); ?>
	<p><strong><?php _e( 'Notice: Please do not place the same multiple menu slug.' , $this->ltd ); ?></strong></p>

	<h3 id="wauc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>

	<p><a href="#TB_inline?height=300&width=600&inlineId=list_variables&modal=false" title="<?php _e( 'Shortcodes' , $this->ltd ); ?>" class="thickbox"><?php _e( 'Available Shortcodes' , $this->ltd ); ?></a></p>

	<form id="wauc_setting_sidemenu" class="wauc_form" method="post" action="<?php echo remove_query_arg( 'wauc_msg' , add_query_arg( array( 'page' => $this->PageSlug ) ) ); ?>">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
		<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
		<input type="hidden" name="record_field" value="sidemenu" />

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="postbox-container-1" class="postbox-container">

					<div id="can_menus">

						<div class="postbox">
							<h3 class="hndle"><span><?php _e( 'Menu items that can be added' , $this->ltd ); ?></span></h3>
							<div class="inside">
		
								<p class="description"><?php _e( 'Sepalator' , $this->ltd ); ?></p>
								<?php $menu_widget = array( 'title' => '-' , 'slug' => 'separator' , 'parent_slug' => '' , 'new' => true , 'cap' => 'read' ); ?>
								<?php $this->sidebar_menu_widget( $menu_widget ); ?>
								<div class="clear"></div>
								
								<?php foreach($this->Menu as $mm) : ?>
		
									<?php if( !empty( $mm[0] ) ) : ?>
		
										<?php $menu_title = $mm[0]; ?>
										<?php if( $mm[5] == 'menu-comments' ) : ?>
											<?php $menu_title = __( 'Comments' ); ?>
										<?php elseif( $mm[5] == 'menu-appearance' ) : ?>
											<?php $menu_title = __( 'Appearance' ); ?>
										<?php elseif( $mm[5] == 'menu-plugins' ) : ?>
											<?php $menu_title = __( 'Plugins' ); ?>
										<?php endif; ?>
										<p class="description"><?php echo $menu_title; ?></p>

										<?php if( empty( $this->SubMenu[$mm[2]] ) ) : ?>

											<?php $menu_widget = array( 'title' => $menu_title , 'slug' => $mm[2] , 'parent_slug' => '' , 'new' => true , 'cap' => $mm[1] , 'submenu' => '' ); ?>
											<?php $this->sidebar_menu_widget( $menu_widget ); ?>

										<?php else: ?>

											<?php foreach($this->SubMenu as $parent_slug => $sub) : ?>
					
												<?php foreach($sub as $sm) : ?>
			
													<?php if( $mm[2] == $parent_slug ) : ?>
														<?php $menu_title = $sm[0]; ?>
														<?php if( $sm[1] == 'update_core' ) : ?>
															<?php $menu_title = __( 'Update' ) . ' [update_total]'; ?>
														<?php elseif( $sm[2] == 'edit-comments.php' ) : ?>
															<?php $menu_title .= ' [comment_count]'; ?>
														<?php elseif( $sm[2] == 'themes.php' ) : ?>
															<?php $menu_title .= ' [update_themes]'; ?>
														<?php elseif( $sm[2] == 'plugins.php' ) : ?>
															<?php $menu_title .= ' [update_plugins]'; ?>
														<?php endif; ?>
														<?php $menu_widget = array( 'title' => $menu_title , 'slug' => $sm[2] , 'parent_slug' => '' , 'new' => true , 'cap' => $sm[1] , 'submenu' => '' ); ?>
														<?php $this->sidebar_menu_widget( $menu_widget ); ?>
													<?php endif; ?>
					
												<?php endforeach; ?>
					
											<?php endforeach; ?>

										<?php endif; ?>

										<div class="clear"></div>
		
									<?php endif; ?>
		
								<?php endforeach; ?>
		
							</div>
		
						</div>

					</div>
					
				</div>
				
				<div id="postbox-container-2" class="postbox-container">

					<div id="setting_menus">

						<div class="postbox">
							<h3 class="hndle">
								<span><?php _e( 'Current menu' , $this->ltd ); ?></span>
							</h3>
							<div class="inside widgets-holder-wrap">
		
								<?php if( empty( $Data ) ) : ?>
		
									<?php foreach($this->Menu as $mm) : ?>
		
										<?php if( isset( $mm[2] ) && strstr( $mm[2] , 'separator' ) ) : ?>
		
											<?php $menu_title = '-'; ?>
											<?php $mm[2] = 'separator'; ?>
											<?php $mwsm = array(); ?>
		
										<?php elseif( !empty( $mm[0] ) ) : ?>
			
											<?php $menu_title = $mm[0]; ?>
											<?php if( !empty( $mm[5] ) ) : ?>
												<?php if( $mm[5] == 'menu-comments' ) : ?>
													<?php $menu_title = __( 'Comments' ) . ' [comment_count]'; ?>
												<?php elseif( $mm[5] == 'menu-appearance' ) : ?>
													<?php $menu_title = __( 'Appearance' ) . ' [update_themes]'; ?>
												<?php elseif( $mm[5] == 'menu-plugins' ) : ?>
													<?php $menu_title = __( 'Plugins' ) . ' [update_plugins]'; ?>
												<?php endif; ?>
											<?php endif; ?>
		
											<?php $mwsm = array(); ?>
											<?php foreach($this->SubMenu as $parent_slug => $sub) : ?>
												<?php foreach($sub as $sm) : ?>
													<?php if( $mm[2] == $parent_slug ) : ?>
														<?php $submenu_title = $sm[0]; ?>
														
														<?php if( $sm[1] == 'update_core' ) : ?>
															<?php $submenu_title = __( 'Update' ) . ' [update_total]'; ?>
														<?php endif; ?>
														<?php $mwsm[] = array( 'title' => $submenu_title , 'slug' => $sm[2] , 'parent_slug' => $parent_slug , 'cap' => $sm[1] ); ?>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endforeach; ?>
		
										<?php endif; ?>
		
										<?php $menu_widget = array( 'title' => $menu_title , 'slug' => $mm[2] , 'parent_slug' => '' , 'new' => false , 'cap' => $mm[1] , 'submenu' => $mwsm ); ?>
										<?php $this->sidebar_menu_widget( $menu_widget ); ?>
			
									<?php endforeach; ?>
		
								<?php else: ?>
		
									<?php if( !empty( $Data["main"] ) ) : ?>
		
										<?php foreach($Data["main"] as $mm) : ?>
										
											<?php if( !empty( $mm["title"] ) ) : ?>
		
												<?php $mwsm = array(); ?>
												<?php if( !empty( $Data["sub"] ) ) : ?>
													<?php foreach($Data["sub"] as $sm) : ?>
				
														<?php if( $mm["slug"] == $sm["parent_slug"] ) : ?>

															<?php $cap = ""; ?>
															<?php if( !empty( $submenu[$mm["slug"]] ) ) : ?>
																<?php foreach( $submenu[$mm["slug"]] as $k => $tmp_sm ) : ?>
																	<?php if( $tmp_sm[2] == $sm["slug"] ) : ?>
																		<?php $cap = $tmp_sm[1]; ?>
																		<?php break; ?>
																	<?php endif; ?>
																<?php endforeach; ?>
															<?php endif; ?>
				
															<?php $mwsm[] = array( 'title' => $sm["title"] , 'slug' => $sm["slug"] , 'parent_slug' => $sm["parent_slug"] , 'cap' => $cap ); ?>
				
														<?php endif; ?>
				
													<?php endforeach; ?>
												<?php endif; ?>
												
												<?php $cap = ""; ?>
												<?php foreach( $this->Menu as $tmp_m ) : ?>
													<?php if( $tmp_m[2] == $mm["slug"] ) : ?>
														<?php $cap = $tmp_m[1]; ?>
														<?php break; ?>
													<?php endif; ?>
												<?php endforeach; ?>
		
												<?php $menu_widget = array( 'title' => $mm["title"] , 'slug' => $mm["slug"] , 'parent_slug' => '' , 'new' => false , 'cap' =>$cap , 'submenu' => $mwsm ); ?>
												<?php $this->sidebar_menu_widget( $menu_widget ); ?>
		
											<?php endif; ?>
		
										<?php endforeach; ?>
		
									<?php endif; ?>
		
								<?php endif; ?>
		
							</div>

						</div>
		
						<p class="sidebar_setting_delete"><a href="#"><?php _e( 'Delete all the Current menu' , $this->ltd ); ?></a></p>

					</div>
				</div>
				
				<br class="clear">
				
			</div>
			
		</div>

		<p class="submit">
			<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
		</p>

		<p class="submit reset">
			<span class="description"><?php printf( __( 'Reset the %s?' , $this->ltd ) , __( 'Side Menu' , $this->ltd ) . __( 'Settings' ) ); ?></span>
			<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset settings' , $this->ltd ); ?>" />
		</p>

	</form>

</div>

<?php require_once( dirname( __FILE__ ) . '/list_variables.php' ); ?>

<script type="text/javascript">

var wauc_widget_each, wauc_menu_sortable;

jQuery(document).ready(function($) {

	var $Form = $("#wauc_setting_sidemenu");
	var $AddInside = $('#can_menus .postbox .inside', $Form);
	var $SettingInside = $('#setting_menus .postbox .inside', $Form);

	$AddInside.children('.widget').draggable({
		connectToSortable: '#setting_menus .postbox .inside',
		handle: '> .widget-top > .widget-title',
		distance: 2,
		helper: 'clone',
		zIndex: 5,
		containment: 'document',
		stop: function(e,ui) {
			wauc_widget_each();
			wauc_menu_sortable();
		}
	});

	$('#postbox-container-1', $Form).droppable({
		tolerance: 'pointer',
		accept: function(o){
			return $(o).parent().parent().parent().attr('id') != 'postbox-container-1';
		},
		drop: function(e,ui) {
			ui.draggable.addClass('deleting');
		},
		over: function(e,ui) {
			ui.draggable.addClass('deleting');
			$('div.widget-placeholder').hide();
		},
		out: function(e,ui) {
			ui.draggable.removeClass('deleting');
			$('div.widget-placeholder').show();
		}
	});


	var $AvailableAction = $('#postbox-container-2 .postbox .inside .widget .widget-top .widget-title-action a[href=#available]', $Form);
	$AvailableAction.live( 'click', function() {
		$(this).parent().parent().parent().children(".widget-inside").slideToggle();
		return false;
	});

	var $RemoveAction = $('#postbox-container-2 .postbox .inside .widget .widget-inside .widget-control-actions .alignleft a[href=#remove]', $Form);
	$RemoveAction.live( 'click', function() {
		$(this).parent().parent().parent().parent().slideUp("normal", function() { $(this).remove(); } );
		return false;
	});

	wauc_menu_sortable = function menu_sortable() {

		$('#wauc_setting_sidemenu #poststuff #post-body #postbox-container-2 #setting_menus .postbox .inside, #wauc_setting_sidemenu #poststuff #post-body #postbox-container-2 #setting_menus .postbox .inside .widget .widget-inside .submenu').sortable({
			placeholder: "widget-placeholder",
			items: '> .widget',
			connectWith: "#wauc_setting_sidemenu #poststuff #post-body #postbox-container-2 #setting_menus .postbox .inside, #wauc_setting_sidemenu #poststuff #post-body #postbox-container-2 #setting_menus .postbox .inside .widget .widget-inside .submenu",
			handle: '> .widget-top > .widget-title',
			cursor: 'move',
			distance: 2,
			containment: 'document',
			change: function(e,ui) {
				var $height = ui.helper.height();
				$('#wauc_setting_sidemenu #poststuff #post-body #postbox-container-2 #setting_menus .postbox .inside .widget-placeholder').height($height);
			},
			stop: function(e,ui) {
				if ( ui.item.hasClass('deleting') ) {
					ui.item.remove();
				}
				ui.item.attr( 'style', '' ).removeClass('ui-draggable');
				wauc_widget_each();
			},
		});

	}
	wauc_menu_sortable();

	wauc_widget_each = function widget_each() {
		var $Count = 0;
		$('#wauc_setting_sidemenu #setting_menus .postbox .inside .widget').each(function() {
			var $InputSlug = $(this).children(".widget-inside").children(".settings").children(".description").children(".slugtext");
			var $InputTitle = $(this).children(".widget-inside").children(".settings").children("label").children(".titletext");
			var $InputParentSlug = $(this).children(".widget-inside").children(".settings").children(".parent_slugtext");

			var $Name = 'data' + '['+$Count+']';
			$InputSlug.attr("name", $Name+'[slug]');
			$InputTitle.attr("name", $Name+'[title]');
			$InputParentSlug.attr("name", $Name+'[parent_slug]');

			if ( $(this).parent().parent().parent().parent().hasClass("submenu") ) {
				// None three
				$(this).remove();
			} else if ( $(this).parent().hasClass("submenu") ) {
				var $ParentSlug = $(this).parent().parent().children(".settings").children(".description").children(".slugtext").val();
				$InputParentSlug.val($ParentSlug);
			} else {
				$InputParentSlug.val('');
			}
			$Count++;
		});
	}
	wauc_widget_each();

	$('#wauc_setting_sidemenu #setting_menus .sidebar_setting_delete a').live('click', function() {
		$('#wauc_setting_sidemenu #setting_menus .postbox .inside').html('');
		return false;
	});
		
});
</script>