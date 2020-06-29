<?php if ($error != "") : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-danger" role="alert" style="padding: 8px 12px; width: 100%;">
                <?php echo $error ?>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="container-fluid">
    <form method="POST" action="?<?php echo http_build_query($url_data) ?>">

        <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

        <div class="row mt-3" style="max-width: 100%;">
            <div class="col">
                <h1 class="wp-heading-inline" style="font-size: 23px;">POS</h1>
            </div>
        </div>


        <div class="row">

            <div class="col">
                <?php
                $d = (clone $url_data);
                $d->tab = "reserved";
                $url = http_build_query($d);
                ?>
                <a href="?<?php echo $url ?>" type="button" class="btn btn<?php echo $url_data->tab == "all" ? "-outline" : "" ?>-primary w-100">
                    Angemeldete User
                </a>
            </div>

            <div class="col" style="margin-right: 30px;">
                <?php
                $d = (clone $url_data);
                $d->tab = "all";
                $url = http_build_query($d);
                ?>
                <a href="?<?php echo $url ?>" type="button" class="btn btn<?php echo $url_data->tab == "reserved" ? "-outline" : "" ?>-primary w-100">
                    Alle User
                </a>
            </div>

        </div>

        <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
            <div class="col-12">
                <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
                    <div class="card-body">

                        <?php if ($url_data->tab != "all") : ?>

                            <div class="row">

                                <?php
                                $d = (clone $url_data);
                                $d->offset = $d->offset - 1;
                                $url = http_build_query($d);
                                ?>
                                <a href="?<?php echo $url ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                                    <clr-icon shape="angle" size="36" dir="up"></clr-icon>
                                </a>

                                <a href="?<?php echo $url ?>" class="d-none d-md-flex flex-md-column justify-content-center">
                                    <clr-icon shape="angle" size="36" dir="left" class=""></clr-icon>
                                </a>

                                <div class="col d-flex justify-content-center">
                                    <h3>
                                        <span class=""> <?php echo dayToString($day->date->format('w')); ?></span>
                                        <span class="ml-3"> <?php echo $day->date->format('d.m.'); ?></span>
                                    </h3>
                                </div>


                                <?php
                                $d = (clone $url_data);
                                $d->offset = $d->offset + 1;
                                $url = http_build_query($d);
                                ?>
                                <a href="=?<?php echo $url ?>" class="w-100 d-md-none d-flex flex-md-column justify-content-center">
                                    <clr-icon shape="angle" size="36" dir="down"></clr-icon>
                                </a>

                                <a href="?<?php echo $url ?>" class="d-none d-md-flex flex-md-column justify-content-center">
                                    <clr-icon shape="angle" size="36" dir="right" class=""></clr-icon>
                                </a>
                            </div>


                        <?php else : ?>


                        <?php endif; ?>

                        <div class="row d-none d-md-flex">

                            <div class="col-2 font-weight-bold">
                                <span class="mr-2">Vorname</span>
                            </div>
                            <div class="col-2 font-weight-bold">
                                <span class="mr-2">Nachname</span>
                            </div>
                            <div class="col-2 font-weight-bold">
                                <span class="mr-2">Username</span>
                            </div>
                            <div class="col-2 font-weight-bold">
                                <span class="mr-2">Von - Bis</span>
                            </div>
                            <div class="col-1 font-weight-bold">
                                <span class="mr-2" title="Sicherheitsunterweisung">SU</span>
                            </div>
                            <div class="col-1 font-weight-bold">
                                <span class="mr-2" style="font-size: 0.7rem;">Anschrift</span>
                            </div>
                            <div class="col-2 font-weight-bold"></div>
                        </div>



                        <?php foreach ($reservations as $r) : ?>
                            <div class="row mb-2 mt-4 mt-md-0 bg-alternating p-2 p-md-0">
                                <?php

                                $r_user = get_userdata($r->mar_user_id);
                                $r_from = new DateTime();
                                $r_from->setTimestamp($r->mar_from);
                                $r_to = new DateTime();
                                $r_to->setTimestamp($r->mar_to);

                                $is_here = false;
                                $mpl_sql = "SELECT * FROM makerspace_presence_logs WHERE mpl_datetime BETWEEN  %s AND %s AND mpl_user_id = %d";
                                $mpl_entries = $wpdb->get_results($wpdb->prepare(
                                    $mpl_sql,
                                    $day->start->format("Y-m-d H:i:s"),
                                    $day->end->format("Y-m-d H:i:s"),
                                    $r->mar_user_id
                                ));

                                $disable_create_log = "disabled";
                                if (get_datetime()->format("Y-m-d") == $day->start->format("Y-m-d")  || $url_data->tab == "all") {
                                    $disable_create_log = "";
                                }

                                ?>


                                <div class="col-4 col-md-2">
                                    <span class="">
                                        <?php echo $r_user->user_firstname ?>
                                    </span>
                                </div>
                                <div class="col-4 col-md-2">
                                    <span class="">
                                        <?php echo $r_user->user_lastname  ?>
                                    </span>
                                </div>
                                <div class="col-4 col-md-2">
                                    <span class="">
                                        <?php echo $r_user->user_login  ?>
                                    </span>
                                </div>

                                <div class="col-6 col-md-2">
                                    <span class=""><?php echo $r_from->format('H:i') ?></span>
                                    -
                                    <span class=""><?php echo $r_to->format('H:i') ?></span>
                                </div>


                                <div class="col-3 col-md-1">
                                    <span class="">

                                        <?php if (get_user_meta($r->mar_user_id, 'ms_user_corona_safetyinstruction', false)) : ?>
                                            <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_safetyinstruction" name="ms_user_corona_safetyinstruction" value="<?php echo $r->mar_user_id ?>">
                                                <clr-icon shape="check"></clr-icon>
                                            </button>
                                        <?php else : ?>
                                            <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_safetyinstruction" name="ms_user_corona_safetyinstruction" value="<?php echo $r->mar_user_id ?>">
                                                <clr-icon shape="times"></clr-icon>
                                            </button>
                                        <?php endif; ?>

                                    </span>
                                </div>
                                <div class="col-3 col-md-1">
                                    <span class="">

                                        <?php if (get_user_meta($r->mar_user_id, 'ms_user_corona_adress', false)) : ?>
                                            <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_adress" name="ms_user_corona_adress" value="<?php echo $r->mar_user_id ?>">
                                                <clr-icon shape="check"></clr-icon>
                                            </button>
                                        <?php else : ?>
                                            <button type="submit" class="btn btn-link btn-sm" id="ms_user_corona_adress" name="ms_user_corona_adress" value="<?php echo $r->mar_user_id ?>">
                                                <clr-icon shape="times"></clr-icon>
                                            </button>
                                        <?php endif; ?>

                                    </span>
                                </div>

                                <div class="col-12 col-md-2">
                                    <?php if (count($mpl_entries) % 2 == 0) : ?>
                                        <button type="submit" class="btn btn-outline-dark btn-sm w-100" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                            <clr-icon shape="login"></clr-icon>
                                            kommen
                                        </button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-outline-dark btn-sm w-100" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                            <clr-icon shape="logout"></clr-icon>
                                            gehen
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>

        </div>

    </form>
</div>