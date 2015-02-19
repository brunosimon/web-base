<?php
/*
Plugin Name: Simple Custom Post Types
Version: 3.4.1
Plugin URI: http://wordpress.org/extend/plugins/simple-custom-types/
Description: WordPress 3.1 and up allow to manage new custom post type, this plugin makes it even simpler, removing the need for you to write <em>any</em> code. Excerpt update your theme ! Adds friendly permalink support, template files, and a conditional for public, non-hierarchical custom post types. 
Author: Amaury Balmer
Author URI: http://www.beapi.fr

----

Copyright 2009-2012 Amaury Balmer (amaury@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

---

Todo :
	Admin
	Client
*/

// Folder name
define ( 'SCUST_VERSION', '3.4.1' );
define ( 'SCUST_OPTION',  'simple-custom-types' );
define ( 'SCUST_FOLDER',  'simple-custom-types' );

define ( 'SCUST_URL', plugins_url('', __FILE__) );
define ( 'SCUST_DIR', dirname(__FILE__) );

// Call all class and functions
require( SCUST_DIR . '/inc/class.client.php' );
require( SCUST_DIR . '/inc/class.widget.php' );
//require( SCUST_DIR . '/inc/functions.api.php' );
//require( SCUST_DIR . '/inc/functions.tpl.php' );

if ( is_admin() ) {
	// Admin class
	require( SCUST_DIR . '/inc/class.admin.php' );
	require( SCUST_DIR . '/inc/class.admin.conversion.php' );
}

add_action( 'plugins_loaded', 'initSimpleCustomTypes' );
function initSimpleCustomTypes() {
	global $simple_customtypes;
	
	// Load translations
	load_plugin_textdomain ( 'simple-customtypes', false, basename(rtrim(dirname(__FILE__), '/')) . '/languages' );
	
	// Client
	$simple_customtypes['client'] = new SimpleCustomTypes_Client();
	
	// Admin
	if ( is_admin() ) {
		$simple_customtypes['admin'] = new SimpleCustomTypes_Admin();
		$simple_customtypes['admin-conversion'] = new SimpleCustomTypes_Admin_Conversion();
	}
	
	// Widget
	add_action( 'widgets_init', create_function('', 'return register_widget("Widget_Recent_Objects");') );
}
?>