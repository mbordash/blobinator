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



    /**
     * Add an options page under the Tools submenu
     *
     * @since  1.0.0
     */
    public function add_blobinator_settings_page() {
        $this->plugin_screen_hook_suffix = add_options_page(
            __( 'Blobinator Settings', 'blobinator' ),
            __( 'Blobinator Settings', 'blobinator' ),
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
        include_once 'partials/blobinator-settings.php';
    }

    public function process_blobinator_text() {

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to be on this page.' );
        }

        check_admin_referer( 'ba_op_verify' );

        if ( isset( $_POST['blobinator_text_to_analyze'] ) && $_POST['blobinator_text_to_analyze'] !== '' ) {

            $textToAnalyze = sanitize_text_field($_POST['blobinator_text_to_analyze']);


            // this should be proxied through my server, testing for now.
            // setup post data for appender

            switch ($_POST['service']) {

                case "concepts" :

                    $appenderToCall = "watson-concept-insights";

                    $postData =  json_encode(array(
                        'config' => array(
                            'orgId' => 'go4ZwT',
                            'documentId' => 'document-01',
                            'options' => array(
                                'appenders' => array(
                                    $appenderToCall
                                )
                            )
                        ),
                        'details' => array(
                            'name' => 'text-content',
                            'sources' => array(
                                array(
                                    'id' => 'document-01',
                                    'type' => 'text',
                                    'content' => array(
                                        'text' => $textToAnalyze
                                    )
                                )
                            )
                        )
                    ));

                    // execute call to cognitive appender
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://ca-qa1-ui.adm01.com/service/1.0/appender/go4ZwT/command/process-document');
                    // curl_setopt($ch, CURLOPT_URL, 'https://dev.api.ibm.com/appender/test/service/1.0/appender/go4ZwT/command/process-document');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer 83a9c7fb-9b64-4df3-8cd2-58df3b26eabe',
                        'Cache-Control: no-cache',
                        'x-ibm-client-id: cd3ef4b8-4bc4-40a5-8c54-9d9ccb862bd6',
                        'x-ibm-client-secret: bN0wR8rL0jN5kD1bA3cE0oX6iP8wF7bK4pO4fO6uT6wH8yC5yW'
                    ));

                    $caOutput = curl_exec($ch);

                    $resultsObject = json_decode($caOutput);

                    $caResponse = array();

                    $counter = 0;
                    foreach( $resultsObject->details->appenders->{'watson-concept-insights'}->concepts as $concepts ) {
                        $counter++;
                        $caResponse[] = $concepts;

                        if ($counter >= 20) {
                            break;
                        }
                    }

                    curl_close($ch);

                    break;

                case "sentiment" :
                    $appenderToCall = "watson-sentiment";

                    $postData =  json_encode(array(
                        'config' => array(
                            'orgId' => 'go4ZwT',
                            'documentId' => 'document-01',
                            'options' => array(
                                'appenders' => array(
                                    $appenderToCall
                                )
                            )
                        ),
                        'details' => array(
                            'name' => 'text-content',
                            'sources' => array(
                                array(
                                    'id' => 'document-01',
                                    'type' => 'text',
                                    'content' => array(
                                        'text' => $textToAnalyze
                                    )
                                )
                            )
                        )
                    ));

                    // execute call to cognitive appender
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://ca-qa1-ui.adm01.com/service/1.0/appender/go4ZwT/command/process-document');
                    // curl_setopt($ch, CURLOPT_URL, 'https://dev.api.ibm.com/appender/test/service/1.0/appender/go4ZwT/command/process-document');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer 83a9c7fb-9b64-4df3-8cd2-58df3b26eabe',
                        'Cache-Control: no-cache',
                        'x-ibm-client-id: cd3ef4b8-4bc4-40a5-8c54-9d9ccb862bd6',
                        'x-ibm-client-secret: bN0wR8rL0jN5kD1bA3cE0oX6iP8wF7bK4pO4fO6uT6wH8yC5yW'
                    ));

                    $caOutput = curl_exec($ch);

                    $resultsObject = json_decode($caOutput);

                    $caResponse = array();

                    $counter = 0;
                    foreach( $resultsObject->details->appenders->{'watson-sentiment'}->docSentiment as $sentiment ) {
                        $counter++;
                        $caResponse[] = $sentiment;
                    }

                    curl_close($ch);

                    break;


                case "keywords" :
                    $appenderToCall = "watson-keywords";

                    $postData =  json_encode(array(
                        'config' => array(
                            'orgId' => 'go4ZwT',
                            'documentId' => 'document-01',
                            'options' => array(
                                'appenders' => array(
                                    $appenderToCall
                                )
                            )
                        ),
                        'details' => array(
                            'name' => 'text-content',
                            'sources' => array(
                                array(
                                    'id' => 'document-01',
                                    'type' => 'text',
                                    'content' => array(
                                        'text' => $textToAnalyze
                                    )
                                )
                            )
                        )
                    ));

                    // execute call to cognitive appender
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://ca-qa1-ui.adm01.com/service/1.0/appender/go4ZwT/command/process-document');
                    // curl_setopt($ch, CURLOPT_URL, 'https://dev.api.ibm.com/appender/test/service/1.0/appender/go4ZwT/command/process-document');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer 83a9c7fb-9b64-4df3-8cd2-58df3b26eabe',
                        'Cache-Control: no-cache',
                        'x-ibm-client-id: cd3ef4b8-4bc4-40a5-8c54-9d9ccb862bd6',
                        'x-ibm-client-secret: bN0wR8rL0jN5kD1bA3cE0oX6iP8wF7bK4pO4fO6uT6wH8yC5yW'
                    ));

                    $caOutput = curl_exec($ch);

                    $resultsObject = json_decode($caOutput);

                    $caResponse = array();

                    $counter = 0;
                    foreach( $resultsObject->details->appenders->{'watson-keywords'}->keywords as $keywords ) {
                        $counter++;
                        $caResponse[] = $keywords;
                        if( $counter >= 20 ) {
                            break;
                        }
                    }

                    curl_close($ch);

                    break;

                case "emotion" :
                    $appenderToCall = "watson-emotion";

                    $postData =  json_encode(array(
                        'config' => array(
                            'orgId' => 'go4ZwT',
                            'documentId' => 'document-01',
                            'options' => array(
                                'appenders' => array(
                                    $appenderToCall
                                )
                            )
                        ),
                        'details' => array(
                            'name' => 'text-content',
                            'sources' => array(
                                array(
                                    'id' => 'document-01',
                                    'type' => 'text',
                                    'content' => array(
                                        'text' => $textToAnalyze
                                    )
                                )
                            )
                        )
                    ));

                    // execute call to cognitive appender
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://ca-qa1-ui.adm01.com/service/1.0/appender/go4ZwT/command/process-document');
                    // curl_setopt($ch, CURLOPT_URL, 'https://dev.api.ibm.com/appender/test/service/1.0/appender/go4ZwT/command/process-document');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer 83a9c7fb-9b64-4df3-8cd2-58df3b26eabe',
                        'Cache-Control: no-cache',
                        'x-ibm-client-id: cd3ef4b8-4bc4-40a5-8c54-9d9ccb862bd6',
                        'x-ibm-client-secret: bN0wR8rL0jN5kD1bA3cE0oX6iP8wF7bK4pO4fO6uT6wH8yC5yW'
                    ));

                    $caOutput = curl_exec($ch);

                    $caResponse = json_decode($caOutput);

                    $caResponse = $caResponse->details->appenders->{'watson-emotion'}->docEmotions;

                    curl_close($ch);

                    break;

                default:
                    wp_die("Failed to include service.");

            }


        } else {

            $caResponse = "Nothing to Analyze";

        }

        echo json_encode($caResponse);
        error_log(json_encode($caResponse));

        exit();

    }

    function blobinator_add_button( $plugin_array ) {

        $plugin_array['blobinator'] = plugin_dir_url( __FILE__ ) . 'js/blobinator-admin.js';
        return $plugin_array;

    }

    function blobinator_register_button( $buttons ) {

        array_push( $buttons, 'blobinator' );
        return $buttons;

    }

    function blobinator_create_results_div( ) {

        include_once 'partials/modal-blobinator-analyze-display.php';

    }

}
