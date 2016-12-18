function handleFormPost() {

    jQuery('#analyzed_text').html('').hide();

    jQuery('#spinner').addClass('is-active');

    var data = jQuery('#analyze-text-form').serialize();

    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: data,
        action: 'blobinator_analyze',
        cache: false,
        success: function(response){
            console.log(response);
            jQuery('#analyzed_text').html(response).show();
        },
        error: function(response) {
            console.log(response.responseText);
        }
    });

    jQuery('#spinner').removeClass('is-active');

    return false;
};
