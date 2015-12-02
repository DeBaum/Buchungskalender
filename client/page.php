<?php

register_activation_hook(WP_PLUGIN_DIR . '/Buchungskalender/Buchungskalender.php', 'buchungskalender_install');
register_deactivation_hook(WP_PLUGIN_DIR . '/Buchungskalender/Buchungskalender.php', 'buchungskalender_remove');

add_action('delete_post', 'buchungskalender_preventSiteDelete');
add_action('trashed_post', 'buchungskalender_preventSiteTrash');

function buchungskalender_install() {
	$page_title = 'Buchungskalender';
	$page_slug = 'buchungskalender';

	$page = get_page_by_path($page_slug);

	delete_option('buchungskalender_PageTitle');
	add_option('buchungskalender_PageTitle', $page_title, '', 'no');

	delete_option('buchungskalender_PageSlug');
	add_option('buchungskalender_PageSlug', $page_slug, '', 'no');

	if (!$page) {
		buchungskalender_recreatePage();
	} else {
		buchungskalender_untrashPage();
	}
}

function buchungskalender_remove() {
	$page_id = get_option('buchungskalender_page_id');

	delete_option('buchungskalender_PageTitle');
	delete_option('buchungskalender_PageSlug');
	delete_option('buchungskalender_page_id');

	if ($page_id) {
		wp_delete_post($page_id);
	}
}

function buchungskalender_preventSiteDelete($postId) {
	if ($postId == get_option('buchungskalender_page_id')) {
		buchungskalender_recreatePage();
	}
}

function buchungskalender_preventSiteTrash($postId) {
	if ($postId == get_option('buchungskalender_page_id')) {
		buchungskalender_untrashPage();
	}
}

function buchungskalender_recreatePage() {
	$page_title = get_option('buchungskalender_PageTitle');
	$page_slug = get_option('buchungskalender_PageSlug');

	if ($page_slug) {
		$_p = array();
		$_p['post_name'] = $page_slug;
		$_p['post_title'] = $page_title;
		$_p['post_content'] = '';
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1);

		save_buchungskalender_page_id(wp_insert_post($_p));
	}
}

function buchungskalender_untrashPage() {
	$page_slug = get_option('buchungskalender_PageSlug');

	if ($page_slug) {

		$page = get_page_by_path($page_slug);

		$page->post_status = 'publish';
		save_buchungskalender_page_id(wp_update_post($page));
	}
}

function save_buchungskalender_page_id($id) {
	delete_option('buchungskalender_page_id');
	add_option('buchungskalender_page_id', $id, '', 'no');
}