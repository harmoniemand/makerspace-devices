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
    public function renderSubmenuReservationAddGuest()
    {
        require dirname(__FILE__) . '/partials/reservation-add-guest/reservation-add-guest.partial.php';
        // require dirname(__FILE__) . '/partials/reservation-timeline.partial.php';
    }
    public function renderSubmenuReservationLogs()
    {
        require dirname(__FILE__) . '/partials/reservation-logs/reservation-logs.controller.php';
    }
    public function renderSubmenuReservationExport()
    {
        require dirname(__FILE__) . '/partials/reservation-export/reservation-export.controller.php';
    }
    public function renderSubmenuStats()
    {
        require dirname(__FILE__) . '/partials/reservation-stats/reservation-stats.controller.php';
    }

    public function registerAdminMenu()
    {
        $page_title = 'Reservierungen';
        $menu_title = 'Reservierungen';
        $capability = 'read';
        $menu_slug  = 'reservations';
        $function   = array($this, "renderSubmenuReservationEditor");
        $icon_url   = 'dashicons-calendar-alt';
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            2
        );

        add_menu_page(
            'Check-In',
            'Check-In',
            'ms_checkin',
            'reservations-checkin',
            array($this, "renderSubmenuReservationPOS"),
            $icon_url,
            4
        );

        $subpage_title = 'Timeline';
        $submenu_title = 'Timeline';
        $submenu_slug = 'reservations-timeline';
        add_submenu_page(
            'reservations-checkin',
            $subpage_title,
            $submenu_title,
            "ms_checkin",
            $submenu_slug,
            array($this, "renderSubmenuReservationTimeline")
        );

        $subpage_title = 'Gast anmelden';
        $submenu_title = 'Gast anmelden';
        $submenu_slug = 'reservations-add-guest';
        add_submenu_page(
            'reservations-checkin',
            $subpage_title,
            $submenu_title,
            "ms_checkin",
            $submenu_slug,
            array($this, "renderSubmenuReservationAddGuest")
        );


        $subpage_title = 'Logs';
        $submenu_title = 'Logs';
        $submenu_slug = 'reservations-logs';
        add_submenu_page(
            'reservations-checkin',
            $subpage_title,
            $submenu_title,
            "edit_others_posts",
            $submenu_slug,
            array($this, "renderSubmenuReservationLogs")
        );

        $subpage_title = 'COVID Export';
        $submenu_title = 'COVID Export';
        $submenu_slug = 'reservations-covid-export';
        add_submenu_page(
            'reservations-checkin',
            $subpage_title,
            $submenu_title,
            "edit_others_posts",
            $submenu_slug,
            array($this, "renderSubmenuReservationExport")
        );

        $subpage_title = 'Stats';
        $submenu_title = 'Stats';
        $submenu_slug = 'reservations-stats';
        add_submenu_page(
            'reservations-checkin',
            $subpage_title,
            $submenu_title,
            "edit_others_posts",
            $submenu_slug,
            array($this, "renderSubmenuStats")
        );
    }



    public function api_get_present_at($attr)
    {
        $end = isset($_GET["end"]) ? new DateTime($_GET["end"]) : get_datetime();

        return (object) array(
            "content" => PresenceLogEntity::get_visitors_present_at($end)
        );
    }

    public function api_get_reservation_presence_count_empty()
    {
        $this->api_get_reservation_presence_count(null);
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
            'callback' => array($this, 'api_get_reservation_presence_count_empty'),
            'permission_callback' => function () {
                return true;
            }
        ));

        register_rest_route('makerspace/v1', '/presence/sum', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_reservation_presence_count_sum'),
            'permission_callback' => function () {
                return true;
            }
        ));

        register_rest_route('makerspace/v1', '/presence/at', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_present_at'),
            'permission_callback' => function () {
                return true;
            }
        ));
    }


    public function shortcode_table($atts)
    {
        ob_start();
        require dirname(__FILE__) . '/shortcodes/reservation-table.shortcode.php';
        $ReturnString = ob_get_contents();
        ob_end_clean();
        return $ReturnString;
    }
    public function shortcode_log_contact($atts)
    {
        ob_start();
        require dirname(__FILE__) . '/shortcodes/reservation-log.shortcode.php';
        $ReturnString = ob_get_contents();
        ob_end_clean();
        return $ReturnString;
    }

    public function register_shortcodes()
    {
        add_shortcode('registrations_table', array($this, "shortcode_table"));
        // add_shortcode('registrations_visitor_count', array($this, "shortcode_visitor_count"));
        add_shortcode('ms_registrations_log_contact', array($this, "shortcode_log_contact"));
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

    public function save_changes()
    {
        global $wpdb;

        // create arrive / leave log for temp visitors
        if (isset($_POST["mpl_temp_visitor_id"])) {
            $sql_mp_create_log = "INSERT INTO makerspace_presence_logs (mpl_temp_visitor_id, mpl_datetime) values (%s, %s)";
            $wpdb->get_results($wpdb->prepare(
                $sql_mp_create_log,
                $_POST["mpl_temp_visitor_id"],
                get_datetime()->format("Y-m-d H:i:s")
            ));
        }

        // create arrive / leave log
        if (isset($_POST["mp_create_log"])) {
            $sql_mp_create_log = "INSERT INTO makerspace_presence_logs (mpl_user_id, mpl_datetime) values (%d, %s)";
            $wpdb->get_results($wpdb->prepare(
                $sql_mp_create_log,
                $_POST["mp_create_log"],
                get_datetime()->format("Y-m-d H:i:s")
            ));
        }

        // toggle security instruction and save
        if (isset($_POST["ms_user_corona_safetyinstruction"])) {
            if (get_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction")) {
                delete_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction");
            } else {
                add_user_meta($_POST["ms_user_corona_safetyinstruction"], "ms_user_corona_safetyinstruction", get_datetime());
            }
        }

        // toggle contact and save
        if (isset($_POST["ms_user_corona_adress"])) {
            if (get_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress")) {
                delete_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress");
            } else {
                add_user_meta($_POST["ms_user_corona_adress"], "ms_user_corona_adress", get_datetime());
            }
        }
    }

    public function register_roles()
    {
        add_role(
            'checkin',
            'CheckIn Service User Role',
            [
                // list of capabilities for this role
                'read'         => true,
                'ms_checkin'   => true,
                'ms_read_users' => true,
                'ms_edit_users' => true,
            ]
        );

        
        $role_editor = get_role('editor');
        $role_editor->add_cap('ms_checkin', true);
    }

    public function renderExport()
    {
        if (current_user_can('edit_posts')) {
            if ($_SERVER['REQUEST_URI'] == '/downloads/export-visitors.csv') {
                require dirname(__FILE__) . '/partials/export/export.controller.php';
            }
        }
    }


    public function register()
    {
        add_action('init', array($this, 'register_roles'));
        add_action('init', array($this, "save_changes"));
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
        add_action('init', array($this, 'register_Shortcodes'));
        add_action('wp_dashboard_setup', array($this, 'register_dashboard_widgets'));
        add_action('rest_api_init', array($this, 'register_api_endpoints'));

        add_action('template_redirect', array($this, 'renderExport'));

    }

    public function activate()
    {
        PresenceLogEntity::create_database_tables();
    }
}
