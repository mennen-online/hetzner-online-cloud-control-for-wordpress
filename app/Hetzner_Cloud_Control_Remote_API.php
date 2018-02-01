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

    private static function setupCurl($url, $request_type = 'POST', $post = false, $postfields = []){
        self::$curl = curl_init();
        $header = [];
        $header[] = 'Authorization: Bearer '.get_option(baekerIT_Hetzner_Cloud_Control::API_TOKEN);
        $header[] = 'Accept: */*';
        $header[] = 'Cache-Control: no-cache';
        $header[] = 'Content-Type: application/json';
        curl_setopt( self::$curl, CURLOPT_URL, self::$url . $url );
        curl_setopt( self::$curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, strtoupper($request_type));
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
        if($post){
            curl_setopt( self::$curl, CURLOPT_POST, true );
            curl_setopt( self::$curl, CURLOPT_POSTFIELDS, json_encode($postfields) );
        }
        $response = curl_exec(self::$curl);
        return json_decode($response);
    }

	public static function getAllServers() {
		return self::setupCurl('/servers', 'GET');
	}

	public static function getAllServerTypes() {
	    return self::setupCurl('/server_types', 'GET');
	}

	public static function getAllImages() {
	    return self::setupCurl('/images', 'GET');
	}

	public static function createNewServer( $name, $type, $image ) {
	    return self::setupCurl('/servers','POST', true, [
	        'name' => $name,
            'server_type' => $type,
            'image' => $image
        ]);
	}

	public static function deleteServer($id){
        return self::setupCurl('/servers/'.$id, 'DELETE');
    }

    public static function changeServerName($id, $name){
        return self::setupCurl('/servers/'.$id, 'PUT', true, ['name' => $name]);
    }


}