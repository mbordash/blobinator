import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { Button, Disabled, TextareaControl, Spinner } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import * as d3 from "d3";
import nvd3 from "nvd3";
var nv = nvd3;

const MyDisabledSentiment = withState( {
    isDisabled: false,
} )( ( { isDisabled, setState } ) => {

    const runSentimentAnalyze = () => {
        setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );
        const { select } = wp.data;
        const title = select( 'core/editor' ).getEditedPostAttribute( 'title' ).trim();
        const content = select( 'core/editor' ).getEditedPostAttribute( 'content' ).trim();
        const security = blobinatorAjaxObject.security;

        let txtToAnalyze = jQuery( '<div>' + title + ' ' + content + '</div>' ).text().substring(0,1500);

        txtToAnalyze = txtToAnalyze.replace(/[^0-9a-z\s]/gi, '');
        txtToAnalyze = txtToAnalyze.replace(/\u00A0/g, ' ');

        let data = jQuery.param({
            'blobinator_text_to_analyze' : txtToAnalyze,
            'action' : 'blobinator_analyze',
            'service' : 'sentiment'
        });

        jQuery.ajax({

            type: 'post',
            url: blobinatorAjaxObject.ajaxurl,
            security: security,
            data: data,
            cache: false,
            success: function (response) {

                if (response.status === 'error') {
                    return false;
                }

                let jsonResponse = JSON.parse(response);;

                let arraySize = jQuery(jsonResponse).size();
                let sentimentText = "neutral";
                let sentimentScore;
                let barColor = ['#5499C7'];


                if ( arraySize === 3 ) {
                    sentimentText = jsonResponse[2];
                    sentimentScore = jsonResponse[1];
                } else {
                    sentimentScore = jsonResponse[0];

                    if (sentimentScore > 0 ) {
                        sentimentText = "positive";
                        barColor = ['#27AE60'];
                    } else if (sentimentScore < 0 ) {
                        sentimentText = "negative";
                        barColor = ['#E74C3C'];
                    }
                }

                let toneAnalysis = 'We detected a <strong>' + sentimentText + '</strong> sentiment for this content.';

                jQuery('#sentiment_result').show();
                jQuery('#overall_tone').html(toneAnalysis);

                nv.addGraph(function() {

                    var chart = nv.models.discreteBarChart()

                        .x(function(d) {return d.label})
                        .y(function(d) {return d.value})
                        .color(barColor)
                        .forceY([-1, 1])
                        .showValues(true);

                    chart.tooltip.enabled(false);

                    d3.select("#sentiment_chart svg")
                        .datum(sentimentData())
                        .transition().duration(1200)
                        .call(chart);
                });

                function sentimentData() {
                    return  [{
                        key: "Sentiment",
                        values: [{
                            "value": sentimentScore
                        }]
                    }]
                }

                setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );

            },
            timeout: 10000
        });
    };

    let sentimentButton = <Button onChange={ () => {} } isPrimary onClick={runSentimentAnalyze}>Analyze</Button>;

    if ( isDisabled ) {
        sentimentButton = <Disabled>{ sentimentButton } <Spinner/></Disabled>;
    }

    return (
        <div>
            <p>{ sentimentButton }</p>
            <div id="sentiment_result" style={{display: 'none'}}>

                <div id='overall_tone'></div>

                <div id='sentiment_chart'>
                    <svg style={{height: '300px', width:'90%', margin:'0 auto'}}></svg>
                </div>

            </div>
        </div>
    );
});

const MyDisabledKeywords = withState( {
    isDisabled: false,
} )( ( { isDisabled, setState } ) => {

    const runKeywordAnalyze = () => {
        setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );
        const { select } = wp.data;
        const title = select( 'core/editor' ).getEditedPostAttribute( 'title' ).trim();
        const content = select( 'core/editor' ).getEditedPostAttribute( 'content' ).trim();
        const security = blobinatorAjaxObject.security;

        let txtToAnalyze = jQuery( '<div>' + title + ' ' + content + '</div>' ).text().substring(0,1500);

        txtToAnalyze = txtToAnalyze.replace(/[^0-9a-z\s]/gi, '');
        txtToAnalyze = txtToAnalyze.replace(/\u00A0/g, ' ');

        let data = jQuery.param({
            'blobinator_text_to_analyze' : txtToAnalyze,
            'action' : 'blobinator_analyze',
            'service' : 'keywords'
        });

        jQuery.ajax({

            type: 'post',
            url: blobinatorAjaxObject.ajaxurl,
            security: security,
            data: data,
            cache: false,
            success: function (response) {

                if (response.status === 'error') {
                    return false;
                }

                let jsonResponse = JSON.parse(response);
                let jsonResponseSize = jQuery(jsonResponse).length;
                let keywordFieldArray = [];
                let keywordFieldText = null;

                if( jsonResponseSize >= 1 ) {

                    jsonResponse.forEach(function (element) {

                        keywordFieldArray.push( element.text.trim() );

                    });

                    keywordFieldText = keywordFieldArray.join( ', ' );

                } else {

                    keywordFieldText = 'No keywords found.';

                }

                jQuery( '#kontxt_keywords' ).val( keywordFieldText );

                setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );

            },
            timeout: 10000
        });
    };

    let keywordsButton = <Button onChange={ () => {} } isPrimary onClick={runKeywordAnalyze}>Analyze</Button>;

    if ( isDisabled ) {
        keywordsButton = <Disabled>{ keywordsButton } <Spinner/></Disabled>;
    }

    return (
        <div>
            <p>{ keywordsButton }</p>
            <TextareaControl name="kontxt_keywords" type="text" id="kontxt_keywords" placeholder="Keywords" />
        </div>
    );
});

