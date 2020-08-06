<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class NextcloudController
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

    public function render_dashboard_widget_nextcloud () {
        include dirname(__FILE__) . '/dashboard/nextcloud-widget.partial.php';
    }
    public function add_dashboard_widgets()
    {
        wp_add_dashboard_widget(
            'ms-nextcloud',         // Widget slug.
            'Maker Space Nextcloud',         // Title.
            array($this, 'render_dashboard_widget_nextcloud') // Display function.
        );
    }

    public function register()
    {
        add_action( 'wp_dashboard_setup', array($this, 'add_dashboard_widgets') );
    }

    public function activate()
    {
    }
}
