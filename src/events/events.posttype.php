<?php


class EventPostType
{

    private $slug;
    private $labels;

    protected static $instance;

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
    }

    public function register_endpoints()
    {
        require dirname(__FILE__) . '/events.controller.php';
        register_rest_route('events/v1', '/(?P<event_slug>.+)', array(
            'methods' => 'GET',
            'callback' => array("EventsController", "Get"),
            // 'permission_callback' => function () {
            //     return true;
            // }
        ));
    }


    public function register_posttype()
    {

        $args = array(
            'labels'      => array(
                'name'          => __('Events'),
                'singular_name' => __('Event'),
                'edit_item'     => __('Event bearbeiten'),
            ),
            'public'      => true,
            'has_archive' => true,
            'menu_icon'          => plugin_dir_url(__FILE__) . '../../menu-icon.png',
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'),
            // 'taxonomies'  => array(),
            // 'capabilities' => array(
            //     'edit_post'          => 'edit_post',
            //     'read_post'          => 'read_post',
            //     'delete_post'        => 'delete_post',
            //     'edit_posts'         => 'edit_posts',
            //     'edit_others_posts'  => 'edit_others_posts',
            //     'publish_posts'      => 'publish_posts',
            //     'read_private_posts' => 'read_private_posts',
            //     'create_posts'       => 'create_posts',
            // ),
        );

        register_post_type("mse_events", $args);
    }


    public function register()
    {
        add_action('init', array($this, 'register_posttype'));
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function activate()
    {
    }

    public function deactivate($network_deactivating)
    {
    }
}
