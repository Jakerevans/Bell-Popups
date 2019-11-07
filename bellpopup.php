<?php
/**
 * WordPress Book List BellPopUp Extension
 *
 * @package     WordPress Book List BellPopUp Extension
 * @author      Jake Evans
 * @copyright   2018 Jake Evans
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: BellPopUp Extension
 * Plugin URI: https://www.jakerevans.com
 * Description: A Plugin that allows the admins of the site to create Pop-Ups, embed videos in them, and display to particular visitors based on specific criteria.
 * Version: 1.0.0
 * Author: Jake Evans
 * Text Domain: bellpopup
 * Author URI: https://www.jakerevans.com
 */

/*
 * SETUP NOTES:
 *
 * Rename root plugin folder to an all-lowercase version of bellpopup
 *
 * Change all filename instances from bellpopup to desired plugin name
 *
 * Modify Plugin Name
 *
 * Modify Description
 *
 * Modify Version Number in Block comment and in Constant
 *
 * Find & Replace these 3 strings:
 * bellpopup
 * bellPopup
 * Bellpopup
 * BellPopUp
 * BELLPOPUP
 * BellPopup
 * bellpopupplugin
 * $bellpopup
 * BELLPLUGINTOPLEVEL
 * repw with something also random - db column that holds license.
 *
 *
 * Change the EDD_SL_ITEM_ID_BELLPOPUP contant below.
 *
 * Install Gulp & all Plugins listed in gulpfile.js
 *
 *
 *
 *
 */




// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/* REQUIRE STATEMENTS */
	require_once 'includes/class-bellpopup-general-functions.php';
	require_once 'includes/class-bellpopup-ajax-functions.php';
	require_once 'includes/classes/update/class-bellpopup-update.php';
/* END REQUIRE STATEMENTS */

/* CONSTANT DEFINITIONS */

	if ( ! defined('BELLPOPUP_VERSION_NUM' ) ) {
		define( 'BELLPOPUP_VERSION_NUM', '1.0.0' );
	}

	// This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
	define( 'EDD_SL_STORE_URL_BELLPOPUP', 'https://bellpopupplugin.com' );

	// The id of your product in EDD.
	define( 'EDD_SL_ITEM_ID_BELLPOPUP', 46 );

	// This Extension's Version Number.
	define( 'BELLPOPUP_VERSION_NUM', '1.0.0' );

	// Root plugin folder directory.
	define( 'BELLPOPUP_ROOT_DIR', plugin_dir_path( __FILE__ ) );

	// Root WordPress Plugin Directory. The If is for taking into account the update process - a temp folder gets created when updating, which temporarily replaces the 'bellpopupplugin-bulkbookupload' folder.
	if ( false !== stripos( plugin_dir_path( __FILE__ ) , '/bellpopup' ) ) { 
		define( 'BELLPOPUP_ROOT_WP_PLUGINS_DIR', str_replace( '/bellpopup', '', plugin_dir_path( __FILE__ ) ) );
	} else {
		$temp = explode( 'plugins/', plugin_dir_path( __FILE__ ) );
		define( 'BELLPOPUP_ROOT_WP_PLUGINS_DIR', $temp[0] . 'plugins/' );
	}

	// Root plugin folder URL .
	define( 'BELLPOPUP_ROOT_URL', plugins_url() . '/bellpopup/' );

	// Root Classes Directory.
	define( 'BELLPOPUP_CLASS_DIR', BELLPOPUP_ROOT_DIR . 'includes/classes/' );

	// Root Update Directory.
	define( 'BELLPOPUP_UPDATE_DIR', BELLPOPUP_CLASS_DIR . 'update/' );

	// Root REST Classes Directory.
	define( 'BELLPOPUP_CLASS_REST_DIR', BELLPOPUP_ROOT_DIR . 'includes/classes/rest/' );

	// Root Compatability Classes Directory.
	define( 'BELLPOPUP_CLASS_COMPAT_DIR', BELLPOPUP_ROOT_DIR . 'includes/classes/compat/' );

	// Root Transients Directory.
	define( 'BELLPOPUP_CLASS_TRANSIENTS_DIR', BELLPOPUP_ROOT_DIR . 'includes/classes/transients/' );

	// Root Image URL.
	define( 'BELLPOPUP_ROOT_IMG_URL', BELLPOPUP_ROOT_URL . 'assets/img/' );

	// Root Image Icons URL.
	define( 'BELLPOPUP_ROOT_IMG_ICONS_URL', BELLPOPUP_ROOT_URL . 'assets/img/icons/' );

	// Root CSS URL.
	define( 'BELLPOPUP_CSS_URL', BELLPOPUP_ROOT_URL . 'assets/css/' );

	// Root JS URL.
	define( 'BELLPOPUP_JS_URL', BELLPOPUP_ROOT_URL . 'assets/js/' );

	// Root UI directory.
	define( 'BELLPOPUP_ROOT_INCLUDES_UI', BELLPOPUP_ROOT_DIR . 'includes/ui/' );

	// Root UI Admin directory.
	define( 'BELLPOPUP_ROOT_INCLUDES_UI_ADMIN_DIR', BELLPOPUP_ROOT_DIR . 'includes/ui/' );

	// Define the Uploads base directory.
	$uploads     = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	define( 'BELLPOPUP_UPLOADS_BASE_DIR', $upload_path . '/' );

	// Define the Uploads base URL.
	$upload_url = $uploads['baseurl'];
	define( 'BELLPOPUP_UPLOADS_BASE_URL', $upload_url . '/' );

	// Nonces array.
	define( 'BELLPOPUP_NONCES_ARRAY',
		wp_json_encode(array(
			'adminnonce1' => 'bellpopup_save_license_key_action_callback',
			'adminnonce2' => 'bellpopup_save_new_popup_action_callback',
		))
	);

