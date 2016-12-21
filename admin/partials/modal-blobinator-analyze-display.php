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

<div id='modal-blobinator-results' class="wrap hidden">

    <form id="analyze-text-form" name="analyze-text-form" action="" method="post" class="form">

        <input type="hidden" name="action" value="blobinator_analyze" />

        <?php wp_nonce_field( 'ba_op_verify' ); ?>

        <input type="hidden" id="blobinator_text_to_analyze" name="blobinator_text_to_analyze" />

    </form>


    <div id="overall_tone"></div>

    <div id="emotion_chart_title"></div>
    <div id="emotion_chart">
        <svg></svg>
    </div>

    <div id="keywords_chart_title"></div>
    <div id="keywords_chart">
        <svg></svg>
    </div>

    <div id="concepts_chart_title"></div>
    <div id="concepts"></div>

    <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>

</div>
