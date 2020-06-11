<?php if (is_user_logged_in()) : ?>

    <div class="alert alert-danger" role="alert">
        Du bist bereits angemeldet. Wenn du einen neuen Account registrieren willst, melde dich vorher ab.
    </div>

<?php else : ?>




    <?php

    $error = "";
    $success = "";

    $mse_registration_username = "";
    $mse_registration_passwort = "";
    $mse_registration_passwort_repeat = "";
    $mse_registration_first_name = "";
    $mse_registration_last_name = "";
    $mse_registration_email = "";
    $mse_registration_privacy = false;
    $mse_registration_information = false;


    if (isset($_POST["makerspace_settings_nonce"])) {

        $mse_registration_username = $_POST["mse_registration_username"];
        $mse_registration_passwort = $_POST["mse_registration_passwort"];
        $mse_registration_passwort_repeat = $_POST["mse_registration_passwort_repeat"];
        $mse_registration_first_name = $_POST["mse_registration_first_name"];
        $mse_registration_last_name = $_POST["mse_registration_last_name"];
        $mse_registration_email = $_POST["mse_registration_email"];
        $mse_registration_privacy = isset($_POST["mse_registration_privacy"]) && $_POST["mse_registration_privacy"] == "Yes" ? true : false;
        $mse_registration_information = isset($_POST["mse_registration_information"]) && $_POST["mse_registration_information"] == "Yes" ? true : false;

        if ($mse_registration_passwort == $mse_registration_passwort_repeat) {
            $ldap['server'] =       get_option("makerspace_ldap_server");
            $ldap['port'] =         get_option("makerspace_ldap_port");
            $ldap['admin'] =        get_option("makerspace_ldap_admin");
            $ldap['admin_pass'] =   get_option("makerspace_ldap_admin_pass");
            $ldap['user_ou'] =      get_option("makerspace_ldap_user_ou");
            $ldap['gidNumberVisitors'] =      get_option("makerspace_ldap_gid_number_visitors");


            // echo "<pre>";
            // print_r($ldap);
            // echo "</pre>";


            $ldap['connection'] = ldap_connect($ldap['server'], $ldap['port']);
            ldap_set_option($ldap['connection'], LDAP_OPT_PROTOCOL_VERSION, 3);

            if ($ldap['connection']) {

                $ldap_binding = ldap_bind($ldap['connection'], $ldap['admin'], $ldap['admin_pass']) or die("Error trying to bind: " . ldap_error($ldap['connection']));

                if ($ldap_binding) {

                    $search = ldap_search($ldap['connection'], $ldap['user_ou'], "(cn=" . $mse_registration_username . ")");
                    $ldapentry = ldap_first_entry($ldap['connection'], $search);

                    if ($ldapentry) {
                        // user already exists
                        $error = __("Der gewünschte Username ist leider schon vergeben.");
                    } else {

                        $count_resourceid = ldap_search($ldap['connection'], $ldap['user_ou'], "(objectClass=*)");
                        $count = ldap_count_entries($ldap['connection'], $count_resourceid);

                        // prepare data
                        $info["givenName"] = $mse_registration_first_name;
                        $info["sn"] = $mse_registration_last_name;
                        $info["cn"] = $mse_registration_username;
                        $info["uidNumber"] = 1000 + $count;
                        $info["uid"] = $mse_registration_username;
                        $info["userPassword"] = "{MD5}" . base64_encode(pack("H*", md5($mse_registration_passwort)));


                        $info["homeDirectory"] = "/home/users/" . $mse_registration_username;
                        $info["gidNumber"] = $ldap['gidNumberVisitors'];

                        $info["objectClass"][0] = "posixAccount";
                        $info["objectClass"][1] = "inetOrgPerson";

                        // echo "<pre>";
                        // print_r($info);
                        // echo "</pre>";

                        // add data to directory
                        $dn = "cn=" . $info['cn'] . "," . $ldap['user_ou'];
                        $r = ldap_add($ldap['connection'], $dn, $info);

                        $success = __("Vielen Dank für deine Registrierung. Du kannst dich jetzt mit deinen gewählten Zugangsdaten anmelden.");
                    }
                } else {
                    $error = __("Es ist ein Fehler beim Erstellen des Users aufgetreten. Wenn der Fehler wieder auftritt, melde dich bei uns.");
                }

                ldap_close($ldap['connection']);
            } else {
                $error = __("Es ist ein Fehler beim Erstellen des Users aufgetreten. Wenn der Fehler wieder auftritt, melde dich bei uns.");
            }
        } else {
            $error = __("Die eingegebene Passwörter stimmen nicht überein.");
        }
    }

    ?>

    <?php if ($error != "") : ?>

        <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
        </div>

    <?php endif; ?>


    <?php if ($success != "") : ?>

        <div class="alert alert-success" role="alert">
            <?php echo $success ?>
        </div>

    <?php else : ?>



        <form method="post" action="<?php echo get_permalink(); ?>">

            <?php wp_nonce_field(basename(__FILE__), 'makerspace_settings_nonce'); ?>


            <div class="container">
                <div class="row mt-5">
                    <div class="12">
                        <h3>Accountdaten</h3>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="form-group row">
                            <label for="mse_registration_username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mse_registration_username" name="mse_registration_username" placeholder="" value="<?php echo $mse_registration_username ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="mse_registration_passwort" class="col-sm-2 col-form-label">Passwort</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="mse_registration_passwort" name="mse_registration_passwort" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="mse_registration_passwort_repeat" class="col-sm-2 col-form-label">Passwort wiederholen</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="mse_registration_passwort_repeat" name="mse_registration_passwort_repeat" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="col-12 mt-3">
                        <div class="form-group row">
                            <label for="mse_registration_first_name" class="col-sm-2 col-form-label">Vorname</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mse_registration_first_name" name="mse_registration_first_name" value="<?php echo $mse_registration_first_name ?>" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="mse_registration_last_name" class="col-sm-2 col-form-label">Nachname</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mse_registration_last_name" name="mse_registration_last_name" value="<?php echo $mse_registration_last_name ?>" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="mse_registration_email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="mse_registration_email" name="mse_registration_email" value="<?php echo $mse_registration_email ?>" placeholder="" required>
                            </div>
                        </div>
                    </div>



                    <div class="col-12 mt-3">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <input type="checkbox" class="form-control" id="mse_registration_privacy" name="mse_registration_privacy" value="Yes" placeholder="" required <?php if ($mse_registration_privacy == true) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?>>
                            </div>
                            <div class="col-sm-10 col-form-label">
                                Ich habe die <a href="/datenschutz" target="blank">Datenschutzerklärung</a> durchgelesen und stimme dieser zu.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <input type="checkbox" class="form-control" id="mse_registration_information" name="mse_registration_information" value="Yes" placeholder="" <?php if ($mse_registration_information == true) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?>>
                            </div>
                            <div class="col-sm-10 col-form-label">
                                Ich möchte regelmäßig über Veranstaltungen und Neuigkeiten aus dem Maker Space informiert werden.
                            </div>
                        </div>
                    </div>


                    <div class="col-12 d-flex  justify-content-end align-items-center">
                        <input type="submit" name="mse-event-register" class="btn btn-primary pr-5 pl-5" value="Account registrieren" />
                    </div>
                </div>

            </div>
        </form>

    <?php endif; ?>
<?php endif; ?>