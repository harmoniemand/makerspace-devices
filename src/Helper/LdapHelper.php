<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class LdapHelper
{

    public $server = "";
    public $port = "";
    public $admin = "";
    public $admin_pass = "";
    public $user_ou = "";

    public $connection = null;
    public $binding = null;

    function __construct()
    {
        $this->server =       get_option("makerspace_ldap_server");
        $this->port =         get_option("makerspace_ldap_port");
        $this->admin =        get_option("makerspace_ldap_admin");
        $this->admin_pass =   get_option("makerspace_ldap_admin_pass");
        $this->user_ou =      get_option("makerspace_ldap_user_ou");

        $this->connect();
        $this->bind();
    }

    public function connect()
    {
        $this->connection = ldap_connect($this->server, $this->port);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    }

    public function bind()
    {
        if ($this->connection) {

            $this->binding = ldap_bind(
                $this->connection,
                $this->admin,
                $this->admin_pass
            )  or die("Error trying to bind: " . ldap_error($this->connection));
        }
    }

    public static function sanitize($query)
    {

        $arr_search = array('\\', '*', '(', ')');
        $arr_replace = array('\5c', '\2a', '\28', '\29');

        $query = str_replace($arr_search, $arr_replace , $query);
        for ($i = 0; $i < strlen($query); $i++) {
            $char = substr($query, $i, 1);
            if (ord($char) < 32) {
                $hex = dechex(ord($char));
                if (strlen($hex) == 1) $hex = '0' . $hex;
                $query = str_replace($char, '\\' . $hex, $query);
            }
        }

        return $query;
    }
}
