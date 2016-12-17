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

    <?php

    if ( isset( $_GET['m'] ) ) {

    ?>

    <div class='notice notice-success is-dismissible'><p><strong><?php echo esc_html($_GET['m']); ?></strong></p></div>

    <?php
    }
    ?>


    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">

        <input type="hidden" name="action" value="blobinator_analyze" />

        <?php wp_nonce_field( 'ba_op_verify' ); ?>

        Input text to analyze: <input type="text" name="blobinator_text" />

        <?php echo get_submit_button('Submit'); ?>
    </form>
</div>
