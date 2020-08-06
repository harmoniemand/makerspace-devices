<?php

$save_form_settings_saved = false;


if (isset($_POST['makerspace_settings_nonce'])) {

    update_option('makerspace_ldap_server', $_POST['makerspace_ldap_server']);
    update_option('makerspace_ldap_port', $_POST['makerspace_ldap_port']);
    update_option('makerspace_ldap_admin', $_POST['makerspace_ldap_admin']);
    update_option('makerspace_ldap_admin_pass', $_POST['makerspace_ldap_admin_pass']);
    update_option('makerspace_ldap_user_ou', $_POST['makerspace_ldap_user_ou']);
    update_option('makerspace_ldap_gid_number_visitors', $_POST['makerspace_ldap_gid_number_visitors']);

    update_option('makerspace_visitor_limit', $_POST['makerspace_visitor_limit']);
    update_option('makerspace_visitor_default_color', $_POST['makerspace_visitor_default_color']);
    $save_form_settings_saved = true;
}


$makerspace_ldap_server = get_option("makerspace_ldap_server");
$makerspace_ldap_port = get_option("makerspace_ldap_port");
$makerspace_ldap_admin = get_option("makerspace_ldap_admin");
$makerspace_ldap_admin_pass = get_option("makerspace_ldap_admin_pass");
$makerspace_ldap_user_ou = get_option("makerspace_ldap_user_ou");
$makerspace_ldap_gid_number_visitors = get_option("makerspace_ldap_gid_number_visitors");

$makerspace_visitor_default_color = get_option("makerspace_visitor_default_color");
$makerspace_visitor_limit = get_option("makerspace_visitor_limit");

?>




<?php if ($save_form_settings_saved) : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-success" role="alert" style="padding: 8px 12px; width: 100%;">
                Reservierung gespeichert
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="?page=ms_settings">

    <?php wp_nonce_field(basename(__FILE__), 'makerspace_settings_nonce'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <h1 class="wp-heading-inline" style="font-size: 23px;">Maker Space Settings</h1>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="makerspace_ldap_server" class="col-sm-2 col-form-label">Visitor Limit</label>
                        <div class="col-sm-10">
                            <input type="number" name="makerspace_visitor_limit" id="makerspace_visitor_limit" class="form-control-plaintext" value="<?php echo $makerspace_visitor_limit ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_visitor_default_color" class="col-sm-2 col-form-label">Standardfarbe Besuchende</label>
                        <div class="col-sm-1">
                            <input type="color" onchange="document.querySelector('#makerspace_visitor_default_color_text').value = this.value" style="padding: 0 8px; line-height: 2; min-height: 32px;" name="makerspace_visitor_default_color" id="makerspace_visitor_default_color" class="form-control-plaintext" value="<?php echo $makerspace_visitor_default_color ?>">
                        </div>
                        <div class="col-sm-9">
                            <input type="text" onchange="document.querySelector('#makerspace_visitor_default_color').value = this.value" name="makerspace_visitor_default_color_text" id="makerspace_visitor_default_color_text" class="form-control-plaintext" value="<?php echo $makerspace_visitor_default_color ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="makerspace_ldap_server" class="col-sm-2 col-form-label">LDAP Server</label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_ldap_server" id="makerspace_ldap_server" class="form-control-plaintext" value="<?php echo $makerspace_ldap_server ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_ldap_port" class="col-sm-2 col-form-label">LDAP Port</label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_ldap_port" id="makerspace_ldap_port" class="form-control-plaintext" value="<?php echo $makerspace_ldap_port ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_ldap_admin" class="col-sm-2 col-form-label">LDAP User</label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_ldap_admin" id="makerspace_ldap_admin" class="form-control-plaintext" value="<?php echo $makerspace_ldap_admin ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_ldap_admin_pass" class="col-sm-2 col-form-label">LDAP Passwort</label>
                        <div class="col-sm-10">
                            <input type="password" name="makerspace_ldap_admin_pass" id="makerspace_ldap_admin_pass" class="form-control-plaintext" value="<?php echo $makerspace_ldap_admin_pass ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_ldap_user_ou" class="col-sm-2 col-form-label">User OU</label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_ldap_user_ou" id="makerspace_ldap_user_ou" class="form-control-plaintext" value="<?php echo $makerspace_ldap_user_ou ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="makerspace_ldap_gid_number_visitors" class="col-sm-2 col-form-label">Visitor gid number</label>
                        <div class="col-sm-10">
                            <input type="text" name="makerspace_ldap_gid_number_visitors" id="makerspace_ldap_gid_number_visitors" class="form-control-plaintext" value="<?php echo $makerspace_ldap_gid_number_visitors ?>">
                        </div>
                    </div>

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