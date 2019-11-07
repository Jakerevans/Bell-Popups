<?php
/**
 * Class BellPopUp_General_Functions - class-toplevel-general-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BellPopUp_General_Functions', false ) ) :
	/**
	 * BellPopUp_General_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class BellPopUp_General_Functions {

		/**
		 *  Functions that loads up all menu pages/contents, etc.
		 */
		public function bellpopup_jre_admin_page_function() {
			global $wpdb;
			require_once BELLPOPUP_ROOT_INCLUDES_UI_ADMIN_DIR . 'class-admin-master-ui.php';
		}

		/** Functions that loads up the menu page entry for this Extension.
		 *
		 *  @param array $submenu_array - The array that contains submenu entries to add to.
		 */
		public function bellpopup_jre_my_admin_menu() {
			add_menu_page( 'Bell  BellPopUp', 'Bell Popup', 'manage_options', 'BellPopUp-Options', array( $this, 'bellpopup_jre_admin_page_function' ), BELLPOPUP_ROOT_IMG_URL . 'belllogonocanvas.png', 6 );

			$submenu_array = array(
				'Popups',
				//'Submenu Page1',
				//'Submenu Page2',
			);

			// Filter to allow the addition of a new subpage.
			if ( has_filter( 'toplevel_add_sub_menu' ) ) {
				$submenu_array = apply_filters( 'toplevel_add_sub_menu', $submenu_array );
			}

			foreach ( $submenu_array as $key => $submenu ) {
				$menu_slug = strtolower( str_replace( ' ', '-', $submenu ) );
				add_submenu_page( 'BellPopUp-Options', 'BellPopUp', $submenu, 'manage_options', 'BellPopUp-Options-' . $menu_slug, array( $this, 'bellpopup_jre_admin_page_function' ) );
			}

			remove_submenu_page( 'BellPopUp-Options', 'BellPopUp-Options' );
		}

		/**
		 *  Here we take the Constant defined in bellpopupplugin.php that holds the values that all our nonces will be created from, we create the actual nonces using wp_create_nonce, and the we define our new, final nonces Constant, called WPPLUGIN_FINAL_NONCES_ARRAY.
		 */
		public function bellpopup_create_nonces() {

			$temp_array = array();
			foreach ( json_decode( BELLPOPUP_NONCES_ARRAY ) as $key => $noncetext ) {
				$nonce              = wp_create_nonce( $noncetext );
				$temp_array[ $key ] = $nonce;
			}

			// Defining our final nonce array.
			define( 'BELLPLUGINTOPLEVEL_FINAL_NONCES_ARRAY', wp_json_encode( $temp_array ) );

		}

		/**
		 *  Function to run the compatability code in the Compat class for upgrades/updates, if stored version number doesn't match the defined global in bellpopup.php
		 */
		public function bellpopup_update_upgrade_function() {

			// Get current version #.
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'bellpopup_jre_user_options' );

			// Check to see if Extension is already registered and matches this version.
			if ( false !== strpos( $existing_string->extensionversions, 'bellpopup' ) ) {
				$split_string = explode( 'bellpopup', $existing_string->extensionversions );
				$version      = substr( $split_string[1], 0, 5 );

				// If version number does not match the current version number found in bellpopupplugin.php, call the Compat class and run upgrade functions.
				if ( BELLPOPUP_VERSION_NUM !== $version ) {
					require_once BELLPLUGINTOPLEVEL_CLASS_COMPAT_DIR . 'class-toplevel-compat-functions.php';
					$compat_class = new BellPopUp_Compat_Functions();
				}
			}
		}

		/**
		 * Adding the admin js file
		 */
		public function bellpopup_admin_js() {

			wp_register_script( 'bellpopup_adminjs', BELLPOPUP_JS_URL . 'bellpopup_admin.min.js', array( 'jquery' ), BELLPOPUP_VERSION_NUM, true );

			global $wpdb;

			$final_array_of_php_values = array();

			// Adding some other individual values we may need.
			$final_array_of_php_values['BELLPOPUP_ROOT_IMG_ICONS_URL']   = BELLPOPUP_ROOT_IMG_ICONS_URL;
			$final_array_of_php_values['BELLPOPUP_ROOT_IMG_URL']   = BELLPOPUP_ROOT_IMG_URL;
			$final_array_of_php_values['FOR_TAB_HIGHLIGHT']    = admin_url() . 'admin.php';
			$final_array_of_php_values['SAVED_ATTACHEMENT_ID'] = get_option( 'media_selector_attachment_id', 0 );
			$final_array_of_php_values['SETTINGS_PAGE_URL'] = menu_page_url( 'WPBookList-Options-settings', false );
			$final_array_of_php_values['DB_PREFIX'] = $wpdb->prefix;


			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wpbooklist_php_variables' object (like wpbooklist_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'bellpopup_adminjs', 'bellPopupPhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'bellpopup_adminjs' );

		}

		/**
		 * Adding the frontend js file
		 */
		public function bellpopup_frontend_js() {

			wp_register_script( 'bellpopup_frontendjs', BELLPOPUP_JS_URL . 'bellpopup_frontend.min.js', array( 'jquery' ), BELLPOPUP_VERSION_NUM, true );
			wp_enqueue_script( 'bellpopup_frontendjs' );

		}

		/**
		 * Adding the admin css file
		 */
		public function bellpopup_admin_style() {

			wp_register_style( 'bellpopup_adminui', BELLPOPUP_CSS_URL . 'bellpopup-main-admin.css', null, BELLPOPUP_VERSION_NUM );
			wp_enqueue_style( 'bellpopup_adminui' );

		}

		/**
		 * Adding the frontend css file
		 */
		public function bellpopup_frontend_style() {

			wp_register_style( 'bellpopup_frontendui', BELLPOPUP_CSS_URL . 'bellpopup-main-frontend.css', null, BELLPOPUP_VERSION_NUM );
			wp_enqueue_style( 'bellpopup_frontendui' );

		}

		/**
		 *  Function to add table names to the global $wpdb.
		 */
		public function bellpopup_register_table_name() {
			global $wpdb;
			$wpdb->bellpopup_settings = "{$wpdb->prefix}bellpopup_settings";
			$wpdb->bellpopup_popups = "{$wpdb->prefix}bellpopup_popups";
		}

		/**
		 *  Function that calls the Style and Scripts needed for displaying of admin pointer messages.
		 */
		public function bellpopup_admin_pointers_javascript() {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
		}

		/**
		 *  Runs once upon plugin activation and creates the table that holds info on BellPopup Pages & Posts.
		 */
		public function bellpopup_create_tables() {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			global $charset_collate;

			// Call this manually as we may have missed the init hook.
			$this->bellpopup_register_table_name();

			$sql_create_table1 = "CREATE TABLE {$wpdb->bellpopup_settings}
			(
				ID bigint(190) auto_increment,
				repw varchar(255),
				PRIMARY KEY  (ID),
				KEY repw (repw)
			) $charset_collate; ";

			// If table doesn't exist, create table and add initial data to it.
			$test_name = $wpdb->prefix . 'bellpopup_settings';
			if ( $test_name !== $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
				dbDelta( $sql_create_table1 );
				$table_name = $wpdb->prefix . 'bellpopup_settings';
				$wpdb->insert( $table_name, array( 'ID' => 1, ) );
			}

			$sql_create_table2 = "CREATE TABLE {$wpdb->bellpopup_popups}
			(
				ID bigint(190) auto_increment,
				popupname varchar(255),
				popupdescription MEDIUMTEXT,
				popuptextblurb MEDIUMTEXT,
				popuplink varchar(255),
				popuptriggeramount varchar(255),
				popupvideo varchar(255),
				popuptrafficsource varchar(255),
				popuptriggerwhere varchar(255),
				popuptriggerwhen varchar(255),
				PRIMARY KEY  (ID),
				KEY popupname (popupname)
			) $charset_collate; ";

			// If table doesn't exist, create table and add initial data to it.
			$test_name = $wpdb->prefix . 'bellpopup_popups';
			if ( $test_name !== $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
				dbDelta( $sql_create_table2 );
				$table_name = $wpdb->prefix . 'bellpopup_popups';
				$wpdb->insert( $table_name, array( 'ID' => 1, ) );
			}
		}

		/**
		 *  The shortcode for displaying the login form / register forms / dashboard.
		 */
		public function bellpopup_login_shortcode_function() {

			ob_start();
			include_once BELLPOPUP_CLASS_DIR . 'class-bellpopup-dashboard-ui.php';
			$front_end_ui = new BellPopUp_Dashboard_UI();
			return ob_get_clean();

		}

	}
endif;
