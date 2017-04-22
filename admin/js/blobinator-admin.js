jQuery(function($) {

    if (typeof(tinyMCE) != "undefined") {

        tinyMCE.create('tinymce.plugins.Blobinator', {

            init: function (ed, url) {

                ed.addButton('blobinator', {
                    title: 'Blobinator',
                    cmd: 'blobinator',
                    image : url + '/images/blobinator_button.png'
                });

                ed.addCommand('blobinator', function () {
                    var selected_text = ed.selection.getContent();
                    if (selected_text === "") {
                        selected_text = ed.getContent();
                    }
                    var return_text = '';
                    return_text = selected_text;

                    tb_position = function(){
                        $("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px', height: TB_HEIGHT + 'px', marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
                    };

                    if ( !return_text || return_text.length === 0 ) {

                        jQuery('#dialog-blobinator-error').html('<p>You haven\'t entered any content yet. Please enter some content before trying to analyze.</p>');

                        tb_show('Blobinator Error', '#TB_inline?height=100&amp;width=400&amp;inlineId=dialog-blobinator-error');

                        return false;
                    }

                    if ( return_text && return_text.length <= 100 ) {

                        jQuery('#dialog-blobinator-error').html('<p>You haven\'t entered enough content. Please enter some additional content before trying to analyze.</p>');

                        tb_show('Blobinator Error', '#TB_inline?height=100&amp;width=400&amp;inlineId=dialog-blobinator-error');

                        return false;
                    }

                    var dynHeight = ( 80 * jQuery(window).height() ) / 100;

                    tb_show('Blobinator Content Analysis', '#TB_inline?height=' + dynHeight + '&amp;width=900&amp;inlineId=modal-blobinator-results');

                    $('#blobinator_text_to_analyze').val(return_text);
                    blobinatorHandleFormPost();
                });
            },

            createControl: function (n, cm) {
                return null;
            },

            getInfo: function () {
                return {
                    longname: 'Blobinator Button',
                    author: 'Michael Bordash',
                    authorurl: 'http://www.blobinator.com',
                    infourl: 'http://www.blobinator.com',
                    version: "1.0.0"
                };
            }
        });

        // Register plugin
        tinyMCE.PluginManager.add('blobinator', tinyMCE.plugins.Blobinator);

    }
});

function blobinator_add_concepts_to_post_tags() {

    var items = [];

    jQuery('#blobinator_concepts  tbody tr td:nth-child(1)').each( function(){
        items.push( jQuery(this).text() );
    });

    var items = jQuery.unique( items );

    jQuery('#new-tag-post_tag').val(jQuery('#new-tag-post_tag').val() + items.join(', '));
    jQuery('#new-tag-product_tag').val(jQuery('#new-tag-product_tag').val() + items.join(', '));

    jQuery('#blobinator_add_concepts_as_tags_div').html('Added to Tags within your Post!').fadeOut({ duration: 2000 });
}

function blobinator_add_keywords_to_post_tags() {

    var items = [];

    var minTagSize = 4;

    d3.selectAll('#keywords_chart svg text').each( function() {

        var tag = jQuery(this).text();

        if ( tag.length >= minTagSize ) {

            items.push( jQuery(this).text() );

        }

    });

    var items = jQuery.unique( items );

    jQuery('#new-tag-post_tag').val(jQuery('#new-tag-post_tag').val() + items.join(', '));
    jQuery('#new-tag-product_tag').val(jQuery('#new-tag-product_tag').val() + items.join(', '));

    jQuery('#blobinator_add_keywords_as_tags_div').html('Added to Tags within your Post!').fadeOut({ duration: 2000 });
}

function blobinatorHandleFormPost() {

    jQuery('#spinner').removeClass('is-inactive').addClass('is-active');

    var data = jQuery('#analyze-text-form').serialize();

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=concepts',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response) {

            if( response.status == 'error' ) {
                jQuery('#dialog-blobinator-error').html(response.message).show();
                jQuery('#dialog-blobinator-success').hide();
                return false;
            }

            var jsonResponse = jQuery.parseJSON(response);

            var contentTable = '<table id="blobinator_concepts" class="widefat"><thead><th>Concept</th><th>Relevance</th><th>More Information</th></thead><tbody>';
            for( var elem in jsonResponse ) {
                contentTable  += '<tr><td>' + jsonResponse[elem]['text'] + '</td>';
                contentTable  += '<td>' + ( Math.round(jsonResponse[elem]['relevance'] * 100 )) + '%</td>';
                contentTable  += '<td><a target="_blank" href="' + jsonResponse[elem]['dbpedia'] + '">dbpedia</a>, ' +
                    '                   <a target="_blank" href="' + jsonResponse[elem]['freebase'] + '">freebase</a>, ' +
                    '                   <a target="_blank" href="' + jsonResponse[elem]['opencyc'] + '">opencyc</a></td></tr>';
            }
            contentTable += '</tbody></table>';

            jQuery('#concepts').html( contentTable ).show();

            jQuery('#blobinator-add-concepts-as-tags').show();

            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            jQuery('#dialog-blobinator-error').html(response.message);
            return false;
        }

    });

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=keywords',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){

            if( response.status == 'error' ) {
                jQuery('#dialog-blobinator-error').html(response.message).show();
                jQuery('#dialog-blobinator-success').hide();
                return false;
            }

            var jsonResponse = jQuery.parseJSON(response);

            // get it into the form required by `nvd3`
            input = [{ key: '', values: jsonResponse }]

            nv.addGraph(function() {
                var height = 500;
                var chart = nv.models.multiBarHorizontalChart()
                    .x(function(d) {return d.text})
                    .y(function(d) {return d.relevance})
                    .margin({top: 30, right: 20, bottom: 50, left: 175})
                    .height(height)
                    .showLegend(false)
                    .showControls(false)
                    .stacked(true)
                    .showValues(false);

                chart.yAxis
                    .tickFormat(d3.format('%'));

                d3.select('#keywords_chart svg')
                    .datum(input)
                    .transition(1000)
                    .call(chart)
                    .style({ 'height': height });

                nv.utils.windowResize(chart.update);
                return chart;

            });

            jQuery('#blobinator-add-keywords-as-tags').show();

            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }
    });

    jQuery.ajax({

        type: 'post',
        url: ajaxurl,
        data: data + '&service=sentiment',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){

            if( response.status == 'error' ) {
                jQuery('#dialog-blobinator-error').html(response.message).show();
                jQuery('#dialog-blobinator-success').hide();
                return false;
            }

            var jsonResponse = jQuery.parseJSON(response);

            var arraySize = jQuery(jsonResponse).size();
            var sentimentText;
            var sentimentScore;

            if ( arraySize === 3 ) {
                sentimentText = 'mixed to slightly ' + jsonResponse[2];
                sentimentScore = jsonResponse[1];
            } else {
                sentimentText = jsonResponse[1];
                sentimentScore = jsonResponse[0];
            }
            
            toneAnalysis = 'We detected a <strong>' + sentimentText + '</strong> tone when analyzing this content with an offset of ' + sentimentScore + ' from neutral using a range of -1 to 1.'


            jQuery('#overall_tone').html( toneAnalysis ).show();

            nv.addGraph(function() {

                var chart = nv.models.multiBarHorizontalChart()

                    .x(function(d) {return d.label})
                    .y(function(d) {return d.value})
                    .forceY([-1,1])
                    .showLegend(false)
                    .showControls(false)
                    .showValues(true);


                d3.select("#sentiment_chart svg")
                    .datum(sentimentData())
                    .transition().duration(1200)
                    .call(chart);
            });

            function sentimentData() {
                return  [{
                    key: "Sentiment",
                    values: [{
                        "label": "Sentiment",
                        "value": sentimentScore
                    }]
                }]
            }


            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }

    });



    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=emotion',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){

            if( response.status == 'error' ) {
                jQuery('#dialog-blobinator-error').html(response.message).show();
                jQuery('#dialog-blobinator-success').hide();
                return false;
            }

            var jsonResponse = jQuery.parseJSON(response);

            var jsArr = [];

            var counter = 0;
            for( var elem in jsonResponse ) {
                jsArr[counter] = {
                    'key': elem,
                    'y': jsonResponse[elem]
                };
                counter++;
            }

            var height = 400;
            var width = 400;

            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .width(width)
                    .height(height)
                    .labelType('percent')
                    .labelSunbeamLayout(true)
                    .showTooltipPercent(true);

                d3.select("#emotion_chart svg")
                    .datum(jsArr)
                    .transition().duration(1200)
                    .attr('height', height)
                    .call(chart);

                return chart;
            });


        },
        error: function(response) {
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }
    });

    return false;
};