<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class RegistrationEntity
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


    public function render_shortcode_registration($atts)
    {
        ob_start();
        require dirname(__FILE__) . '/partials/shortcode-registration.partial.php';
        $ReturnString = ob_get_contents();
        ob_end_clean();
        return $ReturnString;
    }

    public function render_shortcode_login($atts)
    {
        ob_start();
        require dirname(__FILE__) . '/partials/shortcode-login.partial.php';
        $ReturnString = ob_get_contents();
        ob_end_clean();
        return $ReturnString;
    }

    public function register_shortcodes()
    {
        add_shortcode('makerspace_registration', array($this, "render_shortcode_registration"));
        add_shortcode('makerspace_login', array($this, "render_shortcode_login"));
    }

    public function load_styles()
    {
        wp_enqueue_style('css-custom-entity-reservation', plugins_url('reservation.styles.css', __FILE__));
    }

    public function custom_login()
    {
        if (isset($_POST["makerspace_login_nonce"])) {

            $credentials = array(); // Back-compat for plugins passing an empty string.
            $credentials['user_login'] = $_POST["mse_username"];
            $credentials['user_password'] = $_POST["mse_passwort"];
            $credentials['remember'] = isset($_POST["mse_remember"]) ? $_POST["mse_remember"] : false;

            $user = wp_signon($credentials, false);

            if (is_wp_error($user)) {
                // print_r($user);
            } else {
                $userID = $user->ID;
    
                wp_set_current_user($userID, $credentials['user_login']);
                wp_set_auth_cookie($userID, true, false);
                do_action('wp_login', $credentials['user_login']);
    
                if (is_user_logged_in()) {
                    wp_redirect("/wp-admin");
                    exit();
                }
            }

        }
    }
    
    public function register()
    {
        add_action('init', array($this, 'register_Shortcodes'));
        add_action('init', array($this, 'custom_login'));
    }

    public function activate()
    {
    }
}
