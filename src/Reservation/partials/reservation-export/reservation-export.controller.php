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
	mpl_id,
    mpl_user_id,
    IFNULL(first_name.meta_value, mpl_temp_visitor_name) AS first_name,
    IFNULL(last_name.meta_value, '') AS last_name,
    mpl_datetime,
    mpl_temp_visitor_address,
    mpl_temp_visitor_phone,
    mpl_temp_visitor_email

FROM `makerspace_presence_logs`

LEFT JOIN wp_users ON mpl_user_id = wp_users.ID
LEFT JOIN wp_usermeta AS first_name ON mpl_user_id = first_name.user_id AND first_name.meta_key = 'first_name'
LEFT JOIN wp_usermeta AS last_name ON mpl_user_id = last_name.user_id AND last_name.meta_key = 'last_name'

WHERE mpl_datetime BETWEEN '" . $from->format("Y-m-d 00:00:00") . "' AND '" . $to->format("Y-m-d 23:59:59") . "'

ORDER BY mpl_datetime DESC
";
$result = $wpdb->get_results($sql_all);

foreach ($result as $row) {
    if (isset($row->uid)) {
        $user = $user_repo->Read($row->uid);
        $user->_last_visit = $row->last_visit;
        array_push($users, $user);
    } else if(isset($row->first_name)) {
        array_push($users, (object) array(
            "first_name" => $row->first_name,
            "last_name" => $row->last_name,
            "address_str" => $row->mpl_temp_visitor_address,
            "phone" => $row->mpl_temp_visitor_phone,
            "email" => $row->mpl_temp_visitor_email,
            "_last_visit" => $row->mpl_datetime
    ));
    }
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

                <th class="" data-colname="E-Mail">E-Mail</th>
                <th class="" data-colname="Telefon">Telefon</th>
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
                        <?php echo $user->address_str ?>
                        <?php echo $user->address->street ?>
                        <?php echo $user->address->number ?>,
                        <?php echo $user->address->zip ?>
                        <?php echo $user->address->city ?>
                    </td>
                    <td class="" data-colname="E-Mail"><?php echo $user->email  ?></td>
                    <td class="" data-colname="Telefon"><?php echo $user->phone  ?></td>
                    <td class="" data-colname="Letzter Besuch">
                        <?php echo $user->_last_visit ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <br class="clear">
</div>