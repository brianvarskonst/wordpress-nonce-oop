<?php // phpcs:disable

if (defined('ABSPATH')) {
    return;
}

define('MINUTE_IN_SECONDS', 60);
define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);

define('ABSPATH', dirname(__DIR__) . '/vendor/wordpress/wordpress/');
define('WPINC', 'wp-includes');

defined('WP_CONTENT_DIR') or define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
defined('WP_PLUGIN_DIR') or define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

require_once ABSPATH . WPINC . '/pluggable.php';
require_once ABSPATH . WPINC . '/kses.php';
require_once ABSPATH . WPINC . '/functions.php';