<?php


global $wpdb;

$today = get_datetime();
if (isset($_GET["date"])) {
    $today = new DateTime($_GET["date"]);
}

$day = (object) array(
    "date" => $today,
    "start" => (clone $today->setTime(0, 0, 0)),
    "end" => (clone $today->setTime(23, 59, 59)),
    "count" => 0,
    "hours" => array()
);



$sql_logs = "
SELECT * FROM `makerspace_presence_logs`
WHERE mpl_datetime between %s AND %s
";

$logs = $wpdb->get_results($wpdb->prepare(
    $sql_logs,
    $day->start->format("Y-m-d H:i:s"),
    $day->end->format("Y-m-d H:i:s")
));


require dirname(__FILE__) . "/reservation-logs.partial.php";