/* END OF CONSTANT DEFINITIONS */

/* MISC. INCLUSIONS & DEFINITIONS */

	// Loading textdomain.
	load_plugin_textdomain( 'bellpopup', false, BELLPOPUP_ROOT_DIR . 'languages' );

/* END MISC. INCLUSIONS & DEFINITIONS */

/* CLASS INSTANTIATIONS */

	// Call the class found in bellpopupplugin-functions.php.
	$bellpopup_general_functions = new BellPopUp_General_Functions();

	// Call the class found in bellpopupplugin-functions.php.
	$bellpopup_ajax_functions = new BellPopUp_Ajax_Functions();

	// Include the Update Class.
	$bellpopup_update_functions = new BellPopup_Toplevel_Update();


/* END CLASS INSTANTIATIONS */


/* FUNCTIONS FOUND IN CLASS-WPPLUGIN-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// For the admin pages.
	add_action( 'admin_menu', array( $bellpopup_general_functions, 'bellpopup_jre_my_admin_menu' ) );


	// Adding the function that will take our BELLPOPUP_NONCES_ARRAY Constant from above and create actual nonces to be passed to Javascript functions.
	add_action( 'init', array( $bellpopup_general_functions, 'bellpopup_create_nonces' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	//add_action( 'plugins_loaded', array( $bellpopup_general_functions, 'bellpopup_update_upgrade_function' ) );

	// Adding the admin js file.
	add_action( 'admin_enqueue_scripts', array( $bellpopup_general_functions, 'bellpopup_admin_js' ) );

	// Adding the frontend js file.
	add_action( 'wp_enqueue_scripts', array( $bellpopup_general_functions, 'bellpopup_frontend_js' ) );

	// Adding the admin css file for this extension.
	add_action( 'admin_enqueue_scripts', array( $bellpopup_general_functions, 'bellpopup_admin_style' ) );

	// Adding the Front-End css file for this extension.
	add_action( 'wp_enqueue_scripts', array( $bellpopup_general_functions, 'bellpopup_frontend_style' ) );

	// Function to add table names to the global $wpdb.
	add_action( 'admin_footer', array( $bellpopup_general_functions, 'bellpopup_register_table_name' ) );

	// Function that adds in any possible admin pointers
	add_action( 'admin_footer', array( $bellpopup_general_functions, 'bellpopup_admin_pointers_javascript' ) );

	// Creates tables upon activation.
	register_activation_hook( __FILE__, array( $bellpopup_general_functions, 'bellpopup_create_tables' ) );

	// Adding the front-end login / dashboard shortcode.
	add_shortcode( 'bellpopup_login_shortcode', array( $bellpopup_general_functions, 'bellpopup_login_shortcode_function' ) );



/* END OF FUNCTIONS FOUND IN CLASS-WPPLUGIN-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

/* FUNCTIONS FOUND IN CLASS-WPPLUGIN-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// Function to add table names to the global $wpdb.
	add_action( 'wp_ajax_bellpopup_save_new_popup_action', array( $bellpopup_ajax_functions, 'bellpopup_save_new_popup_action_callback' ) );


/* END OF FUNCTIONS FOUND IN CLASS-WPPLUGIN-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */






















