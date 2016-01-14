<?php

require_once(__DIR__ . '/../../../wp-config.php');

define("PLUGIN_NAME", "buchungskalender");
define("ROOT_URL", "http://localhost/wordpress/");
define("API_ROOT_URL", ROOT_URL . "/wp-content/plugins/" . PLUGIN_NAME . "/api/");

global $wpdb;

//
//$db = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_PASSWORD, DB_USER, DB_PASSWORD );
//$stmt = $db->prepare( 'select 1');
//$row = $stmt->execute();
//while($row->)