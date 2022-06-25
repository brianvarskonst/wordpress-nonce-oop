<?php // phpcs:disable

declare(strict_types=1);

(static function (string $root): void {
    define('OBJECT', 'OBJECT');
    define('object', 'OBJECT');
    define('OBJECT_K', 'OBJECT_K');
    define('ARRAY_A', 'ARRAY_A');
    define('ARRAY_N', 'ARRAY_N');

    define('MINUTE_IN_SECONDS', 60);
    define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
    define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
    define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
    define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
    define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);

    if (!defined('WP_DEBUG')) {
        define('WP_DEBUG', true);
    }

    $vendor = "$root/vendor";

    if (!realpath($vendor)) {
        die('Please install via Composer before running tests.');
    }

    putenv('TESTS_DIR=' . __DIR__);
    putenv("LIB_DIR=$root");
    putenv("VENDOR_DIR=$root/vendor");
    putenv('APPLICATION_DIR=' . dirname(__DIR__, 2));

    /** @noinspection PhpIncludeInspection */
    require_once "$vendor/autoload.php";

    defined('ABSPATH') or define('ABSPATH', "$root/vendor/wordpress/wordpress/");

    unset($vendor);
})(dirname(__DIR__));