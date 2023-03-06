<?php
/**
 * Harvest class
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

defined( 'ABSPATH' ) || exit;

/**
 * Class Harvest
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 */
class Harvest extends Base {
	private $account_id;
	private $personal_token;
	private $client;
	public $api = 'https://api.harvestapp.com/api';

	/**
	 * Harvest constructor.
	 */
	public function __construct( $account_id, $personal_token, $client = null ) {
		$this->account_id     = $account_id;
		$this->personal_token = $personal_token;
		$this->client         = $client;

		if ( empty( $this->client ) ) {
			$this->guzzle_client = new Client();
		} else {
			$this->guzzle_client = $this->client;
		}
	}

	/**
	 * @return array
	 */
	public function headers(): array {
		return array(
			'Harvest-Account-ID' => $this->account_id,
			'Authorization'      => 'Bearer ' . $this->personal_token
		);
	}

	public function get( $endpoint, $parameters ) {
		try {
			$response = $this->guzzle_client->request( 'GET', $this->api . $endpoint, array(
					'query'   => $parameters,
					'headers' => $this->headers()
				)
			);

			return $this->result( $response, true );

		} catch ( ClientException $e ) {
			return (array) json_decode( $e->getResponse()->getBody()->getContents() );
		} catch ( GuzzleException $e ) {
			error_log( 'SCALATER\HARVESTAPPCLIENTPORTAL::' . $e->getMessage() );

			return false;
		}
	}

	/**
	 * @param  Response  $response
	 * @param  bool  $get_body
	 *
	 * @return array|false|Response
	 */
	public function result( Response $response, $get_body = false ) {
		if ( ! empty( $this->client ) ) {
			return $response;
		} else {
			if ( $get_body ) {
				$result['content'] = json_decode( $response->getBody()->getContents(), true );;
			} else {
				$result = json_decode( (string) $response->getBody(), true );
			}

			$result['code'] = $response->getStatusCode();

			return $result;
		}
	}

	public function getClients() {
		$clients = $this->get( '/v2/clients', array(
			'is_active' => 'true'
		) );

		if ( ! empty( $clients ) && ! empty( $clients['content'] ) && ! empty( $clients['content']['clients'] ) ) {
			return $clients['content']['clients'];
		}

		return array();
	}

	public function getUnInvoice( $from, $to ) {
		$un_invoiced_time = $this->get( '/v2/reports/uninvoiced', array(
			'from'       => $from,
			'to'         => $to,
		) );

		if ( ! empty( $un_invoiced_time ) && ! empty( $un_invoiced_time['content'] ) && ! empty( $un_invoiced_time['content']['results'] ) ) {
			return $un_invoiced_time['content']['results'];
		}

		return array();
	}
}