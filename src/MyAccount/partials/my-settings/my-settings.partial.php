<?php

global $success;

?>


<?php if (!empty($success)) : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-success" role="alert" style="padding: 8px 12px; width: 100%;">
                <?php echo $success ?>
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


            <?php require dirname(__FILE__) . "/card-calendar.partial.php" ?>
            <?php require dirname(__FILE__) . "/card-mail.partial.php" ?>
        
        
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