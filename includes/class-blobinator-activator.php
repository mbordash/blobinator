<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.blobinator.com
 * @since      1.0.0
 *
 * @package    Blobinator
 * @subpackage Blobinator/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Blobinator
 * @subpackage Blobinator/includes
 * @author     Michael Bordash <michael@internetdj.com>
 */
class Blobinator_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        // request new key and store as wordpress key/val

        $blobinator_settings = new Blobinator_Settings;

        $blobinator_new_key = $blobinator_settings->blobinator_get_new_key();

        $blobinator_settings->blobinator_set_new_key($blobinator_new_key);

	}

}
