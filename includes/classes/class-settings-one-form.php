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

			// For grabbing an image from media library.
			wp_enqueue_media();

			$wordpress_pages = get_pages();
			//asort( $wordpress_pages );
			$page_html       = '';
			foreach ( $wordpress_pages as $key => $page ) {
				$page_html = $page_html . '<option value="' . $page->ID . '">' . $page->post_title . '</option>';
			}


			$contact_form_html = '
				<div class="bellpopupplugin-form-section-wrapper">
					<div class="bellpopupplugin-form-section-fields-wrapper">
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Name</label>
							<input class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-text" id="bellpopupplugin-form-popupname" data-dbname="popupname" type="text" value="" />
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Description</label>
							<textarea class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-text" id="bellpopupplugin-form-popupdescription" data-dbname="popupdescription" type="text" value=""></textarea>
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Text Blurb</label>
							<input class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-text" id="bellpopupplugin-form-popuptextblurb" data-dbname="popuptextblurb" type="text" value="" />
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Link</label>
							<input class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-text" id="bellpopupplugin-form-popuplink" data-dbname="popuplink" type="text" value="" />
						</div>
					</div>
					<div class="bellpopupplugin-form-section-fields-wrapper">
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Video</label>
							<input class="bellpopup-form-section-fields-input bellpopup-form-section-fields-input-text" id="bellpopup-input-popupvideo" type="text" value="">
								<button class="bellpopup-form-section-fields-input bellpopup-form-section-fields-input-button bellpopup-form-section-fields-input-file-upload-button" id="bellpopupplugin-form-popupvideo" data-dbtype="%s" data-dbname="popupvideo">Choose File</button>
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Traffic Source</label>
							<input class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-text" id="bellpopupplugin-form-popuptrafficsource" data-dbname="popuptrafficsource" type="text" value="" />
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Appears...</label>
							<select class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-select" id="bellpopupplugin-form-popuptriggerwhen" data-dbname="popuptriggerwhen">
								<option selected default disabled>Make A Selection...</option>
								<option value="scroll">When User Scrolls 50%</option>
								<option value="scroll">30 Seconds After Page Load</option>
							</select>
						</div>
						<div class="bellpopupplugin-form-section-fields-indiv-wrapper">
							<label class="bellpopupplugin-form-section-fields-label">Popup Appears On...</label>
							<select multiple="multiple" class="bellpopupplugin-form-section-fields-input bellpopupplugin-form-section-fields-input-select" id="bellpopupplugin-form-popuptriggerwhere" data-dbname="popuptriggerwhere">
								' . $page_html . '
							</select>
						</div>
					</div>
					<div>
						<button id="bellpopupplugin-save-popup-button">Save Popup</button>
					</div>
				</div>';

			$string1 = '
				<div id="bellpopupplugin-display-options-container">
					<p class="bellpopupplugin-tab-intro-para">Here you can create new Pop-Ups to display to your visitors</p>
					<div class="bellpopupplugin-form-wrapper">
						' . $contact_form_html . '

					


					</div>
				</div>';

			echo $string1;
		}
	}
endif;
