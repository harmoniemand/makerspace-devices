<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class BasicRepository
{

    function custom_get_user_meta($uid, $key, $single = true)
    {
        global $wpdb;

        if ($single) {
            return get_user_meta($uid, $key, $single);
        }

        $sql = "SELECT umeta_id, meta_value FROM wp_usermeta WHERE user_id = $uid AND meta_key = '$key' ORDER BY umeta_id DESC";
        $data = $wpdb->get_results($sql);

        $arr = array(); 
        foreach($data as $elem) {
            $object = maybe_unserialize($elem->meta_value);
            $object->__umeta_id = $elem->umeta_id;
            array_push($arr, $object);
        }

        return $arr;
    }

    function get_user_meta_latest_value($uid, $key) {
        global $wpdb;

        $sql = "SELECT meta_value FROM wp_usermeta WHERE user_id = $uid AND meta_key = '$key' ORDER BY umeta_id DESC LIMIT 1";
        $data = $wpdb->get_var($sql);
        return maybe_unserialize($data);
    }
}
