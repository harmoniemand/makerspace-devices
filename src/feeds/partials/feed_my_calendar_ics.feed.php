<?php

global $wpdb;

if (!isset($_GET["key"])) {
    status_header(404);
    exit;
}

const DT_FORMAT = 'Ymd\THis';
$key = $_GET["key"];
$events = array();

// get user by key
$user = get_users(
    array(
        'meta_key' => 'my_calendar_url',
        'meta_value' => $key,
        'number' => 1,
        'count_total' => false
    )
)[0];

$showReservations = get_user_meta($user->ID, 'my_calendar_include_reservations', true) == 1 ? true : false;
if ($showReservations) :
    $today = new DateTime();
    $firstDayInMonth = (new DateTime())->setDate((int) $today->format("Y"), (int) $today->format("m"), 1);

    $sql_reservations = "SELECT * FROM makerspace_ms_devices_workshop_reservations WHERE mse_device_from > %d ORDER BY mse_device_from";
    $reservations = $wpdb->get_results($wpdb->prepare(
        $sql_reservations,
        $firstDayInMonth->getTimestamp()
    ));

    foreach ($reservations as $reservation) :

        $event = (object) array(
            "dtstamp" => (new DateTime())->format(DT_FORMAT),
            "dtstart" => (new DateTime())->setTimestamp($reservation->mse_device_from)->format(DT_FORMAT),
            "dtend" => (new DateTime())->setTimestamp($reservation->mse_device_to)->format(DT_FORMAT),
            "UID" => get_site_url() . '/wp-admin/admin.php?page=reservations-editor&rid=' . $reservation->mse_device_workshop_registration_id,
            "created" => (new DateTime())->format(DT_FORMAT),
            "describtion" => $reservation->mse_device_message,
            "summary" => "Reservierung " . get_term($reservation->mse_device_workshop_taxonomie_id, '')->name . ' | ' .  $reservation->mse_device_project_title,
            "last_modified" => (new DateTime())->format(DT_FORMAT)
        );

        array_push($events, $event);
    endforeach;

endif;



$showAllWorkshops = get_user_meta($user->ID, 'my_calendar_include_workshops', true) == 1 ? true : false;
if ($showAllWorkshops) :

    $workshops = get_posts(array(
        'post_type'         => 'workshop',
        'posts_per_page'    =>  -1
    ));

    foreach ($workshops as $workshop) :
        $event = (object) array(
            "dtstamp" => (new DateTime())->format(DT_FORMAT),
            "dtstart" => get_post_meta($workshop->ID, 'workshop_start', true)->format(DT_FORMAT),
            "dtend" => get_post_meta($workshop->ID, 'workshop_end', true)->format(DT_FORMAT),
            "UID" => get_site_url() . '/workshop/' . $workshop->slug,
            "created" => get_the_date(DT_FORMAT, $event->ID),
            "describtion" => htmlspecialchars_decode(get_the_excerpt($event->ID)),
            "summary" => "Workshop " . htmlspecialchars_decode(get_the_title($event->ID)),
            "last_modified" => get_the_date(DT_FORMAT, $event->ID)
        );

        array_push($events, $event);
    endforeach;
endif;


header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=reservations-makerspace.ics");
header('Content-type: text/calendar; charset=utf-8');
header("Pragma: 0");
header("Expires: 0");


?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//bobbin v0.1//NONSGML iCal Writer//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH

<?php foreach ($events as $event) : ?>

    BEGIN:VEVENT
    DTSTART:<?php echo $event->dtstart;  ?>

    DTEND:<?php echo $event->dtend;  ?>

    UID:<?php echo $event->uid;  ?>

    CREATED:<?php echo $event->created;  ?>

    DESCRIPTION:<?php echo $event->description;  ?>

    SUMMARY:<?php echo $event->summary;  ?>

    LAST-MODIFIED:<?php echo $event->last_modified;  ?>

    SEQUENCE:0
    STATUS:CONFIRMED

    TRANSP:OPAQUE
    LOCATION: Maker Space Experimenta
    END:VEVENT

<?php endforeach; ?>

END:VCALENDAR