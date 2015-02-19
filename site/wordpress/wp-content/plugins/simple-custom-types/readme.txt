=== Simple Custom Post Types ===
Contributors: momo360modena
Donate link: http://www.beapi.fr/donate/
Tags: custom, post types, cms, post type, archive, view, permalink, rewriting, rewrite
Requires at least: 3.1
Stable tag: 3.4.1
Tested up to: 3.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress 3.1 and up allow to manage new custom post type, this plugin makes it even simpler, removing the need for you to write <em>any</em> code.

== Description ==

WordPress 3.1 and up allow to manage new custom post type, this plugin makes it even simpler, removing the need for you to write <em>any</em> code. Excerpt update your theme !

This plugin provides a nice interface and easy access. The plugin provides almost all the parameters of the WordPress CPT API.

It's possible to manage the permissions of custom post types such as articles or pages. Or create a full set of custom permissions and related roles !

This plugin is developped on WordPress 3.3, with the constant WP_DEBUG to TRUE.

== Frequently Asked Questions ==

= Does this plugin handles custom fields? =

No, we prefer to use 2 plugins specifically designed to meet two different needs ...

= How to create a custom taxonomy ? =

You can use a another plugin writted by Me and BeAPI :

* [Simple Custom Taxonomy](http://wordpress.org/extend/plugins/simple-taxonomy/)

= How to create a custom role? =

You must install a plugin for managing roles and permissions as:

* [Use role editor](http://wordpress.org/extend/plugins/user-role-editor/)
* [Capability Manager](http://wordpress.org/extend/plugins/capsman/)

== Installation ==

 **Required PHP5.**

1. Download, unzip and upload to your WordPress plugins directory
2. Activate the plugin within you WordPress Administration Backend
3. Go to Settings > Custom Post Types and follow the steps on the [Simple Custom Post Types](http://redmine.beapi.fr/projects/show/simple-customtypes) page

== Screenshots ==

1. Settings page

== Changelog ==

* Version 3.4.1 : 
	* Change default position on menu (used 30)
	* Fix default icons, put FALSE
* Version 3.4 :
	* Add export PHP feature
	* Mark as compatible with WP 3.4.1
* Version 3.3 :
	* Convert conversion tools to BULK edit
	* Sync POT with latest trunk
* Version 3.2.2 :
	* Add role creation for each post type (thanks to benjaminniess for brilliant patch and me for idea :D)
* Version 3.2.1.1 :
	* Fix possible bug with others plugin's beapi that use import 
* Version 3.2.1 :
	* Fix possible bug with others plugin's beapi that use export
* Version 3.2 :
	* Fix a bug with permalink flush on admin... Global not defined...
	* Add Export/Import config tool
* Version 3.1.2 :
	* Fix a bug with permalink flush on admin. Performance improvement !
* Version 3.1.1 :
	* Add possibility to custom slug for CPT archives...
	* Add french translation. (not finished)
* Version 3.1 :
	* Remove custom features archive
	* Compatible 3.1
	* Allow delete without flushing contents
	* Add JS confirmation before deleting.
	* Add all caps
	* Add "post formats" feature to CPT supports
	* Update readme.txt
* Version 3.0.1 :
	* Add some and improve features from Simple Custom Post Type Archives
* Version 3.0.0 :
	* First version stable
	
== Upgrade Notice ==

Nothing here :)