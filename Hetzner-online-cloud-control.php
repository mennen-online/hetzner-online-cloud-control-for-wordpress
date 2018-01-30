<?php

/*
Plugin Name: Hetzner Online Cloud Control
Plugin URI: https://baeker-it.de
Description: Take the full Control of your Hetzner Cloud!
Version: 1.0
Author: Hendrik Bäker
Author URI: http://baeker-it.de
License: GPL2
*/

require_once plugin_dir_path( __FILE__ ) . 'app/Hetzner_Cloud_Control_Remote_API.php';

class baekerIT_Hetzner_Cloud_Control extends Hetzner_Cloud_Control_Remote_API {

	const API_TOKEN = 'baekerit_hetzner_cloud_api_token';

	public function __construct() {
		if ( get_option( self::API_TOKEN ) != null ) {
			add_action( 'admin_menu', [ $this, 'admin_menus' ] );
			add_action( 'wp_ajax_createNewServer' . [ $this, 'hcc_createNewServer' ] );
		} else {
			add_action( 'admin_menu', [ $this, 'setup_menu' ] );
		}
		add_action( 'wp_ajax_hcc_saveToken', [ $this, 'hcc_saveApiToken' ] );
		if ( is_admin() ) {
			wp_enqueue_style( 'bootstrap', plugin_dir_path( __FILE__ ) . 'assets/css/bootstrap.min.css' );
			wp_enqueue_script( 'bootstrap_js', plugin_dir_path( __FILE__ ) . 'assets/js/bootstrap.min.js' );
		}
	}

	public function admin_menus() {
		add_menu_page( 'Hetzner Cloud Control Overview', 'Hetzner Cloud Control', 'manage_options', 'bit_hcc', [
			$this,
			'hcc_overview'
		] );
		add_submenu_page( 'bit_hcc', 'Server Overview', 'Servers', 'manage_options', 'bit_hcc_servers', [
			$this,
			'hcc_serverView'
		] );
	}

	public function setup_menu() {
		add_menu_page( 'Hetzner Cloud Control Installation', 'Hetzner Cloud Control', 'manage_options', 'bit_hcc_setup', [
			$this,
			'init_setup'
		] );
	}

	public function hcc_serverView() {
		include plugin_dir_path( __FILE__ ) . 'resources/views/servers/getAllServers.phtml';
	}

	public function hcc_createNewServer() {
		$name = filter_input( INPUT_POST, 'name' );
		$server_type = filter_input( INPUT_POST, 'type' );
		$image = filter_input( INPUT_POST, 'image' );

		return Hetzner_Cloud_Control_Remote_API::createNewServer( $name, $server_type, $image );
	}

	public function init_setup() {
		include plugin_dir_path( __FILE__ ) . 'resources/views/configuration.phtml';
	}

	public function hcc_saveApiToken() {
		$token = filter_input( INPUT_POST, 'hcc_token' );
		update_option( self::API_TOKEN, $token );

		return [
			'status' => 'success',
			'value'  => $token
		];
	}
}

add_action( 'init', 'loadHcc' );

function loadHcc() {
	return new baekerIT_Hetzner_Cloud_Control();
}