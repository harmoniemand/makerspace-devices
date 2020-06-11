<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class ReservationEntity
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


    public function renderMenuReservationList()
    {
        require dirname(__FILE__) . '/partials/reservation-list.partial.php';
    }
    public function renderSubmenuReservationEditor()
    {
        require dirname(__FILE__) . '/partials/reservation-editor.partial.php';
    }
    public function renderSubmenuReservationCalendar()
    {
        require dirname(__FILE__) . '/partials/reservation-calendar.partial.php';
    }

    public function registerAdminMenu()
    {
        $page_title = 'Reservierungen';
        $menu_title = 'Reservierungen';
        $capability = 'edit_others_posts';
        $menu_slug  = 'reservations';
        $function   = array($this, "renderMenuReservationList");
        $icon_url   = 'dashicons-media-code';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url
        );

        $subpage_title = 'Kalender';
        $submenu_title = 'Kalender';
        $submenu_slug = 'reservations-calendar';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuReservationCalendar")
        );

        $subpage_title = 'Neue Reservierung';
        $submenu_title = 'Neue Reservierung';
        $submenu_slug = 'reservations-editor';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            $capability,
            $submenu_slug,
            array($this, "renderSubmenuReservationEditor")
        );
    }

    public function shortcode_table($atts)
    {
        ob_start();
        require dirname(__FILE__) . '/partials/shortcode-reservation-table.partial.php';
        $ReturnString = ob_get_contents();
        ob_end_clean();
        return $ReturnString;
    }

    public function register_shortcodes()
    {
        add_shortcode('registrations_table', array($this, "shortcode_table"));
    }

    public function load_styles()
    {
        wp_enqueue_style('css-custom-entity-reservation', plugins_url('reservation.styles.css', __FILE__));
    }

    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
        add_action('init', array($this, 'register_Shortcodes'));
    }

    public function activate()
    {
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_ms_devices_workshop_reservations (
                mse_device_workshop_registration_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                mse_device_workshop_taxonomie_id INT NOT NULL,
                mse_device_workshop_registration_email VARCHAR(255) NOT NULL,
                mse_device_workshop_registration_firstname VARCHAR(255) NOT NULL,
                mse_device_workshop_registration_lastname VARCHAR(255) NOT NULL,
                mse_device_from INT NOT NULL,
                mse_device_to INT NOT NULL,
                mse_device_approved INT,
                mse_device_deleted INT,
                mse_device_project_title VARCHAR(255),
                mse_device_message TEXT
                )
            ";

        $wpdb->get_results($sql);
    }
}
