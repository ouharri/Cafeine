jQuery(window).on('load', function() {
  jQuery('#status').fadeOut();
  jQuery('#preloader').delay(350).fadeOut('slow');
  jQuery('body').delay(350).css({'overflow':'visible'});
})

jQuery(function($){
  "use strict";
  jQuery('.main-menu > ul').superfish({
    delay:       500,
    animation:   {opacity:'show',height:'show'},
    speed:       'fast'
  });
});

jQuery(function($){
  $( '.toggle-nav button' ).click( function(e){
    $( 'body' ).toggleClass( 'show-main-menu' );
    var element = $( '.sidenav' );
    classic_coffee_shop_trapFocus( element );
  });

  $( '.close-button' ).click( function(e){
    $( '.toggle-nav button' ).click();
    $( '.toggle-nav button' ).focus();
  });
  $( document ).on( 'keyup',function(evt) {
    if ( $( 'body' ).hasClass( 'show-main-menu' ) && evt.keyCode == 27 ) {
      $( '.toggle-nav button' ).click();
      $( '.toggle-nav button' ).focus();
    }
  });
});

function classic_coffee_shop_trapFocus( element, namespace ) {
  var classic_coffee_shop_focusableEls = element.find( 'a, button' );
  var classic_coffee_shop_firstFocusableEl = classic_coffee_shop_focusableEls[0];
  var classic_coffee_shop_lastFocusableEl = classic_coffee_shop_focusableEls[classic_coffee_shop_focusableEls.length - 1];
  var KEYCODE_TAB = 9;

  classic_coffee_shop_firstFocusableEl.focus();

  element.keydown( function(e) {
    var isTabPressed = ( e.key === 'Tab' || e.keyCode === KEYCODE_TAB );

    if ( !isTabPressed ) { 
      return;
    }

    if ( e.shiftKey ) /* shift + tab */ {
      if ( document.activeElement === classic_coffee_shop_firstFocusableEl ) {
        classic_coffee_shop_lastFocusableEl.focus();
        e.preventDefault();
      }
    } else /* tab */ {
      if ( document.activeElement === classic_coffee_shop_lastFocusableEl ) {
        classic_coffee_shop_firstFocusableEl.focus();
        e.preventDefault();
      }
    }
  });
}

jQuery(document).ready(function() {
  jQuery('.ftr-4-box h5').each(function(index, element) {
    var heading = jQuery(element);
    var word_array, last_word, first_part;

    word_array = heading.html().split(/\s+/); // split on spaces
    last_word = word_array.pop();             // pop the last word
    first_part = word_array.join(' ');        // rejoin the first words together

    heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
  });
});

jQuery(document).ready(function() { 
  jQuery('#catsliderarea .owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    nav:true,
    dots:false,
    smartSpeed:250,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:1
      },
      1000:{
        items:1
      }
    }
  })
});


jQuery(document).ready(function() { 
  jQuery('#product_cat_slider .owl-carousel').owlCarousel({
    loop:true,
    margin:25,
    autoplay:true,
    nav:false,
    dots:true,
    smartSpeed:250,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:2
      },
      1000:{
        items:3
      }
    }
  })
});