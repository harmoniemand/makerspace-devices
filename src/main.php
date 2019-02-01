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


		private static function get_location_makerspace($name, $room) {
			return array(
					'post_title'  => __( $name ),
					'post_status' => 'publish',
					'post_author' => $current_user->ID,
					'post_type'   => 'locations',
					'meta_input'  => array(
						'room_number'	=> $room,
					)
				);
		}
		private static function get_location_sfz($name, $room) {
			return array(
					'post_title'  	=> __( $name ),
					'post_status' 	=> 'publish',
					'post_author' 	=> $current_user->ID,
					'post_type'   	=> 'locations',
					'meta_input'  	=> array(
						'opening_time_monday'		=> '15:00 - 19:00',
						'opening_time_tuesday'		=> '15:00 - 19:00',
						'opening_time_wednesday'	=> '15:00 - 19:00',
						'opening_time_thursday'		=> '15:00 - 19:00',
						'opening_time_friday'		=> '15:00 - 19:00',
						'opening_time_saturday'		=> '15:00 - 19:00',
						'opening_time_sunday'		=> '15:00 - 19:00',

						'room_number'				=> $room,
					)
				);
		}


		public static function activate() {

			
		}

		public static function deactivate( $network_deactivating ) {

		}
    }

}