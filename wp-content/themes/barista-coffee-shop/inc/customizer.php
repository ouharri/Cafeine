<?php
/**
 * Barista Coffee Shop Theme Customizer
 *
 * @link: https://developer.wordpress.org/themes/customize-api/customizer-objects/
 *
 * @package Barista Coffee Shop
 */

use WPTRT\Customize\Section\Barista_Coffee_Shop_Button;

add_action( 'customize_register', function( $manager ) {

    $manager->register_section_type( Barista_Coffee_Shop_Button::class );

    $manager->add_section(
        new Barista_Coffee_Shop_Button( $manager, 'barista_coffee_shop_pro', [
            'title'       => __( 'Coffee Shop Pro', 'barista-coffee-shop' ),
            'priority'    => 0,
            'button_text' => __( 'GET PREMIUM', 'barista-coffee-shop' ),
            'button_url'  => esc_url( 'https://www.themagnifico.net/themes/coffee-wordpress-theme/', 'barista-coffee-shop')
        ] )
    );

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

    $version = wp_get_theme()->get( 'Version' );

    wp_enqueue_script(
        'barista-coffee-shop-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
        [ 'customize-controls' ],
        $version,
        true
    );

    wp_enqueue_style(
        'barista-coffee-shop-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
        [ 'customize-controls' ],
        $version
    );

} );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function barista_coffee_shop_customize_register($wp_customize){
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

    $wp_customize->add_setting('barista_coffee_shop_logo_title', array(
        'default' => true,
        'sanitize_callback' => 'barista_coffee_shop_sanitize_checkbox'
    ));
    $wp_customize->add_control( new WP_Customize_Control($wp_customize,'barista_coffee_shop_logo_title',array(
        'label'          => __( 'Enable Disable Title', 'barista-coffee-shop' ),
        'section'        => 'title_tagline',
        'settings'       => 'barista_coffee_shop_logo_title',
        'type'           => 'checkbox',
    )));

    $wp_customize->add_setting('barista_coffee_shop_theme_description', array(
        'default' => false,
        'sanitize_callback' => 'barista_coffee_shop_sanitize_checkbox'
    ));
    $wp_customize->add_control( new WP_Customize_Control($wp_customize,'barista_coffee_shop_theme_description',array(
        'label'          => __( 'Enable Disable Tagline', 'barista-coffee-shop' ),
        'section'        => 'title_tagline',
        'settings'       => 'barista_coffee_shop_theme_description',
        'type'           => 'checkbox',
    )));

    // General Settings
     $wp_customize->add_section('barista_coffee_shop_general_settings',array(
        'title' => esc_html__('General Settings','barista-coffee-shop'),
        'description' => esc_html__('General settings of our theme.','barista-coffee-shop'),
        'priority'   => 30,
    ));

    $wp_customize->add_setting('barista_coffee_shop_preloader_hide', array(
        'default' => '0',
        'sanitize_callback' => 'barista_coffee_shop_sanitize_checkbox'
    ));
    $wp_customize->add_control( new WP_Customize_Control($wp_customize,'barista_coffee_shop_preloader_hide',array(
        'label'          => __( 'Show Theme Preloader', 'barista-coffee-shop' ),
        'section'        => 'barista_coffee_shop_general_settings',
        'settings'       => 'barista_coffee_shop_preloader_hide',
        'type'           => 'checkbox',
    )));

    $wp_customize->add_setting('barista_coffee_shop_sticky_header', array(
      'default' => false,
      'sanitize_callback' => 'barista_coffee_shop_sanitize_checkbox'
  ));
  $wp_customize->add_control( new WP_Customize_Control($wp_customize,'barista_coffee_shop_sticky_header',array(
      'label'          => __( 'Show Sticky Header', 'barista-coffee-shop' ),
      'section'        => 'barista_coffee_shop_general_settings',
      'settings'       => 'barista_coffee_shop_sticky_header',
      'type'           => 'checkbox',
  )));

  $wp_customize->add_setting('barista_coffee_shop_scroll_hide', array(
    'default' => false,
    'sanitize_callback' => 'barista_coffee_shop_sanitize_checkbox'
  ));
  $wp_customize->add_control( new WP_Customize_Control($wp_customize,'barista_coffee_shop_scroll_hide',array(
      'label'          => __( 'Show Scroll To Top', 'barista-coffee-shop' ),
      'section'        => 'barista_coffee_shop_general_settings',
      'settings'       => 'barista_coffee_shop_scroll_hide',
      'type'           => 'checkbox',
  )));

  // Product Columns
   $wp_customize->add_setting( 'barista_coffee_shop_products_per_row' , array(
       'default'           => '3',
       'transport'         => 'refresh',
       'sanitize_callback' => 'barista_coffee_shop_sanitize_select',
   ) );

   $wp_customize->add_control('barista_coffee_shop_products_per_row', array(
       'label' => __( 'Product per row', 'barista-coffee-shop' ),
       'section'  => 'barista_coffee_shop_general_settings',
       'type'     => 'select',
       'choices'  => array(
           '2' => '2',
           '3' => '3',
           '4' => '4',
       ),
   ) );

   $wp_customize->add_setting('barista_coffee_shop_product_per_page',array(
       'default'   => '9',
       'sanitize_callback' => 'barista_coffee_shop_sanitize_float'
   ));
   $wp_customize->add_control('barista_coffee_shop_product_per_page',array(
       'label' => __('Product per page','barista-coffee-shop'),
       'section'   => 'barista_coffee_shop_general_settings',
       'type'      => 'number'
   ));


    // Top Header
    $wp_customize->add_section('barista_coffee_shop_top_header',array(
        'title' => esc_html__('Top Header','barista-coffee-shop'),
    ));

    $wp_customize->add_setting('barista_coffee_shop_phone_text',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('barista_coffee_shop_phone_text',array(
        'label' => esc_html__('Add Text','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_top_header',
        'setting' => 'barista_coffee_shop_phone_text',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('barista_coffee_shop_phone',array(
        'default' => '',
        'sanitize_callback' => 'barista_coffee_shop_sanitize_phone_number'
    ));
    $wp_customize->add_control('barista_coffee_shop_phone',array(
        'label' => esc_html__('Add Phone Number','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_top_header',
        'setting' => 'barista_coffee_shop_phone',
        'type'  => 'text'
    ));

    // Social Link
    $wp_customize->add_section('barista_coffee_shop_social_link',array(
        'title' => esc_html__('Social Links','barista-coffee-shop'),
    ));

    $wp_customize->add_setting('barista_coffee_shop_facebook_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('barista_coffee_shop_facebook_url',array(
        'label' => esc_html__('Facebook Link','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_social_link',
        'setting' => 'barista_coffee_shop_facebook_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('barista_coffee_shop_twitter_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('barista_coffee_shop_twitter_url',array(
        'label' => esc_html__('Twitter Link','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_social_link',
        'setting' => 'barista_coffee_shop_twitter_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('barista_coffee_shop_intagram_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('barista_coffee_shop_intagram_url',array(
        'label' => esc_html__('Intagram Link','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_social_link',
        'setting' => 'barista_coffee_shop_intagram_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('barista_coffee_shop_linkedin_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('barista_coffee_shop_linkedin_url',array(
        'label' => esc_html__('Linkedin Link','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_social_link',
        'setting' => 'barista_coffee_shop_linkedin_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('barista_coffee_shop_youtube_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('barista_coffee_shop_youtube_url',array(
        'label' => esc_html__('YouTube Link','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_social_link',
        'setting' => 'barista_coffee_shop_pintrest_url',
        'type'  => 'url'
    ));

    //Slider
    $wp_customize->add_section('barista_coffee_shop_top_slider',array(
        'title' => esc_html__('Slider Option','barista-coffee-shop')
    ));

    for ( $barista_coffee_shop_count = 1; $barista_coffee_shop_count <= 3; $barista_coffee_shop_count++ ) {
        $wp_customize->add_setting( 'barista_coffee_shop_top_slider_page' . $barista_coffee_shop_count, array(
            'default'           => '',
            'sanitize_callback' => 'barista_coffee_shop_sanitize_dropdown_pages'
        ) );
        $wp_customize->add_control( 'barista_coffee_shop_top_slider_page' . $barista_coffee_shop_count, array(
            'label'    => __( 'Select Slide Page', 'barista-coffee-shop' ),
            'section'  => 'barista_coffee_shop_top_slider',
            'type'     => 'dropdown-pages'
        ) );
    }

    //Product
    $wp_customize->add_section('barista_coffee_shop_new_product',array(
        'title' => esc_html__('Featured Product','barista-coffee-shop'),
        'description' => esc_html__('Here you have to select product category which will display perticular new featured product in the home page.','barista-coffee-shop')
    ));

    $wp_customize->add_setting('barista_coffee_shop_new_product_title',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('barista_coffee_shop_new_product_title',array(
        'label' => esc_html__('Title','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_new_product',
        'setting' => 'barista_coffee_shop_new_product_title',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('barista_coffee_shop_new_product_text',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('barista_coffee_shop_new_product_text',array(
        'label' => esc_html__('Text','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_new_product',
        'setting' => 'barista_coffee_shop_new_product_text',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('barista_coffee_shop_new_product_number',array(
        'default' => '',
        'sanitize_callback' => 'absint'
    ));
    $wp_customize->add_control('barista_coffee_shop_new_product_number',array(
        'label' => esc_html__('No of Product','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_new_product',
        'setting' => 'barista_coffee_shop_new_product_number',
        'type'  => 'number'
    ));

    $barista_coffee_shop_args = array(
       'type'                     => 'product',
        'child_of'                 => 0,
        'parent'                   => '',
        'orderby'                  => 'term_group',
        'order'                    => 'ASC',
        'hide_empty'               => false,
        'hierarchical'             => 1,
        'number'                   => '',
        'taxonomy'                 => 'product_cat',
        'pad_counts'               => false
    );
    $categories = get_categories( $barista_coffee_shop_args );
    $cats = array();
    $i = 0;
    foreach($categories as $category){
        if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cats[$category->slug] = $category->name;
    }
    $wp_customize->add_setting('barista_coffee_shop_new_product_category',array(
        'sanitize_callback' => 'barista_coffee_shop_sanitize_select',
    ));
    $wp_customize->add_control('barista_coffee_shop_new_product_category',array(
        'type'    => 'select',
        'choices' => $cats,
        'label' => __('Select Product Category','barista-coffee-shop'),
        'section' => 'barista_coffee_shop_new_product',
    ));

    // Footer
    $wp_customize->add_section('barista_coffee_shop_site_footer_section', array(
        'title' => esc_html__('Footer', 'barista-coffee-shop'),
    ));

    $wp_customize->add_setting('barista_coffee_shop_footer_text_setting', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('barista_coffee_shop_footer_text_setting', array(
        'label' => __('Replace the footer text', 'barista-coffee-shop'),
        'section' => 'barista_coffee_shop_site_footer_section',
        'priority' => 1,
        'type' => 'text',
    ));
}
add_action('customize_register', 'barista_coffee_shop_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function barista_coffee_shop_customize_partial_blogname(){
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function barista_coffee_shop_customize_partial_blogdescription(){
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function barista_coffee_shop_customize_preview_js(){
    wp_enqueue_script('barista-coffee-shop-customizer', esc_url(get_template_directory_uri()) . '/assets/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'barista_coffee_shop_customize_preview_js');
