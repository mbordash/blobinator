<?php
/**
 * Created by PhpStorm.
 * User: michaelbordash
 * Date: 12/24/16
 * Time: 9:49 PM
 */


class Blobinator_Settings
{

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( ) {

    }

    private function guidv4($data) {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function blobinator_get_new_key() {

        // obtain free key from core, creating one here as placeholder

        $blobinator_auth_key = $this->guidv4(random_bytes(16));

        return $blobinator_auth_key;

    }

    public function blobinator_set_new_key($blobinator_new_key) {

        // set key as WP option

        update_option( 'blobinator_auth_key', $blobinator_new_key, '', 'yes' );

        return $blobinator_new_key;

    }

    public function blobinator_get_current_key() {

        return get_option( 'blobinator_auth_key');

    }

    public function blobinator_delete_current_key() {

        delete_option( 'blobinator_auth_key' );

        return true;

    }
}