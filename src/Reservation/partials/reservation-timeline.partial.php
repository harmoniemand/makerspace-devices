<div class="wrap">
    <h1 class="wp-heading-inline">Reservierungen - POS</h1>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">



    <div class="" style="position: relative; display: block; width: 100%; border: solid 1px #222; min-height: 50px; padding: 0.2rem;">
        <div style="display: flex;">
            <div style="width: 100%;">15:00</div>
            <div style="width: 100%;">16:00</div>
            <div style="width: 100%;">17:00</div>
            <div style="width: 100%;">18:00</div>
            <div style="width: 100%;">19:00</div>
            <div style="width: 100%;">20:00</div>
            <div style="width: 100%;">21:00</div>
        </div>

        <?php
        $tds = get_datetime();
        $tds->setTime(15, 0, 0);
        $current_time_margin = 0.23 * ((get_datetime()->getTimestamp() - $tds->getTimestamp()) / 60);
        ?>
        <div title="now" style="width: 5px; background: rgba(161, 198, 57, 0.6); position: absolute; top: 0; bottom: 0; left: <?php echo $current_time_margin ?>%;"></div>


        <?php foreach ($reservations as $r) : ?>
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

            $margin_left = ($r_from->format('H') - 15) * 14.2;
            $width = (($r_to->format('H') + 1) - $r_from->format('H')) * 14.2;

            ?>

            <div style="margin-top: 0.2rem; margin-left: <?php echo $margin_left ?>%; width: <?php echo $width ?>%; border: solid 1px red; padding: 0.3rem;">
                <?php echo $r_user->user_firstname ?>
                <?php echo $r_user->user_lastname  ?>
                (<?php echo $r_user->user_login  ?>)
            </div>

        <?php endforeach; ?>

    </div>

</div>