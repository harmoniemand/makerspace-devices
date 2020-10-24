<div class="wrap">

    <div class="d-flex justify-content-between">
        <h1 class="wp-heading-inline">Check-In</h1>

        <h1 class="wp-heading-inline">
            Aktuell befinden sich <?php echo $viewmodel->present_total_count ?> Personen im Maker Space
        </h1>
    </div>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">



    <h2 class="screen-reader-text">Seitenliste filtern</h2>
    <ul class="subsubsub">
        <?php
        $d = (clone $url_data);
        $d->tab = "reserved";
        $url = http_build_query($d);
        $current = $url_data->tab == "reserved" ? "current" : "";
        ?>
        <li class="all">
            <a href="?<?php echo $url ?>" class="<?php echo $current ?>" aria-current="page">
                Anmeldungen<span class="count"> (<?php echo count($viewmodel->reserved) ?>)</span>
            </a> |
        </li>

        <?php
        $d = (clone $url_data);
        $d->tab = "present";
        $url = http_build_query($d);
        $current = $url_data->tab == "present" ? "current" : "";
        ?>
        <li class="all">
            <a href="?<?php echo $url ?>" class="<?php echo $current ?>" aria-current="page">
                Alle Anwesenden<span class="count"> (<?php echo count($viewmodel->present) ?>)</span>
            </a> |
        </li>


        <?php
        $d = (clone $url_data);
        $d->tab = "all";
        $url = http_build_query($d);
        $current = $url_data->tab == "all" ? "current" : "";
        ?>
        <li class="all"><a href="?<?php echo $url ?>" class="<?php echo $current ?>">Alle User<span class="count"> (<?php echo count(get_users()) ?>)</span></a></li>
    </ul>

    <form method="POST" action="?<?php echo http_build_query($url_data) ?>">
        <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

        <!-- <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Seiten durchsuchen:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Seiten durchsuchen"></p> -->

        <!-- <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text">Mehrfachaktion wählen</label><select name="action" id="bulk-action-selector-top">
                    <option value="-1">Mehrfachaktionen</option>
                    <option value="edit" class="hide-if-no-js">Bearbeiten</option>
                    <option value="trash">In den Papierkorb verschieben</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Übernehmen">
            </div>
            <div class="alignleft actions">
                <label for="filter-by-date" class="screen-reader-text">Nach Datum filtern</label>
                <select name="m" id="filter-by-date">
                    <option selected="selected" value="0">Alle Daten</option>
                    <option value="202006">Juni 2020</option>
                    <option value="202005">Mai 2020</option>
                    <option value="202004">April 2020</option>
                    <option value="202003">März 2020</option>
                    <option value="201911">November 2019</option>
                    <option value="201909">September 2019</option>
                    <option value="201905">Mai 2019</option>
                    <option value="201904">April 2019</option>
                    <option value="201901">Januar 2019</option>
                </select>
                <input type="submit" name="filter_action" id="post-query-submit" class="button" value="Auswahl einschränken"> </div>
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($reservations) ?> Einträge</span>
                
            <br class="clear">
        </div> -->
        <h2 class="screen-reader-text">Seitenliste</h2>
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <?php
                        $d = (clone $url_data);
                        $d->orderby = "first_name";
                        $url = http_build_query($d);
                        ?>
                        <a href="?<?php echo $url ?>">
                            <span>Vorname</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <?php
                        $d = (clone $url_data);
                        $d->orderby = "last_name";
                        $url = http_build_query($d);
                        ?>
                        <a href="?<?php echo $url ?>">
                            <span>Nachname</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <?php
                        $d = (clone $url_data);
                        $d->orderby = "user_name";
                        $url = http_build_query($d);
                        ?>
                        <a href="?<?php echo $url ?>">
                            <span>Username</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="author" class="manage-column column-author">Von - Bis</th>
                    <th scope="col" id="security_instructions" class="manage-column column-comments num sortable desc">
                        <?php echo __("SU") ?>
                    </th>
                    <th scope="col" id="address" class="manage-column column-comments num sortable desc">
                        <?php echo __("Anschrift") ?>
                    </th>
                    <th scope="col" id="date" class="manage-column column-date sortable asc">
                        <?php echo __("Aktionen") ?>
                    </th>
                </tr>
            </thead>

            <tbody id="the-list">


                <?php if (count($viewmodel->visitors) > 0) : ?>
                    <tr class="iedit author-self level-0 type-page status-publish hentry">

                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname" colspan="7">
                            <span style="font-weight: bold;"><?php echo _("Gäste") ?></span>
                        </td>

                    </tr>

                    <?php foreach ($viewmodel->visitors as $temp) : ?>


                        <tr class="iedit author-self level-0 type-page status-publish hentry">

                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname" colspan="3">
                                <span>
                                    <?php echo $temp->mpl_temp_visitor_name ?>
                                    <?php echo $temp->temp_visitor_address ?>
                                </span>
                            </td>

                            <td class="" data-colname="Von - Bis" colspan="3">

                            </td>



                            <td class="" data-colname="Aktionen">
                                <button type="submit" class="btn btn-link-dark btn-sm" id="mpl_temp_visitor_id" name="mpl_temp_visitor_id" value="<?php echo $temp->mpl_temp_visitor_id ?>">
                                    <clr-icon shape="login"></clr-icon>
                                    gehen
                                </button>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    <tr class="iedit author-self level-0 type-page status-publish hentry">

                        <td class="title column-title has-row-actions column-primary page-title" style="border-top: solid 2px #000;" data-colname="Vorname" colspan="7">
                            <span style="font-weight: bold;"><?php echo _("Besuchende") ?></span>
                        </td>

                    </tr>

                <?php endif; ?>



                <?php foreach ($viewmodel->table_data as $r) : ?>

                    <?php

                    // print_r($r);

                    $r_user = get_userdata($r->mar_user_id);
                    $r_from = new DateTime();
                    if (strpos($r->mar_from, "-") != false) {
                        $r_from = new DateTime($r->mar_from);
                    } else {
                        $r_from->setTimestamp($r->mar_from);
                    }

                    $r_to = new DateTime();
                    if (strpos($r->mar_to, "-") != false) {
                        $r_to = new DateTime($r->mar_to);
                    } else {
                        $r_to->setTimestamp($r->mar_to);
                    }

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

                    <tr class="iedit author-self level-0 type-page status-publish hentry">

                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname">
                            <span><?php echo $r_user->user_firstname ?></span>
                            <span class="hidden"><?php echo $r_user->user_lastname ?></span>

                            <div class="hidden" id="inline_3240">
                                <div class="post_title"><?php echo $r_user->user_lastname  ?></div>
                                <div class="post_name"><?php echo $r_user->user_login  ?></div>
                            </div>

                            <button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button>
                        </td>

                        <td class="" data-colname="Nachname">
                            <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                            <?php echo $r_user->user_lastname  ?>

                            <!-- <div class="row-actions"><span class="edit"><a href="/wp-admin/post.php?post=3222&amp;action=edit" aria-label="„Aktuell befinden sich“ bearbeiten">Bearbeiten</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Schnellanpassung für „Aktuell befinden sich“ (inline)" aria-expanded="false">QuickEdit</button> | </span><span class="trash"><a href="/wp-admin/post.php?post=3222&amp;action=trash&amp;_wpnonce=378bf7b306" class="submitdelete" aria-label="„Aktuell befinden sich“ in den Papierkorb verschieben">Papierkorb</a> | </span><span class="view"><a href="/ds-corona-anwesende/" rel="bookmark" aria-label="„Aktuell befinden sich“ ansehen">Anschauen</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button> -->
                        </td>
                        <td class="" data-colname="Username">
                            <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                            <strong>
                                <a class="row-title" href="/wp-admin/admin.php?page=ms_users_detail&user_id=<?php echo $r->mar_user_id ?>" aria-label="„Aktuell befinden sich“ (Bearbeiten)">
                                    <?php echo $r_user->user_login  ?>
                                </a>
                            </strong>
                        </td>
                        <td class="" data-colname="Von - Bis">
                            <span class=""><?php echo $r_from->format('H:i') ?></span>
                            -
                            <span class=""><?php echo $r_to->format('H:i') ?></span>
                        </td>

                        <td class="" data-colname="Kontakt" colspan="2">
                            <span class="">

                                <?php
                                include_once dirname(__FILE__) . "/../../Repositories/UserRepository.php";
                                $u = (new UserRepository())->Read($r->mar_user_id);
                                ?>

                                <?php if (!empty($u->phone)) : ?>
                                    <clr-icon title="phone" shape="check"></clr-icon>
                                <?php else : ?>
                                    <clr-icon title="phone" shape="times"></clr-icon>
                                <?php endif; ?>

                                <?php if (!empty($u->email)) : ?>
                                    <clr-icon title="email" shape="check"></clr-icon>
                                <?php else : ?>
                                    <clr-icon title="email" shape="times"></clr-icon>
                                <?php endif; ?>

                                <?php if (count($u->address) > 0) : ?>
                                    <clr-icon title="address" shape="check"></clr-icon>
                                <?php else : ?>
                                    <clr-icon title="address" shape="times"></clr-icon>
                                <?php endif; ?>
                            </span>
                        </td>

                        <td class="" data-colname="Aktionen">
                            <?php if (count($mpl_entries) % 2 == 0) : ?>
                                <button type="submit" class="btn btn-link-dark btn-sm" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                    <clr-icon shape="login"></clr-icon>
                                    kommen
                                </button>
                            <?php else : ?>
                                <button type="submit" class="btn btn-link-dark btn-sm" id="mp_create_log" name="mp_create_log" value="<?php echo $r->mar_user_id ?>" <?php echo $disable_create_log ?>>
                                    <clr-icon shape="logout"></clr-icon>
                                    gehen
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc">
                            <span>Vorname</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc">
                            <span>Nachname</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc">
                            <span>Username</span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="author" class="manage-column column-author">Von - Bis</th>
                    <th scope="col" id="security_instructions" class="manage-column column-comments num sortable desc">
                        <?php echo __("SU") ?>
                    </th>
                    <th scope="col" id="address" class="manage-column column-comments num sortable desc">
                        <?php echo __("Anschrift") ?>
                    </th>
                    <th scope="col" id="date" class="manage-column column-date sortable asc">
                        <?php echo __("Aktionen") ?>
                    </th>
                </tr>
            </tfoot>

        </table>

        <div class="tablenav bottom">

            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($viewmodel->table_data) ?> Einträge</span>
                <br class="clear">
            </div>

    </form>



    <br class="clear">
</div>