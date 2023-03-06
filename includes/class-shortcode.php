<?php
/**
 * Shortcode class
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

use Exception;
use SCALATER\HARVESTAPPCLIENTPORTAL\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Class Shortcode
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 */
class Shortcode extends Base {
	use Singleton;

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_shortcode( 'harvestapp_client_portal', [ $this, 'harvestapp_client_portal_callback' ] );
	}

	/**
	 * Get un-invoiced time from cache
	 *
	 * @param  string  $from
	 * @param  string  $to
	 *
	 * @return array
	 */
	public function getUnInvoiceCached( $from, $to ) {
		$cache_key        = sprintf( 'harvestapp_client_portal_wordpress_uninvoiced_time_%s_%s', $from, $to );
		$un_invoiced_time = get_transient( $cache_key );
		if ( $un_invoiced_time === false ) {
			$account_id     = get_option( 'harvestapp_client_portal_wordpress_user_id' );
			$personal_token = get_option( 'harvestapp_client_portal_wordpress_personal_token' );

			$harvest          = new Harvest( $account_id, $personal_token );
			$un_invoiced_time = $harvest->getUnInvoice( $from, $to );
			set_transient( $cache_key, $un_invoiced_time, 60 * 60 * 24 );
		}

		return $un_invoiced_time;
	}

	/**
	 * Shortcode callback
	 *
	 * @param  array  $attr
	 * @param  string  $content
	 *
	 * @return string
	 */
	public function harvestapp_client_portal_callback( $attr, $content = null ) {
		$attr = shortcode_atts( array(
			'from'  => '',
			'to'    => '',
			'frame' => 'current_month',
		), $attr, 'harvestapp_client_portal' );

		$from  = $attr['from'];
		$to    = $attr['to'];
		$frame = $attr['frame'];

		if ( empty( $from ) && empty( $to ) && $frame == 'current_month' ) {
			$from = date( 'Ymd', strtotime( 'first day of this month' ) );
			$to   = date( 'Ymd', strtotime( 'last day of this month' ) );
		}

		$current_period = sprintf( '%s - %s', date( 'F j, Y', strtotime( $from ) ), date( 'F j, Y', strtotime( $to ) ) );

		$harvest_client = get_the_author_meta( 'harvest_client', get_current_user_id() );

		$un_invoiced_time = $this->getUnInvoiceCached( $from, $to );
		$fall_back_empty  = 0;

		$html = sprintf( "<div class='harvestapp-client-portal-container'><p><b>Current Period:</b> %s</p>", $current_period);
		$html .= '<hr>';
		foreach ( $un_invoiced_time as $project ) {
			if ( $project['client_id'] != $harvest_client ) {
				continue;
			}
			$html .= sprintf('<span class="project-name"><p><b>Project Name:</b> <span>%s</span></p></span>',$project['project_name']);
			$html .= sprintf('<span class="currency"><p><b>Currency:</b> <span>%s</span></p></span>',$project['currency']);
			$html .= sprintf('<span class="total-hours"><p><b>Total Hours:</b> <span>%s</span></p></span>',$project['total_hours']);
			$html .= sprintf('<span class="uninvoiced-hours"><p><b>Uninvoiced Hours:</b> <span>%s</span></p></span>',$project['uninvoiced_hours']);
			$html .= sprintf('<span class="uninvoiced-expenses"><p><b>Uninvoiced Expenses:</b> <span>%s</span></p></span>',$project['uninvoiced_expenses']);
			$html .= sprintf('<span class="uninvoiced-amount"><p><b>Uninvoiced Amount:</b> <span>%s</span></p></span>',$project['uninvoiced_amount']);
			$fall_back_empty ++;
			if($fall_back_empty>1){
				$html .= '<hr>';
			}
		}
		$html .= '</div>';

		if ( $fall_back_empty == 0 ) {
			return '<p>There is no time to show.</p>';
		} else {
			return $html;
		}

	}

}
