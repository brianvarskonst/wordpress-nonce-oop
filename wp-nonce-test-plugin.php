<?php

/**
 * Plugin Name: Wordpress Nonce Test Plugin
 * Plugin URI: https://github.com/brianvarskonst/wordpress-nonce-oop
 * Description: Wordpress Nonce usage in a demo plugin
 * Version: 1.0.
 * Author: Brian Schäffner
 * Author URI: https://github.com/brianvarskonst
 * Requires at least: 5.2
 * Tested at: 5.2.2
 *
 * Text Domain: wp-nonce-test
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Create Error Message Function
 *
 * @param string $message       The Message to handle
 * @param string $template      The Template format to handle
 */
function createErrorMessage(string $message, string $template = '%s: ') {
    if (PHP_SAPI !== 'cli') {
        $template = '<h2>%s</h2>';
        header('Content-type: text/html; charset=utf-8', true, 503);
    }

    echo sprintf($template, 'Fehler');
    echo $message;

    exit(1);
}

// Check the minimum required php version
if (PHP_VERSION_ID < 70200) {
    createErrorMessage('Auf Ihrem Server läuft PHP version ' . PHP_VERSION . ', Wordpress Nonce Test Plugin benötigt mindestens PHP 7.2.0');
}

// Check if composer install was executed
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    createErrorMessage('Bitte führen Sie zuerst "composer install" aus um alle von Wordpress Nonce Test Plugin benötigten Abhängigkeiten zu installieren.');
}

require_once __DIR__ . '/vendor/autoload.php';

// Create a new NonceTest instance and test the NonceManager
$nonceTest = new NonceTest();
$nonceTest->build();