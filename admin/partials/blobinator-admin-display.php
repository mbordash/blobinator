<?php
/**
 * Created by PhpStorm.
 * User: michaelbordash
 * Date: 4/21/17
 * Time: 11:04 PM
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <form action="options.php" method="post">

        <?php

            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            submit_button();

        ?>

    </form>

</div>