<?php

global $login_error;


?>

<?php if (is_user_logged_in()) : ?>

    <div class="alert alert-danger" role="alert">
        Du bist bereits angemeldet. Wenn du einen neuen Account registrieren willst, melde dich vorher ab.
    </div>

<?php else : ?>

    <div class="modal fade" id="staticBackdrop" style="opacity: 1; display: block; background-image: url(<?php echo header_image() ?>); background-size:cover;" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div style="background-color: black; opacity: 0.7; width: 100%; height: 100%; position: absolute; top:0; left: 0;"></div>
        <form method="post" action="<?php echo get_permalink(); ?>">
            <?php wp_nonce_field(basename(__FILE__), 'makerspace_login_nonce'); ?>

            <div class="modal-dialog modal-dialog-centered" style="height: 100vh;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Anmeldung</h5>
                        <a href="/" class="close" data-dismiss="modal" aria-label="Close" style="text-decoration: none !important;">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>


                    <?php if (isset($login_error)) : ?>
                        <div class="modal-body">
                            <div class="alert alert-danger" role="alert">
                                <?php
                                echo $login_error;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="modal-body">
                        <div class="col-12 mt-3">
                            <div class="form-group row">
                                <label for="mse_username" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mse_username" name="mse_username" placeholder="" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group row">
                                <label for="mse_passwort" class="col-sm-2 col-form-label">Passwort</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="mse_passwort" name="mse_passwort" placeholder="" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-2">
                                    <input type="checkbox" class="form-control" id="mse_remember" name="mse_remember" value="Yes" placeholder="">
                                </div>
                                <label for="mse_remember" class="col-10 col-form-label">
                                    <?php echo __("Remember Me") ?>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <a href="/registration">Registrieren</a>
                        <button type="submit" class="btn btn-primary">Anmelden</button>
                    </div>
                    <div class="modal-footer d-flex p-1">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-12">
                                    <a href="/reset-password">
                                        Ich habe mein Passwort vergessen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </form>
    </div>

<?php endif; ?>