<?php


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once MS_DM_DIR . '/src/includes/class-logger.php';


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

            require plugin_dir_path( __FILE__ ) . 'includes/class-ms-devices.php';

			$plugin = new MS_DEVICES();
			$plugin->run();
		}


		public static function activate() {

			
		}

		public static function deactivate( $network_deactivating ) {

		}
    }

}