<?php
global $post;

$devices = get_posts(array(
    'post_type'         => 'devices',
    'posts_per_page'    =>  -1,
    'orderby'           => 'title',
    'order'              => 'ASC',
    // 'tax_query' => array(
    //     array(
    //         'taxonomy' => 'ms_devices_workshop',
    //         'field' => 'term_id',
    //         'terms' => 174,
    //     )
    // )
));

$gfbu_media = get_posts(array(
    'post_type'         => 'attachment',
    'posts_per_page'    =>  -1,
    'orderby'           => 'title',
    'order'              => 'ASC'
));


$security_instruction_device_id = get_post_meta(
    $post->ID,
    "security_instruction_device_id"
);


?>



<?php
//  print_r($devices) 
?>


<div class="form-group row">
    <label for="my_calendar_url" class="col-sm-2 col-form-label"><?php echo __('Gerät') ?></label>
    <div class="col-sm-10">
        <select id="security_instruction_device_id" name="security_instruction_device_id" class="w-100">
            <?php if ($security_instruction_device_id == false) : ?>
                <option value="0" selected>keines</option>
            <?php else : ?>
                <option value="0">keines</option>
            <?php endif; ?>

            <?php foreach ($devices as $device) : ?>
                <?php if ($security_instruction_device_id == $device->ID) : ?>
                    <option value="<?php echo $device->ID ?>" selected><?php echo $device->post_title ?></option>
                <?php else : ?>
                    <option value="<?php echo $device->ID ?>"><?php echo $device->post_title ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
</div>