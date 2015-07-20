<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
	/*
		Tabs
		Sections
		Fields
	*/

	$mmm_curl_options = array(
		array('name' => 'Curl Manager Settings',
			'id' => 'mmm_curl_manager_settings',
			'type' => 0,
			'icon' => 'cog',
			'sections' => array(
				array(
					'name' => 'Settings',
					'size' => '12',
					'fields' => array(
						array('id' => 'webhook_url',
							'label' => 'Webhook Url',
							'type' => 'text'),
						array('id' => 'webhook_username',
							'label' => 'Username',
							'type' => 'text'),
						array('id' => 'webhook_password',
							'label' => 'Password',
							'type' => 'text'),
						array('id' => 'hunt_product',
							'label' => 'Hunt Product ID',
							'type' => 'text')
					)
				)
			)
		)
	);
?>