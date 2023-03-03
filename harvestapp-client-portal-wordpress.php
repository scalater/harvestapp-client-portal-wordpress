<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Plugin Name: Harvest Client Portal
 * Plugin URI: https://scalater.com/
 * Description: WP plugin to connect with https://www.getharvest.com/ to create a client portal to let your client see their project time consumed
 * Version: 1.0.0
 * Requires at least: 4.6
 * Tested up to: 6.1.1
 * Requires PHP: 7.4
 * Stable tag: 1.0.0
 * Author: Scalater Team
 * Author URI: https://scalater.com/
 * License: GPLv2 or later
 * Network: false
 * Text Domain: harvestapp-client-portal-wordpress
 * Domain Path: /languages
 *
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';

init_freemius(
	[
		'id'             => '12140',
		'slug'           => 'harvestapp-client-portal-wordpress',
		'type'           => 'plugin',
		'public_key'     => 'pk_3d3af048e7de14c5106ebd1ff9e39',
		'is_premium'     => false,
		'has_addons'     => false,
		'has_paid_plans' => false,
		'menu'           => [
			'slug'    => 'harvestapp-client-portal-wordpress',
			'support' => false,
		],
	]
);

if ( ! init_plugin( __NAMESPACE__, __FILE__, 'harvestapp-client-portal-wordpress' ) ) {
	return;
}

add_action( 'scalater/admin', [ Admin::class, 'instance' ] );
add_action( 'scalater/init', [ ShortCode::class, 'instance' ] );

