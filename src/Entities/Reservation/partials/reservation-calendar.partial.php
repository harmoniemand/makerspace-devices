<?php
global $wpdb;

$today = new DateTime();

$date = new DateTime();

$total_offset = 0;

if (isset($_GET["month"])) {
    $date_str = $_GET["month"] . "-" . $date->format("j");
    $date = new DateTime($date_str);
}


$daysCurrentMonth = array();
$firstDayInMonth = new DateTime($date->format('Y-m-d'));
$firstDayInMonth->setDate((int) $firstDayInMonth->format("Y"), (int) $firstDayInMonth->format("m"), 1);
$lastDayInMonth = new DateTime($date->format('Y-m-d'));
$lastDayInMonth->setDate((int) $firstDayInMonth->format("Y"), (int) $firstDayInMonth->format("m"), (int) $firstDayInMonth->format("t"));
$lastDayInMonth->setTime(23, 59);


for ($i = 1; $firstDayInMonth->format("t") >= $i; $i++) {
    $current = new DateTime();
    $current->setDate((int) $firstDayInMonth->format("Y"), (int) $firstDayInMonth->format("m"), $i);

    $day = (object) array(
        "day" => $i,
        "display" => $current->format('d.m.Y'),
        "date" => $current
    );

    array_push($daysCurrentMonth, $day);
}


// previous month
$daysPreviousMonth = array();
$lastDayPreviousMonth = new DateTime($date->format('Y-m-d'));
$lastDayPreviousMonth->setDate((int) $lastDayPreviousMonth->format("Y"), (int) $lastDayPreviousMonth->format("m") - 1, 1);
$lastDayPreviousMonth->setDate((int) $lastDayPreviousMonth->format("Y"), (int) $lastDayPreviousMonth->format("m"), (int) $lastDayPreviousMonth->format("t"));
$numbersDaysPreviousMonth = $lastDayPreviousMonth->format("t");

for ($i = 0; $firstDayInMonth->format("w") - 1 > $i; $i++) {
    $current = new DateTime();
    $current->setDate((int) $lastDayPreviousMonth->format("Y"), (int) $lastDayPreviousMonth->format("m"), $numbersDaysPreviousMonth - $i);

    $day = (object) array(
        "day" => $numbersDaysPreviousMonth - $i,
        "display" => $current->format('d.m.Y'),
        "date" => $current
    );

    array_push($daysPreviousMonth, $day);
}
$daysPreviousMonth = array_reverse($daysPreviousMonth);



$sql_reservations = "SELECT * FROM makerspace_ms_devices_workshop_reservations WHERE mse_device_from > %d and mse_device_to < %d";

$reservations = $wpdb->get_results($wpdb->prepare(
    $sql_reservations,
    $firstDayInMonth->getTimestamp(),
    $lastDayInMonth->getTimestamp()
));


?>


<div class="row mt-3" style="max-width: 100%;">
    <div class="col">
        <h1 class="wp-heading-inline" style="font-size: 23px;">Reservierungskalender</h1>
    </div>
</div>

<div class="row mt-3" style="max-width: 100%;">
    <div class="col">

        <div class="calendar-container">
            <div class="calendar-header d-flex">
                <div class="w-100 d-flex pl-5">
                    <?php
                    $prevDate = new DateTime();
                    $prevDate->setTimestamp(strtotime("-1 months", $date->getTimestamp()));
                    ?>
                    <a href="?page=reservations-calendar&month=<?php echo $prevDate->format("Y-m") ?>" class="btn btn-outline-primary">
                        <clr-icon shape="angle" style="transform: rotate(270deg);"></clr-icon>
                    </a>
                </div>
                <div>
                    <h1>
                        <?php echo $date->format("M") ?>
                    </h1>
                    <p><?php echo $date->format("Y") ?></p>
                </div>
                <div class="w-100 d-flex justify-content-end pr-5">
                    <?php
                    $nextDate = new DateTime();
                    $nextDate->setTimestamp(strtotime("+1 months", $date->getTimestamp()));
                    ?>
                    <a href="?page=reservations-calendar&month=<?php echo $nextDate->format("Y-m") ?>" class="btn btn-outline-primary">
                        <clr-icon shape="angle" style="transform: rotate(90deg);"></clr-icon>
                    </a> </div>
            </div>
            <div class="calendar">
                <span class="day-name">Mon</span>
                <span class="day-name">Tue</span>
                <span class="day-name">Wed</span>
                <span class="day-name">Thu</span>
                <span class="day-name">Fri</span>
                <span class="day-name">Sat</span>
                <span class="day-name">Sun</span>

                <?php foreach ($daysPreviousMonth as $day) : ?>
                    <div class="day day--disabled" title="<?php echo $day->display ?>"><?php echo $day->day ?></div>
                <?php endforeach; ?>

                <?php foreach ($daysCurrentMonth as $day) : ?>

                    <?php if ($day->date->format("Y-m-d") == (new DateTime())->format("Y-m-d")) : ?>
                        <div class="day" style="border: solid 1px blue" title="<?php echo $day->display ?>"><?php echo (new DateTime())->format("Y-m-d H:i") ?></div>
                    <?php else : ?>
                        <div class="day" title="<?php echo $day->display ?>"><?php echo $day->day ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>


                <div class="day day--disabled">1</div>
                <div class="day day--disabled">2</div>

                <?php foreach ($reservations as $r) : ?>

                    <?php
                    $date_from = new DateTime();
                    $date_from->setTimestamp($r->mse_device_from);

                    $grid_row = round(($date_from->format('j') + ($firstDayInMonth->format("w") - 1)) / 7) + 2;
                    $duration = ceil(($r->mse_device_to - $r->mse_device_from) / 60 / 60 / 24);
                    ?>

                    <section class="task" style="grid-column: <?php echo $date_from->format("w") ?> / span <?php echo $duration ?>; grid-row: <?php echo $grid_row ?>;  border-left-color: rgb(0, 150, 0); background: rgb(108, 240, 98);">
                        <a href="?page=reservations-editor&rid=<?php echo $r->mse_device_workshop_registration_id ?>" style="color: rgb(0,150, 0);"><?php echo $r->mse_device_project_title ?></a>
                    </section>

                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>