const MyDisabledEmotion = withState( {
    isDisabled: false,
} )( ( { isDisabled, setState } ) => {

    const runEmotionAnalyze = () => {
        setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );
        const { select } = wp.data;
        const title = select( 'core/editor' ).getEditedPostAttribute( 'title' ).trim();
        const content = select( 'core/editor' ).getEditedPostAttribute( 'content' ).trim();
        const security = blobinatorAjaxObject.security;

        let txtToAnalyze = jQuery( '<div>' + title + ' ' + content + '</div>' ).text().substring(0,1500);

        txtToAnalyze = txtToAnalyze.replace(/[^0-9a-z\s]/gi, '');
        txtToAnalyze = txtToAnalyze.replace(/\u00A0/g, ' ');

        let data = jQuery.param({
            'blobinator_text_to_analyze' : txtToAnalyze,
            'action' : 'blobinator_analyze',
            'service' : 'emotion'
        });

        jQuery.ajax({

            type: 'post',
            url: blobinatorAjaxObject.ajaxurl,
            security: security,
            data: data,
            cache: false,
            success: function (response) {

                if (response.status === 'error') {
                    return false;
                }

                let jsonResponse = JSON.parse(response);

                let jsArr = [];

                let counter = 0;
                for( let elem in jsonResponse ) {
                    jsArr[counter] = {
                        'key': elem,
                        'y': jsonResponse[elem]
                    };
                    counter++;
                }

                jQuery('#emotion_result').show();

                nv.addGraph(function() {
                    var chart = nv.models.pieChart()
                        .x(function(d) { return d.key })
                        .y(function(d) { return d.y })
                        .labelType('percent')
                        .labelSunbeamLayout(true)
                        .showTooltipPercent(true);

                    d3.select("#emotion_chart svg")
                        .datum(jsArr)
                        .transition().duration(1200)
                        .call(chart);

                    return chart;
                });

                setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );

            },
            timeout: 10000
        });
    };

    let emotionButton = <Button onChange={ () => {} } isPrimary onClick={runEmotionAnalyze}>Analyze</Button>;

    if ( isDisabled ) {
        emotionButton = <Disabled>{ emotionButton } <Spinner/></Disabled>;
    }

    return (
        <div>
            <p>{ emotionButton }</p>
            <div id="emotion_result" style={{display: 'none'}}>

                <div id='emotion_chart'>
                    <svg style={{height: '300px', width:'90%', margin:'0 auto'}}></svg>
                </div>

            </div>
        </div>
    );
});

const KONTXTPanels = () => (

    <div>
        <PluginDocumentSettingPanel
            name="kontxt-keywords-panel"
            title="KONTXT Keywords"
            className="kontxt-panel"
        >

            <p>KONTXT will analyze your content and provide you with a set of recommended keywords.
            Copy and paste these to your Tags to improve SEO.</p>

            <MyDisabledKeywords />

        </PluginDocumentSettingPanel>

        <PluginDocumentSettingPanel
            name="kontxt-sentiment-panel"
            title="KONTXT Sentiment"
            className="kontxt-panel"
        >

            <p>KONTXT will analyze your content and provide you with the overall tone.</p>

            <MyDisabledSentiment />

        </PluginDocumentSettingPanel>

        <PluginDocumentSettingPanel
            name="kontxt-emotion-panel"
            title="KONTXT Emotion"
            className="kontxt-panel"
        >

            <p>KONTXT will analyze your content and provide you with the overall emotional constructs.</p>

            <MyDisabledEmotion />

        </PluginDocumentSettingPanel>
    </div>

);
registerPlugin( 'plugin-document-setting-panel-blobinator', { render: KONTXTPanels, icon: 'performance' } );