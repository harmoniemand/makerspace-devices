<?php

global $wpdb;

$uid = get_current_user_id();
$saved = false;

// Save Data

function c_update_user_meta($uid, $key, $value) {
    if ($value == false) {
        delete_user_meta($uid, $key);
        return;
    }

    update_user_meta($uid, $key, $value);
}

function save_data($uid) {
    c_update_user_meta($uid, 'my_calendar_include_workshops', isset($_POST["my_calendar_include_workshops"]) );
    c_update_user_meta($uid, 'my_calendar_include_my_workshops', isset($_POST["my_calendar_include_my_workshops"]) );
    c_update_user_meta($uid, 'my_calendar_include_reservations', isset($_POST["my_calendar_include_reservations"]));
    c_update_user_meta($uid, 'my_calendar_include_my_reservations', isset($_POST["my_calendar_include_my_reservations"]));

    update_user_meta($uid, 'my_settings_last_update', new DateTime());
    return true;
}

if (isset($_POST["mse_my_settings"])) {
    $saved = save_data($uid);
}



// Load Data

$my_calendar_url = get_user_meta($uid, 'my_calendar_url', true);
if ($my_calendar_url == false) {
    $my_calendar_url = wp_generate_password(32, false, false);
    update_user_meta($uid, 'my_calendar_url', $my_calendar_url);
}


$my_settings_last_update = get_user_meta($uid, 'my_settings_last_update', true);
$my_settings_last_update = $my_settings_last_update==false ? 'nie' : $my_settings_last_update->format("d.m.Y H:i");

$my_calendar_include_workshops = get_user_meta($uid, 'my_calendar_include_workshops');
$my_calendar_include_my_workshops = get_user_meta($uid, 'my_calendar_include_my_workshops');
$my_calendar_include_reservations = get_user_meta($uid, 'my_calendar_include_reservations');
$my_calendar_include_my_reservations = get_user_meta($uid, 'my_calendar_include_my_reservations');


?>

<?php if ($saved) : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-success" role="alert" style="padding: 8px 12px; width: 100%;">
                <?php echo __('Einstellungen gespeichert') ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="?page=my-settings">

    <?php wp_nonce_field(basename(__FILE__), 'mse_my_settings'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;"><?php echo __('Meine Einstellungen') ?></h1>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __('Kalendereinstellungen') ?></h5>

                    <div class="form-group row">
                        <label for="my_calendar_url" class="col-sm-2 col-form-label"><?php echo __('Kalender-URL') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="my_calendar_url" id="my_calendar_url" class="form-control-plaintext" value="<?php echo $my_calendar_url ?>" disabled>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="my_calendar_include_workshops"><?php echo __('Alle Workshops') ?></label>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input" id="my_calendar_include_workshops" name="my_calendar_include_workshops" <?php if ($my_calendar_include_workshops) { echo 'checked'; } ?>>
                        </div>
                        <label class="col-sm-2 col-form-label" for="my_calendar_include_my_workshops"><?php echo __('Meine Workshops') ?></label>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input" id="my_calendar_include_my_workshops" name="my_calendar_include_my_workshops" <?php if ($my_calendar_include_my_workshops) { echo 'checked'; } ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="my_calendar_include_reservations"><?php echo __('Alle Reservierungen') ?></label>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input" id="my_calendar_include_reservations" name="my_calendar_include_reservations" <?php if ($my_calendar_include_reservations) { echo 'checked'; } ?>>
                        </div>
                        
                        <label class="col-sm-2 col-form-label" for="my_calendar_include_my_reservations"><?php echo __('Meine Reservierungen') ?></label>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input" id="my_calendar_include_my_reservations" name="my_calendar_include_my_reservations" <?php if ($my_calendar_include_my_reservations) { echo 'checked'; } ?>>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Aktionen</li>
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">
                        Letzte Änderung: <?php echo $my_settings_last_update ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</form>