<?php

$labs = get_terms(array(
    'taxonomy' => 'ms_devices_workshop',
    'hide_empty' => false,
    'parent' => 174,
    'orderby' => 'name',
    'order' => 'ASC'
));


?>


<div class="row mt-3" style="max-width: 100%;">
    <div class="col">
        <h1 class="wp-heading-inline" style="font-size: 23px;"><?php echo __('Meine Sicherheitsunterweisungen') ?></h1>
    </div>
</div>

<div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
    <div class="col mt-3">
        <?php foreach ($labs as $lab) : ?>
            <div class="mb-4">
                <h5 class="card-title"><?php echo $lab->name; ?></h5>

                <?php
                $devices = get_posts(array(
                    'post_type' => 'devices',
                    'numberposts' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'ms_devices_workshop',
                            'field' => 'id',
                            'terms' => $lab->term_id
                        )
                    )
                ));
                ?>


                <div class="mse-securityinstructions-device">
                    <?php foreach ($devices as $device) : ?>
                        <div class="mse-securityinstructions-entry">
                            <div>
                                <clr-icon shape="angle" dir="down"></clr-icon>
                            </div>
                            <div>
                                <span><?php echo get_the_title($device->ID) ?></span>
                            </div>
                            <div>
                                <clr-icon shape="check-circle" class="is-success" title="Sicherheitsunterweisung gültig"></clr-icon>
                            </div>
                            <div>
                                <span>21.3.2020 20:00</span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="col-3">
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