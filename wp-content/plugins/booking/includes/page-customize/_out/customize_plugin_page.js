"use strict";
/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

var wpbc_ajx_customize_plugin = function (obj, $) {
  // Secure parameters for Ajax	------------------------------------------------------------------------------------
  var p_secure = obj.security_obj = obj.security_obj || {
    user_id: 0,
    nonce: '',
    locale: ''
  };

  obj.set_secure_param = function (param_key, param_val) {
    p_secure[param_key] = param_val;
  };

  obj.get_secure_param = function (param_key) {
    return p_secure[param_key];
  }; // Listing Search parameters	------------------------------------------------------------------------------------


  var p_listing = obj.search_request_obj = obj.search_request_obj || {// sort            : "booking_id",
    // sort_type       : "DESC",
    // page_num        : 1,
    // page_items_count: 10,
    // create_date     : "",
    // keyword         : "",
    // source          : ""
  };

  obj.search_set_all_params = function (request_param_obj) {
    p_listing = request_param_obj;
  };

  obj.search_get_all_params = function () {
    return p_listing;
  };

  obj.search_get_param = function (param_key) {
    return p_listing[param_key];
  };

  obj.search_set_param = function (param_key, param_val) {
    // if ( Array.isArray( param_val ) ){
    // 	param_val = JSON.stringify( param_val );
    // }
    p_listing[param_key] = param_val;
  };

  obj.search_set_params_arr = function (params_arr) {
    _.each(params_arr, function (p_val, p_key, p_data) {
      // Define different Search  parameters for request
      this.search_set_param(p_key, p_val);
    });
  }; // Other parameters 			------------------------------------------------------------------------------------


  var p_other = obj.other_obj = obj.other_obj || {};

  obj.set_other_param = function (param_key, param_val) {
    p_other[param_key] = param_val;
  };

  obj.get_other_param = function (param_key) {
    return p_other[param_key];
  };

  return obj;
}(wpbc_ajx_customize_plugin || {}, jQuery);

var wpbc_ajx_bookings = [];
/**
 *   Show Content  ---------------------------------------------------------------------------------------------- */

/**
 * Show Content - Calendar and UI elements
 *
 * @param ajx_data
 * @param ajx_search_params
 * @param ajx_cleaned_params
 */

function wpbc_ajx_customize_plugin__page_content__show(ajx_data, ajx_search_params, ajx_cleaned_params) {
  // Content ---------------------------------------------------------------------------------------------------------
  var template__customize_plugin_main_page_content = wp.template('wpbc_ajx_customize_plugin_main_page_content');
  jQuery(wpbc_ajx_customize_plugin.get_other_param('listing_container')).html(template__customize_plugin_main_page_content({
    'ajx_data': ajx_data,
    'ajx_search_params': ajx_search_params,
    // $_REQUEST[ 'search_params' ]
    'ajx_cleaned_params': ajx_cleaned_params
  }));
  var template__inline_calendar;
  var data_arr = {
    'ajx_data': ajx_data,
    'ajx_search_params': ajx_search_params,
    'ajx_cleaned_params': ajx_cleaned_params
  };

  switch (ajx_data['customize_steps']['current']) {
    case 'calendar_skin':
      // Calendar  --------------------------------------------------------------------------------------------
      template__inline_calendar = wp.template('wpbc_ajx_customize_plugin__inline_calendar');
      jQuery('.wpbc_ajx_cstm__section_left').html(template__inline_calendar(data_arr)); // Calendar Skin

      var template__wiget_calendar_skin = wp.template('wpbc_ajx_widget_change_calendar_skin');
      jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(template__wiget_calendar_skin(data_arr)); // Shortcode
      // var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
      // jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );
      // Size
      // var template__wiget_calendar_size = wp.template( 'wpbc_ajx_widget_calendar_size' );
      // jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__wiget_calendar_size( data_arr ) );

      break;

    case 'calendar_size':
      // Calendar  --------------------------------------------------------------------------------------------
      template__inline_calendar = wp.template('wpbc_ajx_customize_plugin__inline_calendar');
      jQuery('.wpbc_ajx_cstm__section_left').html(template__inline_calendar(data_arr)); // Calendar Skin

      var template__wiget_calendar_size = wp.template('wpbc_ajx_widget_calendar_size');
      jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(template__wiget_calendar_size(data_arr)); // Shortcode
      // var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
      // jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );

      break;

    case 'calendar_dates_selection':
      // Calendar  --------------------------------------------------------------------------------------------
      template__inline_calendar = wp.template('wpbc_ajx_customize_plugin__inline_calendar');
      jQuery('.wpbc_ajx_cstm__section_left').html(template__inline_calendar(data_arr));
      jQuery('.wpbc_ajx_cstm__section_left').append('<div class="clear" style="width:100%;margin:50px 0 0;"></div>');
      var message_html_id = wpbc_ajx_customize_plugin__show_message('<strong>' + 'You can test days selection in calendar' + '</strong>', {
        'container': '.wpbc_ajx_cstm__section_left',
        // '#ajax_working',
        'style': 'margin: 6px auto;  padding: 6px 20px;z-index: 999999;',
        'type': 'info',
        'delay': 5000
      });
      wpbc_blink_element('#' + message_html_id, 3, 320); // Widget - Dates selection

      var template__widget_plugin_calendar_dates_selection = wp.template('wpbc_ajx_widget_calendar_dates_selection');
      jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(template__widget_plugin_calendar_dates_selection(data_arr));
      break;

    case 'calendar_weekdays_availability':
      // Scroll  to  current month
      var s_year = wpbc_ajx_customize_plugin.search_set_param('calendar__start_year', 0);
      var s_month = wpbc_ajx_customize_plugin.search_set_param('calendar__start_month', 0); // Calendar  --------------------------------------------------------------------------------------------

      template__inline_calendar = wp.template('wpbc_ajx_customize_plugin__inline_calendar');
      jQuery('.wpbc_ajx_cstm__section_left').html(template__inline_calendar(data_arr)); // Widget - Weekdays Availability

      var template__widget_plugin_calendar_weekdays_availability = wp.template('wpbc_ajx_widget_calendar_weekdays_availability');
      jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(template__widget_plugin_calendar_weekdays_availability(data_arr));
      break;

    case 'calendar_additional':
      // Calendar  --------------------------------------------------------------------------------------------
      template__inline_calendar = wp.template('wpbc_ajx_customize_plugin__inline_calendar');
      jQuery('.wpbc_ajx_cstm__section_left').html(template__inline_calendar(data_arr)); // Calendar Skin

      var template__wiget_calendar_additional = wp.template('wpbc_ajx_widget_calendar_additional');
      jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(template__wiget_calendar_additional(data_arr)); // Shortcode
      // var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
      // jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );

      break;

    default: //console.log( `Sorry, we are out of ${expr}.` );

  } // Toolbar ---------------------------------------------------------------------------------------------------------


  var template__customize_plugin_toolbar_page_content = wp.template('wpbc_ajx_customize_plugin_toolbar_page_content');
  jQuery(wpbc_ajx_customize_plugin.get_other_param('toolbar_container')).html(template__customize_plugin_toolbar_page_content({
    'ajx_data': ajx_data,
    'ajx_search_params': ajx_search_params,
    // $_REQUEST[ 'search_params' ]
    'ajx_cleaned_params': ajx_cleaned_params
  })); // Booking resources  ------------------------------------------------------------------------------------------

  var wpbc_ajx_select_booking_resource = wp.template('wpbc_ajx_select_booking_resource');
  jQuery('#wpbc_hidden_template__select_booking_resource').html(wpbc_ajx_select_booking_resource({
    'ajx_data': ajx_data,
    'ajx_search_params': ajx_search_params,
    'ajx_cleaned_params': ajx_cleaned_params
  }));
  /*
   * By  default hided at ../wp-content/plugins/booking/includes/page-customize/_src/customize_plugin_page.css  #wpbc_hidden_template__select_booking_resource { display: none; }
   *
   * 	We can hide  ///-	Hide resources!
   * 				 //setTimeout( function (){ jQuery( '#wpbc_hidden_template__select_booking_resource' ).html( '' ); }, 1000 );
   */
  // Other  ---------------------------------------------------------------------------------------------------------

  jQuery('.wpbc_processing.wpbc_spin').parent().parent().parent().parent('[id^="wpbc_notice_"]').hide(); // Load calendar ---------------------------------------------------------------------------------------------------------

  wpbc_ajx_customize_plugin__calendar__show({
    'resource_id': ajx_cleaned_params.resource_id,
    'ajx_nonce_calendar': ajx_data.ajx_nonce_calendar,
    'ajx_data_arr': ajx_data,
    'ajx_cleaned_params': ajx_cleaned_params
  }); //------------------------------------------------------------------------------------------------------------------

  /**
   * Change calendar skin view
   */

  jQuery('.wpbc_radio__set_days_customize_plugin').on('change', function (event, resource_id, inst) {
    wpbc__calendar__change_skin(jQuery(this).val());
  }); // Re-load Tooltips

  jQuery(document).ready(function () {
    wpbc_define_tippy_tooltips(wpbc_ajx_customize_plugin.get_other_param('listing_container') + ' ');
    wpbc_define_tippy_tooltips(wpbc_ajx_customize_plugin.get_other_param('toolbar_container') + ' ');
  });
}
/**
 * Show inline month view calendar              with all predefined CSS (sizes and check in/out,  times containers)
 * @param {obj} calendar_params_arr
			{
				'resource_id'       	: ajx_cleaned_params.resource_id,
				'ajx_nonce_calendar'	: ajx_data_arr.ajx_nonce_calendar,
				'ajx_data_arr'          : ajx_data_arr = { ajx_booking_resources:[],  resource_unavailable_dates:[], season_customize_plugin:{},.... }
				'ajx_cleaned_params'    : {
											calendar__days_selection_mode: "dynamic"
											calendar__timeslot_day_bg_as_available: ""
											calendar__view__cell_height: ""
											calendar__view__months_in_row: 4
											calendar__view__visible_months: 12
											calendar__view__width: "100%"

											dates_customize_plugin: "unavailable"
											dates_selection: "2023-03-14 ~ 2023-03-16"
											do_action: "set_customize_plugin"
											resource_id: 1
											ui_clicked_element_id: "wpbc_customize_plugin_apply_btn"
											ui_usr__customize_plugin_selected_toolbar: "info"
								  		 }
			}
*/


function wpbc_ajx_customize_plugin__calendar__show(calendar_params_arr) {
  // Update nonce
  jQuery('#ajx_nonce_calendar_section').html(calendar_params_arr.ajx_nonce_calendar); //------------------------------------------------------------------------------------------------------------------
  // Update bookings
  //------------------------------------------------------------------------------------------------------------------

  if ('undefined' == typeof wpbc_ajx_bookings[calendar_params_arr.resource_id]) {
    wpbc_ajx_bookings[calendar_params_arr.resource_id] = [];
  }

  wpbc_ajx_bookings[calendar_params_arr.resource_id] = calendar_params_arr['ajx_data_arr']['calendar_settings']['booked_dates']; //------------------------------------------------------------------------------------------------------------------
  // Get scrolling month  or year  in calendar  and save it to  the init parameters
  //------------------------------------------------------------------------------------------------------------------

  jQuery('body').off('wpbc__inline_booking_calendar__changed_year_month');
  jQuery('body').on('wpbc__inline_booking_calendar__changed_year_month', function (event, year, month, calendar_params_arr, datepick_this) {
    wpbc_ajx_customize_plugin.search_set_param('calendar__start_year', year);
    wpbc_ajx_customize_plugin.search_set_param('calendar__start_month', month);
  }); //------------------------------------------------------------------------------------------------------------------
  // Define showing mouse over tooltip on unavailable dates
  //------------------------------------------------------------------------------------------------------------------

  jQuery('body').on('wpbc_datepick_inline_calendar_refresh', function (event, resource_id, inst) {
    /**
     * It's defined, when calendar REFRESHED (change months or days selection) loaded in jquery.datepick.wpbc.9.0.js :
     * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_refresh', ...		//FixIn: 9.4.4.13
     */
    // inst.dpDiv  it's:  <div class="datepick-inline datepick-multi" style="width: 17712px;">....</div>
    inst.dpDiv.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_cstm__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  }); //------------------------------------------------------------------------------------------------------------------
  //  Define height of the calendar  cells, 	and  mouse over tooltips at  some unavailable dates
  //------------------------------------------------------------------------------------------------------------------

  jQuery('body').on('wpbc_datepick_inline_calendar_loaded', function (event, resource_id, jCalContainer, inst) {
    /**
     * It's defined, when calendar loaded in jquery.datepick.wpbc.9.0.js :
     * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_loaded', ...		//FixIn: 9.4.4.12
     */
    // Remove highlight day for today  date
    jQuery('.datepick-days-cell.datepick-today.datepick-days-cell-over').removeClass('datepick-days-cell-over'); // Set height of calendar  cells if defined this option

    var stylesheet = document.getElementById('wpbc-calendar-cell-height');

    if (null !== stylesheet) {
      stylesheet.parentNode.removeChild(stylesheet);
    }

    if ('' !== calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height) {
      jQuery('head').append('<style type="text/css" id="wpbc-calendar-cell-height">' + '.hasDatepick .datepick-inline .datepick-title-row th, ' + '.hasDatepick .datepick-inline .datepick-days-cell {' + 'height: ' + calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height + ' !important;' + '}' + '</style>');
    } // Define showing mouse over tooltip on unavailable dates


    jCalContainer.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_cstm__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  }); //------------------------------------------------------------------------------------------------------------------
  // Define months_in_row
  //------------------------------------------------------------------------------------------------------------------

  if (undefined == calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row || '' == calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row) {
    calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row = calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months;
  } //------------------------------------------------------------------------------------------------------------------
  // Define width of entire calendar
  //------------------------------------------------------------------------------------------------------------------


  var width = ''; // var width = 'width:100%;max-width:100%;';
  // Width																											/* FixIn: 9.7.3.4 */

  if (undefined != calendar_params_arr.ajx_cleaned_params.calendar__view__width && '' !== calendar_params_arr.ajx_cleaned_params.calendar__view__width) {
    width += 'max-width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__width + ';';
    width += 'width:100%;';
  } //------------------------------------------------------------------------------------------------------------------
  // Add calendar container: "Calendar is loading..."  and textarea
  //------------------------------------------------------------------------------------------------------------------


  jQuery('.wpbc_ajx_cstm__calendar').html('<div class="' + ' bk_calendar_frame' + ' months_num_in_row_' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row + ' cal_month_num_' + calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months + ' ' + calendar_params_arr.ajx_cleaned_params.calendar__timeslot_day_bg_as_available // 'wpbc_timeslot_day_bg_as_available' || ''
  + '" ' + 'style="' + width + '">' + '<div id="calendar_booking' + calendar_params_arr.resource_id + '">' + 'Calendar is loading...' + '</div>' + '</div>' + '<textarea      id="date_booking' + calendar_params_arr.resource_id + '"' + ' name="date_booking' + calendar_params_arr.resource_id + '"' + ' autocomplete="off"' + ' style="display:none;width:100%;height:10em;margin:2em 0 0;"></textarea>'); //------------------------------------------------------------------------------------------------------------------
  // Define variables for calendar
  //------------------------------------------------------------------------------------------------------------------

  var cal_param_arr = calendar_params_arr.ajx_data_arr.calendar_settings;
  cal_param_arr['html_id'] = 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id;
  cal_param_arr['text_id'] = 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id;
  cal_param_arr['resource_id'] = calendar_params_arr.ajx_cleaned_params.resource_id;
  cal_param_arr['ajx_nonce_calendar'] = calendar_params_arr.ajx_data_arr.ajx_nonce_calendar;
  cal_param_arr['season_customize_plugin'] = calendar_params_arr.ajx_data_arr.season_customize_plugin;
  cal_param_arr['resource_unavailable_dates'] = calendar_params_arr.ajx_data_arr.resource_unavailable_dates;
  cal_param_arr['popover_hints'] = calendar_params_arr.ajx_data_arr.popover_hints; // {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
  //------------------------------------------------------------------------------------------------------------------
  // Show Calendar
  //------------------------------------------------------------------------------------------------------------------

  wpbc_show_inline_booking_calendar(cal_param_arr); //------------------------------------------------------------------------------------------------------------------
  // Scroll  to  specific Year and Month,  if defined in init parameters
  //------------------------------------------------------------------------------------------------------------------

  var s_year = wpbc_ajx_customize_plugin.search_get_param('calendar__start_year');
  var s_month = wpbc_ajx_customize_plugin.search_get_param('calendar__start_month');

  if (0 !== s_year && 0 !== s_month) {
    wpbc__inline_booking_calendar__change_year_month(cal_param_arr['resource_id'], s_year, s_month);
  }
}
/**
 * Show calendar in  different Skin
 *
 * @param selected_skin_url
 */


