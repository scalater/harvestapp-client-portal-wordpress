<?php
/**
 * Share functions across the plugin
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

defined( 'ABSPATH' ) || exit;

/**
 * Class Base to share functions across the plugin
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 */
class Base {

	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	final public function get_version(): string {
		return '1.0.0';
	}

	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	final public static function get_slug(): string {
		return constant( __NAMESPACE__ . '\HANDLE' );
	}

	/**
	 * Get plugins base assets url
	 *
	 * @return string
	 */
	final public function get_assets_base_url(): string {
		return constant( __NAMESPACE__ . '\URL' );
	}

	/**
	 * Get plugins base path
	 *
	 * @return string
	 */
	final public static function get_path(): string {
		return __DIR__ . '/';
	}

	/**
	 * Get assets url and base on SCRIPT_DEBUG constant return minified name of the asset
	 *
	 * @param string $name The asset name.
	 * @param string $extension The extension of the asset. Default is `js`.
	 *
	 * @return string
	 */
	public function get_asset_url( string $name, string $extension = 'js' ): string {
		$url    = $this->get_assets_base_url() . 'assets/';
		$url    .= ( 'js' === $extension ) ? 'js/' : 'css/';
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		return $url . $name . $suffix . '.' . $extension;
	}

	/**
	 * Generate a scope error log
	 *
	 * @param string $message
	 */
	public static function error_log( string $message ) {
		if ( ! empty( $message ) ) {
			error_log( self::get_slug() . ' -- ' . $message );
		}
	}

	/**
	 * Get the view folder path
	 *
	 * @return string
	 */
	final public static function get_views_path(): string {
		return self::get_path() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the template folder path
	 *
	 * @return string
	 */
	final public static function get_templates_path(): string {
		return self::get_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Allowed HTML for wp_kses functions.
	 *
	 * @return array
	 */
	final public static function allowed_html() {
		return [
			'br'     => [],
			'em'     => [],
			'strong' => [],
			'a'      => [
				'href'     => true,
				'rel'      => true,
				'name'     => true,
				'target'   => true,
				'download' => [
					'valueless' => 'y',
				],
			],
			'p'      => [],
		];
	}
}
