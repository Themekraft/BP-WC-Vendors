jQuery(document).ready(function (jQuery) {

    jQuery(document.body).on('change', '#bp-wcv-form-select', function () {

        var form_slug = jQuery( this ).val()

        if( form_slug != 'none'){
            jQuery('#bp-wc-vendors-shortcode-result').html('<b> Copy the below ShortCode into any Page </b><br><p><b>Shortcode: </b>[bp_wcv_bav_or_register form_slug="' + form_slug + '"] </p>');
            jQuery('#bp-wc-vendors-shortcode-result').show();
        } else {
            jQuery('#bp-wc-vendors-shortcode-result').hide();
        }

    });

});