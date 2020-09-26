<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Blobinator_Public {

    private $option_name = 'blobinator';

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( $this->plugin_name . 'nvd3', plugin_dir_url( __FILE__ ) . 'css/nv.d3.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blobinator-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script( $this->plugin_name . '-d3', plugin_dir_url( __FILE__ ) . 'js/d3.v3.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-nvd3', plugin_dir_url( __FILE__ ) . 'js/nv.d3.v3.min.js', array( $this->plugin_name . '-d3' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blobinator-public.js', array( 'jquery' ), $this->version, true );

        $blobinator_local_arr = array(
            'apikey'    => get_option( $this->option_name . '_apikey' )
        );

        wp_localize_script( $this->plugin_name, 'blobinatorAjaxObject', $blobinator_local_arr );

	}

    public function blobinator_create_public_results_div( $content ) {

        $sentimentPermission    = get_option( $this->option_name . '_sentiment' );
        $emotionPermission      = get_option( $this->option_name . '_emotion' );
        $output                 = '';
        $currentPostId          = get_the_ID();

        $blobinator = new Blobinator_Admin( $this->plugin_name, $this->version );

	    if( is_singular() ) {

	        if( $sentimentPermission === 'yes' ) {

                if (!get_post_meta($currentPostId, 'sentiment')) {


                    $blobinator->blobinator_cognitive( $content, "sentiment", $currentPostId );

                }

                ob_start();
                include_once('partials/blobinator-sentiment-div.php');
                $output .= ob_get_clean();

	        }

            if( $emotionPermission === 'yes' ) {

                if (!get_post_meta(get_the_ID(), 'emotion')) {

                    $blobinator->blobinator_cognitive( $content, "emotion", $currentPostId );

                }

                ob_start();
                include_once('partials/blobinator-emotion-div.php');
                $output .= ob_get_clean();

            }


        }

        return $content . $output;
    }

}
