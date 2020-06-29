<?php

global $wpdb;

$visitor_limit = get_option("makerspace_visitor_limit");
$error = "";
$reservations = array();

$url_data = (object) array(
    'tab' => isset($_GET["tab"]) ? $_GET["tab"] : "reserved",
    'page' => $_GET["page"],
    'offset' => isset($_GET["offset"]) ? $_GET["offset"] : 0,

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


if ($url_data->tab != "all") {

    $sql_reservations = "
        SELECT mar_user_id, MIN(mar_from) as mar_from, MAX(mar_to) as mar_to 
        FROM `makerspace_advance_registrations` 
        WHERE mar_from > %d AND mar_to < %d AND mar_deleted = 0 GROUP BY mar_user_id
    ";

    $reservations = $wpdb->get_results($wpdb->prepare(
        $sql_reservations,
        $day->start->getTimestamp(),
        $day->end->getTimestamp()
    ));

    $day->count = count($reservations);

    for ($hour = 15; $hour < 22; $hour++) {
        $hour_count = 0;
        $hour_timestamp_begin = (clone $day->date->setTime($hour, 0, 0))->getTimestamp();
        $hour_timestamp_end = (clone $day->date->setTime($hour, 59, 59))->getTimestamp();

        foreach ($reservations as $r) {
            if ($r->mar_from <= $hour_timestamp_begin && $r->mar_to >= $hour_timestamp_end) {
                $hour_count++;
            }
        }

        $sql_rvp = "SELECT * FROM makerspace_advance_registrations WHERE mar_from = %d AND mar_user_id = %d";
        $rvp = $wpdb->get_row($wpdb->prepare($sql_rvp, $hour_timestamp_begin, get_current_user_id()));

        $h = (object) array(
            "hour" => $hour,
            "count" => $hour_count,
            "start" => $hour_timestamp_begin,
            "end" => $hour_timestamp_end,
            "color" => $hour_count < $visitor_limit ? "rgb(161, 198, 57)" :  "#e40033",
            "reserved" => $rvp == null || $rvp->mar_deleted > 0 ? false : true
        );

        array_push($day->hours, $h);
    }

    usort($reservations, function ($a, $b) {
        if (isset($a->start) && isset($b->start)) {
            return $a->start - $b->start;
        } else {
            return 0;
        }
    });
} else {
    $users = get_users();

    foreach ($users as $user) {
        array_push( $reservations, (object) array(
            "mar_user_id" => $user->ID,
            "mar_from" => 0,
            "mar_to" => 0
        ));
    }
}

require dirname(__FILE__) . "/reservation-pos.partial.php";
