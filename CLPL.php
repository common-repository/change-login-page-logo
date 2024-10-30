<?php
/**
 * Plugin Name: Change Login Page Logo
 * Plugin URI: https://wordpress.org/plugins/change-login-page-logo/
 * Text Domain: CLPL_translation
 * Domain Path: /languages
 * Description: A simple and easy way to change WordPress login logo, using 'Change Login Page Logo' plugin you can change logo image, logo width, height and logo URL.
 * Version: 1.0.3
 * Author: Subodh Ghulaxe
 * Author URI: http://www.subodhghulaxe.com
 */

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( !class_exists( 'CLPL' ) ) {

	/**
	 * Change Login Page Logo class
	 *
	 * @package Change Login Page Logo
	 * @since 1.0.0
	 */
	class CLPL {

		/**
		 * Instance of CLPL class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Plugin settings
		 *
		 * @since 1.0.0
		 * @access public
		 * @var array
		 */
		public $clpl_settings = array();

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
		
		function __construct() {
			$this->constants();
			$this->text_domain();
			$this->clpl_settings = get_option('clpl_settings');

			add_action( 'init', array( &$this, 'init' ));
			add_action( 'login_enqueue_scripts', array( &$this,'login_scripts' ) );
			add_filter( 'login_headerurl', array( &$this, 'login_logo_url' ), 10, 1 );
		}
		
		/**
		 * Define plugin constants
		 *
		 * @since 1.0.0
		 */
		public function constants() {
			defined("CLPL_PLUGIN_NAME") || define( 'CLPL_PLUGIN_NAME', 'Change Login Page Logo' );
			defined("CLPL_BASEDIR") || define( 'CLPL_BASEDIR', dirname( plugin_basename(__FILE__) ) );
			defined("CLPL_TEXTDOMAIN") || define( 'CLPL_TEXTDOMAIN', 'CLPL_translation' );
			defined("CLPL_CSS_URL") || define( 'CLPL_CSS_URL', plugins_url('assets/css/',__FILE__) );
		}
		
		/**
		 * Load plugin text domain
		 *
		 * @since 1.0.0
		 */
		public function text_domain() {
			load_plugin_textdomain( CLPL_TEXTDOMAIN, false, CLPL_BASEDIR . '/languages' );
		}

		/**
		 * Runs after WordPress has finished loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			// Add settings link in plugin listing page.
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( &$this, 'add_action_links' ) );

			// Add donate link in plugin listing page.
			add_filter( 'plugin_row_meta', array( &$this, 'donate_link' ), 10, 2 );
		}

		/**
		 * Add settings link to plugin action links in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $links
		 * @return array
		 */
		public function add_action_links ( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'options-general.php?page=clpl_settings' ) . '">'.__( 'Settings', CLPL_TEXTDOMAIN ).'</a>',
			);

			return array_merge( $mylinks, $links );
		}

		/**
		 * Add donate link to plugin description in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $plugin_meta
		 * @param  string $plugin_file
		 * @return array
		 */
		public function donate_link( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) == $plugin_file )
				$plugin_meta[] = sprintf(
					'&hearts; <a href="%s" target="_blank">%s</a>',
					'https://www.patreon.com/subodhghulaxe',
					__( 'Donate', CLPL_TEXTDOMAIN )
			);
			
			return $plugin_meta;
		}

    /**
		 * Include style and script in WordPress login
		 * 
		 * @since 1.0.0
		 */
		function login_scripts() {
      ?>
      <style type="text/css">
        body.login div#login h1 a {
          <?php if (isset($this->clpl_settings['logo_image']) && !empty($this->clpl_settings['logo_image'])) { ?>
            background-image: url(<?php echo esc_url($this->clpl_settings['logo_image']); ?>) !important;
          <?php } ?>
          padding: 0;
          margin: 0 auto;
          <?php if (isset($this->clpl_settings['logo_bottom_margin']) && !empty($this->clpl_settings['logo_bottom_margin'])) { ?>
            margin-bottom: <?php echo esc_attr($this->clpl_settings['logo_bottom_margin']); ?>px;
          <?php } ?>
          <?php if (isset($this->clpl_settings['logo_width']) && !empty($this->clpl_settings['logo_width'])) { ?>
            width: <?php echo esc_attr($this->clpl_settings['logo_width']); ?>px;
          <?php } ?>
          <?php if (isset($this->clpl_settings['logo_height']) && !empty($this->clpl_settings['logo_height'])) { ?>
            height: <?php echo esc_attr($this->clpl_settings['logo_height']); ?>px;
          <?php } ?>
          <?php if (isset($this->clpl_settings['logo_width']) && isset($this->clpl_settings['logo_height'])) { ?>
            background-size: <?php echo esc_attr($this->clpl_settings['logo_width']); ?>px <?php echo esc_attr($this->clpl_settings['logo_height']); ?>px;
          <?php } ?>
        }
      </style>
      <?php
    }

    /**
		 * Change logo link
		 * 
		 * @since 1.0.0
		 */
    public function login_logo_url( $url ) {
			return isset($this->clpl_settings['logo_link']) && !empty($this->clpl_settings['logo_link']) ? 
			esc_url($this->clpl_settings['logo_link']) : get_bloginfo('url');
		}


	} // end class CLPL
	
	add_action( 'plugins_loaded', array( 'CLPL', 'get_instance' ) );

	include_once('admin/CLPL_Admin.php');

} // end class_exists