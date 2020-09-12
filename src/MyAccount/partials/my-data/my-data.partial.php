<?php

global $wpdb;

$uid = get_current_user_id();
$saved = false;

// Save Data

function c_update_user_meta($uid, $key, $value)
{
    if ($value == false) {
        delete_user_meta($uid, $key);
        return;
    }

    update_user_meta($uid, $key, $value);
}

function save_data($uid)
{
    c_update_user_meta($uid, 'first_name', $_POST["first_name"]);
    c_update_user_meta($uid, 'last_name', $_POST["last_name"]);
    c_update_user_meta($uid, 'nickname', $_POST["public_name"]);
    wp_update_user(array("ID" => $uid, "user_nicename" => $_POST["public_name"],  "display_name" => $_POST["public_name"]));

    c_update_user_meta($uid, 'makerspace_userdata_birthdays', $_POST["makerspace_userdata_birthdays"]);

    c_update_user_meta($uid, 'makerspace_userdata_address_validated', false);
    c_update_user_meta($uid, 'makerspace_userdata_address_street', $_POST["makerspace_userdata_address_street"]);
    c_update_user_meta($uid, 'makerspace_userdata_address_number', $_POST["makerspace_userdata_address_number"]);
    c_update_user_meta($uid, 'makerspace_userdata_address_zip', $_POST["makerspace_userdata_address_zip"]);
    c_update_user_meta($uid, 'makerspace_userdata_address_city', $_POST["makerspace_userdata_address_city"]);

    c_update_user_meta($uid, 'description', $_POST["makerspace_userdata_bio"]);

    return true;
}

if (isset($_POST["mse_my_data_form"])) {
    $saved = save_data($uid);
}



// Load Data

$my_calendar_url = get_user_meta($uid, 'my_calendar_url', true);
if ($my_calendar_url == false) {
    $my_calendar_url = wp_generate_password(32, false, false);
    update_user_meta($uid, 'my_calendar_url', $my_calendar_url);
}

$my_settings_last_update = get_user_meta($uid, 'my_settings_last_update', true);
$my_settings_last_update = $my_settings_last_update == false ? 'nie' : $my_settings_last_update->format("d.m.Y H:i");

$first_name = get_user_meta($uid, 'first_name', true);
$last_name = get_user_meta($uid, 'last_name', true);
$public_name = get_user_meta($uid, 'nickname', true);
$makerspace_userdata_birthdays = get_user_meta($uid, 'makerspace_userdata_birthdays', true);

$makerspace_userdata_address_validated = get_user_meta($uid, 'makerspace_userdata_address_validated', true);
$makerspace_userdata_address_street = get_user_meta($uid, 'makerspace_userdata_address_street', true);
$makerspace_userdata_address_number = get_user_meta($uid, 'makerspace_userdata_address_number', true);
$makerspace_userdata_address_zip = get_user_meta($uid, 'makerspace_userdata_address_zip', true);
$makerspace_userdata_address_city = get_user_meta($uid, 'makerspace_userdata_address_city', true);

$makerspace_userdata_bio = get_user_meta($uid, 'description', true);

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



<form method="POST" action="?page=my_data">

    <?php wp_nonce_field(basename(__FILE__), 'mse_my_data_form'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;"><?php echo __('Meine Stammdaten') ?></h1>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Stammdaten') ?></h5>
                    </div>


                    <div class="form-group row">
                        <label for="first_name" class="col-sm-2 col-form-label"><?php echo __('Vorname') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="first_name" id="first_name" class="form-control-plaintext" value="<?php echo $first_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label"><?php echo __('Nachname') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="last_name" id="last_name" class="form-control-plaintext" value="<?php echo $last_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="public_name" class="col-sm-2 col-form-label">
                            <?php echo __('Öffentlicher Name') ?>
                            <clr-icon shape="info-circle" class="is-info" title="Wird unter deinen Blogeinträgen und bei deinen Workshops angezeigt."></clr-icon>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="public_name" id="public_name" class="form-control-plaintext" value="<?php echo $public_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_userdata_birthdays" class="col-sm-2 col-form-label"><?php echo __('Geburtsdatum') ?></label>
                        <div class="col-sm-10">
                            <input type="date" name="makerspace_userdata_birthdays" id="makerspace_userdata_birthdays" class="form-control-plaintext" value="<?php echo $makerspace_userdata_birthdays ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Anschrift') ?></h5>
                        <?php if ($makerspace_userdata_address_validated) : ?>
                            <span style="margin-left: 1rem;">
                                <clr-icon shape="check-circle" class="is-success" title="addresse bestätigt"></clr-icon>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($makerspace_userdata_address_validated != true) : ?>
                        <div class="alert alert-warning" role="alert">
                            <div class="alert-items">
                                <div class="alert-item static">
                                    <span class="alert-text">
                                        Deine Addresse ist noch nicht bestätigt. Wenn deine Addresse bestätigt ist, kannst du z.B. Equipment ausleihen.
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group row">
                        <label for="makerspace_userdata_address_street" class="col-sm-2 col-form-label"><?php echo __('Straße') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_userdata_address_street" id="makerspace_userdata_address_street" class="form-control-plaintext" value="<?php echo $makerspace_userdata_address_street ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_userdata_address_number" class="col-sm-2 col-form-label"><?php echo __('Hausnummer') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_userdata_address_number" id="makerspace_userdata_address_number" class="form-control-plaintext" value="<?php echo $makerspace_userdata_address_number ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_userdata_address_zip" class="col-sm-2 col-form-label"><?php echo __('PLZ') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_userdata_address_zip" id="makerspace_userdata_address_zip" class="form-control-plaintext" value="<?php echo $makerspace_userdata_address_zip ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_userdata_address_city" class="col-sm-2 col-form-label"><?php echo __('Ort') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_userdata_address_city" id="makerspace_userdata_address_city" class="form-control-plaintext" value="<?php echo $makerspace_userdata_address_city ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Über mich') ?></h5>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_userdata_bio" class="col-sm-2 col-form-label">
                            <?php echo __('Biografie') ?>
                            <clr-icon shape="info-circle" class="is-info" title="Wird unter deinen Blogeinträgen und bei deinen Workshops angezeigt."></clr-icon>
                        </label>
                        <div class="col-sm-10">
                            <textarea rows="5" style="border: 1px solid #7e8993;" class="form-control-plaintext"  name="makerspace_userdata_bio" id="makerspace_userdata_bio"><?php echo $makerspace_userdata_bio ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-12 col-md-3">
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