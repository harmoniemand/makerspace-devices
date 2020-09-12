<?php
global $post;

$entry = (object) array(
    "uploaded_at" => get_datetime(),
    "attachment_id" => 707,
    "uploaded_by" => get_current_user_id()
);

// add_post_meta(
//     $post->ID,
//     "security_instruction_attachment",
//     $entry
// );

$gfbu_attachments = get_post_meta(
    $post->ID,
    "security_instruction_attachment"
);


?>

<div class="ml-2 mb-1 pt-2 pb-2">




    <input id="upload_image_button" type="button" class="page-title-action" value="<?php _e('Upload image'); ?>" />
    <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>




</div>


<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                <a href="">
                    <span><?php echo _("Hochgeladen am") ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                <a href="">
                    <span><?php echo _("Hochgeladen von") ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                <a href="">
                    <span><?php echo _("Datei") ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
    </thead>

    <tbody id="the-list">


        <?php foreach ($gfbu_attachments as $gfbu) : ?>
            <tr class="iedit author-self level-0 type-page status-publish hentry">

                <td class="title column-title has-row-actions column-primary page-title" data-colname="<?php echo _("Hochgeladen am") ?>">
                    <span><?php echo $gfbu->uploaded_at->format("h:i d.m.Y") ?></span>

                    <div class="hidden">
                        <div class="post_title">Dateiname</div>
                    </div>

                    <button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button>
                </td>

                <td class="" data-colname="<?php echo _("Hochgeladen von") ?>">
                    <?php echo $gfbu->uploaded_by ?>
                </td>

                <td class="" data-colname="<?php echo _("Datei") ?>">
                    <?php echo $gfbu->attachment_id ?>
                </td>

            </tr>
        <?php endforeach; ?>

    </tbody>

</table>
