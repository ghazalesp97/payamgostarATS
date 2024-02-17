jQuery(document).ready(function($) {
    $('#atscustomrtemplate').on('change', function() {
        console.log(checkbox_script_data.ajax_url);
        var checkboxValue = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: checkbox_script_data.ajax_url,
            type: 'POST',
            data: {
                action: 'save_checkbox_value',
                checkbox_value: checkboxValue,
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
});
        
        
