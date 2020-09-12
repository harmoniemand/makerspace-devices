<?php

$uid = get_current_user_id();

// Load Data

$my_calendar_url = get_user_meta($uid, 'my_calendar_url', true);
if ($my_calendar_url == false) {
    $my_calendar_url = wp_generate_password(32, false, false);
    update_user_meta($uid, 'my_calendar_url', $my_calendar_url);
}


$my_settings_last_update = get_user_meta($uid, 'my_settings_last_update', true);
$my_settings_last_update = $my_settings_last_update == false ? 'nie' : $my_settings_last_update->format("d.m.Y H:i");

$my_calendar_include_workshops = get_user_meta($uid, 'my_calendar_include_workshops');
$my_calendar_include_my_workshops = get_user_meta($uid, 'my_calendar_include_my_workshops');
$my_calendar_include_reservations = get_user_meta($uid, 'my_calendar_include_reservations');
$my_calendar_include_my_reservations = get_user_meta($uid, 'my_calendar_include_my_reservations');

?>


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
                <input type="checkbox" class="form-check-input" id="my_calendar_include_workshops" name="my_calendar_include_workshops" <?php if ($my_calendar_include_workshops) {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>>
            </div>
            <label class="col-sm-2 col-form-label" for="my_calendar_include_my_workshops"><?php echo __('Meine Workshops') ?></label>
            <div class="col-sm-2 d-flex align-items-center">
                <input type="checkbox" class="form-check-input" id="my_calendar_include_my_workshops" name="my_calendar_include_my_workshops" <?php if ($my_calendar_include_my_workshops) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="my_calendar_include_reservations"><?php echo __('Alle Reservierungen') ?></label>
            <div class="col-sm-2 d-flex align-items-center">
                <input type="checkbox" class="form-check-input" id="my_calendar_include_reservations" name="my_calendar_include_reservations" <?php if ($my_calendar_include_reservations) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
            </div>

            <label class="col-sm-2 col-form-label" for="my_calendar_include_my_reservations"><?php echo __('Meine Reservierungen') ?></label>
            <div class="col-sm-2 d-flex align-items-center">
                <input type="checkbox" class="form-check-input" id="my_calendar_include_my_reservations" name="my_calendar_include_my_reservations" <?php if ($my_calendar_include_my_reservations) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>>
            </div>
        </div>


    </div>
</div>