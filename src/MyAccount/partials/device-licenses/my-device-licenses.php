<?php

$labs = get_terms(array(
    'taxonomy' => 'ms_devices_workshop',
    'hide_empty' => true,
    'parent' => 0,
    'orderby' => 'name',
    'order' => 'ASC'
));

$uid = get_current_user_id();

// $val = (object) array(
//     "created_by" => get_current_user_id(),
//     "date" => get_datetime()
// );

// add_user_meta($uid, "security_instruction_for_3304", $val);

?>




<div class="row mt-3" style="max-width: 100%;">
    <div class="col">
        <h1 class="wp-heading-inline" style="font-size: 23px;"><?php echo __('Meine Sicherheitsunterweisungen') ?></h1>
    </div>
</div>





<div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
    <div class="col">

        <?php foreach ($labs as $lab) : ?>

            <?php

            $devices = get_posts(array(
                'post_type' => 'securityinstructions',
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'ms_devices_workshop',
                        'field' => 'id',
                        'terms' => $lab->term_id
                    )
                )
            ));

            if (count($devices)) :

            ?>
                <div class="card wp-settings p-0" style="border-radius: 0; padding: 8px 12px;">
                    <div class="card-body p-0">

                        <div class="d-flex pl-3 pr-3 pt-2 pb-0">
                            <h5 class="card-title"><?php echo $lab->name; ?></h5>
                        </div>


                        <?php foreach ($devices as $device) : ?>

                            <?php

                            $licenses = get_user_meta($uid, "security_instruction_for_" . $device->ID);

                            ?>


                            <div class="mse-securityinstructions-entry border border-left-0 border-right-0 pl-2">
                                <div class="d-flex c-pointer pb-1 pt-1" data-toggle="collapse" data-target="#<?php echo "security_instruction_for_" . $device->ID ?>" aria-expanded="false" aria-controls="<?php echo "security_instruction_for_" . $device->ID ?>">
                                    <div class="mr-2">
                                        <clr-icon shape="angle" dir="down"></clr-icon>
                                    </div>
                                    <div>
                                        <span><?php echo get_the_title($device->ID) ?></span>
                                    </div>

                                    <?php if (count($licenses) > 0) : ?>
                                        <div class="ml-3">
                                            <clr-icon shape="check-circle" class="is-success" title="Sicherheitsunterweisung gültig"></clr-icon>
                                        </div>
                                    <?php endif; ?>

                                    <div class="ml-auto">
                                        <a href="<?php echo get_permalink($device->ID) ?>" class="btn btn-outline-primary btn-sm">
                                            Inhalt ansehen
                                        </a>
                                    </div>
                                </div>

                                <div class="collapse w-100 pl-0" id="<?php echo "security_instruction_for_" . $device->ID ?>">

                                    <ul class="list-group">
                                        <?php foreach ($licenses as $license) : ?>
                                            <li class="list-group-item" style="margin-bottom: 0;">
                                                <div class="row" style="line-height: 24px;">
                                                    <div class="col-sm-3">
                                                        Angelegt von: <?php echo get_userdata($license->created_by)->display_name ?>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        Angelegt am: <?php echo $license->date->format("d.m.Y") ?>
                                                    </div>

                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

    <div class="col col-md-3">
        <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Aktionen</li>
                <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">
                </li>
                <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                </li>
            </ul>
        </div>
    </div>
</div>