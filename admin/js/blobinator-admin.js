jQuery(function($) {

    tinyMCE.create('tinymce.plugins.Blobinator', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {

            ed.addButton('blobinator', {
                title : 'Blobinator',
                cmd : 'blobinator',
                icon: 'charmap'
            });

            ed.addCommand('blobinator', function() {
                var selected_text = ed.selection.getContent();
                if ( selected_text === "" ) {
                    selected_text = ed.getContent();
                }
                var return_text = '';
                return_text = selected_text;
                tb_show('Blobinator Content Analysis', 'http://wp-blobinator:8080/wp-admin/tools.php?page=blobinator');

//                alert(return_text);
            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Blobinator Button',
                author : 'Michael Bordash',
                authorurl : 'http://www.blobinator.com',
                infourl : 'http://www.blobinator.com',
                version : "0.1"
            };
        }
    });

    // Register plugin
    tinyMCE.PluginManager.add( 'blobinator', tinyMCE.plugins.Blobinator );
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
            console.log(response);
            jQuery('#keywords').html(response).show();
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