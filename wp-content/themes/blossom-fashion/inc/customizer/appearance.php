<?php
/**
 * Appearance Settings
 *
 * @package Blossom_Fashion
 */

function blossom_fashion_customize_register_appearance( $wp_customize ) {
    
    /** Appearance Settings */
    $wp_customize->add_panel( 
        'appearance_settings',
         array(
            'priority'    => 50,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Appearance Settings', 'blossom-fashion' ),
            'description' => __( 'Customize Typography, Header Image & Background Image', 'blossom-fashion' ),
        ) 
    );
    
    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'blossom-fashion' ),
            'priority' => 10,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
		'primary_font',
		array(
			'default'			=> 'Montserrat',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'primary_font',
    		array(
                'label'	      => __( 'Primary Font', 'blossom-fashion' ),
                'description' => __( 'Primary font of the site.', 'blossom-fashion' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_fashion_get_all_fonts(),	
     		)
		)
	);
    
    /** Secondary Font */
    $wp_customize->add_setting(
		'secondary_font',
		array(
			'default'			=> 'Cormorant Garamond',
			'sanitize_callback' => 'blossom_fashion_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Fashion_Select_Control(
    		$wp_customize,
    		'secondary_font',
    		array(
                'label'	      => __( 'Secondary Font', 'blossom-fashion' ),
                'description' => __( 'Secondary font of the site.', 'blossom-fashion' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_fashion_get_all_fonts(),	
     		)
		)
	);
    
    /** Font Size*/
    $wp_customize->add_setting( 
        'font_size', 
        array(
            'default'           => 16,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Slider_Control( 
			$wp_customize,
			'font_size',
			array(
				'section'	  => 'typography_settings',
				'label'		  => __( 'Font Size', 'blossom-fashion' ),
				'description' => __( 'Change the font size of your site.', 'blossom-fashion' ),
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 50,
					'step'	=> 1,
				)                 
			)
		)
	);
    
    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Load Google Fonts Locally', 'blossom-fashion' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'blossom-fashion' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Preload Local Fonts', 'blossom-fashion' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'blossom-fashion' ),
                'active_callback' => 'blossom_fashion_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'blossom-fashion' ); ?></span>
        
        <input type="button" class="button button-primary blossom-fashion-flush-local-fonts-button" name="blossom-fashion-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'blossom-fashion' ); ?>" />
    <?php
    $blossom_fashion_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'blossom-fashion' ),
            'section'       => 'typography_settings',
            'description'   => $blossom_fashion_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_fashion_ed_localgoogle_fonts'
        )
    );

    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 30;
}
add_action( 'customize_register', 'blossom_fashion_customize_register_appearance' );

/**
 * Active Callback for local fonts
*/
function blossom_fashion_ed_localgoogle_fonts(){
    $ed_localgoogle_fonts = get_theme_mod( 'ed_localgoogle_fonts' , false );

    if( $ed_localgoogle_fonts ) return true;
    
    return false; 
}