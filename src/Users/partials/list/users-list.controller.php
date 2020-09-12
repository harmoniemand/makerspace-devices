<?php

if (!defined('ABSPATH')) {
    die('-1');
}


if ( ! current_user_can("<")) {
    http_response_code( 403 );
}

global $wpdb;


$url_data = (object) array(
    'tab' => isset($_GET["tab"]) ? $_GET["tab"] : "reserved",
    'page' => $_GET["page"],
    'offset' => isset($_GET["offset"]) ? $_GET["offset"] : 0,
    'orderby' => isset($_GET["orderby"]) ? $_GET["orderby"] : "first_name"
);


$viewmodel = (object) array(
    "users" => array()
);


$sql_all = "
    SELECT
        wp_users.ID as mar_user_id,
        t_first_name.meta_value as first_name,
        t_last_name.meta_value as last_name,
        wp_users.user_login as user_name
    FROM wp_users
    
    LEFT JOIN wp_usermeta as t_first_name ON t_first_name.user_id = wp_users.ID AND t_first_name.meta_key = 'first_name'
    LEFT JOIN wp_usermeta as t_last_name ON t_last_name.user_id = wp_users.ID AND t_last_name.meta_key = 'last_name'

    ORDER BY $url_data->orderby
    ";

$viewmodel->users = $wpdb->get_results($sql_all);





require dirname(__FILE__) . "/users-list.view.php";
