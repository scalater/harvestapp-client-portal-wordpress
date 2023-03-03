<?php
/**
 * Version: 1.0.1
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

defined( 'ABSPATH' ) || exit;

function init_freemius( $args ) {
	global $scalater_fs;

	$slug = $args['slug'];
	if ( ! isset( $scalater_fs[ $slug ] ) ) {
		require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
		$scalater_fs[ $slug ] = fs_dynamic_init( $args );
	}

	do_action( 'scalater_fs_loaded', [ $args ] );

	return $scalater_fs[ $slug ];
}

function autoload( $class ) {
	global $sca_autoload_namespaces;

	if ( strpos( $class, 'SCALATER\\' ) !== 0 || empty( $sca_autoload_namespaces ) ) {
		return;
	}

	$load_path = null;
	$autoload  = false;

	$pieces    = explode( '\\', $class );
	$classname = array_pop( $pieces );
	$namespace = implode( '\\', $pieces );

	foreach ( $sca_autoload_namespaces as $key => $load_path ) {
		if ( $namespace === $key || ( strpos( $namespace, $key . '\\' ) === 0 ) ) {
			$autoload = true;
			break;
		}
	}

	if ( ! $autoload || ! $load_path ) {
		return;
	}

	$path = $load_path . '/includes' . strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], substr( $namespace, strlen( $key ) ) ) ) . '/';
	$slug = strtolower( str_replace( '_', '-', $classname ) ) . '.php';

	$prefixes = [ 'class', 'trait', 'abstract' ];

	foreach ( $prefixes as $prefix ) {
		$filename = $path . $prefix . '-' . $slug;

		if ( file_exists( $filename ) ) {
			require_once $filename;

			return;
		}
	}
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );

function add_to_autoload_namespaces( $namespace, $load_path ) {
	global $sca_autoload_namespaces;
	$sca_autoload_namespaces[ $namespace ] = $load_path;

	uksort(
		$sca_autoload_namespaces,
		function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);
}

function init_plugin( $namespace, $filename, $slug ) {

	define( $namespace . '\URL', plugins_url( '/', __DIR__ . DIRECTORY_SEPARATOR . $slug . '.php' ) );
	define( $namespace . '\HANDLE', $slug );

	add_action(
		'plugins_loaded',
		function () use ( $slug ) {
			load_plugin_textdomain( $slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			do_action( 'scalater/plugin_loaded' );
		},
		999
	);

	add_action(
		'init',
		function () {
			do_action( 'scalater/init' );
			if ( is_admin() ) {
				do_action( 'scalater/admin' );
			}
			if ( wp_doing_ajax() ) {
				do_action( 'scalater/ajax' );
			}
			if ( wp_doing_cron() ) {
				do_action( 'scalater/cron' );
			}
			if ( ! is_admin() ) {
				do_action( 'scalater/frontend' );
			}
		},
		999
	);

	add_to_autoload_namespaces( $namespace, dirname( $filename ) );

	return true;
}
