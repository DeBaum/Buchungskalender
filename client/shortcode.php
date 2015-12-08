<?php

add_shortcode('Buchungskalender', 'bk_render');

function bk_render() {
	include 'buchungskalender.php';
}