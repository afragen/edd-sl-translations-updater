<?php
/**
 * EDD Translations Updater
 *
 * @package   Fragen\EDD_Translations_Updater
 * @author    Andy Fragen
 * @license   MIT
 * @link      https://github.com/afragen/edd-translations-updater
 */

namespace Fragen\EDD_Translations_Updater;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class API
 *
 * @package Fragen\EDD_Translations_Updater
 */
trait API {

	/**
	 * Variable for setting update transient hours.
	 *
	 * @var integer
	 */
	protected static $hours = 12;

	/**
	 * Variable to hold all repository remote info.
	 *
	 * @var array
	 */
	protected $response = array();

	/**
	 * Return repo data for API calls.
	 *
	 * @return array
	 */
	protected function return_repo_type() {
		$type        = explode( '_', $this->type->type );
		$arr         = array();
		$arr['type'] = $type[1];

		switch ( $type[0] ) {
			case 'github':
				$arr['repo']          = 'github';
				$arr['base_uri']      = 'https://api.github.com';
				$arr['base_download'] = 'https://github.com';
				break;
			case 'bitbucket':
				$arr['repo']          = 'bitbucket';
				$arr['base_uri']      = 'https://bitbucket.org/api';
				$arr['base_download'] = 'https://bitbucket.org';
				break;
			case 'gitlab':
				$arr['repo']          = 'gitlab';
				$arr['base_uri']      = 'https://gitlab.com/api/v4';
				$arr['base_download'] = 'https://gitlab.com';
				break;
		}

		return $arr;
	}

	/**
	 * Call the API and return a json decoded body.
	 *
	 * @param string $url
	 *
	 * @return boolean|\stdClass
	 */
	protected function api( $url ) {
		$response = wp_remote_get( $this->get_api_url( $url ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Return API url.
	 *
	 * @access protected
	 *
	 * @param string $endpoint
	 *
	 * @return string $endpoint
	 */
	protected function get_api_url( $endpoint ) {
		$type = $this->return_repo_type();

		switch ( $type['repo'] ) {
			case 'github':
			case 'bitbucket':
				break;
			case 'gitlab':
				$endpoint = add_query_arg( 'ref', 'master', $endpoint );
				break;
			default:
		}

		return $type['base_uri'] . $endpoint;
	}

	/**
	 * Validate wp_remote_get response.
	 *
	 * @param $response
	 *
	 * @return bool true if invalid
	 */
	protected function validate_response( $response ) {
		return empty( $response ) || isset( $response->message );
	}

	/**
	 * Returns repo cached data.
	 *
	 * @param string|bool $repo Repo name or false.
	 *
	 * @return array|bool false for expired cache
	 */
	protected function get_repo_cache( $repo = false ) {
		if ( ! $repo ) {
			$repo = isset( $this->type->slug ) ? $this->type->slug : 'edd-tu';
		}
		$cache_key = 'edd-tu-' . md5( $repo );
		$cache     = get_site_option( $cache_key );

		if ( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
			return false;
		}

		return $cache;
	}

	/**
	 * Sets repo data for cache in site option.
	 *
	 * @param string      $id       Data Identifier.
	 * @param mixed       $response Data to be stored.
	 * @param string|bool $repo     Repo name or false.
	 *
	 * @return bool
	 */
	protected function set_repo_cache( $id, $response, $repo = false ) {
		if ( ! $repo ) {
			$repo = isset( $this->type->slug ) ? $this->type->slug : 'edd-tu';
		}
		$cache_key = 'edd-tu-' . md5( $repo );
		$timeout   = '+' . self::$hours . ' hours';

		$this->response['timeout'] = strtotime( $timeout, current_time( 'timestamp' ) );
		$this->response[ $id ]     = $response;

		update_site_option( $cache_key, $this->response );

		return true;
	}

}
