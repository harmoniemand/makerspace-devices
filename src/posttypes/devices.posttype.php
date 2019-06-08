<?php


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'MS_Device_Management_Logger' ) ) {

    require_once MS_DM_DIR . '/src/includes/class-logger.php';

    class DevicesPosttype {

        public static function register () {
            self::register_posttype_devices();

            add_filter('manage_posts_columns', array(__CLASS__, 'ST4_columns_head'));
            add_action('manage_posts_custom_column',  array(__CLASS__, 'ST4_columns_content'), 10, 2);
        }

        public static function ST4_columns_head($defaults) {
            $post = get_post($post_ID);
            if ($post && get_post_type($post) == 'devices') {
                $defaults['betriebsanweisung_attachment_id'] = 'Betriebsanweisung';
                //$defaults['datenblatt_attachment_id'] = 'Datenblatt';
                // $defaults['bedienungsanleitung_attachment_id'] = 'Bedienungsanleitung';
            }
            return $defaults;
        }
         
        // SHOW THE FEATURED IMAGE
        public static function ST4_columns_content($column_name, $post_ID) {
            $post = get_post($post_ID);
            if ($post && get_post_type($post) == 'devices') {
                if ($column_name == 'betriebsanweisung_attachment_id') {
                    $betriebsanweisung_attachment_id = get_post_meta($post->ID, 'betriebsanweisung_attachment_id', true);

                    if ($betriebsanweisung_attachment_id && $betriebsanweisung_attachment_id != "" ) {
                        echo '<div>';
                        echo '<a href="/wp-admin/upload.php?item=' . $betriebsanweisung_attachment_id . '">';
                        if($betriebsanweisung_attachment_id != NULL) { echo  get_the_title( $betriebsanweisung_attachment_id ); }
                        echo '</a>';
                    } else {
                        echo '<span class="text-warning">Keine BA hinterlegt</span>';
                    }

                    echo '<br />';

                    $betriebsanweisung_created_date = get_post_meta($post->ID, 'betriebsanweisung_created_date', true);
                    if($betriebsanweisung_created_date) {
                        echo 'Erstellt am: ';
                        echo date_i18n( get_option( 'date_format' ), strtotime( $betriebsanweisung_created_date ) );
                        echo '</div>';
                    } else {
                        echo '<div class="text-warning">BA-Datum fehlt</div>';
                    }
                }

                if($column_name == 'datenblatt_attachment_id') {
                    $datenblatt_attachment_id = get_post_meta($post->ID, 'datenblatt_attachment_id', true);
                    echo '<div><a href="/wp-admin/upload.php?item=' . $datenblatt_attachment_id . '">';
                    if($datenblatt_attachment_id != NULL) { echo  get_the_title( $datenblatt_attachment_id ); }
                    echo "</a></div>";
                }
                
                if($column_name == 'bedienungsanleitung_attachment_id') {
                    $bedienungsanleitung_attachment_id = get_post_meta($post->ID, 'bedienungsanleitung_attachment_id', true);
                    echo '<div><a href="/wp-admin/upload.php?item=' . $bedienungsanleitung_attachment_id . '">';
                    if($bedienungsanleitung_attachment_id != NULL) { echo  get_the_title( $bedienungsanleitung_attachment_id ); }
                    echo "</a></div>";
                }
            }
        }

        private static function register_posttype_devices () {

            $labels = array(
                'name'          => __('Geräte'),
                'singular_name' => __('Gerät'),
                'edit_item' 	=> __('Gerät bearbeiten'),
            );

            $args = array(
                'labels'      => $labels,
                'public'      => true,
                'has_archive' => true,
                'menu_icon'		  => plugin_dir_url( MS_DM_FILE ) . '/src/menu-icon.png',
                'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields'/*, 'excerpt', 'comments', 'custom-fields', 'revisions'*/ ),
                'taxonomies'  => array( /*'category', */'post_tag', 'locations' ),
                'capabilities' => array( 'publish_posts' )
            );

            register_post_type( 'devices', $args );
        }
    }
}