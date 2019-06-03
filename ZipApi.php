<?php

/**
 * Class ZipApi
 */
class ZipApi {
	/**
	 * @var string zip.lv API key
	 */
	private $apiKey;

	/**
	 * zip.lv API URL
	 */
	const API_URL = 'https://zip.lv/api/v1/import';

	/**
	 * ZipApi constructor.
	 * @param string $apiKey zip.lv API key
	 */
	public function __construct( $apiKey ) {
		$this->apiKey = (string)$apiKey;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function addAd( array $data ) {
		return $this->apiCall( 'POST', $data );
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function updateAd( array $data ) {
		return $this->apiCall( 'PUT', $data );
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function deleteAd( array $data ) {
		return $this->apiCall( 'DELETE', $data );
	}

	/**
	 * @param string $method
	 * @param array $data
	 * @return array
	 */
	public function apiCall( $method, array $data ) {
		if ( function_exists('curl_init') ) {
			$response = $this->_apiCallCurl( $method, $data );
		} else {
			$response = $this->_apiCallFsockopen( $method, $data );
		}

		if( $response === false ){
			return ['code' => '0', 'result' => 'No response from API server'];
		} else if( empty( $response ) ){
			return ['code' => '0', 'result' => 'Empty API response'];
		}
		return json_decode( $response, true );
	}

	/**
	 * @param string $method
	 * @param array $data
	 * @return mixed
	 */
	private function _apiCallCurl( $method, array $data ) {
		$ch = curl_init( self::API_URL );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Authorization: Basic '. base64_encode( $this->apiKey )] );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		return curl_exec( $ch );
	}

	/**
	 * @param string $method
	 * @param array $data
	 * @return mixed
	 */
	private function _apiCallFsockopen( $method, $data ) {
		$parts = parse_url( self::API_URL );
		$target = $parts['host'];
		$port = 80;
		$page  = isset($parts['path'])        ? $parts['path']            : '';
		$page .= isset($parts['query'])       ? '?' . $parts['query']     : '';
		$page .= isset($parts['fragment'])    ? '#' . $parts['fragment']  : '';
		$page = ($page == '')                 ? '/'                       : $page;
		if ( $fp = fsockopen( $target, $port, $errno, $errstr, 30 ) ) {
			$content = json_encode( $data );
			$headers  = $method . " $page HTTP/1.1\r\n";
			$headers .= "Host: {$parts['host']}\r\n";
			$headers .= "Authorization: Basic ". base64_encode( $this->apiKey ) ."\r\n";
			$headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$headers .= "Content-Length: ". mb_strlen( $content ) ."\r\n";
			$headers .= "Connection: Close\r\n\r\n";
			$headers .= $content;
			if ( fwrite( $fp, $headers ) ){
				$response = '';
				while ( !feof( $fp ) ) {
					$response .= fgets( $fp, 1024 );
				}
				$start = mb_strpos( $response, '{' );
				$end = mb_strrpos( $response, '}' ) + 1;
				if ( !$start || !$end ) {
					return false;
				}
				return substr( $response, $start, ( $end - $start) );
			}
			fclose($fp);
		}

		return false;
	}
}