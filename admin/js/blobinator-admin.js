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
                    tb_show('Blobinator Content Analysis', '#TB_inline?height=700&amp;width=744&amp;inlineId=modal-blobinator-results');
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

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data + '&service=concepts',
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){
            console.log(response);
            jQuery('#concepts').html(response).show();
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
                    .margin({top: 30, right: 20, bottom: 50, left: 175})
                    .height(height)
                    .showControls(false)
                   // .tooltips(true)
                    .showValues(true);

                chart.yAxis
                    .tickFormat(d3.format(',.2f'));

                chart.x2Axis;

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
            console.log(response);
            jQuery('#sentiment').html(response).show();
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
            jQuery('#emotion').html(response).show();
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        },
        error: function(response) {
            console.log(response.responseText);
            jQuery('#spinner').removeClass('is-active').addClass('is-inactive');
        }
    });


    return false;
};