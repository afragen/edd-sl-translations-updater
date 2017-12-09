<?php
/**
 * EDD Software Licensing Translations Updater
 *
 * @package   Fragen\EDD_SL_Translations_Updater
 * @author    Andy Fragen
 * @license   MIT
 * @link      https://github.com/afragen/edd-sl-translations-updater
 */

namespace Fragen\EDD_SL_Translations_Updater;


class Init {
	use Base;

	/**
	 * Let's get going.
	 */
	public function run() {
		$this->load_hooks();
	}

	/**
	 * Load relevant action/filter hooks.
	 * Use 'init' hook for user capabilities.
	 */
	protected function load_hooks() {
		add_action( 'post_edd_sl_plugin_updater_setup', array( &$this, 'get_edd_plugin_data' ), 10, 1 );
		add_action( 'init', array( &$this, 'init' ), 10 );
	}

	/**
	 * Test and set for proper user capabilities.
	 *
	 * @return bool
	 */
	public function init() {
		global $pagenow;

		$load_multisite   = ( is_network_admin() && current_user_can( 'manage_network' ) );
		$load_single_site = ( ! is_multisite() && current_user_can( 'manage_options' ) );
		$user_can_update  = $load_multisite || $load_single_site;

		$admin_pages = array(
			'plugins.php',
			'themes.php',
			'update-core.php',
			'update.php',
		);

		if ( $user_can_update && in_array( $pagenow, array_unique( $admin_pages ), true ) ) {
			$this->can_update = true;
		}

		return true;
	}

	public function get_edd_plugin_data( $edd_plugin_data ) {
		if ( $this->can_update ) {
			$edd_plugin_data['type'] = 'plugin';
			$this->get_remote_repo_data( $edd_plugin_data );
		}
	}

}
