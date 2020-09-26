
// Sentiment


var jsonResponse = jQuery.parseJSON(jQuery('#sentiment_result_json').text());

var arraySize = jQuery(jsonResponse).size();
var sentimentText;
var sentimentScore;

if ( arraySize === 3 ) {
    sentimentText = jsonResponse[2];
    sentimentScore = jsonResponse[1];
} else {
    sentimentScore = jsonResponse[0];

    if (sentimentScore > 0 ) {
        sentimentText = "positive";
    } else if (sentimentScore < 0 ) {
        sentimentText = "negative";
    } else {
        sentimentText = "neutral";
    }
}


toneAnalysis = 'We detected a <strong>' + sentimentText + '</strong> sentiment for this page.'

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

// Emotion

var jsonResponse = jQuery.parseJSON(jQuery('#emotion_result_json').text());

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

jQuery('#emotion_result').show();

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



