<?php

global $wpdb;

$visitor_limit = get_option("makerspace_visitor_limit");
$error = "";


$today = get_datetime();
$offset = 0;
if (isset($_GET["offset"])) {
    $offset = $_GET["offset"];
}
$today = $today->modify($offset . " day");

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

// toggle security instruction
if (isset($_POST["ms_user_corona_safetyinstruction"])) {
    if (get_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction")) {
        delete_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction");
    } else {
        add_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction", get_datetime());
    }
}


// toggle contact 
if (isset($_POST["ms_user_corona_adress"])) {
    if (get_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress")) {
        delete_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress");
    } else {
        add_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress", get_datetime());
    }
}





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


<form method="POST" action="?page=reservations-pos&offset=<?php echo $offset ?>">

    <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;">POS</h1>
            <span><?php echo get_datetime()->format("Y-m-d H:i:s") ?></span>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col-12 col-md-9 order-1 order-md-0">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">


                    <div class="row">

                        <a href="?page=reservations-pos&offset=<?php echo ($offset - 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="up"></clr-icon>
                        </a>

                        <a href="?page=reservations-pos&offset=<?php echo ($offset - 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="left" class=""></clr-icon>
                        </a>

                        <div class="col d-flex justify-content-center">
                            <h3>
                                <span class=""> <?php echo dayToString($day->date->format('w')); ?></span>
                                <span class="ml-3"> <?php echo $day->date->format('d.m.'); ?></span>
                            </h3>
                        </div>

                        <a href="?page=reservations-pos&offset=<?php echo ($offset + 1) ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="down"></clr-icon>
                        </a>

                        <a href="?page=reservations-pos&offset=<?php echo ($offset + 1) ?>" class="d-none d-md-flex flex-md-column justify-content-center">
                            <clr-icon shape="angle" size="36" dir="right" class=""></clr-icon>
                        </a>
                    </div>

                    <div class="row d-none d-md-flex">

                        <div class="col-2 font-weight-bold">
                            <span class="mr-2">Vorname</span>
                        </div>
                        <div class="col-2 font-weight-bold">
                            <span class="mr-2">Nachname</span>
                        </div>
                        <div class="col-2 font-weight-bold">
                            <span class="mr-2">Username</span>
                        </div>
                        <div class="col-2 font-weight-bold">
                            <span class="mr-2">Von - Bis</span>
                        </div>
                        <div class="col-1 font-weight-bold">
                            <span class="mr-2" title="Sicherheitsunterweisung">SU</span>
                        </div>
                        <div class="col-1 font-weight-bold">
                            <span class="mr-2" style="font-size: 0.7rem;">Anschrift</span>
                        </div>
                        <div class="col-2 font-weight-bold"></div>
                    </div>



                    <?php foreach ($reservations as $r) : ?>
                        <div class="row mb-2 mt-4 mt-md-0 bg-alternating p-2 p-md-0">
                            <?php

                            $r_user = get_userdata($r->mar_user_id);
                            $r_from = new DateTime();
                            $r_from->setTimestamp($r->mar_from);
                            $r_to = new DateTime();
                            $r_to->setTimestamp($r->mar_to);

                            $is_here = false;
                            $mpl_sql = "SELECT * FROM makerspace_presence_logs WHERE mpl_datetime BETWEEN  %s AND %s AND mpl_user_id = %d";
                            $mpl_entries = $wpdb->get_results($wpdb->prepare(
                                $mpl_sql,
                                $day->start->format("Y-m-d H:i:s"),
                                $day->end->format("Y-m-d H:i:s"),
                                $r->mar_user_id
                            ));

                            $disable_create_log = "disabled";
                            if (get_datetime()->format("Y-m-d") == $day->start->format("Y-m-d")) {
                                $disable_create_log = "";
                            }

                            ?>


                            <div class="col-4 col-md-2">
                                <span class="">
                                    <?php echo $r_user->user_firstname ?>
                                </span>
                            </div>
                            <div class="col-4 col-md-2">
                                <span class="">
                                    <?php echo $r_user->user_lastname  ?>
                                </span>
                            </div>
                            <div class="col-4 col-md-2">
                                <span class="">
                                    <?php echo $r_user->user_login  ?>
                                </span>
                            </div>

                            <div class="col-6 col-md-2">
                                <span class=""><?php echo $r_from->format('H:i') ?></span>
                                -
                                <span class=""><?php echo $r_to->format('H:i') ?></span>
                            </div>


                            <div class="col-3 col-md-1">
                                <span class="">

                                    <?php if (get_user_meta($r->mar_user_id, 'ms_user_corona_safetyinstruction', false)) : ?>
                                        <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_safetyinstruction" name="ms_user_corona_safetyinstruction" value="<?php echo $r->mar_user_id ?>">
                                            <clr-icon shape="check"></clr-icon>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_safetyinstruction" name="ms_user_corona_safetyinstruction" value="<?php echo $r->mar_user_id ?>">
                                            <clr-icon shape="times"></clr-icon>
                                        </button>
                                    <?php endif; ?>

                                </span>
                            </div>
                            <div class="col-3 col-md-1">
                                <span class="">

                                    <?php if (get_user_meta($r->mar_user_id, 'ms_user_corona_adress', false)) : ?>
                                        <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_adress" name="ms_user_corona_adress" value="<?php echo $r->mar_user_id ?>">
                                            <clr-icon shape="check"></clr-icon>
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_adress" name="ms_user_corona_adress" value="<?php echo $r->mar_user_id ?>">
                                            <clr-icon shape="times"></clr-icon>
                                        </button>
                                    <?php endif; ?>

                                </span>
                            </div>

                            <div class="col-12 col-md-2">
                                <?php if (count($mpl_entries) % 2 == 0) : ?>
                                    <button type="submit" class="btn btn-outline-dark btn-sm w-100" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                        <clr-icon shape="login"></clr-icon>
                                        kommen
                                    </button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-outline-dark btn-sm w-100" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                    <clr-icon shape="logout"></clr-icon>
                                    gehen
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
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