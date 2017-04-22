


var jsonResponse = jQuery.parseJSON(jQuery('#sentiment_result_json').text());

jsonResponse = ["1","-0.0278146","negative"];

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

jQuery('#sentiment_result').show();
jQuery('#overall_tone').html(toneAnalysis);

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
            "label" : "Sentiment",
            "value": sentimentScore
        }]
    }]
}