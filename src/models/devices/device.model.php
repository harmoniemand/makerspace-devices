<?php


class DeviceModel {

    private $slug = "devices";


    public function metabox_price_template () {
		require( plugin_dir_path( __FILE__ ) . 'partials/metabox.php' );
	}

    public function add_metaboxes() {

		add_meta_box(
			'items_price_metabox',
			'Preis pro Einheit',
			array( $this, 'metabox_price_template' ),
			$this->slug,
			'normal',
			'high'
		);
	}

    public function register_posttype () {

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
			'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
			'taxonomies'  => array( 'category', 'post_tag', 'locations' ),
		);
	
        register_post_type( $this->slug, $args );
        
        
	}
    
    
    public function register () {
        $this->register_posttype();

        add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
    }
    
}