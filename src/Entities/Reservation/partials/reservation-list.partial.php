<?php

global $wpdb;

?>

<div class="row mt-2" style="max-width: 100%;">

    <div class="col">
        <button type="button" class="btn btn-outline-primary">Aktuelle Reservierungen</button>
        <button type="button" class="btn btn-outline-primary">Neue Reservierungen</button>
        <button type="button" class="btn btn-outline-primary">Abgelaufene Reservierungen</button>
    </div>

</div>

<?php
$sql_reservations = "SELECT * FROM makerspace_ms_devices_workshop_reservations WHERE mse_device_from > %d";

$today = time();
$today_start = new DateTime();
$today_start->setTimestamp($today);
$today_start->setTime(0, 0, 1);


$reservations = $wpdb->get_results($wpdb->prepare(
    $sql_reservations,
    $today_start->getTimestamp()
));
?>

<div class="row mt-3" style="max-width: 100%;">
    <div class="col">

        <table class="w-100 wp-list-table widefat fixed striped posts">
            <thead class="border">
                <th class="pl-2 pr-2">Datum</th>
                <th class="pl-2 pr-2">Zeit</th>
                <th class="pl-2 pr-2"></th>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r) : ?>

                    <?php
                    $date_from = new DateTime();
                    $date_from->setTimestamp($r->mse_device_from);

                    $date_to = new DateTime();
                    $date_to->setTimestamp($r->mse_device_to);
                    ?>
                    <tr>
                        <td class="d-flex justify-content-start pl-2 pr-2" style="font-size: 14px;"><?php echo strftime("%d.%m.%y", $r->mse_device_from); ?></td>
                        <td class="pl-2 pr-2" style="font-size: 14px;">
                            <?php echo strftime("%H:%M", $r->mse_device_from); ?><br />
                            <?php echo strftime("%H:%M", $r->mse_device_to); ?>
                        </td>
                        <td style="font-size: 14px;">
                            <div class="font-weight-bolder">
                                <?php echo $r->mse_device_project_title ?>
                            </div>
                            <div class="">
                                <a href="mailto:<?php echo $r->mse_device_workshop_registration_email ?>&subject=Rückmeldung zur deiner Anfrage '<?php echo $r->mse_device_project_title ?>'">
                                    <?php echo $r->mse_device_workshop_registration_email ?>
                                </a>
                            </div>
                        </td>
                        <td style="font-size: 14px;">
                            <?php echo $r->mse_device_message ?>

                        </td>
                        <td class="" style="font-size: 14px;">
                            <?php if (!$r->mse_device_approved) : ?>
                                <a href="" class="">Reservierung freigeben</a> <br />
                            <?php else : ?>
                                <a href="" class="">Freigabe zurücknehmen</a> <br />
                            <?php endif; ?>

                            <a href="?page=reservations-editor&rid=<?php echo $r->mse_device_workshop_registration_id ?>" class="">Bearbeiten</a> <br />
                            <a href="?page=reservations-list&delete=true&rid=<?php echo $r->mse_device_workshop_registration_id ?>" class="">Löschen</a>

                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>