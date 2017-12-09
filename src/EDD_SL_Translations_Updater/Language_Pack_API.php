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

/**
 * Class Language_Pack_API
 *
 * @package Fragen\EDD_SL_Translations_Updater
 */
class Language_Pack_API {
	use API;

	/**
	 * Constructor.
	 *
	 * @param \stdClass $repo EDD SL repo object.
	 */
	public function __construct( $repo ) {
		$this->type     = $repo;
		$this->response = $this->get_repo_cache( $repo->slug );
	}

	/**
	 * Get/process Language Packs.
	 *
	 * @param array $headers Array of headers of Language Pack.
	 *
	 * @return bool When invalid response.
	 */
	public function get_language_pack( $headers ) {
		$response = ! empty( $this->response['languages'] ) ? $this->response['languages'] : false;
		$type     = explode( '_', $headers['type'] );

		if ( ! $response ) {
			$response = $this->get_language_pack_json( $type, $headers );

			if ( $response ) {
				foreach ( $response as $locale ) {
					$package = $this->process_language_pack_package( $type[0], $locale, $headers );

					$response->{$locale->language}->package = $package;
					$response->{$locale->language}->type    = $type[1];
					$response->{$locale->language}->version = $this->type->version;
				}

				$this->set_repo_cache( 'languages', $response, $this->type->slug );
			} else {
				return false;
			}
		}

		$this->type->language_packs = $response;
	}

	/**
	 * Get language-pack.json from appropriate host.
	 *
	 * @param string $type ( github|bitbucket|gitlab )
	 * @param array  $headers
	 *
	 * @return array|bool|mixed|object $response API response object.
	 */
	private function get_language_pack_json( $type, $headers ) {
		switch ( $type[0] ) {
			case 'github':
				$response = $this->api( '/repos/' . $headers['owner'] . '/' . $headers['repo'] . '/contents/language-pack.json' );
				$response = isset( $response->content )
					? json_decode( base64_decode( $response->content ) )
					: null;
				break;
			case 'bitbucket':
				$response = $this->api( '/1.0/repositories/' . $headers['owner'] . '/' . $headers['repo'] . '/src/master/language-pack.json' );
				$response = isset( $response->data )
					? json_decode( $response->data )
					: null;
				break;
			case 'gitlab':
				$id       = urlencode( $headers['owner'] . '/' . $headers['repo'] );
				$response = $this->api( '/projects/' . $id . '/repository/files?file_path=language-pack.json' );
				$response = isset( $response->content )
					? json_decode( base64_decode( $response->content ) )
					: null;
				break;
		}

		if ( $this->validate_response( $response ) ) {
			return false;
		}

		return $response;
	}

	/**
	 * Process $package for update transient.
	 *
	 * @param string $type ( github|bitbucket|gitlab )
	 * @param string $locale
	 * @param array  $headers
	 *
	 * @return array|null|string
	 */
	private function process_language_pack_package( $type, $locale, $headers ) {
		$package = null;
		switch ( $type ) {
			case 'github':
				$package = array( 'https://github.com', $headers['owner'], $headers['repo'], 'blob/master' );
				$package = implode( '/', $package ) . $locale->package;
				$package = add_query_arg( array( 'raw' => 'true' ), $package );
				break;
			case 'bitbucket':
				$package = array( 'https://bitbucket.org', $headers['owner'], $headers['repo'], 'raw/master' );
				$package = implode( '/', $package ) . $locale->package;
				break;
			case 'gitlab':
				$package = array( 'https://gitlab.com', $headers['owner'], $headers['repo'], 'raw/master' );
				$package = implode( '/', $package ) . $locale->package;
				break;
		}

		return $package;
	}

}
