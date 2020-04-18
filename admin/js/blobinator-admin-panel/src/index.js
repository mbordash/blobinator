import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { Button, Disabled, TextareaControl, Spinner } from '@wordpress/components';
import { withState } from '@wordpress/compose';


const MyDisabled = withState( {
    isDisabled: false,
} )( ( { isDisabled, setState } ) => {

    const runBlobinatorAnalyze = () => {
        setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );
        const { select } = wp.data;
        const title = select( 'core/editor' ).getEditedPostAttribute( 'title' ).trim();
        const content = select( 'core/editor' ).getEditedPostAttribute( 'content' ).trim();
        const security = blobinatorAjaxObject.security;

        let txtToAnalyze = jQuery( '<div>' + title + ' ' + content + '</div>' ).text();

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
                    jQuery('#blobinator-analyze-results-status').html(response.message).show();
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

                jQuery( '#blobinator_keywords' ).val( keywordFieldText );

                setState( ( state ) => ( { isDisabled: ! state.isDisabled } ) );

            },
            timeout: 10000
        });
    };

    let blobinatorButton = <Button onChange={ () => {} } isPrimary onClick={runBlobinatorAnalyze}>Analyze</Button>;

    if ( isDisabled ) {
        blobinatorButton = <Disabled>{ blobinatorButton } <Spinner/></Disabled>;
    }

    return (
        <div>
            <p>{ blobinatorButton }</p>
            <TextareaControl name="blobinator_keywords" type="text" id="blobinator_keywords" placeholder="Keywords" />
        </div>
);
});



const BlobinatorSettingPanel = () => (

    <PluginDocumentSettingPanel
name="blobinator-admin-panel"
title="Blobinator SEO Keywords"
className="blobinator-panel"
    >

    <p>Blobinator will analyze your content and provide you with a set of recommended keywords.
    Copy and paste these to your Tags to improve SEO.</p>

<MyDisabled/>

</PluginDocumentSettingPanel>

);
registerPlugin( 'plugin-document-setting-panel-blobinator', { render: BlobinatorSettingPanel, icon: 'performance' } );