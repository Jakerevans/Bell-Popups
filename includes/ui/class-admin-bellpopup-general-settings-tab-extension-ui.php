<?php
/**
 * BellPopUp Tab
 *
 * @author   Jake Evans
 * @category Extension Ui
 * @package  Includes/UI
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BellPopUp', false ) ) :
	/**
	 * BellPopup_Admin_Menu Class.
	 */
	class BellPopUp {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			require_once BELLPOPUP_CLASS_DIR . 'class-admin-ui-template.php';
			require_once BELLPOPUP_CLASS_DIR . 'class-toplevel-form.php';

			// Instantiate the class.
			$this->template = new BellPopup_Admin_UI_Template();
			$this->form     = new BellPopup_Toplevel_Form();
			$this->output_open_admin_container();
			$this->output_tab_content();
			$this->output_close_admin_container();
			$this->output_admin_template_advert();
		}

		/**
		 * Opens the admin container for the tab
		 */
		private function output_open_admin_container(){
			$title    = 'Bell Mobile BellPopUp';
			$icon_url = BELLPOPUP_ROOT_IMG_URL . 'toplevel.svg';
			echo $this->template->output_open_admin_container($title, $icon_url);
		}

		/**
		 * Outputs actual tab contents
		 */
		private function output_tab_content(){
			echo $this->form->output_bellpopup_form();
		}

		/**
		 * Closes admin container
		 */
		private function output_close_admin_container(){
			echo $this->template->output_close_admin_container();
		}

		/**
		 * Outputs advertisment area
		 */
		private function output_admin_template_advert(){
			echo $this->template->output_template_advert();
		}


	}
endif;

// Instantiate the class
$cm = new BellPopUp;
