<?php

require_once dirname(__FILE__) . "/../../entities/presence_log.entity.php";

global $wpdb;

$visitor_limit = get_option("makerspace_visitor_limit");
$error = "";
$reservations = array();

$url_data = (object) array(
    'tab' => isset($_GET["tab"]) ? $_GET["tab"] : "reserved",
    'page' => $_GET["page"],
    'offset' => isset($_GET["offset"]) ? $_GET["offset"] : 0,
    'orderby' => "first_name"
);

$today = get_datetime();
$today = $today->modify($url_data->offset . " day");

$day = (object) array(
    "date" => $today,
    "start" => (clone $today->setTime(0, 0, 0)),
    "end" => (clone $today->setTime(23, 59, 59)),
    "count" => 0,
    "hours" => array()
);

// create arrive / leave log for temp visitors
if (isset($_POST["mpl_temp_visitor_id"])) {
    $sql_mp_create_log = "INSERT INTO makerspace_presence_logs (mpl_temp_visitor_id, mpl_datetime) values (%s, %s)";
    $wpdb->get_results($wpdb->prepare(
        $sql_mp_create_log,
        $_POST["mpl_temp_visitor_id"],
        get_datetime()->format("Y-m-d H:i:s")
    ));
}

// create arrive / leave log
if (isset($_POST["mp_create_log"])) {
    $sql_mp_create_log = "INSERT INTO makerspace_presence_logs (mpl_user_id, mpl_datetime) values (%d, %s)";
    $wpdb->get_results($wpdb->prepare(
        $sql_mp_create_log,
        $_POST["mp_create_log"],
        get_datetime()->format("Y-m-d H:i:s")
    ));
}

// toggle security instruction and save
if (isset($_POST["ms_user_corona_safetyinstruction"])) {
    if (get_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction")) {
        delete_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction");
    } else {
        add_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction", get_datetime());
    }
}

// toggle contact and save
if (isset($_POST["ms_user_corona_adress"])) {
    if (get_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress")) {
        delete_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress");
    } else {
        add_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress", get_datetime());
    }
}

$sql_reservations = "";
$orderby = sanitize_sql_orderby( empty($_GET["orderby"]) ? $url_data->orderby : $_GET["orderby"] );

if ($url_data->tab != "all") {

    $sql_reservations = "

    SELECT
        mar_user_id,
        mar_from, 
        mar_to,
        t_first_name.meta_value as first_name,
        t_last_name.meta_value as last_name,
        wp_users.user_login as user_name
    FROM (
        SELECT 
            mar_user_id,
            MIN(mar_from) as mar_from, 
            MAX(mar_to) as mar_to
        FROM `makerspace_advance_registrations`
        WHERE mar_from > %d AND mar_to < %d AND mar_deleted = 0
        GROUP BY mar_user_id
    ) as tmp

    LEFT JOIN wp_users ON wp_users.ID = mar_user_id
    LEFT JOIN wp_usermeta as t_first_name ON t_first_name.user_id = mar_user_id AND t_first_name.meta_key = 'first_name'
    LEFT JOIN wp_usermeta as t_last_name ON t_last_name.user_id = mar_user_id AND t_last_name.meta_key = 'last_name'

    ORDER BY $orderby
    ";

    $sql_reservations = $wpdb->prepare(
        $sql_reservations,
        $day->start->getTimestamp(),
        $day->end->getTimestamp()
    );
} else {
    $sql_reservations = "
    SELECT
        wp_users.ID as mar_user_id,
        0 as mar_from, 
        0 as mar_to,
        t_first_name.meta_value as first_name,
        t_last_name.meta_value as last_name,
        wp_users.user_login as user_name
    FROM wp_users
    
    LEFT JOIN wp_usermeta as t_first_name ON t_first_name.user_id = wp_users.ID AND t_first_name.meta_key = 'first_name'
    LEFT JOIN wp_usermeta as t_last_name ON t_last_name.user_id = wp_users.ID AND t_last_name.meta_key = 'last_name'

    ORDER BY $orderby
    ";

    $sql_reservations = $wpdb->prepare(
        $sql_reservations
    );
}

$reservations = $wpdb->get_results($sql_reservations);



$sql_temp_visitors = "
SELECT 
    tmp.mpl_temp_visitor_id as mpl_temp_visitor_id, 
    makerspace_presence_logs.mpl_temp_visitor_name as mpl_temp_visitor_name, 
    makerspace_presence_logs.mpl_temp_visitor_address as temp_visitor_address
FROM (
    SELECT 
    	mpl_temp_visitor_id, 
    	count(mpl_id) as log_count 
    FROM `makerspace_presence_logs`
	WHERE mpl_temp_visitor_id IS NOT NULL
    GROUP BY mpl_temp_visitor_id
) as tmp
JOIN makerspace_presence_logs ON makerspace_presence_logs.mpl_temp_visitor_id = tmp.mpl_temp_visitor_id
WHERE MOD(tmp.log_count, 2) > 0
";
$temp_visitors = $wpdb->get_results($sql_temp_visitors);


$logged_in_count = PresenceLogEntity::get_visitors_between($day->start, $day->end);


if ($_GET["page"] == "reservations-timeline") {
    require dirname(__FILE__) . "/reservation-timeline.partial.php";
} else {
    require dirname(__FILE__) . "/reservation-pos-wp.partial.php";
}
