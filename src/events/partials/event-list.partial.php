<?php

if (!defined('ABSPATH')) {
    die('-1');
}

require_once dirname(__FILE__) . "/../../entities/event.entity.php";

$events = EventEntity::get_events();

?>

<div class="wrap">

    <div class="d-flex justify-content-between">
        <h1 class="wp-heading-inline"><?php echo __("Events") ?></h1>
    </div>

    <!-- <a href="/wp-admin/post-new.php?post_type=page" class="page-title-action">Erstellen</a> -->
    <hr class="wp-header-end">

    <h2 class="screen-reader-text">Seitenliste</h2>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>ID</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="?<?php echo $url ?>">
                        <span>Name</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="?<?php echo $url ?>">
                        <span>Slug</span><span class="sorting-indicator"></span>
                    </a>
                </th>

                <th scope="col" class="manage-column column-title column-primary sortable desc">
                </th>
            </tr>
        </thead>

        <tbody id="the-list">

            <?php foreach ($events as $event) : ?>

                <tr class="iedit author-self level-0 type-page status-publish hentry">

                    <td class="title column-title has-row-actions column-primary page-title" data-colname="ID">
                        <span>
                            <a href="/wp-admin/admin.php?page=ms_event_detail&ms_event_id=<?php echo $event->ms_event_id ?>">
                                <?php echo $event->ms_event_id ?>
                            </a>
                        </span>
                        <span class="hidden"><?php echo $event->ms_event_title ?></span>
                        <span class="hidden"><?php echo $event->ms_event_slug ?></span>
                    </td>

                    <td class="" data-colname="Name">
                        <a href="/wp-admin/admin.php?page=ms_event_detail&ms_event_id=<?php echo $event->ms_event_id ?>">
                            <?php echo $event->ms_event_title ?>
                        </a>
                    </td>
                    <td class="" data-colname="Slug">
                        <?php echo $event->ms_event_slug ?>
                    </td>
                    <td class="" data-colname="Aktionen">
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

        <tfoot>
            <tr>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>ID</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="?<?php echo $url ?>">
                        <span>Name</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="?<?php echo $url ?>">
                        <span>Slug</span><span class="sorting-indicator"></span>
                    </a>
                </th>

                <th scope="col" class="manage-column column-title column-primary sortable desc">
                </th>
            </tr>
        </tfoot>

    </table>

    <div class="tablenav bottom">

        <div class="alignleft actions">
        </div>
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($events) ?> Einträge</span>
            <br class="clear">
        </div>

        </form>



        <br class="clear">
    </div>