<?php

if (!defined('ABSPATH')) {
    die('-1');
}


if ( ! current_user_can("<")) {
    http_response_code( 403 );
}

global $wpdb;

include_once dirname(__FILE__) . "/../../../Repositories/UserRepository.php";
$user_repo = new UserRepository();

$url_data = (object) array(
    'tab' => isset($_GET["tab"]) ? $_GET["tab"] : "reserved",
    'page' => $_GET["page"],
    'offset' => isset($_GET["offset"]) ? $_GET["offset"] : 0,
    'orderby' => isset($_GET["orderby"]) ? $_GET["orderby"] : "first_name"
);

$viewmodel = (object) array(
    "users" => $user_repo->ReadAll()
);


require dirname(__FILE__) . "/users-list.view.php";
