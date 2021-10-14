<?php

global $wpdb;
global $atts;

$closed_dates = array(
    (object) array(
        "start" => new DateTime("2020-09-14"),
        "end" => new DateTime("2020-09-16")
    ),
    (object) array(
        "start" => new DateTime("2020-11-02"),
        "end" => new DateTime("2020-12-20")
    ),
    (object) array(
        "start" => new DateTime("2020-12-24"),
        "end" => new DateTime("2020-12-26")
    ),
    (object) array(
        "start" => new DateTime("2020-12-31"),
        "end" => new DateTime("2021-04-06")
    ),
    (object) array(
        "start" => new DateTime("2021-09-14"),
        "end" => new DateTime("2021-09-15")
    ),
);

$visitor_limit = get_option("makerspace_visitor_limit");

$weekdays = array();
$today = new DateTime();
$offset = 0;

if (isset($_GET["offset"])) {
    $offset = $_GET["offset"];
}
$today = $today->modify($offset . " week");

$week_start = clone $today->modify('Monday this week');

for ($i = 0; $i < 5; $i++) {

    $date = (clone $week_start->modify('+1 day'));

    $day = (object) array(
        "date" => $date,
        "day_start" => (clone $date->setTime(0, 0, 0)),
        "day_end" => (clone $date->setTime(23, 59, 59)),
        "count" => 0,
        "hours" => array()
    );
    array_push($weekdays, $day);
}


// $sql_reservations = "SELECT * FROM makerspace_ms_devices_workshop_reservations WHERE mse_device_from > %d AND mse_device_to < %d";
$sql_reservations = "SELECT * FROM makerspace_advance_registrations WHERE mar_from > %d AND mar_to < %d AND mar_deleted < 1";

foreach ($weekdays as $day) {

    $reservations = $wpdb->get_results($wpdb->prepare(
        $sql_reservations,
        $day->day_start->getTimestamp(),
        $day->day_end->getTimestamp()
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
}

?>

<div class="container mb-5">
    <div class="row">

        <a href="?offset=<?php echo ($offset - 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
            <clr-icon shape="angle" size="36" dir="up"></clr-icon>
        </a>

        <a href="?offset=<?php echo ($offset - 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
            <clr-icon shape="angle" size="36" dir="left" class=""></clr-icon>
        </a>

        <?php foreach ($weekdays as $day) : ?>


            <?php
            $closed = false;

            foreach ($closed_dates as $closed_date) {
                if ($day->date->format("Y-m-d") >= $closed_date->start->format("Y-m-d") && $day->date->format("Y-m-d") <= $closed_date->end->format("Y-m-d")) {
                    $closed = true;
                }
            }
            ?>

            <?php if ($closed) : ?>
                <div class="col-11 col-md-6 col-lg border">
                    <h3><?php echo dayToString($day->date->format('w')); ?></h3>
                    <h6><?php echo $day->date->format('d.m.'); ?></h6>
                    <div class="">
                        Maker Space geschlossen
                    </div>
                </div>
            <?php else : ?>

                <div class="col-11 col-md-6 col-lg border">
                    <h3><?php echo dayToString($day->date->format('w')); ?></h3>
                    <h6><?php echo $day->date->format('d.m.'); ?></h6>
                    <div class="">

                        <?php foreach ($day->hours as $h) : ?>
                            <a href="/wp-admin/admin.php?page=reservations" style="text-decoration: none !important; color: black !important; background-color: <?php echo $h->color ?>;" class="d-flex justify-content-between p-1">
                                <span><?php echo $h->hour ?>:00</span>
                                <span><?php echo $visitor_limit - $h->count ?> freie Plätze</span><br />
                            </a>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <a href="?offset=<?php echo ($offset + 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
            <clr-icon shape="angle" size="36" dir="down"></clr-icon>
        </a>

        <a href="?offset=<?php echo ($offset + 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
            <clr-icon shape="angle" size="36" dir="right" class=""></clr-icon>
        </a>
    </div>
</div>


<div class="container mb-5">
    <div class="row">

    </div>
</div>