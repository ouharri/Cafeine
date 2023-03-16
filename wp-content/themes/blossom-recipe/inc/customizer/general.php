<?php
/**
 * General Settings
 *
 * @package Blossom_Recipe
 */

function blossom_recipe_customize_register_general( $wp_customize ){
    
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'blossom-recipe' ),
            'description' => __( 'Customize Banner, Featured, Social, Sharing, SEO, Post/Page, Newsletter & Instagram, Shop, Performance and Miscellaneous settings.', 'blossom-recipe' ),
        ) 
    );
    
    $wp_customize->get_section( 'header_image' )->panel                    = 'general_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'blossom-recipe' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'blossom_recipe_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'blossom_recipe_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'blossom_recipe_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> 'slider_banner',
			'sanitize_callback' => 'blossom_recipe_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Recipe_Select_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'blossom-recipe' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-recipe' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'     => __( 'Disable Banner Section', 'blossom-recipe' ),
                    'static_banner' => __( 'Static/Video Banner', 'blossom-recipe' ),
                    'slider_banner' => __( 'Banner as Slider', 'blossom-recipe' ),
                ),
                'priority' => 5	
     		)            
		)
	);
    
    /** Slider Content Style */
    $wp_customize->add_setting(
		'slider_type',
		array(
			'default'			=> 'latest_posts',
			'sanitize_callback' => 'blossom_recipe_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Recipe_Select_Control(
    		$wp_customize,
    		'slider_type',
    		array(
                'label'	  => __( 'Slider Content Style', 'blossom-recipe' ),
    			'section' => 'header_image',
    			'choices' => blossom_recipe_slider_options(),
                'active_callback' => 'blossom_recipe_banner_ac'	
     		)
		)
	);
    
    /** Slider Category */
    $wp_customize->add_setting(
		'slider_cat',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_recipe_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Recipe_Select_Control(
    		$wp_customize,
    		'slider_cat',
    		array(
                'label'	          => __( 'Slider Category', 'blossom-recipe' ),
    			'section'         => 'header_image',
    			'choices'         => blossom_recipe_get_categories(),
                'active_callback' => 'blossom_recipe_banner_ac'	
     		)
		)
	);
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 4,
            'sanitize_callback' => 'blossom_recipe_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
		new Blossom_Recipe_Slider_Control( 
			$wp_customize,
			'no_of_slides',
			array(
				'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'blossom-recipe' ),
                'description' => __( 'Choose the number of slides you want to display', 'blossom-recipe' ),
                'choices'	  => array(
					'min' 	=> 1,
					'max' 	=> 20,
					'step'	=> 1,
				),
                'active_callback' => 'blossom_recipe_banner_ac'                 
			)
		)
	);
        
    /** HR */
    $wp_customize->add_setting(
        'banner_hr',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Note_Control( 
			$wp_customize,
			'banner_hr',
			array(
				'section'	  => 'header_image',
				'description' => '<hr/>',
                'active_callback' => 'blossom_recipe_banner_ac'
			)
		)
    );

    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Relaxing Is Never Easy On Your Own', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'blossom-recipe' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_recipe_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector' => '.site-banner .banner-caption .banner-title',
        'render_callback' => 'blossom_recipe_get_banner_title',
    ) );

    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Come and discover your oasis. It has never been easier to take a break from stress and the harmful factors that surround you every day!', 'blossom-recipe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Sub Title', 'blossom-recipe' ),
            'section'         => 'header_image',
            'type'            => 'textarea',
            'active_callback' => 'blossom_recipe_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector' => '.site-banner .banner-caption .banner-desc',
        'render_callback' => 'blossom_recipe_get_banner_sub_title',
    ) );

    /** Banner Button Label */
    $wp_customize->add_setting(
        'banner_button',
        array(
            'default'           => __( 'Read More', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_button',
        array(
            'label'           => __( 'Banner Button Label', 'blossom-recipe' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_recipe_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_button', array(
        'selector' => '.site-banner .banner-caption .btn',
        'render_callback' => 'blossom_recipe_get_banner_button',
    ) );

    /** Banner Link */
    $wp_customize->add_setting(
        'banner_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_url',
        array(
            'label'           => __( 'Banner Button Link', 'blossom-recipe' ),
            'section'         => 'header_image',
            'type'            => 'url',
            'active_callback' => 'blossom_recipe_banner_ac'
        )
    );

    /** Slider settings End */

    /** Header Settings Start */
    $wp_customize->add_section(
        'header_settings',
        array(
            'title'    => __( 'Header Settings', 'blossom-recipe' ),
            'priority' => 25,
            'panel'    => 'general_settings',
        )
    );

    /** Header Search */
    $wp_customize->add_setting(
        'ed_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_header_search',
            array(
                'section'       => 'header_settings',
                'label'         => __( 'Header Search', 'blossom-recipe' ),
                'description'   => __( 'Enable to display search form in header.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Enable Newsletter Section */
    $wp_customize->add_setting( 
        'ed_header_newsletter', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_header_newsletter',
            array(
                'section'     => 'header_settings',
                'label'       => __( 'Header Newsletter Section', 'blossom-recipe' ),
                'description' => __( 'Enable to show Newsletter Section', 'blossom-recipe' ),
            )
        )
    );

    /** Newsletter Shortcode */
    $wp_customize->add_setting(
        'header_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'header_newsletter_shortcode',
        array(
            'type'        => 'text',
            'section'     => 'header_settings',
            'label'       => __( 'Newsletter Shortcode', 'blossom-recipe' ),
            'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'blossom-recipe' ),
            'active_callback' => 'blossom_recipe_header_newsletter_callback',
        )
    );
    /** Header Settings Ends */

    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'blossom-recipe' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_social_links',
            array(
                'section'     => 'social_media_settings',
                'label'       => __( 'Enable Social Links', 'blossom-recipe' ),
                'description' => __( 'Enable to show social links at header.', 'blossom-recipe' ),
            )
        )
    );
    
    $wp_customize->add_setting( 
        new Blossom_Recipe_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'Blossom_Recipe_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Control_Repeater(
            $wp_customize,
            'social_links',
            array(
                'section' => 'social_media_settings',               
                'label'   => __( 'Social Links', 'blossom-recipe' ),
                'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'blossom-recipe' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'blossom-recipe' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'blossom-recipe' ),
                        'description' => __( 'Example: https://facebook.com', 'blossom-recipe' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'blossom-recipe' ),
                    'field' => 'link'
                ),
                'choices'   => array(
                    'limit' => 10
                )                        
            )
        )
    );
    /** Social Media Settings Ends */

    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'blossom-recipe' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_post_update_date',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Last Update Post Date', 'blossom-recipe' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'blossom-recipe' ),
            )
        )
    );

    /** Enable Breadcrumbs */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_breadcrumb',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Breadcrumb', 'blossom-recipe' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'blossom-recipe' ),
            'active_callback' => 'blossom_recipe_breadcrumbs_callback'
        )
    );
    /** SEO Settings Ends */

    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'blossom-recipe' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_prefix_archive',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Prefix in Archive Page', 'blossom-recipe' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'blossom-recipe' ),
            )
        )
    );
        
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_excerpt',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Enable Blog Excerpt', 'blossom-recipe' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 55,
            'sanitize_callback' => 'blossom_recipe_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Slider_Control( 
            $wp_customize,
            'excerpt_length',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Excerpt Length', 'blossom-recipe' ),
                'description' => __( 'Automatically generated excerpt length (in words).', 'blossom-recipe' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 100,
                    'step'  => 5,
                )                 
            )
        )
    );
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'Read More', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'blossom-recipe' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-link',
        'render_callback' => 'blossom_recipe_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Note_Control( 
            $wp_customize,
            'post_note_text',
            array(
                'section'     => 'post_page_settings',
                'description' => sprintf( __( '%s These options affect your individual posts.', 'blossom-recipe' ), '<hr/>' ),
            )
        )
    );
    
    /** Author Section title */
    $wp_customize->add_setting(
        'author_title',
        array(
            'default'           => __( 'About Author', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'author_title',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Author Section Title', 'blossom-recipe' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'author_title', array(
        'selector' => '.author-profile .author-name .author-title',
        'render_callback' => 'blossom_recipe_get_author_title',
    ) );

    if( blossom_recipe_is_btnw_activated() ){
        
        /** Enable Newsletter Section */
        $wp_customize->add_setting( 
            'ed_single_newsletter', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Recipe_Toggle_Control( 
                $wp_customize,
                'ed_single_newsletter',
                array(
                    'section'     => 'post_page_settings',
                    'label'       => __( 'Single Newsletter Section', 'blossom-recipe' ),
                    'description' => __( 'Enable to show Newsletter Section', 'blossom-recipe' ),
                )
            )
        );
    
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'single_newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'single_newsletter_shortcode',
            array(
                'type'        => 'text',
                'section'     => 'post_page_settings',
                'label'       => __( 'Newsletter Shortcode', 'blossom-recipe' ),
                'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'blossom-recipe' ),
            )
        ); 
    } else {
        $wp_customize->add_setting(
            'single_newsletter_recommend',
            array(
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        $wp_customize->add_control(
            new blossom_recipe_Plugin_Recommend_Control(
                $wp_customize,
                'single_newsletter_recommend',
                array(
                    'section'     => 'post_page_settings',
                    'label'       => __( 'Newsletter Shortcode', 'blossom-recipe' ),
                    'capability'  => 'install_plugins',
                    'plugin_slug' => 'blossomthemes-email-newsletter',//This is the slug of recommended plugin.
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'blossom-recipe' ), '<strong>', '</strong>' ),
                )
            )
        );
    }
    
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Related Posts', 'blossom-recipe' ),
                'description' => __( 'Enable to show related posts in single page.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'You may also like...', 'blossom-recipe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Posts Section Title', 'blossom-recipe' ),
            'active_callback' => 'blossom_recipe_post_page_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.related-articles .related-title',
        'render_callback' => 'blossom_recipe_get_related_title',
    ) );
    
    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Comments', 'blossom-recipe' ),
                'description' => __( 'Enable to show Comments in Single Post/Page.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_category',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Category', 'blossom-recipe' ),
                'description' => __( 'Enable to hide category.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Hide Post Author */
    $wp_customize->add_setting( 
        'ed_post_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_post_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Post Author', 'blossom-recipe' ),
                'description' => __( 'Enable to hide post author.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_post_date',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Posted Date', 'blossom-recipe' ),
                'description' => __( 'Enable to hide posted date.', 'blossom-recipe' ),
            )
        )
    );
    
    /** Show Featured Image */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_featured_image',
            array(
                'section'         => 'post_page_settings',
                'label'           => __( 'Show Featured Image', 'blossom-recipe' ),
                'description'     => __( 'Enable to show featured image in post detail (single post).', 'blossom-recipe' ),
                'active_callback' => 'blossom_recipe_post_page_ac'
            )
        )
    );
    /** Posts(Blog) & Pages Settings Ends */

    /** Instagram Settings */
    $wp_customize->add_section(
        'instagram_settings',
        array(
            'title'    => __( 'Instagram Settings', 'blossom-recipe' ),
            'priority' => 70,
            'panel'    => 'general_settings',
        )
    );
    
    if( blossom_recipe_is_btif_activated() ){
        /** Enable Instagram Section */
        $wp_customize->add_setting( 
            'ed_instagram', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Recipe_Toggle_Control( 
                $wp_customize,
                'ed_instagram',
                array(
                    'section'     => 'instagram_settings',
                    'label'       => __( 'Instagram Section', 'blossom-recipe' ),
                    'description' => __( 'Enable to show Instagram Section', 'blossom-recipe' ),
                )
            )
        );
        
        /** Note */
        $wp_customize->add_setting(
            'instagram_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Recipe_Note_Control( 
                $wp_customize,
                'instagram_text',
                array(
                    'section'     => 'instagram_settings',
                    'description' => sprintf( __( 'You can change the setting of BlossomThemes Social Feed %1$sfrom here%2$s.', 'blossom-recipe' ), '<a href="' . esc_url( admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' ) ) . '" target="_blank">', '</a>' )
                )
            )
        );        
    }else{
        $wp_customize->add_setting(
            'instagram_recommend',
            array(
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        $wp_customize->add_control(
            new blossom_recipe_Plugin_Recommend_Control(
                $wp_customize,
                'instagram_recommend',
                array(
                    'section'     => 'instagram_settings',
                    'capability'  => 'install_plugins',
                    'plugin_slug' => 'blossomthemes-instagram-feed',//This is the slug of recommended plugin.
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Social Feed%2$s. After that option related with this section will be visible.', 'blossom-recipe' ), '<strong>', '</strong>' ),
                )
            )
        );
    }
    /** Instagram Settings Ends */

    /** Shop Settings */
    $wp_customize->add_section(
        'shop_settings',
        array(
            'title'    => __( 'Shop Settings', 'blossom-recipe' ),
            'priority' => 75,
            'panel'    => 'general_settings',
            'active_callback' => 'blossom_recipe_is_woocommerce_activated'
        )
    );
    
    /** Shop Section */
    $wp_customize->add_setting( 
        'ed_shopping_cart', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_shopping_cart',
            array(
                'section'     => 'shop_settings',
                'label'       => __( 'Shopping Cart', 'blossom-recipe' ),
                'description' => __( 'Enable to show Shopping cart in the header.', 'blossom-recipe' ),
            )
        )
    );        
    
    /** Shop Page Description */
    $wp_customize->add_setting( 
        'ed_shop_archive_description', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_shop_archive_description',
            array(
                'section'         => 'shop_settings',
                'label'           => __( 'Shop Page Description', 'blossom-recipe' ),
                'description'     => __( 'Enable to show Shop Page Description.', 'blossom-recipe' ),
            )
        )
    );

    /** Shop Settings Ends */

    /** Misc Settings Ends */
    /** Shop Settings */
    $wp_customize->add_section(
        'misc_settings',
        array(
            'title'    => __( 'Misc Settings', 'blossom-recipe' ),
            'priority' => 80,
            'panel'    => 'general_settings',
        )
    );

    /** Locally Host Google Fonts */
    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'misc_settings',
                'label'         => __( 'Load Google Fonts Locally', 'blossom-recipe' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'blossom-recipe' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_recipe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Recipe_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'misc_settings',
                'label'         => __( 'Preload Local Fonts', 'blossom-recipe' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'blossom-recipe' ),
                'active_callback' => 'blossom_recipe_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'blossom-recipe' ); ?></span>
        
        <input type="button" class="button button-primary blossom-recipe-flush-local-fonts-button" name="blossom-recipe-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'blossom-recipe' ); ?>" />
    <?php
    $blossom_recipe_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'blossom-recipe' ),
            'section'       => 'misc_settings',
            'description'   => $blossom_recipe_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_recipe_ed_localgoogle_fonts'
        )
    );
    /** Shop Settings Ends */
    
}
add_action( 'customize_register', 'blossom_recipe_customize_register_general' );

if ( ! function_exists( 'blossom_recipe_slider_options' ) ) :
    /**
     * @return array Content type options
     */
    function blossom_recipe_slider_options() {
        $slider_options = array(
            'latest_posts' => __( 'Latest Posts', 'blossom-recipe' ),
            'cat'          => __( 'Category', 'blossom-recipe' ),
        );
        if ( blossom_recipe_is_brm_activated() ) {
            $slider_options = array_merge( $slider_options, array( 'latest_recipes' => __( 'Latest BRM Recipes','blossom-recipe' ) ) );
        }
        if ( blossom_recipe_is_delicious_recipe_activated() ) {
            $slider_options = array_merge( $slider_options, array( 'latest_dr_recipe' => __( 'Latest Delicious Recipes', 'blossom-recipe' ) ) );
        }
        $output = apply_filters( 'blossom_recipe_slider_options', $slider_options );
        return $output;
    }
endif;