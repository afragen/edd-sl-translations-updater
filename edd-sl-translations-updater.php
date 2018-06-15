<?php
/**
 * EDD Software Licensing Translations Updater
 *
 * @package   Fragen\EDD_SL_Translations_Updater
 * @author    Andy Fragen
 * @license   MIT
 * @link      https://github.com/afragen/edd-sl-translations-updater
 */

/**
 * Plugin Name:       EDD Software Licensing Translations Updater
 * Plugin URI:        https://github.com/afragen/edd-sl-translations-updater
 * Description:       An EDD Software Licensing extension to automatically update language packs.
 * Version:           1.1.0.1
 * Author:            Andy Fragen
 * License:           MIT
 * License URI:       http://www.opensource.org/licenses/MIT
 * Domain Path:       /languages
 * Text Domain:       edd-sl-translations-updater
 * Network:           true
 * GitHub Plugin URI: https://github.com/afragen/edd-sl-translations-updater
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
		wp_kses_post( __( 'EDD Software Licensing Translations Updater cannot run on PHP versions older than %1$s. <a href="%2$s">Learn about upgrading your PHP.</a>', 'edd-sl-translations-updater' ) ),
		'5.4.0',
		esc_url( __( 'https://wordpress.org/support/upgrade-php/' ) )
	);
	echo '</p></div>';

	return false;
}

// Load textdomain.
load_plugin_textdomain( 'edd-sl-translations-updater' );

// Plugin namespace root.
$eddsl_translations_updater['root'] = array( 'Fragen\\EDD_SL_Translations_Updater' => __DIR__ . '/src/EDD_SL_Translations_Updater' );

// Add extra classes.
$eddsl_translations_updater['extra_classes'] = array();

// Load Autoloader.
require_once __DIR__ . '/src/Autoloader.php';
$eddsl_translations_updater['loader'] = 'Fragen\\Autoloader';
new $eddsl_translations_updater['loader']( $eddsl_translations_updater['root'], $eddsl_translations_updater['extra_classes'] );

// Instantiate class Fragen\EDD_SL_Translations_Updater.
$eddsl_translations_updater['instantiate'] = 'Fragen\\EDD_SL_Translations_Updater\\Init';
$eddsl_translations_updater['init']        = new $eddsl_translations_updater['instantiate'];
$eddsl_translations_updater['init']->run();
