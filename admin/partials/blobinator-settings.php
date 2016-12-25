<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.blobinator.com
 * @since      1.0.0
 *
 * @package    Blobinator
 * @subpackage Blobinator/admin/partials
 */

$blobinator_settings = new Blobinator_Settings;
$blobinator_current_key = $blobinator_settings->blobinator_get_current_key();


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

    <h2>Blobinator Settings</h2>

    <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>


    <form method="post" name="blobinator_settings_form" class="form">

        <input type="hidden" name="action" value="blobinator_settings_form" />

        <?php wp_nonce_field( 'ba_op_verify' ); ?>

        <!-- remove some meta and generators from the <head> -->
        <fieldset>
            <legend class="blobinator_settings_form_key_text"><span>Update Auth Key</span></legend>
            <label for="<?php echo $this->plugin_name ?>-key">
                <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-key" name="<?php echo $this->plugin_name; ?> [key]" value="<?php echo $blobinator_current_key; ?>"/>
            </label>
        </fieldset>

        <p>

            <button onClick="handleFormPost(); return false;" class="button" type="submit" id="submit-analyze-blob" name="submit-analyze-blob">Submit</button>

        </p>

    </form>

</div>
