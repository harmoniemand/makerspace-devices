<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class UsersController
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


    public function renderMenuUsers()
    {
        require dirname(__FILE__) . '/partials/list/users-list.controller.php';
    }public function renderSubmenuUsersDetail()
    {
        require dirname(__FILE__) . '/partials/detail/users-detail.controller.php';
    }

    public function registerAdminMenu()
    {
        $capability = 'ms_read_users';
        
        $page_title = __('Besuchende');
        $menu_title = __('Besuchende');
        $menu_slug  = 'ms_users';
        $icon_url   = 'dashicons-media-code';
        add_menu_page(
            $page_title,
            $menu_title,
            "ms_read_users",
            $menu_slug,
            array($this, "renderMenuUsers"),
            $icon_url,
            4
        );

        $subpage_title = 'Detail';
        $submenu_title = 'Detail';
        $submenu_slug = 'ms_users_detail';
        add_submenu_page(
            $menu_slug . "_NOT_LISTED",
            $subpage_title,
            $submenu_title,
            "ms_edit_users",
            $submenu_slug,
            array($this, "renderSubmenuUsersDetail")
        );
    }

    public function save_forms()
    {
        if (isset($_POST['makerspace_settings_nonce'])) {
            $this->save_form_settings();
        }
    }

    public function save_form_settings()
    {
        // print_r($_POST);


    }

    public function load_styles()
    {
        // wp_enqueue_style('css-custom-entity-reservation', plugins_url('reservation.styles.css', __FILE__));
    }

    public function register_roles() {
        $role_editor = get_role('editor');
        $role_editor->add_cap('ms_read_users', true);
        $role_editor->add_cap('ms_edit_users', true);

        $role_checkin = get_role('checkin');
        $role_checkin->add_cap('ms_edit_users', true);
    }

    public function register()
    {
        add_action('init', array($this, 'register_roles'));
        add_action('init', array($this, 'save_forms'));
        add_action('admin_enqueue_scripts', array($this, 'load_styles'));
        add_action('admin_menu', array($this, "registerAdminMenu"));
    }

    public function activate()
    {
    }
}
