<?php

if (!defined('ABSPATH')) {
    die('-1');
}

http_response_code(403);

?>



<div class="wrap">

    <div class="d-flex justify-content-between">
        <h1 class="wp-heading-inline">Besuchende</h1>
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
                Alle User<span class="count"> (<?php echo count($viewmodel->users) ?>)</span>
            </a> |
        </li>

    </ul>

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

            <?php foreach ($viewmodel->users as $user) : ?>

                <?php

                $wp_user = get_userdata($user->mar_user_id);

                ?>

                <tr class="iedit author-self level-0 type-page status-publish hentry">

                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname">
                        <span><?php echo $wp_user->user_firstname ?></span>
                        <span class="hidden"><?php echo $wp_user->user_lastname ?></span>

                        <div class="hidden" id="inline_3240">
                            <div class="post_title"><?php echo $wp_user->user_lastname  ?></div>
                            <div class="post_name"><?php echo $wp_user->user_login  ?></div>
                        </div>

                        <button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button>
                    </td>

                    <td class="" data-colname="Nachname">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <?php echo $wp_user->user_lastname  ?>

                        <!-- <div class="row-actions"><span class="edit"><a href="/wp-admin/post.php?post=3222&amp;action=edit" aria-label="„Aktuell befinden sich“ bearbeiten">Bearbeiten</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Schnellanpassung für „Aktuell befinden sich“ (inline)" aria-expanded="false">QuickEdit</button> | </span><span class="trash"><a href="/wp-admin/post.php?post=3222&amp;action=trash&amp;_wpnonce=378bf7b306" class="submitdelete" aria-label="„Aktuell befinden sich“ in den Papierkorb verschieben">Papierkorb</a> | </span><span class="view"><a href="/ds-corona-anwesende/" rel="bookmark" aria-label="„Aktuell befinden sich“ ansehen">Anschauen</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button> -->
                    </td>
                    <td class="" data-colname="Username">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <strong>
                            <a class="row-title" href="/wp-admin/admin.php?page=ms_users_detail&user_id=<?php echo $user->mar_user_id ?>" aria-label="„Aktuell befinden sich“ (Bearbeiten)">
                                <?php echo $wp_user->user_login  ?>
                            </a>
                        </strong>
                    </td>
                    <td class="" data-colname="Von - Bis">
                    </td>

                    <td class="" data-colname="SUs">
                        <span class="">
                        </span>
                    </td>

                    <td class="" data-colname="Anschrift">
                        <span class="">
                            
                        </span>
                    </td>

                    <td class="" data-colname="Aktionen">
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
                <th scope="col" id="author" class="manage-column column-author"></th>
                <th scope="col" id="security_instructions" class="manage-column column-comments num sortable desc"></th>
                <th scope="col" id="address" class="manage-column column-comments num sortable desc"></th>
                <th scope="col" id="date" class="manage-column column-date sortable asc"></th>
            </tr>
        </tfoot>

    </table>

    <div class="tablenav bottom">

        <div class="alignleft actions">
        </div>
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($viewmodel->users) ?> Einträge</span>
            <br class="clear">
        </div>

        </form>



        <br class="clear">
    </div>