<?php

global $wpdb;
include_once dirname(__FILE__) . "./../../../Repositories/UserRepository.php";
$user_repo = new UserRepository();

$saved = false;
$uid = get_current_user_id();

if (isset($_POST["mse_my_data_form"])) {
    $saved_user = UserModel::from_post_array($_POST);
    $saved_user->user_id = $uid;
    $user_repo->Update($saved_user);
}

$user = $user_repo->Read($uid);

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
                            <input type="text" name="first_name" id="first_name" class="form-control-plaintext" value="<?php echo $user->first_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label"><?php echo __('Nachname') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="last_name" id="last_name" class="form-control-plaintext" value="<?php echo $user->last_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="public_name" class="col-sm-2 col-form-label">
                            <?php echo __('Öffentlicher Name') ?>
                            <clr-icon shape="info-circle" class="is-info" title="Wird unter deinen Blogeinträgen und bei deinen Workshops angezeigt."></clr-icon>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="public_name" id="public_name" class="form-control-plaintext" value="<?php echo $user->public_name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="birthday" class="col-sm-2 col-form-label"><?php echo __('Geburtsdatum') ?></label>
                        <div class="col-sm-10">
                            <input type="date" name="birthday" id="birthday" class="form-control-plaintext" value="<?php echo $user->birthday ?>">
                        </div>
                    </div>

                </div>
            </div>



            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Kontakt') ?></h5>

                        <div class="ml-auto">

                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo __('E-Mail') ?></label>
                        <div class="col-sm-10">
                            <input type="email" name="email" id="email" class="form-control-plaintext" value="<?php echo $user->email ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo __('Telefon') ?></label>
                        <div class="col-sm-10">
                            <input type="tel" name="phone" id="phone" class="form-control-plaintext" value="<?php echo $user->phone ?>">
                        </div>
                    </div>
                </div>
            </div>



            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Anschrift') ?></h5>

                        <div class="ml-auto">

                            <a class="btn btn-link" title="Historie anzeigen" data-toggle="collapse" href="#address_history" role="button" aria-expanded="false" aria-controls="address_history">
                                <clr-icon shape="rewind"></clr-icon>
                            </a>

                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo __('Addresse verifiziert') ?></label>

                        <?php if ($user->address && $user->address->validated != true) : ?>
                            <div class="col-sm-10">
                                <div class="alert alert-warning" role="alert">
                                    <div class="alert-items">
                                        <div class="alert-item static">
                                            <span class="alert-text">
                                                Deine Addresse ist noch nicht bestätigt. Wenn deine Addresse bestätigt ist, kannst du z.B. Equipment ausleihen.
                                                Um deine Adresse zu bestätigen, melde dich bei einem unserer Mitarbeitenden.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-sm-10">
                                <?php if ($user->address && $user->address->validated) : ?>
                                    <span style="margin-left: 1rem;">
                                        <clr-icon size="32" shape="check-circle" class="is-success" title="addresse bestätigt"></clr-icon>
                                    </span>
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo __('Vorname') ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" value="<?php echo $user->first_name ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?php echo __('Nachname') ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" value="<?php echo $user->last_name ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address_street" class="col-sm-2 col-form-label"><?php echo __('Straße') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="address_street" id="address_street" class="form-control-plaintext" value="<?php echo $user->address->street ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address_number" class="col-sm-2 col-form-label"><?php echo __('Hausnummer') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="address_number" id="address_number" class="form-control-plaintext" value="<?php echo $user->address->number ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address_zip" class="col-sm-2 col-form-label"><?php echo __('PLZ') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="address_zip" id="address_zip" class="form-control-plaintext" value="<?php echo $user->address->zip ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address_city" class="col-sm-2 col-form-label"><?php echo __('Ort') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="address_city" id="address_city" class="form-control-plaintext" value="<?php echo $user->address->city ?>">
                        </div>
                    </div>

                </div>

                <div class="card-body collapse" id="address_history">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Anschrift Historie') ?></h5>
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item font-weight-bold" style="margin-bottom: 0;">
                            <div class="row" style="line-height: 24px;">
                                <div class="col-sm-1 text-truncate" title="Bestätigt">Bestätigt</div>
                                <div class="col-sm-3 text-truncate" title="Vorname Name">Vorname Name</div>
                                <div class="col-sm-2 text-truncate" title="Straße">Straße</div>
                                <div class="col-sm-1 text-truncate" title="Nummer">Nummer</div>
                                <div class="col-sm-1 text-truncate" title="PLZ">PLZ</div>
                                <div class="col-sm-2 text-truncate" title="Ort">Ort</div>
                                <div class="col-sm-2 text-truncate" title="Erstellt am">Erstellt am</div>
                            </div>
                        </li>

                        <?php foreach ($user->addresses as $address) : ?>

                            <li class="list-group-item" style="margin-bottom: 0;">
                                <div class="row" style="line-height: 24px;">
                                    <div class="col-sm-1">
                                        <?php if ($address && $address->validated) : ?>
                                            <span style="margin-left: 1rem;">
                                                <clr-icon size="20" shape="check-circle" class="is-success" title="addresse bestätigt"></clr-icon>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-sm-3">
                                        <?php echo $address->first_name ?>
                                        <?php echo $address->last_name ?>
                                    </div>
                                    <div class="col-sm-2"><?php echo $address->street ?></div>
                                    <div class="col-sm-1"><?php echo $address->number ?></div>
                                    <div class="col-sm-1"><?php echo $address->zip ?></div>
                                    <div class="col-sm-2"><?php echo $address->city ?></div>
                                    <div class="col-sm-2">
                                        <?php echo $address->created ? $address->created->format('d.m.y H:i') : ""; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>




                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div style="display: flex;">
                        <h5 class="card-title"><?php echo __('Über mich') ?></h5>
                    </div>

                    <div class="form-group row">
                        <label for="bio" class="col-sm-2 col-form-label">
                            <?php echo __('Biografie') ?>
                            <clr-icon shape="info-circle" class="is-info" title="Wird unter deinen Blogeinträgen und bei deinen Workshops angezeigt."></clr-icon>
                        </label>
                        <div class="col-sm-10">
                            <textarea rows="5" style="border: 1px solid #7e8993;" class="form-control-plaintext" name="bio" id="bio"><?php echo $user->bio ?></textarea>
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
                    </li>
                    <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</form>