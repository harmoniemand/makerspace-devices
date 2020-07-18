<?php


if (!defined('ABSPATH')) {
    die('-1');
}

require_once dirname(__FILE__) . "./../entities/presence_log.entity.php";

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


    public function renderSubmenuReservationEditor()
    {
        require dirname(__FILE__) . '/partials/reservation-editor.partial.php';
    }
    public function renderSubmenuReservationPOS()
    {
        require dirname(__FILE__) . '/partials/reservation-pos.controller.php';
    }
    public function renderSubmenuReservationTimeline()
    {
        require dirname(__FILE__) . '/partials/reservation-pos.controller.php';
        // require dirname(__FILE__) . '/partials/reservation-timeline.partial.php';
    }

    public function registerAdminMenu()
    {
        $page_title = 'Reservierungen';
        $menu_title = 'Reservierungen';
        $capability = 'read';
        $menu_slug  = 'reservations';
        $function   = array($this, "renderSubmenuReservationEditor");
        $icon_url   = 'dashicons-media-code';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url
        );

        $subpage_title = 'Timeline';
        $submenu_title = 'Timeline';
        $submenu_slug = 'reservations-timeline';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            "edit_others_posts",
            $submenu_slug,
            array($this, "renderSubmenuReservationTimeline")
        );

        $subpage_title = 'POS';
        $submenu_title = 'POS';
        $submenu_slug = 'reservations-pos';
        add_submenu_page(
            $menu_slug,
            $subpage_title,
            $submenu_title,
            "edit_others_posts",
            $submenu_slug,
            array($this, "renderSubmenuReservationPOS")
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

    public function api_get_present_at($attr)
    {
        $end = isset($_GET["end"]) ? new DateTime($_GET["end"]) : get_datetime();

        return (object) array(
            "content" => PresenceLogEntity::get_visitors_present_at($end)
        );
    }

    public function api_get_reservation_presence_count($data)
    {

        $date = get_datetime();

        if (empty($data)) {
            $date = new DateTime($data);
        }

        return (object) array(
            "count" => PresenceLogEntity::shortcode_visitor_count(null)
        );
    }

    public function api_get_reservation_presence_count_sum($data)
    {
        return $_GET;

        $entries = PresenceLogEntity::get_visitors_by_day(get_datetime());
        return $entries;
    }

    public function register_api_endpoints()
    {

        register_rest_route('makerspace/v1', '/presence', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_reservation_presence_count'),
        ));

        register_rest_route('makerspace/v1', '/presence/sum', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_reservation_presence_count_sum'),
        ));

        register_rest_route('makerspace/v1', '/presence/at', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_present_at'),
        ));
    }


    public function register_shortcodes()
    {
        add_shortcode('registrations_table', array($this, "shortcode_table"));
        add_shortcode('registrations_visitor_count', array($this, "shortcode_visitor_count"));
    }

    // Registrieren von Widgets
    public function render_dashboard_widget_register_for_visit()
    {
        require dirname(__FILE__) . '/partials/widget-register.partial.php';
    }

    public function register_dashboard_widgets()
    {
        wp_add_dashboard_widget(
            'ms-register',         // Widget slug.
            'Für den Besuch anmelden',         // Title.
            array($this, 'render_dashboard_widget_register_for_visit') // Display function.
        );
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
        add_action('wp_dashboard_setup', array($this, 'register_dashboard_widgets'));
        add_action('rest_api_init', array($this, 'register_api_endpoints'));
    }

    public function activate()
    {
        PresenceLogEntity::create_database_tables();
    }
}
