<?php

global $wpdb;

$r = null;
$rid = -1;

if (isset($_GET["rid"])) {
    $rid = $_GET["rid"];
}


if (isset($_POST["mse_device_workshop_nonce"])) {

    $mse_device_from = strtotime($_POST['mse_device_from_date'] . " " . $_POST['mse_device_from_time']);
    $mse_device_to = strtotime($_POST['mse_device_to_date'] . " " . $_POST['mse_device_to_time']);


    if (isset($_GET["rid"]) && $_GET["rid"] == -1) {
        $sql = "
        INSERT INTO makerspace_ms_devices_workshop_reservations (
            mse_device_workshop_taxonomie_id,
            mse_device_workshop_registration_email,
            mse_device_workshop_registration_firstname,
            mse_device_workshop_registration_lastname,
            mse_device_from,
            mse_device_to,
            mse_device_project_title,
            mse_device_message)
        Values( %d, %s, %s, %s, %d, %d, %s, %s)";

        $wpdb->get_results($wpdb->prepare(
            $sql,
            $_POST["mse_device_workshop_taxonomie_id"],
            $_POST["mse_device_workshop_registration_email"],
            $_POST["mse_device_workshop_registration_firstname"],
            $_POST["mse_device_workshop_registration_lastname"],
            $mse_device_from,
            $mse_device_to,
            $_POST["mse_device_project_title"],
            $_POST["mse_device_message"]
        ));
    } else if (isset($_GET["rid"]) && $_GET["rid"] > 0) {

        $sql = "
            UPDATE makerspace_ms_devices_workshop_reservations
            SET mse_device_workshop_taxonomie_id = %d,
            mse_device_workshop_registration_email = %s,
            mse_device_workshop_registration_firstname = %s,
            mse_device_workshop_registration_lastname = %s,
            mse_device_from = %d,
            mse_device_to = %d,
            mse_device_project_title = %s,
            mse_device_message = %s
            WHERE mse_device_workshop_registration_id = %d
        ";

        $wpdb->get_results($wpdb->prepare(
            $sql,
            $_POST["mse_device_workshop_taxonomie_id"],
            $_POST["mse_device_workshop_registration_email"],
            $_POST["mse_device_workshop_registration_firstname"],
            $_POST["mse_device_workshop_registration_lastname"],
            $mse_device_from,
            $mse_device_to,
            $_POST["mse_device_project_title"],
            $_POST["mse_device_message"],
            $rid
        ));
    }

    

    $saved = true;
}

if (isset($rid)) {

    $sql_reservations = "SELECT * FROM makerspace_ms_devices_workshop_reservations WHERE mse_device_workshop_registration_id = %d";

    $r = $wpdb->get_row($wpdb->prepare(
        $sql_reservations,
        $rid
    ));
}

// print_r($rid);

$term_maker_space = get_term_by('slug', 'maker-space');

function get_terms_with_childs($term_id)
{
    $terms = get_terms(array(
        'taxonomy' => 'ms_devices_workshop',
        'hide_empty' => false,
        'parent' =>  $term_id,
        'orderby' => 'name',
        'order' => 'ASC',
    ));

    foreach ($terms as $term) {
        $childs = get_terms_with_childs($term->term_id);

        foreach ($childs as $child) {
            array_push($terms, $child);
        }
    }

    return $terms;
};

$labs = get_terms_with_childs(174);

// print_r(wp_get_current_user());

?>

<?php if (isset($saved)) : ?>
    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <div class="alert alert-success" role="alert" style="padding: 8px 12px; width: 100%;">
                Reservierung gespeichert
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="?page=reservations-new&rid=<?php echo $rid ?>">

    <?php wp_nonce_field(basename(__FILE__), 'mse_device_workshop_nonce'); ?>

    <div class="row mt-3" style="max-width: 100%;">
        <div class="col">
            <?php if (!isset($r)) : ?>
                <h1 class="wp-heading-inline" style="font-size: 23px;">Neue Reservierung</h1>
            <?php else : ?>
                <h1 class="wp-heading-inline" style="font-size: 23px;">Reservierung bearbeiten</h1>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
        <div class="col">
            <div class="card" style="border-radius: 0; padding: 8px 12px;">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="mse_device_workshop_taxonomie_id" class="col-sm-2 col-form-label">Werkstatt</label>
                        <div class="col-sm-10">
                            <select class="form-control w-100" name="mse_device_workshop_taxonomie_id" id="mse_device_workshop_taxonomie_id">
                                <?php foreach ($labs as $lab) : ?>
                                    <option value="<?php echo $lab->term_id ?>"><?php echo $lab->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_workshop_registration_firstname" class="col-sm-2 col-form-label">Vorname</label>
                        <div class="col-sm-10">
                            <?php $firstname = isset($r) ? $r->mse_device_workshop_registration_firstname : wp_get_current_user()->first_name; ?>
                            <input type="text" name="mse_device_workshop_registration_firstname" id="mse_device_workshop_registration_firstname" class="form-control-plaintext" value="<?php echo $firstname ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_workshop_registration_lastname" class="col-sm-2 col-form-label">Nachname</label>
                        <div class="col-sm-10">
                            <?php $lastname = isset($r) ? $r->mse_device_workshop_registration_lastname : wp_get_current_user()->last_name; ?>
                            <input type="text" name="mse_device_workshop_registration_lastname" id="mse_device_workshop_registration_lastname" class="form-control-plaintext" value="<?php echo $lastname ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_workshop_registration_email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <?php $email = isset($r) ? $r->mse_device_workshop_registration_email : wp_get_current_user()->user_email; ?>
                            <input type="text" name="mse_device_workshop_registration_email" id="mse_device_workshop_registration_email" class="form-control-plaintext" value="<?php echo $email ?>">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="mse_device_from_date" class="col-sm-2 col-form-label">Von</label>
                        <div class="col-sm-10">
                            <input type="date" name="mse_device_from_date" id="mse_device_from_date" class="form-control-plaintext" value="<?php echo date('Y-m-d', $r->mse_device_from); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mse_device_from_time" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="time" name="mse_device_from_time" id="mse_device_from_time" class="form-control-plaintext" value="<?php echo date('H:i', $r->mse_device_from); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_to_date" class="col-sm-2 col-form-label">Bis</label>
                        <div class="col-sm-10">
                            <input type="date" name="mse_device_to_date" id="mse_device_to_date" class="form-control-plaintext" value="<?php echo date('Y-m-d', $r->mse_device_to); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mse_device_to_time" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="time" name="mse_device_to_time" id="mse_device_to_time" class="form-control-plaintext" value="<?php echo date('H:i', $r->mse_device_to); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_project_title" class="col-sm-2 col-form-label">Projekt</label>
                        <div class="col-sm-10">
                            <input type="text" name="mse_device_project_title" id="mse_device_project_title" class="form-control-plaintext" value="<?php if (isset($r)) {
                                                                                                                                                        echo $r->mse_device_project_title;
                                                                                                                                                    } ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mse_device_message" class="col-sm-2 col-form-label">Nachricht</label>
                        <div class="col-sm-10">
                            <textarea name="mse_device_message" id="mse_device_message" class="form-control"><?php if (isset($r)) {
                                                                                                                    echo $r->mse_device_message;
                                                                                                                } ?>
                    </textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Speichern</li>
                    <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Hello World</li>
                    <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <button type=" submit" class="btn btn-primary btn-sm" style="background: #0071a1;">speichern</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</form>