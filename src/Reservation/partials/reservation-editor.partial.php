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
    (object) array(
        "start" => new DateTime("2021-12-24"),
        "end" => new DateTime("2021-12-26")
    ),
    (object) array(
        "start" => new DateTime("2021-12-31"),
        "end" => new DateTime("2022-01-1")
    ),
);


$visitor_limit = get_option("makerspace_visitor_limit");
$error = "";

print_r($atts);

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

if (isset($_POST["makerspace_advance_refistration_nonce"])) {
    foreach ($weekdays as $day) {
        foreach ($day->hours as $hour) {
            $now = (new DateTime())->getTimestamp();

            if ($hour->start == $_POST["slot"]) {

                if ($hour->start < $now) {
                    $error = __("Das ausgewählte Element liegt in der Vergangenheit.");
                } else {
                    $hour->reserved = !$hour->reserved;

                    $sql_rvp = "SELECT * FROM makerspace_advance_registrations WHERE mar_from = %d AND mar_user_id = %d";
                    $rvp = $wpdb->get_row($wpdb->prepare($sql_rvp, $hour->start, get_current_user_id()));

                    if ($rvp != null) {
                        $sql_update_rvp = "UPDATE makerspace_advance_registrations set mar_deleted = %d WHERE mar_from = %d AND mar_user_id = %d";
                        $wpdb->get_results($wpdb->prepare($sql_update_rvp, !$hour->reserved, $hour->start, get_current_user_id()));
                    } else {
                        $sql_create_rvp = "INSERT INTO makerspace_advance_registrations (mar_deleted, mar_from, mar_to, mar_user_id) VALUES (%d, %d, %d, %d)";
                        $wpdb->get_results($wpdb->prepare($sql_create_rvp, !$hour->reserved, $hour->start, $hour->end, get_current_user_id()));
                    }
                }
            }
        }
    }
}


?>

<?php if ($error != "") : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-danger" role="alert" style="padding: 8px 12px; width: 100%;">
                <?php echo $error ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="?page=reservations&offset=<?php echo $offset ?>">

    <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;">Meine Anmeldungen</h1>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col-12 col-md-9 order-1 order-md-0">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="row">

                        <a href="?page=reservations&offset=<?php echo ($offset - 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="up"></clr-icon>
                        </a>

                        <a href="?page=reservations&offset=<?php echo ($offset - 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
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

                                <div class="col-12 col-md-6 col-lg mt-5 mt-md-0">
                                    <h3><?php echo dayToString($day->date->format('w')); ?></h3>
                                    <h6><?php echo $day->date->format('d.m.'); ?></h6>
                                    <div class="">

                                        <?php foreach ($day->hours as $h) : ?>

                                            <?php
                                            $button_style = "btn-outline-success";
                                            $button_disabled = "";

                                            if ($h->reserved) {
                                                $button_style = "btn-success";
                                            } else if ($visitor_limit - $h->count < 1) {
                                                $button_style = "btn-outline-danger";
                                                $button_disabled = "disabled";
                                            }

                                            if ($h->start < (new DateTime())->getTimestamp()) {
                                                $button_disabled = "disabled";
                                            }
                                            ?>

                                            <button type="submit" name="slot" id="slot" value="<?php echo $h->start ?>" class="mt-1 w-100 btn <?php echo $button_style ?>" <?php echo $button_disabled ?>>
                                                <span class="mr-3"><?php echo $h->hour ?>:00</span>

                                                <?php if ($h->reserved) : ?>
                                                    <span>du bist angemeldet</span><br />
                                                <?php else : ?>
                                                    <span><?php echo $visitor_limit - $h->count ?> freie Plätze</span><br />
                                                <?php endif; ?>
                                            </button>

                                        <?php endforeach; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <a href="?page=reservations&offset=<?php echo ($offset + 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="down"></clr-icon>
                        </a>

                        <a href="?page=reservations&offset=<?php echo ($offset + 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="right" class=""></clr-icon>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 order-0 order-md-1">
            <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px; font-weight: bold;">Info</li>
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">
                        Hier kannst du deine Anmeldungen sehen. Die Anmeldungen erfolgen stundenweise. Bitte überlege dir, wie viel Zeit du am Stück im Maker Space verbringen willst und buche entsprechend.
                    </li>
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">
                        Da wir aktuell nur <?php echo $visitor_limit ?> Besuchende im Maker Space willkommen heißen dürfen, sei so fair und gib Zeiten wieder frei wenn du nicht kommen kannst.
                    </li>
                    <!-- <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>

</form>