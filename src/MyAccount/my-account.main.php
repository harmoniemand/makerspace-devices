<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class MyAccountMain
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


    public function renderMenuMyData()
    {
        require dirname(__FILE__) . '/partials/my-data.partial.php';
    }
    public function renderSubmenuMySettings() {
        require dirname(__FILE__) . '/partials/my-settings.partial.php';
    }
    public function renderSubmenuDeviceLicenses() {
        require dirname(__FILE__) . '/partials/my-device-licenses.php';
    }

    public function registerAdminMenu()
    {
        $page_title = __('Meine Daten');
        $menu_title = __('Meine Daten');
        $capability = 'read';
        $menu_slug  = 'my_data';
        $function   = array($this, "renderMenuMyData");
        $icon_url   = 'dashicons-media-code';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            2
        );

        $subpage_title_device_license = __('Sicherheitsunterweisungen');
        $submenu_slug_device_license  = 'my_device_licenses';
        add_submenu_page(
            $menu_slug,
            $subpage_title_device_license,
            $subpage_title_device_license,
            $capability,
            $submenu_slug_device_license,
            array($this, "renderSubmenuDeviceLicenses")
        );

        $subpage_title = __('Mein Einstellungen');
        $submenu_title = __('Mein Einstellungen');
        $submenu_slug = 'my-settings';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuMySettings")
        );
    }


    public function load_styles()
    {
        wp_enqueue_style('css-custom-my-account', plugins_url('me.styles.css', __FILE__));
    }

    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
    }

    public function activate()
    {
        
    }
}
