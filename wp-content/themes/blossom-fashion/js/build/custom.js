jQuery(document).ready(function ($) {

    var rtl;

    if (blossom_fashion_data.rtl == '1') {
        rtl = true;
    } else {
        rtl = false;
    }

    // banner slider
    $('#banner-slider').owlCarousel({
        loop: false,
        margin: 0,
        nav: true,
        items: 1,
        dots: false,
        autoplay: false,
        navText: '',
        rtl: rtl,
        lazyLoad: true,
        animateOut: blossom_fashion_data.animation,
    });

    // Shop Section slider
    $('.shop-slider').owlCarousel({
        nav: false,
        dots: true,
        rtl: rtl,
        lazyLoad: true,
        responsive: {
            0: {
                items: 1,
                margin: 15
            },
            768: {
                items: 3,
                margin: 15
            },
            1200: {
                items: 4,
                margin: 15
            },
            1440: {
                margin: 40,
                items: 4
            }
        }
    });

    // Bottom shop slider
    $('.bottom-shop-slider').owlCarousel({
        dots: false,
        nav: true,
        rtl: rtl,
        lazyLoad: true,
        responsive: {
            0: {
                items: 1,
                margin: 0
            },
            768: {
                items: 3,
                margin: 20
            },
            1025: {
                items: 4,
                margin: 22
            },
            1200: {
                items: 5,
                margin: 22
            }
        }
    });

    // instagram slider
    $('.instagram-section .popup-gallery').addClass('owl-carousel');
    $('.instagram-section .popup-gallery').owlCarousel({
        nav: true,
        dots: false,
        stagePadding: 180,
        loop: true,
        rtl: rtl,
        // lazyLoad     : true,
        responsive: {
            0: {
                items: 1,
                margin: 20,
                stagePadding: 60
            },
            768: {
                items: 2,
                margin: 20,
                stagePadding: 100
            },
            1025: {
                items: 4,
                margin: 20,
                stagePadding: 100
            },
            1300: {
                items: 6,
                margin: 20,
                stagePadding: 180
            }

        }
    });

    var winWidth = $(window).width();
    $('#site-navigation ul li.menu-item-has-children').find('> a').after('<button><i class="fa fa-angle-down"></i></button>');
    $('#site-navigation ul li button').on('click', function () {
        $(this).siblings('.sub-menu').slideToggle();
        $(this).toggleClass('active');
    });

    $('#toggle-button').on('click', function () {
        $('.main-navigation').toggleClass('open');
        $('body').toggleClass('menu-open');
        $('.main-navigation').animate({
            width: "toggle",
        });
    });

    $('.close-main-nav-toggle').on('click', function () {
        $('body').removeClass('menu-open');
        $('.main-navigation').removeClass('open');
        $('.main-navigation').animate({
            width: "toggle",
        });
    });

    $('.overlay').on('click', function () {
        $('body').removeClass('menu-open');
        $('.main-navigation').removeClass('open');
        $('.main-navigation').animate({
            width: "toggle",
        });
    });

    $('#toggle-button').on('click', function (event) {
        event.stopPropagation();
    });

    $('#site-navigation').on('click', function (event) {
        event.stopPropagation();
    });

    //for accessibility
    $('.main-navigation ul li a').on('focus', function () {
        $(this).parents('li').addClass('focused');
    }).on('blur', function () {
        $(this).parents('li').removeClass('focused');
    });

    //Header Search form show/hide
    $('.site-header .form-section').on('click', function (event) {
        event.stopPropagation();
    });
    $("#btn-search").on('click', function () {
        $(".site-header .form-holder").show("fast");
        $('body').addClass('search-active');
    });

    $('.btn-close-form').on('click', function () {
        $('.site-header  .form-holder').hide("fast");
        $('body').removeClass('search-active');
    });

    $(window).keyup(function (e) {
        if (e.key == 'Escape') {
            $('.site-header  .form-holder').hide("fast");
            $('body').removeClass('search-active');
        }
    });

    //Ajax for Add to Cart
    $('.btn-simple').on('click', function () {
        $(this).addClass('adding-cart');
        var product_id = $(this).attr('id');

        $.ajax({
            url: blossom_fashion_data.ajax_url,
            type: 'POST',
            data: 'action=blossom_fashion_add_cart_single&product_id=' + product_id,
            success: function (results) {
                $('#' + product_id).replaceWith(results);
            }
        }).done(function () {
            var cart = $('#cart-' + product_id).val();
            $('.cart .number').html(cart);
        });
    });

    //js for next/prev btn in single
    var headerHeight = $('.site-header').height();
    "use strict";

    var top = !1,
        bottom = !0;
    $(".site-header").waypoint(function (direction) {
        "down" == direction ? (bottom = !1, top || $(".single .post-navigation .nav-holder").show()) : (bottom = !0, $(".single .post-navigation .nav-holder").hide());
    }, {
        offset: -headerHeight
    }), $(".site-footer, .instagram-section, .bottom-shop-section").waypoint(function (direction) {
        "down" == direction ? (top = !0, $(".single .post-navigation .nav-holder").hide()) : (top = !1, bottom || $(".single .post-navigation .nav-holder").show());
    }, {
        offset: "100%"
    });

});