<?php

add_action('admin_menu', 'createPluginAdminMenu');

function createPluginAdminMenu() {
	add_options_page('Buchungskalender Einstellungen', 'Buchungskalender', 'manage_options', 'buchungskalender_MainSettings', 'adminMenu_MainSettings');
}

function adminMenu_MainSettings() {
	include 'mainSettings.php';
}