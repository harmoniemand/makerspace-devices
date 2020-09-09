<?php

require_once dirname(__FILE__) . "/../../entities/presence_log.entity.php";

global $wpdb;

$visitor_limit = get_option("makerspace_visitor_limit");
$error = "";

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

$viewmodel = (object) array(
    "all" => array(),
    "reserved" => array(),
    "present" => array(),
    "visitors" => array(),
    "table_data" => array(),
    "present_total_count" => 0
);



$orderby = sanitize_sql_orderby(empty($_GET["orderby"]) ? $url_data->orderby : $_GET["orderby"]);

// collection data for all users

$sql_reserved = "
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

$sql_reserved = $wpdb->prepare(
    $sql_reserved,
    $day->start->getTimestamp(),
    $day->end->getTimestamp()
);

$viewmodel->reserved = $wpdb->get_results($sql_reserved);




// collection data vor reservations

$sql_all = "
    SELECT
        wp_users.ID as mar_user_id,
        '" . $day->start->format("Y-m-d H:i:s") . "' as mar_from, 
        '" . $day->end->format("Y-m-d H:i:s") . "' as mar_to,
        t_first_name.meta_value as first_name,
        t_last_name.meta_value as last_name,
        wp_users.user_login as user_name
    FROM wp_users
    
    LEFT JOIN wp_usermeta as t_first_name ON t_first_name.user_id = wp_users.ID AND t_first_name.meta_key = 'first_name'
    LEFT JOIN wp_usermeta as t_last_name ON t_last_name.user_id = wp_users.ID AND t_last_name.meta_key = 'last_name'

    ORDER BY $orderby
    ";

$viewmodel->all = $wpdb->get_results($sql_all);


// collect data for temp_visitors

$sql_temp_visitors = "
SELECT 
    tmp.mpl_temp_visitor_id as mpl_temp_visitor_id, 
    makerspace_presence_logs.mpl_temp_visitor_name as mpl_temp_visitor_name, 
    makerspace_presence_logs.mpl_temp_visitor_address as temp_visitor_address,
    '" . $day->start->format("Y-m-d H:i:s") . "' as mar_from, 
    '" . $day->end->format("Y-m-d H:i:s") . "' as mar_to
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
$viewmodel->visitors = $wpdb->get_results($sql_temp_visitors);


$sql_present = "
SELECT 
    tmp.mpl_user_id as mar_user_id ,
    tmp.arrived_at as mar_from, 
    '" . $day->end->format("Y-m-d H:i:s") . "' as mar_to,
    t_first_name.meta_value as first_name,
    t_last_name.meta_value as last_name,
    wp_users.user_login as user_name
FROM (
    SELECT 
        mpl_user_id,
        MOD(COUNT(mpl_user_id), 2) as log_count,
        MIN(mpl_datetime) as arrived_at,
        MAX(mpl_datetime) as leaved_at
    FROM `makerspace_presence_logs`
    WHERE 
        mpl_datetime between %s AND %s AND mpl_temp_visitor_id IS NULL
    GROUP BY mpl_user_id
    ) as tmp

LEFT JOIN wp_users ON wp_users.ID = tmp.mpl_user_id
LEFT JOIN wp_usermeta as t_first_name ON t_first_name.user_id = tmp.mpl_user_id AND t_first_name.meta_key = 'first_name'
LEFT JOIN wp_usermeta as t_last_name ON t_last_name.user_id = tmp.mpl_user_id AND t_last_name.meta_key = 'last_name'
WHERE log_count > 0
";

$sql_present = $wpdb->prepare(
    $sql_present,
    $day->start->format("Y-m-d H:i:s"),
    $day->end->format("Y-m-d H:i:s")
);

$viewmodel->present = $wpdb->get_results($sql_present);




$viewmodel->present_total_count = PresenceLogEntity::get_visitors_between($day->start, $day->end);


switch ($url_data->tab) {
    case "reserved":
        $viewmodel->table_data = $viewmodel->reserved;
        break;
    case "all":
        $viewmodel->table_data = $viewmodel->all;
        break;
    default:
        $viewmodel->table_data = $viewmodel->present;
}


if ($_GET["page"] == "reservations-timeline") {
    require dirname(__FILE__) . "/reservation-timeline.partial.php";
} else {
    require dirname(__FILE__) . "/reservation-pos-wp.partial.php";
}
