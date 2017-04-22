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


<div id="modal-blobinator-results" class="wrap hidden">

    <div id="dialog-blobinator-error" class="wrap hidden">Error</div>

    <div id="dialog-blobinator-success">

        <form id="analyze-text-form" name="analyze-text-form" action="" method="post" class="form">

            <input type="hidden" name="post_ID" value="<?php echo get_the_ID(); ?>" />
            <input type="hidden" name="action" value="blobinator_analyze" />

            <?php wp_nonce_field( 'ba_op_verify' ); ?>

            <input type="hidden" id="blobinator_text_to_analyze" name="blobinator_text_to_analyze" />

        </form>


        <h3><span><?php esc_attr_e( 'Sentiment Analysis', 'wp_admin_style' ); ?></span></h3>

        <div class="inside">
            <div id="overall_tone"></div>

            <div id="sentiment_chart">
                <svg></svg>
            </div>

        </div>



        <h3><span><?php esc_attr_e( 'Emotions Detected', 'wp_admin_style' ); ?></span></h3>

        <div class="inside">
            <div id="emotion_chart">
                <svg id="emotion_chart_svg"></svg>
            </div>
        </div>


        <br class="clear">

        <h3><span><?php esc_attr_e( 'Keywords Extracted', 'wp_admin_style' ); ?></span></h3>

        <div id="blobinator_add_keywords_as_tags_div">
            <button class="hidden" onClick="blobinator_add_keywords_to_post_tags(); return false;" class="button" type="submit" id="blobinator-add-keywords-as-tags" name="blobinator-add-keywords-as-tags">Add Keywords to Post Tags</button>
        </div>

        <div class="inside">
            <div id="keywords_chart">
                <svg></svg>
            </div>
        </div>

        <h3><span><?php esc_attr_e( 'Concepts Extracted', 'wp_admin_style' ); ?></span></h3>

        <div id="blobinator_add_concepts_as_tags_div">
            <button style="margin-bottom: 5px" class="hidden" onClick="blobinator_add_concepts_to_post_tags(); return false;" class="button" type="submit" id="blobinator-add-concepts-as-tags" name="blobinator-add-concepts-as-tags">Add Concepts to Post Tags</button>
        </div>

        <div id="concepts"></div>

        <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>

    </div>

</div>
