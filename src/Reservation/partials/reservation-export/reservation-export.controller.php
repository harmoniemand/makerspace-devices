<?php

if (!defined('ABSPATH')) {
    die('-1');
}


if ( ! current_user_can("<")) {
    http_response_code( 403 );
}

global $wpdb;
include_once dirname(__FILE__) . "/../../../Repositories/UserRepository.php";
$user_repo = new UserRepository();

$users = array();

$to = get_datetime();
$from = get_datetime()->modify('-4 week');

$sql_all = "
SELECT 
	tmp.uid as uid,
    tmp.last_visit as last_visit,
    wp_users.user_login as user_login,
    first_name.meta_value as first_name,
    last_name.meta_value as last_name
FROM (
    SELECT 
        mpl_user_id as uid,
        MAX(mpl_datetime) as last_visit
    FROM `makerspace_presence_logs`
    GROUP BY mpl_user_id
) as tmp

JOIN wp_users ON tmp.uid = wp_users.ID
JOIN wp_usermeta AS first_name ON tmp.uid = first_name.user_id AND first_name.meta_key = 'first_name'
JOIN wp_usermeta AS last_name ON tmp.uid = last_name.user_id AND last_name.meta_key = 'last_name'

WHERE last_visit BETWEEN '". $from->format("Y-m-d 00:00:00") ."' AND '". $to->format("Y-m-d 00:00:00") ."'

ORDER BY tmp.last_visit
";
$result= $wpdb->get_results($sql_all);

foreach ($result as $row) {
    array_push($users, $user_repo->Read($row->uid));
}

?>


<div class="wrap">

    <div class="d-flex justify-content-between">
        <h1 class="wp-heading-inline">Besuchende letzte vier Wochen</h1>

        <h1 class="wp-heading-inline">
            <?php echo count( $users ) ?> Besuchende
        </h1>
    </div>
    <hr class="wp-header-end">


        <h2 class="screen-reader-text">Seitenliste</h2>
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>Vorname</span><span class="sorting-indicator"></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>Nachname</span><span class="sorting-indicator"></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>Username</span><span class="sorting-indicator"></span>
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

                <?php foreach ($users as $user) : ?>

                    <tr class="iedit author-self level-0 type-page status-publish hentry">

                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Vorname">
                            <?php echo $user->first_name ?>
                        </td>

                        <td class="" data-colname="Nachname">
                            <?php echo $user->last_name  ?>
                        </td>
                        <td class="" data-colname="Username"></td>
                        <td class="" data-colname="Straße"></td>
                        <td class="" data-colname="SUs"></td>
                        <td class="" data-colname="Anschrift"></td>
                        <td class="" data-colname="Aktionen"></td>
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
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($users) ?> Einträge</span>
                <br class="clear">
            </div>

    </form>



    <br class="clear">
</div>