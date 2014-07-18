# Purpose

This theme shows an example of how to connect Styles Font Menu to Options Framework, then use the custom font settings in your theme's CSS.

# Important Files

`functions.php`

* Requires `options.php` so option IDs are available for enqueing stylesheets automatically.
* Function `example_font_family_css()` shows how to output CSS for your custom font family.

`inc/class-sfm-options/framework-support.php`

* Provides magical connections between Options Framework and Styles Font Menu.
* Enqueues stylesheets for any Google Fonts in your theme settings.
* Provides a custom action for outputting font-family CSS.
* Nothing in this file should need to be edited.

`options.php`

* Requires inc/class-sfm-options-framework-support.php so that `styles_font_menu` type becomes available.
* Creates an example option, `example_font_1`.

# Other Files

All other files in this theme either came from either [Styles Font Menu](https://github.com/stylesplugin/styles-font-menu) or [Options Framework Theme](https://github.com/devinsays/options-framework-theme).