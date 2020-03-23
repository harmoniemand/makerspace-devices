<?php


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function remove_footer_admin () {
	echo '';
	}
	
	add_filter('admin_footer_text', 'remove_footer_admin');

if ( ! class_exists( 'MS_Devices_Main' ) ) {


	class MS_Devices_Main {

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
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}


		function __construct() {
			add_action('admin_enqueue_scripts', array($this, 'load_styles') );

            require_once plugin_dir_path( __FILE__ ) . '/PostTypes/Devices/devices.posttype.php';
            $devicePosttype = new DevicesPosttype();
            $devicePosttype->register();
		}

		public function load_styles() {
            // wp_enqueue_style('boot_css', plugins_url('assets/style.css',__FILE__ ));
            // wp_enqueue_script('jquery_datatables', plugins_url('assets/js/jquery.dataTables.min.js' ) );
        }

		public static function activate() { }

		public static function deactivate( $network_deactivating ) { }
    }

}