<?php

if (!defined('ABSPATH')) {
    die('-1');
}


if (!current_user_can("<")) {
    http_response_code(403);
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

WHERE last_visit BETWEEN '" . $from->format("Y-m-d 00:00:00") . "' AND '" . $to->format("Y-m-d 00:00:00") . "'

ORDER BY tmp.last_visit
";
$result = $wpdb->get_results($sql_all);

foreach ($result as $row) {
    $user = $user_repo->Read($row->uid);
    $user->_last_visit = $row->last_visit;
    array_push($users, $user);
}

?>


<div class="wrap">

    <div class="d-flex justify-content-between">
        <h1 class="wp-heading-inline">Besuchende letzte vier Wochen</h1>

        <h1 class="wp-heading-inline">
            <?php echo count($users) ?> Besuchende
        </h1>
    </div>
    <hr class="wp-header-end">


    <h2 class="screen-reader-text">Seitenliste</h2>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    Vorname
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    Nachname
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    Anschrift
                </th>
                <th scope="col" id="author" class="manage-column column-author">Letzer Besuch</th>
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
                    <td class="" data-colname="Anschrift">
                        <?php echo $user->address->street ?>
                        <?php echo $user->address->number ?>,
                        <?php echo $user->address->zip ?>
                        <?php echo $user->address->city ?>
                    </td>
                    <td class="" data-colname="Letzter Besuch">
                        <?php echo $user->_last_visit ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <br class="clear">
</div>