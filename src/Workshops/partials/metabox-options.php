<?php

global $post;

// Nonce field to validate form request came from current site
wp_nonce_field(basename(__FILE__), 'metabox_workshop_date');

?>


<div class="fluid-container">

    <!-- Betriebsanweisung -->
    <div class="row">
        <?php $workshop_option_highlight = get_post_meta($post->ID, 'workshop_option_highlight', true); ?>
        <?php $workshop_option_free_seats = get_post_meta($post->ID, 'workshop_option_free_seats', true); ?>
        <?php $workshop_option_registration_url = get_post_meta($post->ID, 'workshop_option_registration_url', true); ?>

        <div class="col-12 input-group" style="line-height: 30px;">
            <label for="workshop_option_highlight">Workshop hervorheben</label>
        </div>
        <div class="col-12 input-group">
            <input type="checkbox" class="" id="workshop_option_highlight" name="workshop_option_highlight" <?php if ($workshop_option_highlight) {
                                                                                                                echo 'checked';
                                                                                                            } ?>>
        </div>

        <div class="col-12 input-group mt-3">
            <label class="pr-2" for="workshop_option_free_seats">Plätze</label>
        </div>
        <div class="col-12 input-group">
            <input type="number" class="form-control" id="workshop_option_free_seats" name="workshop_option_free_seats" value="<?php echo  $workshop_option_free_seats ?>">
        </div>

        <div class="col-12 input-group mt-3">
            <label class="pr-2" for="workshop_option_registration_url" title="Zeigt einen Link statt der Anmeldemaske an">Anmeldung unter</label>
        </div>
        <div class="col-12 input-group">
            <input type="text" class="form-control" id="workshop_option_registration_url" name="workshop_option_registration_url" value="<?php echo  $workshop_option_registration_url ?>">
        </div>

    </div>
</div>