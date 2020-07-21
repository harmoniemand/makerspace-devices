<div class="wrap">
    <h1 class="wp-heading-inline">Reservierungen - Logeinträge</h1>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">


    <h2 class="screen-reader-text">Seitenliste filtern</h2>
    <ul class="subsubsub">
        <li class="all">
            <a href="edit.php?post_type=page&amp;all_posts=1" class="current" aria-current="page">
                Heute <span class="count">(<?php echo count($logs) ?>)</span>
            </a>
        </li>
    </ul>

    <form method="POST" action="?<?php echo http_build_query($url_data) ?>">
        <?php wp_nonce_field(basename(__FILE__), 'makerspace_advance_refistration_nonce'); ?>

        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Seiten durchsuchen:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Seiten durchsuchen"></p>

        <input type="hidden" name="post_status" class="post_status_page" value="all">
        <input type="hidden" name="post_type" class="post_type_page" value="page">

        
        <h2 class="screen-reader-text">Seitenliste</h2>
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th scope="col" id="author" class="manage-column column-author">User</th>
                    <th scope="col" id="author" class="manage-column column-author">Zeitpunkt</th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php foreach ($logs as $log) : ?>

                    <?php

                    $log_user = get_userdata($log->mpl_user_id);

                    ?>

                    <tr id="post-3222" class="iedit author-self level-0 post-3222 type-page status-publish hentry">

                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                            <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                            <strong>
                                <a class="row-title" href="/wp-admin/post.php?post=3222&amp;action=edit" aria-label="„Aktuell befinden sich“ (Bearbeiten)">
                                    <?php echo $log_user->user_firstname ?>
                                    <?php echo $log_user->user_lastname  ?>
                                    (<?php echo $log_user->user_login  ?>)
                                </a>
                            </strong>
                        </td>

                        <td class="date column-date" data-colname="Datum">
                            <?php print_r($log->mpl_datetime) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                <th scope="col" class="manage-column column-author">User</th>
                    <th scope="col" class="manage-column column-author">Zeitpunkt</th>
                </tr>
            </tfoot>

        </table>

        <div class="tablenav bottom">

            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($logs) ?> Einträge</span>
            <br class="clear">
        </div>

    </form>



    <br class="clear">
</div>