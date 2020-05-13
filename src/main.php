<?php


if (!defined('ABSPATH')) {
	die('-1');
}

function remove_footer_admin()
{
	echo '';
}

add_filter('admin_footer_text', 'remove_footer_admin');

if (!class_exists('MS_Devices_Main')) {


	class MS_Devices_Main
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
			add_action('admin_enqueue_scripts', array($this, 'load_styles'));

			require_once plugin_dir_path(__FILE__) . '/PostTypes/Devices/devices.posttype.php';
			$devicePosttype = new DevicesPosttype();
			$devicePosttype->register();

			require_once dirname(__FILE__) . '/Entities/Reservation/reservation.entity.php';
			$reservation = ReservationEntity::instance();
			$reservation->register();
		}

		public function load_styles()
		{
			wp_enqueue_style('css-bootstrap', plugins_url('/../node_modules/bootstrap/dist/css/bootstrap.min.css', __FILE__));
			wp_enqueue_style('css-clr-icons', plugins_url('/../node_modules/@clr/icons/clr-icons.min.css', __FILE__));
			
			wp_enqueue_script('js-custom-elements', plugins_url('/../node_modules/@webcomponents/custom-elements/custom-elements.min.js', __FILE__));
			wp_enqueue_script('js-clr-icons', plugins_url('/../node_modules/@clr/icons/clr-icons.min.js', __FILE__));

			wp_enqueue_style('css-custom-styles', plugins_url('styles/styles.css', __FILE__));
			wp_enqueue_style('css-custom-calendar', plugins_url('styles/calendar.css', __FILE__));
		}

		public static function activate()
		{
			require_once dirname(__FILE__) . '/Entities/Reservation/reservation.entity.php';
			$reservation = ReservationEntity::instance();
			$reservation->activate();
		}

		public static function deactivate($network_deactivating)
		{
		}
	}
}
