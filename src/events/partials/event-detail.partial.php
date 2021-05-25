<?php

if (!isset($_GET["ms_event_id"])) {
    echo '<script> window.location.href = "/wp-admin/admin.php?page=ms_event_list"; </script>';
    exit();
}

require_once dirname(__FILE__) . "/../../entities/event.entity.php";

$save_form_event_details_succes = false;

// save workshop entry
if (isset($_POST["ms_event_workshop_form_nonce"])) {
    
    $workshop = new EventWorkshopEntity((object)$_POST);
    $workshop->ms_event_id = $_GET["ms_event_id"];
    
    EventWorkshopEntity::create_workshop($workshop);
    
    $save_form_event_workshop_details_succes = true;
}


$event = EventEntity::get_event_by_id($_GET["ms_event_id"]);
?>




<?php if ($save_form_event_details_succes) : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-success" role="alert" style="padding: 8px 12px; width: 100%;">
                Änderungen gespeichert
            </div>
        </div>
    </div>
<?php endif; ?>



<form method="POST" action="/wp-admin/admin.php?page=ms_event_detail&ms_event_id=>

    <?php wp_nonce_field(basename(__FILE__), 'ms_event_form_nonce'); ?>

    <div class=" row mt-3" style="max-width: 100%;">
    <div class="col">
        <h1 class="wp-heading-inline" style="font-size: 23px;">Event Settings</h1>
    </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="ms_event_title" class="col-sm-2 col-form-label"><?php echo __("event name") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_title" id="ms_event_title" class="form-control-plaintext" value="<?php echo $event->ms_event_title ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ms_event_slug" class="col-sm-2 col-form-label"><?php echo __("event slug") ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="ms_event_slug" id="ms_event_slug" class="form-control-plaintext" value="<?php echo $event->ms_event_slug ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">
                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('workshops') ?></h5>
                    </div>
                    <div class="row">
                        <div class="col">
                            <a href="?page=ms_event_workshop_detail&ms_event_id=<?php echo $event->ms_event_id ?>"><?php echo __("add workshop") ?></a>
                        </div>
                    </div>


                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                            <tr>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("event title") ?></span></a>
                                </th>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("max attendees") ?></span></a>
                                </th>
                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                    <a><span><?php echo __("description") ?></span></a>
                                </th>

                                <th scope="col" class="manage-column column-title column-primary sortable desc">
                                </th>
                            </tr>
                        </thead>

                        <tbody id="the-list">

                            <?php foreach ($event->workshops as $workshop) : ?>

                                <tr class="iedit author-self level-0 type-page status-publish hentry">
                                    <td class="" data-colname="ms_event_workshop_title"><?php echo $workshop->ms_event_workshop_title ?></td>
                                    <td class="" data-colname="ms_event_workshop_max_attendees"><?php echo $workshop->ms_event_workshop_max_attendees ?></td>
                                    <td class="" data-colname="ms_event_workshop_description"><?php echo $workshop->ms_event_workshop_description ?></td>
                                    <td class="">
                                        <a href="?page=ms_event_workshop_detail&ms_event_workshop_id=<?php echo $workshop->ms_event_workshop_id ?>"><?php echo __("edit") ?></a>
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