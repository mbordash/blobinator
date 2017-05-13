<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="emotion_result_json" style="display: none;">
    <?php echo get_post_meta( get_the_ID(), 'emotion', true ); ?>
</div>

<div id="emotion_result" style="display: none;">

    <h3><span><?php esc_attr_e( 'Emotions Detected', 'wp_public_style' ); ?></span></h3>

    <div id="emotion_chart">
        <svg id="emotion_chart_svg"></svg>
    </div>

</div>