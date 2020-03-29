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
 * @package    Customizations
 * @subpackage Customizations/includes
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
 * @package    Customizations
 * @subpackage Customizations/includes
 * @author     Your Name <email@example.com>
 */
class Customizations {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Customizations_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

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
		if ( defined( 'CUSTOMIZATIONS_VERSION' ) ) {
			$this->version = CUSTOMIZATIONS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'customizations';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Customizations_Loader. Orchestrates the hooks of the plugin.
	 * - Customizations_i18n. Defines internationalization functionality.
	 * - Customizations_Admin. Defines all hooks for the admin area.
	 * - Customizations_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-customizations-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-customizations-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-customizations-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-customizations-public.php';

		$this->loader = new Customizations_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Customizations_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Customizations_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Customizations_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Customizations_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
		function add_loginout_link( $items, $args ) {
	            if (is_user_logged_in() && $args->theme_location == 'primary') {
			$items .= '<li><a href="'.  get_permalink( woocommerce_get_page_id( 'myaccount' ) )  .'">My Account</a></li>';
                    }
	            elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
			$items .= '<li><a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">Log In</a></li>';
	            }
		    return $items;
	        }

		function my_prefix_upload_mimes( $mimes ) {
			// Add PhotoShop PSD files to list of permitted WordPress mime types
			$mimes['eps'] = "application/postscript";
			$mimes['ai'] = "application/pdf";
			$mimes['svg']  = 'image/svg+xml';

			return $mimes;
		}
		add_filter( 'upload_mimes', 'my_prefix_upload_mimes' );
	        // Add PDFs to list of permitted mime types
		function my_prefix_pewc_get_permitted_mimes( $permitted_mimes ) {
		  // Add PDF to the list of permitted mime types
		  $permitted_mimes['pdf'] = "application/pdf";
		  $permitted_mimes['ai'] = "application/ai";
		  $permitted_mimes['eps'] = "application/postscript";
		  // Remove a mime type - uncomment the line below if you wish to prevent JPGs from being uploaded
		  unset( $permitted_mimes['jpg|jpeg|jpe'] );
		  unset( $permitted_mimes['png'] );
		  unset( $permitted_mimes['gif'] );
		  return $permitted_mimes;
		}
		add_filter( 'pewc_permitted_mimes', 'my_prefix_pewc_get_permitted_mimes' );

		// Add to the list of restricted filetypes
		function my_prefix_pewc_protected_directory_allowed_filetypes( $restricted_filetypes ) {
		  $restricted_filetypes[] = 'pdf';
		  $restricted_filetypes[] = 'ai';
		  $restricted_filetypes[] = 'eps';
		  return $restricted_filetypes;
		}
		add_filter( 'pewc_protected_directory_allowed_filetypes', 'my_prefix_pewc_protected_directory_allowed_filetypes' );
      
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Customizations_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
