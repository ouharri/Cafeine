<?php
/**
 * Dynamic Styles
 * 
 * @package Blossom_Fashion
*/
if ( ! function_exists( 'blossom_fashion_dynamic_css' ) ) :

function blossom_fashion_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Montserrat' );
    $primary_fonts   = blossom_fashion_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Cormorant Garamond' );
    $secondary_fonts = blossom_fashion_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 16 );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Rufina', 'variant'=>'regular' ) );
    $site_title_fonts     = blossom_fashion_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 120 );
    
    $primary_color = get_theme_mod( 'primary_color', '#f1d3d3' );
    
    $rgb = blossom_fashion_hex2rgb( blossom_fashion_sanitize_hex_color( $primary_color ) );
     
    $custom_css = '';
    $custom_css .= '
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);' . '
    }
    
    /*Typography*/

    body,
    button,
    input,
    select,
    optgroup,
    textarea{
        font-family : ' . wp_kses_post( $primary_fonts['font'] ) . ';
        font-size   : ' . absint( $font_size ) . 'px;        
    }
    
    .site-title{
        font-size   : ' . absint( $site_title_font_size ) . 'px;
        font-family : ' . wp_kses_post( $site_title_fonts['font'] ) . ';
        font-weight : ' . esc_html( $site_title_fonts['weight'] ) . ';
        font-style  : ' . esc_html( $site_title_fonts['style'] ) . ';
    }
    
    /*Color Scheme*/
    a,
    .site-header .social-networks li a:hover,
    .site-title a:hover,
    .banner .text-holder .cat-links a:hover,
	.shop-section .shop-slider .item h3 a:hover,
	#primary .post .entry-header .cat-links a:hover,
	#primary .post .entry-header .entry-meta a:hover,
	#primary .post .entry-footer .social-networks li a:hover,
	.widget ul li a:hover,
	.widget_bttk_author_bio .author-bio-socicons ul li a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-meta a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:hover,
	.bottom-shop-section .bottom-shop-slider .item .product-category a:hover,
	.bottom-shop-section .bottom-shop-slider .item h3 a:hover,
	.instagram-section .header .title a:hover,
	.site-footer .widget ul li a:hover,
	.site-footer .widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.site-footer .widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.single .single-header .site-title:hover,
	.single .single-header .right .social-share .social-networks li a:hover,
	.comments-area .comment-body .fn a:hover,
	.comments-area .comment-body .comment-metadata a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .icon-holder,
	.page-template-contact .contact-details .contact-info-holder .col .text-holder h3 a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .social-networks li a:hover,
    #secondary .widget_bttk_description_widget .social-profile li a:hover,
    #secondary .widget_bttk_contact_social_links .social-networks li a:hover,
    .site-footer .widget_bttk_contact_social_links .social-networks li a:hover,
    .site-footer .widget_bttk_description_widget .social-profile li a:hover,
    .portfolio-sorting .button:hover,
    .portfolio-sorting .button.is-checked,
    .portfolio-item .portfolio-cat a:hover,
    .entry-header .portfolio-cat a:hover,
    .single-blossom-portfolio .post-navigation .nav-previous a:hover,
	.single-blossom-portfolio .post-navigation .nav-next a:hover, 
	.entry-content a:hover,
	.entry-summary a:hover,
	.page-content a:hover,
	.comment-content a:hover,
	.widget .textwidget a:hover{
		color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	.site-header .tools .cart .number,
	.shop-section .header .title:after,
	.header-two .header-t,
	.header-six .header-t,
	.header-eight .header-t,
	.shop-section .shop-slider .item .product-image .btn-add-to-cart:hover,
	.widget .widget-title:before,
	.widget .widget-title:after,
	.widget_calendar caption,
	.widget_bttk_popular_post .style-two li:after,
	.widget_bttk_popular_post .style-three li:after,
	.widget_bttk_pro_recent_post .style-two li:after,
	.widget_bttk_pro_recent_post .style-three li:after,
	.instagram-section .header .title:before,
	.instagram-section .header .title:after,
	#primary .post .entry-content .pull-left:after,
	#primary .page .entry-content .pull-left:after,
	#primary .post .entry-content .pull-right:after,
	#primary .page .entry-content .pull-right:after,
	.page-template-contact .contact-details .contact-info-holder h2:after,
    .widget_bttk_image_text_widget ul li .btn-readmore:hover,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:hover,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:hover,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover, 
    .widget_tag_cloud .tagcloud a:hover,
    .single #primary .post .entry-footer .tags a:hover,
 	#primary .post .entry-footer .tags a:hover,
 	.error-holder .text-holder .btn-home:hover,
 	.site-footer .widget_tag_cloud .tagcloud a:hover,
 	.site-footer .widget_bttk_author_bio .text-holder .readmore:hover,
 	.main-navigation ul li:after,
 	#primary .post .btn-readmore:hover,
 	.widget_bttk_author_bio .text-holder .readmore:hover,
 	.widget_bttk_image_text_widget ul li .btn-readmore:hover,
	 .widget_tag_cloud .tagcloud a:hover, 
	 .instagram-section .profile-link::before, 
	 .instagram-section .profile-link::after,	 
	 .widget_calendar table tbody td a{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}
    
    .banner .text-holder .cat-links a,
	#primary .post .entry-header .cat-links a,
	.widget_bttk_popular_post .style-two li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a,
	.widget_bttk_popular_post .style-three li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a,
	.page-header span,
	.page-template-contact .top-section .section-header span,
    .portfolio-item .portfolio-cat a,
    .entry-header .portfolio-cat a{
		border-bottom-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	.banner .text-holder .title a,
	.header-four .main-navigation ul li a,
	.header-four .main-navigation ul ul li a,
	#primary .post .entry-header .entry-title a,
    .portfolio-item .portfolio-img-title a{
		background-image: linear-gradient(180deg, transparent 96%, ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ' 0);
	}

	.widget_bttk_social_links ul li a:hover{
		border-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
		border-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	#primary .post .btn-readmore:hover{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	@media only screen and (min-width: 1025px){
		.main-navigation ul li:after{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
		}
	}

    @media screen and (max-width: 1024px) {
        #toggle-button, 
        .main-navigation ul, 
        .site-header .nav-holder .form-holder .search-form input[type="search"] {
            font-family: ' . esc_html( $secondary_fonts['font'] ) . ';
        }
	}
	
	@media only screen and (max-width:1024px) {
		.mobile-menu ul li a {
			background-image : linear-gradient(180deg, transparent 93%, ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ' ); 
	   }
	}
    
    /*Typography*/

	.main-navigation ul,
	.banner .text-holder .title,
	.top-section .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.shop-section .header .title,
	#primary .post .entry-header .entry-title,
	#primary .post .post-shope-holder .header .title,
	.widget_bttk_author_bio .title-holder,
	.widget_bttk_popular_post ul li .entry-header .entry-title,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title,
	.widget-area .widget_blossomthemes_email_newsletter_widget .text-holder h3,
	.bottom-shop-section .bottom-shop-slider .item h3,
	.page-title,
	#primary .post .entry-content blockquote,
	#primary .page .entry-content blockquote,
	#primary .post .entry-content .dropcap,
	#primary .page .entry-content .dropcap,
	#primary .post .entry-content .pull-left,
	#primary .page .entry-content .pull-left,
	#primary .post .entry-content .pull-right,
	#primary .page .entry-content .pull-right,
	#primary .post .entry-content h1, 
    #primary .page .entry-content h1, 
    #primary .post .entry-content h2, 
    #primary .page .entry-content h2, 
    #primary .post .entry-content h3, 
    #primary .page .entry-content h3, 
    #primary .post .entry-content h4, 
    #primary .page .entry-content h4, 
    #primary .post .entry-content h5, 
    #primary .page .entry-content h5, 
    #primary .post .entry-content h6, 
    #primary .page .entry-content h6
	.author-section .text-holder .title,
	.single .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.related-posts .title, .popular-posts .title,
	.comments-area .comments-title,
	.comments-area .comment-reply-title,
	.single .single-header .title-holder .post-title,
    .portfolio-text-holder .portfolio-img-title,
    .portfolio-holder .entry-header .entry-title,
    .related-portfolio-title, 
    .related-portfolio-title, .search .top-section .search-form input[type="search"], 
    .archive #primary .post-count, .search #primary .post-count, 
    .archive #primary .post .entry-header .entry-title, .archive #primary .blossom-portfolio .entry-title, .search #primary .search-post .entry-header .entry-title, 
    .widget_bttk_posts_category_slider_widget .carousel-title .title, 
    .archive.author .top-section .text-holder .author-title, 
    .search #primary .page .entry-header .entry-title, 
    .error-holder .text-holder h2, 
    .error-holder .recent-posts .title, 
    .error-holder .recent-posts .post .entry-header .entry-title, 
    .site-footer .widget_blossomthemes_email_newsletter_widget .text-holder h3{
		font-family: ' . esc_html( $secondary_fonts['font'] ) . ';
	}';
    
    if( blossom_fashion_is_woocommerce_activated() ) {
    	$custom_css .=' .woocommerce #secondary .widget_price_filter .ui-slider .ui-slider-range, 
        .woocommerce-checkout .woocommerce form.woocommerce-form-login input.button:hover, 
        .woocommerce-checkout .woocommerce form.checkout_coupon input.button:hover, 
        .woocommerce form.lost_reset_password input.button:hover, 
        .woocommerce .return-to-shop .button:hover, 
        .woocommerce #payment #place_order:hover{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}
        
        .woocommerce #secondary .widget .product_list_widget li .product-title:hover,
    	.woocommerce div.product .entry-summary .product_meta .posted_in a:hover,
    	.woocommerce div.product .entry-summary .product_meta .tagged_as a:hover{
			color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}
        
        .woocommerce-checkout .woocommerce .woocommerce-info,
        .woocommerce ul.products li.product .add_to_cart_button:hover,
        .woocommerce ul.products li.product .product_type_external:hover,
        .woocommerce ul.products li.product .ajax_add_to_cart:hover,
        .woocommerce ul.products li.product .added_to_cart:hover,
        .woocommerce div.product form.cart .single_add_to_cart_button:hover,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:hover,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:hover,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:hover,
        .woocommerce-cart #primary .page .entry-content table.shop_table td.actions .coupon input[type="submit"]:hover,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:hover{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}

    	.woocommerce div.product .product_title,
    	.woocommerce div.product .woocommerce-tabs .panel h2{
			font-family: ' . wp_kses_post( $secondary_fonts['font'] ) . ';
    	}';    
    }
    
    wp_add_inline_style( 'blossom-fashion-style', $custom_css );
}
endif;
add_action( 'wp_enqueue_scripts', 'blossom_fashion_dynamic_css', 99 );

/**
 * Function for sanitizing Hex color 
 */
function blossom_fashion_sanitize_hex_color( $color ){
	if ( '' === $color )
		return '';

    // 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;
}

/**
 * convert hex to rgb
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
*/
function blossom_fashion_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
