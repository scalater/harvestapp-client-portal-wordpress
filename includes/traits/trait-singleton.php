<?php

namespace SCALATER\HARVESTAPPCLIENTPORTAL\Traits;

use Exception;

defined( 'ABSPATH' ) || exit;

trait Singleton {

	/**
	 * Clone method
	 *
	 * @since  1.0.0
	 * @return void
	 */
	protected function __clone() {}

	/**
	 * Wakeup method
	 *
	 * @since 1.0.0
	 * @throws Exception When used.
	 */
	protected function __wakeup() {
		throw new Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Gets the instance
	 *
	 * @since  1.0.0
	 * @return self
	 */
	final public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * The constructor
	 */
	final protected function __construct() {
		$this->init();
	}

	/**
	 * Initialize
	 */
	abstract public function init();
}
