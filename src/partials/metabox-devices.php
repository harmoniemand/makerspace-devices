<?php

require_once MS_DM_DIR . '/src/includes/class-logger.php';

global $post;

// Nonce field to validate form request came from current site
wp_nonce_field( basename( __FILE__ ), 'metabox_devices_nonce' );

MSDM_Logger::Debug("running custom meta box");

?>


<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('makerspace-wordpress-plugin/src/css/bootstrap.css') ?>" />

<div class="fluid-container">

    <!-- Betriebsanweisung -->
    <div class="row">
        <div class="col">

            <?php 
                wp_enqueue_media(); 
                $betriebsanweisung_attachment_id = get_post_meta($post->ID, 'betriebsanweisung_attachment_id', true);
            ?>

            <label for="input_betriebsanweisung">Betriebsanweisung (Bitte keine unterschriebenen Betriebsanweisungen hochladen)</label>
            <div class="input-group mb-3">
                <input type="text" 
                       class="form-control" 
                       placeholder="kein Dokument ausgewählt" 
                       id="input_betriebsanweisung" 
                       disabled 
                       value="<?php if($betriebsanweisung_attachment_id != NULL) { echo  get_the_title( $betriebsanweisung_attachment_id ); } ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button_betriebsanweisung">Dokument wählen</button>
                </div>
            </div>
            <input type='hidden' name='betriebsanweisung_attachment_id' id='betriebsanweisung_attachment_id' value='<?php echo $betriebsanweisung_attachment_id ?>'>
            

            <?php 	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>

            <script type='text/javascript'>
                jQuery( document ).ready( function( $ ) {
                    // Uploading files
                    var file_frame;
                    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                    var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                    jQuery('#button_betriebsanweisung').on('click', function( event ){
                        event.preventDefault();
                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            // Set the post ID to what we want
                            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
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
                        file_frame.on( 'select', function() {
                            // We set multiple to false so only get one image from the uploader
                            attachment = file_frame.state().get('selection').first().toJSON();
                            // Do something with attachment.id and/or attachment.url here
                            $( '#input_betriebsanweisung' ).val( attachment.title );
                            console.log('attachment', attachment);
                            $( '#betriebsanweisung_attachment_id' ).val( attachment.id );
                            // Restore the main post ID
                            wp.media.model.settings.post.id = wp_media_post_id;
                        });
                            // Finally, open the modal
                            file_frame.open();
                    });
                    // Restore the main ID when the add media button is pressed
                    jQuery( 'a.add_media' ).on( 'click', function() {
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                });
            </script>

        </div>
    </div>
    

    <!-- Datenblatt -->
    <div class="row">
        <div class="col">

            <?php 
                wp_enqueue_media(); 
                $datenblatt_attachment_id = get_post_meta($post->ID, 'datenblatt_attachment_id', true);
            ?>

            <label for="input_datenblatt">Datenblatt</label>
            <div class="input-group mb-3">
                <input type="text" 
                       class="form-control" 
                       placeholder="kein Dokument ausgewählt" 
                       id="input_datenblatt" 
                       disabled
                       value="<?php if($datenblatt_attachment_id != NULL) { echo  get_the_title( $datenblatt_attachment_id ); } ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button_datenblatt">Dokument wählen</button>
                </div>
            </div>
            <input type='hidden' name='datenblatt_attachment_id' id='datenblatt_attachment_id' value='<?php echo $datenblatt_attachment_id ?>'>
            

            <?php 	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>

            <script type='text/javascript'>
                jQuery( document ).ready( function( $ ) {
                    // Uploading files
                    var file_frame;
                    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                    var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                    jQuery('#button_datenblatt').on('click', function( event ){
                        event.preventDefault();
                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            // Set the post ID to what we want
                            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
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
                        file_frame.on( 'select', function() {
                            // We set multiple to false so only get one image from the uploader
                            attachment = file_frame.state().get('selection').first().toJSON();
                            // Do something with attachment.id and/or attachment.url here
                            $( '#input_datenblatt' ).val( attachment.title );
                            console.log('attachment', attachment);
                            $( '#datenblatt_attachment_id' ).val( attachment.id );
                            // Restore the main post ID
                            wp.media.model.settings.post.id = wp_media_post_id;
                        });
                            // Finally, open the modal
                            file_frame.open();
                    });
                    // Restore the main ID when the add media button is pressed
                    jQuery( 'a.add_media' ).on( 'click', function() {
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                });
            </script>

        </div>
    </div>
    

    <!-- Bedienungsanleitung -->
    <div class="row">
        <div class="col">

            <?php 
                wp_enqueue_media(); 
                $bedienungsanleitung_attachment_id = get_post_meta($post->ID, 'bedienungsanleitung_attachment_id', true);
            ?>

            <label for="input_bedienungsanleitung">Bedienungsanleitung</label>
            <div class="input-group mb-3">
                <input type="text" 
                       class="form-control" 
                       placeholder="kein Dokument ausgewählt" 
                       id="input_bedienungsanleitung" 
                       disabled
                       value="<?php if($bedienungsanleitung_attachment_id != NULL) { echo  get_the_title( $bedienungsanleitung_attachment_id ); } ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button_bedienungsanleitung">Dokument wählen</button>
                </div>
            </div>
            <input type='hidden' name='bedienungsanleitung_attachment_id' id='bedienungsanleitung_attachment_id' value='<?php echo $bedienungsanleitung_attachment_id ?>'>
            

            <?php 	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>

            <script type='text/javascript'>
                jQuery( document ).ready( function( $ ) {
                    // Uploading files
                    var file_frame;
                    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                    var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                    jQuery('#button_bedienungsanleitung').on('click', function( event ){
                        event.preventDefault();
                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            // Set the post ID to what we want
                            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
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
                        file_frame.on( 'select', function() {
                            // We set multiple to false so only get one image from the uploader
                            attachment = file_frame.state().get('selection').first().toJSON();
                            // Do something with attachment.id and/or attachment.url here
                            $( '#input_bedienungsanleitung' ).val( attachment.title );
                            console.log('attachment', attachment);
                            $( '#bedienungsanleitung_attachment_id' ).val( attachment.id );
                            // Restore the main post ID
                            wp.media.model.settings.post.id = wp_media_post_id;
                        });
                            // Finally, open the modal
                            file_frame.open();
                    });
                    // Restore the main ID when the add media button is pressed
                    jQuery( 'a.add_media' ).on( 'click', function() {
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                });
            </script>

        </div>
    </div>
</div>