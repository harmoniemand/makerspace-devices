<?php

require_once MS_DM_DIR . '/src/includes/class-logger.php';


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

		
		
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles');

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

		//$this->loader->add_action( 'init', $this, 'register_taxonomies' );
		$this->loader->add_action( 'init', $this, 'register_post_types' );

		$this->loader->add_action( 'add_meta_boxes', $this, 'add_metaboxes' );
		$this->loader->add_action( "init", $this, 'save_custom_meta_box' );

	}

	public function enqueue_styles () {

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	/** CUSTOM TAXONOMIES */

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

	private function register_taxonomy_device_categories () {
		$labels = array(
			'name'          => __('Gerätekategorien'),
			'singular_name' => __('Gerätekategorie'),
			'edit_item' 	=> __('Gerätekategorie bearbeiten'),
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
			'device_categories', 
			array('devices'),
			$args );
	}

	private function register_taxonomies () {
		$this->register_taxonomy_locations ();
		$this->register_taxonomy_device_categories();
	}

	/** CUSTOM POSTTYPES */

	

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

	public function register_post_types() {
		$this->register_taxonomies();

		require_once(plugin_dir_path( MS_DM_FILE ) . 'src/posttypes/devices.posttype.php');
		DevicesPosttype::register();

		$this->register_posttype_items();
	}


	/** METABOXES */

	public function render_metabox_items() {
		require( plugin_dir_path( MS_DM_FILE ) . 'src/partials/metabox-items.php' );
	}

	public function render_metabox_devices () {
		require( plugin_dir_path( MS_DM_FILE ) . 'src/partials/metabox-devices.php' );
	}

	public function add_metaboxes() {

		add_meta_box(
			'items_price_metabox',
			'Preis pro Einheit',
			array( $this, 'render_metabox_items' ),
			'items',
			'normal',
			'default'
		);

		add_meta_box(
			'devices_metabox',
			'Gerätespezifische Angaben',
			array( $this, 'render_metabox_devices' ),
			'devices',
			'normal',
			'high'
		);

		MSDM_Logger::Debug("register save_post_devices hook");
	}

	public function save_custom_meta_box ()
	{
		$pid = $_POST["post_ID"];


		if ( $pid == NULL )
			return;

		// MSDM_Logger::Debug("post_id:" . $post_id);


		if(!current_user_can("edit_post", $pid)){
			MSDM_Logger::Debug('cannot edit post');
			return $pid;
		}

		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){
			MSDM_Logger::Debug('doing autosave');
			return $pid;
		}

		if(isset($_POST["betriebsanweisung_attachment_id"])) {
			$betriebsanweisung_attachment_id = $_POST["betriebsanweisung_attachment_id"];
			update_post_meta($pid, "betriebsanweisung_attachment_id", $betriebsanweisung_attachment_id);
		}

		if(isset($_POST["datenblatt_attachment_id"])) {
			$datenblatt_attachment_id = $_POST["datenblatt_attachment_id"];
			update_post_meta($pid, "datenblatt_attachment_id", $datenblatt_attachment_id);
		}

		if(isset($_POST["bedienungsanleitung_attachment_id"])) {
			$bedienungsanleitung_attachment_id = $_POST["bedienungsanleitung_attachment_id"];
			update_post_meta($pid, "bedienungsanleitung_attachment_id", $bedienungsanleitung_attachment_id);
		}
	}


	
}