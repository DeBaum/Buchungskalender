<?php

add_action('admin_menu', 'createPluginAdminMenu');

function createPluginAdminMenu() {
	$menuSlug = 'buchungskalender';
	$menuTitle = 'Buchungskalender';

	add_menu_page($menuTitle, $menuTitle, 'manage_options', $menuSlug, 'adminMenu_index', 'dashicons-calendar-alt');
	add_submenu_page($menuSlug, $menuTitle, 'Einstellungen', 'manage_options', $menuSlug, 'adminMenu_index');
	add_submenu_page($menuSlug, 'Kategorien - ' . $menuTitle, 'Kategorien', 'manage_options', $menuSlug . '-categories', 'adminMenu_categories');
	add_submenu_page($menuSlug, 'Objekte - ' . $menuTitle, 'Objekte', 'manage_options', $menuSlug . '-objects', 'adminMenu_objects');
	add_submenu_page($menuSlug, 'Extras - ' . $menuTitle, 'Extras', 'manage_options', $menuSlug . '-extras', 'adminMenu_extras');
}

function adminMenu_index() {
	include 'menu-index.php';
}

function adminMenu_categories() {
	include 'categories.php';
}

function adminMenu_objects() {
	include 'objects.php';
}

function adminMenu_extras() {
	include 'extras.php';
}