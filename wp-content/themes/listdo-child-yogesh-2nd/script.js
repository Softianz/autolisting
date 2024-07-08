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
    // Show hide Price and payment method at add listing 
    jQuery("input[name = 'custom-radio-26']").on('click', function () {
        let val = jQuery(this).val();
        if (val == 'Firm') {
            jQuery('.box-list-2 .fieldset-custom-radio-27').css('display', 'none');
            jQuery('.box-list-2 .fieldset-job_price_to').css('display', 'none');
        } else {
            jQuery('.box-list-2 .fieldset-custom-radio-27').css('display', 'block');
            jQuery('.box-list-2 .fieldset-job_price_to').css('display', 'block');
        }

    })

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
let meetingStatus = (object, meeting_id, status) => {
    jQuery(object).prop('disabled', true);
    jQuery.ajax({
        url: ajaxurl,
        async: false,
        type: "POST",
        data: {
            action: 'change_meeting_status',
            meeting_id: meeting_id,
            status: status,
            dataType: 'json'
        },
        success: function (res) {
            if (res.status) {
                window.location.reload();
            } else {
                alert(res.msg);
                jQuery(object).prop('disabled', false);
            }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
            jQuery(object).prop('disabled', false);
        }
    });
}
let SoldOut = (object, list_id) => {
    if (confirm('are you sure')) {
        jQuery(object).prop('disabled', true);
        jQuery.ajax({
            url: ajaxurl,
            async: false,
            type: "POST",
            data: {
                action: 'listing_sold_out',
                list_id: list_id,
                dataType: 'json'
            },
            success: function (res) {
                if (res.status) {
                    window.location.reload();
                } else {
                    jQuery(object).prop('disabled', false);
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
                jQuery(object).prop('disabled', false);
            }
        });
    }
}