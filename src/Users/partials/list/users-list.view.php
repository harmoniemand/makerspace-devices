<?php

if (!defined('ABSPATH')) {
    die('-1');
}

// http_response_code(403);

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
                <th scope="col" id="author" class="manage-column column-author">Anschrift</th>
                <th scope="col" id="security_instructions" class="manage-column column-comments num sortable desc">
                    <?php echo __("Anschrift") ?>
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

                <tr class="iedit author-self level-0 type-page status-publish hentry">

                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname">
                        <span><?php echo $user->first_name ?></span>
                        <span class="hidden"><?php echo $user->last_name ?></span>

                        <div class="hidden" id="inline_3240">
                            <div class="post_title"><?php echo $user->last_name  ?></div>
                            <div class="post_name"><?php echo $user->login_name  ?></div>
                        </div>

                        <button type="button" class="toggle-row"><span class="screen-reader-text">Mehr Details anzeigen</span></button>
                    </td>

                    <td class="" data-colname="Nachname">
                        <?php echo $user->last_name  ?>
                    </td>
                    <td class="" data-colname="Username">

                        <strong>
                            <a class="row-title" href="/wp-admin/admin.php?page=ms_users_detail&user_id=<?php echo $user->user_id ?>">
                                <?php echo $user->login_name  ?>
                            </a>
                        </strong>
                    </td>
                    <td class="" data-colname="Von - Bis">
                        <?php echo $user->address->street  ?>

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