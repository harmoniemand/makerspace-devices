<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class FeedsMain
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

    public function render_my_calendar_ics()
    {
        require dirname(__FILE__) . '/partials/feed_my_calendar_ics.feed.php';
    }
    public function add_feeds()
    {
        add_feed('my_calendar', array($this, 'render_my_calendar_ics'));

        flush_rewrite_rules();
    }

    public function register()
    {
        add_action('init', array($this, "add_feeds"));
    }

    public function activate()
    {
    }
}
