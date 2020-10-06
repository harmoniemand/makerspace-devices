<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class BasicModel
{
    public static function strip_all_tags($any) {
        $ret_val = null;
        if (is_object($any)) {
            $ret_val = BasicModel::strip_all_tags_from_object($any);
        } else if (is_array ($any)) {
            $ret_val = BasicModel::strip_all_tags_from_array($any);
        } else {
            $ret_val = BasicModel::strip_all_tags_from_text($any);
        }

        return $ret_val;
    }

    public static function strip_all_tags_from_object($obj)
    {
        $arr = array();

        $obj_arr = get_object_vars ($obj);
        $keys = array_keys($obj_arr);

        foreach($keys as $key) {
            $arr[$key] = BasicModel::strip_all_tags($obj_arr[$key]);
        }

        return (object)$arr;
    }

    public static function strip_all_tags_from_array($arr)
    {
        $ret_arr = array();

        foreach ($arr as $elem) {
            array_push($ret_arr, BasicModel::strip_all_tags_from_text($elem));
        }

        return $ret_arr;
    }

    public static function strip_all_tags_from_text($text)
    {
        return wp_strip_all_tags($text);
    }
}

class BasicMetaModel extends BasicModel
{
    public $__umeta_id;

    
}
