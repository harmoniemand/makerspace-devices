<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MS_Devices
 * @subpackage MS_Devices/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MS_Devices
 * @subpackage MS_Devices/includes
 * @author     Your Name <email@example.com>
 */
class MS_Devices {

    /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MS_Devices_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $MS_Devices    The string used to uniquely identify this plugin.
	 */
	protected $MS_Devices;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MS_Devices_VERSION' ) ) {
			$this->version = MS_Devices_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->MS_Devices = 'ms-devices';

		$this->load_dependencies();
		$this->set_locale();
		//$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MS_Devices_Loader. Orchestrates the hooks of the plugin.
	 * - MS_Devices_i18n. Defines internationalization functionality.
	 * - MS_Devices_Admin. Defines all hooks for the admin area.
	 * - MS_Devices_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ms-devices-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ms-devices-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ms-device-management-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ms-device-management-public.php';

		$this->loader = new MS_Devices_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MS_Device_Management_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MS_Device_Management_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->loader->add_action( 'init', $this, 'register_post_types' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	
	private function register_taxonomy_locations () {

		$labels = array(
			'name'          => __('Räume'),
			'singular_name' => __('Raum'),
			'edit_item' 	=> __('Raum bearbeiten'),
		);

		$args = array(
			'labels'      	=> $labels,
			'public'      	=> true,
			//'menu_icon'		=> plugin_dir_url( MS_DM_FILE ) . '/src/menu-icon.png',
			'show_ui'		=> true,
			'show_in_menu'	=> true,
			'hierarchical'	=> true,

		);
	
		register_taxonomy( 
			'locations', 
			array('devices', 'items'),
			$args );
	}

	private function register_taxonomies () {
		$this->register_taxonomy_locations ();
	}


	private function register_posttype_devices () {

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
			'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
			'taxonomies'  => array( 'category', 'post_tag', 'locations' ),
		);
	
		register_post_type( 'devices', $args );
	}


	

	private function register_posttype_items () {
		

		$labels = array(
			'name'          => __('Bauteile'),
			'singular_name' => __('Bauteil'),
			'edit_item' 	=> __('Bauteil bearbeiten'),
		);

		$args = array(
			'labels'      			=> $labels,
			'public'      			=> true,
			'has_archive' 			=> true,
			'menu_icon'		  		=> plugin_dir_url( MS_DM_FILE ) . '/src/menu-icon.png',
			'supports'				=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
			'taxonomies'  			=> array( 'category', 'post_tag', 'locations' ),
		);
	
		register_post_type( 'items', $args );

		
	}


	public function wpt_events_location() {
		require( plugin_dir_path( MS_DM_FILE ) . 'src/partials/metabox-items.php' );
	}

	public function add_metaboxes() {

		add_meta_box(
			'items_price_metabox',
			'Preis pro Einheit',
			array( $this, 'wpt_events_location' ),
			'items',
			'normal',
			'default'
		);
	}


	public function register_post_types() {
		
		$this->register_posttype_devices();
		$this->register_posttype_items();
		
		$this->register_taxonomies();

		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );

	}
}