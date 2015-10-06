<?php
/**
 * Plugin Name:     Rollbar for WordPress
 * Plugin URI:      https://coder.flowdee.de/rollbar-for-wordpress/
 * Description:     Rollbar for WordPress
 * Version:         1.0.0
 * Author:          flowdee
 * Author URI:      https://flowdee.de
 * Text Domain:     plugin-name
 *
 * @package         RollbarWP
 * @author          flowdee
 * @copyright       Copyright (c) flowdee
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Rollbar_WP' ) ) {

    /**
     * Main Rollbar_WP class
     *
     * @since       1.0.0
     */
    class Rollbar_WP {

        /**
         * @var         Rollbar_WP $instance The one true Rollbar_WP
         * @since       1.0.0
         */
        private static $instance;

        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true Rollbar_WP
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new Rollbar_WP();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'ROLLBAR_WP_VER', '1.0.0' );

            // Plugin path
            define( 'ROLLBAR_WP_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'ROLLBAR_WP_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Include scripts
            require_once ROLLBAR_WP_DIR . 'includes/class.settings.php';
            require_once ROLLBAR_WP_DIR . 'includes/functions.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         *
         * @todo        The hooks listed in this section are a guideline, and
         *              may or may not be relevant to your particular extension.
         *              Please remove any unnecessary lines, and refer to the
         *              WordPress codex and EDD documentation for additional
         *              information on the included hooks.
         *
         *              This method should be used to add any filters or actions
         *              that are necessary to the core of your extension only.
         *              Hooks that are relevant to meta boxes, widgets and
         *              the like can be placed in their respective files.
         *
         *              IMPORTANT! If you are releasing your extension as a
         *              commercial extension in the EDD store, DO NOT remove
         *              the license check!
         */
        private function hooks() {
            add_action('init', 'rollbar_wp_initialize_php_logging');
            add_action('wp_head', 'rollbar_wp_initialize_js_logging');
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = ROLLBAR_WP_DIR . '/languages/';
            $lang_dir = apply_filters( 'rollbar_wp_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'rollbar-wp' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'rollbar-wp', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/rollbar-wp/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/rollbar-wp/ folder
                load_textdomain( 'rollbar-wp', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/rollbar-wp/languages/ folder
                load_textdomain( 'rollbar-wp', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'rollbar-wp', false, $lang_dir );
            }
        }
    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true Rollbar_WP
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \Rollbar_WP The one true Rollbar_WP
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but EDD is not
 *              present.
 */
function Rollbar_WP_load() {
    return Rollbar_WP::instance();
}
add_action( 'plugins_loaded', 'Rollbar_WP_load' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function rollbar_wp_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'rollbar_wp_activation' );
