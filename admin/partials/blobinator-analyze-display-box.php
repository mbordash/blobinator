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


<div id="blobinator-results-box" class="wrap">

    <div id="blobinator-results-status" class="wrap">Enter some content above and then click the Blobinator robot button.</div>

    <div id="blobinator-results-success" class="hidden">

        <div id="spinner" class="spinner is-inactive" style="float:none; width:100%; height: auto; padding:10px 0 10px 50px; background-position: center center;"></div>

        <div id="blobinator-results-navigation">

            <h2 class="nav-tab-wrapper current">

                <a class="nav-tab nav-tab-active" href="javascript:;"><?php esc_attr_e( 'Sentiment Analysis', 'wp_admin_style' ); ?></a>
                <a class="nav-tab" href="javascript:;"><?php esc_attr_e( 'Emotions Detected', 'wp_admin_style' ); ?></a>
                <a class="nav-tab" href="javascript:;"><?php esc_attr_e( 'Keywords Extracted', 'wp_admin_style' ); ?></a>
                <a class="nav-tab" href="javascript:;"><?php esc_attr_e( 'Concepts Extracted', 'wp_admin_style' ); ?></a>
            </h2>

            <div class="inside">
                <div id="overall_tone"></div>

                <div id="sentiment_chart">
                    <svg></svg>
                </div>

            </div>

            <div class="inside hidden">
                <div id="emotion_chart">
                    <svg id="emotion_chart_svg"></svg>
                </div>
            </div>

           <div class="inside hidden">
               <div id="blobinator_add_keywords_as_tags_div">
                   <button onClick="blobinator_add_keywords_to_post_tags(); return false;" class="button" type="submit" id="blobinator-add-keywords-as-tags" name="blobinator-add-keywords-as-tags">Add Keywords to Post Tags</button>
               </div>

               <div id="keywords_chart">
                   <svg></svg>
               </div>

           </div>


            <div class="inside hidden">

                <div id="blobinator_add_concepts_as_tags_div">
                    <button style="margin-bottom: 5px"  onClick="blobinator_add_concepts_to_post_tags(); return false;" class="button" type="submit" id="blobinator-add-concepts-as-tags" name="blobinator-add-concepts-as-tags">Add Concepts to Post Tags</button>
                </div>

                <div id="concepts"></div>

            </div>
        </div>

    </div>

</div>
