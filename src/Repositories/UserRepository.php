<?php


if (!defined('ABSPATH')) {
    die('-1');
}

include_once dirname(__FILE__) . "/BasicRepository.php";
include_once dirname(__FILE__) . "/../Models/UserModel.php";

class UserRepository extends BasicRepository
{


    private function update_user_meta_if_changed($user_id, $key, $value)
    {
        if (get_user_meta($user_id, $key, true) != $value) {
            // value has changed, lets update

            update_user_meta($user_id, $key, $value);
        }
    }

    private function add_user_meta_if_changed($user_id, $key, $value)
    {
        if (get_user_meta($user_id, $key, true) != $value) {
            // value has changed, lets update

            add_user_meta($user_id, $key, $value);
        }
    }


    function Read($user_id)
    {
        $user = new UserModel();

        $user->user_id = $user_id;
        $user->first_name = $this->custom_get_user_meta($user_id, 'first_name', true);
        $user->last_name = $this->custom_get_user_meta($user_id, 'last_name', true);
        $user->public_name = $this->custom_get_user_meta($user_id, 'nickname', true);
        $user->birthday = $this->get_user_meta_latest_value($user_id, 'birthday', true);
        $user->bio = $this->custom_get_user_meta($user_id, 'description', true);

        $user->addresses = $this->custom_get_user_meta($user_id, 'makerspace_userdata_address', false);
        $user->address = $this->get_user_meta_latest_value($user_id, 'makerspace_userdata_address') ?? new UserAddressModel();


        return $user;
    }

    function Update(UserModel $user)
    {
        if (empty($user->user_id)) {
            throw new Exception("user_id must be set to update a user, to create a user use wordpress default functionality");
        }



        $this->update_user_meta_if_changed($user->user_id, 'first_name', $user->first_name);
        $this->add_user_meta_if_changed($user->user_id, 'first_name_history', $user->first_name);

        $this->update_user_meta_if_changed($user->user_id, 'last_name', $user->last_name);
        $this->add_user_meta_if_changed($user->user_id, 'last_name_history', $user->last_name);

        wp_update_user(array(
            'ID'            => $user->user_id,
            'display_name' => $user->public_name
        ));
        $this->update_user_meta_if_changed($user->user_id, 'nickname', $user->public_name);

        $this->update_user_meta_if_changed($user->user_id, 'description', $user->bio);
        $this->add_user_meta_if_changed($user->user_id, 'description_history', $user->bio);

        $this->add_user_meta_if_changed($user->user_id, 'birthday', $user->birthday);

        $address_old = $this->get_user_meta_latest_value($user->user_id, 'makerspace_userdata_address');

        if (
            $address_old == null ||
            $address_old->first_name == $user->address->first_name ||
            $address_old->last_name == $user->address->last_name ||
            $address_old->street == $user->address->street ||
            $address_old->number == $user->address->number ||
            $address_old->zip == $user->address->zip ||
            $address_old->city == $user->address->city
        ) {
            $user->address->created = new DateTime();
            $this->add_user_meta_if_changed($user->user_id, 'makerspace_userdata_address', $user->address);
        }

    }
}
