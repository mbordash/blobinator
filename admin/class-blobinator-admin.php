<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.blobinator.com
 * @since      1.0.0
 *
 * @package    Blobinator
 * @subpackage Blobinator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blobinator
 * @subpackage Blobinator/admin
 * @author     Michael Bordash <michael@internetdj.com>
 */
class Blobinator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blobinator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blobinator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blobinator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blobinator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blobinator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blobinator-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function add_blobinator_page() {
        $this->plugin_screen_hook_suffix = add_management_page(
            __( 'Analyze Blob', 'blobinator' ),
            __( 'Analyze Blob', 'blobinator' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_blobinator_page' )
        );
    }
    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_blobinator_page() {
        include_once 'partials/blobinator-analyze-display.php';
    }

    function process_blobinator_text() {

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to be on this page.' );
        }

        check_admin_referer( 'ba_op_verify' );

        if ( isset( $_POST['blobinator_text'] ) ) {

            $textToAnalyze = sanitize_text_field($_POST['blobinator_text']);

            // go to APPENDER!
            // then display output to user
            // this just returns the input as a query string for testing
            // mjb

            if ( $referer = wp_get_referer() ) {
                echo $textToAnalyze;

                $location =  $referer;
                $location = add_query_arg( array( 'm' => $textToAnalyze ), $location );
                wp_redirect( $location );
            }

        }

        echo "here";
    }

}
