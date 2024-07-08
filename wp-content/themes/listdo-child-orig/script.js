(function () {
    jQuery('#scheduleMeeting').on('submit', function (e) {
        jQuery('.schedulebutton').prop('disabled', true);
        jQuery('.schedulebutton').html('Please Wait.....');
        jQuery('.responsemsg').html('Please Wait.....');
        e.preventDefault();

        jQuery.ajax({
            url: ajaxurl,
            async: false,
            type: "POST",
            data: {
                action: 'submit_meeting_schedule',
                data: jQuery(this).serialize(),
                dataType: 'json'
            },
            success: function (res) {
                console.log(res);
                jQuery('.responsemsg').html(res.msg);
                jQuery('.schedulebutton').prop('disabled', false);
                jQuery('.schedulebutton').html('Submit');
                jQuery('.timeingmsg').html('');
                if (res.status) {
                    jQuery('#scheduleMeeting').trigger("reset");
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });
    

})(jQuery);
/*************Function************* ********************/
let getTimming = (object, post_id) => {
    jQuery('.timeingmsg').html('<span class="open-text">Please wait we are fetching time slot</span>');
    jQuery('#meeting_time').prop('disabled', true);
    jQuery.ajax({
        url: ajaxurl,
        async: false,
        type: "POST",
        data: {
            action: 'get_listing_time_slot',
            post_id: post_id,
            date: jQuery(object).val(),
            dataType: 'json'
        },
        success: function (res) {
            jQuery('.timeingmsg').html(res.data);
            if (res.status) {
                jQuery('#meeting_time').prop('disabled', false);
            }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}
