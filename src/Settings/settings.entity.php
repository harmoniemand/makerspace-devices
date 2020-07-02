<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class SettingsEntity
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


    public function renderSubmenuSettings()
    {
        require dirname(__FILE__) . '/partials/settings.partial.php';
    }

    public function registerAdminMenu()
    {
        $menu_slug  = 'options-general.php';
        $capability = 'add_users';

        $subpage_title = 'MS-Settings';
        $submenu_title = 'MS-Settings';
        $submenu_slug = 'ms_settings';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuSettings")
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
