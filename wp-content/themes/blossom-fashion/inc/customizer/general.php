<?php
/**
 * General Settings
 *
 * @package Blossom_Fashion
 */

function blossom_fashion_customize_register_general( $wp_customize ) {
	
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 85,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'blossom-fashion' ),
            'description' => __( 'Customize Slider, Featured, Social, SEO, Post/Page, Newsletter & Instagram settings.', 'blossom-fashion' ),
        ) 
    );
    
    $wp_customize->get_section( 'header_image' )->panel    = 'general_settings';
    $wp_customize->get_section( 'header_image' )->title    = __( 'Banner Section', 'blossom-fashion' );
    $wp_customize->get_section( 'header_image' )->priority = 10;
    $wp_customize->get_control( 'header_image' )->active_callback = 'blossom_fashion_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback = 'blossom_fashion_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'blossom_fashion_banner_ac';
    $wp_customize->get_section( 'header_image' )->description = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> 'slider_banner',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'blossom-fashion' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-fashion' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'     => __( 'Disable Banner Section', 'blossom-fashion' ),
                    'static_banner' => __( 'Static/Video Banner', 'blossom-fashion' ),
                    'slider_banner' => __( 'Banner as Slider', 'blossom-fashion' ),
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
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'slider_type',
    		array(
                'label'	  => __( 'Slider Content Style', 'blossom-fashion' ),
    			'section' => 'header_image',
    			'choices' => array(
                    'latest_posts' => __( 'Latest Posts', 'blossom-fashion' ),
                    'cat'          => __( 'Category', 'blossom-fashion' )
                ),
                'active_callback' => 'blossom_fashion_banner_ac'	
     		)
		)
	);
    
    /** Slider Category */
    $wp_customize->add_setting(
		'slider_cat',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'slider_cat',
    		array(
                'label'	          => __( 'Slider Category', 'blossom-fashion' ),
    			'section'         => 'header_image',
    			'choices'         => blossom_fashion_get_categories(),
                'active_callback' => 'blossom_fashion_banner_ac'	
     		)
		)
	);
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 3,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Slider_Control( 
			$wp_customize,
			'no_of_slides',
			array(
				'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'blossom-fashion' ),
                'description' => __( 'Choose the number of slides you want to display', 'blossom-fashion' ),
                'choices'	  => array(
					'min' 	=> 1,
					'max' 	=> 20,
					'step'	=> 1,
				),
                'active_callback' => 'blossom_fashion_banner_ac'                 
			)
		)
	);
    
    /** Slider Animation */
    $wp_customize->add_setting(
		'slider_animation',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'slider_animation',
    		array(
                'label'	      => __( 'Slider Animation', 'blossom-fashion' ),
                'section'     => 'header_image',
    			'choices'     => array(
                    'fadeOut'        => __( 'Fade Out', 'blossom-fashion' ),
                    'fadeOutLeft'    => __( 'Fade Out Left', 'blossom-fashion' ),
                    'fadeOutRight'   => __( 'Fade Out Right', 'blossom-fashion' ),
                    'fadeOutUp'      => __( 'Fade Out Up', 'blossom-fashion' ),
                    'fadeOutDown'    => __( 'Fade Out Down', 'blossom-fashion' ),
                    ''               => __( 'Slide', 'blossom-fashion' ),
                    'slideOutLeft'   => __( 'Slide Out Left', 'blossom-fashion' ),
                    'slideOutRight'  => __( 'Slide Out Right', 'blossom-fashion' ),
                    'slideOutUp'     => __( 'Slide Out Up', 'blossom-fashion' ),
                    'slideOutDown'   => __( 'Slide Out Down', 'blossom-fashion' ),                    
                ),
                'active_callback' => 'blossom_fashion_banner_ac'                                	
     		)
		)
	);
    /** Slider Settings Ends */
    
    /** Featured Area Settings */
    $wp_customize->add_section(
        'featured_area_settings',
        array(
            'title'    => __( 'Featured Area Settings', 'blossom-fashion' ),
            'priority' => 20,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Featured Area */
    $wp_customize->add_setting( 
        'ed_featured_area', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_featured_area',
			array(
				'section'     => 'featured_area_settings',
				'label'	      => __( 'Enable Featured Area', 'blossom-fashion' ),
                'description' => __( 'Enable to show Featured Area in home page.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Featured Content One */
    $wp_customize->add_setting(
		'featured_content_one',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'featured_content_one',
    		array(
                'label'	  => __( 'Featured Content One', 'blossom-fashion' ),
    			'section' => 'featured_area_settings',
    			'choices' => blossom_fashion_get_posts( 'page' ),	
     		)
		)
	);
    
    /** Featured Content Two */
    $wp_customize->add_setting(
		'featured_content_two',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'featured_content_two',
    		array(
                'label'	  => __( 'Featured Content Two', 'blossom-fashion' ),
    			'section' => 'featured_area_settings',
    			'choices' => blossom_fashion_get_posts( 'page' ),	
     		)
		)
	);
    
    /** Featured Content Three */
    $wp_customize->add_setting(
		'featured_content_three',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'featured_content_three',
    		array(
                'label'	  => __( 'Featured Content Three', 'blossom-fashion' ),
    			'section' => 'featured_area_settings',
    			'choices' => blossom_fashion_get_posts( 'page' ),	
     		)
		)
	);
    /** Featured Area Settings Ends */
    
    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'blossom-fashion' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_social_links',
			array(
				'section'     => 'social_media_settings',
				'label'	      => __( 'Enable Social Links', 'blossom-fashion' ),
                'description' => __( 'Enable to show social links at header.', 'blossom-fashion' ),
			)
		)
	);
    
    $wp_customize->add_setting( 
        new Blossom_Fashion_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'Blossom_Fashion_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Control_Repeater(
			$wp_customize,
			'social_links',
			array(
				'section' => 'social_media_settings',				
				'label'	  => __( 'Social Links', 'blossom-fashion' ),
				'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'blossom-fashion' ),
                        'description' => __( 'Example: fa-bell', 'blossom-fashion' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'blossom-fashion' ),
                        'description' => __( 'Example: http://facebook.com', 'blossom-fashion' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'blossom-fashion' ),
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
            'title'    => __( 'SEO Settings', 'blossom-fashion' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_post_update_date',
			array(
				'section'     => 'seo_settings',
				'label'	      => __( 'Enable Last Update Post Date', 'blossom-fashion' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Enable Breadcrumb */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_breadcrumb',
			array(
				'section'     => 'seo_settings',
				'label'	      => __( 'Enable Breadcrumb', 'blossom-fashion' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'blossom-fashion' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'blossom-fashion' ),
        )
    );
        
    /** SEO Settings Ends */
    
    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'blossom-fashion' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'page_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'blossom_fashion_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Radio_Image_Control(
			$wp_customize,
			'page_sidebar_layout',
			array(
				'section'	  => 'post_page_settings',
				'label'		  => __( 'Page Sidebar Layout', 'blossom-fashion' ),
				'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in respective page.', 'blossom-fashion' ),
				'choices'	  => array(
					'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.png' ),
					'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.png' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.png' ),
				)
			)
		)
	);
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'post_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'blossom_fashion_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Radio_Image_Control(
			$wp_customize,
			'post_sidebar_layout',
			array(
				'section'	  => 'post_page_settings',
				'label'		  => __( 'Post Sidebar Layout', 'blossom-fashion' ),
				'description' => __( 'This is the general sidebar layout for posts. You can override the sidebar layout for individual post in respective post.', 'blossom-fashion' ),
				'choices'	  => array(
					'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.png' ),
					'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.png' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.png' ),
				)
			)
		)
	);
    
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_excerpt',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Enable Blog Excerpt', 'blossom-fashion' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 55,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Slider_Control( 
			$wp_customize,
			'excerpt_length',
			array(
				'section'	  => 'post_page_settings',
				'label'		  => __( 'Excerpt Length', 'blossom-fashion' ),
				'description' => __( 'Automatically generated excerpt length (in words).', 'blossom-fashion' ),
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 100,
					'step'	=> 5,
				)                 
			)
		)
	);
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'Continue Reading', 'blossom-fashion' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'blossom-fashion' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-readmore',
        'render_callback' => 'blossom_fashion_get_read_more',
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
        new Blossom_Fashion_Note_Control( 
			$wp_customize,
			'post_note_text',
			array(
				'section'	  => 'post_page_settings',
				'description' => __( '<hr/>These options affect your individual posts.', 'blossom-fashion' ),
			)
		)
    );
    
    /** Hide Author */
    $wp_customize->add_setting( 
        'ed_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_author',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Author', 'blossom-fashion' ),
                'description' => __( 'Enable to hide author section.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Author Section title */
    $wp_customize->add_setting(
        'author_title',
        array(
            'default'           => __( 'About Author', 'blossom-fashion' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'author_title',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Author Section Title', 'blossom-fashion' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'author_title', array(
        'selector' => '.author-section .title',
        'render_callback' => 'blossom_fashion_get_author_title',
    ) );
    
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_related',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Show Related Posts', 'blossom-fashion' ),
                'description' => __( 'Enable to show related posts in single page.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'You may also like...', 'blossom-fashion' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Related Posts Section Title', 'blossom-fashion' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.related-posts .title',
        'render_callback' => 'blossom_fashion_get_related_title',
    ) );
    
    /** Show Popular Posts */
    $wp_customize->add_setting( 
        'ed_popular', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_popular',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Show Popular Posts', 'blossom-fashion' ),
                'description' => __( 'Enable to show popular posts in single page.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Popular Posts section title */
    $wp_customize->add_setting(
        'popular_post_title',
        array(
            'default'           => __( 'Popular Posts', 'blossom-fashion' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'popular_post_title',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Popular Posts Section Title', 'blossom-fashion' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'popular_post_title', array(
        'selector' => '.popular-posts .title',
        'render_callback' => 'blossom_fashion_get_popular_title',
    ) );
    
    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Comments', 'blossom-fashion' ),
                'description' => __( 'Enable to hide Comments.', 'blossom-fashion' ),
            )
        )
    );  

    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_category',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Category', 'blossom-fashion' ),
                'description' => __( 'Enable to hide category.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_post_date',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Posted Date', 'blossom-fashion' ),
                'description' => __( 'Enable to hide posted date.', 'blossom-fashion' ),
			)
		)
	);
    
    /** Show Featured Image */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_featured_image',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Show Featured Image', 'blossom-fashion' ),
                'description' => __( 'Enable to show featured image in post detail (single page).', 'blossom-fashion' ),
			)
		)
	);
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Toggle_Control( 
			$wp_customize,
			'ed_prefix_archive',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Prefix in Archive Page', 'blossom-fashion' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'blossom-fashion' ),
			)
		)
	);
    /** Posts(Blog) & Pages Settings Ends */
    
    /** Newsletter Settings */
    $wp_customize->add_section(
        'newsletter_settings',
        array(
            'title'    => __( 'Newsletter Settings', 'blossom-fashion' ),
            'priority' => 60,
            'panel'    => 'general_settings',
        )
    );
    
    if( blossom_fashion_is_btnw_activated() ){
        /** Enable Newsletter Section */
        $wp_customize->add_setting( 
            'ed_newsletter', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Toggle_Control( 
    			$wp_customize,
    			'ed_newsletter',
    			array(
    				'section'     => 'newsletter_settings',
    				'label'	      => __( 'Newsletter Section', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Newsletter Section', 'blossom-fashion' ),
    			)
    		)
    	);
        
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'newsletter_shortcode',
            array(
                'type'        => 'text',
                'section'     => 'newsletter_settings',
                'label'       => __( 'Newsletter Shortcode', 'blossom-fashion' ),
                'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'blossom-fashion' ),
            )
        );
                
    }else{
        /** Note */
        $wp_customize->add_setting(
            'newsletter_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Fashion_Note_Control( 
    			$wp_customize,
    			'newsletter_text',
    			array(
    				'section'	  => 'newsletter_settings',
    				'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'blossom-fashion' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>' )
    			)
    		)
        );
    }
    
    /** Instagram Settings */
    $wp_customize->add_section(
        'instagram_settings',
        array(
            'title'    => __( 'Instagram Settings', 'blossom-fashion' ),
            'priority' => 70,
            'panel'    => 'general_settings',
        )
    );
    
    if( blossom_fashion_is_btif_activated() ){
        /** Enable Instagram Section */
        $wp_customize->add_setting( 
            'ed_instagram', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Toggle_Control( 
    			$wp_customize,
    			'ed_instagram',
    			array(
    				'section'     => 'instagram_settings',
    				'label'	      => __( 'Instagram Section', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Instagram Section', 'blossom-fashion' ),
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
            new Blossom_Fashion_Note_Control( 
    			$wp_customize,
    			'instagram_text',
    			array(
    				'section'	  => 'instagram_settings',
    				'description' => sprintf( __( 'You can change the setting of BlossomThemes Social Feed %1$sfrom here%2$s.', 'blossom-fashion' ), '<a href="' . esc_url( admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' ) ) . '" target="_blank">', '</a>' )
    			)
    		)
        );        
    }else{
        /** Note */
        $wp_customize->add_setting(
            'instagram_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Fashion_Note_Control( 
    			$wp_customize,
    			'instagram_text',
    			array(
    				'section'	  => 'instagram_settings',
    				'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Social Feed%2$s. After that option related with this section will be visible.', 'blossom-fashion' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>' )
    			)
    		)
        );
    }
    
    /** Shop Settings */
    $wp_customize->add_section(
        'shop_settings',
        array(
            'title'    => __( 'Shop Settings', 'blossom-fashion' ),
            'priority' => 80,
            'panel'    => 'general_settings',
        )
    );
    
    if( blossom_fashion_is_woocommerce_activated() ){
        /** Shop Section */
        $wp_customize->add_setting( 
            'ed_shopping_cart', 
            array(
                'default'           => true,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Toggle_Control( 
    			$wp_customize,
    			'ed_shopping_cart',
    			array(
    				'section'     => 'shop_settings',
    				'label'	      => __( 'Shopping Cart', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Shopping cart in the header.', 'blossom-fashion' ),
    			)
    		)
    	);

        /** Shop Page Description */
        $wp_customize->add_setting( 
            'shop_archive_description', 
            array(
                'default'           => true,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Fashion_Toggle_Control( 
                $wp_customize,
                'shop_archive_description',
                array(
                    'section'     => 'shop_settings',
                    'label'       => __( 'Shop Page Description', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Shop Page Description.', 'blossom-fashion' ),
                )
            )
        );
        
        /** Shop Section */
        $wp_customize->add_setting( 
            'ed_top_shop_section', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Toggle_Control( 
    			$wp_customize,
    			'ed_top_shop_section',
    			array(
    				'section'     => 'shop_settings',
    				'label'	      => __( 'Shop Section', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Shop Section below Featured Section. The latest products will be displayed on this section when enabled.', 'blossom-fashion' ),
    			)
    		)
    	);
        
        /** Shop Section Title */
        $wp_customize->add_setting(
            'shop_section_title',
            array(
                'default'           => __( 'Welcome to our Shop!', 'blossom-fashion' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'shop_section_title',
            array(
                'type'        => 'text',
                'section'     => 'shop_settings',
                'label'       => __( 'Shop Section Title', 'blossom-fashion' ),
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'shop_section_title', array(
            'selector' => '.shop-section .title',
            'render_callback' => 'blossom_fashion_get_shop_title',
        ) );
    
        /** Shop Section Content */
        $wp_customize->add_setting(
            'shop_section_content',
            array(
                'default'           => __( 'This option can be change from Customize > General Settings > Shop settings.', 'blossom-fashion' ),
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'shop_section_content',
            array(
                'type'        => 'text',
                'section'     => 'shop_settings',
                'label'       => __( 'Shop Section Content', 'blossom-fashion' ),
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'shop_section_content', array(
            'selector' => '.shop-section .content',
            'render_callback' => 'blossom_fashion_get_shop_content',
        ) );
        
        /** No. of Products */
        $wp_customize->add_setting(
            'no_of_products',
            array(
                'default'           => 8,
                'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
            )
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Slider_Control( 
    			$wp_customize,
    			'no_of_products',
    			array(
    				'section'     => 'shop_settings',
                    'label'       => __( 'Number of Products', 'blossom-fashion' ),
                    'description' => __( 'Choose the number of products you want to display', 'blossom-fashion' ),
                    'choices'	  => array(
    					'min' 	=> 4,
    					'max' 	=> 12,
    					'step'	=> 1,
    				)                 
    			)
    		)
    	);
    
        /** HR */
        $wp_customize->add_setting(
            'hr',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        
        $wp_customize->add_control(
            new Blossom_Fashion_Note_Control( 
    			$wp_customize,
    			'hr',
    			array(
    				'section'	  => 'shop_settings',
    				'description' => '<hr/>',
    			)
    		)
        );
                
        /** Shop Section */
        $wp_customize->add_setting( 
            'ed_bottom_shop_section', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_fashion_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new Blossom_Fashion_Toggle_Control( 
    			$wp_customize,
    			'ed_bottom_shop_section',
    			array(
    				'section'     => 'shop_settings',
    				'label'	      => __( 'Bottom Shop Section', 'blossom-fashion' ),
                    'description' => __( 'Enable to show Shop Section below Blog Posts.', 'blossom-fashion' ),
    			)
    		)
    	);
        
        /** Shop Section Title */
        $wp_customize->add_setting(
            'bottom_shop_section_title',
            array(
                'default'           => __( 'Shop My Closet', 'blossom-fashion' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'bottom_shop_section_title',
            array(
                'type'        => 'text',
                'section'     => 'shop_settings',
                'label'       => __( 'Bottom Shop Section Title', 'blossom-fashion' ),
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'bottom_shop_section_title', array(
            'selector' => '.bottom-shop-section .title',
            'render_callback' => 'blossom_fashion_get_bottom_shop_title',
        ) );
        
        /** Slider Category */
        $wp_customize->add_setting(
    		'product_cat',
    		array(
    			'default'			=> '',
    			'sanitize_callback' => 'blossom_fashion_sanitize_select'
    		)
    	);
    
    	$wp_customize->add_control(
    		new Blossom_Fashion_Select_Control(
        		$wp_customize,
        		'product_cat',
        		array(
                    'label'	  => __( 'Product Category', 'blossom-fashion' ),
        			'section' => 'shop_settings',
        			'choices' => blossom_fashion_get_categories( true, 'product_cat', true ),         		)
    		)
    	);       
                
    }else{
        /** Note */
        $wp_customize->add_setting(
            'shop_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Fashion_Note_Control( 
    			$wp_customize,
    			'shop_text',
    			array(
    				'section'	  => 'shop_settings',
    				'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sWoocommerce%2$s. After that option related with this section will be visible.', 'blossom-fashion' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>' )
    			)
    		)
        );
    }
    
}
add_action( 'customize_register', 'blossom_fashion_customize_register_general' );

/**
 * Active Callback
*/
function blossom_fashion_banner_ac( $control ){
    $banner      = $control->manager->get_setting( 'ed_banner_section' )->value();
    $slider_type = $control->manager->get_setting( 'slider_type' )->value();
    $control_id  = $control->id;
    
    if ( $control_id == 'header_image' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'header_video' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'external_header_video' && $banner == 'static_banner' ) return true;
    
    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;          
    if ( $control_id == 'slider_animation' && $banner == 'slider_banner' ) return true;
    
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && $slider_type == 'latest_posts' ) return true;
    
    return false;
}