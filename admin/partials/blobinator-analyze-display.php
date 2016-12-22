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

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

    <h2>Analyze a Blob of Text using the Blobinator</h2>

    <form id="analyze-text-form" name="analyze-text-form" action="" method="post" class="form">

        <input type="hidden" name="action" value="blobinator_analyze" />

        <?php wp_nonce_field( 'ba_op_verify' ); ?>

        <p>

            <textarea cols="80" rows="10" class="large-text" id="blobinator_text" name="blobinator_text" placeholder="Input text to analyze"></textarea>

        </p>

        <p>

            <button onClick="handleFormPost(); return false;" class="button" type="submit" id="submit-analyze-blob" name="submit-analyze-blob">Submit</button>

        </p>

    </form>


    <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>

    <div id="sentiment" class="notice notice-success inline hidden"></div>

    <div id="emotion" class="notice notice-success inline hidden"></div>

    <div id="concepts" class="notice notice-success inline hidden"></div>

    <div id="keywords" class="notice notice-success inline hidden"></div>

</div>
