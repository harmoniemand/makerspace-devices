<div class="wrap">
    <h1 class="wp-heading-inline">Reservierungen - POS</h1>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">

    <h2 class="screen-reader-text">Seitenliste filtern</h2>
    <ul class="subsubsub">
        <li class="all">
            <a>
                Angemeldet <span class="count">(<?php echo count($viewmodel->table_data) ?>)</span> |
            </a>
        </li>

        <li class="mine">
            <a>
                Anwesend <span class="count">()</span>
            </a>
        </li>
    </ul>



    <div class="" style="clear: both; position: relative; display: block; width: 100%; border: solid 1px #222; min-height: 50px; min-height: 80vh; padding-top: 50px;">
        <div style="display: flex; height: 100%; position: absolute; left: 0; right: 0; top: 0;">
            <div style="padding: .5rem; width: 100%; height: 100%; background: rgba(0,0,0,0.1);">15:00</div>
            <div style="padding: .5rem; width: 100%;">16:00</div>
            <div style="padding: .5rem; width: 100%; height: 100%; background: rgba(0,0,0,0.1);">17:00</div>
            <div style="padding: .5rem; width: 100%;">18:00</div>
            <div style="padding: .5rem; width: 100%; height: 100%; background: rgba(0,0,0,0.1);">19:00</div>
            <div style="padding: .5rem; width: 100%;">20:00</div>
            <div style="padding: .5rem; width: 100%; height: 100%; background: rgba(0,0,0,0.1);">21:00</div>
        </div>

        <?php
        $tds = get_datetime();
        $tds->setTime(15, 0, 0);
        $current_time_margin = 0.23 * ((get_datetime()->getTimestamp() - $tds->getTimestamp()) / 60);
        ?>
        <div title="now" style="width: 5px; background: rgba(161, 198, 57, 0.6); position: absolute; top: 0; bottom: 0; left: <?php echo $current_time_margin ?>%;"></div>


        <?php foreach ($viewmodel->table_data as $r) : ?>
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

            $margin_left = ($r_from->format('H') - 15) * 14.28;
            $width = (($r_to->format('H') + 1) - $r_from->format('H')) * 14.28;

            $color = get_user_meta($r->mar_user_id, 'visitor_color', true);
            if (empty($color)) {
                $color = get_option("makerspace_visitor_default_color");
            }

            $border_color = $color;

            $color = str_replace("#", "", $color);
            $background_color = "rgba(" . hexdec(substr($color, 0, 2)) . "," . hexdec(substr($color, 2, 2)) . "," . hexdec(substr($color, 4, 2)) . ",0.2)";

            ?>

            <div style="position: relative; margin-top: .4rem; margin-left: <?php echo $margin_left ?>%; width: <?php echo $width ?>%; background: <?php echo $background_color ?>; border: solid 1px <?php echo $border_color ?>; padding: 0.3rem;">
                <?php echo $r_user->user_firstname ?>
                <?php echo $r_user->user_lastname  ?>
                (<?php echo $r_user->user_login  ?>)

                <?php if (count($mpl_entries) > 0) : ?>
                    <?php if (count($mpl_entries) % 2 == 0) : ?>
                        <clr-icon shape="logout-circle" size="28" title="gegangen"></clr-icon>
                    <?php else : ?>
                        <clr-icon shape="check-circle" size="28" class="is-success" title="anwesend"></clr-icon>
                    <?php endif; ?>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</div>