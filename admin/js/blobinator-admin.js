jQuery(function($) {



    if (typeof(tinyMCE) != "undefined") {

        tinyMCE.create('tinymce.plugins.Blobinator', {

            init: function (ed, url) {

                ed.addButton('blobinator', {
                    title: 'Blobinator',
                    cmd: 'blobinator',
                    icon: 'charmap'
                });

                ed.addCommand('blobinator', function () {
                    var selected_text = ed.selection.getContent();
                    if (selected_text === "") {
                        selected_text = ed.getContent();
                    }
                    var return_text = '';
                    return_text = selected_text;

                    old_tb_position = tb_position;
                    tb_position = function(){
                        $("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px', height: TB_HEIGHT + 'px', marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
                    };

                    tb_show('Blobinator Content Analysis', '#TB_inline?height=900&amp;width=900&amp;inlineId=modal-blobinator-results');

                    $('#blobinator_text_to_analyze').val(return_text);
                    handleFormPost();
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
                    version: "0.1"
                };
            }
        });

        // Register plugin
        tinyMCE.PluginManager.add('blobinator', tinyMCE.plugins.Blobinator);

    }
});

function handleFormPost() {

    jQuery('#analyzed_text').html('').hide();

    jQuery('#spinner').removeClass('is-inactive').addClass('is-active');

    var data = jQuery('#analyze-text-form').serialize();

    jQuery('#concepts_chart_title').html('<h4>Concepts Extracted</h4>');

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=concepts',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){

            var jsonResponse = jQuery.parseJSON(response);

            console.log(jsonResponse);

            var contentTable = '<table class="widefat"><thead><th>Concept</th><th>Relevance</th><th>More Information</th></thead><tbody>';
            for( var elem in jsonResponse ) {
                contentTable  += '<tr><td>' + jsonResponse[elem]['text'] + '</td>';
                contentTable  += '<td>' + ( Math.round(jsonResponse[elem]['relevance'] * 100 )) + '%</td>';
                contentTable  += '<td><a target="_blank" href="' + jsonResponse[elem]['dbpedia'] + '">dbpedia</a>, ' +
                    '                   <a target="_blank" href="' + jsonResponse[elem]['freebase'] + '">freebase</a>, ' +
                    '                   <a target="_blank" href="' + jsonResponse[elem]['opencyc'] + '">opencyc</a></td></tr>';
            }
            contentTable += '</tbody></table>';

            jQuery('#concepts').html( contentTable ).show();

            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            console.log(response.responseText);
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }

    });

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=keywords',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){

            var jsonResponse = jQuery.parseJSON(response);

            console.log(jsonResponse);

            // get it into the form required by `nvd3`
            input = [{ key: "Keyword Relevance", values: jsonResponse }]

            nv.addGraph(function() {
                var height = 500;
                var chart = nv.models.multiBarHorizontalChart()
                    .x(function(d) {return d.text})
                    .y(function(d) {return d.relevance})
                    .barColor(d3.scale.category20().range())
                    .margin({top: 30, right: 20, bottom: 50, left: 175})
                    .height(height)
                    .showControls(false)
                    .stacked(true)
                    .showValues(true);

                chart.yAxis
                    .tickFormat(d3.format('%'));

                chart.yAxis.axisLabel('Confidence');

                d3.select('#keywords_chart svg')
                    .datum(input)
                    .transition(1000)
                    .call(chart)
                    .style({ 'height': height });

                nv.utils.windowResize(chart.update);
                return chart;

            });

            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            console.log(response.responseText);
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

            var jsonResponse = jQuery.parseJSON(response);

            console.log(jsonResponse);

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
            
            toneAnalysis = '<h2>We detected a <strong>' + sentimentText + '</strong> tone when analyzing your content with an offset of ' + sentimentScore + ' from neutral using a range of -1 to 1.'


            jQuery('#overall_tone').html( toneAnalysis ).show();

            nv.addGraph(function() {

                var chart = nv.models.discreteBarChart()

                    .x(function(d) {return d.label})
                    .y(function(d) {return d.value})
                    .forceY([-1,1])
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
                        "label": "Content Selection",
                        "value": sentimentScore
                    }]
                }]
            }


            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            console.log(response.responseText);
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

            console.log(response);

            var jsonResponse = jQuery.parseJSON(response);

            var jsArr = [];

            var counter = 0;
            for( var elem in jsonResponse ) {
                jsArr[counter] = {
                    'label': elem,
                    'value': jsonResponse[elem]
                };
                counter++;
            }

            nv.addGraph(function() {

                var width = 300;
                var height = 300;

                var chart = nv.models.pie()

                    .x(function(d) {return d.label})
                    .y(function(d) {return d.value})
                    .width(width)
                    .height(height)
                    .showLabels(true)
                    .labelType("key")
                    .labelThreshold(0.01)
                    .labelSunbeamLayout(true);


                d3.select("#emotion_chart svg")
                    .datum([jsArr])
                    .transition().duration(1200)
                    .call(chart)
                    .style({ 'height': height });

            });
        },
        error: function(response) {
            console.log(response.responseText);
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }
    });

    return false;
};