<?php
/*
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';

require_once dirname( __FILE__ ) . '/options.php';

/**
 * Example CSS output for Options Framework SFM fonts
 */
add_action( 'wp_head', 'example_font_family_css', 999 );
function example_font_family_css() {
	?>
	<!-- Font Families set in Theme Options -->
	<style>
		h1, p {
			<?php
				// Output either font-family CSS for this option ID,
				// or a CSS comment noting that none was set.
				do_action( 'ofsfm_css_font_family', 'example_font_1' );
			?>
		}
	</style>
	<?php
}