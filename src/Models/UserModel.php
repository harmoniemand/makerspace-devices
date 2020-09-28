<?php


if (!defined('ABSPATH')) {
    die('-1');
}

include_once dirname(__FILE__) . "/BasicModel.php";


class UserModel
{
    public $user_id;

    public $first_name;
    public $last_name;
    public $public_name;

    public $birthday;
    public $bio;

    public $addresses = array();
    public ?UserAddressModel $address;



    public function __constructor() {
    }

    public static function from_post_array($post_arr) {
        $user = new UserModel();

        $user->first_name = $post_arr["first_name"] ?? "";
        $user->last_name = $post_arr["last_name"] ?? "";
        $user->public_name = $post_arr["public_name"] ?? "";
        $user->birthday = $post_arr["birthday"] ?? null;
        $user->bio = $post_arr["bio"] ?? null;

        $user->address = UserAddressModel::from_post_array($post_arr);

        return $user;
    }
}

class UserAddressModel extends BasicMetaModel
{
    public bool $validated = false;
    public ?int $validated_by = null;
    public ?DateTime $validated_at = null;

    public ?DateTime $created = null;

    public string $first_name = "";
    public string $last_name = "";
    
    public string $street ="";
    public string $number ="";
    public string $zip ="";
    public string $city ="";

    public function __constructor()
    {

    }

    public static function from_post_array($post_arr) {
        $user_address = new UserAddressModel();

        $user_address->validated = $post_arr["address_validated"] ?? false;

        $user_address->first_name = $post_arr["first_name"] ?? null;
        $user_address->last_name = $post_arr["last_name"] ?? null;
        
        $user_address->street = $post_arr["address_street"] ?? null;
        $user_address->number = $post_arr["address_number"] ?? null;
        $user_address->zip = $post_arr["address_zip"] ?? null;
        $user_address->city = $post_arr["address_city"] ?? null;

        return $user_address;
    }

}