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

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">

                <div class="postbox">

                    <h2><span><?php esc_attr_e( 'Emotions Detected', 'wp_admin_style' ); ?></span></h2>

                    <div class="inside">
                        <div id="emotion_chart">
                            <svg id="emotion_chart_svg"></svg>
                        </div>
                    </div>

                </div>

            </div>

            <div id="postbox-container-1" class="postbox-container">

                <div class="postbox">

                    <h2><span><?php esc_attr_e( 'Tone Analysis', 'wp_admin_style' ); ?></span></h2>

                    <div class="inside">
                        <div id="overall_tone"></div>

                        <div id="sentiment_chart">
                            <svg></svg>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <br class="clear">

    <h4><span><?php esc_attr_e( 'Keywords Extracted', 'wp_admin_style' ); ?></span></h4>

    <div class="inside">
        <div id="keywords_chart">
            <svg></svg>
        </div>

    </div>




    <div id="concepts_chart_title"></div>
    <div id="concepts"></div>

    <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>

</div>
