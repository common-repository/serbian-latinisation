<?php
/*
Plugin Name: Serbian Latinisation
Description: Serbian Latinisation plugin allows you to have a website in both cyrillic and latin scripts
Version: 1.4
Author: Sibin Grasic
Author URI: http://sgi.io
Text Domain: serbian-latinisation
*/

/**
 * 
 * @package SGI\SSR
 */

/* Prevent Direct access */
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/*Define plugin main file*/
if ( !defined('SGI_SRLAT_FILE') )
	define ( 'SGI_SRLAT_FILE', __FILE__ );

/* Define BaseName */
if ( !defined('SGI_SRLAT_BASENAME') )
	define ('SGI_SRLAT_BASENAME',plugin_basename(SGI_SRLAT_FILE));

/* Define internal path */
if ( !defined( 'SGI_SRLAT_PATH' ) )
	define( 'SGI_SRLAT_PATH', plugin_dir_path( SGI_SRLAT_FILE ) );

/* Define internal version for possible update changes */
define ('SGI_SRLAT_VERSION', '1.4');

/* Load Up the text domain */
function sgi_SRLAT_load_textdomain()
{
	load_plugin_textdomain('serbian-latinisation', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('wp_loaded','sgi_srlat_load_textdomain');

/* Check if we're running compatible software */
if ( version_compare( PHP_VERSION, '5.4', '<' ) && version_compare(WP_VERSION, '3.7', '<') ) :
	if (is_admin()) :
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( __FILE__ );
		wp_die(__('Serbian Latinization plugin requires WordPress 3.7 and PHP 5.4 or greater. The plugin has now disabled itself','serbian-latinisation'));
	endif;
endif;

/* Let's load up our plugin */

require_once (SGI_SRLAT_PATH.'lib/utils.php');
require_once (SGI_SRLAT_PATH.'lib/sgi-translit-core.php');

function sgi_srlat_widget_init()
{

	$wpml_active = (defined('ICL_LANGUAGE_CODE'));
	
	if (!$wpml_active) :

		require_once (SGI_SRLAT_PATH.'lib/widget.sgi-script-selector.php');
		register_widget('SGI_SRLat_Widget');

	endif;

}

function sgi_srlat_backend_init()
{

	require_once (SGI_SRLAT_PATH.'lib/backend/admin-core.php');
	require_once (SGI_SRLAT_PATH.'lib/backend/admin-utils.php');

	new SGI_SRLat_Admin_Core();
	new SGI_SRLat_Admin_Utils();
}

function sgi_srlat_frontend_init()
{
	require_once (SGI_SRLAT_PATH.'lib/frontend/frontend-core.php');
	require_once (SGI_SRLAT_PATH.'lib/frontend/shortcode-cyrilic.php');
	SGI_SRLat_Frontend::get_instance();
	SGI_SRLat_Cyr_Shortcode::get_instance();
}


if (is_admin()) : 

	add_action('plugins_loaded','sgi_srlat_backend_init');

else :

	add_action('plugins_loaded','sgi_srlat_frontend_init',20);

endif;

add_action('widgets_init','sgi_srlat_widget_init',20);