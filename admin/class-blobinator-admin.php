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

    protected $api_host = 'https://www.blobinator.com';
    //protected $api_host         = 'http://wp-blobinator:8080';
    protected $api_path         = '/api/blobinator';
    protected $api_manager_path = '/';

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

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;

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

        wp_enqueue_style( $this->plugin_name . 'nvd3', plugin_dir_url( __FILE__ ) . 'css/nv.d3.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blobinator-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blobinator-admin.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-d3', plugin_dir_url( __FILE__ ) . 'js/d3.v3.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-nvd3', plugin_dir_url( __FILE__ ) . 'js/nv.d3.min.js', array( $this->plugin_name . '-d3' ), $this->version, true );
        wp_enqueue_script( 'jquery-ui-dialog' );

	}

    public function blobinator_add_button( $plugin_array ) {

        $plugin_array['blobinator'] = plugin_dir_url( __FILE__ ) . 'js/blobinator-admin.js';
        return $plugin_array;

    }

    public function blobinator_register_button( $buttons ) {

        array_push( $buttons, 'blobinator' );
        return $buttons;

    }

    public function blobinator_create_results_div( ) {

        include_once 'partials/modal-blobinator-analyze-display.php';

    }

    /**
     * Handle ajax request for text processing and display
     *
     * @since  1.0.0
     */
    public function blobinator_process_text() {

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to be on this page.' );
        }

        //get and check API key exists, pass key along server side request
        $blobinatorOptions      = get_option('blobinator_content_analyzer_-_free_data');
        $blobinatorApiKey       = 'wc_order_58773985ef2e1_am_Vu6R0EbYeLPE';
        $blobinatorApiEmail     = 'trial_key@blobinator.com';
        $blobinatorProductId    = get_option('blobinator_content_analyzer_-_free_product_id');
        $blobinatorInstanceId   = get_option('blobinator_content_analyzer_-_free_instance');

        if ( !isset($blobinatorApiKey) || $blobinatorApiKey === '' ) {

            $response_array['status'] = "error";
            $response_array['message'] = "Your License Key for Blobinator is not set. Please go to Settings > Blobinator Content Analyzer - Free API Key Activation to set your key first.";

            header('Content-type: application/json');
            echo json_encode($response_array);

            wp_die();

        }

        check_admin_referer( 'ba_op_verify' );

        if ( isset( $_POST['blobinator_text_to_analyze'] ) && $_POST['blobinator_text_to_analyze'] !== '' ) {

            $textToAnalyze = urlencode(sanitize_text_field($_POST['blobinator_text_to_analyze']));
            $service = sanitize_text_field($_POST['service']);


            $requestBody = array(
                'blobinator_text_to_analyze'    => $textToAnalyze,
                'service'                       => $service,
                'blobinator_api_key'            => $blobinatorApiKey,
                'blobinator_activation_email'   => $blobinatorApiEmail,
                'blobinator_product_id'         => $blobinatorProductId,
                'blobinator_instance_id'        => $blobinatorInstanceId
            );

            $opts = array(
                'body'      => $requestBody,
                'headers'   => 'Content-type: application/x-www-form-urlencoded'
            );

            $response = wp_remote_post($this->api_host . $this->api_path, $opts);

            if( $response['response']['code'] == 200 ) {

                echo $response['body'];
                error_log($response['body']);

            } else {

                $response_array['status'] = "error";
                $response_array['message'] = "Something went wrong with this request. Code received: " . $response['response']['code'];

                header('Content-type: application/json');
                echo json_encode($response_array);

            }
        }

        exit();

    }

}
