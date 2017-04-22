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

<div id="sentiment_result_json" style="display: none;">
    <?php echo json_encode( get_post_meta( get_the_ID( ), 'sentiment' ), JSON_UNESCAPED_SLASHES ); ?>
</div>

<div id="sentiment_result" style="display: none;">

    <h3><span><?php esc_attr_e( 'Sentiment Analysis', 'wp_public_style' ); ?></span></h3>

    <div id="overall_tone"></div>

    <div id="sentiment_chart">
        <svg></svg>
    </div>

</div>