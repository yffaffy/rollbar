<?php
/**
 * Helper Functions
 *
 * @package     RollbarWP\Functions
 * @since       1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Libs
//if( !class_exists( 'Rollbar_WP' ) && !class_exists( 'RollbarNotifier' ) && !class_exists( 'Ratchetio' ) ) {
    require_once ROLLBAR_WP_DIR . 'includes/lib/rollbar.php';
//}

function rollbar_wp_initialize() {

    // If logging is enabled continue
    if ( rollbar_wp_logging_enabled() == false ) {
        return;
    }

    // Config
    $config = array(
        // required
        'access_token' => '3b9a9afbd5034e91b9519fb15cd021b9',
        // optional - environment name. any string will do.
        'environment' => 'production',
        // optional - path to directory your code is in. used for linking stack traces.
        'root' => ABSPATH
    );

    // installs global error and exception handlers
    Rollbar::init($config);
}
add_action('init', 'rollbar_wp_initialize');

/*
 * Check wether logging is enabled or not
 */
function rollbar_wp_logging_enabled() {
    $options = get_option( 'rollbar_wp_settings' );

    if ( $options['rollbar_wp_logging_enabled'] == '1' ) {
        return true;
    }

    return false;
}

/*
 * Filter
 */
function rollbar_wp_filter_php_errors ( $errfile ) {

    if (strpos($errfile,'rollbar') !== false) {
        return true;
    }

    return false;
}