<?php

$error = "";
$success = "";

if (isset($_POST["makerspace_reservation_log_form_nonce"])) {
    
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

        <?php wp_nonce_field(basename(__FILE__), 'makerspace_reservation_log_form_nonce'); ?>


        <div class="container">
            <div class="row mt-5">

                <div class="col-12 mt-3">
                    <div class="form-group row">
                        <label for="mse_registration_first_name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_registration_first_name" name="mse_registration_first_name" value="" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group row">
                        <label for="mse_registration_last_name" class="col-sm-2 col-form-label">Straße / Nr</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_registration_last_name" name="mse_registration_last_name" value="" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group row">
                        <label for="mse_registration_last_name" class="col-sm-2 col-form-label"> PLZ / Stadt</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mse_registration_last_name" name="mse_registration_last_name" value="" placeholder="" required>
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