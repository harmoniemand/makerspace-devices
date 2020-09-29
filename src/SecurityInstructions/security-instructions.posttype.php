<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class SecurityInstructionPosttype
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
        $this->slug = "securityinstructions";

        $this->labels = array(
            'name'          => __('SUs und BAs'),
            'singular_name' => __('SU'),
            'edit_item'     => __('SU bearbeiten'),
        );
    }





    public function ST4_columns_head($defaults)
    {
        $post = get_post($post_ID);
        if ($post && get_post_type($post) == 'securityinstructions') {
            $defaults['betriebsanweisung_attachment_id'] = 'Betriebsanweisung';
        }
        return $defaults;
    }

    // SHOW THE FEATURED IMAGE
    public function ST4_columns_content($column_name, $post_ID)
    {
        $post = get_post($post_ID);
        if ($post && get_post_type($post) == 'securityinstructions') {
            if ($column_name == 'betriebsanweisung_attachment_id') {
                $betriebsanweisung_attachment_id = get_post_meta($post->ID, 'betriebsanweisung_attachment_id', true);

                if ($betriebsanweisung_attachment_id && $betriebsanweisung_attachment_id != "") {
                    echo '<div>';
                    echo '<a href="/wp-admin/upload.php?item=' . $betriebsanweisung_attachment_id . '">';
                    if ($betriebsanweisung_attachment_id != NULL) {
                        echo  get_the_title($betriebsanweisung_attachment_id);
                    }
                    echo '</a>';
                } else {
                    echo '<span class="text-warning">Keine BA hinterlegt</span>';
                }

                echo '<br />';

                $betriebsanweisung_created_date = get_post_meta($post->ID, 'betriebsanweisung_created_date', true);
                if ($betriebsanweisung_created_date) {
                    echo 'Erstellt am: ';
                    echo date_i18n(get_option('date_format'), strtotime($betriebsanweisung_created_date));
                    echo '</div>';
                } else {
                    echo '<div class="text-warning">BA-Datum fehlt</div>';
                }
            }

            if ($column_name == 'datenblatt_attachment_id') {
                $datenblatt_attachment_id = get_post_meta($post->ID, 'datenblatt_attachment_id', true);
                echo '<div><a href="/wp-admin/upload.php?item=' . $datenblatt_attachment_id . '">';
                if ($datenblatt_attachment_id != NULL) {
                    echo  get_the_title($datenblatt_attachment_id);
                }
                echo "</a></div>";
            }

            if ($column_name == 'bedienungsanleitung_attachment_id') {
                $bedienungsanleitung_attachment_id = get_post_meta($post->ID, 'bedienungsanleitung_attachment_id', true);
                echo '<div><a href="/wp-admin/upload.php?item=' . $bedienungsanleitung_attachment_id . '">';
                if ($bedienungsanleitung_attachment_id != NULL) {
                    echo  get_the_title($bedienungsanleitung_attachment_id);
                }
                echo "</a></div>";
            }
        }
    }

    public function register_taxonnomies()
    {
        
    }


    public function register_posttype()
    {
        $args = array(
            'labels'      => $this->labels,
            'public'      => true,
            'has_archive' => true,
            'menu_icon'   => plugin_dir_url(MSM_FILE) . '/src/menu-icon.png',
            'show_in_rest' => true,
            'supports'    => array('title', 'revisions', 'editor' /*, 'author', 'thumbnail',  'excerpt', 'comments', 'custom-fields', 'revisions'*/),
            'taxonomies'  => array('ms_devices_workshop'),
            // 'capabilities' => array( 'publish_posts' )
        );

        register_post_type($this->slug, $args);
    }

    public function render_metabox_general_infos()
    {
        require(plugin_dir_path(__FILE__) . 'partials/metabox-general-infos.php');
    }
    public function render_metabox_gfbu_list()
    {
        require(plugin_dir_path(__FILE__) . 'partials/metabox-gfbu-list.php');
    }
    

    public function add_metaboxes()
    {

        add_meta_box(
            'metabox_general_infos',
            __('Allgemeine Informationen'),
            array($this, 'render_metabox_general_infos'),
            $this->slug,
            'normal',
            'default'
        );
        
        add_meta_box(
            'metabox_gfbu_list',
            __('Dokumente'),
            array($this, 'render_metabox_gfbu_list'),
            $this->slug,
            'normal',
            'default'
        );
    }
    public function save_custom_meta_box()
    {
        if (!isset($_POST["post_ID"]))
            return;

        $pid = $_POST["post_ID"];

        if ($pid == NULL)
            return;


        if (!current_user_can("edit_post", $pid)) {
            return $pid;
        }

        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return $pid;
        }

        // print_r($_POST);

        if(isset($_POST["security_instruction_device_id"])) {
            update_post_meta(
                $pid,
                "security_instruction_device_id",
                $_POST["security_instruction_device_id"]
            );
        }
    }

    public function load_scripts_styles() {
        // wp_enqueue_script("mse_si_custom_mediascript", )
    }

    public function media_selector_print_scripts() {
        require( plugin_dir_path(__FILE__) . "partials/media.js.php" );
    }

    public function register()
    {

        add_filter('manage_posts_columns', array($this, 'ST4_columns_head'));
        add_action('manage_posts_custom_column',  array($this, 'ST4_columns_content'), 10, 2);

        // add_action('init', array($this, 'register_taxonnomies'));
        add_action('init', array($this, 'register_posttype'));
        // add_action( 'init', array( $this, 'add_caps') );

        add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
        add_action( 'init', array( $this, 'save_custom_meta_box') );

        // add_filter('manage_workshop_columns', array($this, 'list_columns_head'));
        // add_action('manage_posts_custom_column',  array($this, 'list_columns_content'), 10, 2);


        // subpages
        // add_action( 'admin_menu', array($this, 'add_menu') );

        add_action('admin_enqueue_scripts', array($this, 'load_scripts_styles'));
        
        add_action( 'admin_enqueue_scripts', array( $this, 'media_selector_print_scripts') );

    }
}
