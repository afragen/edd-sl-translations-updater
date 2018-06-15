<?php
/**
 * EDD Translations Updater
 *
 * @package   Fragen\EDD_Translations_Updater
 * @author    Andy Fragen
 * @license   MIT
 * @link      https://github.com/afragen/edd-translations-updater
 */

/**
 * Plugin Name:       EDD Translations Updater
 * Plugin URI:        https://github.com/afragen/edd-translations-updater
 * Description:       An EDD Software Licensing extension to automatically update language packs.
 * Version:           1.1.0.2
 * Author:            Andy Fragen
 * License:           MIT
 * License URI:       http://www.opensource.org/licenses/MIT
 * Domain Path:       /languages
 * Text Domain:       edd-translations-updater
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/edd-translations-updater
 * Requires WP:       4.6
 * Requires PHP:      5.4
 */

/*
 * Exit if called directly.
 * PHP version check and exit.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( version_compare( '5.4.0', PHP_VERSION, '>=' ) ) {
	echo '<div class="error notice is-dismissible"><p>';
	printf(
		/* translators: 1: minimum PHP version required, 2: Upgrade PHP URL */
		wp_kses_post( __( 'EDD Translations Updater cannot run on PHP versions older than %1$s. <a href="%2$s">Learn about upgrading your PHP.</a>', 'edd-translations-updater' ) ),
		'5.4.0',
		esc_url( __( 'https://wordpress.org/support/upgrade-php/' ) )
	);
	echo '</p></div>';

	return false;
}

// Load textdomain.
load_plugin_textdomain( 'edd-translations-updater' );

// Plugin namespace root.
$edd_translations_updater['root'] = array( 'Fragen\\EDD_Translations_Updater' => __DIR__ . '/src/EDD_Translations_Updater' );

// Add extra classes.
$edd_translations_updater['extra_classes'] = array();

// Load Autoloader.
require_once __DIR__ . '/src/Autoloader.php';
$edd_translations_updater['loader'] = 'Fragen\\Autoloader';
new $edd_translations_updater['loader']( $edd_translations_updater['root'], $edd_translations_updater['extra_classes'] );

// Instantiate class Fragen\EDD_Translations_Updater.
$edd_translations_updater['instantiate'] = 'Fragen\\EDD_Translations_Updater\\Init';
$edd_translations_updater['init']        = new $edd_translations_updater['instantiate']();
$edd_translations_updater['init']->run();
