<?php

declare(strict_types=1);

/**
 * Plugin Name: WordPress Nonce Test Plugin
 * Plugin URI: https://github.com/brianvarskonst/wordpress-nonce-oop
 * Description: WordPress Nonce usage in a demo plugin
 * Version: 1.0.
 * Author: Brian Schäffner
 * Author URI: https://github.com/brianvarskonst
 * Requires at least: 6.0
 * Tested at: 6.0
 *
 * Text Domain: wp-nonce-test
 */

if (!defined('ABSPATH')) {
    die();
}

function createErrorMessage(string $message, string $template = '%s: ') {
    if (PHP_SAPI !== 'cli') {
        $template = '<h2>%s</h2>';
        header('Content-type: text/html; charset=utf-8', true, 503);
    }

    echo sprintf($template, 'Fehler');
    echo $message;

    exit(1);
}

$autoloader = __DIR__ . '/vendor/autoload.php';

// Check if composer install was executed
if (!file_exists($autoloader) || !is_readable($autoloader)) {
    createErrorMessage('Please run "composer install" before you are testing the Nonce Manager.');
}

require_once $autoloader;

// Create a new NonceTest instance and test the NonceManager
$nonceTest = new NonceTest();
$nonceTest->build();