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
 * Class Base
 *
 * Update WordPress language packs from a git hosted repo.
 *
 * @package Fragen\EDD_Translations_Updater
 * @author  Andy Fragen
 */
trait Base {

	/**
	 * Store details of all EDD SL repositories that are installed.
	 *
	 * @var \stdClass $config
	 */
	protected $config;

	/**
	 * Get remote repo meta data for language-pack.json file.
	 * Initiates remote APIs for data.
	 *
	 * @param array $repo EDD SL plugin/theme data.
	 *
	 * @return bool
	 */
	public function get_remote_repo_data( $repo ) {
		if ( 'theme' === $repo['type'] ) {
			$new_repo[ $repo['theme_slug'] ] = $repo;
			$new_repo['type']                = $repo['type'];
			$repo                            = $new_repo;
		}

		$slug = $this->get_repo_slug( $repo );
		$type = $repo['type'];
		unset( $repo['type'] );
		$repo       = (object) array_pop( $repo );
		$repo->slug = $slug;
		$repo->type = $type;

		if ( ! isset( $repo->languages ) ) {
			return false;
		}

		$this->config[ $slug ] = $repo;
		$language_pack         = new Language_Pack( $repo, new Language_Pack_API( $repo ) );
		$language_pack->run();

		return true;
	}

	/**
	 * Get slug from $repo.
	 * Sometimes there may be more than one repository used with EDD Software Licensing.
	 *
	 * @param array $repo EDD SL config data.
	 *
	 * @return string $slug
	 */
	private function get_repo_slug( $repo ) {
		$repos = $this->config;
		$keys  = array_keys( $repo );
		array_pop( $keys ); // remove type.
		$slug = array_filter(
			$keys, function( $e ) use ( $repos ) {
				if ( ! isset( $repos[ $e ] ) ) {
					return $e;
				}
			}
		);

		return array_pop( $slug );
	}

	/**
	 * Parse URI param returning array of parts.
	 *
	 * @param string $repo_header
	 *
	 * @return array $header
	 */
	protected function parse_header_uri( $repo_header ) {
		$header_parts         = parse_url( $repo_header );
		$header_path          = pathinfo( $header_parts['path'] );
		$header['scheme']     = isset( $header_parts['scheme'] ) ? $header_parts['scheme'] : null;
		$header['host']       = isset( $header_parts['host'] ) ? $header_parts['host'] : null;
		$header['type']       = explode( '.', $header['host'] )[0] . '_' . $this->repo->type;
		$header['owner']      = trim( $header_path['dirname'], '/' );
		$header['repo']       = $header_path['filename'];
		$header['owner_repo'] = implode( '/', array( $header['owner'], $header['repo'] ) );
		$header['base_uri']   = str_replace( $header_parts['path'], '', $repo_header );
		$header['uri']        = isset( $header['scheme'] ) ? trim( $repo_header, '/' ) : null;

		$header = $this->sanitize( $header );

		return $header;
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @param array $input Contains all settings fields as array keys.
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();
		foreach ( array_keys( (array) $input ) as $id ) {
			$new_input[ sanitize_file_name( $id ) ] = sanitize_text_field( $input[ $id ] );
		}

		return $new_input;
	}

}
