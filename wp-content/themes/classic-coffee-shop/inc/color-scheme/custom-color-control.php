<?php 

$classic_coffee_shop_color_scheme_one = get_theme_mod('classic_coffee_shop_color_scheme_one');

$classic_coffee_shop_color_scheme_css = "";

if($classic_coffee_shop_color_scheme_one != false){

  $classic_coffee_shop_color_scheme_css .='.pagemore a:hover, .woocommerce ul.products li.product .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, #commentform input#submit:hover, .main-nav ul ul a:hover,.rsvp_button a:hover,span.onsale,#product_cat_slider button.owl-dot.active,#footer,.main-nav ul ul,#sidebar input.search-submit, #footer input.search-submit, form.woocommerce-product-search button{';

  $classic_coffee_shop_color_scheme_css .='background: '.esc_attr($classic_coffee_shop_color_scheme_one).';';

  $classic_coffee_shop_color_scheme_css .='}';

  $classic_coffee_shop_color_scheme_css .='.social-icons,.logo,.rsvp_button a:hover,.listarticle, aside.widget,#sidebar input[type="text"], #sidebar input[type="search"], #footer input[type="search"],#sidebar .tagcloud a,.rsvp_button a{';

  $classic_coffee_shop_color_scheme_css .='border-color: '.esc_attr($classic_coffee_shop_color_scheme_one).';';

  $classic_coffee_shop_color_scheme_css .='}';

  $classic_coffee_shop_color_scheme_css .='a,h1.site-title a,h1, h2, h3, h4, h5, h6,.main-nav a:hover,.woocommerce ul.products li.product .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce a.button, .woocommerce button.button, .woocommerce #respond input#submit, #commentform input#submit,h3.widget-title,#sidebar .tagcloud a,.listarticle h2 a,.rsvp_button a{';

  $classic_coffee_shop_color_scheme_css .='color: '.esc_attr($classic_coffee_shop_color_scheme_one).';';

  $classic_coffee_shop_color_scheme_css .='}';
}