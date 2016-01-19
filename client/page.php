<?php

add_action('delete_post', 'bk_preventSiteDelete');
add_action('save_post', 'bk_preventSiteModification');
add_action('trashed_post', 'bk_preventSiteTrash');

function add_page_on_install() {
	$page_title = 'Buchungskalender';
	$page_slug = 'buchungskalender';

	$page = get_page_by_path($page_slug);

	delete_option('bk_PageTitle');
	add_option('bk_PageTitle', $page_title, '', 'no');

	delete_option('bk_PageSlug');
	add_option('bk_PageSlug', $page_slug, '', 'no');

	if (!$page) {
		bk_recreatePage();
	} else {
		bk_untrashPage();
	}
}

function delete_page_on_remove() {
	$page_id = get_option('bk_page_id');

	delete_option('bk_PageTitle');
	delete_option('bk_PageSlug');
	delete_option('bk_page_id');

	if ($page_id) {
		wp_delete_post($page_id);
	}
}

function bk_preventSiteDelete($postId) {
	if ($postId == get_option('bk_page_id')) {
		bk_recreatePage();
	}
}

function bk_preventSiteTrash($postId) {
	if ($postId == get_option('bk_page_id')) {
		bk_untrashPage();
	}
}

function bk_preventSiteModification($postId) {
	if ($postId == get_option('bk_page_id')) {
		bk_untrashPage();
	}
}

function bk_recreatePage() {
	$page_title = get_option('bk_PageTitle');
	$page_slug = get_option('bk_PageSlug');

	if ($page_slug) {
		$_p = array();
		$_p['post_name'] = $page_slug;
		$_p['post_title'] = $page_title;
		$_p['post_content'] = '[Buchungskalender]';
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['page_template'] = 'page-templates/front-page-rev-slider-content.php';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1);

		bk_save_post_id(wp_insert_post($_p));
	}
}

function bk_untrashPage() {
	$page_slug = get_option('bk_PageSlug');

	if ($page_slug) {
		$page = get_page_by_path($page_slug);

		$page->post_status = 'publish';
		$page->post_content = '[Buchungskalender]';
		$page->page_template = 'page-templates/front-page-rev-slider-content.php';

		remove_action('save_post', 'bk_preventSiteModification');
		bk_save_post_id(wp_update_post($page));
		add_action('save_post', 'bk_preventSiteModification');
	}
}


function bk_save_post_id($id) {
	delete_option('bk_page_id');
	add_option('bk_page_id', $id, '', 'no');
}