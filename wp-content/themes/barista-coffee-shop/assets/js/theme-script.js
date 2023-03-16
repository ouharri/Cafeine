function barista_coffee_shop_openNav() {
  jQuery(".sidenav").addClass('show');
}
function barista_coffee_shop_closeNav() {
  jQuery(".sidenav").removeClass('show');
}

( function( window, document ) {
  function barista_coffee_shop_keepFocusInMenu() {
    document.addEventListener( 'keydown', function( e ) {
      const barista_coffee_shop_nav = document.querySelector( '.sidenav' );

      if ( ! barista_coffee_shop_nav || ! barista_coffee_shop_nav.classList.contains( 'show' ) ) {
        return;
      }

      const elements = [...barista_coffee_shop_nav.querySelectorAll( 'input, a, button' )],
        barista_coffee_shop_lastEl = elements[ elements.length - 1 ],
        barista_coffee_shop_firstEl = elements[0],
        barista_coffee_shop_activeEl = document.activeElement,
        tabKey = e.keyCode === 9,
        shiftKey = e.shiftKey;

      if ( ! shiftKey && tabKey && barista_coffee_shop_lastEl === barista_coffee_shop_activeEl ) {
        e.preventDefault();
        barista_coffee_shop_firstEl.focus();
      }

      if ( shiftKey && tabKey && barista_coffee_shop_firstEl === barista_coffee_shop_activeEl ) {
        e.preventDefault();
        barista_coffee_shop_lastEl.focus();
      }
    } );
  }
  barista_coffee_shop_keepFocusInMenu();
} )( window, document );

var btn = jQuery('#button');

jQuery(window).scroll(function() {
  if (jQuery(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  jQuery('html, body').animate({scrollTop:0}, '300');
});

jQuery(document).ready(function() {
  var owl = jQuery('#top-slider .owl-carousel');
    owl.owlCarousel({
      margin: 0,
      nav: true,
      autoplay:true,
      autoplayTimeout:3000,
      autoplayHoverPause:true,
      autoHeight: true,
      loop: true,
      dots:false,
      navText : ['<i class="fa fa-lg fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-lg fa-chevron-right" aria-hidden="true"></i>'],
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 1
        },
        1024: {
          items: 1
      }
    }
  })
})

window.addEventListener('load', (event) => {
  jQuery(".loading").delay(2000).fadeOut("slow");
});

jQuery(window).scroll(function() {
  var data_sticky = jQuery('.main-header').attr('data-sticky');

  if (data_sticky == "true") {
    if (jQuery(this).scrollTop() > 1){
      jQuery('.main-header').addClass("stick_header");
    } else {
      jQuery('.main-header').removeClass("stick_header");
    }
  }
});
