<?php

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( !class_exists( 'CLPL_Admin' ) ) {

  /**
	 * Change Login Page Logo Admin class
	 *
	 * @package Change Login Page Logo
	 * @since 1.0.0
	 */
	class CLPL_Admin {

		/**
		 * Instance of CLPL_Admin class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Return unique instance of this class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function get_instance() {
		    if ( ! self::$instance ) {
		      self::$instance = new self();
		    }
		    return self::$instance;
		}
		
		public function __construct() {
			if (is_admin()) {
        add_action( 'admin_menu', array( &$this, 'register_admin_menu' ) );
      }

      add_action( 'admin_enqueue_scripts', array( &$this,'admin_scripts' ) );
			add_action( 'wp_ajax_clpl_save_settings', array( &$this,'save_settings' ) );
		}

    /**
		 * Include style in WordPress admin
		 * 
		 * @since 1.0.0
		 */
		function admin_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_media();
			wp_enqueue_style('clpl-admin-style', CLPL_CSS_URL.'admin.css');
		}

    /**
		 * Add submenu in WordPress admin settings menu
		 * 
		 * @since 1.0.0
		 */
		public function register_admin_menu() {
			add_options_page( CLPL_PLUGIN_NAME.' Settings', __( 'Change Login Logo', CLPL_TEXTDOMAIN ), 'manage_options', 'clpl_settings', array( &$this, 'settings' ) );
		}

		/**
		 * Load the setting page
		 * 
		 * @since 1.0.0
		 */
		public function settings() {
			include_once('pages/settings.php');
		}

    /**
		 * Save plugin settings
		 * 
		 * @since 1.0.0
		 * @return string (json)
		 */
		public function save_settings() {
			$response = array();
			$error = "";

			// Check for request security
			check_ajax_referer( 'clpl-save-settings', 'security' );

			// Check user capabilities
			if (!current_user_can('manage_options'))
				return;

			// Securing inputs
			$clpl_settings = array(
				"logo_image" => isset($_POST['clpl_settings']['logo_image']) ? esc_url_raw($_POST['clpl_settings']['logo_image']) : '',
				"logo_width" => isset($_POST['clpl_settings']['logo_width']) ? sanitize_text_field($_POST['clpl_settings']['logo_width']) : '',
				"logo_height" => isset($_POST['clpl_settings']['logo_height']) ? sanitize_text_field($_POST['clpl_settings']['logo_height']) : '',
				"logo_bottom_margin" => isset($_POST['clpl_settings']['logo_bottom_margin']) ? sanitize_text_field($_POST['clpl_settings']['logo_bottom_margin']) : '',
				"logo_link" => isset($_POST['clpl_settings']['logo_link']) ? esc_url_raw($_POST['clpl_settings']['logo_link']) : '',
			);

      // Save setting in WordPress options
      $result = update_option('clpl_settings', $clpl_settings);
      if ($result) {
        $response['status'] = 'success';
        $response['message'] = __( 'Settings updated successfully.', CLPL_TEXTDOMAIN );
      } else {
        $response['status'] = 'error';
        $response['message'] = __( 'Please update the settings and then save.', CLPL_TEXTDOMAIN );
      }

			echo json_encode($response);
			exit();
		}

  } // end class CLPL_Admin

	add_action( 'plugins_loaded', array( 'CLPL_Admin', 'get_instance' ) );

} // end class_exists