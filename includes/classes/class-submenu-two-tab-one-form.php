<?php
/**
 * BellPopup Book Display Options Form Tab Class - class-bellpopupplugin-book-display-options-form.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes/Classes
 * @version  6.1.5.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BellPopup_Settings1_Form', false ) ) :

	/**
	 * BellPopup_Admin_Menu Class.
	 */
	class BellPopup_Settings1_Form {


		/**
		 * Class Constructor - Simply calls the Translations
		 */
		public function __construct() {

			

		}

		/**
		 * Outputs all HTML elements on the page.
		 */
		public function output_settings1_form() {
			global $wpdb;

			// Set the current WordPress user.
			$currentwpuser = wp_get_current_user();

			$string1 = '<div id="bellpopupplugin-display-options-container">
							<p class="bellpopupplugin-tab-intro-para">This is some intro text for The first tab of the 2nd Submenu Page</p>
						</div>';


			echo $string1;
		}
	}
endif;
