<?php if (is_user_logged_in()) : ?>

<div class="alert alert-danger" role="alert">
    <?php echo __("Du bist bereits angemeldet. Wenn du dein Passwort zurücksetzen möchtest, kannst du das im Userprofil tun.") ?>
</div>

<?php else : ?>




<?php

$error = "";
$success = "";

$mse_password_reset_username = "";

if (isset($_POST["makerspace_password_reset_request_form_nonce"])) {

    $mse_password_reset_username = $_POST["mse_password_reset_username"];
    $user = null;

    if (strpos($mse_password_reset_username, "@") != false) {
        // email
        $user = get_user_by("email", $mse_password_reset_username);
    } else {
        $user = get_user_by("login", $mse_password_reset_username);
    }

    $mail = (object) array(
        "to" => $user->user_email,
        "from" => get_option("admin_email"),
        "subject" => __("Passwort zurücksetzen"),
        "headers" => array('Content-Type: text/html; charset=UTF-8'),
        "content" => "test"
    );

    print_r($mail);

    wp_mail(
        $mail->to,
        $mail->subject,
        $mail->content,
        $mail->headers
    );

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

        <?php wp_nonce_field(basename(__FILE__), 'makerspace_password_reset_request_form_nonce'); ?>


        <div class="container">
            <div class="row mt-5">

                <div class="col-12 mt-3">
                    <div class="form-group row">
                        <label for="mse_registration_username" class="col-sm-4 col-form-label">Username oder E-Mail-Adresse</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mse_password_reset_username" name="mse_password_reset_username" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex  justify-content-end align-items-center">
                    <input type="submit" name="mse-event-register" class="btn btn-primary pr-5 pl-5" value="<?php echo _("Passwortlink anfordern") ?>" />
                </div>
            </div>

        </div>
    </form>

<?php endif; ?>
<?php endif; ?>