function wpbc__calendar__change_skin(selected_skin_url) {
  //console.log( 'SKIN SELECTION ::', selected_skin_url );
  // Remove CSS skin
  var stylesheet = document.getElementById('wpbc-calendar-skin-css');
  stylesheet.parentNode.removeChild(stylesheet); // Add new CSS skin

  var headID = document.getElementsByTagName("head")[0];
  var cssNode = document.createElement('link');
  cssNode.type = 'text/css';
  cssNode.setAttribute("id", "wpbc-calendar-skin-css");
  cssNode.rel = 'stylesheet';
  cssNode.media = 'screen';
  cssNode.href = selected_skin_url; //"http://beta/wp-content/plugins/booking/css/skins/green-01.css";

  headID.appendChild(cssNode);
}
/**
 *   Tooltips  ---------------------------------------------------------------------------------------------- */

/**
 * Define showing tooltip,  when  mouse over on  SELECTABLE (available, pending, approved, resource unavailable),  days
 * Can be called directly  from  datepick init function.
 *
 * @param value
 * @param date
 * @param calendar_params_arr
 * @param datepick_this
 * @returns {boolean}
 */


function wpbc_cstm__prepare_tooltip__in_calendar(value, date, calendar_params_arr, datepick_this) {
  if (null == date) {
    return false;
  }

  var td_class = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear();
  var jCell = jQuery('#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class);
  wpbc_cstm__show_tooltip__for_element(jCell, calendar_params_arr['popover_hints']);
  return true;
}
/**
 * Define tooltip  for showing on UNAVAILABLE days (season, weekday, today_depends unavailable)
 *
 * @param jCell					jQuery of specific day cell
 * @param popover_hints		    Array with tooltip hint texts	 : {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
 */


function wpbc_cstm__show_tooltip__for_element(jCell, popover_hints) {
  var tooltip_time = '';

  if (jCell.hasClass('season_unavailable')) {
    tooltip_time = popover_hints['season_unavailable'];
  } else if (jCell.hasClass('weekdays_unavailable')) {
    tooltip_time = popover_hints['weekdays_unavailable'];
  } else if (jCell.hasClass('before_after_unavailable')) {
    tooltip_time = popover_hints['before_after_unavailable'];
  } else if (jCell.hasClass('date2approve')) {} else if (jCell.hasClass('date_approved')) {} else {}

  jCell.attr('data-content', tooltip_time);
  var td_el = jCell.get(0); //jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class ).get(0);

  if (undefined == td_el._tippy && '' != tooltip_time) {
    wpbc_tippy(td_el, {
      content: function content(reference) {
        var popover_content = reference.getAttribute('data-content');
        return '<div class="popover popover_tippy">' + '<div class="popover-content">' + popover_content + '</div>' + '</div>';
      },
      allowHTML: true,
      trigger: 'mouseenter focus',
      interactive: !true,
      hideOnClick: true,
      interactiveBorder: 10,
      maxWidth: 550,
      theme: 'wpbc-tippy-times',
      placement: 'top',
      delay: [400, 0],
      //FixIn: 9.4.2.2
      ignoreAttributes: true,
      touch: true,
      //['hold', 500], // 500ms delay			//FixIn: 9.2.1.5
      appendTo: function appendTo() {
        return document.body;
      }
    });
  }
}
/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax show request
 */


function wpbc_ajx_customize_plugin__ajax_request() {
  console.groupCollapsed('WPBC_AJX_CUSTOMIZE_PLUGIN');
  console.log(' == Before Ajax Send - search_get_all_params() == ', wpbc_ajx_customize_plugin.search_get_all_params());
  wpbc_customize_plugin_reload_button__spin_start(); // Start Ajax

  jQuery.post(wpbc_url_ajax, {
    action: 'WPBC_AJX_CUSTOMIZE_PLUGIN',
    wpbc_ajx_user_id: wpbc_ajx_customize_plugin.get_secure_param('user_id'),
    nonce: wpbc_ajx_customize_plugin.get_secure_param('nonce'),
    wpbc_ajx_locale: wpbc_ajx_customize_plugin.get_secure_param('locale'),
    search_params: wpbc_ajx_customize_plugin.search_get_all_params()
  },
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    console.log(' == Response WPBC_AJX_CUSTOMIZE_PLUGIN == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      wpbc_ajx_customize_plugin__actual_content__hide();
      wpbc_ajx_customize_plugin__show_message(response_data);
      return;
    } // Reload page, after filter toolbar has been reset


    if (undefined != response_data['ajx_cleaned_params'] && 'reset_done' === response_data['ajx_cleaned_params']['do_action']) {
      location.reload();
      return;
    } // Show listing


    wpbc_ajx_customize_plugin__page_content__show(response_data['ajx_data'], response_data['ajx_search_params'], response_data['ajx_cleaned_params']); //wpbc_ajx_customize_plugin__define_ui_hooks();						// Redefine Hooks, because we show new DOM elements

    if ('' != response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />")) {
      wpbc_admin_show_message(response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />"), '1' == response_data['ajx_data']['ajx_after_action_result'] ? 'success' : 'error', 10000);
    }

    wpbc_customize_plugin_reload_button__spin_pause(); // Remove spin icon from  button and Enable this button.

    wpbc_button__remove_spin(response_data['ajx_cleaned_params']['ui_clicked_element_id']);
    jQuery('#ajax_respond').html(response_data); // For ability to show response, add such DIV element to page
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (window.console && window.console.log) {
      console.log('Ajax_Error', jqXHR, textStatus, errorThrown);
    }

    var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown;

    if (jqXHR.status) {
      error_message += ' (<b>' + jqXHR.status + '</b>)';

      if (403 == jqXHR.status) {
        error_message += ' Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.';
      }
    }

    if (jqXHR.responseText) {
      error_message += ' ' + jqXHR.responseText;
    }

    error_message = error_message.replace(/\n/g, "<br />");
    wpbc_ajx_customize_plugin__actual_content__hide();
    wpbc_ajx_customize_plugin__show_message(error_message);
  }) // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
  ; // End Ajax
}
/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */


function wpbc_ajx_customize_plugin__send_request_with_params(params_arr) {
  // Define different Search  parameters for request
  _.each(params_arr, function (p_val, p_key, p_data) {
    //console.log( 'Request for: ', p_key, p_val );
    wpbc_ajx_customize_plugin.search_set_param(p_key, p_val);
  }); // Send Ajax Request


  wpbc_ajx_customize_plugin__ajax_request();
}
/**
 * Search request for "Page Number"
 * @param page_number	int
 */


function wpbc_ajx_customize_plugin__pagination_click(page_number) {
  wpbc_ajx_customize_plugin__send_request_with_params({
    'page_num': page_number
  });
}
/**
 *   Show / Hide Content  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Content 	- 	Sending Ajax Request	-	with parameters that  we early  defined
 */


function wpbc_ajx_customize_plugin__actual_content__show() {
  wpbc_ajx_customize_plugin__ajax_request(); // Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}
/**
 * Hide Listing Content
 */


function wpbc_ajx_customize_plugin__actual_content__hide() {
  jQuery(wpbc_ajx_customize_plugin.get_other_param('listing_container')).html('');
}
/**
 *   M e s s a g e  --------------------------------------------------------------------------------------------- */

/**
 *
 */

/**
 * Show message in content
 *
 * @param message				Message HTML
 * @param params = {
 *                   ['type']				'warning' | 'info' | 'error' | 'success'		default: 'warning'
 *                   ['container']			'.wpbc_ajx_cstm__section_left'		default: wpbc_ajx_customize_plugin.get_other_param( 'listing_container' )
 *                   ['is_append']			true | false						default: true
 *				   }
 * Example:
 * 			var html_id = wpbc_ajx_customize_plugin__show_message( 'You can test days selection in calendar', 'info', '.wpbc_ajx_cstm__section_left', true );
 *
 *
 * @returns string  - HTML ID
 */


function wpbc_ajx_customize_plugin__show_message(message) {
  var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var params_default = {
    'type': 'warning',
    'container': wpbc_ajx_customize_plugin.get_other_param('listing_container'),
    'is_append': true,
    'style': 'text-align:left;',
    'delay': 0
  };

  _.each(params, function (p_val, p_key, p_data) {
    params_default[p_key] = p_val;
  });

  params = params_default;
  var unique_div_id = new Date();
  unique_div_id = 'wpbc_notice_' + unique_div_id.getTime();
  var alert_class = 'notice ';

  if (params['type'] == 'error') {
    alert_class += 'notice-error ';
    message = '<i style="margin-right: 0.5em;color: #d63638;" class="menu_icon icon-1x wpbc_icn_report_gmailerrorred"></i>' + message;
  }

  if (params['type'] == 'warning') {
    alert_class += 'notice-warning ';
    message = '<i style="margin-right: 0.5em;color: #e9aa04;" class="menu_icon icon-1x wpbc_icn_warning"></i>' + message;
  }

  if (params['type'] == 'info') {
    alert_class += 'notice-info ';
  }

  if (params['type'] == 'success') {
    alert_class += 'notice-info alert-success updated ';
    message = '<i style="margin-right: 0.5em;color: #64aa45;" class="menu_icon icon-1x wpbc_icn_done_outline"></i>' + message;
  }

  message = '<div id="' + unique_div_id + '" class="wpbc-settings-notice ' + alert_class + '" style="' + params['style'] + '">' + message + '</div>';

  if (params['is_append']) {
    jQuery(params['container']).append(message);
  } else {
    jQuery(params['container']).html(message);
  }

  params['delay'] = parseInt(params['delay']);

  if (params['delay'] > 0) {
    var closed_timer = setTimeout(function () {
      jQuery('#' + unique_div_id).fadeOut(1500);
    }, params['delay']);
  }

  return unique_div_id;
}
/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */


function wpbc_customize_plugin_reload_button__spin_start() {
  jQuery('#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin').removeClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  Pause
 */


function wpbc_customize_plugin_reload_button__spin_pause() {
  jQuery('#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin').addClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */


function wpbc_customize_plugin_reload_button__is_spin() {
  if (jQuery('#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin').hasClass('wpbc_animation_pause')) {
    return true;
  } else {
    return false;
  }
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtY3VzdG9taXplL19zcmMvY3VzdG9taXplX3BsdWdpbl9wYWdlLmpzIl0sIm5hbWVzIjpbIndwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4iLCJvYmoiLCIkIiwicF9zZWN1cmUiLCJzZWN1cml0eV9vYmoiLCJ1c2VyX2lkIiwibm9uY2UiLCJsb2NhbGUiLCJzZXRfc2VjdXJlX3BhcmFtIiwicGFyYW1fa2V5IiwicGFyYW1fdmFsIiwiZ2V0X3NlY3VyZV9wYXJhbSIsInBfbGlzdGluZyIsInNlYXJjaF9yZXF1ZXN0X29iaiIsInNlYXJjaF9zZXRfYWxsX3BhcmFtcyIsInJlcXVlc3RfcGFyYW1fb2JqIiwic2VhcmNoX2dldF9hbGxfcGFyYW1zIiwic2VhcmNoX2dldF9wYXJhbSIsInNlYXJjaF9zZXRfcGFyYW0iLCJzZWFyY2hfc2V0X3BhcmFtc19hcnIiLCJwYXJhbXNfYXJyIiwiXyIsImVhY2giLCJwX3ZhbCIsInBfa2V5IiwicF9kYXRhIiwicF9vdGhlciIsIm90aGVyX29iaiIsInNldF9vdGhlcl9wYXJhbSIsImdldF9vdGhlcl9wYXJhbSIsImpRdWVyeSIsIndwYmNfYWp4X2Jvb2tpbmdzIiwid3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fcGFnZV9jb250ZW50X19zaG93IiwiYWp4X2RhdGEiLCJhanhfc2VhcmNoX3BhcmFtcyIsImFqeF9jbGVhbmVkX3BhcmFtcyIsInRlbXBsYXRlX19jdXN0b21pemVfcGx1Z2luX21haW5fcGFnZV9jb250ZW50Iiwid3AiLCJ0ZW1wbGF0ZSIsImh0bWwiLCJ0ZW1wbGF0ZV9faW5saW5lX2NhbGVuZGFyIiwiZGF0YV9hcnIiLCJ0ZW1wbGF0ZV9fd2lnZXRfY2FsZW5kYXJfc2tpbiIsImFwcGVuZCIsInRlbXBsYXRlX193aWdldF9jYWxlbmRhcl9zaXplIiwibWVzc2FnZV9odG1sX2lkIiwid3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fc2hvd19tZXNzYWdlIiwid3BiY19ibGlua19lbGVtZW50IiwidGVtcGxhdGVfX3dpZGdldF9wbHVnaW5fY2FsZW5kYXJfZGF0ZXNfc2VsZWN0aW9uIiwic195ZWFyIiwic19tb250aCIsInRlbXBsYXRlX193aWRnZXRfcGx1Z2luX2NhbGVuZGFyX3dlZWtkYXlzX2F2YWlsYWJpbGl0eSIsInRlbXBsYXRlX193aWdldF9jYWxlbmRhcl9hZGRpdGlvbmFsIiwidGVtcGxhdGVfX2N1c3RvbWl6ZV9wbHVnaW5fdG9vbGJhcl9wYWdlX2NvbnRlbnQiLCJ3cGJjX2FqeF9zZWxlY3RfYm9va2luZ19yZXNvdXJjZSIsInBhcmVudCIsImhpZGUiLCJ3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19jYWxlbmRhcl9fc2hvdyIsInJlc291cmNlX2lkIiwiYWp4X25vbmNlX2NhbGVuZGFyIiwib24iLCJldmVudCIsImluc3QiLCJ3cGJjX19jYWxlbmRhcl9fY2hhbmdlX3NraW4iLCJ2YWwiLCJkb2N1bWVudCIsInJlYWR5Iiwid3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMiLCJjYWxlbmRhcl9wYXJhbXNfYXJyIiwib2ZmIiwieWVhciIsIm1vbnRoIiwiZGF0ZXBpY2tfdGhpcyIsImRwRGl2IiwiZmluZCIsInRoaXNfZXZlbnQiLCJqQ2VsbCIsImN1cnJlbnRUYXJnZXQiLCJ3cGJjX2NzdG1fX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQiLCJqQ2FsQ29udGFpbmVyIiwicmVtb3ZlQ2xhc3MiLCJzdHlsZXNoZWV0IiwiZ2V0RWxlbWVudEJ5SWQiLCJwYXJlbnROb2RlIiwicmVtb3ZlQ2hpbGQiLCJjYWxlbmRhcl9fdmlld19fY2VsbF9oZWlnaHQiLCJ1bmRlZmluZWQiLCJjYWxlbmRhcl9fdmlld19fbW9udGhzX2luX3JvdyIsImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyIsIndpZHRoIiwiY2FsZW5kYXJfX3ZpZXdfX3dpZHRoIiwiY2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGUiLCJjYWxfcGFyYW1fYXJyIiwiYWp4X2RhdGFfYXJyIiwiY2FsZW5kYXJfc2V0dGluZ3MiLCJzZWFzb25fY3VzdG9taXplX3BsdWdpbiIsInJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzIiwicG9wb3Zlcl9oaW50cyIsIndwYmNfc2hvd19pbmxpbmVfYm9va2luZ19jYWxlbmRhciIsIndwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VfeWVhcl9tb250aCIsInNlbGVjdGVkX3NraW5fdXJsIiwiaGVhZElEIiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJjc3NOb2RlIiwiY3JlYXRlRWxlbWVudCIsInR5cGUiLCJzZXRBdHRyaWJ1dGUiLCJyZWwiLCJtZWRpYSIsImhyZWYiLCJhcHBlbmRDaGlsZCIsIndwYmNfY3N0bV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciIsInZhbHVlIiwiZGF0ZSIsInRkX2NsYXNzIiwiZ2V0TW9udGgiLCJnZXREYXRlIiwiZ2V0RnVsbFllYXIiLCJ0b29sdGlwX3RpbWUiLCJoYXNDbGFzcyIsImF0dHIiLCJ0ZF9lbCIsImdldCIsIl90aXBweSIsIndwYmNfdGlwcHkiLCJjb250ZW50IiwicmVmZXJlbmNlIiwicG9wb3Zlcl9jb250ZW50IiwiZ2V0QXR0cmlidXRlIiwiYWxsb3dIVE1MIiwidHJpZ2dlciIsImludGVyYWN0aXZlIiwiaGlkZU9uQ2xpY2siLCJpbnRlcmFjdGl2ZUJvcmRlciIsIm1heFdpZHRoIiwidGhlbWUiLCJwbGFjZW1lbnQiLCJkZWxheSIsImlnbm9yZUF0dHJpYnV0ZXMiLCJ0b3VjaCIsImFwcGVuZFRvIiwiYm9keSIsIndwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2FqYXhfcmVxdWVzdCIsImNvbnNvbGUiLCJncm91cENvbGxhcHNlZCIsImxvZyIsIndwYmNfY3VzdG9taXplX3BsdWdpbl9yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0IiwicG9zdCIsIndwYmNfdXJsX2FqYXgiLCJhY3Rpb24iLCJ3cGJjX2FqeF91c2VyX2lkIiwid3BiY19hanhfbG9jYWxlIiwic2VhcmNoX3BhcmFtcyIsInJlc3BvbnNlX2RhdGEiLCJ0ZXh0U3RhdHVzIiwianFYSFIiLCJncm91cEVuZCIsIndwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2FjdHVhbF9jb250ZW50X19oaWRlIiwibG9jYXRpb24iLCJyZWxvYWQiLCJyZXBsYWNlIiwid3BiY19hZG1pbl9zaG93X21lc3NhZ2UiLCJ3cGJjX2N1c3RvbWl6ZV9wbHVnaW5fcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsIndwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJzdGF0dXMiLCJyZXNwb25zZVRleHQiLCJ3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19zZW5kX3JlcXVlc3Rfd2l0aF9wYXJhbXMiLCJ3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19wYWdpbmF0aW9uX2NsaWNrIiwicGFnZV9udW1iZXIiLCJ3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19hY3R1YWxfY29udGVudF9fc2hvdyIsIm1lc3NhZ2UiLCJwYXJhbXMiLCJwYXJhbXNfZGVmYXVsdCIsInVuaXF1ZV9kaXZfaWQiLCJEYXRlIiwiZ2V0VGltZSIsImFsZXJ0X2NsYXNzIiwicGFyc2VJbnQiLCJjbG9zZWRfdGltZXIiLCJzZXRUaW1lb3V0IiwiZmFkZU91dCIsImFkZENsYXNzIiwid3BiY19jdXN0b21pemVfcGx1Z2luX3JlbG9hZF9idXR0b25fX2lzX3NwaW4iXSwibWFwcGluZ3MiOiJBQUFBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUVBLElBQUlBLHlCQUF5QixHQUFJLFVBQVdDLEdBQVgsRUFBZ0JDLENBQWhCLEVBQW1CO0FBRW5EO0FBQ0EsTUFBSUMsUUFBUSxHQUFHRixHQUFHLENBQUNHLFlBQUosR0FBbUJILEdBQUcsQ0FBQ0csWUFBSixJQUFvQjtBQUN4Q0MsSUFBQUEsT0FBTyxFQUFFLENBRCtCO0FBRXhDQyxJQUFBQSxLQUFLLEVBQUksRUFGK0I7QUFHeENDLElB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Q0EsR0FGRCxNQUVPO0FBQ05oSixJQUFBQSxNQUFNLENBQUVpSixNQUFNLENBQUMsV0FBRCxDQUFSLENBQU4sQ0FBOEJ4SSxJQUE5QixDQUFvQ3VJLE9BQXBDO0FBQ0E7O0FBRURDLEVBQUFBLE1BQU0sQ0FBQyxPQUFELENBQU4sR0FBa0JNLFFBQVEsQ0FBRU4sTUFBTSxDQUFDLE9BQUQsQ0FBUixDQUExQjs7QUFDQSxNQUFLQSxNQUFNLENBQUMsT0FBRCxDQUFOLEdBQWtCLENBQXZCLEVBQTBCO0FBRXpCLFFBQUlPLFlBQVksR0FBR0MsVUFBVSxDQUFFLFlBQVc7QUFDM0J6SixNQUFBQSxNQUFNLENBQUUsTUFBTW1KLGFBQVIsQ0FBTixDQUE4Qk8sT0FBOUIsQ0FBdUMsSUFBdkM7QUFDQSxLQUZjLEVBR2pCVCxNQUFNLENBQUUsT0FBRixDQUhXLENBQTdCO0FBS0E7O0FBRUQsU0FBT0UsYUFBUDtBQUNBO0FBSUQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVMvQiwrQ0FBVCxHQUEwRDtBQUN6RHBILEVBQUFBLE1BQU0sQ0FBRSwyREFBRixDQUFOLENBQXFFa0QsV0FBckUsQ0FBa0Ysc0JBQWxGO0FBQ0E7QUFFRDtBQUNBO0FBQ0E7OztBQUNBLFNBQVNrRiwrQ0FBVCxHQUEwRDtBQUN6RHBJLEVBQUFBLE1BQU0sQ0FBRSwyREFBRixDQUFOLENBQXNFMkosUUFBdEUsQ0FBZ0Ysc0JBQWhGO0FBQ0E7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFDQSxTQUFTQyw0Q0FBVCxHQUF1RDtBQUNuRCxNQUFLNUosTUFBTSxDQUFFLDJEQUFGLENBQU4sQ0FBc0V5RixRQUF0RSxDQUFnRixzQkFBaEYsQ0FBTCxFQUErRztBQUNqSCxXQUFPLElBQVA7QUFDQSxHQUZFLE1BRUk7QUFDTixXQUFPLEtBQVA7QUFDQTtBQUNEIiwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XHJcblxyXG4vKipcclxuICogUmVxdWVzdCBPYmplY3RcclxuICogSGVyZSB3ZSBjYW4gIGRlZmluZSBTZWFyY2ggcGFyYW1ldGVycyBhbmQgVXBkYXRlIGl0IGxhdGVyLCAgd2hlbiAgc29tZSBwYXJhbWV0ZXIgd2FzIGNoYW5nZWRcclxuICpcclxuICovXHJcblxyXG52YXIgd3BiY19hanhfY3VzdG9taXplX3BsdWdpbiA9IChmdW5jdGlvbiAoIG9iaiwgJCkge1xyXG5cclxuXHQvLyBTZWN1cmUgcGFyYW1ldGVycyBmb3IgQWpheFx0LS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHBfc2VjdXJlID0gb2JqLnNlY3VyaXR5X29iaiA9IG9iai5zZWN1cml0eV9vYmogfHwge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR1c2VyX2lkOiAwLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRub25jZSAgOiAnJyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0bG9jYWxlIDogJydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfTtcclxuXHJcblx0b2JqLnNldF9zZWN1cmVfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSwgcGFyYW1fdmFsICkge1xyXG5cdFx0cF9zZWN1cmVbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5nZXRfc2VjdXJlX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXkgKSB7XHJcblx0XHRyZXR1cm4gcF9zZWN1cmVbIHBhcmFtX2tleSBdO1xyXG5cdH07XHJcblxyXG5cclxuXHQvLyBMaXN0aW5nIFNlYXJjaCBwYXJhbWV0ZXJzXHQtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR2YXIgcF9saXN0aW5nID0gb2JqLnNlYXJjaF9yZXF1ZXN0X29iaiA9IG9iai5zZWFyY2hfcmVxdWVzdF9vYmogfHwge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBzb3J0ICAgICAgICAgICAgOiBcImJvb2tpbmdfaWRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gc29ydF90eXBlICAgICAgIDogXCJERVNDXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIHBhZ2VfbnVtICAgICAgICA6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIHBhZ2VfaXRlbXNfY291bnQ6IDEwLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBjcmVhdGVfZGF0ZSAgICAgOiBcIlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBrZXl3b3JkICAgICAgICAgOiBcIlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBzb3VyY2UgICAgICAgICAgOiBcIlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX3NldF9hbGxfcGFyYW1zID0gZnVuY3Rpb24gKCByZXF1ZXN0X3BhcmFtX29iaiApIHtcclxuXHRcdHBfbGlzdGluZyA9IHJlcXVlc3RfcGFyYW1fb2JqO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfZ2V0X2FsbF9wYXJhbXMgPSBmdW5jdGlvbiAoKSB7XHJcblx0XHRyZXR1cm4gcF9saXN0aW5nO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfZ2V0X3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXkgKSB7XHJcblx0XHRyZXR1cm4gcF9saXN0aW5nWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX3NldF9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5LCBwYXJhbV92YWwgKSB7XHJcblx0XHQvLyBpZiAoIEFycmF5LmlzQXJyYXkoIHBhcmFtX3ZhbCApICl7XHJcblx0XHQvLyBcdHBhcmFtX3ZhbCA9IEpTT04uc3RyaW5naWZ5KCBwYXJhbV92YWwgKTtcclxuXHRcdC8vIH1cclxuXHRcdHBfbGlzdGluZ1sgcGFyYW1fa2V5IF0gPSBwYXJhbV92YWw7XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfcGFyYW1zX2FyciA9IGZ1bmN0aW9uKCBwYXJhbXNfYXJyICl7XHJcblx0XHRfLmVhY2goIHBhcmFtc19hcnIsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBEZWZpbmUgZGlmZmVyZW50IFNlYXJjaCAgcGFyYW1ldGVycyBmb3IgcmVxdWVzdFxyXG5cdFx0XHR0aGlzLnNlYXJjaF9zZXRfcGFyYW0oIHBfa2V5LCBwX3ZhbCApO1xyXG5cdFx0fSApO1xyXG5cdH1cclxuXHJcblxyXG5cdC8vIE90aGVyIHBhcmFtZXRlcnMgXHRcdFx0LS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHBfb3RoZXIgPSBvYmoub3RoZXJfb2JqID0gb2JqLm90aGVyX29iaiB8fCB7IH07XHJcblxyXG5cdG9iai5zZXRfb3RoZXJfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSwgcGFyYW1fdmFsICkge1xyXG5cdFx0cF9vdGhlclsgcGFyYW1fa2V5IF0gPSBwYXJhbV92YWw7XHJcblx0fTtcclxuXHJcblx0b2JqLmdldF9vdGhlcl9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfb3RoZXJbIHBhcmFtX2tleSBdO1xyXG5cdH07XHJcblxyXG5cclxuXHRyZXR1cm4gb2JqO1xyXG59KCB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luIHx8IHt9LCBqUXVlcnkgKSk7XHJcblxyXG52YXIgd3BiY19hanhfYm9va2luZ3MgPSBbXTtcclxuXHJcbi8qKlxyXG4gKiAgIFNob3cgQ29udGVudCAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNob3cgQ29udGVudCAtIENhbGVuZGFyIGFuZCBVSSBlbGVtZW50c1xyXG4gKlxyXG4gKiBAcGFyYW0gYWp4X2RhdGFcclxuICogQHBhcmFtIGFqeF9zZWFyY2hfcGFyYW1zXHJcbiAqIEBwYXJhbSBhanhfY2xlYW5lZF9wYXJhbXNcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX3BhZ2VfY29udGVudF9fc2hvdyggYWp4X2RhdGEsIGFqeF9zZWFyY2hfcGFyYW1zICwgYWp4X2NsZWFuZWRfcGFyYW1zICl7XHJcblxyXG5cdC8vIENvbnRlbnQgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHRlbXBsYXRlX19jdXN0b21pemVfcGx1Z2luX21haW5fcGFnZV9jb250ZW50ID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX21haW5fcGFnZV9jb250ZW50JyApO1xyXG5cdGpRdWVyeSggd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoIHRlbXBsYXRlX19jdXN0b21pemVfcGx1Z2luX21haW5fcGFnZV9jb250ZW50KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9kYXRhJyAgICAgICAgICAgICAgOiBhanhfZGF0YSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X3NlYXJjaF9wYXJhbXMnICAgICA6IGFqeF9zZWFyY2hfcGFyYW1zLFx0XHRcdFx0XHRcdFx0XHQvLyAkX1JFUVVFU1RbICdzZWFyY2hfcGFyYW1zJyBdXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiBhanhfY2xlYW5lZF9wYXJhbXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSApICk7XHJcblxyXG5cdHZhciB0ZW1wbGF0ZV9faW5saW5lX2NhbGVuZGFyO1xyXG5cdHZhciBkYXRhX2FyciA9IHtcclxuXHRcdFx0XHRcdFx0XHQnYWp4X2RhdGEnICAgICAgICAgICAgICA6IGFqeF9kYXRhLFxyXG5cdFx0XHRcdFx0XHRcdCdhanhfc2VhcmNoX3BhcmFtcycgICAgIDogYWp4X3NlYXJjaF9wYXJhbXMsXHJcblx0XHRcdFx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiBhanhfY2xlYW5lZF9wYXJhbXNcclxuXHRcdFx0XHRcdFx0fTtcclxuXHJcblx0c3dpdGNoICggYWp4X2RhdGFbJ2N1c3RvbWl6ZV9zdGVwcyddWydjdXJyZW50J10gKXtcclxuXHJcblx0XHRjYXNlICdjYWxlbmRhcl9za2luJzpcclxuXHJcblx0XHRcdC8vIENhbGVuZGFyICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdFx0XHR0ZW1wbGF0ZV9faW5saW5lX2NhbGVuZGFyID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19pbmxpbmVfY2FsZW5kYXInICk7XHJcblx0XHRcdGpRdWVyeSgnLndwYmNfYWp4X2NzdG1fX3NlY3Rpb25fbGVmdCcpLmh0bWwoXHR0ZW1wbGF0ZV9faW5saW5lX2NhbGVuZGFyKCBkYXRhX2FyciApICk7XHJcblxyXG5cdFx0XHQvLyBDYWxlbmRhciBTa2luXHJcblx0XHRcdHZhciB0ZW1wbGF0ZV9fd2lnZXRfY2FsZW5kYXJfc2tpbiA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfd2lkZ2V0X2NoYW5nZV9jYWxlbmRhcl9za2luJyApO1xyXG5cdFx0XHRqUXVlcnkoJy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX3JpZ2h0IC53cGJjX3dpZGdldHMnKS5hcHBlbmQoXHR0ZW1wbGF0ZV9fd2lnZXRfY2FsZW5kYXJfc2tpbiggZGF0YV9hcnIgKSApO1xyXG5cclxuXHRcdFx0Ly8gU2hvcnRjb2RlXHJcblx0XHRcdC8vIHZhciB0ZW1wbGF0ZV9fd2lkZ2V0X3BsdWdpbl9zaG9ydGNvZGUgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X3dpZGdldF9wbHVnaW5fc2hvcnRjb2RlJyApO1xyXG5cdFx0XHQvLyBqUXVlcnkoJy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX3JpZ2h0IC53cGJjX3dpZGdldHMnKS5hcHBlbmQoXHR0ZW1wbGF0ZV9fd2lkZ2V0X3BsdWdpbl9zaG9ydGNvZGUoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdC8vIFNpemVcclxuXHRcdFx0Ly8gdmFyIHRlbXBsYXRlX193aWdldF9jYWxlbmRhcl9zaXplID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF93aWRnZXRfY2FsZW5kYXJfc2l6ZScgKTtcclxuXHRcdFx0Ly8galF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9yaWdodCAud3BiY193aWRnZXRzJykuYXBwZW5kKFx0dGVtcGxhdGVfX3dpZ2V0X2NhbGVuZGFyX3NpemUoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdGJyZWFrO1xyXG5cclxuXHRcdGNhc2UgJ2NhbGVuZGFyX3NpemUnOlxyXG5cclxuXHRcdFx0Ly8gQ2FsZW5kYXIgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0XHRcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2lubGluZV9jYWxlbmRhcicgKTtcclxuXHRcdFx0alF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9sZWZ0JykuaHRtbChcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdC8vIENhbGVuZGFyIFNraW5cclxuXHRcdFx0dmFyIHRlbXBsYXRlX193aWdldF9jYWxlbmRhcl9zaXplID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF93aWRnZXRfY2FsZW5kYXJfc2l6ZScgKTtcclxuXHRcdFx0alF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9yaWdodCAud3BiY193aWRnZXRzJykuYXBwZW5kKFx0dGVtcGxhdGVfX3dpZ2V0X2NhbGVuZGFyX3NpemUoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdC8vIFNob3J0Y29kZVxyXG5cdFx0XHQvLyB2YXIgdGVtcGxhdGVfX3dpZGdldF9wbHVnaW5fc2hvcnRjb2RlID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF93aWRnZXRfcGx1Z2luX3Nob3J0Y29kZScgKTtcclxuXHRcdFx0Ly8galF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9yaWdodCAud3BiY193aWRnZXRzJykuYXBwZW5kKFx0dGVtcGxhdGVfX3dpZGdldF9wbHVnaW5fc2hvcnRjb2RlKCBkYXRhX2FyciApICk7XHJcblxyXG5cdFx0XHRicmVhaztcclxuXHJcblx0XHRjYXNlICdjYWxlbmRhcl9kYXRlc19zZWxlY3Rpb24nOlxyXG5cclxuXHRcdFx0Ly8gQ2FsZW5kYXIgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0XHRcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2lubGluZV9jYWxlbmRhcicgKTtcclxuXHRcdFx0alF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9sZWZ0JykuaHRtbChcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdGpRdWVyeSgnLndwYmNfYWp4X2NzdG1fX3NlY3Rpb25fbGVmdCcpLmFwcGVuZCgnPGRpdiBjbGFzcz1cImNsZWFyXCIgc3R5bGU9XCJ3aWR0aDoxMDAlO21hcmdpbjo1MHB4IDAgMDtcIj48L2Rpdj4nKTtcclxuXHJcblx0XHRcdHZhciBtZXNzYWdlX2h0bWxfaWQgPSB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19zaG93X21lc3NhZ2UoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0JzxzdHJvbmc+JyArXHQnWW91IGNhbiB0ZXN0IGRheXMgc2VsZWN0aW9uIGluIGNhbGVuZGFyJyArICc8L3N0cm9uZz4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjb250YWluZXInOiAnLndwYmNfYWp4X2NzdG1fX3NlY3Rpb25fbGVmdCcsXHRcdC8vICcjYWpheF93b3JraW5nJyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3N0eWxlJyAgICA6ICdtYXJnaW46IDZweCBhdXRvOyAgcGFkZGluZzogNnB4IDIwcHg7ei1pbmRleDogOTk5OTk5OycsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd0eXBlJyAgICAgOiAnaW5mbycsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkZWxheScgICAgOiA1MDAwXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KTtcclxuXHRcdFx0d3BiY19ibGlua19lbGVtZW50KCAnIycgKyBtZXNzYWdlX2h0bWxfaWQsIDMsIDMyMCApO1xyXG5cclxuXHRcdFx0Ly8gV2lkZ2V0IC0gRGF0ZXMgc2VsZWN0aW9uXHJcblx0XHRcdCB2YXIgdGVtcGxhdGVfX3dpZGdldF9wbHVnaW5fY2FsZW5kYXJfZGF0ZXNfc2VsZWN0aW9uID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF93aWRnZXRfY2FsZW5kYXJfZGF0ZXNfc2VsZWN0aW9uJyApO1xyXG5cdFx0XHQgalF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9yaWdodCAud3BiY193aWRnZXRzJykuYXBwZW5kKFx0dGVtcGxhdGVfX3dpZGdldF9wbHVnaW5fY2FsZW5kYXJfZGF0ZXNfc2VsZWN0aW9uKCBkYXRhX2FyciApICk7XHJcblxyXG5cdFx0XHRicmVhaztcclxuXHJcblx0XHRjYXNlICdjYWxlbmRhcl93ZWVrZGF5c19hdmFpbGFiaWxpdHknOlxyXG5cclxuXHRcdFx0Ly8gU2Nyb2xsICB0byAgY3VycmVudCBtb250aFxyXG5cdFx0XHR2YXIgc195ZWFyID0gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5zZWFyY2hfc2V0X3BhcmFtKCAnY2FsZW5kYXJfX3N0YXJ0X3llYXInLCAwICk7XHJcblx0XHRcdHZhciBzX21vbnRoID0gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5zZWFyY2hfc2V0X3BhcmFtKCAnY2FsZW5kYXJfX3N0YXJ0X21vbnRoJywgMCApO1xyXG5cclxuXHRcdFx0Ly8gQ2FsZW5kYXIgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0XHRcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2lubGluZV9jYWxlbmRhcicgKTtcclxuXHRcdFx0alF1ZXJ5KCcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9sZWZ0JykuaHRtbChcdHRlbXBsYXRlX19pbmxpbmVfY2FsZW5kYXIoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdC8vIFdpZGdldCAtIFdlZWtkYXlzIEF2YWlsYWJpbGl0eVxyXG5cdFx0XHQgdmFyIHRlbXBsYXRlX193aWRnZXRfcGx1Z2luX2NhbGVuZGFyX3dlZWtkYXlzX2F2YWlsYWJpbGl0eSA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfd2lkZ2V0X2NhbGVuZGFyX3dlZWtkYXlzX2F2YWlsYWJpbGl0eScgKTtcclxuXHRcdFx0IGpRdWVyeSgnLndwYmNfYWp4X2NzdG1fX3NlY3Rpb25fcmlnaHQgLndwYmNfd2lkZ2V0cycpLmFwcGVuZChcdHRlbXBsYXRlX193aWRnZXRfcGx1Z2luX2NhbGVuZGFyX3dlZWtkYXlzX2F2YWlsYWJpbGl0eSggZGF0YV9hcnIgKSApO1xyXG5cclxuXHRcdFx0YnJlYWs7XHJcblxyXG5cdFx0Y2FzZSAnY2FsZW5kYXJfYWRkaXRpb25hbCc6XHJcblxyXG5cdFx0XHQvLyBDYWxlbmRhciAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRcdFx0dGVtcGxhdGVfX2lubGluZV9jYWxlbmRhciA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9faW5saW5lX2NhbGVuZGFyJyApO1xyXG5cdFx0XHRqUXVlcnkoJy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX2xlZnQnKS5odG1sKFx0dGVtcGxhdGVfX2lubGluZV9jYWxlbmRhciggZGF0YV9hcnIgKSApO1xyXG5cclxuXHRcdFx0Ly8gQ2FsZW5kYXIgU2tpblxyXG5cdFx0XHR2YXIgdGVtcGxhdGVfX3dpZ2V0X2NhbGVuZGFyX2FkZGl0aW9uYWwgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X3dpZGdldF9jYWxlbmRhcl9hZGRpdGlvbmFsJyApO1xyXG5cdFx0XHRqUXVlcnkoJy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX3JpZ2h0IC53cGJjX3dpZGdldHMnKS5hcHBlbmQoXHR0ZW1wbGF0ZV9fd2lnZXRfY2FsZW5kYXJfYWRkaXRpb25hbCggZGF0YV9hcnIgKSApO1xyXG5cclxuXHRcdFx0Ly8gU2hvcnRjb2RlXHJcblx0XHRcdC8vIHZhciB0ZW1wbGF0ZV9fd2lkZ2V0X3BsdWdpbl9zaG9ydGNvZGUgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X3dpZGdldF9wbHVnaW5fc2hvcnRjb2RlJyApO1xyXG5cdFx0XHQvLyBqUXVlcnkoJy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX3JpZ2h0IC53cGJjX3dpZGdldHMnKS5hcHBlbmQoXHR0ZW1wbGF0ZV9fd2lkZ2V0X3BsdWdpbl9zaG9ydGNvZGUoIGRhdGFfYXJyICkgKTtcclxuXHJcblx0XHRcdGJyZWFrO1xyXG5cclxuXHRcdGRlZmF1bHQ6XHJcblx0XHRcdC8vY29uc29sZS5sb2coIGBTb3JyeSwgd2UgYXJlIG91dCBvZiAke2V4cHJ9LmAgKTtcclxuXHR9XHJcblxyXG5cdC8vIFRvb2xiYXIgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHRlbXBsYXRlX19jdXN0b21pemVfcGx1Z2luX3Rvb2xiYXJfcGFnZV9jb250ZW50ID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX3Rvb2xiYXJfcGFnZV9jb250ZW50JyApO1xyXG5cdGpRdWVyeSggd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5nZXRfb3RoZXJfcGFyYW0oICd0b29sYmFyX2NvbnRhaW5lcicgKSApLmh0bWwoIHRlbXBsYXRlX19jdXN0b21pemVfcGx1Z2luX3Rvb2xiYXJfcGFnZV9jb250ZW50KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9kYXRhJyAgICAgICAgICAgICAgOiBhanhfZGF0YSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X3NlYXJjaF9wYXJhbXMnICAgICA6IGFqeF9zZWFyY2hfcGFyYW1zLFx0XHRcdFx0XHRcdFx0XHQvLyAkX1JFUVVFU1RbICdzZWFyY2hfcGFyYW1zJyBdXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiBhanhfY2xlYW5lZF9wYXJhbXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSApICk7XHJcblxyXG5cclxuXHRcdC8vIEJvb2tpbmcgcmVzb3VyY2VzICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRcdHZhciB3cGJjX2FqeF9zZWxlY3RfYm9va2luZ19yZXNvdXJjZSA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfc2VsZWN0X2Jvb2tpbmdfcmVzb3VyY2UnICk7XHJcblx0XHRqUXVlcnkoICcjd3BiY19oaWRkZW5fdGVtcGxhdGVfX3NlbGVjdF9ib29raW5nX3Jlc291cmNlJykuaHRtbCggd3BiY19hanhfc2VsZWN0X2Jvb2tpbmdfcmVzb3VyY2UoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfZGF0YScgICAgICAgICAgICAgIDogYWp4X2RhdGEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X3NlYXJjaF9wYXJhbXMnICAgICA6IGFqeF9zZWFyY2hfcGFyYW1zLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiBhanhfY2xlYW5lZF9wYXJhbXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9IFx0KSApO1xyXG5cdFx0LypcclxuXHRcdCAqIEJ5ICBkZWZhdWx0IGhpZGVkIGF0IC4uL3dwLWNvbnRlbnQvcGx1Z2lucy9ib29raW5nL2luY2x1ZGVzL3BhZ2UtY3VzdG9taXplL19zcmMvY3VzdG9taXplX3BsdWdpbl9wYWdlLmNzcyAgI3dwYmNfaGlkZGVuX3RlbXBsYXRlX19zZWxlY3RfYm9va2luZ19yZXNvdXJjZSB7IGRpc3BsYXk6IG5vbmU7IH1cclxuXHRcdCAqXHJcblx0XHQgKiBcdFdlIGNhbiBoaWRlICAvLy8tXHRIaWRlIHJlc291cmNlcyFcclxuXHRcdCAqIFx0XHRcdFx0IC8vc2V0VGltZW91dCggZnVuY3Rpb24gKCl7IGpRdWVyeSggJyN3cGJjX2hpZGRlbl90ZW1wbGF0ZV9fc2VsZWN0X2Jvb2tpbmdfcmVzb3VyY2UnICkuaHRtbCggJycgKTsgfSwgMTAwMCApO1xyXG5cdFx0ICovXHJcblxyXG5cclxuXHJcblxyXG5cdC8vIE90aGVyICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICcud3BiY19wcm9jZXNzaW5nLndwYmNfc3BpbicpLnBhcmVudCgpLnBhcmVudCgpLnBhcmVudCgpLnBhcmVudCggJ1tpZF49XCJ3cGJjX25vdGljZV9cIl0nICkuaGlkZSgpO1xyXG5cclxuXHJcblx0Ly8gTG9hZCBjYWxlbmRhciAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19jYWxlbmRhcl9fc2hvdygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICA6IGFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfbm9uY2VfY2FsZW5kYXInOiBhanhfZGF0YS5hanhfbm9uY2VfY2FsZW5kYXIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X2RhdGFfYXJyJyAgICAgICAgICA6IGFqeF9kYXRhLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiBhanhfY2xlYW5lZF9wYXJhbXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9ICk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0LyoqXHJcblx0ICogQ2hhbmdlIGNhbGVuZGFyIHNraW4gdmlld1xyXG5cdCAqL1xyXG5cdGpRdWVyeSggJy53cGJjX3JhZGlvX19zZXRfZGF5c19jdXN0b21pemVfcGx1Z2luJyApLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoIGV2ZW50LCByZXNvdXJjZV9pZCwgaW5zdCApe1xyXG5cdFx0d3BiY19fY2FsZW5kYXJfX2NoYW5nZV9za2luKCBqUXVlcnkoIHRoaXMgKS52YWwoKSApO1xyXG5cdH0pO1xyXG5cclxuXHJcblx0Ly8gUmUtbG9hZCBUb29sdGlwc1xyXG5cdGpRdWVyeSggZG9jdW1lbnQgKS5yZWFkeSggZnVuY3Rpb24gKCl7XHJcblx0XHR3cGJjX2RlZmluZV90aXBweV90b29sdGlwcyggd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSArICcgJyApO1xyXG5cdFx0d3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMoIHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4uZ2V0X290aGVyX3BhcmFtKCAndG9vbGJhcl9jb250YWluZXInICkgKyAnICcgKTtcclxuXHR9KTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiBTaG93IGlubGluZSBtb250aCB2aWV3IGNhbGVuZGFyICAgICAgICAgICAgICB3aXRoIGFsbCBwcmVkZWZpbmVkIENTUyAoc2l6ZXMgYW5kIGNoZWNrIGluL291dCwgIHRpbWVzIGNvbnRhaW5lcnMpXHJcbiAqIEBwYXJhbSB7b2JqfSBjYWxlbmRhcl9wYXJhbXNfYXJyXHJcblx0XHRcdHtcclxuXHRcdFx0XHQncmVzb3VyY2VfaWQnICAgICAgIFx0OiBhanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0J2FqeF9ub25jZV9jYWxlbmRhcidcdDogYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHQnYWp4X2RhdGFfYXJyJyAgICAgICAgICA6IGFqeF9kYXRhX2FyciA9IHsgYWp4X2Jvb2tpbmdfcmVzb3VyY2VzOltdLCAgcmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXM6W10sIHNlYXNvbl9jdXN0b21pemVfcGx1Z2luOnt9LC4uLi4gfVxyXG5cdFx0XHRcdCdhanhfY2xlYW5lZF9wYXJhbXMnICAgIDoge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU6IFwiZHluYW1pY1wiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fdGltZXNsb3RfZGF5X2JnX2FzX2F2YWlsYWJsZTogXCJcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3ZpZXdfX2NlbGxfaGVpZ2h0OiBcIlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fdmlld19fbW9udGhzX2luX3JvdzogNFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzOiAxMlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX3ZpZXdfX3dpZHRoOiBcIjEwMCVcIlxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX2N1c3RvbWl6ZV9wbHVnaW46IFwidW5hdmFpbGFibGVcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfc2VsZWN0aW9uOiBcIjIwMjMtMDMtMTQgfiAyMDIzLTAzLTE2XCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRvX2FjdGlvbjogXCJzZXRfY3VzdG9taXplX3BsdWdpblwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRyZXNvdXJjZV9pZDogMVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0dWlfY2xpY2tlZF9lbGVtZW50X2lkOiBcIndwYmNfY3VzdG9taXplX3BsdWdpbl9hcHBseV9idG5cIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0dWlfdXNyX19jdXN0b21pemVfcGx1Z2luX3NlbGVjdGVkX3Rvb2xiYXI6IFwiaW5mb1wiXHJcblx0XHRcdFx0XHRcdFx0XHQgIFx0XHQgfVxyXG5cdFx0XHR9XHJcbiovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2NhbGVuZGFyX19zaG93KCBjYWxlbmRhcl9wYXJhbXNfYXJyICl7XHJcblxyXG5cdC8vIFVwZGF0ZSBub25jZVxyXG5cdGpRdWVyeSggJyNhanhfbm9uY2VfY2FsZW5kYXJfc2VjdGlvbicgKS5odG1sKCBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9ub25jZV9jYWxlbmRhciApO1xyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBVcGRhdGUgYm9va2luZ3NcclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGlmICggJ3VuZGVmaW5lZCcgPT0gdHlwZW9mICh3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdKSApeyB3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdID0gW107IH1cclxuXHR3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdID0gY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsgJ2NhbGVuZGFyX3NldHRpbmdzJyBdWyAnYm9va2VkX2RhdGVzJyBdO1xyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuIFx0Ly8gR2V0IHNjcm9sbGluZyBtb250aCAgb3IgeWVhciAgaW4gY2FsZW5kYXIgIGFuZCBzYXZlIGl0IHRvICB0aGUgaW5pdCBwYXJhbWV0ZXJzXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICdib2R5JyApLm9mZiggJ3dwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VkX3llYXJfbW9udGgnICk7XHJcblx0alF1ZXJ5KCAnYm9keScgKS5vbiggJ3dwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VkX3llYXJfbW9udGgnLCBmdW5jdGlvbiAoIGV2ZW50LCB5ZWFyLCBtb250aCwgY2FsZW5kYXJfcGFyYW1zX2FyciwgZGF0ZXBpY2tfdGhpcyApe1xyXG5cclxuXHRcdHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4uc2VhcmNoX3NldF9wYXJhbSggJ2NhbGVuZGFyX19zdGFydF95ZWFyJywgeWVhciApO1xyXG5cdFx0d3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5zZWFyY2hfc2V0X3BhcmFtKCAnY2FsZW5kYXJfX3N0YXJ0X21vbnRoJywgbW9udGggKTtcclxuXHR9ICk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gRGVmaW5lIHNob3dpbmcgbW91c2Ugb3ZlciB0b29sdGlwIG9uIHVuYXZhaWxhYmxlIGRhdGVzXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICdib2R5JyApLm9uKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfcmVmcmVzaCcsIGZ1bmN0aW9uICggZXZlbnQsIHJlc291cmNlX2lkLCBpbnN0ICl7XHJcblxyXG5cdFx0LyoqXHJcblx0XHQgKiBJdCdzIGRlZmluZWQsIHdoZW4gY2FsZW5kYXIgUkVGUkVTSEVEIChjaGFuZ2UgbW9udGhzIG9yIGRheXMgc2VsZWN0aW9uKSBsb2FkZWQgaW4ganF1ZXJ5LmRhdGVwaWNrLndwYmMuOS4wLmpzIDpcclxuXHRcdCAqIFx0XHQkKCAnYm9keScgKS50cmlnZ2VyKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfcmVmcmVzaCcsIC4uLlx0XHQvL0ZpeEluOiA5LjQuNC4xM1xyXG5cdFx0ICovXHJcblxyXG5cdFx0Ly8gaW5zdC5kcERpdiAgaXQnczogIDxkaXYgY2xhc3M9XCJkYXRlcGljay1pbmxpbmUgZGF0ZXBpY2stbXVsdGlcIiBzdHlsZT1cIndpZHRoOiAxNzcxMnB4O1wiPi4uLi48L2Rpdj5cclxuXHJcblx0XHRpbnN0LmRwRGl2LmZpbmQoICcuc2Vhc29uX3VuYXZhaWxhYmxlLC5iZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUsLndlZWtkYXlzX3VuYXZhaWxhYmxlJyApLm9uKCAnbW91c2VvdmVyJywgZnVuY3Rpb24gKCB0aGlzX2V2ZW50ICl7XHJcblx0XHRcdC8vIGFsc28gYXZhaWxhYmxlIHRoZXNlIHZhcnM6IFx0cmVzb3VyY2VfaWQsIGpDYWxDb250YWluZXIsIGluc3RcclxuXHRcdFx0dmFyIGpDZWxsID0galF1ZXJ5KCB0aGlzX2V2ZW50LmN1cnJlbnRUYXJnZXQgKTtcclxuXHRcdFx0d3BiY19jc3RtX19zaG93X3Rvb2x0aXBfX2Zvcl9lbGVtZW50KCBqQ2VsbCwgY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsncG9wb3Zlcl9oaW50cyddICk7XHJcblx0XHR9KTtcclxuXHJcblx0fSk7XHJcblxyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vICBEZWZpbmUgaGVpZ2h0IG9mIHRoZSBjYWxlbmRhciAgY2VsbHMsIFx0YW5kICBtb3VzZSBvdmVyIHRvb2x0aXBzIGF0ICBzb21lIHVuYXZhaWxhYmxlIGRhdGVzXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICdib2R5JyApLm9uKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfbG9hZGVkJywgZnVuY3Rpb24gKCBldmVudCwgcmVzb3VyY2VfaWQsIGpDYWxDb250YWluZXIsIGluc3QgKXtcclxuXHJcblx0XHQvKipcclxuXHRcdCAqIEl0J3MgZGVmaW5lZCwgd2hlbiBjYWxlbmRhciBsb2FkZWQgaW4ganF1ZXJ5LmRhdGVwaWNrLndwYmMuOS4wLmpzIDpcclxuXHRcdCAqIFx0XHQkKCAnYm9keScgKS50cmlnZ2VyKCAnd3BiY19kYXRlcGlja19pbmxpbmVfY2FsZW5kYXJfbG9hZGVkJywgLi4uXHRcdC8vRml4SW46IDkuNC40LjEyXHJcblx0XHQgKi9cclxuXHJcblx0XHQvLyBSZW1vdmUgaGlnaGxpZ2h0IGRheSBmb3IgdG9kYXkgIGRhdGVcclxuXHRcdGpRdWVyeSggJy5kYXRlcGljay1kYXlzLWNlbGwuZGF0ZXBpY2stdG9kYXkuZGF0ZXBpY2stZGF5cy1jZWxsLW92ZXInICkucmVtb3ZlQ2xhc3MoICdkYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKTtcclxuXHJcblx0XHQvLyBTZXQgaGVpZ2h0IG9mIGNhbGVuZGFyICBjZWxscyBpZiBkZWZpbmVkIHRoaXMgb3B0aW9uXHJcblx0XHR2YXIgc3R5bGVzaGVldCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnd3BiYy1jYWxlbmRhci1jZWxsLWhlaWdodCcgKTtcclxuXHRcdGlmICggbnVsbCAhPT0gc3R5bGVzaGVldCApe1xyXG5cdFx0XHRzdHlsZXNoZWV0LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoIHN0eWxlc2hlZXQgKTtcclxuXHRcdH1cclxuXHRcdGlmICggJycgIT09IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCApe1xyXG5cdFx0XHRqUXVlcnkoICdoZWFkJyApLmFwcGVuZCggJzxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIiBpZD1cIndwYmMtY2FsZW5kYXItY2VsbC1oZWlnaHRcIj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnLmhhc0RhdGVwaWNrIC5kYXRlcGljay1pbmxpbmUgLmRhdGVwaWNrLXRpdGxlLXJvdyB0aCwgJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCsgJy5oYXNEYXRlcGljayAuZGF0ZXBpY2staW5saW5lIC5kYXRlcGljay1kYXlzLWNlbGwgeydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJ2hlaWdodDogJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCArICcgIWltcG9ydGFudDsnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnfSdcclxuXHRcdFx0XHRcdFx0XHRcdFx0Kyc8L3N0eWxlPicgKTtcclxuXHRcdH1cclxuXHJcblx0XHQvLyBEZWZpbmUgc2hvd2luZyBtb3VzZSBvdmVyIHRvb2x0aXAgb24gdW5hdmFpbGFibGUgZGF0ZXNcclxuXHRcdGpDYWxDb250YWluZXIuZmluZCggJy5zZWFzb25fdW5hdmFpbGFibGUsLmJlZm9yZV9hZnRlcl91bmF2YWlsYWJsZSwud2Vla2RheXNfdW5hdmFpbGFibGUnICkub24oICdtb3VzZW92ZXInLCBmdW5jdGlvbiAoIHRoaXNfZXZlbnQgKXtcclxuXHRcdFx0Ly8gYWxzbyBhdmFpbGFibGUgdGhlc2UgdmFyczogXHRyZXNvdXJjZV9pZCwgakNhbENvbnRhaW5lciwgaW5zdFxyXG5cdFx0XHR2YXIgakNlbGwgPSBqUXVlcnkoIHRoaXNfZXZlbnQuY3VycmVudFRhcmdldCApO1xyXG5cdFx0XHR3cGJjX2NzdG1fX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQoIGpDZWxsLCBjYWxlbmRhcl9wYXJhbXNfYXJyWyAnYWp4X2RhdGFfYXJyJyBdWydwb3BvdmVyX2hpbnRzJ10gKTtcclxuXHRcdH0pO1xyXG5cdH0gKTtcclxuXHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gRGVmaW5lIG1vbnRoc19pbl9yb3dcclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGlmICggICAoIHVuZGVmaW5lZCA9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbW9udGhzX2luX3JvdyApXHJcblx0XHQgICAgICB8fCAoICcnID09IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19tb250aHNfaW5fcm93IClcclxuXHQpe1xyXG5cdFx0Y2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX21vbnRoc19pbl9yb3cgPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHM7XHJcblx0fVxyXG5cdFxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gRGVmaW5lIHdpZHRoIG9mIGVudGlyZSBjYWxlbmRhclxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHdpZHRoID0gICAnJztcdFx0XHRcdFx0Ly8gdmFyIHdpZHRoID0gJ3dpZHRoOjEwMCU7bWF4LXdpZHRoOjEwMCU7JztcclxuXHQvLyBXaWR0aFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8qIEZpeEluOiA5LjcuMy40ICovXHJcblx0aWYgKCAgICggdW5kZWZpbmVkICE9IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X193aWR0aCApXHJcblx0XHQgICAgICAmJiAoICcnICE9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fd2lkdGggKVxyXG5cdCl7XHJcblx0XHR3aWR0aCArPSAnbWF4LXdpZHRoOicgXHQrIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X193aWR0aCArICc7JztcclxuXHRcdHdpZHRoICs9ICd3aWR0aDoxMDAlOyc7XHJcblx0fVxyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBBZGQgY2FsZW5kYXIgY29udGFpbmVyOiBcIkNhbGVuZGFyIGlzIGxvYWRpbmcuLi5cIiAgYW5kIHRleHRhcmVhXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRqUXVlcnkoICcud3BiY19hanhfY3N0bV9fY2FsZW5kYXInICkuaHRtbChcclxuXHJcblx0XHQnPGRpdiBjbGFzcz1cIidcdCsgJyBia19jYWxlbmRhcl9mcmFtZSdcclxuXHRcdFx0XHRcdFx0KyAnIG1vbnRoc19udW1faW5fcm93XycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbW9udGhzX2luX3Jvd1xyXG5cdFx0XHRcdFx0XHQrICcgY2FsX21vbnRoX251bV8nIFx0KyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHNcclxuXHRcdFx0XHRcdFx0KyAnICcgXHRcdFx0XHRcdCsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGUgXHRcdFx0XHQvLyAnd3BiY190aW1lc2xvdF9kYXlfYmdfYXNfYXZhaWxhYmxlJyB8fCAnJ1xyXG5cdFx0XHRcdCsgJ1wiICdcclxuXHRcdFx0KyAnc3R5bGU9XCInICsgd2lkdGggKyAnXCI+J1xyXG5cclxuXHRcdFx0XHQrICc8ZGl2IGlkPVwiY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJ1wiPicgKyAnQ2FsZW5kYXIgaXMgbG9hZGluZy4uLicgKyAnPC9kaXY+J1xyXG5cclxuXHRcdCsgJzwvZGl2PidcclxuXHJcblx0XHQrICc8dGV4dGFyZWEgICAgICBpZD1cImRhdGVfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJ1wiJ1xyXG5cdFx0XHRcdFx0KyAnIG5hbWU9XCJkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICdcIidcclxuXHRcdFx0XHRcdCsgJyBhdXRvY29tcGxldGU9XCJvZmZcIidcclxuXHRcdFx0XHRcdCsgJyBzdHlsZT1cImRpc3BsYXk6bm9uZTt3aWR0aDoxMDAlO2hlaWdodDoxMGVtO21hcmdpbjoyZW0gMCAwO1wiPjwvdGV4dGFyZWE+J1xyXG5cdCk7XHJcblxyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIERlZmluZSB2YXJpYWJsZXMgZm9yIGNhbGVuZGFyXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR2YXIgY2FsX3BhcmFtX2FyciA9ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5jYWxlbmRhcl9zZXR0aW5ncztcclxuXHRjYWxfcGFyYW1fYXJyWyAnaHRtbF9pZCcgXSBcdFx0XHRcdFx0XHQ9ICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkO1xyXG5cdGNhbF9wYXJhbV9hcnJbICd0ZXh0X2lkJyBdIFx0XHRcdFx0XHRcdD0gJ2RhdGVfYm9va2luZycgXHQgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZDtcclxuXHRjYWxfcGFyYW1fYXJyWyAncmVzb3VyY2VfaWQnIF0gXHRcdFx0XHRcdD0gY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQ7XHJcblx0Y2FsX3BhcmFtX2FyclsgJ2FqeF9ub25jZV9jYWxlbmRhcicgXSBcdFx0XHQ9IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcjtcclxuXHRjYWxfcGFyYW1fYXJyWyAnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nIF0gXHRcdD0gY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW47XHJcblx0Y2FsX3BhcmFtX2FyclsgJ3Jlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzJyBdIFx0PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5yZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcztcclxuXHRjYWxfcGFyYW1fYXJyWyAncG9wb3Zlcl9oaW50cycgXSBcdFx0XHRcdD0gY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIucG9wb3Zlcl9oaW50cztcdFx0XHRcdFx0Ly8geydzZWFzb25fdW5hdmFpbGFibGUnOicuLi4nLCd3ZWVrZGF5c191bmF2YWlsYWJsZSc6Jy4uLicsJ2JlZm9yZV9hZnRlcl91bmF2YWlsYWJsZSc6Jy4uLicsfVxyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBTaG93IENhbGVuZGFyXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIoIGNhbF9wYXJhbV9hcnIgKTtcclxuXHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gU2Nyb2xsICB0byAgc3BlY2lmaWMgWWVhciBhbmQgTW9udGgsICBpZiBkZWZpbmVkIGluIGluaXQgcGFyYW1ldGVyc1xyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHNfeWVhciAgPSB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLnNlYXJjaF9nZXRfcGFyYW0oICdjYWxlbmRhcl9fc3RhcnRfeWVhcicgKTtcclxuXHR2YXIgc19tb250aCA9IHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4uc2VhcmNoX2dldF9wYXJhbSggJ2NhbGVuZGFyX19zdGFydF9tb250aCcgKTtcclxuXHRpZiAoICggMCAhPT0gc195ZWFyICkgJiYgKCAwICE9PSBzX21vbnRoICkgKXtcclxuXHRcdCB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fY2hhbmdlX3llYXJfbW9udGgoIGNhbF9wYXJhbV9hcnJbICdyZXNvdXJjZV9pZCcgXSwgc195ZWFyLCBzX21vbnRoIClcclxuXHR9XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBTaG93IGNhbGVuZGFyIGluICBkaWZmZXJlbnQgU2tpblxyXG4gKlxyXG4gKiBAcGFyYW0gc2VsZWN0ZWRfc2tpbl91cmxcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfX2NhbGVuZGFyX19jaGFuZ2Vfc2tpbiggc2VsZWN0ZWRfc2tpbl91cmwgKXtcclxuXHJcbi8vY29uc29sZS5sb2coICdTS0lOIFNFTEVDVElPTiA6OicsIHNlbGVjdGVkX3NraW5fdXJsICk7XHJcblxyXG5cdC8vIFJlbW92ZSBDU1Mgc2tpblxyXG5cdHZhciBzdHlsZXNoZWV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICd3cGJjLWNhbGVuZGFyLXNraW4tY3NzJyApO1xyXG5cdHN0eWxlc2hlZXQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCggc3R5bGVzaGVldCApO1xyXG5cclxuXHJcblx0Ly8gQWRkIG5ldyBDU1Mgc2tpblxyXG5cdHZhciBoZWFkSUQgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSggXCJoZWFkXCIgKVsgMCBdO1xyXG5cdHZhciBjc3NOb2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ2xpbmsnICk7XHJcblx0Y3NzTm9kZS50eXBlID0gJ3RleHQvY3NzJztcclxuXHRjc3NOb2RlLnNldEF0dHJpYnV0ZSggXCJpZFwiLCBcIndwYmMtY2FsZW5kYXItc2tpbi1jc3NcIiApO1xyXG5cdGNzc05vZGUucmVsID0gJ3N0eWxlc2hlZXQnO1xyXG5cdGNzc05vZGUubWVkaWEgPSAnc2NyZWVuJztcclxuXHRjc3NOb2RlLmhyZWYgPSBzZWxlY3RlZF9za2luX3VybDtcdC8vXCJodHRwOi8vYmV0YS93cC1jb250ZW50L3BsdWdpbnMvYm9va2luZy9jc3Mvc2tpbnMvZ3JlZW4tMDEuY3NzXCI7XHJcblx0aGVhZElELmFwcGVuZENoaWxkKCBjc3NOb2RlICk7XHJcbn1cclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqICAgVG9vbHRpcHMgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIHNob3dpbmcgdG9vbHRpcCwgIHdoZW4gIG1vdXNlIG92ZXIgb24gIFNFTEVDVEFCTEUgKGF2YWlsYWJsZSwgcGVuZGluZywgYXBwcm92ZWQsIHJlc291cmNlIHVuYXZhaWxhYmxlKSwgIGRheXNcclxuXHQgKiBDYW4gYmUgY2FsbGVkIGRpcmVjdGx5ICBmcm9tICBkYXRlcGljayBpbml0IGZ1bmN0aW9uLlxyXG5cdCAqXHJcblx0ICogQHBhcmFtIHZhbHVlXHJcblx0ICogQHBhcmFtIGRhdGVcclxuXHQgKiBAcGFyYW0gY2FsZW5kYXJfcGFyYW1zX2FyclxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHJcblx0ICogQHJldHVybnMge2Jvb2xlYW59XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19jc3RtX19wcmVwYXJlX3Rvb2x0aXBfX2luX2NhbGVuZGFyKCB2YWx1ZSwgZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgZGF0ZXBpY2tfdGhpcyApe1xyXG5cclxuXHRcdGlmICggbnVsbCA9PSBkYXRlICl7ICByZXR1cm4gZmFsc2U7ICB9XHJcblxyXG5cdFx0dmFyIHRkX2NsYXNzID0gKCBkYXRlLmdldE1vbnRoKCkgKyAxICkgKyAnLScgKyBkYXRlLmdldERhdGUoKSArICctJyArIGRhdGUuZ2V0RnVsbFllYXIoKTtcclxuXHJcblx0XHR2YXIgakNlbGwgPSBqUXVlcnkoICcjY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJyB0ZC5jYWw0ZGF0ZS0nICsgdGRfY2xhc3MgKTtcclxuXHJcblx0XHR3cGJjX2NzdG1fX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQoIGpDZWxsLCBjYWxlbmRhcl9wYXJhbXNfYXJyWyAncG9wb3Zlcl9oaW50cycgXSApO1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIHRvb2x0aXAgIGZvciBzaG93aW5nIG9uIFVOQVZBSUxBQkxFIGRheXMgKHNlYXNvbiwgd2Vla2RheSwgdG9kYXlfZGVwZW5kcyB1bmF2YWlsYWJsZSlcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBqQ2VsbFx0XHRcdFx0XHRqUXVlcnkgb2Ygc3BlY2lmaWMgZGF5IGNlbGxcclxuXHQgKiBAcGFyYW0gcG9wb3Zlcl9oaW50c1x0XHQgICAgQXJyYXkgd2l0aCB0b29sdGlwIGhpbnQgdGV4dHNcdCA6IHsnc2Vhc29uX3VuYXZhaWxhYmxlJzonLi4uJywnd2Vla2RheXNfdW5hdmFpbGFibGUnOicuLi4nLCdiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnOicuLi4nLH1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2NzdG1fX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQoIGpDZWxsLCBwb3BvdmVyX2hpbnRzICl7XHJcblxyXG5cdFx0dmFyIHRvb2x0aXBfdGltZSA9ICcnO1xyXG5cclxuXHRcdGlmICggakNlbGwuaGFzQ2xhc3MoICdzZWFzb25fdW5hdmFpbGFibGUnICkgKXtcclxuXHRcdFx0dG9vbHRpcF90aW1lID0gcG9wb3Zlcl9oaW50c1sgJ3NlYXNvbl91bmF2YWlsYWJsZScgXTtcclxuXHRcdH0gZWxzZSBpZiAoIGpDZWxsLmhhc0NsYXNzKCAnd2Vla2RheXNfdW5hdmFpbGFibGUnICkgKXtcclxuXHRcdFx0dG9vbHRpcF90aW1lID0gcG9wb3Zlcl9oaW50c1sgJ3dlZWtkYXlzX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICdiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnICkgKXtcclxuXHRcdFx0dG9vbHRpcF90aW1lID0gcG9wb3Zlcl9oaW50c1sgJ2JlZm9yZV9hZnRlcl91bmF2YWlsYWJsZScgXTtcclxuXHRcdH0gZWxzZSBpZiAoIGpDZWxsLmhhc0NsYXNzKCAnZGF0ZTJhcHByb3ZlJyApICl7XHJcblxyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICdkYXRlX2FwcHJvdmVkJyApICl7XHJcblxyXG5cdFx0fSBlbHNlIHtcclxuXHJcblx0XHR9XHJcblxyXG5cdFx0akNlbGwuYXR0ciggJ2RhdGEtY29udGVudCcsIHRvb2x0aXBfdGltZSApO1xyXG5cclxuXHRcdHZhciB0ZF9lbCA9IGpDZWxsLmdldCgwKTtcdC8valF1ZXJ5KCAnI2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICcgdGQuY2FsNGRhdGUtJyArIHRkX2NsYXNzICkuZ2V0KDApO1xyXG5cclxuXHRcdGlmICggKCB1bmRlZmluZWQgPT0gdGRfZWwuX3RpcHB5ICkgJiYgKCAnJyAhPSB0b29sdGlwX3RpbWUgKSApe1xyXG5cclxuXHRcdFx0XHR3cGJjX3RpcHB5KCB0ZF9lbCAsIHtcclxuXHRcdFx0XHRcdGNvbnRlbnQoIHJlZmVyZW5jZSApe1xyXG5cclxuXHRcdFx0XHRcdFx0dmFyIHBvcG92ZXJfY29udGVudCA9IHJlZmVyZW5jZS5nZXRBdHRyaWJ1dGUoICdkYXRhLWNvbnRlbnQnICk7XHJcblxyXG5cdFx0XHRcdFx0XHRyZXR1cm4gJzxkaXYgY2xhc3M9XCJwb3BvdmVyIHBvcG92ZXJfdGlwcHlcIj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdCsgJzxkaXYgY2xhc3M9XCJwb3BvdmVyLWNvbnRlbnRcIj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyBwb3BvdmVyX2NvbnRlbnRcclxuXHRcdFx0XHRcdFx0XHRcdFx0KyAnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdCArICc8L2Rpdj4nO1xyXG5cdFx0XHRcdFx0fSxcclxuXHRcdFx0XHRcdGFsbG93SFRNTCAgICAgICAgOiB0cnVlLFxyXG5cdFx0XHRcdFx0dHJpZ2dlclx0XHRcdCA6ICdtb3VzZWVudGVyIGZvY3VzJyxcclxuXHRcdFx0XHRcdGludGVyYWN0aXZlICAgICAgOiAhIHRydWUsXHJcblx0XHRcdFx0XHRoaWRlT25DbGljayAgICAgIDogdHJ1ZSxcclxuXHRcdFx0XHRcdGludGVyYWN0aXZlQm9yZGVyOiAxMCxcclxuXHRcdFx0XHRcdG1heFdpZHRoICAgICAgICAgOiA1NTAsXHJcblx0XHRcdFx0XHR0aGVtZSAgICAgICAgICAgIDogJ3dwYmMtdGlwcHktdGltZXMnLFxyXG5cdFx0XHRcdFx0cGxhY2VtZW50ICAgICAgICA6ICd0b3AnLFxyXG5cdFx0XHRcdFx0ZGVsYXlcdFx0XHQgOiBbNDAwLCAwXSxcdFx0XHQvL0ZpeEluOiA5LjQuMi4yXHJcblx0XHRcdFx0XHRpZ25vcmVBdHRyaWJ1dGVzIDogdHJ1ZSxcclxuXHRcdFx0XHRcdHRvdWNoXHRcdFx0IDogdHJ1ZSxcdFx0XHRcdC8vWydob2xkJywgNTAwXSwgLy8gNTAwbXMgZGVsYXlcdFx0XHQvL0ZpeEluOiA5LjIuMS41XHJcblx0XHRcdFx0XHRhcHBlbmRUbzogKCkgPT4gZG9jdW1lbnQuYm9keSxcclxuXHRcdFx0XHR9KTtcclxuXHRcdH1cclxuXHR9XHJcblxyXG5cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgQWpheCAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IHNob3cgcmVxdWVzdFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fYWpheF9yZXF1ZXN0KCl7XHJcblxyXG5jb25zb2xlLmdyb3VwQ29sbGFwc2VkKCAnV1BCQ19BSlhfQ1VTVE9NSVpFX1BMVUdJTicgKTsgY29uc29sZS5sb2coICcgPT0gQmVmb3JlIEFqYXggU2VuZCAtIHNlYXJjaF9nZXRfYWxsX3BhcmFtcygpID09ICcgLCB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLnNlYXJjaF9nZXRfYWxsX3BhcmFtcygpICk7XHJcblxyXG5cdHdwYmNfY3VzdG9taXplX3BsdWdpbl9yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0KCk7XHJcblxyXG5cdC8vIFN0YXJ0IEFqYXhcclxuXHRqUXVlcnkucG9zdCggd3BiY191cmxfYWpheCxcclxuXHRcdFx0XHR7XHJcblx0XHRcdFx0XHRhY3Rpb24gICAgICAgICAgOiAnV1BCQ19BSlhfQ1VTVE9NSVpFX1BMVUdJTicsXHJcblx0XHRcdFx0XHR3cGJjX2FqeF91c2VyX2lkOiB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLmdldF9zZWN1cmVfcGFyYW0oICd1c2VyX2lkJyApLFxyXG5cdFx0XHRcdFx0bm9uY2UgICAgICAgICAgIDogd3BiY19hanhfY3VzdG9taXplX3BsdWdpbi5nZXRfc2VjdXJlX3BhcmFtKCAnbm9uY2UnICksXHJcblx0XHRcdFx0XHR3cGJjX2FqeF9sb2NhbGUgOiB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLmdldF9zZWN1cmVfcGFyYW0oICdsb2NhbGUnICksXHJcblxyXG5cdFx0XHRcdFx0c2VhcmNoX3BhcmFtc1x0OiB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLnNlYXJjaF9nZXRfYWxsX3BhcmFtcygpXHJcblx0XHRcdFx0fSxcclxuXHRcdFx0XHQvKipcclxuXHRcdFx0XHQgKiBTIHUgYyBjIGUgcyBzXHJcblx0XHRcdFx0ICpcclxuXHRcdFx0XHQgKiBAcGFyYW0gcmVzcG9uc2VfZGF0YVx0XHQtXHRpdHMgb2JqZWN0IHJldHVybmVkIGZyb20gIEFqYXggLSBjbGFzcy1saXZlLXNlYXJjZy5waHBcclxuXHRcdFx0XHQgKiBAcGFyYW0gdGV4dFN0YXR1c1x0XHQtXHQnc3VjY2VzcydcclxuXHRcdFx0XHQgKiBAcGFyYW0ganFYSFJcdFx0XHRcdC1cdE9iamVjdFxyXG5cdFx0XHRcdCAqL1xyXG5cdFx0XHRcdGZ1bmN0aW9uICggcmVzcG9uc2VfZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKSB7XHJcblxyXG5jb25zb2xlLmxvZyggJyA9PSBSZXNwb25zZSBXUEJDX0FKWF9DVVNUT01JWkVfUExVR0lOID09ICcsIHJlc3BvbnNlX2RhdGEgKTsgY29uc29sZS5ncm91cEVuZCgpO1xyXG5cclxuXHRcdFx0XHRcdC8vIFByb2JhYmx5IEVycm9yXHJcblx0XHRcdFx0XHRpZiAoICh0eXBlb2YgcmVzcG9uc2VfZGF0YSAhPT0gJ29iamVjdCcpIHx8IChyZXNwb25zZV9kYXRhID09PSBudWxsKSApe1xyXG5cclxuXHRcdFx0XHRcdFx0d3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fYWN0dWFsX2NvbnRlbnRfX2hpZGUoKTtcclxuXHRcdFx0XHRcdFx0d3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fc2hvd19tZXNzYWdlKCByZXNwb25zZV9kYXRhICk7XHJcblxyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVsb2FkIHBhZ2UsIGFmdGVyIGZpbHRlciB0b29sYmFyIGhhcyBiZWVuIHJlc2V0XHJcblx0XHRcdFx0XHRpZiAoICAgICAgICggICAgIHVuZGVmaW5lZCAhPSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdKVxyXG5cdFx0XHRcdFx0XHRcdCYmICggJ3Jlc2V0X2RvbmUnID09PSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdWyAnZG9fYWN0aW9uJyBdKVxyXG5cdFx0XHRcdFx0KXtcclxuXHRcdFx0XHRcdFx0bG9jYXRpb24ucmVsb2FkKCk7XHJcblx0XHRcdFx0XHRcdHJldHVybjtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHQvLyBTaG93IGxpc3RpbmdcclxuXHRcdFx0XHRcdHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX3BhZ2VfY29udGVudF9fc2hvdyggcmVzcG9uc2VfZGF0YVsgJ2FqeF9kYXRhJyBdLCByZXNwb25zZV9kYXRhWyAnYWp4X3NlYXJjaF9wYXJhbXMnIF0gLCByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdICk7XHJcblxyXG5cdFx0XHRcdFx0Ly93cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19kZWZpbmVfdWlfaG9va3MoKTtcdFx0XHRcdFx0XHQvLyBSZWRlZmluZSBIb29rcywgYmVjYXVzZSB3ZSBzaG93IG5ldyBET00gZWxlbWVudHNcclxuXHRcdFx0XHRcdGlmICggJycgIT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9kYXRhJyBdWyAnYWp4X2FmdGVyX2FjdGlvbl9tZXNzYWdlJyBdLnJlcGxhY2UoIC9cXG4vZywgXCI8YnIgLz5cIiApICl7XHJcblx0XHRcdFx0XHRcdHdwYmNfYWRtaW5fc2hvd19tZXNzYWdlKFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgcmVzcG9uc2VfZGF0YVsgJ2FqeF9kYXRhJyBdWyAnYWp4X2FmdGVyX2FjdGlvbl9tZXNzYWdlJyBdLnJlcGxhY2UoIC9cXG4vZywgXCI8YnIgLz5cIiApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAoICcxJyA9PSByZXNwb25zZV9kYXRhWyAnYWp4X2RhdGEnIF1bICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdCcgXSApID8gJ3N1Y2Nlc3MnIDogJ2Vycm9yJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgMTAwMDBcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KTtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHR3cGJjX2N1c3RvbWl6ZV9wbHVnaW5fcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpO1xyXG5cdFx0XHRcdFx0Ly8gUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuXHRcdFx0XHRcdHdwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiggcmVzcG9uc2VfZGF0YVsgJ2FqeF9jbGVhbmVkX3BhcmFtcycgXVsgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApXHJcblxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnI2FqYXhfcmVzcG9uZCcgKS5odG1sKCByZXNwb25zZV9kYXRhICk7XHRcdC8vIEZvciBhYmlsaXR5IHRvIHNob3cgcmVzcG9uc2UsIGFkZCBzdWNoIERJViBlbGVtZW50IHRvIHBhZ2VcclxuXHRcdFx0XHR9XHJcblx0XHRcdCAgKS5mYWlsKCBmdW5jdGlvbiAoIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApIHsgICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdBamF4X0Vycm9yJywganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICk7IH1cclxuXHJcblx0XHRcdFx0XHR2YXIgZXJyb3JfbWVzc2FnZSA9ICc8c3Ryb25nPicgKyAnRXJyb3IhJyArICc8L3N0cm9uZz4gJyArIGVycm9yVGhyb3duIDtcclxuXHRcdFx0XHRcdGlmICgganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAoPGI+JyArIGpxWEhSLnN0YXR1cyArICc8L2I+KSc7XHJcblx0XHRcdFx0XHRcdGlmICg0MDMgPT0ganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSArPSAnIFByb2JhYmx5IG5vbmNlIGZvciB0aGlzIHBhZ2UgaGFzIGJlZW4gZXhwaXJlZC4gUGxlYXNlIDxhIGhyZWY9XCJqYXZhc2NyaXB0OnZvaWQoMClcIiBvbmNsaWNrPVwiamF2YXNjcmlwdDpsb2NhdGlvbi5yZWxvYWQoKTtcIj5yZWxvYWQgdGhlIHBhZ2U8L2E+Lic7XHJcblx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdGlmICgganFYSFIucmVzcG9uc2VUZXh0ICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAnICsganFYSFIucmVzcG9uc2VUZXh0O1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSA9IGVycm9yX21lc3NhZ2UucmVwbGFjZSggL1xcbi9nLCBcIjxiciAvPlwiICk7XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fYWN0dWFsX2NvbnRlbnRfX2hpZGUoKTtcclxuXHRcdFx0XHRcdHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX3Nob3dfbWVzc2FnZSggZXJyb3JfbWVzc2FnZSApO1xyXG5cdFx0XHQgIH0pXHJcblx0ICAgICAgICAgIC8vIC5kb25lKCAgIGZ1bmN0aW9uICggZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKSB7ICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdzZWNvbmQgc3VjY2VzcycsIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICk7IH0gICAgfSlcclxuXHRcdFx0ICAvLyAuYWx3YXlzKCBmdW5jdGlvbiAoIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnYWx3YXlzIGZpbmlzaGVkJywgZGF0YV9qcVhIUiwgdGV4dFN0YXR1cywganFYSFJfZXJyb3JUaHJvd24gKTsgfSAgICAgfSlcclxuXHRcdFx0ICA7ICAvLyBFbmQgQWpheFxyXG5cclxufVxyXG5cclxuXHJcblxyXG4vKipcclxuICogICBIIG8gbyBrIHMgIC0gIGl0cyBBY3Rpb24vVGltZXMgd2hlbiBuZWVkIHRvIHJlLVJlbmRlciBWaWV3cyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTZW5kIEFqYXggU2VhcmNoIFJlcXVlc3QgYWZ0ZXIgVXBkYXRpbmcgc2VhcmNoIHJlcXVlc3QgcGFyYW1ldGVyc1xyXG4gKlxyXG4gKiBAcGFyYW0gcGFyYW1zX2FyclxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zICggcGFyYW1zX2FyciApe1xyXG5cclxuXHQvLyBEZWZpbmUgZGlmZmVyZW50IFNlYXJjaCAgcGFyYW1ldGVycyBmb3IgcmVxdWVzdFxyXG5cdF8uZWFjaCggcGFyYW1zX2FyciwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApIHtcclxuXHRcdC8vY29uc29sZS5sb2coICdSZXF1ZXN0IGZvcjogJywgcF9rZXksIHBfdmFsICk7XHJcblx0XHR3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLnNlYXJjaF9zZXRfcGFyYW0oIHBfa2V5LCBwX3ZhbCApO1xyXG5cdH0pO1xyXG5cclxuXHQvLyBTZW5kIEFqYXggUmVxdWVzdFxyXG5cdHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX2FqYXhfcmVxdWVzdCgpO1xyXG59XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBTZWFyY2ggcmVxdWVzdCBmb3IgXCJQYWdlIE51bWJlclwiXHJcblx0ICogQHBhcmFtIHBhZ2VfbnVtYmVyXHRpbnRcclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19wYWdpbmF0aW9uX2NsaWNrKCBwYWdlX251bWJlciApe1xyXG5cclxuXHRcdHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fX3NlbmRfcmVxdWVzdF93aXRoX3BhcmFtcygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BhZ2VfbnVtJzogcGFnZV9udW1iZXJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9ICk7XHJcblx0fVxyXG5cclxuXHJcblxyXG4vKipcclxuICogICBTaG93IC8gSGlkZSBDb250ZW50ICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiAgU2hvdyBMaXN0aW5nIENvbnRlbnQgXHQtIFx0U2VuZGluZyBBamF4IFJlcXVlc3RcdC1cdHdpdGggcGFyYW1ldGVycyB0aGF0ICB3ZSBlYXJseSAgZGVmaW5lZFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fYWN0dWFsX2NvbnRlbnRfX3Nob3coKXtcclxuXHJcblx0d3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fYWpheF9yZXF1ZXN0KCk7XHRcdFx0Ly8gU2VuZCBBamF4IFJlcXVlc3RcdC1cdHdpdGggcGFyYW1ldGVycyB0aGF0ICB3ZSBlYXJseSAgZGVmaW5lZCBpbiBcIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZ1wiIE9iai5cclxufVxyXG5cclxuLyoqXHJcbiAqIEhpZGUgTGlzdGluZyBDb250ZW50XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19hY3R1YWxfY29udGVudF9faGlkZSgpe1xyXG5cclxuXHRqUXVlcnkoICB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICApLmh0bWwoICcnICk7XHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgTSBlIHMgcyBhIGcgZSAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICpcclxuICovXHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiBTaG93IG1lc3NhZ2UgaW4gY29udGVudFxyXG4gKlxyXG4gKiBAcGFyYW0gbWVzc2FnZVx0XHRcdFx0TWVzc2FnZSBIVE1MXHJcbiAqIEBwYXJhbSBwYXJhbXMgPSB7XHJcbiAqICAgICAgICAgICAgICAgICAgIFsndHlwZSddXHRcdFx0XHQnd2FybmluZycgfCAnaW5mbycgfCAnZXJyb3InIHwgJ3N1Y2Nlc3MnXHRcdGRlZmF1bHQ6ICd3YXJuaW5nJ1xyXG4gKiAgICAgICAgICAgICAgICAgICBbJ2NvbnRhaW5lciddXHRcdFx0Jy53cGJjX2FqeF9jc3RtX19zZWN0aW9uX2xlZnQnXHRcdGRlZmF1bHQ6IHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4uZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInIClcclxuICogICAgICAgICAgICAgICAgICAgWydpc19hcHBlbmQnXVx0XHRcdHRydWUgfCBmYWxzZVx0XHRcdFx0XHRcdGRlZmF1bHQ6IHRydWVcclxuICpcdFx0XHRcdCAgIH1cclxuICogRXhhbXBsZTpcclxuICogXHRcdFx0dmFyIGh0bWxfaWQgPSB3cGJjX2FqeF9jdXN0b21pemVfcGx1Z2luX19zaG93X21lc3NhZ2UoICdZb3UgY2FuIHRlc3QgZGF5cyBzZWxlY3Rpb24gaW4gY2FsZW5kYXInLCAnaW5mbycsICcud3BiY19hanhfY3N0bV9fc2VjdGlvbl9sZWZ0JywgdHJ1ZSApO1xyXG4gKlxyXG4gKlxyXG4gKiBAcmV0dXJucyBzdHJpbmcgIC0gSFRNTCBJRFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9fc2hvd19tZXNzYWdlKCBtZXNzYWdlLCBwYXJhbXMgPSB7fSApe1xyXG5cclxuXHR2YXIgcGFyYW1zX2RlZmF1bHQgPSB7XHJcblx0XHRcdFx0XHRcdFx0XHQndHlwZScgICAgIDogJ3dhcm5pbmcnLFxyXG5cdFx0XHRcdFx0XHRcdFx0J2NvbnRhaW5lcic6IHdwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW4uZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICksXHJcblx0XHRcdFx0XHRcdFx0XHQnaXNfYXBwZW5kJzogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdCdzdHlsZScgICAgOiAndGV4dC1hbGlnbjpsZWZ0OycsXHJcblx0XHRcdFx0XHRcdFx0XHQnZGVsYXknICAgIDogMFxyXG5cdFx0XHRcdFx0XHRcdH07XHJcblx0Xy5lYWNoKCBwYXJhbXMsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKXtcclxuXHRcdHBhcmFtc19kZWZhdWx0WyBwX2tleSBdID0gcF92YWw7XHJcblx0fSApO1xyXG5cdHBhcmFtcyA9IHBhcmFtc19kZWZhdWx0O1xyXG5cclxuICAgIHZhciB1bmlxdWVfZGl2X2lkID0gbmV3IERhdGUoKTtcclxuICAgIHVuaXF1ZV9kaXZfaWQgPSAnd3BiY19ub3RpY2VfJyArIHVuaXF1ZV9kaXZfaWQuZ2V0VGltZSgpO1xyXG5cclxuXHR2YXIgYWxlcnRfY2xhc3MgPSAnbm90aWNlICc7XHJcblx0aWYgKCBwYXJhbXNbJ3R5cGUnXSA9PSAnZXJyb3InICl7XHJcblx0XHRhbGVydF9jbGFzcyArPSAnbm90aWNlLWVycm9yICc7XHJcblx0XHRtZXNzYWdlID0gJzxpIHN0eWxlPVwibWFyZ2luLXJpZ2h0OiAwLjVlbTtjb2xvcjogI2Q2MzYzODtcIiBjbGFzcz1cIm1lbnVfaWNvbiBpY29uLTF4IHdwYmNfaWNuX3JlcG9ydF9nbWFpbGVycm9ycmVkXCI+PC9pPicgKyBtZXNzYWdlO1xyXG5cdH1cclxuXHRpZiAoIHBhcmFtc1sndHlwZSddID09ICd3YXJuaW5nJyApe1xyXG5cdFx0YWxlcnRfY2xhc3MgKz0gJ25vdGljZS13YXJuaW5nICc7XHJcblx0XHRtZXNzYWdlID0gJzxpIHN0eWxlPVwibWFyZ2luLXJpZ2h0OiAwLjVlbTtjb2xvcjogI2U5YWEwNDtcIiBjbGFzcz1cIm1lbnVfaWNvbiBpY29uLTF4IHdwYmNfaWNuX3dhcm5pbmdcIj48L2k+JyArIG1lc3NhZ2U7XHJcblx0fVxyXG5cdGlmICggcGFyYW1zWyd0eXBlJ10gPT0gJ2luZm8nICl7XHJcblx0XHRhbGVydF9jbGFzcyArPSAnbm90aWNlLWluZm8gJztcclxuXHR9XHJcblx0aWYgKCBwYXJhbXNbJ3R5cGUnXSA9PSAnc3VjY2VzcycgKXtcclxuXHRcdGFsZXJ0X2NsYXNzICs9ICdub3RpY2UtaW5mbyBhbGVydC1zdWNjZXNzIHVwZGF0ZWQgJztcclxuXHRcdG1lc3NhZ2UgPSAnPGkgc3R5bGU9XCJtYXJnaW4tcmlnaHQ6IDAuNWVtO2NvbG9yOiAjNjRhYTQ1O1wiIGNsYXNzPVwibWVudV9pY29uIGljb24tMXggd3BiY19pY25fZG9uZV9vdXRsaW5lXCI+PC9pPicgKyBtZXNzYWdlO1xyXG5cdH1cclxuXHJcblx0bWVzc2FnZSA9ICc8ZGl2IGlkPVwiJyArIHVuaXF1ZV9kaXZfaWQgKyAnXCIgY2xhc3M9XCJ3cGJjLXNldHRpbmdzLW5vdGljZSAnICsgYWxlcnRfY2xhc3MgKyAnXCIgc3R5bGU9XCInICsgcGFyYW1zWyAnc3R5bGUnIF0gKyAnXCI+JyArIG1lc3NhZ2UgKyAnPC9kaXY+JztcclxuXHJcblx0aWYgKCBwYXJhbXNbJ2lzX2FwcGVuZCddICl7XHJcblx0XHRqUXVlcnkoIHBhcmFtc1snY29udGFpbmVyJ10gKS5hcHBlbmQoIG1lc3NhZ2UgKTtcclxuXHR9IGVsc2Uge1xyXG5cdFx0alF1ZXJ5KCBwYXJhbXNbJ2NvbnRhaW5lciddICkuaHRtbCggbWVzc2FnZSApO1xyXG5cdH1cclxuXHJcblx0cGFyYW1zWydkZWxheSddID0gcGFyc2VJbnQoIHBhcmFtc1snZGVsYXknXSApO1xyXG5cdGlmICggcGFyYW1zWydkZWxheSddID4gMCApe1xyXG5cclxuXHRcdHZhciBjbG9zZWRfdGltZXIgPSBzZXRUaW1lb3V0KCBmdW5jdGlvbiAoKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGpRdWVyeSggJyMnICsgdW5pcXVlX2Rpdl9pZCApLmZhZGVPdXQoIDE1MDAgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgcGFyYW1zWyAnZGVsYXknIF1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCApO1xyXG5cdH1cclxuXHJcblx0cmV0dXJuIHVuaXF1ZV9kaXZfaWQ7XHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgU3VwcG9ydCBGdW5jdGlvbnMgLSBTcGluIEljb24gaW4gQnV0dG9ucyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIFN0YXJ0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2N1c3RvbWl6ZV9wbHVnaW5fcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpe1xyXG5cdGpRdWVyeSggJyN3cGJjX2N1c3RvbWl6ZV9wbHVnaW5fcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicpLnJlbW92ZUNsYXNzKCAnd3BiY19hbmltYXRpb25fcGF1c2UnICk7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBTcGluIGJ1dHRvbiBpbiBGaWx0ZXIgdG9vbGJhciAgLSAgUGF1c2VcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfY3VzdG9taXplX3BsdWdpbl9yZWxvYWRfYnV0dG9uX19zcGluX3BhdXNlKCl7XHJcblx0alF1ZXJ5KCAnI3dwYmNfY3VzdG9taXplX3BsdWdpbl9yZWxvYWRfYnV0dG9uIC5tZW51X2ljb24ud3BiY19zcGluJyApLmFkZENsYXNzKCAnd3BiY19hbmltYXRpb25fcGF1c2UnICk7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBTcGluIGJ1dHRvbiBpbiBGaWx0ZXIgdG9vbGJhciAgLSAgaXMgU3Bpbm5pbmcgP1xyXG4gKlxyXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfY3VzdG9taXplX3BsdWdpbl9yZWxvYWRfYnV0dG9uX19pc19zcGluKCl7XHJcbiAgICBpZiAoIGpRdWVyeSggJyN3cGJjX2N1c3RvbWl6ZV9wbHVnaW5fcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicgKS5oYXNDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApICl7XHJcblx0XHRyZXR1cm4gdHJ1ZTtcclxuXHR9IGVsc2Uge1xyXG5cdFx0cmV0dXJuIGZhbHNlO1xyXG5cdH1cclxufVxyXG4iXSwiZmlsZSI6ImluY2x1ZGVzL3BhZ2UtY3VzdG9taXplL19vdXQvY3VzdG9taXplX3BsdWdpbl9wYWdlLmpzIn0=
