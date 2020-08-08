<?php

require_once dirname(__FILE__) . "/../../Helper/GuidHelper.php";

global $wpdb;

$error = "";
$success = "";

if (isset($_POST["makerspace_reservation_log_form_nonce"])) {
    $sql_mp_create_log = "
    INSERT INTO makerspace_presence_logs (
        mpl_temp_visitor_id, 
        mpl_temp_visitor_name,
        mpl_temp_visitor_address
    ) values (%s, %s, %s)";

    $wpdb->get_results($wpdb->prepare(
        $sql_mp_create_log,
        GuidHelper::GUID(),
        $_POST["mse_mpl_name"],
        $_POST["mse_mpl_city"] . " " . $_POST["mse_mpl_street"]
    ));

    $success = "Deine Kontaktdaten wurden hinterlegt und werden in 4 Wochen wieder automatisch gelöscht.";
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

    <div class="col-12">
        <a href="<?php echo get_permalink(); ?>" class="btn btn-primary">Zurück zum Formular</a>
    </div>

<?php else : ?>



    <form method="post" action="<?php echo get_permalink(); ?>">

        <?php wp_nonce_field(basename(__FILE__), 'makerspace_reservation_log_form_nonce'); ?>


        <div class="container">
            <div class="row mt-5">

                <div class="col-12 mt-3">
                    <div class="form-group row">
                        <label for="mse_mpl_name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_mpl_name" name="mse_mpl_name" value="" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group row">
                        <label for="mse_mpl_street" class="col-sm-2 col-form-label">Straße / Nr</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_mpl_street" name="mse_mpl_street" value="" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group row">
                        <label for="mse_mpl_city" class="col-sm-2 col-form-label"> PLZ / Stadt</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_mpl_city" name="mse_mpl_city" value="" placeholder="" required>
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


                <div class="col-12 d-flex  justify-content-end align-items-center">
                    <input type="submit" name="mse-event-register" class="btn btn-primary pr-5 pl-5" value="Kontaktdaten hinterlegen" />
                </div>
            </div>

        </div>
    </form>

<?php endif; ?>