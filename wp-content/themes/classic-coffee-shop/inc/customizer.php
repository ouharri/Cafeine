<?php
/**
 * Classic Coffee Shop Theme Customizer
 *
 * @package Classic Coffee Shop
 */

get_template_part('/inc/select/category-dropdown-custom-control');

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function classic_coffee_shop_customize_register( $wp_customize ) {

	function classic_coffee_shop_sanitize_dropdown_pages( $page_id, $setting ) {
  		$page_id = absint( $page_id );
  		return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	function classic_coffee_shop_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

    function classic_coffee_shop_sanitize_select( $input, $setting ){
        //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
        $input = sanitize_key($input);
        //get the list of possible select options
        $choices = $setting->manager->get_control( $setting->id )->choices;
        //return input if valid or return default option
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';





	$wp_customize->add_setting('classic_coffee_shop_title_enable',array(
		'default' => true,
		'sanitize_callback' => 'classic_coffee_shop_sanitize_checkbox',
	));
	$wp_customize->add_control( 'classic_coffee_shop_title_enable', array(
	   'settings' => 'classic_coffee_shop_title_enable',
	   'section'   => 'title_tagline',
	   'label'     => __('Enable Site Title','classic-coffee-shop'),
	   'type'      => 'checkbox'
	));

	// site title color
	$wp_customize->add_setting('classic_coffee_sitetitle_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_sitetitle_color', array(
	   'settings' => 'classic_coffee_sitetitle_color',
	   'section'   => 'title_tagline',
	   'label' => __('Site Title Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	$wp_customize->add_setting('classic_coffee_shop_tagline_enable',array(
		'default' => false,
		'sanitize_callback' => 'classic_coffee_shop_sanitize_checkbox',
	));
	$wp_customize->add_control( 'classic_coffee_shop_tagline_enable', array(
	   'settings' => 'classic_coffee_shop_tagline_enable',
	   'section'   => 'title_tagline',
	   'label'     => __('Enable Site Tagline','classic-coffee-shop'),
	   'type'      => 'checkbox'
	));


	// site tagline color
	$wp_customize->add_setting('classic_coffee_sitetagline_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_sitetagline_color', array(
	   'settings' => 'classic_coffee_sitetagline_color',
	   'section'   => 'title_tagline',
	   'label' => __('Site Tagline Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	//Theme Options
	$wp_customize->add_panel( 'classic_coffee_shop_panel_area', array(
		'priority' => 10,
		'capability' => 'edit_theme_options',
		'title' => __( 'Theme Options Panel', 'classic-coffee-shop' ),
	) );

	// Header Section
	$wp_customize->add_section('classic_coffee_shop_general_section', array(
        'title' => __('General Section', 'classic-coffee-shop'),
        'priority' => null,
		'panel' => 'classic_coffee_shop_panel_area',
 	));

	$wp_customize->add_setting('classic_coffee_shop_preloader',array(
		'default' => true,
		'sanitize_callback' => 'classic_coffee_shop_sanitize_checkbox',
	));

	$wp_customize->add_control( 'classic_coffee_shop_preloader', array(
	   'section'   => 'classic_coffee_shop_general_section',
	   'label'	=> __('Check to show preloader','classic-coffee-shop'),
	   'type'      => 'checkbox'
 	));

	// Header Section
	$wp_customize->add_section('classic_coffee_shop_links_section', array(
        'title' => __('Header Section', 'classic-coffee-shop'),
        'priority' => null,
		'panel' => 'classic_coffee_shop_panel_area',
 	));


	// header bg color
	$wp_customize->add_setting('classic_coffee_bg_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_bg_color', array(
	   'settings' => 'classic_coffee_bg_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('BG Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// header border color
	$wp_customize->add_setting('classic_coffee_headerborder_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headerborder_color', array(
	   'settings' => 'classic_coffee_headerborder_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Border Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// header menu color
	$wp_customize->add_setting('classic_coffee_headermenu_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headermenu_color', array(
	   'settings' => 'classic_coffee_headermenu_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Menu Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// header menuhover color
	$wp_customize->add_setting('classic_coffee_headermenuhover_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headermenuhover_color', array(
	   'settings' => 'classic_coffee_headermenuhover_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Menu Hover Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// header submenu color
	$wp_customize->add_setting('classic_coffee_headersubmenu_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headersubmenu_color', array(
	   'settings' => 'classic_coffee_headersubmenu_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('SubMenu Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// header submenuhover color
	$wp_customize->add_setting('classic_coffee_headersubmenuhover_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headersubmenuhover_color', array(
	   'settings' => 'classic_coffee_headersubmenuhover_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('SubMenu Hover Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// header submenbg color
	$wp_customize->add_setting('classic_coffee_headersubmenbg_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headersubmenbg_color', array(
	   'settings' => 'classic_coffee_headersubmenbg_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('SubMenu BG Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// icon hover color
	$wp_customize->add_setting('classic_coffee_headericonhvr_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_headericonhvr_color', array(
	   'settings' => 'classic_coffee_headericonhvr_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Icon Hover Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	$wp_customize->add_setting('classic_coffee_shop_fb_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_fb_link', array(
	   'settings' => 'classic_coffee_shop_fb_link',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Facebook Link', 'classic-coffee-shop'),
	   'type'      => 'url'
	));

	// fackbook icon color
	$wp_customize->add_setting('classic_coffee_fb_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_fb_color', array(
	   'settings' => 'classic_coffee_fb_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Facebook Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	$wp_customize->add_setting('classic_coffee_shop_twitt_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_twitt_link', array(
	   'settings' => 'classic_coffee_shop_twitt_link',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Twitter Link', 'classic-coffee-shop'),
	   'type'      => 'url'
	));

	// Twitter icon color
	$wp_customize->add_setting('classic_coffee_twitter_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_twitter_color', array(
	   'settings' => 'classic_coffee_twitter_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Twitter Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	$wp_customize->add_setting('classic_coffee_shop_linked_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_linked_link', array(
	   'settings' => 'classic_coffee_shop_linked_link',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Linkdin Link', 'classic-coffee-shop'),
	   'type'      => 'url'
	));

	// Linkdin icon color
	$wp_customize->add_setting('classic_coffee_linkdin_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_linkdin_color', array(
	   'settings' => 'classic_coffee_linkdin_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Linkdin Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	$wp_customize->add_setting('classic_coffee_shop_insta_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_insta_link', array(
	   'settings' => 'classic_coffee_shop_insta_link',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Instagram Link', 'classic-coffee-shop'),
	   'type'      => 'url'
	));

	// Instagram icon color
	$wp_customize->add_setting('classic_coffee_instagram_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_instagram_color', array(
	   'settings' => 'classic_coffee_instagram_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Instagram Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	$wp_customize->add_setting('classic_coffee_shop_youtube_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_youtube_link', array(
	   'settings' => 'classic_coffee_shop_youtube_link',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Youtube Link', 'classic-coffee-shop'),
	   'type'      => 'url'
	));


	// youtube icon color
	$wp_customize->add_setting('classic_coffee_youtube_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_youtube_color', array(
	   'settings' => 'classic_coffee_youtube_color',
	   'section'   => 'classic_coffee_shop_links_section',
	   'label' => __('Youtube Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// Home Category Dropdown Section
	$wp_customize->add_section('classic_coffee_shop_one_cols_section',array(
		'title'	=> __('Slider','classic-coffee-shop'),
		'description'	=> __('Select Category from the Dropdowns for slider, Also use the given image dimension (1200 x 450).','classic-coffee-shop'),
		'priority'	=> null,
		'panel' => 'classic_coffee_shop_panel_area'
	));

	$wp_customize->add_setting( 'classic_coffee_shop_slidersection', array(
		'default'	=> '0',
		'sanitize_callback'	=> 'absint'
	) );
	$wp_customize->add_control( new Classic_Coffee_Shop_Category_Dropdown_Custom_Control( $wp_customize, 'classic_coffee_shop_slidersection', array(
		'section' => 'classic_coffee_shop_one_cols_section',
		'label' => __('Select the post category to show slider', 'classic-coffee-shop'),
		'settings'   => 'classic_coffee_shop_slidersection',
	) ) );

	$wp_customize->add_setting('classic_coffee_shop_hide_categorysec',array(
		'default' => false,
		'sanitize_callback' => 'classic_coffee_shop_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_hide_categorysec', array(
	   'settings' => 'classic_coffee_shop_hide_categorysec',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label'     => __('Check To Enable This Section','classic-coffee-shop'),
	   'type'      => 'checkbox'
	));

	$wp_customize->add_setting('classic_coffee_shop_button_text',array(
		'default' => 'SHOP HERE',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_button_text', array(
	   'settings' => 'classic_coffee_shop_button_text',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Add Button Text', 'classic-coffee-shop'),
	   'type'      => 'text'
	));


	// slider Title color
	$wp_customize->add_setting('classic_coffee_slider_title_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_title_color', array(
	   'settings' => 'classic_coffee_slider_title_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Title Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// slider description color
	$wp_customize->add_setting('classic_coffee_slider_description_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_description_color', array(
	   'settings' => 'classic_coffee_slider_description_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Description Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// slider buttonborder color
	$wp_customize->add_setting('classic_coffee_slider_buttonborder_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_buttonborder_color', array(
	   'settings' => 'classic_coffee_slider_buttonborder_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Button Border Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// slider buttontext color
	$wp_customize->add_setting('classic_coffee_slider_buttontext_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_buttontext_color', array(
	   'settings' => 'classic_coffee_slider_buttontext_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Button Text Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// slider buttonhover color
	$wp_customize->add_setting('classic_coffee_slider_buttonhover_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_buttonhover_color', array(
	   'settings' => 'classic_coffee_slider_buttonhover_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Button Hover Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// slider buttontexthover color
	$wp_customize->add_setting('classic_coffee_slider_buttontexthover_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_buttontexthover_color', array(
	   'settings' => 'classic_coffee_slider_buttontexthover_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Button Text Hover Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// slider opacity color
	$wp_customize->add_setting('classic_coffee_slider_opacity_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_opacity_color', array(
	   'settings' => 'classic_coffee_slider_opacity_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Opacity Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// slider arrow color
	$wp_customize->add_setting('classic_coffee_slider_arrow_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_slider_arrow_color', array(
	   'settings' => 'classic_coffee_slider_arrow_color',
	   'section'   => 'classic_coffee_shop_one_cols_section',
	   'label' => __('Arrow Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));




	// Hot Products Category Section
	$wp_customize->add_section('classic_coffee_shop_two_cols_section',array(
		'title'	=> __('Products Category','classic-coffee-shop'),
		'priority'	=> null,
		'panel' => 'classic_coffee_shop_panel_area'
	));

	$wp_customize->add_setting('classic_coffee_shop_product_title',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_product_title', array(
	   'settings' => 'classic_coffee_shop_product_title',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Add Section Title', 'classic-coffee-shop'),
	   'type'      => 'text'
	));

	$wp_customize->add_setting('classic_coffee_shop_product_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( 'classic_coffee_shop_product_text', array(
	   'settings' => 'classic_coffee_shop_product_text',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Add Section Text', 'classic-coffee-shop'),
	   'type'      => 'text'
	));

	$args = array(
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
	$categories = get_categories($args);
	$cat_posts = array();
	$m = 0;
	$cat_posts[]='Select';
	foreach($categories as $category){
		if($m==0){
			$default = $category->slug;
			$m++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('classic_coffee_shop_hot_products_cat',array(
		'default'	=> 'select',
		'sanitize_callback' => 'classic_coffee_shop_sanitize_select',
	));
	$wp_customize->add_control('classic_coffee_shop_hot_products_cat',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select category to display products ','classic-coffee-shop'),
		'section' => 'classic_coffee_shop_two_cols_section',
	));

	// product heading color
	$wp_customize->add_setting('classic_coffee_product_heading_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_product_heading_color', array(
	   'settings' => 'classic_coffee_product_heading_color',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Heading Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// product subheading color
	$wp_customize->add_setting('classic_coffee_product_subheading_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_product_subheading_color', array(
	   'settings' => 'classic_coffee_product_subheading_color',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Sub Heading Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// product title color
	$wp_customize->add_setting('classic_coffee_product_title_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_product_title_color', array(
	   'settings' => 'classic_coffee_product_title_color',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Product Title Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// product border color
	$wp_customize->add_setting('classic_coffee_product_border_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_product_border_color', array(
	   'settings' => 'classic_coffee_product_border_color',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Product Border Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// product opacity color
	$wp_customize->add_setting('classic_coffee_product_opacity_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_product_opacity_color', array(
	   'settings' => 'classic_coffee_product_opacity_color',
	   'section'   => 'classic_coffee_shop_two_cols_section',
	   'label' => __('Product Opacity Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));



	// Footer Section
	$wp_customize->add_section('classic_coffee_shop_footer', array(
		'title'	=> __('Footer Section','classic-coffee-shop'),
		'priority'	=> null,
		'panel' => 'classic_coffee_shop_panel_area',
	));

	$wp_customize->add_setting('classic_coffee_shop_copyright_line',array(
		'default' => 'Coffee Shop WordPress Theme',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'classic_coffee_shop_copyright_line', array(
	   'section' 	=> 'classic_coffee_shop_footer',
	   'label'	 	=> __('Copyright Line','classic-coffee-shop'),
	   'type'    	=> 'text',
	   'priority' 	=> null,
    ));

    $wp_customize->add_setting('classic_coffee_shop_copyright_link',array(
		'default' => 'https://www.theclassictemplates.com/themes/free-coffee-shop-wordpress-theme/',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'classic_coffee_shop_copyright_link', array(
	   'section' 	=> 'classic_coffee_shop_footer',
	   'label'	 	=> __('Link','classic-coffee-shop'),
	   'type'    	=> 'text',
	   'priority' 	=> null,
    ));


	// footer bg color
	$wp_customize->add_setting('classic_coffee_footerbg_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footerbg_color', array(
	   'settings' => 'classic_coffee_footerbg_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Footer BG Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// footer copyright color
	$wp_customize->add_setting('classic_coffee_footercopyright_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footercopyright_color', array(
	   'settings' => 'classic_coffee_footercopyright_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Footer Copyright Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// footer fb color
	$wp_customize->add_setting('classic_coffee_footerfb_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footerfb_color', array(
	   'settings' => 'classic_coffee_footerfb_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Facebook Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// footer twitter color
	$wp_customize->add_setting('classic_coffee_footertwitter_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footertwitter_color', array(
	   'settings' => 'classic_coffee_footertwitter_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Twitter Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));


	// footer linkedin color
	$wp_customize->add_setting('classic_coffee_footerlinkedin_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footerlinkedin_color', array(
	   'settings' => 'classic_coffee_footerlinkedin_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Linkedin Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// footer insta color
	$wp_customize->add_setting('classic_coffee_footerinsta_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footerinsta_color', array(
	   'settings' => 'classic_coffee_footerinsta_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Instagram Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));

	// footer youtube color
	$wp_customize->add_setting('classic_coffee_footeryoutube_color',array(
		'default' => '',
		'sanitize_callback' => 'esc_html',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( 'classic_coffee_footeryoutube_color', array(
	   'settings' => 'classic_coffee_footeryoutube_color',
	   'section'   => 'classic_coffee_shop_footer',
	   'label' => __('Youtube Color', 'classic-coffee-shop'),
	   'type'      => 'color'
	));






	// Color
    $wp_customize->add_setting('classic_coffee_shop_color_scheme_one',array(
		'default' => '#37180e',
		'sanitize_callback' => 'sanitize_hex_color',
	));
    $wp_customize->add_control(
	    new WP_Customize_Color_Control(
	    $wp_customize,
	    'classic_coffee_shop_color_scheme_one',
	    array(
	        'label'      => __( 'Color Scheme', 'classic-coffee-shop' ),
	        'section'    => 'colors',
	        'settings'   => 'classic_coffee_shop_color_scheme_one',
	    ) )
	);

    // Google Fonts
    $wp_customize->add_section( 'classic_coffee_shop_google_fonts_section', array(
		'title'       => __( 'Google Fonts', 'classic-coffee-shop' ),
		'priority'       => 24,
	) );

	$font_choices = array(
		'Arvo:400,700,400italic,700italic' => 'Arvo',
		'Abril Fatface' => 'Abril Fatface',
		'Acme' => 'Acme',
		'Anton' => 'Anton',
		'Arimo:400,700,400italic,700italic' => 'Arimo',
		'Architects Daughter' => 'Architects Daughter',
		'Arsenal' => 'Arsenal',
		'Alegreya' => 'Alegreya',
		'Alfa Slab One' => 'Alfa Slab One',
		'Averia Serif Libre' => 'Averia Serif Libre',
		'Bitter:400,700,400italic' => 'Bitter',
		'Bangers' => 'Bangers',
		'Boogaloo' => 'Boogaloo',
		'Bad Script' => 'Bad Script',
		'Bree Serif' => 'Bree Serif',
		'BenchNine' => 'BenchNine',
		'Cabin:400,700,400italic' => 'Cabin',
		'Cardo' => 'Cardo',
		'Courgette' => 'Courgette',
		'Cherry Swash' => 'Cherry Swash',
		'Cormorant Garamond' => 'Cormorant Garamond',
		'Crimson Text' => 'Crimson Text',
		'Cuprum' => 'Cuprum',
		'Cookie' => 'Cookie',
		'Chewy' => 'Chewy',
		'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
		'Droid Sans:400,700' => 'Droid Sans',
		'Days One' => 'Days One',
		'Dosis' => 'Dosis',
		'Emilys Candy:' => 'Emilys Candy',
		'Economica' => 'Economica',
		'Fjalla One:400' => 'Fjalla One',
		'Francois One:400' => 'Francois One',
		'Fredoka One' => 'Fredoka One',
		'Frank Ruhl Libre' => 'Frank Ruhl Libre',
		'Gloria Hallelujah' => 'Gloria Hallelujah',
		'Great Vibes' => 'Great Vibes',
		'Josefin Sans:400,300,600,700' => 'Josefin Sans',
		'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
		'Lora:400,700,400italic,700italic' => 'Lora',
		'Lato:400,700,400italic,700italic' => 'Lato',
		'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
		'Montserrat:400,700' => 'Montserrat',
		'Oxygen:400,300,700' => 'Oxygen',
		'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
		'Open Sans:400italic,700italic,400,700' => 'Open Sans',
		'Oswald:400,700' => 'Oswald',
		'PT Serif:400,700' => 'PT Serif',
		'PT Sans:400,700,400italic,700italic' => 'PT Sans',
		'PT Sans Narrow:400,700' => 'PT Sans Narrow',
		'Playfair Display:400,700,400italic' => 'Playfair Display',
		'Poppins:0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900' => 'Poppins',
		'Roboto:400,400italic,700,700italic' => 'Roboto',
		'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
		'Roboto Slab:400,700' => 'Roboto Slab',
		'Rokkitt:400' => 'Rokkitt',
		'Raleway:400,700' => 'Raleway',
		'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
		'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
		'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
	);

	$wp_customize->add_setting( 'classic_coffee_shop_headings_fonts', array(
		'sanitize_callback' => 'classic_coffee_shop_sanitize_fonts',
	));
	$wp_customize->add_control( 'classic_coffee_shop_headings_fonts', array(
		'type' => 'select',
		'description' => __('Select your desired font for the headings.', 'classic-coffee-shop'),
		'section' => 'classic_coffee_shop_google_fonts_section',
		'choices' => $font_choices
	));

	$wp_customize->add_setting( 'classic_coffee_shop_body_fonts', array(
		'sanitize_callback' => 'classic_coffee_shop_sanitize_fonts'
	));
	$wp_customize->add_control( 'classic_coffee_shop_body_fonts', array(
		'type' => 'select',
		'description' => __( 'Select your desired font for the body.', 'classic-coffee-shop' ),
		'section' => 'classic_coffee_shop_google_fonts_section',
		'choices' => $font_choices
	));
}
add_action( 'customize_register', 'classic_coffee_shop_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function classic_coffee_shop_customize_preview_js() {
	wp_enqueue_script( 'classic_coffee_shop_customizer', esc_url(get_template_directory_uri()) . '/js/customize-preview.js', array( 'customize-preview' ), '20161510', true );
}
add_action( 'customize_preview_init', 'classic_coffee_shop_customize_preview_js' );
