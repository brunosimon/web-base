<?php

$Data = $this->get_data( 'user_role' );
$UserRoles = $this->get_user_role();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php _e( 'Reset User Roles' , $this->ltd ); ?></h2>
	<p>&nbsp;</p>

	<form id="wauc_reset_userrole" class="wauc_form" method="post" action="<?php echo remove_query_arg( 'wauc_msg' , add_query_arg( array( 'page' => $this->PageSlug ) ) ); ?>">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
		<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
		<input type="hidden" name="record_field" value="user_role" />

		<h3><?php _e( 'Applied user roles' , $this->ltd ); ?></h3>
		<ul class="description">
			<?php foreach( $Data as $key => $val ) : ?>
				<?php if( !empty( $UserRoles[$key] ) ): ?>
					<li><?php echo $UserRoles[$key]["label"]; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<br />

		<p><?php printf( __( 'Reset the %s?' , $this->ltd ) , __( 'User Roles Settings' , $this->ltd ) ); ?></p>
		<p class="submit">
			<input type="submit" class="button-primary" name="reset" value="<?php _e( 'Reset settings' , $this->ltd ); ?>" />
		</p>

	</form>
	
	<p>&nbsp;</p>

	<h2><?php _e( 'Reset settings of all' , $this->ltd ); ?></h2>
	<form id="wauc_reset_all_settings" class="wauc_form" method="post" action="<?php echo remove_query_arg( 'wauc_msg' , add_query_arg( array( 'page' => $this->PageSlug ) ) ); ?>">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
		<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
		<input type="hidden" name="record_field" value="all_settings" />
		<p><?php _e( 'Setting all of the below will be deleted.' , $this->ltd ); ?></p>
		<ul class="description">
			<li><?php _e( 'Site Settings' , $this->ltd ); ?></li>
			<li><?php printf( __( '%1$s %2$s' , $this->ltd ) , __( 'General' ) , __( 'Settings' ) ); ?></li>
			<li><?php _e( 'Dashboard' ); ?></li>
			<li><?php _e( 'Admin Bar Menu' , $this->ltd ); ?></li>
			<li><?php _e( 'Side Menu' , $this->ltd ); ?></li>
			<li><?php _e( 'Manage meta box' , $this->ltd ); ?></li>
			<li><?php _e( 'Add New Post and Edit Post Screen Setting' , $this->ltd ); ?></li>
			<li><?php _e( 'Appearance Menus Screen Setting' , $this->ltd ); ?></li>
			<li><?php _e( 'Login Screen' , $this->ltd ); ?></li>
			<li><?php printf( __( '%1$s of %2$s %3$s' , $this->ltd ) , __( 'Change' ) , __( 'Plugin' ) , __( 'Capabilities' ) ); ?></li>
		</ul>
		<br />

		<p><?php _e( 'Are you sure you want to delete all settings?' , $this->ltd ); ?></p>
		<p class="submit">
			<input type="submit" class="button-primary" name="reset" value="<?php _e( 'Reset settings of all' , $this->ltd ); ?>" />
		</p>

	</form>


</div>
