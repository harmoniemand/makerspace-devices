<?php

if (!isset($_GET["ms_event_workshop_id"]) && !isset($_GET["ms_event_id"])) {
    echo '<script> window.location.href = "/wp-admin/admin.php?page=ms_event_list"; </script>';
    exit();
}

$event = null;
$save_form_event_workshop_details_succes = false;

require_once dirname(__FILE__) . "/../../entities/event.entity.php";
require_once dirname(__FILE__) . "/../../entities/event-workshop.entity.php";
require_once dirname(__FILE__) . "/../../entities/event-workshop-registration.entity.php";

if (isset($_GET["ms_event_id"])) {
    $event = EventEntity::get_event_by_id($_GET["ms_event_id"]);
    $workshop = new EventWorkshopEntity();
}

if (isset($_GET["ms_event_workshop_id"])) {
    $workshop = EventWorkshopEntity::get_workshop_by_id($_GET["ms_event_workshop_id"]);
    $event = EventEntity::get_event_by_id($workshop->ms_event_id);
}

?>


<form method="POST" action="/wp-admin/admin.php?page=ms_event_detail&ms_event_id=<?php echo $event->ms_event_id ?>">

    <?php wp_nonce_field(basename(__FILE__), 'ms_event_workshop_form_nonce'); ?>

    <input type="hidden" name="ms_event_id" id="ms_event_id" value="<?php echo $event->ms_event_id ?>">


    <div class=" row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;">
                <a href="/wp-admin/admin.php?page=ms_event_detail&ms_event_id=<?php echo $event->ms_event_id ?>">
                    <?php echo $event->ms_event_title ?>
                </a>
                <span class="text-secondary">&gt;</span>

                <span>
                    <?php echo $workshop->ms_event_workshop_title ? $workshop->ms_event_workshop_title : __("new workshop"); ?>
                </span>
            </h1>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="ms_event_workshop_title" class="col-sm-2 col-form-label"><?php echo __("workshop name") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_workshop_title" id="ms_event_workshop_title" class="form-control-plaintext" value="<?php echo $workshop->ms_event_workshop_title ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ms_event_workshop_image_url" class="col-sm-2 col-form-label"><?php echo __("image url") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_workshop_image_url" id="ms_event_workshop_image_url" class="form-control-plaintext" value="<?php echo $workshop->ms_event_workshop_image_url ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ms_event_workshop_description" class="col-sm-2 col-form-label"><?php echo __("description") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_workshop_description" id="ms_event_workshop_description" class="form-control-plaintext" value="<?php echo $workshop->ms_event_workshop_description ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ms_event_workshop_additional_info" class="col-sm-2 col-form-label"><?php echo __("additional info") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_workshop_additional_info" id="ms_event_workshop_additional_info" class="form-control-plaintext" value="<?php echo $workshop->ms_event_workshop_additional_info ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ms_event_workshop_max_attendees" class="col-sm-2 col-form-label"><?php echo __("workshop name") ?></label>
                        <div class="col-sm-10">
                            <input type="number" name="ms_event_workshop_max_attendees" id="ms_event_workshop_max_attendees" class="form-control-plaintext" value="<?php echo $workshop->ms_event_workshop_max_attendees ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">
                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('workshops') ?></h5>
                    </div>

                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                            <tr>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("first name") ?></span></a>
                                </th>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("last name") ?></span></a>
                                </th>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("birthday") ?></span></a>
                                </th>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("email") ?></span></a>
                                </th>

                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                </th>
                            </tr>
                        </thead>

                        <tbody id="the-list">

                            <?php foreach ($workshop->registrations as $registration) : ?>

                                <tr class="iedit author-self level-0 type-page status-publish hentry">
                                    <td class="" data-colname="ms_events_workshop_registration_firstname"><?php echo $registration->ms_events_workshop_registration_firstname ?></td>
                                    <td class="" data-colname="ms_events_workshop_registration_lastname"><?php echo $registration->ms_events_workshop_registration_lastname ?></td>
                                    <td class="" data-colname="ms_events_workshop_registration_birthday"><?php echo $registration->ms_events_workshop_registration_birthday ?></td>
                                    <td class="" data-colname="ms_events_workshop_registration_birthday"><?php echo $registration->ms_events_workshop_registration_email ?></td>
                                    <td class="">

                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

        <div class="col-3">
            <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Speichern</li>
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Hello World</li>
                    <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</form>