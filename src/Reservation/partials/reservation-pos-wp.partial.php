<div class="wrap">
    <h1 class="wp-heading-inline">Reservierungen - POS</h1>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">


    <h2 class="screen-reader-text">Seitenliste filtern</h2>
    <ul class="subsubsub">
        <li class="all"><a href="edit.php?post_type=page&amp;all_posts=1" class="current" aria-current="page">Nach Tag <span class="count">(20)</span></a> |</li>
        <li class="mine"><a href="edit.php?post_type=page&amp;author=3">Alle Besucherenden <span class="count">(16)</span></a></li>
    </ul>

    <form method="POST" action="?<?php echo http_build_query($url_data) ?>">
        <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Seiten durchsuchen:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Seiten durchsuchen"></p>

        <input type="hidden" name="post_status" class="post_status_page" value="all">
        <input type="hidden" name="post_type" class="post_type_page" value="page">

        <div class="tablenav top">

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
            <div class="tablenav-pages one-page"><span class="displaying-num">20 Einträge</span>
                <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Aktuelle Seite</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> von <span class="total-pages">1</span></span></span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span></div>
            <br class="clear">
        </div>
        <h2 class="screen-reader-text">Seitenliste</h2>
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox">
                    </td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc">
                            <span>Name</span><span class="sorting-indicator"></span>
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

                    ?>

                    <tr id="post-3222" class="iedit author-self level-0 post-3222 type-page status-publish hentry">
                        <th scope="row" class="check-column">
                            <label class="screen-reader-text" for="cb-select-3222">
                                <?php echo $r_user->user_firstname ?>
                                <?php echo $r_user->user_lastname  ?>
                                (<?php echo $r_user->user_login  ?>)
                            </label>
                            <input id="cb-select-3222" type="checkbox" name="post[]" value="3222">
                            <div class="locked-indicator">
                                <span class="locked-indicator-icon" aria-hidden="true"></span>
                                <span class="screen-reader-text">
                                    „Aktuell befinden sich“ ist gesperrt </span>
                            </div>
                        </th>
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                            <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                            <strong>
                                <a class="row-title" href="/wp-admin/post.php?post=3222&amp;action=edit" aria-label="„Aktuell befinden sich“ (Bearbeiten)">
                                    <?php echo $r_user->user_firstname ?>
                                    <?php echo $r_user->user_lastname  ?>
                                    (<?php echo $r_user->user_login  ?>)
                                </a>
                            </strong>

                            <div class="row-actions"><span class="edit"><a href="/wp-admin/post.php?post=3222&amp;action=edit" aria-label="„Aktuell befinden sich“ bearbeiten">Bearbeiten</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Schnellanpassung für „Aktuell befinden sich“ (inline)" aria-expanded="false">QuickEdit</button> | </span><span class="trash"><a href="/wp-admin/post.php?post=3222&amp;action=trash&amp;_wpnonce=378bf7b306" class="submitdelete" aria-label="„Aktuell befinden sich“ in den Papierkorb verschieben">Papierkorb</a> | </span><span class="view"><a href="/ds-corona-anwesende/" rel="bookmark" aria-label="„Aktuell befinden sich“ ansehen">Anschauen</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button>
                        </td>
                        <td class="author column-author" data-colname="Autor">
                            <span class=""><?php echo $r_from->format('H:i') ?></span>
                            -
                            <span class=""><?php echo $r_to->format('H:i') ?></span>
                        </td>

                        <td class="comments column-comments" data-colname="Kommentare">
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
                        </td>

                        <td class="comments column-comments" data-colname="Kommentare">
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
                        </td>

                        <td class="date column-date" data-colname="Datum">
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
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox"></td>
                    <th scope="col" class="manage-column column-title column-primary sortable desc"><a href="/wp-admin/edit.php?post_type=page&amp;orderby=title&amp;order=asc"><span>Titel</span><span class="sorting-indicator"></span></a></th>
                    <th scope="col" class="manage-column column-author">Autor</th>
                    <th scope="col" id="security_instructions" class="manage-column column-comments num sortable desc">
                        <?php echo __("SU") ?>
                    </th>
                    <th scope="col" id="address" class="manage-column column-comments num sortable desc">
                        <?php echo __("Anschrift") ?>
                    </th>
                    <th scope="col" class="manage-column column-date sortable asc"><a href="/wp-admin/edit.php?post_type=page&amp;orderby=date&amp;order=desc"><span>Datum</span><span class="sorting-indicator"></span></a></th>
                </tr>
            </tfoot>

        </table>

        <div class="tablenav bottom">

            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page"><span class="displaying-num">20 Einträge</span>
                <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <span class="screen-reader-text">Aktuelle Seite</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 von <span class="total-pages">1</span></span></span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span></div>
            <br class="clear">
        </div>

    </form>



    <br class="clear">
</div>