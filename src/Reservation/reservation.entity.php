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


    public function renderSubmenuReservationEditor()
    {
        require dirname(__FILE__) . '/partials/reservation-editor.partial.php';
    }
    public function renderSubmenuReservationPOS()
    {
        require dirname(__FILE__) . '/partials/reservation-pos.partial.php';
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

    
    public function shortcode_visitor_count($atts) {
        global $wpdb;
        
        $sql = "SELECT COUNT(mpl_id) as count FROM makerspace_presence_logs WHERE mpl_datetime BETWEEN %s AND %s GROUP BY mpl_user_id";
        $day_start = (get_datetime()->setTime(0, 0, 0));
        $day_end = (get_datetime()->setTime(23, 59, 59));
        

        $entries = $wpdb->get_results($wpdb->prepare(
            $sql,
            $day_start->format("Y-m-d H:i:s"),
            $day_end->format("Y-m-d H:i:s")
        ));

        $count = 0;
        foreach ($entries as $e) {
            if ($e->count % 2 == 1) {
                $count++;
            }
        }

        return $count;
    }

    public function api_get_reservation_presence_count($data)
    {
        return (object)array(
            "count" => $this->shortcode_visitor_count(null)
        );
    }

    public function register_api_endpoints() {

            register_rest_route( 'makerspace/v1', '/presence', array(
              'methods' => 'GET',
              'callback' => array($this, 'api_get_reservation_presence_count'),
            ) );
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
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_advance_registrations (
                mar_registration_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                mar_user_id  bigint(20) NOT NULL,
                mar_from INT NOT NULL,
                mar_to INT NOT NULL,
                mar_approved_by INT,
                mar_deleted INT,
                mar_term_id bigint(20),
                mse_device_message TEXT
                )
            ";

        $wpdb->get_results($sql);


        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_presence_logs (
                mpl_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                mpl_user_id  bigint(20) NOT NULL,
                mpl_datetime datetime NOT NULL
                )
            ";

        $wpdb->get_results($sql);
    }
}
