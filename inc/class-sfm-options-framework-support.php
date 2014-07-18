<?php

require_once __DIR__ . '/styles-font-menu/plugin.php';

SFM_Options_Framework_Support::setup_hooks();

/**
 * Enable 'styles_font_menu' type for Options Framework.
 * Output Google Fonts as enqueued stylesheets in theme.
 */
class SFM_Options_Framework_Support {

	/**
	 * Setup hooks to connect Styles Font Menu to Options Framework
	 */
	static public function setup_hooks() {
		add_filter( 'optionsframework_styles_font_menu', array( __CLASS__, 'add_option_type' ), 10, 3 );
		add_filter( 'of_sanitize_styles_font_menu', array( __CLASS__, 'sanitize_styles_font_menu' ), 10, 2 );
		add_action( 'template_redirect', array( __CLASS__, 'enqueue_fonts' ) );

		// Use this action to output font families in your CSS
		add_action( 'ofsfm_css_font_family', array( __CLASS__, 'css_font_family' ), 10, 2 );
	}

	/**
	 * Add 'styles_font_menu' type to Options Framework
	 */
	static public function add_option_type( $option_name, $field, $default_value ) {
			$attributes = array(
				'name' => $option_name . '[' . $field['id'] . ']',
				'id' => $field['id'],
			);

			// Return output from Styles Font Menu to options framework
			ob_start();
			do_action( 'styles_font_menu', $attributes, $default_value );
			return ob_get_clean();
	}

	/**
	 * Sanitize Font Menu input so it can be saved to the database.
	 */
	static public function sanitize_styles_font_menu( $input, $option ){
		$output = '';

		$font_info = json_decode( $input );

		// Save the data if it JSON and references a valid font family
		if ( is_object( $font_info ) && isset( $font_info->family ) ) {
			$sfm = SFM_Plugin::get_instance();

			$all_families = wp_list_pluck(
				array_merge( $sfm->google_fonts->option_values['fonts'], $sfm->standard_fonts->option_values['fonts'] ),
				'family'
			);

			if ( in_array( $font_info->family, $all_families ) ) {
				$output = $input;
			}
		}

		return $output;
	}

	/**
	 * Add Google Font Stylesheets to the template head.
	 * For this to work, options.php must be included in functions.php:
	 * 	require_once __DIR__ . '/options.php'
	 *
	 * Also, function optionsframework_options must exist in options.php
	 */
	static public function enqueue_fonts(){
		// Get options
		$options = optionsframework_options(); // Must use funciton in options.php

		// Get IDs of styles_font_menu options
		$font_option_ids = wp_list_pluck(
			wp_list_filter( $options, array( 'type' => 'styles_font_menu' ) ),
			'id'
		);

		$enqueued = array();

		// Enqueue each font if it's set and is a Google Font
		foreach ( $font_option_ids as $id ) {
			$font = json_decode( of_get_option( $id ) );

			if ( is_object( $font ) && isset( $font->import_family ) && ! in_array( $font->classname, $enqueued ) ) {
				wp_enqueue_style( 'sfm-' . $font->classname, '//fonts.googleapis.com/css?family=' . $font->import_family );
				$enqueued[] = $font->classname;
			}
		}
	}

	static public function css_font_family( $option_id ) {
		$font = json_decode( of_get_option( $option_id ) );

		if ( is_object( $font ) && isset( $font->family ) ) {
			echo 'font-family: ' . $font->family;
		}else {
			echo '/* No font selected */';
		}
	}
}