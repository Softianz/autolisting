(function ($) {
    "use strict";
    
    if (!$.apusThemeExtensions)
        $.apusThemeExtensions = {};
    
    function ApusThemeCore() {
        var self = this;
        // self.init();
    };

    ApusThemeCore.prototype = {
        /**
         *  Initialize
         */
        init: function() {
            var self = this;
            if ( self.target_html == null ) {
                self.target_html = $('#apus_login_register_form_wrapper').html();
                $('#apus_login_register_form_wrapper').html('');
            }
            // slick init
            self.initSlick($("[data-carousel=slick]"));

            // Unveil init
            setTimeout(function(){
                self.layzyLoadImage();
            }, 500);

            // isoto
            self.initIsotope();
            self.initCounterUp();

            setTimeout(function(){
                self.changeHeaderMarginTop();
            }, 50);
            $(window).resize(function(){
                setTimeout(function(){
                    self.changeHeaderMarginTop();
                }, 50);
            });
            // Sticky Header
            self.initHeaderSticky('main-sticky-header');

            // back to top
            self.backToTop();

            // popup image
            self.popupImage();

            self.SharePopup();

            self.preloadSite();
            
            self.timerCountdown();

            $('[data-toggle="tooltip"]').tooltip();
            
            self.initMobileMenu();

            self.userLoginRegister();

            self.loadExtension();

            $(document.body).on('click', '.nav [data-toggle="dropdown"]', function(e){
                e.preventDefault();
                if ( this.href && this.href != '#' ){
                    if ( this.target && this.target == '_blank' ) {
                        window.open(this.href, '_blank');
                    } else {
                        window.location.href = this.href;
                    }
                }
            });

            setTimeout(function(){
                self.changePaddingTopMobileContent();    
            }, 50);
            $(window).resize(function(){
                self.changePaddingTopMobileContent();
            });

            $(window).load(function(){
                self.recaptchaCallback();
            });
        },
        init_elementor: function() {
            var self = this;
            self.initSlick($("[data-carousel=slick]"));

            // Unveil init
            setTimeout(function(){
                self.layzyLoadImage();
            }, 500);
        },
        target_html: null,

        changePaddingTopMobileContent: function() {
            if ($(window).width() < 1200) {
                var header_h = $('#apus-header-mobile').outerHeight();
                $('#apus-main-content').css({ 'padding-top': header_h });
                $('.listings-filter-wrapper').css({ 'top': header_h });
                $('.listings-filter-wrapper').css({ 'height': 'calc(100% - ' + header_h+ 'px)' });
            } else {
                if ( ! $('#apus-listing-map').is('.fix-map') ) {
                    $('#apus-main-content').css({ 'padding-top': 0 });
                }
            }
        },
        /**
         *  Extensions: Load scripts
         */
        loadExtension: function() {
            var self = this;
            
            if ($.apusThemeExtensions.shop) {
                $.apusThemeExtensions.shop.call(self);
            }
            
            if ($.apusThemeExtensions.listing) {
                $.apusThemeExtensions.listing.call(self);
            }
        },
        initSlick: function(element) {
            var self = this;
            element.each( function(){
                if ( $(this).hasClass('slick-initialized') ) {
                    $(this).slick( 'refresh' );
                } else {
                    var config = {
                        arrows: $(this).data( 'nav' ),
                        dots: $(this).data( 'pagination' ),
                        centerPadding: '0px',
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        prevArrow:"<button type='button' class='slick-arrow slick-prev pull-left'><i class='flaticon-left' aria-hidden='true'></i></span><span class='textnav'>"+listdo_opts.previous+"</span></button>",
                        nextArrow:"<button type='button' class='slick-arrow slick-next pull-right'><span class='textnav'>"+listdo_opts.next+"</span><i class='flaticon-right' aria-hidden='true'></i></button>",
                    };
                
                    var slick = $(this);
                    if( $(this).data('items') ){
                        config.slidesToShow = $(this).data( 'items' );
                        config.slidesToScroll = $(this).data( 'items' );
                    }
                    if( $(this).data('infinite') ){
                        config.infinite = $(this).data( 'infinite' );
                    }
                    if( $(this).data('autoplay') ){
                        config.autoplay = true;
                        config.autoplaySpeed = 2500;
                    }
                    if( $(this).data('vertical') ){
                        config.vertical = true;
                    }
                    if( $(this).data('rows') ){
                        config.rows = $(this).data( 'rows' );
                    }
                    if( $(this).data('asnavfor') ){
                        config.asNavFor = $(this).data( 'asnavfor' );
                    }
                    if( $(this).data('slidestoscroll') ){
                        config.slidesToScroll = $(this).data( 'slidestoscroll' );
                    }
                    if( $(this).data('focusonselect') ){
                        config.focusOnSelect = $(this).data( 'focusonselect' );
                    }

                    if( $(this).data('centermode') ){
                        config.centerMode = $(this).data('centermode');
                    }
                    
                    if ($(this).data('desktop')) {
                        var desktop = $(this).data('desktop');
                    } else {
                        var desktop = config.items;
                    }

                    if ($(this).data('smalldesktop')) {
                        var smalldesktop = $(this).data('smalldesktop');
                    } else {
                        if ($(this).data('desktop')) {
                            var smalldesktop = $(this).data('desktop');
                        } else{
                            var smalldesktop = config.items;
                        }
                    }

                    if ($(this).data('medium')) {
                        var medium = $(this).data('medium');
                    } else {
                        var medium = config.items;
                    }
                    if ($(this).data('smallmedium')) {
                        var smallmedium = $(this).data('smallmedium');
                    } else {
                        var smallmedium = 2;
                    }
                    if ($(this).data('extrasmall')) {
                        var extrasmall = $(this).data('extrasmall');
                    } else {
                        var extrasmall = 1;
                    }
                    if ($(this).data('smallest')) {
                        var smallest = $(this).data('smallest');
                    } else {
                        var smallest = 1;
                    }
                    config.responsive = [
                        {
                            breakpoint: 414,
                            settings: {
                                slidesToShow: smallest,
                                slidesToScroll: smallest,
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: extrasmall,
                                slidesToScroll: extrasmall,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: smallmedium,
                                slidesToScroll: smallmedium
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: medium,
                                slidesToScroll: medium
                            }
                        },
                        {
                            breakpoint: 1281,
                            settings: {
                                slidesToShow: smalldesktop,
                                slidesToScroll: smalldesktop
                            }
                        },
                        {
                            breakpoint: 1700,
                            settings: {
                                slidesToShow: desktop,
                                slidesToScroll: desktop
                            }
                        }
                    ];
                    if ( $('html').attr('dir') == 'rtl' ) {
                        config.rtl = true;
                    }

                    $(this).slick( config );
                }
            } );

            // Fix slick in bootstrap tabs
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                var $slick = $("[data-carousel=slick]", target);

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });
        },
        layzyLoadImage: function() {
            $(window).off('scroll.unveil resize.unveil lookup.unveil');
            var $images = $('.image-wrapper:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.image-wrapper').first().addClass('image-loaded');
                        $(this).removeAttr('data-src');
                        $(this).removeAttr('data-srcset');
                        $(this).removeAttr('data-sizes');
                    });
                });
            }

            var $images = $('.product-image:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.product-image').first().addClass('image-loaded');
                    });
                });
            }
        },
        initCounterUp: function() {
            if($('.counterUp').length > 0){
                $('.counterUp').counterUp({
                    delay: 10,
                    time: 800
                });
            }
        },
        initIsotope: function() {
            $('.isotope-items').each(function(){  
                var $container = $(this);
                
                $container.imagesLoaded( function(){
                    $container.isotope({
                        itemSelector : '.isotope-item',
                        transformsEnabled: true,         // Important for videos
                        masonry: {
                            columnWidth: $container.data('columnwidth')
                        }
                    }); 
                });
            });

            /*---------------------------------------------- 
             *    Apply Filter        
             *----------------------------------------------*/
            $('.isotope-filter li a').on('click', function(){
               
                var parentul = $(this).parents('ul.isotope-filter').data('related-grid');
                $(this).parents('ul.isotope-filter').find('li a').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter'); 
                $('#'+parentul).isotope({ filter: selector }, function(){ });
                
                return(false);
            });
        },
        changeHeaderMarginTop: function() {
            if ($(window).width() > 1199) {
                if ( $('#apus-header').length > 0 ) {
                    var header_height_dk = $('#apus-header').outerHeight();
                    $('.header_transparent .apus-breadscrumb').css({'padding-top': header_height_dk});
                    $('.header-v1 + #apus-main-content .apus-breadscrumb').css({'padding-top': header_height_dk});
                    $('.header_transparent .detail-top').css({'padding-top': header_height_dk});
                }
            }
        },
        initHeaderSticky: function(main_sticky_class) {
            if ( typeof Waypoint !== 'undefined' ) {
                if ( $('.' + main_sticky_class) && typeof Waypoint.Sticky !== 'undefined' ) {
                    var opts = {
                        element: $('.' + main_sticky_class)[0],
                        wrapper: '<div class="main-sticky-header-wrapper">',
                        offset: '-10px',
                        stuckClass: 'sticky-header'
                    };
                    var sticky = new Waypoint.Sticky(opts);
                }
            }
        },
        headerSticky: function(main_sticky, _menu_action) {
            if( $(document).scrollTop() > _menu_action ){
                main_sticky.addClass('sticky-header');
            }else{
                main_sticky.removeClass('sticky-header');
            }
        },
        backToTop: function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 400) {
                    $('#back-to-top').addClass('active');
                } else {
                    $('#back-to-top').removeClass('active');
                }
            });
            $('#back-to-top').on('click', function () {
                $('html, body').animate({scrollTop: '0px'}, 800);
                return false;
            });
        },
        popupImage: function() {
            // popup
            $(".popup-image").magnificPopup({type:'image'});
            $('.popup-video').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });

            $('.widget-gallery').each(function(){
                var tagID = $(this).attr('id');
                $('#' + tagID).magnificPopup({
                    delegate: '.popup-image-gallery',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                    }
                });
            });
        },

        SharePopup: function() {
            var self = this;
            $(document).on('click', '.show-social, .share-popup', function(){
                $.magnificPopup.open({
                    mainClass: 'popup-wrapper-normal',
                    closeBtnInside:true,
                    closeMarkup:'<button type="button" class="mfp-close"><i class="ti-close"></i></button>',
                    items    : {
                        src : $('.content-share-social').html(),
                        type: 'inline'
                    },
                });
            });
        },

        preloadSite: function() {
            // preload page
            if ( $('body').hasClass('apus-body-loading') ) {
                setTimeout(function(){
                    $('body').removeClass('apus-body-loading');
                    $('.apus-page-loading').fadeOut(100);
                }, 200);
            }
        },
        initMobileMenu: function() {

            // stick mobile
            var self = this;
            var main_sticky = $('.header-mobile');
            setTimeout(function(){
                if ( main_sticky.length > 0 ){
                    if ($(window).width() < 992) {
                        var _menu_action = 0;
                        $(window).scroll(function(event) {
                            self.headerSticky(main_sticky, _menu_action);
                        });
                        self.headerSticky(main_sticky, _menu_action);
                    }
                }
            }, 50);

            $(window).resize(function(){
                setTimeout(function(){
                    if ( main_sticky.length > 0 ){
                        if ($(window).width() < 992) {
                            var _menu_action = 0;
                            $(window).scroll(function(event) {
                                self.headerSticky(main_sticky, _menu_action);
                            });
                            self.headerSticky(main_sticky, _menu_action);
                        }
                    }
                }, 50);
            });

            // mobile menu
            $('.btn-offcanvas, .btn-toggle-canvas').on('click', function (e) {
                e.stopPropagation();
                $('.apus-offcanvas').toggleClass('active');           
                $('.over-dark').toggleClass('active');         
            });
            $('body').on('click', function() {
                if ($('.apus-offcanvas').hasClass('active')) {
                    $('.apus-offcanvas').toggleClass('active');
                    $('.over-dark').toggleClass('active');
                }
            });
            $('.apus-offcanvas').on('click', function(e) {
                e.stopPropagation();
            });

            $("#main-mobile-menu .icon-toggle").on('click', function(){
                $(this).parent().find('> .sub-menu').slideToggle();
                if ( $(this).find('i').hasClass('ti-angle-down') ) {
                    $(this).find('i').removeClass('ti-angle-down').addClass('ti-angle-up');
                } else {
                    $(this).find('i').removeClass('ti-angle-up').addClass('ti-angle-down');
                }
                return false;
            } );
            $('.apus-offcanvas-body').perfectScrollbar();

            // show mini cart
            $('.apus-top-cart .mini-cart').on('click', function (e) {
                e.stopPropagation();
                $('.apus-top-cart > .dropdown-menu').toggleClass('active');         
            });

            // sidebar mobile
            if ($(window).width() < 992) {
                $('.sidebar-right, .sidebar-left').perfectScrollbar();
            }
            $(window).resize(function(){
                if ($(window).width() < 992) {
                    $('.sidebar-right, .sidebar-left').perfectScrollbar();
                }
            });
            
            $('body').on('click', '.mobile-sidebar-btn', function(){
                if ( $('.sidebar-left').length > 0 ) {
                    $('.sidebar-left').toggleClass('active');
                } else if ( $('.sidebar-right').length > 0 ) {
                    $('.sidebar-right').toggleClass('active');
                }
                $('.mobile-sidebar-panel-overlay').toggleClass('active');
            });
            $('body').on('click', '.mobile-sidebar-panel-overlay, .close-sidebar-btn', function(){
                if ( $('.sidebar-left').length > 0 ) {
                    $('.sidebar-left').removeClass('active');
                } else if ( $('.sidebar-right').length > 0 ) {
                    $('.sidebar-right').removeClass('active');
                }
                $('.mobile-sidebar-panel-overlay').removeClass('active');
            });
        },
        setCookie: function(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires+";path=/";
        },
        getCookie: function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        },
        recaptchaCallback: function() {
            var recaptchas = document.getElementsByClassName("ga-recaptcha");
            for(var i=0; i<recaptchas.length; i++) {
                var recaptcha = recaptchas[i];
                var sitekey = recaptcha.dataset.sitekey;

                grecaptcha.render(recaptcha, {
                    'sitekey' : sitekey
                });
            }
        },
        loginRegisterPopup: function(target) {
            var self = this;
            $.magnificPopup.open({
                mainClass: 'apus-mfp-zoom-in',
                items    : {
                    src : self.target_html,
                    type: 'inline'
                },
                callbacks: {
                    open: function() {
                        $(target).trigger('click');
                        $('.apus_login_register_form .nav-tabs li').removeClass('active');
                        $(target).parent().addClass('active');
                        var id = $(target).attr('href');
                        $('.apus_login_register_form .tab-pane').removeClass('active');
                        $(id).addClass('active').addClass('in');

                        $('#apus_forgot_password_form').hide();
                        $('#apus_login_form').show();

                        $('.apus_login_register_form').addClass('animated fadeInDown');

                        self.recaptchaCallback();
                    }
                }

            });
            
        },
        userLoginRegister: function() {
            var self = this;
            // login/register
            
            $('.apus-user-login').on('click', function(){
                var target = $(this).attr('href');
                
                self.loginRegisterPopup(target);
                return false;
            });
            
            $('.apus-user-register').on('click', function(){
                var target = $(this).attr('href');
                
                self.loginRegisterPopup(target);
                return false;
            });

            $('.account-sign-in a:not(.logout-link), .must-log-in a').on('click', function(e){
                e.preventDefault();
                var target = $('.apus-user-login').attr('href');
                self.loginRegisterPopup(target);
                return false;
            });
            $('body').on('click', '.apus_login_register_form .mfp-close', function(){
                $.magnificPopup.close();
            });
            
            // sign in proccess
            $('body').on('submit', 'form.apus-login-form', function(){
                var $this = $(this);
                $('.alert', this).remove();
                $this.addClass('loading');
                $.ajax({
                    url: listdo_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data:  $(this).serialize()+"&action=apus_ajax_login"
                }).done(function(data) {
                    $this.removeClass('loading');
                    if ( data.loggedin ) {
                        $this.prepend( '<div class="alert alert-info">' + data.msg + '</div>' );
                        location.reload(); 
                    } else {
                        $this.prepend( '<div class="alert alert-warning">' + data.msg + '</div>' );
                    }
                });
                return false; 
            } );
            $('body').on('click', '.back-link', function(e){
                e.preventDefault();
                $('.form-container').hide();
                $($(this).attr('href')).show(); 
                return false;
            } );

             // lost password in proccess
            $('body').on('submit', 'form.forgotpassword-form', function(){
                var $this= $(this);
                $('.alert', this).remove();
                $this.addClass('loading');
                $.ajax({
                  url: listdo_opts.ajaxurl,
                  type:'POST',
                  dataType: 'json',
                  data:  $(this).serialize()+"&action=apus_ajax_forgotpass"
                }).done(function(data) {
                     $this.removeClass('loading');
                    if ( data.loggedin ) {
                        $this.prepend( '<div class="alert alert-info">'+data.msg+'</div>' );
                        location.reload(); 
                    } else {
                        $this.prepend( '<div class="alert alert-warning">'+data.msg+'</div>' );
                    }
                });
                return false; 
            } );
            $('body').on('click', '#apus_forgot_password_form form .btn-cancel', function(e){
                e.preventDefault();
                $('#apus_forgot_password_form').hide();
                $('#apus_login_form').show();
            } );

            // register
            $('body').on('submit', 'form.apus-register-form', function(){
                var $this= $(this);
                $('.alert', this).remove();
                $this.addClass('loading');
                $.ajax({
                  url: listdo_opts.ajaxurl,
                  type:'POST',
                  dataType: 'json',
                  data:  $(this).serialize()+"&action=apus_ajax_register"
                }).done(function(data) {
                    $this.removeClass('loading');
                    if ( data.loggedin ) {
                        $this.prepend( '<div class="alert alert-info">'+data.msg+'</div>' );
                        location.reload();
                    } else {
                        $this.prepend( '<div class="alert alert-warning">'+data.msg+'</div>' );
                        grecaptcha.reset();
                    }
                });
                return false;
            } );
            
            $(document).on('click', '.listdo-resend-approve-account-btn', function(e) {
                e.preventDefault();
                var $this = $(this),
                    $container = $(this).parent();
                $this.addClass('loading');
                $.ajax({
                    url: listdo_opts.ajaxurl,
                    type:'POST',
                    dataType: 'json',
                    data: {
                        action: 'listdo_ajax_resend_approve_account',
                        login: $this.data('login'),
                    }
                }).done(function(data) {
                    $this.removeClass('loading');
                    if ( data.status ) {
                        $container.html( data.msg );
                    } else {
                        $container.html( data.msg );
                    }
                });
            });
        },
        timerCountdown: function(){
            $('[data-time="timmer"]').each(function(index, el) {
                var $this = $(this);
                var $date = $this.data('date').split("-");
                $this.apusCountDown({
                    TargetDate:$date[0]+"/"+$date[1]+"/"+$date[2]+" "+$date[3]+":"+$date[4]+":"+$date[5],
                    DisplayFormat:"<div class=\"times\"><div class=\"day\">%%D%% "+ listdo_opts.days +"</div><div class=\"hours\">%%H%% "+ listdo_opts.hours +"</div><div class=\"minutes\">%%M%% "+ listdo_opts.mins +"</div><div class=\"seconds\">%%S%% "+ listdo_opts.secs +"</div></div>",
                    FinishMessage: "",
                });
            });
        }
    }

    $.apusThemeCore = ApusThemeCore.prototype;
    
    
    $.fn.wrapStart = function(numWords){
        return this.each(function(){
            var $this = $(this);
            var node = $this.contents().filter(function(){
                return this.nodeType == 3;
            }).first(),
            text = node.text().trim(),
            first = text.split(' ', 1).join(" ");
            if (!node.length) return;
            node[0].nodeValue = text.slice(first.length);
            node.before('<b>' + first + '</b>');
        });
    };
    
    $(document).ready(function() {
        // Initialize script
        var apusthemecore_init = new ApusThemeCore();
        apusthemecore_init.init();

    });
    
    jQuery(window).on("elementor/frontend/init", function() {
        
        var apusthemecore_init = new ApusThemeCore();

        // General element
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_brands.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_features_box.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_posts.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_testimonials.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        // woo elements
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_listings_category_list_banner.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_listings.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_listings_packages.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

    });

})(jQuery);
