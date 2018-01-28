<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.01.18
 * Time: 13:47
 */

class Hetzner_Cloud_Control_Remote_API {

	static $curl;
	static protected $url = 'https://api.hetzner.cloud/v1';

	public static function getAllServers() {
		self::$curl = curl_init();
		curl_setopt( self::$curl, CURLOPT_URL, self::$url . '/servers' );
		curl_setopt( self::$curl, CURLOPT_HEADER, [ 'Authorization: Bearer ' . self::TOKEN ] );
		$response = curl_exec( self::$curl );

		return json_decode( $response );
	}

	public static function getAllServerTypes() {
		self::$curl = curl_init();
		curl_setopt( self::$curl, CURLOPT_URL, self::$url . '/server_types' );
		curl_setopt( self::$curl, CURLOPT_HEADER, [ 'Authorization: Bearer ' . self::TOKEN ] );
		$response = curl_exec( self::$curl );

		return json_decode( $response );
	}

	public static function getAllImages() {
		self::$curl = curl_init();
		curl_setopt( self::$curl, CURLOPT_URL, self::$url . '/images' );
		curl_setopt( self::$curl, CURLOPT_HEADER, [ 'Authorization: Bearer ' . self::TOKEN ] );
		$response = curl_exec( self::$curl );

		return json_decode( $response );
	}

	public static function createNewServer( $name, $type, $image ) {
		self::$curl = curl_init();
		curl_setopt( self::$curl, CURLOPT_URL, self::$url . '/servers' );
		curl_setopt( self::$curl, CURLOPT_POST, true );
		curl_setopt( self::$curl, CURLOPT_POSTFIELDS, [
			'name'  => $name,
			'type'  => $type,
			'image' => $image
		] );
		curl_setopt( self::$curl, CURLOPT_HEADER, [ 'Authorization: Bearer ' . self::TOKEN ] );
		$response = curl_exec( self::$curl );

		return json_decode( $response );
	}

}