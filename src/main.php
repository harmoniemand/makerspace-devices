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
			add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));


			// Load Feeds

			require_once plugin_dir_path(__FILE__) . '/feeds/feeds.php';
			$feedsMain = new FeedsMain();
			$feedsMain->register();


			// Load Submodules

			require_once dirname(__FILE__) . '/Settings/settings.controller.php';
			$settingsEntity = SettingsEntity::instance();
			$settingsEntity->register();
			
			require_once dirname(__FILE__) . '/Registration/registration.entity.php';
			$registrationEntity = RegistrationEntity::instance();
			$registrationEntity->register();

			require_once plugin_dir_path(__FILE__) . '/MyAccount/my-account.controller.php';
			$myAccountMain = new MyAccountMain();
			$myAccountMain->register();

			require_once plugin_dir_path(__FILE__) . '/Devices/devices.posttype.php';
			$devicePosttype = new DevicesPosttype();
			$devicePosttype->register();

			require_once plugin_dir_path(__FILE__) . '/SecurityInstructions/security-instructions.posttype.php';
			$securityInstructionPosttype = new SecurityInstructionPosttype();
			$securityInstructionPosttype->register();

			require_once dirname(__FILE__) . '/Reservation/reservation.controller.php';
			$reservation = ReservationEntity::instance();
			$reservation->register();

			require_once dirname(__FILE__) . '/Workshops/workshop.posttype.php';
			$workshopPostType = WorkshopPostType::instance();
			$workshopPostType->register();

			require_once dirname(__FILE__) . '/Nextcloud/nextcloud.controller.php';
			$nextcloudController = NextcloudController::instance();
			$nextcloudController->register();

			require_once dirname(__FILE__) . '/Users/users.controller.php';
			$usersController = UsersController::instance();
			$usersController->register();

			require_once dirname(__FILE__) . '/events/events.posttype.php';
			$eventPostType = EventPostType::instance();
			$eventPostType->register();

			// require_once dirname(__FILE__) . '/_Debug/debug.controller.php';
			// $debugController = DebugController::instance();
			// $debugController->register();
		}

		public function load_styles()
		{
			wp_enqueue_style('css-bootstrap', plugins_url('/../node_modules/bootstrap/dist/css/bootstrap.min.css', __FILE__));
			wp_enqueue_style('css-clr-icons', plugins_url('/../node_modules/@clr/icons/clr-icons.css', __FILE__));

			wp_enqueue_script('js-custom-elements', plugins_url('/../node_modules/@webcomponents/custom-elements/custom-elements.min.js', __FILE__));
			wp_enqueue_script('js-clr-icons', plugins_url('/../node_modules/@clr/icons/clr-icons.min.js', __FILE__));
			wp_enqueue_script('js-bootstrap-util', plugins_url('/../node_modules/bootstrap/js/dist/util.js', __FILE__));
			wp_enqueue_script('js-bootstrap-collapse', plugins_url('/../node_modules/bootstrap/js/dist/collapse.js', __FILE__));

			wp_enqueue_style('css-custom-styles', plugins_url('styles/styles.css', __FILE__));
			wp_enqueue_style('css-custom-calendar', plugins_url('styles/calendar.css', __FILE__));
		}

		public function remove_dashboard_widgets()
		{
			if (current_user_can('administrator')) {
				// remove nothing
			} else {
				remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Quick Press widget
				remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Recent Drafts
				remove_meta_box('dashboard_primary', 'dashboard', 'side'); //WordPress.com Blog
				remove_meta_box('dashboard_secondary', 'dashboard', 'side'); //Other WordPress News
				remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Incoming Links
				remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Plugins
				remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Right Now
				remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); //Gravity Forms
				remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Recent Comments
				remove_meta_box('icl_dashboard_widget', 'dashboard', 'normal'); //Multi Language Plugin
				remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Activity
				remove_action('welcome_panel', 'wp_welcome_panel');
			}

		}


		public static function activate()
		{
			// entities

			require_once dirname(__FILE__) . '/entities/event.entity.php';
			require_once dirname(__FILE__) . '/entities/event-workshop.entity.php';
			require_once dirname(__FILE__) . '/entities/event-workshop-registration.entity.php';

			EventEntity::create_database_tables();
			EventWorkshopEntity::create_database_tables();
			EventWorkshopRegistrationEntity::create_database_tables();


			// Types

			require_once dirname(__FILE__) . '/Reservation/reservation.controller.php';
			$reservation = ReservationEntity::instance();
			$reservation->activate();

			
			require_once dirname(__FILE__) . '/Workshops/workshop.posttype.php';
			$workshopPostType = WorkshopPostType::instance();
			$workshopPostType->activate();
		}

		public static function deactivate($network_deactivating)
		{
		}
	}
}
