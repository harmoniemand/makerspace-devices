<?php

require_once MS_DM_DIR . '/src/includes/class-logger.php';

global $post;

// Nonce field to validate form request came from current site
wp_nonce_field( basename( __FILE__ ), 'metabox_devices_nonce' );

MSDM_Logger::Debug("running custom meta box");

?>


<link rel="stylesheet" type="text/css"
    href="<?php echo plugins_url('makerspace-wordpress-plugin/src/css/bootstrap.css') ?>" />

<div class="fluid-container">

    <!-- Betriebsanweisung -->
    <div class="row">
        <!-- <div class="col-12">
            <?php $betriebsanweisung_not_needed = get_post_meta($post->ID, 'betriebsanweisung_not_needed', true); ?>

            <label for="betriebsanweisung_not_needed">Betriebsanweisung nicht notwendig</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" id="betriebsanweisung_not_needed" name="betriebsanweisung_not_needed"
                            aria-label="Checkbox for following text input"  value="true"
                            checked="<?php if($betriebsanweisung_not_needed == true) { echo 'true'; } ?>">
                    </div>
                </div>
                <label for="betriebsanweisung_not_needed" type="text" class="form-control"></label>
            </div>
        </div> -->
        
        <div class="col-12">
            <?php $betriebsanweisung_created_date = get_post_meta($post->ID, 'betriebsanweisung_created_date', true); ?>
            <label for="betriebsanweisung_created_date">Betriebsanweisung erstellt am</label>
            <div class="input-group">
                <input type="date" 
                    class="form-control" 
                    id="betriebsanweisung_created_date" 
                    name="betriebsanweisung_created_date" 
                    value="<?php if($betriebsanweisung_created_date != NULL) { echo $betriebsanweisung_created_date; } ?>">
                <div class="input-group-append">
                    <label for="betriebsanweisung_created_date" class="input-group-text"><img src="<?php echo plugin_dir_url( MS_DM_FILE ) . 'src/assets/calendar.svg' ?>" /></span>
                </div>
            </div>
        </div>

        <div class="col-12">

            <?php 
                wp_enqueue_media(); 
                $betriebsanweisung_attachment_id = get_post_meta($post->ID, 'betriebsanweisung_attachment_id', true);
            ?>

            <label for="input_betriebsanweisung">Betriebsanweisung (Bitte keine unterschriebenen Betriebsanweisungen
                hochladen)</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="kein Dokument ausgewählt"
                    id="input_betriebsanweisung" disabled
                    value="<?php if($betriebsanweisung_attachment_id != NULL) { echo  get_the_title( $betriebsanweisung_attachment_id ); } ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button_betriebsanweisung">Dokument
                        wählen</button>
                </div>
            </div>
            <input type='hidden' name='betriebsanweisung_attachment_id' id='betriebsanweisung_attachment_id'
                value='<?php echo $betriebsanweisung_attachment_id ?>'>
        </div>

        
        
        



        <?php 	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>

        <script type='text/javascript'>



            jQuery(document).ready(function ($) {
                
                console.log("test");

                $('#betriebsanweisung_not_needed').on('change', function(event) {
                    console.log('changed', event.target.checked);
                    $('#betriebsanweisung_created_date').attr('disabled', event.target.checked);
                    $('#button_betriebsanweisung').attr('disabled', event.target.checked);
                })
                
                
                // Uploading files
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

                jQuery('#button_betriebsanweisung').on('click', function (event) {
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param('post_id', set_to_post_id);
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select a image to upload',
                        button: {
                            text: 'Use this image',
                        },
                        multiple: false	// Set to true to allow multiple files to be selected
                    });
                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();
                        // Do something with attachment.id and/or attachment.url here
                        $('#input_betriebsanweisung').val(attachment.title);
                        console.log('attachment', attachment);
                        $('#betriebsanweisung_attachment_id').val(attachment.id);
                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                    // Finally, open the modal
                    file_frame.open();
                });
                // Restore the main ID when the add media button is pressed
                jQuery('a.add_media').on('click', function () {
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
            });
        </script>

    </div>
</div>