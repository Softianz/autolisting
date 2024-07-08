(function($) {
    "use strict";
    
    var map, mapSidebar, markers, CustomHtmlIcon, group;
    var markerArray = [];

    $.extend($.apusThemeCore, {
        /**
         *  Initialize scripts
         */
        listing_init: function() {
            var self = this;

            if ($('#apus-listing-map').length) {
                L.Icon.Default.imagePath = 'wp-content/themes/listdo/images/';
            }
            

            if ($('.detail-haft-map').length > 0) {
                $('body').addClass( 'no-breadscrumb no-footer fix-header' );
            }
            if ($('.detail-full-gallery').length > 0 || $('.detail-full-map').length > 0) {
                $('body').addClass( 'no-breadscrumb' );
            }
            
            $('.write-a-review').on('click', function(e){
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $("#review_form_wrapper").offset().top
                }, 1000);
            });
            
            self.select2Init();

            self.listingBtnFilter();

            self.halfMapPaddingTop();

            self.listingFilter();

            self.bookmarkInit();

            self.searchAjaxInit();

            self.searchInit();

            self.getListings();
            
            setTimeout(function(){
                self.mapInit();
                self.mapSidebarInit();
            }, 50);
            
            self.previewInit();

            // listing detail
            self.listingDetail();
            self.listingComment();
            self.listingReview();

            self.submitForm();
            
            self.editProfile();

            $('.login-form-popup-message').on('click', function(){
                var target = $('.apus-user-login').attr('href');
                
                self.loginRegisterPopup(target);
                return false;
            });
        },
        searchAjaxInit: function() {
            if ( $.isFunction( $.fn.typeahead ) ) {
                $('.apus-autocompleate-input').each(function(){
                    var $this = $(this);
                    $this.typeahead({
                            'hint': true,
                            'highlight': true,
                            'minLength': 2,
                            'limit': 4
                        }, {
                            name: 'search',
                            source: function (query, processSync, processAsync) {
                                processSync([listdo_listing_opts.empty_msg]);
                                $this.closest('.twitter-typeahead').addClass('loading');
                                return $.ajax({
                                    url: listdo_listing_opts.ajaxurl, 
                                    type: 'GET',
                                    data: {
                                        'search': query,
                                        'action': 'listdo_autocomplete_search_listing'
                                    },
                                    dataType: 'json',
                                    success: function (json) {
                                        $this.closest('.twitter-typeahead').removeClass('loading');
                                        $this.closest('.has-suggestion').removeClass('active');
                                        return processAsync(json);
                                    }
                                });
                            },
                            templates: {
                                empty : [
                                    '<div class="empty-message">',
                                    listdo_listing_opts.empty_msg,
                                    '</div>'
                                ].join('\n'),
                                suggestion: Handlebars.compile( listdo_listing_opts.template )
                            },
                        }
                    );
                    $this.on('typeahead:selected', function (e, data) {
                        e.preventDefault();
                        setTimeout(function(){
                            $('.apus-autocompleate-input').val(data.title);    
                        }, 5);
                        
                        return false;
                    });
                });
            }
        },
        listingChangeMarginTopAffix: function() {
            var affix_height = 0;
            //if ($(window).width() > 991) {
                if ( $('.panel-affix').length > 0 ) {
                    affix_height = $('.panel-affix').outerHeight();
                    $('.panel-affix-wrapper').css({'height': affix_height});
                }
            //}
            return affix_height;
        },
        listingDetail: function() {
            var self = this;
            // sticky tabs
            var affix_height = 0;
            var affix_height_top = 0;
            setTimeout(function(){
                affix_height = affix_height_top = self.listingChangeMarginTopAffix();
            }, 50);
            $(window).resize(function(){
                affix_height = affix_height_top = self.listingChangeMarginTopAffix();
            });
            if ($(window).width() >= 1200) {
                //Function from Bluthemes, lets you add li elemants to affix object without having to alter and data attributes set out by bootstrap
                setTimeout(function(){
                    // name your elements here
                    var stickyElement   = '.panel-affix',   // the element you want to make sticky
                        bottomElement   = '#apus-footer'; // the bottom element where you want the sticky element to stop (usually the footer) 

                    // make sure the element exists on the page before trying to initalize
                    if($( stickyElement ).length){
                        $( stickyElement ).each(function(){
                            var header_height = 0;
                            if ($(window).width() >= 1200) {
                                if ($('.main-sticky-header').length > 0) {
                                    header_height = $('.main-sticky-header').outerHeight();
                                    affix_height_top = affix_height + header_height;
                                }
                            } else {
                                header_height = $('#apus-header-mobile').outerHeight();
                                affix_height_top = affix_height + header_height;
                                header_height = 0;
                            }
                            affix_height_top = affix_height_top + 10;
                            // let's save some messy code in clean variables
                            // when should we start affixing? (the amount of pixels to the top from the element)
                            var fromTop = $( this ).offset().top, 
                                // where is the bottom of the element?
                                fromBottom = $( document ).height()-($( this ).offset().top + $( this ).outerHeight()),
                                // where should we stop? (the amount of pixels from the top where the bottom element is)
                                // also add the outer height mismatch to the height of the element to account for padding and borders
                                stopOn = $( document ).height()-( $( bottomElement ).offset().top)+($( this ).outerHeight() - $( this ).height()); 
                
                            // if the element doesn't need to get sticky, then skip it so it won't mess up your layout
                            if( (fromBottom-stopOn) > 200 ){
                                // let's put a sticky width on the element and assign it to the top
                                $( this ).css('width', $( this ).width()).css('top', 0).css('position', '');
                                // assign the affix to the element
                                $( this ).affix({
                                    offset: { 
                                        // make it stick where the top pixel of the element is
                                        top: fromTop - header_height,  
                                        // make it stop where the top pixel of the bottom element is
                                        bottom: stopOn
                                    }
                                // when the affix get's called then make sure the position is the default (fixed) and it's at the top
                                }).on('affix.bs.affix', function(){
                                    var header_height = 0;
                                    if ($(window).width() >= 1200) {
                                        if ($('.main-sticky-header').length > 0) {
                                            header_height = $('.main-sticky-header').outerHeight();
                                            affix_height_top = affix_height + header_height;
                                        }
                                    } else {
                                        header_height = $('#apus-header-mobile').outerHeight();
                                        affix_height_top = affix_height + header_height;
                                        header_height = 0;
                                    }
                                    affix_height_top = affix_height_top + 10;
                                    $( this ).css('top', header_height).css('position', header_height);
                                });
                            }
                            // trigger the scroll event so it always activates 
                            $( window ).trigger('scroll'); 
                        }); 
                    }

                    //Offset scrollspy height to highlight li elements at good window height
                    $('body').scrollspy({
                        target: ".header-tabs-nav",
                        offset: affix_height_top + 20
                    });
                }, 50);
            }
            

            //Smooth Scrolling For Internal Page Links
              $('.panel-affix a[href*="#"]:not([href="#"])').on('click', function() {
                if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                  var target = $(this.hash);
                  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                  if (target.length) {
                    $('html,body').animate({
                      scrollTop: target.offset().top - affix_height_top
                    }, 1000);
                    return false;
                  }
                }
              });
            


            $('.listing-review-btn .listing-reviews').on('click', function(e){
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $( $.attr(this, 'href') ).offset().top
                }, 500);
            });

            $('.event-schedule-wrapper .panel-heading').on('click', function (e) {
                $(this).toggleClass('active');           
            });
        },
        imagesPreview: function(input, placeToInsertImagePreview) {
            if (input.files) {
                var filesAmount = input.files.length;
                
                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        },
        listingComment: function() {
            var self = this;
            // file attachments
            $('#field_attachments_cover').on('click', function(){
                $("#field_attachments").trigger('click');
            });
            $('#field_attachments').on('change', function() {
                $('.group-upload-preview').html('');
                self.imagesPreview(this, 'div.group-upload-preview');
                $('.group-upload-preview').css("display","block");
            });

            var isAdvancedUpload = function() {
                var div = document.createElement('div');
                return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
            }();

            if (isAdvancedUpload) {
                var droppedFiles = false;
                
                $('#field_attachments_cover').on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }).on('dragover dragenter', function() {
                    $('#field_attachments_cover').addClass('is-dragover');
                }).on('dragleave dragend drop', function() {
                    $('#field_attachments_cover').removeClass('is-dragover');
                }).on('drop', function(e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    $('#field_attachments').prop('files', droppedFiles).trigger('change');
                });
            }

            $('.comment-attactments').each(function(){
                var $this = $(this);
                $('.show-more-images', $this).on('click', function(){
                    $('.attachment', $this).removeClass('hidden');
                    $(this).addClass('hidden');
                    initProductImageLoad();
                });
            });

            $('.photos-wrapper').each(function(){
                var $this = $(this);
                $('.show-more-images', $this).on('click', function(){
                    $('.attachment', $this).removeClass('hidden');
                    $(this).addClass('hidden');
                    initProductImageLoad();
                });
            });

            // like
            $('.comment-actions .comment-like').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                $this.addClass('loading');

                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  "action=listdo_comment_like&comment_id="+$this.data('id') + "&security=" + listdo_listing_opts.ajax_nonce
                }).done(function(data) {
                    $this.removeClass('loading');
                    $this.html(data.icon + data.dtitle);
                    $this.attr( 'title', data.dtitle );
                    $this.attr( 'data-original-title', data.dtitle );
                    $this.toggleClass('active');
                });
            });
            // dislike
            $('.comment-actions .comment-dislike').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                $this.addClass('loading');

                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  "action=listdo_comment_dislike&comment_id="+$this.data('id') + "&security=" + listdo_listing_opts.ajax_nonce
                }).done(function(data) {
                    $this.removeClass('loading');
                    $this.html(data.icon + data.dtitle);
                    $this.attr( 'title', data.dtitle );
                    $this.attr( 'data-original-title', data.dtitle );
                    $this.toggleClass('active');
                });
            });
            // love
            $('.comment-actions .comment-love').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                $this.addClass('loading');

                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  "action=listdo_comment_love&comment_id="+$this.data('id') + "&security=" + listdo_listing_opts.ajax_nonce
                }).done(function(data) {
                    $this.removeClass('loading');
                    $this.html(data.icon + data.dtitle);
                    $this.attr( 'title', data.dtitle );
                    $this.attr( 'data-original-title', data.dtitle );
                    $this.toggleClass('active');
                });
            });

            // edit comment
            $('.comment-actions .listdo-edit-comment').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                var ajax_val;
                $this.addClass('loading');

                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'html',
                    data:  "action=listdo_comment_edit&comment_id="+$this.data('id') + "&security=" + listdo_listing_opts.ajax_nonce
                }).done(function(data) {
                    $this.removeClass('loading');
                    
                    $.magnificPopup.open({
                        mainClass: 'apus-mfp-zoom-in',
                        items    : {
                            src : data,
                            type: 'inline'
                        },
                        callbacks: {
                            open: function() {
                                self.listingReview();

                                // process edit comment
                                $(document).on('submit', 'form.edit-review-form', function(e){
                                    e.preventDefault();
                                    if ( ajax_val ) {
                                        return;
                                    }
                                    var $form = $(this);
                                    $form.addClass('loading');
                                    $form.find('.alert-text').remove();

                                    ajax_val = $.ajax({
                                        url: listdo_listing_opts.ajaxurl,
                                        type:'POST',
                                        dataType: 'json',
                                        data: $form.serialize() + "&action=listdo_process_comment_edit"
                                    }).done(function(data) {
                                        $form.removeClass('loading');
                                        
                                        $form.find('.alert-text').remove();
                                        if ( data.status ) {
                                            var comment_data = data.comment_data;
                                            $this.closest('.the-comment').find('.description').html(comment_data.comment_content);
                                            $this.closest('.the-comment').find('.star-rating').replaceWith(comment_data.rating_ouput);

                                            $form.prepend('<div class="alert-text text-info">' + data.msg + '</div>');
                                        } else {
                                            $form.prepend('<div class="alert-text text-warning">' + data.msg + '</div>');
                                        }

                                        $form.toggleClass('active');
                                        ajax_val = null;
                                    });
                                    return false;
                                });

                            }
                        }
                    });
                    
                    $this.toggleClass('active');
                });

            });
            
            //
            $('.comment-box').each(function(){
                var $this = $(this);
                $('.comment-see-more', $this).on('click', function(){
                    $('.comment-text', $this).slideToggle();
                    $('.title-job', $this).toggleClass("active");
                    initProductImageLoad();
                });
            });
            
            // follow/following
            $( "body" ).on( "click", ".btn-follow-following", function( e ) {
                e.preventDefault();

                var user_id = $(this).data('id');
                var $self = $(this);
                if ( $self.hasClass('loading') ) {
                    return false;
                }
                $self.addClass('loading');
                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data: {
                        'action': 'listdo_follow_user',
                        'user_id': user_id,
                        'security': listdo_listing_opts.ajax_nonce
                    }
                }).done(function(response) {
                    if ( response.status === 'error' ) {
                        self.showMessage(response.msg, response.status);
                    } else {
                        $self.removeClass('btn-follow-user').removeClass('btn-following-user').addClass(response.class);
                        if ( $self.hasClass('btn-outline') ) {
                            $self.removeClass('btn-outline');
                        } else {
                            $self.addClass('btn-outline');
                        }
                        $self.html(response.msg);
                    }
                    $self.removeClass('loading');
                });
            });
        },

        halfMapPaddingTop:function(){
            if ( $('#apus-header').length > 0 ) {
                var header_h = $('#apus-header').outerHeight();
                
                if ($('#apus-listing-map').is('.fix-map')) {
                    $('.fix-header #apus-main-content').css({'padding-top': header_h});
                }
            }
        },

        listingBtnFilter: function() {
            var self = this;
            $('.btn-show-filter').on('click', function(e){
                e.preventDefault();
                $('.job_filters .search_jobs').slideToggle(500);

                var $slick = $('.job_filters .search_jobs [data-carousel=slick]');

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });

            //  show amenities
            $('.apus-half-map-layout .title-amenity').on('click', function(e){
                $('.apus-half-map-layout .amenities-wrap').slideToggle(500);
            });

            // scroll filter sidebar
            if ($(window).width() < 992) {
                $('.wrapper-filters1 .job_filters').perfectScrollbar();
            }

            $(window).resize(function(){
                if ($(window).width() < 992) {
                    $('.wrapper-filters1 .job_filters').perfectScrollbar();
                }
            });

            // halfmap version 3
            if ($(window).width() >= 992) {
                $('.apus-half-map-layout-v3 .list-content .wrapper-filters1 .job_filters').perfectScrollbar();
                $('.apus-half-map-layout-v4 .list-content .wrapper-filters1 .job_filters').perfectScrollbar();
            }

            $('.btn-view-map').on('click', function(e){
                e.preventDefault();
                $('.apus-listing-map').removeClass('hidden-sm').removeClass('hidden-xs');
                $('.apus-listing-warpper .job_listings').addClass('hidden-sm').addClass('hidden-xs');
                $('.apus-listing-warpper .main-results').addClass('p-results-mobile');
                $('.btn-view-listing').removeClass('hidden-sm').removeClass('hidden-xs');
                $(this).addClass('hidden-sm').addClass('hidden-xs');
                $('.showing_jobs').addClass('hidden-sm').addClass('hidden-xs');
                $('.listing-action').addClass('hidden-sm').addClass('hidden-xs');
                setTimeout(function() {
                    $(window).trigger('pxg:refreshmap');
                    window.dispatchEvent(new Event('resize'));
                });
            });
            $('.btn-view-listing').on('click', function(e){
                e.preventDefault();
                $('.apus-listing-warpper .main-results').removeClass('p-results-mobile');
                $('.apus-listing-map').addClass('hidden-sm').addClass('hidden-xs');
                $('.apus-listing-warpper .job_listings').removeClass('hidden-sm').removeClass('hidden-xs');
                $('.btn-view-map').removeClass('hidden-sm').removeClass('hidden-xs');
                $(this).addClass('hidden-sm').addClass('hidden-xs');
                $('.showing_jobs').removeClass('hidden-sm').removeClass('hidden-xs');
                $('.listing-action').removeClass('hidden-sm').removeClass('hidden-xs');
            });

            $('.showmore').on('click', function(e){
                e.preventDefault();
                $(this).parent().find('.content-inner').toggleClass('active');
            });

            if ( $( 'input.field-datetimepicker' ).length > 0 && $.isFunction( $.fn.datetimepicker ) ) {
                jQuery.datetimepicker.setLocale(listdo_listing_opts.lang_code);
                $('input.field-datetimepicker').datetimepicker({'scrollInput': false});
            }
            // scrollbar
            function change_padding_filters() {
                if ($(window).width() >= 992) {
                    $('.apus-half-map-layout-v2 form.job_filters, .apus-grid-layout form.job_filters').perfectScrollbar();
                    var header_h = $('#apus-header').outerHeight();
                    if(header_h > 0){
                       $('.apus-half-map-layout-v2 .wrapper-filters').css({ 'top': header_h,'height': 'calc(100vh - ' + header_h+ 'px)' }); 
                    }
                } else {
                    $('.apus-half-map-layout-v2 .wrapper-filters').css({ 'top': 'inherit', 'height': 'auto' }); 
                }
            }

            function change_padding_filters_v3() {
                if ($(window).width() >= 1200) {
                    var header_h = $('#apus-header').outerHeight();
                    if(header_h > 0){
                       $('.apus-half-map-layout-v3 .wrapper-filters1').css({ 'top': header_h,'height': 'calc(100vh - ' + header_h+ 'px)' }); 
                       $('.apus-half-map-layout-v4 .wrapper-filters1').css({ 'top': header_h,'height': 'calc(100vh - ' + header_h+ 'px)' }); 
                    }
                } else {
                    $('.apus-half-map-layout-v3 .wrapper-filters1').css({ 'top': 0 }); 
                    $('.apus-half-map-layout-v4 .wrapper-filters1').css({ 'top': 0 }); 
                }
            }

            setTimeout(function(){
                change_padding_filters();    
                change_padding_filters_v3();    
            }, 50);
            $(window).resize(function(){
                change_padding_filters();
                change_padding_filters_v3();
                $('.apus-listing-warpper').removeClass('active')
            });

            $('.listings-filter-header, .mobile-groups-button .btn-filter').on('click', function(e){
                e.stopPropagation();
                $('.apus-half-map-layout, .apus-grid-layout').toggleClass('active');
                var $slick = $('.job_filters .search_jobs [data-carousel=slick]');

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });
            $('body').on('click', function() {
                if ($('.apus-half-map-layout, .apus-grid-layout').hasClass('active')) {
                    $('.apus-half-map-layout, .apus-grid-layout').removeClass('active');
                }
            });
            $('.apus-half-map-layout .wrapper-filters, .apus-grid-layout .wrapper-filters').on('click', function(e) {
                e.stopPropagation();
            });

            // filter 1
            $('.show-filter1, .btn-view-map, .btn-view-listing').on('click', function(e) {
                e.stopPropagation();
                $('.wrapper-filters1').toggleClass('active');
            });
            // filter 2
            $('.show-filter2').on('click', function(e) {
                e.stopPropagation();
                $('.wrapper-filters1').toggleClass('active');
                $('.over-dark').toggleClass('active');
                $(this).toggleClass('active');
            });
            $('body').on('click', function() {
                if ($('.wrapper-filters1').hasClass('active')) {
                    $('.wrapper-filters1').removeClass('active');
                    $('.over-dark').removeClass('active');
                }
            });
            $('.wrapper-filters1').on('click', function(e) {
                e.stopPropagation();
            });
        },
        select2Init: function() {
            // select2
            if ( $.isFunction( $.fn.select2 ) && typeof job_manager_select2_args !== 'undefined' ) {
                var select2_args = job_manager_select2_args;
                select2_args['allowClear']              = true;
                select2_args['minimumResultsForSearch'] = 10;
                var order_args = { minimumResultsForSearch: 10 };
                if ( $('html').attr('dir') == 'rtl' ) {
                    order_args.dir = 'rtl';
                }
                $( 'select[name^="filter_order"]' ).select2( order_args );

                order_args.width = '100%';
                if( $('.widget-listingsearch select[name^="search_categories"],.widget-listingsearch select[name^="job_region_select"]').length ){
                    select2_args.theme = 'default customizer-orderby';
                }
                $( 'select[name^="job_region_select"]' ).select2( select2_args );
                $( 'select[name^="filter_price_range"]' ).select2( select2_args );
                $( 'select[name^="search_categories"]' ).select2( select2_args );
                $( 'select[name^="job_type_select"]' ).select2( select2_args );
                $( 'select[name^="search_types"]' ).select2( select2_args );

                // submit form
                $( 'select[name^="job_regions"]' ).select2( order_args );
                $( 'select[name^="job_categories"]' ).select2( order_args );
                $( 'select[name^="job_c_category"]' ).select2( order_args );
                $( 'select[name^="job_c_type"]' ).select2( order_args );

                $( 'select[name="job_amenities"]' ).select2( order_args );
                $( 'select[name="job_type"]' ).select2( order_args );
                $( 'select[name="job_category"]' ).select2( order_args );
                $( 'select[name="job_price_range"]' ).select2( order_args );
            }
        },
        listingFilterProcess:function() {
            var $supports_html5_history = false;
            if ( window.history && window.history.pushState ) {
                $supports_html5_history = true;
            }

            var target = $('.job_listings');
            target.find('.job_listings').addClass('loading');
            target.triggerHandler( 'update_results', [ 1, false ] );

            if ( $supports_html5_history ) {
                var form  = target.find( '.job_filters' );
                var data  = $( form ).serialize();
                var index = $( 'div.job_listings' ).index( target );
                window.history.replaceState( { id: 'job_manager_state', page: 1, data: data, index: index }, '', location );
            }
        },
        listingFilter: function() {
            var self = this;
            
            if ( $('input[name^=filter_event_date_]').length > 0 && $.isFunction( $.fn.datetimepicker ) ) {
                jQuery.datetimepicker.setLocale(listdo_listing_opts.lang_code);
                $('input[name^=filter_event_date_]').datetimepicker({
                    timepicker: false,
                    format: listdo_listing_opts.date_format,
                    'scrollInput': false
                });
                var date_from_val = '', date_to_val = '';
                $('input[name^=filter_event_date_from]').on('change', function(){
                    if ( date_from_val == $(this).val() ) {
                        return false;
                    }
                    date_from_val = $(this).val();
                    self.listingFilterProcess();
                });
                $('input[name^=filter_event_date_to]').on('change', function(){
                    if ( date_to_val == $(this).val() ) {
                        return false;
                    }
                    date_to_val = $(this).val();
                    self.listingFilterProcess();
                });
            }
            $('.submit-filter .btn-filter').on('click', function(){
                self.listingFilterProcess();
                if ( $(window).width() <= 991 ) {
                    $('.wrapper-filters1').removeClass('active');
                }
            });
            
            $('input[name^=search_keywords]').on('change', function(){
                self.listingFilterProcess();
            });

            // Category
            var change_category_fc = function(val) {
                if ( $('select[name^="job_type_select"]').length > 0 ) {
                    var main_select = $('select[name^="job_type_select"]');
                    main_select.val('');
                    var main_con = main_select.parent();
                    main_con.addClass('loading');
                    var placeholder = '';
                    if ( main_select.data('placeholder') !== 'undefined' ) {
                        placeholder = main_select.data('placeholder');
                    }
                    $.ajax({
                        url: listdo_listing_opts.ajaxurl,
                        type:'POST',
                        dataType: 'html',
                        data:{
                            'action': 'listdo_process_change_category',
                            'category_parent': val,
                            'security': listdo_listing_opts.ajax_nonce,
                            'name': main_select.attr('name'),
                            'id': '',
                            'placeholder': placeholder,
                        }
                    }).done(function(data) {
                        main_con.removeClass('loading');
                        if ( $.isFunction( $.fn.select2 ) ) {
                            main_con.find('select[name^="job_type_select"]').select2("close");
                        }
                        main_con.html(data);
                        if ( $.isFunction( $.fn.select2 ) ) {
                            var select2_args = job_manager_select2_args;
                            select2_args['allowClear']              = true;
                            select2_args['minimumResultsForSearch'] = 10;
                            main_con.find('select[name^="job_type_select"]').select2(select2_args);
                        }
                        
                    });
                }
            }
            $(document).on('change', 'body.listing-type-car select[name^="search_categories"]', function(){
                var val = $(this).val();
                change_category_fc(val);
            });
            // var val = $('body.listing-type-car .job_filters select[name^="search_categories"]').val();
            // if ( val ) {
            //     change_category_fc(val);
            // }
            
            // Category change amenity
            var change_category_amenities_fc = function(val) {
                if ( $('.search_amenity_wrapper .amenities-wrap').length > 0 ) {
                    var main_con = $('.search_amenity_wrapper .amenities-wrap');

                    main_con.find('input:checked').each(function(i, obj) {
                        $(obj).attr('checked', false);
                        $(obj).parent().removeClass('active');
                    });

                    main_con.addClass('loading');
                    
                    $.ajax({
                        url: listdo_listing_opts.ajaxurl,
                        type:'POST',
                        dataType: 'html',
                        data:{
                            'action': 'listdo_process_change_category_amenities',
                            'category_parent': val,
                            'security': listdo_listing_opts.ajax_nonce
                        }
                    }).done(function(data) {
                        main_con.removeClass('loading');
                        main_con.replaceWith(data);
                    });
                }
            }
            $(document).on('change', 'select[name^="search_categories"]', function(){
                var val = $(this).val();
                change_category_amenities_fc(val);
            });


            $(document).on('change', '.job_filters select[name^="search_categories"]', function(){
                setTimeout(function(){
                    self.listingFilterProcess();
                }, 50);
            });
            $(document).on('change', '.job_filters select[name^="job_region_select"]', function(){
                setTimeout(function(){
                    self.listingFilterProcess();
                }, 50);
            });

            $('.job_filters select[name^=job_type_select]').change(function(){
                self.listingFilterProcess();
            });


            $( 'select[name^="search_rooms"]' ).change(function(){
                self.listingFilterProcess();
            });

            // amenities
            var updateAmenities = function() {
                $('.job_filters').on('change', '.job_tags :input, .job_amenities :input', function(){
                    $( this ).parent().toggleClass('active');
                    self.listingFilterProcess();
                });
            };
            updateAmenities();
            $( '.job_tags :input, .job_amenities :input' ).each( function() {
                if ($(this).is(':checked') ) {
                    $( this ).parent().addClass('active');
                } else {
                    $( this ).parent().removeClass('active');
                }
            });

            $('.listing-action select[name=filter_order]').change(function(){
                $('#input_filter_order').val($(this).val());
                self.listingFilterProcess();
            });

            $('.search-distance-slider').each(function(){
                var $this = $(this);
                var search_distance = $this.closest('.search-distance-wrapper').find('input[name^=search_distance]');
                var search_wrap = $this.closest('.search_distance_wrapper');
                $(this).slider({
                    range: "min",
                    value: search_distance.val(),
                    min: 0,
                    max: 100,
                    slide: function( event, ui ) {
                        search_distance.val( ui.value );
                        $('.text-distance', search_wrap).text( ui.value );
                        $('.distance-custom-handle', $this).attr( "data-value", ui.value );

                        self.listingFilterProcess();
                    },
                    create: function() {
                        $('.distance-custom-handle', $this).attr( "data-value", $( this ).slider( "value" ) );
                    }
                } );
            } );

            $('.price_slider_wrapper .price_range_slider').each(function(){
                var $this = $(this);
                $this.slider({
                    range: true,
                    min: $this.data('min'),
                    max: $this.data('max'),
                    values: [ $this.parent().find('.filter-price-from').val(), $this.parent().find('.filter-price-to').val() ],
                    slide: function( event, ui ) {
                        $this.parent().find('.price_from .price').text( self.addCommas(ui.values[ 0 ]) );
                        $this.parent().find('.filter-price-from').val( ui.values[ 0 ] )
                        $this.parent().find('.price_to .price').text( self.addCommas(ui.values[ 1 ]) );
                        $this.parent().find('.filter-price-to').val( ui.values[ 1 ] );

                        self.listingFilterProcess();
                    }
                } );
            });
            

            $.fn.bindFirst = function(name, selector, fn) {
                // bind as you normally would
                // don't want to miss out on any jQuery magic
                this.on(name, selector, fn);

                // Thanks to a comment by @Martin, adding support for
                // namespaced events too.
                this.each(function() {
                    var handlers = $._data(this, 'events')[name.split('.')[0]];
                    // take out the handler we just inserted from the end
                    var handler = handlers.pop();
                    // move it at the beginning
                    handlers.splice(0, 0, handler);
                });
            };

            $('.job_filters').bindFirst('click', '.reset', function() {
                
                $('.job_amenities').find(':checked').each(function(i, obj) {
                    $(obj).attr('checked', false);
                    $(obj).prop('checked', false);
                    $(obj).parent().removeClass('active');
                });
                
                $('.job_filters select[name^="job_region_select"]').find(':selected').each(function(i, obj) {
                    $(obj).attr('selected', false);
                    $(obj).prop('selected', false);
                });
                $('.job_filters select[name^="job_region_select"]').trigger('change.select2');


                $('.job_filters select[name^=job_type_select]').find(':selected').each(function(i, obj) {
                    $(obj).attr('selected', false);
                    $(obj).prop('selected', false);
                });
                $('.job_filters select[name^=job_type_select]').trigger('change.select2');




                $('input[name="search_keywords"]').each(function(i, obj) {
                    $(obj).val('');
                });
                $('input[name^="search_lat"]').val('');
                $('input[name^="search_lng"]').val('');
                $('input[name^="search_location"]').val('');
                $('input[name^="search_location"]').parent().find('.clear-location').removeClass('hidden').addClass('hidden');

                self.listingFilterProcess();
            });
            $( '.search_location').on('click', '.clear-location', function() {
                var container = $(this).parent();
                container.find('input[name^="search_lat"]').val('');
                container.find('input[name^="search_lng"]').val('');
                container.find('input[name^="search_location"]').val('');
                $('input[name="search_location"]').val('');
                $('#leaflet-geocode-container').html('').removeClass('active');
                container.find('.clear-location').removeClass('hidden').addClass('hidden');
            });
            $('input[name^="search_location"]').on('keyup', function(){
                var val = $(this).val();
                if ( $(this).val() !== '' ) {
                    $(this).parent().find('.clear-location').removeClass('hidden');
                } else {
                    $(this).parent().find('.clear-location').removeClass('hidden').addClass('hidden');
                }
            });
            $('input[name^="search_location"]').each(function(){
                var this_e = $(this);
                var parent = $(this).parent();
                var val = this_e.val();
                if ( this_e.val() !== '' ) {
                    parent.find('.clear-location').removeClass('hidden');
                } else {
                    parent.find('.clear-location').removeClass('hidden').addClass('hidden');
                }
            });

            // $('.fields-filter.list-inner-full').perfectScrollbar();

            // find me
            $('.find-me').on('click', function() {
                $(this).addClass('loading');
                var this_e = $(this);
                var container = $(this).parent();

                navigator.geolocation.getCurrentPosition(function (position) {
                    container.find('input[name^="search_lat"]').val(position.coords.latitude);
                    container.find('input[name^="search_lng"]').val(position.coords.longitude);
                    $('input[name="search_location"]').val('location');
                    container.find('.clear-location').removeClass('hidden');

                    var position = [position.coords.latitude, position.coords.longitude];

                    var geocodeService = L.esri.Geocoding.geocodeService();
                    geocodeService.reverse().latlng(position).run(function(error, result) {
                        $('input[name="search_location"]').val(result.address.Match_addr);
                    });

                    setTimeout(function(){
                        $('.job_listings').triggerHandler('update_results', [1, false]);
                    }, 50);
                    return this_e.removeClass('loading');
                }, function (e) {
                    return this_e.removeClass('loading');
                }, {
                    enableHighAccuracy: true
                });
            });

            // search autocomplete location
            if ( listdo_listing_opts.geocoder_country ) {
                var geocoder = new L.Control.Geocoder.Nominatim({
                    geocodingQueryParams: {countrycodes: listdo_listing_opts.geocoder_country}
                });
            } else {
                var geocoder = new L.Control.Geocoder.Nominatim();
            }

            $("input[name=search_location]").attr('autocomplete', 'off').after('<div id="leaflet-geocode-container"></div>');
            $("input[name=search_location]").on("keyup",function search(e) {
                var s = $(this).val(), $this = $(this);
                if (s && s.length >= 2) {
                    
                    $this.parent().addClass('loading');
                    geocoder.geocode(s, function(results) {
                        var output_html = '';
                        for (var i = 0; i < results.length; i++) {
                            output_html += '<li class="result-item" data-latitude="'+results[i].center.lat+'" data-longitude="'+results[i].center.lng+'" ><i class="fa fa-map-marker" aria-hidden="true"></i> '+results[i].name+'</li>';
                        }
                        if ( output_html ) {
                            output_html = '<ul>'+ output_html +'</ul>';
                        }

                        $('#leaflet-geocode-container').html(output_html).addClass('active');

                        var highlight_texts = s.split(' ');

                        highlight_texts.forEach(function (item) {
                            $('#leaflet-geocode-container').highlight(item);
                        });

                        $this.parent().removeClass('loading');
                    });
                } else {
                    $("#leaflet-geocode-container").html('').removeClass('active');
                }
            });
            $('.search_location').on('click', '#leaflet-geocode-container ul li', function() {
                var container = $(this).closest('.search_location');
                container.find('input[name=search_lat]').val($(this).data('latitude'));
                container.find('input[name=search_lng]').val($(this).data('longitude'));
                container.find('input[name=search_location]').val($(this).text());
                $('#leaflet-geocode-container').removeClass('active').html('');

                jQuery('.job_listings').triggerHandler('update_results', [1, false]);
            });

        },
        addCommas: function(str) {
            var parts = (str + "").split("."),
                main = parts[0],
                len = main.length,
                output = "",
                first = main.charAt(0),
                i;
            
            if (first === '-') {
                main = main.slice(1);
                len = main.length;    
            } else {
                first = "";
            }
            i = len - 1;
            while(i >= 0) {
                output = main.charAt(i) + output;
                if ((len - i) % 3 === 0 && i > 0) {
                    output = listdo_listing_opts.money_thousands_separator + output;
                }
                --i;
            }
            // put sign back
            output = first + output;
            // put decimal part back
            if (parts.length > 1) {
                output += listdo_listing_opts.money_dec_point + parts[1];
            }
            return output;
        },
        listingReview: function() {
            if ( $('.comment-form-rating').length > 0 ) {
                $('.comment-form-rating .rating-inner').each(function(){
                    var e_this = $(this);
                    var $star = e_this.find('.review-stars');
                    var $review = e_this.find('input.rating');
                    $star.find('li').on('mouseover',
                        function () {
                            $(this).nextAll().find('span').removeClass('active');
                            $(this).prevAll().find('span').removeClass('active').addClass('active');
                            $(this).find('span').removeClass('active').addClass('active');
                            var key = $(this).data('key');
                            e_this.find('.review-label').html( listdo_listing_opts.reviews[key] );
                            //$review.val($(this).index() + 1);
                        }
                    );
                    $star.on('mouseout', function(){
                        var current = $review.val() - 1;
                        var current_e = $star.find('li').eq(current);

                        current_e.nextAll().find('span').removeClass('active');
                        current_e.prevAll().find('span').removeClass('active').addClass('active');
                        current_e.find('span').removeClass('active').addClass('active');
                        var key = current_e.data('key');
                        e_this.find('.review-label').html( listdo_listing_opts.reviews[key] );
                    });

                    $star.find('li').on('click', function () {
                        $(this).nextAll().find('span').removeClass('active');
                        $(this).prevAll().find('span').removeClass('active').addClass('active');
                        $(this).find('span').removeClass('active').addClass('active');
                        
                        var key = $(this).data('key');
                        e_this.find('.review-label').html( listdo_listing_opts.reviews[key] );
                        $review.val($(this).index() + 1);
                    } );


                    var val = $review.val() - 1;
                    var key = $star.find('li').eq(val).data('key');
                    e_this.find('.review-label').html( listdo_listing_opts.reviews[key] );
                });
            }

        },
        bookmarkInit: function() {
            var self = this;
            // bookmark
            $( "body" ).on( "click", ".apus-bookmark-add", function( e ) {
                e.preventDefault();

                var post_id = $(this).data('id');
                var url = listdo_listing_opts.ajaxurl + '?action=listdo_add_bookmark&post_id=' + post_id + '&security=' + listdo_listing_opts.ajax_nonce;
                var $self = $(this);
                $self.addClass('loading');
                $.ajax({
                    url: url,
                    type:'POST',
                    dataType: 'json',
                }).done(function(reponse) {
                    if (reponse.status === 'success') {
                        $self.addClass('apus-bookmark-added').removeClass('apus-bookmark-add');
                    }
                    $self.removeClass('loading');
                    self.showMessage(reponse.msg, reponse.status);
                });
            });
            $(document).on('click', '.apus-bookmark-not-login', function(e){
                e.preventDefault();
                
                $('.apus-user-login').trigger('click');
                return false;
            });

            // bookmark remove
            $( "body" ).on( "click", ".apus-bookmark-added", function( e ) {
                e.preventDefault();

                var post_id = $(this).data('id');
                var url = listdo_listing_opts.ajaxurl + '?action=listdo_remove_bookmark&post_id=' + post_id + '&security=' + listdo_listing_opts.ajax_nonce;
                var $self = $(this);
                $self.addClass('loading');
                $.ajax({
                    url: url,
                    type:'POST',
                    dataType: 'json',
                }).done(function(reponse) {
                    if (reponse.status === 'success') {
                        $self.removeClass('apus-bookmark-added').addClass('apus-bookmark-add');
                    }
                    $self.removeClass('loading');
                    self.showMessage(reponse.msg, reponse.status);
                });
            });
            $( "body" ).on( "click", ".apus-bookmark-remove", function( e ) {
                e.preventDefault();

                var post_id = $(this).data('id');
                var url = listdo_listing_opts.ajaxurl + '?action=listdo_remove_bookmark&post_id=' + post_id + '&security=' + listdo_listing_opts.ajax_nonce;
                $(this).addClass('loading');
                $.ajax({
                    url: url,
                    type:'POST',
                    dataType: 'json',
                }).done(function(reponse) {
                    if (reponse.status === 'success') {
                        var parent = $('#bookmark-listing-' + post_id).parent();
                        if ( $('.my-listing-item-wrapper', parent).length <= 1 ) {
                            location.reload();
                        } else {
                            $('#bookmark-listing-' + post_id).remove();
                        }
                    }
                    self.showMessage(reponse.msg, reponse.status);
                });
            });
        },
        showMessage: function(msg, status) {
            console.log(msg);
            if ( msg ) {
                var classes = 'alert alert-warning';
                if ( status == 'success' ) {
                    classes = 'alert alert-info';
                }
                var $html = '<div id="listdo-popup-message" class="animated fadeInRight"><div class="message-inner '+ classes +'">'+ msg +'</div></div>';
                $('body').find('#listdo-popup-message').remove();
                $('body').append($html).fadeIn(500);
                setTimeout(function() {
                    $('body').find('#listdo-popup-message').removeClass('fadeInRight').addClass('delay-1s fadeOutRight');
                }, 2500);
            }
        },
        searchInit: function() {
            var self = this;
            // widget search jobs
            $(".search_jobs .show-more-filter").on('click', function(e){
                e.preventDefault();
                $(".search_jobs .tags-wrap").toggle('500');
                if($(this).find('i').hasClass('fa-plus')){
                    $(this).find('i').removeClass('fa-plus');
                    $(this).find('i').addClass('fa-minus');
                } else {
                    $(this).find('i').removeClass('fa-minus');
                    $(this).find('i').addClass('fa-plus');
                }
            });
            $('.job_search_form .has-suggestion').on('click', function(e) {
                e.stopPropagation();
            });
            $(".job_search_form .has-suggestion").on('click', function(){
                var search_val = $(this).find('input[name=search_keywords]').val();
                if ( search_val === '' ) {
                    $(this).toggleClass("active");
                } else {
                    $(this).removeClass("active");
                }
                //alert(search_val);
            });
            $('body').on('click', function() {
                if ($('.job_search_form .has-suggestion').hasClass('active')) {
                    $('.job_search_form .has-suggestion').removeClass('active');
                }
            });

            $('.navbar-collapse-suggestions').perfectScrollbar();

            // fix map
            if ($('#apus-listing-map').is('.fix-map')) {
                setTimeout(function(){
                    self.changePaddingTopContent();    
                }, 50);
                $(window).resize(function(){
                    self.changePaddingTopContent();
                });
            }
        },
        changePaddingTopContent: function() {
            if ($(window).width() >= 1200) {
                var header_h = $('#apus-header').outerHeight();
            } else {
                var header_h = $('#apus-header-mobile').outerHeight();
            }
            $('#apus-listing-map').css({ 'top': header_h });
            $('#apus-listing-map').css({ 'height': 'calc(100vh - ' + header_h+ 'px)' });
            
            $('.listings-filter-wrapper').css({ 'top': header_h });
            $('.listings-filter-wrapper').css({ 'height': 'calc(100% - ' + header_h+ 'px)' });

        },
        getListings: function() {
            var self = this;
            if ( $('.widget-listing-maps .apus-listing-map').length ) {
                $('.widget-listing-maps .apus-listing-map').each(function(e){
                    var $this = $(this);
                    
                    $this.addClass('loading');
                    var $settings = $(this).data('settings');
                    $.ajax({
                        url: listdo_listing_opts.ajaxurl,
                        type:'POST',
                        dataType: 'html',
                        data: {
                            action: 'listdo_get_ajax_listings',
                            settings: $settings,
                        }
                    }).done(function(data) {
                        $this.removeClass('loading');
                        $this.closest('.widget-listing-maps').find('.job_listings_cards').html(data);
                        setTimeout(function(){
                            self.updateMakerCards();
                        });
                    });
                });
            }
        },
        mapInit: function() {
            var self = this;
            //
            self.initStreetView();

            var $window = $(window);
            if ($('.no_job_listings_found').length) {
                $('<div class="results">' + listdo_listing_opts.strings['no_job_listings_found'] + '</div>').prependTo('.showing_jobs, .listing-search-result');
            }

            if (!$('#apus-listing-map').length) {
                $('.job_listings').on('updated_results', function(e, result) {
                    var target = $( this );
                    self.layzyLoadImage();
                    self.timerCountdown();
                    self.previewInit();

                    if ( true === target.data( 'show_pagination' ) ) {
                        target.find('.job-manager-pagination').remove();

                        if ( result.pagination ) {
                            target.find('.main-results').append( result.pagination );
                        }
                    }
                    self.updateMakerCards(result.total_found, result);
                    $('[data-toggle="tooltip"]').tooltip(); 
                });
                return;
            }

            map = L.map('apus-listing-map', {
                scrollWheelZoom: false
            });

            markers = new L.MarkerClusterGroup({
                showCoverageOnHover: false
            });

            CustomHtmlIcon = L.HtmlIcon.extend({
                options: {
                    html: "<div class='map-popup'></div>",
                    iconSize: [42, 42],
                    iconAnchor: [22, 42],
                    popupAnchor: [0, -42]
                }
            });

            $window.on('pxg:refreshmap', function() {
                map._onResize();
                setTimeout(function() {
                    if(markerArray.length > 0 ){
                        group = L.featureGroup(markerArray);
                        map.fitBounds(group.getBounds()); 
                    }
                }, 100);
            });

            $window.on('pxg:simplerefreshmap', function() {
                map._onResize();
            });

            if ( listdo_listing_opts.map_service == 'mapbox' ) {
                var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/'+listdo_listing_opts.mapbox_style+'/tiles/{z}/{x}/{y}?access_token='+ listdo_listing_opts.mapbox_token, {
                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Mapbox</a> &copy;  <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
                    maxZoom: 18,
                });
            } else if( listdo_listing_opts.map_service == 'openstreetmap' ) {
                var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

            } else {
                if ( listdo_listing_opts.custom_style != '' ) {
                    try {
                        var custom_style = $.parseJSON(listdo_listing_opts.custom_style);
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap',
                            styles: custom_style
                        });

                    } catch(err) {
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap'
                        });
                    }
                } else {
                    var tileLayer = L.gridLayer.googleMutant({
                        type: 'roadmap'
                    });
                }
                $('#apus-listing-map').addClass('map--google');
            }

            map.addLayer(tileLayer);
            

            // check home/archive/single page
            if ( $('#apus-listing-map').is('.apus-homepage-listing-map') ) {
                self.updateMakerCards();
            } else {
                if ( !$('#apus-listing-map').is('.apus-single-listing-map') ) {

                    $('.job_listings').on('updated_results', function(e, result) {
                        var target = $( this );
                        self.layzyLoadImage();
                        self.timerCountdown();
                        self.previewInit();
                        if ( true === target.data( 'show_pagination' ) ) {
                            target.find('.job-manager-pagination').remove();
                            if ( result.pagination ) {
                                target.find('.main-results').append( result.pagination );
                            }
                        }
                        self.updateMakerCards(result.total_found, result);
                        $('[data-toggle="tooltip"]').tooltip(); 
                    });
                    // FacetWP
                    $(document).on('facetwp-loaded', function(e, result) {
                        self.updateMakerCards();
                    });
                } else {
                    var $item = $('.apus-single-listing-wrapper');
                    
                    if ( $item.data('latitude') !== "" && $item.data('latitude') !== "" ) {
                        var zoom = (typeof MapWidgetZoom !== "undefined") ? MapWidgetZoom : 15;
                        self.addMakerToMap($item);
                        map.addLayer(markers);
                        map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                        // $(window).on('update:map', function() {
                        //     map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                        // });
                        
                        $('.top-nav-map').on('click', function(e){
                            e.preventDefault();
                            $('#apus-listing-map-street-view').hide();
                            $('#apus-listing-map').show();
                            $('.top-nav-street-view').removeClass('active');
                            $('.top-nav-map').removeClass('active').addClass('active');
                            map._onResize();
                        });
                    } else {
                        $('#apus-listing-map').hide();
                        $('.listing-address').css('marginTop', 0);
                    }
                }
            }
        },
        mapSidebarInit: function() {
            var self = this;

            var $window = $(window);
            var markersSidebar;
            if (!$('#apus-listing-map-sidebar').length) {
                return;
            }

            mapSidebar = L.map('apus-listing-map-sidebar', {
                scrollWheelZoom: false
            });

            markersSidebar = new L.MarkerClusterGroup({
                showCoverageOnHover: false
            });

            CustomHtmlIcon = L.HtmlIcon.extend({
                options: {
                    html: "<div class='map-popup'></div>",
                    iconSize: [48, 59],
                    iconAnchor: [24, 59],
                    popupAnchor: [0, -59]
                }
            });

            $window.on('pxg:refreshmap', function() {
                mapSidebar._onResize();
            });

            $window.on('pxg:simplerefreshmap', function() {
                mapSidebar._onResize();
            });

            if ( listdo_listing_opts.map_service == 'mapbox' ) {
                var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/'+listdo_listing_opts.mapbox_style+'/tiles/{z}/{x}/{y}?access_token='+ listdo_listing_opts.mapbox_token, {
                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Mapbox</a> &copy;  <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
                    maxZoom: 18,
                });
            } else {
                if ( listdo_listing_opts.custom_style != '' ) {
                    try {
                        var custom_style = $.parseJSON(listdo_listing_opts.custom_style);
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap',
                            styles: custom_style
                        });

                    } catch(err) {
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap'
                        });
                    }
                } else {
                    var tileLayer = L.gridLayer.googleMutant({
                        type: 'roadmap'
                    });
                }
                $('#apus-listing-map').addClass('map--google');
            }
            
            mapSidebar.addLayer(tileLayer);

            var $item = $('.apus-single-listing-wrapper');
            
            if ( $item.data('latitude') !== "" && $item.data('latitude') !== "" ) {
                var zoom = (typeof MapWidgetZoom !== "undefined") ? MapWidgetZoom : 15;

                if ( $item.data('latitude') == "" || $item.data('longitude') == "") {
                    return;
                }
                var logo_wrapper = $item.find('.listing-logo img'), marker;
                var mapPinHTML = "<div class='map-popup map-popup-empty'><div class='icon-wrapper'><div class='icon-cat'>!</div></div></div>";

                if (logo_wrapper.length) {
                    var img = logo_wrapper.data('src');
                    if ( typeof img === 'undefined' ) {
                        img = logo_wrapper.attr('src');
                    }
                    mapPinHTML = "<div class='map-popup'><div class='icon-cat'><img src='" + img + "' alt=''></div></div>";
                } else if ( $item.data('thumb') ) {
                    mapPinHTML = "<div class='map-popup'><div class='icon-cat'><img src='" + $item.data('thumb') + "' alt=''></div></div>";
                }

                marker = L.marker([$item.data('latitude'), $item.data('longitude')], {
                    icon: new CustomHtmlIcon({ html: mapPinHTML })
                });
                markersSidebar.addLayer(marker);

                mapSidebar.addLayer(markersSidebar);

                mapSidebar.setView([$item.data('latitude'), $item.data('longitude')], zoom);

            } else {
                $('#apus-listing-map-sidebar').hide();
                $('.listing-address').css('marginTop', 0);
            }
        },
        initStreetView: function() {
            var panorama = null;
            
            $('.top-nav-street-view').on('click', function(e){
                e.preventDefault();
                $('#apus-listing-map-street-view').show();
                $('#apus-listing-map').hide();
                $('.top-nav-street-view').removeClass('active').addClass('active');
                $('.top-nav-map').removeClass('active');

                var $item = $('.apus-single-listing-wrapper');

                if ( $item.data('latitude') !== "" && $item.data('longitude') !== "") {
                    var zoom = (typeof MapWidgetZoom !== "undefined") ? MapWidgetZoom : 15;
                    
                    if ( panorama == null ) {

                        var fenway = new google.maps.LatLng($item.data('latitude'),$item.data('longitude'));
                        var panoramaOptions = {
                            position: fenway,
                            pov: {
                                heading: 34,
                                pitch: 10
                            }
                        };
                        panorama = new  google.maps.StreetViewPanorama(document.getElementById('apus-listing-map-street-view'),panoramaOptions);
                    }
                }
            });
        },
        updateMakerCards: function($total_found, $result) {
            var self = this;
            var $items = $('.job_listings_cards .job_listing');
            
            $('.showing_jobs .results, .listing-search-result .results, .listing-search-result-filter .results').remove();

            var result_str = '<div class="results"><span class="results-no">';

            if (typeof $result !== 'undefined' && typeof $result.found !== 'undefined') {
                result_str += '<span class="results-no">' + $result.found + '</span> ';
            }

            result_str += listdo_listing_opts.strings['results-no'];

            if (typeof $result !== 'undefined' && typeof $result.str_found !== 'undefined') {
                result_str = result_str + $result.str_found;
            }
            
            result_str = result_str + '</div>';

            $(result_str).prependTo('.showing_jobs, .listing-search-result');

            if (typeof $result !== 'undefined' && $result.showing !== '' && $result.showing_links !== '') {
                $('<div class="results">' +
                    $result.showing + ' ' +
                    $result.showing_links +
                    '</div>').prependTo('.listing-search-result-filter');
            }
            

            if ($('#apus-listing-map').length && typeof map !== "undefined") {
                
                if (!$items.length) {
                    map.setView([listdo_listing_opts.default_latitude, listdo_listing_opts.default_longitude], 12);
                    return;
                }
                
                map.removeLayer(markers);
                markers = new L.MarkerClusterGroup({
                    showCoverageOnHover: false
                });
                $items.each(function(i, obj) {
                    self.addMakerToMap($(obj), true);
                });

                map.addLayer(markers);

                if ( markerArray.length > 0 ) {
                    group = L.featureGroup(markerArray);
                    map.fitBounds(group.getBounds());
                    if ( markerArray.length == 1 ) {
                        map.setZoom(16);
                    }
                }
            }
        },
        addMakerToMap: function($item, archive) {
            var self = this;
            var logo_wrapper = $item.find('.listing-logo img'), marker;

            if ( $item.data('latitude') == "" || $item.data('longitude') == "") {
                return;
            }

            var mapPinHTML = "<div class='map-popup map-popup-empty'><div class='icon-wrapper'><div class='icon-cat'>!</div></div></div>";

            if (logo_wrapper.length) {
                var img = logo_wrapper.data('src');
                if ( typeof img === 'undefined' ) {
                    img = logo_wrapper.attr('src');
                }
                mapPinHTML = "<div class='map-popup'><div class='icon-cat'><img src='" + img + "' alt=''></div></div>";
            } else if ( $item.data('thumb') ) {
                mapPinHTML = "<div class='map-popup'><div class='icon-cat'><img src='" + $item.data('thumb') + "' alt=''></div></div>";
            }

            marker = L.marker([$item.data('latitude'), $item.data('longitude')], {
                icon: new CustomHtmlIcon({ html: mapPinHTML })
            });

            if (typeof archive !== "undefined") {

                $item.on('hover', function() {
                    $(marker._icon).find('.map-popup').addClass('map-popup-selected');
                }, function() {
                    $(marker._icon).find('.map-popup').removeClass('map-popup-selected');
                });
                var price_html = '';
                if ( $item.find('.price-range').length ) {
                    //price_html = "<div class='listing-price'>" + $item.find('.price-range').html() + "</div>";
                }
                var title_html = '';
                if ( $item.find('.listing-title').length ) {
                    title_html = "<div class='listing-title'>" + $item.find('.listing-title').html() + "</div>";
                }
                var address_html = '';
                if ( $item.find('.listing-address').length ) {
                    address_html = "<div class='listing-address'>" + $item.find('.listing-address').html() + "</div>";
                }
                var phone_html = '';
                if ( $item.find('.listing-phone').length ) {
                    phone_html = "<div class='listing-phone'>" + $item.find('.listing-phone').clone().wrap('<div>').parent().html() + "</div>";
                }
                var review_html = '';
                if ( $item.find('.wrapper-star-average-rating').length ) {
                    review_html = "<div class='wrapper-star-average-rating'>" + $item.find('.wrapper-star-average-rating').html() + "</div>";
                }
                var content_botom = '';
                if ( $item.find('.listing-content-bottom').length ) {
                    content_botom = "<div class='listing-content-bottom'>" + $item.find('.listing-content-bottom').html() + "</div>";
                }
                var time_html = '';

                marker.bindPopup(
                    "<div class='job-grid-style job_listing'>" +
                        "<div class='listing-image'>" +
                            "<div class='image-wrapper image-loaded'>" +
                                "<a class='map-popup-url' href='" + $item.data('permalink') + "'>" +
                                "<img src='" + $item.data('img') + "' alt=''>" +
                                "</a>" + time_html +
                            "</div>" + price_html +
                            "<div class='listing-title-wrapper'>" + review_html + title_html + address_html + phone_html + "</div>" +
                        "</div>" + content_botom + 
                    "</div>").openPopup();
            }

            markers.addLayer(marker);
            markerArray.push(L.marker([$item.data('latitude'), $item.data('longitude')]));

            self.layzyLoadImage();
            self.timerCountdown();
        },
        previewMap: function() {
            var self = this;
            var $window = $(window);
            map = L.map('apus-preview-listing-map', {
                scrollWheelZoom: false
            });

            markers = new L.MarkerClusterGroup({
                showCoverageOnHover: false
            });

            CustomHtmlIcon = L.HtmlIcon.extend({
                options: {
                    html: "<div class='map-popup'></div>",
                    iconSize: [42, 42],
                    iconAnchor: [22, 42],
                    popupAnchor: [0, -42]
                }
            });

            $window.on('pxg:refreshmap', function() {
                map._onResize();
            });

            $window.on('pxg:simplerefreshmap', function() {
                map._onResize();
            });

            if ( listdo_listing_opts.map_service == 'mapbox' ) {
                var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/'+listdo_listing_opts.mapbox_style+'/tiles/{z}/{x}/{y}?access_token='+ listdo_listing_opts.mapbox_token, {
                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Mapbox</a> &copy;  <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
                    maxZoom: 18,
                });
            } else {
                if ( listdo_listing_opts.custom_style != '' ) {
                    try {
                        var custom_style = $.parseJSON(listdo_listing_opts.custom_style);
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap',
                            styles: custom_style
                        });

                    } catch(err) {
                        var tileLayer = L.gridLayer.googleMutant({
                            type: 'roadmap'
                        });
                    }
                } else {
                    var tileLayer = L.gridLayer.googleMutant({
                        type: 'roadmap'
                    });
                }
                $('#apus-listing-map').addClass('map--google');
            }
            
            map.addLayer(tileLayer);

            // check home/archive/single page
            
            var $item = $('.quickview-wrapper');
            if ( $item.data('latitude') !== "" &&  $item.data('longitude') !== "") {
                var zoom = (typeof MapWidgetZoom !== "undefined") ? MapWidgetZoom : 15;
                self.addMakerToMap($item);
                map.addLayer(markers);

                map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                $(window).on('update:map', function() {
                    map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                });
            } else {
                $('#apus-preview-listing-map').hide();
                $('.listing-address').css('marginTop', 0);
            }
        },
        previewInit: function() {
            var self = this;
            $('a.listing-preview').on('click', function (e) {
                e.preventDefault();
                var $self = $(this);
                $self.addClass('loading');
                var listing_id = $(this).data('id');
                var url = listdo_listing_opts.ajaxurl + '?action=listdo_preview_listing&listing_id=' + listing_id + '&security=' + listdo_listing_opts.ajax_nonce;
                
                $.get(url,function(data,status){
                    $.magnificPopup.open({
                        mainClass: 'apus-mfp-zoom-in apus-preview-listing',
                        items : {
                            src : data,
                            type: 'inline'
                        }
                    });
                    
                    $('.preview-content-inner').perfectScrollbar();
                    self.layzyLoadImage();
                    self.previewMap();
                    self.bookmarkInit();

                    self.initSlick($(".quickview-slick"));

                    $self.removeClass('loading');
                });
            });
        },
        submitForm: function() {
            var self = this;
            // menu price section
            $('.add-new-section-menu-price').on('click', function(e){
                e.preventDefault();
                var length = $('.menu-prices-field-wrapper .menu-prices-section-item').length;
                var html = $('.menu-prices-field-wrapper .menu-prices-section-item').eq(0).clone(true);
                html.find('.input-section-title').attr('name', "_job_menu_prices["+length+"][section_title]");
                html.find('.input-section-item-title').attr('name', "_job_menu_prices["+length+"][title][]");
                html.find('.input-section-item-price').attr('name', "_job_menu_prices["+length+"][price][]");
                html.find('.input-section-item-description').attr('name', "_job_menu_prices["+length+"][description][]");

                html.find('.menu-prices-section-item-title span').text( length + 1 );

                $('.menu-prices-field-wrapper').append(html);
            });

            $('.remove-section-menu-price').on('click', function(e) {
                e.preventDefault();
                var index = $('.menu-prices-field-wrapper .menu-prices-section-item').last().index();
                if ( index > 0 ) {
                    $('.menu-prices-field-wrapper .menu-prices-section-item').eq(index).remove();
                }
            });
            
            // menu price section item
            $('body').on('click', '.add-new-menu-price', function(e){
                e.preventDefault();
                var parent = $(this).parent();
                var length = $('.menu-prices-section-item-wrapper .menu-prices-item', parent).length;
                var html = $('.menu-prices-item', parent).eq(0).clone(true);
                html.find('.group-field-item-title span').text( length + 1 );

                parent.find('.menu-prices-section-item-wrapper').append( html );
            });
            $('body').on('click', '.remove-menu-price', function(e){
                e.preventDefault();
                var parent = $(this).parent();

                var index = $('.menu-prices-item', parent).last().index();
                if ( index > 0 ) {
                    $('.menu-prices-item', parent).eq(index).remove();
                }
            });

            var number_increment = 0;
            $( ".repeate_field_add_row" ).on('click', function(){

                var $wrap     = $(this).closest('.wc-job-manager-repeater-rows');
                var max_index = 0;
                $wrap.find('input.repeater-row-index').each(function(){
                    if ( parseInt( $(this).val() ) > max_index ) {
                        max_index = parseInt( $(this).val() );
                    }
                });
                var html = $(this).data('row').replace( /%%repeater-row-index%%/g, max_index + 1 );

                var html_e = $(html);


                $(this).closest('.wc-job-manager-repeater-rows').find('.repeater-fields-rows').append( html_e );

                if (isAdvancedUpload) {

                    var droppedFiles = false;
                    $(this).closest('.wc-job-manager-repeater-rows').find('.label-can-drag', ).each(function(){
                        var label_self = $(this);
                        label_self.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                        }).on('dragover dragenter', function() {
                            label_self.addClass('is-dragover');
                        }).on('dragleave dragend drop', function() {
                            label_self.removeClass('is-dragover');
                        }).on('drop', function(e) {
                            droppedFiles = e.originalEvent.dataTransfer.files;
                            label_self.parent().find('input[type="file"]').prop('files', droppedFiles).trigger('change');
                        });
                    });
                }

                $(this).closest('.wc-job-manager-repeater-rows').find('input[type="file"]').on('change', function() {
                    var preview = $(this).closest('.field').find('.job-manager-uploaded-files');
                    preview.html('<span class="job-manager-uploaded-file-preview"></span>');
                    var image_parent = preview.find('.job-manager-uploaded-file-preview');
                    self.imagesPreview(this, image_parent);
                    image_parent.append('<a class="job-manager-remove-uploaded-file-logo" href="#"><i class="fa fa-close" aria-hidden="true"></i></a>');
                    $(preview).css("display","block");
                });
                
                number_increment = number_increment + 1;
                return false;
            });


            $(document).on('click', '.delete-repeat-row', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-field').remove();
            } );


            // hours operation
            $('.add-new-hour').on('click', function(e){
                e.preventDefault();
                var parent = $(this).closest('.enter-hours-content');
                var length = parent.find('.enter-hours-item-inner').length;
                var html = parent.find('.enter-hours-item-inner').eq(0).clone(true);
                

                parent.find('.enter-hours-wrapper').append(html);
            });

            $('.remove-hour').on('click', function(e) {
                e.preventDefault();
                var parent = $(this).closest('.enter-hours-content');
                var index = parent.find('.enter-hours-item-inner').last().index();
                if ( index > 0 ) {
                    parent.find('.enter-hours-item-inner').eq(index).remove();
                }
            });
            

            // Regions
            $('body').on('change', 'select.select-field-region', function(){
                var val = $(this).val();
                var next = $(this).data('next');
                var main_select = 'select.select-field-region' + next;
                if ( $(main_select).length > 0 ) {
                    $(main_select).parent().addClass('loading');
                    $(main_select).prop('disabled', true);
                    $( main_select ).val('');
                    $(main_select).trigger('change');

                    var parent = $(main_select).parent();
                    var name = $(this).attr('name');
                    var placeholder = $('.select-field-region'+next).data('placeholder');

                    if ( val ) {
                        $.ajax({
                            url: listdo_listing_opts.ajaxurl,
                            type:'POST',
                            dataType: 'html',
                            data:{
                                'action': 'listdo_process_change_region',
                                'region_parent': val,
                                'next': next,
                                'name': name,
                                'security': listdo_listing_opts.ajax_nonce,
                                'placeholder': placeholder,
                            }
                        }).done(function(data) {
                            $(main_select).parent().removeClass('loading');
                            
                            parent.html(data);
                            if ( $.isFunction( $.fn.select2 ) ) {
                                
                                var select2_args = job_manager_select2_args;
                                select2_args['allowClear']              = true;
                                select2_args['minimumResultsForSearch'] = 10;
                                $( main_select ).select2(select2_args);
                            }
                            
                        });
                    } else {
                        var html = '';
                        if ( name == '_job_regions[]' || name == 'job_regions[]' ) {
                            html += '<label>' + listdo_listing_opts.region_labels[next] +'</label>';
                        }
                        
                        html += '<select class="select-field-region select-field-region'+next+'" data-next="'+ (next + 1)+'" autocomplete="off" name="'+name+'" data-placeholder="'+placeholder+'">';
                        html += '<option value="">'+placeholder+'</option>';
                        html += '</select>';
                        parent.html(html);
                        $(main_select).parent().removeClass('loading');
                        
                        if ( $.isFunction( $.fn.select2 ) ) {
                            var select2_args = job_manager_select2_args;
                            select2_args['allowClear']              = true;
                            select2_args['minimumResultsForSearch'] = 10;
                            $( main_select ).select2(select2_args);
                        }
                    }
                }
            });

            // Category
            $('body').on('change', 'select#job_c_category', function(){
                if ( $('#job_c_type').length > 0 ) {
                    var main_select = $('#job_c_type');
                    var val = $(this).val();
                    var main_con = main_select.parent();
                    $(main_con).addClass('loading');

                    var placeholder = '';
                    if ( main_select.data('placeholder') !== 'undefined' ) {
                        placeholder = main_select.data('placeholder');
                    }
                    $.ajax({
                        url: listdo_listing_opts.ajaxurl,
                        type:'POST',
                        dataType: 'html',
                        data:{
                            'action': 'listdo_process_change_category',
                            'category_parent': val,
                            'security': listdo_listing_opts.ajax_nonce,
                            'name': main_select.attr('name'),
                            'id': main_select.attr('id'),
                            'placeholder': placeholder,
                        }
                    }).done(function(data) {
                        $(main_con).removeClass('loading');
                        if ( $.isFunction( $.fn.select2 ) ) {
                            main_con.find('#job_c_type').select2("close");
                        }
                        main_con.html(data);
                        if ( $.isFunction( $.fn.select2 ) ) {
                            var select2_args = job_manager_select2_args;
                            select2_args['allowClear']              = true;
                            select2_args['minimumResultsForSearch'] = 10;
                            main_con.find('#job_c_type').select2(select2_args);
                        }
                        
                    });
                }
            });

            // Category
            $('body').on('change', 'select#job_category', function(){
                if ( $('.fieldset-job_amenities').length > 0 ) {
                    var val = $(this).val();
                    change_category_amenities($(this), val);
                }
            });
            $('body').on('change', '.job-manager-term-checklist-job_category input[name^=job_category]', function(){
                if ( $('.fieldset-job_amenities').length > 0 ) {
                    var val = [];
                    $.each($(".job-manager-term-checklist-job_category input[name^=job_category]:checked"), function(){            
                        val.push($(this).val());
                    });
                    change_category_amenities($(this), val);
                }
            });
            var change_category_amenities = function($this, val) {
                var main_select = $('.fieldset-job_amenities');
                var job_id = $this.closest('form').find('input[name=job_id]').val();
                $(main_select).addClass('loading');

                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'html',
                    data:{
                        'action': 'listdo_process_change_category_amenity',
                        'category_parent': val,
                        'job_id': job_id,
                        'security': listdo_listing_opts.ajax_nonce,
                    }
                }).done(function(data) {
                    $(main_select).removeClass('loading');
                    
                    if ( $.isFunction( $.fn.select2 ) ) {
                        main_select.find('select').select2("close");
                    }

                    main_select.find('div.field').replaceWith(data);
                    
                    if ( $.isFunction( $.fn.select2 ) ) {
                        var select2_args = job_manager_select2_args;
                        select2_args['allowClear']              = true;
                        select2_args['minimumResultsForSearch'] = 10;
                        main_select.find('select').select2(select2_args);
                    }

                });
            }

            var isAdvancedUpload = function() {
                var div = document.createElement('div');
                return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
            }();

            if (isAdvancedUpload) {

                var droppedFiles = false;
                $('.label-can-drag').each(function(){
                    var label_self = $(this);
                    label_self.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }).on('dragover dragenter', function() {
                        label_self.addClass('is-dragover');
                    }).on('dragleave dragend drop', function() {
                        label_self.removeClass('is-dragover');
                    }).on('drop', function(e) {
                        droppedFiles = e.originalEvent.dataTransfer.files;
                        label_self.parent().find('input[type="file"]').prop('files', droppedFiles).trigger('change');
                    });
                });
            }


            // file attachments
            $('label.file-logo').on('click', function(){
                $(this).closest('.upload-logo').find('input[type="file"]').trigger('click');
            });
            $('.upload-logo input[type="file"]').on('change', function() {
                var preview = $(this).parent().parent().find('.job-manager-uploaded-files');
                preview.html('');
                self.imagesPreview(this, preview);
                preview.append('<a class="job-manager-remove-uploaded-file-logo" href="#"><i class="fa fa-close" aria-hidden="true"></i></a>');
                $(preview).css("display","block");
            });
            $(document).on('click', '.job-manager-remove-uploaded-file-logo', function(e) {
                e.preventDefault();
                $(this).closest('.job-manager-uploaded-files').html('');
                $(this).closest('.job-manager-uploaded-files').parent().find('.upload-logo input[type="file"]').val(null);
            });

            if (isAdvancedUpload) {

                var droppedFiles = false;
                $('.upload-logo').each(function(){
                    var label_self = $(this);
                    label_self.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }).on('dragover dragenter', function() {
                        label_self.addClass('is-dragover');
                    }).on('dragleave dragend drop', function() {
                        label_self.removeClass('is-dragover');
                    }).on('drop', function(e) {
                        droppedFiles = e.originalEvent.dataTransfer.files;
                        label_self.find('input[type="file"]').prop('files', droppedFiles).trigger('change');
                    });
                });
            }
        },
        editProfile: function() {
            var self = this;
            // user profile edit
            if ( $('#change-profile-form-birthday').length > 0 && $.isFunction( $.fn.datetimepicker )) {
                $('#change-profile-form-birthday').datetimepicker({
                    timepicker: false,
                    format: listdo_listing_opts.date_format,
                    'scrollInput': false
                });
            }
            $('form.change-profile-form').on('submit', function(e){
                e.preventDefault();
                var self_form = $(this);
                self_form.addClass('loading');
                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  self_form.serialize() + "&action=listdo_process_change_profile_form"
                }).done(function(data) {
                    self_form.removeClass('loading');
                    self_form.find('.msg').html(data.msg);
                });
            });
            // user change pass
            $('form.change-password-form').on('submit', function(e){
                e.preventDefault();
                var self_form = $(this);
                self_form.addClass('loading');
                $.ajax({
                    url: listdo_listing_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  self_form.serialize() + "&action=listdo_process_change_password"
                }).done(function(data) {
                    self_form.removeClass('loading');
                    self_form.find('.msg').html(data.msg);
                });
            });

            
            $( document.body ).on( 'click', '.job-manager-remove-uploaded-file', function() {
                $(this).closest( '.job-manager-uploaded-file' ).remove();
                return false;
            });
        }
    });

    $.apusThemeExtensions.listing = $.apusThemeCore.listing_init;

    jQuery(document).ready(function($){
        if ( $( 'div.job_listings' ).length > 0 ) {
            $( 'div.job_listings' ).triggerHandler( 'update_results', [ 1, false ] );
        }
    });
    
})(jQuery);