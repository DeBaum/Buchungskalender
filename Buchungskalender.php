<?php

/*
Plugin Name: Buchungskalender
Plugin URI: http://github.com/DeBaum/Buchungskalender
Description: INA2 Buchungskalender für die Stadtverwaltung Bocholt
Version: 0.1
Author: Dustin Baum
Author URI: http://github.com/Debaum
License: GPL2
*/

include 'admin/menu.php';
include 'client/page.php';
include 'client/shortcode.php';
include 'api/apiInit.php';

register_activation_hook(__FILE__, 'on_install');
register_deactivation_hook(__FILE__, 'on_remove');

function on_install() {
	add_page_on_install();
	init_api_on_install();
}

function on_remove() {
	delete_page_on_remove();
	dispose_api_on_remove();
}