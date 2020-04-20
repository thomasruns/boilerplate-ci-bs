<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['site_name'] = 'CI Boilerplate';
$config['bootstrap_theme'] = 'yeti';
$config['default_timezone'] = 'America/Los_Angeles';

switch(ENVIRONMENT) {
    case 'production':
        // production settings go here
        break;

    case 'development':
	// development settings go here
	break;

    default:

}


$config['sparkpost_api_key'] = '';

$config['ip_whitelist'] = [
    '[::1]', // mamp/localhost
    '192.168.1.1' // Your IP address here
];
