<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class DevicesPosttype
{


    public function ST4_columns_head($defaults)
    {
        $post = get_post($post_ID);
        if ($post && get_post_type($post) == 'devices') {
            $defaults['betriebsanweisung_attachment_id'] = 'Betriebsanweisung';
        }
        return $defaults;
    }

    // SHOW THE FEATURED IMAGE
    public function ST4_columns_content($column_name, $post_ID)
    {
        $post = get_post($post_ID);
        if ($post && get_post_type($post) == 'devices') {
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
        $labels = array(
            'name' => _x('Wertstätten', 'taxonomy general name'),
            'singular_name' => _x('Werkstatt', 'taxonomy singular name'),
            'search_items' =>  __('Werkstatt suchen'),
            'all_items' => __('Alle Werkstätten'),
            'parent_item' => __('Übergeordnete Werkstatt'),
            'parent_item_colon' => __('Übergeordnete Werkstatt:'),
            'edit_item' => __('Werkstatt bearbeiten'),
            'update_item' => __('Werkstatt ändern'),
            'add_new_item' => __('Neue Werkstatt'),
            'new_item_name' => __('Name der Werkstatt'),
            'menu_name' => __('Werkstätten'),
        );

        // Now register the taxonomy

        register_taxonomy('ms_devices_workshop', array('devices'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'ms_devices_workshop'),
        ));
    }


    public function register_posttype()
    {

        $labels = array(
            'name'          => __('Geräte'),
            'singular_name' => __('Gerät'),
            'edit_item'     => __('Gerät bearbeiten'),
        );

        $args = array(
            'labels'      => $labels,
            'public'      => true,
            'has_archive' => true,
            'menu_icon'   => plugin_dir_url(MSM_FILE) . '/src/menu-icon.png',
            'show_in_rest' => true,
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt',/* 'comments', 'custom-fields', 'revisions'*/),
            'taxonomies'  => array('ms_devices_workshop', 'category', 'post_tag'),
            // 'capabilities' => array( 'publish_posts' )
        );

        register_post_type('devices', $args);
    }

    public function register()
    {

        add_filter('manage_posts_columns', array($this, 'ST4_columns_head'));
        add_action('manage_posts_custom_column',  array($this, 'ST4_columns_content'), 10, 2);

        add_action('init', array($this, 'register_taxonnomies'));
        add_action('init', array($this, 'register_posttype'));
        // add_action( 'init', array( $this, 'add_caps') );

        // add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
        // add_action( 'init', array( $this, 'save_custom_meta_box') );

        // add_filter('manage_workshop_columns', array($this, 'list_columns_head'));
        // add_action('manage_posts_custom_column',  array($this, 'list_columns_content'), 10, 2);


        // subpages
        // add_action( 'admin_menu', array($this, 'add_menu') );


    }
}
