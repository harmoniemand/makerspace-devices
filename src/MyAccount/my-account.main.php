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
    public function renderSubmenuMySettings()
    {
        require dirname(__FILE__) . '/partials/my-settings.partial.php';
    }
    public function renderSubmenuDeviceLicenses()
    {
        require dirname(__FILE__) . '/partials/my-device-licenses.php';
    }
    public function renderSubmenuChangePassword()
    {
        require dirname(__FILE__) . '/change-password/change-password.partial.php';
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

        // $subpage_title_device_license = __('Sicherheitsunterweisungen');
        // $submenu_slug_device_license  = 'my_device_licenses';
        // add_submenu_page(
        //     $menu_slug,
        //     $subpage_title_device_license,
        //     $subpage_title_device_license,
        //     $capability,
        //     $submenu_slug_device_license,
        //     array($this, "renderSubmenuDeviceLicenses")
        // );

        $subpage_title = __('Meine Einstellungen');
        $submenu_title = __('Meine Einstellungen');
        $submenu_slug = 'my-settings';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuMySettings")
        );

        if (!current_user_can("add_users")) {
            remove_menu_page('users.php');                  //Users
            remove_menu_page('profile.php');                  //Users
        }

        $subpage_title = __('Mein Passwort');
        $submenu_title = __('Mein Passwort');
        $submenu_slug = 'my-password';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuChangePassword")
        );

        if (!current_user_can("add_users")) {
            remove_menu_page('users.php');                  //Users
            remove_menu_page('profile.php');                  //Users
        }
    }

    public function render_dashboard_widget_change_password () {
        include 'change-password/change-password-dashboard-widget.partial.php';
    }
    public function add_dashboard_widgets()
    {
        wp_add_dashboard_widget(
            'ms-user-change-password',         // Widget slug.
            'Passwort ändern',         // Title.
            array($this, 'render_dashboard_widget_change_password') // Display function.
        );
    }


    public function prevent_user_profile()
    {
        global $pagenow;
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';

        if ($pagenow == 'profile.php') {
            wp_redirect("/wp-admin/admin.php?page=my_data");
            exit();
        }
    }


    public function load_styles()
    {
        wp_enqueue_style('css-custom-my-account', plugins_url('me.styles.css', __FILE__));
    }

    public function register()
    {
        add_action('init', array($this, 'prevent_user_profile'));
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
        add_action( 'wp_dashboard_setup', array($this, 'add_dashboard_widgets') );
    }

    public function activate()
    {
    }
}
