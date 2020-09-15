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
        require dirname(__FILE__) . '/partials/my-data/my-data.partial.php';
    }
    public function renderSubmenuMySettings()
    {
        require dirname(__FILE__) . '/partials/my-settings/my-settings.partial.php';
    }
    public function renderSubmenuDeviceLicenses()
    {
        require dirname(__FILE__) . '/partials/device-licenses/my-device-licenses.php';
    }
    public function renderSubmenuChangePassword()
    {
        require dirname(__FILE__) . '/partials/change-password/change-password.partial.php';
    }

    public function registerAdminMenu()
    {
        $page_title = __('Stammdaten');
        $menu_title = __('Stammdaten');
        $capability = 'read';
        $menu_slug  = 'my_data';
        $function   = array($this, "renderMenuMyData");
        $icon_url   = 'dashicons-admin-users';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            3
        );

        add_menu_page(
            __('Sicherheits-unterweisungen'), // page_title
            __('Sicherheits-unterweisungen'), // menu_title
            'read', // capability
            'my_device_licenses', // menu slug
            array($this, "renderSubmenuDeviceLicenses"), // function
            'dashicons-hammer', // icon
            3
        );

        add_menu_page(
            __('Einstellungen'), // page_title
            __('Einstellungen'), // menu_title
            'read', // capability
            'my_settings', // menu slug
            array($this, "renderSubmenuMySettings"), // function
            'dashicons-admin-generic', // icon
            3
        );

        if (!current_user_can("add_users")) {
            remove_menu_page('users.php');                  //Users
            remove_menu_page('profile.php');                  //Users
        }

        

        add_menu_page(
            __('Passwort'), // page_title
            __('Passwort'), // menu_title
            'read', // capability
            'my_password', // menu slug
            array($this, "renderSubmenuChangePassword"), // function
            'dashicons-lock', // icon
            3
        );

        if (!current_user_can("add_users")) {
            remove_menu_page('users.php');                  //Users
            remove_menu_page('profile.php');                  //Users
        }
    }

    public function render_dashboard_widget_change_password()
    {
        include dirname(__FILE__) . '/partials/change-password/change-password-dashboard-widget.partial.php';
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



    function c_update_user_meta($uid, $key, $value)
    {
        if ($value == false) {
            delete_user_meta($uid, $key);
            return;
        }

        update_user_meta($uid, $key, $value);
    }
    function save_my_account_calendar($uid)
    {
        $this->c_update_user_meta($uid, 'my_calendar_include_workshops', isset($_POST["my_calendar_include_workshops"]));
        $this->c_update_user_meta($uid, 'my_calendar_include_my_workshops', isset($_POST["my_calendar_include_my_workshops"]));
        $this->c_update_user_meta($uid, 'my_calendar_include_reservations', isset($_POST["my_calendar_include_reservations"]));
        $this->c_update_user_meta($uid, 'my_calendar_include_my_reservations', isset($_POST["my_calendar_include_my_reservations"]));

        $this->c_update_user_meta($uid, 'my_settings_last_update', new DateTime());
        return true;
    }


    public function save_changes()
    {
        if (isset($_POST["mse_my_settings"])) {
            global $success;
            $success = "Deine Einstellungen wurden erfolgreich gespeichert.";
            $uid = get_current_user_id();

            $this->save_my_account_calendar($uid);
            $success = $this->save_my_account_calendar($uid) ? $success : "Fehler beim Speichern der Kalendereinstellungen";
        }
    }

    public function register()
    {
        add_action('init', array($this, 'prevent_user_profile'));
        add_action('init', array($this, 'save_changes'));
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));
    }

    public function activate()
    {
    }
}
