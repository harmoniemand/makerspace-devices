<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class DebugController
{

    const VERSION = '1.0.0';

    /**
     * Static Singleton Holder
     * @var self
     */
    protected static $instance;

    /**
     * Get (and instantiate, if necessary) the instance of the class
     *
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    function __construct()
    {
    }


    public function renderMenuDebug()
    {
        require dirname(__FILE__) . '/partials/debug.partial.php';
    }

    public function registerAdminMenu()
    {
        $menu_slug  = 'options-general.php';
        $capability = 'add_users';

        $page_title = __('Maker Space Settings');
        $menu_title = __('Maker Space Settings');
        $menu_slug  = 'ms_settings_general';
        $icon_url   = 'dashicons-media-code';
        add_menu_page(
            "Debug",
            "Debug",
            "read",
            "md_debug",
            array($this, "renderMenuDebug"),
            $icon_url,
            0
        );
    }

    public function save_forms()
    {
        if (isset($_POST['makerspace_settings_nonce'])) {
            $this->save_form_settings();
        }
    }

    public function save_form_settings()
    {
        // print_r($_POST);


    }

    public function load_styles()
    {
        // wp_enqueue_style('css-custom-entity-reservation', plugins_url('reservation.styles.css', __FILE__));
    }

    public function register()
    {
        add_action('init', array($this, 'save_forms'));
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
    }

    public function activate()
    {
    }
}
